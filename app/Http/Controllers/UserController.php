<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pasien;
use App\Models\Ruangan;
use App\Models\Kelas;
use App\Models\Dokter;
use App\Models\Pemeriksa;
use PhpOffice\PhpWord\TemplateProcessor;
use App\Models\PemeriksaanKimia;
use App\Models\PemeriksaanHematology;
use Carbon\Carbon;
use App\Jobs\GeneratePdfJob;
use Illuminate\Support\Facades\DB;
use App\Services\LogActivityService;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Writer\PngWriter;

class UserController extends Controller
{
    public function index(Request $request)
    {
        Carbon::setLocale('id');
        $today     = Carbon::today();
        $todayDmy  = $today->format('ymd');

        $query = Pasien::query();

        // =========================
        // FILTER TANGGAL
        // =========================
        $date = $request->filled('search_date')
            ? Carbon::parse($request->search_date)
            : Carbon::today();

        $dmyDate  = $date->format('ymd');
        $dateOnly = $date->toDateString();

        // Filter berdasarkan tanggal
        $query->where(function ($q) use ($dmyDate, $dateOnly) {
            $q->where('nomor_registrasi', 'like', $dmyDate . '%')
            ->orWhere(function ($qq) use ($dateOnly) {
                $qq->where(function ($x) {
                        $x->whereNull('nomor_registrasi')
                            ->orWhere('nomor_registrasi', '');
                    })
                    ->whereDate('updated_at', $dateOnly);
            });
        });

        // =========================
        // FILTER KOLOM (SERVER-SIDE)
        // =========================
        $this->applyColumnFilters($query, $request);

        $pasiens = $query
            ->orderByDesc('updated_at')
            ->orderByDesc('nomor_registrasi')
            ->paginate(50)
            ->withQueryString();

        // =========================
        // STATISTIK
        // =========================
        $stat = Pasien::where(function ($q) use ($dmyDate, $dateOnly) {
            $q->where('nomor_registrasi', 'like', $dmyDate . '%')
            ->orWhere(function ($qq) use ($dateOnly) {
                $qq->where(function ($x) {
                        $x->whereNull('nomor_registrasi')
                            ->orWhere('nomor_registrasi', '');
                    })
                    ->whereDate('updated_at', $dateOnly);
            });
        });

        $this->applyColumnFilters($stat, $request);

        $statusOrders  = (clone $stat)->count();
        $statusSelesai = (clone $stat)->whereNotNull('id_pemeriksa')->count();
        $statusProses  = (clone $stat)->whereNull('id_pemeriksa')->count();

        LogActivityService::log(
            action: 'READ',
            module: 'Pasien',
            description: 'User mengakses pasien dengan filter'
        );

        return view('user.index', compact(
            'pasiens',
            'statusOrders',
            'statusSelesai',
            'statusProses',
            'date'
        ))->with(['activeDate' => $date]);
    }

    public function search(Request $request)
    {
        // Method ini bisa digabung dengan index() atau diarahkan ke index()
        return $this->index($request);
    }

    private function applyColumnFilters($query, Request $request)
    {
        // Filter Tanggal (kolom 0)
        if ($request->filled('filter_tanggal')) {
            $filter = $request->filter_tanggal;
            $query->where(function ($q) use ($filter) {
                $q->whereRaw("TO_CHAR(created_at, 'DD/MM/YYYY') LIKE ?", ["%{$filter}%"])
                ->orWhereRaw("TO_CHAR(updated_at, 'DD/MM/YYYY') LIKE ?", ["%{$filter}%"]);
            });
        }

        // Filter No. Reg Lab (kolom 1)
        if ($request->filled('filter_registrasi')) {
            $query->where('nomor_registrasi', 'ilike', "%{$request->filter_registrasi}%");
        }

        // Filter RM Pasien (kolom 2)
        if ($request->filled('filter_rm')) {
            $query->where('rm_pasien', 'ilike', "%{$request->filter_rm}%");
        }

        // Filter Nama Pasien (kolom 3)
        if ($request->filled('filter_nama')) {
            $query->where('nama_pasien', 'ilike', "%{$request->filter_nama}%");
        }

        // Filter Asal Kunjungan (kolom 4)
        if ($request->filled('filter_asal')) {
            $query->where('ket_klinik', 'ilike', "%{$request->filter_asal}%");
        }

        // Filter Penjamin (kolom 5)
        if ($request->filled('filter_penjamin')) {
            $query->where('nota', 'ilike', "%{$request->filter_penjamin}%");
        }

        // Filter Status (kolom 6)
        if ($request->filled('filter_status')) {
            if ($request->filter_status === 'Selesai') {
                $query->whereNotNull('id_pemeriksa')->whereNotNull('waktu_validasi');
            } elseif ($request->filter_status === 'Diproses') {
                $query->whereNull('id_pemeriksa')->orWhereNull('waktu_validasi');
            }
        }

        // Filter Global Search
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('rm_pasien', 'ilike', "%{$search}%")
                ->orWhere('nama_pasien', 'ilike', "%{$search}%")
                ->orWhere('nomor_registrasi', 'ilike', "%{$search}%")
                ->orWhere('nota', 'ilike', "%{$search}%")
                ->orWhere('ket_klinik', 'ilike', "%{$search}%");
            });
        }
    }

    public function show($no_lab)
    {
        // Ambil pasien dengan relasi yang diperlukan
        $pasien = Pasien::with([
            'hematology.dataPemeriksaan.lisMappings',
            'kimia.dataPemeriksaan',
            'hasilPemeriksaanLain.dataPemeriksaan.lisMappings',
            'pemeriksa',
            'ujiPemeriksaan',
        ])->findOrFail($no_lab);

        $data = $this->processLabData($pasien);

        /* =========================
        * HEMATOLOGY (TETAP)
        * ========================= */
        $urutan = [
            'WBC','NEU%','LYM%','MON%','EOS%','BAS%',
            'RBC','HGB','HCT','MCV','MCH','MCHC',
            'RDW-CV','RDW-SD','PLT','MPV','PDW','PCT'
        ];

        $hasil = $pasien->hematology()
            ->with('dataPemeriksaan.lisMappings')
            ->get();

        $lisIndex = [];
        foreach ($hasil as $item) {
            if ($item->dataPemeriksaan && $item->dataPemeriksaan->lisMappings) {
                foreach ($item->dataPemeriksaan->lisMappings as $mapping) {
                    $lisLower = strtolower(trim($mapping->lis));
                    if (!isset($lisIndex[$lisLower])) {
                        $lisIndex[$lisLower] = $item;
                    }
                }
            }
        }

        $hematology_fix = [];
        foreach ($urutan as $lis) {
            $lisLower = strtolower(trim($lis));

            if (isset($lisIndex[$lisLower])) {
                $hematology_fix[] = $lisIndex[$lisLower];
                continue;
            }

            $found = null;
            foreach ($lisIndex as $indexLis => $item) {
                if (strpos($indexLis, $lisLower) !== false) {
                    $found = $item;
                    break;
                }
            }

            $hematology_fix[] = $found;
        }

        /* =========================
        * KIMIA (ORDER BY dp.urutan)
        * ========================= */
        $kimia = $pasien->kimia()
            ->leftJoin('data_pemeriksaan as dp', 'pemeriksaan_kimia.id_data_pemeriksaan', '=', 'dp.id_data_pemeriksaan')
            ->with('dataPemeriksaan.jenisPemeriksaan')
            ->orderBy('dp.urutan', 'asc') // 🔥 FIX
            ->select('pemeriksaan_kimia.*')
            ->get();

        /* =========================
        * PEMERIKSAAN LAIN (ORDER BY dp.urutan)
        * ========================= */
        $hasil_lain = DB::table('hasil_pemeriksaan_lain as hpl')
        ->leftJoin('data_pemeriksaan as dp', 'hpl.id_data_pemeriksaan', '=', 'dp.id_data_pemeriksaan')
        ->leftJoin('jenis_pemeriksaan_1 as jp1', 'dp.id_jenis_pemeriksaan_1', '=', 'jp1.id_jenis_pemeriksaan_1')
        ->where('hpl.no_lab', $no_lab)
        ->whereNull('hpl.deleted_at')
        ->select(
            'hpl.*',
            'dp.data_pemeriksaan',
            'dp.satuan as satuan_pemeriksaan',
            'dp.rujukan as rujukan_pemeriksaan',
            'dp.metode',
            'dp.ch',
            'dp.cl',
            'dp.urutan as urutan_pemeriksaan', // TAMBAHKAN
            'jp1.nama_pemeriksaan as jenis_pemeriksaan_nama',
            'jp1.id_jenis_pemeriksaan_1 as jenis_pemeriksaan_id',
            DB::raw("COALESCE(jp1.nama_pemeriksaan, 'Lainnya') as kelompok_pemeriksaan") // GUNAKAN COALESCE
        )
        ->orderBy('dp.urutan', 'asc') // KEMUDIAN URUTKAN PADA DATA PEMERIKSAAN
        ->get();

        /* =========================
        * GROUPING DENGAN URUTAN YANG TEPAT
        * ========================= */
        $hasil_lain_grouped = [];
        $jenis_pemeriksaan_order = []; // Untuk menyimpan urutan jenis pemeriksaan

        // Pertama, urutkan berdasarkan created_at
        $hasil_lain = $hasil_lain->sortBy('created_at');

        foreach ($hasil_lain as $item) {
            // Gunakan COALESCE untuk menangani null
            $jenis_pemeriksaan = $item->kelompok_pemeriksaan;

            if (!isset($hasil_lain_grouped[$jenis_pemeriksaan])) {
                $hasil_lain_grouped[$jenis_pemeriksaan] = [];
                // Simpan waktu pertama kali jenis pemeriksaan ini muncul
                if (!isset($jenis_pemeriksaan_order[$jenis_pemeriksaan])) {
                    $jenis_pemeriksaan_order[$jenis_pemeriksaan] = $item->created_at;
                }
            }

            $hasil_lain_grouped[$jenis_pemeriksaan][] = $item;
        }

        // Urutkan kelompok berdasarkan waktu pertama kali muncul (created_at tertua)
        uksort($hasil_lain_grouped, function($a, $b) use ($jenis_pemeriksaan_order) {
            $timeA = $jenis_pemeriksaan_order[$a] ?? Carbon::now();
            $timeB = $jenis_pemeriksaan_order[$b] ?? Carbon::now();
            return $timeA <=> $timeB;
        });

        // Urutkan item dalam setiap kelompok
        foreach ($hasil_lain_grouped as &$items) {
            usort($items, function($a, $b) {
                // Urutkan berdasarkan urutan dari data_pemeriksaan
                $urutan_a = $a->urutan_pemeriksaan ?? 9999;
                $urutan_b = $b->urutan_pemeriksaan ?? 9999;

                if ($urutan_a != $urutan_b) {
                    return $urutan_a <=> $urutan_b;
                }

                // Jika urutan sama, urutkan berdasarkan data_pemeriksaan
                return strcmp($a->data_pemeriksaan ?? '', $b->data_pemeriksaan ?? '');
            });
        }

        /* =========================
        * JENIS PEMERIKSAAN LIST
        * ========================= */
        $jenis_pemeriksaan_1_list = DB::table('jenis_pemeriksaan_1')
            ->whereNull('deleted_at')
            ->orderBy('nama_pemeriksaan')
            ->get();

        LogActivityService::log(
            action: 'READ',
            module: 'Pasien',
            description: 'Melihat hasil detail pasien ID: ' . $no_lab
        );

        return view('user.detail', [
            'pasien' => $pasien,
            'kimia' => $kimia,
            'hematology_fix' => $hematology_fix,
            'hasil_lain' => $hasil_lain,
            'hasil_lain_grouped' => $hasil_lain_grouped,
            'jenis_pemeriksaan_1_list' => $jenis_pemeriksaan_1_list,
            'data' => $data['umur_format'],
        ]);
    }

    private function processLabData($pasien)
    {

        LogActivityService::log(
            action: 'PROCESS',
            module: 'Hasil Laboratorium',
            description: 'Proses data lab No LAB: ' . ($pasien->no_lab ?? '-')
        );
        // Data dasar pasien
        $no_lab = $pasien->no_lab ?? '-';
        $nama = $pasien->nama_pasien ?? '-';
        $jenis_kelamin = $pasien->jenis_kelamin ?? '-';
        $no_rm = $pasien->rm_pasien ?? '-';
        $alamat = $pasien->alamat ?? '-';
        $tanggal_pemeriksaan = $pasien->tanggal_pemeriksaan ?? '-';
        $dokter_nama = $pasien->pengirim ?? '-';
        $tgl_lahir = $pasien->tgl_lahir ?? '-';
        $ruang = $pasien->ket_klinik ?? '-';
        $kelas = $pasien->id_kelas ?? '-';
        $catatan = $pasien->catatan ?? '-';
        $keluhan = $pasien->keluhan ?? '-';
        $penjamin = $pasien->nota ?? '-';
        $ket_klinik = $pasien->ket_klinik ?? '-';
        $tgl_ambil_sample = $pasien->tgl_ambil_sample ?? '-';
        $created_at = $pasien->created_at ?? '-';
        $print_tanggal = now()->timezone('Asia/Jakarta');
        $updated_at = $pasien->updated_at ?? '-';
        $waktu_validasi = $pasien->waktu_validasi ?? '-';

        // Ambil komponen tanggal dan waktu secara terpisah
        $print_tahun = $print_tanggal->year;
        $print_bulan = $print_tanggal->month;
        $print_hari = $print_tanggal->day;
        $print_jam = $print_tanggal->hour;
        $print_menit = $print_tanggal->minute;
        $print_detik = $print_tanggal->second;

        // Format tanggal lahir
        $susun_tgl_lahir = $this->formatTanggalLahir($tgl_lahir);

        // Hitung umur
        $umur = $this->hitungUmur($tgl_lahir, $pasien->umur);
        $umur_format = $umur['tahun'] . ' Tahun ' . $umur['bulan'] . ' Bulan ' . $umur['hari'] . ' Hari';

        // Format tanggal cetak
        $print_tanggal = now();
        $tanggal_cetak = $this->formatTanggalCetak($print_tanggal);
        $tanggal_cetak_format = $tanggal_cetak['format_tanggal'];
        $pecah_tanggal = $tanggal_cetak['pecah_tanggal'];

        // Get pemeriksaan hematology
        $hematology = PemeriksaanHematology::where('no_lab', $no_lab)
            ->where('status_pemeriksaan', 'selesai')
            ->orderBy('jenis_pengujian')
            ->get();

        // Get pemeriksaan kimia
        $kimia = PemeriksaanKimia::where('no_lab', $no_lab)
            ->orderBy('analysis')
            ->get();

        // Proses data pemeriksaan
        $pemeriksaanData = $this->processPemeriksaanData($hematology, $kimia);

        return [
            // Data pasien
            'nama' => $nama,
            'sex' => $jenis_kelamin == 'PRIA' ? 'L' : ($jenis_kelamin == 'WANITA' ? 'P' : $jenis_kelamin),
            'no_lab' => $no_lab,
            'no_rm' => $no_rm,
            'tgl_lahir' => $tgl_lahir,
            'susun_tgl_lahir' => $susun_tgl_lahir,
            'umur_tahun' => $umur['tahun'],
            'umur_bulan' => $umur['bulan'],
            'umur_hari' => $umur['hari'],
            'umur_format' => $umur_format,
            'periksa_tgl' => $this->formatTanggalIndo($tanggal_pemeriksaan),
            'tgl_ambil_sample' => $this->formatTanggalIndo($tgl_ambil_sample),
            'print_tanggal' => $print_tanggal,
            'print_tahun' => $print_tahun,
            'print_bulan' => $print_bulan,
            'print_hari' => $print_hari,
            'print_jam' => $print_jam,
            'print_menit' => $print_menit,
            'print_detik' => $print_detik,
            'updated_at' => $updated_at,
            'alamat' => $alamat,
            'dokter' => $dokter_nama,
            'ruang' => $ruang,
            'kelas' => $kelas,
            'penjamin' => $penjamin,
            'ket_klinik' => $ket_klinik,
            'created_at' => $pasien->created_at,
            'validator' => $pasien->pemeriksa->nama_pemeriksa ?? '-',
            // Catatan
            'catatan1' => $keluhan,
            'catatan2' => $catatan,

            // Data pemeriksaan
            'pemeriksaanData' => $pemeriksaanData,

            // Data untuk footer
            'pecah_tanggal' => $pecah_tanggal,
            'waktu_validasi' => $waktu_validasi,

            // Data dokter penanggung jawab
            'dokter_penanggung_jawab' => 'dr. Donny Kostradi, M.Kes, Sp.PK',

            // Flag untuk auto print
            'autoPrint' => request()->has('print'),
            'tanggal_cetak_format' => $tanggal_cetak_format
        ];
    }

    private function formatTanggalLahir($tgl_lahir)
    {

        LogActivityService::log(
            action: 'FORMAT',
            module: 'Tanggal Lahir',
            description: 'Format tanggal lahir: ' . (is_scalar($tgl_lahir) ? $tgl_lahir : 'Carbon')
        );

        if (empty($tgl_lahir)) {
            return '';
        }

        try {
            if ($tgl_lahir instanceof \Carbon\Carbon) {
                return $tgl_lahir->format('d-m-Y');
            }

            $date = new \DateTime($tgl_lahir);
            return $date->format('d-m-Y');
        } catch (\Exception $e) {
            return $tgl_lahir;
        }
    }

    private function hitungUmur($tgl_lahir, $umur_string = null)
    {
        LogActivityService::log(
            action: 'HITUNG',
            module: 'Umur Pasien',
            description: 'Hitung umur pasien dari tgl lahir: ' . (is_scalar($tgl_lahir) ? $tgl_lahir : 'Carbon')
                . ' atau umur string: ' . ($umur_string ?? '-')
        );
        // Jika ada umur_string yang sudah diformat
        if ($umur_string) {
            return $this->parseUmurString($umur_string);
        }

        // Hitung dari tanggal lahir
        if (empty($tgl_lahir)) {
            return ['tahun' => 0, 'bulan' => 0, 'hari' => 0];
        }

        try {
            $biday = $tgl_lahir instanceof \Carbon\Carbon ? $tgl_lahir : new \DateTime($tgl_lahir);
            $today = new \DateTime();
            $diff = $today->diff($biday);

            return [
                'tahun' => $diff->y,
                'bulan' => $diff->m,
                'hari' => $diff->d
            ];
        } catch (\Exception $e) {
            return ['tahun' => 0, 'bulan' => 0, 'hari' => 0];
        }
    }

    private function parseUmurString($umur_string)
    {
        LogActivityService::log(
            action: 'PARSE',
            module: 'Umur Pasien',
            description: 'Parse umur string: ' . $umur_string
        );
        $tahun = 0;
        $bulan = 0;
        $hari = 0;

        if (preg_match('/(\d+)\s*Tahun/i', $umur_string, $matches)) {
            $tahun = (int)$matches[1];
        }

        if (preg_match('/(\d+)\s*Bulan/i', $umur_string, $matches)) {
            $bulan = (int)$matches[1];
        }

        if (preg_match('/(\d+)\s*Hari/i', $umur_string, $matches)) {
            $hari = (int)$matches[1];
        }

        return ['tahun' => $tahun, 'bulan' => $bulan, 'hari' => $hari];
    }

    private function formatTanggalCetak($date)
    {
        try {

            LogActivityService::log(
                action: 'FORMAT',
                module: 'Tanggal Cetak',
                description: 'Format tanggal cetak: ' . (is_scalar($date) ? $date : 'Carbon')
            );

            if (is_string($date)) {
                $date = new \DateTime($date);
            } elseif (!$date instanceof \DateTime) {
                $date = new \DateTime();
            }

            $format_tanggal = $date->format('d-m-Y');
            $pecah_tanggal = explode('-', $format_tanggal);

            // Format Indonesia untuk footer
            $bulan = $date->format('n');
            $bulan_indonesia = [
                1 => 'Januari',
                2 => 'Februari',
                3 => 'Maret',
                4 => 'April',
                5 => 'Mei',
                6 => 'Juni',
                7 => 'Juli',
                8 => 'Agustus',
                9 => 'September',
                10 => 'Oktober',
                11 => 'November',
                12 => 'Desember'
            ];

            $format_indonesia = $date->format('d') . ' ' . $bulan_indonesia[$bulan] . ' ' . $date->format('Y');

            return [
                'format_tanggal' => $format_tanggal,
                'format_indonesia' => $format_indonesia,
                'pecah_tanggal' => $pecah_tanggal
            ];
        } catch (\Exception $e) {
            return [
                'format_tanggal' => date('d-m-Y'),
                'format_indonesia' => date('d F Y'),
                'pecah_tanggal' => explode('-', date('d-m-Y'))
            ];
        }
    }

    private function formatTanggalIndo($date)
    {
        if (empty($date)) {
            return '';
        }

        try {

            LogActivityService::log(
                action: 'FORMAT',
                module: 'Tanggal Indonesia',
                description: 'Format tanggal indo: ' . (is_scalar($date) ? $date : 'Carbon')
            );

            if (is_string($date)) {
                $date = new \DateTime($date);
            }

            $bulan = $date->format('n');
            $bulan_indonesia = [
                1 => 'Januari',
                2 => 'Februari',
                3 => 'Maret',
                4 => 'April',
                5 => 'Mei',
                6 => 'Juni',
                7 => 'Juli',
                8 => 'Agustus',
                9 => 'September',
                10 => 'Oktober',
                11 => 'November',
                12 => 'Desember'
            ];

            return $date->format('d') . ' ' . $bulan_indonesia[$bulan] . ' ' . $date->format('Y');
        } catch (\Exception $e) {
            return $date;
        }
    }

    private function processPemeriksaanData($hematology, $kimia)
    {

        LogActivityService::log(
            action: 'PROCESS',
            module: 'Pemeriksaan',
            description: 'Proses data pemeriksaan Hematology & Kimia'
        );

        $data = [];

        // Group pemeriksaan by type
        $groups = [
            'HEMATOLOGY' => $hematology,
            'KIMIA' => $kimia
        ];

        foreach ($groups as $groupName => $items) {
            if ($items->count() > 0) {
                $data[] = [
                    'group_name' => $groupName,
                    'items' => $this->processItems($items, $groupName)
                ];
            }
        }

        return $data;
    }

    private function processItems($items, $groupName)
    {

        LogActivityService::log(
            action: 'PROCESS',
            module: 'Pemeriksaan',
            description: "Proses item pemeriksaan {$groupName}"
        );

        $processed = [];

        foreach ($items as $item) {
            $hasil = $item->hasil_pengujian ?? '';
            $hasil = str_replace('<', '&lt;', $hasil);

            // Format hasil numerik
            $hasil_format = $this->formatHasil($hasil);

            // Get satuan
            $satuan = $groupName == 'HEMATOLOGY'
                ? ($item->satuan_hasil_pengujian ?? '')
                : ($item->satuan_hasil_pengujian ?? '');

            // Get rujukan
            $rujukan = $item->rujukan ?? '';
            $rujukan_format = $this->formatRujukan($rujukan);

            // Analisis flag
            $flag = $this->analyzeFlag($hasil, $rujukan);

            $processed[] = [
                'parameter' => $groupName == 'HEMATOLOGY'
                    ? ($item->jenis_pengujian ?? '')
                    : ($item->analysis ?? ''),
                'hasil' => $hasil_format,
                'rujukan' => $rujukan_format,
                'satuan' => $satuan,
                'metoda' => $groupName == 'KIMIA' ? ($item->method ?? 'Chemical Analyzer') : 'Hematology Analyzer',
                'flag' => $flag['code'],
                'flag_html' => $flag['html'],
                'keterangan' => $item->keterangan ?? '',
                'catatan' => $item->catatan ?? ''
            ];
        }

        return $processed;
    }

    private function formatHasil($hasil)
    {
        if (is_numeric($hasil)) {
            $hasil_float = (float)$hasil;

            // Format sesuai dengan kebutuhan
            if ($hasil_float == floor($hasil_float)) {
                // Integer
                return number_format($hasil_float, 0, '.', '');
            } else {
                // Decimal
                $decimal_places = max(0, strlen(substr(strrchr($hasil_float, "."), 1)));
                return number_format($hasil_float, min($decimal_places, 4), '.', '');
            }
        }

        return $hasil;
    }

    private function formatRujukan($rujukan)
    {
        if (empty($rujukan)) {
            return '';
        }

        // Jika ada tanda "-", format sebagai range
        if (strpos($rujukan, '-') !== false) {
            $parts = explode('-', $rujukan);
            if (count($parts) >= 2) {
                return trim($parts[0]) . ' - ' . trim($parts[1]);
            }
        }

        return $rujukan;
    }

    private function analyzeFlag($hasil, $rujukan)
    {
        if (empty($hasil) || empty($rujukan) || !is_numeric($hasil)) {
            return ['code' => '0', 'html' => ''];
        }

        $hasil_float = (float)$hasil;

        // Cek jika rujukan berupa range
        if (strpos($rujukan, '-') !== false) {
            $range = explode('-', $rujukan);
            if (count($range) >= 2) {
                $min = trim($range[0]);
                $max = trim($range[1]);

                if (is_numeric($min) && is_numeric($max)) {
                    $min_float = (float)$min;
                    $max_float = (float)$max;

                    if ($hasil_float > $max_float * 1.5) {
                        // Critical High
                        return ['code' => 'h', 'html' => '<span style="color:yellow;" valign="top">CH</span>'];
                    } elseif ($hasil_float > $max_float) {
                        // High
                        return ['code' => '4', 'html' => '<span style="color:red;" valign="top">H</span>'];
                    } elseif ($hasil_float < $min_float * 0.5) {
                        // Critical Low
                        return ['code' => 'l', 'html' => '<span style="color:yellow;" valign="top">CL</span>'];
                    } elseif ($hasil_float < $min_float) {
                        // Low
                        return ['code' => '5', 'html' => '<span style="color:blue;" valign="top">L</span>'];
                    }
                }
            }
        }

        return ['code' => '0', 'html' => ''];
    }

    public function history($rm_pasien = null)
    {
        try {
            // Jika RM pasien null, siapkan data kosong
            if (is_null($rm_pasien) || empty($rm_pasien)) {
                $histories = collect(); // koleksi kosong
                $latestPatient = null;

                return view('history', compact('histories', 'latestPatient'));
            }

            // Ambil semua data pasien dengan RM yang sama
            $histories = Pasien::where('rm_pasien', $rm_pasien)
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            // Ambil data pasien terbaru untuk header
            $latestPatient = Pasien::where('rm_pasien', $rm_pasien)
                ->orderBy('created_at', 'desc')
                ->first();

            // Jika tidak ada data pasien dengan RM tersebut
            if (!$latestPatient) {
                $histories = collect(); // tetap bisa akses halaman tapi tanpa data
            }

            return view('user.history', compact('histories', 'latestPatient'));
        } catch (\Exception $e) {
            return redirect()->route('pasien.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function cetakHasilLab($no_lab)
    {
        try {
            LogActivityService::log(
                action: 'PRINT',
                module: 'Hasil Laboratorium',
                description: 'Cetak hasil lab No LAB: ' . $no_lab
            );

            // 1. Ambil data pasien
            $pasien = Pasien::with([
                'hematology.dataPemeriksaan.lisMappings',
                'kimia.dataPemeriksaan',
                'hasilPemeriksaanLain.dataPemeriksaan.lisMappings',
                'pemeriksa',
                'ruangan'
            ])->where('no_lab', $no_lab)->firstOrFail();

            // 2. Proses data lab
            $lab = $this->processLabData($pasien);

            // 3. Hematology dengan urutan LIS
            $urutan = [
                'WBC', 'NEU%', 'LYM%', 'MON%', 'EOS%', 'BAS%',
                'RBC', 'HGB', 'HCT', 'MCV', 'MCH', 'MCHC',
                'RDW-CV', 'RDW-SD', 'PLT', 'MPV', 'PDW', 'PCT'
            ];

            $hasil = $pasien->hematology()->with('dataPemeriksaan.lisMappings')->get();
            $lisIndex = [];

            foreach ($hasil as $item) {
                if ($item->dataPemeriksaan && $item->dataPemeriksaan->lisMappings) {
                    foreach ($item->dataPemeriksaan->lisMappings as $mapping) {
                        $key = strtolower(trim($mapping->lis));
                        if (!isset($lisIndex[$key])) {
                            $lisIndex[$key] = $item;
                        }
                    }
                }
            }

            $hematology_fix = [];
            foreach ($urutan as $lis) {
                $key = strtolower(trim($lis));
                if (isset($lisIndex[$key])) {
                    $hematology_fix[] = $lisIndex[$key];
                } else {
                    $found = null;
                    foreach ($lisIndex as $lisKey => $item) {
                        if (strpos($lisKey, $key) !== false) {
                            $found = $item;
                            break;
                        }
                    }
                    $hematology_fix[] = $found;
                }
            }
            $hematology_fix = array_filter($hematology_fix);

            // 4. Kimia
            $kimia = $pasien->kimia()
                ->join('data_pemeriksaan as dp', 'pemeriksaan_kimia.id_data_pemeriksaan', '=', 'dp.id_data_pemeriksaan')
                ->with('dataPemeriksaan')
                ->orderBy('dp.urutan', 'asc') // 🔥 KUNCI
                ->select('pemeriksaan_kimia.*')
                ->get()
                ->filter(function ($item) {
                    return $item && !empty($item->analysis);
                })->values();


            // Di cetakHasilLab() method, ubah query hasil_lain menjadi:
            $hasil_lain = DB::table('hasil_pemeriksaan_lain as hpl')
                ->leftJoin('data_pemeriksaan as dp', 'hpl.id_data_pemeriksaan', '=', 'dp.id_data_pemeriksaan')
                ->leftJoin('jenis_pemeriksaan_1 as jp1', 'dp.id_jenis_pemeriksaan_1', '=', 'jp1.id_jenis_pemeriksaan_1')
                ->where('hpl.no_lab', $no_lab)
                ->whereNull('hpl.deleted_at')
                ->select(
                    'hpl.*',
                    'dp.data_pemeriksaan',
                    'dp.satuan as satuan_pemeriksaan',
                    'dp.rujukan as rujukan_pemeriksaan',
                    'dp.metode',
                    'dp.ch',
                    'dp.cl',
                    'jp1.nama_pemeriksaan as jenis_pemeriksaan_nama',
                    'jp1.id_jenis_pemeriksaan_1 as jenis_pemeriksaan_id'
                )
                ->orderBy('dp.urutan', 'asc') // 🔥 KUNCI UTAMA
                ->get();


            // Kemudian GROUP dengan cara yang SAMA PERSIS seperti di show()
            $hasil_lain_grouped = [];
            foreach ($hasil_lain as $item) {
                $jenis = $item->jenis_pemeriksaan_nama ?: 'Lainnya';

                if (!isset($hasil_lain_grouped[$jenis])) {
                    $hasil_lain_grouped[$jenis] = [];
                }

                $hasil_lain_grouped[$jenis][] = $item;
            }

            // 7. LOGIKA MULTI-HALAMAN
            $max_rows_per_page = 20;

            // Kumpulkan semua data
            $all_data = [];

            // Hematology
            if (!empty($hematology_fix)) {
                $all_data[] = ['type' => 'header', 'title' => 'HEMATOLOGY'];
                foreach ($hematology_fix as $item) {
                    if ($item) {
                        $all_data[] = [
                            'type' => 'row',
                            'category' => 'hematology',
                            'data_pemeriksaan' => $item->dataPemeriksaan->data_pemeriksaan ?? '',
                            'hasil_pengujian' => $item->hasil_pengujian ?? '',
                            'rujukan' => $item->dataPemeriksaan->rujukan ?? '',
                            'satuan' => $item->dataPemeriksaan->satuan ?? '',
                            'keterangan' => $item->keterangan ?? ''
                        ];
                    }
                }
            }

            // Kimia
            if ($kimia->count() > 0) {
                $all_data[] = ['type' => 'header', 'title' => 'KIMIA'];
                foreach ($kimia as $item) {
                    if ($item && !empty($item->analysis)) {
                        $all_data[] = [
                            'type' => 'row',
                            'category' => 'kimia',
                            'data_pemeriksaan' => $item->dataPemeriksaan->data_pemeriksaan ?? '',
                            'hasil_pengujian' => $item->hasil_pengujian ?? '',
                            'rujukan' => $item->dataPemeriksaan->rujukan ?? '',
                            'satuan' => $item->dataPemeriksaan->satuan ?? '',
                            'keterangan' => $item->keterangan ?? ''
                        ];
                    }
                }
            }

            // Hasil Lain
            if (!empty($hasil_lain_grouped)) {
                foreach ($hasil_lain_grouped as $jenis => $items) {
                    if (count($items) > 0) {
                        $all_data[] = ['type' => 'header', 'title' => strtoupper($jenis)];
                        foreach ($items as $item) {
                            if ($item) {
                                $all_data[] = [
                                    'type' => 'row',
                                    'category' => 'hasil_lain',
                                    'data_pemeriksaan' => $item->data_pemeriksaan ?? '',
                                    'hasil_pengujian' => $item->hasil_pengujian ?? '',
                                    'rujukan' => $item->rujukan_pemeriksaan ?? '',
                                    'satuan' => $item->satuan_pemeriksaan ?? '',
                                    'keterangan' => $item->keterangan ?? ''
                                ];
                            }
                        }
                    }
                }
            }

            // Bagi data ke dalam halaman
            $pages = [];
            $current_page_data = [];
            $current_page_row_count = 0;

            foreach ($all_data as $item) {
                $item_rows = ($item['type'] == 'header') ? 1 : 1;

                if ($current_page_row_count + $item_rows > $max_rows_per_page && !empty($current_page_data)) {
                    $pages[] = $current_page_data;
                    $current_page_data = [];
                    $current_page_row_count = 0;

                    // Tambah header lagi di halaman baru jika ini adalah row
                    if ($item['type'] == 'row') {
                        // Cari header terakhir
                        $last_header = null;
                        for ($i = count($all_data) - 1; $i >= 0; $i--) {
                            if ($all_data[$i]['type'] == 'header') {
                                $last_header = $all_data[$i];
                                break;
                            }
                        }
                        if ($last_header) {
                            $current_page_data[] = $last_header;
                            $current_page_row_count += 1;
                        }
                    }
                }

                $current_page_data[] = $item;
                $current_page_row_count += $item_rows;
            }

            // Tambah halaman terakhir
            if (!empty($current_page_data)) {
                $pages[] = $current_page_data;
            }

            $total_pages = count($pages);

            // 8. QR CODE
            Carbon::setLocale('id');
            $now = Carbon::now('Asia/Jakarta');
            $dateFile = $now->format('Y-m-d');

            $qrDir = public_path('file/qr');
            if (!is_dir($qrDir)) mkdir($qrDir, 0755, true);

            $qrPath = "$qrDir/qr_$dateFile.png";

            if (!file_exists($qrPath)) {
                $qrText = "dr. DONNY KOSTRADI, M.Kes, Sp.PK\n"
                    . "MR15712507005085 \n"
                    . "Hasil Pemeriksaan Laboratorium RS. Baiturrahim\n"
                    . "Jambi, " . $now->translatedFormat('d F Y');

                $result = Builder::create()
                    ->writer(new PngWriter())
                    ->data($qrText)
                    ->encoding(new Encoding('UTF-8'))
                    ->size(200)
                    ->margin(5)
                    ->build();

                $result->saveToFile($qrPath);
            }

            $today = $now->translatedFormat('d F Y');

            // 9. Return view
            return view('user.print', array_merge([
                'pasien' => $pasien,
                'hematology_fix' => $hematology_fix,
                'kimia' => $kimia,
                'hasil_lain' => $hasil_lain,
                'hasil_lain_grouped' => $hasil_lain_grouped,
                'autoPrint' => request()->has('print'),
                'no_lab' => $no_lab,
                'qrCodePath' => asset("file/qr/qr_$dateFile.png"),
                'today' => $today,
                'pages' => $pages,
                'total_pages' => $total_pages,
                'max_rows_per_page' => $max_rows_per_page
            ], $lab));

        } catch (\Exception $e) {
            dd($e->getMessage(), $e->getTraceAsString());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function checkFile($no_lab)
    {
        // Selalu jalankan job setiap panggilan
        GeneratePdfJob::dispatch($no_lab);
        sleep(5);

        $folder = public_path("file");
        $pattern = "{$folder}/hasil_pengujian_{$no_lab}*.pdf";

        $files = glob($pattern);

        if (empty($files)) {
            return response()->json(['ready' => false]);
        }

        // Ambil file terbaru berdasarkan suffix terbesar
        $latest = collect($files)->sortByDesc(function ($file) {

            $name = pathinfo($file, PATHINFO_FILENAME);

            if (preg_match('/_(\d+)$/', $name, $m)) {
                return intval($m[1]);
            }

            return 0; // tanpa suffix dianggap paling lama
        })->first();

        return response()->json([
            'ready' => true,
            'file'  => asset("file/" . basename($latest))
        ]);
    }
}

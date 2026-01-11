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
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Jobs\GeneratePdfJob;
use Illuminate\Support\Facades\DB;
use App\Services\LogActivityService;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Writer\PngWriter;
use App\Models\DataPemeriksaan;

class UserController extends Controller
{
    public function index(Request $request)
    {
        Carbon::setLocale('id');

        // =========================
        // DATE CONTEXT
        // =========================
        $date = $request->filled('search_date')
            ? Carbon::parse($request->search_date)
            : Carbon::today();

        $activeDate = $date;

        $ymd = $date->format('ymd');
        $dateOnly = $date->toDateString();

        // =========================
        // QUERY DATA PASIEN
        // =========================
        $query = Pasien::query();

        $query->where(function ($q) use ($ymd, $dateOnly, $request) {

            // DEFAULT INDEX (HARIAN)
            $q->where(function ($x) use ($ymd, $dateOnly) {
                $x->where('nomor_registrasi', 'like', $ymd . '%')
                ->orWhere(function ($xx) use ($dateOnly) {
                    $xx->where(function ($n) {
                            $n->whereNull('nomor_registrasi')
                                ->orWhere('nomor_registrasi', '');
                        })
                        ->whereDate('updated_at', $dateOnly);
                });
            });

            // KHUSUS RM PASIEN → SEMUA HISTORY (TIDAK PEDULI TANGGAL)
            if ($request->filled('filter_rm')) {
                $rm = $request->filter_rm;
                $q->orWhere('rm_pasien', 'ilike', "%{$rm}%");
            }
        });

        // =========================
        // FILTER LAIN (TETAP)
        // =========================
        $this->applyColumnFilters($query, $request);

        $pasiens = $query
            ->orderByDesc('nomor_registrasi')
            ->paginate(50)
            ->withQueryString();

        // =========================
        // STATISTIK (TIDAK DIUBAH)
        // =========================
        $stat = Pasien::query();

        $stat->where(function ($q) use ($ymd, $dateOnly, $request) {

            // DEFAULT INDEX (HARIAN)
            $q->where(function ($x) use ($ymd, $dateOnly) {
                $x->where('nomor_registrasi', 'like', $ymd . '%')
                ->orWhere(function ($xx) use ($dateOnly) {
                    $xx->where(function ($n) {
                            $n->whereNull('nomor_registrasi')
                                ->orWhere('nomor_registrasi', '');
                        })
                        ->whereDate('updated_at', $dateOnly);
                });
            });

            // KHUSUS RM PASIEN (STATISTIK IKUT)
            if ($request->filled('filter_rm')) {
                $rm = $request->filter_rm;
                $q->orWhere('rm_pasien', 'ilike', "%{$rm}%");
            }
        });

        $this->applyColumnFilters($stat, $request);

        $statusOrders  = (clone $stat)->count();
        $statusSelesai = (clone $stat)->whereNotNull('id_pemeriksa')->count();
        $statusProses  = (clone $stat)->whereNull('id_pemeriksa')->count();

        // =========================
        // LOG
        // =========================
        LogActivityService::log(
            action: 'READ',
            module: 'Pasien',
            description: 'User mengakses pasien dengan filter'
        );

        // =========================
        // RETURN VIEW (LENGKAP)
        // =========================
        return view('user.index', compact(
            'pasiens',
            'statusOrders',
            'statusSelesai',
            'statusProses',
            'date'
        ))->with([
            'activeDate' => $activeDate
        ]);
    }

    public function search(Request $request)
    {
        // Method ini bisa digabung dengan index() atau diarahkan ke index()
        return $this->index($request);
    }

    // =========================
    // HELPER METHOD UNTUK FILTER KOLOM
    // =========================
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
            'hematology.dataPemeriksaan.detailConditions',
            'kimia.dataPemeriksaan',
            'hasilPemeriksaanLain.dataPemeriksaan.lisMappings',
            'pemeriksa',
            'ujiPemeriksaan',
        ])->findOrFail($no_lab);

        // Proses lab data (jika Anda punya logic lain, letakkan di sini)
        $data = $this->processLabDataFix($pasien);

        // Tambahkan data kondisi pasien ke array data
        $data['jenis_kelamin'] = $pasien->jenis_kelamin;
        $data['umur_hari'] = $this->hitungUmurFix($pasien->tgl_lahir, $pasien->umur);
        // simpan format asli juga jika dibutuhkan view
        $data['umur_format'] = $pasien->umur ?? null;

        /* =========================
         * HEMATOLOGY DENGAN RUJUKAN KONDISI
         * ========================= */
        $urutan = [
            'WBC','NEU%','LYM%','MON%','EOS%','BAS%',
            'RBC','HGB','HCT','MCV','MCH','MCHC',
            'RDW-CV','RDW-SD','PLT','MPV','PDW','PCT'
        ];

        $hasil = $pasien->hematology()
            ->with('dataPemeriksaan.lisMappings')
            ->with('dataPemeriksaan.detailConditions')
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
                $item = $lisIndex[$lisLower];

                if ($item && $item->dataPemeriksaan) {
                    // PENTING: kirim umur_hari (integer)
                    $rujukanData = $item->dataPemeriksaan->getRujukanByKondisiPasien(
                        $pasien->jenis_kelamin,
                        $data['umur_hari']
                    );

                    if ($rujukanData['is_from_detail']) {
                        $ch_value = $rujukanData['ch'] ?? '-';
                        $cl_value = $rujukanData['cl'] ?? '-';
                        $rujukan_value = $rujukanData['rujukan'] ?? '-';
                        $satuan_value = $rujukanData['satuan'] ?? '-';
                    } else {
                        $ch_value = $item->dataPemeriksaan->ch ?? '-';
                        $cl_value = $item->dataPemeriksaan->cl ?? '-';
                        $rujukan_value = $item->dataPemeriksaan->rujukan ?? '-';
                        $satuan_value = $item->dataPemeriksaan->satuan ?? '-';
                    }

                    $item->rujukan_by_kondisi = [
                        'rujukan' => $rujukan_value,
                        'ch' => $ch_value,
                        'cl' => $cl_value,
                        'satuan' => $satuan_value,
                        'is_from_detail' => $rujukanData['is_from_detail'],
                        'detail_condition' => $rujukanData['detail_condition']
                    ];

                    if ($item->hasil_pengujian && $rujukan_value !== '-') {
                        $item->calculated_keterangan = $this->determineKeterangan(
                            $item->hasil_pengujian,
                            $rujukan_value,
                            $ch_value,
                            $cl_value
                        );
                    } else {
                        $item->calculated_keterangan = $item->keterangan ?? '-';
                    }

                    if ($item->hasil_pengujian) {
                        Log::info('Hematology Keterangan Calculation', [
                            'no_lab' => $no_lab,
                            'jenis' => $item->dataPemeriksaan->data_pemeriksaan ?? 'Unknown',
                            'hasil' => $item->hasil_pengujian,
                            'rujukan' => $rujukan_value,
                            'ch_used' => $ch_value,
                            'cl_used' => $cl_value,
                            'keterangan' => $item->calculated_keterangan,
                            'is_from_detail' => $rujukanData['is_from_detail']
                        ]);
                    }
                }

                $hematology_fix[] = $item;
                continue;
            }

            // fallback partial match
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
            ->with('dataPemeriksaan.detailConditions')
            ->orderByRaw("\n                CASE\n                    WHEN trim(dp.kode_pemeriksaan) ~ '^[0-9]+$' THEN 0\n                    ELSE 1\n                END,\n                CASE\n                    WHEN trim(dp.kode_pemeriksaan) ~ '^[0-9]+$'\n                    THEN trim(dp.kode_pemeriksaan)::integer\n                    ELSE NULL\n                END,\n                dp.kode_pemeriksaan\n            ")
            ->select('pemeriksaan_kimia.*')
            ->get();

        foreach ($kimia as $item) {
            if ($item->dataPemeriksaan) {
                $rujukanData = $item->dataPemeriksaan->getRujukanByKondisiPasien(
                    $pasien->jenis_kelamin,
                    $data['umur_hari']
                );

                if ($rujukanData['is_from_detail']) {
                    $ch_value = $rujukanData['ch'] ?? '-';
                    $cl_value = $rujukanData['cl'] ?? '-';
                    $rujukan_value = $rujukanData['rujukan'] ?? '-';
                    $satuan_value = $rujukanData['satuan'] ?? '-';
                } else {
                    $ch_value = $item->dataPemeriksaan->ch ?? '-';
                    $cl_value = $item->dataPemeriksaan->cl ?? '-';
                    $rujukan_value = $item->dataPemeriksaan->rujukan ?? '-';
                    $satuan_value = $item->dataPemeriksaan->satuan ?? '-';
                }

                $item->rujukan_by_kondisi = [
                    'rujukan' => $rujukan_value,
                    'ch' => $ch_value,
                    'cl' => $cl_value,
                    'satuan' => $satuan_value,
                    'is_from_detail' => $rujukanData['is_from_detail'],
                    'detail_condition' => $rujukanData['detail_condition']
                ];

                if ($item->hasil_pengujian && $rujukan_value !== '-') {
                    $item->calculated_keterangan = $this->determineKeterangan(
                        $item->hasil_pengujian,
                        $rujukan_value,
                        $ch_value,
                        $cl_value
                    );
                } else {
                    $item->calculated_keterangan = $item->keterangan ?? '-';
                }

                if ($item->hasil_pengujian) {
                    Log::info('Kimia Keterangan Calculation', [
                        'no_lab' => $no_lab,
                        'jenis' => $item->analysis ?? 'Unknown',
                        'hasil' => $item->hasil_pengujian,
                        'rujukan' => $rujukan_value,
                        'ch_used' => $ch_value,
                        'cl_used' => $cl_value,
                        'keterangan' => $item->calculated_keterangan,
                        'is_from_detail' => $rujukanData['is_from_detail']
                    ]);
                }
            }
        }

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
                'dp.kode_pemeriksaan',
                'dp.data_pemeriksaan',
                'dp.satuan as satuan_pemeriksaan',
                'dp.rujukan as rujukan_pemeriksaan',
                'dp.metode',
                'dp.ch',
                'dp.cl',
                'dp.urutan as urutan_pemeriksaan',
                'jp1.nama_pemeriksaan as jenis_pemeriksaan_nama',
                'jp1.id_jenis_pemeriksaan_1 as jenis_pemeriksaan_id',
                DB::raw("COALESCE(jp1.nama_pemeriksaan, 'Lainnya') as kelompok_pemeriksaan")
            )
            ->orderByRaw("\n                CASE\n                    WHEN trim(dp.kode_pemeriksaan) ~ '^[0-9]+$' THEN 0\n                    ELSE 1\n                END,\n                CASE\n                    WHEN trim(dp.kode_pemeriksaan) ~ '^[0-9]+$'\n                    THEN trim(dp.kode_pemeriksaan)::integer\n                    ELSE NULL\n                END,\n                dp.kode_pemeriksaan\n            ")
            ->get();

        $hasil_lain = $hasil_lain->map(function($item) use ($pasien, $data) {
            if ($item->id_data_pemeriksaan) {
                $dataPemeriksaan = DataPemeriksaan::with('detailConditions')
                    ->find($item->id_data_pemeriksaan);

                if ($dataPemeriksaan) {
                    $rujukanData = $dataPemeriksaan->getRujukanByKondisiPasien(
                        $pasien->jenis_kelamin,
                        $data['umur_hari']
                    );

                    if ($rujukanData['is_from_detail']) {
                        $item->ch_by_kondisi = $rujukanData['ch'] ?? $item->ch ?? '-';
                        $item->cl_by_kondisi = $rujukanData['cl'] ?? $item->cl ?? '-';
                        $item->rujukan_by_kondisi = $rujukanData['rujukan'] ?? $item->rujukan_pemeriksaan ?? '-';
                        $item->satuan_by_kondisi = $rujukanData['satuan'] ?? $item->satuan_pemeriksaan ?? '-';
                    } else {
                        $item->ch_by_kondisi = $item->ch ?? '-';
                        $item->cl_by_kondisi = $item->cl ?? '-';
                        $item->rujukan_by_kondisi = $item->rujukan_pemeriksaan ?? '-';
                        $item->satuan_by_kondisi = $item->satuan_pemeriksaan ?? '-';
                    }

                    $item->is_from_detail = $rujukanData['is_from_detail'];
                    $item->detail_condition = $rujukanData['detail_condition'];

                    if ($item->hasil_pengujian && $item->rujukan_by_kondisi !== '-') {
                        $item->calculated_keterangan = $this->determineKeterangan(
                            $item->hasil_pengujian,
                            $item->rujukan_by_kondisi,
                            $item->ch_by_kondisi,
                            $item->cl_by_kondisi
                        );
                    } else {
                        $item->calculated_keterangan = $item->keterangan ?? '-';
                    }
                } else {
                    $item->ch_by_kondisi = $item->ch ?? '-';
                    $item->cl_by_kondisi = $item->cl ?? '-';
                    $item->rujukan_by_kondisi = $item->rujukan_pemeriksaan ?? '-';
                    $item->satuan_by_kondisi = $item->satuan_pemeriksaan ?? '-';
                    $item->is_from_detail = false;
                    $item->calculated_keterangan = $item->keterangan ?? '-';
                }
            } else {
                $item->ch_by_kondisi = $item->ch ?? '-';
                $item->cl_by_kondisi = $item->cl ?? '-';
                $item->rujukan_by_kondisi = $item->rujukan ?? '-';
                $item->satuan_by_kondisi = $item->satuan_hasil_pengujian ?? '-';
                $item->is_from_detail = false;
                $item->calculated_keterangan = $item->keterangan ?? '-';
            }

            return $item;
        });

        /* =========================
         * GROUPING DENGAN URUTAN YANG TEPAT
         * ========================= */
        $hasil_lain_grouped = [];
        $jenis_pemeriksaan_order = [];

        foreach ($hasil_lain as $item) {
            $jenis_pemeriksaan = $item->kelompok_pemeriksaan;

            if (!isset($hasil_lain_grouped[$jenis_pemeriksaan])) {
                $hasil_lain_grouped[$jenis_pemeriksaan] = [];
                if (!isset($jenis_pemeriksaan_order[$jenis_pemeriksaan])) {
                    $jenis_pemeriksaan_order[$jenis_pemeriksaan] = $item->created_at ?? now();
                }
            }

            $hasil_lain_grouped[$jenis_pemeriksaan][] = $item;
        }

        /* =========================
         * JENIS PEMERIKSAAN LIST
         * ========================= */
        $jenis_pemeriksaan_1_list = DB::table('jenis_pemeriksaan_1')
            ->whereNull('deleted_at')
            ->orderBy('nama_pemeriksaan')
            ->get();

        $rujukanBatchPayload = [];

        // 1. HEMATOLOGY
        foreach ($hematology_fix as $item) {
            if (!empty($item->id_data_pemeriksaan)) {
                $rujukanBatchPayload[$item->id_data_pemeriksaan] = [
                    'id_data_pemeriksaan' => (string) $item->id_data_pemeriksaan,
                    'jenis_kelamin' => $pasien->jenis_kelamin ?? '',
                    'umur_pasien' => $data['umur_hari'] ?? '', // KIRIM HARI
                ];
            }
        }

        // 2. KIMIA
        foreach ($kimia as $item) {
            if (!empty($item->id_data_pemeriksaan)) {
                $rujukanBatchPayload[$item->id_data_pemeriksaan] = [
                    'id_data_pemeriksaan' => (string) $item->id_data_pemeriksaan,
                    'jenis_kelamin' => $pasien->jenis_kelamin ?? '',
                    'umur_pasien' => $data['umur_hari'] ?? '',
                ];
            }
        }

        // 3. PEMERIKSAAN LAIN
        foreach ($hasil_lain as $item) {
            if (!empty($item->id_data_pemeriksaan)) {
                $rujukanBatchPayload[$item->id_data_pemeriksaan] = [
                    'id_data_pemeriksaan' => (string) $item->id_data_pemeriksaan,
                    'jenis_kelamin' => $pasien->jenis_kelamin ?? '',
                    'umur_pasien' => $data['umur_hari'] ?? '',
                ];
            }
        }

        // reset index agar jadi array bersih
        $rujukanBatchPayload = array_values($rujukanBatchPayload);

        LogActivityService::log(
            action: 'READ',
            module: 'Pasien',
            description: 'Melihat hasil detail pasien ID: ' . $no_lab
        );

        return view('detail', [
            'pasien' => $pasien,
            'kimia' => $kimia,
            'hematology_fix' => $hematology_fix,
            'hasil_lain' => $hasil_lain,
            'hasil_lain_grouped' => $hasil_lain_grouped,
            'jenis_pemeriksaan_1_list' => $jenis_pemeriksaan_1_list,
            'data' => $data,
            'rujukanBatchPayload'=> $rujukanBatchPayload,
        ]);
    }

    // ---------------- Helper methods ----------------

    /**
     * Basic processor for lab data. Keep small — extend as needed.
     */
    protected function processLabDataFix(Pasien $pasien): array
    {
        return [
            'raw_pasien' => $pasien,
            'umur_format' => $pasien->umur ?? null,
        ];
    }

    /**
     * Hitung umur dalam hari. Jika tgl_lahir ada gunakan Carbon diffInDays, jika gagal gunakan parser string.
     */
    protected function hitungUmurFix(?string $tgl_lahir, $umur_format): ?int
    {
        if ($tgl_lahir) {
            try {
                $lahir = Carbon::parse($tgl_lahir);
                return $lahir->diffInDays(Carbon::now());
            } catch (\Exception $e) {
                Log::warning('hitungUmur: gagal parse tgl_lahir - ' . $e->getMessage());
                return DataPemeriksaan::normalizeUmurToHari($umur_format);
            }
        }

        return DataPemeriksaan::normalizeUmurToHari($umur_format);
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

    public function history($rm_pasien, Request $request)
    {
        // Ambil semua history pasien berdasarkan rm_pasien
        $histories = Pasien::where('rm_pasien', $rm_pasien)
            ->where('waktu_validasi', '!=', null)
            ->orwhere('id_pemeriksa', '!=', '')
            ->orderBy('created_at', 'desc')
            ->paginate(100);

        // Ambil data pasien terbaru untuk header
        $latestPatient = Pasien::where('rm_pasien', $rm_pasien)
            ->orderBy('created_at', 'desc')
            ->first();

        // Tampilkan view dengan parameter view jika ada
        return view('user.history', compact('histories', 'latestPatient'));
    }

    private function determineKeterangan($hasil, $rujukan, $ch = null, $cl = null)
    {
        $hasil = trim($hasil ?? '');
        $ch = trim($ch ?? '');
        $cl = trim($cl ?? '');
        $rujukan = trim($rujukan ?? '');

        // Jika hasil kosong
        if (empty($hasil)) {
            return '-';
        }

        // Cek apakah hasil numerik
        $hasilNum = is_numeric($hasil) ? (float) $hasil : null;

        /* ================================
        * PRIORITAS 1: Gunakan CH dan CL jika ada
        * ================================ */
        if ($hasilNum !== null) {
            // Cek CH (Critical High) - jika ada dan hasil melebihi
            if (!empty($ch) && $ch !== '' && $ch !== '-') {
                $chNum = is_numeric($ch) ? (float) $ch : null;
                if ($chNum !== null && $hasilNum > $chNum) {
                    return 'CH';
                }
            }

            // Cek CL (Critical Low) - jika ada dan hasil kurang dari
            if (!empty($cl) && $cl !== '' && $cl !== '-') {
                $clNum = is_numeric($cl) ? (float) $cl : null;
                if ($clNum !== null && $hasilNum < $clNum) {
                    return 'CL';
                }
            }

            // Jika ada CH tapi tidak CL, atau sebaliknya
            // Cek apakah hasil berada dalam batas normal jika hanya satu yang ada
            if (!empty($ch) && $ch !== '' && $ch !== '-' && empty($cl)) {
                $chNum = is_numeric($ch) ? (float) $ch : null;
                if ($chNum !== null && $hasilNum <= $chNum) {
                    return '-'; // Normal karena tidak melebihi CH
                } elseif ($chNum !== null && $hasilNum > $chNum) {
                    return 'CH';
                }
            }

            if (!empty($cl) && $cl !== '' && $cl !== '-' && empty($ch)) {
                $clNum = is_numeric($cl) ? (float) $cl : null;
                if ($clNum !== null && $hasilNum >= $clNum) {
                    return '-'; // Normal karena tidak kurang dari CL
                } elseif ($clNum !== null && $hasilNum < $clNum) {
                    return 'CL';
                }
            }

            // Jika kedua CH dan CL ada dan hasil di antara keduanya
            if (!empty($ch) && !empty($cl) && $ch !== '' && $cl !== '' && $ch !== '-' && $cl !== '-') {
                $chNum = is_numeric($ch) ? (float) $ch : null;
                $clNum = is_numeric($cl) ? (float) $cl : null;

                if ($chNum !== null && $clNum !== null) {
                    if ($hasilNum > $chNum) {
                        return 'CH';
                    } elseif ($hasilNum < $clNum) {
                        return 'CL';
                    } else {
                        return '-'; // Normal karena antara CL dan CH
                    }
                }
            }
        }

        /* ================================
        * PRIORITAS 2: Gunakan RUJUKAN jika CH/CL tidak ada/tidak valid
        * ================================ */
        // Jika rujukan kosong, null, atau '-'
        if (empty($rujukan) || $rujukan === '' || $rujukan === '-') {
            return '-';
        }

        // Jika hasil non-numeric, gunakan logika kualitatif dari rujukan
        if ($hasilNum === null) {
            $hasilLower = strtolower($hasil);
            $rujukanLower = strtolower($rujukan);

            if (strpos($rujukanLower, 'negative') !== false || strpos($rujukanLower, 'negatif') !== false) {
                if (
                    strpos($hasilLower, 'negative') !== false ||
                    strpos($hasilLower, 'negatif') !== false ||
                    strpos($hasilLower, 'non-reactive') !== false ||
                    strpos($hasilLower, 'nonreactive') !== false
                ) {
                    return '-';
                } else {
                    return 'H';
                }
            } elseif (strpos($rujukanLower, 'positive') !== false || strpos($rujukanLower, 'positif') !== false) {
                if (
                    strpos($hasilLower, 'positive') !== false ||
                    strpos($hasilLower, 'positif') !== false ||
                    strpos($hasilLower, 'reactive') !== false ||
                    strpos($hasilLower, 'reaktif') !== false
                ) {
                    return '-';
                } else {
                    return 'L';
                }
            }

            return '-';
        }

        // Handle rujukan dengan format "X - Y"
        if (strpos($rujukan, '-') !== false && strpos($rujukan, '<') === false && strpos($rujukan, '>') === false) {
            $parts = explode('-', $rujukan);
            if (count($parts) === 2) {
                $min = trim($parts[0]);
                $max = trim($parts[1]);

                if (is_numeric($min) && is_numeric($max)) {
                    $min = (float) $min;
                    $max = (float) $max;

                    if ($hasilNum !== null) {
                        if ($hasilNum < $min) {
                            return 'L';
                        } elseif ($hasilNum > $max) {
                            return 'H';
                        } else {
                            return '-';
                        }
                    }
                }
            }
        }

        // Handle rujukan dengan format "< X" (Normal jika < X)
        if (strpos($rujukan, '<') === 0) {
            $batas = trim(substr($rujukan, 1));
            if (is_numeric($batas)) {
                $batas = (float) $batas;
                if ($hasilNum !== null) {
                    if ($hasilNum < $batas) {
                        return '-';
                    } else {
                        return 'H';
                    }
                }
            }
        }

        // Handle rujukan dengan format "> X" (Normal jika > X)
        if (strpos($rujukan, '>') === 0) {
            $batas = trim(substr($rujukan, 1));
            if (is_numeric($batas)) {
                $batas = (float) $batas;
                if ($hasilNum !== null) {
                    if ($hasilNum > $batas) {
                        return '-';
                    } else {
                        return 'L';
                    }
                }
            }
        }

        // Default
        return '-';
    }

    public function cetakHasilLab($no_lab)
    {
        try {
            LogActivityService::log(
                action: 'PRINT',
                module: 'Hasil Laboratorium',
                description: 'Cetak hasil lab No LAB: ' . $no_lab
            );

            // 1. Ambil data pasien dengan relasi yang sama seperti di show()
            $pasien = Pasien::with([
                'hematology.dataPemeriksaan.lisMappings',
                'hematology.dataPemeriksaan.detailConditions',
                'kimia.dataPemeriksaan.detailConditions',
                'hasilPemeriksaanLain.dataPemeriksaan.lisMappings',
                'pemeriksa',
                'ruangan',
                'ujiPemeriksaan'
            ])->where('no_lab', $no_lab)->firstOrFail();

            // 2. Proses data lab untuk kondisi (menggunakan processLabDataFix seperti di show())
            $dataCondition = $this->processLabDataFix($pasien);

            // 3. Proses data lab untuk view (menggunakan processLabData seperti sebelumnya)
            $lab = $this->processLabData($pasien);

            // 4. Gabungkan data condition ke dalam $lab array
            $lab['jenis_kelamin'] = $pasien->jenis_kelamin;
            $lab['umur_hari'] = $this->hitungUmurFix($pasien->tgl_lahir, $pasien->umur);
            $lab['umur_format'] = $pasien->umur ?? null;

            // 5. Tambahkan data dari processLabDataFix jika ada
            if (isset($dataCondition['umur_hari'])) {
                $lab['umur_hari'] = $dataCondition['umur_hari'];
            }

            /* =========================
            * HEMATOLOGY DENGAN RUJUKAN KONDISI (sama seperti di show())
            * ========================= */
            $urutan = [
                'WBC', 'NEU%', 'LYM%', 'MON%', 'EOS%', 'BAS%',
                'RBC', 'HGB', 'HCT', 'MCV', 'MCH', 'MCHC',
                'RDW-CV', 'RDW-SD', 'PLT', 'MPV', 'PDW', 'PCT'
            ];

            $hasilHematology = $pasien->hematology()
                ->with('dataPemeriksaan.lisMappings')
                ->with('dataPemeriksaan.detailConditions')
                ->get();

            $lisIndex = [];
            foreach ($hasilHematology as $item) {
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
                    $item = $lisIndex[$key];
                } else {
                    $item = null;
                    foreach ($lisIndex as $lisKey => $it) {
                        if (strpos($lisKey, $key) !== false) {
                            $item = $it;
                            break;
                        }
                    }
                }

                if ($item && $item->dataPemeriksaan) {
                    // Menggunakan umur_hari dari kondisi seperti di show()
                    $rujukanData = $item->dataPemeriksaan->getRujukanByKondisiPasien(
                        $pasien->jenis_kelamin,
                        $lab['umur_hari'] // Menggunakan umur_hari dari kondisi
                    );

                    if ($rujukanData['is_from_detail']) {
                        $ch_value = $rujukanData['ch'] ?? '-';
                        $cl_value = $rujukanData['cl'] ?? '-';
                        $rujukan_value = $rujukanData['rujukan'] ?? '-';
                        $satuan_value = $rujukanData['satuan'] ?? '-';
                    } else {
                        $ch_value = $item->dataPemeriksaan->ch ?? '-';
                        $cl_value = $item->dataPemeriksaan->cl ?? '-';
                        $rujukan_value = $item->dataPemeriksaan->rujukan ?? '-';
                        $satuan_value = $item->dataPemeriksaan->satuan ?? '-';
                    }

                    $item->rujukan_by_kondisi = [
                        'rujukan' => $rujukan_value,
                        'ch' => $ch_value,
                        'cl' => $cl_value,
                        'satuan' => $satuan_value,
                        'is_from_detail' => $rujukanData['is_from_detail'],
                        'detail_condition' => $rujukanData['detail_condition']
                    ];

                    if ($item->hasil_pengujian && $rujukan_value !== '-') {
                        $item->calculated_keterangan = $this->determineKeterangan(
                            $item->hasil_pengujian,
                            $rujukan_value,
                            $ch_value,
                            $cl_value
                        );
                    } else {
                        $item->calculated_keterangan = $item->keterangan ?? '-';
                    }
                }

                if ($item) $hematology_fix[] = $item;
            }

            /* =========================
            * KIMIA (ORDER BY dp.urutan) — sama seperti di show()
            * ========================= */
            $kimia = $pasien->kimia()
                ->leftJoin('data_pemeriksaan as dp', 'pemeriksaan_kimia.id_data_pemeriksaan', '=', 'dp.id_data_pemeriksaan')
                ->with('dataPemeriksaan.jenisPemeriksaan')
                ->with('dataPemeriksaan.detailConditions')
                ->orderByRaw("
                    CASE
                        WHEN trim(dp.kode_pemeriksaan) ~ '^[0-9]+$' THEN 0
                        ELSE 1
                    END,
                    CASE
                        WHEN trim(dp.kode_pemeriksaan) ~ '^[0-9]+$'
                        THEN trim(dp.kode_pemeriksaan)::integer
                        ELSE NULL
                    END,
                    dp.kode_pemeriksaan
                ")
                ->select('pemeriksaan_kimia.*')
                ->get();

            foreach ($kimia as $item) {
                if ($item->dataPemeriksaan) {
                    $rujukanData = $item->dataPemeriksaan->getRujukanByKondisiPasien(
                        $pasien->jenis_kelamin,
                        $lab['umur_hari'] // Menggunakan umur_hari dari kondisi
                    );

                    if ($rujukanData['is_from_detail']) {
                        $ch_value = $rujukanData['ch'] ?? '-';
                        $cl_value = $rujukanData['cl'] ?? '-';
                        $rujukan_value = $rujukanData['rujukan'] ?? '-';
                        $satuan_value = $rujukanData['satuan'] ?? '-';
                    } else {
                        $ch_value = $item->dataPemeriksaan->ch ?? '-';
                        $cl_value = $item->dataPemeriksaan->cl ?? '-';
                        $rujukan_value = $item->dataPemeriksaan->rujukan ?? '-';
                        $satuan_value = $item->dataPemeriksaan->satuan ?? '-';
                    }

                    $item->rujukan_by_kondisi = [
                        'rujukan' => $rujukan_value,
                        'ch' => $ch_value,
                        'cl' => $cl_value,
                        'satuan' => $satuan_value,
                        'is_from_detail' => $rujukanData['is_from_detail'],
                        'detail_condition' => $rujukanData['detail_condition']
                    ];

                    if ($item->hasil_pengujian && $rujukan_value !== '-') {
                        $item->calculated_keterangan = $this->determineKeterangan(
                            $item->hasil_pengujian,
                            $rujukan_value,
                            $ch_value,
                            $cl_value
                        );
                    } else {
                        $item->calculated_keterangan = $item->keterangan ?? '-';
                    }
                }
            }

            /* =========================
            * HASIL LAIN (ORDER BY dp.urutan) — sama seperti di show()
            * ========================= */
            $hasil_lain = DB::table('hasil_pemeriksaan_lain as hpl')
                ->leftJoin('data_pemeriksaan as dp', 'hpl.id_data_pemeriksaan', '=', 'dp.id_data_pemeriksaan')
                ->leftJoin('jenis_pemeriksaan_1 as jp1', 'dp.id_jenis_pemeriksaan_1', '=', 'jp1.id_jenis_pemeriksaan_1')
                ->where('hpl.no_lab', $no_lab)
                ->whereNull('hpl.deleted_at')
                ->select(
                    'hpl.*',
                    'dp.data_pemeriksaan',
                    'dp.kode_pemeriksaan',
                    'dp.satuan as satuan_pemeriksaan',
                    'dp.rujukan as rujukan_pemeriksaan',
                    'dp.metode',
                    'dp.ch',
                    'dp.cl',
                    'dp.urutan as urutan_pemeriksaan',
                    'jp1.nama_pemeriksaan as jenis_pemeriksaan_nama',
                    'jp1.id_jenis_pemeriksaan_1 as jenis_pemeriksaan_id',
                    DB::raw("COALESCE(jp1.nama_pemeriksaan, 'Lainnya') as kelompok_pemeriksaan"),
                    'hpl.created_at'
                )
                ->orderByRaw("
                    CASE
                        WHEN trim(dp.kode_pemeriksaan) ~ '^[0-9]+$' THEN 0
                        ELSE 1
                    END,
                    CASE
                        WHEN trim(dp.kode_pemeriksaan) ~ '^[0-9]+$'
                        THEN trim(dp.kode_pemeriksaan)::integer
                        ELSE NULL
                    END,
                    dp.kode_pemeriksaan
                ")
                ->get();

            $hasil_lain = $hasil_lain->map(function($item) use ($pasien, $lab) {
                if ($item->id_data_pemeriksaan) {
                    $dataPemeriksaan = DataPemeriksaan::with('detailConditions')
                        ->find($item->id_data_pemeriksaan);

                    if ($dataPemeriksaan) {
                        $rujukanData = $dataPemeriksaan->getRujukanByKondisiPasien(
                            $pasien->jenis_kelamin,
                            $lab['umur_hari'] // Menggunakan umur_hari dari kondisi
                        );

                        if ($rujukanData['is_from_detail']) {
                            $item->ch_by_kondisi = $rujukanData['ch'] ?? ($item->ch ?? '-');
                            $item->cl_by_kondisi = $rujukanData['cl'] ?? ($item->cl ?? '-');
                            $item->rujukan_by_kondisi = $rujukanData['rujukan'] ?? ($item->rujukan_pemeriksaan ?? '-');
                            $item->satuan_by_kondisi = $rujukanData['satuan'] ?? ($item->satuan_pemeriksaan ?? '-');
                        } else {
                            $item->ch_by_kondisi = $item->ch ?? '-';
                            $item->cl_by_kondisi = $item->cl ?? '-';
                            $item->rujukan_by_kondisi = $item->rujukan_pemeriksaan ?? '-';
                            $item->satuan_by_kondisi = $item->satuan_pemeriksaan ?? '-';
                        }

                        $item->is_from_detail = $rujukanData['is_from_detail'];
                        $item->detail_condition = $rujukanData['detail_condition'];

                        if ($item->hasil_pengujian && ($item->rujukan_by_kondisi ?? '-') !== '-') {
                            $item->calculated_keterangan = $this->determineKeterangan(
                                $item->hasil_pengujian,
                                $item->rujukan_by_kondisi,
                                $item->ch_by_kondisi,
                                $item->cl_by_kondisi
                            );
                        } else {
                            $item->calculated_keterangan = $item->keterangan ?? '-';
                        }
                    } else {
                        // fallback
                        $item->ch_by_kondisi = $item->ch ?? '-';
                        $item->cl_by_kondisi = $item->cl ?? '-';
                        $item->rujukan_by_kondisi = $item->rujukan_pemeriksaan ?? '-';
                        $item->satuan_by_kondisi = $item->satuan_pemeriksaan ?? '-';
                        $item->is_from_detail = false;
                        $item->calculated_keterangan = $item->keterangan ?? '-';
                    }
                } else {
                    // tidak ada id_data_pemeriksaan
                    $item->ch_by_kondisi = $item->ch ?? '-';
                    $item->cl_by_kondisi = $item->cl ?? '-';
                    $item->rujukan_by_kondisi = $item->rujukan ?? '-';
                    $item->satuan_by_kondisi = $item->satuan_hasil_pengujian ?? '-';
                    $item->is_from_detail = false;
                    $item->calculated_keterangan = $item->keterangan ?? '-';
                }

                return $item;
            });

            /* =========================
            * GROUPING DENGAN URUTAN YANG TEPAT (sama seperti show())
            * ========================= */
            $hasil_lain_grouped = [];
            $jenis_pemeriksaan_order = [];

            foreach ($hasil_lain as $item) {
                $jenis_pemeriksaan = $item->kelompok_pemeriksaan ?? ($item->jenis_pemeriksaan_nama ?: 'Lainnya');

                if (!isset($hasil_lain_grouped[$jenis_pemeriksaan])) {
                    $hasil_lain_grouped[$jenis_pemeriksaan] = [];
                    if (!isset($jenis_pemeriksaan_order[$jenis_pemeriksaan])) {
                        $jenis_pemeriksaan_order[$jenis_pemeriksaan] = $item->created_at;
                    }
                }

                $hasil_lain_grouped[$jenis_pemeriksaan][] = $item;
            }

            /* =========================
            * BUILD DATA UNTUK PENCETAKAN (multi-page)
            * ========================= */
            $max_rows_per_page = 20;
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
                            'rujukan' => $item->rujukan_by_kondisi['rujukan'] ?? ($item->dataPemeriksaan->rujukan ?? '-'),
                            'satuan' => $item->rujukan_by_kondisi['satuan'] ?? ($item->dataPemeriksaan->satuan ?? '-'),
                            'keterangan' => $item->calculated_keterangan ?? ($item->keterangan ?? '-')
                        ];
                    }
                }
            }

            // Kimia
            if ($kimia->count() > 0) {
                $all_data[] = ['type' => 'header', 'title' => 'KIMIA'];
                foreach ($kimia as $item) {
                    if ($item) {
                        $all_data[] = [
                            'type' => 'row',
                            'category' => 'kimia',
                            'data_pemeriksaan' => $item->analysis ?? ($item->dataPemeriksaan->data_pemeriksaan ?? ''),
                            'hasil_pengujian' => $item->hasil_pengujian ?? '',
                            'rujukan' => $item->rujukan_by_kondisi['rujukan'] ?? ($item->dataPemeriksaan->rujukan ?? '-'),
                            'satuan' => $item->rujukan_by_kondisi['satuan'] ?? ($item->dataPemeriksaan->satuan ?? '-'),
                            'keterangan' => $item->calculated_keterangan ?? ($item->keterangan ?? '-')
                        ];
                    }
                }
            }

            // Hasil Lain (grouped)
            if (!empty($hasil_lain_grouped)) {
                foreach ($hasil_lain_grouped as $jenis => $items) {
                    if (count($items) > 0) {
                        $all_data[] = ['type' => 'header', 'title' => strtoupper($jenis)];
                        foreach ($items as $item) {
                            $all_data[] = [
                                'type' => 'row',
                                'category' => 'hasil_lain',
                                'data_pemeriksaan' => $item->data_pemeriksaan ?? '',
                                'hasil_pengujian' => $item->hasil_pengujian ?? '',
                                'rujukan' => $item->rujukan_by_kondisi ?? ($item->rujukan_pemeriksaan ?? '-'),
                                'satuan' => $item->satuan_by_kondisi ?? ($item->satuan_pemeriksaan ?? '-'),
                                'keterangan' => $item->calculated_keterangan ?? ($item->keterangan ?? '-'),
                            ];
                        }
                    }
                }
            }

            // Bagi data ke dalam halaman
            $pages = [];
            $current_page_data = [];
            $current_page_row_count = 0;

            foreach ($all_data as $idx => $item) {
                $item_rows = ($item['type'] == 'header') ? 1 : 1;

                if ($current_page_row_count + $item_rows > $max_rows_per_page && !empty($current_page_data)) {
                    $pages[] = $current_page_data;
                    $current_page_data = [];
                    $current_page_row_count = 0;

                    // Jika item row harus dimulai di halaman baru, tambahkan header terakhir (jika ada)
                    if ($item['type'] == 'row') {
                        // cari header terakhir sebelum index idx
                        $last_header = null;
                        for ($i = $idx; $i >= 0; $i--) {
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

            if (!empty($current_page_data)) {
                $pages[] = $current_page_data;
            }

            $total_pages = count($pages);

            // 8. QR CODE (sama seperti sebelumnya)
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

            // 9. Return view (gabungkan $lab dan data lainnya)
            return view('print', [
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
                'max_rows_per_page' => $max_rows_per_page,
                // Gabungkan semua data dari $lab (yang sudah berisi data dari processLabData() dan condition)
                'umur_hari' => $lab['umur_hari'] ?? null,
                'umur_format' => $lab['umur_format'] ?? null,
                'jenis_kelamin' => $lab['jenis_kelamin'] ?? null,
            ]);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    public function getHtmlContent($no_lab)
    {
        // Ambil data yang sama dengan cetakHasilLab
        $data = $this->getDataForPrint($no_lab);

        return response(view('html-content', $data)->render())
            ->header('Content-Type', 'text/html');
    }

    private function getDataForPrint($no_lab)
    {
        // Method ini mengembalikan data yang sama dengan cetakHasilLab
        // Implementasi disesuaikan dengan kebutuhan
        return $this->cetakHasilLab($no_lab)->getData();
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

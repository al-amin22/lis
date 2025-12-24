<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pasien;
use App\Models\Ruangan;
use App\Models\Kelas;
use App\Models\Dokter;
use App\Models\Pemeriksa;
use App\Models\PemeriksaanKimia;
use App\Models\PemeriksaanHematology;
use Carbon\Carbon;
use App\Jobs\GeneratePdfJob;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\DataPemeriksaan;
use App\Models\HasilPemeriksaanLain;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use DateTime;
use Illuminate\Support\Facades\Http;
use App\Services\LogActivityService;
use App\Models\UjiPemeriksaan;
use App\Models\Penjamin;

class PasienController extends Controller
{
    public function index(Request $request)
    {
        $today     = Carbon::today();
        $todayDmy  = $today->format('ymd'); // contoh: 221225
        $search    = trim($request->get('search'));

        $query = Pasien::query();

        // =========================
        // JIKA SEARCH → LANGSUNG CARI
        // =========================
        if (!empty($search)) {
            $query->where('nomor_registrasi', 'like', "%{$search}%");
        }
        // =========================
        // DEFAULT → HARI INI (PRIORITAS NO REG)
        // =========================
        else {
            $query->where(function ($q) use ($today, $todayDmy) {

                // 1️⃣ PRIORITAS: NOMOR REGISTRASI
                $q->where('nomor_registrasi', 'like', $todayDmy . '%')

                // 2️⃣ FALLBACK: JIKA NO REG KOSONG / TIDAK VALID
                ->orWhere(function ($qq) use ($today) {
                    $qq->whereNull('nomor_registrasi')
                        ->orWhere('nomor_registrasi', '')
                        ->whereDate('updated_at', $today);
                });
            });
        }

        $pasiens = $query
            ->orderByDesc('nomor_registrasi') // 🔥 hari & urutan REG
            ->orderByDesc('updated_at')
            ->paginate(50)
            ->withQueryString();

        // =========================
        // STATISTIK (LOGIKA SAMA)
        // =========================
        $stat = Pasien::query();

        if (!empty($search)) {
            $stat->where('nomor_registrasi', 'like', "%{$search}%");
        } else {
            $stat->where(function ($q) use ($today, $todayDmy) {

                $q->where('nomor_registrasi', 'like', $todayDmy . '%')
                ->orWhere(function ($qq) use ($today) {
                    $qq->whereNull('nomor_registrasi')
                        ->orWhere('nomor_registrasi', '')
                        ->whereDate('updated_at', $today);
                });
            });
        }

        $statusOrders  = (clone $stat)->count();
        $statusSelesai = (clone $stat)->whereNotNull('id_pemeriksa')->count();
        $statusProses  = (clone $stat)->whereNull('id_pemeriksa')->count();

        // =========================
        // LOG
        // =========================
        LogActivityService::log(
            action: 'READ',
            module: 'Pasien',
            description: 'User mengakses pasien berdasarkan tanggal nomor registrasi'
        );

        return view('index', compact(
            'pasiens',
            'statusOrders',
            'statusSelesai',
            'statusProses',
            'search'
        ));
    }

    public function search(Request $request)
    {
        // =========================
        // TANGGAL (WAJIB & TIDAK BOLEH RESET)
        // =========================
        $date = $request->filled('search_date')
            ? Carbon::parse($request->search_date)
            : Carbon::today();

        $dmyDate  = $date->format('ymd');
        $dateOnly = $date->toDateString();

        $query = Pasien::query();

        // =========================
        // FILTER TANGGAL (WAJIB)
        // =========================
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
        // KEYWORD (OPSIONAL, TIDAK RESET TANGGAL)
        // =========================
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

        // =========================
        // DATA
        // =========================
        $pasiens = $query
            ->orderByDesc('nomor_registrasi')
            ->orderByDesc('updated_at')
            ->paginate(50)
            ->withQueryString();

        // =========================
        // STATISTIK (PAKAI TANGGAL SAJA)
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

        $statusOrders  = (clone $stat)->count();
        $statusSelesai = (clone $stat)->whereNotNull('id_pemeriksa')->count();
        $statusProses  = (clone $stat)->whereNull('id_pemeriksa')->count();

        return view('index', compact(
            'pasiens',
            'statusOrders',
            'statusSelesai',
            'statusProses'
        ));
    }

    public function create()
    {
        // =====================
        // RM (TETAP)
        // =====================
        $lastPasien = Pasien::whereRaw("rm_pasien ~ '^RM[0-9]+$'")
            ->orderByRaw("CAST(SUBSTRING(rm_pasien FROM 3) AS INTEGER) DESC")
            ->first();

        $nextNumber = $lastPasien
            ? ((int) preg_replace('/[^0-9]/', '', $lastPasien->rm_pasien) + 1)
            : 1;

        $nextRm = 'RM' . str_pad($nextNumber, 7, '0', STR_PAD_LEFT);

        // =====================
        // NO LAB (FORMAT TANGGAL)
        // =====================
        $todayPrefix = Carbon::now()->format('ymd'); // contoh: 231225

        // Cari no_lab terakhir HARI INI
        $lastLabToday = Pasien::where('no_lab', 'like', $todayPrefix . '%')
            ->orderBy('no_lab', 'desc')
            ->first();

        if ($lastLabToday) {
            // Ambil 6 digit terakhir
            $lastSequence = (int) substr($lastLabToday->no_lab, -6);
            $nextSequence = $lastSequence + 1;
        } else {
            // Jika belum ada data hari ini
            $nextSequence = 1;
        }

        $nextLabNumber = $todayPrefix . str_pad($nextSequence, 6, '0', STR_PAD_LEFT);

        // =====================
        // NOTA (TETAP)
        // =====================
        $lastNota = Pasien::whereRaw("nota ~ '^NOTA[0-9]+$'")
            ->orderByRaw("CAST(SUBSTRING(nota FROM 5) AS INTEGER) DESC")
            ->first();

        $nextNotaNumberInt = $lastNota
            ? ((int) preg_replace('/[^0-9]/', '', $lastNota->nota) + 1)
            : 1;

        $nextNota = 'NOTA' . str_pad($nextNotaNumberInt, 7, '0', STR_PAD_LEFT);

        // =====================
        // DROPDOWN
        // =====================
        $ruangan = Ruangan::all();
        $kelas = Kelas::all();
        $dokter = Dokter::all();
        $pemeriksa = Pemeriksa::all();
        $penjamin = Penjamin::all();


        LogActivityService::log(
            action: 'READ',
            module: 'Pasien',
            description: 'Membuka form tambah pasien'
        );

        return view('tambahdata', compact(
            'nextRm',
            'nextLabNumber',
            'nextNota',
            'ruangan',
            'kelas',
            'dokter',
            'pemeriksa',
            'penjamin'
        ));
    }


   public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'no_lab'                => 'nullable|string|max:50|unique:pasien,no_lab',
                'rm_pasien'             => 'nullable|string|max:50',
                'nota'                  => 'nullable|string|max:50',
                'tgl_pendaftaran'       => 'nullable|date',
                'nama_pasien'           => 'required|string|max:255',
                'tgl_lahir'             => 'nullable|date',
                'umur'                  => 'nullable|string|max:50',
                'jenis_kelamin'         => 'required|string|max:50',
                'alamat'                => 'nullable|string|max:255',
                'id_ruangan'            => 'nullable|string|max:50',
                'id_kelas'              => 'nullable|string|max:50',
                'ket_klinik'            => 'nullable|string|max:255',
                'catatan'               => 'nullable|string|max:255',
                'id_dokter'             => 'nullable|string|max:50',
                'id_pemeriksa'          => 'nullable|string|max:50',
                'tgl_ambil_sample'      => 'nullable|date',
                'no_telepon'            => 'nullable|string|max:20',
                'pengirim'              => 'nullable|string|max:100',
                'penjamin'              => 'nullable|string|max:100',
                'nomor_registrasi'      => 'nullable|string|max:50',
            ]);

            // =========================
            // GENERATE NO LAB JIKA KOSONG
            // =========================
            if (empty($validated['no_lab'])) {
                $lastLab = Pasien::orderBy('no_lab', 'desc')->first();
                $lastLabNumber = $lastLab
                    ? (int) preg_replace('/[^0-9]/', '', $lastLab->no_lab)
                    : 0;

                $validated['no_lab'] = 'LAB' . str_pad($lastLabNumber + 1, 4, '0', STR_PAD_LEFT);
            }

            // =========================
            // SET NOMOR REGISTRASI = NO LAB
            // =========================
            $validated['nomor_registrasi'] = $validated['no_lab'];

            // =========================
            // SIMPAN
            // =========================
            $pasien = Pasien::create($validated);

            LogActivityService::log(
                action: 'CREATE',
                module: 'Pasien',
                description: 'Menambahkan data pasien baru | RM: ' . $pasien->rm_pasien . ' | No Lab: ' . $pasien->no_lab,
                oldData: null,
                newData: $pasien->toArray()
            );

            return redirect()->route('pasien.index')
                ->with('success', 'Data Pasien Berhasil Disimpan.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage())
                ->withInput();
        }
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


    /**
     * Display the specified resource.
     */
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
            ->join('data_pemeriksaan as dp', 'pemeriksaan_kimia.id_data_pemeriksaan', '=', 'dp.id_data_pemeriksaan')
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
                'dp.ch', // TAMBAHKAN INI
                'dp.cl',
                'jp1.nama_pemeriksaan as jenis_pemeriksaan_nama',
                'jp1.id_jenis_pemeriksaan_1 as jenis_pemeriksaan_id'
            )
            ->orderBy('dp.urutan', 'asc') // 🔥 FIX
            ->get();

        /* =========================
        * GROUPING (GAYA ASLI – TIDAK DIUBAH)
        * ========================= */
        $hasil_lain_grouped = [];
        foreach ($hasil_lain as $item) {
            $jenis_pemeriksaan = $item->jenis_pemeriksaan_nama ?? 'Lainnya';

            if (!isset($hasil_lain_grouped[$jenis_pemeriksaan])) {
                $hasil_lain_grouped[$jenis_pemeriksaan] = [];
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
            'data' => $data['umur_format'],
        ]);
    }

    // Di HasilLabController.php - updateFieldAjax method
    public function updateFieldAjax(Request $request)
    {
        try {
            $request->validate([
                'type' => 'required|in:hematology,kimia,hasil_lain',
                'id' => 'required',
                'field' => 'required|in:hasil_pengujian,keterangan',
                'value' => 'nullable|string|max:255',
                'keterangan' => 'required|in:H,L,-,CH,CL' // Wajib ada!
            ]);

            $type = $request->type;
            $id = $request->id;
            $field = $request->field;
            $value = $request->value;
            $keteranganFromClient = $request->keterangan; // Ini yang akan disimpan

            // Set timezone ke WIB
            $now = now()->timezone('Asia/Jakarta');

            // Get model based on type
            if ($type === 'hematology') {
                $model = PemeriksaanHematology::with('dataPemeriksaan')->findOrFail($id);
            } elseif ($type === 'kimia') {
                $model = PemeriksaanKimia::findOrFail($id);
            } else {
                $model = HasilPemeriksaanLain::with('dataPemeriksaan')->findOrFail($id);
            }

            /* =======================
            * AUDIT: ambil data SEBELUM perubahan
            * ======================= */
            $auditOldData = [
                'hasil_pengujian' => $model->hasil_pengujian,
                'keterangan'      => $model->keterangan,
            ];

            // Update field
            if ($field === 'hasil_pengujian') {
                $model->hasil_pengujian = $value;

                // SELALU GUNAKAN KETERANGAN DARI CLIENT
                $model->keterangan = $keteranganFromClient;

                // Jangan hitung ulang di server, gunakan yang dari client
                $source = 'client-keterangan';

            } else {
                // Update keterangan langsung (field = 'keterangan')
                $model->keterangan = $value;
                $source = 'direct-update';
            }

            // Simpan ke database
            $model->save();

            /* =======================
            * AUDIT: ambil data SETELAH perubahan
            * ======================= */
            $auditNewData = [
                'hasil_pengujian' => $model->hasil_pengujian,
                'keterangan'      => $model->keterangan, // Ini CH/CL/H/L/-
            ];

            /* =======================
            * AUDIT: simpan ke log_activities
            * ======================= */
            LogActivityService::log(
                action: 'UPDATE',
                module: 'Hasil Lab',
                description: sprintf(
                    'Update %s | ID: %s | Field: %s | Keterangan: %s | No Lab: %s',
                    ucfirst($type),
                    $id,
                    $field,
                    $model->keterangan, // Tampilkan keterangan di log
                    $model->no_lab ?? '-'
                ),
                oldData: $auditOldData,
                newData: $auditNewData
            );

            // Update pasien timestamp
            if ($model->no_lab) {
                Pasien::where('no_lab', $model->no_lab)->update(['updated_at' => $now]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diperbarui',
                'data' => [
                    'hasil_pengujian' => $model->hasil_pengujian,
                    'keterangan' => $model->keterangan, // Pastikan CH/CL/H/L/- dikembalikan
                    'updated_at' => $now->format('d/m/Y H:i:s'),
                    'source' => $source,
                    'note' => 'Keterangan dari client langsung disimpan ke database'
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'error_details' => $e->getTraceAsString()
            ], 500);
        }
    }

    public function getPemeriksa()
    {
        $pemeriksa = Pemeriksa::orderBy('nama_pemeriksa', 'asc')
            ->get(['id_pemeriksa', 'nama_pemeriksa', 'alamat', 'no_telp']);

        LogActivityService::log(
            action: 'READ',
            module: 'Pemeriksa',
            description: 'Mengambil daftar pemeriksa untuk keperluan dropdown/select2'
        );

        return response()->json($pemeriksa);
    }


    public function updateDataValidator(Request $request, $no_lab)
    {
        try {
            $request->validate([
                'id_pemeriksa' => 'required|exists:pemeriksa,id_pemeriksa'
            ]);

            $pasien = Pasien::findOrFail($no_lab);

            /* =======================
            * SIMPAN DATA LAMA (AUDIT)
            * ======================= */
            $oldData = [
                'id_pemeriksa' => $pasien->id_pemeriksa,
                'nama_pemeriksa' => optional($pasien->pemeriksa)->nama_pemeriksa
            ];

            // Update pemeriksa
            $now = now()->timezone('Asia/Jakarta');
            $pasien->updated_at = $now;
            $pasien->waktu_validasi = $now;
            $pasien->id_pemeriksa = $request->id_pemeriksa;
            $pasien->save();

            // Reload relasi
            $pasien->load('pemeriksa');

            /* =======================
            * SIMPAN DATA BARU (AUDIT)
            * ======================= */
            $newData = [
                'id_pemeriksa'   => $pasien->id_pemeriksa,
                'nama_pemeriksa' => optional($pasien->pemeriksa)->nama_pemeriksa
            ];

            /* =======================
            * LOG AKTIVITAS
            * ======================= */
            LogActivityService::log(
                action: 'UPDATE',
                module: 'Pasien',
                description: 'Update validator pemeriksa pasien No LAB: ' . $no_lab,
                oldData: $oldData,
                newData: $newData
            );

            return response()->json([
                'success' => true,
                'message' => 'Validator berhasil diperbarui',
                'pemeriksa' => [
                    'id_pemeriksa' => $pasien->pemeriksa->id_pemeriksa,
                    'nama_pemeriksa' => $pasien->pemeriksa->nama_pemeriksa,
                ],
                'validated_at' => Carbon::parse($pasien->updated_at)->format('d/m/Y H:i')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    // HasilLainController.php
    public function getPemeriksaanByKode(Request $request)
    {
        try {
            $kodePemeriksaan = $request->get('id_data_pemeriksaan');

            $pemeriksaan = DataPemeriksaan::where('id_data_pemeriksaan', $kodePemeriksaan)
                ->select('id_data_pemeriksaan', 'data_pemeriksaan', 'satuan as satuan', 'rujukan', 'metode')
                ->first();

            if ($pemeriksaan) {
                return response()->json([
                    'success' => true,
                    'data' => $pemeriksaan
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }



    public function updateDataPasien(Request $request, $no_lab)
    {
        $request->validate([
            'id_pemeriksa' => 'required|exists:pemeriksa,id_pemeriksa',
        ]);

        $pasien = Pasien::findOrFail($no_lab);

        /* =======================
        * DATA LAMA (AUDIT)
        * ======================= */
        $oldData = [
            'id_pemeriksa' => $pasien->id_pemeriksa,
            'nama_pemeriksa' => optional($pasien->pemeriksa)->nama_pemeriksa,
        ];

        // Update data pasien (WIB)
        $now = now()->timezone('Asia/Jakarta');

        $pasien->update([
            'id_pemeriksa' => $request->id_pemeriksa,
            'updated_at'   => $now
        ]);

        // Reload relasi pemeriksa (opsional tapi bagus untuk audit)
        $pasien->load('pemeriksa');

        /* =======================
        * DATA BARU (AUDIT)
        * ======================= */
        $newData = [
            'id_pemeriksa'   => $pasien->id_pemeriksa,
            'nama_pemeriksa' => optional($pasien->pemeriksa)->nama_pemeriksa
        ];

        /* =======================
        * LOG AKTIVITAS
        * ======================= */
        LogActivityService::log(
            action: 'UPDATE',
            module: 'Pasien',
            description: 'Update pemeriksa pasien No LAB: ' . $no_lab,
            oldData: $oldData,
            newData: $newData
        );

        return response()->json([
            'success' => true,
            'message' => 'Data pemeriksa berhasil disimpan',
            'updated_at' => Carbon::parse($pasien->updated_at)->format('d/m/Y H:i')
        ]);
    }
    // Tambahkan method untuk get data pemeriksaan
    public function getDataPemeriksaan(Request $request)
    {
        $search = $request->get('search', '');

        $data = DataPemeriksaan::where('data_pemeriksaan', 'ilike', "%{$search}%")
            ->orWhere('lis', 'ilike', "%{$search}%")
            ->orWhere('id_data_pemeriksaan', 'ilike', "%{$search}%")
            ->limit(20)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id_data_pemeriksaan,
                    'text' => $item->data_pemeriksaan . ' (' . $item->lis . ')',
                    'data_pemeriksaan' => $item->data_pemeriksaan,
                    'satuan' => $item->satuan,
                    'rujukan' => $item->rujukan,
                    'metode' => $item->metode
                ];
            });

        /* =======================
        * LOG AKTIVITAS (READ)
        * ======================= */
        LogActivityService::log(
            action: 'READ',
            module: 'Data Pemeriksaan',
            description: $search
                ? 'Cari data pemeriksaan dengan keyword: ' . $search
                : 'Mengambil daftar data pemeriksaan'
        );

        return response()->json($data);
    }

    public function hapusHasilLain(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|exists:hasil_pemeriksaan_lain,id_hasil_lain'
            ]);

            $hasilLain = HasilPemeriksaanLain::find($request->id);
            $no_lab = $hasilLain->no_lab;

             $oldData = [
                'id_hasil_lain'   => $hasilLain->id_hasil_lain,
                'no_lab'          => $hasilLain->no_lab,
                'hasil_pengujian' => $hasilLain->hasil_pengujian,
                'keterangan'      => $hasilLain->keterangan,
            ];

            $hasilLain->delete();

            // Update timestamp pasien
            Pasien::where('no_lab', $no_lab)->update(['updated_at' => now()]);

            LogActivityService::log(
                action: 'DELETE',
                module: 'Hasil Pemeriksaan Lain',
                description: 'Hapus hasil pemeriksaan lain No LAB: ' . $no_lab,
                oldData: $oldData,
                newData: null
            );

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus: ' . $e->getMessage()
            ], 500);
        }
    }

    // Update method update() di HasilLabController
    public function update(Request $request, $no_lab)
    {
        try {
            DB::beginTransaction();

            $pasien = Pasien::where('no_lab', $no_lab)->firstOrFail();

            $oldData = [
                'hematology' => PemeriksaanHematology::where('no_lab', $no_lab)->get()->toArray(),
                'kimia'      => PemeriksaanKimia::where('no_lab', $no_lab)->get()->toArray(),
                'hasil_lain' => HasilPemeriksaanLain::where('no_lab', $no_lab)->get()->toArray(),
            ];

            // ================== HEMATOLOGY ==================
            if ($request->has('hematology')) {
                foreach ($request->hematology as $data) {
                    if (isset($data['id'])) {
                        $hematology = PemeriksaanHematology::with('dataPemeriksaan')->find($data['id']);
                        if ($hematology) {
                            $hasChanges = false;

                            if (isset($data['hasil_pengujian'])) {
                                $hematology->hasil_pengujian = $data['hasil_pengujian'];
                                $hasChanges = true;

                                if ($hematology->dataPemeriksaan && $hematology->dataPemeriksaan->rujukan) {
                                    $hematology->keterangan = $this->determineKeterangan(
                                        $data['hasil_pengujian'],
                                        $hematology->dataPemeriksaan->rujukan
                                    );
                                }
                            }

                            if (isset($data['keterangan'])) {
                                $hematology->keterangan = $data['keterangan'];
                                $hasChanges = true;
                            }

                            if ($hasChanges) {
                                $hematology->save();
                            }
                        }
                    } else {
                        if (!empty($data['jenis_pengujian'])) {
                            $jenis_pengujian = trim($data['jenis_pengujian']);

                            $existing = PemeriksaanHematology::where('no_lab', $no_lab)
                                ->where('jenis_pengujian', $jenis_pengujian)
                                ->first();

                            if ($existing) {
                                $hasChanges = false;

                                if (isset($data['hasil_pengujian'])) {
                                    $existing->hasil_pengujian = $data['hasil_pengujian'];
                                    $hasChanges = true;

                                    $dp = DataPemeriksaan::where('lis', 'ilike', '%' . $jenis_pengujian . '%')->first();
                                    if ($dp && $dp->rujukan) {
                                        $existing->keterangan = $this->determineKeterangan(
                                            $data['hasil_pengujian'],
                                            $dp->rujukan
                                        );
                                    }
                                }

                                if (isset($data['keterangan'])) {
                                    $existing->keterangan = $data['keterangan'];
                                    $hasChanges = true;
                                }

                                if ($hasChanges) {
                                    $existing->save();
                                }
                            } else {
                                PemeriksaanHematology::create([
                                    'no_lab' => $no_lab,
                                    'jenis_pengujian' => $jenis_pengujian,
                                    'hasil_pengujian' => $data['hasil_pengujian'] ?? null,
                                    'keterangan' => $data['keterangan'] ?? null,
                                ]);
                            }
                        }
                    }
                }
            }

            // ================== KIMIA ==================
            if ($request->has('kimia')) {
                foreach ($request->kimia as $data) {
                    if (isset($data['id'])) {
                        $kimia = PemeriksaanKimia::find($data['id']);
                        if ($kimia) {
                            $hasChanges = false;

                            if (isset($data['hasil_pengujian'])) {
                                $kimia->hasil_pengujian = $data['hasil_pengujian'];
                                $hasChanges = true;

                                if ($kimia->rujukan) {
                                    $kimia->keterangan = $this->determineKeterangan(
                                        $data['hasil_pengujian'],
                                        $kimia->rujukan
                                    );
                                }
                            }

                            if (isset($data['keterangan'])) {
                                $kimia->keterangan = $data['keterangan'];
                                $hasChanges = true;
                            }

                            if ($hasChanges) {
                                $kimia->save();
                            }
                        }
                    }
                }
            }

            // ================== HASIL LAIN ==================
            if ($request->has('hasil_lain')) {
                foreach ($request->hasil_lain as $data) {
                    $id = $data['id'] ?? null;

                    if ($id) {
                        $hasilLain = HasilPemeriksaanLain::with('dataPemeriksaan')->find($id);
                        if ($hasilLain && isset($data['hasil_pengujian'])) {
                            $hasilLain->hasil_pengujian = $data['hasil_pengujian'];

                            if ($hasilLain->dataPemeriksaan && $hasilLain->dataPemeriksaan->rujukan) {
                                $hasilLain->keterangan = $this->determineKeterangan(
                                    $data['hasil_pengujian'],
                                    $hasilLain->dataPemeriksaan->rujukan
                                );
                            }

                            $hasilLain->save();
                        }
                    } elseif (!empty($data['id_data_pemeriksaan']) && !empty($data['hasil_pengujian'])) {
                        $dp = DataPemeriksaan::find($data['id_data_pemeriksaan']);

                        $keterangan = '-';
                        if ($dp && $dp->rujukan) {
                            $keterangan = $this->determineKeterangan($data['hasil_pengujian'], $dp->rujukan);
                        }

                        HasilPemeriksaanLain::create([
                            'no_lab' => $no_lab,
                            'jenis_pengujian' => $data['jenis_pengujian'] ?? $dp->data_pemeriksaan ?? '',
                            'hasil_pengujian' => $data['hasil_pengujian'],
                            'satuan_hasil_pengujian' => $data['satuan'] ?? $dp->satuan ?? '',
                            'rujukan' => $data['rujukan'] ?? $dp->rujukan ?? '',
                            'keterangan' => $keterangan,
                            'id_data_pemeriksaan' => $data['id_data_pemeriksaan'],
                            'id_jenis_pemeriksaan' => $dp->id_jenis_pemeriksaan_1 ?? null,
                        ]);
                    }
                }
            }

            $pasien->updated_at = now()->timezone('Asia/Jakarta');
            $pasien->save();

            $newData = [
                'hematology' => PemeriksaanHematology::where('no_lab', $no_lab)->get()->toArray(),
                'kimia'      => PemeriksaanKimia::where('no_lab', $no_lab)->get()->toArray(),
                'hasil_lain' => HasilPemeriksaanLain::where('no_lab', $no_lab)->get()->toArray(),
            ];

            LogActivityService::log(
                action: 'UPDATE',
                module: 'Pemeriksaan Laboratorium',
                description: 'Update hasil pemeriksaan No LAB: ' . $no_lab,
                oldData: $oldData,
                newData: $newData
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan',
                'updated_at' => $pasien->updated_at->format('d/m/Y H:i:s')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan: ' . $e->getMessage()
            ], 500);
        }
    }


    public function edit($no_lab)
    {
        // Ambil data pasien berdasarkan no_lab
        $pasien = Pasien::where('no_lab', $no_lab)->firstOrFail();

        LogActivityService::log(
            action: 'READ',
            module: 'Pasien',
            description: 'Mengakses halaman edit data pasien No LAB: ' . $no_lab
        );

        return view('editdata', compact('pasien'));
    }

    // public function update(Request $request, $no_lab)
    // {
    //     try {
    //         $pasien = Pasien::where('no_lab', $no_lab)->firstOrFail();

    //         // Validasi request
    //         $request->validate([
    //             'rm_pasien'             => 'nullable|string|max:50',
    //             'no_lab'                => 'nullable|string|max:50|unique:pasien,no_lab,' . $pasien->id,
    //             'nota'                  => 'nullable|string|max:50',
    //             'tanggal_registrasi'    => 'required|date',
    //             'nama_pasien'           => 'required|string|max:255',
    //             'tanggal_lahir'         => 'nullable|date',
    //             'umur'                  => 'nullable|integer|min:0',
    //             'jenis_kelamin'         => 'required|string|in:Laki-Laki,Perempuan',
    //             'alamat'                => 'nullable|string|max:255',
    //             'id_ruangan'            => 'nullable|string|max:50',
    //             'id_kelas'              => 'nullable|string|max:50',
    //             'keterangan_klinik'     => 'nullable|string|max:255',
    //             'catatan'               => 'nullable|string|max:255',
    //             'id_dokter'             => 'nullable|string|max:50',
    //             'id_pemeriksa'          => 'nullable|string|max:50',
    //             'tanggal_ambil_sampel'  => 'nullable|date',
    //         ]);

    //         // Update data pasien
    //         $pasien->update([
    //             'rm_pasien' => $request->rm_pasien,
    //             'no_lab' => $request->no_lab,
    //             'nota' => $request->nota,
    //             'tanggal_registrasi' => $request->tanggal_registrasi,
    //             'nama_pasien' => $request->nama_pasien,
    //             'tanggal_lahir' => $request->tanggal_lahir,
    //             'umur' => $request->umur,
    //             'jenis_kelamin' => $request->jenis_kelamin,
    //             'alamat' => $request->alamat,
    //             'id_ruangan' => $request->id_ruangan,
    //             'id_kelas' => $request->id_kelas,
    //             'keterangan_klinik' => $request->keterangan_klinik,
    //             'catatan' => $request->catatan,
    //             'id_dokter' => $request->id_dokter,
    //             'id_pemeriksa' => $request->id_pemeriksa,
    //             'tanggal_ambil_sampel' => $request->tanggal_ambil_sampel,
    //             'deleted_at' => null,
    //         ]);

    //         return redirect()->route('pasien.index')->with('success', 'Data pasien berhasil diperbarui.');
    //     } catch (\Illuminate\Validation\ValidationException $e) {
    //         // Jika validasi gagal
    //         return redirect()->back()->withErrors($e->validator)->withInput();
    //     } catch (\Exception $e) {
    //         // Jika ada error lain
    //         return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
    //     }
    // }

    public function destroy($no_lab)
    {
        try {
            // Ambil pasien
            $pasien = Pasien::where('no_lab', $no_lab)->firstOrFail();

            // =======================
            // DATA LAMA (AUDIT)
            // =======================
            $oldData = [
                'pasien'     => $pasien->toArray(),
                'hematology' => $pasien->hematology()->get()->toArray(),
                'kimia'      => $pasien->kimia()->get()->toArray(),
            ];

            // Hapus data relasi hematology
            $pasien->hematology()->delete();

            // Hapus data relasi kimia
            $pasien->kimia()->delete();

            // Hapus data pasien
            $pasien->delete();

            // =======================
            // LOG AKTIVITAS
            // =======================
            LogActivityService::log(
                action: 'DELETE',
                module: 'Pasien',
                description: 'Hapus pasien dan seluruh hasil lab No LAB: ' . $no_lab,
                oldData: $oldData,
                newData: null
            );

            return redirect()
                ->route('pasien.index')
                ->with('success', 'Data pasien beserta hasil pengujian lab berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()
                ->route('pasien.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Menampilkan history berdasarkan RM Pasien
    public function history($rm_pasien = null)
    {
        try {
            if (is_null($rm_pasien) || empty($rm_pasien)) {
                LogActivityService::log(
                    action: 'READ',
                    module: 'Riwayat Pasien',
                    description: 'Akses halaman riwayat pasien tanpa RM'
                );

                $histories = collect();
                $latestPatient = null;

                return view('history', compact('histories', 'latestPatient'));
            }

            $histories = Pasien::where('rm_pasien', $rm_pasien)
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            $latestPatient = Pasien::where('rm_pasien', $rm_pasien)
                ->orderBy('created_at', 'desc')
                ->first();

            LogActivityService::log(
                action: 'READ',
                module: 'Riwayat Pasien',
                description: 'Melihat riwayat pasien RM: ' . $rm_pasien
            );

            if (!$latestPatient) {
                $histories = collect();
            }

            return view('history', compact('histories', 'latestPatient'));
        } catch (\Exception $e) {
            return redirect()->route('pasien.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    public function printPdf($no_lab)
    {
        LogActivityService::log(
            action: 'PRINT',
            module: 'Laporan Laboratorium',
            description: 'Generate & cetak PDF hasil lab No LAB: ' . $no_lab
        );
        // Jalankan job di background
        GeneratePdfJob::dispatch($no_lab);

        // Tampilkan halaman loader
        return view('user.print', [
            'no_lab' => $no_lab
        ]);
    }

    public function checkFile($no_lab)
    {
        LogActivityService::log(
            action: 'READ',
            module: 'Laporan Laboratorium',
            description: 'Cek ketersediaan file PDF hasil lab No LAB: ' . $no_lab
        );

        GeneratePdfJob::dispatch($no_lab);
        sleep(5);

        $folder = public_path("file");
        $pattern = "{$folder}/hasil_pengujian_{$no_lab}*.pdf";

        $files = glob($pattern);

        if (empty($files)) {
            return response()->json(['ready' => false]);
        }

        $latest = collect($files)->sortByDesc(function ($file) {
            $name = pathinfo($file, PATHINFO_FILENAME);

            if (preg_match('/_(\d+)$/', $name, $m)) {
                return intval($m[1]);
            }

            return 0;
        })->first();

        return response()->json([
            'ready' => true,
            'file'  => asset("file/" . basename($latest))
        ]);
    }

    public function downloadLabPDF($no_lab)
    {
        try {
            LogActivityService::log(
                action: 'DOWNLOAD_PDF',
                module: 'Hasil Laboratorium',
                description: 'Download PDF hasil lab No LAB: ' . $no_lab
            );

            // ============================
            // 1. Ambil data pasien + relasi
            // ============================
            $pasien = Pasien::with([
                'hematology.dataPemeriksaan.lisMappings',
                'kimia.dataPemeriksaan',
                'hasilPemeriksaanLain.dataPemeriksaan.lisMappings',
                'pemeriksa',
                'ruangan'
            ])->findOrFail($no_lab);

            // ============================
            // 2. Ambil data lengkap pasien dari processLabData()
            // ============================
            $lab = $this->processLabData($pasien);

            // ============================
            // 3. Urutan Hematology (LIS)
            // ============================
            $urutan = [
                'WBC',
                'NEU%',
                'LYM%',
                'MON%',
                'EOS%',
                'BAS%',
                'RBC',
                'HGB',
                'HCT',
                'MCV',
                'MCH',
                'MCHC',
                'RDW-CV',
                'RDW-SD',
                'PLT',
                'MPV',
                'PDW',
                'PCT'
            ];

            $hasil = $pasien->hematology()->with('dataPemeriksaan.lisMappings')->get();

            // Buat index berdasarkan LIS
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

            // Susun sesuai urutan
            $hematology_fix = [];
            foreach ($urutan as $lis) {
                $key = strtolower(trim($lis));
                if (isset($lisIndex[$key])) {
                    $hematology_fix[] = $lisIndex[$key];
                } else {
                    // partial match
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

            // ============================
            // 4. Kimia
            // ============================
            $kimia = $pasien->kimia()
                ->with('dataPemeriksaan')
                ->orderBy('id_pemeriksaan_kimia', 'asc')
                ->get();

            // ============================
            // 5. Hasil Lain (JOIN MANUAL)
            // ============================
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
                    'jp1.nama_pemeriksaan as jenis_pemeriksaan_nama',
                    'jp1.id_jenis_pemeriksaan_1 as jenis_pemeriksaan_id'
                )
                ->orderBy('hpl.id_hasil_lain', 'asc')
                ->get();

            // ============================
            // 6. Grouping hasil lain
            // ============================
            $hasil_lain_grouped = [];
            foreach ($hasil_lain as $item) {
                $jenis = $item->jenis_pemeriksaan_nama ?: 'Lainnya';

                if ($jenis == 'Lainnya' && $item->id_data_pemeriksaan) {
                    $cek = DB::table('data_pemeriksaan as dp')
                        ->join('jenis_pemeriksaan_1 as jp1', 'dp.id_jenis_pemeriksaan_1', '=', 'jp1.id_jenis_pemeriksaan_1')
                        ->where('dp.id_data_pemeriksaan', $item->id_data_pemeriksaan)
                        ->whereNull('dp.deleted_at')
                        ->select('jp1.nama_pemeriksaan')
                        ->first();

                    if ($cek) {
                        $jenis = $cek->nama_pemeriksaan;
                        $item->jenis_pemeriksaan_nama = $cek->nama_pemeriksaan;
                    }
                }

                $hasil_lain_grouped[$jenis][] = $item;
            }

            // ============================
            // 7. QR CODE
            // ============================
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


            // ============================
            // 8. KIRIM DATA KE VIEW PDF
            // ============================
            $data = array_merge([
                'pasien' => $pasien,
                'hematology_fix' => $hematology_fix,
                'kimia' => $kimia,
                'hasil_lain' => $hasil_lain,
                'hasil_lain_grouped' => $hasil_lain_grouped,
                'no_lab' => $no_lab,
                'qrCodePath' => asset("file/qr/qr_$dateFile.png"),
                'today' => $today
            ], $lab);

            // ============================
            // 9. GENERATE PDF LANGSUNG
            // ============================
            $pdf = PDF::loadView('download-pdf', $data)
                ->setPaper('a4', 'portrait')
                ->setOptions([
                    'defaultFont' => 'Arial',
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => true,
                    'dpi' => 150,
                    'margin_top' => 10,
                    'margin_bottom' => 10,
                    'margin_left' => 10,
                    'margin_right' => 10,
                    'enable_css_float' => true,
                    'enable_php' => true,
                ]);

            $fileName = "Hasil-Lab-{$no_lab}-" . date('Y-m-d') . ".pdf";

            return $pdf->download($fileName);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
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
            ])->findOrFail($no_lab);

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
            return view('print', array_merge([
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


    // public function cetakHasilLab($no_lab)
    // {
    //     try {

    //         LogActivityService::log(
    //             action: 'PRINT',
    //             module: 'Hasil Laboratorium',
    //             description: 'Cetak hasil lab No LAB: ' . $no_lab
    //         );

    //         // ============================
    //         // 1. Ambil data pasien + relasi
    //         // ============================
    //         $pasien = Pasien::with([
    //             'hematology.dataPemeriksaan.lisMappings',
    //             'kimia.dataPemeriksaan',
    //             'hasilPemeriksaanLain.dataPemeriksaan.lisMappings',
    //             'pemeriksa',
    //             'ruangan'
    //         ])->findOrFail($no_lab);

    //         // ============================
    //         // 2. Ambil data lengkap pasien dari processLabData()
    //         // ============================
    //         $lab = $this->processLabData($pasien);


    //         // ============================
    //         // 3. Urutan Hematology (LIS)
    //         // ============================
    //         $urutan = [
    //             'WBC',
    //             'NEU%',
    //             'LYM%',
    //             'MON%',
    //             'EOS%',
    //             'BAS%',
    //             'RBC',
    //             'HGB',
    //             'HCT',
    //             'MCV',
    //             'MCH',
    //             'MCHC',
    //             'RDW-CV',
    //             'RDW-SD',
    //             'PLT',
    //             'MPV',
    //             'PDW',
    //             'PCT'
    //         ];

    //         $hasil = $pasien->hematology()->with('dataPemeriksaan.lisMappings')->get();

    //         // Buat index berdasarkan LIS
    //         $lisIndex = [];
    //         foreach ($hasil as $item) {
    //             if ($item->dataPemeriksaan && $item->dataPemeriksaan->lisMappings) {
    //                 foreach ($item->dataPemeriksaan->lisMappings as $mapping) {
    //                     $key = strtolower(trim($mapping->lis));
    //                     if (!isset($lisIndex[$key])) {
    //                         $lisIndex[$key] = $item;
    //                     }
    //                 }
    //             }
    //         }

    //         // Susun sesuai urutan
    //         $hematology_fix = [];
    //         foreach ($urutan as $lis) {
    //             $key = strtolower(trim($lis));
    //             if (isset($lisIndex[$key])) {
    //                 $hematology_fix[] = $lisIndex[$key];
    //             } else {
    //                 // partial match
    //                 $found = null;
    //                 foreach ($lisIndex as $lisKey => $item) {
    //                     if (strpos($lisKey, $key) !== false) {
    //                         $found = $item;
    //                         break;
    //                     }
    //                 }
    //                 $hematology_fix[] = $found;
    //             }
    //         }


    //         // ============================
    //         // 4. Kimia
    //         // ============================
    //         $kimia = $pasien->kimia()
    //             ->with('dataPemeriksaan')
    //             ->orderBy('id_pemeriksaan_kimia', 'asc')
    //             ->get();


    //         // ============================
    //         // 5. Hasil Lain (JOIN MANUAL)
    //         // ============================
    //         $hasil_lain = DB::table('hasil_pemeriksaan_lain as hpl')
    //             ->leftJoin('data_pemeriksaan as dp', 'hpl.id_data_pemeriksaan', '=', 'dp.id_data_pemeriksaan')
    //             ->leftJoin('jenis_pemeriksaan_1 as jp1', 'dp.id_jenis_pemeriksaan_1', '=', 'jp1.id_jenis_pemeriksaan_1')
    //             ->where('hpl.no_lab', $no_lab)
    //             ->whereNull('hpl.deleted_at')
    //             ->select(
    //                 'hpl.*',
    //                 'dp.data_pemeriksaan',
    //                 'dp.satuan as satuan_pemeriksaan',
    //                 'dp.rujukan as rujukan_pemeriksaan',
    //                 'dp.metode',
    //                 'jp1.nama_pemeriksaan as jenis_pemeriksaan_nama',
    //                 'jp1.id_jenis_pemeriksaan_1 as jenis_pemeriksaan_id'
    //             )
    //             ->orderBy('hpl.id_hasil_lain', 'asc')
    //             ->get();


    //         // ============================
    //         // 6. Grouping hasil lain
    //         // ============================
    //         $hasil_lain_grouped = [];
    //         foreach ($hasil_lain as $item) {

    //             $jenis = $item->jenis_pemeriksaan_nama ?: 'Lainnya';

    //             if ($jenis == 'Lainnya' && $item->id_data_pemeriksaan) {
    //                 $cek = DB::table('data_pemeriksaan as dp')
    //                     ->join('jenis_pemeriksaan_1 as jp1', 'dp.id_jenis_pemeriksaan_1', '=', 'jp1.id_jenis_pemeriksaan_1')
    //                     ->where('dp.id_data_pemeriksaan', $item->id_data_pemeriksaan)
    //                     ->whereNull('dp.deleted_at')
    //                     ->select('jp1.nama_pemeriksaan')
    //                     ->first();

    //                 if ($cek) {
    //                     $jenis = $cek->nama_pemeriksaan;
    //                     $item->jenis_pemeriksaan_nama = $cek->nama_pemeriksaan;
    //                 }
    //             }

    //             $hasil_lain_grouped[$jenis][] = $item;
    //         }


    //         // ============================
    //         // 7. QR CODE
    //         // ============================
    //         Carbon::setLocale('id');
    //         $now = Carbon::now('Asia/Jakarta');
    //         $dateFile = $now->format('Y-m-d');

    //         $qrDir = public_path('file/qr');
    //         if (!is_dir($qrDir)) mkdir($qrDir, 0755, true);

    //         $qrPath = "$qrDir/qr_$dateFile.png";

    //         if (!file_exists($qrPath)) {
    //             $qrText = "dr. DONNY KOSTRADI, M.Kes, Sp.PK\n"
    //                 . "MR15712507005085 \n"
    //                 . "Hasil Pemeriksaan Laboratorium RS. Baiturrahim\n"
    //                 . "Jambi, " . $now->translatedFormat('d F Y');

    //             $result = Builder::create()
    //                 ->writer(new PngWriter())
    //                 ->data($qrText)
    //                 ->encoding(new Encoding('UTF-8'))
    //                 ->size(200)
    //                 ->margin(5)
    //                 ->build();

    //             $result->saveToFile($qrPath);
    //         }

    //         $today = $now->translatedFormat('d F Y');


    //         // ============================
    //         // 8. KIRIM DATA KE VIEW
    //         // ============================
    //         return view('print', array_merge([
    //             'pasien' => $pasien,
    //             'hematology_fix' => $hematology_fix,
    //             'kimia' => $kimia,
    //             'hasil_lain' => $hasil_lain,
    //             'hasil_lain_grouped' => $hasil_lain_grouped,
    //             'autoPrint' => true,
    //             'no_lab' => $no_lab,
    //             'qrCodePath' => asset("file/qr/qr_$dateFile.png"),
    //             'today' => $today
    //         ], $lab));
    //     } catch (\Exception $e) {
    //         return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    //     }
    // }

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

    // Fungsi untuk preview
    public function previewHasilLab($no_lab)
    {
        try {
            $pasien = Pasien::where('no_lab', $no_lab)->firstOrFail();
            $data = $this->processLabData($pasien);
            return view('faktur-lab', $data);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // App\Http\Controllers\HasilLabController.php
    public function searchKodePemeriksaanLain(Request $request)
    {
        try {
            $request->validate([
                'search' => 'nullable|string|max:100',
                'jenis_pemeriksaan' => 'nullable|string|max:100',
                'exclude_current' => 'nullable|string|max:50'
            ]);

            $search = $request->search;
            $jenisPemeriksaan = $request->jenis_pemeriksaan;
            $excludeCurrent = $request->exclude_current;

            LogActivityService::log(
                action: 'SEARCH',
                module: 'Data Pemeriksaan Lain',
                description: 'Cari kode pemeriksaan lain dengan search: ' . ($search ?? '-')
                    . ', jenis_pemeriksaan: ' . ($jenisPemeriksaan ?? '-')
                    . ', exclude_current: ' . ($excludeCurrent ?? '-')
            );

            $query = DB::table('data_pemeriksaan as dp')
                ->leftJoin('jenis_pemeriksaan_1 as jp1', 'dp.id_jenis_pemeriksaan_1', '=', 'jp1.id_jenis_pemeriksaan_1')
                ->whereNull('dp.deleted_at')
                ->whereNull('jp1.deleted_at');

            // Filter by jenis pemeriksaan
            if ($jenisPemeriksaan && $jenisPemeriksaan !== '') {
                $query->where('jp1.nama_pemeriksaan', $jenisPemeriksaan);
            }

            // Exclude current kode if exists
            if ($excludeCurrent && $excludeCurrent !== '') {
                $query->where('dp.id_data_pemeriksaan', '!=', $excludeCurrent);
            }

            // Search term
            if ($search && strlen($search) >= 2) {
                $query->where(function ($q) use ($search) {
                    $q->where('dp.id_data_pemeriksaan', 'ILIKE', "%{$search}%")
                        ->orWhere('dp.data_pemeriksaan', 'ILIKE', "%{$search}%")
                        ->orWhere('jp1.nama_pemeriksaan', 'ILIKE', "%{$search}%");
                });
            }

            $results = $query->select(
                'dp.id_data_pemeriksaan',
                'dp.data_pemeriksaan',
                'dp.satuan',
                'dp.rujukan',
                'dp.metode',
                'jp1.nama_pemeriksaan as jenis_pemeriksaan'
            )
                ->orderBy('dp.data_pemeriksaan')
                ->limit(20)
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil ditemukan',
                'data' => $results
            ]);
        } catch (\Exception $e) {
            LogActivityService::log(
                action: 'ERROR',
                module: 'Data Pemeriksaan Lain',
                description: 'Error saat mencari kode pemeriksaan lain: ' . $e->getMessage()
            );
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function storeHasilLain(Request $request)
    {
        try {
            $request->validate([
                'no_lab' => 'required|exists:pasien,no_lab',
                'id_data_pemeriksaan' => 'required|exists:data_pemeriksaan,id_data_pemeriksaan',
                'jenis_pengujian' => 'required|string|max:100',
                'hasil_pengujian' => 'nullable|string|max:100',
                'keterangan' => 'nullable|in:H,L,-',
                'satuan' => 'nullable|string|max:50',
                'rujukan' => 'nullable|string|max:100'
            ]);

            // Cek duplikat
            $existing = HasilPemeriksaanLain::where('no_lab', $request->no_lab)
                ->where('id_data_pemeriksaan', $request->id_data_pemeriksaan)
                ->whereNull('deleted_at')
                ->first();

            if ($existing) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pemeriksaan ini sudah ada untuk pasien ini'
                ], 422);
            }

            // Ambil data dari data_pemeriksaan jika tidak disediakan
            if (!$request->satuan || !$request->rujukan) {
                $dataPemeriksaan = DataPemeriksaan::where('id_data_pemeriksaan', $request->id_data_pemeriksaan)
                    ->whereNull('deleted_at')
                    ->first();

                if (!$dataPemeriksaan) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Data pemeriksaan tidak ditemukan'
                    ], 404);
                }
            }

            // Create new record
            $hasilLain = HasilPemeriksaanLain::create([
                'no_lab' => $request->no_lab,
                'jenis_pengujian' => $request->jenis_pengujian,
                'id_data_pemeriksaan' => $request->id_data_pemeriksaan,
                'hasil_pengujian' => $request->hasil_pengujian,
                'satuan_hasil_pengujian' => $request->satuan ?? $dataPemeriksaan->satuan ?? null,
                'rujukan' => $request->rujukan ?? $dataPemeriksaan->rujukan ?? null,
                'keterangan' => $request->keterangan ?? '-',
                'status_pemeriksaan' => 'selesai'
            ]);

            LogActivityService::log(
                action: 'CREATE',
                module: 'Hasil Pemeriksaan Lain',
                description: 'Tambah hasil pemeriksaan lain ID: ' . $hasilLain->id_hasil_lain . ' untuk No LAB: ' . $request->no_lab
            );

            // Update timestamp pasien
            Pasien::where('no_lab', $request->no_lab)->update(['updated_at' => now()]);
            LogActivityService::log(
                action: 'UPDATE',
                module: 'Pasien',
                description: 'Update timestamp pasien No LAB: ' . $request->no_lab
            );

            return response()->json([
                'success' => true,
                'message' => 'Pemeriksaan berhasil ditambahkan',
                'data' => [
                    'id_hasil_lain' => $hasilLain->id_hasil_lain,
                    'jenis_pengujian' => $hasilLain->jenis_pengujian,
                    'hasil_pengujian' => $hasilLain->hasil_pengujian,
                    'keterangan' => $hasilLain->keterangan,
                    'created_at' => $hasilLain->created_at->format('d/m/Y H:i:s')
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateKodePemeriksaanLain(Request $request, $id)
    {
        try {
            $request->validate([
                'id_data_pemeriksaan' => 'required|exists:data_pemeriksaan,id_data_pemeriksaan',
                'satuan' => 'nullable|string|max:50',
                'rujukan' => 'nullable|string|max:100',
                'jenis_pengujian' => 'nullable|string|max:100'
            ]);

            $hasilLain = HasilPemeriksaanLain::findOrFail($id);

            // Ambil data dari data_pemeriksaan
            $dataPemeriksaan = DataPemeriksaan::where('id_data_pemeriksaan', $request->id_data_pemeriksaan)
                ->whereNull('deleted_at')
                ->first();

            if (!$dataPemeriksaan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data pemeriksaan tidak ditemukan'
                ], 404);
            }

            // Update record
            $hasilLain->update([
                'id_data_pemeriksaan' => $request->id_data_pemeriksaan,
                'jenis_pengujian' => $request->jenis_pengujian ?? $dataPemeriksaan->data_pemeriksaan,
                'satuan_hasil_pengujian' => $request->satuan ?? $dataPemeriksaan->satuan,
                'rujukan' => $request->rujukan ?? $dataPemeriksaan->rujukan
            ]);

            LogActivityService::log(
                action: 'UPDATE',
                module: 'Hasil Pemeriksaan Lain',
                description: 'Update kode pemeriksaan ID: ' . $hasilLain->id_hasil_lain . ' untuk No LAB: ' . $hasilLain->no_lab
            );

            // Jika ada hasil_pengujian, hitung ulang keterangan
            if ($hasilLain->hasil_pengujian && $dataPemeriksaan->rujukan) {
                $hasilLain->keterangan = $this->determineKeterangan(
                    $hasilLain->hasil_pengujian,
                    $dataPemeriksaan->rujukan
                );
                $hasilLain->save();
            }

            LogActivityService::log(
                action: 'UPDATE',
                module: 'Hasil Pemeriksaan Lain',
                description: 'Update keterangan hasil pemeriksaan ID: ' . $hasilLain->id_hasil_lain . ' untuk No LAB: ' . $hasilLain->no_lab
            );

            // Update timestamp pasien
            Pasien::where('no_lab', $hasilLain->no_lab)->update(['updated_at' => now()]);

            LogActivityService::log(
                action: 'UPDATE',
                module: 'Pasien',
                description: 'Update timestamp pasien No LAB: ' . $hasilLain->no_lab
            );

            return response()->json([
                'success' => true,
                'message' => 'Kode pemeriksaan berhasil diperbarui',
                'data' => [
                    'id_hasil_lain' => $hasilLain->id_hasil_lain,
                    'jenis_pengujian' => $hasilLain->jenis_pengujian,
                    'id_data_pemeriksaan' => $hasilLain->id_data_pemeriksaan,
                    'satuan' => $hasilLain->satuan_hasil_pengujian,
                    'rujukan' => $hasilLain->rujukan,
                    'keterangan' => $hasilLain->keterangan,
                    'updated_at' => now()->format('d/m/Y H:i:s')
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroyHasilLain($id)
    {
        try {
            $hasilLain = HasilPemeriksaanLain::findOrFail($id);
            $no_lab = $hasilLain->no_lab;

            // Soft delete
            $hasilLain->deleted_at = now();
            $hasilLain->save();

            LogActivityService::log(
                action: 'DELETE',
                module: 'Hasil Pemeriksaan Lain',
                description: 'Hapus hasil pemeriksaan lain ID: ' . $id . ' untuk No LAB: ' . $no_lab
            );

            // Update timestamp pasien
            Pasien::where('no_lab', $no_lab)->update(['updated_at' => now()]);

            LogActivityService::log(
                action: 'UPDATE',
                module: 'Pasien',
                description: 'Update timestamp pasien No LAB: ' . $no_lab
            );

            return response()->json([
                'success' => true,
                'message' => 'Pemeriksaan berhasil dihapus',
                'data' => [
                    'id_hasil_lain' => $id,
                    'deleted_at' => now()->format('d/m/Y H:i:s')
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroyTabelHasilLain(Request $request)
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'integer|exists:hasil_pemeriksaan_lain,id_hasil_lain'
            ]);

            $ids = $request->ids;

            // Get no_lab before deletion
            $no_lab = HasilPemeriksaanLain::whereIn('id_hasil_lain', $ids)
                ->value('no_lab');

            // Soft delete all
            HasilPemeriksaanLain::whereIn('id_hasil_lain', $ids)
                ->update(['deleted_at' => now()]);

            LogActivityService::log(
                action: 'DELETE',
                module: 'Hasil Pemeriksaan Lain',
                description: 'Hapus multiple hasil pemeriksaan lain IDs: ' . implode(', ', $ids)
            );

            // Update timestamp pasien
            if ($no_lab) {
                Pasien::where('no_lab', $no_lab)->update(['updated_at' => now()]);
            }

            return response()->json([
                'success' => true,
                'message' => count($ids) . ' pemeriksaan berhasil dihapus',
                'data' => [
                    'deleted_count' => count($ids),
                    'deleted_at' => now()->format('d/m/Y H:i:s')
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroyMultiple(Request $request)
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'required|integer|exists:hasil_lain,id_hasil_lain'
            ]);

            DB::beginTransaction();

            $deletedCount = HasilPemeriksaanLain::whereIn('id_hasil_lain', $request->ids)->delete();
            LogActivityService::log(
                action: 'DELETE',
                module: 'Hasil Pemeriksaan Lain',
                description: 'Hapus multiple hasil pemeriksaan lain IDs: ' . implode(', ', $request->ids)
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Berhasil menghapus ' . $deletedCount . ' pemeriksaan',
                'deleted_count' => $deletedCount
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus pemeriksaan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getJenisPemeriksaan()
    {
        try {
            $jenis_pemeriksaan = DB::table('jenis_pemeriksaan_1')
                ->whereNull('deleted_at')
                ->orderBy('nama_pemeriksaan')
                ->get(['id_jenis_pemeriksaan_1', 'nama_pemeriksaan']);

            LogActivityService::log(
                action: 'FETCH',
                module: 'Jenis Pemeriksaan',
                description: 'Ambil data jenis pemeriksaan'
            );

            return response()->json([
                'success' => true,
                'data' => $jenis_pemeriksaan
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    // Di controller Anda
    public function updateRealtime(Request $request)
    {
        $request->validate([
            'no_lab' => 'required',
            'field' => 'required',
            'value' => 'required'
        ]);

        $pasien = Pasien::where('no_lab', $request->no_lab)->first();

        if (!$pasien) {
            return response()->json([
                'success' => false,
                'message' => 'Pasien tidak ditemukan'
            ], 404);
        }

        $oldData = $pasien->toArray();
        $newData = [$request->field => $request->value];

        // Di method updateRealtime, khusus untuk field pengirim
        if ($request->field === 'pengirim') {
            // Log aktivitas khusus untuk update pengirim/dokter
            LogActivityService::log(
                action: 'UPDATE_PENGIRIM',
                module: 'Pasien',
                description: 'Update pengirim/dokter untuk No LAB: ' . $request->no_lab,
                oldData: $oldData,
                newData: $newData
            );
        }

        // Tangani khusus untuk field tgl_lahir
        if ($request->field === 'tgl_lahir') {
            try {
                // Validasi input YYYY-MM-DD
                if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $request->value)) {
                    throw new \Exception('Format harus YYYY-MM-DD');
                }

                // Parse dengan Carbon dan validasi
                $carbonDate = Carbon::createFromFormat('Y-m-d', $request->value);

                // Validasi range tahun
                if ($carbonDate->year < 1900 || $carbonDate->year > 2100) {
                    throw new \Exception('Tahun harus antara 1900-2100');
                }

                // Validasi tanggal nyata
                if (!$carbonDate->isValid()) {
                    throw new \Exception('Tanggal tidak valid');
                }

                // Simpan ke database
                $pasien->tgl_lahir = $carbonDate->format('Y-m-d');

                // Hitung umur lengkap dengan Carbon
                $today = Carbon::now();
                $years = $today->diffInYears($carbonDate);
                $months = $today->diffInMonths($carbonDate) % 12;

                // Hitung hari yang akurat
                $tempDate = $carbonDate->copy()->addYears($years)->addMonths($months);
                $days = $today->diffInDays($tempDate);

                // Format umur: X Tahun Y Bulan Z Hari
                $umur = '';
                if ($years > 0) {
                    $umur .= $years . ' Tahun ';
                }
                if ($months > 0 || $years > 0) {
                    $umur .= $months . ' Bulan ';
                }
                $umur .= $days . ' Hari';
                $pasien->umur = trim($umur);

                LogActivityService::log(
                    action: 'UPDATE',
                    module: 'Pasien',
                    description: 'Update tgl_lahir dan umur untuk No LAB: ' . $request->no_lab,
                    oldData: $oldData,
                    newData: ['tgl_lahir' => $pasien->tgl_lahir, 'umur' => $pasien->umur]
                );
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tanggal lahir tidak valid: ' . $e->getMessage()
                ], 422);
            }
        } else {
            // Field lainnya
            $pasien->{$request->field} = $request->value;

            LogActivityService::log(
                action: 'UPDATE',
                module: 'Pasien',
                description: 'Update field ' . $request->field . ' untuk No LAB: ' . $request->no_lab,
                oldData: $oldData,
                newData: $newData
            );
        }

        $pasien->updated_at = now();

        $pasien->save();

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diperbarui',
            'data' => [
                'nama_pasien' => $pasien->nama_pasien,
                'tgl_lahir' => $pasien->tgl_lahir,
                'umur' => $pasien->umur,
                'jenis_kelamin' => $pasien->jenis_kelamin,
                'pengirim' => $pasien->pengirim,
                'nota' => $pasien->nota,
                'alamat' => $pasien->alamat,
                'ket_klinik' => $pasien->ket_klinik,
                'nomor_registrasi' => $pasien->nomor_registrasi,
                'created_at' => $pasien->created_at,
                'updated_at' => $pasien->updated_at,
                'waktu_validasi' => $pasien->waktu_validasi
            ]
        ]);
    }


    // Fungsi untuk mengambil data pasien
    public function getDataPasienDetail($no_lab)
    {
        $pasien = Pasien::where('no_lab', $no_lab)->first();

        if (!$pasien) {
            return response()->json([
                'success' => false,
                'message' => 'Pasien tidak ditemukan'
            ], 404);
        }

        // Format tgl_lahir untuk display
        if ($pasien->tgl_lahir) {
            try {
                $pasien->tgl_lahir = Carbon::parse($pasien->tgl_lahir)->format('Y-m-d');
            } catch (\Exception $e) {
                $pasien->tgl_lahir = '';
            }
        }

        // Hitung ulang umur jika kosong
        if ($pasien->tgl_lahir && (!$pasien->umur || empty($pasien->umur))) {
            try {
                $birthDate = Carbon::parse($pasien->tgl_lahir);
                $today = Carbon::now();

                $years = $today->diffInYears($birthDate);
                $months = $today->diffInMonths($birthDate) % 12;

                $tempDate = $birthDate->copy()->addYears($years)->addMonths($months);
                $days = $today->diffInDays($tempDate);

                $umur = '';
                if ($years > 0) {
                    $umur .= $years . ' Tahun ';
                }

                if ($months > 0 || $years > 0) {
                    $umur .= $months . ' Bulan ';
                }

                $umur .= $days . ' Hari';

                $pasien->umur = trim($umur);
                $pasien->save();

                LogActivityService::log(
                    action: 'UPDATE',
                    module: 'Pasien',
                    description: 'Hitung ulang umur untuk No LAB: ' . $no_lab
                );
            } catch (\Exception $e) {
                // Biarkan kosong jika error
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'nama_pasien' => $pasien->nama_pasien,
                'tgl_lahir' => $pasien->tgl_lahir,
                'umur' => $pasien->umur,
                'jenis_kelamin' => $pasien->jenis_kelamin,
                'pengirim' => $pasien->pengirim,
                'nota' => $pasien->nota,
                'alamat' => $pasien->alamat,
                'ket_klinik' => $pasien->ket_klinik
            ]
        ]);
    }

    public function ambilOrderDariSimrs()
    {
        try {
            // ===============================
            // KONFIGURASI SIMRS
            // ===============================
            $loginUrl = 'https://app.rs-baiturrahim.com/rsbr/new72/bridging/frontend/auth/login';
            $dataUrl = 'https://app.rs-baiturrahim.com/rsbr/new72/bridging/frontend/api/getData';
            $username = 'arvindo';
            $password = 'ArvindoWS2025!@';

            // ===============================
            // LOGIN SIMRS
            // ===============================
            $loginResponse = Http::withoutVerifying()
                ->withHeaders([
                    'x-username' => $username,
                    'x-password' => $password,
                    'Accept' => 'application/json',
                ])->get($loginUrl);

            if (!$loginResponse->successful()) {
                throw new \Exception('Login SIMRS gagal');
            }

            $token = $loginResponse->json()['response']['token'] ?? null;
            if (!$token) {
                throw new \Exception('Token tidak ditemukan');
            }

            // ===============================
            // AMBIL DATA ORDER
            // ===============================
            $dataResponse = Http::withoutVerifying()
                ->withHeaders([
                    'x-token' => $token,
                    'Accept' => 'application/json',
                ])->post($dataUrl);

            if (!$dataResponse->successful()) {
                throw new \Exception('Gagal ambil data SIMRS');
            }

            $ordersList = $dataResponse->json()['response']['list'] ?? [];

            if (count($ordersList) === 0) {
                return response()->json([
                    'success' => true,
                    'message' => 'Tidak ada data dari SIMRS',
                ]);
            }

            // ===============================
            // PROSES BATCH - 100 DATA PER BATCH
            // ===============================
            $batchSize = 100;
            $totalProcessed = 0;
            $totalSuccess = 0;
            $totalUjiSuccess = 0;
            $allNewOrders = [];

            for ($i = 0; $i < count($ordersList); $i += $batchSize) {
                $batchOrders = array_slice($ordersList, $i, $batchSize);

                // Filter data baru per batch
                $batchNoLabs = [];
                foreach ($batchOrders as $order) {
                    if (isset($order['nomor_registrasi']) && !empty($order['nomor_registrasi'])) {
                        $originalNoLab = $order['nomor_registrasi'];
                        $convertedNoLab = $this->konversiFormatNoLab($originalNoLab);
                        $batchNoLabs[] = $convertedNoLab;
                    }
                }

                if (empty($batchNoLabs)) {
                    $totalProcessed += count($batchOrders);
                    continue;
                }

                // Cek data pasien yang sudah ada di database
                $existingNoLabs = DB::table('pasien')
                    ->whereIn('no_lab', $batchNoLabs)
                    ->pluck('no_lab')
                    ->toArray();

                $existingMap = array_flip($existingNoLabs);

                // Cek data uji pemeriksaan yang sudah ada di database
                $existingUji = DB::table('uji_pemeriksaan')
                    ->whereIn('no_lab', $batchNoLabs)
                    ->select('no_lab', 'kode_pemeriksaan')
                    ->get()
                    ->keyBy(function ($item) {
                        return $item->no_lab . '|' . $item->kode_pemeriksaan;
                    })
                    ->toArray();

                // Proses data baru per batch
                $batchPasien = [];
                $batchUji = [];
                $newOrdersInBatch = [];
                $ujiUnikKey = [];

                foreach ($batchOrders as $order) {
                    if (!isset($order['nomor_registrasi']) || empty($order['nomor_registrasi'])) {
                        continue;
                    }

                    $originalNoLab = $order['nomor_registrasi'];
                    $noLab = $this->konversiFormatNoLab($originalNoLab);

                    // Skip jika pasien sudah ada di database
                    if (isset($existingMap[$noLab])) {
                        continue;
                    }

                    $newOrdersInBatch[] = $order;
                    $nomorRegistrasi = $this->konversiFormatNoLab($originalNoLab);

                    $batchPasien[] = [
                        'no_lab' => $noLab,
                        'nomor_registrasi' => $nomorRegistrasi,
                        'rm_pasien' => $order['rm_pasien'] ?? null,
                        'tgl_pendaftaran' => $order['tgl_pendaftaran'] ?? null,
                        'nota' => $order['nota'] ?? null,
                        'nama_pasien' => $order['nama_pasien'] ?? null,
                        'tgl_lahir' => $order['tgl_lahir'] ?? null,
                        'jenis_kelamin' => $order['jenis_kelamin'] ?? null,
                        'alamat' => $order['alamat'] ?? null,
                        'pengirim' => $order['pengirim'] ?? null,
                        'ket_klinik' => $order['asal_ruangan'] ?? null,
                        'id_pemeriksa' => null,
                        'synced_at' => now(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    // UJI PEMERIKSAAN - CEK DUPLIKAT DI DATABASE DAN DI BATCH
                    if (!empty($order['jenis_pemeriksaan'])) {
                        foreach ($order['jenis_pemeriksaan'] as $kategori => $listPemeriksaan) {
                            foreach ($listPemeriksaan as $item) {
                                foreach ($item as $kode => $nama) {
                                    $uniqueKey = $noLab . '|' . $kode;

                                    // Skip jika sudah ada di database (sudah pernah disimpan)
                                    if (isset($existingUji[$uniqueKey])) {
                                        continue;
                                    }

                                    // Skip jika sudah ada di batch ini (duplikat dalam batch yang sama)
                                    if (isset($ujiUnikKey[$uniqueKey])) {
                                        continue;
                                    }

                                    $ujiUnikKey[$uniqueKey] = true;

                                    $batchUji[] = [
                                        'no_lab' => $noLab,
                                        'kategori' => $kategori,
                                        'kode_pemeriksaan' => $kode,
                                        'nama_pemeriksaan' => $nama,
                                        'created_at' => now(),
                                        'updated_at' => now(),
                                    ];
                                }
                            }
                        }
                    }
                }

                // INSERT PER BATCH dengan error handling untuk duplikat
                if (!empty($batchPasien)) {
                    try {
                        DB::transaction(function () use ($batchPasien, $batchUji) {
                            DB::table('pasien')->insert($batchPasien);
                            if (!empty($batchUji)) {
                                // Gunakan insertOrIgnore untuk menghindari error duplikat
                                // Atau gunakan try-catch untuk setiap insert
                                foreach ($batchUji as $uji) {
                                    try {
                                        DB::table('uji_pemeriksaan')->insert($uji);
                                    } catch (\Exception $e) {
                                        // Log error duplikat tapi lanjutkan proses
                                        Log::warning('Duplikat data uji pemeriksaan: ' .
                                            $uji['no_lab'] . ' - ' . $uji['kode_pemeriksaan'], [
                                            'error' => $e->getMessage()
                                        ]);
                                    }
                                }
                            }
                        });

                        $totalSuccess += count($batchPasien);
                        $totalUjiSuccess += count($batchUji);
                        $allNewOrders = array_merge($allNewOrders, $newOrdersInBatch);

                    } catch (\Exception $e) {
                        Log::error('Error insert batch: ' . $e->getMessage());
                        // Lanjutkan ke batch berikutnya meskipun ada error
                    }
                }

                $totalProcessed += count($batchOrders);

                // Delay kecil antar batch
                if ($i + $batchSize < count($ordersList)) {
                    usleep(100000); // 100ms
                }
            }

            // ===============================
            // LOG DAN RESPONSE
            // ===============================
            if ($totalSuccess > 0) {
                LogActivityService::log(
                    action: 'SYNC',
                    module: 'SIMRS',
                    description: 'Sinkronisasi data Order Pasien ARS, total berhasil: ' .
                        $totalSuccess . ' pasien, ' . $totalUjiSuccess . ' pemeriksaan',
                );
            }

            $rangeData = 'Tidak ada data baru';
            if ($totalSuccess > 0 && count($allNewOrders) > 0) {
                $firstOrder = $allNewOrders[0]['nomor_registrasi'];
                $lastOrder = $allNewOrders[count($allNewOrders) - 1]['nomor_registrasi'];
                $rangeData = 'Dari ' . $firstOrder . ' sampai ' . $lastOrder;
            }

            return response()->json([
                'success' => true,
                'message' => 'Sinkronisasi Data Pasien Dengan ARS selesai',
                'total_data_diterima' => count($ordersList),
                'total_data_diproses' => $totalProcessed,
                'total_pasien_baru' => $totalSuccess,
                'total_uji_baru' => $totalUjiSuccess,
                'range_data' => $rangeData,
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    private function konversiFormatNoLab(string $noLab): string
    {
        if (strlen($noLab) < 6) {
            return $noLab;
        }

        $tanggal = substr($noLab, 0, 6); // ddmmyy
        $urutan = substr($noLab, 6);    // xxxx

        $dd = substr($tanggal, 0, 2);
        $mm = substr($tanggal, 2, 2);
        $yy = substr($tanggal, 4, 2);

        // Konversi ke format yymmdd
        return $yy . $mm . $dd . $urutan;
    }



    public function listHasil(Request $request)
    {
        try {

            // ==========================
            // 1. Ambil semua pasien (opsional filter)
            // ==========================
            $pasienList = Pasien::with([
                'hematology.dataPemeriksaan.lisMappings',
                'kimia.dataPemeriksaan',
                'hasilPemeriksaanLain.dataPemeriksaan.lisMappings',
                'pemeriksa',
                'ruangan'
            ])
            ->whereNull('deleted_at')
            // ->where('status', 'SELESAI') // aktifkan jika perlu
            ->orderBy('created_at', 'desc')
            ->get();

            // ==========================
            // 2. Urutan Hematology
            // ==========================
            $urutanHematology = [
                'WBC','NEU%','LYM%','MON%','EOS%','BAS%',
                'RBC','HGB','HCT','MCV','MCH','MCHC',
                'RDW-CV','RDW-SD','PLT','MPV','PDW','PCT'
            ];

            // ==========================
            // 3. Loop semua pasien
            // ==========================
            $data = $pasienList->map(function ($pasien) use ($urutanHematology) {

                /**
                 * =================================================
                 * HEMATOLOGY
                 * =================================================
                 */
                $hasilHematology = $pasien->hematology()
                    ->with('dataPemeriksaan.lisMappings')
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

                $hematologyFix = [];
                foreach ($urutanHematology as $lis) {
                    $key = strtolower(trim($lis));
                    $item = $lisIndex[$key] ?? null;

                    if (!$item) {
                        foreach ($lisIndex as $lisKey => $value) {
                            if (strpos($lisKey, $key) !== false) {
                                $item = $value;
                                break;
                            }
                        }
                    }

                    if ($item) {
                        $hematologyFix[] = [
                            'id_data_pemeriksaan' => $item->id_data_pemeriksaan,
                            'nama_pemeriksaan' => $item->dataPemeriksaan->data_pemeriksaan,
                            'hasil_pengujian' => $item->hasil_pengujian,
                            'satuan' => $item->dataPemeriksaan->satuan,
                            'rujukan' => $item->dataPemeriksaan->rujukan,
                            'keterangan' => $item->keterangan,
                        ];
                    }
                }

                /**
                 * =================================================
                 * KIMIA
                 * =================================================
                 */
                $kimia = $pasien->kimia()
                    ->with('dataPemeriksaan')
                    ->orderBy('id_pemeriksaan_kimia', 'asc')
                    ->get()
                    ->map(function ($item) {
                        return [
                            'id_data_pemeriksaan' => $item->id_data_pemeriksaan,
                            'nama_pemeriksaan' => optional($item->dataPemeriksaan)->data_pemeriksaan,
                            'hasil_pengujian' => $item->hasil_pengujian,
                            'satuan' => optional($item->dataPemeriksaan)->satuan,
                            'rujukan' => optional($item->dataPemeriksaan)->rujukan,
                            'keterangan' => $item->keterangan,
                        ];
                    });

                /**
                 * =================================================
                 * HASIL PEMERIKSAAN LAIN
                 * =================================================
                 */
                $hasilLain = DB::table('hasil_pemeriksaan_lain as hpl')
                    ->leftJoin('data_pemeriksaan as dp', 'hpl.id_data_pemeriksaan', '=', 'dp.id_data_pemeriksaan')
                    ->leftJoin('jenis_pemeriksaan_1 as jp1', 'dp.id_jenis_pemeriksaan_1', '=', 'jp1.id_jenis_pemeriksaan_1')
                    ->where('hpl.no_lab', $pasien->no_lab)
                    ->whereNull('hpl.deleted_at')
                    ->select(
                        'hpl.*',
                        'dp.data_pemeriksaan',
                        'dp.satuan as satuan_pemeriksaan',
                        'dp.rujukan as rujukan_pemeriksaan',
                        'dp.metode',
                        'jp1.nama_pemeriksaan as jenis_pemeriksaan'
                    )
                    ->orderBy('hpl.id_hasil_lain', 'asc')
                    ->get()
                    ->groupBy('jenis_pemeriksaan');

                /**
                 * =================================================
                 * PAYLOAD PASIEN
                 * =================================================
                 */
                return [
                    'no_lab' => $pasien->no_lab,
                    'tanggal' => Carbon::parse($pasien->created_at)->format('Y-m-d H:i:s'),

                    'pasien' => [
                        'rm_pasien' => $pasien->rm_pasien,
                        'nama_pasien' => $pasien->nama_pasien,
                        'tgl_lahir' => $pasien->tgl_lahir,
                        'jenis_kelamin' => $pasien->jenis_kelamin,
                        'alamat' => $pasien->alamat,
                        'pengirim' => $pasien->pengirim,
                        'asal_ruangan' => $pasien->ket_klinik,
                    ],

                    'hasil' => [
                        'hematology' => $hematologyFix,
                        'kimia' => $kimia,
                        'lainnya' => $hasilLain,
                    ]
                ];
            });

            LogActivityService::log(
                action: 'FETCH',
                module: 'Hasil Lab',
                description: 'Ambil data hasil lab untuk ' . $data->count() . ' pasien'
            );

            // ==========================
            // 4. Response
            // ==========================
            return response()->json([
                'success' => true,
                'total_pasien' => $data->count(),
                'data' => $data
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // HasilLabController.php
    public function updateKeteranganBatch(Request $request)
    {
        try {
            $type = $request->input('type');
            $id = $request->input('id');
            $field = $request->input('field');
            $value = $request->input('value');
            $noLab = $request->input('no_lab');

            if (!$id || !$type) {
                return response()->json([
                    'success' => false,
                    'message' => 'ID dan type required'
                ]);
            }

            switch ($type) {
                case 'hematology':
                    $model = PemeriksaanHematology::find($id);
                    break;

                case 'kimia':
                    $model = PemeriksaanKimia::find($id);
                    break;

                case 'hasil_lain':
                    $model = HasilPemeriksaanLain::find($id);
                    break;

                default:
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid type'
                    ]);
            }

            if (!$model) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data not found'
                ]);
            }

            // Update field
            $model->{$field} = $value;
            $model->save();

            return response()->json([
                'success' => true,
                'message' => 'Keterangan updated',
                'data' => [
                    'id' => $id,
                    'field' => $field,
                    'value' => $value,
                    'keterangan' => $value,
                    'updated_at' => $model->updated_at->format('d/m/Y H:i')
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function searchPenjamin(Request $request)
    {
        $search = $request->get('search');

        $penjamins = Penjamin::where('nama_penjamin', 'like', "%{$search}%")
            ->limit(20)
            ->get(['id_penjamin', 'nama_penjamin']);

        return response()->json([
            'success' => true,
            'data' => $penjamins
        ]);
    }

    public function searchRuangan(Request $request)
    {
        $search = $request->get('search');

        $ruangans = Ruangan::where('nama_ruangan', 'like', "%{$search}%")
            ->limit(20)
            ->get(['id_ruangan', 'nama_ruangan']);

        return response()->json([
            'success' => true,
            'data' => $ruangans
        ]);
    }


    public function updatePenjamin(Request $request)
    {
        $request->validate([
            'no_lab' => 'required|exists:pasien,no_lab',
            'nota' => 'nullable|string|max:255'
        ]);

        $pasien = Pasien::where('no_lab', $request->no_lab)->firstOrFail();

        $pasien->update([
            'nota' => $request->nota
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Penjamin berhasil diperbarui',
            'data' => [
                'nota' => $pasien->nota,
                'updated_at' => $pasien->updated_at->format('d/m/Y H:i')
            ]
        ]);
    }

    public function updateRuangan(Request $request)
    {
        $request->validate([
            'no_lab' => 'required|exists:pasien,no_lab',
            'id_ruangan' => 'nullable|exists:ruangan,id_ruangan',
            'ket_klinik' => 'nullable|string'
        ]);

        $pasien = Pasien::where('no_lab', $request->no_lab)->firstOrFail();

        $pasien->update([
            'id_ruangan' => $request->id_ruangan,
            'ket_klinik' => $request->ket_klinik
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Asal kunjungan berhasil diperbarui',
            'data' => [
                'id_ruangan' => $pasien->id_ruangan,
                'ket_klinik' => $pasien->ket_klinik,
                'updated_at' => $pasien->updated_at->format('d/m/Y H:i')
            ]
        ]);
    }

    public function getHistoryHover(Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'jenis_pemeriksaan' => 'required|string',
                'rm_pasien' => 'required|string',
                'type' => 'required|string|in:hematology,kimia,hasil_lain',
                'current_no_lab' => 'required|string'
            ]);

            // Tentukan tabel utama
            $mainTable = '';

            switch($request->type) {
                case 'hematology':
                    $mainTable = 'pemeriksaan_hematology';
                    break;
                case 'kimia':
                    $mainTable = 'pemeriksaan_kimia';
                    break;
                case 'hasil_lain':
                    $mainTable = 'hasil_pemeriksaan_lain';
                    break;
            }

            if (empty($mainTable)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Type pemeriksaan tidak valid'
                ], 400);
            }

            // Query dengan JOIN ke tabel data_pemeriksaan dan pasien
            $history = DB::table($mainTable)
                ->select(
                    "$mainTable.hasil_pengujian",
                    "$mainTable.no_lab",
                    'pasien.waktu_validasi'
                )
                ->leftJoin('pasien', 'pasien.no_lab', '=', "$mainTable.no_lab")
                ->leftJoin('data_pemeriksaan', 'data_pemeriksaan.id_data_pemeriksaan', '=', "$mainTable.id_data_pemeriksaan")
                ->where('data_pemeriksaan.data_pemeriksaan', $request->jenis_pemeriksaan)
                ->where('pasien.rm_pasien', $request->rm_pasien) // rm_pasien dari tabel pasien
                ->where("$mainTable.no_lab", '!=', $request->current_no_lab)
                ->orderBy('pasien.waktu_validasi', 'DESC')
                ->limit(50)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $history,
                'count' => $history->count()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil history: ' . $e->getMessage()
            ], 500);
        }
    }

}


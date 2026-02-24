<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use App\Models\JenisPemeriksaan;
use App\Models\DataPemeriksaan;
use App\Models\Pemeriksa;
use App\Models\PemeriksaanHematology;
use App\Models\PemeriksaanKimia;
use App\Models\HasilPemeriksaanLain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    /**
     * Tampilkan halaman utama laporan
     */
    public function index()
    {
        $jenisPemeriksaan = JenisPemeriksaan::withCount([
            'dataPemeriksaan' => function($query) {
                $query->whereHas('hematology.pasien', function($q) {
                    $q->whereNotNull('waktu_validasi');
                });
            }
        ])->orderBy('nama_pemeriksaan')->get();

        $pengirim = Pasien::select('pengirim', DB::raw('COUNT(*) as total_pasien'))
            ->whereNotNull('waktu_validasi')
            ->whereNotNull('pengirim')
            ->where('pengirim', '!=', '')
            ->groupBy('pengirim')
            ->orderBy('total_pasien', 'desc')
            ->get();

        $pemeriksa = Pemeriksa::withCount([
            'pasien' => function($query) {
                $query->whereNotNull('waktu_validasi');
            }
        ])->orderBy('nama_pemeriksa')->get();

        return view('laporan.index', compact('jenisPemeriksaan', 'pengirim', 'pemeriksa'));
    }

    /**
     * Laporan Lengkap Statistik
     */
    public function laporanLengkap(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'format' => 'nullable|in:pdf,excel,view'
        ]);

        // Set default jika tidak ada input (30 hari terakhir)
        $tanggalMulai = $request->filled('tanggal_mulai')
            ? Carbon::parse($request->tanggal_mulai)->startOfDay()
            : now()->subDays(30)->startOfDay();

        $tanggalSelesai = $request->filled('tanggal_selesai')
            ? Carbon::parse($request->tanggal_selesai)->endOfDay()
            : now()->endOfDay();

        $format = $request->input('format', 'view');

        // =============================
        // DATA LAPORAN
        // =============================

        $totalPasien = Pasien::whereBetween('waktu_validasi', [$tanggalMulai, $tanggalSelesai])->count();

        $perJenisPemeriksaan = JenisPemeriksaan::withCount([
            'dataPemeriksaan' => function ($q) use ($tanggalMulai, $tanggalSelesai) {
                $q->whereHas('hematology.pasien', function ($qq) use ($tanggalMulai, $tanggalSelesai) {
                    $qq->whereBetween('waktu_validasi', [$tanggalMulai, $tanggalSelesai]);
                });
            }
        ])->get();

        $perPengirim = Pasien::select('pengirim', DB::raw('COUNT(*) as total'))
            ->whereBetween('waktu_validasi', [$tanggalMulai, $tanggalSelesai])
            ->whereNotNull('pengirim')
            ->groupBy('pengirim')
            ->orderByDesc('total')
            ->get();

        $perPemeriksa = Pemeriksa::withCount(['pasien' => function ($q) use ($tanggalMulai, $tanggalSelesai) {
            $q->whereBetween('waktu_validasi', [$tanggalMulai, $tanggalSelesai]);
        }])
        ->orderByDesc('pasien_count')
        ->get();

        $perJenisKelamin = Pasien::select('jenis_kelamin', DB::raw('COUNT(*) as total'))
            ->whereBetween('waktu_validasi', [$tanggalMulai, $tanggalSelesai])
            ->whereNotNull('jenis_kelamin')
            ->groupBy('jenis_kelamin')
            ->get();

        // PER JAM PostgreSQL
        $perJamRaw = Pasien::selectRaw("EXTRACT(HOUR FROM waktu_validasi)::int AS jam, COUNT(*) AS total")
            ->whereBetween('waktu_validasi', [$tanggalMulai, $tanggalSelesai])
            ->whereNotNull('waktu_validasi')
            ->groupBy('jam')
            ->orderBy('jam', 'asc')
            ->get();

        // isi jam 0..23 agar chart tidak kosong
        $jamMap = array_fill(0, 24, 0);
        foreach ($perJamRaw as $r) {
            $jam = (int) $r->jam;
            if ($jam >= 0 && $jam <= 23) {
                $jamMap[$jam] = (int) $r->total;
            }
        }
        $perJamCsv = implode(',', $jamMap);

        // Dokter / referal top
        $perDokter = Pasien::select('pengirim as nama_dokter', DB::raw('COUNT(*) as total'))
            ->whereBetween('waktu_validasi', [$tanggalMulai, $tanggalSelesai])
            ->whereNotNull('pengirim')
            ->groupBy('pengirim')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // ✅ Eager load relasi yang dibutuhkan (PERBAIKAN UTAMA)
        // Jangan pakai ujiPemeriksaan lagi
        $pasienDetail = Pasien::with([
                'pemeriksa',

                // sumber jenis pemeriksaan yang benar:
                'hematology.dataPemeriksaan.jenisPemeriksaan',
                'kimia.dataPemeriksaan.jenisPemeriksaan',
                'hasilPemeriksaanLain.dataPemeriksaan.jenisPemeriksaan',
            ])
            ->whereBetween('waktu_validasi', [$tanggalMulai, $tanggalSelesai])
            ->orderByDesc('waktu_validasi')
            ->limit(50)
            ->get();

        // ✅ Tambahkan field "jenis_pemeriksaan_nama" dari jenis_pemeriksaan_1
        foreach ($pasienDetail as $p) {

            // pakai set agar unik
            $jenisSet = [];

            // 1) hematology
            foreach ($p->hematology as $h) {
                if ($h->dataPemeriksaan && $h->dataPemeriksaan->jenisPemeriksaan) {
                    $nama = $h->dataPemeriksaan->jenisPemeriksaan->nama_pemeriksaan;
                    if ($nama) $jenisSet[$nama] = true;
                }
            }

            // 2) kimia
            foreach ($p->kimia as $k) {
                if ($k->dataPemeriksaan && $k->dataPemeriksaan->jenisPemeriksaan) {
                    $nama = $k->dataPemeriksaan->jenisPemeriksaan->nama_pemeriksaan;
                    if ($nama) $jenisSet[$nama] = true;
                }
            }

            // 3) hasil pemeriksaan lain
            foreach ($p->hasilPemeriksaanLain as $l) {
                if ($l->dataPemeriksaan && $l->dataPemeriksaan->jenisPemeriksaan) {
                    $nama = $l->dataPemeriksaan->jenisPemeriksaan->nama_pemeriksaan;
                    if ($nama) $jenisSet[$nama] = true;
                }
            }

            $jenisList = array_keys($jenisSet);

            // hasil akhir
            $p->jenis_pemeriksaan_nama = count($jenisList) ? implode(', ', $jenisList) : '-';
        }

        // Hitung hari untuk informasi tambahan
        $hari = $tanggalMulai->diffInDays($tanggalSelesai) + 1;
        $rataRataPerHari = $totalPasien > 0 ? $totalPasien / $hari : 0;

        $data = [
            'tanggalMulai' => $tanggalMulai,
            'tanggalSelesai' => $tanggalSelesai,
            'totalPasien' => $totalPasien,
            'perJenisPemeriksaan' => $perJenisPemeriksaan,
            'perPengirim' => $perPengirim,
            'perPemeriksa' => $perPemeriksa,
            'perJenisKelamin' => $perJenisKelamin,
            'perJamCsv' => $perJamCsv,
            'perJamRaw' => $perJamRaw,
            'perDokter' => $perDokter,
            'pasienDetail' => $pasienDetail,
            'hari' => $hari,
            'rataRataPerHari' => $rataRataPerHari,
            'request' => $request->all(),
        ];

        // =============================
        // OUTPUT
        // =============================
        if ($format === 'pdf') {
            $pdf = PDF::loadView('laporan.pdf.laporan_lengkap', $data);
            return $pdf->download('laporan-lengkap-' . date('Y-m-d') . '.pdf');
        }

        if ($format === 'excel') {
            return $this->exportExcelLaporanLengkap($data);
        }

        return view('laporan.lengkap', $data);
    }


    /**
     * Laporan By Jenis Pemeriksaan
     */
    public function laporanByJenisPemeriksaan(Request $request)
    {
        $request->validate([
            'id_jenis_pemeriksaan_1' => 'nullable|exists:jenis_pemeriksaan_1,id_jenis_pemeriksaan_1',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'group_by' => 'nullable|in:day,week,month,year',
            'format' => 'nullable|in:pdf,excel,view'
        ]);

        $query = DataPemeriksaan::with(['jenisPemeriksaan', 'hematology.pasien'])
            ->whereHas('hematology.pasien', function($q) use ($request) {
                $q->whereNotNull('waktu_validasi');
                if ($request->tanggal_mulai && $request->tanggal_selesai) {
                    $q->whereBetween('waktu_validasi', [
                        Carbon::parse($request->tanggal_mulai)->startOfDay(),
                        Carbon::parse($request->tanggal_selesai)->endOfDay()
                    ]);
                }
            });

        if ($request->id_jenis_pemeriksaan_1) {
            $query->where('id_jenis_pemeriksaan_1', $request->id_jenis_pemeriksaan_1);
        }

        // Statistik per jenis pemeriksaan
        $statistikJenisPemeriksaan = JenisPemeriksaan::withCount([
            'dataPemeriksaan' => function($query) use ($request) {
                $query->whereHas('hematology.pasien', function($q) use ($request) {
                    $q->whereNotNull('waktu_validasi');
                    if ($request->tanggal_mulai && $request->tanggal_selesai) {
                        $q->whereBetween('waktu_validasi', [
                            Carbon::parse($request->tanggal_mulai)->startOfDay(),
                            Carbon::parse($request->tanggal_selesai)->endOfDay()
                        ]);
                    }
                });
            }
        ])
        ->with(['dataPemeriksaan.hematology' => function($query) use ($request) {
            $query->whereHas('pasien', function($q) use ($request) {
                $q->whereNotNull('waktu_validasi');
                if ($request->tanggal_mulai && $request->tanggal_selesai) {
                    $q->whereBetween('waktu_validasi', [
                        Carbon::parse($request->tanggal_mulai)->startOfDay(),
                        Carbon::parse($request->tanggal_selesai)->endOfDay()
                    ]);
                }
            });
        }])
        ->orderBy('nama_pemeriksaan')
        ->get();

        // Trend per periode
        $trendPeriode = $this->getTrendPeriode($request);

        $data = [
            'statistikJenisPemeriksaan' => $statistikJenisPemeriksaan,
            'trendPeriode' => $trendPeriode,
            'filter' => $request->all(),
            'totalPasien' => $statistikJenisPemeriksaan->sum('data_pemeriksaan_count')
        ];

        $format = $request->format ?? 'view';

        if ($format === 'pdf') {
            $pdf = PDF::loadView('laporan.pdf.by_jenis_pemeriksaan', $data);
            return $pdf->download('laporan-jenis-pemeriksaan-' . date('Y-m-d') . '.pdf');
        }

        if ($format === 'excel') {
            return $this->exportExcelByJenisPemeriksaan($data);
        }

        return view('laporan.by_jenis_pemeriksaan', $data);
    }

    /**
     * Laporan By Pengirim
     */
    public function laporanByPengirim(Request $request)
    {
        $request->validate([
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'pengirim' => 'nullable|string',
            'format' => 'nullable|in:pdf,excel,view'
        ]);

        $query = Pasien::whereNotNull('waktu_validasi');

        if ($request->tanggal_mulai && $request->tanggal_selesai) {
            $query->whereBetween('waktu_validasi', [
                Carbon::parse($request->tanggal_mulai)->startOfDay(),
                Carbon::parse($request->tanggal_selesai)->endOfDay()
            ]);
        }

        if ($request->pengirim) {
            $query->where('pengirim', 'like', '%' . $request->pengirim . '%');
        }

        // Statistik pengirim
        $statistikPengirim = $query->select(
            'pengirim',
            DB::raw('COUNT(*) as total_pasien'),
            DB::raw('COUNT(DISTINCT id_dokter) as total_dokter'),
            DB::raw('AVG(umur) as rata_rata_umur'),
            DB::raw('SUM(CASE WHEN jenis_kelamin = "PRIA" OR jenis_kelamin = "PRIA" THEN 1 ELSE 0 END) as total_pria'),
            DB::raw('SUM(CASE WHEN jenis_kelamin = "WANITA" OR jenis_kelamin = "WANITA" THEN 1 ELSE 0 END) as total_wanita')
        )
        ->whereNotNull('pengirim')
        ->where('pengirim', '!=', '')
        ->groupBy('pengirim')
        ->orderBy('total_pasien', 'desc')
        ->get();

        // Detail per pengirim
        $detailPengirim = [];
        foreach ($statistikPengirim as $pengirim) {
            $detail = Pasien::with(['dokter', 'ruangan', 'ujiPemeriksaan'])
                ->where('pengirim', $pengirim->pengirim)
                ->whereNotNull('waktu_validasi');

            if ($request->tanggal_mulai && $request->tanggal_selesai) {
                $detail->whereBetween('waktu_validasi', [
                    Carbon::parse($request->tanggal_mulai)->startOfDay(),
                    Carbon::parse($request->tanggal_selesai)->endOfDay()
                ]);
            }

            $detailPengirim[$pengirim->pengirim] = $detail->take(10)->get();
        }

        $data = [
            'statistikPengirim' => $statistikPengirim,
            'detailPengirim' => $detailPengirim,
            'filter' => $request->all(),
            'totalSemuaPasien' => $statistikPengirim->sum('total_pasien')
        ];

        $format = $request->format ?? 'view';

        if ($format === 'pdf') {
            $pdf = PDF::loadView('laporan.pdf.by_pengirim', $data);
            return $pdf->download('laporan-pengirim-' . date('Y-m-d') . '.pdf');
        }

        if ($format === 'excel') {
            return $this->exportExcelByPengirim($data);
        }

        return view('laporan.by_pengirim', $data);
    }

    /**
     * Laporan By Pemeriksa
     */
    public function laporanByPemeriksa(Request $request)
    {
        $request->validate([
            'id_pemeriksa' => 'nullable|exists:pemeriksa,id_pemeriksa',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'format' => 'nullable|in:pdf,excel,view'
        ]);

        $query = Pemeriksa::withCount(['pasien' => function($query) use ($request) {
            $query->whereNotNull('waktu_validasi');
            if ($request->tanggal_mulai && $request->tanggal_selesai) {
                $query->whereBetween('waktu_validasi', [
                    Carbon::parse($request->tanggal_mulai)->startOfDay(),
                    Carbon::parse($request->tanggal_selesai)->endOfDay()
                ]);
            }
        }])
        ->with(['pasien' => function($query) use ($request) {
            $query->whereNotNull('waktu_validasi');
            if ($request->tanggal_mulai && $request->tanggal_selesai) {
                $query->whereBetween('waktu_validasi', [
                    Carbon::parse($request->tanggal_mulai)->startOfDay(),
                    Carbon::parse($request->tanggal_selesai)->endOfDay()
                ]);
            }
            $query->with(['dokter', 'ruangan', 'ujiPemeriksaan'])
                  ->latest('waktu_validasi')
                  ->take(5);
        }]);

        if ($request->id_pemeriksa) {
            $query->where('id_pemeriksa', $request->id_pemeriksa);
        }

        $pemeriksa = $query->orderBy('nama_pemeriksa')->get();

        // Statistik kinerja pemeriksa
        $kinerjaPemeriksa = [];
        foreach ($pemeriksa as $p) {
            $kinerjaPemeriksa[$p->id_pemeriksa] = [
                'total_pasien' => $p->pasien_count,
                'rata_waktu_validasi' => $this->hitungRataWaktuValidasi($p->id_pemeriksa, $request),
                'jenis_pemeriksaan_terbanyak' => $this->getJenisPemeriksaanTerbanyak($p->id_pemeriksa, $request)
            ];
        }

        $data = [
            'pemeriksa' => $pemeriksa,
            'kinerjaPemeriksa' => $kinerjaPemeriksa,
            'filter' => $request->all(),
            'totalSemuaPasien' => $pemeriksa->sum('pasien_count')
        ];

        $format = $request->format ?? 'view';

        if ($format === 'pdf') {
            $pdf = PDF::loadView('laporan.pdf.by_pemeriksa', $data);
            return $pdf->download('laporan-pemeriksa-' . date('Y-m-d') . '.pdf');
        }

        if ($format === 'excel') {
            return $this->exportExcelByPemeriksa($data);
        }

        return view('laporan.by_pemeriksa', $data);
    }

    /**
     * Laporan Harian
     */
    public function laporanHarian(Request $request)
    {
        $request->validate([
            'tanggal' => 'nullable|date',
            'format' => 'nullable|in:pdf,excel,view'
        ]);

        $tanggal = $request->tanggal ? Carbon::parse($request->tanggal) : Carbon::today();

        $data = $this->generateDataLaporanHarian($tanggal);

        $format = $request->format ?? 'view';

        if ($format === 'pdf') {
            $pdf = PDF::loadView('laporan.pdf.harian', $data);
            return $pdf->download('laporan-harian-' . $tanggal->format('Y-m-d') . '.pdf');
        }

        if ($format === 'excel') {
            return $this->exportExcelHarian($data);
        }

        return view('laporan.harian', $data);
    }

    /**
     * Laporan Bulanan
     */
    public function laporanBulanan(Request $request)
    {
        $request->validate([
            'bulan' => 'nullable|date_format:Y-m',
            'format' => 'nullable|in:pdf,excel,view'
        ]);

        $bulan = $request->bulan ? Carbon::parse($request->bulan) : Carbon::now();

        $data = $this->generateDataLaporanBulanan($bulan);

        $format = $request->format ?? 'view';

        if ($format === 'pdf') {
            $pdf = PDF::loadView('laporan.pdf.bulanan', $data);
            return $pdf->download('laporan-bulanan-' . $bulan->format('Y-m') . '.pdf');
        }

        if ($format === 'excel') {
            return $this->exportExcelBulanan($data);
        }

        return view('laporan.bulanan', $data);
    }

    /**
     * Laporan Tahunan
     */
    public function laporanTahunan(Request $request)
    {
        $request->validate([
            'tahun' => 'nullable|date_format:Y',
            'format' => 'nullable|in:pdf,excel,view'
        ]);

        $tahun = $request->tahun ? (int)$request->tahun : Carbon::now()->year;

        $data = $this->generateDataLaporanTahunan($tahun);

        $format = $request->format ?? 'view';

        if ($format === 'pdf') {
            $pdf = PDF::loadView('laporan.pdf.tahunan', $data);
            return $pdf->download('laporan-tahunan-' . $tahun . '.pdf');
        }

        if ($format === 'excel') {
            return $this->exportExcelTahunan($data);
        }

        return view('laporan.tahunan', $data);
    }

    /**
     * Dashboard Statistik
     */
    public function dashboard()
    {
        $hariIni = \Carbon\Carbon::today();
        $bulanIni = \Carbon\Carbon::now()->startOfMonth();

        $statistik = [
            'hari_ini' => [
                'total_pasien' => Pasien::whereDate('waktu_validasi', $hariIni)->count(),
                'pemeriksaan_selesai' => Pasien::whereDate('waktu_validasi', $hariIni)->count(),
                'pemeriksaan_pending' => Pasien::whereNull('waktu_validasi')
                    ->whereDate('tgl_pendaftaran', $hariIni)
                    ->count(),
            ],
            'bulan_ini' => [
                'total_pasien' => Pasien::whereBetween('waktu_validasi', [
                    $bulanIni,
                    \Carbon\Carbon::now()->endOfMonth()
                ])->count(),
                'per_jenis_pemeriksaan' => JenisPemeriksaan::withCount([
                    'dataPemeriksaan' => function($query) use ($bulanIni) {
                        $query->whereHas('hematology.pasien', function($q) use ($bulanIni) {
                            $q->whereBetween('waktu_validasi', [
                                $bulanIni,
                                \Carbon\Carbon::now()->endOfMonth()
                            ]);
                        });
                    }
                ])->get(),
                'per_pengirim' => Pasien::select('pengirim', DB::raw('COUNT(*) as total'))
                    ->whereBetween('waktu_validasi', [$bulanIni, \Carbon\Carbon::now()->endOfMonth()])
                    ->whereNotNull('pengirim')
                    ->groupBy('pengirim')
                    ->orderBy('total', 'desc')
                    ->limit(10)
                    ->get(),
            ],
            'grafik_30_hari' => $this->getGrafik30Hari(), // implementasikan sesuai struktur data kamu
            'top_pemeriksa' => Pemeriksa::withCount(['pasien' => function($query) use ($bulanIni) {
                $query->whereBetween('waktu_validasi', [
                    $bulanIni,
                    \Carbon\Carbon::now()->endOfMonth()
                ]);
            }])
            ->orderBy('pasien_count', 'desc')
            ->limit(5)
            ->get(),
        ];

        return view('laporan.dashboard', compact('statistik'));
    }


    /**
     * =========================================================
     * PRIVATE METHODS
     * =========================================================
     */

    private function generateDataLaporanLengkap($tanggalMulai, $tanggalSelesai)
    {
        return [
            'totalPasien' => Pasien::whereBetween('waktu_validasi', [$tanggalMulai, $tanggalSelesai])->count(),
            'perJenisKelamin' => Pasien::select(
                DB::raw('jenis_kelamin'),
                DB::raw('COUNT(*) as total')
            )
            ->whereBetween('waktu_validasi', [$tanggalMulai, $tanggalSelesai])
            ->groupBy('jenis_kelamin')
            ->get(),
            'perJenisPemeriksaan' => JenisPemeriksaan::withCount([
                'dataPemeriksaan' => function($query) use ($tanggalMulai, $tanggalSelesai) {
                    $query->whereHas('hematology.pasien', function($q) use ($tanggalMulai, $tanggalSelesai) {
                        $q->whereBetween('waktu_validasi', [$tanggalMulai, $tanggalSelesai]);
                    });
                }
            ])->get(),
            'perPengirim' => Pasien::select('pengirim', DB::raw('COUNT(*) as total'))
                ->whereBetween('waktu_validasi', [$tanggalMulai, $tanggalSelesai])
                ->whereNotNull('pengirim')
                ->groupBy('pengirim')
                ->orderBy('total', 'desc')
                ->get(),
            'perPemeriksa' => Pemeriksa::withCount(['pasien' => function($query) use ($tanggalMulai, $tanggalSelesai) {
                $query->whereBetween('waktu_validasi', [$tanggalMulai, $tanggalSelesai]);
            }])->get(),
            'perDokter' => Pasien::select('dokter.nama_dokter', DB::raw('COUNT(pasien.id_dokter) as total'))
                ->join('dokter', 'pasien.id_dokter', '=', 'dokter.id_dokter')
                ->whereBetween('waktu_validasi', [$tanggalMulai, $tanggalSelesai])
                ->groupBy('pasien.id_dokter', 'dokter.nama_dokter')
                ->orderBy('total', 'desc')
                ->get(),
            'perJam' => Pasien::select(
                DB::raw('HOUR(waktu_validasi) as jam'),
                DB::raw('COUNT(*) as total')
            )
            ->whereBetween('waktu_validasi', [$tanggalMulai, $tanggalSelesai])
            ->groupBy(DB::raw('HOUR(waktu_validasi)'))
            ->orderBy('jam')
            ->get(),
            'pasienDetail' => Pasien::with(['dokter', 'pemeriksa', 'ruangan', 'ujiPemeriksaan'])
                ->whereBetween('waktu_validasi', [$tanggalMulai, $tanggalSelesai])
                ->orderBy('waktu_validasi', 'desc')
                ->take(50)
                ->get(),
            'tanggalMulai' => $tanggalMulai,
            'tanggalSelesai' => $tanggalSelesai,
        ];
    }

    private function generateDataLaporanHarian($tanggal)
    {
        return [
            'tanggal' => $tanggal,
            'totalPasien' => Pasien::whereDate('waktu_validasi', $tanggal)->count(),
            'perJam' => Pasien::select(
                DB::raw('HOUR(waktu_validasi) as jam'),
                DB::raw('COUNT(*) as total')
            )
            ->whereDate('waktu_validasi', $tanggal)
            ->groupBy(DB::raw('HOUR(waktu_validasi)'))
            ->orderBy('jam')
            ->get(),
            'perJenisPemeriksaan' => JenisPemeriksaan::withCount([
                'dataPemeriksaan' => function($query) use ($tanggal) {
                    $query->whereHas('hematology.pasien', function($q) use ($tanggal) {
                        $q->whereDate('waktu_validasi', $tanggal);
                    });
                }
            ])->get(),
            'perPengirim' => Pasien::select('pengirim', DB::raw('COUNT(*) as total'))
                ->whereDate('waktu_validasi', $tanggal)
                ->whereNotNull('pengirim')
                ->groupBy('pengirim')
                ->orderBy('total', 'desc')
                ->get(),
            'perPemeriksa' => Pemeriksa::withCount(['pasien' => function($query) use ($tanggal) {
                $query->whereDate('waktu_validasi', $tanggal);
            }])->get(),
            'pasienHarian' => Pasien::with(['dokter', 'pemeriksa', 'ujiPemeriksaan'])
                ->whereDate('waktu_validasi', $tanggal)
                ->orderBy('waktu_validasi')
                ->get(),
        ];
    }

    private function generateDataLaporanBulanan($bulan)
    {
        $start = $bulan->copy()->startOfMonth();
        $end = $bulan->copy()->endOfMonth();

        return [
            'bulan' => $bulan,
            'totalPasien' => Pasien::whereBetween('waktu_validasi', [$start, $end])->count(),
            'perHari' => Pasien::select(
                DB::raw('DATE(waktu_validasi) as tanggal'),
                DB::raw('COUNT(*) as total')
            )
            ->whereBetween('waktu_validasi', [$start, $end])
            ->groupBy(DB::raw('DATE(waktu_validasi)'))
            ->orderBy('tanggal')
            ->get(),
            'perJenisPemeriksaan' => JenisPemeriksaan::withCount([
                'dataPemeriksaan' => function($query) use ($start, $end) {
                    $query->whereHas('hematology.pasien', function($q) use ($start, $end) {
                        $q->whereBetween('waktu_validasi', [$start, $end]);
                    });
                }
            ])->get(),
            'perPengirim' => Pasien::select('pengirim', DB::raw('COUNT(*) as total'))
                ->whereBetween('waktu_validasi', [$start, $end])
                ->whereNotNull('pengirim')
                ->groupBy('pengirim')
                ->orderBy('total', 'desc')
                ->get(),
            'topPemeriksa' => Pemeriksa::withCount(['pasien' => function($query) use ($start, $end) {
                $query->whereBetween('waktu_validasi', [$start, $end]);
            }])
            ->orderBy('pasien_count', 'desc')
            ->limit(10)
            ->get(),
            'pasienBulanan' => Pasien::with(['dokter', 'pemeriksa'])
                ->whereBetween('waktu_validasi', [$start, $end])
                ->orderBy('waktu_validasi', 'desc')
                ->take(100)
                ->get(),
        ];
    }

    private function generateDataLaporanTahunan($tahun)
    {
        $start = Carbon::create($tahun, 1, 1)->startOfDay();
        $end = Carbon::create($tahun, 12, 31)->endOfDay();

        return [
            'tahun' => $tahun,
            'totalPasien' => Pasien::whereBetween('waktu_validasi', [$start, $end])->count(),
            'perBulan' => Pasien::select(
                DB::raw('MONTH(waktu_validasi) as bulan'),
                DB::raw('COUNT(*) as total')
            )
            ->whereBetween('waktu_validasi', [$start, $end])
            ->groupBy(DB::raw('MONTH(waktu_validasi)'))
            ->orderBy('bulan')
            ->get(),
            'perJenisPemeriksaan' => JenisPemeriksaan::withCount([
                'dataPemeriksaan' => function($query) use ($start, $end) {
                    $query->whereHas('hematology.pasien', function($q) use ($start, $end) {
                        $q->whereBetween('waktu_validasi', [$start, $end]);
                    });
                }
            ])->get(),
            'perPengirim' => Pasien::select('pengirim', DB::raw('COUNT(*) as total'))
                ->whereBetween('waktu_validasi', [$start, $end])
                ->whereNotNull('pengirim')
                ->groupBy('pengirim')
                ->orderBy('total', 'desc')
                ->get(),
            'perPemeriksa' => Pemeriksa::withCount(['pasien' => function($query) use ($start, $end) {
                $query->whereBetween('waktu_validasi', [$start, $end]);
            }])->get(),
            'trendBulanan' => $this->getTrendBulanan($tahun),
        ];
    }

    private function getTrendPeriode($request)
    {
        $groupBy = $request->group_by ?? 'month';

        switch ($groupBy) {
            case 'day':
                return Pasien::select(
                    DB::raw('DATE(waktu_validasi) as periode'),
                    DB::raw('COUNT(*) as total')
                )
                ->whereNotNull('waktu_validasi')
                ->when($request->tanggal_mulai && $request->tanggal_selesai, function($query) use ($request) {
                    return $query->whereBetween('waktu_validasi', [
                        Carbon::parse($request->tanggal_mulai)->startOfDay(),
                        Carbon::parse($request->tanggal_selesai)->endOfDay()
                    ]);
                })
                ->groupBy(DB::raw('DATE(waktu_validasi)'))
                ->orderBy('periode')
                ->get();

            case 'week':
                return Pasien::select(
                    DB::raw('YEARWEEK(waktu_validasi, 1) as periode'),
                    DB::raw('COUNT(*) as total')
                )
                ->whereNotNull('waktu_validasi')
                ->when($request->tanggal_mulai && $request->tanggal_selesai, function($query) use ($request) {
                    return $query->whereBetween('waktu_validasi', [
                        Carbon::parse($request->tanggal_mulai)->startOfDay(),
                        Carbon::parse($request->tanggal_selesai)->endOfDay()
                    ]);
                })
                ->groupBy(DB::raw('YEARWEEK(waktu_validasi, 1)'))
                ->orderBy('periode')
                ->get();

            case 'month':
            default:
                return Pasien::select(
                    DB::raw('DATE_FORMAT(waktu_validasi, "%Y-%m") as periode'),
                    DB::raw('COUNT(*) as total')
                )
                ->whereNotNull('waktu_validasi')
                ->when($request->tanggal_mulai && $request->tanggal_selesai, function($query) use ($request) {
                    return $query->whereBetween('waktu_validasi', [
                        Carbon::parse($request->tanggal_mulai)->startOfDay(),
                        Carbon::parse($request->tanggal_selesai)->endOfDay()
                    ]);
                })
                ->groupBy(DB::raw('DATE_FORMAT(waktu_validasi, "%Y-%m")'))
                ->orderBy('periode')
                ->get();
        }
    }

    private function hitungRataWaktuValidasi($idPemeriksa, $request)
    {
        $query = Pasien::select(DB::raw('AVG(TIMESTAMPDIFF(MINUTE, tgl_pendaftaran, waktu_validasi)) as rata_waktu'))
            ->where('id_pemeriksa', $idPemeriksa)
            ->whereNotNull('waktu_validasi')
            ->whereNotNull('tgl_pendaftaran');

        if ($request->tanggal_mulai && $request->tanggal_selesai) {
            $query->whereBetween('waktu_validasi', [
                Carbon::parse($request->tanggal_mulai)->startOfDay(),
                Carbon::parse($request->tanggal_selesai)->endOfDay()
            ]);
        }

        return $query->first()->rata_waktu ?? 0;
    }

    private function getJenisPemeriksaanTerbanyak($idPemeriksa, $request)
    {
        $query = Pasien::join('pemeriksaan_hematology', 'pasien.no_lab', '=', 'pemeriksaan_hematology.no_lab')
            ->join('data_pemeriksaan', 'pemeriksaan_hematology.id_data_pemeriksaan', '=', 'data_pemeriksaan.id_data_pemeriksaan')
            ->join('jenis_pemeriksaan_1', 'data_pemeriksaan.id_jenis_pemeriksaan_1', '=', 'jenis_pemeriksaan_1.id_jenis_pemeriksaan_1')
            ->select(
                'jenis_pemeriksaan_1.nama_pemeriksaan',
                DB::raw('COUNT(DISTINCT pasien.no_lab) as total')
            )
            ->where('pasien.id_pemeriksa', $idPemeriksa)
            ->whereNotNull('pasien.waktu_validasi')
            ->groupBy('jenis_pemeriksaan_1.id_jenis_pemeriksaan_1', 'jenis_pemeriksaan_1.nama_pemeriksaan')
            ->orderBy('total', 'desc')
            ->limit(1);

        if ($request->tanggal_mulai && $request->tanggal_selesai) {
            $query->whereBetween('pasien.waktu_validasi', [
                Carbon::parse($request->tanggal_mulai)->startOfDay(),
                Carbon::parse($request->tanggal_selesai)->endOfDay()
            ]);
        }

        return $query->first();
    }

    private function getGrafik30Hari()
    {
        $startDate = Carbon::now()->subDays(30);
        $endDate = Carbon::now();

        return Pasien::select(
            DB::raw('DATE(waktu_validasi) as tanggal'),
            DB::raw('COUNT(*) as total')
        )
        ->whereBetween('waktu_validasi', [$startDate, $endDate])
        ->whereNotNull('waktu_validasi')
        ->groupBy(DB::raw('DATE(waktu_validasi)'))
        ->orderBy('tanggal')
        ->get();
    }

    private function getTrendBulanan($tahun)
    {
        $data = [];
        for ($i = 1; $i <= 12; $i++) {
            $start = Carbon::create($tahun, $i, 1)->startOfMonth();
            $end = Carbon::create($tahun, $i, 1)->endOfMonth();

            $data[] = [
                'bulan' => $start->format('F'),
                'total' => Pasien::whereBetween('waktu_validasi', [$start, $end])->count(),
                'per_jenis' => JenisPemeriksaan::withCount([
                    'dataPemeriksaan' => function($query) use ($start, $end) {
                        $query->whereHas('hematology.pasien', function($q) use ($start, $end) {
                            $q->whereBetween('waktu_validasi', [$start, $end]);
                        });
                    }
                ])->get()->pluck('data_pemeriksaan_count', 'nama_pemeriksaan')
            ];
        }
        return $data;
    }

    /**
     * =========================================================
     * EXPORT METHODS (Simplified - implementasi lengkap disesuaikan)
     * =========================================================
     */

    private function exportExcelLaporanLengkap($data)
    {
        // Implementasi export Excel
        // Gunakan library seperti Maatwebsite/Laravel-Excel
        return response()->json(['message' => 'Export Excel belum diimplementasikan']);
    }

    private function exportExcelByJenisPemeriksaan($data)
    {
        // Implementasi export Excel
        return response()->json(['message' => 'Export Excel belum diimplementasikan']);
    }

    private function exportExcelByPengirim($data)
    {
        // Implementasi export Excel
        return response()->json(['message' => 'Export Excel belum diimplementasikan']);
    }

    private function exportExcelByPemeriksa($data)
    {
        // Implementasi export Excel
        return response()->json(['message' => 'Export Excel belum diimplementasikan']);
    }

    private function exportExcelHarian($data)
    {
        // Implementasi export Excel
        return response()->json(['message' => 'Export Excel belum diimplementasikan']);
    }

    private function exportExcelBulanan($data)
    {
        // Implementasi export Excel
        return response()->json(['message' => 'Export Excel belum diimplementasikan']);
    }

    private function exportExcelTahunan($data)
    {
        // Implementasi export Excel
        return response()->json(['message' => 'Export Excel belum diimplementasikan']);
    }
}

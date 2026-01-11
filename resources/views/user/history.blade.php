@extends('layouts_user.app')

@section('content')
<!-- App hero header starts -->
<div class="app-hero-header d-flex align-items-center">
    <!-- Breadcrumb starts -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <i class="ri-home-8-line lh-1 pe-3 me-3 border-end"></i>
            <a href="{{ url('user/dashboard') }}">Home</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('user.index') }}">Dashboard</a>
        </li>
        <li class="breadcrumb-item text-primary" aria-current="page">
            History Pasien - {{ $latestPatient->rm_pasien ?? '' }}
        </li>
    </ol>
    <!-- Breadcrumb ends -->
</div>
<!-- App Hero header ends -->

<!-- App body starts -->
<div class="app-body">
    {{-- Alert pesan berhasil/gagal --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    {{-- Tampilkan error validasi --}}
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Header info pasien -->
    <div class="row gx-3 mb-4">
        <div class="col-sm-12">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="mb-2">History Pasien</h4>
                            <p class="mb-1"><strong>RM Pasien:</strong> {{ $latestPatient->rm_pasien ?? '-' }}</p>
                            <p class="mb-1"><strong>Nama Pasien:</strong> {{ $latestPatient->nama_pasien ?? '-' }}</p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <p class="mb-1"><strong>Total Kunjungan:</strong> {{ $histories->total() }}</p>
                            <p class="mb-1"><strong>Kunjungan Terakhir:</strong>
                                {{ $latestPatient ? \Carbon\Carbon::parse($latestPatient->created_at)->format('d/m/Y') : '-' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Konten utama dengan 2 kolom -->
    <div class="row gx-3">
        <!-- Kolom kiri: Tabel history -->
        <div class="col-lg-8 col-md-12">
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Riwayat Pemeriksaan</h5>
                    <div class="d-flex align-items-center gap-2">
                        <a href="{{ route('user.index') }}" class="btn btn-sm btn-secondary">Kembali ke Daftar Pasien</a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Table starts -->
                    <div class="table-outer">
                        <div class="table-responsive">
                            <table class="table m-0 align-middle">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>No Lab</th>
                                        <th>Nama Pasien</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($histories as $patient)
                                    <tr id="row-{{ $patient->no_lab }}" class="{{ request('view') == $patient->no_lab ? 'table-active' : '' }}">
                                        <td>
                                            @php
                                                // Parse tanggal dari nomor_registrasi (yymmdd)
                                                $nomorRegistrasi = $patient->nomor_registrasi ?? '';

                                                if (strlen($nomorRegistrasi) >= 6) {
                                                    $datePart = substr($nomorRegistrasi, 0, 6);

                                                    $year  = substr($datePart, 0, 2);
                                                    $month = substr($datePart, 2, 2);
                                                    $day   = substr($datePart, 4, 2);

                                                    // Konversi tahun 2 digit ke 4 digit
                                                    $year = (int)$year < 50 ? '20' . $year : '19' . $year;

                                                    echo "{$day}/{$month}/{$year}";
                                                } else {
                                                    echo '-';
                                                }
                                            @endphp
                                        </td>
                                        <td>{{ $patient->no_lab ?? '-'}}</td>
                                        <td>{{ $patient->nama_pasien ?? '-'}}</td>
                                        <td>
                                            @if($patient->id_pemeriksa && $patient->waktu_validasi)
                                                <span class="badge bg-success">Selesai</span>
                                            @else
                                                <span class="badge bg-warning">Diproses</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('user.print', $patient->no_lab) }}" target="_blank" class="btn btn-sm btn-secondary" title="Print">
                                                    <i class="ri-printer-line"></i>
                                                </a>
                                                <a href="{{ route('user.history', ['rm_pasien' => $latestPatient->rm_pasien, 'view' => $patient->no_lab]) }}"
                                                   class="btn btn-sm btn-primary" title="View Detail">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Tidak ada data history untuk pasien ini.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>

                            <!-- Pagination -->
                            <div class="d-flex justify-content-center mt-3">
                                {{ $histories->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                    <!-- Table ends -->
                </div>
            </div>
        </div>

        <!-- Kolom kanan: History Panel -->
        <div class="col-lg-4 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Detail Pemeriksaan</h5>
                </div>
                <div class="card-body p-2">
                    @if(request('view'))
                        @php
                            $selectedPatient = $histories->firstWhere('no_lab', request('view'));

                            if($selectedPatient) {
                                $no_lab = $selectedPatient->no_lab;

                                // 1. Hematology
                                $hematology = DB::table('pemeriksaan_hematology as ph')
                                    ->leftJoin('data_pemeriksaan as dp', 'ph.id_data_pemeriksaan', '=', 'dp.id_data_pemeriksaan')
                                    ->leftJoin('jenis_pemeriksaan_1 as jp1', 'dp.id_jenis_pemeriksaan_1', '=', 'jp1.id_jenis_pemeriksaan_1')
                                    ->where('ph.no_lab', $no_lab)
                                    ->whereNull('ph.deleted_at')
                                    ->select(
                                        'ph.hasil_pengujian',
                                        'dp.data_pemeriksaan',
                                        'dp.satuan as satuan_pemeriksaan',
                                        'dp.kode_pemeriksaan',
                                        'jp1.nama_pemeriksaan as jenis_pemeriksaan_nama',
                                        DB::raw("COALESCE(jp1.nama_pemeriksaan, 'Hematology') as kelompok_pemeriksaan")
                                    )
                                    ->whereNotNull('ph.hasil_pengujian')
                                    ->where('ph.hasil_pengujian', '!=', '')
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

                                // 2. Kimia
                                $kimia = DB::table('pemeriksaan_kimia as pk')
                                    ->leftJoin('data_pemeriksaan as dp', 'pk.id_data_pemeriksaan', '=', 'dp.id_data_pemeriksaan')
                                    ->leftJoin('jenis_pemeriksaan_1 as jp1', 'dp.id_jenis_pemeriksaan_1', '=', 'jp1.id_jenis_pemeriksaan_1')
                                    ->where('pk.no_lab', $no_lab)
                                    ->whereNull('pk.deleted_at')
                                    ->select(
                                        'pk.hasil_pengujian',
                                        'dp.data_pemeriksaan',
                                        'dp.satuan as satuan_pemeriksaan',
                                        'dp.kode_pemeriksaan',
                                        'jp1.nama_pemeriksaan as jenis_pemeriksaan_nama',
                                        DB::raw("COALESCE(jp1.nama_pemeriksaan, 'Kimia') as kelompok_pemeriksaan")
                                    )
                                    ->whereNotNull('pk.hasil_pengujian')
                                    ->where('pk.hasil_pengujian', '!=', '')
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

                                // 3. Hasil Lain
                                $hasil_lain = DB::table('hasil_pemeriksaan_lain as hpl')
                                    ->leftJoin('data_pemeriksaan as dp', 'hpl.id_data_pemeriksaan', '=', 'dp.id_data_pemeriksaan')
                                    ->leftJoin('jenis_pemeriksaan_1 as jp1', 'dp.id_jenis_pemeriksaan_1', '=', 'jp1.id_jenis_pemeriksaan_1')
                                    ->where('hpl.no_lab', $no_lab)
                                    ->whereNull('hpl.deleted_at')
                                    ->select(
                                        'hpl.hasil_pengujian',
                                        'dp.data_pemeriksaan',
                                        'dp.satuan as satuan_pemeriksaan',
                                        'dp.kode_pemeriksaan',
                                        'jp1.nama_pemeriksaan as jenis_pemeriksaan_nama',
                                        DB::raw("COALESCE(jp1.nama_pemeriksaan, 'Lainnya') as kelompok_pemeriksaan")
                                    )
                                    ->whereNotNull('hpl.hasil_pengujian')
                                    ->where('hpl.hasil_pengujian', '!=', '')
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

                                // Gabungkan semua data dan kelompokkan
                                $groupedExaminations = collect();

                                // Tambahkan hematology ke kelompoknya
                                foreach ($hematology as $item) {
                                    $kelompok = $item->kelompok_pemeriksaan;
                                    if (!$groupedExaminations->has($kelompok)) {
                                        $groupedExaminations->put($kelompok, collect());
                                    }
                                    $groupedExaminations[$kelompok]->push((object)[
                                        'nama' => $item->data_pemeriksaan ?? 'Hematology',
                                        'hasil' => $item->hasil_pengujian,
                                        'satuan' => $item->satuan_pemeriksaan ?? ''
                                    ]);
                                }

                                // Tambahkan kimia ke kelompoknya
                                foreach ($kimia as $item) {
                                    $kelompok = $item->kelompok_pemeriksaan;
                                    if (!$groupedExaminations->has($kelompok)) {
                                        $groupedExaminations->put($kelompok, collect());
                                    }
                                    $groupedExaminations[$kelompok]->push((object)[
                                        'nama' => $item->data_pemeriksaan ?? 'Kimia',
                                        'hasil' => $item->hasil_pengujian,
                                        'satuan' => $item->satuan_pemeriksaan ?? ''
                                    ]);
                                }

                                // Tambahkan hasil lain ke kelompoknya
                                foreach ($hasil_lain as $item) {
                                    $kelompok = $item->kelompok_pemeriksaan;
                                    if (!$groupedExaminations->has($kelompok)) {
                                        $groupedExaminations->put($kelompok, collect());
                                    }
                                    $groupedExaminations[$kelompok]->push((object)[
                                        'nama' => $item->data_pemeriksaan ?? 'Lainnya',
                                        'hasil' => $item->hasil_pengujian,
                                        'satuan' => $item->satuan_pemeriksaan ?? ''
                                    ]);
                                }
                            }
                        @endphp

                        @if($selectedPatient)
                            @if($groupedExaminations->count() > 0)
                                <div class="examination-horizontal">
                                    @foreach($groupedExaminations as $kelompok => $items)
                                        <div class="kelompok-pemeriksaan mb-2">
                                            <div class="kelompok-header p-1">
                                                <small class="fw-semibold text-dark">{{ $kelompok }}</small>
                                            </div>
                                            <div class="items-list">
                                                @foreach($items as $exam)
                                                    <div class="exam-item d-flex align-items-center border-bottom py-1">
                                                        <div class="exam-name flex-shrink-0 me-2" style="width: 60%;">
                                                            <small class="text-muted">{{ $exam->nama }}</small>
                                                        </div>
                                                        <div class="exam-result d-flex align-items-center flex-grow-1">
                                                            <span class="fw-bold me-1">{{ $exam->hasil }}</span>
                                                            @if($exam->satuan)
                                                                <small class="text-secondary">{{ $exam->satuan }}</small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach

                                    @if($selectedPatient->catatan || $selectedPatient->keluhan)
                                        <div class="mt-2 pt-2 border-top">
                                            @if($selectedPatient->keluhan)
                                                <div class="mb-1">
                                                    <small class="text-muted d-block">Keluhan:</small>
                                                    <small>{{ $selectedPatient->keluhan }}</small>
                                                </div>
                                            @endif
                                            @if($selectedPatient->catatan)
                                                <div>
                                                    <small class="text-muted d-block">Catatan:</small>
                                                    <small>{{ $selectedPatient->catatan }}</small>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="text-center py-2">
                                    <small class="text-muted">Belum ada hasil pemeriksaan</small>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-2">
                                <small class="text-warning">Data tidak ditemukan</small>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-3">
                            <small class="text-muted">Pilih data dari tabel</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <!-- End Kolom kanan: History Panel -->
    </div>
</div>

<style>
.table-active {
    background-color: rgba(13, 110, 253, 0.1) !important;
    border-left: 3px solid #0d6efd;
}
.history-detail .table th,
.history-detail .table td {
    padding: 0.3rem 0.5rem;
    font-size: 0.875rem;
}
.history-detail .table thead th {
    background-color: #f8f9fa;
    font-weight: 600;
    font-size: 0.8rem;
    text-transform: uppercase;
}
.history-detail .badge {
    font-size: 0.7rem;
    padding: 0.25em 0.6em;
}
</style>
@endsection

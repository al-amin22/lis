@extends('layouts.app')

@section('content')
@php
    use Carbon\Carbon;

    // Ringkasan jenis kelamin
    $pria = $perJenisKelamin->whereIn('jenis_kelamin', ['L','LAKI-LAKI','PRIA','MALE'])->sum('total');
    $wanita = $perJenisKelamin->whereIn('jenis_kelamin', ['P','PEREMPUAN','WANITA','FEMALE'])->sum('total');
    $totalJK = $perJenisKelamin->sum('total');
    $lainnya = max(0, $totalJK - $pria - $wanita);

    $totalPas = max(1, (int) $totalPasien);
    $peakHour = $perJamRaw->sortByDesc('total')->first();

    // Tanggal untuk form
    $tglMulai = request('tanggal_mulai', now()->subDays(30)->format('Y-m-d'));
    $tglSelesai = request('tanggal_selesai', now()->format('Y-m-d'));
@endphp

<div class="container-fluid">
    <!-- FILTER FORM -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-primary">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-filter"></i> Filter Periode Laporan</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('laporan.lengkap') }}" class="row g-3">
                        <div class="col-md-3">
                            <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                            <input type="date"
                                   class="form-control"
                                   id="tanggal_mulai"
                                   name="tanggal_mulai"
                                   value="{{ $tglMulai }}"
                                   max="{{ now()->format('Y-m-d') }}"
                                   required>
                        </div>

                        <div class="col-md-3">
                            <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                            <input type="date"
                                   class="form-control"
                                   id="tanggal_selesai"
                                   name="tanggal_selesai"
                                   value="{{ $tglSelesai }}"
                                   max="{{ now()->format('Y-m-d') }}"
                                   required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Aksi</label>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Tampilkan Laporan
                                </button>

                                <div class="dropdown">
                                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-bolt"></i> Periode Cepat
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item filter-quick" href="#" data-start="{{ now()->subDays(7)->format('Y-m-d') }}" data-end="{{ now()->format('Y-m-d') }}">
                                                <span class="badge bg-primary me-2">7H</span> Minggu Ini
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item filter-quick" href="#" data-start="{{ now()->subDays(30)->format('Y-m-d') }}" data-end="{{ now()->format('Y-m-d') }}">
                                                <span class="badge bg-success me-2">30H</span> Bulan Ini
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item filter-quick" href="#" data-start="{{ now()->startOfMonth()->format('Y-m-d') }}" data-end="{{ now()->endOfMonth()->format('Y-m-d') }}">
                                                <span class="badge bg-info me-2">BM</span> Bulan Berjalan
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item filter-quick" href="#" data-start="{{ now()->subDays(90)->format('Y-m-d') }}" data-end="{{ now()->format('Y-m-d') }}">
                                                <span class="badge bg-warning me-2">3B</span> 3 Bulan Terakhir
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item filter-quick" href="#" data-start="{{ now()->subDays(365)->format('Y-m-d') }}" data-end="{{ now()->format('Y-m-d') }}">
                                                <span class="badge bg-danger me-2">1T</span> 1 Tahun Terakhir
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('laporan.lengkap') }}">
                                                <i class="fas fa-redo"></i> Reset Filter
                                            </a>
                                        </li>
                                    </ul>
                                </div>

                                <a href="?{{ http_build_query(array_merge(request()->all(), ['format' => 'pdf'])) }}"
                                   class="btn btn-danger ms-auto">
                                    <i class="fas fa-file-pdf"></i> Export PDF
                                </a>

                                <a href="?{{ http_build_query(array_merge(request()->all(), ['format' => 'excel'])) }}"
                                   class="btn btn-success">
                                    <i class="fas fa-file-excel"></i> Export Excel
                                </a>
                            </div>
                        </div>
                    </form>

                    <!-- Info Periode -->
                    <div class="mt-3">
                        <div class="alert alert-info mb-0 py-2 d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-calendar-alt me-2"></i>
                                <strong>Periode Aktif:</strong>
                                {{ Carbon::parse($tanggalMulai)->translatedFormat('d F Y') }}
                                hingga
                                {{ Carbon::parse($tanggalSelesai)->translatedFormat('d F Y') }}
                                <span class="badge bg-dark ms-2">{{ $hari }} Hari</span>
                            </div>
                            <div>
                                <span class="badge bg-primary me-2">Total: {{ $totalPasien }} Pasien</span>
                                <span class="badge bg-success">Rata-rata: {{ number_format($rataRataPerHari, 1) }}/hari</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- LAPORAN UTAMA -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0"><i class="fas fa-file-alt"></i> Laporan Lengkap Statistik</h4>
                        <small class="mb-0">
                            Dicetak: {{ now()->translatedFormat('d F Y H:i') }}
                        </small>
                    </div>
                    <button class="btn btn-light btn-sm" onclick="window.print()">
                        <i class="fas fa-print"></i> Print
                    </button>
                </div>

                <div class="card-body">
                    <!-- Ringkasan Statistik -->
                    <div class="row mb-4">
                        <div class="col-md-3 text-center mb-3">
                            <div class="stat-card bg-primary text-white p-3 rounded">
                                <h1 class="display-4 mb-0">{{ $totalPasien }}</h1>
                                <p class="mb-0">Total Pasien</p>
                                <small>{{ number_format($rataRataPerHari, 1) }}/hari</small>
                            </div>
                        </div>

                        <div class="col-md-3 text-center mb-3">
                            <div class="stat-card bg-info text-white p-3 rounded">
                                <h1 class="display-4 mb-0">{{ $perJenisPemeriksaan->count() }}</h1>
                                <p class="mb-0">Jenis Pemeriksaan</p>
                                <small>{{ $perJenisPemeriksaan->sum('data_pemeriksaan_count') }} total</small>
                            </div>
                        </div>

                        <div class="col-md-3 text-center mb-3">
                            <div class="stat-card bg-success text-white p-3 rounded">
                                <h1 class="display-4 mb-0">{{ $perPengirim->count() }}</h1>
                                <p class="mb-0">Total Pengirim</p>
                                <small>10 Teratas Ditampilkan</small>
                            </div>
                        </div>

                        <div class="col-md-3 text-center mb-3">
                            <div class="stat-card bg-warning text-dark p-3 rounded">
                                <h1 class="display-4 mb-0">{{ $perPemeriksa->count() }}</h1>
                                <p class="mb-0">Total Pemeriksa</p>
                                <small>10 Teratas Ditampilkan</small>
                            </div>
                        </div>
                    </div>

                    <!-- Distribusi -->
                    <div class="row mb-4">
                        <!-- Jenis Kelamin -->
                        <div class="col-md-6 mb-3">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="fas fa-venus-mars"></i> Distribusi Jenis Kelamin</h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="jenisKelaminChart"
                                            height="150"
                                            data-pria="{{ $pria }}"
                                            data-wanita="{{ $wanita }}"
                                            data-lainnya="{{ $lainnya }}"></canvas>

                                    <div class="mt-3">
                                        <div class="row text-center">
                                            <div class="col-4">
                                                <h4 class="text-primary mb-0">{{ $pria }}</h4>
                                                <small class="text-muted">Pria ({{ $totalPas > 0 ? number_format($pria/$totalPas*100, 1) : 0 }}%)</small>
                                            </div>
                                            <div class="col-4">
                                                <h4 class="text-danger mb-0">{{ $wanita }}</h4>
                                                <small class="text-muted">Wanita ({{ $totalPas > 0 ? number_format($wanita/$totalPas*100, 1) : 0 }}%)</small>
                                            </div>
                                            <div class="col-4">
                                                <h4 class="text-secondary mb-0">{{ $lainnya }}</h4>
                                                <small class="text-muted">Lainnya ({{ $totalPas > 0 ? number_format($lainnya/$totalPas*100, 1) : 0 }}%)</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Per Jam -->
                        <div class="col-md-6 mb-3">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="fas fa-clock"></i> Distribusi Per Jam Validasi</h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="perJamChart"
                                            height="150"
                                            data-values="{{ $perJamCsv }}"></canvas>

                                    <div class="mt-3">
                                        @if($peakHour)
                                        <div class="alert alert-light mb-0">
                                            <i class="fas fa-chart-line text-primary"></i>
                                            <strong>Jam Puncak Validasi:</strong>
                                            <span class="badge bg-dark ms-2">
                                                {{ sprintf('%02d', $peakHour->jam) }}:00 - {{ sprintf('%02d', $peakHour->jam + 1) }}:00
                                            </span>
                                            <span class="ms-2">
                                                ({{ $peakHour->total }} pasien • {{ $totalPas > 0 ? number_format($peakHour->total/$totalPas*100, 1) : 0 }}%)
                                            </span>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Top 10 Jenis Pemeriksaan -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="fas fa-vial"></i> Top 10 Jenis Pemeriksaan</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Jenis Pemeriksaan</th>
                                                    <th class="text-center">Total</th>
                                                    <th class="text-center">% dari Total</th>
                                                    <th class="text-center">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($perJenisPemeriksaan->sortByDesc('data_pemeriksaan_count')->take(10) as $index => $jenis)
                                                    @php
                                                        $percentage = $totalPas > 0 ? ($jenis->data_pemeriksaan_count / $totalPas * 100) : 0;
                                                        $pctAttr = number_format($percentage, 1, '.', '');
                                                    @endphp
                                                    <tr>
                                                        <td>
                                                            <span class="badge bg-{{ $index < 3 ? 'danger' : 'secondary' }}">
                                                                {{ $index + 1 }}
                                                            </span>
                                                        </td>
                                                        <td>{{ $jenis->nama_pemeriksaan }}</td>
                                                        <td class="text-center">
                                                            <strong>{{ $jenis->data_pemeriksaan_count }}</strong>
                                                        </td>
                                                        <td style="min-width:220px;">
                                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                                <small>{{ number_format($percentage, 1) }}%</small>
                                                                <small class="text-muted">{{ $jenis->data_pemeriksaan_count }} dari {{ $totalPasien }}</small>
                                                            </div>
                                                            <div class="progress" style="height: 20px;">
                                                                <div class="progress-bar progress-dynamic"
                                                                     role="progressbar"
                                                                     data-progress="{{ $pctAttr }}"
                                                                     style="background-color: {{ $index < 3 ? '#dc3545' : '#28a745' }}"></div>
                                                            </div>
                                                        </td>
                                                        <td class="text-center">
                                                            @if($index < 3)
                                                                <span class="badge bg-danger">TOP {{ $index + 1 }}</span>
                                                            @elseif($percentage > 15)
                                                                <span class="badge bg-warning text-dark">POPULER</span>
                                                            @elseif($percentage > 5)
                                                                <span class="badge bg-info">NORMAL</span>
                                                            @else
                                                                <span class="badge bg-secondary">RENDAH</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Top Pengirim & Pemeriksa -->
                    <div class="row mb-4">
                        <!-- Top Pengirim -->
                        <div class="col-md-6 mb-3">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="fas fa-user-md"></i> Top 10 Pengirim / Dokter</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Nama Pengirim</th>
                                                    <th class="text-center">Total</th>
                                                    <th class="text-center">Persentase</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($perPengirim->take(10) as $index => $pengirim)
                                                    @php
                                                        $percentage = $totalPas > 0 ? ($pengirim->total / $totalPas * 100) : 0;
                                                    @endphp
                                                    <tr>
                                                        <td>
                                                            <span class="badge bg-{{ $index < 3 ? 'primary' : 'secondary' }}">
                                                                {{ $index + 1 }}
                                                            </span>
                                                        </td>
                                                        <td>{{ $pengirim->pengirim ?: 'Tidak Diketahui' }}</td>
                                                        <td class="text-center">
                                                            <strong>{{ $pengirim->total }}</strong>
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="d-flex align-items-center">
                                                                <div class="progress flex-grow-1 me-2" style="height: 10px;">
                                                                    <div class="progress-bar bg-info"
                                                                         style="width: {{ min($percentage, 100) }}%"></div>
                                                                </div>
                                                                <span class="badge bg-dark">{{ number_format($percentage, 1) }}%</span>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    @if($perPengirim->count() > 10)
                                    <div class="text-center mt-2">
                                        <small class="text-muted">
                                            + {{ $perPengirim->count() - 10 }} pengirim lainnya
                                        </small>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Top Pemeriksa -->
                        <div class="col-md-6 mb-3">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="fas fa-microscope"></i> Top 10 Pemeriksa</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Nama Pemeriksa</th>
                                                    <th class="text-center">Total</th>
                                                    <th class="text-center">Efisiensi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($perPemeriksa->take(10) as $index => $pemeriksa)
                                                    @php
                                                        $total = $pemeriksa->pasien_count;
                                                        $avgPerDay = $total > 0 ? round($total / $hari, 1) : 0;
                                                    @endphp
                                                    <tr>
                                                        <td>
                                                            <span class="badge bg-{{ $index < 3 ? 'warning' : 'secondary' }}">
                                                                {{ $index + 1 }}
                                                            </span>
                                                        </td>
                                                        <td>{{ $pemeriksa->nama_pemeriksa }}</td>
                                                        <td class="text-center">
                                                            <strong>{{ $total }}</strong>
                                                            <br>
                                                            <small class="text-muted">{{ $avgPerDay }}/hari</small>
                                                        </td>
                                                        <td class="text-center">
                                                            @if($avgPerDay >= 5)
                                                                <span class="badge bg-success">Sangat Efisien</span>
                                                            @elseif($avgPerDay >= 3)
                                                                <span class="badge bg-warning text-dark">Cukup Efisien</span>
                                                            @elseif($avgPerDay >= 1)
                                                                <span class="badge bg-info">Normal</span>
                                                            @else
                                                                <span class="badge bg-secondary">Minimal</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Detail Pasien -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="fas fa-list"></i> 50 Pasien Terbaru (Periode Terpilih)</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-striped mb-0">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th>No Lab</th>
                                                    <th>Nama Pasien</th>
                                                    <th>Umur</th>
                                                    <th>JK</th>
                                                    <th>Pengirim</th>
                                                    <th>Pemeriksa</th>
                                                    <th>Waktu Validasi</th>
                                                    <th>Jenis Pemeriksaan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($pasienDetail as $pasien)
                                                    <tr>
                                                        <td><strong>{{ $pasien->no_lab }}</strong></td>
                                                        <td>{{ $pasien->nama_pasien }}</td>
                                                        <td>{{ $pasien->umur }} thn</td>
                                                        <td>
                                                            @if(in_array($pasien->jenis_kelamin, ['L', 'LAKI-LAKI', 'PRIA']))
                                                                <span class="badge bg-primary">L</span>
                                                            @elseif(in_array($pasien->jenis_kelamin, ['P', 'PEREMPUAN', 'WANITA']))
                                                                <span class="badge bg-danger">P</span>
                                                            @else
                                                                <span class="badge bg-secondary">{{ substr($pasien->jenis_kelamin, 0, 1) }}</span>
                                                            @endif
                                                        </td>
                                                        <td>{{ \Illuminate\Support\Str::limit($pasien->pengirim ?: '-', 20) }}</td>
                                                        <td>{{ $pasien->pemeriksa->nama_pemeriksa ?? '-' }}</td>
                                                        <td>{{ Carbon::parse($pasien->waktu_validasi)->format('d/m H:i') }}</td>
                                                        <td>
                                                            <span class="badge bg-info">
                                                                {{ \Illuminate\Support\Str::limit($pasien->jenis_pemeriksaan_nama ?? '-', 25) }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Ringkasan Akhir -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="fas fa-chart-pie"></i> Ringkasan Statistik</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <ul class="list-group">
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <span><i class="fas fa-users text-primary me-2"></i> Total Pasien</span>
                                                    <span class="badge bg-primary rounded-pill">{{ $totalPasien }}</span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <span><i class="fas fa-calendar-day text-info me-2"></i> Rata-rata Pasien/Hari</span>
                                                    <span class="badge bg-info rounded-pill">{{ number_format($rataRataPerHari, 1) }}</span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <span><i class="fas fa-vial text-success me-2"></i> Pemeriksaan Terpopuler</span>
                                                    <span class="badge bg-success rounded-pill">
                                                        {{ $perJenisPemeriksaan->sortByDesc('data_pemeriksaan_count')->first()->nama_pemeriksaan ?? '-' }}
                                                    </span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <span><i class="fas fa-clock text-warning me-2"></i> Jam Puncak Validasi</span>
                                                    <span class="badge bg-warning text-dark rounded-pill">
                                                        {{ $peakHour ? sprintf('%02d:00-%02d:00', $peakHour->jam, $peakHour->jam + 1) : '00:00-01:00' }}
                                                    </span>
                                                </li>
                                            </ul>
                                        </div>

                                        <div class="col-md-6">
                                            <ul class="list-group">
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <span><i class="fas fa-star text-danger me-2"></i> Pemeriksa Terproduktif</span>
                                                    <span class="badge bg-danger rounded-pill">
                                                        {{ $perPemeriksa->sortByDesc('pasien_count')->first()->nama_pemeriksa ?? '-' }}
                                                    </span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <span><i class="fas fa-user-md text-secondary me-2"></i> Dokter Terbanyak Referal</span>
                                                    <span class="badge bg-secondary rounded-pill">
                                                        {{ $perDokter->first()->nama_dokter ?? '-' }}
                                                    </span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <span><i class="fas fa-venus-mars text-primary me-2"></i> Rasio Pria:Wanita</span>
                                                    <span class="badge bg-primary rounded-pill">
                                                        {{ $pria }}:{{ $wanita }}
                                                    </span>
                                                </li>
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <span><i class="fas fa-calendar-alt text-success me-2"></i> Periode Laporan</span>
                                                    <span class="badge bg-success rounded-pill">{{ $hari }} Hari</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer text-muted text-center">
                                    <small>
                                        <i class="fas fa-info-circle"></i>
                                        Laporan ini dihasilkan pada {{ now()->translatedFormat('d F Y H:i:s') }}
                                        berdasarkan periode yang dipilih.
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                </div><!-- card-body -->
            </div>
        </div>
    </div>
</div>

{{-- Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // ====================
    // CHART JENIS KELAMIN
    // ====================
    const jkChartEl = document.getElementById('jenisKelaminChart');
    if (jkChartEl) {
        const pria = parseInt(jkChartEl.dataset.pria) || 0;
        const wanita = parseInt(jkChartEl.dataset.wanita) || 0;
        const lainnya = parseInt(jkChartEl.dataset.lainnya) || 0;

        new Chart(jkChartEl, {
            type: 'doughnut',
            data: {
                labels: ['Pria', 'Wanita', 'Lainnya'],
                datasets: [{
                    data: [pria, wanita, lainnya],
                    backgroundColor: ['#007bff', '#dc3545', '#6c757d'],
                    borderWidth: 1,
                    hoverOffset: 15
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { padding: 20 }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) label += ': ';
                                const total = pria + wanita + lainnya;
                                const percentage = total > 0 ? ((context.raw / total) * 100).toFixed(1) : 0;
                                label += context.raw + ' (' + percentage + '%)';
                                return label;
                            }
                        }
                    }
                }
            }
        });
    }

    // ====================
    // CHART PER JAM
    // ====================
    const jamChartEl = document.getElementById('perJamChart');
    if (jamChartEl) {
        const csv = jamChartEl.dataset.values || '';
        const values = csv.split(',').map(v => parseInt(v) || 0);

        // Buat label jam
        const labels = Array.from({length: 24}, (_, i) => {
            return i.toString().padStart(2, '0') + ':00';
        });

        // Warna berdasarkan nilai
        const backgroundColors = values.map(val => {
            const maxVal = Math.max(...values);
            if (maxVal === 0) return 'rgba(40, 167, 69, 0.5)';
            const opacity = 0.3 + (val / maxVal) * 0.7;
            return `rgba(40, 167, 69, ${opacity})`;
        });

        new Chart(jamChartEl, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Validasi',
                    data: values,
                    backgroundColor: backgroundColors,
                    borderColor: '#28a745',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 },
                        title: { display: true, text: 'Jumlah Pasien' }
                    },
                    x: {
                        ticks: { maxTicksLimit: 12 },
                        title: { display: true, text: 'Jam Validasi' }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `Jam ${context.label}: ${context.raw} pasien`;
                            }
                        }
                    }
                }
            }
        });
    }

    // ====================
    // PROGRESS BAR ANIMATION
    // ====================
    const progressBars = document.querySelectorAll('.progress-dynamic');
    progressBars.forEach((bar, index) => {
        const raw = bar.dataset.progress || '0';
        const target = parseFloat(raw.replace(',', '.')) || 0;

        setTimeout(() => {
            bar.style.transition = 'width 1s ease-in-out';
            bar.style.width = Math.min(target, 100) + '%';
            bar.setAttribute('aria-valuenow', target);
        }, 100 + (index * 50));
    });

    // ====================
    // FILTER QUICK PERIOD
    // ====================
    document.querySelectorAll('.filter-quick').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const start = this.dataset.start;
            const end = this.dataset.end;

            document.getElementById('tanggal_mulai').value = start;
            document.getElementById('tanggal_selesai').value = end;

            // Submit form
            this.closest('form').submit();
        });
    });

    // ====================
    // DATE VALIDATION
    // ====================
    const tglMulai = document.getElementById('tanggal_mulai');
    const tglSelesai = document.getElementById('tanggal_selesai');

    if (tglMulai && tglSelesai) {
        // Set max date ke hari ini
        const today = new Date().toISOString().split('T')[0];
        tglMulai.max = today;
        tglSelesai.max = today;

        tglMulai.addEventListener('change', function() {
            tglSelesai.min = this.value;
            if (tglSelesai.value && tglSelesai.value < this.value) {
                tglSelesai.value = this.value;
            }
        });

        tglSelesai.addEventListener('change', function() {
            if (tglMulai.value && this.value < tglMulai.value) {
                alert('Tanggal selesai tidak boleh kurang dari tanggal mulai');
                this.value = tglMulai.value;
            }
        });

        // Inisialisasi min date untuk selesai
        if (tglMulai.value) {
            tglSelesai.min = tglMulai.value;
        }
    }

    // ====================
    // PRINT STYLING
    // ====================
    const printBtn = document.querySelector('[onclick="window.print()"]');
    if (printBtn) {
        printBtn.addEventListener('click', function() {
            // Sembunyikan filter card saat print
            const filterCard = document.querySelector('.card.border-primary');
            if (filterCard) {
                const originalDisplay = filterCard.style.display;
                filterCard.style.display = 'none';

                window.print();

                // Kembalikan setelah print
                setTimeout(() => {
                    filterCard.style.display = originalDisplay;
                }, 100);
            }
        });
    }
});
</script>

<style>
/* Styling Umum */
.stat-card {
    transition: transform .25s ease, box-shadow .25s ease;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}
.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 15px rgba(0,0,0,0.15);
}

.table th {
    background-color: #f8f9fa;
    border-top: 1px solid #dee2e6;
    font-weight: 600;
    color: #495057;
}

.badge {
    font-size: 0.85em;
    padding: 0.35em 0.65em;
    font-weight: 500;
}

.list-group-item {
    border-left: none;
    border-right: none;
    padding: 0.75rem 1rem;
}

.progress {
    border-radius: 10px;
    background: #e9ecef;
    overflow: hidden;
    height: 20px;
}

.progress-bar {
    height: 100%;
    border-radius: 10px;
}

.card {
    border: 1px solid rgba(0,0,0,.125);
    box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,.075);
    margin-bottom: 1rem;
}

.card-header {
    border-bottom: 1px solid rgba(0,0,0,.125);
}

/* Responsive */
@media (max-width: 768px) {
    .display-4 {
        font-size: 2rem;
    }

    .btn {
        width: 100%;
        margin-bottom: 0.5rem;
    }

    .dropdown {
        width: 100%;
    }

    .dropdown-toggle {
        width: 100%;
    }

    .d-flex.gap-2 {
        flex-wrap: wrap;
    }
}

/* Print Styles */
@media print {
    .btn, .dropdown, .form-control, .form-label,
    .alert:not(.alert-info), .filter-quick {
        display: none !important;
    }

    .card.border-primary {
        display: none !important;
    }

    .card {
        border: 1px solid #000 !important;
        box-shadow: none !important;
    }

    .card-header {
        background-color: #f8f9fa !important;
        color: #000 !important;
        border-bottom: 2px solid #000 !important;
    }

    .bg-success, .bg-primary, .bg-info, .bg-warning {
        background-color: #f8f9fa !important;
        color: #000 !important;
    }

    .text-white {
        color: #000 !important;
    }

    .progress, .progress-bar {
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    .stat-card {
        border: 1px solid #000 !important;
        box-shadow: none !important;
    }

    .table {
        border: 1px solid #000;
    }

    .table th, .table td {
        border: 1px solid #000;
    }

    .badge {
        border: 1px solid #000;
        background-color: #fff !important;
        color: #000 !important;
    }
}

/* Hover Effects */
.table-hover tbody tr:hover {
    background-color: rgba(0,123,255,0.05);
}

.dropdown-item:hover {
    background-color: #f8f9fa;
}

/* Animation */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.card {
    animation: fadeIn 0.5s ease-out;
}

/* Custom Scrollbar */
::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
    background: #555;
}
</style>
@endsection

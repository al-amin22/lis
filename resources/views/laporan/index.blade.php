@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <!-- Card Dashboard -->
                        <div class="col-md-3 mb-4">
                            <a href="{{ route('laporan.dashboard') }}" class="card text-white bg-info h-100 text-decoration-none">
                                <div class="card-body text-center">
                                    <i class="fas fa-tachometer-alt fa-3x mb-3"></i>
                                    <h5 class="card-title">Dashboard</h5>
                                    <p class="card-text">Statistik Real-time</p>
                                </div>
                            </a>
                        </div>

                        <!-- Card Laporan Lengkap -->
                        <div class="col-md-3 mb-4">
                            <a href="{{ route('laporan.lengkap', [
                                    'tanggal_mulai' => now()->subDays(30)->format('Y-m-d'),
                                    'tanggal_selesai' => now()->format('Y-m-d'),
                                    'format' => 'view'
                                ]) }}"
                            class="card text-white bg-success h-100 text-decoration-none">
                                <div class="card-body text-center">
                                    <i class="fas fa-file-alt fa-3x mb-3"></i>
                                    <h5 class="card-title">Laporan Lengkap</h5>
                                    <p class="card-text">Statistik Menyeluruh (30 hari)</p>
                                </div>
                            </a>
                        </div>


                        <!-- Card Jenis Pemeriksaan -->
                        <div class="col-md-3 mb-4">
                            <a href="#modalJenisPemeriksaan" data-toggle="modal" class="card text-white bg-warning h-100 text-decoration-none">
                                <div class="card-body text-center">
                                    <i class="fas fa-vial fa-3x mb-3"></i>
                                    <h5 class="card-title">By Jenis Pemeriksaan</h5>
                                    <p class="card-text">Analisis per Tes</p>
                                </div>
                            </a>
                        </div>

                        <!-- Card Pengirim -->
                        <div class="col-md-3 mb-4">
                            <a href="#modalPengirim" data-toggle="modal" class="card text-white bg-danger h-100 text-decoration-none">
                                <div class="card-body text-center">
                                    <i class="fas fa-user-md fa-3x mb-3"></i>
                                    <h5 class="card-title">By Pengirim</h5>
                                    <p class="card-text">Statistik Dokter/Pengirim</p>
                                </div>
                            </a>
                        </div>

                        <!-- Card Pemeriksa -->
                        <div class="col-md-3 mb-4">
                            <a href="#modalPemeriksa" data-toggle="modal" class="card text-white bg-primary h-100 text-decoration-none">
                                <div class="card-body text-center">
                                    <i class="fas fa-microscope fa-3x mb-3"></i>
                                    <h5 class="card-title">By Pemeriksa</h5>
                                    <p class="card-text">Kinerja Analis</p>
                                </div>
                            </a>
                        </div>

                        <!-- Card Harian -->
                        <div class="col-md-3 mb-4">
                            <a href="#modalHarian" data-toggle="modal" class="card text-red bg-secondary h-100 text-decoration-none">
                                <div class="card-body text-center">
                                    <i class="fas fa-calendar-day fa-3x mb-3"></i>
                                    <h5 class="card-title">Laporan Harian</h5>
                                    <p class="card-text">Aktivitas Harian</p>
                                </div>
                            </a>
                        </div>

                        <!-- Card Bulanan -->
                        <div class="col-md-3 mb-4">
                            <a href="#modalBulanan" data-toggle="modal" class="card text-white bg-info h-100 text-decoration-none">
                                <div class="card-body text-center">
                                    <i class="fas fa-calendar-alt fa-3x mb-3"></i>
                                    <h5 class="card-title">Laporan Bulanan</h5>
                                    <p class="card-text">Trend Bulanan</p>
                                </div>
                            </a>
                        </div>

                        <!-- Card Tahunan -->
                        <div class="col-md-3 mb-4">
                            <a href="#modalTahunan" data-toggle="modal" class="card text-white bg-success h-100 text-decoration-none">
                                <div class="card-body text-center">
                                    <i class="fas fa-calendar fa-3x mb-3"></i>
                                    <h5 class="card-title">Laporan Tahunan</h5>
                                    <p class="card-text">Analisis Tahunan</p>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Statistik Cepat -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="fas fa-chart-bar"></i> Statistik Hari Ini</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @php
                                            $today = \Carbon\Carbon::today();
                                            $todayCount = \App\Models\Pasien::whereDate('waktu_validasi', $today)->count();
                                            $monthCount = \App\Models\Pasien::whereMonth('waktu_validasi', $today->month)
                                                ->whereYear('waktu_validasi', $today->year)
                                                ->count();
                                            $topPengirim = \App\Models\Pasien::select('pengirim', DB::raw('COUNT(*) as total'))
                                                ->whereDate('waktu_validasi', $today)
                                                ->whereNotNull('pengirim')
                                                ->groupBy('pengirim')
                                                ->orderBy('total', 'desc')
                                                ->first();
                                        @endphp

                                        <div class="col-md-3 text-center">
                                            <div class="stat-card">
                                                <h2 class="text-primary">{{ $todayCount }}</h2>
                                                <p class="text-muted">Pasien Hari Ini</p>
                                            </div>
                                        </div>

                                        <div class="col-md-3 text-center">
                                            <div class="stat-card">
                                                <h2 class="text-success">{{ $monthCount }}</h2>
                                                <p class="text-muted">Pasien Bulan Ini</p>
                                            </div>
                                        </div>

                                        <div class="col-md-3 text-center">
                                            <div class="stat-card">
                                                <h2 class="text-warning">{{ \App\Models\Pemeriksa::count() }}</h2>
                                                <p class="text-muted">Total Pemeriksa</p>
                                            </div>
                                        </div>

                                        <div class="col-md-3 text-center">
                                            <div class="stat-card">
                                                <h2 class="text-danger">{{ $topPengirim->total ?? 0 }}</h2>
                                                <p class="text-muted">Top Pengirim: {{ $topPengirim->pengirim ?? '-' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Laporan Lengkap -->
<div class="modal fade" id="modalLengkap" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fas fa-file-alt"></i> Laporan Lengkap</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('laporan.lengkap') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="tanggal_mulai_lengkap">Tanggal Mulai</label>
                        <input type="date" class="form-control" id="tanggal_mulai_lengkap" name="tanggal_mulai" value="{{ date('Y-m-d', strtotime('-30 days')) }}" required>
                    </div>
                    <div class="form-group">
                        <label for="tanggal_selesai_lengkap">Tanggal Selesai</label>
                        <input type="date" class="form-control" id="tanggal_selesai_lengkap" name="tanggal_selesai" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="format_lengkap">Format Laporan</label>
                        <select class="form-control" id="format_lengkap" name="format">
                            <option value="view">Tampilan Web</option>
                            <option value="pdf">PDF</option>
                            <option value="excel">Excel</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Generate Laporan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Jenis Pemeriksaan -->
<div class="modal fade" id="modalJenisPemeriksaan" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title"><i class="fas fa-vial"></i> Laporan By Jenis Pemeriksaan</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('laporan.jenis-pemeriksaan') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="id_jenis_pemeriksaan">Jenis Pemeriksaan (Opsional)</label>
                        <select class="form-control" id="id_jenis_pemeriksaan" name="id_jenis_pemeriksaan_1">
                            <option value="">Semua Jenis</option>
                            @foreach($jenisPemeriksaan as $jenis)
                                <option value="{{ $jenis->id_jenis_pemeriksaan_1 }}">{{ $jenis->nama_pemeriksaan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tanggal_mulai_jenis">Tanggal Mulai (Opsional)</label>
                        <input type="date" class="form-control" id="tanggal_mulai_jenis" name="tanggal_mulai">
                    </div>
                    <div class="form-group">
                        <label for="tanggal_selesai_jenis">Tanggal Selesai (Opsional)</label>
                        <input type="date" class="form-control" id="tanggal_selesai_jenis" name="tanggal_selesai">
                    </div>
                    <div class="form-group">
                        <label for="group_by">Group By</label>
                        <select class="form-control" id="group_by" name="group_by">
                            <option value="month">Bulanan</option>
                            <option value="week">Mingguan</option>
                            <option value="day">Harian</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="format_jenis">Format Laporan</label>
                        <select class="form-control" id="format_jenis" name="format">
                            <option value="view">Tampilan Web</option>
                            <option value="pdf">PDF</option>
                            <option value="excel">Excel</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">Generate Laporan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Pengirim -->
<div class="modal fade" id="modalPengirim" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-user-md"></i> Laporan By Pengirim</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('laporan.pengirim') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="pengirim">Pengirim (Opsional)</label>
                        <input type="text" class="form-control" id="pengirim" name="pengirim" placeholder="Nama pengirim/dokter...">
                    </div>
                    <div class="form-group">
                        <label for="tanggal_mulai_pengirim">Tanggal Mulai (Opsional)</label>
                        <input type="date" class="form-control" id="tanggal_mulai_pengirim" name="tanggal_mulai">
                    </div>
                    <div class="form-group">
                        <label for="tanggal_selesai_pengirim">Tanggal Selesai (Opsional)</label>
                        <input type="date" class="form-control" id="tanggal_selesai_pengirim" name="tanggal_selesai">
                    </div>
                    <div class="form-group">
                        <label for="format_pengirim">Format Laporan</label>
                        <select class="form-control" id="format_pengirim" name="format">
                            <option value="view">Tampilan Web</option>
                            <option value="pdf">PDF</option>
                            <option value="excel">Excel</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Generate Laporan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Pemeriksa -->
<div class="modal fade" id="modalPemeriksa" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-microscope"></i> Laporan By Pemeriksa</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('laporan.pemeriksa') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="id_pemeriksa">Pemeriksa (Opsional)</label>
                        <select class="form-control" id="id_pemeriksa" name="id_pemeriksa">
                            <option value="">Semua Pemeriksa</option>
                            @foreach($pemeriksa as $p)
                                <option value="{{ $p->id_pemeriksa }}">{{ $p->nama_pemeriksa }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tanggal_mulai_pemeriksa">Tanggal Mulai (Opsional)</label>
                        <input type="date" class="form-control" id="tanggal_mulai_pemeriksa" name="tanggal_mulai">
                    </div>
                    <div class="form-group">
                        <label for="tanggal_selesai_pemeriksa">Tanggal Selesai (Opsional)</label>
                        <input type="date" class="form-control" id="tanggal_selesai_pemeriksa" name="tanggal_selesai">
                    </div>
                    <div class="form-group">
                        <label for="format_pemeriksa">Format Laporan</label>
                        <select class="form-control" id="format_pemeriksa" name="format">
                            <option value="view">Tampilan Web</option>
                            <option value="pdf">PDF</option>
                            <option value="excel">Excel</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Generate Laporan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Harian -->
<div class="modal fade" id="modalHarian" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-secondary text-white">
                <h5 class="modal-title"><i class="fas fa-calendar-day"></i> Laporan Harian</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('laporan.harian') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="tanggal_harian">Tanggal</label>
                        <input type="date" class="form-control" id="tanggal_harian" name="tanggal" value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="form-group">
                        <label for="format_harian">Format Laporan</label>
                        <select class="form-control" id="format_harian" name="format">
                            <option value="view">Tampilan Web</option>
                            <option value="pdf">PDF</option>
                            <option value="excel">Excel</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-dark">Generate Laporan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Bulanan -->
<div class="modal fade" id="modalBulanan" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title"><i class="fas fa-calendar-alt"></i> Laporan Bulanan</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('laporan.bulanan') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="bulan">Bulan</label>
                        <input type="month" class="form-control" id="bulan" name="bulan" value="{{ date('Y-m') }}">
                    </div>
                    <div class="form-group">
                        <label for="format_bulanan">Format Laporan</label>
                        <select class="form-control" id="format_bulanan" name="format">
                            <option value="view">Tampilan Web</option>
                            <option value="pdf">PDF</option>
                            <option value="excel">Excel</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-info">Generate Laporan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Tahunan -->
<div class="modal fade" id="modalTahunan" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fas fa-calendar"></i> Laporan Tahunan</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{ route('laporan.tahunan') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="tahun">Tahun</label>
                        <input type="number" class="form-control" id="tahun" name="tahun" min="2020" max="2030" value="{{ date('Y') }}">
                    </div>
                    <div class="form-group">
                        <label for="format_tahunan">Format Laporan</label>
                        <select class="form-control" id="format_tahunan" name="format">
                            <option value="view">Tampilan Web</option>
                            <option value="pdf">PDF</option>
                            <option value="excel">Excel</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Generate Laporan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.stat-card {
    padding: 20px;
    border-radius: 10px;
    background: #f8f9fa;
    transition: transform 0.3s;
}
.stat-card:hover {
    transform: translateY(-5px);
    background: #e9ecef;
}
.card {
    transition: transform 0.3s;
}
.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1);
}
</style>
@endsection

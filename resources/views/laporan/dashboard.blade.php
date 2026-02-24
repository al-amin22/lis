@extends('layouts.app')

@section('content')
@php
    use Carbon\Carbon;

    // Persiapkan data untuk Chart (CSV di data-attributes)
    $collectionGrafik = collect($statistik['grafik_30_hari'] ?? []);
    $labelsCsv = $collectionGrafik->map(fn($i) => Carbon::parse($i->tanggal)->format('j/n'))->implode(',');
    $valuesCsv = $collectionGrafik->pluck('total')->implode(',');

    // total untuk top pemeriksa (hindari division by zero)
    $totalTopPemeriksa = $statistik['top_pemeriksa']->sum('pasien_count') ?: 0;
@endphp

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <!-- Ringkasan Statistik -->
                    <div class="row mb-4">
                        <div class="col-md-4 mb-3">
                            <div class="card bg-info text-white h-100">
                                <div class="card-body d-flex flex-column justify-content-between">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-0">Pasien Hari Ini</h6>
                                            <h2 class="mt-2">{{ $statistik['hari_ini']['total_pasien'] ?? 0 }}</h2>
                                        </div>
                                        <i class="fas fa-users fa-3x opacity-50"></i>
                                    </div>
                                    <div class="d-flex justify-content-between mt-3 small">
                                        <div><i class="fas fa-check-circle"></i> {{ $statistik['hari_ini']['pemeriksaan_selesai'] ?? 0 }} Selesai</div>
                                        <div><i class="fas fa-clock"></i> {{ $statistik['hari_ini']['pemeriksaan_pending'] ?? 0 }} Pending</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="card bg-success text-white h-100">
                                <div class="card-body d-flex flex-column justify-content-between">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-0">Pasien Bulan Ini</h6>
                                            <h2 class="mt-2">{{ $statistik['bulan_ini']['total_pasien'] ?? 0 }}</h2>
                                        </div>
                                        <i class="fas fa-calendar-alt fa-3x opacity-50"></i>
                                    </div>
                                    <div class="mt-3 small">
                                        <span>Rata-rata: {{ ($statistik['bulan_ini']['total_pasien'] ?? 0) > 0 ? number_format(($statistik['bulan_ini']['total_pasien'] / now()->day), 1) : 0 }}/hari</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="card bg-warning text-dark h-100">
                                <div class="card-body d-flex flex-column justify-content-between">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-0">Jenis Pemeriksaan</h6>
                                            <h2 class="mt-2">{{ $statistik['bulan_ini']['per_jenis_pemeriksaan']->count() ?? 0 }}</h2>
                                        </div>
                                        <i class="fas fa-vial fa-3x opacity-50"></i>
                                    </div>
                                    <div class="mt-3 small">
                                        <span>{{ $statistik['bulan_ini']['per_jenis_pemeriksaan']->sum('data_pemeriksaan_count') ?? 0 }} total pemeriksaan</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Grafik 30 Hari -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i> Trend 30 Hari Terakhir</h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="grafik30Hari" height="100"
                                            data-labels="{{ e($labelsCsv) }}"
                                            data-values="{{ e($valuesCsv) }}"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Top Pemeriksa & Top Pengirim -->
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="fas fa-microscope me-2"></i> Top 5 Pemeriksa Bulan Ini</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Nama Pemeriksa</th>
                                                    <th class="text-center">Total Pasien</th>
                                                    <th>Persentase</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($statistik['top_pemeriksa'] as $index => $pemeriksa)
                                                    @php
                                                        $pct = $totalTopPemeriksa > 0 ? ($pemeriksa->pasien_count / $totalTopPemeriksa * 100) : 0;
                                                        $pctForAttr = number_format($pct, 1, '.', '');
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>{{ $pemeriksa->nama_pemeriksa }}</td>
                                                        <td class="text-center">{{ $pemeriksa->pasien_count }}</td>
                                                        <td style="min-width:220px;">
                                                            {{-- Non-JS fallback: tampilkan text persen --}}
                                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                                <small>{{ number_format($pct,1) }}%</small>
                                                                <small class="text-muted small">dari total top</small>
                                                            </div>

                                                            {{-- Dynamic progress: set width via JS menggunakan data-progress --}}
                                                            <div class="progress" style="height:18px;">
                                                                <div class="progress-bar progress-dynamic" role="progressbar"
                                                                     data-progress="{{ $pctForAttr }}"
                                                                     aria-valuenow="{{ $pctForAttr }}"
                                                                     aria-valuemin="0" aria-valuemax="100">
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="4" class="text-center">Tidak ada data</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Top Pengirim -->
                        <div class="col-md-6 mb-3">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="fas fa-user-md me-2"></i> Top 10 Pengirim Bulan Ini</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Pengirim</th>
                                                    <th class="text-center">Total Pasien</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($statistik['bulan_ini']['per_pengirim'] as $index => $pengirim)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>{{ $pengirim->pengirim ?: 'Tidak Ada Data' }}</td>
                                                        <td class="text-center">{{ $pengirim->total }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="3" class="text-center">Tidak ada data</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Jenis Pemeriksaan Terpopuler -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="fas fa-vial me-2"></i> Jenis Pemeriksaan Terpopuler Bulan Ini</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @foreach($statistik['bulan_ini']['per_jenis_pemeriksaan']->sortByDesc('data_pemeriksaan_count')->take(6) as $jenis)
                                            @php
                                                $percentJenis = ($statistik['bulan_ini']['total_pasien'] ?? 0) > 0
                                                    ? ($jenis->data_pemeriksaan_count / $statistik['bulan_ini']['total_pasien'] * 100)
                                                    : 0;
                                                $percentForAttr = number_format($percentJenis, 1, '.', '');
                                            @endphp
                                            <div class="col-md-4 mb-3">
                                                <div class="card border h-100">
                                                    <div class="card-body">
                                                        <h6 class="card-title mb-2">{{ $jenis->nama_pemeriksaan }}</h6>
                                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                                            <h3 class="mb-0 text-primary">{{ $jenis->data_pemeriksaan_count }}</h3>
                                                            <span class="badge bg-primary">{{ number_format($percentJenis, 1) }}%</span>
                                                        </div>
                                                        <div class="progress" style="height:8px;">
                                                            <div class="progress-bar progress-dynamic-small"
                                                                 role="progressbar"
                                                                 data-progress="{{ $percentForAttr }}"
                                                                 aria-valuemin="0" aria-valuemax="100">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div> <!-- /row -->
                                </div>
                            </div>
                        </div>
                    </div>

                </div> {{-- card-body --}}
            </div> {{-- card --}}
        </div>
    </div>
</div>

{{-- Chart.js CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // ---------------- Chart 30 Hari ----------------
    (function initChart() {
        const canvas = document.getElementById('grafik30Hari');
        if (!canvas) return;

        const labelsCsv = canvas.dataset.labels || '';
        const valuesCsv = canvas.dataset.values || '';

        const labels = labelsCsv.split(',').filter(Boolean);
        const values = valuesCsv.split(',').map(v => {
            const n = Number(v);
            return Number.isFinite(n) ? n : 0;
        });

        const ctx = canvas.getContext('2d');
        // Defensive: destroy existing chart instance if present (avoid duplicates)
        if (canvas._chartInstance) {
            canvas._chartInstance.destroy();
        }

        canvas._chartInstance = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Pasien',
                    data: values,
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13,110,253,0.08)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3,
                    pointRadius: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: { display: true },
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                },
                plugins: {
                    legend: { display: true, position: 'top' }
                }
            }
        });
    })();

    // ---------------- Animate progress bars ----------------
    function applyProgressAnimation(selector, duration = 800) {
        document.querySelectorAll(selector).forEach(function(el) {
            const raw = el.getAttribute('data-progress') || '0';
            const target = parseFloat(raw.replace(',', '.')) || 0;
            // initial state
            el.style.width = '0%';
            // trigger animation slightly later so transition animates
            requestAnimationFrame(function() {
                setTimeout(function() {
                    el.style.transition = 'width ' + duration + 'ms cubic-bezier(.4,0,.2,1)';
                    el.style.width = target + '%';
                    el.setAttribute('aria-valuenow', String(target));
                }, 40);
            });
        });
    }

    // Apply to default and small progress bars
    applyProgressAnimation('.progress-dynamic', 900);
    applyProgressAnimation('.progress-dynamic-small', 700);

    // Ensure print shows final widths (some browsers ignore transitions)
    window.addEventListener('beforeprint', function() {
        document.querySelectorAll('[data-progress]').forEach(function(el) {
            const raw = el.getAttribute('data-progress') || '0';
            const target = parseFloat(raw.replace(',', '.')) || 0;
            el.style.transition = 'none';
            el.style.width = target + '%';
        });
    });

    // Restore animated behavior after print
    window.addEventListener('afterprint', function() {
        applyProgressAnimation('[data-progress]');
    });

});
</script>

<style>
/* Layout & aesthetics */
.card { border-radius: 10px; overflow: hidden; }
.card-header { border-bottom: none; }
.table th { border-top: none; }

/* Progress bar base */
.progress { border-radius: 8px; background: #e9ecef; overflow: hidden; }
.progress-bar { height: 100%; display: block; background-color: #198754; max-width: 100%; }

/* Specific classes for dynamic progress bars (JS will set width) */
.progress-dynamic { background-color: #198754; width: 0%; }
.progress-dynamic-small { background-color: #0d6efd; width: 0%; }

/* Small UI tweaks */
.badge { font-size: 0.85em; padding: 0.4em 0.6em; }
.shadow-sm { box-shadow: 0 1px 3px rgba(0,0,0,0.06); }

/* Print adjustments */
@media print {
    .btn { display: none !important; }
    .progress, .progress-bar { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
}
</style>
@endsection

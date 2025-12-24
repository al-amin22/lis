@extends('layouts.app')

@section('content')
<!-- App hero header starts -->
<div class="app-hero-header d-flex align-items-center">
    <!-- Breadcrumb starts -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <i class="ri-home-8-line lh-1 pe-3 me-3 border-end"></i>
            <a href="{{ url('admin/dashboard') }}">Home</a>
        </li>
        <li class="breadcrumb-item text-primary" aria-current="page">
            Dashboard
        </li>
    </ol>
    <!-- Breadcrumb ends -->

    <!-- Sales stats starts -->
    <!-- <div class="ms-auto d-lg-flex d-none flex-row">
        <div class="d-flex flex-row gap-1 day-sorting">
            <button class="btn btn-sm btn-primary">Today</button>
            <button class="btn btn-sm">7d</button>
            <button class="btn btn-sm">2w</button>
            <button class="btn btn-sm">1m</button>
            <button class="btn btn-sm">3m</button>
            <button class="btn btn-sm">6m</button>
            <button class="btn btn-sm">1y</button>
        </div>
    </div> -->
    <!-- Sales stats ends -->
</div>
<!-- App Hero header ends -->

<!-- App body starts -->
<div class="app-body">
    <!-- Row starts -->
    <div class="row gx-3">
        <div class="col-xxl-12 col-sm-12">
            <div class="card mb-3 bg-2">
                <div class="card-body">
                    <div class="py-4 px-3 text-white">
                        <h6>Selamat Datang kembali Di</h6>
                        <h2>ARVINDO LIS</h2>
                        <h5>Tugas Hari Ini : </h5>
                        <div class="mt-4 d-flex gap-3">
                            <div class="d-flex align-items-center">
                                <div class="icon-box lg bg-arctic rounded-3 me-3">
                                    <i class="ri-surgical-mask-line fs-4"></i>
                                </div>
                                <div class="d-flex flex-column">
                                    <h2 class="m-0 lh-1">{{ $statusOrders ?? 0}}</h2>
                                    <p class="m-0">Total Pasien</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="icon-box lg bg-lime rounded-3 me-3">
                                    <i class="ri-lungs-line fs-4"></i>
                                </div>
                                <div class="d-flex flex-column">
                                    <h2 class="m-0 lh-1">{{ $statusSelesai ?? 0}}</h2>
                                    <p class="m-0">Selesai</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="icon-box lg bg-peach rounded-3 me-3">
                                    <i class="ri-walk-line fs-4"></i>
                                </div>
                                <div class="d-flex flex-column">
                                    <h2 class="m-0 lh-1">{{ $statusProses ?? 0}}</h2>
                                    <p class="m-0">Diproses</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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

    <!-- App body ends -->
    <div class="row gx-3">
        <div class="col-sm-12">
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Pemeriksaan
                        @if(request('search_date'))
                            Tanggal: {{ \Carbon\Carbon::parse(request('search_date'))->format('d/m/Y') }}
                        @else
                            Hari Ini
                        @endif
                    </h5>
                    <div class="d-flex align-items-center gap-2">
                        <button id="ambilOrder" class="btn btn-sm btn-primary">Cek Order</button>
                        <a href="{{ route('pasien.create') }}" class="btn btn-sm btn-primary">+ Tambah Pasien</a>
                        <a href="{{ route('pasien.index') }}" class="btn btn-sm btn-primary">Refresh</a>

                        <!-- FORM PENCARIAN TANGGAL -->
                        <div class="position-relative" style="max-width: 250px;">
                            <form method="GET" action="{{ route('pasien.search') }}" class="d-flex" id="dateSearchForm">
                                <input type="date"
                                    name="search_date"
                                    id="searchDateInput"
                                    class="form-control form-control-sm me-2"
                                    value="{{ request('search_date') }}"
                                    title="Cari berdasarkan tanggal">
                                <button type="submit" class="btn btn-sm btn-primary" title="Cari berdasarkan tanggal">
                                    <i class="ri-calendar-line"></i>
                                </button>
                            </form>
                            <div id="dateSearchLoading" class="position-absolute top-50 end-0 translate-middle-y me-2" style="display: none;">
                                <div class="spinner-border spinner-border-sm text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>

                        <!-- FORM PENCARIAN KEYWORD -->
                        <form method="GET" action="{{ route('pasien.search') }}" class="d-flex" style="max-width: 250px;">
                            <input type="text"
                                name="search"
                                class="form-control form-control-sm me-2"
                                placeholder="Cari Data Pasien..."
                                value="{{ request('search') }}">

                            {{-- 🔥 PERTAHANKAN TANGGAL --}}
                            @if(request('search_date'))
                                <input type="hidden" name="search_date" value="{{ request('search_date') }}">
                            @endif

                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="ri-search-line"></i>
                            </button>
                        </form>

                        <!-- TOMBOL RESET JIKA ADA FILTER -->
                        @if(request('search_date') || request('search'))
                            <a href="{{ route('pasien.index') }}" class="btn btn-sm btn-secondary" title="Reset filter">
                                <i class="ri-close-line"></i> Reset
                            </a>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <!-- Table starts -->
                    <div class="table-outer">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle text-nowrap">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>No. Registrasi Lab</th>
                                        <th>RM Pasien</th>
                                        <th>Nama Pasien</th>
                                        <th>Asal Kunjungan</th>
                                        <th>Penjamin</th>
                                        <th>Status</th> <!-- Kolom baru untuk status -->
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pasiens as $patient)
                                    <tr>
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
                                        <td>{{ $patient->nomor_registrasi ?? '-'}}</td>
                                        <td>{{ $patient->rm_pasien ?? '-'}}</td>
                                        <td>{{ $patient->nama_pasien ?? '-'}}</td>
                                        <td>{{ $patient->ket_klinik ?? '-'}}</td>
                                        <td>{{ $patient->nota ?? '-'}}</td>
                                        <td>
                                            @if($patient->id_pemeriksa && $patient->waktu_validasi)
                                                <span class="badge bg-success">Selesai</span>
                                            @else
                                                <span class="badge bg-warning">Diproses</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('pasien.print', $patient->no_lab) }}" target="_blank" class="btn btn-sm btn-secondary">
                                                Print
                                            </a>
                                            <a href="{{ route('pasien.show', $patient->no_lab) }}" class="btn btn-sm btn-primary">View</a>
                                            <a href="{{ route('pasien.history', $patient->rm_pasien ?? '') }}"
                                            class="btn btn-sm btn-info" title="History">
                                                History
                                            </a>
                                            <form action="{{ route('pasien.destroy', $patient->no_lab) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-center mt-3">
                                {{ $pasiens->links('pagination::bootstrap-5') }}
                            </div>

                        </div>
                    </div>
                    <!-- Table ends -->
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    async function ambilOrder(auto = false) {
        const btn = document.getElementById('ambilOrder');

        try {
            btn.disabled = true;
            btn.innerText = auto ? 'Proses... ⏳' : 'Proses... ⏳';

            const response = await fetch('/api/pasien/ambil-order', {
                method: 'POST',
                headers: {'Accept': 'application/json'}
            });

            const result = await response.json();

            if (result.success) {
                btn.innerText = `Berhasil..`;
                setTimeout(() => btn.innerText = 'Ambil Order', 5000);

                // 🔥 RELOAD HALAMAN AGAR DATA LANGSUNG MUNCUL
                location.reload();
            } else {
                btn.innerText = 'Gagal ❌';
                setTimeout(() => btn.innerText = 'Ambil Order', 5000);

                // // 🔥 RELOAD HALAMAN AGAR DATA LANGSUNG MUNCUL
                // location.reload();
            }

        } catch (error) {
            if (!auto) alert('Error: ' + error.message);
            btn.innerText = 'Error ⚠️';
            setTimeout(() => btn.innerText = 'Ambil Order', 3000);
        } finally {
            btn.disabled = false;
        }
    }

    // Klik manual
    document.getElementById('ambilOrder').addEventListener('click', () => ambilOrder(false));

    // Otomatis setiap 20 menit
    setInterval(() => ambilOrder(true), 20 * 60 * 1000);
</script>
<script>
    // Auto-submit dengan loading indicator
    document.getElementById('searchDateInput').addEventListener('change', function() {
        const loading = document.getElementById('dateSearchLoading');
        const form = this.form;

        // Tampilkan loading
        if (loading) loading.style.display = 'block';

        // Submit form
        setTimeout(() => {
            form.submit();
        }, 100);
    });

    // Sembunyikan loading saat halaman selesai dimuat
    document.addEventListener('DOMContentLoaded', function() {
        const loading = document.getElementById('dateSearchLoading');
        if (loading) loading.style.display = 'none';

        // Set nilai input tanggal dari URL
        const urlParams = new URLSearchParams(window.location.search);
        const searchDate = urlParams.get('search_date');
        const searchInput = document.getElementById('searchDateInput');

        if (searchDate && searchInput) {
            searchInput.value = searchDate;
        }
    });
</script>

@endsection

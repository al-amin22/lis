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
                                    <p class="m-0">Order</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="icon-box lg bg-lime rounded-3 me-3">
                                    <i class="ri-lungs-line fs-4"></i>
                                </div>
                                <div class="d-flex flex-column">
                                    <h2 class="m-0 lh-1">{{ $statusSelesai ?? 0}}</h2>
                                    <p class="m-0">selesai</p>
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
                    <h5 class="card-title mb-0">Pemeriksaan Hari Ini</h5>
                    <div class="d-flex align-items-center gap-2">
                        <form method="GET" action="{{ route('user.search') }}" class="d-flex" style="max-width: 300px;">
                            <input type="text" name="search" class="form-control form-control-sm me-2"
                                placeholder="Cari RM Pasien / Nama" value="{{ request('search') }}">
                            <button type="submit" class="btn btn-sm btn-primary">Search</button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Table starts -->
                    <div class="table-outer">
                        <div class="table-responsive">
                            <table class="table m-0 align-middle">
                                <thead>
                                    <tr>
                                        <th>No Lab</th>
                                        <th>RM Pasien</th>
                                        <th>Nama Pasien</th>
                                        <th>Hematology</th>
                                        <th>Kimia</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pasiens as $patient)
                                    <tr>
                                        <td>{{ $patient->no_lab ?? '-'}}</td>
                                        <td>{{ $patient->rm_pasien ?? '-'}}</td>
                                        <td>{{ $patient->nama_pasien ?? '-'}}</td>
                                        <td>
                                            @if($patient->hematology->isNotEmpty())
                                            ✅
                                            @else
                                            ❌
                                            @endif
                                        </td>
                                        <td>
                                            @if($patient->kimia->isNotEmpty())
                                            ✅ <!-- centang biru -->
                                            @else
                                            ❌ <!-- X merah -->
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('user.print', $patient->no_lab) }}" target="_blank" class="btn btn-sm btn-secondary">
                                                Print
                                            </a>

                                            <a href="{{ route('user.show', $patient->no_lab) }}" class="btn btn-sm btn-primary">View</a>
                                            <!-- <a href="{{ route('pasien.edit', $patient->no_lab) }}" class="btn btn-sm btn-warning">Edit</a> -->
                                            <a
                                                href="{{ $patient->rm_pasien ? route('user.history', $patient->rm_pasien) : route('user.history', '') }}"
                                                class="btn btn-sm btn-info"
                                                title="History">
                                                History
                                            </a>
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
@endsection

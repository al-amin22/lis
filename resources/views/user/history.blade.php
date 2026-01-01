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

    <!-- Tabel history -->
    <div class="row gx-3">
        <div class="col-sm-12">
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
                                        <th>No Lab</th>
                                        <th>Tanggal</th>
                                        <th>Nama Pasien</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($histories as $patient)
                                    <tr>

                                        <td>@php
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
                                                <a href="{{ route('user.show', $patient->no_lab) }}" class="btn btn-sm btn-primary" title="View">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Tidak ada data history untuk pasien ini.</td>
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
    </div>
</div>
@endsection

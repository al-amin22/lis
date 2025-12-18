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
        <li class="breadcrumb-item">
            <a href="{{ route('pasien.index') }}">Dashboard</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('pasien.index') }}">Data Pasien</a>
        </li>
        <li class="breadcrumb-item text-primary" aria-current="page">
            Edit Data Pasien
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

<!-- App body starts -->
<div class="row gx-3">
    <div class="row gx-3">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Hasil Pemeriksaan - {{ $pasien->nama_pasien ?? '-' }}</h5>
                </div>
                <div class="card-body">
                    <!-- Data pasien display starts -->
                    <div class="row gx-3">
                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                            <div class="mb-3">
                                <label class="form-label text-muted small mb-1">No. Lab</label>
                                <div class="d-flex align-items-center">
                                    <i class="ri-file-list-3-line me-2 text-primary"></i>
                                    <h6 class="m-0 text-dark">{{ $pasien->no_lab ?? '-' }}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                            <div class="mb-3">
                                <label class="form-label text-muted small mb-1">RM Pasien</label>
                                <div class="d-flex align-items-center">
                                    <i class="ri-user-card-line me-2 text-primary"></i>
                                    <h6 class="m-0 text-dark">{{ $pasien->rm_pasien ?? '-' }}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                            <div class="mb-3">
                                <label class="form-label text-muted small mb-1">Nama Pasien</label>
                                <div class="d-flex align-items-center">
                                    <i class="ri-user-line me-2 text-primary"></i>
                                    <h6 class="m-0 text-dark">{{ $pasien->nama_pasien ?? '-' }}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                            <div class="mb-3">
                                <label class="form-label text-muted small mb-1">Jenis Kelamin</label>
                                <div class="d-flex align-items-center">
                                    <i class="ri-genderless-line me-2 text-primary"></i>
                                    <h6 class="m-0 text-dark">{{ $pasien->jenis_kelamin ?? '-' }}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                            <div class="mb-3">
                                <label class="form-label text-muted small mb-1">Umur</label>
                                <div class="d-flex align-items-center">
                                    <i class="ri-calendar-line me-2 text-primary"></i>
                                    <h6 class="m-0 text-dark">{{ $pasien->umur ?? '-' }}</h6>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="col-xxl-3 col-lg-4 col-sm-6">
                            <div class="mb-3">
                                <label class="form-label text-muted small mb-1">Nomor HP</label>
                                <div class="d-flex align-items-center">
                                    <i class="ri-phone-line me-2 text-primary"></i>
                                    <h6 class="m-0 text-dark">{{ $pasien->no_telepon ?? '-' }}</h6>
                                </div>
                            </div>
                        </div> -->
                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                            <div class="mb-3">
                                <label class="form-label text-muted small mb-1">Tanggal Pengujian</label>
                                <div class="d-flex align-items-center">
                                    <i class="ri-calendar-2-line me-2 text-primary"></i>
                                    <h6 class="m-0 text-dark">{{ \Carbon\Carbon::parse($pasien->created_at)->format('d/m/Y') ?? '-' }}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                            <div class="mb-3">
                                <label class="form-label text-muted small mb-1">Dokter Spesialis</label>
                                <div class="d-flex align-items-center">
                                    <i class="ri-stethoscope-line me-2 text-primary"></i>
                                    <h6 class="m-0 text-dark">{{ $pasien->dokter->nama_dokter ?? '-' }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Keluhan section -->
                    <!-- <div class="row">
                        <div class="col-sm-12">
                            <div class="mb-3">
                                <label class="form-label text-muted small mb-1">Catatan</label>
                                <div class="border rounded p-3 bg-light">
                                    <div class="d-flex align-items-start">
                                        <i class="ri-chat-quote-line me-2 text-primary mt-1"></i>
                                        <p class="m-0 text-dark">{{ $pasien->catatan }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->
                    <!-- Data pasien display ends -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Script khusus untuk halaman edit data pasien -->
<script>
    $(document).ready(function() {
        // Inisialisasi tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        // Hitung umur saat halaman dimuat jika ada tanggal lahir
        calculateAge();

        // Hitung umur saat tanggal lahir diubah
        $('#a12').on('change', calculateAge);

        function calculateAge() {
            let tglLahir = new Date($('#a12').val());

            // Jika tanggal lahir tidak valid, kembalikan umur yang sudah ada
            if (isNaN(tglLahir.getTime())) {
                return;
            }

            let today = new Date();

            let tahun = today.getFullYear() - tglLahir.getFullYear();
            let bulan = today.getMonth() - tglLahir.getMonth();
            let hari = today.getDate() - tglLahir.getDate();

            // Koreksi selisih bulan & hari
            if (hari < 0) {
                bulan--;
                let lastMonth = new Date(today.getFullYear(), today.getMonth(), 0);
                hari += lastMonth.getDate();
            }

            if (bulan < 0) {
                tahun--;
                bulan += 12;
            }

            // Format hasil
            let umurText = tahun + " Tahun " + bulan + " Bulan " + hari + " Hari";
            $('#a13').val(tahun >= 0 ? umurText : "0 Tahun 0 Bulan 0 Hari");
        }

        // Validasi form sederhana
        $('form').on('submit', function(e) {
            let namaPasien = $('input[name="nama_pasien"]').val();
            if (!namaPasien.trim()) {
                e.preventDefault();
                alert('Nama pasien harus diisi!');
                return false;
            }
        });
    });
</script>
@endsection

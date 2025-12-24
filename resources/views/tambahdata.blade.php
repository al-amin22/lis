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
        <li class="breadcrumb-item text-primary" aria-current="page">
            Tambah Data Pasien
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
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Tambah Data Pasien</h5>
            </div>
            <div class="card-body">
                <!-- Form start -->
                <form action="{{ route('pasien.store') }}" method="POST">
                    @csrf

                    <!-- Row starts -->
                    <div class="row gx-3">

                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                            <div class="mb-3">
                                <label class="form-label" for="a1">RM Pasien</label>
                                <input type="text" class="form-control" id="a1" name="rm_pasien" placeholder="Masukkan RM Pasien jika ada">
                            </div>
                        </div>

                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                            <div class="mb-3">
                                <label class="form-label" for="a2">No. Registrasi Lab</label>
                                <input type="text" class="form-control" id="a2" name="no_lab"
                                    value="{{ old('no_lab', $nextLabNumber) }}"
                                    placeholder="Masukkan No. Registrasi Lab Jika Ada">
                            </div>
                        </div>

                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                            <div class="mb-3">
                                <label class="form-label">Nama Pasien</label>
                                <input type="text" class="form-control" name="nama_pasien"
                                    placeholder="Masukkan Nama Lengkap">
                            </div>
                        </div>

                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                            <div class="mb-3">
                                <label class="form-label">Tgl Lahir</label>
                                <input type="date" class="form-control" name="tgl_lahir"
                                    max="{{ date('Y-m-d') }}">
                            </div>
                        </div>

                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                            <div class="mb-3">
                                <label class="form-label">Jenis Kelamin</label>
                                <select class="form-select" name="jenis_kelamin">
                                    <option value="">Select</option>
                                    <option value="PRIA">Laki-Laki</option>
                                    <option value="WANITA">Perempuan</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                            <div class="mb-3">
                                <label class="form-label">No. Telepon</label>
                                <input type="text" class="form-control" name="no_telepon"
                                    placeholder="Masukkan No. Telepon">
                            </div>
                        </div>

                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                            <div class="mb-3">
                                <label class="form-label">Alamat</label>
                                <input type="text" class="form-control" name="alamat"
                                    placeholder="Masukkan Alamat Lengkap">
                            </div>
                        </div>

                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                            <div class="mb-3">
                                <label class="form-label">Penjamin</label>
                                <select name="nota" class="form-select">
                                    <option value="">Pilih Penjamin</option>
                                    @foreach($penjamin as $item)
                                        <option value="{{ $item->nama_penjamin }}">
                                            {{ $item->nama_penjamin }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                            <div class="mb-3">
                                <label class="form-label">Kelas</label>
                                <select name="id_kelas" class="form-select">
                                    <option value="">Pilih Kelas</option>
                                    @foreach($kelas as $item)
                                        <option value="{{ $item->id_kelas }}">
                                            {{ $item->nama_kelas }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                            <div class="mb-3">
                                <label class="form-label">Ruangan</label>
                                <select name="ket_klinik" class="form-select">
                                    <option value="">Pilih Ruangan</option>
                                    @foreach($ruangan as $item)
                                        <option value="{{ $item->nama_ruangan }}">
                                            {{ $item->nama_ruangan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                            <div class="mb-3">
                                <label class="form-label">Validator</label>
                                <select name="id_pemeriksa" class="form-select">
                                    <option value="">Pilih Validator</option>
                                    @foreach($pemeriksa as $item)
                                        <option value="{{ $item->id_pemeriksa }}">
                                            {{ $item->nama_pemeriksa }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                            <div class="mb-3">
                                <label class="form-label">Pengirim</label>
                                <select name="pengirim" class="form-select">
                                    <option value="">Pilih Pengirim</option>
                                    @foreach($dokter as $item)
                                        <option value="{{ $item->nama_dokter }}">
                                            {{ $item->nama_dokter }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="d-flex gap-2 justify-content-end">
                                <a href="{{ route('pasien.index') }}" class="btn btn-outline-secondary">
                                    Kembali Ke Dashboard
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    Simpan
                                </button>
                            </div>
                        </div>

                    </div>
                    <!-- Row ends -->
                </form>

                <!-- Form end -->
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Script khusus untuk halaman tambah data pasien -->
<script>
    $(document).ready(function() {
        // Inisialisasi tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        // Contoh fungsi untuk halaman tambah data
        console.log('Halaman tambah data pasien loaded');

        // Validasi form sederhana
        $('#patientForm').on('submit', function(e) {
            e.preventDefault();
            // Logika validasi dan submit form
            console.log('Form submitted');
        });
    });
</script>
<script>
    document.getElementById('a12').addEventListener('change', function() {
        let tglLahir = new Date(this.value);
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
        document.getElementById('a13').value = tahun >= 0 ? umurText : "0 Tahun 0 Bulan 0 Hari";
    });
</script>
@endsection

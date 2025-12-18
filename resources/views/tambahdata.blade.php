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
                                <input
                                    type="text"
                                    class="form-control"
                                    id="a1"
                                    name="rm_pasien"
                                    value="{{ $nextRm}}"
                                    placeholder="Masukkan RM Pasien jika ada" />
                            </div>
                        </div>
                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                            <div class="mb-3">
                                <label class="form-label" for="a2">No. Lab</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="a2"
                                    name="no_lab"
                                    value="{{ old('no_lab', $nextLabNumber) }}"
                                    placeholder="Masukkan No. Lab Jika Ada" />
                            </div>
                        </div>
                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                            <div class="mb-3">
                                <label class="form-label" for="a2">Nota</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="a2"
                                    name="nota"
                                    value="{{ old('nota', $nextNota) }}"
                                    placeholder="Masukkan No. Nota Jika Ada" />
                            </div>
                        </div>
                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                            <div class="mb-3">
                                <label class="form-label" for="a11">Tanggal Registrasi</label>
                                <input
                                    type="date"
                                    class="form-control"
                                    id="a11"
                                    name="tgl_pendaftaran"
                                    value="{{ old('tgl_pendaftaran', date('Y-m-d')) }}"
                                    placeholder="Pilih Tanggal Registrasi" />
                            </div>
                        </div>
                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                            <div class="mb-3">
                                <label class="form-label" for="a2">Nama Pasien</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="a2"
                                    name="nama_pasien"
                                    placeholder="Masukkan Nama Lengkap" />
                            </div>
                        </div>
                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                            <div class="mb-3">
                                <label class="form-label" for="a12">Tgl Lahir</label>
                                <input
                                    type="date"
                                    class="form-control"
                                    id="a12"
                                    name="tgl_lahir"
                                    placeholder="Pilih Tanggal Lahir"
                                    max="{{ date('Y-m-d') }}" />
                            </div>
                        </div>

                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                            <div class="mb-3">
                                <label class="form-label" for="a13">Umur</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="a13"
                                    name="umur"
                                    placeholder="Umur akan otomatis terisi"
                                    readonly />
                            </div>
                        </div>
                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                            <div class="mb-3">
                                <label class="form-label" for="a3">jenis Kelamin</label>
                                <select class="form-select" id="a3" name="jenis_kelamin">
                                    <option value="">Select</option>
                                    <option value="Laki-Laki">Laki-Laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                            <div class="mb-3">
                                <label class="form-label" for="a13">No. Telepon</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="a13"
                                    name="no_telepon"
                                    placeholder="Masukkan No. Telepon" />
                            </div>
                        </div>
                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                            <div class="mb-3">
                                <label class="form-label" for="a14">Alamat</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="a14"
                                    name="alamat"
                                    placeholder="Masukkan Alamat Lengkap" />
                            </div>
                        </div>
                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                            <div class="mb-3">
                                <label class="form-label" for="a15">Ruangan</label>
                                <select name="id_ruangan" class="form-select">
                                    <option value="">Pilih Ruangan</option>
                                    @foreach($ruangan as $ruangan)
                                    <option value="{{ $ruangan->id_ruangan }}">{{ $ruangan->nama_ruangan }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                            <div class="mb-3">
                                <label class="form-label" for="a16">Kelas</label>
                                <select name="id_kelas" class="form-select">
                                    <option value="">Pilih Kelas</option>
                                    @foreach($kelas as $kelas)
                                    <option value="{{ $kelas->id_kelas }}">{{ $kelas->nama_kelas }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                            <div class="mb-3">
                                <label class="form-label" for="a17">Keterangan Klinik</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="a17"
                                    name="ket_klinik"
                                    placeholder="Masukkan Keterangan Klinik" />
                            </div>
                        </div>
                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                            <div class="mb-3">
                                <label class="form-label " for="a19">Dokter ACC</label>
                                <select name="id_dokter" class="form-select">
                                    <option value="">Pilih Dokter ACC</option>
                                    @foreach($dokter as $dokter)
                                    <option value="{{ $dokter->id_dokter }}">{{ $dokter->nama_dokter }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                            <div class="mb-3">
                                <label class="form-label" for="a20">Penguji</label>
                                <select name="id_pemeriksa" class="form-select">
                                    <option value="">Pilih Penguji</option>
                                    @foreach($pemeriksa as $pemeriksa)
                                    <option value="{{ $pemeriksa->id_pemeriksa }}">{{ $pemeriksa->nama_pemeriksa }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                            <div class="mb-3">
                                <label class="form-label" for="a21">Tanggal Ambil Sampel</label>
                                <input
                                    type="date"
                                    class="form-control"
                                    id="a21"
                                    name="tgl_ambil_sample"
                                    placeholder="Pilih Tanggal Ambil Sampel" />
                            </div>
                        </div>
                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                            <div class="mb-3">
                                <label class="form-label" for="catatan">Catatan</label>
                                <textarea
                                    class="form-control"
                                    id="catatan"
                                    name="catatan"
                                    rows="2"
                                    placeholder="Masukkan catatan pasien"></textarea>
                            </div>
                        </div>
                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                            <div class="mb-3">
                                <label class="form-label" for="pengirim">Pengirim</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="pengirim"
                                    name="pengirim"
                                    placeholder="Masukkan Nama Pengirim" />
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

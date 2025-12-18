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
            Jenis Pemeriksaan
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

    <!-- Row starts -->
    <div class="row gx-3">
        <div class="col-sm-12">
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Daftar Jenis Pemeriksaan</h5>
                    <div class="d-flex align-items-center gap-2">
                        <!-- Modal Trigger for Single Input -->
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#tambahModal">
                            <i class="ri-add-line me-1"></i> Tambah Jenis
                        </button>

                        <!-- Modal Trigger for Batch Input -->
                        <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#tambahBatchModal">
                            <i class="ri-file-list-line me-1"></i> Tambah Banyak
                        </button>

                        <!-- Search Form -->
                        <form method="GET" action="{{ route('pasien.index.jenis.pemeriksaan') }}" class="d-flex" style="max-width: 300px;">
                            <input type="text" name="search" class="form-control form-control-sm me-2"
                                placeholder="Cari jenis pemeriksaan" value="{{ request('search') }}">
                            <button type="submit" class="btn btn-sm btn-primary">Cari</button>
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
                                        <th>No</th>
                                        <th>Jenis Pemeriksaan</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($jenisPemeriksaans as $index => $jenis)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $jenis->nama_pemeriksaan }}</td>
                                        <td>
                                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#modalEdit{{ $jenis->id_jenis_pemeriksaan_1 }}">
                                                Edit
                                            </button>

                                            <form action="{{ route('pasien.destroy.jenis.pemeriksaan', $jenis->id_jenis_pemeriksaan_1) }}"
                                                method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus jenis pemeriksaan ini?')">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="ri-delete-bin-line"></i> Hapus
                                                </button>
                                            </form>

                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-4">
                                            <p class="text-muted">Tidak ada data jenis pemeriksaan.</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Table ends -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Single -->
<div class="modal fade" id="tambahModal" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahModalLabel">Tambah Jenis Pemeriksaan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('pasien.store.jenis.pemeriksaan') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_pemeriksaan" class="form-label">Nama Jenis Pemeriksaan</label>
                        <input type="text" class="form-control" id="nama_pemeriksaan" name="nama_pemeriksaan" required
                            placeholder="Contoh: Hematologi Lengkap">
                        <div class="form-text">Masukkan nama jenis pemeriksaan</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Tambah Batch -->
<div class="modal fade" id="tambahBatchModal" tabindex="-1" aria-labelledby="tambahBatchModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahBatchModalLabel">Tambah Banyak Jenis Pemeriksaan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('pasien.store.batch.jenis.pemeriksaan') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_pemeriksaan" class="form-label">Daftar Jenis Pemeriksaan</label>
                        <textarea class="form-control" id="nama_pemeriksaan" name="nama_pemeriksaan"
                            rows="10" required
                            placeholder="Masukkan satu jenis pemeriksaan per baris. Contoh:
                            Hematologi
                            Kimia">
                        </textarea>
                        <div class="form-text">
                            Masukkan satu jenis pemeriksaan per baris. Sistem akan otomatis menghindari duplikasi.
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Simpan Semua</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit -->
@foreach($jenisPemeriksaans as $jenis)
<div class="modal fade" id="modalEdit{{ $jenis->id_jenis_pemeriksaan_1 }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Edit: {{ $jenis->nama_pemeriksaan }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('pasien.update.jenis.pemeriksaan', $jenis->id_jenis_pemeriksaan_1) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Jenis Pemeriksaan</label>
                        <input type="text"
                            name="nama_pemeriksaan"
                            class="form-control"
                            value="{{ $jenis->nama_pemeriksaan }}"
                            required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </form>

        </div>
    </div>
</div>
@endforeach

@endsection

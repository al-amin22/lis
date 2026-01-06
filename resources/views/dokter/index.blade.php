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
            Data Dokter
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
                    <h5 class="card-title mb-0">Daftar Dokter</h5>
                    <div class="d-flex align-items-center gap-2">
                        <!-- Modal Trigger for Single Input -->
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#tambahModal">
                            <i class="ri-add-line me-1"></i> Tambah Dokter
                        </button>

                        <!-- Modal Trigger for Batch Input -->
                        <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#tambahBatchModal">
                            <i class="ri-file-list-line me-1"></i> Tambah Banyak
                        </button>

                        <!-- Search Form -->
                        <form method="GET" action="{{ route('pasien.dokter.index') }}" class="d-flex" style="max-width: 300px;">
                            <input type="text" name="search" class="form-control form-control-sm me-2"
                                placeholder="Cari nama dokter" value="{{ request('search') }}">
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
                                        <th>Nama Dokter</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($dokters as $index => $dokter)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $dokter->nama_dokter ?? '-' }}</td>
                                        <td>
                                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#modalEdit{{ $dokter->id_dokter }}">
                                                Edit
                                            </button>

                                            <!-- <form action="{{ route('pasien.dokter.destroy', $dokter->id_dokter) }}"
                                                method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus dokter ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="ri-delete-bin-line"></i> Hapus
                                                </button>
                                            </form> -->
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <p class="text-muted">Tidak ada data dokter.</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-center mt-3">
                                {{ $dokters->links('pagination::bootstrap-5') }}
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
                <h5 class="modal-title" id="tambahModalLabel">Tambah Dokter</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('pasien.dokter.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_dokter" class="form-label">Nama Dokter *</label>
                        <input type="text" class="form-control" id="nama_dokter" name="nama_dokter" required
                            placeholder="Masukkan nama dokter">
                    </div>
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat *</label>
                        <textarea class="form-control" id="alamat" name="alamat" rows="3" required
                            placeholder="Masukkan alamat dokter"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="no_telp" class="form-label">Nomor Telepon *</label>
                        <input type="text" class="form-control" id="no_telp" name="no_telp" required
                            placeholder="Masukkan nomor telepon">
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
                <h5 class="modal-title" id="tambahBatchModalLabel">Tambah Banyak Dokter</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('pasien.dokter.store.multiple') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="batch_dokter" class="form-label">Daftar Dokter (Format CSV)</label>
                        <textarea class="form-control" id="batch_dokter" name="batch_dokter"
                            rows="10" required
                            placeholder="Format: Nama Dokter;Alamat;Nomor Telepon (gunakan titik koma sebagai pemisah)

                                Contoh:
                                dr. Andi Wijaya;Jl. Merdeka No. 123;081234567890
                                dr. Budi Santoso;Jl. Sudirman No. 456;081298765432">
                        </textarea>
                        <div class="form-text">
                            Masukkan data dokter dengan format: Nama Dokter;Alamat;Nomor Telepon. Satu dokter per baris.
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
@foreach($dokters as $dokter)
<div class="modal fade" id="modalEdit{{ $dokter->id_dokter }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Edit: {{ $dokter->nama_dokter }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ route('pasien.dokter.update', $dokter->id_dokter) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Dokter</label>
                        <input type="text"
                            name="nama_dokter"
                            class="form-control"
                            value="{{ $dokter->nama_dokter }}"
                            required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat"
                            class="form-control"
                            rows="3"
                            required>{{ $dokter->alamat }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nomor Telepon</label>
                        <input type="text"
                            name="no_telp"
                            class="form-control"
                            value="{{ $dokter->no_telp }}"
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

@section('scripts')
<script>
    // Auto-hide alerts setelah 5 detik
    setTimeout(function() {
        var alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            var bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
</script>
@endsection

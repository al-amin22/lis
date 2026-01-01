@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Data Ruangan</h5>
                        <div>
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambahModal">
                                <i class="fas fa-plus me-1"></i> Tambah Ruangan
                            </button>
                            <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#tambahMultipleModal">
                                <i class="fas fa-layer-group me-1"></i> Tambah Multiple
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th width="50" class="text-center">No</th>
                                    <th>Nama Ruangan</th>
                                    <th width="180" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($ruangan as $index => $item)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $item->nama_ruangan }}</td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-warning btn-sm me-1"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editModal"
                                            data-id="{{ $item->id_ruangan }}"
                                            data-nama="{{ $item->nama_ruangan }}">
                                            <i class="fas fa-edit me-1"></i> Edit
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#hapusModal"
                                            data-id="{{ $item->id_ruangan }}"
                                            data-nama="{{ $item->nama_ruangan }}"
                                            data-jumlah="{{ $item->pasien_count }}">
                                            <i class="fas fa-trash me-1"></i> Hapus
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">
                                        <i class="fas fa-door-closed me-2"></i>Tidak ada data ruangan
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-center mt-3">
                            {{ $ruangan->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Ruangan -->
<div class="modal fade" id="tambahModal" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('pasien.ruangan.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahModalLabel">
                        <i class="fas fa-plus-circle me-2"></i>Tambah Ruangan Baru
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_ruangan" class="form-label">
                            <i class="fas fa-door-open me-1"></i>Nama Ruangan
                        </label>
                        <input type="text" class="form-control" id="nama_ruangan" name="nama_ruangan"
                            required maxlength="100" placeholder="Masukkan nama ruangan">
                        <div class="form-text">
                            <i class="fas fa-info-circle me-1"></i>Maksimal 100 karakter
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Tambah Multiple -->
<div class="modal fade" id="tambahMultipleModal" tabindex="-1" aria-labelledby="tambahMultipleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('pasien.ruangan.store.multiple') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahMultipleModalLabel">
                        <i class="fas fa-layer-group me-2"></i>Tambah Ruangan Multiple
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted mb-3">
                        <i class="fas fa-info-circle me-2"></i>Tambahkan beberapa ruangan sekaligus
                    </p>

                    <div id="ruangan-container">
                        <div class="row mb-3 ruangan-item">
                            <div class="col-10">
                                <label class="form-label">
                                    <i class="fas fa-door-open me-1"></i>Nama Ruangan
                                </label>
                                <input type="text" class="form-control" name="ruangan[0][nama_ruangan]"
                                    required maxlength="100" placeholder="Masukkan nama ruangan">
                            </div>
                            <div class="col-2 d-flex align-items-end">
                                <button type="button" class="btn btn-danger btn-sm remove-ruangan" disabled>
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <button type="button" id="tambah-ruangan" class="btn btn-success btn-sm">
                        <i class="fas fa-plus me-1"></i> Tambah Field
                    </button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Simpan Semua
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Ruangan -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">
                        <i class="fas fa-edit me-2"></i>Edit Ruangan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_id" name="id">
                    <div class="mb-3">
                        <label for="edit_nama_ruangan" class="form-label">
                            <i class="fas fa-door-open me-1"></i>Nama Ruangan
                        </label>
                        <input type="text" class="form-control" id="edit_nama_ruangan" name="nama_ruangan"
                            required maxlength="100">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Perbarui
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Hapus Ruangan -->
<div class="modal fade" id="hapusModal" tabindex="-1" aria-labelledby="hapusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="hapusForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title" id="hapusModalLabel">
                        <i class="fas fa-trash-alt me-2"></i>Konfirmasi Hapus
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="hapus_id" name="id">
                    <p>
                        <i class="fas fa-question-circle me-2"></i>
                        Apakah Anda yakin ingin menghapus ruangan <strong id="hapus_nama" class="text-danger"></strong>?
                    </p>
                    <div id="warningPasien" class="alert alert-warning d-none">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Ruangan ini memiliki <span id="jumlah_pasien">0</span> pasien.
                        Jika dihapus, semua pasien terkait akan kehilangan data ruangan ini.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i>Hapus
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .ruangan-item {
        padding: 10px;
        border-radius: 5px;
        border-left: 4px solid #0d6efd;
        background-color: #f8f9fa;
        margin-bottom: 10px;
    }

    .ruangan-item:hover {
        background-color: #e9ecef;
    }

    .table tbody tr:hover {
        background-color: #f5f5f5;
    }

    .modal-header {
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
    }
</style>
@endsection

@section('scripts')
<script>
    // Inisialisasi modals
    const editModal = document.getElementById('editModal');
    const hapusModal = document.getElementById('hapusModal');

    // Event listener untuk modal edit
    if (editModal) {
        editModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const nama = button.getAttribute('data-nama');

            document.getElementById('edit_nama_ruangan').value = nama;
            document.getElementById('editForm').action = '/admin/ruangan/' + id;
        });
    }

    // Event listener untuk modal hapus
    if (hapusModal) {
        hapusModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const nama = button.getAttribute('data-nama');
            const jumlahPasien = button.getAttribute('data-jumlah');

            document.getElementById('hapus_nama').textContent = nama;
            document.getElementById('hapusForm').action = '/admin/ruangan/' + id;

            const warningDiv = document.getElementById('warningPasien');
            const jumlahSpan = document.getElementById('jumlah_pasien');

            if (parseInt(jumlahPasien) > 0) {
                warningDiv.classList.remove('d-none');
                jumlahSpan.textContent = jumlahPasien;
            } else {
                warningDiv.classList.add('d-none');
            }
        });
    }

    // Tambah multiple ruangan
    let ruanganCounter = 0;
    document.getElementById('tambah-ruangan')?.addEventListener('click', function() {
        ruanganCounter++;
        const container = document.getElementById('ruangan-container');

        const div = document.createElement('div');
        div.className = 'row mb-3 ruangan-item';
        div.innerHTML = `
        <div class="col-10">
            <label class="form-label">
                <i class="fas fa-door-open me-1"></i>Nama Ruangan
            </label>
            <input type="text" class="form-control" name="ruangan[${ruanganCounter}][nama_ruangan]"
                   required maxlength="100" placeholder="Masukkan nama ruangan">
        </div>
        <div class="col-2 d-flex align-items-end">
            <button type="button" class="btn btn-danger btn-sm remove-ruangan">
                <i class="fas fa-minus"></i>
            </button>
        </div>
    `;

        container.appendChild(div);

        // Aktifkan tombol hapus pada field pertama
        const removeButtons = document.querySelectorAll('.remove-ruangan');
        if (removeButtons.length > 1) {
            removeButtons[0].disabled = false;
        }

        // Tambah event listener untuk tombol hapus
        div.querySelector('.remove-ruangan').addEventListener('click', function() {
            div.remove();

            // Jika hanya tersisa 1 field, nonaktifkan tombol hapus
            const remainingItems = document.querySelectorAll('.ruangan-item');
            if (remainingItems.length === 1) {
                remainingItems[0].querySelector('.remove-ruangan').disabled = true;
            }
        });
    });

    // Inisialisasi event listener untuk tombol hapus pertama
    document.querySelector('.remove-ruangan')?.addEventListener('click', function() {
        if (!this.disabled) {
            this.closest('.ruangan-item').remove();
        }
    });

    // Pastikan Font Awesome icons dimuat dengan baik
    document.addEventListener('DOMContentLoaded', function() {
        // Cek jika Font Awesome gagal load
        setTimeout(function() {
            const icons = document.querySelectorAll('.fas, .fa');
            icons.forEach(icon => {
                if (getComputedStyle(icon, ':before').content === '""' ||
                    getComputedStyle(icon, ':before').content === 'none') {
                    console.warn('Icon tidak tampil:', icon.className);
                    // Fallback text untuk debugging
                    icon.setAttribute('title', 'Icon: ' + icon.className);
                }
            });
        }, 1000);
    });
</script>
@endsection

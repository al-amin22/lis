@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-user-md me-2"></i>Data Pemeriksa
                        </h5>
                        <div>
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambahModal">
                                <i class="fas fa-plus me-1"></i> Tambah Pemeriksa
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
                                    <th>Nama Pemeriksa</th>
                                    <th>Alamat</th>
                                    <th width="130">No. Telepon</th>
                                    <th width="180" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pemeriksa as $index => $item)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>
                                        <i class="fas fa-user-md me-2 text-primary"></i>{{ $item->nama_pemeriksa }}
                                    </td>
                                    <td>{{ Str::limit($item->alamat, 50) }}</td>
                                    <td>
                                        <i class="fas fa-phone me-2 text-success"></i>{{ $item->no_telp }}
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-warning btn-sm me-1"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editModal"
                                            data-id="{{ $item->id_pemeriksa }}"
                                            data-nama="{{ $item->nama_pemeriksa }}"
                                            data-alamat="{{ $item->alamat }}"
                                            data-telp="{{ $item->no_telp }}">
                                            <i class="fas fa-edit me-1"></i> Edit
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#hapusModal"
                                            data-id="{{ $item->id_pemeriksa }}"
                                            data-nama="{{ $item->nama_pemeriksa }}"
                                            data-jumlah="{{ $item->pasien_count }}">
                                            <i class="fas fa-trash me-1"></i> Hapus
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">
                                        <i class="fas fa-user-md me-2"></i>Tidak ada data pemeriksa
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Pemeriksa -->
<div class="modal fade" id="tambahModal" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('pasien.pemeriksa.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahModalLabel">
                        <i class="fas fa-user-plus me-2"></i>Tambah Pemeriksa Baru
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_pemeriksa" class="form-label">
                            <i class="fas fa-user-md me-1"></i>Nama Pemeriksa
                        </label>
                        <input type="text" class="form-control" id="nama_pemeriksa" name="nama_pemeriksa"
                            required maxlength="100" placeholder="Masukkan nama pemeriksa">
                    </div>

                    <div class="mb-3">
                        <label for="alamat" class="form-label">
                            <i class="fas fa-map-marker-alt me-1"></i>Alamat
                        </label>
                        <textarea class="form-control" id="alamat" name="alamat"
                            rows="3" required placeholder="Masukkan alamat pemeriksa"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="no_telp" class="form-label">
                            <i class="fas fa-phone me-1"></i>No. Telepon
                        </label>
                        <input type="text" class="form-control" id="no_telp" name="no_telp"
                            required maxlength="15" placeholder="Masukkan nomor telepon">
                        <div class="form-text">
                            <i class="fas fa-info-circle me-1"></i>Maksimal 15 karakter
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
            <form action="{{ route('pasien.pemeriksa.store.multiple') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahMultipleModalLabel">
                        <i class="fas fa-users me-2"></i>Tambah Pemeriksa Multiple
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted mb-3">
                        <i class="fas fa-info-circle me-2"></i>Tambahkan beberapa pemeriksa sekaligus
                    </p>

                    <div id="pemeriksa-container">
                        <div class="card mb-3 pemeriksa-item">
                            <div class="card-header bg-light">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold">Pemeriksa #1</span>
                                    <button type="button" class="btn btn-danger btn-sm remove-pemeriksa" disabled>
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">
                                            <i class="fas fa-user-md me-1"></i>Nama Pemeriksa
                                        </label>
                                        <input type="text" class="form-control" name="pemeriksa[0][nama_pemeriksa]"
                                            required maxlength="100" placeholder="Masukkan nama pemeriksa">
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">
                                            <i class="fas fa-map-marker-alt me-1"></i>Alamat
                                        </label>
                                        <textarea class="form-control" name="pemeriksa[0][alamat]"
                                            rows="2" required placeholder="Masukkan alamat"></textarea>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label">
                                            <i class="fas fa-phone me-1"></i>No. Telepon
                                        </label>
                                        <input type="text" class="form-control" name="pemeriksa[0][no_telp]"
                                            required maxlength="15" placeholder="Masukkan nomor telepon">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="button" id="tambah-pemeriksa" class="btn btn-success btn-sm">
                        <i class="fas fa-plus me-1"></i> Tambah Pemeriksa
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

<!-- Modal Edit Pemeriksa -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">
                        <i class="fas fa-edit me-2"></i>Edit Pemeriksa
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_id" name="id">

                    <div class="mb-3">
                        <label for="edit_nama_pemeriksa" class="form-label">
                            <i class="fas fa-user-md me-1"></i>Nama Pemeriksa
                        </label>
                        <input type="text" class="form-control" id="edit_nama_pemeriksa" name="nama_pemeriksa"
                            required maxlength="100">
                    </div>

                    <div class="mb-3">
                        <label for="edit_alamat" class="form-label">
                            <i class="fas fa-map-marker-alt me-1"></i>Alamat
                        </label>
                        <textarea class="form-control" id="edit_alamat" name="alamat"
                            rows="3" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="edit_no_telp" class="form-label">
                            <i class="fas fa-phone me-1"></i>No. Telepon
                        </label>
                        <input type="text" class="form-control" id="edit_no_telp" name="no_telp"
                            required maxlength="15">
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

<!-- Modal Hapus Pemeriksa -->
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
                        Apakah Anda yakin ingin menghapus pemeriksa <strong id="hapus_nama" class="text-danger"></strong>?
                    </p>
                    <div id="warningPasien" class="alert alert-warning d-none">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Pemeriksa ini memiliki <span id="jumlah_pasien">0</span> pasien.
                        Jika dihapus, semua pasien terkait akan kehilangan data pemeriksa ini.
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
    .pemeriksa-item {
        border-left: 4px solid #0d6efd;
        margin-bottom: 15px;
    }

    .pemeriksa-item .card-header {
        background-color: #f8f9fa !important;
        border-bottom: 1px solid #dee2e6;
    }

    .table tbody tr:hover {
        background-color: #f8f9fa;
    }

    .modal-header {
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
    }

    .form-label i {
        width: 20px;
        text-align: center;
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
            const alamat = button.getAttribute('data-alamat');
            const telp = button.getAttribute('data-telp');

            document.getElementById('edit_nama_pemeriksa').value = nama;
            document.getElementById('edit_alamat').value = alamat;
            document.getElementById('edit_no_telp').value = telp;
            document.getElementById('editForm').action = '/admin/pemeriksa/' + id;
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
            document.getElementById('hapusForm').action = '/admin/pemeriksa/' + id;

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

    // Tambah multiple pemeriksa
    let pemeriksaCounter = 0;
    document.getElementById('tambah-pemeriksa')?.addEventListener('click', function() {
        pemeriksaCounter++;
        const container = document.getElementById('pemeriksa-container');

        const div = document.createElement('div');
        div.className = 'card mb-3 pemeriksa-item';
        div.innerHTML = `
        <div class="card-header bg-light">
            <div class="d-flex justify-content-between align-items-center">
                <span class="fw-bold">Pemeriksa #${pemeriksaCounter + 1}</span>
                <button type="button" class="btn btn-danger btn-sm remove-pemeriksa">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label">
                        <i class="fas fa-user-md me-1"></i>Nama Pemeriksa
                    </label>
                    <input type="text" class="form-control" name="pemeriksa[${pemeriksaCounter}][nama_pemeriksa]"
                           required maxlength="100" placeholder="Masukkan nama pemeriksa">
                </div>
                <div class="col-md-12 mb-3">
                    <label class="form-label">
                        <i class="fas fa-map-marker-alt me-1"></i>Alamat
                    </label>
                    <textarea class="form-control" name="pemeriksa[${pemeriksaCounter}][alamat]"
                              rows="2" required placeholder="Masukkan alamat"></textarea>
                </div>
                <div class="col-md-12">
                    <label class="form-label">
                        <i class="fas fa-phone me-1"></i>No. Telepon
                    </label>
                    <input type="text" class="form-control" name="pemeriksa[${pemeriksaCounter}][no_telp]"
                           required maxlength="15" placeholder="Masukkan nomor telepon">
                </div>
            </div>
        </div>
    `;

        container.appendChild(div);

        // Aktifkan tombol hapus pada field pertama
        const removeButtons = document.querySelectorAll('.remove-pemeriksa');
        if (removeButtons.length > 1) {
            removeButtons[0].disabled = false;
        }

        // Tambah event listener untuk tombol hapus
        div.querySelector('.remove-pemeriksa').addEventListener('click', function() {
            div.remove();

            // Jika hanya tersisa 1 field, nonaktifkan tombol hapus
            const remainingItems = document.querySelectorAll('.pemeriksa-item');
            if (remainingItems.length === 1) {
                remainingItems[0].querySelector('.remove-pemeriksa').disabled = true;
            }

            // Update nomor urut
            updatePemeriksaNumbers();
        });
    });

    // Update nomor urut pemeriksa
    function updatePemeriksaNumbers() {
        const items = document.querySelectorAll('.pemeriksa-item');
        items.forEach((item, index) => {
            const header = item.querySelector('.card-header span');
            if (header) {
                header.textContent = `Pemeriksa #${index + 1}`;
            }
        });
    }

    // Inisialisasi event listener untuk tombol hapus pertama
    document.querySelector('.remove-pemeriksa')?.addEventListener('click', function() {
        if (!this.disabled) {
            this.closest('.pemeriksa-item').remove();
            updatePemeriksaNumbers();
        }
    });
</script>
@endsection

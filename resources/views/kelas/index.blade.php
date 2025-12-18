@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Data Kelas</h5>
                        <div>
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambahModal">
                                <i class="fas fa-plus"></i> Tambah Kelas
                            </button>
                            <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#tambahMultipleModal">
                                <i class="fas fa-layer-group"></i> Tambah Multiple
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
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

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th width="50">No</th>
                                    <th>Nama Kelas</th>
                                    <th width="150" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($kelas as $index => $item)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $item->nama_kelas }}</td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-warning btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editModal"
                                            data-id="{{ $item->id_kelas }}"
                                            data-nama="{{ $item->nama_kelas }}">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#hapusModal"
                                            data-id="{{ $item->id_kelas }}"
                                            data-nama="{{ $item->nama_kelas }}"
                                            data-jumlah="{{ $item->pasien_count }}">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">Tidak ada data kelas</td>
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

<!-- Modal Tambah Kelas -->
<div class="modal fade" id="tambahModal" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('pasien.kelas.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahModalLabel">Tambah Kelas Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_kelas" class="form-label">Nama Kelas</label>
                        <input type="text" class="form-control" id="nama_kelas" name="nama_kelas"
                            required maxlength="100" placeholder="Masukkan nama kelas">
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

<!-- Modal Tambah Multiple -->
<div class="modal fade" id="tambahMultipleModal" tabindex="-1" aria-labelledby="tambahMultipleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('pasien.kelas.store.multiple') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahMultipleModalLabel">Tambah Kelas Multiple</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted mb-3">Tambahkan beberapa kelas sekaligus</p>

                    <div id="kelas-container">
                        <div class="row mb-3 kelas-item">
                            <div class="col-10">
                                <label class="form-label">Nama Kelas</label>
                                <input type="text" class="form-control" name="kelas[0][nama_kelas]"
                                    required maxlength="100" placeholder="Masukkan nama kelas">
                            </div>
                            <div class="col-2 d-flex align-items-end">
                                <button type="button" class="btn btn-danger btn-sm remove-kelas" disabled>
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <button type="button" id="tambah-kelas" class="btn btn-success btn-sm">
                        <i class="fas fa-plus"></i> Tambah Field
                    </button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Semua</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Kelas -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Kelas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_id" name="id">
                    <div class="mb-3">
                        <label for="edit_nama_kelas" class="form-label">Nama Kelas</label>
                        <input type="text" class="form-control" id="edit_nama_kelas" name="nama_kelas"
                            required maxlength="100">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Perbarui</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Hapus Kelas -->
<div class="modal fade" id="hapusModal" tabindex="-1" aria-labelledby="hapusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="hapusForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title" id="hapusModalLabel">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="hapus_id" name="id">
                    <p>Apakah Anda yakin ingin menghapus kelas <strong id="hapus_nama"></strong>?</p>
                    <div id="warningPasien" class="alert alert-warning d-none">
                        <i class="fas fa-exclamation-triangle"></i>
                        Kelas ini memiliki <span id="jumlah_pasien">0</span> pasien.
                        Jika dihapus, semua pasien terkait akan kehilangan data kelas ini.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>
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

            document.getElementById('edit_nama_kelas').value = nama;
            document.getElementById('editForm').action = 'admin/kelas/' + id;
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
            document.getElementById('hapusForm').action = 'admin/kelas/' + id;

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

    // Tambah multiple kelas
    let kelasCounter = 0;
    document.getElementById('tambah-kelas')?.addEventListener('click', function() {
        kelasCounter++;
        const container = document.getElementById('kelas-container');

        const div = document.createElement('div');
        div.className = 'row mb-3 kelas-item';
        div.innerHTML = `
        <div class="col-10">
            <label class="form-label">Nama Kelas</label>
            <input type="text" class="form-control" name="kelas[${kelasCounter}][nama_kelas]"
                   required maxlength="100" placeholder="Masukkan nama kelas">
        </div>
        <div class="col-2 d-flex align-items-end">
            <button type="button" class="btn btn-danger btn-sm remove-kelas">
                <i class="fas fa-minus"></i>
            </button>
        </div>
    `;

        container.appendChild(div);

        // Aktifkan tombol hapus pada field pertama
        const removeButtons = document.querySelectorAll('.remove-kelas');
        if (removeButtons.length > 1) {
            removeButtons[0].disabled = false;
        }

        // Tambah event listener untuk tombol hapus
        div.querySelector('.remove-kelas').addEventListener('click', function() {
            div.remove();

            // Jika hanya tersisa 1 field, nonaktifkan tombol hapus
            const remainingItems = document.querySelectorAll('.kelas-item');
            if (remainingItems.length === 1) {
                remainingItems[0].querySelector('.remove-kelas').disabled = true;
            }
        });
    });

    // Inisialisasi event listener untuk tombol hapus pertama
    document.querySelector('.remove-kelas')?.addEventListener('click', function() {
        if (!this.disabled) {
            this.closest('.kelas-item').remove();
        }
    });
</script>
@endsection

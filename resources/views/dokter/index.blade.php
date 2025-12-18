@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card mb-3"> <!-- Changed from shadow mb-4 to mb-3 -->
                <div class="card-header d-flex justify-content-between align-items-center"> <!-- Changed classes -->
                    <h5 class="card-title mb-0">Data Dokter</h5> <!-- Changed from h6.m-0.font-weight-bold.text-primary -->
                    <div class="d-flex align-items-center gap-2"> <!-- Added gap-2 -->
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                            <i class="fas fa-plus"></i> Tambah Dokter
                        </button>
                        <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#addMultipleModal">
                            <i class="fas fa-layer-group"></i> Tambah Banyak
                        </button>
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

                    <!-- Table starts -->
                    <div class="table-outer">
                        <div class="table-responsive">
                            <table class="table m-0 align-middle">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Dokter</th>
                                        <th>Alamat</th>
                                        <th>Nomor Telepon</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($dokters as $dokter)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $dokter->nama_dokter ?? '-' }}</td>
                                        <td>{{ $dokter->alamat ?? '' }}</td>
                                        <td>{{ $dokter->no_telp ?? '' }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-warning"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editModal"
                                                data-id="{{ $dokter->id_dokter }}"
                                                data-nama="{{ $dokter->nama_dokter }}"
                                                data-alamat="{{ $dokter->alamat }}"
                                                data-telepon="{{ $dokter->no_telp }}">
                                                <i class="bi bi-pencil-square"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger delete-btn"
                                                data-id="{{ $dokter->id_dokter }}"
                                                data-nama="{{ $dokter->nama_dokter }}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Table ends -->
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Dokter -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('pasien.dokter.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addModalLabel">Tambah Dokter</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nama_dokter" class="form-label">Nama Dokter *</label>
                            <input type="text" class="form-control" id="nama_dokter" name="nama_dokter" required>
                        </div>
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat *</label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="no_telp" class="form-label">Nomor Telepon *</label>
                            <input type="text" class="form-control" id="no_telp" name="no_telp" required>
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

    <!-- Modal Edit Dokter -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Dokter</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="edit_id" name="id">
                        <div class="mb-3">
                            <label for="edit_nama_dokter" class="form-label">Nama Dokter *</label>
                            <input type="text" class="form-control" id="edit_nama_dokter" name="nama_dokter" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_alamat" class="form-label">Alamat *</label>
                            <textarea class="form-control" id="edit_alamat" name="alamat" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="edit_no_telp" class="form-label">Nomor Telepon *</label>
                            <input type="text" class="form-control" id="edit_no_telp" name="no_telp" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Banyak Dokter -->
    <div class="modal fade" id="addMultipleModal" tabindex="-1" aria-labelledby="addMultipleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('pasien.dokter.store.multiple') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addMultipleModalLabel">Tambah Banyak Dokter</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="dokterContainer">
                            <div class="dokter-form mb-4 p-3 border rounded">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="mb-0">Dokter #1</h6>
                                    <button type="button" class="btn btn-sm btn-danger remove-dokter" onclick="removeDokterForm(this)">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Nama Dokter *</label>
                                        <input type="text" class="form-control" name="dokters[0][nama_dokter]" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Alamat *</label>
                                        <textarea class="form-control" name="dokters[0][alamat]" rows="1" required></textarea>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Nomor Telepon *</label>
                                        <input type="text" class="form-control" name="dokters[0][no_telp]" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addDokterForm()">
                            <i class="fas fa-plus"></i> Tambah Form Dokter
                        </button>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">Simpan Semua</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Delete Confirmation -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menghapus dokter <strong id="deleteDokterName"></strong>?</p>
                        <p class="text-danger"><small>Tindakan ini tidak dapat dibatalkan.</small></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Edit Modal Handler
    var editModal = document.getElementById('editModal');
    editModal.addEventListener('show.bs.modal', function(event) {
        var button = event.relatedTarget;
        var id = button.getAttribute('data-id');
        var nama = button.getAttribute('data-nama');
        var alamat = button.getAttribute('data-alamat');
        var telepon = button.getAttribute('data-telepon');

        var modalTitle = editModal.querySelector('.modal-title');
        var dokterId = editModal.querySelector('#edit_id');
        var dokterNama = editModal.querySelector('#edit_nama_dokter');
        var dokterAlamat = editModal.querySelector('#edit_alamat');
        var dokterTelepon = editModal.querySelector('#edit_no_telp');
        var form = editModal.querySelector('#editForm');

        modalTitle.textContent = 'Edit Dokter: ' + nama;
        dokterId.value = id;
        dokterNama.value = nama;
        dokterAlamat.value = alamat;
        dokterTelepon.value = telepon;
        form.action = '{{ url("admin/dokter") }}/' + id;
    });

    // Delete Modal Handler
    var deleteModal = document.getElementById('deleteModal');
    var deleteButtons = document.querySelectorAll('.delete-btn');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            var id = this.getAttribute('data-id');
            var nama = this.getAttribute('data-nama');

            var deleteForm = deleteModal.querySelector('#deleteForm');
            var dokterName = deleteModal.querySelector('#deleteDokterName');

            dokterName.textContent = nama;
            deleteForm.action = '{{ url("admin/dokter") }}/' + id;

            var modal = new bootstrap.Modal(deleteModal);
            modal.show();
        });
    });

    // Dynamic form untuk tambah banyak dokter
    let dokterCounter = 1;

    function addDokterForm() {
        const container = document.getElementById('dokterContainer');
        const newForm = document.createElement('div');
        newForm.className = 'dokter-form mb-4 p-3 border rounded';
        newForm.innerHTML = `
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0">Dokter #${dokterCounter + 1}</h6>
                <button type="button" class="btn btn-sm btn-danger remove-dokter" onclick="removeDokterForm(this)">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Nama Dokter *</label>
                    <input type="text" class="form-control" name="dokters[${dokterCounter}][nama_dokter]" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Alamat *</label>
                    <textarea class="form-control" name="dokters[${dokterCounter}][alamat]" rows="1" required></textarea>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Nomor Telepon *</label>
                    <input type="text" class="form-control" name="dokters[${dokterCounter}][no_telp]" required>
                </div>
            </div>
        `;
        container.appendChild(newForm);
        dokterCounter++;
    }

    function removeDokterForm(button) {
        const form = button.closest('.dokter-form');
        form.remove();
        renumberForms();
    }

    function renumberForms() {
        const forms = document.querySelectorAll('.dokter-form');
        forms.forEach((form, index) => {
            const header = form.querySelector('h6');
            const inputs = form.querySelectorAll('input, textarea');

            header.textContent = `Dokter #${index + 1}`;

            inputs.forEach(input => {
                const name = input.getAttribute('name');
                if (name) {
                    const newName = name.replace(/\[\d+\]/, `[${index}]`);
                    input.setAttribute('name', newName);
                }
            });
        });
        dokterCounter = forms.length;
    }

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

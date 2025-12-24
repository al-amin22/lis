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
            <a href="{{ route('pasien.index.jenis.pemeriksaan') }}">Jenis Pemeriksaan</a>
        </li>
        <li class="breadcrumb-item text-primary" aria-current="page">
            Data Pemeriksaan
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
                    <h5 class="card-title mb-0">Daftar Data Pemeriksaan</h5>
                    <div class="d-flex align-items-center gap-2">
                        <!-- Modal Trigger for Single Input -->
                        <!-- <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#tambahModal">
                            <i class="ri-add-line me-1"></i> Tambah Data
                        </button> -->

                        <!-- Modal Trigger for Batch Input -->
                        <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#tambahBatchModal">
                            <i class="ri-file-list-line me-1"></i> Tambah Data
                        </button>



                        <!-- Filter by Jenis Pemeriksaan -->
                        <select id="filterJenis" class="form-select form-select-sm" style="width: 200px;">
                            <option value="">Semua Jenis Pemeriksaan</option>
                            @foreach($jenisPemeriksaans as $jenis)
                            <option value="{{ $jenis->id_jenis_pemeriksaan_1 }}">{{ $jenis->nama_pemeriksaan }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Table starts -->
                    <div class="table-outer">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle text-nowrap">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode</th>
                                        <th>Jenis Pemeriksaan</th>
                                        <th>Data Pemeriksaan</th>
                                        <!-- <th>LIS</th> -->
                                        <th>Satuan</th>
                                        <th>Rujukan</th>
                                        <!-- <th>Metode</th> -->
                                        <th>Urutan</th>
                                        <th>CH</th>
                                        <th>CL</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($dataPemeriksaans as $index => $data)
                                    <tr class="data-row" data-jenis="{{ $data->id_jenis_pemeriksaan_1 }}">
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <span class="badge bg-warning">{{ $data->id_data_pemeriksaan }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">
                                                {{ $data->jenisPemeriksaan->nama_pemeriksaan ?? '-' }}
                                            </span>
                                        </td>
                                        <td>{{ $data->data_pemeriksaan }}</td>
                                        <!-- <td>{{ $data->lis ?? '-' }}</td> -->
                                        <td>{{ $data->satuan ?? '-' }}</td>
                                        <td>{{ $data->rujukan ?? '-' }}</td>
                                        <!-- <td>{{ $data->metode ?? '-' }}</td> -->
                                        <td>{{ $data->urutan ?? '-' }}</td>
                                        <td>{{ $data->ch ?? '-' }}</td>
                                        <td>{{ $data->cl ?? '-' }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-warning"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editModal"
                                                data-kode="{{ $data->id_data_pemeriksaan }}"
                                                data-jenis="{{ $data->id_jenis_pemeriksaan_1 }}"
                                                data-nama="{{ $data->data_pemeriksaan }}"
                                                data-lis="{{ $data->lis }}"
                                                data-satuan="{{ $data->satuan }}"
                                                data-rujukan="{{ $data->rujukan }}"
                                                data-metode="{{ $data->metode }}"
                                                data-urutan="{{ $data->urutan }}"
                                                data-ch="{{ $data->ch }}"
                                                data-cl="{{ $data->cl }}">
                                                <i class="ri-edit-line"></i> Edit
                                            </button>
                                            <button class="btn btn-sm btn-primary"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalBatchJenis{{ $data->id_jenis_pemeriksaan_1 }}">
                                                Edit Batch
                                            </button>

                                            <!-- <form action="{{ route('pasien.destroy.data.pemeriksaan', $data->id_data_pemeriksaan) }}"
                                                method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
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
                                        <td colspan="9" class="text-center py-4">
                                            <p class="text-muted">Tidak ada data pemeriksaan.</p>
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

@foreach($jenisPemeriksaans as $jenis)
<div class="modal fade" id="modalBatchJenis{{ $jenis->id_jenis_pemeriksaan_1 }}" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <form method="POST"
                  action="{{ route('pasien.data-pemeriksaan.update-batch-jenis', $jenis->id_jenis_pemeriksaan_1) }}">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title">
                        Update Batch – {{ $jenis->nama_pemeriksaan }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Nama Pemeriksaan</th>
                                    <th>Satuan</th>
                                    <th>Rujukan</th>
                                    <th>Metode</th>
                                    <th>Urutan</th>
                                    <th>CH</th>
                                    <th>CL</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($jenis->dataPemeriksaan as $i => $item)
                                <tr>
                                    <td>
                                        {{ $item->id_data_pemeriksaan }}
                                        <input type="hidden"
                                            name="items[{{ $i }}][id_data_pemeriksaan]"
                                            value="{{ $item->id_data_pemeriksaan }}">
                                    </td>

                                    <td>
                                        <input type="text" class="form-control"
                                            name="items[{{ $i }}][data_pemeriksaan]"
                                            value="{{ $item->data_pemeriksaan }}">
                                    </td>

                                    <td>
                                        <input type="text" class="form-control"
                                            name="items[{{ $i }}][satuan]"
                                            value="{{ $item->satuan }}">
                                    </td>

                                    <td>
                                        <input type="text" class="form-control"
                                            name="items[{{ $i }}][rujukan]"
                                            value="{{ $item->rujukan }}">
                                    </td>

                                    <td>
                                        <input type="text" class="form-control"
                                            name="items[{{ $i }}][metode]"
                                            value="{{ $item->metode }}">
                                    </td>

                                    <td>
                                        <input type="number" class="form-control"
                                            name="items[{{ $i }}][urutan]"
                                            value="{{ $item->urutan }}">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control"
                                            name="items[{{ $i }}][ch]"
                                            value="{{ $item->ch }}">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control"
                                            name="items[{{ $i }}][cl]"
                                            value="{{ $item->cl }}">
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary">Update Semua</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>

            </form>

        </div>
    </div>
</div>
@endforeach

<!-- Modal Tambah Single -->
<div class="modal fade" id="tambahModal" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahModalLabel">Tambah Data Pemeriksaan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('pasien.store.data.pemeriksaan') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="id_jenis_pemeriksaan_1" class="form-label">Jenis Pemeriksaan *</label>
                            <select class="form-select" id="id_jenis_pemeriksaan_1" name="id_jenis_pemeriksaan_1" required>
                                <option value="">Pilih Jenis Pemeriksaan</option>
                                @foreach($jenisPemeriksaans as $jenis)
                                <option value="{{ $jenis->id_jenis_pemeriksaan_1 }}">{{ $jenis->nama_pemeriksaan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="data_pemeriksaan" class="form-label">Data Pemeriksaan *</label>
                            <input type="text" class="form-control" id="data_pemeriksaan" name="data_pemeriksaan" required
                                placeholder="Contoh: Hemoglobin">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="lis" class="form-label">LIS</label>
                            <input type="text" class="form-control" id="lis" name="lis"
                                placeholder="Contoh: HGB">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="satuan" class="form-label">Satuan</label>
                            <input type="text" class="form-control" id="satuan" name="satuan"
                                placeholder="Contoh: g/dL">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="rujukan" class="form-label">Nilai Rujukan</label>
                            <input type="text" class="form-control" id="rujukan" name="rujukan"
                                placeholder="Contoh: 12.0 - 16.0">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="metode" class="form-label">Metode</label>
                            <input type="text" class="form-control" id="metode" name="metode"
                                placeholder="Contoh: Cyanmethemoglobin">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="urutan" class="form-label">Urutan</label>
                            <input type="number" class="form-control" id="urutan" name="urutan"
                                placeholder="Contoh: 1">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="ch" class="form-label">CH</label>
                            <input type="text" class="form-control" id="ch" name="ch"
                                placeholder="Contoh: ">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="cl" class="form-label">CL</label>
                            <input type="text" class="form-control" id="cl" name="cl"
                                placeholder="Contoh: ">
                        </div>
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
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahBatchModalLabel">Tambah Banyak Data Pemeriksaan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('pasien.store.batch.data.pemeriksaan') }}" method="POST" id="batchForm">
                @csrf
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="batch_id_jenis_pemeriksaan_1" class="form-label">Jenis Pemeriksaan *</label>
                            <select class="form-select" id="batch_id_jenis_pemeriksaan_1" name="id_jenis_pemeriksaan_1" required>
                                <option value="">Pilih Jenis Pemeriksaan</option>
                                @foreach($jenisPemeriksaans as $jenis)
                                <option value="{{ $jenis->id_jenis_pemeriksaan_1 }}">{{ $jenis->nama_pemeriksaan }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered" id="batchTable">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="35%">Nama Pemeriksaan *</th>
                                    <!-- <th width="15%">LIS</th> -->
                                    <th width="15%">Satuan</th>
                                    <th width="15%">Rujukan</th>
                                    <th width="15%">Metode</th>
                                    <th width="15%">Urutan</th>
                                    <th width="5%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="batchTableBody">
                                <!-- Baris pertama akan ditambahkan otomatis -->
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        <button type="button" class="btn btn-sm btn-outline-primary" id="addRowBtn">
                            <i class="ri-add-line me-1"></i> Tambah Baris
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="addMultipleBtn">
                            <i class="ri-add-box-line me-1"></i> Tambah 5 Baris
                        </button>
                    </div>

                    <div class="alert alert-info mt-3">
                        <i class="ri-information-line me-2"></i>
                        Kode pemeriksaan akan digenerate otomatis berdasarkan jenis pemeriksaan yang dipilih.
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
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Data Pemeriksaan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="edit_id_jenis_pemeriksaan_1" class="form-label">Jenis Pemeriksaan *</label>
                            <select class="form-select" id="edit_id_jenis_pemeriksaan_1" name="id_jenis_pemeriksaan_1" required>
                                <option value="">Pilih Jenis Pemeriksaan</option>
                                @foreach($jenisPemeriksaans as $jenis)
                                <option value="{{ $jenis->id_jenis_pemeriksaan_1 }}">{{ $jenis->nama_pemeriksaan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_data_pemeriksaan" class="form-label">Data Pemeriksaan *</label>
                            <input type="text" class="form-control" id="edit_data_pemeriksaan" name="data_pemeriksaan" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_lis" class="form-label">LIS</label>
                            <input type="text" class="form-control" id="edit_lis" name="lis">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_satuan" class="form-label">Satuan</label>
                            <input type="text" class="form-control" id="edit_satuan" name="satuan">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="edit_rujukan" class="form-label">Nilai Rujukan</label>
                            <input type="text" class="form-control" id="edit_rujukan" name="rujukan">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="edit_metode" class="form-label">Metode</label>
                            <input type="text" class="form-control" id="edit_metode" name="metode">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_urutan" class="form-label">Urutan</label>
                            <input type="number" class="form-control" id="edit_urutan" name="urutan">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_ch" class="form-label">CH</label>
                            <input type="text" class="form-control" id="edit_ch" name="ch">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_cl" class="form-label">CL</label>
                            <input type="text" class="form-control" id="edit_cl" name="cl">
                        </div>
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
<script>
    // Filter by Jenis Pemeriksaan
    // Edit Modal Handler
    document.addEventListener('DOMContentLoaded', function() {
        const editModal = document.getElementById('editModal');

        editModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const kode = button.getAttribute('data-kode');
            const jenis = button.getAttribute('data-jenis');
            const nama = button.getAttribute('data-nama');
            const lis = button.getAttribute('data-lis');
            const satuan = button.getAttribute('data-satuan');
            const rujukan = button.getAttribute('data-rujukan');
            const metode = button.getAttribute('data-metode');
            const urutan = button.getAttribute('data-urutan');
            const ch = button.getAttribute('data-ch');
            const cl = button.getAttribute('data-cl');

            // Update modal title
            const modalTitle = editModal.querySelector('.modal-title');
            modalTitle.textContent = 'Edit: ' + nama;

            // Set form values
            document.getElementById('edit_id_jenis_pemeriksaan_1').value = jenis;
            document.getElementById('edit_data_pemeriksaan').value = nama;
            document.getElementById('edit_lis').value = lis || '';
            document.getElementById('edit_satuan').value = satuan || '';
            document.getElementById('edit_rujukan').value = rujukan || '';
            document.getElementById('edit_metode').value = metode || '';
            document.getElementById('edit_urutan').value = urutan || '';
            document.getElementById('edit_ch').value = ch || '';
            document.getElementById('edit_cl').value = cl || '';

            // Set form action
            const form = editModal.querySelector('#editForm');
            form.action = "{{ route('pasien.update.data.pemeriksaan', '') }}/" + kode;
        });
    });

    // Edit Modal Handler
    document.addEventListener('DOMContentLoaded', function() {
        const editModal = document.getElementById('editModal');

        editModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const kode = button.getAttribute('data-kode');
            const jenis = button.getAttribute('data-jenis');
            const nama = button.getAttribute('data-nama');
            const lis = button.getAttribute('data-lis');
            const satuan = button.getAttribute('data-satuan');
            const rujukan = button.getAttribute('data-rujukan');
            const metode = button.getAttribute('data-metode');
            const urutan = button.getAttribute('data-urutan');
            const ch = button.getAttribute('data-ch');
            const cl = button.getAttribute('data-cl');

            // Update modal content
            const modalTitle = editModal.querySelector('.modal-title');
            const form = editModal.querySelector('#editForm');

            modalTitle.textContent = 'Edit: ' + nama;

            // Set form values
            document.getElementById('edit_id_jenis_pemeriksaan_1').value = jenis;
            document.getElementById('edit_data_pemeriksaan').value = nama;
            document.getElementById('edit_lis').value = lis;
            document.getElementById('edit_satuan').value = satuan;
            document.getElementById('edit_rujukan').value = rujukan;
            document.getElementById('edit_urutan').value = '';
            document.getElementById('edit_metode').value = metode;
            document.getElementById('edit_urutan').value = urutan;
            document.getElementById('edit_ch').value = ch;
            document.getElementById('edit_cl').value = cl;

            // Set form action
            form.action = "{{ route('pasien.update.data.pemeriksaan', '') }}/" + kode;
        });
    });

    // Batch Input Functionality
    document.addEventListener('DOMContentLoaded', function() {
        let rowCount = 0;
        const batchTableBody = document.getElementById('batchTableBody');
        const addRowBtn = document.getElementById('addRowBtn');
        const addMultipleBtn = document.getElementById('addMultipleBtn');
        const batchForm = document.getElementById('batchForm');

        // Function to add a new row
        function addRow() {
            rowCount++;
            const rowIndex = rowCount - 1;

            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${rowCount}</td>
                <td>
                    <input type="text"
                           name="nama_pemeriksaan[${rowIndex}]"
                           class="form-control form-control-sm"
                           placeholder="Nama pemeriksaan"
                           required>
                </td>
               {{-- <td>
                    <input type="text"
                           name="lis[${rowIndex}]"
                           class="form-control form-control-sm"
                           placeholder="LIS">
                </td> --}}
                <td>
                    <input type="text"
                           name="satuan[${rowIndex}]"
                           class="form-control form-control-sm"
                           placeholder="Satuan">
                </td>
                <td>
                    <input type="text"
                           name="rujukan[${rowIndex}]"
                           class="form-control form-control-sm"
                           placeholder="Nilai rujukan">
                </td>
                <td>
                    <input type="text"
                           name="metode[${rowIndex}]"
                           class="form-control form-control-sm"
                           placeholder="Metode">
                </td>
                <td>
                    <input type="number"
                           name="urutan[${rowIndex}]"
                           class="form-control form-control-sm"
                           placeholder="Urutan">
                </td>
                <td>
                    <input type="number"
                           name="ch[${rowIndex}]"
                           class="form-control form-control-sm"
                           placeholder="ch">
                </td>
                <td>
                    <input type="number"
                           name="cl[${rowIndex}]"
                           class="form-control form-control-sm"
                           placeholder="cl">
                </td>
                <td>
                    <button type="button" class="btn btn-sm btn-outline-danger remove-row" ${rowCount === 1 ? 'disabled' : ''}>
                        <i class="ri-delete-bin-line"></i>
                    </button>
                </td>
            `;

            batchTableBody.appendChild(row);

            // Add event listener for remove button
            const removeBtn = row.querySelector('.remove-row');
            removeBtn.addEventListener('click', function() {
                if (rowCount > 1) {
                    row.remove();
                    rowCount--;
                    updateRowNumbers();
                }
            });
        }

        // Function to update row numbers
        function updateRowNumbers() {
            const rows = batchTableBody.querySelectorAll('tr');
            rows.forEach((row, index) => {
                row.querySelector('td:first-child').textContent = index + 1;
            });
        }

        // Function to add multiple rows
        function addMultipleRows(count) {
            for (let i = 0; i < count; i++) {
                addRow();
            }
        }

        // Add initial row
        addRow();

        // Event listeners
        addRowBtn.addEventListener('click', function() {
            addRow();
        });

        addMultipleBtn.addEventListener('click', function() {
            addMultipleRows(5);
        });

        // Form validation before submit
        batchForm.addEventListener('submit', function(e) {
            const jenisSelect = document.getElementById('batch_id_jenis_pemeriksaan_1');
            const namaInputs = batchTableBody.querySelectorAll('input[name^="nama_pemeriksaan"]');

            // Check if jenis pemeriksaan is selected
            if (!jenisSelect.value) {
                e.preventDefault();
                alert('Pilih jenis pemeriksaan terlebih dahulu!');
                jenisSelect.focus();
                return;
            }

            // Check if at least one row has nama pemeriksaan filled
            let hasData = false;
            namaInputs.forEach(input => {
                if (input.value.trim()) {
                    hasData = true;
                }
            });

            if (!hasData) {
                e.preventDefault();
                alert('Isi minimal satu data pemeriksaan!');
                return;
            }
        });

        // Auto-focus on first input when modal opens
        const batchModal = document.getElementById('tambahBatchModal');
        batchModal.addEventListener('shown.bs.modal', function() {
            const firstInput = batchTableBody.querySelector('input[name^="nama_pemeriksaan"]');
            if (firstInput) {
                firstInput.focus();
            }
        });

        // Reset form when modal closes
        batchModal.addEventListener('hidden.bs.modal', function() {
            // Reset to one row
            batchTableBody.innerHTML = '';
            rowCount = 0;
            addRow();

            // Reset jenis select
            document.getElementById('batch_id_jenis_pemeriksaan_1').value = '';
        });
    });
</script>

@endsection

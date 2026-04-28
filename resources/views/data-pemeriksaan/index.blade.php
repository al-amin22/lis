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
                            <i class="ri-file-list-line me-1"></i> Tambah Batch
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
                                        <th>kode uji</th>
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
                                        <td>{{ $data->kode_uji_pemeriksaan ?? '-' }}</td>
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
                                            <button type="button" class="btn btn-sm btn-primary"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalBatchJenis{{ $data->id_jenis_pemeriksaan_1 }}">
                                                <i class="ri-file-list-line"></i> Edit Batch
                                            </button>

                                            <a href="{{ route('pasien.data-pemeriksaan.show', $data->id_data_pemeriksaan) }}"
                                                class="btn btn-sm btn-info"
                                                title="Lihat Detail">
                                                    <i class="ri-eye-line"></i> Show
                                            </a>


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
<div class="modal fade" id="modalBatchJenis{{ $jenis->id_jenis_pemeriksaan_1 }}" tabindex="-1" role="dialog" aria-labelledby="modalBatchJenisLabel{{ $jenis->id_jenis_pemeriksaan_1 }}" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form method="POST" action="{{ route('pasien.data-pemeriksaan.update-batch-jenis', $jenis->id_jenis_pemeriksaan_1) }}">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5 class="modal-title" id="modalBatchJenisLabel{{ $jenis->id_jenis_pemeriksaan_1 }}">
                        Update Batch – {{ $jenis->nama_pemeriksaan }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                                    <th>Kode Uji Pemeriksaan</th>
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
                                        <input type="text"
                                               class="form-control"
                                               name="items[{{ $i }}][data_pemeriksaan]"
                                               value="{{ $item->data_pemeriksaan }}">
                                    </td>

                                    <td>
                                        <input type="text"
                                               class="form-control"
                                               name="items[{{ $i }}][satuan]"
                                               value="{{ $item->satuan }}">
                                    </td>

                                    <td>
                                        <input type="text"
                                               class="form-control"
                                               name="items[{{ $i }}][rujukan]"
                                               value="{{ $item->rujukan }}">
                                    </td>

                                    <td>
                                        <input type="text"
                                               class="form-control"
                                               name="items[{{ $i }}][metode]"
                                               value="{{ $item->metode }}">
                                    </td>

                                    <td>
                                        <input type="number"
                                               class="form-control"
                                               name="items[{{ $i }}][urutan]"
                                               value="{{ $item->urutan }}">
                                    </td>

                                    <td>
                                        <input type="text"
                                               class="form-control"
                                               name="items[{{ $i }}][ch]"
                                               value="{{ $item->ch }}">
                                    </td>

                                    <td>
                                        <input type="text"
                                               class="form-control"
                                               name="items[{{ $i }}][cl]"
                                               value="{{ $item->cl }}">
                                    </td>

                                    <td>
                                        <div class="position-relative search-container">
                                            <input type="text"
                                                   class="form-control uji-search-input"
                                                   placeholder="Cari kode / nama uji..."
                                                   value="{{ $item->kode_uji_pemeriksaan }}"
                                                   autocomplete="off"
                                                   data-id="{{ $item->id_data_pemeriksaan }}">
                                            <div class="search-results dropdown-menu"></div>
                                            <input type="hidden"
                                                   name="items[{{ $i }}][kode_uji_pemeriksaan]"
                                                   value="{{ $item->kode_uji_pemeriksaan }}"
                                                   class="uji-kode-hidden">
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update Semua</button>
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
                <h5 class="modal-title" id="tambahBatchModalLabel">Tambah Batch Data Pemeriksaan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('pasien.data-pemeriksaan.store-batch') }}" method="POST" id="batchForm">
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
                                    <th width="20%">Kode Pemeriksaan *</th>
                                    <th width="25%">Data Pemeriksaan *</th>
                                    <th width="15%">Satuan</th>
                                    <th width="15%">Rujukan</th>
                                    <th width="15%">Metode</th>
                                    <th width="8%">CH</th>
                                    <th width="8%">CL</th>
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
                        Kode pemeriksaan diisi manual. Urutan tidak perlu diisi karena akan disimpan null.
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
    document.addEventListener('DOMContentLoaded', function() {
        // ==================== FILTER JENIS PEMERIKSAAN ====================
        const filterJenis = document.getElementById('filterJenis');
        if (filterJenis) {
            filterJenis.addEventListener('change', function() {
                const selectedJenis = this.value;
                const dataRows = document.querySelectorAll('.data-row');

                dataRows.forEach(row => {
                    if (!selectedJenis || row.getAttribute('data-jenis') === selectedJenis) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        }

        // ==================== EDIT MODAL HANDLER ====================
        const editModal = document.getElementById('editModal');
        if (editModal) {
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
        }

        // ==================== BATCH ADD MODAL FUNCTIONALITY ====================
        const batchModal = document.getElementById('tambahBatchModal');
        const batchTableBody = document.getElementById('batchTableBody');
        let batchRowCount = 0;

        if (batchModal && batchTableBody) {
            // Initialize when modal is shown
            batchModal.addEventListener('shown.bs.modal', function() {
                initializeBatchModal();
            });
        }

        function initializeBatchModal() {
            // Clear existing rows
            batchTableBody.innerHTML = '';
            batchRowCount = 0;

            // Add first row
            addBatchRow();

            // Setup event listeners for add buttons
            const addRowBtn = document.getElementById('addRowBtn');
            const addMultipleBtn = document.getElementById('addMultipleBtn');

            if (addRowBtn) {
                addRowBtn.onclick = function() {
                    addBatchRow();
                };
            }

            if (addMultipleBtn) {
                addMultipleBtn.onclick = function() {
                    for (let i = 0; i < 5; i++) {
                        addBatchRow();
                    }
                };
            }
        }

        function addBatchRow() {
            const rowIndex = batchRowCount++;
            const row = document.createElement('tr');

            row.innerHTML = `
                <td>${rowIndex + 1}</td>
                <td>
                    <input type="text"
                           class="form-control form-control-sm"
                           name="kode_pemeriksaan[${rowIndex}]"
                           placeholder="Contoh: 1230 atau 4 digit angka lainnya">
                </td>
                <td>
                    <input type="text"
                           class="form-control form-control-sm"
                           name="data_pemeriksaan[${rowIndex}]"
                           placeholder="Contoh: Hemoglobin">
                </td>
                <td>
                    <input type="text"
                           class="form-control form-control-sm"
                           name="satuan[${rowIndex}]"
                           placeholder="Satuan">
                </td>
                <td>
                    <input type="text"
                           class="form-control form-control-sm"
                           name="rujukan[${rowIndex}]"
                           placeholder="Rujukan">
                </td>
                <td>
                    <input type="text"
                           class="form-control form-control-sm"
                           name="metode[${rowIndex}]"
                           placeholder="Metode">
                </td>
                <td>
                    <input type="text"
                           class="form-control form-control-sm"
                           name="ch[${rowIndex}]"
                           placeholder="CH">
                </td>
                <td>
                    <input type="text"
                           class="form-control form-control-sm"
                           name="cl[${rowIndex}]"
                           placeholder="CL">
                </td>
                <td>
                    <button type="button"
                            class="btn btn-sm btn-outline-danger remove-row-btn"
                            ${rowIndex === 0 ? 'disabled' : ''}>
                        <i class="ri-delete-bin-line"></i>
                    </button>
                </td>
            `;

            batchTableBody.appendChild(row);

            // Setup remove button
            const removeBtn = row.querySelector('.remove-row-btn');
            if (removeBtn) {
                removeBtn.onclick = function() {
                    if (batchRowCount > 1) {
                        row.remove();
                        batchRowCount--;
                        updateBatchRowNumbers();
                    }
                };
            }
        }

        function updateBatchRowNumbers() {
            const rows = batchTableBody.querySelectorAll('tr');
            rows.forEach((row, index) => {
                // Update row number
                const firstCell = row.querySelector('td:first-child');
                if (firstCell) firstCell.textContent = index + 1;

                const kodePemeriksaanInput = row.querySelector('input[name^="kode_pemeriksaan"]');
                if (kodePemeriksaanInput) kodePemeriksaanInput.name = `kode_pemeriksaan[${index}]`;

                const dataPemeriksaanInput = row.querySelector('input[name^="data_pemeriksaan"]');
                if (dataPemeriksaanInput) dataPemeriksaanInput.name = `data_pemeriksaan[${index}]`;

                const satuanInput = row.querySelector('input[name^="satuan"]');
                if (satuanInput) satuanInput.name = `satuan[${index}]`;

                const rujukanInput = row.querySelector('input[name^="rujukan"]');
                if (rujukanInput) rujukanInput.name = `rujukan[${index}]`;

                const metodeInput = row.querySelector('input[name^="metode"]');
                if (metodeInput) metodeInput.name = `metode[${index}]`;

                const chInput = row.querySelector('input[name^="ch"]');
                if (chInput) chInput.name = `ch[${index}]`;

                const clInput = row.querySelector('input[name^="cl"]');
                if (clInput) clInput.name = `cl[${index}]`;
            });
        }

        // ==================== FORM VALIDATION ====================
        const batchForm = document.getElementById('batchForm');
        if (batchForm) {
            batchForm.addEventListener('submit', function(e) {
                const jenisSelect = document.getElementById('batch_id_jenis_pemeriksaan_1');
                if (!jenisSelect || !jenisSelect.value) {
                    e.preventDefault();
                    alert('Pilih jenis pemeriksaan terlebih dahulu!');
                    if (jenisSelect) jenisSelect.focus();
                    return;
                }

                // Check if at least one row has data
                const hasData = Array.from(batchTableBody.querySelectorAll('input[name^="data_pemeriksaan"]'))
                    .some(input => input.value.trim() !== '');

                if (!hasData) {
                    e.preventDefault();
                    alert('Isi minimal satu data pemeriksaan!');
                    return;
                }
            });
        }

        // ==================== MODAL CLEANUP ====================
        // Reset batch modal when closed
        if (batchModal) {
            batchModal.addEventListener('hidden.bs.modal', function() {
                batchTableBody.innerHTML = '';
                batchRowCount = 0;
            });
        }
    });
</script>
@endsection

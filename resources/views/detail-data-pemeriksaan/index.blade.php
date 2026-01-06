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
        <li class="breadcrumb-item">
            <a href="{{ route('pasien.index.data.pemeriksaan') }}">Data Pemeriksaan</a>
        </li>
        <li class="breadcrumb-item text-primary" aria-current="page">
            Detail Data Pemeriksaan
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
                    <h5 class="card-title mb-0">Daftar Detail Data Pemeriksaan</h5>
                    <div class="d-flex align-items-center gap-2">
                        <!-- Search form -->
                        <form class="d-flex me-2" method="GET" action="{{ route('detail-data-pemeriksaan.index') }}">
                            <div class="input-group input-group-sm" style="width: 250px;">
                                <input type="text"
                                       class="form-control"
                                       name="search"
                                       placeholder="Cari data pemeriksaan/umur/rujukan..."
                                       value="{{ request('search') }}">
                                <button class="btn btn-outline-secondary" type="submit">
                                    <i class="ri-search-line"></i>
                                </button>
                                @if(request('search'))
                                <a href="{{ route('detail-data-pemeriksaan.index') }}" class="btn btn-outline-danger">
                                    <i class="ri-close-line"></i>
                                </a>
                                @endif
                            </div>
                        </form>

                        <!-- Modal Trigger for Single Input -->
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#tambahModal">
                            <i class="ri-add-line me-1"></i> Tambah Data
                        </button>

                        <!-- Modal Trigger for Batch Input -->
                        <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#tambahBatchModal">
                            <i class="ri-file-list-line me-1"></i> Tambah Batch
                        </button>
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
                                        <th>Jenis Pemeriksaan</th>
                                        <th>Data Pemeriksaan</th>
                                        <th>Umur</th>
                                        <th>Jenis Kelamin</th>
                                        <th>Rujukan</th>
                                        <th>Satuan</th>
                                        <th>Metode</th>
                                        <th>Urutan</th>
                                        <th>CH</th>
                                        <th>CL</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($details as $index => $detail)
                                    <tr>
                                        <td>{{ ($details->currentPage() - 1) * $details->perPage() + $index + 1 }}</td>
                                        <td>
                                            <span class="badge bg-primary">
                                                {{ $detail->dataPemeriksaan->jenisPemeriksaan->nama_pemeriksaan ?? '-' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $detail->dataPemeriksaan->data_pemeriksaan ?? '-' }}</span>
                                        </td>


                                        <td>{{ $detail->umur ?? '-' }}</td>
                                        <td>
                                           @if($detail->jenis_kelamin)
                                                @if($detail->jenis_kelamin === 'PRIA')
                                                    <span class="badge bg-primary">PRIA</span>
                                                @elseif($detail->jenis_kelamin === 'WANITA')
                                                    <span class="badge bg-danger">WANITA</span>
                                                @else
                                                    <span class="badge bg-secondary">Semua</span>
                                                @endif
                                            @else
                                                <span class="badge bg-secondary">Semua</span>
                                            @endif

                                        </td>
                                        <td>{{ $detail->rujukan ?? '-' }}</td>
                                        <td>{{ $detail->satuan ?? '-' }}</td>
                                        <td>{{ $detail->metode ?? '-' }}</td>
                                        <td>{{ $detail->urutan ?? '-' }}</td>
                                        <td>{{ $detail->ch ?? '-' }}</td>
                                        <td>{{ $detail->cl ?? '-' }}</td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-warning"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editModal"
                                                data-id="{{ $detail->id_detail_data_pemeriksaan }}"
                                                data-id-data-pemeriksaan="{{ $detail->id_data_pemeriksaan }}"
                                                data-nama-pemeriksaan="{{ $detail->dataPemeriksaan->data_pemeriksaan ?? '' }}"
                                                data-umur="{{ $detail->umur }}"
                                                data-jenis-kelamin="{{ $detail->jenis_kelamin }}"
                                                data-rujukan="{{ $detail->rujukan }}"
                                                data-satuan="{{ $detail->satuan }}"
                                                data-metode="{{ $detail->metode }}"
                                                data-urutan="{{ $detail->urutan }}"
                                                data-ch="{{ $detail->ch }}"
                                                data-cl="{{ $detail->cl }}">
                                                <i class="ri-edit-line"></i> Edit
                                            </button>
                                            <form action="{{ route('detail-data-pemeriksaan.destroy', $detail->id_detail_data_pemeriksaan) }}"
                                                method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
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
                                        <td colspan="13" class="text-center py-4">
                                            <p class="text-muted">Tidak ada detail data pemeriksaan.</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Table ends -->

                    <!-- Pagination -->
                    @if($details->hasPages())
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted">
                            Menampilkan {{ $details->firstItem() }} - {{ $details->lastItem() }} dari {{ $details->total() }} data
                        </div>
                        <div>
                            {{ $details->links() }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Single -->
<div class="modal fade" id="tambahModal" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahModalLabel">Tambah Detail Data Pemeriksaan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('detail-data-pemeriksaan.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="id_data_pemeriksaan" class="form-label">Data Pemeriksaan *</label>
                            <select class="form-select select2-data-pemeriksaan"
                                    id="id_data_pemeriksaan"
                                    name="id_data_pemeriksaan"
                                    required
                                    data-placeholder="Cari data pemeriksaan...">
                                <option value=""></option>
                            </select>
                            <small class="text-muted">Ketik untuk mencari data pemeriksaan</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="umur" class="form-label">Umur</label>
                            <input type="text" class="form-control" id="umur" name="umur"
                                placeholder="Contoh: 0-30 hari, 1-12 bulan, 13-18 tahun">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                            <select class="form-select" id="jenis_kelamin" name="jenis_kelamin">
                                <option value="">Semua</option>
                                <option value="PRIA">PRIA</option>
                                <option value="WANITA">WANITA</option>
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="rujukan" class="form-label">Nilai Rujukan</label>
                            <input type="text" class="form-control" id="rujukan" name="rujukan"
                                placeholder="Contoh: 12.0 - 16.0">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="satuan" class="form-label">Satuan</label>
                            <input type="text" class="form-control" id="satuan" name="satuan"
                                placeholder="Contoh: g/dL">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="metode" class="form-label">Metode</label>
                            <input type="text" class="form-control" id="metode" name="metode"
                                placeholder="Contoh: Cyanmethemoglobin">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="urutan" class="form-label">Urutan</label>
                            <input type="number" class="form-control" id="urutan" name="urutan"
                                placeholder="Contoh: 1">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="ch" class="form-label">CH</label>
                            <input type="text" class="form-control" id="ch" name="ch"
                                placeholder="Contoh: ">
                        </div>
                        <div class="col-md-4 mb-3">
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
                <h5 class="modal-title" id="tambahBatchModalLabel">Tambah Banyak Detail Data Pemeriksaan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('detail-data-pemeriksaan.store-multiple') }}" method="POST" id="batchForm">
                @csrf
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label">Data Pemeriksaan *</label>
                            <select class="form-select select2-data-pemeriksaan-batch"
                                    id="batch_id_data_pemeriksaan"
                                    name="id_data_pemeriksaan"
                                    required
                                    data-placeholder="Cari data pemeriksaan...">
                                <option value=""></option>
                            </select>
                            <small class="text-muted">Pilih data pemeriksaan untuk batch ini</small>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered" id="batchTable">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th width="15%">Umur</th>
                                    <th width="15%">Jenis Kelamin</th>
                                    <th width="20%">Rujukan *</th>
                                    <th width="10%">Satuan</th>
                                    <th width="10%">Metode</th>
                                    <th width="10%">Urutan</th>
                                    <th width="10%">CH</th>
                                    <th width="10%">CL</th>
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
                        Semua data akan menggunakan Data Pemeriksaan yang dipilih di atas.
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
                <h5 class="modal-title" id="editModalLabel">Edit Detail Data Pemeriksaan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="edit_id_data_pemeriksaan" class="form-label">Data Pemeriksaan *</label>
                            <select class="form-select select2-data-pemeriksaan-edit"
                                    id="edit_id_data_pemeriksaan"
                                    name="id_data_pemeriksaan"
                                    required
                                    data-placeholder="Cari data pemeriksaan...">
                                <option value=""></option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_umur" class="form-label">Umur</label>
                            <input type="text" class="form-control" id="edit_umur" name="umur">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_jenis_kelamin" class="form-label">Jenis Kelamin</label>
                            <select class="form-select" id="edit_jenis_kelamin" name="jenis_kelamin">
                                <option value="">Semua</option>
                                <option value="PRIA">PRIA</option>
                                <option value="WANITA">WANITA</option>
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="edit_rujukan" class="form-label">Nilai Rujukan</label>
                            <input type="text" class="form-control" id="edit_rujukan" name="rujukan">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_satuan" class="form-label">Satuan</label>
                            <input type="text" class="form-control" id="edit_satuan" name="satuan">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="edit_metode" class="form-label">Metode</label>
                            <input type="text" class="form-control" id="edit_metode" name="metode">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="edit_urutan" class="form-label">Urutan</label>
                            <input type="number" class="form-control" id="edit_urutan" name="urutan">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="edit_ch" class="form-label">CH</label>
                            <input type="text" class="form-control" id="edit_ch" name="ch">
                        </div>
                        <div class="col-md-4 mb-3">
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

<!-- Load Select2 CSS saja, jQuery sudah ada di template -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container .select2-selection--single {
        height: 38px;
    }
    .select2-container .select2-selection--single .select2-selection__rendered {
        line-height: 36px;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
    }
    .select2-container {
        width: 100% !important;
    }
</style>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialize Select2
        function setupSelect2(selector, modalSelector) {
            if (!$(selector).length) return;

            $(selector).select2({
                placeholder: $(selector).data('placeholder') || 'Cari data pemeriksaan...',
                allowClear: true,
                minimumInputLength: 1,
                width: '100%',
                dropdownParent: $(modalSelector),
                ajax: {
                    url: "{{ route('detail-data-pemeriksaan.get-data-pemeriksaan') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            search: params.term
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: $.map(data, function (item) {
                                return {
                                    id: item.id_data_pemeriksaan,
                                    text: item.nama_pemeriksaan + ' (' + item.jenis_pemeriksaan + ')'
                                };
                            })
                        };
                    },
                    cache: true
                }
            });
        }

        // Initialize Select2 when modals are shown
        $('#tambahModal').on('shown.bs.modal', function () {
            setupSelect2('#id_data_pemeriksaan', '#tambahModal');
        });

        $('#tambahBatchModal').on('shown.bs.modal', function () {
            setupSelect2('#batch_id_data_pemeriksaan', '#tambahBatchModal');
        });

        $('#editModal').on('shown.bs.modal', function () {
            setupSelect2('#edit_id_data_pemeriksaan', '#editModal');
        });

        // Setup for edit modal when data exists
        $('#editModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var idDataPemeriksaan = button.data('id-data-pemeriksaan');
            var namaPemeriksaan = button.data('nama-pemeriksaan');

            var $select = $('#edit_id_data_pemeriksaan');

            if (idDataPemeriksaan && namaPemeriksaan) {
                $select.empty();
                var newOption = new Option(namaPemeriksaan, idDataPemeriksaan, true, true);
                $select.append(newOption);
            }
        });

        // Batch functionality
        let rowCount = 0;
        const batchTableBody = $('#batchTableBody');
        const addRowBtn = $('#addRowBtn');
        const addMultipleBtn = $('#addMultipleBtn');
        const batchForm = $('#batchForm');

        // Function to add a new row
        function addRow() {
            rowCount++;
            const rowIndex = rowCount - 1;

            const row = $(`
                <tr>
                    <td>${rowCount}</td>
                    <td>
                        <input type="text"
                               name="details[${rowIndex}][umur]"
                               class="form-control form-control-sm"
                               placeholder="Contoh: 0-30 hari">
                    </td>
                    <td>
                        <select name="details[${rowIndex}][jenis_kelamin]"
                                class="form-select form-control-sm">
                            <option value="">Semua</option>
                            <option value="PRIA">PRIA</option>
                            <option value="WANITA">WANITA</option>
                        </select>
                    </td>
                    <td>
                        <input type="text"
                               name="details[${rowIndex}][rujukan]"
                               class="form-control form-control-sm"
                               placeholder="Nilai rujukan"
                               required>
                    </td>
                    <td>
                        <input type="text"
                               name="details[${rowIndex}][satuan]"
                               class="form-control form-control-sm"
                               placeholder="Satuan">
                    </td>
                    <td>
                        <input type="text"
                               name="details[${rowIndex}][metode]"
                               class="form-control form-control-sm"
                               placeholder="Metode">
                    </td>
                    <td>
                        <input type="number"
                               name="details[${rowIndex}][urutan]"
                               class="form-control form-control-sm"
                               placeholder="Urutan">
                    </td>
                    <td>
                        <input type="text"
                               name="details[${rowIndex}][ch]"
                               class="form-control form-control-sm"
                               placeholder="CH">
                    </td>
                    <td>
                        <input type="text"
                               name="details[${rowIndex}][cl]"
                               class="form-control form-control-sm"
                               placeholder="CL">
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-outline-danger remove-row" ${rowCount === 1 ? 'disabled' : ''}>
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </td>
                </tr>
            `);

            // Add hidden input for id_data_pemeriksaan
            const selectedId = $('#batch_id_data_pemeriksaan').val();
            row.append(`<input type="hidden" name="details[${rowIndex}][id_data_pemeriksaan]" value="${selectedId || ''}">`);

            batchTableBody.append(row);

            // Add event listener for remove button
            row.find('.remove-row').click(function() {
                if (rowCount > 1) {
                    row.remove();
                    rowCount--;
                    updateRowNumbers();
                }
            });
        }

        // Function to update row numbers
        function updateRowNumbers() {
            batchTableBody.find('tr').each(function(index) {
                $(this).find('td:first-child').text(index + 1);

                // Update hidden input names
                const hiddenInput = $(this).find('input[type="hidden"]');
                if (hiddenInput.length) {
                    hiddenInput.attr('name', `details[${index}][id_data_pemeriksaan]`);
                }

                // Update other input names
                $(this).find('input, select').each(function() {
                    const currentName = $(this).attr('name');
                    if (currentName && !currentName.includes('id_data_pemeriksaan')) {
                        const fieldName = currentName.match(/\[([^\]]+)\]$/)[1];
                        $(this).attr('name', `details[${index}][${fieldName}]`);
                    }
                });
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
        addRowBtn.click(function() {
            addRow();
        });

        if (addMultipleBtn.length) {
            addMultipleBtn.click(function() {
                addMultipleRows(5);
            });
        }

        // Update hidden inputs when data pemeriksaan changes
        $('#batch_id_data_pemeriksaan').change(function() {
            const selectedId = $(this).val();
            batchTableBody.find('input[type="hidden"]').val(selectedId);
        });

        // Form validation before submit
        if (batchForm.length) {
            batchForm.submit(function(e) {
                const jenisSelect = $('#batch_id_data_pemeriksaan');
                const rujukanInputs = batchTableBody.find('input[name*="rujukan"]');

                // Check if data pemeriksaan is selected
                if (!jenisSelect.val()) {
                    e.preventDefault();
                    alert('Pilih data pemeriksaan terlebih dahulu!');
                    return;
                }

                // Check if at least one row has rujukan filled
                let hasData = false;
                rujukanInputs.each(function() {
                    if ($(this).val().trim()) {
                        hasData = true;
                        return false; // break the loop
                    }
                });

                if (!hasData) {
                    e.preventDefault();
                    alert('Isi minimal satu data dengan nilai rujukan!');
                    return;
                }
            });
        }

        // Reset batch form when modal closes
        $('#tambahBatchModal').on('hidden.bs.modal', function() {
            batchTableBody.empty();
            rowCount = 0;
            addRow();
        });

        // Edit modal functionality
        $('#editModal').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const id = button.data('id');
            const umur = button.data('umur');
            const jenisKelamin = button.data('jenis-kelamin');
            const rujukan = button.data('rujukan');
            const satuan = button.data('satuan');
            const metode = button.data('metode');
            const urutan = button.data('urutan');
            const ch = button.data('ch');
            const cl = button.data('cl');

            // Set form values
            $('#edit_umur').val(umur || '');
            $('#edit_jenis_kelamin').val(jenisKelamin || '');
            $('#edit_rujukan').val(rujukan || '');
            $('#edit_satuan').val(satuan || '');
            $('#edit_metode').val(metode || '');
            $('#edit_urutan').val(urutan || '');
            $('#edit_ch').val(ch || '');
            $('#edit_cl').val(cl || '');

            // Set form action
            const form = $(this).find('#editForm');
            form.attr('action', "{{ route('detail-data-pemeriksaan.update', '') }}/" + id);
        });
    });
</script>
@endsection

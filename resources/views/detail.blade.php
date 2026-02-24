@extends('layouts.app')

@section('content')
<div
    id="rujukan-batch-data"
    data-items='@json($rujukanBatchPayload)'>
</div>
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
            Hasil Pengujian
        </li>
    </ol>
    <!-- Breadcrumb ends -->
</div>
<!-- App Hero header ends -->

<!-- App body starts -->
<div class="app-body">
    @if($pasien)
        <!-- Content starts -->
        <div class="row gx-3">
            <div class="col-sm-12">
                <div class="card">
                <div class="card-header">
                        <h5 class="card-title">Hasil Pengujian Lab - {{ $pasien->nama_pasien ?? '-' }}</h5>
                    </div>
                    <div class="card-body">
                        <!-- Data pasien display starts -->
                        <div class="row gx-3">
                            <div class="col-xxl-3 col-lg-4 col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted small mb-1">No. Registrasi Lab / RM Pasien</label>
                                    <div class="d-flex align-items-center">
                                        <i class="ri-file-list-3-line me-2 text-primary"></i>
                                        <h6 class="m-0 text-dark">{{ $pasien->nomor_registrasi ?? '-' }} / {{ $pasien->rm_pasien ?? '-' }}</h6>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xxl-3 col-lg-4 col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">No. Registrasi Lab</label>
                                    <div class="input-group">
                                        <input
                                            type="text"
                                            class="form-control realtime-field"
                                            data-field="nomor_registrasi"
                                            value="{{ $pasien->nomor_registrasi }}"
                                            placeholder="Nomor registrasi pasien">
                                        <span class="input-group-text save-status" style="display: none; min-width: 40px;"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xxl-3 col-lg-4 col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Nama Pasien</label>
                                    <div class="input-group">
                                        <input
                                            type="text"
                                            class="form-control realtime-field"
                                            data-field="nama_pasien"
                                            value="{{ $pasien->nama_pasien }}"
                                            placeholder="Nama lengkap pasien">
                                        <span class="input-group-text save-status" style="display: none; min-width: 40px;"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- <div class="col-xxl-3 col-lg-4 col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Jenis Kelamin</label>
                                    <div class="input-group">
                                        <select class="form-select realtime-select" data-field="jenis_kelamin">
                                            <option value="">Pilih</option>
                                            <option value="L" {{ $pasien->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                            <option value="P" {{ $pasien->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
                                        </select>
                                        <span class="input-group-text save-status" style="display: none; min-width: 40px;"></span>
                                    </div>
                                </div>
                            </div> -->

                            <div class="col-xxl-3 col-lg-4 col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Alamat</label>
                                    <div class="input-group">
                                        <input
                                            type="text"
                                            class="form-control realtime-field"
                                            data-field="alamat"
                                            value="{{ $pasien->alamat }}"
                                            placeholder="Alamat lengkap pasien">
                                        <span class="input-group-text save-status" style="display: none; min-width: 40px;"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- TANGGAL LAHIR - DENGAN DATEPICKER -->
                            <!-- TANGGAL LAHIR - INPUT TYPE DATE NATIVE -->
                            <div class="col-xxl-3 col-lg-4 col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Tanggal Lahir</label>
                                    <div class="input-group">
                                        <input
                                            type="date"
                                            class="form-control realtime-date"
                                            data-field="tgl_lahir"
                                            value="{{ $pasien->tgl_lahir ? \Carbon\Carbon::parse($pasien->tgl_lahir)->format('Y-m-d') : '' }}"
                                            max="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                            min="1900-01-01">
                                        <span class="input-group-text save-status" style="display: none; min-width: 40px;"></span>
                                        <span class="input-group-text">

                                        </span>
                                    </div>
                                    <small class="text-muted">Klik untuk memilih tanggal dari kalender</small>
                                </div>
                            </div>
                            <div class="col-xxl-3 col-lg-4 col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Umur</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="display_umur"
                                        value="{{ $pasien->umur }}"
                                        readonly
                                        style="background-color: #f8f9fa;">

                                </div>
                            </div>

                            <!-- Pengirim -->
                            <div class="col-xxl-3 col-lg-4 col-sm-6">
                                <div class="mb-2 mb-sm-3">
                                    <label class="form-label">Pengirim</label>
                                    <div class="input-group dropdown"> <!-- Tambahkan class dropdown di sini -->
                                        <input
                                            type="text"
                                            class="form-control form-control-sm form-control-md realtime-dokter dropdown-toggle"
                                            data-field="pengirim"
                                            id="pengirimInput"
                                            value="{{ $pasien->pengirim }}"
                                            placeholder="Cari atau ketik nama dokter..."
                                            autocomplete="off"
                                            data-bs-toggle="dropdown"
                                            aria-expanded="false"
                                            aria-haspopup="true">
                                        <span class="input-group-text save-status" style="display: none; min-width: 40px;"></span>
                                        <button class="btn btn-outline-secondary btn-sm btn-md" type="button" id="addDokterBtn" title="Tambah dokter baru">
                                            <i class="ri-user-add-line"></i>
                                        </button>

                                        <!-- Dropdown Bootstrap -->
                                        <div id="dokterDropdown" class="dropdown-menu w-100" aria-labelledby="pengirimInput"
                                            style="max-height: 300px; overflow-y: auto; position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(0px, 40px);">
                                            <div class="dropdown-item text-muted py-2">
                                                <div class="d-flex align-items-center">
                                                    <i class="ri-search-line me-2"></i>
                                                    <span>Ketik minimal 2 karakter untuk mencari dokter</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <small class="text-muted">
                                        <span id="dokterInfo" style="display: none;">
                                            <i class="ri-check-line text-success me-1"></i>
                                            <span id="selectedDokterName"></span>
                                            <span id="dokterDetail" class="text-muted ms-1"></span>
                                        </span>
                                    </small>
                                </div>
                            </div>

                            <!-- Ganti bagian Penjamin yang ada -->
                            <div class="col-xxl-3 col-lg-4 col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted small mb-1">Penjamin</label>
                                    <div class="d-flex align-items-center">
                                        <i class="ri-shield-check-line me-2 text-primary"></i>
                                        <div class="flex-grow-1 position-relative">
                                            <!-- Input untuk search penjamin -->
                                            <input
                                                type="text"
                                                class="form-control realtime-penjamin dropdown-toggle"
                                                data-field="nota"
                                                id="penjaminInput"
                                                value="{{ $pasien->penjamin->nama_penjamin ?? ($pasien->nota ?? '') }}"
                                                placeholder="Ketik nama penjamin..."
                                                autocomplete="off"
                                                data-bs-toggle="dropdown"
                                                aria-expanded="false"
                                                aria-haspopup="true"
                                                style="font-size: 1rem; font-weight: 500; min-height: 1.8rem;">
                                            <span class="input-group-text save-status" style="display: none; min-width: 40px;"></span>

                                            <!-- Hidden input untuk ID penjamin -->
                                            <input
                                                type="hidden"
                                                id="id_penjamin"
                                                name="id_penjamin"
                                                value="{{ $pasien->id_penjamin ?? '' }}">

                                            <!-- Dropdown untuk hasil pencarian -->
                                            <div id="penjaminDropdown" class="dropdown-menu w-100" aria-labelledby="penjaminInput"
                                                style="max-height: 300px; overflow-y: auto; position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(0px, 40px);">
                                                <div class="dropdown-item text-muted py-2">
                                                    <div class="d-flex align-items-center">
                                                        <i class="ri-search-line me-2"></i>
                                                        <span>Ketik minimal 2 karakter untuk mencari penjamin</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <small class="ri-check-line text-success me-1" style="display: none;"></small>
                                    <div id="penjaminInfo" style="display: none;">
                                        <small>
                                            <i class="ri-check-line me-1"></i>
                                            <span id="selectedPenjaminName"></span>
                                            <span id="penjaminDetail" class="text-muted ms-1"></span>
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <!-- Ganti bagian Asal Kunjungan yang ada -->
                            <div class="col-xxl-3 col-lg-4 col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Asal Kunjungan</label>
                                    <div class="input-group">
                                        <!-- Input untuk search ruangan -->
                                        <input
                                            type="text"
                                            class="form-control realtime-ruangan dropdown-toggle"
                                            data-field="ket_klinik"
                                            id="ruanganInput"
                                            value="{{ $pasien->ruangan->nama_ruangan ?? ($pasien->ket_klinik ?? '') }}"
                                            placeholder="Ketik nama ruangan..."
                                            autocomplete="off"
                                            data-bs-toggle="dropdown"
                                            aria-expanded="false"
                                            aria-haspopup="true">
                                        <span class="input-group-text save-status" style="display: none; min-width: 40px; align-self: flex-start;"></span>

                                        <!-- Hidden input untuk ID ruangan -->
                                        <input
                                            type="hidden"
                                            id="id_ruangan"
                                            name="id_ruangan"
                                            value="{{ $pasien->id_ruangan ?? '' }}">

                                        <!-- Dropdown untuk hasil pencarian -->
                                        <div id="ruanganDropdown" class="dropdown-menu w-100" aria-labelledby="ruanganInput"
                                            style="max-height: 300px; overflow-y: auto; position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate(0px, 40px);">
                                            <div class="dropdown-item text-muted py-2">
                                                <div class="d-flex align-items-center">
                                                    <i class="ri-search-line me-2"></i>
                                                    <span>Ketik minimal 2 karakter untuk mencari ruangan</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="ruanganInfo" style="display: none;">
                                        <small>
                                            <i class="ri-check-line me-1"></i>
                                            <span id="selectedRuanganName"></span>
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xxl-3 col-lg-4 col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Waktu Periksa</label>
                                    <div class="input-group">
                                        <input
                                            type="datetime-local"
                                            class="form-control realtime-datetime"
                                            data-field="created_at"
                                            value="{{ $pasien->created_at ? \Carbon\Carbon::parse($pasien->created_at)->format('Y-m-d\TH:i') : '' }}">
                                        <span class="input-group-text save-status" style="display: none; min-width: 40px;"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xxl-3 col-lg-4 col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Waktu Validasi</label>
                                    <div class="input-group">
                                        <input
                                            type="datetime-local"
                                            class="form-control realtime-datetime"
                                            data-field="waktu_validasi"
                                            value="{{ $pasien->waktu_validasi ? \Carbon\Carbon::parse($pasien->waktu_validasi)->format('Y-m-d\TH:i') : '' }}">
                                        <span class="input-group-text save-status" style="display: none; min-width: 40px;"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- waktu_ttd -->
                            @if(Auth::check() && Auth::user()->name === 'karu')
                            <div class="col-xxl-3 col-lg-4 col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Waktu Tanda Tangan</label>
                                    <div class="input-group">
                                        <input
                                            type="datetime-local"
                                            class="form-control realtime-datetime"
                                            data-field="waktu_ttd"
                                            value="{{ $pasien->waktu_ttd ? \Carbon\Carbon::parse($pasien->waktu_ttd)->format('Y-m-d\TH:i') : '' }}">
                                        <span class="input-group-text save-status" style="display: none; min-width: 40px;"></span>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Data pasien display ends -->
                        </div>
                    </div>
                </div>
                <div class="card mb-3">
                    <!-- Info Uji Pemeriksaan / Info Yang Diuji -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Info Pengujian Lab</h5>
                        </div>
                        <div class="card-body">
                            @if(isset($uji_pemeriksaan) && $uji_pemeriksaan->count() > 0)
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($uji_pemeriksaan as $uji)
                                    <div class="border rounded p-2 bg-light uji-item"
                                        id="uji-{{ $uji->id_uji_pemeriksaan }}"
                                        data-kode="{{ $uji->kode_pemeriksaan }}">

                                        <div class="d-flex align-items-center">
                                            <span class="badge bg-primary me-2">
                                                {{ $uji->kategori ?? 'N/A' }}
                                            </span>

                                            <span class="me-2 flex-grow-1">
                                                {{ $uji->nama_pemeriksaan ?? '-' }}
                                                <small class="text-muted">({{ $uji->kode_pemeriksaan }})</small>
                                            </span>

                                            <!-- Tombol Generate Hasil Lain (FULLINT) -->
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-primary fullint-generate-btn"
                                                    data-kode="{{ $uji->kode_pemeriksaan }}"
                                                    data-nama="{{ $uji->nama_pemeriksaan }}"
                                                    title="Generate Hasil Lain">
                                                <i class="ri-play-list-add-line me-1"></i>Generate
                                            </button>

                                            <!-- Tombol Hapus Uji (tidak disentuh fullint) -->
                                            <button type="button"
                                                    class="btn btn-sm btn-danger btn-hapus-uji ms-2"
                                                    data-id="{{ $uji->id_uji_pemeriksaan }}"
                                                    title="Hapus">
                                                ✕
                                            </button>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>

                                <div class="mt-3 text-muted small">
                                    <i class="ri-information-line me-1"></i>
                                    Total: <span id="total-uji">{{ $uji_pemeriksaan->count() }}</span> uji pemeriksaan
                                </div>
                            @else
                                <div class="alert alert-info mb-0">
                                    <i class="ri-information-line me-2"></i>
                                    Tidak ada data uji pemeriksaan
                                </div>
                            @endif
                        </div>
                    </div>
                    <!-- END Info Uji Pemeriksaan -->
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-0">Hasil Pengujian Laboratorium</h5>
                            <p class="card-subtitle text-muted mb-0">No. Lab: {{ $pasien->nomor_registrasi }} | Nama: {{ $pasien->nama_pasien }}</p>
                        </div>
                        <div>
                            <span id="saveStatus" class="badge bg-secondary">
                                <i class="ri-check-line me-1"></i>Tersimpan
                            </span>
                        </div>
                    </div>

                    <form id="hasilLabForm" method="POST" action="{{ route('hasil-lab.update', $pasien->no_lab) }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="_token" id="csrf_token" value="{{ csrf_token() }}">

                        <div class="card-body">
                            <!-- Alert info -->
                            <div class="alert alert-info mb-3">
                                <i class="ri-information-line me-2"></i>
                                <strong>Petunjuk:</strong>
                                <ul class="mb-0 mt-1">
                                    <li>Isi langsung di tabel seperti Excel</li>
                                    <li>Data otomatis tersimpan saat berpindah sel</li>
                                    <li><strong>Keterangan (H/L/-) otomatis dihitung berdasarkan hasil dan rujukan</strong></li>
                                </ul>
                            </div>

                            <!-- HEMATOLOGY SECTION -->
                            @if($hematology && count($hematology) > 0)
                                <div class="mb-4">
                                    <div class="row">
                                        <!-- TABEL HEMATOLOGY -->
                                        <div class="col-lg-9 col-md-12">
                                            <h6 class="mb-3 border-bottom pb-2">
                                                <button type="button" id="tambahRowHematologyBtn" class="btn btn-sm btn-outline-primary">
                                                    <i class="ri-add-line me-1"></i>Tambah Row
                                                </button>
                                                <i class="ri-test-tube-line me-2"></i>HEMATOLOGY
                                                <span class="badge bg-info ms-2">Kondisi: {{ $pasien->jenis_kelamin }} | {{ $data['umur_format'] }}</span>
                                            </h6>
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-sm" id="hematologyTable">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th width="25%" class="bg-light">Jenis Pengujian</th>
                                                            <th width="10%">Hasil Pengujian</th>
                                                            <th width="10%" class="bg-light">Satuan</th>
                                                            <th width="15%" class="bg-light">Rujukan</th>
                                                            <th width="10%" class="bg-light">CH</th>
                                                            <th width="10%" class="bg-light">CL</th>
                                                            <th width="20%">Keterangan</th>
                                                            <th width="5%">Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($hematology as $index => $item)
                                                            @php
                                                                // Gunakan nama pemeriksaan dari data_pemeriksaan
                                                                $jenis = $item->dataPemeriksaan->data_pemeriksaan ?? 'Unknown';

                                                                // Gunakan data yang sudah dihitung di controller
                                                                if (isset($item->rujukan_by_kondisi)) {
                                                                    $rujukanData = $item->rujukan_by_kondisi;
                                                                    $isRujukanFromDetail = $rujukanData['is_from_detail'] ?? false;
                                                                    $detailCondition = $rujukanData['detail_condition'] ?? null;

                                                                    $rujukan_value = $rujukanData['rujukan'] ?? '-';
                                                                    $ch_value = $rujukanData['ch'] ?? '-';
                                                                    $cl_value = $rujukanData['cl'] ?? '-';
                                                                    $satuan_value = $rujukanData['satuan'] ?? '-';

                                                                    // Gunakan keterangan yang sudah dihitung
                                                                    $keterangan = $item->calculated_keterangan ?? $item->keterangan ?? '-';
                                                                } else {
                                                                    $keterangan = '-';
                                                                    $rujukan_value = '-';
                                                                    $ch_value = '-';
                                                                    $cl_value = '-';
                                                                    $satuan_value = $item->dataPemeriksaan->satuan ?? '-';
                                                                    $isRujukanFromDetail = false;
                                                                }

                                                                // Tentukan warna untuk keterangan
                                                                if ($keterangan === 'CH' || $keterangan === 'H') {
                                                                    $bgColor = 'bg-danger bg-opacity-10';
                                                                    $textColor = 'text-danger';
                                                                    $textDisplay = $keterangan === 'CH' ? 'CH' : 'H';
                                                                } elseif ($keterangan === 'CL' || $keterangan === 'L') {
                                                                    $bgColor = 'bg-primary bg-opacity-10';
                                                                    $textColor = 'text-primary';
                                                                    $textDisplay = $keterangan === 'CL' ? 'CL' : 'L';
                                                                } elseif ($keterangan === '-' || $keterangan === '') {
                                                                    $bgColor = 'bg-success bg-opacity-10';
                                                                    $textColor = 'text-success';
                                                                    $textDisplay = '-';
                                                                } else {
                                                                    $bgColor = 'bg-light';
                                                                    $textColor = 'text-muted';
                                                                    $textDisplay = '-';
                                                                }
                                                            @endphp

                                                            <tr data-index="{{ $index }}" data-id="{{ $item->id_pemeriksaan_hematology }}" class="@if($isRujukanFromDetail) table-info @endif">
                                                                <td class="bg-light">
                                                                    <strong>{{ $jenis }}</strong>
                                                                    @if($item->analysis && $item->analysis !== $jenis)
                                                                        <br><small class="text-muted">({{ $item->analysis }})</small>
                                                                    @endif
                                                                    <input type="hidden"
                                                                        name="hematology[{{ $index }}][id]"
                                                                        value="{{ $item->id_pemeriksaan_hematology ?? '' }}">
                                                                    <input type="hidden"
                                                                        name="hematology[{{ $index }}][jenis_pengujian]"
                                                                        value="{{ $jenis }}">
                                                                    <input type="hidden"
                                                                        name="hematology[{{ $index }}][id_data_pemeriksaan]"
                                                                        value="{{ $item->dataPemeriksaan->id_data_pemeriksaan ?? '' }}">
                                                                </td>
                                                                <td class="hasil-cell">
                                                                    @if($item && $item->id_pemeriksaan_hematology)
                                                                        <input type="text"
                                                                            name="hematology[{{ $index }}][hasil_pengujian]"
                                                                            class="form-control form-control-sm excel-input hasil-input"
                                                                            value="{{ $item->hasil_pengujian ?? '' }}"
                                                                            placeholder="Hasil"
                                                                            data-original="{{ $item->hasil_pengujian ?? '' }}"
                                                                            data-id="{{ $item->id_pemeriksaan_hematology }}"
                                                                            data-type="hematology"
                                                                            data-rujukan="{{ $rujukan_value }}"
                                                                            data-ch="{{ $ch_value }}"
                                                                            data-cl="{{ $cl_value }}"
                                                                            data-id-data-pemeriksaan="{{ $item->dataPemeriksaan->id_data_pemeriksaan ?? '' }}"
                                                                            data-jenis="{{ $jenis }}"
                                                                            data-jenis-full="{{ $item->dataPemeriksaan->data_pemeriksaan ?? '' }}"
                                                                            data-rm="{{ $pasien->rm_pasien }}"
                                                                            data-umur="{{ $data['umur_format'] }}"
                                                                            data-jenis-kelamin="{{ $pasien->jenis_kelamin }}"
                                                                            autocomplete="off">
                                                                    @else
                                                                        <input type="text"
                                                                            name="hematology[{{ $index }}][hasil_pengujian]"
                                                                            class="form-control form-control-sm excel-input hasil-input"
                                                                            value=""
                                                                            placeholder="Hasil"
                                                                            data-original=""
                                                                            data-id=""
                                                                            data-type="hematology"
                                                                            data-rujukan=""
                                                                            data-ch=""
                                                                            data-cl=""
                                                                            data-id-data-pemeriksaan=""
                                                                            data-jenis="{{ $jenis }}"
                                                                            data-rm="{{ $pasien->rm_pasien }}"
                                                                            autocomplete="off">
                                                                    @endif
                                                                </td>
                                                                <td class="bg-light">
                                                                    {{ $satuan_value }}
                                                                </td>
                                                                <td class="bg-light rujukan-cell">
                                                                    {{ $rujukan_value }}
                                                                    @if($isRujukanFromDetail)
                                                                        <span class="badge bg-info ms-1" title="CH/CL khusus kondisi">K</span>
                                                                    @endif
                                                                </td>
                                                                <td class="bg-light ch-cell" style="text-align:center;">
                                                                    {{ $ch_value }}
                                                                    @if($isRujukanFromDetail && $ch_value !== '-' && $ch_value !== '')
                                                                        <br><small class="text-info">detail</small>
                                                                    @endif
                                                                </td>
                                                                <td class="bg-light cl-cell" style="text-align:center;">
                                                                    {{ $cl_value }}
                                                                    @if($isRujukanFromDetail && $cl_value !== '-' && $cl_value !== '')
                                                                        <br><small class="text-info">detail</small>
                                                                    @endif
                                                                </td>
                                                                <td class="keterangan-cell">
                                                                    <div class="keterangan-display {{ $bgColor }} {{ $textColor }} rounded py-1 px-2 text-center"
                                                                        data-keterangan="{{ $keterangan }}">
                                                                        <strong>{{ $textDisplay }}</strong>
                                                                    </div>
                                                                    <input type="hidden"
                                                                        name="hematology[{{ $index }}][keterangan]"
                                                                        value="{{ $keterangan }}">
                                                                </td>
                                                                <td>
                                                                   <button type="button" class="btn btn-sm btn-outline-danger hapus-row-hematologi-btn mt-1">
                                                                        <i class="ri-delete-bin-line"></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div> <!-- END col-lg-9 -->

                                        <!-- HISTORY PANEL HEMATOLOGY -->
                                        <div class="col-lg-3 col-md-12">
                                            <div class="card h-100 border-start border-primary">
                                                <div class="card-header bg-light py-2">
                                                    <h6 class="card-title mb-0 small">
                                                        <i class="ri-history-line me-2 text-primary"></i>History Hematology
                                                    </h6>
                                                </div>
                                                <div class="card-body p-0">
                                                    <div class="p-2 border-bottom bg-primary bg-opacity-5" id="currentHoverInfo_hematology">
                                                        <div class="text-center">
                                                            <div class="text-primary mb-1 small" id="hoverJenisPemeriksaan_hematology">
                                                                <i class="ri-cursor-line me-1"></i>
                                                                <span>Pilih hasil</span>
                                                            </div>
                                                            <div class="small text-muted" id="hoverTypeInfo_hematology">
                                                                Klik pada kolom "Hasil"
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="p-2" id="historyPanelContent_hematology" style="height: 300px; overflow-y: auto; font-size: 0.85rem;">
                                                        <div class="text-center text-muted py-4">
                                                            <i class="ri-file-list-3-line display-6 mb-3 opacity-50"></i>
                                                            <p class="mb-1 small">History akan muncul di sini</p>
                                                            <small class="text-muted">Klik pada hasil</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> <!-- END col-lg-3 -->
                                    </div> <!-- END row -->
                                </div> <!-- END mb-4 -->
                            @else
                                <div class="alert alert-warning mb-3">
                                    <i class="ri-test-tube-line me-2"></i>Data hasil pemeriksaan untuk Hematology Belum/Tidak Dilakukan
                                </div>
                            @endif

                            @if($kimia->count() > 0)
                                <!-- KIMIA SECTION -->
                                <div class="d-flex justify-content-between align-items-center mt-3 mb-2">
                                    <h6 class="mb-3 border-bottom pb-2">
                                        <button type="button" id="tambahRowKimiaBtn" class="btn btn-sm btn-outline-primary">
                                            <i class="ri-add-line me-1"></i>Tambah Row Kimia
                                        </button>
                                        <i class="ri-flask-line me-2"></i>KIMIA

                                        <span class="badge bg-info ms-2">Kondisi: {{ $pasien->jenis_kelamin }} | {{ $data['umur_format'] }}</span>
                                    </h6>


                                </div>
                                <div class="mt-4">
                                    <div class="row">
                                        <!-- TABEL KIMIA -->
                                        <div class="col-lg-9 col-md-12">
                                            <div class="table-responsive">
                                                <table class="table table-bordered align-middle text-nowrap table-row-skip" id="kimiaTable">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th width="15%" class="bg-light">Dari Alat</th>
                                                            <th width="15%">Nama Standar dari RS</th>
                                                            <th width="10%" class="bg-light">Hasil</th>
                                                            <th width="10%" class="bg-light">Rujukan</th>
                                                            <th width="5%" class="bg-light">CH</th>
                                                            <th width="5%" class="bg-light">CL</th>
                                                            <th width="20%">Keterangan</th>
                                                            <th width="5%" class="bg-light">Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($kimia as $index => $item)
                                                        @php
                                                        // Gunakan data yang sudah dihitung di controller
                                                        if ($item && isset($item->rujukan_by_kondisi)) {
                                                            $rujukanData = $item->rujukan_by_kondisi;
                                                            $isRujukanFromDetail = $rujukanData['is_from_detail'] ?? false;
                                                            $detailCondition = $rujukanData['detail_condition'] ?? null;

                                                            $rujukan_value = $rujukanData['rujukan'] ?? '-';
                                                            $ch_value = $rujukanData['ch'] ?? '-';
                                                            $cl_value = $rujukanData['cl'] ?? '-';
                                                            $satuan_value = $rujukanData['satuan'] ?? '-';

                                                            // Gunakan keterangan yang sudah dihitung
                                                            $keterangan = $item->calculated_keterangan ?? $item->keterangan ?? '-';
                                                        } else {
                                                            $keterangan = $item->keterangan ?? '-';
                                                            $rujukan_value = $item->dataPemeriksaan->rujukan ?? '-';
                                                            $ch_value = $item->dataPemeriksaan->ch ?? '-';
                                                            $cl_value = $item->dataPemeriksaan->cl ?? '-';
                                                            $satuan_value = $item->dataPemeriksaan->satuan ?? '-';
                                                            $isRujukanFromDetail = false;
                                                        }

                                                        $id_data_pemeriksaan = $item->id_data_pemeriksaan ?? null;
                                                        $analysis = $item->analysis ?? '';

                                                        // Warna untuk keterangan
                                                        if ($keterangan === 'CH' || $keterangan === 'H') {
                                                            $bgColor = 'bg-danger bg-opacity-10';
                                                            $textColor = 'text-danger';
                                                            $textDisplay = $keterangan === 'CH' ? 'CH' : 'H';
                                                        } elseif ($keterangan === 'CL' || $keterangan === 'L') {
                                                            $bgColor = 'bg-primary bg-opacity-10';
                                                            $textColor = 'text-primary';
                                                            $textDisplay = $keterangan === 'CL' ? 'CL' : 'L';
                                                        } elseif ($keterangan === '-') {
                                                            $bgColor = 'bg-success bg-opacity-10';
                                                            $textColor = 'text-success';
                                                            $textDisplay = '';
                                                        } else {
                                                            $bgColor = 'bg-light';
                                                            $textColor = 'text-muted';
                                                            $textDisplay = '-';
                                                        }
                                                        @endphp
                                                        <tr data-index="{{ $index }}" data-kimia-id="{{ $item->id_pemeriksaan_kimia }}"
                                                            class="@if($isRujukanFromDetail) table-info @endif">
                                                            <td class="bg-light">
                                                                <strong>{{ $analysis }}</strong>
                                                                <input type="hidden"
                                                                    name="kimia[{{ $index }}][id]"
                                                                    value="{{ $item->id_pemeriksaan_kimia }}">
                                                                <input type="hidden"
                                                                    name="kimia[{{ $index }}][analysis]"
                                                                    value="{{ $analysis }}">
                                                            </td>

                                                            <!-- Kolom Search Kode Pemeriksaan -->
                                                            <td class="search-cell">
                                                                @if(!$id_data_pemeriksaan)
                                                                <div class="position-relative">
                                                                    <input type="text"
                                                                        class="form-control form-control-sm kode-search-input"
                                                                        placeholder="Cari data pemeriksaan..."
                                                                        data-kimia-id="{{ $item->id_pemeriksaan_kimia }}"
                                                                        data-analysis="{{ $analysis }}"
                                                                        autocomplete="off">
                                                                    <div class="kode-search-results dropdown-menu w-100"
                                                                        style="display: none; max-height: 200px; overflow-y: auto;">
                                                                    </div>
                                                                    <input type="hidden"
                                                                        name="kimia[{{ $index }}][id_data_pemeriksaan]"
                                                                        class="kode-pemeriksaan-input"
                                                                        value="">
                                                                </div>
                                                                <div class="mt-1">
                                                                    <small class="text-warning">
                                                                        <i class="ri-alert-line me-1"></i>Belum dipetakan
                                                                    </small>
                                                                </div>
                                                                @else
                                                                <div class="position-relative">
                                                                    <input type="text"
                                                                        class="form-control form-control-sm kode-edit-input"
                                                                        placeholder="Cari data pemeriksaan..."
                                                                        value="{{ $item->dataPemeriksaan->data_pemeriksaan ?? '' }}"
                                                                        data-kimia-id="{{ $item->id_pemeriksaan_kimia }}"
                                                                        data-analysis="{{ $analysis }}"
                                                                        data-current-id="{{ $id_data_pemeriksaan }}"
                                                                        autocomplete="off">
                                                                    <div class="kode-search-results dropdown-menu w-100"
                                                                        style="display: none; max-height: 200px; overflow-y: auto;">
                                                                    </div>
                                                                    <input type="hidden"
                                                                        name="kimia[{{ $index }}][id_data_pemeriksaan]"
                                                                        class="kode-pemeriksaan-input"
                                                                        value="{{ $id_data_pemeriksaan }}">

                                                                    <div class="mt-1 d-flex justify-content-between align-items-center">
                                                                        <small class="text-success">
                                                                            <i class="ri-links-line me-1"></i>
                                                                            <span class="kode-display">
                                                                                {{ $item->dataPemeriksaan->data_pemeriksaan ?? '' }}
                                                                            </span>
                                                                        </small>
                                                                    </div>
                                                                </div>
                                                                @endif
                                                            </td>

                                                            <td class="hasil-cell">
                                                                <input type="text"
                                                                    name="kimia[{{ $index }}][hasil_pengujian]"
                                                                    class="form-control form-control-sm excel-input hasil-input"
                                                                    value="{{ $item->hasil_pengujian ?? '' }}"
                                                                    placeholder="Hasil"
                                                                    data-original="{{ $item->hasil_pengujian ?? '' }}"
                                                                    data-id="{{ $item->id_pemeriksaan_kimia }}"
                                                                    data-type="kimia"
                                                                    data-id-data-pemeriksaan="{{ $id_data_pemeriksaan }}"
                                                                    data-jenis="{{ $item->dataPemeriksaan->data_pemeriksaan ?? '' }}"
                                                                    data-rujukan="{{ $rujukan_value }}"
                                                                    data-ch="{{ $ch_value }}"
                                                                    data-cl="{{ $cl_value }}"
                                                                    data-analysis="{{ $analysis }}"
                                                                    data-rm="{{ $pasien->rm_pasien }}"
                                                                    data-umur="{{ $data['umur_format'] }}"
                                                                    data-jenis-kelamin="{{ $pasien->jenis_kelamin }}"
                                                                    autocomplete="off">
                                                            </td>
                                                            <td class="bg-light rujukan-cell" style="text-align:center;">
                                                                <span class="rujukan-display">
                                                                    {{ $rujukan_value }}
                                                                    @if($isRujukanFromDetail)
                                                                    <span class="badge bg-info ms-1" title="CH/CL khusus kondisi">K</span>
                                                                    @endif
                                                                </span>
                                                            </td>
                                                            <td class="bg-light ch-cell" style="text-align:center;">
                                                                {{ $ch_value }}
                                                            </td>
                                                            <td class="bg-light cl-cell" style="text-align:center;">
                                                                {{ $cl_value }}
                                                            </td>
                                                            <td class="keterangan-cell">
                                                                <div class="keterangan-display {{ $bgColor }} {{ $textColor }} rounded py-1 px-2 text-center"
                                                                    data-keterangan="{{ $keterangan }}">
                                                                    <strong>{{ $textDisplay }}</strong>
                                                                </div>
                                                                <input type="hidden"
                                                                    name="kimia[{{ $index }}][keterangan]"
                                                                    value="{{ $keterangan }}">
                                                            </td>
                                                        <td>
                                                                <button type="button" class="btn btn-sm btn-outline-danger hapus-row-kimia-btn mt-1">
                                                                    <i class="ri-delete-bin-line"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div> <!-- END col-lg-9 -->

                                        <!-- HISTORY PANEL KIMIA -->
                                        <div class="col-lg-3 col-md-12">
                                            <div class="card h-100 border-start border-primary">
                                                <div class="card-header bg-light py-2">
                                                    <h6 class="card-title mb-0 small">
                                                        <i class="ri-history-line me-2 text-primary"></i>History Kimia
                                                    </h6>
                                                </div>
                                                <div class="card-body p-0">
                                                    <div class="p-2 border-bottom bg-primary bg-opacity-5" id="currentHoverInfo_kimia">
                                                        <div class="text-center">
                                                            <div class="text-primary mb-1 small" id="hoverJenisPemeriksaan_kimia">
                                                                <i class="ri-cursor-line me-1"></i>
                                                                <span>Pilih hasil</span>
                                                            </div>
                                                            <div class="small text-muted" id="hoverTypeInfo_kimia">
                                                                Klik pada kolom "Hasil"
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="p-2" id="historyPanelContent_kimia" style="height: 300px; overflow-y: auto; font-size: 0.85rem;">
                                                        <div class="text-center text-muted py-4">
                                                            <i class="ri-file-list-3-line display-6 mb-3 opacity-50"></i>
                                                            <p class="mb-1 small">History akan muncul di sini</p>
                                                            <small class="text-muted">Klik pada hasil</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> <!-- END col-lg-3 -->
                                    </div> <!-- END row -->
                                </div> <!-- END mt-4 -->
                            @else
                                <div class="alert alert-warning">
                                    <i class="ri-flask-line me-2"></i>Data hasil pemeriksaan untuk Kimia Belum/Tidak Dilakukan
                                </div>
                            @endif

                            <!-- HASIL LAIN SECTION -->
                            <!-- HASIL LAIN SECTION -->
                            @if($hasil_lain->count() > 0)
                                @foreach($hasil_lain_grouped as $jenis_pemeriksaan => $items)
                                    @php
                                        $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '_', $jenis_pemeriksaan));
                                    @endphp

                                <div class="pt-3 border-top pemeriksaan-lain-section" data-jenis-pemeriksaan="{{ $jenis_pemeriksaan }}">
                                        <div class="row">
                                            <!-- TABEL PEMERIKSAAN LAIN -->
                                            <div class="col-lg-9 col-md-12">
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <h6 class="mb-0 border-bottom pb-2">
                                                        <i class="ri-list-check me-2"></i>{{ $jenis_pemeriksaan }}
                                                        <span class="badge bg-info ms-2">Kondisi: {{ $pasien->jenis_kelamin }} | {{ $data['umur_format'] }}</span>
                                                    </h6>
                                                    <div>
                                                        <!-- Tombol Tambah Row (Manual) -->
                                                        <button type="button" class="btn btn-sm btn-outline-primary tambah-row-btn-hasil-lain"
                                                                data-jenis-pemeriksaan="{{ $jenis_pemeriksaan }}">
                                                            <i class="ri-add-line me-1"></i>Tambah Row
                                                        </button>
                                                        <!-- Tombol Modal (Checkbox Multiple) -->
                                                        <button type="button" class="btn btn-sm btn-outline-success modal-hasil-lain-btn ms-2"
                                                                data-jenis-pemeriksaan="{{ $jenis_pemeriksaan }}">
                                                            <i class="ri-list-check me-1"></i>Pilih dari Daftar
                                                        </button>
                                                        <!-- Tombol Hapus Tabel -->
                                                        <button type="button" class="btn btn-sm btn-outline-danger hapus-tabel-btn-hasil-lain ms-2"
                                                                data-jenis-pemeriksaan="{{ $jenis_pemeriksaan }}">
                                                            <i class="ri-delete-bin-line me-1"></i>Hapus Tabel
                                                        </button>
                                                    </div>
                                                </div>

                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-sm pemeriksaan-lain-table table-row-skip" id="tabel_{{ $slug }}">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th width="20%" class="bg-light">Pilih Jenis Pemeriksaan</th>
                                                                <th width="10%" class="bg-light">Satuan</th>
                                                                <th width="15%" class="bg-light">Rujukan</th>
                                                                <th width="5%" class="bg-light">CH</th>
                                                                <th width="5%" class="bg-light">CL</th>
                                                                <th width="15%">Hasil Pengujian</th>
                                                                <th width="10%">Keterangan</th>
                                                                <th width="5%">Aksi</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($items as $index => $item)
                                                            @php
                                                                // Gunakan data yang sudah dihitung di controller
                                                                $rujukan_value = $item->rujukan_by_kondisi ?? '-';
                                                                $ch_value = $item->ch_by_kondisi ?? '-';
                                                                $cl_value = $item->cl_by_kondisi ?? '-';
                                                                $satuan_value = $item->satuan_by_kondisi ?? '-';
                                                                $isRujukanFromDetail = $item->is_from_detail ?? false;
                                                                $detailCondition = $item->detail_condition ?? null;

                                                                // Gunakan keterangan yang sudah dihitung
                                                                $keterangan = $item->calculated_keterangan ?? $item->keterangan ?? '-';

                                                                // Tentukan warna untuk keterangan
                                                                if ($keterangan === 'CH' || $keterangan === 'H') {
                                                                    $bgColor = 'bg-danger bg-opacity-10';
                                                                    $textColor = 'text-danger';
                                                                    $textDisplay = $keterangan === 'CH' ? 'CH' : 'H';
                                                                } elseif ($keterangan === 'CL' || $keterangan === 'L') {
                                                                    $bgColor = 'bg-primary bg-opacity-10';
                                                                    $textColor = 'text-primary';
                                                                    $textDisplay = $keterangan === 'CL' ? 'CL' : 'L';
                                                                } else {
                                                                    $bgColor = 'bg-success bg-opacity-10';
                                                                    $textColor = 'text-success';
                                                                    $textDisplay = '-';
                                                                }
                                                            @endphp

                                                            <tr data-index="{{ $index }}"
                                                                data-id="{{ $item->id_hasil_lain ?? '' }}"
                                                                data-jenis-pemeriksaan="{{ $jenis_pemeriksaan }}"
                                                                class="@if($isRujukanFromDetail) table-info @endif">
                                                                <!-- Kolom Search Data Pemeriksaan -->
                                                                <td class="search-cell-hasil-lain">
                                                                    <div class="position-relative">
                                                                        <input type="text"
                                                                            class="form-control form-control-sm search-data-pemeriksaan-hasil-lain"
                                                                            placeholder="Cari data pemeriksaan..."
                                                                            value="{{ $item->data_pemeriksaan ?? $item->jenis_pengujian ?? '' }}"
                                                                            data-row-id="{{ $item->id_hasil_lain ?? '' }}"
                                                                            data-jenis-pemeriksaan="{{ $jenis_pemeriksaan }}"
                                                                            data-index="{{ $index }}"
                                                                            autocomplete="off">

                                                                        <!-- Dropdown hasil pencarian -->
                                                                        <div class="search-results-hasil-lain dropdown-menu w-100"
                                                                            style="display: none; max-height: 200px; overflow-y: auto; z-index: 1050;">
                                                                        </div>

                                                                        <!-- Hidden inputs -->
                                                                        <input type="hidden"
                                                                            name="hasil_lain[{{ $jenis_pemeriksaan }}][{{ $index }}][id]"
                                                                            value="{{ $item->id_hasil_lain ?? '' }}">
                                                                        <input type="hidden"
                                                                            name="hasil_lain[{{ $jenis_pemeriksaan }}][{{ $index }}][id_data_pemeriksaan]"
                                                                            class="id-data-pemeriksaan-input"
                                                                            value="{{ $item->id_data_pemeriksaan ?? '' }}">
                                                                        <input type="hidden"
                                                                            name="hasil_lain[{{ $jenis_pemeriksaan }}][{{ $index }}][jenis_pengujian]"
                                                                            class="jenis-pengujian-input"
                                                                            value="{{ $item->jenis_pengujian ?? '' }}">
                                                                    </div>
                                                                </td>

                                                                <!-- Kolom Satuan -->
                                                                <td class="bg-light satuan-cell-hasil-lain">
                                                                    <span class="satuan-display-hasil-lain">{{ $satuan_value }}</span>
                                                                </td>

                                                                <!-- Kolom Rujukan -->
                                                                <td class="bg-light rujukan-cell-hasil-lain">
                                                                    <span class="rujukan-display-hasil-lain">
                                                                        {{ $rujukan_value }}
                                                                        @if($isRujukanFromDetail)
                                                                        <span class="badge bg-info ms-1" title="CH/CL khusus kondisi">K</span>
                                                                        @endif
                                                                    </span>
                                                                </td>

                                                                <!-- Kolom CH -->
                                                                <td class="bg-light ch-cell-hasil-lain">
                                                                    <span class="ch-display-hasil-lain">
                                                                        {{ $ch_value }}
                                                                    </span>
                                                                    <input type="hidden" class="ch-input-hasil-lain" value="{{ $ch_value }}">
                                                                </td>

                                                                <!-- Kolom CL -->
                                                                <td class="bg-light cl-cell-hasil-lain">
                                                                    <span class="cl-display-hasil-lain">
                                                                        {{ $cl_value }}
                                                                    </span>
                                                                    <input type="hidden" class="cl-input-hasil-lain" value="{{ $cl_value }}">
                                                                </td>

                                                                <!-- Kolom Hasil Pengujian -->
                                                                <td class="hasil-cell-hasil-lain">
                                                                    <input type="text"
                                                                        name="hasil_lain[{{ $jenis_pemeriksaan }}][{{ $index }}][hasil_pengujian]"
                                                                        class="form-control form-control-sm hasil-input-hasil-lain"
                                                                        value="{{ $item->hasil_pengujian ?? '' }}"
                                                                        placeholder="Hasil"
                                                                        data-id="{{ $item->id_hasil_lain ?? '' }}"
                                                                        data-type="hasil_lain"
                                                                        data-id-data-pemeriksaan="{{ $item->id_data_pemeriksaan ?? '' }}"
                                                                        data-rujukan="{{ $rujukan_value }}"
                                                                        data-ch="{{ $ch_value }}"
                                                                        data-cl="{{ $cl_value }}"
                                                                        data-jenis="{{ $item->jenis_pengujian ?? '' }}"
                                                                        data-umur="{{ $data['umur_format'] }}"
                                                                        data-jenis-kelamin="{{ $pasien->jenis_kelamin }}"
                                                                        autocomplete="off">
                                                                </td>

                                                                <!-- Kolom Keterangan -->
                                                                <td class="keterangan-cell-hasil-lain">
                                                                    <div class="keterangan-display-hasil-lain {{ $bgColor }} {{ $textColor }} rounded py-1 px-2 text-center"
                                                                        data-keterangan="{{ $keterangan }}">
                                                                        <strong>{{ $textDisplay }}</strong>
                                                                    </div>
                                                                    <input type="hidden"
                                                                        name="hasil_lain[{{ $jenis_pemeriksaan }}][{{ $index }}][keterangan]"
                                                                        class="keterangan-input-hasil-lain"
                                                                        value="{{ $keterangan }}">
                                                                </td>

                                                                <!-- Kolom Aksi -->
                                                                <td>
                                                                    <button type="button" class="btn btn-sm btn-outline-danger hapus-row-btn-hasil-lain">
                                                                        <i class="ri-delete-bin-line"></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>

                                            <!-- HISTORY PANEL -->
                                            <div class="col-lg-3 col-md-12">
                                                <div class="card h-100 border-start border-primary history-panel-card">
                                                    <div class="card-header bg-light py-2">
                                                        <h6 class="card-title mb-0 small">
                                                            <i class="ri-history-line me-2 text-primary"></i>History {{ $jenis_pemeriksaan }}
                                                        </h6>
                                                    </div>
                                                    <div class="card-body p-0">
                                                        <div class="p-2 border-bottom bg-primary bg-opacity-5"
                                                            id="currentHoverInfo_{{ $slug }}">
                                                            <div class="text-center">
                                                                <div class="text-primary mb-1 small"
                                                                    id="hoverJenisPemeriksaan_{{ $slug }}">
                                                                    <i class="ri-history-line me-1"></i>
                                                                    <span>History Pemeriksaan</span>
                                                                </div>
                                                                <div class="small text-muted"
                                                                    id="hoverTypeInfo_{{ $slug }}">
                                                                    Klik pada kolom "Hasil"
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="p-2 history-panel-content"
                                                            id="historyPanelContent_{{ $slug }}"
                                                            style="overflow-y: auto; font-size: 0.85rem;">
                                                            <!-- Content akan diisi oleh JavaScript -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif

                            <!-- Tombol untuk tambah tabel pemeriksaan baru -->
                            <div class="mt-3">
                                <div class="card">
                                    <div class="card-header">
                                        <h6 class="mb-0">
                                            <i class="ri-add-box-line me-2"></i>Tambah Pemeriksaan Lain
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label">Pilih Jenis Pemeriksaan</label>
                                                <select id="jenisPemeriksaanSelect" class="form-select">
                                                    <option value="">-- Pilih Jenis Pemeriksaan --</option>
                                                    @foreach($jenis_pemeriksaan_1_list as $itemJenis)
                                                        <option value="{{ $itemJenis->nama_pemeriksaan }}">
                                                            {{ $itemJenis->nama_pemeriksaan }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                            </div>
                                            <div class="col-md-6 d-flex align-items-end">
                                                <button type="button" id="tambahTabelBtn" class="btn btn-primary">
                                                    <i class="ri-table-line me-1"></i>Tambah Tabel Pemeriksaan
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal untuk Pilih Data Pemeriksaan (Checkbox Multiple) -->

                            <div class="modal fade" id="modalPilihDataPemeriksaan" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">
                                                <i class="ri-list-check me-2"></i>
                                                <span id="modalTitleJenisPemeriksaan">Pilih Data Pemeriksaan</span>
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <!-- Search Bar -->
                                            <div class="mb-3">
                                                <div class="input-group">
                                                    <input type="text"
                                                        class="form-control"
                                                        id="searchModalDataPemeriksaan"
                                                        placeholder="Cari data pemeriksaan...">
                                                    <button class="btn btn-outline-secondary" type="button" id="clearSearchModal">
                                                        <i class="ri-close-line"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- Checkbox Select All -->
                                            <div class="mb-3 d-flex align-items-center">
                                                <div class="form-check me-3">
                                                    <input class="form-check-input" type="checkbox" id="selectAllModal">
                                                    <label class="form-check-label" for="selectAllModal">
                                                        Pilih Semua
                                                    </label>
                                                </div>
                                                <div class="text-muted small" id="selectedCountModal">
                                                    0 item dipilih
                                                </div>
                                            </div>

                                            <!-- Table Data Pemeriksaan -->
                                            <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                                                <table class="table table-sm table-hover">
                                                    <thead class="table-light sticky-top">
                                                        <tr>
                                                            <th width="5%">#</th>
                                                            <th width="15%">Kode</th>
                                                            <th width="40%">Nama Pemeriksaan</th>
                                                            <th width="15%">Satuan</th>
                                                            <th width="25%">Rujukan</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="modalDataPemeriksaanList">
                                                        <!-- Data akan diisi oleh JavaScript -->
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="button" class="btn btn-primary" id="tambahDataPemeriksaanBtn">
                                                <i class="ri-add-line me-1"></i>Tambah ke Tabel
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="modal fade" id="modalKonfirmasiHapusTabel" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Konfirmasi Hapus</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            Yakin ingin menghapus tabel <strong id="modalNamaTabel"></strong> ?
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button class="btn btn-danger" id="konfirmasiHapusTabelBtn">
                                                Ya, Hapus
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <!-- Toast Container -->
                            <div id="toastContainer" class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;"></div>
                        </div> <!-- END card-body -->

                        <div class="card-footer border-top">
                            <div class="row align-items-center">
                                <div class="col-md-4">
                                    <div class="mb-2 mb-md-0">
                                        <label class="form-label small mb-1">
                                            <i class="ri-user-check-line me-1"></i> Validator
                                        </label>

                                        <div class="input-group" id="pemeriksaContainer">
                                            <!-- HAPUS data-bs-toggle dan data-bs-display -->
                                            <input
                                                type="text"
                                                id="pemeriksaInput"
                                                class="form-control form-control-sm"
                                                placeholder="Ketik minimal 2 karakter nama pemeriksa..."
                                                autocomplete="off"
                                                value="{{ $pasien->pemeriksa->nama_pemeriksa ?? '' }}"
                                                data-pemeriksa-id="{{ $pasien->id_pemeriksa ?? '' }}"
                                                data-pemeriksa-nama="{{ $pasien->pemeriksa->nama_pemeriksa ?? '' }}">

                                            <button id="savePemeriksaBtn"
                                                type="button"
                                                class="btn btn-sm btn-outline-primary ms-1">
                                                <i class="ri-save-line"></i>
                                            </button>

                                            <!-- DROPDOWN HASIL SEARCH -->
                                            <div id="pemeriksaDropdown"
                                                class="dropdown-menu"
                                                style="display: none; max-height:300px; overflow-y:auto;">
                                            </div>
                                        </div>

                                        <div class="mt-1" id="validatorInfo">
                                            @if($pasien->id_pemeriksa)
                                                <small class="text-success">
                                                    <i class="ri-checkbox-circle-line me-1"></i>
                                                    Sudah divalidasi oleh:
                                                    <strong>{{ $pasien->pemeriksa->nama_pemeriksa }}</strong>
                                                </small>
                                            @else
                                                <small class="text-warning">
                                                    <i class="ri-alert-line me-1"></i>
                                                    Belum divalidasi.
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="text-muted small">
                                        <i class="ri-time-line me-1"></i>
                                        <span id="lastSaved">Terakhir disimpan: {{ $pasien->updated_at ? \Carbon\Carbon::parse($pasien->updated_at)->format('d/m/Y H:i') : '-' }}</span>
                                    </span>
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="button" id="saveAllBtn" class="btn btn-primary">
                                        <i class="ri-save-line me-1"></i> Simpan Semua
                                    </button>
                                    <a href="{{ route('pasien.print', $pasien->no_lab) }}" class="btn btn-success">
                                        <i class="ri-printer-line me-1"></i> Print
                                    </a>
                                    <button type="button" id="refreshBtn" class="btn btn-outline-secondary">
                                        <i class="ri-refresh-line me-1"></i> Refresh
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Content ends -->
    @else
    <div class="alert alert-danger">
        <i class="ri-error-warning-line me-2"></i>Data pasien tidak ditemukan.
    </div>
    @endif

</div>
<!-- App body ends -->


<style>
    /* Simple Modal Styles */
    #simplePilihPemeriksaanModal .modal-dialog {
        max-width: 800px;
    }

    #simplePilihPemeriksaanModal .modal-body {
        max-height: 70vh;
    }

    .simple-pemeriksaan-item {
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .simple-pemeriksaan-item:hover {
        background-color: rgba(0, 0, 0, 0.03);
    }

    .simple-pemeriksaan-item.table-primary {
        background-color: rgba(13, 110, 253, 0.1) !important;
    }

    .simple-pemeriksaan-item.table-light {
        opacity: 0.6;
    }

    .simple-checkbox {
        margin-top: 0.25rem;
    }

    .btn-add-single {
        padding: 0.15rem 0.35rem;
        font-size: 0.75rem;
    }

    /* Sticky header */
    #simplePemeriksaanModal .sticky-top {
        position: sticky;
        top: 0;
        z-index: 10;
        background: white;
    }

    /* Scrollbar styling */
    #simplePemeriksaanModal .table-responsive::-webkit-scrollbar {
        width: 6px;
    }

    #simplePemeriksaanModal .table-responsive::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    #simplePemeriksaanModal .table-responsive::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
    }
    /* History Panel Styling */
    .card.border-start {
        border-left-width: 3px !important;
    }

    #currentHoverInfo {
        transition: all 0.3s ease;
        min-height: 70px;
    }

    .history-item-card {
        padding: 0.75rem;
        margin-bottom: 0.5rem;
        background-color: #f8f9fa;
        border-radius: 4px;
        transition: transform 0.2s ease;
    }

    .history-item-card:hover {
        transform: translateX(-2px);
        background-color: #e9ecef;
    }

    .history-date {
        font-size: 0.75rem;
        color: #6c757d;
        display: block;
    }

    .history-result {
        font-weight: 200;
        color: #0d6efd;
        font-size: 0.5rem;
    }

    .history-lab {
        font-size: 0.8rem;
        color: #6c757d;
    }

    /* Scrollbar styling */
    #historyPanelContent {
        scrollbar-width: thin;
        scrollbar-color: #c1c1c1 #f1f1f1;
    }

    #historyPanelContent::-webkit-scrollbar {
        width: 6px;
    }

    #historyPanelContent::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }

    #historyPanelContent::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 3px;
    }

    #historyPanelContent::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }
</style>

<style>

    /* Dropdown Hasil Lain Styles - Like Kimia */
    .kode-search-results {
        position: absolute;
        z-index: 1050;
        background: white;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        box-shadow: 0 6px 12px rgba(0,0,0,0.175);
        padding: 0;
        margin: 2px 0 0;
        overflow: hidden;
    }

    .kode-search-results .dropdown-header {
        padding: 0.5rem 1rem;
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .kode-search-results .dropdown-item {
        padding: 0.75rem 1rem;
        border-bottom: 1px solid #f8f9fa;
        cursor: pointer;
        white-space: normal;
        word-wrap: break-word;
        transition: background-color 0.15s;
    }

    .kode-search-results .dropdown-item:last-child {
        border-bottom: none;
    }

    .kode-search-results .dropdown-item:hover {
        background-color: #f8f9fa;
    }

    .kode-search-results .dropdown-item:active {
        background-color: #e9ecef;
    }

    .kode-search-results .dropdown-item.disabled {
        opacity: 0.6;
        cursor: not-allowed;
        background-color: #f8f9fa;
    }

    /* Loading indicator */
    .kode-search-results .spinner-border {
        width: 1rem;
        height: 1rem;
        border-width: 0.2em;
    }

    /* Badge styles */
    .kode-search-results .badge {
        font-size: 0.7rem;
        padding: 0.2rem 0.4rem;
    }
    /* Custom Styles */

    /* Global Search Dropdown Styles */
    #globalSearchResults {
        max-height: 400px;
        overflow-y: auto;
        width: 500px;
    }

    #globalSearchResults .dropdown-header {
        padding: 0.75rem 1rem;
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        font-weight: 600;
    }

    #globalSearchResults .kode-option-hasil-lain {
        display: block;
        width: 100%;
        padding: 0.75rem 1rem;
        clear: both;
        font-weight: 400;
        color: #212529;
        text-align: inherit;
        text-decoration: none;
        white-space: normal;
        background-color: transparent;
        border: 0;
        border-bottom: 1px solid #f8f9fa;
        cursor: pointer;
        transition: background-color 0.15s ease-in-out;
    }

    #globalSearchResults .kode-option-hasil-lain:hover {
        background-color: #f8f9fa;
    }

    #globalSearchResults .kode-option-hasil-lain:last-child {
        border-bottom: none;
    }

    #globalSearchResults .kode-option-hasil-lain .kode-badge {
        background-color: #0d6efd;
        color: white;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        font-size: 0.75rem;
        margin-right: 0.5rem;
    }

    #globalSearchResults .kode-option-hasil-lain .rujukan-badge {
        background-color: #198754;
        color: white;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        font-size: 0.75rem;
    }

    .excel-input:focus {
        box-shadow: 0 0 0 2px rgba(13, 110, 253, 0.25) !important;
        border-color: #86b7fe !important;
        background-color: #fff !important;
    }

    .is-changing {
        background-color: #fff3cd !important;
        border-color: #ffc107 !important;
    }

    .is-changed {
        border-color: #0d6efd !important;
        background-color: #e7f1ff !important;
    }

    .table-warning {
        background-color: rgba(255, 193, 7, 0.1) !important;
    }

    .table-success {
        background-color: rgba(25, 135, 84, 0.1) !important;
        transition: background-color 0.5s ease;
    }

    .keterangan-display {
        min-height: 31px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.875rem;
        transition: all 0.3s ease;
        border: 1px solid transparent;
    }

    .bg-danger.bg-opacity-10 {
        background-color: rgba(220, 53, 69, 0.1) !important;
        border-color: rgba(220, 53, 69, 0.3) !important;
    }

    .bg-primary.bg-opacity-10 {
        background-color: rgba(13, 110, 253, 0.1) !important;
        border-color: rgba(13, 110, 253, 0.3) !important;
    }

    .bg-success.bg-opacity-10 {
        background-color: rgba(25, 135, 84, 0.1) !important;
        border-color: rgba(25, 135, 84, 0.3) !important;
    }

    .text-danger {
        color: #dc3545 !important;
        font-weight: bold;
    }

    .text-primary {
        color: #0d6efd !important;
        font-weight: bold;
    }

    .text-success {
        color: #198754 !important;
        /* font-weight: bold; */
    }

    .has-error {
        border-color: #dc3545 !important;
        background-color: rgba(220, 53, 69, 0.05) !important;
    }

    /* Loading spinner */
    .spin {
        animation: spin 1s linear infinite;
        display: inline-block;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    /* Toast notification */
    .toast-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1050;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .pemeriksaan-checkbox:checked + label {
        background-color: rgba(13, 110, 253, 0.1) !important;
    }

    .pemeriksaan-item {
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .pemeriksaan-item:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }

    .pemeriksaan-item.selected {
        background-color: rgba(13, 110, 253, 0.05);
        border-left: 3px solid #0d6efd;
    }

    .selected-order {
        display: inline-block;
        width: 24px;
        height: 24px;
        line-height: 24px;
        text-align: center;
        background-color: #0d6efd;
        color: white;
        border-radius: 50%;
        font-size: 0.75rem;
        font-weight: bold;
        margin-right: 8px;
    }

    .preview-badge {
        background-color: #e7f1ff;
        border: 1px solid #86b7fe;
        color: #0d6efd;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        font-size: 0.75rem;
        margin-right: 0.25rem;
        margin-bottom: 0.25rem;
        display: inline-block;
    }

    .sticky-top {
        position: sticky;
        top: 0;
        z-index: 10;
    }

    /* Loading overlay */
    .loading-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
    }

    /* Scrollbar styling */
    .table-responsive::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    .table-responsive::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }

    .table-responsive::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 4px;
    }

    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }

    /* Style untuk dropdown penjamin dan ruangan */
    .penjamin-option:hover, .ruangan-option:hover {
        background-color: #f8f9fa;
        cursor: pointer;
    }

    .penjamin-option:active, .ruangan-option:active {
        background-color: #e9ecef;
    }

    /* #penjaminInfo, #ruanganInfo {
        margin-top: 5px;
        padding: 5px;
        background-color: rgba(25, 135, 84, 0.1);
        border-radius: 4px;
        border-left: 3px solid #198754;
    } */

    #penjaminDropdown, #ruanganDropdown {
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,.15);
        border: 1px solid rgba(0,0,0,.15);
    }

    .dropdown-item {
        white-space: normal;
        word-wrap: break-word;
    }

    .dropdown-item:hover {
        background-color: #f8f9fa;
    }

    /* Style untuk input yang sedang aktif */
    #penjaminInput:focus, #ruanganInput:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }

    /* Loading spinner */
    .spinner-border {
        width: 1rem;
        height: 1rem;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        #penjaminDropdown, #ruanganDropdown {
            width: 100% !important;
            min-width: 100% !important;
            max-width: 100% !important;
        }
    }

    /* Toast Container */
    .toast-container {
        z-index: 9999;
    }

    /* Checkbox Modal */
    #checkboxModal .modal-body {
        max-height: 60vh;
        overflow-y: auto;
    }

    #checkboxModal .checkbox-item {
        padding: 5px;
        border-radius: 4px;
        transition: background-color 0.2s;
    }

    #checkboxModal .checkbox-item:hover {
        background-color: rgba(0, 123, 255, 0.1);
    }

    /* Search Results Dropdown */
    .kode-search-results {
        z-index: 1050;
        max-height: 300px;
        overflow-y: auto;
    }

    .kode-search-results .dropdown-item {
        padding: 8px 12px;
        cursor: pointer;
    }

    .kode-search-results .dropdown-item:hover {
        background-color: #f8f9fa;
    }

    /* Loading Modal */
    #loadingModal .modal-content {
        background: transparent;
        box-shadow: none;
        border: none;
    }

    #loadingModal .spinner-border {
        width: 3rem;
        height: 3rem;
    }
</style>

<style>
    .search-results-hasil-lain {
        position: absolute !important;
        z-index: 9999;
        max-height: 250px;
        overflow-y: auto;
    }
    /* WAJIB: biar dropdown tidak kepotong */
    .table-responsive,
    .card-body,
    .card {
        overflow: visible !important;
    }

    /* Dropdown search harus absolute & melayang */
    .search-results-hasil-lain {
        position: absolute !important;
        top: 100% !important;
        left: 0;
        right: 0;
        z-index: 99999 !important;
        max-height: 250px;
        overflow-y: auto;
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 6px;
        box-shadow: 0 6px 18px rgba(0,0,0,0.15);
    }

</style>

<style>
    .ri-loader-4-line {
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    #pemeriksaContainer {
        position: relative;
    }

    #pemeriksaDropdown {
        background: white;
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }

    .dropdown-item {
        padding: 0.5rem 1rem;
        cursor: pointer;
    }

    .dropdown-item:hover {
        background-color: #f8f9fa;
    }
</style>


<!-- Toast Container -->
<div class="toast-container"></div>


@endsection

@section('scripts')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


<script>
    document.addEventListener('change', function (e) {
        if (!e.target.classList.contains('realtime-datetime')) return;

        const input = e.target;
        const value = input.value;

        // kosong boleh
        if (!value) return;

        // Ambil tahun
        const year = parseInt(value.substring(0, 4));

        // Validasi tahun (misal 1900–2100)
        if (isNaN(year) || year < 1900 || year > 2100) {
            input.value = '';
            input.focus();
            return;
        }

        // Validasi format ISO datetime-local
        const regex = /^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/;
        if (!regex.test(value)) {
            alert('Format salah: Gunakan format tanggal & waktu yang benar');
            input.value = '';
            input.focus();
            return;
        }
    });
</script>

<!-- Select2 Initialization Script -->
<script>
    $(document).ready(function () {
        $('#jenisPemeriksaanSelect').select2({
            placeholder: '-- Pilih Jenis Pemeriksaan --',
            width: '100%',
            allowClear: true,
            minimumResultsForSearch: 0 // PAKSA INPUT SEARCH MUNCUL
        });
    });
</script>
<!-- Validator System Script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('🔧 Validator System Initializing...');

        const input = document.getElementById('pemeriksaInput');
        const dropdown = document.getElementById('pemeriksaDropdown');
        const saveBtn = document.getElementById('savePemeriksaBtn');
        const csrfToken = '{{ csrf_token() }}';

        if (!input) {
            console.error('❌ Input validator tidak ditemukan');
            return;
        }

        let typingTimer;
        const delay = 500;
        let selectedPemeriksaId = input.dataset.pemeriksaId || null;
        let selectedPemeriksaNama = input.dataset.pemeriksaNama || null;

        // ==================== FUNGSI UTAMA ====================

        // 1. Fungsi untuk menampilkan toast
        function showValidatorToast(type, message) {
            const toastId = 'validator-toast-' + Date.now();
            const toastHtml = `
                <div id="${toastId}" class="toast align-items-center text-bg-${type}" role="alert">
                    <div class="d-flex">
                        <div class="toast-body">
                            <i class="ri-${type === 'success' ? 'check' : 'error-warning'}-line me-2"></i>
                            ${message}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                    </div>
                </div>
            `;

            // Gunakan container yang sama
            const container = document.querySelector('#toastContainer') || createToastContainer();
            container.insertAdjacentHTML('beforeend', toastHtml);

            const toastElement = document.getElementById(toastId);
            const bsToast = new bootstrap.Toast(toastElement, { delay: 3000 });
            bsToast.show();

            toastElement.addEventListener('hidden.bs.toast', function() {
                this.remove();
            });
        }

        function createToastContainer() {
            const container = document.createElement('div');
            container.id = 'toastContainer';
            container.className = 'toast-container position-fixed top-0 end-0 p-3';
            container.style.zIndex = '9999';
            document.body.appendChild(container);
            return container;
        }

        // 2. Fungsi untuk menampilkan dropdown
        function showDropdown(content) {
            if (!dropdown) return;

            dropdown.innerHTML = content;
            dropdown.style.display = 'block';
            dropdown.style.position = 'absolute';
            dropdown.style.top = (input.offsetHeight + 5) + 'px';
            dropdown.style.left = '0';
            dropdown.style.width = input.offsetWidth + 'px';
            dropdown.style.zIndex = '1050';
            dropdown.style.backgroundColor = 'white';
            dropdown.style.border = '1px solid #dee2e6';
            dropdown.style.borderRadius = '4px';
            dropdown.style.boxShadow = '0 0.5rem 1rem rgba(0,0,0,.15)';
            dropdown.style.maxHeight = '300px';
            dropdown.style.overflowY = 'auto';
        }

        function hideDropdown() {
            if (dropdown) {
                dropdown.style.display = 'none';
            }
        }

        // 3. Fungsi untuk mencari pemeriksa
        function searchPemeriksa(keyword) {
            if (keyword.length < 2) {
                showDropdown(`
                    <div class="dropdown-item text-muted py-2">
                        <i class="ri-search-line me-2"></i>
                        <span>Ketik minimal 2 karakter</span>
                    </div>
                `);
                return;
            }

            showDropdown(`
                <div class="dropdown-item py-2">
                    <div class="d-flex align-items-center">
                        <div class="spinner-border spinner-border-sm text-primary me-2"></div>
                        <span class="text-muted">Mencari pemeriksa...</span>
                    </div>
                </div>
            `);

            // Gunakan route yang benar
            const url = '{{ route("pemeriksa.search") }}';
            console.log('Searching pemeriksa:', keyword, 'URL:', url);

            fetch(`${url}?q=${encodeURIComponent(keyword)}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Search results:', data);

                    if (!data || data.length === 0) {
                        showDropdown(`
                            <div class="dropdown-item text-muted py-2">
                                <i class="ri-user-search-line me-2"></i>
                                <span>Tidak ditemukan pemeriksa</span>
                            </div>
                        `);
                        return;
                    }

                    let html = '';
                    data.forEach(item => {
                        html += `
                            <button type="button"
                                    class="dropdown-item pemeriksa-item text-start py-2"
                                    data-id="${item.id}"
                                    data-nama="${item.text || item.nama_pemeriksa}">
                                <i class="ri-user-line me-2"></i>
                                ${item.text || item.nama_pemeriksa}
                            </button>
                        `;
                    });

                    showDropdown(html);
                })
                .catch(error => {
                    console.error('Search error:', error);
                    showDropdown(`
                        <div class="dropdown-item text-danger py-2">
                            <i class="ri-error-warning-line me-2"></i>
                            <span>Gagal memuat data</span>
                        </div>
                    `);
                });
        }

        // 4. Fungsi untuk menyimpan validator
        function saveValidator() {
            const value = input.value.trim();

            if (!value) {
                showValidatorToast('warning', 'Masukkan nama pemeriksa');
                return;
            }

            const originalHtml = saveBtn.innerHTML;
            saveBtn.innerHTML = '<i class="ri-loader-4-line spin"></i>';
            saveBtn.disabled = true;

            // Kirim ke server
            fetch('{{ route("pasien.update.data.validator", $pasien->no_lab) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    id_pemeriksa: selectedPemeriksaId,
                    nama_pemeriksa: value
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log('Save response:', data);

                if (data.success) {
                    // Update data
                    selectedPemeriksaId = data.pemeriksa?.id_pemeriksa || selectedPemeriksaId;
                    selectedPemeriksaNama = data.pemeriksa?.nama_pemeriksa || value;

                    // Update UI
                    input.value = selectedPemeriksaNama;
                    input.dataset.pemeriksaId = selectedPemeriksaId;
                    input.dataset.pemeriksaNama = selectedPemeriksaNama;

                    // Update info display
                    document.getElementById('validatorInfo').innerHTML = `
                        <small class="text-success">
                            <i class="ri-checkbox-circle-line me-1"></i>
                            Sudah divalidasi oleh: <strong>${selectedPemeriksaNama}</strong>
                        </small>
                    `;

                    showValidatorToast('success', 'Validator berhasil diperbarui');
                } else {
                    showValidatorToast('danger', data.message || 'Gagal menyimpan');
                }
            })
            .catch(error => {
                console.error('Save error:', error);
                showValidatorToast('danger', 'Terjadi kesalahan jaringan');
            })
            .finally(() => {
                saveBtn.innerHTML = originalHtml;
                saveBtn.disabled = false;
            });
        }

        // ==================== EVENT LISTENERS ====================

        // Input typing dengan debounce
        input.addEventListener('input', function() {
            clearTimeout(typingTimer);

            const keyword = this.value.trim();

            // Reset selection jika berbeda dengan yang dipilih
            if (keyword !== selectedPemeriksaNama) {
                selectedPemeriksaId = null;
                selectedPemeriksaNama = null;
            }

            typingTimer = setTimeout(() => {
                searchPemeriksa(keyword);
            }, delay);
        });

        // Klik dropdown item
        document.addEventListener('click', function(e) {
            const item = e.target.closest('.pemeriksa-item');
            if (item) {
                selectedPemeriksaId = item.dataset.id;
                selectedPemeriksaNama = item.dataset.nama;

                input.value = selectedPemeriksaNama;
                hideDropdown();

                // Auto save
                setTimeout(saveValidator, 300);
            }
        });

        // Klik tombol save
        saveBtn.addEventListener('click', function(e) {
            e.preventDefault();
            saveValidator();
        });

        // Enter key
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                saveValidator();
            }
        });

        // Klik di luar untuk hide dropdown
        document.addEventListener('click', function(e) {
            if (!e.target.closest('#pemeriksaInput') &&
                !e.target.closest('#pemeriksaDropdown')) {
                hideDropdown();
            }
        });

        // Initialize
        function initializeValidator() {
            const currentId = input.dataset.pemeriksaId;
            const currentNama = input.dataset.pemeriksaNama;

            if (currentId && currentNama) {
                selectedPemeriksaId = currentId;
                selectedPemeriksaNama = currentNama;
                console.log('Initialized with:', { id: selectedPemeriksaId, nama: selectedPemeriksaNama });
            }
        }

        // Jalankan inisialisasi
        setTimeout(initializeValidator, 100);

        console.log('✅ Validator System Ready');
    });
</script>
<!-- Sync History Panel Height Script -->
<script>
    function syncHistoryHeight() {
        document.querySelectorAll('.pemeriksaan-lain-section').forEach(section => {
            const tableWrapper = section.querySelector('.table-responsive');
            const historyContent = section.querySelector('.history-panel-content');

            if (tableWrapper && historyContent) {
                historyContent.style.height = tableWrapper.offsetHeight + 'px';
            }
        });
    }

    // Saat halaman load
    document.addEventListener('DOMContentLoaded', syncHistoryHeight);

    // Jika tabel berubah (tambah/hapus row)
    document.addEventListener('click', function (e) {
        if (
            e.target.closest('.tambah-row-btn-hasil-lain') ||
            e.target.closest('.hapus-row-btn-hasil-lain') ||
            e.target.closest('.hapus-tabel-btn-hasil-lain')
        ) {
            setTimeout(syncHistoryHeight, 100);
        }
    });
</script>
<!-- Main Detail Page Script -->
<script>
    $(document).ready(function() {
        // Setup CSRF token untuk AJAX
        const csrfToken = $('#csrf_token').val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        });

        let pendingChanges = false;
        let ajaxQueue = [];
        let isProcessingQueue = false;

        // Function untuk menampilkan toast notifikasi - GLOBAL SCOPE
        window.showToast = function(type, message) {
            const toastId = 'toast-' + Date.now();
            const toastHtml = `
                <div id="${toastId}" class="toast align-items-center text-bg-${type} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            <i class="ri-${type === 'success' ? 'check-circle' : 'error-warning'}-fill me-2"></i>
                            ${message}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                    </div>
                </div>
            `;

            $('.toast-container').append(toastHtml);
            const toastElement = document.getElementById(toastId);
            const toast = new bootstrap.Toast(toastElement, {
                delay: 3000
            });
            toast.show();

            toastElement.addEventListener('hidden.bs.toast', function() {
                $(this).remove();
            });
        };

        // Function untuk update keterangan display - GLOBAL SCOPE
        // Function untuk update keterangan display - MODIFIED untuk CH dan CL
        window.updateKeteranganDisplay = function($display, keterangan) {
            // Reset semua kelas
            $display.removeClass('bg-danger bg-opacity-10 bg-primary bg-opacity-10 bg-success bg-opacity-10 text-danger text-primary text-success');

            // Tambahkan kelas sesuai nilai
            if (keterangan === 'CH' || keterangan === 'H') {
                $display.addClass('bg-danger bg-opacity-10 text-danger')
                    .html('<strong>' + (keterangan === 'CH' ? 'CH' : 'H') + '</strong>');
            } else if (keterangan === 'CL' || keterangan === 'L') {
                $display.addClass('bg-primary bg-opacity-10 text-primary')
                    .html('<strong>' + (keterangan === 'CL' ? 'CL' : 'L') + '</strong>');
            } else if (keterangan === '-' || keterangan === '') {
                $display.addClass('bg-success bg-opacity-10 text-success')
                    .html('<strong>-</strong>');
            } else {
                $display.addClass('bg-light text-muted')
                    .html('<strong>-</strong>');
            }

            // Update data attribute juga
            $display.data('keterangan', keterangan === '' || keterangan === null ? '-' : keterangan);

            // Update hidden input
            const $row = $display.closest('tr');
            const $hiddenInput = $row.find('input[name*="[keterangan]"]');

            // Pastikan format konsisten
            const finalKeterangan = keterangan === '' || keterangan === null ? '-' : keterangan;
            $hiddenInput.val(finalKeterangan);
        };

        // Function untuk update keterangan client-side (preview) - GLOBAL SCOPE
        // Function untuk update keterangan client-side (preview) - GLOBAL SCOPE
        // Function untuk update keterangan client-side dengan CH dan CL
        // Function untuk update keterangan client-side dengan prioritas CH/CL
        // Function untuk update keterangan client-side (preview)
        window.updateKeteranganClientSide = function($input) {
            const value = $input.val();

            if (!value || value.trim() === '') {
                // Clear jika kosong
                const $row = $input.closest('tr');
                const $keteranganDisplay = $row.find('.keterangan-display');
                const $hiddenInput = $row.find('input[name*="[keterangan]"]');

                updateKeteranganDisplay($keteranganDisplay, '');
                $hiddenInput.val('');
                return;
            }

            // Gunakan fungsi calculateKeterangan yang sama
            const keterangan = calculateKeterangan(value, $input);

            // Update display
            const $row = $input.closest('tr');
            const $keteranganDisplay = $row.find('.keterangan-display');
            const $hiddenInput = $row.find('input[name*="[keterangan]"]');

            updateKeteranganDisplay($keteranganDisplay, keterangan);
            $hiddenInput.val(keterangan);

            console.log('updateKeteranganClientSide →', keterangan);
        };
        // Helper function untuk parse CH/CL value
        window.parseCriticalValue = function(criticalStr) {
            if (!criticalStr || criticalStr === '' || criticalStr === '-' || criticalStr === 'null') {
                return null;
            }

            const str = criticalStr.toString().trim();

            // Format: "> 170" atau ">170"
            if (str.includes('>')) {
                const value = str.replace('>', '').trim();
                const num = parseFloat(value);
                return !isNaN(num) ? { value: num, operator: '>' } : null;
            }

            // Format: "< 90" atau "<90"
            if (str.includes('<')) {
                const value = str.replace('<', '').trim();
                const num = parseFloat(value);
                return !isNaN(num) ? { value: num, operator: '<' } : null;
            }

            // Format: angka langsung "170"
            const num = parseFloat(str);
            return !isNaN(num) ? { value: num, operator: 'direct' } : null;
        };

        // Function untuk cek critical values
        window.checkCriticalValues = function(hasilNum, chStr, clStr) {
            const ch = parseCriticalValue(chStr);
            const cl = parseCriticalValue(clStr);

            if (ch) {
                if (ch.operator === '>') {
                    if (hasilNum > ch.value) return 'CH';
                } else if (ch.operator === 'direct') {
                    if (hasilNum > ch.value) return 'CH';
                }
            }

            if (cl) {
                if (cl.operator === '<') {
                    if (hasilNum < cl.value) return 'CL';
                } else if (cl.operator === 'direct') {
                    if (hasilNum < cl.value) return 'CL';
                }
            }

            return null;
        };

        // Function untuk menambah request ke queue
        // Function untuk menambah request ke queue
        // Function untuk menambah request ke queue dengan prioritas CH/CL
        // Function untuk menambah request ke queue - MODIFIED untuk konsistensi
        function addToQueue(type, id, field, value, $element) {
            if (!id) {
                console.error('❌ addToQueue: ID tidak valid', id);
                return;
            }

            const $row = $element.closest('tr');
            const $keteranganDisplay = $row.find('.keterangan-display');
            const $hiddenInput = $row.find('input[name*="[keterangan]"]');

            // Hitung keterangan
            const keterangan = calculateKeterangan(value, $element);

            console.log('📝 addToQueue - Keterangan dihitung:', keterangan);

            // Update UI
            updateKeteranganDisplay($keteranganDisplay, keterangan);
            $hiddenInput.val(keterangan);

            console.log('📤 Kirim ke server:', {
                type: type,
                id: id,
                field: field,
                value: value,
                keterangan: keterangan
            });

            // Tambah ke queue
            ajaxQueue.push({
                type: type,
                id: id,
                field: field,
                value: value,
                $element: $element,
                keterangan: keterangan, // PASTIKAN INI ADA
                timestamp: Date.now()
            });

            if (!isProcessingQueue) {
                processQueue();
            }
        }
        function calculateKeterangan(value, $element) {
            const hasilStr = value.toString().trim();
            const hasilNum = parseFloat(hasilStr.replace(',', '.'));
            const rujukan = $element.data('rujukan') || $element.attr('data-rujukan') || '';
            const ch = $element.data('ch') || $element.attr('data-ch') || '';
            const cl = $element.data('cl') || $element.attr('data-cl') || '';

            console.log('🔍 calculateKeterangan:', {
                hasil: hasilStr,
                rujukan: rujukan,
                ch: ch,
                cl: cl,
                isNumeric: !isNaN(hasilNum)
            });

            // Clear jika kosong
            if (!hasilStr || hasilStr === '') {
                return '-';
            }

            // Jika numeric
            if (!isNaN(hasilNum)) {
                // 1. PRIORITAS: Cek CH (Critical High)
                if (ch && ch !== '' && ch !== '-' && ch !== 'null') {
                    const chNum = parseCriticalValue(ch);
                    console.log('CH Check:', { ch, chNum, hasilNum });

                    if (chNum !== null && hasilNum > chNum) {
                        console.log('✅ CH terdeteksi:', hasilNum, '>', chNum);
                        return 'CH';
                    }
                }

                // 2. PRIORITAS: Cek CL (Critical Low)
                if (cl && cl !== '' && cl !== '-' && cl !== 'null') {
                    const clNum = parseCriticalValue(cl);
                    console.log('CL Check:', { cl, clNum, hasilNum });

                    if (clNum !== null && hasilNum < clNum) {
                        console.log('✅ CL terdeteksi:', hasilNum, '<', clNum);
                        return 'CL';
                    }
                }

                // 3. PRIORITAS: Cek Rujukan (jika CH/CL tidak match)
                console.log('CH/CL tidak match, cek rujukan...');
                const rujukanResult = checkRujukanLogic(hasilNum, rujukan);
                if (rujukanResult !== '-') {
                    console.log('✅ Rujukan terdeteksi:', rujukanResult);
                    return rujukanResult;
                }

                console.log('Tidak ada kondisi yang match, default ke -');
                return '-';
            }
            // Jika non-numeric
            else {
                console.log('Non-numeric result, checking qualitative...');
                const nonNumericResult = checkNonNumericLogic(hasilStr, rujukan);
                return nonNumericResult;
            }
        }


        function parseCriticalValue(criticalStr) {
            if (!criticalStr || criticalStr === '' || criticalStr === '-' || criticalStr === 'null') {
                return null;
            }

            const str = criticalStr.toString().trim();

            // Format: "> 170" atau ">170"
            if (str.includes('>')) {
                const value = parseFloat(str.replace('>', '').trim());
                return isNaN(value) ? null : value;
            }

            // Format: "< 90" atau "<90"
            if (str.includes('<')) {
                const value = parseFloat(str.replace('<', '').trim());
                return isNaN(value) ? null : value;
            }

            // Format: angka langsung "170"
            const value = parseFloat(str);
            return isNaN(value) ? null : value;
        }

        function checkRujukanLogic(hasilNum, rujukanStr) {
            if (!rujukanStr || rujukanStr === '' || rujukanStr === '-' || rujukanStr === 'null') {
                return '-';
            }

            const rujukan = rujukanStr.toString().trim();

            // 1. FORMAT RANGE: "13 - 50" atau "13-50"
            if (rujukan.includes('-') && !rujukan.includes('<') && !rujukan.includes('>')) {
                const parts = rujukan.split('-');
                if (parts.length === 2) {
                    const min = parseFloat(parts[0].trim());
                    const max = parseFloat(parts[1].trim());

                    if (!isNaN(min) && !isNaN(max)) {
                        if (hasilNum < min) return 'L';
                        if (hasilNum > max) return 'H';
                        return '-';
                    }
                }
            }

            // 2. FORMAT "< X" (Normal jika < X, High jika ≥ X)
            if (rujukan.startsWith('<')) {
                const batas = parseFloat(rujukan.replace('<', '').trim());
                if (!isNaN(batas)) {
                    return hasilNum < batas ? '-' : 'H';
                }
            }

            // 3. FORMAT "> X" (Normal jika > X, Low jika ≤ X)
            if (rujukan.startsWith('>')) {
                const batas = parseFloat(rujukan.replace('>', '').trim());
                if (!isNaN(batas)) {
                    return hasilNum > batas ? '-' : 'L';
                }
            }

            return '-';
        }

        function checkNonNumericLogic(hasilStr, rujukanStr) {
            if (!rujukanStr || rujukanStr === '' || rujukanStr === '-' || rujukanStr === 'null') {
                return '-';
            }

            const hasilLower = hasilStr.toLowerCase();
            const rujukanLower = rujukanStr.toLowerCase();

            if (rujukanLower.includes('negative') || rujukanLower.includes('negatif')) {
                if (hasilLower.includes('negative') || hasilLower.includes('negatif') ||
                    hasilLower.includes('non-reactive') || hasilLower.includes('nonreactive')) {
                    return '-';
                } else {
                    return 'H';
                }
            }

            if (rujukanLower.includes('positive') || rujukanLower.includes('positif')) {
                if (hasilLower.includes('positive') || hasilLower.includes('positif') ||
                    hasilLower.includes('reactive') || hasilLower.includes('reaktif')) {
                    return '-';
                } else {
                    return 'L';
                }
            }

            return '-';
        }
        // Function untuk memproses queue
        // Function untuk memproses queue
        function processQueue() {
            if (ajaxQueue.length === 0 || isProcessingQueue) {
                return;
            }

            isProcessingQueue = true;
            const request = ajaxQueue.shift();

            if (!request.$element || !request.$element.length) {
                isProcessingQueue = false;
                processQueue();
                return;
            }

            // Data untuk dikirim - SELALU gunakan keterangan dari client
            // Di fungsi processQueue(), pastikan keterangan selalu dikirim:
            const postData = {
                _token: csrfToken,
                type: request.type,
                id: request.id,
                field: request.field,
                value: request.value,
                keterangan: request.keterangan // SELALU sertakan
            };

            console.log('processQueue - Mengirim data:', postData);
            console.log('Keterangan client:', request.keterangan);

            $.ajax({
                url: '{{ route("hasil-lab.update-field-ajax") }}',
                method: 'POST',
                data: postData,
                beforeSend: function() {
                    request.$element.addClass('is-changing');
                },
                success: function(response) {
                    console.log('✅ Response dari server:', response);

                    if (response.success) {
                        // Pastikan keterangan di response sama dengan yang dikirim
                        if (response.data.keterangan !== request.keterangan) {
                            console.warn('⚠️ PERHATIAN: Keterangan di response berbeda!', {
                                dikirim: request.keterangan,
                                diterima: response.data.keterangan
                            });
                        }

                        // Update UI dengan response dari server (harus sama)
                        request.$element.removeClass('is-changing').addClass('is-changed');
                        request.$element.data('original', request.value);

                        // Log untuk debugging
                        console.log('📝 Database menyimpan keterangan:', response.data.keterangan);

                        // Tampilkan toast dengan info keterangan
                        if (typeof window.showToast === 'function') {
                            let msg = 'Data berhasil disimpan';
                            if (response.data.keterangan === 'CH') msg += ' (Critical High)';
                            else if (response.data.keterangan === 'CL') msg += ' (Critical Low)';
                            else if (response.data.keterangan === 'H') msg += ' (High)';
                            else if (response.data.keterangan === 'L') msg += ' (Low)';
                            window.showToast('success', msg);
                        }
                    }
                },
                error: function(xhr) {
                    request.$element.removeClass('is-changing').addClass('has-error');
                    console.error('Update error:', xhr.responseText);

                    let errorMessage = 'Gagal menyimpan perubahan';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }

                    if (xhr.status === 419) {
                        errorMessage = 'Session expired. Silakan refresh halaman.';
                    }

                    showToast('danger', errorMessage);
                },
                complete: function() {
                    isProcessingQueue = false;
                    setTimeout(processQueue, 100);
                }
            });
        }

        // Event handler untuk input hasil
        $('.hasil-input').on('input', function() {
            const $input = $(this);
            const id = $input.data('id');
            const type = $input.data('type');
            const value = $input.val();

            console.log('====== INPUT BERUBAH ======');
            console.log('ID:', id, 'Type:', type);
            console.log('Nilai baru:', value);

            // Ambil hidden input sebelum update
            const $hiddenBefore = $input.closest('tr').find('input[name*="[keterangan]"]');
            console.log('Keterangan sebelum updateKeteranganClientSide:', $hiddenBefore.val());

            // Hitung keterangan berdasarkan rujukan dari data_pemeriksaan
            updateKeteranganClientSide($input);

            // Ambil hidden input setelah update
            const $hiddenAfter = $input.closest('tr').find('input[name*="[keterangan]"]');
            console.log('Keterangan setelah updateKeteranganClientSide:', $hiddenAfter.val());

            // Debounce untuk AJAX call
            clearTimeout($input.data('timer'));
            $input.data('timer', setTimeout(() => {
                if (id) {
                    addToQueue(type, id, 'hasil_pengujian', value, $input);
                }
            }, 800));
        });

        // Initialize Excel navigation
        function initExcelNavigation() {
            // Select all inputs on focus
            $('.excel-input').on('focus', function() {
                $(this).select();
                $(this).closest('td').addClass('table-warning');
            });

            // Remove highlight on blur
            $('.excel-input').on('blur', function() {
                $(this).closest('td').removeClass('table-warning');
            });

            // Excel-like keyboard navigation
            $('.excel-input').on('keydown', function(e) {
                const $current = $(this);
                const $row = $current.closest('tr');
                const $cell = $current.closest('td');
                const cellIndex = $cell.index();
                const $rows = $row.closest('tbody').find('tr');
                const rowIndex = $rows.index($row);

                switch (e.key) {
                    case 'Enter':
                        e.preventDefault();
                        const $nextRow = $rows.eq(rowIndex + 1);
                        if ($nextRow.length) {
                            const $nextInput = $nextRow.find('td').eq(cellIndex).find('.excel-input');
                            if ($nextInput.length) {
                                $nextInput.focus().select();
                            }
                        }
                        break;

                    case 'ArrowDown':
                        e.preventDefault();
                        const $downRow = $rows.eq(rowIndex + 1);
                        if ($downRow.length) {
                            const $downInput = $downRow.find('td').eq(cellIndex).find('.excel-input');
                            if ($downInput.length) {
                                $downInput.focus().select();
                            }
                        }
                        break;

                    case 'ArrowUp':
                        e.preventDefault();
                        const $upRow = $rows.eq(rowIndex - 1);
                        if ($upRow.length) {
                            const $upInput = $upRow.find('td').eq(cellIndex).find('.excel-input');
                            if ($upInput.length) {
                                $upInput.focus().select();
                            }
                        }
                        break;

                    case 'ArrowRight':
                        e.preventDefault();
                        const $nextCell = $row.find('td').eq(cellIndex + 1).find('.excel-input');
                        if ($nextCell.length) {
                            $nextCell.focus().select();
                        }
                        break;

                    case 'ArrowLeft':
                        e.preventDefault();
                        const $prevCell = $row.find('td').eq(cellIndex - 1).find('.excel-input');
                        if ($prevCell.length) {
                            $prevCell.focus().select();
                        }
                        break;
                }
            });

            // Double click to clear
            $('.excel-input').on('dblclick', function() {
                $(this).val('').trigger('input');
            });
        }

        // Update save status
        function updateSaveStatus() {
            const $status = $('#saveStatus');
            const changedCount = $('.is-changed').length;

            if (changedCount > 0) {
                $status.removeClass('bg-secondary').addClass('bg-warning');
                $status.html(`<i class="ri-edit-line me-1"></i>${changedCount} perubahan`);
            } else {
                $status.removeClass('bg-warning').addClass('bg-secondary');
                $status.html('<i class="ri-check-line me-1"></i>Tersimpan');
            }
        }

        // Function untuk update UI setelah validasi
        function updateValidatorUI(pemeriksaData, validatedAt) {
            const $select = $('#pemeriksaSelect');
            const $btn = $('#savePemeriksaBtn');
            const $validatorInfo = $('#validatorInfo');

            if (pemeriksaData) {
                // Update info
                $validatorInfo.html(`
            <small class="text-success">
                <i class="ri-checkbox-circle-line me-1"></i>
                Sudah divalidasi oleh:
                <strong>${pemeriksaData.nama_pemeriksa}</strong>
                <br>
                <small class="text-muted">
                    Pada: ${validatedAt}
                </small>
            </small>
        `);

                // Tampilkan tombol edit
                $btn.html('<i class="ri-edit-line"></i>');
                $btn.removeClass('btn-outline-primary').addClass('btn-outline-warning');
                $btn.prop('disabled', false);

                // Tampilkan opsi edit di select
                $select.prop('disabled', false);

                showToast('success', 'Validator berhasil diperbarui');
            }
        }
        // Load pemeriksa data
        function loadPemeriksaData() {
            $.ajax({
                url: '{{ route("pasien.pemeriksa", ["pasien" => $pasien->no_lab]) }}',
                method: 'GET',
                success: function(response) {
                    const $select = $('#pemeriksaSelect');
                    const currentValue = '{{ $pasien->id_pemeriksa ?? "" }}';

                    $select.empty().append('<option value="">-- Pilih Validator --</option>');

                    response.forEach(function(pemeriksa) {
                        $select.append(new Option(
                            `${pemeriksa.nama_pemeriksa}`,
                            pemeriksa.id_pemeriksa
                        ));
                    });

                    if (currentValue) {
                        $select.val(currentValue);
                        // Jika sudah ada pemeriksa, set tombol ke mode edit
                        $('#savePemeriksaBtn').html('<i class="ri-edit-line"></i>');
                        $('#savePemeriksaBtn').removeClass('btn-outline-primary').addClass('btn-outline-warning');
                    }
                },
                error: function(xhr) {
                    console.error('Failed to load pemeriksa data:', xhr);
                    showToast('danger', 'Gagal memuat data pemeriksa');
                }
            });
        }

        // Validate before print
        function validateBeforePrint() {
            const $select = $('#pemeriksaSelect');
            const hasPemeriksa = $select.val();

            if (!hasPemeriksa) {
                const result = confirm(
                    'Pasien belum divalidasi oleh pemeriksa. ' +
                    'Lanjutkan print tanpa validasi?'
                );

                if (!result) {
                    $select.focus();
                    return false;
                }
            }

            return true;
        }

        // Save all changes
        $('#saveAllBtn').on('click', function() {
            const $btn = $(this);
            const originalText = $btn.html();

            $btn.prop('disabled', true).html('<i class="ri-loader-4-line spin me-1"></i>Menyimpan...');

            const formData = new FormData($('#hasilLabForm')[0]);

            $.ajax({
                url: $('#hasilLabForm').attr('action'),
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        $('.is-changed').each(function() {
                            $(this).removeClass('is-changed');
                            $(this).data('original', $(this).val());
                        });

                        if (response.updated_at) {
                            $('#lastSaved').text(`Terakhir disimpan: ${response.updated_at}`);
                        }

                        pendingChanges = false;
                        updateSaveStatus();
                        showToast('success', 'Semua data berhasil disimpan');
                    }
                },
                error: function(xhr) {
                    console.error('Save all error:', xhr.responseText);

                    let errorMessage = 'Gagal menyimpan semua data';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }

                    if (xhr.status === 419) {
                        errorMessage = 'Session expired. Silakan refresh halaman.';
                    }

                    showToast('danger', errorMessage);
                },
                complete: function() {
                    $btn.prop('disabled', false).html(originalText);
                }
            });
        });

        // Print button click handler
        $('#printBtn').on('click', function(e) {
            if (!validateBeforePrint()) {
                e.preventDefault();
            }
        });

        // Save pemeriksa button click handler
        $('#savePemeriksaBtn').on('click', function() {
            const $btn = $(this);
            const $select = $('#pemeriksaSelect');
            const pemeriksaId = $select.val();
            const currentPemeriksaId = '{{ $pasien->id_pemeriksa ?? "" }}';

            if (!pemeriksaId) {
                showToast('warning', 'Silakan pilih pemeriksa terlebih dahulu');
                $select.focus();
                return;
            }

            // Jika sudah ada pemeriksa dan sama dengan yang dipilih, toggle edit mode
            if (currentPemeriksaId && currentPemeriksaId === pemeriksaId.toString() &&
                $btn.find('i').hasClass('ri-edit-line')) {
                // Mode edit - enable select dan ganti tombol
                $select.prop('disabled', false);
                $btn.html('<i class="ri-save-line"></i>');
                $btn.removeClass('btn-outline-warning').addClass('btn-outline-primary');
                return;
            }

            $btn.prop('disabled', true).html('<i class="ri-loader-4-line spin"></i>');

            $.ajax({
                url: '{{ route("pasien.update.data.validator", $pasien->no_lab) }}',
                method: 'POST',
                data: {
                    _token: csrfToken,
                    id_pemeriksa: pemeriksaId
                },
                success: function(response) {
                    if (response.success) {
                        // Update UI dengan data baru
                        updateValidatorUI(response.pemeriksa, response.validated_at);

                        // Update select dengan data baru
                        $select.val(pemeriksaId);

                        // Jika sudah ada pemeriksa sebelumnya, ganti mode tombol ke edit
                        if (currentPemeriksaId) {
                            $btn.html('<i class="ri-edit-line"></i>');
                            $btn.removeClass('btn-outline-primary').addClass('btn-outline-warning');
                        } else {
                            $btn.html('<i class="ri-edit-line"></i>');
                            $btn.removeClass('btn-outline-primary').addClass('btn-outline-warning');
                        }

                        $btn.prop('disabled', false);
                    }
                },
                error: function(xhr) {
                    console.error('Failed to save pemeriksa:', xhr);
                    let errorMessage = 'Gagal menyimpan data pemeriksa';

                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }

                    showToast('danger', errorMessage);
                    $btn.prop('disabled', false).html('<i class="ri-save-line"></i>');
                }
            });
        });

        // Refresh button
        $('#refreshBtn').on('click', function() {
            if ($('.is-changed').length > 0) {
                if (!confirm('Ada perubahan yang belum disimpan. Yakin ingin me-refresh halaman?')) {
                    return;
                }
            }
            window.location.reload();
        });

        // Warn before leaving page with unsaved changes
        $(window).on('beforeunload', function() {
            if ($('.is-changed').length > 0) {
                return 'Ada perubahan yang belum disimpan. Yakin ingin meninggalkan halaman?';
            }
        });

        // Form submit handler
        $('#hasilLabForm').on('submit', function(e) {
            e.preventDefault();
            $('#saveAllBtn').click();
        });

        // Initialize
        initExcelNavigation();

        // Load pemeriksa data on page load
        loadPemeriksaData();

        // INISIALISASI DATA RUJUKAN DARI data_pemeriksaan SAAT PAGE LOAD
        // INISIALISASI DATA RUJUKAN, CH, DAN CL SAAT PAGE LOAD
        console.log('Initializing rujukan, CH, CL data from data_pemeriksaan...');
        $('.hasil-input').each(function() {
            const $input = $(this);
            const $row = $input.closest('tr');
            const type = $input.data('type');

            // Ambil rujukan dari rujukan-display
            let rujukanDisplay = $row.find('.rujukan-display').text().trim();
            let chDisplay = $row.find('.ch-cell').text().trim();
            let clDisplay = $row.find('.cl-cell').text().trim();

            // Set data attributes
            $input.data('rujukan', rujukanDisplay);
            $input.attr('data-rujukan', rujukanDisplay);

            $input.data('ch', chDisplay);
            $input.attr('data-ch', chDisplay);

            $input.data('cl', clDisplay);
            $input.attr('data-cl', clDisplay);

            console.log('Initialized data for input:', {
                id: $input.data('id'),
                type: type,
                rujukan: rujukanDisplay,
                ch: chDisplay,
                cl: clDisplay
            });
        });

        // Auto-focus first input
        if ($('.hasil-input').length > 0) {
            $('.hasil-input').first().focus();
        }
    });
</script>

<!-- Script untuk menambah row kimia secara dinamis -->
<script>
    $(document).ready(function() {
        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        // ==============================
        // TOMBOL TAMBAH ROW KIMIA
        // ==============================
        $('#tambahRowKimiaBtn').on('click', function() {
            const $table = $('#kimiaTable tbody');
            const currentRowCount = $table.find('tr').length;
            const newIndex = currentRowCount;
            const manualId = 'manual_kimia_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);

            // Row baru
            const newRow = `
                <tr data-index="${newIndex}" data-kimia-id="${manualId}" class="table-warning kimia-row">
                    <td class="bg-light">
                        <input type="text"
                            class="form-control form-control-sm analysis-input"
                            name="kimia[${newIndex}][analysis]"
                            placeholder="Dari Alat"
                            value=""
                            autocomplete="off">
                    </td>
                    <td class="search-cell">
                        <div class="position-relative">
                            <input type="text"
                                class="form-control form-control-sm kode-search-input"
                                placeholder="Cari data pemeriksaan..."
                                data-kimia-id="${manualId}"
                                data-index="${newIndex}"
                                autocomplete="off">
                            <div class="kode-search-results dropdown-menu w-100"
                                style="display: none; max-height: 200px; overflow-y: auto;">
                            </div>
                            <input type="hidden"
                                name="kimia[${newIndex}][id]"
                                value="${manualId}">
                            <input type="hidden"
                                name="kimia[${newIndex}][id_data_pemeriksaan]"
                                class="kode-pemeriksaan-input"
                                value="">
                            <div class="mt-1 status-mapping">
                                <small class="text-warning">
                                    <i class="ri-alert-line me-1"></i>Belum dipetakan
                                </small>
                            </div>
                        </div>
                    </td>
                    <td class="hasil-cell">
                        <input type="text"
                            name="kimia[${newIndex}][hasil_pengujian]"
                            class="form-control form-control-sm hasil-input"
                            value=""
                            placeholder="Hasil"
                            data-id="${manualId}"
                            data-type="kimia"
                            data-id-data-pemeriksaan=""
                            data-jenis=""
                            data-rujukan=""
                            data-ch=""
                            data-cl=""
                            data-umur="{{ $data["umur_format"] }}"
                            data-jenis-kelamin="{{ $pasien->jenis_kelamin }}"
                            autocomplete="off">
                    </td>
                    <td class="bg-light rujukan-cell" style="text-align:center;">
                        <span class="rujukan-display">-</span>
                    </td>
                    <td class="bg-light ch-cell" style="text-align:center;">
                        <span class="ch-display">-</span>
                    </td>
                    <td class="bg-light cl-cell" style="text-align:center;">
                        <span class="cl-display">-</span>
                    </td>
                    <td class="keterangan-cell">
                        <div class="keterangan-display bg-success bg-opacity-10 text-success rounded py-1 px-2 text-center"
                            data-keterangan="-">
                            <strong>-</strong>
                        </div>
                        <input type="hidden"
                            name="kimia[${newIndex}][keterangan]"
                            class="keterangan-input"
                            value="-">
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-outline-danger hapus-row-kimia-btn">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </td>
                </tr>
            `;

            $table.append(newRow);

            // Focus ke input search
            setTimeout(() => {
                $table.find('tr:last-child .kode-search-input').focus();
            }, 100);

            console.log('Row kimia baru ditambahkan:', manualId);
        });

        // ==============================
        // SEARCH DATA PEMERIKSAAN
        // ==============================
        $(document).on('input', '.kode-search-input', function() {
            const $input = $(this);
            const searchTerm = $input.val();
            const $results = $input.siblings('.kode-search-results');

            if (searchTerm.length < 2) {
                $results.hide().empty();
                return;
            }

            clearTimeout($input.data('searchTimer'));
            $input.data('searchTimer', setTimeout(() => {
                $.ajax({
                    url: '{{ route("hasil-lain.search-kode-pemeriksaan") }}',
                    method: 'GET',
                    data: {
                        search: searchTerm,
                        tipe: 'kimia'
                    },
                    beforeSend: function() {
                        $results.html('<div class="dropdown-item text-center py-2"><i class="ri-loader-4-line spin"></i> Mencari...</div>').show();
                    },
                    success: function(response) {
                        if (response.success && response.data.length > 0) {
                            let html = '';
                            response.data.forEach(function(item) {
                                html += `<a href="#" class="dropdown-item p-2 kode-search-item"
                                    data-id="${item.id_data_pemeriksaan || item.id}"
                                    data-nama="${item.data_pemeriksaan || item.nama}"
                                    data-rujukan="${item.rujukan || '-'}"
                                    data-ch="${item.ch || '-'}"
                                    data-cl="${item.cl || '-'}"
                                    data-satuan="${item.satuan || '-'}"
                                    data-metode="${item.metode || '-'}">
                                    <div><strong>${item.data_pemeriksaan || item.nama}</strong></div>
                                    <small class="text-muted">${item.rujukan || 'No reference'}</small>
                                    </a>`;
                            });
                            $results.html(html).show();
                        } else {
                            $results.html('<div class="dropdown-item text-center py-2 text-muted">Tidak ditemukan</div>').show();
                        }
                    },
                    error: function() {
                        $results.html('<div class="dropdown-item text-center py-2 text-danger">Error loading data</div>').show();
                    }
                });
            }, 300));
        });

        // ==============================
        // KLIK ITEM SEARCH
        // ==============================
        $(document).on('click', '.kode-search-item', function(e) {
            e.preventDefault();

            const $item = $(this);
            const idDataPemeriksaan = $item.data('id');
            const namaPemeriksaan = $item.data('nama');
            const rujukan = $item.data('rujukan');
            const ch = $item.data('ch');
            const cl = $item.data('cl');
            const satuan = $item.data('satuan');
            const metode = $item.data('metode');

            const $row = $(this).closest('tr');
            const $searchInput = $row.find('.kode-search-input');
            const $analysisInput = $row.find('.analysis-input');
            const $hasilInput = $row.find('.hasil-input');
            const currentId = $row.data('kimia-id'); // ini adalah id_pemeriksaan_kimia
            const index = $row.data('index');

            // 1. Update UI
            if (!$analysisInput.val()) {
                $analysisInput.val(namaPemeriksaan);
            }

            $searchInput.val(namaPemeriksaan);
            $row.find('.kode-pemeriksaan-input').val(idDataPemeriksaan);

            $row.find('.status-mapping').html(
                `<small class="text-success">
                    <i class="ri-links-line me-1"></i> ${namaPemeriksaan}
                </small>`
            );

            $hasilInput
                .attr('data-id-data-pemeriksaan', idDataPemeriksaan)
                .attr('data-jenis', namaPemeriksaan)
                .attr('data-rujukan', rujukan)
                .attr('data-ch', ch)
                .attr('data-cl', cl)
                .data('id-data-pemeriksaan', idDataPemeriksaan)
                .data('rujukan', rujukan)
                .data('ch', ch)
                .data('cl', cl);

            $row.find('.rujukan-display').text(rujukan);
            $row.find('.ch-display').text(ch);
            $row.find('.cl-display').text(cl);

            // Hide dropdown
            $row.find('.kode-search-results').hide().empty();

            // 2. Tentukan apakah ini row baru atau sudah ada di DB
            const isManualRow = currentId.toString().startsWith('manual_kimia_');

            // Data yang akan dikirim
            const dataToSend = {
                _token: csrfToken,
                id_data_pemeriksaan: idDataPemeriksaan,
                analysis: $analysisInput.val(),
                data_pemeriksaan: namaPemeriksaan,
                satuan: satuan,
                rujukan: rujukan,
                method: metode,
                hasil_pengujian: $hasilInput.val(),
                keterangan: $row.find('.keterangan-input').val(),
                no_lab: '{{ $pasien->no_lab }}',
            };

            let url;

            if (isManualRow) {
                // Row baru: CREATE
                url = '{{ route("kimia.save-manual-row") }}';
                dataToSend.manual_id = currentId; // hanya untuk tracking di frontend
            } else {
                // Row sudah ada: UPDATE - tambahkan id_pemeriksaan_kimia
                url = '{{ route("kimia.update-row") }}';
                dataToSend.id_pemeriksaan_kimia = currentId; // INI YANG PENTING!
            }

            // 3. Kirim ke server
            $.ajax({
                url: url,
                method: 'POST', // selalu POST
                data: dataToSend,
                beforeSend: function() {
                    $row.addClass('table-warning');
                },
                success: function(response) {
                    if (response.success) {
                        // Jika ini row baru, update ID-nya dari manual ke database ID
                        if (isManualRow && response.id_pemeriksaan_kimia) {
                            const newId = response.id_pemeriksaan_kimia;
                            $row.attr('data-kimia-id', newId);
                            $row.data('kimia-id', newId);
                            $hasilInput.attr('data-id', newId);
                            $hasilInput.data('id', newId);

                            // Update hidden input jika ada
                            $row.find('input[name*="[id]"]').val(newId);
                        }

                        $row.removeClass('table-warning').addClass('table-success');

                        // Ambil rujukan berdasarkan kondisi
                        fetchRujukanByKondisiKimia(idDataPemeriksaan, $row, $hasilInput).then(() => {
                            if ($hasilInput.val()) {
                                updateKimiaKeterangan($hasilInput);
                            }
                        });

                        // Toast sukses
                        if (typeof window.showToast === 'function') {
                            window.showToast('success', 'Data kimia berhasil disimpan: ' + namaPemeriksaan);
                        }

                        setTimeout(() => {
                            $row.removeClass('table-success');
                        }, 2000);
                    } else {
                        if (typeof window.showToast === 'function') {
                            window.showToast('danger', response.message || 'Gagal menyimpan data');
                        }
                        $row.removeClass('table-warning');
                    }
                },
                error: function(xhr) {
                    console.error('Save error:', xhr);
                    $row.removeClass('table-warning');

                    let errorMessage = 'Gagal menyimpan data';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }

                    if (typeof window.showToast === 'function') {
                        window.showToast('danger', errorMessage);
                    }
                }
            });

            // Focus ke input hasil
            setTimeout(() => {
                $hasilInput.focus();
            }, 100);
        });

        // Fungsi untuk menyimpan row ke database (tanpa mapping)
        function saveKimiaRowToDatabase(manualId, idDataPemeriksaan, namaPemeriksaan, $row, $analysisInput, $hasilInput, rujukan, satuan, metode) {
            const isManualRow = manualId.toString().startsWith('manual_kimia_');

            $.ajax({
                url: isManualRow
                    ? '{{ route("kimia.save-manual-row") }}'
                    : '{{ route("kimia.update-row") }}',
                method: 'POST',
                data: {
                    _token: csrfToken,
                    manual_id: isManualRow ? manualId : undefined,
                    id_pemeriksaan_kimia: isManualRow ? undefined : manualId,
                    id_data_pemeriksaan: idDataPemeriksaan,
                    analysis: $analysisInput.val(),
                    satuan: satuan,
                    rujukan: rujukan,
                    method: metode,
                    hasil_pengujian: $hasilInput.val(),
                    keterangan: $row.find('.keterangan-input').val(),
                    no_lab: '{{ $pasien->no_lab }}',
                },
                beforeSend: function() {
                    $row.addClass('table-warning');
                },
                success: function(response) {
                    if (response.success) {
                        // Update ID jika ini row baru
                        if (isManualRow && response.id_pemeriksaan_kimia) {
                            const newId = response.id_pemeriksaan_kimia;
                            $row.attr('data-kimia-id', newId);
                            $row.data('kimia-id', newId);
                            $hasilInput.attr('data-id', newId);
                            $hasilInput.data('id', newId);
                        }

                        $row.removeClass('table-warning').addClass('table-success');

                        // Ambil rujukan berdasarkan kondisi
                        fetchRujukanByKondisiKimia(idDataPemeriksaan, $row, $hasilInput).then(() => {
                            if ($hasilInput.val()) {
                                updateKimiaKeterangan($hasilInput);
                            }
                        });

                        // Toast sukses
                        if (typeof window.showToast === 'function') {
                            window.showToast('success', 'Data kimia berhasil disimpan: ' + namaPemeriksaan);
                        }

                        setTimeout(() => {
                            $row.removeClass('table-success');
                        }, 2000);
                    } else {
                        if (typeof window.showToast === 'function') {
                            window.showToast('danger', response.message || 'Gagal menyimpan data');
                        }
                        $row.removeClass('table-warning');
                    }
                },
                error: function(xhr) {
                    console.error('Save error:', xhr);
                    $row.removeClass('table-warning');

                    let errorMessage = 'Gagal menyimpan data';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }

                    if (typeof window.showToast === 'function') {
                        window.showToast('danger', errorMessage);
                    }
                }
            });
        }

        // ==============================
        // FETCH RUJUKAN BY KONDISI (KIMIA)
        // ==============================

        async function fetchRujukanByKondisiKimia(idDataPemeriksaan, $row, $hasilInput) {
            if (!idDataPemeriksaan) return null;

            const jenisKelamin = $hasilInput.data('jenis-kelamin') || '{{ $pasien->jenis_kelamin }}';
            const umurPasien = $hasilInput.data('umur') || '{{ $data["umur_format"] ?? "" }}';
            const kimiaId = $row.data('kimia-id');

            try {
                const response = await $.ajax({
                    url: '{{ route("pasien.get-rujukan-by-kondisi-batch") }}',
                    method: 'POST',
                    data: {
                        items: [{
                            id_data_pemeriksaan: idDataPemeriksaan,
                            jenis_kelamin: jenisKelamin,
                            umur_pasien: umurPasien,
                            client_key: idDataPemeriksaan + '_' + kimiaId
                        }],
                        no_cache: true
                    }
                });

                if (response.success && response.data[idDataPemeriksaan + '_' + kimiaId]) {
                    const rujukanData = response.data[idDataPemeriksaan + '_' + kimiaId].data;

                    // Update UI dengan data rujukan by kondisi
                    let rujukanText = rujukanData.rujukan || '-';

                    if (rujukanData.is_from_detail) {
                        rujukanText += ' <span class="badge bg-info ms-1" title="CH/CL khusus kondisi">K</span>';
                        $row.addClass('table-info');
                    } else {
                        $row.removeClass('table-info');
                    }

                    $row.find('.rujukan-display').html(rujukanText);
                    $row.find('.ch-display').text(rujukanData.ch || '-');
                    $row.find('.cl-display').text(rujukanData.cl || '-');

                    // Update data attributes untuk perhitungan
                    $hasilInput
                        .data('rujukan', rujukanData.rujukan || '')
                        .data('ch', rujukanData.ch || '')
                        .data('cl', rujukanData.cl || '')
                        .attr('data-rujukan', rujukanData.rujukan || '')
                        .attr('data-ch', rujukanData.ch || '')
                        .attr('data-cl', rujukanData.cl || '');

                    return rujukanData;
                }
            } catch (error) {
                console.error('Error mendapatkan rujukan berdasarkan kondisi (Kimia):', error);
            }

            return null;
        }

        async function updateKimiaKeterangan($input) {
            const hasil = $input.val().trim();
            const $row = $input.closest('tr');
            const $keteranganDisplay = $row.find('.keterangan-display');
            const $hiddenInput = $row.find('input[name*="[keterangan]"]');

            console.log('=== KIMIA - updateKeterangan ===');
            console.log('Hasil:', hasil);

            // Clear jika kosong
            if (!hasil) {
                console.log('Hasil kosong, reset keterangan');
                updateKeteranganDisplay($keteranganDisplay, '');
                $hiddenInput.val('');
                return;
            }

            // Dapatkan data
            const idDataPemeriksaan = $input.data('id-data-pemeriksaan');

            // Gunakan data yang sudah ada di input
            let rujukan = $input.data('rujukan') || '';
            let ch = $input.data('ch') || '';
            let cl = $input.data('cl') || '';

            console.log('Data awal dari input:', { idDataPemeriksaan, rujukan, ch, cl });

            // Jika ada ID data pemeriksaan, ambil data rujukan berdasarkan kondisi
            if (idDataPemeriksaan) {
                try {
                    console.log('Mengambil rujukan berdasarkan kondisi...');
                    const rujukanData = await fetchRujukanByKondisiKimia(idDataPemeriksaan, $row, $input);

                    if (rujukanData) {
                        console.log('Data rujukan ditemukan:', rujukanData);

                        // Update data lokal dengan data dari kondisi
                        rujukan = rujukanData.rujukan || rujukan;
                        ch = rujukanData.ch || ch;
                        cl = rujukanData.cl || cl;

                        console.log('Data setelah update dari kondisi:', { rujukan, ch, cl });

                        // Jangan lupa update data pada input untuk penggunaan selanjutnya
                        $input
                            .data('rujukan', rujukan)
                            .data('ch', ch)
                            .data('cl', cl);
                    } else {
                        console.log('Tidak ada data rujukan dari kondisi');
                    }
                } catch (error) {
                    console.error('Error mendapatkan rujukan berdasarkan kondisi:', error);
                }
            }

            // Gunakan data yang sudah diupdate untuk perhitungan
            calculateAndUpdateKeteranganKimia($input, $keteranganDisplay, $hiddenInput, hasil, rujukan, ch, cl);
        }

        function calculateAndUpdateKeteranganKimia($input, $keteranganDisplay, $hiddenInput, hasil, rujukan, ch, cl) {
            console.log('Kimia - Perhitungan dengan:', {
                hasil,
                rujukan: rujukan || '(kosong)',
                ch: ch || '(kosong)',
                cl: cl || '(kosong)'
            });

            // Validasi input
            if (!hasil || hasil.trim() === '') {
                console.log('Hasil kosong');
                updateKeteranganDisplay($keteranganDisplay, '');
                $hiddenInput.val('');
                return;
            }

            const hasilStr = hasil.toString().trim();
            const hasilNum = parseFloat(hasilStr.replace(',', '.'));

            // Jika rujukan tidak tersedia
            if (!rujukan || rujukan === '' || rujukan === '-' || rujukan === 'null') {
                console.log('Rujukan tidak tersedia');

                // Coba gunakan CH/CL jika ada
                if (ch && ch !== '' && ch !== '-' && ch !== 'null' &&
                    cl && cl !== '' && cl !== '-' && cl !== 'null') {

                    const chNum = parseFloat(ch.toString().replace(',', '.'));
                    const clNum = parseFloat(cl.toString().replace(',', '.'));

                    console.log('Menggunakan CH/CL:', { chNum, clNum, hasilNum });

                    if (!isNaN(chNum) && !isNaN(clNum) && !isNaN(hasilNum)) {
                        if (hasilNum > chNum) {
                            console.log(`CH dari data CH/CL: ${hasilNum} > ${chNum}`);
                            updateKeteranganDisplay($keteranganDisplay, 'CH');
                            $hiddenInput.val('CH');
                            return;
                        } else if (hasilNum < clNum) {
                            console.log(`CL dari data CH/CL: ${hasilNum} < ${clNum}`);
                            updateKeteranganDisplay($keteranganDisplay, 'CL');
                            $hiddenInput.val('CL');
                            return;
                        }
                    }
                }

                console.log('Tidak ada data untuk perhitungan');
                updateKeteranganDisplay($keteranganDisplay, '-');
                $hiddenInput.val('-');
                return;
            }

            const rujukanStr = rujukan.toString().trim();
            const chStr = ch ? ch.toString().trim() : '';
            const clStr = cl ? cl.toString().trim() : '';

            // 1. CEK CRITICAL HIGH/LOW (CH/CL) - PRIORITAS TERTINGGI
            console.log('Cek CH/CL:', { chStr, clStr, hasilNum });

            if (chStr && chStr !== '' && chStr !== '-' && chStr !== 'null') {
                let chNum;
                if (chStr.includes('>')) {
                    chNum = parseFloat(chStr.replace('>', '').replace(',', '.').trim());
                } else {
                    chNum = parseFloat(chStr.replace(',', '.'));
                }

                console.log('Parsed CH:', chNum);

                if (!isNaN(chNum) && !isNaN(hasilNum) && hasilNum > chNum) {
                    console.log(`✅ KIMIA CH DETECTED: ${hasilNum} > ${chNum}`);
                    updateKeteranganDisplay($keteranganDisplay, 'CH');
                    $hiddenInput.val('CH');
                    return;
                }
            }

            if (clStr && clStr !== '' && clStr !== '-' && clStr !== 'null') {
                let clNum;
                if (clStr.includes('<')) {
                    clNum = parseFloat(clStr.replace('<', '').replace(',', '.').trim());
                } else {
                    clNum = parseFloat(clStr.replace(',', '.'));
                }

                console.log('Parsed CL:', clNum);

                if (!isNaN(clNum) && !isNaN(hasilNum) && hasilNum < clNum) {
                    console.log(`✅ KIMIA CL DETECTED: ${hasilNum} < ${clNum}`);
                    updateKeteranganDisplay($keteranganDisplay, 'CL');
                    $hiddenInput.val('CL');
                    return;
                }
            }

            // 2. CEK HASIL KUALITATIF (NON-NUMERIC)
            if (isNaN(hasilNum)) {
                console.log('Hasil non-numerik, cek kualitatif');
                const hasilLower = hasilStr.toLowerCase();
                const rujukanLower = rujukanStr.toLowerCase();

                console.log('Kualitatif:', { hasilLower, rujukanLower });

                if (rujukanLower.includes('negative') || rujukanLower.includes('negatif')) {
                    if (hasilLower.includes('negative') || hasilLower.includes('negatif') ||
                        hasilLower.includes('non-reactive') || hasilLower.includes('nonreactive')) {
                        updateKeteranganDisplay($keteranganDisplay, '-');
                        $hiddenInput.val('-');
                    } else {
                        updateKeteranganDisplay($keteranganDisplay, 'H');
                        $hiddenInput.val('H');
                    }
                    return;
                } else if (rujukanLower.includes('positive') || rujukanLower.includes('positif')) {
                    if (hasilLower.includes('positive') || hasilLower.includes('positif') ||
                        hasilLower.includes('reactive') || hasilLower.includes('reaktif')) {
                        updateKeteranganDisplay($keteranganDisplay, '-');
                        $hiddenInput.val('-');
                    } else {
                        updateKeteranganDisplay($keteranganDisplay, 'L');
                        $hiddenInput.val('L');
                    }
                    return;
                }

                // Default untuk non-numerik
                updateKeteranganDisplay($keteranganDisplay, '-');
                $hiddenInput.val('-');
                return;
            }

            // 3. CEK RUJUKAN NUMERIK
            console.log('Cek rujukan numerik:', rujukanStr);

            // Format range: "1 - 90" atau "1-90"
            if (rujukanStr.includes('-') && !rujukanStr.includes('<') && !rujukanStr.includes('>')) {
                // Bersihkan whitespace
                const cleanStr = rujukanStr.replace(/\s+/g, '');
                const parts = cleanStr.split('-');

                console.log('Range parts:', parts);

                if (parts.length === 2) {
                    const min = parseFloat(parts[0].replace(',', '.'));
                    const max = parseFloat(parts[1].replace(',', '.'));

                    console.log('Parsed range:', { min, max, hasilNum });

                    if (!isNaN(min) && !isNaN(max)) {
                        if (hasilNum < min) {
                            console.log(`✅ KIMIA L DETECTED: ${hasilNum} < ${min}`);
                            updateKeteranganDisplay($keteranganDisplay, 'L');
                            $hiddenInput.val('L');
                        } else if (hasilNum > max) {
                            console.log(`✅ KIMIA H DETECTED: ${hasilNum} > ${max}`);
                            updateKeteranganDisplay($keteranganDisplay, 'H');
                            $hiddenInput.val('H');
                        } else {
                            console.log(`✅ KIMIA NORMAL: ${hasilNum} dalam range ${min}-${max}`);
                            updateKeteranganDisplay($keteranganDisplay, '-');
                            $hiddenInput.val('-');
                        }
                        return;
                    }
                }
            }

            // Format: "< X"
            if (rujukanStr.startsWith('<')) {
                const batas = parseFloat(rujukanStr.replace('<', '').replace(',', '.').trim());
                console.log('Parsed < format:', { batas, hasilNum });

                if (!isNaN(batas)) {
                    if (hasilNum >= batas) {
                        console.log(`✅ KIMIA H DETECTED: ${hasilNum} ≥ ${batas}`);
                        updateKeteranganDisplay($keteranganDisplay, 'H');
                        $hiddenInput.val('H');
                    } else {
                        console.log(`✅ KIMIA NORMAL: ${hasilNum} < ${batas}`);
                        updateKeteranganDisplay($keteranganDisplay, '-');
                        $hiddenInput.val('-');
                    }
                    return;
                }
            }

            // Format: "> X"
            if (rujukanStr.startsWith('>')) {
                const batas = parseFloat(rujukanStr.replace('>', '').replace(',', '.').trim());
                console.log('Parsed > format:', { batas, hasilNum });

                if (!isNaN(batas)) {
                    if (hasilNum <= batas) {
                        console.log(`✅ KIMIA L DETECTED: ${hasilNum} ≤ ${batas}`);
                        updateKeteranganDisplay($keteranganDisplay, 'L');
                        $hiddenInput.val('L');
                    } else {
                        console.log(`✅ KIMIA NORMAL: ${hasilNum} > ${batas}`);
                        updateKeteranganDisplay($keteranganDisplay, '-');
                        $hiddenInput.val('-');
                    }
                    return;
                }
            }

            // Format single value: "X"
            const singleValue = parseFloat(rujukanStr.replace(',', '.'));
            if (!isNaN(singleValue)) {
                console.log('Parsed single value:', { singleValue, hasilNum });

                // Untuk single value, cek apakah hasil sama dengan nilai rujukan
                const tolerance = 0.0001; // Toleransi kecil untuk floating point

                if (Math.abs(hasilNum - singleValue) < tolerance) {
                    console.log(`✅ KIMIA NORMAL: ${hasilNum} sama dengan ${singleValue}`);
                    updateKeteranganDisplay($keteranganDisplay, '-');
                    $hiddenInput.val('-');
                } else if (hasilNum < singleValue) {
                    console.log(`✅ KIMIA L DETECTED: ${hasilNum} < ${singleValue}`);
                    updateKeteranganDisplay($keteranganDisplay, 'L');
                    $hiddenInput.val('L');
                } else {
                    console.log(`✅ KIMIA H DETECTED: ${hasilNum} > ${singleValue}`);
                    updateKeteranganDisplay($keteranganDisplay, 'H');
                    $hiddenInput.val('H');
                }
                return;
            }

            // Default - tidak ada pola yang cocok
            console.log('Tidak ada pola rujukan yang cocok');
            updateKeteranganDisplay($keteranganDisplay, '-');
            $hiddenInput.val('-');
        }

        // Fungsi display tetap sama
        function updateKeteranganDisplay($display, keterangan) {
            if (!$display.length) {
                console.error('Display element tidak ditemukan');
                return;
            }

            // Reset semua kelas
            $display.removeClass(
                'bg-danger bg-opacity-10 ' +
                'bg-primary bg-opacity-10 ' +
                'bg-success bg-opacity-10 ' +
                'text-danger text-primary text-success'
            );

            // Set kelas berdasarkan keterangan
            let bgClass = '';
            let textClass = '';
            let displayText = '';

            switch (keterangan) {
                case 'CH':
                case 'H':
                    bgClass = 'bg-danger bg-opacity-10';
                    textClass = 'text-danger';
                    displayText = keterangan;
                    break;

                case 'CL':
                case 'L':
                    bgClass = 'bg-primary bg-opacity-10';
                    textClass = 'text-primary';
                    displayText = keterangan;
                    break;

                case '-':
                    bgClass = 'bg-success bg-opacity-10';
                    textClass = 'text-success';
                    displayText = '';
                    break;

                default:
                    bgClass = 'bg-light';
                    textClass = 'text-muted';
                    displayText = '-';
                    keterangan = '-';
            }

            // Apply classes
            $display.addClass(bgClass + ' ' + textClass);
            $display.html('<strong>' + displayText + '</strong>');
            $display.data('keterangan', keterangan);
        }

        // Event input hasil
        let kimiaSaveTimers = {};

        $(document).on('input', '.hasil-input[data-type="kimia"]', function () {
            const $input = $(this);
            const $row   = $input.closest('tr');

            const kimiaId = String($row.data('kimia-id'));
            const hasil   = $input.val();

            // 1️⃣ HITUNG KETERANGAN (LOGIC LAMA ANDA)
            updateKimiaKeterangan($input);

            // Ambil keterangan terbaru (SETELAH dihitung)
            const keterangan = $row.find('.keterangan-input').val();

            // 2️⃣ STOP JIKA ROW MASIH MANUAL
            if (!kimiaId || kimiaId.startsWith('manual_kimia_')) {
                return;
            }

            // 3️⃣ DEBOUNCE AUTOSAVE
            if (kimiaSaveTimers[kimiaId]) {
                clearTimeout(kimiaSaveTimers[kimiaId]);
            }

            kimiaSaveTimers[kimiaId] = setTimeout(() => {
                $.ajax({
                    url: '{{ route("kimia.update-hasil-realtime") }}',
                    method: 'POST',
                    data: {
                        _token: csrfToken,
                        id_pemeriksaan_kimia: kimiaId,
                        hasil_pengujian: hasil,
                        keterangan: keterangan
                    },
                    success: function (res) {
                        if (res.success) {
                            $row.addClass('table-success');
                            setTimeout(() => {
                                $row.removeClass('table-success');
                            }, 400);
                        }
                    },
                    error: function () {
                        $row.addClass('table-danger');
                    }
                });
            }, 600); // ⏱️ 600ms setelah user berhenti mengetik
        });

        // Event hapus row
        $(document).on('click', '.hapus-row-kimia-btn', function() {
            if (!confirm('Hapus row ini?')) return;

            const $row = $(this).closest('tr');
            const kimiaIdRaw = $row.data('kimia-id');
            const kimiaId = String(kimiaIdRaw);

            // ROW MANUAL (BELUM MASUK DB)
            if (kimiaId.startsWith('manual_kimia_')) {
                $row.remove();
                if (typeof window.showToast === 'function') {
                    window.showToast('warning', 'Row dihapus');
                }
                return;
            }

            // ROW SUDAH ADA DI DATABASE
            $.ajax({
                url: '{{ route("kimia.delete-manual-row", ["id" => "__ID__"]) }}'.replace('__ID__', kimiaId),
                method: 'DELETE',
                data: {
                    _token: csrfToken
                },
                success: function(response) {
                    if (response.status) {
                        $row.remove();
                        if (typeof window.showToast === 'function') {
                            window.showToast('warning', 'Data kimia berhasil dihapus');
                        }
                    } else {
                        if (typeof window.showToast === 'function') {
                            window.showToast('danger', response.message || 'Gagal menghapus data');
                        }
                    }
                },
                error: function() {
                    if (typeof window.showToast === 'function') {
                        window.showToast('danger', 'Terjadi kesalahan saat menghapus');
                    }
                }
            });
        });

        // Sembunyikan dropdown saat klik di luar
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.kode-search-input, .kode-search-results').length) {
                $('.kode-search-results').hide().empty();
            }
        });

        // ==============================
        // EXCEL NAVIGATION
        // ==============================
        function initKimiaExcelNavigation() {
            // Select all inputs on focus
            $('.hasil-input').on('focus', function() {
                $(this).select();
                $(this).closest('td').addClass('table-warning');
            });

            // Remove highlight on blur
            $('.hasil-input').on('blur', function() {
                $(this).closest('td').removeClass('table-warning');
            });

            // Excel-like keyboard navigation
            $('.hasil-input').on('keydown', function(e) {
                const $current = $(this);
                const $row = $current.closest('tr');
                const $cell = $current.closest('td');
                const cellIndex = $cell.index();
                const $rows = $row.closest('tbody').find('tr');
                const rowIndex = $rows.index($row);

                switch (e.key) {
                    case 'Enter':
                    case 'ArrowDown':
                        e.preventDefault();
                        const $nextRow = $rows.eq(rowIndex + 1);
                        if ($nextRow.length) {
                            const $nextInput = $nextRow.find('td').eq(cellIndex).find('.hasil-input');
                            if ($nextInput.length) {
                                $nextInput.focus().select();
                            }
                        }
                        break;

                    case 'ArrowUp':
                        e.preventDefault();
                        const $upRow = $rows.eq(rowIndex - 1);
                        if ($upRow.length) {
                            const $upInput = $upRow.find('td').eq(cellIndex).find('.hasil-input');
                            if ($upInput.length) {
                                $upInput.focus().select();
                            }
                        }
                        break;
                }
            });
        }

        // Initialize Excel navigation
        initKimiaExcelNavigation();
    });
</script>

<!-- Script untuk mendapatkan rujukan berdasarkan kondisi pasien Untuk Kimia -->
<script>
    $(document).ready(function() {
        const csrfToken = $('#csrf_token').val();
        const jenis = $input.data('jenis');
        const type = $input.data('type');

        if (!type || type !== 'kimia' || !jenis) {
            return;
        }

        // ============================================
        // FUNGSI UNTUK MENDAPATKAN RUJUKAN BERDASARKAN KONDISI
        // ============================================

        function getRujukanByKondisi(idDataPemeriksaan, jenisKelamin, umurPasien) {
            // Versi queue/cache — menggantikan Ajax lama
            return new Promise(function(resolve) {
                if (!idDataPemeriksaan) {
                    resolve(null);
                    return;
                }

                // inisialisasi struktur global
                window.__rujukanCache = window.__rujukanCache || {};
                window.__rujukanResolvers = window.__rujukanResolvers || {};

                // Kalau sudah ada di cache, langsung return
                if (Object.prototype.hasOwnProperty.call(window.__rujukanCache, idDataPemeriksaan)) {
                    resolve(window.__rujukanCache[idDataPemeriksaan]);
                    return;
                }

                // Masukkan resolver ke queue — akan dipanggil ketika batch berhasil
                window.__rujukanResolvers[idDataPemeriksaan] = window.__rujukanResolvers[idDataPemeriksaan] || [];
                window.__rujukanResolvers[idDataPemeriksaan].push(resolve);
            });
        }


        // ============================================
        // FUNGSI UNTUK UPDATE KETERANGAN KIMIA DENGAN KONDISI
        // ============================================

        async function updateKimiaKeterangan($input) {
            const hasil = $input.val();
            const $row = $input.closest('tr');
            const $keteranganDisplay = $row.find('.keterangan-display');
            const $hiddenInput = $row.find('input[name*="[keterangan]"]');

            console.log('=== KIMIA - updateKeterangan ===');
            console.log('Hasil:', hasil);

            // Clear jika kosong
            if (!hasil || hasil.trim() === '') {
                console.log('Hasil kosong, reset keterangan');
                updateKeteranganDisplay($keteranganDisplay, '');
                $hiddenInput.val('');
                return;
            }

            // Dapatkan data kondisi pasien
            const idDataPemeriksaan = $input.data('id-data-pemeriksaan');
            const jenisKelamin = $input.data('jenis-kelamin') || '{{ $pasien->jenis_kelamin }}';
            const umurPasien = $input.data('umur') || '{{ $data["umur_format"] ?? "" }}';

            let rujukan = $input.data('rujukan') || $input.attr('data-rujukan') || '';
            let ch = $input.data('ch') || $input.attr('data-ch') || '';
            let cl = $input.data('cl') || $input.attr('data-cl') || '';

            // Jika ada ID data pemeriksaan, coba dapatkan rujukan berdasarkan kondisi
            if (idDataPemeriksaan) {
                try {
                    const rujukanData = await getRujukanByKondisi(idDataPemeriksaan, jenisKelamin, umurPasien);

                    if (rujukanData) {
                        // Update data pada input
                        rujukan = rujukanData.rujukan || rujukan;
                        ch = rujukanData.ch || ch;
                        cl = rujukanData.cl || cl;

                        // Update tampilan di tabel
                        const rujukanDisplay = rujukan +
                            (rujukanData.is_from_detail ?
                                ' <span class="badge bg-info ms-1" title="CH/CL khusus kondisi">K</span>' :
                                '');

                        $row.find('.rujukan-display').html(rujukanDisplay);

                        // // Update CH dengan indikator detail jika ada
                        // const chDisplay = ch || '-';
                        // const chDetail = (rujukanData.is_from_detail && ch !== '-' && ch !== '') ?
                        //     '<br><small class="text-info">detail</small>' : '';
                        // $row.find('.ch-cell').html(chDisplay + chDetail);

                        // // Update CL dengan indikator detail jika ada
                        // const clDisplay = cl || '-';
                        // const clDetail = (rujukanData.is_from_detail && cl !== '-' && cl !== '') ?
                        //     '<br><small class="text-info">detail</small>' : '';
                        // $row.find('.cl-cell').html(clDisplay + clDetail);

                        // Jika rujukan dari detail kondisi, highlight row
                        if (rujukanData.is_from_detail) {
                            $row.addClass('table-info');
                        } else {
                            $row.removeClass('table-info');
                        }

                        // Update data pada input untuk perhitungan selanjutnya
                        $input.data('rujukan', rujukan);
                        $input.data('ch', ch);
                        $input.data('cl', cl);

                        console.log('Rujukan Kimia diupdate berdasarkan kondisi:', rujukanData);
                    }
                } catch (error) {
                    console.error('Error mendapatkan rujukan berdasarkan kondisi (Kimia):', error);
                }
            }

            // Gunakan rujukan yang sudah diupdate untuk perhitungan
            calculateAndUpdateKeteranganKimia($input, $keteranganDisplay, $hiddenInput, hasil, rujukan, ch, cl);
        }



        function calculateAndUpdateKeteranganKimia($input, $keteranganDisplay, $hiddenInput, hasil, rujukan, ch, cl) {
            console.log('Kimia - Perhitungan dengan:', { hasil, rujukan, ch, cl });

            if (!rujukan || rujukan.trim() === '' || rujukan.trim() === '-') {
                console.log('Rujukan tidak tersedia atau "-"');
                updateKeteranganDisplay($keteranganDisplay, '-');
                $hiddenInput.val('-');
                return;
            }

            try {
                const rujukanStr = rujukan.toString().trim();
                const hasilStr = hasil.toString().trim();
                const hasilNum = parseFloat(hasilStr.replace(',', '.'));

                console.log('Kimia - Data untuk perhitungan:', {
                    rujukan: rujukanStr,
                    hasil: hasilStr,
                    hasilNum: hasilNum
                });

                // Jika bukan angka, cek kualitatif
                if (isNaN(hasilNum)) {
                    const hasilLower = hasilStr.toLowerCase();
                    const rujukanLower = rujukanStr.toLowerCase();

                    if (rujukanLower.includes('negative') || rujukanLower.includes('negatif')) {
                        if (hasilLower.includes('negative') || hasilLower.includes('negatif') ||
                            hasilLower.includes('non-reactive') || hasilLower.includes('nonreactive')) {
                            updateKeteranganDisplay($keteranganDisplay, '-');
                            $hiddenInput.val('-');
                        } else {
                            updateKeteranganDisplay($keteranganDisplay, 'H');
                            $hiddenInput.val('H');
                        }
                        return;
                    } else if (rujukanLower.includes('positive') || rujukanLower.includes('positif')) {
                        if (hasilLower.includes('positive') || hasilLower.includes('positif') ||
                            hasilLower.includes('reactive') || hasilLower.includes('reaktif')) {
                            updateKeteranganDisplay($keteranganDisplay, '-');
                            $hiddenInput.val('-');
                        } else {
                            updateKeteranganDisplay($keteranganDisplay, 'L');
                            $hiddenInput.val('L');
                        }
                        return;
                    } else {
                        updateKeteranganDisplay($keteranganDisplay, '-');
                        $hiddenInput.val('-');
                    }
                    return;
                }

                // CEK CRITICAL HIGH (CH)
                if (ch && ch !== '' && ch !== '-' && ch !== 'null') {
                    let chNum;
                    if (ch.includes('>')) {
                        chNum = parseFloat(ch.replace('>', '').trim());
                    } else {
                        chNum = parseFloat(ch);
                    }

                    if (!isNaN(chNum) && hasilNum > chNum) {
                        console.log(`✅ KIMIA CH DETECTED: ${hasilNum} > ${chNum}`);
                        updateKeteranganDisplay($keteranganDisplay, 'CH');
                        $hiddenInput.val('CH');
                        return;
                    }
                }

                // CEK CRITICAL LOW (CL)
                if (cl && cl !== '' && cl !== '-' && cl !== 'null') {
                    let clNum;
                    if (cl.includes('<')) {
                        clNum = parseFloat(cl.replace('<', '').trim());
                    } else {
                        clNum = parseFloat(cl);
                    }

                    if (!isNaN(clNum) && hasilNum < clNum) {
                        console.log(`✅ KIMIA CL DETECTED: ${hasilNum} < ${clNum}`);
                        updateKeteranganDisplay($keteranganDisplay, 'CL');
                        $hiddenInput.val('CL');
                        return;
                    }
                }

                // CEK RUJUKAN NORMAL
                // Format range: "1 - 90"
                if (rujukanStr.includes('-') && !rujukanStr.includes('<') && !rujukanStr.includes('>')) {
                    const parts = rujukanStr.split('-');
                    if (parts.length === 2) {
                        const min = parseFloat(parts[0].trim());
                        const max = parseFloat(parts[1].trim());

                        if (!isNaN(min) && !isNaN(max)) {
                            if (hasilNum < min) {
                                console.log(`✅ KIMIA L DETECTED: ${hasilNum} < ${min}`);
                                updateKeteranganDisplay($keteranganDisplay, 'L');
                                $hiddenInput.val('L');
                            } else if (hasilNum > max) {
                                console.log(`✅ KIMIA H DETECTED: ${hasilNum} > ${max}`);
                                updateKeteranganDisplay($keteranganDisplay, 'H');
                                $hiddenInput.val('H');
                            } else {
                                console.log(`✅ KIMIA NORMAL: ${hasilNum} dalam range ${min}-${max}`);
                                updateKeteranganDisplay($keteranganDisplay, '-');
                                $hiddenInput.val('-');
                            }
                            return;
                        }
                    }
                }

                // Format: "< X"
                if (rujukanStr.startsWith('<')) {
                    const batas = parseFloat(rujukanStr.replace('<', '').trim());
                    if (!isNaN(batas)) {
                        if (hasilNum >= batas) {
                            console.log(`✅ KIMIA H DETECTED: ${hasilNum} ≥ ${batas}`);
                            updateKeteranganDisplay($keteranganDisplay, 'H');
                            $hiddenInput.val('H');
                        } else {
                            console.log(`✅ KIMIA NORMAL: ${hasilNum} < ${batas}`);
                            updateKeteranganDisplay($keteranganDisplay, '-');
                            $hiddenInput.val('-');
                        }
                        return;
                    }
                }

                // Format: "> X"
                if (rujukanStr.startsWith('>')) {
                    const batas = parseFloat(rujukanStr.replace('>', '').trim());
                    if (!isNaN(batas)) {
                        if (hasilNum <= batas) {
                            console.log(`✅ KIMIA L DETECTED: ${hasilNum} ≤ ${batas}`);
                            updateKeteranganDisplay($keteranganDisplay, 'L');
                            $hiddenInput.val('L');
                        } else {
                            console.log(`✅ KIMIA NORMAL: ${hasilNum} > ${batas}`);
                            updateKeteranganDisplay($keteranganDisplay, '-');
                            $hiddenInput.val('-');
                        }
                        return;
                    }
                }

                // Default
                console.log('Tidak ada pola yang cocok');
                updateKeteranganDisplay($keteranganDisplay, '-');
                $hiddenInput.val('-');

            } catch (e) {
                console.error('KIMIA - Error:', e);
                updateKeteranganDisplay($keteranganDisplay, '-');
                $hiddenInput.val('-');
            }
        }

        // Function untuk update keterangan display
        function updateKeteranganDisplay($display, keterangan) {
            if (!$display.length) {
                console.error('Display element tidak ditemukan');
                return;
            }

            // Reset semua kelas
            $display.removeClass(
                'bg-danger bg-opacity-10 ' +
                'bg-primary bg-opacity-10 ' +
                'bg-success bg-opacity-10 ' +
                'text-danger text-primary text-success'
            );

            // Set kelas berdasarkan keterangan
            let bgClass = '';
            let textClass = '';
            let displayText = '';

            switch (keterangan) {
                case 'CH':
                case 'H':
                    bgClass = 'bg-danger bg-opacity-10';
                    textClass = 'text-danger';
                    displayText = keterangan;
                    break;

                case 'CL':
                case 'L':
                    bgClass = 'bg-primary bg-opacity-10';
                    textClass = 'text-primary';
                    displayText = keterangan;
                    break;

                case '-':
                    bgClass = 'bg-success bg-opacity-10';
                    textClass = 'text-success';
                    displayText = '';
                    break;

                default:
                    bgClass = 'bg-light';
                    textClass = 'text-muted';
                    displayText = '-';
                    keterangan = '-';
            }

            // Apply classes
            $display.addClass(bgClass + ' ' + textClass);
            $display.html('<strong>' + displayText + '</strong>');
            $display.data('keterangan', keterangan);
        }

        // ============================================
        // EVENT HANDLER UNTUK INPUT KIMIA
        // ============================================


        $('#tambahRowHematologyBtn').on('click', function() {
            console.log('Tombol tambah row hematology diklik');

            // Cari jumlah row yang ada saat ini
            const $table = $('#hematologyTable tbody');
            const currentRowCount = $table.find('tr').length;
            const newIndex = currentRowCount;

            console.log('Current row count:', currentRowCount, 'New index:', newIndex);

            // Row baru dengan semua elemen yang diperlukan
            const newRow =
                '<tr data-index="' + newIndex + '" class="table-warning hematology-row">' +
                '    <td class="search-cell-hematology">' +
                '        <div class="position-relative">' +
                '            <input type="text"' +
                '                class="form-control form-control-sm search-data-hematology"' +
                '                placeholder="Cari data pemeriksaan..."' +
                '                data-index="' + newIndex + '"' +
                '                autocomplete="off">' +
                '' +
                '            <div class="search-results-hematology dropdown-menu"' +
                '                style="display: none; max-height: 200px; overflow-y: auto; z-index: 1050;">' +
                '            </div>' +
                '' +
                '            <input type="hidden"' +
                '                name="hematology[' + newIndex + '][id]"' +
                '                class="row-id-input"' +
                '                value="">' +
                '            <input type="hidden"' +
                '                name="hematology[' + newIndex + '][jenis_pengujian]"' +
                '                class="jenis-pengujian-input"' +
                '                value="">' +
                '            <input type="hidden"' +
                '                name="hematology[' + newIndex + '][id_data_pemeriksaan]"' +
                '                class="id-data-pemeriksaan-input"' +
                '                value="">' +
                '        </div>' +
                '    </td>' +
                '' +
                '    <td class="hasil-cell-hematology">' +
                '        <input type="text"' +
                '            name="hematology[' + newIndex + '][hasil_pengujian]"' +
                '            class="form-control form-control-sm hasil-input-hematology"' +
                '            value=""' +
                '            placeholder="Hasil"' +
                '            data-id=""' +
                '            data-type="hematology"' +
                '            autocomplete="off">' +
                '    </td>' +
                '' +
                '    <td class="bg-light satuan-cell-hematology">' +
                '        <span class="satuan-display-hematology">-</span>' +
                '    </td>' +
                '' +
                '    <td class="bg-light rujukan-cell-hematology">' +
                '        <span class="rujukan-display-hematology">-</span>' +
                '    </td>' +
                '' +
                '    <td class="bg-light ch-cell-hematology" style="text-align:center;">' +
                '        <span class="ch-display-hematology">-</span>' +
                '        <input type="hidden" class="ch-input-hematology" name="hematology[' + newIndex + '][ch]" value="">' +
                '    </td>' +
                '' +
                '    <td class="bg-light cl-cell-hematology" style="text-align:center;">' +
                '        <span class="cl-display-hematology">-</span>' +
                '        <input type="hidden" class="cl-input-hematology" name="hematology[' + newIndex + '][cl]" value="">' +
                '    </td>' +
                '' +
                '    <td class="keterangan-cell-hematology">' +
                '        <div class="keterangan-display-hematology bg-success bg-opacity-10 text-success rounded py-1 px-2 text-center"' +
                '            data-keterangan="-">' +
                '            <strong>-</strong>' +
                '        </div>' +
                '        <input type="hidden"' +
                '            name="hematology[' + newIndex + '][keterangan]"' +
                '            class="keterangan-input-hematology"' +
                '            value="-">' +
                '    </td>' +
                '' +
                '    <td class="actions-cell-hematology text-center">' +
                '        <!-- Tombol Hapus Row -->' +
                '        <button type="button" class="btn btn-sm btn-outline-danger hapus-row-hematology-btn mt-1">' +
                '            <i class="ri-delete-bin-line"></i>' +
                '        </button>' +
                '    </td>' +
                '</tr>';

            // Tambahkan row ke tabel
            $table.append(newRow);
            console.log('Row baru ditambahkan dengan index:', newIndex);

            // Focus ke input search
            setTimeout(function() {
                $table.find('tr:last-child .search-data-hematology').focus();
            }, 100);

            // Inisialisasi Excel navigation untuk row baru
            initHematologyExcelNavigation();

            // Tampilkan toast
            if (typeof window.showToast === 'function') {
                window.showToast('success', 'Row hematology baru ditambahkan');
            }
        });

        $(document).on('input', '.hasil-input[data-type="kimia"]', function() {
            const $input = $(this);
            const value = $input.val();

            console.log('====== KIMIA - INPUT BERUBAH ======');
            console.log('Nilai baru:', value);

            // Update keterangan Kimia secara real-time
            updateKimiaKeterangan($input);
        });

        // ============================================
        // EXCEL NAVIGATION KHUSUS UNTUK KIMIA
        // ============================================

        function initKimiaExcelNavigation() {
            // Select all inputs on focus
            $('.hasil-input[data-type="kimia"]').on('focus', function() {
                $(this).select();
                $(this).closest('td').addClass('table-warning');
            });

            // Remove highlight on blur
            $('.hasil-input[data-type="kimia"]').on('blur', function() {
                $(this).closest('td').removeClass('table-warning');
            });

            // Excel-like keyboard navigation
            $('.hasil-input[data-type="kimia"]').on('keydown', function(e) {
                const $current = $(this);
                const $row = $current.closest('tr');
                const $cell = $current.closest('td');
                const cellIndex = $cell.index();
                const $rows = $row.closest('tbody').find('tr');
                const rowIndex = $rows.index($row);

                switch (e.key) {
                    case 'Enter':
                    case 'ArrowDown':
                        e.preventDefault();
                        const $nextRow = $rows.eq(rowIndex + 1);
                        if ($nextRow.length) {
                            const $nextInput = $nextRow.find('td').eq(cellIndex).find('.hasil-input[data-type="kimia"]');
                            if ($nextInput.length) {
                                $nextInput.focus().select();
                            }
                        }
                        break;

                    case 'ArrowUp':
                        e.preventDefault();
                        const $upRow = $rows.eq(rowIndex - 1);
                        if ($upRow.length) {
                            const $upInput = $upRow.find('td').eq(cellIndex).find('.hasil-input[data-type="kimia"]');
                            if ($upInput.length) {
                                $upInput.focus().select();
                            }
                        }
                        break;

                    case 'ArrowRight':
                        e.preventDefault();
                        const $nextCell = $row.find('td').eq(cellIndex + 1).find('.hasil-input[data-type="kimia"]');
                        if ($nextCell.length) {
                            $nextCell.focus().select();
                        }
                        break;

                    case 'ArrowLeft':
                        e.preventDefault();
                        const $prevCell = $row.find('td').eq(cellIndex - 1).find('.hasil-input[data-type="kimia"]');
                        if ($prevCell.length) {
                            $prevCell.focus().select();
                        }
                        break;
                }
            });
        }

        // ============================================
        // INISIALISASI DATA RUJUKAN SAAT PAGE LOAD
        // ============================================

        console.log('Initializing kimia rujukan data...');
        $('.hasil-input[data-type="kimia"]').each(function() {
            const $input = $(this);
            const $row = $input.closest('tr');
            const rujukanDisplay = $row.find('.rujukan-display').text().trim().replace(' K', '');

            // Jika rujukan display valid, set data-rujukan
            if (rujukanDisplay && rujukanDisplay !== '-') {
                $input.data('rujukan', rujukanDisplay);
                $input.attr('data-rujukan', rujukanDisplay);

                console.log('Kimia initialized rujukan:', {
                    analysis: $row.find('input[name*="[analysis]"]').val(),
                    rujukan: rujukanDisplay
                });

                // Juga update keterangan berdasarkan nilai yang ada
                if ($input.val()) {
                    updateKimiaKeterangan($input);
                }
            }
        });

        // Initialize Excel navigation untuk Kimia
        initKimiaExcelNavigation();

        // ============================================
        // FUNGSI UNTUK UPDATE KODE DISPLAY (TIDAK DIUBAH)
        // ============================================

        function updateKodeDisplay($row, kode, dataPemeriksaan, isMapped = true) {
            const rowIndex = $row.data('index');
            const kimiaId = $row.data('kimia-id');
            const analysis = $row.find('input[name*="[analysis]"]').val();

            let html = '';

            if (isMapped && kode) {
                html = `
                    <div class="position-relative">
                        <input type="text"
                            class="form-control form-control-sm kode-edit-input"
                            placeholder="Cari data pemeriksaan..."
                            value="${dataPemeriksaan || kode}"
                            data-kimia-id="${kimiaId}"
                            data-analysis="${analysis}"
                            data-current-kode="${kode}"
                            autocomplete="off">
                        <div class="kode-search-results dropdown-menu w-100"
                            style="display: none; max-height: 200px; overflow-y: auto;">
                        </div>
                        <input type="hidden"
                            name="kimia[${rowIndex}][id_data_pemeriksaan]"
                            class="kode-pemeriksaan-input"
                            value="${kode}">

                        <div class="mt-1 d-flex justify-content-between align-items-center">
                            <small class="text-success">
                                <i class="ri-links-line me-1"></i>
                                <span class="kode-display">${kode}</span>
                            </small>
                        </div>
                    </div>
                `;
            } else {
                html = `
                    <div class="position-relative">
                        <input type="text"
                            class="form-control form-control-sm kode-search-input"
                            placeholder="Cari kode pemeriksaan..."
                            data-kimia-id="${kimiaId}"
                            data-analysis="${analysis}"
                            autocomplete="off">
                        <div class="kode-search-results dropdown-menu w-100"
                            style="display: none; max-height: 200px; overflow-y: auto;">
                        </div>
                        <input type="hidden"
                            name="kimia[${rowIndex}][id_data_pemeriksaan]"
                            class="kode-pemeriksaan-input"
                            value="">
                        <div class="mt-1">
                            <small class="text-warning">
                                <i class="ri-alert-line me-1"></i>Belum dipetakan
                            </small>
                        </div>
                    </div>
                `;
            }

            $row.find('.search-cell').html(html);
        }

        // ============================================
        // FUNGSI UNTUK RESET MAPPING KODE (TIDAK DIUBAH)
        // ============================================

        function resetKodeMapping(kimiaId) {
            if (!confirm('Yakin ingin menghapus mapping kode pemeriksaan ini?')) {
                return;
            }

            $.ajax({
                url: '{{ route("kimia.reset-kode-pemeriksaan") }}',
                method: 'POST',
                data: {
                    _token: csrfToken,
                    id_pemeriksaan_kimia: kimiaId
                },
                beforeSend: function() {
                    if (typeof showToast === 'function') {
                        showToast('info', 'Menghapus mapping...');
                    }
                },
                success: function(response) {
                    if (response.success) {
                        const $row = $(`tr[data-kimia-id="${kimiaId}"]`);

                        // Update UI untuk search mode
                        updateKodeDisplay($row, null, null, false);

                        // Reset satuan dan rujukan display
                        $row.find('.satuan-display').text('-');
                        $row.find('.rujukan-display').text('-');
                        $row.find('.method-cell').text('-');

                        // RESET PENTING: Hapus data-rujukan dari input hasil
                        const $hasilInput = $row.find('.hasil-input');
                        $hasilInput.removeData('rujukan');
                        $hasilInput.removeAttr('data-rujukan');

                        console.log('Reset rujukan for input:', $hasilInput.data('id'));

                        // Reset keterangan jika ada nilai
                        if ($hasilInput.val()) {
                            const $keteranganDisplay = $row.find('.keterangan-display');

                            updateKeteranganDisplay($keteranganDisplay, '-');

                            // AJAX untuk update keterangan di database
                            $.ajax({
                                url: '{{ route("hasil-lab.update-field-ajax") }}',
                                method: 'POST',
                                data: {
                                    _token: csrfToken,
                                    type: 'kimia',
                                    id: $hasilInput.data('id'),
                                    field: 'hasil_pengujian',
                                    value: $hasilInput.val(),
                                    keterangan: '-'
                                },
                                success: function(resp) {
                                    console.log('Keterangan direset di database:', resp);
                                }
                            });
                        }

                        if (typeof showToast === 'function') {
                            showToast('success', 'Mapping berhasil direset');
                        }
                    } else {
                        if (typeof showToast === 'function') {
                            showToast('danger', response.message || 'Gagal reset mapping');
                        }
                    }
                },
                error: function(xhr) {
                    console.error('Reset error:', xhr);
                    if (typeof showToast === 'function') {
                        showToast('danger', 'Terjadi kesalahan saat reset mapping');
                    }
                }
            });
        }

        // ============================================
        // FUNGSI UNTUK HANDLE SEARCH KODE (TIDAK DIUBAH)
        // ============================================

        function handleKodeSearch($input) {
            const searchTerm = $input.val().trim();
            const $results = $input.next('.kode-search-results');
            const kimiaId = $input.data('kimia-id');
            const analysis = $input.data('analysis');
            const currentId = $input.data('current-id');

            if (searchTerm.length < 2) {
                $results.hide().empty();
                return;
            }

            clearTimeout($input.data('searchTimer'));
            $input.data('searchTimer', setTimeout(() => {
                $.ajax({
                    url: '{{ route("kimia.search-kode-pemeriksaan") }}',
                    method: 'POST',
                    data: {
                        _token: csrfToken,
                        search: searchTerm,
                        analysis: analysis,
                        exclude_current: currentId
                    },
                    beforeSend: function() {
                        $results.html('<div class="dropdown-item text-center py-2"><i class="ri-loader-4-line spin"></i> Mencari data pemeriksaan...</div>').show();
                    },
                    success: function(response) {
                        $results.empty();

                        if (response.success && response.data && response.data.length > 0) {
                            response.data.forEach(function(item) {
                                const $option = $(`
                                    <button type="button" class="dropdown-item kode-option"
                                            data-id="${item.id_data_pemeriksaan}"
                                            data-data-pemeriksaan="${item.data_pemeriksaan}"
                                            data-satuan="${item.satuan || ''}"
                                            data-rujukan="${item.rujukan || ''}"
                                            data-ch="${item.ch || ''}"
                                            data-cl="${item.cl || ''}"
                                            data-metode="${item.metode || ''}">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong>${item.id_data_pemeriksaan}</strong>
                                                <div class="small text-muted">${item.data_pemeriksaan}</div>
                                            </div>
                                            <i class="ri-arrow-right-s-line"></i>
                                        </div>
                                    </button>
                                `);
                                $results.append($option);
                            });

                            // Tambahkan opsi "Clear mapping" jika sedang edit
                            if (currentId) {
                                const $clearOption = $(`
                                    <div class="dropdown-divider"></div>
                                    <button type="button" class="dropdown-item text-danger clear-option"
                                            data-kimia-id="${kimiaId}">
                                        <i class="ri-close-line me-2"></i>
                                        Hapus mapping ini
                                    </button>
                                `);
                                $results.append($clearOption);
                            }
                        } else {
                            $results.html('<div class="dropdown-item text-center py-2 text-muted">Tidak ditemukan data pemeriksaan</div>');
                        }

                        $results.show();
                    },
                    error: function(xhr) {
                        $results.html('<div class="dropdown-item text-center py-2 text-danger">Error loading data</div>').show();
                    }
                });
            }, 500));
        }

        // ============================================
        // EVENT HANDLERS YANG SUDAH ADA (TIDAK DIUBAH)
        // ============================================

        // Real-time search untuk kode pemeriksaan
        $(document).on('input', '.kode-search-input, .kode-edit-input', function() {
            handleKodeSearch($(this));
        });

        // Pilih kode pemeriksaan
        $(document).on('click', '.kode-option', function() {
            const $option = $(this);
            const id = $option.data('id');
            const dataPemeriksaan = $option.data('data-pemeriksaan');
            const satuan = $option.data('satuan');
            const rujukan = $option.data('rujukan');
            const metode = $option.data('metode');

            console.log('Data pemeriksaan selected:', {
                id: id,
                data_pemeriksaan: dataPemeriksaan,
                rujukan: rujukan,
                satuan: satuan,
                metode: metode
            });

            const $row = $option.closest('tr');
            const kimiaId = $row.data('kimia-id');
            const analysis = $row.find('input[name*="[analysis]"]').val();
            const $input = $row.find('.kode-search-input, .kode-edit-input');
            const $results = $row.find('.kode-search-results');

            // Update UI sementara
            $input.val(dataPemeriksaan);
            $results.hide().empty();

            // Kirim ke server untuk update dan mapping
            $.ajax({
                url: '{{ route("kimia.update-kode-pemeriksaan") }}',
                method: 'POST',
                data: {
                    _token: csrfToken,
                    id_pemeriksaan_kimia: kimiaId,
                    id_data_pemeriksaan: id,
                    analysis: analysis,
                    method: metode,
                    satuan: satuan,
                    rujukan: rujukan
                },
                beforeSend: function() {
                    $row.addClass('table-warning');
                },
                success: function(response) {
                    if (response.success) {
                        $row.removeClass('table-warning').addClass('table-success');

                        // Update display dengan mode edit
                        const displayText = `${id} - ${dataPemeriksaan}`;
                        updateKodeDisplay($row, id, dataPemeriksaan, true);

                        // Update satuan dan rujukan display
                        $row.find('.satuan-display').text(satuan || '-');
                        $row.find('.rujukan-display').text(rujukan || '-');
                        $row.find('.method-cell').text(metode || '-');

                        // UPDATE data pada input hasil untuk kondisi pasien
                        const $hasilInput = $row.find('.hasil-input');
                        $hasilInput.data('rujukan', rujukan);
                        $hasilInput.attr('data-rujukan', rujukan);
                        $hasilInput.data('id-data-pemeriksaan', id);

                        console.log('Updated rujukan dan id-data-pemeriksaan on hasil-input:', {
                            id: $hasilInput.data('id'),
                            id_data_pemeriksaan: id,
                            rujukan: rujukan
                        });

                        // Jika sudah ada nilai hasil, hitung ulang keterangan dengan kondisi baru
                        if ($hasilInput.val()) {
                            updateKimiaKeterangan($hasilInput);
                        }

                        if (typeof showToast === 'function') {
                            showToast('success', 'Data pemeriksaan berhasil dipetakan');
                        }

                        setTimeout(() => {
                            $row.removeClass('table-success');
                        }, 2000);
                    } else {
                        if (typeof showToast === 'function') {
                            showToast('danger', response.message || 'Gagal memetakan data pemeriksaan');
                        }
                        $row.removeClass('table-warning');
                        $input.val('');
                    }
                },
                error: function(xhr) {
                    console.error('Update error:', xhr);
                    $row.removeClass('table-warning');

                    let errorMessage = 'Gagal memetakan data pemeriksaan';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }

                    if (typeof showToast === 'function') {
                        showToast('danger', errorMessage);
                    }
                    $input.val('');
                }
            });
        });

        // Tombol reset mapping
        $(document).on('click', '.btn-reset-kode', function(e) {
            e.stopPropagation();
            const kimiaId = $(this).data('kimia-id');
            resetKodeMapping(kimiaId);
        });

        // Opsi clear dari dropdown
        $(document).on('click', '.clear-option', function(e) {
            e.stopPropagation();
            const kimiaId = $(this).data('kimia-id');
            $(this).closest('.kode-search-results').hide();
            resetKodeMapping(kimiaId);
        });

        // Tutup dropdown saat klik di luar
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.kode-search-input, .kode-edit-input, .kode-search-results').length) {
                $('.kode-search-results').hide();
            }
        });

        // Keyboard navigation untuk input edit
        $(document).on('keydown', '.kode-edit-input', function(e) {
            if (e.key === 'Escape') {
                const currentKode = $(this).data('current-kode');
                const dataPemeriksaan = $(this).val();
                if (currentKode) {
                    $(this).val(dataPemeriksaan);
                }
                $(this).next('.kode-search-results').hide();
            } else if (e.key === 'Delete' && e.ctrlKey) {
                e.preventDefault();
                const kimiaId = $(this).data('kimia-id');
                resetKodeMapping(kimiaId);
            }
        });
    });
</script>

<!-- AKHIR SCRIPT KIMIA -->
<script>
    $(document).ready(function() {
        // Setup CSRF token untuk AJAX
        const csrfToken = $('#csrf_token').val();

        // ============================================
        // FUNGSI UNTUK MENDAPATKAN RUJUKAN BERDASARKAN KONDISI
        // ============================================
        function getRujukanByKondisi(idDataPemeriksaan, jenisKelamin, umurPasien) {
            // Versi queue/cache — menggantikan Ajax lama
            return new Promise(function(resolve) {
                if (!idDataPemeriksaan) {
                    resolve(null);
                    return;
                }

                // inisialisasi struktur global
                window.__rujukanCache = window.__rujukanCache || {};
                window.__rujukanResolvers = window.__rujukanResolvers || {};

                // Kalau sudah ada di cache, langsung return
                if (Object.prototype.hasOwnProperty.call(window.__rujukanCache, idDataPemeriksaan)) {
                    resolve(window.__rujukanCache[idDataPemeriksaan]);
                    return;
                }

                // Masukkan resolver ke queue — akan dipanggil ketika batch berhasil
                window.__rujukanResolvers[idDataPemeriksaan] = window.__rujukanResolvers[idDataPemeriksaan] || [];
                window.__rujukanResolvers[idDataPemeriksaan].push(resolve);
            });
        }


        // ============================================
        // UPDATE HEMATOLOGY KETERANGAN DENGAN KONDISI
        // ============================================

        window.updateHematologyKeterangan = async function($input) {
            const hasil = $input.val();
            const $row = $input.closest('tr');
            const $keteranganDisplay = $row.find('.keterangan-display');
            const $hiddenInput = $row.find('input[name*="[keterangan]"]');

            console.log('=== HEMATOLOGY - updateKeterangan ===');
            console.log('Hasil:', hasil);

            // Clear jika kosong
            if (!hasil || hasil.trim() === '') {
                console.log('Hasil kosong, reset keterangan');
                updateKeteranganDisplay($keteranganDisplay, '');
                $hiddenInput.val('');
                return;
            }

            // Dapatkan data kondisi pasien
            const idDataPemeriksaan = $input.data('id-data-pemeriksaan');
            const jenisKelamin = $input.data('jenis-kelamin') || '{{ $pasien->jenis_kelamin }}';
            const umurPasien = $input.data('umur') || '{{ $data["umur_format"] ?? "" }}';

            let rujukan = $input.data('rujukan') || $input.attr('data-rujukan') || '';
            let ch = $input.data('ch') || $input.attr('data-ch') || '';
            let cl = $input.data('cl') || $input.attr('data-cl') || '';

            // Jika ada ID data pemeriksaan, coba dapatkan rujukan berdasarkan kondisi
            if (idDataPemeriksaan) {
                try {
                    const rujukanData = await getRujukanByKondisi(idDataPemeriksaan, jenisKelamin, umurPasien);

                    if (rujukanData) {
                        // Update data pada input
                        rujukan = rujukanData.rujukan || rujukan;
                        ch = rujukanData.ch || ch;
                        cl = rujukanData.cl || cl;

                        // Update tampilan di tabel
                        $row.find('.rujukan-cell').html(
                            rujukan +
                            (rujukanData.is_from_detail ?
                                ' <span class="badge bg-info ms-1" title="Rujukan khusus kondisi">K</span>' :
                                '')
                        );
                        $row.find('.ch-cell').text(ch || '-');
                        $row.find('.cl-cell').text(cl || '-');

                        // Jika rujukan dari detail kondisi, highlight row
                        if (rujukanData.is_from_detail) {
                            $row.addClass('table-info');
                        } else {
                            $row.removeClass('table-info');
                        }

                        console.log('Rujukan diupdate berdasarkan kondisi:', rujukanData);
                    }
                } catch (error) {
                    console.error('Error mendapatkan rujukan berdasarkan kondisi:', error);
                }
            }

            // Gunakan rujukan yang sudah diupdate untuk perhitungan
            calculateAndUpdateKeterangan($input, $keteranganDisplay, $hiddenInput, hasil, rujukan, ch, cl);
        };

        function calculateAndUpdateKeterangan($input, $keteranganDisplay, $hiddenInput, hasil, rujukan, ch, cl) {
            console.log('Perhitungan dengan:', { hasil, rujukan, ch, cl });

            if (!rujukan || rujukan.trim() === '' || rujukan.trim() === '-') {
                console.log('Rujukan tidak tersedia atau "-"');
                updateKeteranganDisplay($keteranganDisplay, '-');
                $hiddenInput.val('-');
                return;
            }

            try {
                const rujukanStr = rujukan.toString().trim();
                const hasilStr = hasil.toString().trim();
                const hasilNum = parseFloat(hasilStr.replace(',', '.'));

                console.log('Data untuk perhitungan:', {
                    rujukan: rujukanStr,
                    hasil: hasilStr,
                    hasilNum: hasilNum
                });

                // Jika bukan angka, cek kualitatif
                if (isNaN(hasilNum)) {
                    const hasilLower = hasilStr.toLowerCase();
                    const rujukanLower = rujukanStr.toLowerCase();

                    if (rujukanLower.includes('negative') || rujukanLower.includes('negatif')) {
                        if (hasilLower.includes('negative') || hasilLower.includes('negatif') ||
                            hasilLower.includes('non-reactive') || hasilLower.includes('nonreactive')) {
                            updateKeteranganDisplay($keteranganDisplay, '-');
                            $hiddenInput.val('-');
                        } else {
                            updateKeteranganDisplay($keteranganDisplay, 'H');
                            $hiddenInput.val('H');
                        }
                        return;
                    } else if (rujukanLower.includes('positive') || rujukanLower.includes('positif')) {
                        if (hasilLower.includes('positive') || hasilLower.includes('positif') ||
                            hasilLower.includes('reactive') || hasilLower.includes('reaktif')) {
                            updateKeteranganDisplay($keteranganDisplay, '-');
                            $hiddenInput.val('-');
                        } else {
                            updateKeteranganDisplay($keteranganDisplay, 'L');
                            $hiddenInput.val('L');
                        }
                        return;
                    } else {
                        updateKeteranganDisplay($keteranganDisplay, '-');
                        $hiddenInput.val('-');
                    }
                    return;
                }

                // CEK CRITICAL HIGH (CH)
                if (ch && ch !== '' && ch !== '-' && ch !== 'null') {
                    let chNum;
                    if (ch.includes('>')) {
                        chNum = parseFloat(ch.replace('>', '').trim());
                    } else {
                        chNum = parseFloat(ch);
                    }

                    if (!isNaN(chNum) && hasilNum > chNum) {
                        console.log(`✅ CH DETECTED: ${hasilNum} > ${chNum}`);
                        updateKeteranganDisplay($keteranganDisplay, 'CH');
                        $hiddenInput.val('CH');
                        return;
                    }
                }

                // CEK CRITICAL LOW (CL)
                if (cl && cl !== '' && cl !== '-' && cl !== 'null') {
                    let clNum;
                    if (cl.includes('<')) {
                        clNum = parseFloat(cl.replace('<', '').trim());
                    } else {
                        clNum = parseFloat(cl);
                    }

                    if (!isNaN(clNum) && hasilNum < clNum) {
                        console.log(`✅ CL DETECTED: ${hasilNum} < ${clNum}`);
                        updateKeteranganDisplay($keteranganDisplay, 'CL');
                        $hiddenInput.val('CL');
                        return;
                    }
                }

                // CEK RUJUKAN NORMAL
                // Format range: "1 - 90"
                if (rujukanStr.includes('-') && !rujukanStr.includes('<') && !rujukanStr.includes('>')) {
                    const parts = rujukanStr.split('-');
                    if (parts.length === 2) {
                        const min = parseFloat(parts[0].trim());
                        const max = parseFloat(parts[1].trim());

                        if (!isNaN(min) && !isNaN(max)) {
                            if (hasilNum < min) {
                                console.log(`✅ L DETECTED: ${hasilNum} < ${min}`);
                                updateKeteranganDisplay($keteranganDisplay, 'L');
                                $hiddenInput.val('L');
                            } else if (hasilNum > max) {
                                console.log(`✅ H DETECTED: ${hasilNum} > ${max}`);
                                updateKeteranganDisplay($keteranganDisplay, 'H');
                                $hiddenInput.val('H');
                            } else {
                                console.log(`✅ NORMAL: ${hasilNum} dalam range ${min}-${max}`);
                                updateKeteranganDisplay($keteranganDisplay, '-');
                                $hiddenInput.val('-');
                            }
                            return;
                        }
                    }
                }

                // Format: "< X"
                if (rujukanStr.startsWith('<')) {
                    const batas = parseFloat(rujukanStr.replace('<', '').trim());
                    if (!isNaN(batas)) {
                        if (hasilNum >= batas) {
                            console.log(`✅ H DETECTED: ${hasilNum} ≥ ${batas}`);
                            updateKeteranganDisplay($keteranganDisplay, 'H');
                            $hiddenInput.val('H');
                        } else {
                            console.log(`✅ NORMAL: ${hasilNum} < ${batas}`);
                            updateKeteranganDisplay($keteranganDisplay, '-');
                            $hiddenInput.val('-');
                        }
                        return;
                    }
                }

                // Format: "> X"
                if (rujukanStr.startsWith('>')) {
                    const batas = parseFloat(rujukanStr.replace('>', '').trim());
                    if (!isNaN(batas)) {
                        if (hasilNum <= batas) {
                            console.log(`✅ L DETECTED: ${hasilNum} ≤ ${batas}`);
                            updateKeteranganDisplay($keteranganDisplay, 'L');
                            $hiddenInput.val('L');
                        } else {
                            console.log(`✅ NORMAL: ${hasilNum} > ${batas}`);
                            updateKeteranganDisplay($keteranganDisplay, '-');
                            $hiddenInput.val('-');
                        }
                        return;
                    }
                }

                // Default
                console.log('Tidak ada pola yang cocok');
                updateKeteranganDisplay($keteranganDisplay, '-');
                $hiddenInput.val('-');

            } catch (e) {
                console.error('HEMATOLOGY - Error:', e);
                updateKeteranganDisplay($keteranganDisplay, '-');
                $hiddenInput.val('-');
            }
        }

        // Function untuk update keterangan display
        function updateKeteranganDisplay($display, keterangan) {
            if (!$display.length) {
                console.error('Display element tidak ditemukan');
                return;
            }

            // Reset semua kelas
            $display.removeClass(
                'bg-danger bg-opacity-10 ' +
                'bg-primary bg-opacity-10 ' +
                'bg-success bg-opacity-10 ' +
                'text-danger text-primary text-success'
            );

            // Set kelas berdasarkan keterangan
            let bgClass = '';
            let textClass = '';
            let displayText = '';

            switch (keterangan) {
                case 'CH':
                case 'H':
                    bgClass = 'bg-danger bg-opacity-10';
                    textClass = 'text-danger';
                    displayText = keterangan;
                    break;

                case 'CL':
                case 'L':
                    bgClass = 'bg-primary bg-opacity-10';
                    textClass = 'text-primary';
                    displayText = keterangan;
                    break;

                case '-':
                    bgClass = 'bg-success bg-opacity-10';
                    textClass = 'text-success';
                    displayText = '-';
                    break;

                default:
                    bgClass = 'bg-light';
                    textClass = 'text-muted';
                    displayText = '-';
                    keterangan = '-';
            }

            // Apply classes
            $display.addClass(bgClass + ' ' + textClass);
            $display.html('<strong>' + displayText + '</strong>');
            $display.data('keterangan', keterangan);
        }

        // ============================================
        // INISIALISASI DATA RUJUKAN SAAT PAGE LOAD
        // ============================================

        console.log('Initializing hematology rujukan data...');
        $('.hasil-input[data-type="hematology"]').each(function() {
            const $input = $(this);
            const $row = $input.closest('tr');
            const rujukanDisplay = $row.find('.rujukan-cell').text().trim().replace(' K', '');

            // Jika rujukan display valid, set data-rujukan
            if (rujukanDisplay && rujukanDisplay !== '-') {
                $input.data('rujukan', rujukanDisplay);
                $input.attr('data-rujukan', rujukanDisplay);

                console.log('Hematology initialized rujukan:', {
                    jenis: $row.find('td:first strong').text(),
                    rujukan: rujukanDisplay
                });

                // Juga update keterangan berdasarkan nilai yang ada
                if ($input.val()) {
                    updateHematologyKeterangan($input);
                }
            }
        });

        // ============================================
        // EVENT HANDLER UNTUK INPUT HEMATOLOGY
        // ============================================

        $(document).on('input', '.hasil-input[data-type="hematology"]', function() {
            const $input = $(this);
            const value = $input.val();

            console.log('====== HEMATOLOGY - INPUT BERUBAH ======');
            console.log('Nilai baru:', value);

            // Update keterangan Hematology secara real-time
            updateHematologyKeterangan($input);
        });

        // ============================================
        // EXCEL NAVIGATION KHUSUS UNTUK HEMATOLOGY
        // ============================================

        function initHematologyExcelNavigation() {
            // Select all inputs on focus
            $('.hasil-input[data-type="hematology"]').on('focus', function() {
                $(this).select();
                $(this).closest('td').addClass('table-warning');
            });

            // Remove highlight on blur
            $('.hasil-input[data-type="hematology"]').on('blur', function() {
                $(this).closest('td').removeClass('table-warning');
            });

            // Excel-like keyboard navigation
            $('.hasil-input[data-type="hematology"]').on('keydown', function(e) {
                const $current = $(this);
                const $row = $current.closest('tr');
                const $cell = $current.closest('td');
                const cellIndex = $cell.index();
                const $rows = $row.closest('tbody').find('tr');
                const rowIndex = $rows.index($row);

                switch (e.key) {
                    case 'Enter':
                    case 'ArrowDown':
                        e.preventDefault();
                        const $nextRow = $rows.eq(rowIndex + 1);
                        if ($nextRow.length) {
                            const $nextInput = $nextRow.find('td').eq(cellIndex).find('.hasil-input[data-type="hematology"]');
                            if ($nextInput.length) {
                                $nextInput.focus().select();
                            }
                        }
                        break;

                    case 'ArrowUp':
                        e.preventDefault();
                        const $upRow = $rows.eq(rowIndex - 1);
                        if ($upRow.length) {
                            const $upInput = $upRow.find('td').eq(cellIndex).find('.hasil-input[data-type="hematology"]');
                            if ($upInput.length) {
                                $upInput.focus().select();
                            }
                        }
                        break;

                    case 'ArrowRight':
                        e.preventDefault();
                        const $nextCell = $row.find('td').eq(cellIndex + 1).find('.hasil-input[data-type="hematology"]');
                        if ($nextCell.length) {
                            $nextCell.focus().select();
                        }
                        break;

                    case 'ArrowLeft':
                        e.preventDefault();
                        const $prevCell = $row.find('td').eq(cellIndex - 1).find('.hasil-input[data-type="hematology"]');
                        if ($prevCell.length) {
                            $prevCell.focus().select();
                        }
                        break;
                }
            });
        }

        // Initialize Excel navigation untuk Hematology
        initHematologyExcelNavigation();
    });
</script>
<!-- AWAL SCRIPT REALTIME UPDATE SYSTEM -->
<script>
    $(document).ready(function() {
        console.log('🚀 === Realtime Update System Loading... ===');

        // 1. AMBIL CSRF TOKEN
        const csrfToken = $('meta[name="csrf-token"]').attr('content') || $('#csrf_token').val();
        if (!csrfToken) {
            console.error('❌ CSRF Token tidak ditemukan!');
            return;
        }
        console.log('✅ CSRF Token ditemukan');

        // 2. AMBIL NO LAB DARI URL
        function extractNoLabFromUrl() {
            const segments = window.location.pathname.split('/').filter(s => s.trim() !== '');
            for (let i = segments.length - 1; i >= 0; i--) {
                const segment = segments[i];
                const excluded = ['edit', 'show', 'detail', 'update', 'create', 'pasien', 'patient'];
                if (!excluded.includes(segment.toLowerCase()) && segment.length > 0) {
                    return segment;
                }
            }
            return null;
        }

        const noLab = extractNoLabFromUrl();
        if (!noLab) {
            console.error('❌ No Lab tidak ditemukan!');
            return;
        }

        console.log('✅ No Lab:', noLab);
        window.pasienNoLab = noLab;

        // 3. VARIABEL
        let debounceTimers = {};
        const SAVE_DELAY = 800;

        // ============================================
        // FUNGSI VALIDASI TANGGAL
        // ============================================

        function validateDateInput(dateStr) {
            if (!dateStr || dateStr.trim() === '') {
                return {
                    isValid: false,
                    error: 'Tanggal tidak boleh kosong'
                };
            }

            // Format YYYY-MM-DD (dari input type="date")
            const regex = /^(\d{4})-(\d{2})-(\d{2})$/;
            const match = dateStr.match(regex);

            if (!match) {
                return {
                    isValid: false,
                    error: 'Format tanggal tidak valid'
                };
            }

            const [, year, month, day] = match;
            const dayNum = parseInt(day);
            const monthNum = parseInt(month);
            const yearNum = parseInt(year);

            // Validasi tahun 1900-sekarang
            const currentYear = new Date().getFullYear();
            if (yearNum < 1900 || yearNum > currentYear) {
                return {
                    isValid: false,
                    error: `Tahun harus antara 1900-${currentYear}`
                };
            }

            // Validasi bulan 01-12
            if (monthNum < 1 || monthNum > 12) {
                return {
                    isValid: false,
                    error: 'Bulan tidak valid'
                };
            }

            // Validasi hari berdasarkan bulan
            const daysInMonth = new Date(yearNum, monthNum, 0).getDate();
            if (dayNum < 1 || dayNum > daysInMonth) {
                return {
                    isValid: false,
                    error: 'Tanggal tidak valid untuk bulan tersebut'
                };
            }

            // Validasi tanggal tidak di masa depan
            const inputDate = new Date(yearNum, monthNum - 1, dayNum);
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            if (inputDate > today) {
                return {
                    isValid: false,
                    error: 'Tanggal lahir tidak boleh di masa depan'
                };
            }

            return {
                isValid: true,
                dbFormat: dateStr, // YYYY-MM-DD untuk database
                dateObj: inputDate
            };
        }

        // ============================================
        // FUNGSI HITUNG UMUR DENGAN FORMAT "X Tahun Y Bulan Z Hari"
        // ============================================

        function calculateAgeWithFormat(birthDateStr) {
            const validation = validateDateInput(birthDateStr);
            if (!validation.isValid) {
                return {
                    success: false,
                    umur: '',
                    error: validation.error
                };
            }

            try {
                const birth = validation.dateObj;
                const today = new Date();

                // Normalisasi waktu
                birth.setHours(0, 0, 0, 0);
                today.setHours(0, 0, 0, 0);

                let years = today.getFullYear() - birth.getFullYear();
                let months = today.getMonth() - birth.getMonth();
                let days = today.getDate() - birth.getDate();

                // Koreksi jika hari negatif
                if (days < 0) {
                    months--;
                    const lastMonth = new Date(today.getFullYear(), today.getMonth(), 0);
                    days += lastMonth.getDate();
                }

                // Koreksi jika bulan negatif
                if (months < 0) {
                    years--;
                    months += 12;
                }

                // Pastikan tidak negatif
                years = Math.max(0, years);
                months = Math.max(0, months);
                days = Math.max(0, days);

                // Format hasil: "X Tahun Y Bulan Z Hari"
                let result = '';
                if (years > 0) result += years + ' Tahun ';
                if (months > 0 || years > 0) result += months + ' Bulan ';
                result += days + ' Hari';

                const umurFormatted = result.trim();

                return {
                    success: true,
                    umur: umurFormatted,
                    dbFormat: validation.dbFormat,
                    details: {
                        years: years,
                        months: months,
                        days: days
                    }
                };

            } catch (e) {
                console.error('Error calculateAgeWithFormat:', e);
                return {
                    success: false,
                    umur: '',
                    error: 'Gagal menghitung umur'
                };
            }
        }

        // ============================================
        // FUNGSI KIRIM KE SERVER
        // ============================================

        function sendToServer(field, value, $element) {
            console.log(`📤 Kirim ${field}:`, value);

            // Tampilkan status saving
            showSavingStatus($element);

            $.ajax({
                url: '/pasien/update-realtime',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                data: {
                    no_lab: noLab,
                    field: field,
                    value: value
                },
                timeout: 5000,
                success: function(response) {
                    console.log(`✅ Response ${field}:`, response);

                    if (response.success) {
                        // Update timestamp
                        if (response.data?.updated_at) {
                            $('#lastSaved').text(`Terakhir disimpan: ${response.data.updated_at}`);
                        }

                        if (field === 'tgl_lahir') {
                            console.log('ℹ️ Umur tidak diupdate dari server (pakai hitungan frontend)');
                        }

                        // Hapus status changed
                        $element.removeClass('is-changed');
                        updateSaveStatus();

                        // Tampilkan success
                        showSuccessStatus($element);

                    } else {
                        showErrorStatus($element, response.message);
                        console.error('Server error:', response.message);
                    }
                },

                error: function(xhr) {
                    console.error(`❌ AJAX Error ${field}:`, xhr);
                    showErrorStatus($element, 'Gagal menyimpan');
                }
            });
        }

        // ============================================
        // FUNGSI DEBOUNCE
        // ============================================

        function debounceSave(field, value, $element) {
            // Tandai sebagai berubah
            $element.addClass('is-changed');
            updateSaveStatus();
            showFieldChangedStatus($element);

            // Clear timeout lama
            if (debounceTimers[field]) {
                clearTimeout(debounceTimers[field]);
            }

            // Set timeout baru
            debounceTimers[field] = setTimeout(() => {
                console.log(`🚀 Kirim ${field} setelah debounce`);
                sendToServer(field, value, $element);
                delete debounceTimers[field];
            }, SAVE_DELAY);
        }

        // ============================================
        // FUNGSI STATUS INDICATOR
        // ============================================

        function showFieldChangedStatus($element) {
            const $status = findStatusElement($element);
            if ($status.length) {
                $status.html('<i class="fas fa-edit text-warning"></i>').show();
            }
        }

        function showSavingStatus($element) {
            const $status = findStatusElement($element);
            if ($status.length) {
                $status.html('<i class="fas fa-spinner fa-spin text-primary"></i>').show();
            }
        }

        function showSuccessStatus($element) {
            const $status = findStatusElement($element);
            if ($status.length) {
                $status.html('<i class="fas fa-check text-success"></i>');
                setTimeout(() => $status.fadeOut(500), 1000);
            }
        }

        function showErrorStatus($element, message) {
            const $status = findStatusElement($element);
            if ($status.length) {
                $status.html(`<i class="fas fa-times text-danger"></i>`);
                setTimeout(() => $status.fadeOut(500), 2000);
            }
        }

        function findStatusElement($element) {
            return $element.closest('.input-group').find('.save-status') ||
                $element.siblings('.save-status') ||
                $element.closest('.position-relative').find('.save-status') ||
                $element.closest('.mb-3').find('.save-status');
        }

        function updateSaveStatus() {
            const changedCount = $('.is-changed').length;
            const $status = $('#saveStatus');

            if (changedCount > 0) {
                $status.removeClass('bg-secondary').addClass('bg-warning')
                    .html(`<i class="ri-edit-line me-1"></i>${changedCount} perubahan`);
            } else {
                $status.removeClass('bg-warning').addClass('bg-secondary')
                    .html('<i class="ri-check-line me-1"></i>Tersimpan');
            }
        }

        function formatAgeShort(details) {
            const y = Math.max(0, parseInt(details.years || 0));
            const m = Math.max(0, parseInt(details.months || 0));
            const d = Math.max(0, parseInt(details.days || 0));
            return `${y} Th ${m} Bln ${d} Hari`;
        }

        // Event saat tanggal berubah
        // ============================================
        // EVENT HANDLERS - TANGGAL LAHIR (DIPERBAIKI)
        // ============================================


        $(document).on('input change', '.realtime-date', function() {
            const $this = $(this);
            const dateStr = $this.val(); // Format: YYYY-MM-DD

            console.log('📅 Date changed:', dateStr);

            // Jika tanggal dihapus
            if (!dateStr) {
                $('#display_umur').val('');
                // Tandai perubahan dan kirim kosong (pakai debounce agar konsisten)
                debounceSave('tgl_lahir', '', $this);
                // juga hapus umur di DB jika perlu
                debounceSave('umur', '', $this);
                return;
            }

            // Validasi tanggal terlebih dahulu
            const validation = validateDateInput(dateStr);
            if (!validation.isValid) {
                alert(validation.error);
                $this.val('').focus();
                return;
            }

            // Hitung umur secara sinkron dan pastikan format benar
            const ageResult = calculateAgeWithFormat(dateStr);
            if (!ageResult.success) {
                alert(ageResult.error || 'Gagal menghitung umur');
                $this.val('').focus();
                return;
            }

            // 1) Update display umur segera di UI (tanpa menunggu request)
            //    Gunakan short format sesuai permintaan: "0 Th 2 Bln 11 Hari"
            const shortUmur = formatAgeShort(ageResult.details);
            $('#display_umur').val(shortUmur);
            console.log('👶 Umur dihitung (lokal, short):', shortUmur);

            // 2) Simpan tgl_lahir ke server (gunakan debounce seperti biasa)
            debounceSave('tgl_lahir', dateStr, $this);

            // 3) Simpan umur (short format) ke server juga (pakai debounce agar tidak spam)
            debounceSave('umur', shortUmur, $this);
        });

        // ============================================
        // EVENT HANDLERS - FIELD LAINNYA
        // ============================================

        // Text input
        $(document).on('input', '.realtime-field:not(.realtime-date)', function() {
            const $this = $(this);
            const field = $this.data('field');
            const value = $this.val().trim();

            if (field) {
                debounceSave(field, value, $this);
            }
        });

        // Select dropdown
        $(document).on('change', '.realtime-select', function() {
            const $this = $(this);
            const field = $this.data('field');
            const value = $this.val();

            if (field) {
                $this.addClass('is-changed');
                updateSaveStatus();
                showFieldChangedStatus($this);
                sendToServer(field, value, $this);
            }
        });

        // Event handler untuk datetime input
        $(document).on('change', '.realtime-datetime', function() {
            const $this = $(this);
            const field = $this.data('field'); // waktu_periksa atau waktu_validasi
            const value = $this.val(); // Format: YYYY-MM-DDTHH:mm

            if (field) {
                console.log(`Datetime ${field} changed:`, value);

                // Tandai sebagai berubah
                $this.addClass('is-changed');
                updateSaveStatus();
                showFieldChangedStatus($this);

                // Gunakan sistem debounce yang sama
                debounceSave(field, value, $this);
            }
        });

        // Textarea
        $(document).on('input', 'textarea.realtime-field', function() {
            const $this = $(this);
            const field = $this.data('field');
            const value = $this.val().trim();

            // Auto-resize
            $this.height('auto').height($this.prop('scrollHeight'));

            if (field) {
                debounceSave(field, value, $this);
            }
        });

        // ============================================
        // INISIALISASI SAAT PAGE LOAD
        // ============================================

        function initializePage() {
            console.log('🔍 Inisialisasi data pasien...');

            // 1. Hitung ulang umur jika ada tanggal lahir
            const $tglLahir = $('[data-field="tgl_lahir"]');
            const $displayUmur = $('#display_umur');
            const currentUmur = $displayUmur.val();
            const currentDate = $tglLahir.val();

            console.log('Tanggal lahir awal:', currentDate);
            console.log('Umur awal dari database:', currentUmur);

            if (currentDate) {
                // Hitung ulang umur untuk memastikan format konsisten
                const ageResult = calculateAgeWithFormat(currentDate);
                if (ageResult.success) {
                    // short format untuk disimpan: "0 Th 2 Bln 11 Hari"
                    const calculatedShort = formatAgeShort(ageResult.details);

                    // Update display selalu dengan short format
                    $displayUmur.val(calculatedShort);

                    // Jika umur di database berbeda dengan hasil hitungan, update ke server
                    if (currentUmur !== calculatedShort) {
                        console.log('🔄 Auto update umur (short) ke server:', {
                            database: currentUmur,
                            calculated: calculatedShort
                        });

                        // Kirim langsung (bukan debounce) supaya segera tersimpan saat buka halaman
                        sendToServer('umur', calculatedShort, $tglLahir);
                    } else {
                        console.log('✅ Umur sudah realtime & sinkron (short format)');
                    }
                }
            }

            // 2. Set original value untuk reset
            $('.realtime-field, .realtime-select, .realtime-date').each(function() {
                $(this).data('original', $(this).val());
            });

            console.log('✅ Inisialisasi selesai');
        }


        // Jalankan setelah page load
        setTimeout(initializePage, 500);

        // ============================================
        // SAVE ALL FUNCTION - DIPERBAIKI
        // ============================================

        $('#saveAllBtn').on('click', function() {
            const $btn = $(this);
            const originalText = $btn.html();

            $btn.prop('disabled', true).html('<i class="ri-loader-4-line spin me-1"></i>Menyimpan...');

            // Cek apakah ada perubahan tgl_lahir yang belum disimpan
            const $tglLahirField = $('[data-field="tgl_lahir"]');
            const tglLahirChanged = $tglLahirField.hasClass('is-changed');
            const currentTglLahir = $tglLahirField.val();

            // Jika tgl_lahir berubah, hitung ulang umur
            if (tglLahirChanged && currentTglLahir) {
                const ageResult = calculateAgeWithFormat(currentTglLahir);
                if (ageResult.success) {
                    // Update display umur
                    $('#display_umur').val(ageResult.umur);
                }
            }

            // Simpan semua field yang berubah
            const changedFields = $('.is-changed');
            let savedCount = 0;
            const totalCount = changedFields.length;

            if (totalCount === 0) {
                if (typeof showToast === 'function') {
                    showToast('info', 'Tidak ada perubahan untuk disimpan');
                }
                $btn.prop('disabled', false).html(originalText);
                return;
            }

            changedFields.each(function() {
                const $field = $(this);
                const field = $field.data('field');
                const value = $field.val();

                if (field) {
                    $.ajax({
                        url: '/pasien/update-realtime',
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        data: {
                            no_lab: noLab,
                            field: field,
                            value: value
                        },
                        success: function(response) {
                            savedCount++;
                            $field.removeClass('is-changed');

                            if (savedCount === totalCount) {
                                updateSaveStatus();
                                $btn.prop('disabled', false).html(originalText);

                                if (typeof showToast === 'function') {
                                    showToast('success', `Semua ${totalCount} perubahan berhasil disimpan`);
                                }
                            }
                        },
                        error: function() {
                            savedCount++;
                            if (savedCount === totalCount) {
                                $btn.prop('disabled', false).html(originalText);
                            }
                        }
                    });
                }
            });
        });

        // ============================================
        // BEFORE UNLOAD WARNING
        // ============================================

        $(window).on('beforeunload', function() {
            const changedCount = $('.is-changed').length;
            if (changedCount > 0) {
                return 'Ada perubahan belum disimpan. Yakin ingin keluar?';
            }
        });

        console.log('🎉 === Sistem Realtime Update Siap ===');
        console.log('📅 Datepicker: Input type="date" native browser');
        console.log('👶 Format umur: "X Tahun Y Bulan Z Hari"');
    });
</script>
<!-- AKHIR SCRIPT REALTIME UPDATE SYSTEM -->

<!-- AWAL SCRIPT MODAL PEMERIKSAAN SYSTEM -->
<script>
    $(document).ready(function() {
        console.log('=== MODAL PEMERIKSAAN SYSTEM INITIALIZING ===');

        const csrfToken = $('#csrf_token').val();
        let selectedPemeriksaan = [];
        let currentJenisPemeriksaan = null;
        let currentTableSection = null;
        let selectionOrder = 0;

        // Modal instance
        const pilihPemeriksaanModal = new bootstrap.Modal(document.getElementById('pilihPemeriksaanModal'));
        console.log('Modal instance created');

        // Fungsi untuk membuka modal
        $(document).on('click', '.tambah-row-btn-modal', function(e) {
            e.preventDefault();
            console.log('=== TAMBAH ROW MODAL CLICKED ===');

            const jenisPemeriksaan = $(this).data('jenis-pemeriksaan');
            const $section = $(this).closest('.pemeriksaan-lain-section');

            console.log('Jenis Pemeriksaan:', jenisPemeriksaan);
            console.log('Table Section:', $section.length ? 'Found' : 'Not found');

            currentJenisPemeriksaan = jenisPemeriksaan;
            currentTableSection = $section;

            // Reset selection
            selectedPemeriksaan = [];
            selectionOrder = 0;
            console.log('Selection reset');

            // Load data pemeriksaan untuk jenis ini
            loadPemeriksaanByJenis(jenisPemeriksaan);

            // Show modal
            pilihPemeriksaanModal.show();
            console.log('Modal shown');
        });

        // Fungsi untuk load data pemeriksaan berdasarkan jenis
        function loadPemeriksaanByJenis(jenisPemeriksaan) {
            console.log('=== LOAD PEMERIKSAAN BY JENIS ===');
            console.log('Jenis:', jenisPemeriksaan);

            const $list = $('#pemeriksaanList');
            const $noResults = $('#noResults');

            // Show loading
            $list.html(`
                <tr>
                    <td colspan="5" class="text-center py-4">
                        <div class="spinner-border spinner-border-sm text-primary me-2"></div>
                        Memuat data pemeriksaan dari database...
                    </td>
                </tr>
            `);

            $.ajax({
                url: '{{ route("hasil-lain.get-pemeriksaan-by-jenis") }}',
                method: 'GET',
                data: {
                    jenis_pemeriksaan: jenisPemeriksaan
                },
                beforeSend: function() {
                    console.log('AJAX request sent to:', '{{ route("hasil-lain.get-pemeriksaan-by-jenis") }}');
                    console.log('Request data:', { jenis_pemeriksaan: jenisPemeriksaan });
                },
                success: function(response) {
                    console.log('=== AJAX RESPONSE SUCCESS ===');
                    console.log('Response status:', response.success);
                    console.log('Data length:', response.data ? response.data.length : 0);
                    console.log('Sample data:', response.data ? response.data[0] : 'No data');

                    if (response.success && response.data.length > 0) {
                        let html = '';

                        response.data.forEach((item, index) => {
                            // Cek apakah sudah ada di tabel
                            const existingRow = currentTableSection.find(`.kode-pemeriksaan-input[value="${item.id_data_pemeriksaan}"]`);
                            const isDisabled = existingRow.length > 0;

                            console.log(`Item ${index}:`, {
                                kode: item.id_data_pemeriksaan,
                                nama: item.data_pemeriksaan,
                                sudah_ada: isDisabled
                            });

                            // Di bagian modal checkbox, update untuk tampilkan CH dan CL:
                            html += `
                                <tr class="pemeriksaan-item ${isDisabled ? 'table-light' : ''}"
                                    data-kode="${item.id_data_pemeriksaan}"
                                    data-data-pemeriksaan="${item.data_pemeriksaan}"
                                    data-satuan="${item.satuan || ''}"
                                    data-rujukan="${item.rujukan || ''}"
                                    data-metode="${item.metode || ''}"
                                    data-ch="${item.ch || ''}"
                                    data-cl="${item.cl || ''}">
                                    <td class="text-center">
                                        <div class="form-check">
                                            <input type="checkbox"
                                                class="form-check-input pemeriksaan-checkbox"
                                                id="pemeriksaan_${index}"
                                                data-kode="${item.id_data_pemeriksaan}"
                                                ${isDisabled ? 'disabled' : ''}>
                                            <label class="form-check-label" for="pemeriksaan_${index}"></label>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">${item.id_data_pemeriksaan}</span>
                                    </td>
                                    <td>
                                        <div class="fw-medium">${item.data_pemeriksaan}</div>
                                        ${isDisabled ?
                                            '<small class="text-warning"><i class="ri-alert-line me-1"></i>Sudah ada di tabel</small>' :
                                            ''}
                                    </td>
                                    <td class="text-muted">${item.satuan || '-'}</td>
                                    <td class="text-muted small">${item.rujukan || '-'}</td>
                                    <td class="text-muted small">${item.ch || '-'}</td>
                                    <td class="text-muted small">${item.cl || '-'}</td>
                                </tr>
                            `;
                        });

                        $list.html(html);
                        $noResults.hide();
                        console.log('HTML generated with', response.data.length, 'items');
                    } else {
                        console.log('No data found or empty response');
                        $list.html('');
                        $noResults.show();
                    }

                    updateSelectionCounter();
                },
                error: function(xhr, status, error) {
                    console.error('=== AJAX RESPONSE ERROR ===');
                    console.error('Status:', status);
                    console.error('Error:', error);
                    console.error('Response Text:', xhr.responseText);
                    console.error('Status Code:', xhr.status);

                    $list.html(`
                        <tr>
                            <td colspan="5" class="text-center py-4 text-danger">
                                <i class="ri-error-warning-line me-2"></i>
                                Gagal memuat data dari server
                                <br>
                                <small class="text-muted">Status: ${xhr.status} - ${error}</small>
                            </td>
                        </tr>
                    `);

                    if (typeof window.showToast === 'function') {
                        window.showToast('danger', 'Gagal memuat data pemeriksaan');
                    }
                }
            });
        }

        // Pencarian pemeriksaan
        $('#searchPemeriksaanInput').on('input', function() {
            const searchTerm = $(this).val().toLowerCase();
            console.log('Search term:', searchTerm);

            if (!searchTerm) {
                $('#pemeriksaanList tr').show();
                console.log('Search cleared, showing all rows');
                return;
            }

            let visibleCount = 0;
            $('#pemeriksaanList tr').each(function() {
                const $row = $(this);
                const kode = $row.data('kode') || '';
                const nama = $row.data('data-pemeriksaan') || '';
                const text = (kode + ' ' + nama).toLowerCase();

                if (text.includes(searchTerm)) {
                    $row.show();
                    visibleCount++;
                } else {
                    $row.hide();
                }
            });

            console.log('Visible rows after search:', visibleCount);
            updateSelectionCounter();
        });

        // Clear search
        $('#clearSearchBtn').on('click', function() {
            console.log('Clear search clicked');
            $('#searchPemeriksaanInput').val('').trigger('input');
        });

        // Centang satu pemeriksaan dengan urutan
        $(document).on('change', '.pemeriksaan-checkbox:not([disabled])', function() {
            const $checkbox = $(this);
            const isChecked = $checkbox.prop('checked');
            const $row = $checkbox.closest('tr');
            const kode = $row.data('kode');
            const dataPemeriksaan = $row.data('data-pemeriksaan');

            console.log('=== CHECKBOX CHANGE ===');
            console.log('Kode:', kode);
            console.log('Pemeriksaan:', dataPemeriksaan);
            console.log('Checked:', isChecked);

            if (isChecked) {
                // Tambah dengan urutan
                selectionOrder++;
                const newItem = {
                    kode: kode,
                    data_pemeriksaan: dataPemeriksaan,
                    satuan: $row.data('satuan'),
                    rujukan: $row.data('rujukan'),
                    metode: $row.data('metode'),
                    order: selectionOrder
                };

                selectedPemeriksaan.push(newItem);
                $row.addClass('selected');

                // Tambah badge order
                $row.find('td:first').append(`
                    <span class="selected-order">${selectionOrder}</span>
                `);

                console.log('Item added with order', selectionOrder);
                console.log('Selected items:', selectedPemeriksaan);
            } else {
                // Hapus dari selection
                const index = selectedPemeriksaan.findIndex(item => item.kode === kode);
                console.log('Removing item at index:', index);

                if (index > -1) {
                    const removedOrder = selectedPemeriksaan[index].order;
                    selectedPemeriksaan.splice(index, 1);

                    console.log('Removed order:', removedOrder);
                    console.log('Selected items after removal:', selectedPemeriksaan);

                    // Update order untuk yang lain
                    selectedPemeriksaan.forEach(item => {
                        if (item.order > removedOrder) {
                            item.order--;
                        }
                    });
                    selectionOrder--;

                    console.log('Updated orders:', selectedPemeriksaan.map(item => item.order));

                    // Update tampilan order
                    $('#pemeriksaanList tr').each(function() {
                        const $row = $(this);
                        const rowKode = $row.data('kode');
                        const item = selectedPemeriksaan.find(p => p.kode === rowKode);

                        $row.find('.selected-order').remove();
                        if (item) {
                            $row.find('td:first').append(`
                                <span class="selected-order">${item.order}</span>
                            `);
                            console.log('Updated order badge for', rowKode, 'to', item.order);
                        }
                    });
                }
                $row.removeClass('selected');
                $row.find('.selected-order').remove();
            }

            updateSelectionCounter();
            updatePreview();
        });

        // Centang semua
        $('#selectAllBtn').on('click', function() {
            console.log('=== SELECT ALL CLICKED ===');
            selectionOrder = 0;
            selectedPemeriksaan = [];

            let checkedCount = 0;
            $('#pemeriksaanList tr:visible').each(function() {
                const $row = $(this);
                const $checkbox = $row.find('.pemeriksaan-checkbox:not([disabled])');

                if ($checkbox.length && $row.is(':visible')) {
                    selectionOrder++;
                    $checkbox.prop('checked', true);

                    selectedPemeriksaan.push({
                        kode: $row.data('kode'),
                        data_pemeriksaan: $row.data('data-pemeriksaan'),
                        satuan: $row.data('satuan'),
                        rujukan: $row.data('rujukan'),
                        metode: $row.data('metode'),
                        order: selectionOrder
                    });

                    $row.addClass('selected');
                    $row.find('.selected-order').remove();
                    $row.find('td:first').append(`
                        <span class="selected-order">${selectionOrder}</span>
                    `);
                    checkedCount++;
                }
            });

            console.log('Selected', checkedCount, 'items');
            console.log('Selected items:', selectedPemeriksaan);

            updateSelectionCounter();
            updatePreview();
        });

        // Hapus semua centangan
        $('#deselectAllBtn').on('click', function() {
            console.log('=== DESELECT ALL CLICKED ===');

            $('.pemeriksaan-checkbox').prop('checked', false);
            $('#pemeriksaanList tr').removeClass('selected');
            $('.selected-order').remove();
            selectedPemeriksaan = [];
            selectionOrder = 0;

            console.log('All items deselected');

            updateSelectionCounter();
            updatePreview();
        });

        // Select all checkbox
        $('#selectAllCheckbox').on('change', function() {
            const isChecked = $(this).prop('checked');
            console.log('Select All Checkbox changed:', isChecked);

            if (isChecked) {
                $('#selectAllBtn').click();
            } else {
                $('#deselectAllBtn').click();
            }
        });

        // Update counter
        function updateSelectionCounter() {
            const visibleCount = $('#pemeriksaanList tr:visible').length;
            const selectedCount = selectedPemeriksaan.length;

            $('#selectedCount').text(`${selectedCount} dipilih`);

            // Update select all checkbox state
            const allChecked = $('#pemeriksaanList tr:visible .pemeriksaan-checkbox:not([disabled])').length ===
                            $('#pemeriksaanList tr:visible .pemeriksaan-checkbox:not([disabled]):checked').length;
            $('#selectAllCheckbox').prop('checked', allChecked && visibleCount > 0);

            console.log('Selection counter updated:', {
                visible: visibleCount,
                selected: selectedCount,
                allChecked: allChecked
            });
        }

        // Update preview
        function updatePreview() {
            console.log('=== UPDATE PREVIEW ===');
            console.log('Selected items:', selectedPemeriksaan);

            const preview = selectedPemeriksaan
                .sort((a, b) => a.order - b.order)
                .slice(0, 5) // Tampilkan maks 5 item
                .map(item => `<span class="preview-badge">${item.data_pemeriksaan}</span>`)
                .join('');

            const moreText = selectedPemeriksaan.length > 5 ?
                `<span class="text-muted">+${selectedPemeriksaan.length - 5} lagi</span>` : '';

            $('#selectedItemsPreview').html(preview + moreText);

            console.log('Preview updated');
        }

        // Tombol tambah row langsung (tanpa modal)
        $(document).on('click', '.tambah-row-btn:not(.tambah-row-btn-modal)', function(e) {
            e.preventDefault();
            const jenisPemeriksaan = $(this).data('jenis-pemeriksaan');
            const $section = $(`.pemeriksaan-lain-section[data-jenis-pemeriksaan="${jenisPemeriksaan}"]`);

            console.log('Tambah row langsung untuk jenis:', jenisPemeriksaan);

            // Hitung row index
            const rowCount = $section.find('tbody tr').length;
            const index = rowCount;

            // Di bagian newRow template untuk hasil_lain, tambahkan kolom CH dan CL:
            const newRow = `

                <td class="bg-light" hidden>
                    <strong class="jenis-pengujian-display">Belum dipilih</strong>
                    <input type="hidden"
                        name="hasil_lain[${jenisPemeriksaan}][${index}][id]"
                        value="">
                    <input type="hidden"
                        name="hasil_lain[${jenisPemeriksaan}][${index}][jenis_pengujian]"
                        value="">
                </td>
                <td class="search-cell" style="position: relative;">
                    <div class="position-relative">
                        <input type="text"
                            class="form-control form-control-sm kode-search-input-lain"
                            placeholder="Cari kode pemeriksaan..."
                            data-jenis-pemeriksaan="${jenisPemeriksaan}"
                            data-index="${index}"
                            data-row-id=""
                            autocomplete="off">
                        <input type="hidden"
                            name="hasil_lain[${jenisPemeriksaan}][${index}][id_data_pemeriksaan]"
                            class="kode-pemeriksaan-input"
                            value="">
                    </div>
                </td>
                <td class="bg-light satuan-cell" style="text-align:center;">
                    <span class="satuan-display">-</span>
                </td>
                <td class="bg-light rujukan-cell" style="text-align:center;">
                    <span class="rujukan-display">-</span>
                </td>
                <td class="bg-light ch-cell" style="text-align:center;">
                    <span class="ch-display">-</span>
                    <input type="hidden"
                        name="hasil_lain[${jenisPemeriksaan}][${index}][ch]"
                        class="ch-input"
                        value="-">
                </td>
                <td class="bg-light cl-cell" style="text-align:center;">
                    <span class="cl-display">-</span>
                    <input type="hidden"
                        name="hasil_lain[${jenisPemeriksaan}][${index}][cl]"
                        class="cl-input"
                        value="-">
                </td>
                <td class="hasil-cell">
                    <input type="text"
                        name="hasil_lain[${jenisPemeriksaan}][${index}][hasil_pengujian]"
                        class="form-control form-control-sm excel-input hasil-input-lain"
                        value=""
                        placeholder="Hasil"
                        data-original=""
                        data-id=""
                        data-type="hasil_lain"
                        data-rujukan=""
                        data-ch=""
                        data-cl=""
                        autocomplete="off">
                </td>
                <td class="keterangan-cell">
                    <div class="keterangan-display bg-success bg-opacity-10 text-success rounded py-1 px-2 text-center"
                        data-keterangan="-">
                        <strong>-</strong>
                    </div>
                    <input type="hidden"
                        name="hasil_lain[${jenisPemeriksaan}][${index}][keterangan]"
                        value="-">
                </td>
                <td>
                    <button type="button" class="btn btn-sm btn-outline-danger hapus-row-btn">
                        <i class="ri-delete-bin-line"></i>
                    </button>
                </td>
            </tr><tr data-index="${index}" data-jenis-pemeriksaan="${jenisPemeriksaan}" data-is-new="true">
            `;

            $section.find('tbody').append(newRow);

            // Focus ke input kode
            setTimeout(() => {
                $section.find('tbody tr:last-child .kode-search-input-lain').focus();
            }, 100);
        });

        // Tombol buka modal (checkbox)
        $(document).on('click', '.tambah-modal-btn', function(e) {
            e.preventDefault();
            console.log('=== BUKA MODAL CHECKBOX ===');

            const jenisPemeriksaan = $(this).data('jenis-pemeriksaan');
            const $section = $(this).closest('.pemeriksaan-lain-section');

            console.log('Jenis Pemeriksaan:', jenisPemeriksaan);
            console.log('Table Section:', $section.length ? 'Found' : 'Not found');

            currentJenisPemeriksaan = jenisPemeriksaan;
            currentTableSection = $section;

            // Reset selection
            selectedPemeriksaan = [];
            selectionOrder = 0;
            console.log('Selection reset');

            // Load data pemeriksaan untuk jenis ini
            loadPemeriksaanByJenis(jenisPemeriksaan);

            // Show modal
            pilihPemeriksaanModal.show();
            console.log('Modal shown');
        });



        // Terapkan ke tabel
        $('#applyToTableBtn').on('click', function() {
            console.log('=== APPLY TO TABLE CLICKED ===');
            console.log('Selected items:', selectedPemeriksaan);

            if (selectedPemeriksaan.length === 0) {
                console.log('No items selected, showing warning');
                if (typeof window.showToast === 'function') {
                    window.showToast('warning', 'Pilih minimal satu pemeriksaan');
                }
                return;
            }

            // Urutkan berdasarkan order
            const sortedPemeriksaan = [...selectedPemeriksaan].sort((a, b) => a.order - b.order);
            console.log('Sorted items:', sortedPemeriksaan);

            // Dapatkan tbody
            const $tbody = currentTableSection.find('tbody');
            const existingRows = $tbody.find('tr').length;

            console.log('Target table section:', currentTableSection.data('jenis-pemeriksaan'));
            console.log('Existing rows:', existingRows);
            console.log('Adding', sortedPemeriksaan.length, 'new rows');

            // Tambahkan setiap pemeriksaan sebagai row baru
            sortedPemeriksaan.forEach((item, index) => {
                const rowIndex = existingRows + index;

                console.log(`Adding row ${index}:`, {
                    rowIndex: rowIndex,
                    kode: item.kode,
                    nama: item.data_pemeriksaan,
                    satuan: item.satuan,
                    rujukan: item.rujukan
                });

                // Di bagian apply to table untuk hasil lain, update dengan CH dan CL:
                const newRow = `
                    <tr data-index="${rowIndex}"
                        data-jenis-pemeriksaan="${currentJenisPemeriksaan}"
                        data-is-new="true">
                        <td class="bg-light" hidden>
                            <strong class="jenis-pengujian-display">${item.data_pemeriksaan}</strong>
                            <input type="hidden"
                                name="hasil_lain[${currentJenisPemeriksaan}][${rowIndex}][id]"
                                value="">
                            <input type="hidden"
                                name="hasil_lain[${currentJenisPemeriksaan}][${rowIndex}][jenis_pengujian]"
                                value="${item.data_pemeriksaan}">
                        </td>
                        <td class="search-cell" style="position: relative;">
                            <div class="position-relative">
                                <input type="text"
                                    class="form-control form-control-sm kode-edit-input-lain"
                                    placeholder="Cari kode pemeriksaan..."
                                    value="${item.data_pemeriksaan}"
                                    data-jenis-pemeriksaan="${currentJenisPemeriksaan}"
                                    data-index="${rowIndex}"
                                    data-row-id=""
                                    data-current-kode="${item.kode}"
                                    autocomplete="off">
                                <input type="hidden"
                                    name="hasil_lain[${currentJenisPemeriksaan}][${rowIndex}][id_data_pemeriksaan]"
                                    class="kode-pemeriksaan-input"
                                    value="${item.kode}">
                            </div>
                        </td>
                        <td class="bg-light satuan-cell" style="text-align:center;">
                            <span class="satuan-display">${item.satuan || '-'}</span>
                        </td>
                        <td class="bg-light rujukan-cell" style="text-align:center;">
                            <span class="rujukan-display">${item.rujukan || '-'}</span>
                        </td>
                        <td class="bg-light ch-cell" style="text-align:center;">
                            <span class="ch-display">${item.ch || '-'}</span>
                            <input type="hidden"
                                name="hasil_lain[${currentJenisPemeriksaan}][${rowIndex}][ch]"
                                class="ch-input"
                                value="${item.ch || '-'}">
                        </td>
                        <td class="bg-light cl-cell" style="text-align:center;">
                            <span class="cl-display">${item.cl || '-'}</span>
                            <input type="hidden"
                                name="hasil_lain[${currentJenisPemeriksaan}][${rowIndex}][cl]"
                                class="cl-input"
                                value="${item.cl || '-'}">
                        </td>
                        <td class="hasil-cell">
                            <input type="text"
                                name="hasil_lain[${currentJenisPemeriksaan}][${rowIndex}][hasil_pengujian]"
                                class="form-control form-control-sm excel-input hasil-input-lain"
                                value=""
                                placeholder="Hasil"
                                data-original=""
                                data-id=""
                                data-type="hasil_lain"
                                data-rujukan="${item.rujukan || ''}"
                                data-ch="${item.ch || ''}"
                                data-cl="${item.cl || ''}"
                                autocomplete="off">
                        </td>
                        <td class="keterangan-cell">
                            <div class="keterangan-display bg-success bg-opacity-10 text-success rounded py-1 px-2 text-center"
                                data-keterangan="-">
                                <strong>-</strong>
                            </div>
                            <input type="hidden"
                                name="hasil_lain[${currentJenisPemeriksaan}][${rowIndex}][keterangan]"
                                value="-">
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-outline-danger hapus-row-btn">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </td>
                    </tr>
                `;

                $tbody.append(newRow);
                console.log(`Row ${index} added to table`);
            });

            // Tutup modal
            pilihPemeriksaanModal.hide();
            console.log('Modal hidden');

            // Reset modal
            selectedPemeriksaan = [];
            selectionOrder = 0;
            $('#searchPemeriksaanInput').val('');
            $('.selected-order').remove();
            $('.pemeriksaan-checkbox').prop('checked', false);
            $('#pemeriksaanList tr').removeClass('selected');

            // Show success message
            if (typeof window.showToast === 'function') {
                window.showToast('success', `${sortedPemeriksaan.length} pemeriksaan berhasil ditambahkan`);
            }

            console.log('Modal reset completed');

            // Focus ke input hasil pertama yang baru
            setTimeout(() => {
                const $firstInput = $tbody.find('tr:last-child .hasil-input-lain');
                if ($firstInput.length) {
                    $firstInput.focus();
                    console.log('Focused on first input of new rows');
                }
            }, 300);
        });

        // Update tombol tambah row di setiap section existing
        console.log('=== UPDATING EXISTING BUTTONS ===');
        $('.tambah-row-btn').each(function() {
            const $btn = $(this);
            const jenisPemeriksaan = $btn.data('jenis-pemeriksaan');

            console.log('Found old button for jenis:', jenisPemeriksaan);

            $btn.replaceWith(`
                <button type="button" class="btn btn-sm btn-outline-primary tambah-row-btn-modal"
                        data-jenis-pemeriksaan="${jenisPemeriksaan}">
                    <i class="ri-add-line me-1"></i>Tambah Pemeriksaan
                </button>
            `);

            console.log('Button replaced with modal button');
        });

        // Handler untuk tombol "Tambah Tabel Pemeriksaan" yang baru
        // Handler untuk tombol "Tambah Tabel Pemeriksaan" yang baru
        // Handler untuk tombol "Tambah Tabel Pemeriksaan" yang baru
        $(document).on('click', '#tambahTabelBtn', function(e) {
            e.preventDefault();
            console.log('=== TAMBAH TABEL BARU CLICKED ===');

            const jenisPemeriksaan = $('#jenisPemeriksaanSelect').val();
            console.log('Selected jenis:', jenisPemeriksaan);

            if (!jenisPemeriksaan) {
                console.log('No jenis selected, showing warning');
                if (typeof window.showToast === 'function') {
                    window.showToast('warning', 'Pilih jenis pemeriksaan terlebih dahulu');
                }
                return;
            }

            // Cek apakah tabel sudah ada
            if ($(`.pemeriksaan-lain-section[data-jenis-pemeriksaan="${jenisPemeriksaan}"]`).length > 0) {
                console.log('Table already exists for jenis:', jenisPemeriksaan);
                if (typeof window.showToast === 'function') {
                    window.showToast('warning', `Tabel ${jenisPemeriksaan} sudah ada`);
                }
                return;
            }

            console.log('Creating new table for jenis:', jenisPemeriksaan);

            // Buat tabel baru dengan DUA TOMBOL
            const newTableSection = `
            <div class="mt-4 pemeriksaan-lain-section" data-jenis-pemeriksaan="${jenisPemeriksaan}">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0 border-bottom pb-2">
                        <i class="ri-list-check me-2"></i>${jenisPemeriksaan}
                    </h6>
                    <div>
                        <!-- TOMBOL 1: TAMBAH ROW KOSONG -->
                        <button type="button" class="btn btn-sm btn-outline-primary tambah-row-btn"
                                data-jenis-pemeriksaan="${jenisPemeriksaan}">
                            <i class="ri-add-line me-1"></i>Tambah Row
                        </button>

                        <!-- TOMBOL 2: TAMBAH DENGAN MODAL -->
                        <button type="button" class="btn btn-sm btn-outline-success simple-modal-btn ms-2"
                                data-jenis-pemeriksaan="${jenisPemeriksaan}">
                            <i class="ri-list-check me-1"></i>Pilih dari Daftar
                        </button>

                        <button type="button" class="btn btn-sm btn-outline-danger hapus-tabel-btn ms-2"
                                data-jenis-pemeriksaan="${jenisPemeriksaan}">
                            <i class="ri-delete-bin-line me-1"></i>Hapus Tabel
                        </button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm pemeriksaan-lain-table table-row-skip">
                        <thead class="table-light">
                            <tr>
                                <th width="20%" class="bg-light" hidden>Jenis Pengujian</th>
                                <th width="25%" class="bg-light">Pilih Jenis Pemeriksaan</th>
                                <th width="15%" class="bg-light">Satuan</th>
                                <th width="15%" class="bg-light">Rujukan</th>
                                <th width="15%">Hasil Pengujian</th>
                                <th width="10%">Ket</th>
                                <th width="5%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Rows akan ditambahkan melalui salah satu tombol -->
                        </tbody>
                    </table>
                </div>
            </div>
            `;

            // Tambahkan sebelum tombol tambah tabel
            $('#tambahTabelBtn').closest('.card').before(newTableSection);
            console.log('New table section added');

            // Reset select
            $('#jenisPemeriksaanSelect').val('');

            if (typeof window.showToast === 'function') {
                window.showToast('success', `Tabel ${jenisPemeriksaan} berhasil ditambahkan`);
            }
        });

        // Close modal events
        $('#pilihPemeriksaanModal').on('hidden.bs.modal', function() {
            console.log('=== MODAL HIDDEN EVENT ===');

            selectedPemeriksaan = [];
            selectionOrder = 0;
            $('#searchPemeriksaanInput').val('');
            $('.selected-order').remove();
            $('.pemeriksaan-checkbox').prop('checked', false);
            $('#pemeriksaanList tr').removeClass('selected');
            updateSelectionCounter();
            updatePreview();

            console.log('Modal state reset');
        });


        // Test fungsi di console
        window.testModalSystem = function() {
            console.log('=== MODAL SYSTEM TEST ===');
            console.log('Current state:', {
                selectedPemeriksaan: selectedPemeriksaan,
                currentJenisPemeriksaan: currentJenisPemeriksaan,
                selectionOrder: selectionOrder,
                modalVisible: $('#pilihPemeriksaanModal').hasClass('show')
            });
            console.log('CSRF Token:', csrfToken ? 'Present' : 'Missing');
            console.log('Toast function:', typeof window.showToast);
        };

        console.log('=== MODAL PEMERIKSAAN SYSTEM READY ===');
        console.log('Use testModalSystem() to debug');
    });
</script>
<!-- End Modal Pemeriksaan Script -->

<!-- Script untuk sistem pemilihan dokter pengirim -->
<script>
    $(document).ready(function() {
        const csrfToken = $('#csrf_token').val();
        let dokters = [];
        let selectedDokter = null;
        let isProcessing = false;
        let searchTimer = null;

        // Inisialisasi dropdown Bootstrap
        const dokterDropdown = new bootstrap.Dropdown(document.getElementById('pengirimInput'));

        // Function untuk update dropdown content
        function updateDropdownContent(html) {
            const $dropdown = $('#dokterDropdown');
            $dropdown.html(html);

            // Jika ada content, pastikan dropdown terbuka
            if (html && html.trim() !== '<div class="dropdown-item text-muted py-2"><div class="d-flex align-items-center"><i class="ri-search-line me-2"></i><span>Ketik minimal 2 karakter untuk mencari dokter</span></div></div>') {
                dokterDropdown.show();
            }
        }

        // Debounce search
        $('#pengirimInput').on('input', function() {
            const searchTerm = $(this).val().trim();

            clearTimeout(searchTimer);

            if (searchTerm.length < 2) {
                updateDropdownContent(`
                    <div class="dropdown-item text-muted py-2">
                        <div class="d-flex align-items-center">
                            <i class="ri-search-line me-2"></i>
                            <span>Ketik minimal 2 karakter untuk mencari dokter</span>
                        </div>
                    </div>
                `);
                resetDokterSelection();
                return;
            }

            searchTimer = setTimeout(() => {
                searchDokter(searchTerm);
            }, 500);
        });

        // Focus pada input
        $('#pengirimInput').on('focus', function() {
            const searchTerm = $(this).val().trim();
            if (searchTerm.length >= 2) {
                searchDokter(searchTerm);
            }
        });

        // Pilih dokter dari dropdown
        $(document).on('click', '.dokter-option', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const dokterId = $(this).data('id') || null;
            const dokterName = $(this).data('name') || '';
            const noTelp = $(this).data('telp') || '';

            // Update input
            $('#pengirimInput').val(dokterName);
            selectedDokter = {
                id: dokterId,
                name: dokterName,
                no_telp: noTelp
            };

            // Update display info
            updateDokterInfo();

            // Simpan ke database
            savePengirim(dokterName);

            // Update dropdown dengan pesan success
            updateDropdownContent(`
                <div class="dropdown-item text-success py-2">
                    <div class="d-flex align-items-center">
                        <i class="ri-check-line me-2"></i>
                        <span>${dokterName} dipilih</span>
                    </div>
                </div>
            `);

            // Tutup dropdown setelah 1 detik
            setTimeout(() => {
                dokterDropdown.hide();
            }, 1000);
        });

        // Fungsi search dokter
        function searchDokter(term) {
            if (isProcessing) return;

            isProcessing = true;

            updateDropdownContent(`
                <div class="dropdown-item py-2">
                    <div class="d-flex align-items-center">
                        <div class="spinner-border spinner-border-sm text-primary me-2"></div>
                        <span class="text-muted">Mencari dokter...</span>
                    </div>
                </div>
            `);

            $.ajax({
                url: '{{ route("dokter.search") }}',
                method: 'GET',
                data: { search: term },
                success: function(response) {
                    if (response.success && response.data && Array.isArray(response.data)) {
                        dokters = response.data;

                        if (dokters.length > 0) {
                            let html = '';
                            dokters.forEach(dokter => {
                                if (!dokter || !dokter.nama_dokter) return;

                                html += `
                                    <button type="button" class="dropdown-item dokter-option text-start py-2"
                                            data-id="${dokter.id_dokter || ''}"
                                            data-name="${dokter.nama_dokter}"
                                            data-telp="${dokter.no_telp || ''}">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="me-3">
                                                <strong class="d-block">${dokter.nama_dokter}</strong>
                                                ${dokter.no_telp ? `<small class="text-muted">${dokter.no_telp}</small>` : ''}
                                            </div>
                                            <i class="ri-arrow-right-s-line text-muted"></i>
                                        </div>
                                    </button>
                                `;
                            });

                            // Tambahkan opsi "Tambah baru"
                            html += `
                                <div class="dropdown-divider"></div>
                                <button type="button" class="dropdown-item text-primary py-2" id="addNewDokterBtn">
                                    <div class="d-flex align-items-center">
                                        <i class="ri-user-add-line me-2"></i>
                                        <div>
                                            <strong>Tambah dokter baru</strong>
                                            <div class="small text-muted">"${term}"</div>
                                        </div>
                                    </div>
                                </button>
                            `;

                            updateDropdownContent(html);
                        } else {
                            updateDropdownContent(`
                                <button type="button" class="dropdown-item text-start py-2" id="addNewDokterBtn">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="me-3">
                                            <div class="text-primary">
                                                <i class="ri-user-add-line me-1"></i>
                                                Tidak ditemukan
                                            </div>
                                            <small class="text-muted">Klik untuk menambahkan "${term}"</small>
                                        </div>
                                        <i class="ri-arrow-right-s-line text-muted"></i>
                                    </div>
                                </button>
                            `);
                        }
                    } else {
                        updateDropdownContent(`
                            <div class="dropdown-item text-center py-2 text-danger">
                                <i class="ri-error-warning-line me-2"></i>
                                <span>Data tidak valid dari server</span>
                            </div>
                        `);
                    }
                },
                error: function(xhr, status, error) {
                    updateDropdownContent(`
                        <div class="dropdown-item text-center py-2 text-danger">
                            <i class="ri-wifi-off-line me-2"></i>
                            <div>Gagal memuat data dokter</div>
                            <small class="text-muted">${xhr.status}: ${error}</small>
                        </div>
                    `);
                },
                complete: function() {
                    isProcessing = false;
                }
            });
        }

        // Tambah dokter baru dari dropdown
        $(document).on('click', '#addNewDokterBtn', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const searchTerm = $('#pengirimInput').val().trim();
            if (searchTerm) {
                createNewDokter(searchTerm);
            }
        });

        // Tombol tambah dokter
        $('#addDokterBtn').on('click', function() {
            const currentValue = $('#pengirimInput').val().trim();

            if (!currentValue) {
                if (typeof window.showToast === 'function') {
                    window.showToast('warning', 'Masukkan nama dokter terlebih dahulu');
                }
                return;
            }

            // Cek apakah sudah ada di array dokters
            let existing = null;
            if (Array.isArray(dokters) && dokters.length > 0) {
                existing = dokters.find(d => {
                    if (d && d.nama_dokter) {
                        return d.nama_dokter.toLowerCase() === currentValue.toLowerCase();
                    }
                    return false;
                });
            }

            if (existing) {
                // Pilih dokter yang sudah ada
                selectedDokter = {
                    id: existing.id_dokter || null,
                    name: existing.nama_dokter || '',
                    no_telp: existing.no_telp || ''
                };

                updateDokterInfo();
                savePengirim(existing.nama_dokter);

                if (typeof window.showToast === 'function') {
                    window.showToast('info', 'Dokter sudah ada, menggunakan data yang ada');
                }
            } else {
                // Buat dokter baru
                createNewDokter(currentValue);
            }

            dokterDropdown.hide();
        });

        // Fungsi create dokter baru
        function createNewDokter(namaDokter) {
            if (isProcessing) return;

            isProcessing = true;

            // Tampilkan loading
            const $saveStatus = $('#pengirimInput').closest('.input-group').find('.save-status');
            const originalHtml = $saveStatus.html();
            $saveStatus.html('<i class="fas fa-spinner fa-spin text-primary"></i>').show();

            $.ajax({
                url: '{{ route("dokter.create") }}',
                method: 'POST',
                data: {
                    _token: csrfToken,
                    nama_dokter: namaDokter,
                    no_telp: ''
                },
                success: function(response) {
                    if (response.success && response.data) {
                        const dokter = response.data;

                        selectedDokter = {
                            id: dokter.id_dokter || null,
                            name: dokter.nama_dokter || namaDokter,
                            no_telp: dokter.no_telp || ''
                        };

                        updateDokterInfo();
                        savePengirim(dokter.nama_dokter || namaDokter);

                        // Tambahkan ke array dokters jika berhasil
                        if (!Array.isArray(dokters)) dokters = [];
                        dokters.push({
                            id_dokter: dokter.id_dokter,
                            nama_dokter: dokter.nama_dokter,
                            no_telp: dokter.no_telp
                        });

                        if (typeof window.showToast === 'function') {
                            window.showToast('success', 'Dokter baru berhasil ditambahkan');
                        }

                    } else {
                        // Fallback: simpan langsung sebagai pengirim
                        savePengirim(namaDokter);

                        if (typeof window.showToast === 'function') {
                            window.showToast('warning', response.message || 'Menyimpan sebagai pengirim biasa');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    // Fallback: simpan langsung sebagai pengirim
                    savePengirim(namaDokter);

                    if (typeof window.showToast === 'function') {
                        window.showToast('warning', 'Menyimpan sebagai pengirim (tanpa referensi dokter)');
                    }
                },
                complete: function() {
                    isProcessing = false;
                    setTimeout(() => {
                        $saveStatus.fadeOut(500, function() {
                            $(this).html(originalHtml);
                        });
                    }, 1000);

                    // Tutup dropdown
                    dokterDropdown.hide();
                }
            });
        }

        // Fungsi save pengirim ke database
        function savePengirim(namaDokter) {
            const noLab = window.pasienNoLab;

            if (!noLab) {
                console.error('No Lab tidak ditemukan');
                return;
            }

            // Tampilkan saving status
            const $status = $('#pengirimInput').closest('.input-group').find('.save-status');
            $status.html('<i class="fas fa-spinner fa-spin text-primary"></i>').show();

            // Direct AJAX call
            $.ajax({
                url: '/pasien/update-realtime',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                data: {
                    no_lab: noLab,
                    field: 'pengirim',
                    value: namaDokter
                },
                success: function(response) {
                    if (response && response.success) {
                        $('#pengirimInput').removeClass('is-changed');
                        if (typeof window.updateSaveStatus === 'function') {
                            window.updateSaveStatus();
                        }

                        // Update status tersimpan
                        setTimeout(() => {
                            $status.html('<i class="fas fa-check text-success"></i>');
                            setTimeout(() => $status.fadeOut(500), 1000);
                        }, 1000);
                    }
                },
                error: function(xhr, status, error) {
                    const $status = $('#pengirimInput').closest('.input-group').find('.save-status');
                    $status.html('<i class="fas fa-times text-danger"></i>');
                    setTimeout(() => $status.fadeOut(500), 2000);
                }
            });
        }

        // Update info dokter yang dipilih
        function updateDokterInfo() {
            const $info = $('#dokterInfo');

            if (selectedDokter && selectedDokter.name) {
                $info.show();
                $('#selectedDokterName').text(selectedDokter.name);

                let detail = '';

                if (selectedDokter.no_telp) {
                    detail += detail ? ', ' : '';
                    detail += `Telp: ${selectedDokter.no_telp}`;
                }

                $('#dokterDetail').text(detail ? `(${detail})` : '');
            } else {
                $info.hide();
            }
        }

        // Reset seleksi dokter
        function resetDokterSelection() {
            selectedDokter = null;
            updateDokterInfo();
        }

        // Enter key untuk save langsung
        $('#pengirimInput').on('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();

                const value = $(this).val().trim();
                if (!value) {
                    return;
                }

                // Cari matching dokter
                let matchingDokter = null;
                if (Array.isArray(dokters) && dokters.length > 0) {
                    matchingDokter = dokters.find(d => {
                        return d && d.nama_dokter &&
                            d.nama_dokter.toLowerCase() === value.toLowerCase();
                    });
                }

                if (matchingDokter) {
                    // Pilih dokter yang cocok
                    $('#pengirimInput').val(matchingDokter.nama_dokter);
                    selectedDokter = {
                        id: matchingDokter.id_dokter,
                        name: matchingDokter.nama_dokter,
                        no_telp: matchingDokter.no_telp || ''
                    };
                    updateDokterInfo();
                    savePengirim(matchingDokter.nama_dokter);

                    // Update dropdown
                    updateDropdownContent(`
                        <div class="dropdown-item text-success py-2">
                            <div class="d-flex align-items-center">
                                <i class="ri-check-line me-2"></i>
                                <span>${matchingDokter.nama_dokter} dipilih</span>
                            </div>
                        </div>
                    `);
                } else {
                    // Simpan sebagai dokter baru
                    $('#addDokterBtn').click();
                }

                dokterDropdown.hide();
            }
        });

        // Initialize saat page load
        function initializeDokterData() {
            const currentPengirim = $('#pengirimInput').val();

            if (currentPengirim && currentPengirim.trim() !== '') {
                // Set selectedDokter berdasarkan nilai yang ada
                selectedDokter = {
                    id: null,
                    name: currentPengirim,
                    no_telp: ''
                };

                updateDokterInfo();

                // Coba cari dokter yang sesuai di background
                if (currentPengirim.length >= 2) {
                    $.ajax({
                        url: '{{ route("dokter.search") }}',
                        method: 'GET',
                        data: { search: currentPengirim },
                        success: function(response) {
                            if (response.success && response.data && Array.isArray(response.data)) {
                                dokters = response.data;

                                // Coba cari match exact
                                const found = dokters.find(d => {
                                    return d && d.nama_dokter &&
                                        d.nama_dokter.toLowerCase() === currentPengirim.toLowerCase();
                                });

                                if (found) {
                                    selectedDokter = {
                                        id: found.id_dokter,
                                        name: found.nama_dokter,
                                        no_telp: found.no_telp || ''
                                    };
                                    updateDokterInfo();
                                }
                            }
                        }
                    });
                }
            }
        }

        // Initialize
        $(window).on('load', function() {
            setTimeout(initializeDokterData, 1000);
        });

        // Close dropdown saat klik di luar
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.input-group.dropdown').length &&
                !$(e.target).hasClass('dropdown-item') &&
                !$(e.target).closest('.dropdown-item').length) {
                dokterDropdown.hide();
            }
        });

        console.log('✅ Dokter autocomplete system loaded with Bootstrap dropdown');
    });
</script>
<!-- Script untuk update keterangan hasil lain berdasarkan kondisi pasien -->

<!-- Script untuk update keterangan hasil lain berdasarkan kondisi pasien -->
<script>
    $(document).ready(function() {
        const csrfToken = $('#csrf_token').val();

        // ============================================
        // FUNGSI UNTUK MENDAPATKAN RUJUKAN BERDASARKAN KONDISI
        // ============================================

        function getRujukanByKondisi(idDataPemeriksaan, jenisKelamin, umurPasien) {
            // Versi queue/cache — menggantikan Ajax lama
            return new Promise(function(resolve) {
                if (!idDataPemeriksaan) {
                    resolve(null);
                    return;
                }

                // inisialisasi struktur global
                window.__rujukanCache = window.__rujukanCache || {};
                window.__rujukanResolvers = window.__rujukanResolvers || {};

                // Kalau sudah ada di cache, langsung return
                if (Object.prototype.hasOwnProperty.call(window.__rujukanCache, idDataPemeriksaan)) {
                    resolve(window.__rujukanCache[idDataPemeriksaan]);
                    return;
                }

                // Masukkan resolver ke queue — akan dipanggil ketika batch berhasil
                window.__rujukanResolvers[idDataPemeriksaan] = window.__rujukanResolvers[idDataPemeriksaan] || [];
                window.__rujukanResolvers[idDataPemeriksaan].push(resolve);
            });
        }


        // ============================================
        // FUNGSI UNTUK UPDATE KETERANGAN HASIL LAIN DENGAN KONDISI
        // ============================================

        async function updateHasilLainKeterangan($input) {
            const hasil = $input.val();
            const $row = $input.closest('tr');
            const $keteranganDisplay = $row.find('.keterangan-display-hasil-lain');
            const $hiddenInput = $row.find('.keterangan-input-hasil-lain');

            console.log('=== HASIL LAIN - updateKeterangan ===');
            console.log('Hasil:', hasil);

            // Clear jika kosong
            if (!hasil || hasil.trim() === '') {
                console.log('Hasil kosong, reset keterangan');
                updateKeteranganDisplayHasilLain($keteranganDisplay, '');
                $hiddenInput.val('');
                return;
            }

            // Dapatkan data kondisi pasien
            const idDataPemeriksaan = $row.find('.id-data-pemeriksaan-input').val();
            const jenisKelamin = '{{ $pasien->jenis_kelamin }}';
            const umurPasien = '{{ $data["umur_format"] ?? "" }}';

            let rujukan = $row.find('.rujukan-display-hasil-lain').text().trim();
            let ch = $row.find('.ch-display-hasil-lain').text().trim();
            let cl = $row.find('.cl-display-hasil-lain').text().trim();

            // Jika ada ID data pemeriksaan, coba dapatkan rujukan berdasarkan kondisi
            if (idDataPemeriksaan) {
                try {
                    const rujukanData = await getRujukanByKondisi(idDataPemeriksaan, jenisKelamin, umurPasien);

                    if (rujukanData) {
                        // Update data pada display
                        rujukan = rujukanData.rujukan || rujukan;
                        ch = rujukanData.ch || ch;
                        cl = rujukanData.cl || cl;
                        const satuan = rujukanData.satuan || $row.find('.satuan-display-hasil-lain').text().trim();

                        // Update tampilan di tabel
                        const rujukanDisplay = rujukan +
                            (rujukanData.is_from_detail ?
                                ' <span class="badge bg-info ms-1" title="CH/CL khusus kondisi">K</span>' :
                                '');

                        $row.find('.rujukan-display-hasil-lain').html(rujukanDisplay);
                        $row.find('.satuan-display-hasil-lain').text(satuan);

                        // Update CH dengan indikator detail jika ada
                        const chDisplay = ch || '-';
                        const chDetail = (rujukanData.is_from_detail && ch !== '-' && ch !== '') ?
                            '<br><small class="text-info">detail</small>' : '';
                        $row.find('.ch-display-hasil-lain').html(chDisplay + chDetail);
                        $row.find('.ch-input-hasil-lain').val(ch);

                        // Update CL dengan indikator detail jika ada
                        const clDisplay = cl || '-';
                        const clDetail = (rujukanData.is_from_detail && cl !== '-' && cl !== '') ?
                            '<br><small class="text-info">detail</small>' : '';
                        $row.find('.cl-display-hasil-lain').html(clDisplay + clDetail);
                        $row.find('.cl-input-hasil-lain').val(cl);

                        // Jika rujukan dari detail kondisi, highlight row
                        if (rujukanData.is_from_detail) {
                            $row.addClass('table-info');
                        } else {
                            $row.removeClass('table-info');
                        }

                        console.log('Rujukan Hasil Lain diupdate berdasarkan kondisi:', rujukanData);
                    }
                } catch (error) {
                    console.error('Error mendapatkan rujukan berdasarkan kondisi (Hasil Lain):', error);
                }
            }

            // Gunakan rujukan yang sudah diupdate untuk perhitungan
            calculateAndUpdateKeteranganHasilLain($input, $keteranganDisplay, $hiddenInput, hasil, rujukan, ch, cl);
        }

        function calculateAndUpdateKeteranganHasilLain($input, $keteranganDisplay, $hiddenInput, hasil, rujukan, ch, cl) {
            console.log('Hasil Lain - Perhitungan dengan:', { hasil, rujukan, ch, cl });

            if (!rujukan || rujukan.trim() === '' || rujukan.trim() === '-') {
                console.log('Rujukan tidak tersedia atau "-"');
                updateKeteranganDisplayHasilLain($keteranganDisplay, '-');
                $hiddenInput.val('-');
                return;
            }

            try {
                const rujukanStr = rujukan.toString().trim();
                const hasilStr = hasil.toString().trim();
                const hasilNum = parseFloat(hasilStr.replace(',', '.'));

                console.log('Hasil Lain - Data untuk perhitungan:', {
                    rujukan: rujukanStr,
                    hasil: hasilStr,
                    hasilNum: hasilNum
                });

                // Jika bukan angka, cek kualitatif
                if (isNaN(hasilNum)) {
                    const hasilLower = hasilStr.toLowerCase();
                    const rujukanLower = rujukanStr.toLowerCase();

                    if (rujukanLower.includes('negative') || rujukanLower.includes('negatif')) {
                        if (hasilLower.includes('negative') || hasilLower.includes('negatif') ||
                            hasilLower.includes('non-reactive') || hasilLower.includes('nonreactive')) {
                            updateKeteranganDisplayHasilLain($keteranganDisplay, '-');
                            $hiddenInput.val('-');
                        } else {
                            updateKeteranganDisplayHasilLain($keteranganDisplay, 'H');
                            $hiddenInput.val('H');
                        }
                        return;
                    } else if (rujukanLower.includes('positive') || rujukanLower.includes('positif')) {
                        if (hasilLower.includes('positive') || hasilLower.includes('positif') ||
                            hasilLower.includes('reactive') || hasilLower.includes('reaktif')) {
                            updateKeteranganDisplayHasilLain($keteranganDisplay, '-');
                            $hiddenInput.val('-');
                        } else {
                            updateKeteranganDisplayHasilLain($keteranganDisplay, 'L');
                            $hiddenInput.val('L');
                        }
                        return;
                    } else {
                        updateKeteranganDisplayHasilLain($keteranganDisplay, '-');
                        $hiddenInput.val('-');
                    }
                    return;
                }

                // CEK CRITICAL HIGH (CH)
                if (ch && ch !== '' && ch !== '-' && ch !== 'null') {
                    let chNum;
                    if (ch.includes('>')) {
                        chNum = parseFloat(ch.replace('>', '').trim());
                    } else {
                        chNum = parseFloat(ch);
                    }

                    if (!isNaN(chNum) && hasilNum > chNum) {
                        console.log(`✅ HASIL LAIN CH DETECTED: ${hasilNum} > ${chNum}`);
                        updateKeteranganDisplayHasilLain($keteranganDisplay, 'CH');
                        $hiddenInput.val('CH');
                        return;
                    }
                }

                // CEK CRITICAL LOW (CL)
                if (cl && cl !== '' && cl !== '-' && cl !== 'null') {
                    let clNum;
                    if (cl.includes('<')) {
                        clNum = parseFloat(cl.replace('<', '').trim());
                    } else {
                        clNum = parseFloat(cl);
                    }

                    if (!isNaN(clNum) && hasilNum < clNum) {
                        console.log(`✅ HASIL LAIN CL DETECTED: ${hasilNum} < ${clNum}`);
                        updateKeteranganDisplayHasilLain($keteranganDisplay, 'CL');
                        $hiddenInput.val('CL');
                        return;
                    }
                }

                // CEK RUJUKAN NORMAL
                // Format range: "1 - 90"
                if (rujukanStr.includes('-') && !rujukanStr.includes('<') && !rujukanStr.includes('>')) {
                    const parts = rujukanStr.split('-');
                    if (parts.length === 2) {
                        const min = parseFloat(parts[0].trim());
                        const max = parseFloat(parts[1].trim());

                        if (!isNaN(min) && !isNaN(max)) {
                            if (hasilNum < min) {
                                console.log(`✅ HASIL LAIN L DETECTED: ${hasilNum} < ${min}`);
                                updateKeteranganDisplayHasilLain($keteranganDisplay, 'L');
                                $hiddenInput.val('L');
                            } else if (hasilNum > max) {
                                console.log(`✅ HASIL LAIN H DETECTED: ${hasilNum} > ${max}`);
                                updateKeteranganDisplayHasilLain($keteranganDisplay, 'H');
                                $hiddenInput.val('H');
                            } else {
                                console.log(`✅ HASIL LAIN NORMAL: ${hasilNum} dalam range ${min}-${max}`);
                                updateKeteranganDisplayHasilLain($keteranganDisplay, '-');
                                $hiddenInput.val('-');
                            }
                            return;
                        }
                    }
                }

                // Format: "< X"
                if (rujukanStr.startsWith('<')) {
                    const batas = parseFloat(rujukanStr.replace('<', '').trim());
                    if (!isNaN(batas)) {
                        if (hasilNum >= batas) {
                            console.log(`✅ HASIL LAIN H DETECTED: ${hasilNum} ≥ ${batas}`);
                            updateKeteranganDisplayHasilLain($keteranganDisplay, 'H');
                            $hiddenInput.val('H');
                        } else {
                            console.log(`✅ HASIL LAIN NORMAL: ${hasilNum} < ${batas}`);
                            updateKeteranganDisplayHasilLain($keteranganDisplay, '-');
                            $hiddenInput.val('-');
                        }
                        return;
                    }
                }

                // Format: "> X"
                if (rujukanStr.startsWith('>')) {
                    const batas = parseFloat(rujukanStr.replace('>', '').trim());
                    if (!isNaN(batas)) {
                        if (hasilNum <= batas) {
                            console.log(`✅ HASIL LAIN L DETECTED: ${hasilNum} ≤ ${batas}`);
                            updateKeteranganDisplayHasilLain($keteranganDisplay, 'L');
                            $hiddenInput.val('L');
                        } else {
                            console.log(`✅ HASIL LAIN NORMAL: ${hasilNum} > ${batas}`);
                            updateKeteranganDisplayHasilLain($keteranganDisplay, '-');
                            $hiddenInput.val('-');
                        }
                        return;
                    }
                }

                // Default
                console.log('Tidak ada pola yang cocok');
                updateKeteranganDisplayHasilLain($keteranganDisplay, '-');
                $hiddenInput.val('-');

            } catch (e) {
                console.error('HASIL LAIN - Error:', e);
                updateKeteranganDisplayHasilLain($keteranganDisplay, '-');
                $hiddenInput.val('-');
            }
        }

        // Function untuk update keterangan display khusus Hasil Lain
        function updateKeteranganDisplayHasilLain($display, keterangan) {
            if (!$display.length) {
                console.error('Display element tidak ditemukan');
                return;
            }

            // Reset semua kelas
            $display.removeClass(
                'bg-danger bg-opacity-10 ' +
                'bg-primary bg-opacity-10 ' +
                'bg-success bg-opacity-10 ' +
                'text-danger text-primary text-success'
            );

            // Set kelas berdasarkan keterangan
            let bgClass = '';
            let textClass = '';
            let displayText = '';

            switch (keterangan) {
                case 'CH':
                case 'H':
                    bgClass = 'bg-danger bg-opacity-10';
                    textClass = 'text-danger';
                    displayText = keterangan === 'CH' ? 'CH' : 'H';
                    break;

                case 'CL':
                case 'L':
                    bgClass = 'bg-primary bg-opacity-10';
                    textClass = 'text-primary';
                    displayText = keterangan === 'CL' ? 'CL' : 'L';
                    break;

                case '-':
                    bgClass = 'bg-success bg-opacity-10';
                    textClass = 'text-success';
                    displayText = '-';
                    break;

                default:
                    bgClass = 'bg-light';
                    textClass = 'text-muted';
                    displayText = '-';
                    keterangan = '-';
            }

            // Apply classes
            $display.addClass(bgClass + ' ' + textClass);
            $display.html('<strong>' + displayText + '</strong>');
            $display.data('keterangan', keterangan);
        }

        // ============================================
        // EVENT HANDLER UNTUK INPUT HASIL LAIN
        // ============================================

        $(document).on('input', '.hasil-input-hasil-lain', function() {
            const $input = $(this);
            const value = $input.val();

            console.log('====== HASIL LAIN - INPUT BERUBAH ======');
            console.log('Nilai baru:', value);

            // Update keterangan Hasil Lain secara real-time
            updateHasilLainKeterangan($input);
        });

        // ============================================
        // EXCEL NAVIGATION KHUSUS UNTUK HASIL LAIN
        // ============================================

        function initHasilLainExcelNavigation() {
            // Select all inputs on focus
            $('.hasil-input-hasil-lain').on('focus', function() {
                $(this).select();
                $(this).closest('td').addClass('table-warning');
            });

            // Remove highlight on blur
            $('.hasil-input-hasil-lain').on('blur', function() {
                $(this).closest('td').removeClass('table-warning');
            });

            // Excel-like keyboard navigation
            $('.hasil-input-hasil-lain').on('keydown', function(e) {
                const $current = $(this);
                const $row = $current.closest('tr');
                const $cell = $current.closest('td');
                const cellIndex = $cell.index();
                const $rows = $row.closest('tbody').find('tr');
                const rowIndex = $rows.index($row);

                switch (e.key) {
                    case 'Enter':
                    case 'ArrowDown':
                        e.preventDefault();
                        const $nextRow = $rows.eq(rowIndex + 1);
                        if ($nextRow.length) {
                            const $nextInput = $nextRow.find('td').eq(cellIndex).find('.hasil-input-hasil-lain');
                            if ($nextInput.length) {
                                $nextInput.focus().select();
                            }
                        }
                        break;

                    case 'ArrowUp':
                        e.preventDefault();
                        const $upRow = $rows.eq(rowIndex - 1);
                        if ($upRow.length) {
                            const $upInput = $upRow.find('td').eq(cellIndex).find('.hasil-input-hasil-lain');
                            if ($upInput.length) {
                                $upInput.focus().select();
                            }
                        }
                        break;

                    case 'ArrowRight':
                        e.preventDefault();
                        const $nextCell = $row.find('td').eq(cellIndex + 1).find('.hasil-input-hasil-lain');
                        if ($nextCell.length) {
                            $nextCell.focus().select();
                        }
                        break;

                    case 'ArrowLeft':
                        e.preventDefault();
                        const $prevCell = $row.find('td').eq(cellIndex - 1).find('.hasil-input-hasil-lain');
                        if ($prevCell.length) {
                            $prevCell.focus().select();
                        }
                        break;
                }
            });
        }

        // ============================================
        // INISIALISASI DATA RUJUKAN SAAT PAGE LOAD
        // ============================================

        console.log('Initializing hasil lain rujukan data...');

        // Inisialisasi saat page load
        $(window).on('load', function() {
            setTimeout(function() {
                $('.hasil-input-hasil-lain').each(function() {
                    const $input = $(this);

                    // Juga update keterangan berdasarkan nilai yang ada
                    if ($input.val() && $input.val().trim() !== '') {
                        updateHasilLainKeterangan($input);
                    }
                });

                // Initialize Excel navigation untuk Hasil Lain
                initHasilLainExcelNavigation();

                console.log('Hasil Lain initialization complete');
            }, 2000);
        });

        // ============================================
        // FUNGSI UNTUK SEARCH DATA PEMERIKSAAN (MODAL)
        // ============================================

        function handleSearchHasilLain($input) {
            const $row = $input.closest('tr'); // ✅ FIX UTAMA

            const searchTerm = $input.val().trim();
            const $results = $input.next('.search-results-hasil-lain');
            const jenisPemeriksaan = $input.data('jenis-pemeriksaan');
            const rowId = $input.data('row-id');

            const currentId = $row.find('.id-data-pemeriksaan-input').val();

            if (searchTerm.length < 2) {
                $results.hide().empty();
                return;
            }

            clearTimeout($input.data('searchTimer'));
            $input.data('searchTimer', setTimeout(() => {
                $.ajax({
                    url: '{{ route("hasil-lain.search-kode-pemeriksaan") }}',
                    method: 'GET',
                    data: {
                        search: searchTerm,
                        jenis_pemeriksaan: jenisPemeriksaan,
                        exclude_current: currentId
                    },
                    beforeSend: function() {
                        $results
                            .html('<div class="dropdown-item text-center py-2">Mencari data...</div>')
                            .show();
                    },
                    success: function(response) {
                        $results.empty();

                        if (response.success && response.data.length > 0) {
                            response.data.forEach(item => {
                                $results.append(`
                                    <button type="button"
                                        class="dropdown-item kode-option-hasil-lain"
                                        data-id="${item.id_data_pemeriksaan}"
                                        data-nama="${item.data_pemeriksaan}"
                                        data-satuan="${item.satuan || ''}"
                                        data-rujukan="${item.rujukan || ''}"
                                        data-ch="${item.ch || ''}"
                                        data-cl="${item.cl || ''}">
                                        <strong>${item.data_pemeriksaan}</strong>
                                    </button>
                                `);
                            });
                        } else {
                            $results.html('<div class="dropdown-item text-muted">Tidak ditemukan</div>');
                        }

                        $results.show();
                    },
                    error: function() {
                        $results
                            .html('<div class="dropdown-item text-danger">Error</div>')
                            .show();
                    }
                });
            }, 500));
        }


        // Event handler untuk search input hasil lain
        $(document).on('input', '.search-data-pemeriksaan-hasil-lain', function() {
            handleSearchHasilLain($(this));
        });

        // Event handler untuk memilih kode pemeriksaan
        $(document).on('click', '.kode-option-hasil-lain', function() {
            const $option = $(this);
            const id = $option.data('id');
            const nama = $option.data('nama');
            const satuan = $option.data('satuan');
            const rujukan = $option.data('rujukan');
            const metode = $option.data('metode');
            const ch = $option.data('ch');
            const cl = $option.data('cl');

            const $row = $option.closest('tr');
            const $input = $row.find('.search-data-pemeriksaan-hasil-lain');
            const $results = $row.find('.search-results-hasil-lain');

            // Update UI
            $input.val(nama);
            $results.hide().empty();

            // Update hidden inputs
            $row.find('.id-data-pemeriksaan-input').val(id);
            $row.find('.jenis-pengujian-input').val(nama);

            // Update display
            $row.find('.satuan-display-hasil-lain').text(satuan || '-');
            $row.find('.rujukan-display-hasil-lain').text(rujukan || '-');
            $row.find('.ch-display-hasil-lain').text(ch || '-');
            $row.find('.cl-display-hasil-lain').text(cl || '-');
            $row.find('.ch-input-hasil-lain').val(ch || '-');
            $row.find('.cl-input-hasil-lain').val(cl || '-');

            // Highlight row
            $row.addClass('table-success');
            setTimeout(() => {
                $row.removeClass('table-success');
            }, 2000);

            // Jika sudah ada nilai hasil, hitung ulang keterangan dengan kondisi baru
            const $hasilInput = $row.find('.hasil-input-hasil-lain');
            if ($hasilInput.val() && $hasilInput.val().trim() !== '') {
                updateHasilLainKeterangan($hasilInput);
            }
        });

        // ============================================
        // EVENT HANDLERS TAMBAHAN (TIDAK MENGUBAH YANG LAMA)
        // ============================================

        // Tutup dropdown saat klik di luar
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.search-data-pemeriksaan-hasil-lain, .search-results-hasil-lain').length) {
                $('.search-results-hasil-lain').hide();
            }
        });

        // Event handler untuk row baru yang ditambahkan
        $(document).on('DOMNodeInserted', '.pemeriksaan-lain-table tbody tr', function() {
            const $row = $(this);
            const $input = $row.find('.hasil-input-hasil-lain');

            // Beri waktu untuk element benar-benar ready
            setTimeout(function() {
                if ($input.length) {
                    initHasilLainExcelNavigation();

                    // Jika sudah ada nilai, hitung keterangan
                    if ($input.val() && $input.val().trim() !== '') {
                        updateHasilLainKeterangan($input);
                    }
                }
            }, 300);
        });

        // Auto-focus first input Hasil Lain jika ada
        if ($('.hasil-input-hasil-lain').length > 0) {
            $('.hasil-input-hasil-lain').first().focus();
        }
    });
</script>
<!-- end Script untuk update keterangan hasil lain berdasarkan kondisi pasien -->

<!-- Script untuk PENJAMIN & RUANGAN SYSTEM -->
<script>
    $(document).ready(function() {
        console.log('=== PENJAMIN & RUANGAN SYSTEM STARTING ===');
        console.log('Timestamp:', new Date().toISOString());

        const csrfToken = $('#csrf_token').val();
        console.log('CSRF Token:', csrfToken ? 'Found (' + csrfToken.substring(0, 20) + '...)' : 'NOT FOUND');

        // ============================================
        // PENJAMIN AUTOSEARCH SYSTEM
        // ============================================
        console.log('=== INITIALIZING PENJAMIN SYSTEM ===');

        let penjamins = [];
        let selectedPenjamin = null;
        let isProcessingPenjamin = false;
        let penjaminSearchTimer = null;

        // Cek elemen Penjamin
        const penjaminInput = document.getElementById('penjaminInput');
        console.log('Penjamin Input Element:', penjaminInput ? 'Found' : 'NOT FOUND');

        if (!penjaminInput) {
            console.error('❌ ERROR: #penjaminInput tidak ditemukan di DOM');
            console.error('Elements with ID "penjaminInput":', document.querySelectorAll('#penjaminInput').length);
        }

        // Inisialisasi dropdown Bootstrap untuk Penjamin
        let penjaminDropdown = null;
        if (penjaminInput) {
            try {
                penjaminDropdown = new bootstrap.Dropdown(penjaminInput);
                console.log('✅ Penjamin dropdown initialized');
            } catch (error) {
                console.error('❌ ERROR: Gagal inisialisasi dropdown penjamin:', error.message);
                console.error('Stack trace:', error.stack);
            }
        }

        // Function untuk update dropdown content Penjamin
        function updatePenjaminDropdownContent(html) {
            console.log('📝 updatePenjaminDropdownContent called');
            const $dropdown = $('#penjaminDropdown');

            if ($dropdown.length === 0) {
                console.error('❌ ERROR: #penjaminDropdown tidak ditemukan di DOM');
                return;
            }

            $dropdown.html(html);
            console.log('Penjamin dropdown content updated');

            if (html && !html.includes('Ketik minimal 2 karakter')) {
                if (penjaminDropdown) {
                    try {
                        penjaminDropdown.show();
                        console.log('Penjamin dropdown shown');
                    } catch (error) {
                        console.error('❌ ERROR: Gagal menampilkan dropdown penjamin:', error);
                    }
                }
            }
        }

        // Debounce search untuk Penjamin
        $('#penjaminInput').on('input', function() {
            console.log('⌨️ Penjamin input event');
            const searchTerm = $(this).val().trim();
            console.log('Search term:', searchTerm);

            clearTimeout(penjaminSearchTimer);

            if (searchTerm.length < 2) {
                console.log('Search term too short, resetting dropdown');
                updatePenjaminDropdownContent(`
                    <div class="dropdown-item text-muted py-2">
                        <div class="d-flex align-items-center">
                            <i class="ri-search-line me-2"></i>
                            <span>Ketik minimal 2 karakter untuk mencari penjamin</span>
                        </div>
                    </div>
                `);
                resetPenjaminSelection();
                return;
            }

            penjaminSearchTimer = setTimeout(() => {
                console.log('🚀 Starting penjamin search for:', searchTerm);
                searchPenjamin(searchTerm);
            }, 500);
        });

        // Function search Penjamin
        function searchPenjamin(term) {
            console.log('🔍 searchPenjamin called with term:', term);

            if (isProcessingPenjamin) {
                console.log('⚠️ Penjamin search already in progress, skipping');
                return;
            }

            isProcessingPenjamin = true;
            console.log('🔄 Penjamin search started');

            updatePenjaminDropdownContent(`
                <div class="dropdown-item py-2">
                    <div class="d-flex align-items-center">
                        <div class="spinner-border spinner-border-sm text-primary me-2"></div>
                        <span class="text-muted">Mencari penjamin...</span>
                    </div>
                </div>
            `);

            // Log request details
            console.log('📤 AJAX Request Details:', {
                url: '{{ route("penjamin.search") }}',
                method: 'GET',
                data: { search: term },
                timestamp: new Date().toISOString()
            });

            $.ajax({
                url: '{{ route("penjamin.search") }}',
                method: 'GET',
                data: { search: term },
                beforeSend: function(jqXHR, settings) {
                    console.log('📤 AJAX Request Sent:', settings);
                },
                success: function(response, status, xhr) {
                    console.log('✅ AJAX Response Received:', {
                        status: xhr.status,
                        statusText: xhr.statusText,
                        response: response
                    });

                    if (response && response.success && response.data && Array.isArray(response.data)) {
                        penjamins = response.data;
                        console.log('📊 Penjamins found:', penjamins.length);

                        if (penjamins.length > 0) {
                            let html = '';
                            penjamins.forEach((penjamin, index) => {
                                if (!penjamin || !penjamin.nama_penjamin) {
                                    console.warn('⚠️ Invalid penjamin data at index', index, ':', penjamin);
                                    return;
                                }

                                console.log(`Penjamin ${index}:`, {
                                    id: penjamin.id_penjamin,
                                    name: penjamin.nama_penjamin,
                                    kode: penjamin.kode_penjamin
                                });

                                html += `
                                    <button type="button" class="dropdown-item penjamin-option text-start py-2"
                                            data-id="${penjamin.id_penjamin || ''}"
                                            data-name="${penjamin.nama_penjamin}"
                                            data-kode="${penjamin.kode_penjamin || ''}">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="me-3">
                                                <strong class="d-block">${penjamin.nama_penjamin}</strong>
                                                ${penjamin.kode_penjamin ? `<small class="text-muted">Kode: ${penjamin.kode_penjamin}</small>` : ''}
                                            </div>
                                            <i class="ri-arrow-right-s-line text-muted"></i>
                                        </div>
                                    </button>
                                `;
                            });

                            console.log('Generated HTML for', penjamins.length, 'penjamins');
                            updatePenjaminDropdownContent(html);
                        } else {
                            console.log('No penjamins found for search term:', term);
                            updatePenjaminDropdownContent(`
                                <div class="dropdown-item text-center py-2 text-muted">
                                    <i class="ri-search-line me-2"></i>
                                    <span>Tidak ditemukan penjamin</span>
                                </div>
                            `);
                        }
                    } else {
                        console.error('❌ Invalid response format:', response);
                        updatePenjaminDropdownContent(`
                            <div class="dropdown-item text-center py-2 text-danger">
                                <i class="ri-error-warning-line me-2"></i>
                                <span>Data tidak valid dari server</span>
                                <div class="small">Format response tidak sesuai</div>
                            </div>
                        `);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('❌ AJAX Error:', {
                        status: xhr.status,
                        statusText: xhr.statusText,
                        error: error,
                        responseText: xhr.responseText,
                        readyState: xhr.readyState
                    });

                    let errorMessage = 'Gagal memuat data penjamin';
                    if (xhr.status === 404) {
                        errorMessage = 'Endpoint tidak ditemukan (404)';
                    } else if (xhr.status === 500) {
                        errorMessage = 'Server error (500)';
                    } else if (xhr.status === 0) {
                        errorMessage = 'Koneksi terputus atau CORS error';
                    }

                    updatePenjaminDropdownContent(`
                        <div class="dropdown-item text-center py-2 text-danger">
                            <i class="ri-wifi-off-line me-2"></i>
                            <div>${errorMessage}</div>
                            <small class="text-muted">Status: ${xhr.status} - ${error}</small>
                        </div>
                    `);
                },
                complete: function() {
                    console.log('✅ Penjamin search completed');
                    isProcessingPenjamin = false;
                }
            });
        }

        // Pilih Penjamin dari dropdown
        $(document).on('click', '.penjamin-option', function(e) {
            console.log('🎯 Penjamin option clicked');
            e.preventDefault();
            e.stopPropagation();

            const penjaminId = $(this).data('id') || null;
            const penjaminName = $(this).data('name') || '';
            const penjaminKode = $(this).data('kode') || '';

            console.log('Selected Penjamin:', {
                id: penjaminId,
                name: penjaminName,
                kode: penjaminKode
            });

            // Update input
            $('#penjaminInput').val(penjaminName);
            $('#id_penjamin').val(penjaminId);
            selectedPenjamin = {
                id: penjaminId,
                name: penjaminName,
                kode: penjaminKode
            };

            // Update display info
            updatePenjaminInfo();

            // Simpan ke database
            savePenjamin(penjaminId, penjaminName);

            // Update dropdown dengan pesan success
            updatePenjaminDropdownContent(`
                <div class="dropdown-item text-success py-2">
                    <div class="d-flex align-items-center">
                        <i class="ri-check-line me-2"></i>
                        <span>${penjaminName} dipilih</span>
                    </div>
                </div>
            `);

            // Tutup dropdown setelah 1 detik
            setTimeout(() => {
                if (penjaminDropdown) {
                    penjaminDropdown.hide();
                    console.log('Penjamin dropdown hidden');
                }
            }, 1000);
        });

        // Function save Penjamin ke database
        // Function save Penjamin ke database
        function savePenjamin(penjaminId, penjaminName) {
            console.log('💾 Saving penjamin to database:', { penjaminId, penjaminName });

            const noLab = window.pasienNoLab || '{{ $pasien->no_lab }}';
            console.log('No Lab:', noLab);

            if (!noLab) {
                console.error('❌ ERROR: No Lab tidak ditemukan');
                return;
            }

            // Tampilkan saving status
            const $status = $('#penjaminInput').closest('.position-relative').find('.save-status');
            if ($status.length === 0) {
                console.error('❌ ERROR: Save status element not found');
            } else {
                $status.html('<i class="fas fa-spinner fa-spin text-primary"></i>').show();
                console.log('Save status shown');
            }

            // AJAX call untuk update penjamin - KIRIM no_lab dalam data POST
            $.ajax({
                url: '{{ route("pasien.update.penjamin") }}', // GUNAKAN ROUTE NAME
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                data: {
                    no_lab: noLab,  // TAMBAHKAN INI
                    nota: penjaminName
                },
                beforeSend: function() {
                    console.log('📤 Sending penjamin update request:', {
                        url: '{{ route("pasien.update.penjamin") }}',
                        data: { no_lab: noLab, nota: penjaminName }
                    });
                },
                success: function(response) {
                    console.log('✅ Penjamin update success:', response);

                    if (response && response.success) {
                        $('#penjaminInput').removeClass('is-changed');
                        if (typeof window.updateSaveStatus === 'function') {
                            window.updateSaveStatus();
                        }

                        // Update status tersimpan
                        setTimeout(() => {
                            $status.html('<i class="fas fa-check text-success"></i>');
                            setTimeout(() => $status.fadeOut(500), 1000);
                            console.log('Save status updated to success');
                        }, 1000);

                        if (typeof window.showToast === 'function') {
                            window.showToast('success', 'Penjamin berhasil diperbarui');
                        }
                    } else {
                        console.error('❌ Server response indicates failure:', response);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('❌ Penjamin update error:', {
                        status: xhr.status,
                        statusText: xhr.statusText,
                        error: error,
                        responseText: xhr.responseText
                    });

                    $status.html('<i class="fas fa-times text-danger"></i>');
                    setTimeout(() => $status.fadeOut(500), 2000);
                    console.log('Save status updated to error');

                    if (typeof window.showToast === 'function') {
                        window.showToast('danger', 'Gagal menyimpan penjamin');
                    }
                }
            });
        }

        // Update info Penjamin yang dipilih
        function updatePenjaminInfo() {
            console.log('ℹ️ Updating penjamin info');
            const $info = $('#penjaminInfo');

            if ($info.length === 0) {
                console.error('❌ ERROR: #penjaminInfo tidak ditemukan');
                return;
            }

            if (selectedPenjamin && selectedPenjamin.name) {
                $info.show();
                $('#selectedPenjaminName').text(selectedPenjamin.name);

                let detail = '';
                if (selectedPenjamin.kode) {
                    detail += detail ? ', ' : '';
                    detail += `Kode: ${selectedPenjamin.kode}`;
                }

                $('#penjaminDetail').text(detail ? `(${detail})` : '');
                console.log('Penjamin info updated:', selectedPenjamin);
            } else {
                $info.hide();
                console.log('Penjamin info hidden (no selection)');
            }
        }

        // Reset seleksi Penjamin
        function resetPenjaminSelection() {
            console.log('🔄 Resetting penjamin selection');
            selectedPenjamin = null;
            updatePenjaminInfo();
        }

        // ============================================
        // RUANGAN AUTOSEARCH SYSTEM
        // ============================================
        console.log('=== INITIALIZING RUANGAN SYSTEM ===');

        let ruangans = [];
        let selectedRuangan = null;
        let isProcessingRuangan = false;
        let ruanganSearchTimer = null;

        // Cek elemen Ruangan
        const ruanganInput = document.getElementById('ruanganInput');
        console.log('Ruangan Input Element:', ruanganInput ? 'Found' : 'NOT FOUND');

        if (!ruanganInput) {
            console.error('❌ ERROR: #ruanganInput tidak ditemukan di DOM');
            console.error('Elements with ID "ruanganInput":', document.querySelectorAll('#ruanganInput').length);
        }

        // Inisialisasi dropdown Bootstrap untuk Ruangan
        let ruanganDropdown = null;
        if (ruanganInput) {
            try {
                ruanganDropdown = new bootstrap.Dropdown(ruanganInput);
                console.log('✅ Ruangan dropdown initialized');
            } catch (error) {
                console.error('❌ ERROR: Gagal inisialisasi dropdown ruangan:', error.message);
            }
        }

        // Function untuk update dropdown content Ruangan
        function updateRuanganDropdownContent(html) {
            console.log('📝 updateRuanganDropdownContent called');
            const $dropdown = $('#ruanganDropdown');

            if ($dropdown.length === 0) {
                console.error('❌ ERROR: #ruanganDropdown tidak ditemukan di DOM');
                return;
            }

            $dropdown.html(html);
            console.log('Ruangan dropdown content updated');

            if (html && !html.includes('Ketik minimal 2 karakter')) {
                if (ruanganDropdown) {
                    try {
                        ruanganDropdown.show();
                        console.log('Ruangan dropdown shown');
                    } catch (error) {
                        console.error('❌ ERROR: Gagal menampilkan dropdown ruangan:', error);
                    }
                }
            }
        }

        // Debounce search untuk Ruangan
        $('#ruanganInput').on('input', function() {
            console.log('⌨️ Ruangan input event');
            const searchTerm = $(this).val().trim();
            console.log('Search term:', searchTerm);

            clearTimeout(ruanganSearchTimer);

            if (searchTerm.length < 2) {
                console.log('Search term too short, resetting dropdown');
                updateRuanganDropdownContent(`
                    <div class="dropdown-item text-muted py-2">
                        <div class="d-flex align-items-center">
                            <i class="ri-search-line me-2"></i>
                            <span>Ketik minimal 2 karakter untuk mencari ruangan</span>
                        </div>
                    </div>
                `);
                resetRuanganSelection();
                return;
            }

            ruanganSearchTimer = setTimeout(() => {
                console.log('🚀 Starting ruangan search for:', searchTerm);
                searchRuangan(searchTerm);
            }, 500);
        });

        // Function search Ruangan
        function searchRuangan(term) {
            console.log('🔍 searchRuangan called with term:', term);

            if (isProcessingRuangan) {
                console.log('⚠️ Ruangan search already in progress, skipping');
                return;
            }

            isProcessingRuangan = true;
            console.log('🔄 Ruangan search started');

            updateRuanganDropdownContent(`
                <div class="dropdown-item py-2">
                    <div class="d-flex align-items-center">
                        <div class="spinner-border spinner-border-sm text-primary me-2"></div>
                        <span class="text-muted">Mencari ruangan...</span>
                    </div>
                </div>
            `);

            $.ajax({
                url: '{{ route("ruangan.search") }}',
                method: 'GET',
                data: { search: term },
                beforeSend: function() {
                    console.log('📤 AJAX Request Sent to ruangan.search');
                },
                success: function(response, status, xhr) {
                    console.log('✅ AJAX Response Received from ruangan.search:', {
                        status: xhr.status,
                        response: response
                    });

                    if (response && response.success && response.data && Array.isArray(response.data)) {
                        ruangans = response.data;
                        console.log('📊 Ruangans found:', ruangans.length);

                        if (ruangans.length > 0) {
                            let html = '';
                            ruangans.forEach((ruangan, index) => {
                                if (!ruangan || !ruangan.nama_ruangan) {
                                    console.warn('⚠️ Invalid ruangan data at index', index, ':', ruangan);
                                    return;
                                }

                                html += `
                                    <button type="button" class="dropdown-item ruangan-option text-start py-2"
                                            data-id="${ruangan.id_ruangan || ''}"
                                            data-name="${ruangan.nama_ruangan}"
                                            data-kode="${ruangan.kode_ruangan || ''}">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="me-3">
                                                <strong class="d-block">${ruangan.nama_ruangan}</strong>
                                                ${ruangan.kode_ruangan ? `<small class="text-muted">Kode: ${ruangan.kode_ruangan}</small>` : ''}
                                            </div>
                                            <i class="ri-arrow-right-s-line text-muted"></i>
                                        </div>
                                    </button>
                                `;
                            });

                            updateRuanganDropdownContent(html);
                        } else {
                            console.log('No ruangans found for search term:', term);
                            updateRuanganDropdownContent(`
                                <div class="dropdown-item text-center py-2 text-muted">
                                    <i class="ri-search-line me-2"></i>
                                    <span>Tidak ditemukan ruangan</span>
                                </div>
                            `);
                        }
                    } else {
                        console.error('❌ Invalid response format from ruangan.search:', response);
                        updateRuanganDropdownContent(`
                            <div class="dropdown-item text-center py-2 text-danger">
                                <i class="ri-error-warning-line me-2"></i>
                                <span>Data tidak valid dari server</span>
                            </div>
                        `);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('❌ AJAX Error from ruangan.search:', {
                        status: xhr.status,
                        error: error,
                        responseText: xhr.responseText
                    });

                    updateRuanganDropdownContent(`
                        <div class="dropdown-item text-center py-2 text-danger">
                            <i class="ri-wifi-off-line me-2"></i>
                            <div>Gagal memuat data ruangan</div>
                            <small class="text-muted">${xhr.status}: ${error}</small>
                        </div>
                    `);
                },
                complete: function() {
                    console.log('✅ Ruangan search completed');
                    isProcessingRuangan = false;
                }
            });
        }

        // Pilih Ruangan dari dropdown
        $(document).on('click', '.ruangan-option', function(e) {
            console.log('🎯 Ruangan option clicked');
            e.preventDefault();
            e.stopPropagation();

            const ruanganId = $(this).data('id') || null;
            const ruanganName = $(this).data('name') || '';
            const ruanganKode = $(this).data('kode') || '';

            console.log('Selected Ruangan:', {
                id: ruanganId,
                name: ruanganName,
                kode: ruanganKode
            });

            // Update input
            $('#ruanganInput').val(ruanganName);
            $('#id_ruangan').val(ruanganId);
            selectedRuangan = {
                id: ruanganId,
                name: ruanganName,
                kode: ruanganKode
            };

            // Update display info
            updateRuanganInfo();

            // Simpan ke database
            saveRuangan(ruanganId, ruanganName);

            // Update dropdown dengan pesan success
            updateRuanganDropdownContent(`
                <div class="dropdown-item text-success py-2">
                    <div class="d-flex align-items-center">
                        <i class="ri-check-line me-2"></i>
                        <span>${ruanganName} dipilih</span>
                    </div>
                </div>
            `);

            // Tutup dropdown setelah 1 detik
            setTimeout(() => {
                if (ruanganDropdown) {
                    ruanganDropdown.hide();
                    console.log('Ruangan dropdown hidden');
                }
            }, 1000);
        });

        // Function save Ruangan ke database
        // Function save Ruangan ke database
        function saveRuangan(ruanganId, ruanganName) {
            console.log('💾 Saving ruangan to database:', { ruanganId, ruanganName });

            const noLab = window.pasienNoLab || '{{ $pasien->no_lab }}';
            console.log('No Lab:', noLab);

            if (!noLab) {
                console.error('❌ ERROR: No Lab tidak ditemukan');
                return;
            }

            // Tampilkan saving status
            const $status = $('#ruanganInput').closest('.input-group').find('.save-status');
            if ($status.length === 0) {
                console.error('❌ ERROR: Save status element not found for ruangan');
            } else {
                $status.html('<i class="fas fa-spinner fa-spin text-primary"></i>').show();
                console.log('Ruangan save status shown');
            }

            // AJAX call untuk update ruangan - KIRIM no_lab dalam data POST
            $.ajax({
                url: '{{ route("pasien.update.ruangan") }}', // GUNAKAN ROUTE NAME
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                data: {
                    no_lab: noLab,  // TAMBAHKAN INI
                    id_ruangan: ruanganId,
                    ket_klinik: ruanganName
                },
                beforeSend: function() {
                    console.log('📤 Sending ruangan update request');
                },
                success: function(response) {
                    console.log('✅ Ruangan update success:', response);

                    if (response && response.success) {
                        $('#ruanganInput').removeClass('is-changed');
                        if (typeof window.updateSaveStatus === 'function') {
                            window.updateSaveStatus();
                        }

                        // Update status tersimpan
                        setTimeout(() => {
                            $status.html('<i class="fas fa-check text-success"></i>');
                            setTimeout(() => $status.fadeOut(500), 1000);
                            console.log('Ruangan save status updated to success');
                        }, 1000);

                        if (typeof window.showToast === 'function') {
                            window.showToast('success', 'Asal kunjungan berhasil diperbarui');
                        }
                    } else {
                        console.error('❌ Server response indicates failure:', response);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('❌ Ruangan update error:', {
                        status: xhr.status,
                        error: error,
                        responseText: xhr.responseText
                    });

                    $status.html('<i class="fas fa-times text-danger"></i>');
                    setTimeout(() => $status.fadeOut(500), 2000);
                    console.log('Ruangan save status updated to error');

                    if (typeof window.showToast === 'function') {
                        window.showToast('danger', 'Gagal menyimpan asal kunjungan');
                    }
                }
            });
        }
        // Update info Ruangan yang dipilih
        function updateRuanganInfo() {
            console.log('ℹ️ Updating ruangan info');
            const $info = $('#ruanganInfo');

            if ($info.length === 0) {
                console.error('❌ ERROR: #ruanganInfo tidak ditemukan');
                return;
            }

            if (selectedRuangan && selectedRuangan.name) {
                $info.show();
                $('#selectedRuanganName').text(selectedRuangan.name);
                console.log('Ruangan info updated:', selectedRuangan);
            } else {
                $info.hide();
                console.log('Ruangan info hidden (no selection)');
            }
        }

        // Reset seleksi Ruangan
        function resetRuanganSelection() {
            console.log('🔄 Resetting ruangan selection');
            selectedRuangan = null;
            updateRuanganInfo();
        }

        // ============================================
        // INISIALISASI DATA SAAT PAGE LOAD
        // ============================================
        function initializePenjaminRuanganData() {
            console.log('🔄 Initializing penjamin & ruangan data on page load');

            // Inisialisasi Penjamin
            const currentPenjamin = $('#penjaminInput').val();
            const currentPenjaminId = $('#id_penjamin').val();

            console.log('Current penjamin data:', {
                value: currentPenjamin,
                id: currentPenjaminId
            });

            if (currentPenjamin && currentPenjamin.trim() !== '') {
                selectedPenjamin = {
                    id: currentPenjaminId || null,
                    name: currentPenjamin,
                    kode: ''
                };
                updatePenjaminInfo();
                console.log('Penjamin initialized from existing data');
            } else {
                console.log('No existing penjamin data found');
            }

            // Inisialisasi Ruangan
            const currentRuangan = $('#ruanganInput').val();
            const currentRuanganId = $('#id_ruangan').val();

            console.log('Current ruangan data:', {
                value: currentRuangan,
                id: currentRuanganId
            });

            if (currentRuangan && currentRuangan.trim() !== '') {
                selectedRuangan = {
                    id: currentRuanganId || null,
                    name: currentRuangan,
                    kode: ''
                };
                updateRuanganInfo();
                console.log('Ruangan initialized from existing data');
            } else {
                console.log('No existing ruangan data found');
            }
        }

        // Initialize on page load
        $(window).on('load', function() {
            console.log('📄 Window load event fired');
            setTimeout(initializePenjaminRuanganData, 1000);
        });

        // Juga inisialisasi saat document ready
        setTimeout(initializePenjaminRuanganData, 500);

        // Close dropdown saat klik di luar
        $(document).on('click', function(e) {
            // Untuk Penjamin
            if (!$(e.target).closest('#penjaminInput').length &&
                !$(e.target).closest('#penjaminDropdown').length) {
                if (penjaminDropdown) {
                    penjaminDropdown.hide();
                    console.log('Penjamin dropdown closed (outside click)');
                }
            }

            // Untuk Ruangan
            if (!$(e.target).closest('#ruanganInput').length &&
                !$(e.target).closest('#ruanganDropdown').length) {
                if (ruanganDropdown) {
                    ruanganDropdown.hide();
                    console.log('Ruangan dropdown closed (outside click)');
                }
            }
        });

        // Test function untuk debugging
        window.testPenjaminRuanganSystem = function() {
            console.log('=== TESTING PENJAMIN & RUANGAN SYSTEM ===');
            console.log('Selected Penjamin:', selectedPenjamin);
            console.log('Selected Ruangan:', selectedRuangan);
            console.log('Penjamins in memory:', penjamins.length);
            console.log('Ruangans in memory:', ruangans.length);
            console.log('CSRF Token exists:', !!csrfToken);
            console.log('No Lab:', window.pasienNoLab);
            console.log('Penjamin Input value:', $('#penjaminInput').val());
            console.log('Ruangan Input value:', $('#ruanganInput').val());

            // Test AJAX endpoints
            console.log('Testing AJAX endpoints:');
            console.log('- Penjamin search route: {{ route("penjamin.search") }}');
            console.log('- Ruangan search route: {{ route("ruangan.search") }}');
            console.log('- Update penjamin route: /pasien/update-penjamin');
            console.log('- Update ruangan route: /pasien/update-ruangan');
        };

        console.log('✅ Penjamin & Ruangan autocomplete system loaded successfully');
        console.log('=== SYSTEM READY ===');
    });
</script>
<!-- End of Penjamin & Ruangan AutoSearch System -->

<!-- Multi-Panel History System -->
<script>
    $(document).ready(function () {
        console.log('✅ Multi-Panel History System Initializing...');

        const csrfToken = $('#csrf_token').val();
        let currentActiveElement = null;
        let isHistoryLoading = false;

        function createSlug(text) {
            return text.toLowerCase()
                .replace(/[^\w\s-]/g, '')
                .replace(/\s+/g, '_')
                .replace(/-+/g, '_');
        }

        function getPanelSuffix(type, jenisPemeriksaan) {
            switch (type) {
                case 'hematology':
                    return '_hematology';
                case 'kimia':
                    return '_kimia';
                case 'hasil_lain':
                    return '_' + createSlug(jenisPemeriksaan);
                default:
                    return '';
            }
        }

        function loadHistory(jenisPemeriksaan, type, rmPasien, idDataPemeriksaan, kelompok = null) {
            if (!jenisPemeriksaan || !rmPasien || !idDataPemeriksaan) return;

            let panelSuffix;
            if (type === 'hasil_lain' && kelompok) {
                panelSuffix = '_' + createSlug(kelompok);
            } else {
                panelSuffix = getPanelSuffix(type, jenisPemeriksaan);
            }

            if ($(`#historyPanelContent${panelSuffix}`).length === 0) return;

            // ✅ GUNAKAN LOADING LAMA (INI SAJA)
            $(`#historyPanelContent${panelSuffix}`).html(`
                <div class="text-center py-3">
                    <div class="spinner-border spinner-border-sm text-primary"></div>
                    <div class="mt-1 text-muted small">Memuat riwayat...</div>
                </div>
            `);

            isHistoryLoading = true;

            $.ajax({
                url: '{{ route("hasil-lab.get-history-hover") }}',
                method: 'POST',
                data: {
                    _token: csrfToken,
                    jenis_pemeriksaan: jenisPemeriksaan,
                    id_data_pemeriksaan: idDataPemeriksaan,
                    type: type,
                    rm_pasien: rmPasien,
                    current_no_lab: '{{ $pasien->no_lab }}'
                },
                success(res) {
                    if (res.success && res.data && res.data.length) {
                        displayHistoryData(res.data, panelSuffix);
                    } else {
                        showNoDataMessage(jenisPemeriksaan, panelSuffix);
                    }
                },
                error() {
                    showError('Terjadi kesalahan saat memuat data', panelSuffix);
                },
                complete() {
                    isHistoryLoading = false;
                }
            });
        }

        function displayHistoryData(data, panelSuffix) {
            let html = '<div class="history-items">';
            data.forEach((item, i) => {
                html += `
                    <div class="history-row py-2 border-bottom ${i % 2 === 0 ? 'bg-light bg-opacity-25' : ''}">
                        <div class="d-flex justify-content-between mb-1">
                            <div class="small text-muted">
                                <i class="ri-calendar-line me-1"></i>${formatDate(item.waktu_validasi)}
                            </div>
                            <div class="fw-bold text-success">
                                ${item.hasil_pengujian || '-'}
                            </div>
                        </div>
                        <div class="small text-muted">
                            <i class="ri-user-line me-1"></i>${item.nama_pasien || '-'}
                        </div>
                    </div>
                `;
            });
            html += '</div>';
            $(`#historyPanelContent${panelSuffix}`).html(html);
        }

        function showNoDataMessage(jenis, panelSuffix) {
            $(`#historyPanelContent${panelSuffix}`).html(`
                <div class="text-center py-5 text-muted">
                    <i class="ri-database-2-line display-4 opacity-50"></i>
                    <p class="mt-2 small">Tidak ada riwayat<br><strong>${jenis}</strong></p>
                </div>
            `);
        }

        function showError(msg, panelSuffix) {
            $(`#historyPanelContent${panelSuffix}`).html(`
                <div class="text-center py-5 text-danger">
                    <i class="ri-error-warning-line display-4 opacity-50"></i>
                    <p class="mt-2 small">${msg}</p>
                </div>
            `);
        }

        function formatDate(dateStr) {
            if (!dateStr) return '-';

            // ambil tanggal saja: YYYY-MM-DD
            const tgl = dateStr.substring(0, 10); // "2026-01-30"

            const [y, m, d] = tgl.split('-');

            const bulan = [
                'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            ];

            return parseInt(d) + ' ' + bulan[parseInt(m) - 1] + ' ' + y;
        }

        function resetHistoryPanel(panelSuffix) {
            if ($(`#historyPanelContent${panelSuffix}`).length === 0) return;

            $(`#hoverJenisPemeriksaan${panelSuffix}`).html(`
                <i class="ri-history-line me-1"></i> History Pemeriksaan
            `);

            $(`#hoverTypeInfo${panelSuffix}`).text('Klik hasil untuk melihat riwayat');

            $(`#historyPanelContent${panelSuffix}`).html(`
                <div class="text-center py-5 text-muted">
                    <i class="ri-file-list-3-line display-5 opacity-25"></i>
                    <p class="small mt-2">Klik hasil untuk melihat riwayat</p>
                </div>
            `);
        }

        function resetAllHistoryPanels() {
            $('[id^="historyPanelContent_"]').each(function () {
                const suffix = $(this).attr('id').replace('historyPanelContent', '');
                resetHistoryPanel(suffix);
            });
        }

        /* =========================
        EVENT CLICK (FIX UTAMA)
        ========================= */
        $(document).on(
            'click',
            '.hasil-input, .hasil-input-lain, .hasil-input-hasil-lain',
            function (e) {
                e.preventDefault();
                e.stopPropagation();

                const $this = $(this);
                const type = $this.data('type');
                const rm = $this.data('rm') || '{{ $pasien->rm_pasien }}';
                const idDataPemeriksaan = $this.data('id-data-pemeriksaan'); // ✅ AMBIL DI SINI

                let jenis = null;
                let kelompok = null;

                if (type === 'hasil_lain') {
                    const $section = $this.closest('.pemeriksaan-lain-section');
                    kelompok = $section.data('jenis-pemeriksaan');
                    jenis = kelompok;
                } else {
                    jenis = $this.data('jenis');
                }

                if (!jenis || !type || !rm || !idDataPemeriksaan) {
                    console.warn('Data tidak lengkap', { jenis, type, rm, idDataPemeriksaan });
                    return;
                }

                loadHistory(jenis, type, rm, idDataPemeriksaan, kelompok);
            }
        );


        $(document).on('click keydown', function (e) {
            if (e.type === 'keydown' && e.key !== 'Escape') return;
            if (!$(e.target).closest('.hasil-input, .hasil-input-hasil-lain, .history-panel-card').length) {
                if (currentActiveElement) {
                    currentActiveElement.removeClass('click-active');
                    currentActiveElement = null;
                }
                resetAllHistoryPanels();
            }
        });

        $('.card.h-100.border-start.border-primary').addClass('history-panel-card');
        resetAllHistoryPanels();

        console.log('✅ Multi-Panel History System Ready');
    });
</script>
<!-- End of Multi-Panel History System -->

<!-- HASIL LAIN SYSTEM - COMPLETE VERSION -->
<script>
    $(document).ready(function() {
        console.log('=== HASIL LAIN SYSTEM - PRODUCTION VERSION ===');

        const csrfToken = $('#csrf_token').val();
        console.log('CSRF Token:', csrfToken ? 'Found' : 'Missing');

        // Helper: dapatkan unique id untuk sebuah row (dipakai sbg client_key)
        function getRowUniqueId($row) {
            const idDataRow = $row.data('id') || $row.find('.row-id-input').val();
            if (idDataRow && idDataRow !== '') return String(idDataRow);
            const idx = $row.data('index');
            return 'manual_' + (idx !== undefined ? idx : Date.now());
        }

        // Variables untuk modal
        let currentJenisPemeriksaanModal = null;
        let currentTableSectionModal = null;
        let modalSelectedData = [];
        let modalDataPemeriksaanList = [];

        // Variable untuk hapus tabel
        let tabelYangAkanDihapus = null;

        // ============================================
        // 1. TAMBAH TABEL PEMERIKSAAN BARU
        // ============================================
        $('#tambahTabelBtn').on('click', function() {
            const jenisPemeriksaan = $('#jenisPemeriksaanSelect').val();

            if (!jenisPemeriksaan) {
                if (typeof window.showToast === 'function') {
                    window.showToast('warning', 'Pilih jenis pemeriksaan terlebih dahulu');
                }
                return;
            }

            if ($('.pemeriksaan-lain-section[data-jenis-pemeriksaan="' + jenisPemeriksaan + '"]').length > 0) {
                if (typeof window.showToast === 'function') {
                    window.showToast('warning', 'Tabel ' + jenisPemeriksaan + ' sudah ada');
                }
                return;
            }

            const slug = jenisPemeriksaan.toLowerCase().replace(/[^a-z0-9]+/g, '_');

            const newTableSection =
                '<div class="pt-3 border-top pemeriksaan-lain-section"' +
                '    data-jenis-pemeriksaan="' + jenisPemeriksaan + '"' +
                '    id="section_' + slug + '">' +
                '    <div class="row">' +
                '        <div class="col-lg-9 col-md-12">' +
                '            <div class="d-flex justify-content-between align-items-center mb-3">' +
                '                <h6 class="mb-0 border-bottom pb-2">' +
                '                    <i class="ri-list-check me-2"></i>' + jenisPemeriksaan +
                '                    <span class="badge bg-info ms-2">Kondisi: {{ $pasien->jenis_kelamin }} | {{ $data["umur_format"] }}</span>' +
                '                </h6>' +
                '                <div>' +
                '                    <button type="button" class="btn btn-sm btn-outline-primary tambah-row-btn-hasil-lain"' +
                '                            data-jenis-pemeriksaan="' + jenisPemeriksaan + '">' +
                '                        <i class="ri-add-line me-1"></i>Tambah Row' +
                '                    </button>' +
                '                    <button type="button" class="btn btn-sm btn-outline-success modal-hasil-lain-btn ms-2"' +
                '                            data-jenis-pemeriksaan="' + jenisPemeriksaan + '">' +
                '                        <i class="ri-list-check me-1"></i>Pilih dari Daftar' +
                '                    </button>' +
                '                    <button type="button" class="btn btn-sm btn-outline-danger hapus-tabel-btn-hasil-lain ms-2"' +
                '                            data-jenis-pemeriksaan="' + jenisPemeriksaan + '">' +
                '                        <i class="ri-delete-bin-line me-1"></i>Hapus Tabel' +
                '                    </button>' +
                '                </div>' +
                '            </div>' +
                '            <div class="table-responsive overflow-visible">' +
                '                <table class="table table-bordered table-sm pemeriksaan-lain-table table-row-skip" id="tabel_' + slug + '">' +
                '                    <thead class="table-light">' +
                '                        <tr>' +
                '                            <th width="20%" class="bg-light">Pilih Jenis Pemeriksaan</th>' +
                '                            <th width="10%" class="bg-light">Satuan</th>' +
                '                            <th width="15%" class="bg-light">Rujukan</th>' +
                '                            <th width="5%" class="bg-light">CH</th>' +
                '                            <th width="5%" class="bg-light">CL</th>' +
                '                            <th width="15%">Hasil Pengujian</th>' +
                '                            <th width="10%">Keterangan</th>' +
                '                            <th width="5%">Aksi</th>' +
                '                        </tr>' +
                '                    </thead>' +
                '                    <tbody></tbody>' +
                '                </table>' +
                '            </div>' +
                '        </div>' +
                '        <div class="col-lg-3 col-md-12">' +
                '            <div class="card h-100 border-start border-primary history-panel-card">' +
                '                <div class="card-header bg-light py-2">' +
                '                    <h6 class="card-title mb-0 small">' +
                '                        <i class="ri-history-line me-2 text-primary"></i>History ' + jenisPemeriksaan +
                '                    </h6>' +
                '                </div>' +
                '                <div class="card-body p-0">' +
                '                    <div class="p-2 border-bottom bg-primary bg-opacity-5" id="currentHoverInfo_' + slug + '">' +
                '                        <div class="text-center">' +
                '                            <div class="text-primary mb-1 small" id="hoverJenisPemeriksaan_' + slug + '">' +
                '                                <i class="ri-history-line me-1"></i>' +
                '                                <span>History Pemeriksaan</span>' +
                '                            </div>' +
                '                            <div class="small text-muted" id="hoverTypeInfo_' + slug + '">' +
                '                                Klik pada kolom "Hasil"' +
                '                            </div>' +
                '                        </div>' +
                '                    </div>' +
                '                    <div class="p-2" id="historyPanelContent_' + slug + '"' +
                '                        style="height: 300px; overflow-y: auto; font-size: 0.85rem;">' +
                '                        <div class="text-center text-muted py-4">' +
                '                            <i class="ri-file-list-3-line display-6 mb-3 opacity-50"></i>' +
                '                            <p class="mb-1 small">History akan muncul di sini</p>' +
                '                            <small class="text-muted">Klik pada hasil</small>' +
                '                        </div>' +
                '                    </div>' +
                '                </div>' +
                '            </div>' +
                '        </div>' +
                '    </div>' +
                '</div>';

            $('#tambahTabelBtn').closest('.card').before(newTableSection);
            $('#jenisPemeriksaanSelect').val('');
            if (typeof window.showToast === 'function') window.showToast('success', 'Tabel ' + jenisPemeriksaan + ' berhasil ditambahkan');
            console.log('Tabel ' + jenisPemeriksaan + ' ditambahkan');
        });

        // ============================================
        // 2. TOMBOL "PILIH DARI DAFTAR" (MODAL CHECKBOX)
        // ============================================
        $(document).on('click', '.modal-hasil-lain-btn', function(e) {
            e.preventDefault();
            const jenisPemeriksaan = $(this).data('jenis-pemeriksaan');
            const $section = $(this).closest('.pemeriksaan-lain-section');
            currentJenisPemeriksaanModal = jenisPemeriksaan;
            currentTableSectionModal = $section;
            modalSelectedData = [];
            $('#searchModalDataPemeriksaan').val('');
            $('#selectAllModal').prop('checked', false);
            updateSelectedCountModal();
            $('#modalTitleJenisPemisah').text('Pilih Data Pemeriksaan - ' + jenisPemeriksaan);
            $('#modalDataPemeriksaanList').html('<tr><td colspan="5" class="text-center py-4"><div class="spinner-border spinner-border-sm text-primary me-2"></div>Memuat data pemeriksaan...</td></tr>');
            loadModalDataPemeriksaan(jenisPemeriksaan);
            const modal = new bootstrap.Modal(document.getElementById('modalPilihDataPemeriksaan'));
            modal.show();
        });

        function loadModalDataPemeriksaan(jenisPemeriksaan) {
            $.ajax({
                url: '/hasil-lain/get-pemeriksaan-by-jenis',
                method: 'GET',
                data: { jenis_pemeriksaan: jenisPemeriksaan },
                success: function(response) {
                    if (response.success && response.data && response.data.length > 0) {
                        modalDataPemeriksaanList = response.data;
                        renderModalDataPemeriksaanList();
                    } else {
                        $('#modalDataPemeriksaanList').html('<tr><td colspan="5" class="text-center py-4 text-muted"><i class="ri-inbox-line me-2"></i>Tidak ada data pemeriksaan untuk jenis ini</td></tr>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Modal load error:', error);
                    $('#modalDataPemeriksaanList').html('<tr><td colspan="5" class="text-center py-4 text-danger"><i class="ri-error-warning-line me-2"></i>Gagal memuat data</td></tr>');
                }
            });
        }

        function renderModalDataPemeriksaanList(filterTerm) {
            if (filterTerm === undefined) filterTerm = '';
            const $list = $('#modalDataPemeriksaanList');
            $list.empty();
            let filteredData = modalDataPemeriksaanList;
            if (filterTerm) {
                const term = filterTerm.toLowerCase();
                filteredData = modalDataPemeriksaanList.filter(function(item) {
                    const searchText = (item.id_data_pemeriksaan + ' ' + item.data_pemeriksaan + ' ' + (item.rujukan || '')).toLowerCase();
                    return searchText.includes(term);
                });
            }
            if (filteredData.length === 0) {
                $list.html('<tr><td colspan="5" class="text-center py-4 text-muted"><i class="ri-search-line me-2"></i>Tidak ditemukan data</td></tr>');
                return;
            }
            filteredData.forEach(function(item) {
                const isSelected = modalSelectedData.some(function(selected) { return selected.id === item.id_data_pemeriksaan; });
                const row =
                    '<tr class="' + (isSelected ? 'table-primary' : '') + '">' +
                    '    <td><div class="form-check">' +
                    '        <input class="form-check-input modal-data-checkbox" type="checkbox"' +
                    '            data-id="' + item.id_data_pemeriksaan + '"' +
                    '            data-nama="' + item.data_pemeriksaan + '"' +
                    '            data-satuan="' + (item.satuan || '') + '"' +
                    '            data-rujukan="' + (item.rujukan || '') + '"' +
                    '            data-ch="' + (item.ch || '') + '"' +
                    '            data-cl="' + (item.cl || '') + '"' +
                    (isSelected ? ' checked' : '') + '>' +
                    '    </div></td>' +
                    '    <td><span class="badge bg-light text-dark">' + item.id_data_pemeriksaan + '</span></td>' +
                    '    <td>' + item.data_pemeriksaan + '</td>' +
                    '    <td>' + (item.satuan || '-') + '</td>' +
                    '    <td>' + (item.rujukan || '-') + '</td>' +
                    '</tr>';
                $list.append(row);
            });
        }

        $('#searchModalDataPemeriksaan').on('input', function() {
            renderModalDataPemeriksaanList($(this).val());
        });
        $('#clearSearchModal').on('click', function() { $('#searchModalDataPemeriksaan').val('').trigger('input'); });

        $('#selectAllModal').on('change', function() {
            const isChecked = $(this).prop('checked');
            const visibleCheckboxes = $('.modal-data-checkbox:visible');
            visibleCheckboxes.each(function() {
                const $checkbox = $(this);
                if (isChecked && !$checkbox.prop('checked')) { $checkbox.prop('checked', true); addToModalSelected($checkbox); }
                if (!isChecked && $checkbox.prop('checked')) { $checkbox.prop('checked', false); removeFromModalSelected($checkbox.data('id')); }
            });
            updateSelectedCountModal();
        });

        $(document).on('change', '.modal-data-checkbox', function() {
            const $checkbox = $(this);
            if ($checkbox.prop('checked')) addToModalSelected($checkbox);
            else { removeFromModalSelected($checkbox.data('id')); $('#selectAllModal').prop('checked', false); }
            updateSelectedCountModal();
        });

        function addToModalSelected($checkbox) {
            const data = {
                id: $checkbox.data('id'),
                nama: $checkbox.data('nama'),
                satuan: $checkbox.data('satuan'),
                rujukan: $checkbox.data('rujukan'),
                ch: $checkbox.data('ch'),
                cl: $checkbox.data('cl')
            };
            if (modalSelectedData.findIndex(item => item.id === data.id) === -1) modalSelectedData.push(data);
        }
        function removeFromModalSelected(id) { modalSelectedData = modalSelectedData.filter(item => item.id !== id); }
        function updateSelectedCountModal() { $('#selectedCountModal').text(modalSelectedData.length + ' item dipilih'); }

        // Tambah data ke tabel dari modal
        $('#tambahDataPemeriksaanBtn').on('click', function() {
            if (modalSelectedData.length === 0) { if (typeof window.showToast === 'function') window.showToast('warning', 'Pilih minimal satu data pemeriksaan'); return; }
            if (!currentTableSectionModal || !currentJenisPemeriksaanModal) { console.error('Table section tidak ditemukan'); return; }

            const $tbody = currentTableSectionModal.find('tbody');
            const currentRowCount = $tbody.find('tr').length;

            modalSelectedData.forEach(function(item, index) {
                const rowIndex = currentRowCount + index;
                const newRow =
                    '<tr data-index="' + rowIndex + '" data-jenis-pemeriksaan="' + currentJenisPemeriksaanModal + '">' +
                    '    <td class="search-cell-hasil-lain"><div class="position-relative">' +
                    '        <input type="text" class="form-control form-control-sm search-data-pemeriksaan-hasil-lain" placeholder="Cari data pemeriksaan..."' +
                    '            value="' + item.nama + '" data-jenis-pemeriksaan="' + currentJenisPemeriksaanModal + '" data-index="' + rowIndex + '" autocomplete="off" readonly>' +
                    '        <div class="search-results-hasil-lain dropdown-menu" style="display: none; max-height: 200px; overflow-y: auto; z-index: 1050;"></div>' +
                    '        <input type="hidden" class="id-data-pemeriksaan-input" value="' + item.id + '">' +
                    '        <input type="hidden" class="jenis-pengujian-input" value="' + item.nama + '">' +
                    '        <input type="hidden" class="row-id-input" value="">' +
                    '    </div></td>' +
                    '    <td class="bg-light satuan-cell-hasil-lain"><span class="satuan-display-hasil-lain">' + (item.satuan || '-') + '</span></td>' +
                    '    <td class="bg-light rujukan-cell-hasil-lain"><span class="rujukan-display-hasil-lain">' + (item.rujukan || '-') + '</span></td>' +
                    '    <td class="bg-light ch-cell-hasil-lain"><span class="ch-display-hasil-lain">' + (item.ch || '-') + '</span></td>' +
                    '    <td class="bg-light cl-cell-hasil-lain"><span class="cl-display-hasil-lain">' + (item.cl || '-') + '</span></td>' +
                    '    <td class="hasil-cell-hasil-lain"><input type="text" class="form-control form-control-sm hasil-input-hasil-lain" value="" placeholder="Hasil" data-id="" data-type="hasil_lain" data-id-data-pemeriksaan="' + item.id + '" data-rujukan="' + (item.rujukan || '') + '" data-ch="' + (item.ch || '') + '" data-cl="' + (item.cl || '') + '" autocomplete="off"></td>' +
                    '    <td class="keterangan-cell-hasil-lain"><div class="keterangan-display-hasil-lain bg-success bg-opacity-10 text-success rounded py-1 px-2 text-center" data-keterangan="-"><strong>-</strong></div><input type="hidden" class="keterangan-input-hasil-lain" value="-"></td>' +
                    '    <td><button type="button" class="btn btn-sm btn-outline-danger hapus-row-btn-hasil-lain"><i class="ri-delete-bin-line"></i></button></td>' +
                    '</tr>';

                $tbody.append(newRow);
                const $lastRow = $tbody.find('tr:last-child');
                updateFormNames($lastRow);

                // Simpan ke database (sedikit delay agar DOM stabil)
                setTimeout(function() {
                    saveDataPemeriksaanToDatabase($lastRow, item.id, item.nama, item.satuan, item.rujukan);
                }, 100);
            });

            const modal = bootstrap.Modal.getInstance(document.getElementById('modalPilihDataPemeriksaan'));
            if (modal) modal.hide();
            const count = modalSelectedData.length;
            modalSelectedData = [];
            if (typeof window.showToast === 'function') window.showToast('success', count + ' data pemeriksaan berhasil ditambahkan');
        });

        // ============================================
        // 3. HAPUS TABEL
        // ============================================
        $(document).on('click', '.hapus-tabel-btn-hasil-lain', function(e) {
            e.preventDefault();
            const jenisPemeriksaan = $(this).data('jenis-pemeriksaan');
            const $section = $(this).closest('.pemeriksaan-lain-section');
            tabelYangAkanDihapus = { jenisPemeriksaan: jenisPemeriksaan, $section: $section };
            $('#modalNamaTabel').text(jenisPemeriksaan);
            const modal = new bootstrap.Modal(document.getElementById('modalKonfirmasiHapusTabel'));
            modal.show();
        });

        $(document).on('click', '#konfirmasiHapusTabelBtn', function() {
            if (!tabelYangAkanDihapus) return;
            const $section = tabelYangAkanDihapus.$section;
            const jenisPemeriksaan = tabelYangAkanDihapus.jenisPemeriksaan;
            const ids = [];
            $section.find('tr[data-id]').each(function() {
                const id = $(this).data('id');
                if (id) ids.push(id);
            });

            if (ids.length === 0) {
                $section.remove();
                const modal = bootstrap.Modal.getInstance(document.getElementById('modalKonfirmasiHapusTabel'));
                if (modal) modal.hide();
                if (window.showToast) window.showToast('success', 'Tabel ' + jenisPemeriksaan + ' berhasil dihapus');
                tabelYangAkanDihapus = null;
                return;
            }

            $.ajax({
                url: '/hasil-lain/destroy-multiple',
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken },
                data: { ids: ids },
                success: function(res) {
                    if (res.success) {
                        $section.remove();
                        if (window.showToast) window.showToast('success', res.message);
                    } else {
                        if (window.showToast) window.showToast('danger', res.message || 'Gagal menghapus tabel');
                    }
                },
                error: function() { if (window.showToast) window.showToast('danger', 'Terjadi kesalahan saat menghapus tabel'); },
                complete: function() {
                    const modal = bootstrap.Modal.getInstance(document.getElementById('modalKonfirmasiHapusTabel'));
                    if (modal) modal.hide();
                    tabelYangAkanDihapus = null;
                }
            });
        });

        // ============================================
        // 4. TAMBAH ROW MANUAL
        // ============================================
        $(document).on('click', '.tambah-row-btn-hasil-lain', function(e) {
            e.preventDefault();
            const jenisPemeriksaan = $(this).data('jenis-pemeriksaan');
            const $section = $(this).closest('.pemeriksaan-lain-section');
            const $tbody = $section.find('tbody');
            const rowCount = $tbody.find('tr').length;
            const newRow =
                '<tr data-index="' + rowCount + '" data-jenis-pemeriksaan="' + jenisPemeriksaan + '">' +
                '    <td class="search-cell-hasil-lain"><div class="position-relative">' +
                '        <input type="text" class="form-control form-control-sm search-data-pemeriksaan-hasil-lain" placeholder="Cari data pemeriksaan..." data-jenis-pemeriksaan="' + jenisPemeriksaan + '" data-index="' + rowCount + '" autocomplete="off">' +
                '        <div class="search-results-hasil-lain dropdown-menu" style="display: none; max-height: 200px; overflow-y: auto; z-index: 1050;"></div>' +
                '        <input type="hidden" class="id-data-pemeriksaan-input" value="">' +
                '        <input type="hidden" class="jenis-pengujian-input" value="">' +
                '        <input type="hidden" class="row-id-input" value="">' +
                '    </div></td>' +
                '    <td class="bg-light satuan-cell-hasil-lain"><span class="satuan-display-hasil-lain">-</span></td>' +
                '    <td class="bg-light rujukan-cell-hasil-lain"><span class="rujukan-display-hasil-lain">-</span></td>' +
                '    <td class="bg-light ch-cell-hasil-lain"><span class="ch-display-hasil-lain">-</span></td>' +
                '    <td class="bg-light cl-cell-hasil-lain"><span class="cl-display-hasil-lain">-</span></td>' +
                '    <td class="hasil-cell-hasil-lain"><input type="text" class="form-control form-control-sm hasil-input-hasil-lain" value="" placeholder="Hasil" data-id="" data-type="hasil_lain" autocomplete="off"></td>' +
                '    <td class="keterangan-cell-hasil-lain"><div class="keterangan-display-hasil-lain bg-success bg-opacity-10 text-success rounded py-1 px-2 text-center" data-keterangan="-"><strong>-</strong></div><input type="hidden" class="keterangan-input-hasil-lain" value="-"></td>' +
                '    <td><button type="button" class="btn btn-sm btn-outline-danger hapus-row-btn-hasil-lain"><i class="ri-delete-bin-line"></i></button></td>' +
                '</tr>';
            $tbody.append(newRow);
            setTimeout(function() { $tbody.find('tr:last-child .search-data-pemeriksaan-hasil-lain').focus(); }, 100);
        });

        // ============================================
        // 5. SEARCH REALTIME DATA PEMERIKSAAN
        // ============================================
        $(document).on('input', '.search-data-pemeriksaan-hasil-lain', function() {
            const $input = $(this);
            const searchTerm = $input.val().trim();
            const $results = $input.next('.search-results-hasil-lain');
            const jenisPemeriksaan = $input.data('jenis-pemeriksaan');

            clearTimeout($input.data('searchTimer'));
            if (searchTerm.length < 2) { $results.hide().empty(); return; }

            $input.data('searchTimer', setTimeout(function() {
                $.ajax({
                    url: '/hasil-lain/search-data-pemeriksaan',
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken },
                    data: { search: searchTerm, jenis_pemeriksaan: jenisPemeriksaan },
                    beforeSend: function() { $results.html('<div class="dropdown-item">Mencari data...</div>').show(); },
                    success: function(response) {
                        $results.empty();
                        if (response.success && response.data && response.data.length > 0) {
                            response.data.forEach(function(item) {
                                const option =
                                    '<button type="button" class="dropdown-item pilih-data-pemeriksaan-option" ' +
                                    'data-id="' + item.id_data_pemeriksaan + '" data-nama="' + item.data_pemeriksaan + '" data-satuan="' + (item.satuan || '') + '" data-rujukan="' + (item.rujukan || '') + '" data-ch="' + (item.ch || '') + '" data-cl="' + (item.cl || '') + '">' +
                                    '<div class="d-flex justify-content-between align-items-center"><div><strong>' + item.data_pemeriksaan + '</strong><div class="small text-muted">' + (item.satuan || '') + ' | ' + (item.rujukan || '') + '</div></div><i class="ri-arrow-right-s-line text-muted"></i></div></button>';
                                $results.append(option);
                            });
                        } else {
                            $results.html('<div class="dropdown-item text-muted">Tidak ditemukan data</div>');
                        }
                        $results.show();
                    },
                    error: function(xhr, status, error) { console.error('Search error:', error); $results.html('<div class="dropdown-item text-danger">Error</div>').show(); }
                });
            }, 500));
        });

        // ============================================
        // 6. PILIH DATA DARI SEARCH REALTIME
        // ============================================
        $(document).on('click', '.pilih-data-pemeriksaan-option', function(e) {
            e.preventDefault();
            const $option = $(this);
            const $row = $option.closest('tr');

            const idDataPemeriksaan = $option.data('id');
            const nama = $option.data('nama');
            const satuan = $option.data('satuan') || '-';
            const rujukan = $option.data('rujukan') || '-';
            const ch = $option.data('ch') || '-';
            const cl = $option.data('cl') || '-';

            $row.find('.search-data-pemeriksaan-hasil-lain').val(nama).attr('readonly', true);
            $row.find('.search-results-hasil-lain').hide().empty();

            $row.find('.id-data-pemeriksaan-input').val(idDataPemeriksaan);
            $row.find('.jenis-pengujian-input').val(nama);

            $row.find('.satuan-display-hasil-lain').text(satuan);
            $row.find('.rujukan-display-hasil-lain').text(rujukan);
            $row.find('.ch-display-hasil-lain').text(ch);
            $row.find('.cl-display-hasil-lain').text(cl);

            const $hasilInput = $row.find('.hasil-input-hasil-lain');
            $hasilInput.attr('data-id-data-pemeriksaan', idDataPemeriksaan)
                .attr('data-rujukan', rujukan)
                .attr('data-ch', ch)
                .attr('data-cl', cl);

            updateFormNames($row);
            updateRujukanBerdasarkanKondisi($row, idDataPemeriksaan);
            saveDataPemeriksaanToDatabase($row, idDataPemeriksaan, nama, satuan, rujukan);
        });

        // ============================================
        // FUNGSI: GET RUJUKAN BERDASARKAN KONDISI (single call)
        // ============================================
        function getRujukanByKondisi(idDataPemeriksaan, jenisKelamin, umurPasien, clientKey) {
            if (!idDataPemeriksaan) return Promise.resolve(null);

            return $.ajax({
                url: '{{ route("pasien.get-rujukan-by-kondisi-batch") }}',
                method: 'POST',
                data: {
                    items: [{
                        id_data_pemeriksaan: idDataPemeriksaan,
                        jenis_kelamin: jenisKelamin,
                        umur_pasien: umurPasien,
                        client_key: clientKey
                    }],
                    no_cache: true
                }
            }).then(function(res) {
                if (res && res.success && res.data && res.data[clientKey]) {
                    return res.data[clientKey].data;
                }
                return null;
            }).catch(function(err) {
                console.error('getRujukanByKondisi error:', err);
                return null;
            });
        }

        // ============================================
        // Update rujukan berdasarkan kondisi pasien
        // ============================================
        function updateRujukanBerdasarkanKondisi($row, idDataPemeriksaan) {
            if (!idDataPemeriksaan) return;

            const jenisKelamin = '{{ $pasien->jenis_kelamin }}';
            const umurPasien = '{{ $data["umur_format"] ?? "" }}';

            // Dapatkan row unique id (pakai DB id jika sudah ada)
            const rowId = getRowUniqueId($row);
            const clientKey = idDataPemeriksaan + '_' + rowId;

            console.log('updateRujukanBerdasarkanKondisi =>', { idDataPemeriksaan, rowId, clientKey });

            getRujukanByKondisi(idDataPemeriksaan, jenisKelamin, umurPasien, clientKey)
                .then(function(rujukanData) {
                    if (!rujukanData) {
                        console.log('Tidak ada rujukan khusus untuk', clientKey);
                        return;
                    }

                    // Ambil nilai
                    const rujukan = rujukanData.rujukan || $row.find('.rujukan-display-hasil-lain').text().trim() || '-';
                    const satuan = rujukanData.satuan || $row.find('.satuan-display-hasil-lain').text().trim() || '-';
                    const ch = rujukanData.ch || $row.find('.ch-display-hasil-lain').text().trim() || '-';
                    const cl = rujukanData.cl || $row.find('.cl-display-hasil-lain').text().trim() || '-';

                    // Siapkan tampilan rujukan (dengan badge K bila dari kondisi)
                    let rujukanDisplay = rujukan;
                    if (rujukanData.is_from_detail) {
                        rujukanDisplay += ' <span class="badge bg-info ms-1" title="CH/CL khusus kondisi">K</span>';
                        $row.addClass('table-info');
                    } else {
                        $row.removeClass('table-info');
                    }

                    // Update UI
                    $row.find('.rujukan-display-hasil-lain').html(rujukanDisplay);
                    $row.find('.satuan-display-hasil-lain').text(satuan);
                    // tambahkan detail kecil jika ada dari kondisi
                    let chHtml = ch || '-';
                    if (rujukanData.is_from_detail && ch && ch !== '-' && ch !== '') chHtml += '<br><small class="text-info">detail</small>';
                    $row.find('.ch-display-hasil-lain').html(chHtml);

                    let clHtml = cl || '-';
                    if (rujukanData.is_from_detail && cl && cl !== '-' && cl !== '') clHtml += '<br><small class="text-info">detail</small>';
                    $row.find('.cl-display-hasil-lain').html(clHtml);

                    // Update atribut data pada input hasil untuk perhitungan nanti
                    const $hasilInput = $row.find('.hasil-input-hasil-lain');
                    $hasilInput
                        .attr('data-rujukan', rujukan)
                        .attr('data-ch', ch)
                        .attr('data-cl', cl);

                    // Jika sudah ada hasil, hitung ulang keterangan
                    if ($hasilInput.val() && $hasilInput.val().trim() !== '') {
                        updateKeteranganHasilLain($hasilInput);
                    }

                    console.log('Rujukan updated for', clientKey, rujukanData);
                })
                .catch(function(err) {
                    console.error('updateRujukanBerdasarkanKondisi error:', err);
                });
        }

        // ============================================
        // SAVE DATA PEMERIKSAAN TO DATABASE (single definition)
        // ============================================
        function saveDataPemeriksaanToDatabase($row, idDataPemeriksaan, jenisPengujian, satuan, rujukan) {
            const noLab = window.pasienNoLab || '{{ $pasien->no_lab }}';
            const jenisPemeriksaan = $row.data('jenis-pemeriksaan');

            if (!noLab || !idDataPemeriksaan) {
                console.error('No Lab atau ID Data Pemeriksaan tidak ditemukan');
                return;
            }

            console.log('SAVE HASIL LAIN →', {
                noLab,
                jenisPemeriksaan,
                idDataPemeriksaan
            });

            $.ajax({
                url: '/hasil-lain/store-manual',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                data: {
                    no_lab: noLab,
                    jenis_pemeriksaan: jenisPemeriksaan,
                    id_data_pemeriksaan: idDataPemeriksaan,
                    jenis_pengujian: jenisPengujian,
                    satuan: satuan,
                    rujukan: rujukan
                },
                beforeSend: function() {
                    $row.addClass('table-warning');
                },
                success: function(response) {
                    console.log('SAVE RESPONSE:', response);

                    if (!response.success || !response.data || !response.data.id_hasil_lain) {
                        $row.removeClass('table-warning');
                        window.showToast?.('danger', response.message || 'Gagal menyimpan data');
                        return;
                    }

                    const dbId = response.data.id_hasil_lain;

                    // ============================
                    // 1. SET ID DB KE ROW (WAJIB)
                    // ============================
                    $row
                        .attr('data-id', dbId)
                        .data('id', dbId);

                    $row.find('.row-id-input').val(dbId);
                    $row.find('.hasil-input-hasil-lain')
                        .attr('data-id', dbId)
                        .data('id', dbId);

                    // ============================
                    // 2. UPDATE FORM INPUT NAMES
                    // ============================
                    updateFormInputs($row, dbId);

                    // ============================
                    // 3. FETCH ULANG RUJUKAN
                    // ============================
                    updateRujukanBerdasarkanKondisi($row, idDataPemeriksaan);

                    // ============================
                    // 4. UI FEEDBACK
                    // ============================
                    setTimeout(function() {
                        $row.removeClass('table-warning').addClass('table-success');
                        setTimeout(() => $row.removeClass('table-success'), 2000);
                    }, 100);

                    window.showToast?.('success', 'Data berhasil disimpan');
                },
                error: function(xhr) {
                    console.error('SAVE ERROR:', xhr.responseText);
                    $row.removeClass('table-warning');
                    window.showToast?.('danger', 'Gagal menyimpan data');
                }
            });
        }

        // ============================================
        // Update names / inputs helpers
        // ============================================
        function updateFormNames($row) {
            const rowIndex = $row.data('index');
            const jenisPemeriksaan = $row.data('jenis-pemeriksaan');

            $row.find('.id-data-pemeriksaan-input').attr('name', 'hasil_lain[' + jenisPemeriksaan + '][' + rowIndex + '][id_data_pemeriksaan]');
            $row.find('.jenis-pengujian-input').attr('name', 'hasil_lain[' + jenisPemeriksaan + '][' + rowIndex + '][jenis_pengujian]');
            $row.find('.row-id-input').attr('name', 'hasil_lain[' + jenisPemeriksaan + '][' + rowIndex + '][id]');
            $row.find('.hasil-input-hasil-lain').attr('name', 'hasil_lain[' + jenisPemeriksaan + '][' + rowIndex + '][hasil_pengujian]');
            $row.find('.keterangan-input-hasil-lain').attr('name', 'hasil_lain[' + jenisPemeriksaan + '][' + rowIndex + '][keterangan]');

            const chValue = $row.find('.ch-display-hasil-lain').text();
            const clValue = $row.find('.cl-display-hasil-lain').text();

            if (!$row.find('.ch-input-hidden').length) {
                $row.find('.ch-cell-hasil-lain').append('<input type="hidden" class="ch-input-hidden" name="hasil_lain[' + jenisPemeriksaan + '][' + rowIndex + '][ch]" value="' + chValue + '">');
            } else {
                $row.find('.ch-input-hidden').val(chValue);
            }
            if (!$row.find('.cl-input-hidden').length) {
                $row.find('.cl-cell-hasil-lain').append('<input type="hidden" class="cl-input-hidden" name="hasil_lain[' + jenisPemeriksaan + '][' + rowIndex + '][cl]" value="' + clValue + '">');
            } else {
                $row.find('.cl-input-hidden').val(clValue);
            }
        }

        function updateFormInputs($row, idHasilLain) {
            updateFormNames($row);
            $row.find('.row-id-input').val(idHasilLain);
        }

        // ============================================
        // 7. FUNGSI UPDATE KETERANGAN HASIL LAIN
        // ============================================
        async function updateKeteranganHasilLain($input) {
            const hasil = $input.val().trim();
            const $row = $input.closest('tr');
            const $keteranganDisplay = $row.find('.keterangan-display-hasil-lain');
            const $hiddenInput = $row.find('.keterangan-input-hasil-lain');

            console.log('=== HASIL LAIN - updateKeterangan ===');
            console.log('Hasil:', hasil);

            // Clear jika kosong
            if (!hasil) {
                console.log('Hasil kosong, reset keterangan');
                updateKeteranganDisplay($keteranganDisplay, '');
                $hiddenInput.val('');
                return;
            }

            // Dapatkan data
            const idDataPemeriksaan = $input.data('id-data-pemeriksaan');

            // Gunakan data yang sudah ada di input
            let rujukan = $input.data('rujukan') || '';
            let ch = $input.data('ch') || '';
            let cl = $input.data('cl') || '';

            console.log('Data awal dari input:', { idDataPemeriksaan, rujukan, ch, cl });

            // Jika ada ID data pemeriksaan, ambil data rujukan berdasarkan kondisi
            if (idDataPemeriksaan) {
                try {
                    console.log('Mengambil rujukan berdasarkan kondisi...');
                    const rujukanData = await fetchRujukanByKondisiHasilLain(idDataPemeriksaan, $row, $input);

                    if (rujukanData) {
                        console.log('Data rujukan ditemukan:', rujukanData);

                        // Update data lokal dengan data dari kondisi
                        rujukan = rujukanData.rujukan || rujukan;
                        ch = rujukanData.ch || ch;
                        cl = rujukanData.cl || cl;

                        console.log('Data setelah update dari kondisi:', { rujukan, ch, cl });

                        // Update data pada input untuk penggunaan selanjutnya
                        $input
                            .data('rujukan', rujukan)
                            .data('ch', ch)
                            .data('cl', cl);
                    } else {
                        console.log('Tidak ada data rujukan dari kondisi');
                    }
                } catch (error) {
                    console.error('Error mendapatkan rujukan berdasarkan kondisi:', error);
                }
            }

            // Gunakan data yang sudah diupdate untuk perhitungan
            calculateAndUpdateKeteranganHasilLain($input, $keteranganDisplay, $hiddenInput, hasil, rujukan, ch, cl);
        }

        // ============================================
        // 8. FUNGSI: FETCH RUJUKAN BERDASARKAN KONDISI HASIL LAIN
        // ============================================
        function fetchRujukanByKondisiHasilLain(idDataPemeriksaan, $row, $input) {
            const jenisKelamin = '{{ $pasien->jenis_kelamin }}';
            const umurPasien = '{{ $data["umur_format"] ?? "" }}';

            // Dapatkan row unique id
            const rowId = getRowUniqueId($row);
            const clientKey = idDataPemeriksaan + '_' + rowId;

            console.log('fetchRujukanByKondisiHasilLain =>', { idDataPemeriksaan, rowId, clientKey });

            return new Promise((resolve, reject) => {
                $.ajax({
                    url: '{{ route("pasien.get-rujukan-by-kondisi-batch") }}',
                    method: 'POST',
                    data: {
                        items: [{
                            id_data_pemeriksaan: idDataPemeriksaan,
                            jenis_kelamin: jenisKelamin,
                            umur_pasien: umurPasien,
                            client_key: clientKey
                        }],
                        no_cache: true
                    },
                    success: function(res) {
                        if (res && res.success && res.data && res.data[clientKey]) {
                            resolve(res.data[clientKey].data);
                        } else {
                            resolve(null);
                        }
                    },
                    error: function(err) {
                        console.error('fetchRujukanByKondisiHasilLain error:', err);
                        reject(err);
                    }
                });
            });
        }

        // ============================================
        // 9. FUNGSI: CALCULATE AND UPDATE KETERANGAN HASIL LAIN
        // ============================================
        function calculateAndUpdateKeteranganHasilLain($input, $keteranganDisplay, $hiddenInput, hasil, rujukan, ch, cl) {
            console.log('Hasil Lain - Perhitungan dengan:', {
                hasil,
                rujukan: rujukan || '(kosong)',
                ch: ch || '(kosong)',
                cl: cl || '(kosong)'
            });

            // Validasi input
            if (!hasil || hasil.trim() === '') {
                console.log('Hasil kosong');
                updateKeteranganDisplay($keteranganDisplay, '');
                $hiddenInput.val('');
                return;
            }

            const hasilStr = hasil.toString().trim();
            const hasilNum = parseFloat(hasilStr.replace(',', '.'));

            // Jika rujukan tidak tersedia
            if (!rujukan || rujukan === '' || rujukan === '-' || rujukan === 'null') {
                console.log('Rujukan tidak tersedia');

                // Coba gunakan CH/CL jika ada
                if (ch && ch !== '' && ch !== '-' && ch !== 'null' &&
                    cl && cl !== '' && cl !== '-' && cl !== 'null') {

                    const chNum = parseFloat(ch.toString().replace(',', '.'));
                    const clNum = parseFloat(cl.toString().replace(',', '.'));

                    console.log('Menggunakan CH/CL:', { chNum, clNum, hasilNum });

                    if (!isNaN(chNum) && !isNaN(clNum) && !isNaN(hasilNum)) {
                        if (hasilNum > chNum) {
                            console.log(`CH dari data CH/CL: ${hasilNum} > ${chNum}`);
                            updateKeteranganDisplay($keteranganDisplay, 'CH');
                            $hiddenInput.val('CH');
                            return;
                        } else if (hasilNum < clNum) {
                            console.log(`CL dari data CH/CL: ${hasilNum} < ${clNum}`);
                            updateKeteranganDisplay($keteranganDisplay, 'CL');
                            $hiddenInput.val('CL');
                            return;
                        }
                    }
                }

                console.log('Tidak ada data untuk perhitungan');
                updateKeteranganDisplay($keteranganDisplay, '-');
                $hiddenInput.val('-');
                return;
            }

            const rujukanStr = rujukan.toString().trim();
            const chStr = ch ? ch.toString().trim() : '';
            const clStr = cl ? cl.toString().trim() : '';

            // 1. CEK CRITICAL HIGH/LOW (CH/CL) - PRIORITAS TERTINGGI
            console.log('Cek CH/CL:', { chStr, clStr, hasilNum });

            // Handle CH dengan berbagai format: >10, >=10, > 10, >= 10
            if (chStr && chStr !== '' && chStr !== '-' && chStr !== 'null') {
                let chNum;
                // Format: >=10 atau >= 10
                if (chStr.includes('>=')) {
                    chNum = parseFloat(chStr.replace('>=', '').replace(',', '.').trim());
                    console.log('Parsed CH (>=):', chNum);

                    if (!isNaN(chNum) && !isNaN(hasilNum)) {
                        if (hasilNum >= chNum) {
                            console.log(`✅ HASIL LAIN CH DETECTED: ${hasilNum} >= ${chNum}`);
                            updateKeteranganDisplay($keteranganDisplay, 'CH');
                            $hiddenInput.val('CH');
                            return;
                        }
                    }
                }
                // Format: >10 atau > 10
                else if (chStr.includes('>')) {
                    chNum = parseFloat(chStr.replace('>', '').replace(',', '.').trim());
                    console.log('Parsed CH (>):', chNum);

                    if (!isNaN(chNum) && !isNaN(hasilNum)) {
                        if (hasilNum > chNum) {
                            console.log(`✅ HASIL LAIN CH DETECTED: ${hasilNum} > ${chNum}`);
                            updateKeteranganDisplay($keteranganDisplay, 'CH');
                            $hiddenInput.val('CH');
                            return;
                        }
                    }
                }
                // Format angka biasa
                else {
                    chNum = parseFloat(chStr.replace(',', '.'));
                    console.log('Parsed CH (angka):', chNum);

                    if (!isNaN(chNum) && !isNaN(hasilNum) && hasilNum > chNum) {
                        console.log(`✅ HASIL LAIN CH DETECTED: ${hasilNum} > ${chNum}`);
                        updateKeteranganDisplay($keteranganDisplay, 'CH');
                        $hiddenInput.val('CH');
                        return;
                    }
                }
            }

            // Handle CL dengan berbagai format: <10, <=10, < 10, <= 10
            if (clStr && clStr !== '' && clStr !== '-' && clStr !== 'null') {
                let clNum;
                // Format: <=10 atau <= 10
                if (clStr.includes('<=')) {
                    clNum = parseFloat(clStr.replace('<=', '').replace(',', '.').trim());
                    console.log('Parsed CL (<=):', clNum);

                    if (!isNaN(clNum) && !isNaN(hasilNum)) {
                        if (hasilNum <= clNum) {
                            console.log(`✅ HASIL LAIN CL DETECTED: ${hasilNum} <= ${clNum}`);
                            updateKeteranganDisplay($keteranganDisplay, 'CL');
                            $hiddenInput.val('CL');
                            return;
                        }
                    }
                }
                // Format: <10 atau < 10
                else if (clStr.includes('<')) {
                    clNum = parseFloat(clStr.replace('<', '').replace(',', '.').trim());
                    console.log('Parsed CL (<):', clNum);

                    if (!isNaN(clNum) && !isNaN(hasilNum)) {
                        if (hasilNum < clNum) {
                            console.log(`✅ HASIL LAIN CL DETECTED: ${hasilNum} < ${clNum}`);
                            updateKeteranganDisplay($keteranganDisplay, 'CL');
                            $hiddenInput.val('CL');
                            return;
                        }
                    }
                }
                // Format angka biasa
                else {
                    clNum = parseFloat(clStr.replace(',', '.'));
                    console.log('Parsed CL (angka):', clNum);

                    if (!isNaN(clNum) && !isNaN(hasilNum) && hasilNum < clNum) {
                        console.log(`✅ HASIL LAIN CL DETECTED: ${hasilNum} < ${clNum}`);
                        updateKeteranganDisplay($keteranganDisplay, 'CL');
                        $hiddenInput.val('CL');
                        return;
                    }
                }
            }

            // 2. CEK HASIL KUALITATIF (NON-NUMERIC)
            if (isNaN(hasilNum)) {
                console.log('Hasil non-numerik, cek kualitatif');
                const hasilLower = hasilStr.toLowerCase();
                const rujukanLower = rujukanStr.toLowerCase();

                console.log('Kualitatif:', { hasilLower, rujukanLower });

                if (rujukanLower.includes('negative') || rujukanLower.includes('negatif')) {
                    if (hasilLower.includes('negative') || hasilLower.includes('negatif') ||
                        hasilLower.includes('non-reactive') || hasilLower.includes('nonreactive')) {
                        updateKeteranganDisplay($keteranganDisplay, '-');
                        $hiddenInput.val('-');
                    } else {
                        updateKeteranganDisplay($keteranganDisplay, 'H');
                        $hiddenInput.val('H');
                    }
                    return;
                } else if (rujukanLower.includes('positive') || rujukanLower.includes('positif')) {
                    if (hasilLower.includes('positive') || hasilLower.includes('positif') ||
                        hasilLower.includes('reactive') || hasilLower.includes('reaktif')) {
                        updateKeteranganDisplay($keteranganDisplay, '-');
                        $hiddenInput.val('-');
                    } else {
                        updateKeteranganDisplay($keteranganDisplay, 'L');
                        $hiddenInput.val('L');
                    }
                    return;
                }

                // Default untuk non-numerik
                updateKeteranganDisplay($keteranganDisplay, '-');
                $hiddenInput.val('-');
                return;
            }

            // 3. CEK RUJUKAN NUMERIK
            console.log('Cek rujukan numerik:', rujukanStr);

            // Format range: "1 - 90" atau "1-90"
            if (rujukanStr.includes('-') && !rujukanStr.includes('<') && !rujukanStr.includes('>') && !rujukanStr.includes('=')) {
                // Bersihkan whitespace
                const cleanStr = rujukanStr.replace(/\s+/g, '');
                const parts = cleanStr.split('-');

                console.log('Range parts:', parts);

                if (parts.length === 2) {
                    const min = parseFloat(parts[0].replace(',', '.'));
                    const max = parseFloat(parts[1].replace(',', '.'));

                    console.log('Parsed range:', { min, max, hasilNum });

                    if (!isNaN(min) && !isNaN(max)) {
                        if (hasilNum < min) {
                            console.log(`✅ HASIL LAIN L DETECTED: ${hasilNum} < ${min}`);
                            updateKeteranganDisplay($keteranganDisplay, 'L');
                            $hiddenInput.val('L');
                        } else if (hasilNum > max) {
                            console.log(`✅ HASIL LAIN H DETECTED: ${hasilNum} > ${max}`);
                            updateKeteranganDisplay($keteranganDisplay, 'H');
                            $hiddenInput.val('H');
                        } else {
                            console.log(`✅ HASIL LAIN NORMAL: ${hasilNum} dalam range ${min}-${max}`);
                            updateKeteranganDisplay($keteranganDisplay, '-');
                            $hiddenInput.val('-');
                        }
                        return;
                    }
                }
            }

            // Format: ">= X" atau ">=X"
            if (rujukanStr.includes('>=')) {
                const batas = parseFloat(rujukanStr.replace('>=', '').replace(',', '.').trim());
                console.log('Parsed >= format:', { batas, hasilNum });

                if (!isNaN(batas)) {
                    if (hasilNum < batas) {
                        console.log(`✅ HASIL LAIN L DETECTED: ${hasilNum} < ${batas}`);
                        updateKeteranganDisplay($keteranganDisplay, 'L');
                        $hiddenInput.val('L');
                    } else {
                        console.log(`✅ HASIL LAIN NORMAL: ${hasilNum} >= ${batas}`);
                        updateKeteranganDisplay($keteranganDisplay, '-');
                        $hiddenInput.val('-');
                    }
                    return;
                }
            }

            // Format: "<= X" atau "<=X"
            if (rujukanStr.includes('<=')) {
                const batas = parseFloat(rujukanStr.replace('<=', '').replace(',', '.').trim());
                console.log('Parsed <= format:', { batas, hasilNum });

                if (!isNaN(batas)) {
                    if (hasilNum > batas) {
                        console.log(`✅ HASIL LAIN H DETECTED: ${hasilNum} > ${batas}`);
                        updateKeteranganDisplay($keteranganDisplay, 'H');
                        $hiddenInput.val('H');
                    } else {
                        console.log(`✅ HASIL LAIN NORMAL: ${hasilNum} <= ${batas}`);
                        updateKeteranganDisplay($keteranganDisplay, '-');
                        $hiddenInput.val('-');
                    }
                    return;
                }
            }

            // Format: "> X" atau ">X"
            if (rujukanStr.includes('>') && !rujukanStr.includes('>=')) {
                const batas = parseFloat(rujukanStr.replace('>', '').replace(',', '.').trim());
                console.log('Parsed > format:', { batas, hasilNum });

                if (!isNaN(batas)) {
                    if (hasilNum <= batas) {
                        console.log(`✅ HASIL LAIN L DETECTED: ${hasilNum} ≤ ${batas}`);
                        updateKeteranganDisplay($keteranganDisplay, 'L');
                        $hiddenInput.val('L');
                    } else {
                        console.log(`✅ HASIL LAIN NORMAL: ${hasilNum} > ${batas}`);
                        updateKeteranganDisplay($keteranganDisplay, '-');
                        $hiddenInput.val('-');
                    }
                    return;
                }
            }

            // Format: "< X" atau "<X"
            if (rujukanStr.includes('<') && !rujukanStr.includes('<=')) {
                const batas = parseFloat(rujukanStr.replace('<', '').replace(',', '.').trim());
                console.log('Parsed < format:', { batas, hasilNum });

                if (!isNaN(batas)) {
                    if (hasilNum >= batas) {
                        console.log(`✅ HASIL LAIN H DETECTED: ${hasilNum} ≥ ${batas}`);
                        updateKeteranganDisplay($keteranganDisplay, 'H');
                        $hiddenInput.val('H');
                    } else {
                        console.log(`✅ HASIL LAIN NORMAL: ${hasilNum} < ${batas}`);
                        updateKeteranganDisplay($keteranganDisplay, '-');
                        $hiddenInput.val('-');
                    }
                    return;
                }
            }

            // Format single value: "X"
            const singleValue = parseFloat(rujukanStr.replace(',', '.'));
            if (!isNaN(singleValue)) {
                console.log('Parsed single value:', { singleValue, hasilNum });

                // Untuk single value, cek apakah hasil sama dengan nilai rujukan
                const tolerance = 0.0001; // Toleransi kecil untuk floating point

                if (Math.abs(hasilNum - singleValue) < tolerance) {
                    console.log(`✅ HASIL LAIN NORMAL: ${hasilNum} sama dengan ${singleValue}`);
                    updateKeteranganDisplay($keteranganDisplay, '-');
                    $hiddenInput.val('-');
                } else if (hasilNum < singleValue) {
                    console.log(`✅ HASIL LAIN L DETECTED: ${hasilNum} < ${singleValue}`);
                    updateKeteranganDisplay($keteranganDisplay, 'L');
                    $hiddenInput.val('L');
                } else {
                    console.log(`✅ HASIL LAIN H DETECTED: ${hasilNum} > ${singleValue}`);
                    updateKeteranganDisplay($keteranganDisplay, 'H');
                    $hiddenInput.val('H');
                }
                return;
            }

            // Default - tidak ada pola yang cocok
            console.log('Tidak ada pola rujukan yang cocok');
            updateKeteranganDisplay($keteranganDisplay, '-');
            $hiddenInput.val('-');
        }

        // ============================================
        // 10. FUNGSI UPDATE KETERANGAN DISPLAY
        // ============================================
        function updateKeteranganDisplay($display, keterangan) {
            if (!$display.length) {
                console.error('Display element tidak ditemukan');
                return;
            }

            // Reset semua kelas
            $display.removeClass(
                'bg-danger bg-opacity-10 ' +
                'bg-primary bg-opacity-10 ' +
                'bg-success bg-opacity-10 ' +
                'text-danger text-primary text-success'
            );

            // Set kelas berdasarkan keterangan
            let bgClass = '';
            let textClass = '';
            let displayText = '';

            switch (keterangan) {
                case 'CH':
                case 'H':
                    bgClass = 'bg-danger bg-opacity-10';
                    textClass = 'text-danger';
                    displayText = keterangan;
                    break;

                case 'CL':
                case 'L':
                    bgClass = 'bg-primary bg-opacity-10';
                    textClass = 'text-primary';
                    displayText = keterangan;
                    break;

                case '-':
                    bgClass = 'bg-success bg-opacity-10';
                    textClass = 'text-success';
                    displayText = '';
                    break;

                default:
                    bgClass = 'bg-light';
                    textClass = 'text-muted';
                    displayText = '-';
                    keterangan = '-';
            }

            // Apply classes
            $display.addClass(bgClass + ' ' + textClass);
            $display.html('<strong>' + displayText + '</strong>');
            $display.data('keterangan', keterangan);
        }

        // ============================================
        // 11. UPDATE HASIL PENGUJIAN
        // ============================================
        $(document).on('input', '.hasil-input-hasil-lain', function() {
            const $input = $(this);
            const id = $input.data('id');
            const value = $input.val();
            if (!id) {
                console.log('ID belum ada, data belum disimpan ke database');
                updateKeteranganHasilLain($input); // Tetap update keterangan meski belum ada ID
                return;
            }
            updateKeteranganHasilLain($input);
            clearTimeout($input.data('saveTimer'));
            $input.data('saveTimer', setTimeout(function() {
                saveHasilPengujian(id, value, $input);
            }, 800));
        });

        function saveHasilPengujian(id, value, $input) {
            const $row = $input.closest('tr');
            const keterangan = $row.find('.keterangan-input-hasil-lain').val();

            console.log('Saving hasil pengujian - ID:', id, 'Value:', value, 'Keterangan:', keterangan);

            $.ajax({
                url: '/hasil-lain/update-hasil-pengujian/' + id,
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                data: {
                    hasil_pengujian: value,
                    keterangan: keterangan
                },
                beforeSend: function() {
                    $input.addClass('is-changing');
                },
                success: function(response) {
                    console.log('Save response:', response);

                    if (response.success) {
                        $input.removeClass('is-changing').addClass('is-changed');

                        if (typeof window.updateSaveStatus === 'function') {
                            window.updateSaveStatus();
                        }

                        if (typeof window.showToast === 'function') {
                            window.showToast('success', 'Hasil berhasil disimpan');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Save error:', error);
                    $input.removeClass('is-changing').addClass('has-error');

                    if (typeof window.showToast === 'function') {
                        window.showToast('danger', 'Gagal menyimpan hasil');
                    }
                }
            });
        }

        // ============================================
        // 12. HAPUS ROW INDIVIDUAL
        // ============================================
        $(document).on('click', '.hapus-row-btn-hasil-lain', function() {
            const $row = $(this).closest('tr');
            const rowId = $row.data('id');
            if (!rowId) { $row.remove(); return; }
            if (!confirm('Yakin ingin menghapus data ini secara permanen?')) return;
            $.ajax({
                url: '/hasil-lain/destroy/' + rowId,
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': csrfToken },
                success: function(res) {
                    if (res.success) { $row.remove(); if (window.showToast) window.showToast('success', res.message); }
                    else { if (window.showToast) window.showToast('danger', res.message || 'Gagal menghapus data'); }
                },
                error: function() { if (window.showToast) window.showToast('danger', 'Gagal menghapus data'); }
            });
        });

        // ============================================
        // 13. CLOSE DROPDOWN SAAT KLIK DI LUAR
        // ============================================
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.search-cell-hasil-lain').length) {
                $('.search-results-hasil-lain').hide().empty();
            }
        });

        // ============================================
        // 14. ESCAPE KEY UNTUK TUTUP DROPDOWN
        // ============================================
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape') {
                $('.search-results-hasil-lain').hide().empty();
            }
        });

        // ============================================
        // 15. INISIALISASI DATA SAAT PAGE LOAD
        // ============================================
        $(window).on('load', function() {
            setTimeout(function() {
                $('.hasil-input-hasil-lain').each(function() {
                    const $input = $(this);
                    const $row = $input.closest('tr');
                    const idDataPemeriksaan = $row.find('.id-data-pemeriksaan-input').val();
                    if (idDataPemeriksaan) updateRujukanBerdasarkanKondisi($row, idDataPemeriksaan);
                    if ($input.val() && $input.val().trim() !== '') updateKeteranganHasilLain($input);
                });
                console.log('✅ Hasil Lain initialization complete');
            }, 2000);
        });

        console.log('✅ Hasil Lain System Complete Production Version Loaded');
    });
</script>
<!-- END SCRIPT HASIL LAIN SYSTEM -->

<!-- SCRIPT BATCH REQUEST RUJUKAN BERDASARKAN KONDISI-->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // baca payload dari element yang kita buat
        const holder = document.getElementById('rujukan-batch-data');
        if (!holder) return;

        let items;
        try {
            items = JSON.parse(holder.dataset.items || '[]');
        } catch (e) {
            console.error('Payload rujukan tidak valid', e);
            items = [];
        }

        if (!Array.isArray(items) || items.length === 0) {
            // jika tidak ada item, pastikan resolve dengan null untuk queue yang mungkin ada
            if (window.__rujukanResolvers) {
                Object.entries(window.__rujukanResolvers).forEach(([id, arr]) => {
                    arr.forEach(fn => fn(null));
                });
                window.__rujukanResolvers = {};
            }
            return;
        }

        // anti double run
        if (window.__rujukanBatchRequested) return;
        window.__rujukanBatchRequested = true;

        // kirim 1 request batch (POST)
        fetch('{{ route("pasien.get-rujukan-by-kondisi-batch") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ items: items })
        })
        .then(r => r.json())
        .then(json => {
            // struktur expected: { success: true, data: { "<id>": { success: true, data: [...] }, ... } }
            const resultMap = (json && json.data) ? json.data : {};

            // siapkan cache global
            window.__rujukanCache = window.__rujukanCache || {};
            window.__rujukanResolvers = window.__rujukanResolvers || {};

            // isi cache + resolve any waiting resolvers
            Object.entries(resultMap).forEach(([id, payload]) => {
                const data = (payload && payload.success) ? payload.data : null;
                window.__rujukanCache[id] = data;

                // resolve queued promises
                const resolvers = window.__rujukanResolvers[id] || [];
                resolvers.forEach(fn => {
                    try { fn(data); } catch (e) { console.error(e); }
                });
                delete window.__rujukanResolvers[id];

                // update DOM bila ada elemen target: id="rujukan-<id>"
                const el = document.getElementById('rujukan-' + id);
                if (el) {
                    if (!data || data.length === 0) {
                        el.innerHTML = '<small class="text-muted">Tidak ada rujukan</small>';
                    } else {
                        el.innerHTML = '<ul class="mb-0 ps-3">' +
                            data.map(r => `<li>${r.nilai_min} - ${r.nilai_max} ${r.satuan ?? ''}</li>`).join('') +
                            '</ul>';
                    }
                }
            });

            // untuk ID yang tidak ada di resultMap, resolve null
            Object.keys(window.__rujukanResolvers || {}).forEach(id => {
                const arr = window.__rujukanResolvers[id] || [];
                arr.forEach(fn => fn(null));
                delete window.__rujukanResolvers[id];
            });
        })
        .catch(err => {
            console.error('Batch rujukan gagal', err);
            // on error resolve all waiting resolvers with null
            Object.entries(window.__rujukanResolvers || {}).forEach(([id, arr]) => {
                arr.forEach(fn => fn(null));
            });
            window.__rujukanResolvers = {};
        });
    });
</script>
<!-- END SCRIPT BATCH REQUEST RUJUKAN BERDASARKAN KONDISI-->

<!-- SCRIPT TAMBAH ROW & SEARCH HEMATOLOGY -->
<script>
    $(document).ready(function() {
        const csrfToken = $('meta[name="csrf-token"]').attr('content');

        function getHematologiRowId($row) {
            return $row.data('hematologi-id') ?? $row.data('id') ?? null;
        }

        // ==============================
        // TOMBOL TAMBAH ROW HEMATOLOGI
        // ==============================
        $('#tambahRowHematologyBtn').on('click', function() {
            const $table = $('#hematologyTable tbody');
            const currentRowCount = $table.find('tr').length;
            const newIndex = currentRowCount;
            const manualId = 'manual_hematologi_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);

            // Row baru
            const newRow = `
                <tr data-index="${newIndex}" data-hematologi-id="${manualId}" class="table-warning hematologi-row">
                    <td class="search-cell">
                        <div class="position-relative">
                            <input type="text"
                                class="form-control form-control-sm kode-search-input-hematologi"
                                placeholder="Cari data pemeriksaan..."
                                data-hematologi-id="${manualId}"
                                data-index="${newIndex}"
                                autocomplete="off">
                            <div class="kode-search-results-hematologi dropdown-menu w-100"
                                style="display: none; max-height: 200px; overflow-y: auto;">
                            </div>
                            <input type="hidden"
                                name="hematologi[${newIndex}][id]"
                                value="${manualId}">
                            <input type="hidden"
                                name="hematologi[${newIndex}][id_data_pemeriksaan]"
                                class="kode-pemeriksaan-input-hematologi"
                                value="">
                        </div>
                    </td>
                    <td class="hasil-cell">
                        <input type="text"
                            name="hematologi[${newIndex}][hasil_pengujian]"
                            class="form-control form-control-sm hasil-input-hematologi"
                            value=""
                            placeholder="Hasil"
                            data-id="${manualId}"
                            data-type="hematologi"
                            data-id-data-pemeriksaan=""
                            data-jenis=""
                            data-rujukan=""
                            data-ch=""
                            data-cl=""
                            data-umur="{{ $data["umur_format"] }}"
                            data-jenis-kelamin="{{ $pasien->jenis_kelamin }}"
                            autocomplete="off">
                    </td>
                    <td class="bg-light satuan-cell" style="text-align:center;">
                        <span class="satuan-display">-</span>
                    </td>
                    <td class="bg-light rujukan-cell" style="text-align:center;">
                        <span class="rujukan-display">-</span>
                    </td>
                    <td class="bg-light ch-cell" style="text-align:center;">
                        <span class="ch-display">-</span>
                    </td>
                    <td class="bg-light cl-cell" style="text-align:center;">
                        <span class="cl-display">-</span>
                    </td>
                    <td class="keterangan-cell">
                        <div class="keterangan-display bg-success bg-opacity-10 text-success rounded py-1 px-2 text-center"
                            data-keterangan="-">
                            <strong>-</strong>
                        </div>
                        <input type="hidden"
                            name="hematologi[${newIndex}][keterangan]"
                            class="keterangan-input"
                            value="-">
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-outline-danger hapus-row-hematologi-btn">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </td>
                </tr>
            `;

            $table.append(newRow);

            // Inisialisasi event listener untuk row baru
            initHematologiRowEvents($table.find('tr:last-child'));

            // Focus ke input search
            setTimeout(() => {
                $table.find('tr:last-child .kode-search-input-hematologi').focus();
            }, 100);

            console.log('Row hematologi baru ditambahkan:', manualId);
        });

        // ==============================
        // INISIALISASI EVENT LISTENER UNTUK ROW HEMATOLOGI
        // ==============================
        function initHematologiRowEvents($row) {
            // Event untuk input hasil pada row baru
            $row.find('.hasil-input-hematologi').on('input', function() {
                const $input = $(this);
                updateHematologiKeterangan($input);

                // Auto-save jika sudah ada ID database
                const $row = $input.closest('tr');
                const hematologiId = getHematologiRowId($row);

                if (hematologiId && !hematologiId.toString().startsWith('manual_hematologi_')) {
                    saveHematologiHasilRealtime($input, hematologiId);
                }
            });

            // Event untuk keyboard navigation
            $row.find('.hasil-input-hematologi').on('keydown', function(e) {
                handleExcelNavigation(e, $(this));
            });
        }

        // ==============================
        // SEARCH DATA PEMERIKSAAN HEMATOLOGI
        // ==============================
        $(document).on('input', '.kode-search-input-hematologi', function() {
            const $input = $(this);
            const searchTerm = $input.val();
            const $results = $input.siblings('.kode-search-results-hematologi');

            if (searchTerm.length < 2) {
                $results.hide().empty();
                return;
            }

            clearTimeout($input.data('searchTimer'));
            $input.data('searchTimer', setTimeout(() => {
                $.ajax({
                    url: '{{ route("hasil-lain.search-kode-pemeriksaan") }}',
                    method: 'GET',
                    data: {
                        search: searchTerm,
                        tipe: 'hematologi'
                    },
                    beforeSend: function() {
                        $results.html('<div class="dropdown-item text-center py-2"><i class="ri-loader-4-line spin"></i> Mencari...</div>').show();
                    },
                    success: function(response) {
                        if (response.success && response.data.length > 0) {
                            let html = '';
                            response.data.forEach(function(item) {
                                html += `<a href="#" class="dropdown-item p-2 kode-search-item-hematologi"
                                    data-id="${item.id_data_pemeriksaan || item.id}"
                                    data-nama="${item.data_pemeriksaan || item.nama}"
                                    data-satuan="${item.satuan || '-'}"
                                    data-rujukan="${item.rujukan || '-'}"
                                    data-ch="${item.ch || '-'}"
                                    data-cl="${item.cl || '-'}"
                                    data-metode="${item.metode || '-'}">
                                    <div><strong>${item.data_pemeriksaan || item.nama}</strong></div>
                                    <small class="text-muted">${item.satuan || ''} | ${item.rujukan || 'No reference'}</small>
                                    </a>`;
                            });
                            $results.html(html).show();
                        } else {
                            $results.html('<div class="dropdown-item text-center py-2 text-muted">Tidak ditemukan</div>').show();
                        }
                    },
                    error: function() {
                        $results.html('<div class="dropdown-item text-center py-2 text-danger">Error loading data</div>').show();
                    }
                });
            }, 300));
        });

        // ==============================
        // KLIK ITEM SEARCH HEMATOLOGI - REVISI UTAMA
        // ==============================
        $(document).on('click', '.kode-search-item-hematologi', async function(e) {
            e.preventDefault();

            const $item = $(this);
            const idDataPemeriksaan = $item.data('id');
            const namaPemeriksaan = $item.data('nama');
            const satuan = $item.data('satuan');
            const rujukan = $item.data('rujukan');
            const ch = $item.data('ch');
            const cl = $item.data('cl');

            const $row = $(this).closest('tr');
            const $searchInput = $row.find('.kode-search-input-hematologi');
            const $hasilInput = $row.find('.hasil-input-hematologi');
            const currentId = getHematologiRowId($row);
            const index = $row.data('index');

            // 1. Update UI dengan data dari pencarian
            $searchInput.val(namaPemeriksaan);
            $row.find('.kode-pemeriksaan-input-hematologi').val(idDataPemeriksaan);

            // Update data attributes pada input hasil
            $hasilInput
                .attr('data-id-data-pemeriksaan', idDataPemeriksaan)
                .attr('data-jenis', namaPemeriksaan)
                .attr('data-satuan', satuan)
                .attr('data-rujukan', rujukan)
                .attr('data-ch', ch)
                .attr('data-cl', cl)
                .data('id-data-pemeriksaan', idDataPemeriksaan)
                .data('satuan', satuan)
                .data('rujukan', rujukan)
                .data('ch', ch)
                .data('cl', cl);

            // Tampilkan data default
            $row.find('.satuan-display').text(satuan || '-');
            $row.find('.rujukan-display').text(rujukan || '-');
            $row.find('.ch-display').text(ch || '-');
            $row.find('.cl-display').text(cl || '-');

            // Hide dropdown
            $row.find('.kode-search-results-hematologi').hide().empty();

            // 2. Tentukan apakah ini row baru atau sudah ada di DB
            const isManualRow = currentId && currentId.toString().startsWith('manual_hematologi_');

            // Data yang akan dikirim
            const dataToSend = {
                _token: csrfToken,
                id_data_pemeriksaan: idDataPemeriksaan,
                jenis_pengujian: namaPemeriksaan,
                satuan_hasil_pengujian: satuan,
                rujukan: rujukan,
                hasil_pengujian: $hasilInput.val(),
                keterangan: $row.find('.keterangan-input').val(),
                no_lab: '{{ $pasien->no_lab }}',
            };

            let url;
            let isNewRow = false;

            if (isManualRow) {
                // Row baru: CREATE
                url = '{{ route("hematologi.save-manual-row") }}';
                dataToSend.manual_id = currentId;
                isNewRow = true;
            } else {
                // Row sudah ada: UPDATE
                url = '{{ route("hematologi.update-row") }}';
                dataToSend.id_pemeriksaan_hematology = currentId;
            }

            try {
                // 3. Kirim ke server untuk save/update
                $row.addClass('table-warning');

                const response = await $.ajax({
                    url: url,
                    method: 'POST',
                    data: dataToSend
                });

                if (response.success) {
                    // Jika ini row baru, update ID-nya dari manual ke database ID
                    let newHematologiId = currentId;
                    if (isNewRow && response.id_pemeriksaan_hematology) {
                        newHematologiId = response.id_pemeriksaan_hematology;

                        // Update semua atribut ID
                        $row.attr('data-hematologi-id', newHematologiId);
                        $row.data('hematologi-id', newHematologiId);
                        $row.attr('data-id', newHematologiId);
                        $row.data('id', newHematologiId);

                        $hasilInput.attr('data-id', newHematologiId);
                        $hasilInput.data('id', newHematologiId);

                        // Update hidden input id (form)
                        $row.find('input[name*="[id]"]').val(newHematologiId);

                        console.log('Hematologi: manual row saved, replaced id ->', newHematologiId);
                    }

                    // 4. AMBIL RUJUKAN BERDASARKAN KONDISI PASAL
                    const rujukanData = await fetchRujukanByKondisiHematologi(
                        idDataPemeriksaan,
                        $row,
                        $hasilInput,
                        newHematologiId
                    );

                    // 5. UPDATE KETERANGAN JIKA SUDAH ADA HASIL
                    if ($hasilInput.val() && $hasilInput.val().trim() !== '') {
                        // Gunakan data rujukan dari kondisi jika ada, atau data default
                        const finalRujukan = rujukanData?.rujukan || rujukan;
                        const finalCh = rujukanData?.ch || ch;
                        const finalCl = rujukanData?.cl || cl;

                        // Update data attributes dengan nilai final
                        $hasilInput
                            .data('rujukan', finalRujukan)
                            .data('ch', finalCh)
                            .data('cl', finalCl)
                            .attr('data-rujukan', finalRujukan)
                            .attr('data-ch', finalCh)
                            .attr('data-cl', finalCl);

                        // Hitung keterangan
                        await updateHematologiKeterangan($hasilInput);
                    }

                    $row.removeClass('table-warning').addClass('table-success');

                    // Toast sukses
                    if (typeof window.showToast === 'function') {
                        window.showToast('success', 'Data hematologi berhasil disimpan: ' + namaPemeriksaan);
                    }

                    setTimeout(() => {
                        $row.removeClass('table-success');
                    }, 2000);

                } else {
                    throw new Error(response.message || 'Gagal menyimpan data');
                }
            } catch (error) {
                console.error('Save error:', error);
                $row.removeClass('table-warning');

                let errorMessage = 'Gagal menyimpan data';
                if (error.responseJSON && error.responseJSON.message) {
                    errorMessage = error.responseJSON.message;
                }

                if (typeof window.showToast === 'function') {
                    window.showToast('danger', errorMessage);
                }
            }

            // Focus ke input hasil
            setTimeout(() => {
                $hasilInput.focus();
            }, 100);
        });

        // ==============================
        // FETCH RUJUKAN BY KONDISI (HEMATOLOGI) - DIOPTIMALKAN
        // ==============================
        async function fetchRujukanByKondisiHematologi(idDataPemeriksaan, $row, $hasilInput, hematologiId = null) {
            if (!idDataPemeriksaan) return null;

            const jenisKelamin = $hasilInput.data('jenis-kelamin') || '{{ $pasien->jenis_kelamin }}';
            const umurPasien = $hasilInput.data('umur') || '{{ $data["umur_format"] ?? "" }}';

            // Gunakan hematologiId yang diberikan atau ambil dari row
            const finalHematologiId = hematologiId || getHematologiRowId($row);
            if (!finalHematologiId) return null;

            const clientKey = idDataPemeriksaan + '_' + finalHematologiId;
            console.log('fetchRujukanByKondisiHematologi client_key:', clientKey);

            try {
                const response = await $.ajax({
                    url: '{{ route("pasien.get-rujukan-by-kondisi-batch") }}',
                    method: 'POST',
                    data: {
                        items: [{
                            id_data_pemeriksaan: idDataPemeriksaan,
                            jenis_kelamin: jenisKelamin,
                            umur_pasien: umurPasien,
                            client_key: clientKey
                        }],
                        no_cache: true
                    }
                });

                console.log('Response rujukan hematologi:', response);

                if (response.success && response.data && response.data[clientKey]) {
                    const rujukanData = response.data[clientKey].data;

                    // Update UI dengan data rujukan by kondisi
                    let rujukanText = rujukanData.rujukan || '-';
                    let satuanText = rujukanData.satuan || $row.find('.satuan-display').text();
                    let chText = rujukanData.ch || '-';
                    let clText = rujukanData.cl || '-';

                    if (rujukanData.is_from_detail) {
                        rujukanText = `${rujukanData.rujukan} <span class="badge bg-info ms-1 badge-kondisi" title="Rujukan berdasarkan kondisi">K</span>`;
                        $row.addClass('table-info').attr('data-from-kondisi', '1');
                    } else {
                        $row.removeClass('table-info').removeAttr('data-from-kondisi');
                    }

                    // Update tampilan di tabel
                    $row.find('.rujukan-display').html(rujukanText);
                    $row.find('.satuan-display').text(satuanText);
                    $row.find('.ch-display').text(chText);
                    $row.find('.cl-display').text(clText);

                    // Update data attributes untuk perhitungan
                    $hasilInput
                        .data('rujukan', rujukanData.rujukan || '')
                        .data('satuan', satuanText)
                        .data('ch', chText)
                        .data('cl', clText)
                        .attr('data-rujukan', rujukanData.rujukan || '')
                        .attr('data-satuan', satuanText)
                        .attr('data-ch', chText)
                        .attr('data-cl', clText);

                    return rujukanData;
                } else {
                    console.log('fetchRujukanByKondisiHematologi: response tidak mengandung key', clientKey);
                    return null;
                }
            } catch (error) {
                console.error('Error mendapatkan rujukan berdasarkan kondisi (Hematologi):', error);
                return null;
            }
        }

        // ==============================
        // FUNGSI UPDATE KETERANGAN HEMATOLOGI - DIOPTIMALKAN
        // ==============================
        async function updateHematologiKeterangan($input) {
            const hasil = $input.val().trim();
            const $row = $input.closest('tr');
            const $keteranganDisplay = $row.find('.keterangan-display');
            const $hiddenInput = $row.find('input[name*="[keterangan]"]');

            console.log('=== HEMATOLOGI - updateKeterangan ===');
            console.log('Hasil:', hasil);

            // Clear jika kosong
            if (!hasil) {
                console.log('Hasil kosong, reset keterangan');
                updateKeteranganDisplay($keteranganDisplay, '');
                $hiddenInput.val('');
                return;
            }

            // Dapatkan data
            const idDataPemeriksaan = $input.data('id-data-pemeriksaan');

            // Gunakan data yang sudah ada di input
            let rujukan = $input.data('rujukan') || '';
            let ch = $input.data('ch') || '';
            let cl = $input.data('cl') || '';

            console.log('Data awal dari input:', { idDataPemeriksaan, rujukan, ch, cl });

            // Jika ada ID data pemeriksaan, ambil data rujukan berdasarkan kondisi
            if (idDataPemeriksaan) {
                try {
                    console.log('Mengambil rujukan berdasarkan kondisi...');
                    const rujukanData = await fetchRujukanByKondisiHematologi(idDataPemeriksaan, $row, $input);

                    if (rujukanData) {
                        console.log('Data rujukan ditemukan:', rujukanData);

                        // Update data lokal dengan data dari kondisi
                        rujukan = rujukanData.rujukan || rujukan;
                        ch = rujukanData.ch || ch;
                        cl = rujukanData.cl || cl;

                        console.log('Data setelah update dari kondisi:', { rujukan, ch, cl });

                        // Update data pada input untuk penggunaan selanjutnya
                        $input
                            .data('rujukan', rujukan)
                            .data('ch', ch)
                            .data('cl', cl)
                            .attr('data-rujukan', rujukan)
                            .attr('data-ch', ch)
                            .attr('data-cl', cl);
                    } else {
                        console.log('Tidak ada data rujukan dari kondisi');
                    }
                } catch (error) {
                    console.error('Error mendapatkan rujukan berdasarkan kondisi:', error);
                }
            }

            // Gunakan data yang sudah diupdate untuk perhitungan
            calculateAndUpdateKeteranganHematologi($input, $keteranganDisplay, $hiddenInput, hasil, rujukan, ch, cl);
        }

        // ==============================
        // FUNGSI PERHITUNGAN KETERANGAN HEMATOLOGI - DIOPTIMALKAN
        // ==============================
        function calculateAndUpdateKeteranganHematologi($input, $keteranganDisplay, $hiddenInput, hasil, rujukan, ch, cl) {
            console.log('Hematologi - Perhitungan dengan:', {
                hasil,
                rujukan: rujukan || '(kosong)',
                ch: ch || '(kosong)',
                cl: cl || '(kosong)'
            });

            // Validasi input
            if (!hasil || hasil.trim() === '') {
                console.log('Hasil kosong');
                updateKeteranganDisplay($keteranganDisplay, '');
                $hiddenInput.val('');
                return;
            }

            const hasilStr = hasil.toString().trim();
            const hasilNum = parseFloat(hasilStr.replace(',', '.'));

            // Jika rujukan tidak tersedia
            if (!rujukan || rujukan === '' || rujukan === '-' || rujukan === 'null') {
                console.log('Rujukan tidak tersedia');

                // Coba gunakan CH/CL jika ada
                if (ch && ch !== '' && ch !== '-' && ch !== 'null' &&
                    cl && cl !== '' && cl !== '-' && cl !== 'null') {

                    const chNum = parseNumberFromString(ch);
                    const clNum = parseNumberFromString(cl);

                    console.log('Menggunakan CH/CL:', { chNum, clNum, hasilNum });

                    if (!isNaN(chNum) && !isNaN(clNum) && !isNaN(hasilNum)) {
                        if (hasilNum > chNum) {
                            console.log(`CH dari data CH/CL: ${hasilNum} > ${chNum}`);
                            updateKeteranganDisplay($keteranganDisplay, 'CH');
                            $hiddenInput.val('CH');
                            return;
                        } else if (hasilNum < clNum) {
                            console.log(`CL dari data CH/CL: ${hasilNum} < ${clNum}`);
                            updateKeteranganDisplay($keteranganDisplay, 'CL');
                            $hiddenInput.val('CL');
                            return;
                        }
                    }
                }

                console.log('Tidak ada data untuk perhitungan');
                updateKeteranganDisplay($keteranganDisplay, '-');
                $hiddenInput.val('-');
                return;
            }

            const rujukanStr = rujukan.toString().trim();
            const chStr = ch ? ch.toString().trim() : '';
            const clStr = cl ? cl.toString().trim() : '';

            // 1. CEK CRITICAL HIGH/LOW (CH/CL) - PRIORITAS TERTINGGI
            console.log('Cek CH/CL:', { chStr, clStr, hasilNum });

            if (chStr && chStr !== '' && chStr !== '-' && chStr !== 'null') {
                const chNum = parseNumberFromString(chStr);
                console.log('Parsed CH:', chNum);

                if (!isNaN(chNum) && !isNaN(hasilNum)) {
                    // Handle berbagai format: >, >=, atau angka biasa
                    if (chStr.includes('>=')) {
                        if (hasilNum >= chNum) {
                            console.log(`✅ HEMATOLOGI CH DETECTED: ${hasilNum} >= ${chNum}`);
                            updateKeteranganDisplay($keteranganDisplay, 'CH');
                            $hiddenInput.val('CH');
                            return;
                        }
                    } else if (chStr.includes('>')) {
                        if (hasilNum > chNum) {
                            console.log(`✅ HEMATOLOGI CH DETECTED: ${hasilNum} > ${chNum}`);
                            updateKeteranganDisplay($keteranganDisplay, 'CH');
                            $hiddenInput.val('CH');
                            return;
                        }
                    } else if (hasilNum > chNum) {
                        console.log(`✅ HEMATOLOGI CH DETECTED: ${hasilNum} > ${chNum}`);
                        updateKeteranganDisplay($keteranganDisplay, 'CH');
                        $hiddenInput.val('CH');
                        return;
                    }
                }
            }

            if (clStr && clStr !== '' && clStr !== '-' && clStr !== 'null') {
                const clNum = parseNumberFromString(clStr);
                console.log('Parsed CL:', clNum);

                if (!isNaN(clNum) && !isNaN(hasilNum)) {
                    // Handle berbagai format: <, <=, atau angka biasa
                    if (clStr.includes('<=')) {
                        if (hasilNum <= clNum) {
                            console.log(`✅ HEMATOLOGI CL DETECTED: ${hasilNum} <= ${clNum}`);
                            updateKeteranganDisplay($keteranganDisplay, 'CL');
                            $hiddenInput.val('CL');
                            return;
                        }
                    } else if (clStr.includes('<')) {
                        if (hasilNum < clNum) {
                            console.log(`✅ HEMATOLOGI CL DETECTED: ${hasilNum} < ${clNum}`);
                            updateKeteranganDisplay($keteranganDisplay, 'CL');
                            $hiddenInput.val('CL');
                            return;
                        }
                    } else if (hasilNum < clNum) {
                        console.log(`✅ HEMATOLOGI CL DETECTED: ${hasilNum} < ${clNum}`);
                        updateKeteranganDisplay($keteranganDisplay, 'CL');
                        $hiddenInput.val('CL');
                        return;
                    }
                }
            }

            // 2. CEK HASIL KUALITATIF (NON-NUMERIC)
            if (isNaN(hasilNum)) {
                console.log('Hasil non-numerik, cek kualitatif');
                const hasilLower = hasilStr.toLowerCase();
                const rujukanLower = rujukanStr.toLowerCase();

                console.log('Kualitatif:', { hasilLower, rujukanLower });

                if (rujukanLower.includes('negative') || rujukanLower.includes('negatif')) {
                    if (hasilLower.includes('negative') || hasilLower.includes('negatif') ||
                        hasilLower.includes('non-reactive') || hasilLower.includes('nonreactive')) {
                        updateKeteranganDisplay($keteranganDisplay, '-');
                        $hiddenInput.val('-');
                    } else {
                        updateKeteranganDisplay($keteranganDisplay, 'H');
                        $hiddenInput.val('H');
                    }
                    return;
                } else if (rujukanLower.includes('positive') || rujukanLower.includes('positif')) {
                    if (hasilLower.includes('positive') || hasilLower.includes('positif') ||
                        hasilLower.includes('reactive') || hasilLower.includes('reaktif')) {
                        updateKeteranganDisplay($keteranganDisplay, '-');
                        $hiddenInput.val('-');
                    } else {
                        updateKeteranganDisplay($keteranganDisplay, 'L');
                        $hiddenInput.val('L');
                    }
                    return;
                }

                // Default untuk non-numerik
                updateKeteranganDisplay($keteranganDisplay, '-');
                $hiddenInput.val('-');
                return;
            }

            // 3. CEK RUJUKAN NUMERIK
            console.log('Cek rujukan numerik:', rujukanStr);

            // Parse rujukan untuk berbagai format
            const parsedRujukan = parseRujukanValue(rujukanStr);

            if (parsedRujukan) {
                console.log('Parsed rujukan:', parsedRujukan);

                if (parsedRujukan.type === 'range') {
                    // Format range: "1 - 90" atau "1-90"
                    const { min, max } = parsedRujukan;
                    if (hasilNum < min) {
                        console.log(`✅ HEMATOLOGI L DETECTED: ${hasilNum} < ${min}`);
                        updateKeteranganDisplay($keteranganDisplay, 'L');
                        $hiddenInput.val('L');
                    } else if (hasilNum > max) {
                        console.log(`✅ HEMATOLOGI H DETECTED: ${hasilNum} > ${max}`);
                        updateKeteranganDisplay($keteranganDisplay, 'H');
                        $hiddenInput.val('H');
                    } else {
                        console.log(`✅ HEMATOLOGI NORMAL: ${hasilNum} dalam range ${min}-${max}`);
                        updateKeteranganDisplay($keteranganDisplay, '-');
                        $hiddenInput.val('-');
                    }
                    return;
                } else if (parsedRujukan.type === 'less_than') {
                    // Format: "< X" atau "<= X"
                    const { value, inclusive } = parsedRujukan;
                    if (inclusive) {
                        if (hasilNum <= value) {
                            console.log(`✅ HEMATOLOGI NORMAL: ${hasilNum} <= ${value}`);
                            updateKeteranganDisplay($keteranganDisplay, '-');
                            $hiddenInput.val('-');
                        } else {
                            console.log(`✅ HEMATOLOGI H DETECTED: ${hasilNum} > ${value}`);
                            updateKeteranganDisplay($keteranganDisplay, 'H');
                            $hiddenInput.val('H');
                        }
                    } else {
                        if (hasilNum < value) {
                            console.log(`✅ HEMATOLOGI NORMAL: ${hasilNum} < ${value}`);
                            updateKeteranganDisplay($keteranganDisplay, '-');
                            $hiddenInput.val('-');
                        } else {
                            console.log(`✅ HEMATOLOGI H DETECTED: ${hasilNum} >= ${value}`);
                            updateKeteranganDisplay($keteranganDisplay, 'H');
                            $hiddenInput.val('H');
                        }
                    }
                    return;
                } else if (parsedRujukan.type === 'greater_than') {
                    // Format: "> X" atau ">= X"
                    const { value, inclusive } = parsedRujukan;
                    if (inclusive) {
                        if (hasilNum >= value) {
                            console.log(`✅ HEMATOLOGI NORMAL: ${hasilNum} >= ${value}`);
                            updateKeteranganDisplay($keteranganDisplay, '-');
                            $hiddenInput.val('-');
                        } else {
                            console.log(`✅ HEMATOLOGI L DETECTED: ${hasilNum} < ${value}`);
                            updateKeteranganDisplay($keteranganDisplay, 'L');
                            $hiddenInput.val('L');
                        }
                    } else {
                        if (hasilNum > value) {
                            console.log(`✅ HEMATOLOGI NORMAL: ${hasilNum} > ${value}`);
                            updateKeteranganDisplay($keteranganDisplay, '-');
                            $hiddenInput.val('-');
                        } else {
                            console.log(`✅ HEMATOLOGI L DETECTED: ${hasilNum} <= ${value}`);
                            updateKeteranganDisplay($keteranganDisplay, 'L');
                            $hiddenInput.val('L');
                        }
                    }
                    return;
                } else if (parsedRujukan.type === 'single_value') {
                    // Format single value: "X"
                    const { value } = parsedRujukan;
                    const tolerance = 0.0001;

                    if (Math.abs(hasilNum - value) < tolerance) {
                        console.log(`✅ HEMATOLOGI NORMAL: ${hasilNum} sama dengan ${value}`);
                        updateKeteranganDisplay($keteranganDisplay, '-');
                        $hiddenInput.val('-');
                    } else if (hasilNum < value) {
                        console.log(`✅ HEMATOLOGI L DETECTED: ${hasilNum} < ${value}`);
                        updateKeteranganDisplay($keteranganDisplay, 'L');
                        $hiddenInput.val('L');
                    } else {
                        console.log(`✅ HEMATOLOGI H DETECTED: ${hasilNum} > ${value}`);
                        updateKeteranganDisplay($keteranganDisplay, 'H');
                        $hiddenInput.val('H');
                    }
                    return;
                }
            }

            // Default - tidak ada pola yang cocok
            console.log('Tidak ada pola rujukan yang cocok');
            updateKeteranganDisplay($keteranganDisplay, '-');
            $hiddenInput.val('-');
        }

        // ==============================
        // HELPER FUNCTIONS
        // ==============================

        // Helper untuk parse angka dari string dengan berbagai format
        function parseNumberFromString(str) {
            if (!str) return NaN;

            // Hapus simbol >, <, = dan whitespace
            const cleaned = str.replace(/[><=]/g, '').trim();
            return parseFloat(cleaned.replace(',', '.'));
        }

        // Helper untuk parse nilai rujukan
        function parseRujukanValue(rujukanStr) {
            if (!rujukanStr) return null;

            const str = rujukanStr.trim();

            // Cek range: "1 - 90" atau "1-90"
            if (str.includes('-') && !str.includes('<') && !str.includes('>') && !str.includes('=')) {
                const cleanStr = str.replace(/\s+/g, '');
                const parts = cleanStr.split('-');

                if (parts.length === 2) {
                    const min = parseFloat(parts[0].replace(',', '.'));
                    const max = parseFloat(parts[1].replace(',', '.'));

                    if (!isNaN(min) && !isNaN(max)) {
                        return { type: 'range', min, max };
                    }
                }
            }

            // Cek <=
            if (str.includes('<=')) {
                const value = parseNumberFromString(str);
                if (!isNaN(value)) {
                    return { type: 'less_than', value, inclusive: true };
                }
            }

            // Cek <
            if (str.includes('<') && !str.includes('<=')) {
                const value = parseNumberFromString(str);
                if (!isNaN(value)) {
                    return { type: 'less_than', value, inclusive: false };
                }
            }

            // Cek >=
            if (str.includes('>=')) {
                const value = parseNumberFromString(str);
                if (!isNaN(value)) {
                    return { type: 'greater_than', value, inclusive: true };
                }
            }

            // Cek >
            if (str.includes('>') && !str.includes('>=')) {
                const value = parseNumberFromString(str);
                if (!isNaN(value)) {
                    return { type: 'greater_than', value, inclusive: false };
                }
            }

            // Cek single value
            const singleValue = parseFloat(str.replace(',', '.'));
            if (!isNaN(singleValue)) {
                return { type: 'single_value', value: singleValue };
            }

            return null;
        }

        // Fungsi display keterangan
        function updateKeteranganDisplay($display, keterangan) {
            if (!$display.length) {
                console.error('Display element tidak ditemukan');
                return;
            }

            // Reset semua kelas
            $display.removeClass(
                'bg-danger bg-opacity-10 ' +
                'bg-primary bg-opacity-10 ' +
                'bg-success bg-opacity-10 ' +
                'text-danger text-primary text-success'
            );

            // Set kelas berdasarkan keterangan
            let bgClass = '';
            let textClass = '';
            let displayText = '';

            switch (keterangan) {
                case 'CH':
                case 'H':
                    bgClass = 'bg-danger bg-opacity-10';
                    textClass = 'text-danger';
                    displayText = keterangan;
                    break;

                case 'CL':
                case 'L':
                    bgClass = 'bg-primary bg-opacity-10';
                    textClass = 'text-primary';
                    displayText = keterangan;
                    break;

                case '-':
                    bgClass = 'bg-success bg-opacity-10';
                    textClass = 'text-success';
                    displayText = '';
                    break;

                default:
                    bgClass = 'bg-light';
                    textClass = 'text-muted';
                    displayText = '-';
                    keterangan = '-';
            }

            // Apply classes
            $display.addClass(bgClass + ' ' + textClass);
            $display.html('<strong>' + displayText + '</strong>');
            $display.data('keterangan', keterangan);
        }

        // ==============================
        // AUTO-SAVE HASIL HEMATOLOGI
        // ==============================
        let hematologiSaveTimers = {};

        function saveHematologiHasilRealtime($input, hematologiId) {
            const $row = $input.closest('tr');
            const hasil = $input.val();
            const keterangan = $row.find('.keterangan-input').val();

            if (!hematologiId) return;

            // Debounce autosave
            if (hematologiSaveTimers[hematologiId]) {
                clearTimeout(hematologiSaveTimers[hematologiId]);
            }

            hematologiSaveTimers[hematologiId] = setTimeout(() => {
                $.ajax({
                    url: '{{ route("hematologi.update-hasil-realtime") }}',
                    method: 'POST',
                    data: {
                        _token: csrfToken,
                        id_pemeriksaan_hematology: hematologiId,
                        hasil_pengujian: hasil,
                        keterangan: keterangan
                    },
                    success: function(res) {
                        if (res.success) {
                            $row.addClass('table-success');
                            setTimeout(() => {
                                $row.removeClass('table-success');
                            }, 400);
                        }
                    },
                    error: function() {
                        $row.addClass('table-danger');
                    }
                });
            }, 600);
        }

        // Event hapus row hematologi
        $(document).on('click', '.hapus-row-hematologi-btn', function() {
            if (!confirm('Hapus row ini?')) return;

            const $row = $(this).closest('tr');
            const hematologiIdRaw = getHematologiRowId($row);
            if (!hematologiIdRaw) {
                window.showToast?.('danger', 'ID hematologi tidak ditemukan');
                return;
            }
            const hematologiId = String(hematologiIdRaw);

            // ROW MANUAL (BELUM MASUK DB)
            if (hematologiId.startsWith('manual_hematologi_')) {
                $row.remove();
                if (typeof window.showToast === 'function') {
                    window.showToast('warning', 'Row dihapus');
                }
                return;
            }

            // ROW SUDAH ADA DI DATABASE
            $.ajax({
                url: '{{ route("hematologi.delete-manual-row", ["id" => "__ID__"]) }}'.replace('__ID__', hematologiId),
                method: 'DELETE',
                data: {
                    _token: csrfToken
                },
                success: function(response) {
                    if (response.status) {
                        $row.remove();
                        if (typeof window.showToast === 'function') {
                            window.showToast('warning', 'Data hematologi berhasil dihapus');
                        }
                    } else {
                        if (typeof window.showToast === 'function') {
                            window.showToast('danger', response.message || 'Gagal menghapus data');
                        }
                    }
                },
                error: function() {
                    if (typeof window.showToast === 'function') {
                        window.showToast('danger', 'Terjadi kesalahan saat menghapus');
                    }
                }
            });
        });

        // Sembunyikan dropdown saat klik di luar
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.kode-search-input-hematologi, .kode-search-results-hematologi').length) {
                $('.kode-search-results-hematologi').hide().empty();
            }
        });

        // ==============================
        // EXCEL NAVIGATION
        // ==============================
        function handleExcelNavigation(e, $current) {
            const $row = $current.closest('tr');
            const $cell = $current.closest('td');
            const cellIndex = $cell.index();
            const $rows = $row.closest('tbody').find('tr');
            const rowIndex = $rows.index($row);

            switch (e.key) {
                case 'Enter':
                case 'ArrowDown':
                    e.preventDefault();
                    const $nextRow = $rows.eq(rowIndex + 1);
                    if ($nextRow.length) {
                        const $nextInput = $nextRow.find('td').eq(cellIndex).find('.hasil-input-hematologi');
                        if ($nextInput.length) {
                            $nextInput.focus().select();
                        }
                    }
                    break;

                case 'ArrowUp':
                    e.preventDefault();
                    const $upRow = $rows.eq(rowIndex - 1);
                    if ($upRow.length) {
                        const $upInput = $upRow.find('td').eq(cellIndex).find('.hasil-input-hematologi');
                        if ($upInput.length) {
                            $upInput.focus().select();
                        }
                    }
                    break;
            }
        }

        function initHematologiExcelNavigation() {
            // Select all inputs on focus
            $('.hasil-input-hematologi').on('focus', function() {
                $(this).select();
                $(this).closest('td').addClass('table-warning');
            });

            // Remove highlight on blur
            $('.hasil-input-hematologi').on('blur', function() {
                $(this).closest('td').removeClass('table-warning');
            });

            // Excel-like keyboard navigation
            $('.hasil-input-hematologi').on('keydown', function(e) {
                handleExcelNavigation(e, $(this));
            });
        }

        // Initialize Excel navigation untuk semua row yang ada
        $(document).ready(function() {
            initHematologiExcelNavigation();

            // Inisialisasi event untuk row yang sudah ada
            $('.hematologi-row').each(function() {
                initHematologiRowEvents($(this));
            });
        });
    });
</script>
<!-- END TAMBAH ROW & SEARCH HEMATOLOGY -->

<!-- Terapkan pada semua tabel dengan class .table-row-skip -->
<script>
    $(document).on(
        'keydown',
        '.table-row-skip input, .table-row-skip select, .table-row-skip [contenteditable="true"], .table-row-skip textarea',
        function (e) {
            // ENTER atau PANAH BAWAH
            if (e.key === 'Enter' || e.key === 'ArrowDown') {
                e.preventDefault();

                const $current = $(this);
                const $row = $current.closest('tr');
                const $cell = $current.closest('td, th');
                const cellIndex = $cell.index();
                const $rows = $row.closest('tbody').find('tr');
                const rowIndex = $rows.index($row);

                const $nextRow = $rows.eq(rowIndex + 1);

                if ($nextRow.length) {
                    // Cari input yang sesuai di kolom yang sama
                    let $targetInput = null;

                    // Prioritaskan input hasil terlebih dahulu
                    $targetInput = $nextRow.find('td').eq(cellIndex).find('.hasil-input, .hasil-input-hasil-lain, .excel-input').first();

                    // Jika tidak ada input hasil, cari input lain
                    if (!$targetInput.length) {
                        $targetInput = $nextRow.find('td').eq(cellIndex).find('input, select, textarea, [contenteditable="true"]').first();
                    }

                    if ($targetInput.length) {
                        $targetInput.focus();
                        if ($targetInput.is('input[type="text"], textarea')) {
                            $targetInput.select();
                        }
                        return;
                    }
                }

                // Jika di baris terakhir, lanjut ke baris pertama kolom berikutnya
                if (e.key === 'Enter' && !$nextRow.length) {
                    const $nextCell = $row.find('td').eq(cellIndex + 1);
                    if ($nextCell.length) {
                        const $nextInput = $nextCell.find('input, select, textarea, [contenteditable="true"]').first();
                        if ($nextInput.length) {
                            $nextInput.focus();
                            if ($nextInput.is('input[type="text"], textarea')) {
                                $nextInput.select();
                            }
                        }
                    }
                }
            }

            // PANAH ATAS
            if (e.key === 'ArrowUp') {
                e.preventDefault();

                const $current = $(this);
                const $row = $current.closest('tr');
                const $cell = $current.closest('td, th');
                const cellIndex = $cell.index();
                const $rows = $row.closest('tbody').find('tr');
                const rowIndex = $rows.index($row);

                const $prevRow = $rows.eq(rowIndex - 1);

                if ($prevRow.length) {
                    let $targetInput = $prevRow.find('td').eq(cellIndex).find('input, select, textarea, [contenteditable="true"]').first();

                    if ($targetInput.length) {
                        $targetInput.focus();
                        if ($targetInput.is('input[type="text"], textarea')) {
                            $targetInput.select();
                        }
                    }
                }
            }

            // PANAH KANAN (opsional, untuk navigasi horizontal)
            if (e.key === 'ArrowRight') {
                e.preventDefault();

                const $current = $(this);
                const $row = $current.closest('tr');
                const $cell = $current.closest('td, th');
                const cellIndex = $cell.index();

                const $nextCell = $row.find('td').eq(cellIndex + 1);
                if ($nextCell.length) {
                    const $nextInput = $nextCell.find('input, select, textarea, [contenteditable="true"]').first();
                    if ($nextInput.length) {
                        $nextInput.focus();
                        if ($nextInput.is('input[type="text"], textarea')) {
                            $nextInput.select();
                        }
                    }
                }
            }

            // PANAH KIRI (opsional, untuk navigasi horizontal)
            if (e.key === 'ArrowLeft') {
                e.preventDefault();

                const $current = $(this);
                const $row = $current.closest('tr');
                const $cell = $current.closest('td, th');
                const cellIndex = $cell.index();

                const $prevCell = $row.find('td').eq(cellIndex - 1);
                if ($prevCell.length) {
                    const $prevInput = $prevCell.find('input, select, textarea, [contenteditable="true"]').first();
                    if ($prevInput.length) {
                        $prevInput.focus();
                        if ($prevInput.is('input[type="text"], textarea')) {
                            $prevInput.select();
                        }
                    }
                }
            }

            // TAB - biarkan default behavior
            if (e.key === 'Tab') {
                // Biarkan tab berfungsi normal
                return;
            }
        }
    );
</script>
<!-- end terapkan pada semua tabel dengan class .table-row-skip -->

<!-- SISTEM OTOMATIS HASIL LAIN DARI UJI PEMERIKSAAN -->
<script>
    (function($){
        $(document).ready(function() {
            // =====================================================
            // HASIL LAIN - FULLINT Final Advanced (production-ready)
            // - Unified single-file script
            // - Namespaced events using NS_FULLINT
            // - Keeps automatic "generate by kode uji" feature
            // - Adds: Tambah Tabel, Tambah Row Manual, Pilih dari Modal, Hapus Row, Hapus Tabel
            // - Expects modal elements and some buttons to exist in the DOM (see note below)
            // =====================================================

            if (window.__HASIL_LAIN_FULLINT_INIT) {
                console.warn('⛔ HASIL LAIN FULLINT: already initialized, skipping duplicate init');
                return;
            }
            window.__HASIL_LAIN_FULLINT_INIT = true;

            const DEBUG_FULLINT = !!window.__HASIL_LAIN_FULLINT_DEBUG;
            const NS_FULLINT = '.HASILLAIN_FULLINT';

            const csrfTokenFullint = $('#csrf_token').val() || $('meta[name="csrf-token"]').attr('content') || '';
            const noLabFullint = window.pasienNoLab || ('{{ $pasien->no_lab }}' || '');

            if (DEBUG_FULLINT) console.log('=== HASIL LAIN FULL-INTEGRATION (INIT) ===');

            // -----------------------
            // Helpers (unique names)
            // -----------------------
            function fullint_getRowUniqueId($row) {
                const idDataRow = $row.data('id') || $row.find('.fullint-rowid-input').val();
                if (idDataRow && idDataRow !== '') return String(idDataRow);
                const idx = $row.data('index');
                return 'manual_fullint_' + (idx !== undefined ? idx : Date.now());
            }

            function fullint_updateFormNames($row) {
                const rowIndex = $row.data('index');
                const jenisPemeriksaan = $row.data('jenis-pemeriksaan');

                if (!jenisPemeriksaan || rowIndex === undefined) {
                    if (DEBUG_FULLINT) console.warn('fullint_updateFormNames: jenis/index tidak ditemukan', { jenisPemeriksaan, rowIndex });
                    return;
                }

                $row.find('.fullint-id-input').attr('name',
                    'hasil_lain[' + jenisPemeriksaan + '][' + rowIndex + '][id_data_pemeriksaan]');
                $row.find('.fullint-jenis-input').attr('name',
                    'hasil_lain[' + jenisPemeriksaan + '][' + rowIndex + '][jenis_pengujian]');
                $row.find('.fullint-rowid-input').attr('name',
                    'hasil_lain[' + jenisPemeriksaan + '][' + rowIndex + '][id]');
                $row.find('.fullint-hasil-input').attr('name',
                    'hasil_lain[' + jenisPemeriksaan + '][' + rowIndex + '][hasil_pengujian]');
                $row.find('.fullint-ket-input').attr('name',
                    'hasil_lain[' + jenisPemeriksaan + '][' + rowIndex + '][keterangan]');

                const chValue = $row.find('.fullint-ch-display').text();
                const clValue = $row.find('.fullint-cl-display').text();

                if (!$row.find('.fullint-ch-input-hidden').length) {
                    $row.find('.fullint-ch-cell').append(
                        '<input type="hidden" class="fullint-ch-input-hidden" name="hasil_lain[' +
                        jenisPemeriksaan + '][' + rowIndex + '][ch]" value="' + chValue + '">'
                    );
                } else {
                    $row.find('.fullint-ch-input-hidden').val(chValue);
                }

                if (!$row.find('.fullint-cl-input-hidden').length) {
                    $row.find('.fullint-cl-cell').append(
                        '<input type="hidden" class="fullint-cl-input-hidden" name="hasil_lain[' +
                        jenisPemeriksaan + '][' + rowIndex + '][cl]" value="' + clValue + '">'
                    );
                } else {
                    $row.find('.fullint-cl-input-hidden').val(clValue);
                }
            }

            // -----------------------
            // Rujukan fetch & kalkulasi (unique names)
            // -----------------------
            function fullint_updateRujukanBerdasarkanKondisi($row, idDataPemeriksaan) {
                if (!idDataPemeriksaan) return;

                const jenisKelamin = '{{ $pasien->jenis_kelamin }}';
                const umurPasien = '{{ $data["umur_format"] ?? "" }}';

                const rowId = fullint_getRowUniqueId($row);
                const clientKey = idDataPemeriksaan + '_' + rowId;

                if (DEBUG_FULLINT) console.log('fullint_updateRujukan =>', { idDataPemeriksaan, rowId, clientKey });

                $.ajax({
                    url: '{{ route("pasien.get-rujukan-by-kondisi-batch") }}',
                    method: 'POST',
                    data: {
                        items: [{
                            id_data_pemeriksaan: idDataPemeriksaan,
                            jenis_kelamin: jenisKelamin,
                            umur_pasien: umurPasien,
                            client_key: clientKey
                        }],
                        no_cache: true
                    },
                    success: function(res) {
                        if (res && res.success && res.data && res.data[clientKey]) {
                            const rujukanData = res.data[clientKey].data;

                            const rujukan = rujukanData.rujukan || $row.find('.fullint-rujukan-display').text().trim() || '-';
                            const satuan = rujukanData.satuan || $row.find('.fullint-satuan-display').text().trim() || '-';
                            const ch = rujukanData.ch || $row.find('.fullint-ch-display').text().trim() || '-';
                            const cl = rujukanData.cl || $row.find('.fullint-cl-display').text().trim() || '-';

                            let rujukanDisplay = rujukan;
                            if (rujukanData.is_from_detail) {
                                rujukanDisplay += ' <span class="badge bg-info ms-1" title="CH/CL khusus kondisi">K</span>';
                                $row.addClass('table-info');
                            } else {
                                $row.removeClass('table-info');
                            }

                            $row.find('.fullint-rujukan-display').html(rujukanDisplay);
                            $row.find('.fullint-satuan-display').text(satuan);

                            let chHtml = ch || '-';
                            if (rujukanData.is_from_detail && ch && ch !== '-' && ch !== '') chHtml += '<br><small class="text-info">detail</small>';
                            $row.find('.fullint-ch-display').html(chHtml);

                            let clHtml = cl || '-';
                            if (rujukanData.is_from_detail && cl && cl !== '-' && cl !== '') clHtml += '<br><small class="text-info">detail</small>';
                            $row.find('.fullint-cl-display').html(clHtml);

                            const $hasilInput = $row.find('.fullint-hasil-input');
                            $hasilInput
                                .attr('data-rujukan', rujukan)
                                .attr('data-ch', ch)
                                .attr('data-cl', cl);

                            if ($hasilInput.val() && $hasilInput.val().trim() !== '') {
                                fullint_updateKeteranganHasilLain($hasilInput);
                            }

                            if (DEBUG_FULLINT) console.log('fullint rujukan updated', clientKey, rujukanData);
                        } else {
                            if (DEBUG_FULLINT) console.log('fullint no rujukan for', clientKey);
                        }
                    },
                    error: function(err) {
                        console.error('fullint_updateRujukan error:', err);
                    }
                });
            }

            function fullint_fetchRujukanByKondisi(idDataPemeriksaan, $row, $input) {
                if (!idDataPemeriksaan) return Promise.resolve(null);

                const jenisKelamin = '{{ $pasien->jenis_kelamin }}';
                const umurPasien = '{{ $data["umur_format"] ?? "" }}';
                const rowId = fullint_getRowUniqueId($row);
                const clientKey = idDataPemeriksaan + '_' + rowId;

                if (DEBUG_FULLINT) console.log('fullint_fetchRujukan =>', { idDataPemeriksaan, rowId, clientKey });

                return $.ajax({
                    url: '{{ route("pasien.get-rujukan-by-kondisi-batch") }}',
                    method: 'POST',
                    data: {
                        items: [{
                            id_data_pemeriksaan: idDataPemeriksaan,
                            jenis_kelamin: jenisKelamin,
                            umur_pasien: umurPasien,
                            client_key: clientKey
                        }],
                        no_cache: true
                    }
                }).then(function(res) {
                    if (res && res.success && res.data && res.data[clientKey]) {
                        return res.data[clientKey].data;
                    }
                    return null;
                }).catch(function(err) {
                    console.error('fullint_fetchRujukan error:', err);
                    return null;
                });
            }

            // Full implementation of updateKeterangan (copied logic, unique name)
            async function fullint_updateKeteranganHasilLain($input) {
                const hasil = ($input.val() || '').toString().trim();
                const $row = $input.closest('tr');
                const $keteranganDisplay = $row.find('.fullint-ket-display');
                const $hiddenInput = $row.find('.fullint-ket-input');

                if (DEBUG_FULLINT) console.log('fullint_updateKeterangan called', hasil);

                if (!hasil) {
                    fullint_updateKeteranganDisplay($keteranganDisplay, '');
                    $hiddenInput.val('');
                    return;
                }

                const idDataPemeriksaan = $input.data('id-data-pemeriksaan');

                let rujukan = $input.data('rujukan') || '';
                let ch = $input.data('ch') || '';
                let cl = $input.data('cl') || '';

                if (DEBUG_FULLINT) console.log('fullint initial data', { idDataPemeriksaan, rujukan, ch, cl });

                if (idDataPemeriksaan) {
                    try {
                        const rujukanData = await fullint_fetchRujukanByKondisi(idDataPemeriksaan, $row, $input);
                        if (rujukanData) {
                            rujukan = rujukanData.rujukan || rujukan;
                            ch = rujukanData.ch || ch;
                            cl = rujukanData.cl || cl;
                            $input.data('rujukan', rujukan).data('ch', ch).data('cl', cl);
                            if (DEBUG_FULLINT) console.log('fullint updated from kondisi', { rujukan, ch, cl });
                        }
                    } catch (e) {
                        console.error('fullint error fetch rujukan', e);
                    }
                }

                const hasilStr = hasil.toString().trim();
                const hasilNum = parseFloat(hasilStr.replace(',', '.'));

                if (!rujukan || rujukan === '' || rujukan === '-' || rujukan === 'null') {
                    if (ch && ch !== '' && ch !== '-' && ch !== 'null' && cl && cl !== '' && cl !== '-' && cl !== 'null') {
                        const chNum = parseFloat(ch.toString().replace(',', '.'));
                        const clNum = parseFloat(cl.toString().replace(',', '.'));
                        if (!isNaN(chNum) && !isNaN(clNum) && !isNaN(hasilNum)) {
                            if (hasilNum > chNum) { fullint_updateKeteranganDisplay($keteranganDisplay, 'CH'); $hiddenInput.val('CH'); return; }
                            else if (hasilNum < clNum) { fullint_updateKeteranganDisplay($keteranganDisplay, 'CL'); $hiddenInput.val('CL'); return; }
                        }
                    }
                    fullint_updateKeteranganDisplay($keteranganDisplay, '-'); $hiddenInput.val('-'); return;
                }

                const rujukanStr = rujukan.toString().trim();
                const chStr = ch ? ch.toString().trim() : '';
                const clStr = cl ? cl.toString().trim() : '';

                // CH checks
                if (chStr && chStr !== '' && chStr !== '-' && chStr !== 'null') {
                    let chNum;
                    if (chStr.includes('>=')) {
                        chNum = parseFloat(chStr.replace('>=', '').replace(',', '.').trim());
                        if (!isNaN(chNum) && !isNaN(hasilNum) && hasilNum >= chNum) { fullint_updateKeteranganDisplay($keteranganDisplay, 'CH'); $hiddenInput.val('CH'); return; }
                    } else if (chStr.includes('>')) {
                        chNum = parseFloat(chStr.replace('>', '').replace(',', '.').trim());
                        if (!isNaN(chNum) && !isNaN(hasilNum) && hasilNum > chNum) { fullint_updateKeteranganDisplay($keteranganDisplay, 'CH'); $hiddenInput.val('CH'); return; }
                    } else {
                        chNum = parseFloat(chStr.replace(',', '.'));
                        if (!isNaN(chNum) && !isNaN(hasilNum) && hasilNum > chNum) { fullint_updateKeteranganDisplay($keteranganDisplay, 'CH'); $hiddenInput.val('CH'); return; }
                    }
                }

                // CL checks
                if (clStr && clStr !== '' && clStr !== '-' && clStr !== 'null') {
                    let clNum;
                    if (clStr.includes('<=')) {
                        clNum = parseFloat(clStr.replace('<=', '').replace(',', '.').trim());
                        if (!isNaN(clNum) && !isNaN(hasilNum) && hasilNum <= clNum) { fullint_updateKeteranganDisplay($keteranganDisplay, 'CL'); $hiddenInput.val('CL'); return; }
                    } else if (clStr.includes('<')) {
                        clNum = parseFloat(clStr.replace('<', '').replace(',', '.').trim());
                        if (!isNaN(clNum) && !isNaN(hasilNum) && hasilNum < clNum) { fullint_updateKeteranganDisplay($keteranganDisplay, 'CL'); $hiddenInput.val('CL'); return; }
                    } else {
                        clNum = parseFloat(clStr.replace(',', '.'));
                        if (!isNaN(clNum) && !isNaN(hasilNum) && hasilNum < clNum) { fullint_updateKeteranganDisplay($keteranganDisplay, 'CL'); $hiddenInput.val('CL'); return; }
                    }
                }

                // Non-numeric qualitative
                if (isNaN(hasilNum)) {
                    const hasilLower = hasilStr.toLowerCase();
                    const rujukanLower = rujukanStr.toLowerCase();

                    if (rujukanLower.includes('negative') || rujukanLower.includes('negatif')) {
                        if (hasilLower.includes('negative') || hasilLower.includes('negatif') || hasilLower.includes('non-reactive') || hasilLower.includes('nonreactive')) {
                            fullint_updateKeteranganDisplay($keteranganDisplay, '-'); $hiddenInput.val('-');
                        } else {
                            fullint_updateKeteranganDisplay($keteranganDisplay, 'H'); $hiddenInput.val('H');
                        }
                        return;
                    } else if (rujukanLower.includes('positive') || rujukanLower.includes('positif')) {
                        if (hasilLower.includes('positive') || hasilLower.includes('positif') || hasilLower.includes('reactive') || hasilLower.includes('reaktif')) {
                            fullint_updateKeteranganDisplay($keteranganDisplay, '-'); $hiddenInput.val('-');
                        } else {
                            fullint_updateKeteranganDisplay($keteranganDisplay, 'L'); $hiddenInput.val('L');
                        }
                        return;
                    }
                    fullint_updateKeteranganDisplay($keteranganDisplay, '-'); $hiddenInput.val('-'); return;
                }

                // Numeric rujukan formats
                if (rujukanStr.includes('-') && !rujukanStr.includes('<') && !rujukanStr.includes('>') && !rujukanStr.includes('=')) {
                    const cleanStr = rujukanStr.replace(/\s+/g, '');
                    const parts = cleanStr.split('-');
                    if (parts.length === 2) {
                        const min = parseFloat(parts[0].replace(',', '.'));
                        const max = parseFloat(parts[1].replace(',', '.'));
                        if (!isNaN(min) && !isNaN(max)) {
                            if (hasilNum < min) { fullint_updateKeteranganDisplay($keteranganDisplay, 'L'); $hiddenInput.val('L'); }
                            else if (hasilNum > max) { fullint_updateKeteranganDisplay($keteranganDisplay, 'H'); $hiddenInput.val('H'); }
                            else { fullint_updateKeteranganDisplay($keteranganDisplay, '-'); $hiddenInput.val('-'); }
                            return;
                        }
                    }
                }

                if (rujukanStr.includes('>=')) {
                    const batas = parseFloat(rujukanStr.replace('>=', '').replace(',', '.').trim());
                    if (!isNaN(batas)) {
                        if (hasilNum < batas) { fullint_updateKeteranganDisplay($keteranganDisplay, 'L'); $hiddenInput.val('L'); }
                        else { fullint_updateKeteranganDisplay($keteranganDisplay, '-'); $hiddenInput.val('-'); }
                        return;
                    }
                }

                if (rujukanStr.includes('<=')) {
                    const batas = parseFloat(rujukanStr.replace('<=', '').replace(',', '.').trim());
                    if (!isNaN(batas)) {
                        if (hasilNum > batas) { fullint_updateKeteranganDisplay($keteranganDisplay, 'H'); $hiddenInput.val('H'); }
                        else { fullint_updateKeteranganDisplay($keteranganDisplay, '-'); $hiddenInput.val('-'); }
                        return;
                    }
                }

                if (rujukanStr.includes('>') && !rujukanStr.includes('>=')) {
                    const batas = parseFloat(rujukanStr.replace('>', '').replace(',', '.').trim());
                    if (!isNaN(batas)) {
                        if (hasilNum <= batas) { fullint_updateKeteranganDisplay($keteranganDisplay, 'L'); $hiddenInput.val('L'); }
                        else { fullint_updateKeteranganDisplay($keteranganDisplay, '-'); $hiddenInput.val('-'); }
                        return;
                    }
                }

                if (rujukanStr.includes('<') && !rujukanStr.includes('<=')) {
                    const batas = parseFloat(rujukanStr.replace('<', '').replace(',', '.').trim());
                    if (!isNaN(batas)) {
                        if (hasilNum >= batas) { fullint_updateKeteranganDisplay($keteranganDisplay, 'H'); $hiddenInput.val('H'); }
                        else { fullint_updateKeteranganDisplay($keteranganDisplay, '-'); $hiddenInput.val('-'); }
                        return;
                    }
                }

                const singleValue = parseFloat(rujukanStr.replace(',', '.'));
                if (!isNaN(singleValue)) {
                    const tolerance = 0.0001;
                    if (Math.abs(hasilNum - singleValue) < tolerance) { fullint_updateKeteranganDisplay($keteranganDisplay, '-'); $hiddenInput.val('-'); }
                    else if (hasilNum < singleValue) { fullint_updateKeteranganDisplay($keteranganDisplay, 'L'); $hiddenInput.val('L'); }
                    else { fullint_updateKeteranganDisplay($keteranganDisplay, 'H'); $hiddenInput.val('H'); }
                    return;
                }

                // Default
                fullint_updateKeteranganDisplay($keteranganDisplay, '-');
                $hiddenInput.val('-');
            }

            function fullint_updateKeteranganDisplay($display, keterangan) {
                if (!$display.length) {
                    if (DEBUG_FULLINT) console.error('fullint_updateKeteranganDisplay: display not found');
                    return;
                }

                $display.removeClass(
                    'bg-danger bg-opacity-10 ' +
                    'bg-primary bg-opacity-10 ' +
                    'bg-success bg-opacity-10 ' +
                    'text-danger text-primary text-success'
                );

                let bgClass = '';
                let textClass = '';
                let displayText = '';

                switch (keterangan) {
                    case 'CH':
                    case 'H':
                        bgClass = 'bg-danger bg-opacity-10';
                        textClass = 'text-danger';
                        displayText = keterangan;
                        break;
                    case 'CL':
                    case 'L':
                        bgClass = 'bg-primary bg-opacity-10';
                        textClass = 'text-primary';
                        displayText = keterangan;
                        break;
                    case '-':
                        bgClass = 'bg-success bg-opacity-10';
                        textClass = 'text-success';
                        displayText = '';
                        break;
                    default:
                        bgClass = 'bg-light';
                        textClass = 'text-muted';
                        displayText = '-';
                        keterangan = '-';
                }

                $display.addClass(bgClass + ' ' + textClass);
                $display.html('<strong>' + displayText + '</strong>');
                $display.data('keterangan', keterangan);
            }

            // -----------------------
            // Save row to DB with unique locks (fullint)
            // -----------------------
            function fullint_saveDataPemeriksaanToDatabase($row, idDataPemeriksaan, jenisPengujian, satuan, rujukan) {
                const noLabLocal = window.pasienNoLab || noLabFullint;
                const jenisPemeriksaan = $row.data('jenis-pemeriksaan');

                if (!noLabLocal || !idDataPemeriksaan) {
                    if (DEBUG_FULLINT) console.error('fullint_save: missing noLab or idDataPemeriksaan');
                    return;
                }

                if ($row.data('savingRow_fullint')) {
                    if (DEBUG_FULLINT) console.warn('fullint_save: already saving this row', idDataPemeriksaan);
                    return;
                }
                $row.data('savingRow_fullint', true);

                if (DEBUG_FULLINT) console.log('fullint SAVE →', { noLabLocal, jenisPemeriksaan, idDataPemeriksaan });

                $.ajax({
                    url: '/hasil-lain/store-manual',
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfTokenFullint },
                    data: {
                        no_lab: noLabLocal,
                        jenis_pemeriksaan: jenisPemeriksaan,
                        id_data_pemeriksaan: idDataPemeriksaan,
                        jenis_pengujian: jenisPengujian,
                        satuan: satuan,
                        rujukan: rujukan
                    },
                    beforeSend: function() {
                        $row.addClass('table-warning');
                    },
                    success: function(response) {
                        if (DEBUG_FULLINT) console.log('fullint save response', response);
                        if (!response || !response.success || !response.data || !response.data.id_hasil_lain) {
                            $row.removeClass('table-warning');
                            if (typeof window.showToast === 'function') window.showToast('danger', (response && response.message) || 'Gagal menyimpan data');
                            return;
                        }

                        const dbId = response.data.id_hasil_lain;
                        $row.attr('data-id', dbId).data('id', dbId);
                        $row.find('.fullint-rowid-input').val(dbId);
                        $row.find('.fullint-hasil-input').attr('data-id', dbId).data('id', dbId);

                        fullint_updateFormNames($row);

                        fullint_updateRujukanBerdasarkanKondisi($row, idDataPemeriksaan);

                        setTimeout(function() {
                            $row.removeClass('table-warning').addClass('table-success');
                            setTimeout(() => $row.removeClass('table-success'), 2000);
                        }, 100);

                        if (typeof window.showToast === 'function') window.showToast('success', 'Data berhasil disimpan');
                    },
                    error: function(xhr) {
                        console.error('fullint save error:', xhr && xhr.responseText ? xhr.responseText : xhr);
                        $row.removeClass('table-warning');
                        if (typeof window.showToast === 'function') window.showToast('danger', 'Gagal menyimpan data');
                    },
                    complete: function() {
                        $row.data('savingRow_fullint', false);
                    }
                });
            }

            // -----------------------
            // Generate per-uji (namespaced)
            // -----------------------
            $(document).off('click' + NS_FULLINT, '.fullint-generate-btn')
                    .on('click' + NS_FULLINT, '.fullint-generate-btn', function() {
                const $btn = $(this);
                const kodeUji = $btn.data('kode');
                const namaUji = $btn.data('nama');

                if (DEBUG_FULLINT) console.log('fullint generate', { kodeUji, namaUji });

                $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Loading...');

                $.ajax({
                    url: '/hasil-lain/get-by-kode-uji/' + kodeUji,
                    method: 'GET',
                    success: function(response) {
                        try {
                            if (!response || !response.success || !response.data || response.data.length === 0) throw new Error('Tidak ada data pemeriksaan untuk kode ini');

                            let totalAdded = 0;
                            const grouped = {};
                            response.data.forEach(item => {
                                const jenis = item.jenis_pemeriksaan || 'Lainnya';
                                if (!grouped[jenis]) grouped[jenis] = [];
                                grouped[jenis].push(item);
                            });

                            Object.keys(grouped).forEach(jenis => {
                                const res = fullint_processJenisPemeriksaan(jenis, grouped[jenis]);
                                totalAdded += res.added;
                            });

                            if (totalAdded > 0) {
                                $btn.removeClass('btn-outline-primary').addClass('btn-success').html('<i class="ri-check-line me-1"></i>✓ Generated').prop('disabled', true);
                                if (window.showToast) window.showToast('success', 'Berhasil menambahkan ' + totalAdded + ' data pemeriksaan!');
                            } else {
                                $btn.prop('disabled', false).html('<i class="ri-list-check me-1"></i>Generate');
                                if (window.showToast) window.showToast('info', 'Semua data sudah ada di tabel');
                            }
                        } catch (e) {
                            console.error('fullint generate error:', e);
                            $btn.prop('disabled', false).html('<i class="ri-refresh-line me-1"></i>Coba Lagi');
                            if (window.showToast) window.showToast('danger', e.message || 'Gagal memproses data');
                        }
                    },
                    error: function(err) {
                        console.error('fullint generate ajax error:', err);
                        $btn.prop('disabled', false).html('<i class="ri-refresh-line me-1"></i>Coba Lagi');
                        if (window.showToast) window.showToast('danger', 'Gagal mengambil data pemeriksaan');
                    }
                });
            });

            const NS = '.HASILLAIN_FULLINT';

    // Override behavior tombol generate agar tidak disable
    $(document).off('click' + NS, '.fullint-generate-btn')
        .on('click' + NS, '.fullint-generate-btn', function () {
            const $btn = $(this);
            const kodeUji = $btn.data('kode');
            const namaUji = $btn.data('nama');

            if (!$btn.data('original-html')) {
                $btn.data('original-html', $btn.html());
            }

            // UI loading
            $btn.html('<span class="spinner-border spinner-border-sm me-1"></span>Loading...');

            $.ajax({
                url: '/hasil-lain/get-by-kode-uji/' + kodeUji,
                method: 'GET',
                success: function (response) {
                    try {
                        if (!response || !response.success || !response.data || response.data.length === 0) {
                            throw new Error('Tidak ada data pemeriksaan untuk kode ini');
                        }

                        let totalAdded = 0;
                        const grouped = {};

                        response.data.forEach(item => {
                            const jenis = item.jenis_pemeriksaan || 'Lainnya';
                            if (!grouped[jenis]) grouped[jenis] = [];
                            grouped[jenis].push(item);
                        });

                        Object.keys(grouped).forEach(jenis => {
                            const res = fullint_processJenisPemeriksaan(jenis, grouped[jenis]);
                            totalAdded += res.added;
                        });

                        // UI sukses (TANPA disable)
                        if (totalAdded > 0) {
                            $btn
                                .removeClass('btn-outline-primary')
                                .addClass('btn-success')
                                .html('<i class="ri-check-line me-1"></i>Generated (' + totalAdded + ')');

                            window.showToast?.('success', 'Berhasil menambahkan ' + totalAdded + ' data pemeriksaan');
                        } else {
                            $btn
                                .removeClass('btn-success')
                                .addClass('btn-outline-primary')
                                .html('<i class="ri-list-check me-1"></i>Generate');

                            window.showToast?.('info', 'Semua data sudah ada di tabel');
                        }
                    } catch (e) {
                        console.error(e);
                        $btn.html('<i class="ri-refresh-line me-1"></i>Coba Lagi');
                        window.showToast?.('danger', e.message || 'Gagal memproses data');
                    }
                },
                error: function () {
                    $btn.html('<i class="ri-refresh-line me-1"></i>Coba Lagi');
                    window.showToast?.('danger', 'Gagal mengambil data pemeriksaan');
                }
            });
        });

            // -----------------------
            // processJenis & addSingleRow (unique)
            // -----------------------
            function fullint_processJenisPemeriksaan(jenisPemeriksaan, items) {
                if (DEBUG_FULLINT) console.log('fullint process jenis', jenisPemeriksaan, items.length);

                let $section = $('.pemeriksaan-lain-section[data-jenis-pemeriksaan="' + jenisPemeriksaan + '"]');
                let $tbody;
                if ($section.length === 0) {
                    $section = fullint_createNewTableSection(jenisPemeriksaan);
                    $tbody = $section.find('tbody');
                } else $tbody = $section.find('tbody');

                const existingIds = [];
                $tbody.find('.fullint-id-input').each(function() {
                    const v = $(this).val(); if (v) existingIds.push(String(v));
                });

                const newItems = items.filter(i => !existingIds.includes(String(i.id_data_pemeriksaan)));
                if (newItems.length === 0) return { added: 0, skipped: items.length };

                let rowIndex = $tbody.find('tr').length;
                newItems.forEach((item, idx) => fullint_addSingleRow($tbody, item, jenisPemeriksaan, rowIndex + idx));

                return { added: newItems.length, skipped: items.length - newItems.length };
            }

            function fullint_addSingleRow($tbody, item, jenisPemeriksaan, rowIndex) {
                const rowHtml = `
                    <tr data-index="${rowIndex}" data-jenis-pemeriksaan="${jenisPemeriksaan}" data-id-data-pemeriksaan="${item.id_data_pemeriksaan}">
                        <td class="search-cell-hasil-lain">
                            <div class="position-relative">
                                <input type="text" class="form-control form-control-sm fullint-search-input" placeholder="Cari data pemeriksaan..." value="${item.data_pemeriksaan}" data-jenis-pemeriksaan="${jenisPemeriksaan}" data-index="${rowIndex}" autocomplete="off" readonly>
                                <div class="fullint-search-results dropdown-menu" style="display: none; max-height: 200px; overflow-y: auto; z-index: 1050;"></div>
                                <input type="hidden" class="fullint-id-input" value="${item.id_data_pemeriksaan}">
                                <input type="hidden" class="fullint-jenis-input" value="${item.data_pemeriksaan}">
                                <input type="hidden" class="fullint-rowid-input" value="">
                            </div>
                        </td>
                        <td class="bg-light fullint-satuan-cell"><span class="fullint-satuan-display">${item.satuan || '-'}</span></td>
                        <td class="bg-light fullint-rujukan-cell"><span class="fullint-rujukan-display">${item.rujukan || '-'}</span></td>
                        <td class="bg-light fullint-ch-cell"><span class="fullint-ch-display">${item.ch || '-'}</span></td>
                        <td class="bg-light fullint-cl-cell"><span class="fullint-cl-display">${item.cl || '-'}</span></td>
                        <td class="hasil-cell-hasil-lain"><input type="text" class="form-control form-control-sm fullint-hasil-input" value="" placeholder="Hasil" data-type="hasil_lain" data-id-data-pemeriksaan="${item.id_data_pemeriksaan}" data-rujukan="${item.rujukan || ''}" data-ch="${item.ch || ''}" data-cl="${item.cl || ''}" autocomplete="off"></td>
                        <td class="keterangan-cell-hasil-lain"><div class="fullint-ket-display bg-success bg-opacity-10 text-success rounded py-1 px-2 text-center" data-keterangan="-"><strong>-</strong></div><input type="hidden" class="fullint-ket-input" value="-"></td>
                        <td><button type="button" class="btn btn-sm btn-outline-danger fullint-hapus-row-btn"><i class="ri-delete-bin-line"></i></button></td>
                    </tr>`;
                $tbody.append(rowHtml);

                const $row = $tbody.find('tr:last-child');
                fullint_updateFormNames($row);

                const $hasilInput = $row.find('.fullint-hasil-input');
                $hasilInput.attr('data-rujukan', item.rujukan || '').attr('data-ch', item.ch || '').attr('data-cl', item.cl || '');

                fullint_updateRujukanBerdasarkanKondisi($row, item.id_data_pemeriksaan);

                setTimeout(() => fullint_saveDataPemeriksaanToDatabase($row, item.id_data_pemeriksaan, item.data_pemeriksaan, item.satuan, item.rujukan), 100);
            }

            function fullint_createNewTableSection(jenisPemeriksaan) {
                const slug = jenisPemeriksaan.toLowerCase().replace(/[^a-z0-9]+/g, '_');
                const newTableSection = `
                    <div class="pt-3 border-top pemeriksaan-lain-section" data-jenis-pemeriksaan="${jenisPemeriksaan}" id="section_${slug}">
                        <div class="row">
                            <div class="col-lg-9 col-md-12">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="mb-0 border-bottom pb-2">
                                        <i class="ri-list-check me-2"></i>${jenisPemeriksaan}
                                        <span class="badge bg-info ms-2">Kondisi: {{ $pasien->jenis_kelamin }} | {{ $data["umur_format"] }}</span>
                                    </h6>
                                    <div>
                                        <button type="button" class="btn btn-sm btn-outline-primary fullint-tambah-row-btn" data-jenis-pemeriksaan="${jenisPemeriksaan}"><i class="ri-add-line me-1"></i>Tambah Row</button>
                                        <button type="button" class="btn btn-sm btn-outline-success fullint-modal-hasil-lain-btn ms-2" data-jenis-pemeriksaan="${jenisPemeriksaan}"><i class="ri-list-check me-1"></i>Pilih dari Daftar</button>
                                        <button type="button" class="btn btn-sm btn-outline-danger fullint-hapus-tabel-btn ms-2" data-jenis-pemeriksaan="${jenisPemeriksaan}"><i class="ri-delete-bin-line me-1"></i>Hapus Tabel</button>
                                    </div>
                                </div>
                                <div class="table-responsive overflow-visible">
                                    <table class="table table-bordered table-sm pemeriksaan-lain-table table-row-skip" id="tabel_${slug}">
                                        <thead class="table-light">
                                            <tr>
                                                <th width="20%" class="bg-light">Pilih Jenis Pemeriksaan</th>
                                                <th width="10%" class="bg-light">Satuan</th>
                                                <th width="15%" class="bg-light">Rujukan</th>
                                                <th width="5%" class="bg-light">CH</th>
                                                <th width="5%" class="bg-light">CL</th>
                                                <th width="15%">Hasil Pengujian</th>
                                                <th width="10%">Keterangan</th>
                                                <th width="5%">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-12">
                                <div class="card h-100 border-start border-primary history-panel-card">
                                    <div class="card-header bg-light py-2">
                                        <h6 class="card-title mb-0 small"><i class="ri-history-line me-2 text-primary"></i>History ${jenisPemeriksaan}</h6>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="p-2 border-bottom bg-primary bg-opacity-5" id="currentHoverInfo_${slug}">
                                            <div class="text-center">
                                                <div class="text-primary mb-1 small" id="hoverJenisPemeriksaan_${slug}"><i class="ri-history-line me-1"></i><span>History Pemeriksaan</span></div>
                                                <div class="small text-muted" id="hoverTypeInfo_${slug}">Klik pada kolom "Hasil"</div>
                                            </div>
                                        </div>
                                        <div class="p-2" id="historyPanelContent_${slug}" style="height: 300px; overflow-y: auto; font-size: 0.85rem;">
                                            <div class="text-center text-muted py-4"><i class="ri-file-list-3-line display-6 mb-3 opacity-50"></i><p class="mb-1 small">History akan muncul di sini</p><small class="text-muted">Klik pada hasil</small></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>`;
                $('#tambahTabelBtn').closest('.card').before(newTableSection);
                return $('.pemeriksaan-lain-section[data-jenis-pemeriksaan="' + jenisPemeriksaan + '"]');
            }

            // -----------------------
            // Namespaced event bindings for inputs, delete, search, pilih
            // -----------------------

            // input hasil (debounce + per-input saving lock) - using fullint class
            $(document).off('input' + NS_FULLINT, '.fullint-hasil-input')
                    .on('input' + NS_FULLINT, '.fullint-hasil-input', function() {
                const $input = $(this);
                const id = $input.data('id');
                const value = $input.val();

                if (DEBUG_FULLINT) console.count('fullint.input fired');

                fullint_updateKeteranganHasilLain($input);

                clearTimeout($input.data('saveTimer_fullint'));
                $input.data('saveTimer_fullint', setTimeout(function() {
                    if (!id) {
                        if (DEBUG_FULLINT) console.log('fullint: no id yet, skip save');
                        return;
                    }
                    fullint_saveHasilPengujian(id, value, $input);
                }, 800));
            });

            function fullint_saveHasilPengujian(id, value, $input) {
                if (!$input || !$input.length) return;
                if ($input.data('saving_fullint')) {
                    if (DEBUG_FULLINT) console.warn('fullint save already in progress for input', id);
                    return;
                }
                $input.data('saving_fullint', true);

                const $row = $input.closest('tr');
                const keterangan = $row.find('.fullint-ket-input').val();

                if (DEBUG_FULLINT) console.log('fullint saving hasil -', { id, value, keterangan });

                $.ajax({
                    url: '/hasil-lain/update-hasil-pengujian/' + id,
                    method: 'PUT',
                    headers: { 'X-CSRF-TOKEN': csrfTokenFullint },
                    data: { hasil_pengujian: value, keterangan: keterangan },
                    beforeSend: function() { $input.addClass('is-changing'); },
                    success: function(response) {
                        if (DEBUG_FULLINT) console.log('fullint save response', response);
                        if (response && response.success) {
                            $input.removeClass('is-changing').addClass('is-changed');
                            if (typeof window.updateSaveStatus === 'function') window.updateSaveStatus();
                            if (typeof window.showToast === 'function') window.showToast('success', 'Hasil berhasil disimpan');
                        }
                    },
                    error: function(xhr, status, err) {
                        console.error('fullint save error', err);
                        $input.removeClass('is-changing').addClass('has-error');
                        if (typeof window.showToast === 'function') window.showToast('danger', 'Gagal menyimpan hasil');
                    },
                    complete: function() { $input.data('saving_fullint', false); }
                });
            }

            // hapus row (per-button deleting lock) - using fullint class
            $(document).off('click' + NS_FULLINT, '.fullint-hapus-row-btn')
                    .on('click' + NS_FULLINT, '.fullint-hapus-row-btn', function() {
                const $btn = $(this);
                const $row = $btn.closest('tr');
                const rowId = $row.data('id');

                if ($btn.data('deleting_fullint')) {
                    if (DEBUG_FULLINT) console.warn('fullint delete already in progress for', rowId);
                    return;
                }

                if (!rowId) { $row.remove(); return; }
                if (!confirm('Yakin ingin menghapus data ini secara permanen?')) return;

                $btn.data('deleting_fullint', true);

                $.ajax({
                    url: '/hasil-lain/destroy/' + rowId,
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': csrfTokenFullint },
                    success: function(res) {
                        if (res && res.success) {
                            $row.remove();
                            if (window.showToast) window.showToast('success', res.message);
                        } else {
                            if (window.showToast) window.showToast('danger', res && res.message || 'Gagal menghapus data');
                        }
                    },
                    error: function() { if (window.showToast) window.showToast('danger', 'Gagal menghapus data'); },
                    complete: function() { $btn.data('deleting_fullint', false); }
                });
            });

            // search realtime (debounced) - using fullint classes
            $(document).off('input' + NS_FULLINT, '.fullint-search-input')
                    .on('input' + NS_FULLINT, '.fullint-search-input', function() {
                const $input = $(this);
                const searchTerm = $input.val().trim();
                const $results = $input.next('.fullint-search-results');
                const jenisPemeriksaan = $input.data('jenis-pemeriksaan');

                clearTimeout($input.data('searchTimer_fullint'));
                if (searchTerm.length < 2) { $results.hide().empty(); return; }

                $input.data('searchTimer_fullint', setTimeout(function() {
                    $.ajax({
                        url: '/hasil-lain/search-data-pemeriksaan',
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': csrfTokenFullint },
                        data: { search: searchTerm, jenis_pemeriksaan: jenisPemeriksaan },
                        beforeSend: function() { $results.html('<div class="dropdown-item">Mencari data...</div>').show(); },
                        success: function(response) {
                            $results.empty();
                            if (response && response.success && response.data && response.data.length > 0) {
                                response.data.forEach(function(item) {
                                    const option = '<button type="button" class="dropdown-item fullint-pilih-option" ' +
                                        'data-id="' + item.id_data_pemeriksaan + '" data-nama="' + item.data_pemeriksaan + '" data-satuan="' + (item.satuan || '') + '" data-rujukan="' + (item.rujukan || '') + '" data-ch="' + (item.ch || '') + '" data-cl="' + (item.cl || '') + '">' +
                                        '<div class="d-flex justify-content-between align-items-center"><div><strong>' + item.data_pemeriksaan + '</strong><div class="small text-muted">' + (item.satuan || '') + ' | ' + (item.rujukan || '') + '</div></div><i class="ri-arrow-right-s-line text-muted"></i></div></button>';
                                    $results.append(option);
                                });
                            } else $results.html('<div class="dropdown-item text-muted">Tidak ditemukan data</div>');
                            $results.show();
                        },
                        error: function(err) { console.error('fullint search error', err); $results.html('<div class="dropdown-item text-danger">Error</div>').show(); }
                    });
                }, 500));
            });

            // pilih item dari search/dropdown - using fullint class
            $(document).off('click' + NS_FULLINT, '.fullint-pilih-option')
                    .on('click' + NS_FULLINT, '.fullint-pilih-option', function(e) {
                e.preventDefault();
                const $option = $(this);
                const $row = $option.closest('tr');

                const idDataPemeriksaan = $option.data('id');
                const nama = $option.data('nama');
                const satuan = $option.data('satuan') || '-';
                const rujukan = $option.data('rujukan') || '-';
                const ch = $option.data('ch') || '-';
                const cl = $option.data('cl') || '-';

                $row.find('.fullint-search-input').val(nama).attr('readonly', true);
                $row.find('.fullint-search-results').hide().empty();

                $row.find('.fullint-id-input').val(idDataPemeriksaan);
                $row.find('.fullint-jenis-input').val(nama);

                $row.find('.fullint-satuan-display').text(satuan);
                $row.find('.fullint-rujukan-display').text(rujukan);
                $row.find('.fullint-ch-display').text(ch);
                $row.find('.fullint-cl-display').text(cl);

                const $hasilInput = $row.find('.fullint-hasil-input');
                $hasilInput.attr('data-id-data-pemeriksaan', idDataPemeriksaan)
                    .attr('data-rujukan', rujukan)
                    .attr('data-ch', ch)
                    .attr('data-cl', cl);

                fullint_updateFormNames($row);
                fullint_updateRujukanBerdasarkanKondisi($row, idDataPemeriksaan);

                fullint_saveDataPemeriksaanToDatabase($row, idDataPemeriksaan, nama, satuan, rujukan);
            });

            // ----------------------------------
            // UI: Tambah Tabel / Modal / Tambah Row (fullint names)
            // ----------------------------------

            // Tambah Tabel (menggunakan fullint namespace)
            $(document).off('click' + NS_FULLINT, '#tambahTabelBtn')
                .on('click' + NS_FULLINT, '#tambahTabelBtn', function() {
                const jenisPemeriksaan = $('#jenisPemeriksaanSelect').val();

                if (!jenisPemeriksaan) {
                    if (typeof window.showToast === 'function') window.showToast('warning', 'Pilih jenis pemeriksaan terlebih dahulu');
                    return;
                }

                if ($('.pemeriksaan-lain-section[data-jenis-pemeriksaan="' + jenisPemeriksaan + '"]').length > 0) {
                    if (typeof window.showToast === 'function') window.showToast('warning', 'Tabel ' + jenisPemeriksaan + ' sudah ada');
                    return;
                }

                fullint_createNewTableSection(jenisPemeriksaan);
                $('#jenisPemeriksaanSelect').val('');
                if (typeof window.showToast === 'function') window.showToast('success', 'Tabel ' + jenisPemeriksaan + ' berhasil ditambahkan');
                if (DEBUG_FULLINT) console.log('Tabel ' + jenisPemeriksaan + ' ditambahkan');
            });

            // Modal: Pilih dari daftar
            let fullint_currentJenisPemeriksaanModal = null;
            let fullint_currentTableSectionModal = null;
            let fullint_modalSelectedData = [];
            let fullint_modalDataPemeriksaanList = [];

            $(document).off('click' + NS_FULLINT, '.fullint-modal-hasil-lain-btn')
                .on('click' + NS_FULLINT, '.fullint-modal-hasil-lain-btn', function(e){
                e.preventDefault();
                const jenisPemeriksaan = $(this).data('jenis-pemeriksaan');
                const $section = $(this).closest('.pemeriksaan-lain-section');
                fullint_currentJenisPemeriksaanModal = jenisPemeriksaan;
                fullint_currentTableSectionModal = $section;
                fullint_modalSelectedData = [];
                $('#searchModalDataPemeriksaan').val('');
                $('#selectAllModal').prop('checked', false);
                $('#selectedCountModal').text('0 item dipilih');
                $('#modalTitleJenisPemisah').text('Pilih Data Pemeriksaan - ' + jenisPemeriksaan);
                $('#modalDataPemeriksaanList').html('<tr><td colspan="5" class="text-center py-4"><div class="spinner-border spinner-border-sm text-primary me-2"></div>Memuat data pemeriksaan...</td></tr>');
                fullint_loadModalDataPemeriksaan(jenisPemeriksaan);
                const modal = new bootstrap.Modal(document.getElementById('modalPilihDataPemeriksaan'));
                modal.show();
            });

            function fullint_loadModalDataPemeriksaan(jenisPemeriksaan) {
                $.ajax({
                    url: '/hasil-lain/get-pemeriksaan-by-jenis',
                    method: 'GET',
                    data: { jenis_pemeriksaan: jenisPemeriksaan },
                    success: function(response) {
                        if (response.success && response.data && response.data.length > 0) {
                            fullint_modalDataPemeriksaanList = response.data;
                            fullint_renderModalDataPemeriksaanList();
                        } else {
                            $('#modalDataPemeriksaanList').html('<tr><td colspan="5" class="text-center py-4 text-muted"><i class="ri-inbox-line me-2"></i>Tidak ada data pemeriksaan untuk jenis ini</td></tr>');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Modal load error:', error);
                        $('#modalDataPemeriksaanList').html('<tr><td colspan="5" class="text-center py-4 text-danger"><i class="ri-error-warning-line me-2"></i>Gagal memuat data</td></tr>');
                    }
                });
            }

            function fullint_renderModalDataPemeriksaanList(filterTerm) {
                if (filterTerm === undefined) filterTerm = '';
                const $list = $('#modalDataPemeriksaanList');
                $list.empty();
                let filteredData = fullint_modalDataPemeriksaanList;
                if (filterTerm) {
                    const term = filterTerm.toLowerCase();
                    filteredData = fullint_modalDataPemeriksaanList.filter(function(item) {
                        const searchText = (item.id_data_pemeriksaan + ' ' + item.data_pemeriksaan + ' ' + (item.rujukan || '')).toLowerCase();
                        return searchText.includes(term);
                    });
                }
                if (filteredData.length === 0) {
                    $list.html('<tr><td colspan="5" class="text-center py-4 text-muted"><i class="ri-search-line me-2"></i>Tidak ditemukan data</td></tr>');
                    return;
                }
                filteredData.forEach(function(item) {
                    const isSelected = fullint_modalSelectedData.some(function(selected) { return selected.id === item.id_data_pemeriksaan; });
                    const row =
                        '<tr class="' + (isSelected ? 'table-primary' : '') + '">' +
                        '    <td><div class="form-check">' +
                        '        <input class="form-check-input modal-data-checkbox" type="checkbox"' +
                        '            data-id="' + item.id_data_pemeriksaan + '"' +
                        '            data-nama="' + item.data_pemeriksaan + '"' +
                        '            data-satuan="' + (item.satuan || '') + '"' +
                        '            data-rujukan="' + (item.rujukan || '') + '"' +
                        '            data-ch="' + (item.ch || '') + '"' +
                        '            data-cl="' + (item.cl || '') + '"' +
                        (isSelected ? ' checked' : '') + '>' +
                        '    </div></td>' +
                        '    <td><span class="badge bg-light text-dark">' + item.id_data_pemeriksaan + '</span></td>' +
                        '    <td>' + item.data_pemeriksaan + '</td>' +
                        '    <td>' + (item.satuan || '-') + '</td>' +
                        '    <td>' + (item.rujukan || '-') + '</td>' +
                        '</tr>';
                    $list.append(row);
                });
            }

            $('#searchModalDataPemeriksaan').on('input' + NS_FULLINT, function() {
                fullint_renderModalDataPemeriksaanList($(this).val());
            });
            $('#clearSearchModal').on('click' + NS_FULLINT, function() { $('#searchModalDataPemeriksaan').val('').trigger('input'); });

            $('#selectAllModal').on('change' + NS_FULLINT, function() {
                const isChecked = $(this).prop('checked');
                const visibleCheckboxes = $('.modal-data-checkbox:visible');
                visibleCheckboxes.each(function() {
                    const $checkbox = $(this);
                    if (isChecked && !$checkbox.prop('checked')) { $checkbox.prop('checked', true); fullint_addToModalSelected($checkbox); }
                    if (!isChecked && $checkbox.prop('checked')) { $checkbox.prop('checked', false); fullint_removeFromModalSelected($checkbox.data('id')); }
                });
                $('#selectedCountModal').text(fullint_modalSelectedData.length + ' item dipilih');
            });

            $(document).on('change' + NS_FULLINT, '.modal-data-checkbox', function() {
                const $checkbox = $(this);
                if ($checkbox.prop('checked')) fullint_addToModalSelected($checkbox);
                else { fullint_removeFromModalSelected($checkbox.data('id')); $('#selectAllModal').prop('checked', false); }
                $('#selectedCountModal').text(fullint_modalSelectedData.length + ' item dipilih');
            });

            function fullint_addToModalSelected($checkbox) {
                const data = {
                    id: $checkbox.data('id'),
                    nama: $checkbox.data('nama'),
                    satuan: $checkbox.data('satuan'),
                    rujukan: $checkbox.data('rujukan'),
                    ch: $checkbox.data('ch'),
                    cl: $checkbox.data('cl')
                };
                if (fullint_modalSelectedData.findIndex(item => item.id === data.id) === -1) fullint_modalSelectedData.push(data);
            }
            function fullint_removeFromModalSelected(id) { fullint_modalSelectedData = fullint_modalSelectedData.filter(item => item.id !== id); }

            // Tambah data ke tabel dari modal
            $(document).off('click' + NS_FULLINT, '#tambahDataPemeriksaanBtn')
                .on('click' + NS_FULLINT, '#tambahDataPemeriksaanBtn', function() {
                if (fullint_modalSelectedData.length === 0) { if (typeof window.showToast === 'function') window.showToast('warning', 'Pilih minimal satu data pemeriksaan'); return; }
                if (!fullint_currentTableSectionModal || !fullint_currentJenisPemeriksaanModal) { console.error('Table section tidak ditemukan'); return; }

                const $tbody = fullint_currentTableSectionModal.find('tbody');
                const currentRowCount = $tbody.find('tr').length;

                fullint_modalSelectedData.forEach(function(item, index) {
                    const rowIndex = currentRowCount + index;
                    const newRow =
                        '<tr data-index="' + rowIndex + '" data-jenis-pemeriksaan="' + fullint_currentJenisPemeriksaanModal + '">' +
                        '    <td class="search-cell-hasil-lain"><div class="position-relative">' +
                        '        <input type="text" class="form-control form-control-sm fullint-search-input" placeholder="Cari data pemeriksaan..."' +
                        '            value="' + item.nama + '" data-jenis-pemeriksaan="' + fullint_currentJenisPemeriksaanModal + '" data-index="' + rowIndex + '" autocomplete="off" readonly>' +
                        '        <div class="fullint-search-results dropdown-menu" style="display: none; max-height: 200px; overflow-y: auto; z-index: 1050;"></div>' +
                        '        <input type="hidden" class="fullint-id-input" value="' + item.id + '">' +
                        '        <input type="hidden" class="fullint-jenis-input" value="' + item.nama + '">' +
                        '        <input type="hidden" class="fullint-rowid-input" value="">' +
                        '    </div></td>' +
                        '    <td class="bg-light fullint-satuan-cell"><span class="fullint-satuan-display">' + (item.satuan || '-') + '</span></td>' +
                        '    <td class="bg-light fullint-rujukan-cell"><span class="fullint-rujukan-display">' + (item.rujukan || '-') + '</span></td>' +
                        '    <td class="bg-light fullint-ch-cell"><span class="fullint-ch-display">' + (item.ch || '-') + '</span></td>' +
                        '    <td class="bg-light fullint-cl-cell"><span class="fullint-cl-display">' + (item.cl || '-') + '</span></td>' +
                        '    <td class="hasil-cell-hasil-lain"><input type="text" class="form-control form-control-sm fullint-hasil-input" value="" placeholder="Hasil" data-id="" data-type="hasil_lain" data-id-data-pemeriksaan="' + item.id + '" data-rujukan="' + (item.rujukan || '') + '" data-ch="' + (item.ch || '') + '" data-cl="' + (item.cl || '') + '" autocomplete="off"></td>' +
                        '    <td class="keterangan-cell-hasil-lain"><div class="fullint-ket-display bg-success bg-opacity-10 text-success rounded py-1 px-2 text-center" data-keterangan="-"><strong>-</strong></div><input type="hidden" class="fullint-ket-input" value="-"></td>' +
                        '    <td><button type="button" class="btn btn-sm btn-outline-danger fullint-hapus-row-btn"><i class="ri-delete-bin-line"></i></button></td>' +
                        '</tr>';

                    $tbody.append(newRow);
                    const $lastRow = $tbody.find('tr:last-child');
                    fullint_updateFormNames($lastRow);

                    // Simpan ke database (sedikit delay agar DOM stabil)
                    setTimeout(function() {
                        fullint_saveDataPemeriksaanToDatabase($lastRow, item.id, item.nama, item.satuan, item.rujukan);
                    }, 100);
                });

                const modal = bootstrap.Modal.getInstance(document.getElementById('modalPilihDataPemeriksaan'));
                if (modal) modal.hide();
                const count = fullint_modalSelectedData.length;
                fullint_modalSelectedData = [];
                if (typeof window.showToast === 'function') window.showToast('success', count + ' data pemeriksaan berhasil ditambahkan');
            });

            // Hapus tabel (fullint)
            let fullint_tabelYangAkanDihapus = null;
            $(document).off('click' + NS_FULLINT, '.fullint-hapus-tabel-btn')
                .on('click' + NS_FULLINT, '.fullint-hapus-tabel-btn', function(e) {
                e.preventDefault();
                const jenisPemeriksaan = $(this).data('jenis-pemeriksaan');
                const $section = $(this).closest('.pemeriksaan-lain-section');
                fullint_tabelYangAkanDihapus = { jenisPemeriksaan: jenisPemeriksaan, $section: $section };
                $('#modalNamaTabel').text(jenisPemeriksaan);
                const modal = new bootstrap.Modal(document.getElementById('modalKonfirmasiHapusTabel'));
                modal.show();
            });

            $(document).off('click' + NS_FULLINT, '#konfirmasiHapusTabelBtn')
                .on('click' + NS_FULLINT, '#konfirmasiHapusTabelBtn', function() {
                if (!fullint_tabelYangAkanDihapus) return;
                const $section = fullint_tabelYangAkanDihapus.$section;
                const jenisPemeriksaan = fullint_tabelYangAkanDihapus.jenisPemeriksaan;
                const ids = [];
                $section.find('tr[data-id]').each(function() {
                    const id = $(this).data('id');
                    if (id) ids.push(id);
                });

                if (ids.length === 0) {
                    $section.remove();
                    const modal = bootstrap.Modal.getInstance(document.getElementById('modalKonfirmasiHapusTabel'));
                    if (modal) modal.hide();
                    if (window.showToast) window.showToast('success', 'Tabel ' + jenisPemeriksaan + ' berhasil dihapus');
                    fullint_tabelYangAkanDihapus = null;
                    return;
                }

                $.ajax({
                    url: '/hasil-lain/destroy-multiple',
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfTokenFullint },
                    data: { ids: ids },
                    success: function(res) {
                        if (res.success) {
                            $section.remove();
                            if (window.showToast) window.showToast('success', res.message);
                        } else {
                            if (window.showToast) window.showToast('danger', res.message || 'Gagal menghapus tabel');
                        }
                    },
                    error: function() { if (window.showToast) window.showToast('danger', 'Terjadi kesalahan saat menghapus tabel'); },
                    complete: function() {
                        const modal = bootstrap.Modal.getInstance(document.getElementById('modalKonfirmasiHapusTabel'));
                        if (modal) modal.hide();
                        fullint_tabelYangAkanDihapus = null;
                    }
                });
            });

            // Tambah row manual (namespaced)
            $(document).off('click' + NS_FULLINT, '.fullint-tambah-row-btn')
                .on('click' + NS_FULLINT, '.fullint-tambah-row-btn', function(e) {
                e.preventDefault();
                const jenisPemeriksaan = $(this).data('jenis-pemeriksaan');
                const $section = $(this).closest('.pemeriksaan-lain-section');
                const $tbody = $section.find('tbody');
                const rowCount = $tbody.find('tr').length;
                const newRow =
                    '<tr data-index="' + rowCount + '" data-jenis-pemeriksaan="' + jenisPemeriksaan + '">' +
                    '    <td class="search-cell-hasil-lain"><div class="position-relative">' +
                    '        <input type="text" class="form-control form-control-sm fullint-search-input" placeholder="Cari data pemeriksaan..." data-jenis-pemeriksaan="' + jenisPemeriksaan + '" data-index="' + rowCount + '" autocomplete="off">' +
                    '        <div class="fullint-search-results dropdown-menu" style="display: none; max-height: 200px; overflow-y: auto; z-index: 1050;"></div>' +
                    '        <input type="hidden" class="fullint-id-input" value="">' +
                    '        <input type="hidden" class="fullint-jenis-input" value="">' +
                    '        <input type="hidden" class="fullint-rowid-input" value="">' +
                    '    </div></td>' +
                    '    <td class="bg-light fullint-satuan-cell"><span class="fullint-satuan-display">-</span></td>' +
                    '    <td class="bg-light fullint-rujukan-cell"><span class="fullint-rujukan-display">-</span></td>' +
                    '    <td class="bg-light fullint-ch-cell"><span class="fullint-ch-display">-</span></td>' +
                    '    <td class="bg-light fullint-cl-cell"><span class="fullint-cl-display">-</span></td>' +
                    '    <td class="hasil-cell-hasil-lain"><input type="text" class="form-control form-control-sm fullint-hasil-input" value="" placeholder="Hasil" data-id="" data-type="hasil_lain" autocomplete="off"></td>' +
                    '    <td class="keterangan-cell-hasil-lain"><div class="fullint-ket-display bg-success bg-opacity-10 text-success rounded py-1 px-2 text-center" data-keterangan="-"><strong>-</strong></div><input type="hidden" class="fullint-ket-input" value="-"></td>' +
                    '    <td><button type="button" class="btn btn-sm btn-outline-danger fullint-hapus-row-btn"><i class="ri-delete-bin-line"></i></button></td>' +
                    '</tr>';
                $tbody.append(newRow);
                setTimeout(function() { $tbody.find('tr:last-child .fullint-search-input').focus(); }, 100);
            });

            // ----------------------------------
            // Init on load: update rujukan & keterangan
            // ----------------------------------
            $(window).on('load' + NS_FULLINT, function() {
                setTimeout(function() {
                    $('.fullint-hasil-input').each(function() {
                        const $input = $(this);
                        const $row = $input.closest('tr');
                        const idDataPemeriksaan = $row.find('.fullint-id-input').val();
                        if (idDataPemeriksaan) fullint_updateRujukanBerdasarkanKondisi($row, idDataPemeriksaan);
                        if ($input.val() && $input.val().trim() !== '') fullint_updateKeteranganHasilLain($input);
                    });
                    if (DEBUG_FULLINT) console.log('fullint initialization complete');
                }, 1200);
            });

            if (DEBUG_FULLINT) console.log('✅ HASIL LAIN FULL-INTEGRATION (final) loaded');
        });
    })(jQuery);
</script>
<!-- END SISTEM OTOMATIS HASIL LAIN DARI UJI PEMERIKSAAN -->


@endsection

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
    <!-- Row starts -->
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
                                <label class="form-label text-muted small mb-1">No. Lab / RM Pasien</label>
                                <div class="d-flex align-items-center">
                                    <i class="ri-file-list-3-line me-2 text-primary"></i>
                                    <h6 class="m-0 text-dark">{{ $pasien->no_lab ?? '-' }} / {{ $pasien->rm_pasien ?? '-' }}</h6>
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
                                <input type="hidden" data-field="umur" value="{{ $pasien->umur }}">
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

                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                            <div class="mb-3">
                                <label class="form-label text-muted small mb-1">Penjamin</label>
                                <div class="d-flex align-items-center">
                                    <i class="ri-shield-check-line me-2 text-primary"></i>
                                    <div class="flex-grow-1 position-relative">
                                        <input
                                            type="text"
                                            class="form-control realtime-field"
                                            data-field="nota"
                                            value="{{ $pasien->nota ?? '' }}"
                                            placeholder="Ketik nama penjamin..."
                                            style="font-size: 1rem; font-weight: 500; min-height: 1.8rem;">
                                        <div class="save-status position-absolute end-0 top-50 translate-middle-y me-2" style="display: none;">
                                            <i class="fas fa-spinner fa-spin text-primary" style="font-size: 0.8rem;"></i>
                                        </div>
                                    </div>
                                </div>
                                <small class="text-muted penjamin-message" style="display: none; font-size: 0.75rem;"></small>
                            </div>
                        </div>

                        <!-- Asal Kunjungan - FIX: Gunakan textarea bukan input -->
                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                            <div class="mb-3">
                                <label class="form-label">Asal Kunjungan</label>
                                <div class="input-group">
                                    <textarea
                                        class="form-control realtime-field"
                                        data-field="ket_klinik"
                                        rows="1"
                                        placeholder="Keterangan klinis">{{ $pasien->ket_klinik ?? '-' }}</textarea>
                                    <span class="input-group-text save-status" style="display: none; min-width: 40px; align-self: flex-start;"></span>
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
                                        value="{{ $pasien->created_at ? \Carbon\Carbon::parse($pasien->waktu_periksa)->format('Y-m-d\TH:i') : '' }}">
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
                                        data-field="updated_at"
                                        value="{{ $pasien->updated_at ? \Carbon\Carbon::parse($pasien->waktu_validasi)->format('Y-m-d\TH:i') : '' }}">
                                    <span class="input-group-text save-status" style="display: none; min-width: 40px;"></span>
                                </div>
                            </div>
                        </div>
                        <!-- Data pasien display ends -->
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="alert alert-info mb-0">
                            <i class="ri-information-line me-2"></i>
                            <strong>Info:</strong> Jenis Pengujian Yang Diminta:
                            <ul class="mb-0 mt-1">
                                @foreach($pasien->ujiPemeriksaan->groupBy('kategori') as $kategori => $ujiGroup)
                                    <li>
                                        <strong>{{ $kategori }}</strong>
                                        <ul>
                                            @foreach($ujiGroup as $uji)
                                                <li>{{ $uji->nama_pemeriksaan }}</li>
                                            @endforeach
                                        </ul>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Row ends -->

        <!-- Hasil Pengujian Section -->
        <div class="row gx-3 mt-3">
            <div class="col-sm-12">
                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-0">Hasil Pengujian Laboratorium</h5>
                            <p class="card-subtitle text-muted mb-0">No. Lab: {{ $pasien->no_lab }} | Nama: {{ $pasien->nama_pasien }}</p>
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
                            <!-- HEMATOLOGY SECTION -->
                            @if(count(array_filter($hematology_fix)) > 0)
                            <div class="mb-4">
                                <h6 class="mb-3 border-bottom pb-2">
                                    <i class="ri-test-tube-line me-2"></i>HEMATOLOGY
                                </h6>

                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm">
                                        <thead class="table-light">
                                            <tr>
                                                <th width="25%" class="bg-light">Jenis Pengujian</th>
                                                <th width="20%">Hasil Pengujian</th>
                                                <th width="15%" class="bg-light">Satuan</th>
                                                <th width="20%" class="bg-light">Rujukan</th>
                                                <th width="20%">Keterangan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                            $jenis_pemeriksaan_list = [
                                            'WBC', 'Neutrofil%', 'Limfosit%', 'Monosit%', 'Eosinofil%',
                                            'Basofil%', 'RBC', 'HGB', 'HCT', 'MCV', 'MCH', 'MCHC',
                                            'RDW-CV', 'RDW-SD', 'PLT', 'MPV', 'PDW', 'PCT'
                                            ];
                                            @endphp

                                            @foreach($hematology_fix as $index => $item)
                                            @php
                                            $jenis = $jenis_pemeriksaan_list[$index] ?? 'Unknown';
                                            $keterangan = $item->keterangan ?? '';

                                            // PASTIKAN INI ADA: Ambil rujukan dari dataPemeriksaan
                                            $rujukan_pemeriksaan = $item->dataPemeriksaan->rujukan ?? '-';

                                            // Tentukan warna untuk keterangan
                                            if ($keterangan === 'H') {
                                            $bgColor = 'bg-danger bg-opacity-10';
                                            $textColor = 'text-danger';
                                            $textDisplay = 'H';
                                            } elseif ($keterangan === 'L') {
                                            $bgColor = 'bg-primary bg-opacity-10';
                                            $textColor = 'text-primary';
                                            $textDisplay = 'L';
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

                                            <tr data-index="{{ $index }}">
                                                <td class="bg-light">
                                                    <strong>{{ $jenis }}</strong>
                                                    <input type="hidden"
                                                        name="hematology[{{ $index }}][id]"
                                                        value="{{ $item->id_pemeriksaan_hematology ?? '' }}">
                                                    <input type="hidden"
                                                        name="hematology[{{ $index }}][jenis_pengujian]"
                                                        value="{{ $jenis }}">
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
                                                        data-rujukan="{{ $rujukan_pemeriksaan }}"
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
                                                        autocomplete="off">
                                                    @endif
                                                </td>
                                                <td class="bg-light">
                                                    {{ $item->dataPemeriksaan->satuan ?? '-' }}
                                                </td>
                                                <td class="bg-light rujukan-cell">
                                                    {{ $rujukan_pemeriksaan }}
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
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @else
                            <div class="alert alert-warning mb-3">
                                <i class="ri-test-tube-line me-2"></i>Data hasil pemeriksaan untuk Hematology Belum/Tidak Dilakukan
                            </div>
                            @endif

                            <!-- KIMIA SECTION -->
                            @if($kimia->count() > 0)
                            <div class="mt-4">
                                <h6 class="mb-3 border-bottom pb-2">
                                    <i class="ri-flask-line me-2"></i>KIMIA
                                </h6>

                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm">
                                        <thead class="table-light">
                                            <tr>
                                                <th width="15%" class="bg-light">Nama Dari Alat</th>
                                                <th width="30%" class="bg-light">Nama Standar dari RS</th> <!-- Kolom baru -->
                                                <th width="15%" class="bg-light">Method</th>
                                                <th width="15%">Hasil Pengujian</th>
                                                <th width="10%" class="bg-light">Satuan</th>
                                                <th width="5%" class="bg-light">Rujukan</th>
                                                <th width="10%">Ket</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($kimia as $index => $item)
                                            @php
                                            $keterangan = $item->keterangan ?? '';
                                            $kode_pemeriksaan = $item->kode_pemeriksaan ?? null;
                                            $analysis = $item->analysis ?? '';

                                            // Warna untuk keterangan (sama seperti sebelumnya)
                                            if ($keterangan === 'H') {
                                            $bgColor = 'bg-danger bg-opacity-10';
                                            $textColor = 'text-danger';
                                            $textDisplay = 'H';
                                            } elseif ($keterangan === 'L') {
                                            $bgColor = 'bg-primary bg-opacity-10';
                                            $textColor = 'text-primary';
                                            $textDisplay = 'L';
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
                                            <tr data-index="{{ $index }}" data-kimia-id="{{ $item->id_pemeriksaan_kimia }}">
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
                                                <!-- Kolom Search Kode Pemeriksaan -->
                                                <td class="search-cell">
                                                    @if(!$kode_pemeriksaan)
                                                    <div class="position-relative">
                                                        <input type="text"
                                                            class="form-control form-control-sm kode-search-input"
                                                            placeholder="Cari kode pemeriksaan..."
                                                            data-kimia-id="{{ $item->id_pemeriksaan_kimia }}"
                                                            data-analysis="{{ $analysis }}"
                                                            autocomplete="off">
                                                        <div class="kode-search-results dropdown-menu w-100"
                                                            style="display: none; max-height: 200px; overflow-y: auto;">
                                                            <!-- Hasil pencarian akan dimuat di sini -->
                                                        </div>
                                                        <input type="hidden"
                                                            name="kimia[{{ $index }}][kode_pemeriksaan]"
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
                                                        <!-- Input untuk edit dengan display kode -->
                                                        <input type="text"
                                                            class="form-control form-control-sm kode-edit-input"
                                                            placeholder="Cari kode pemeriksaan..."
                                                            value="{{ $item->dataPemeriksaan->data_pemeriksaan ?? $kode_pemeriksaan }}"
                                                            data-kimia-id="{{ $item->id_pemeriksaan_kimia }}"
                                                            data-analysis="{{ $analysis }}"
                                                            data-current-kode="{{ $kode_pemeriksaan }}"
                                                            autocomplete="off">
                                                        <div class="kode-search-results dropdown-menu w-100"
                                                            style="display: none; max-height: 200px; overflow-y: auto;">
                                                            <!-- Hasil pencarian akan dimuat di sini -->
                                                        </div>
                                                        <input type="hidden"
                                                            name="kimia[{{ $index }}][kode_pemeriksaan]"
                                                            class="kode-pemeriksaan-input"
                                                            value="{{ $kode_pemeriksaan }}">

                                                        <!-- Tombol Edit/Reset -->
                                                        <div class="mt-1 d-flex justify-content-between align-items-center">
                                                            <small class="text-success">
                                                                <i class="ri-links-line me-1"></i>Telah dipetakan: <span class="kode-display">{{ $kode_pemeriksaan }}</span>
                                                            </small>
                                                            <!-- <button type="button" class="btn-reset-kode btn btn-xs btn-outline-danger"
                                                                data-kimia-id="{{ $item->id_pemeriksaan_kimia }}"
                                                                title="Reset mapping">
                                                                <i class="ri-close-line"></i>
                                                            </button> -->
                                                        </div>
                                                    </div>
                                                    @endif
                                                </td>

                                                <td class="bg-light method-cell">
                                                    {{ $item->dataPemeriksaan->metode ?? '-' }}
                                                    <input type="hidden"
                                                        name="kimia[{{ $index }}][method]"
                                                        value="{{ $item->dataPemeriksaan->metode ?? '' }}">
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
                                                        data-rujukan="{{ $item->rujukan ?? '' }}"
                                                        autocomplete="off">
                                                </td>
                                                <td class="bg-light satuan-cell" style="text-align:center;">
                                                    <span class="satuan-display">
                                                        {{ $item->dataPemeriksaan->satuan_hasil_pengujian ?? '-' }}
                                                    </span>
                                                </td>
                                                <td class=" bg-light rujukan-cell" style="text-align:center;">
                                                    <span class="rujukan-display">
                                                        {{ $item->dataPemeriksaan->rujukan ?? '-' }}
                                                    </span>
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
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @else
                            <div class="alert alert-warning">
                                <i class="ri-flask-line me-2"></i>Data hasil pemeriksaan untuk Kimia Belum/Tidak Dilakukan
                            </div>
                            @endif

                            @if($hasil_lain->count() > 0)
                            <!-- Tampilkan pemeriksaan yang sudah ada -->
                            @foreach($hasil_lain_grouped as $jenis_pemeriksaan => $items)
                            <div class="mt-4 pemeriksaan-lain-section" data-jenis-pemeriksaan="{{ $jenis_pemeriksaan }}">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="mb-0 border-bottom pb-2">
                                        <i class="ri-list-check me-2"></i>{{ $jenis_pemeriksaan }}
                                    </h6>
                                    <!-- DUA TOMBOL TERPISAH -->
                                    <div>
                                        <!-- TOMBOL 1: TAMBAH ROW KOSONG (Langsung) -->
                                        <button type="button" class="btn btn-sm btn-outline-primary tambah-row-btn"
                                                data-jenis-pemeriksaan="{{ $jenis_pemeriksaan }}">
                                            <i class="ri-add-line me-1"></i>Tambah Row
                                        </button>

                                        <!-- TOMBOL 2: TAMBAH DENGAN MODAL CHECKBOX -->
                                        <button type="button" class="btn btn-sm btn-outline-success tambah-modal-btn ms-2"
                                                data-jenis-pemeriksaan="{{ $jenis_pemeriksaan }}">
                                            <i class="ri-checkbox-multiple-line me-1"></i>Pilih dari Daftar
                                        </button>

                                        <button type="button" class="btn btn-sm btn-outline-danger hapus-tabel-btn ms-2"
                                                data-jenis-pemeriksaan="{{ $jenis_pemeriksaan }}">
                                            <i class="ri-delete-bin-line me-1"></i>Hapus Tabel
                                        </button>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm pemeriksaan-lain-table">
                                        <thead class="table-light">
                                            <tr>
                                                <th width="20%" class="bg-light" style="text-align:center;" hidden>Jenis Pengujian</th>
                                                <th width="25%" class="bg-light" style="text-align:center;">Pilih Jenis Pemeriksaan</th>
                                                <th width="15%" class="bg-light" style="text-align:center;">Satuan</th>
                                                <th width="15%" class="bg-light" style="text-align:center;">Rujukan</th>
                                                <th width="15%" style="text-align:center;">Hasil Pengujian</th>
                                                <th width="10%" style="text-align:center;">Ket</th>
                                                <th width="5%" style="text-align:center;">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($items as $index => $item)
                                            @php
                                            // Ambil data dari object hasil query
                                            $jenis_pemeriksaan_id = $item->jenis_pemeriksaan_id ?? '';
                                            $data_pemeriksaan = $item->data_pemeriksaan ?? $item->jenis_pengujian ?? '';
                                            $satuan = $item->satuan_pemeriksaan ?? $item->satuan_hasil_pengujian ?? '-';
                                            $rujukan = $item->rujukan_pemeriksaan ?? $item->rujukan ?? '-';
                                            $keterangan = $item->Keterangan ?? '';

                                            // Tentukan warna untuk keterangan
                                            if ($keterangan === 'H') {
                                            $bgColor = 'bg-danger bg-opacity-10';
                                            $textColor = 'text-danger';
                                            $textDisplay = 'H';
                                            } elseif ($keterangan === 'L') {
                                            $bgColor = 'bg-primary bg-opacity-10';
                                            $textColor = 'text-primary';
                                            $textDisplay = 'L';
                                            } else {
                                            $bgColor = 'bg-success bg-opacity-10';
                                            $textColor = 'text-success';
                                            $textDisplay = '-';
                                            }
                                            @endphp

                                            <tr data-index="{{ $index }}"
                                                data-id="{{ $item->id_hasil_lain ?? '' }}"
                                                data-jenis-pemeriksaan="{{ $jenis_pemeriksaan }}"
                                                data-jenis-pemeriksaan-id="{{ $jenis_pemeriksaan_id }}">
                                                <td class="bg-light" hidden>
                                                    <strong>{{ $item->jenis_pengujian ?? 'Belum dipilih' }}</strong>
                                                    <input type="hidden"
                                                        name="hasil_lain[{{ $jenis_pemeriksaan }}][{{ $index }}][id]"
                                                        value="{{ $item->id_hasil_lain ?? '' }}">
                                                    <input type="hidden"
                                                        name="hasil_lain[{{ $jenis_pemeriksaan }}][{{ $index }}][jenis_pengujian]"
                                                        value="{{ $item->jenis_pengujian ?? '' }}">
                                                </td>

                                                <td class="search-cell">
                                                    @if(!$item->kode_pemeriksaan)
                                                    <div class="position-relative">
                                                        <input type="text"
                                                            class="form-control form-control-sm kode-search-input-lain"
                                                            placeholder="Cari kode pemeriksaan..."
                                                            data-jenis-pemeriksaan="{{ $jenis_pemeriksaan }}"
                                                            data-jenis-pemeriksaan-id="{{ $jenis_pemeriksaan_id }}"
                                                            data-index="{{ $index }}"
                                                            data-row-id="{{ $item->id_hasil_lain ?? '' }}"
                                                            autocomplete="off">
                                                        <div class="kode-search-results dropdown-menu w-100"
                                                            style="display: none; max-height: 200px; overflow-y: auto;">
                                                            <!-- Hasil pencarian akan dimuat di sini -->
                                                        </div>
                                                        <input type="hidden"
                                                            name="hasil_lain[{{ $jenis_pemeriksaan }}][{{ $index }}][kode_pemeriksaan]"
                                                            class="kode-pemeriksaan-input"
                                                            value="">
                                                    </div>
                                                    @else
                                                    <div class="position-relative">
                                                        <input type="text"
                                                            class="form-control form-control-sm kode-edit-input-lain"
                                                            placeholder="Cari kode pemeriksaan..."
                                                            value="{{ $data_pemeriksaan }}"
                                                            data-jenis-pemeriksaan="{{ $jenis_pemeriksaan }}"
                                                            data-jenis-pemeriksaan-id="{{ $jenis_pemeriksaan_id }}"
                                                            data-index="{{ $index }}"
                                                            data-row-id="{{ $item->id_hasil_lain ?? '' }}"
                                                            data-current-kode="{{ $item->kode_pemeriksaan }}"
                                                            autocomplete="off">
                                                        <div class="kode-search-results dropdown-menu w-100"
                                                            style="display: none; max-height: 200px; overflow-y: auto;">
                                                            <!-- Hasil pencarian akan dimuat di sini -->
                                                        </div>
                                                        <input type="hidden"
                                                            name="hasil_lain[{{ $jenis_pemeriksaan }}][{{ $index }}][kode_pemeriksaan]"
                                                            class="kode-pemeriksaan-input"
                                                            value="{{ $item->kode_pemeriksaan }}">
                                                        <div class="mt-1">
                                                            <small class="text-success">
                                                                <i class="ri-links-line me-1"></i>Telah dipetakan
                                                            </small>
                                                        </div>
                                                    </div>
                                                    @endif
                                                </td>

                                                <td class="bg-light satuan-cell" style="text-align:center;">
                                                    <span class="satuan-display">
                                                        {{ $satuan }}
                                                    </span>
                                                </td>

                                                <td class="bg-light rujukan-cell" style="text-align:center;">
                                                    <span class="rujukan-display">
                                                        {{ $rujukan }}
                                                    </span>
                                                </td>

                                                <td class="hasil-cell">
                                                    <input type="text"
                                                        name="hasil_lain[{{ $jenis_pemeriksaan }}][{{ $index }}][hasil_pengujian]"
                                                        class="form-control form-control-sm excel-input hasil-input-lain"
                                                        value="{{ $item->hasil_pengujian ?? '' }}"
                                                        placeholder="Hasil"
                                                        data-original="{{ $item->hasil_pengujian ?? '' }}"
                                                        data-id="{{ $item->id_hasil_lain }}"
                                                        data-type="hasil_lain"
                                                        data-rujukan="{{ $rujukan }}"
                                                        autocomplete="off">
                                                </td>

                                                <td class="keterangan-cell">
                                                    <div class="keterangan-display {{ $bgColor }} {{ $textColor }} rounded py-1 px-2 text-center"
                                                        data-keterangan="{{ $keterangan }}">
                                                        <strong>{{ $textDisplay }}</strong>
                                                    </div>
                                                    <input type="hidden"
                                                        name="hasil_lain[{{ $jenis_pemeriksaan }}][{{ $index }}][keterangan]"
                                                        value="{{ $keterangan }}">
                                                </td>

                                                <td>
                                                    <button type="button" class="btn btn-sm btn-outline-danger hapus-row-btn">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @endforeach
                            @endif

                            <!-- Tombol untuk tambah tabel pemeriksaan baru -->
                            <div class="mt-4">
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
                                                    @foreach($jenis_pemeriksaan_1_list as $itemJenis) <!-- UBAH DI SINI -->
                                                    <option value="{{ $itemJenis->nama_pemeriksaan }}">{{ $itemJenis->nama_pemeriksaan }}</option>
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
                        </div>

                        <div class="card-footer border-top">
                            <div class="row align-items-center">
                                <div class="col-md-4">
                                    <div class="mb-2 mb-md-0">
                                        <label class="form-label small mb-1">
                                            <i class="ri-user-check-line me-1"></i>Validator
                                        </label>
                                        <div class="d-flex align-items-center">
                                            <select id="pemeriksaSelect" class="form-select form-select-sm"
                                                style="max-width: 250px;">
                                                <!-- HAPUS atribut disabled -->
                                                <option value="">-- Pilih Pemeriksa --</option>
                                                @if($pasien->pemeriksa)
                                                <option value="{{ $pasien->id_pemeriksa }}" selected>
                                                    {{ $pasien->pemeriksa->nama_pemeriksa }}
                                                </option>
                                                @endif
                                            </select>
                                            <button id="savePemeriksaBtn"
                                                class="btn btn-sm btn-outline-primary ms-2">
                                                <!-- HAPUS atribut disabled -->
                                                <i class="ri-save-line"></i>
                                            </button>
                                        </div>
                                        @if($pasien->id_pemeriksa)
                                        <div class="mt-1">
                                            <small class="text-success" id="validatorInfo">
                                                <i class="ri-checkbox-circle-line me-1"></i>
                                                Sudah divalidasi oleh:
                                                <strong>{{ $pasien->pemeriksa->nama_pemeriksa ?? '-'}}</strong>
                                                <br>
                                                <small class="text-muted">
                                                    Pada: {{ $pasien->updated_at ?? '-' }}
                                                </small>

                                            </small>
                                        </div>
                                        @else
                                        <div class="mt-1" id="validatorInfo">
                                            <small class="text-warning">
                                                <i class="ri-alert-line me-1"></i>
                                                Belum divalidasi. Pilih pemeriksa sebelum print.
                                            </small>
                                        </div>
                                        @endif
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
                                    <a href="{{ route('pasien.print', $pasien->no_lab) }}" target="_blank" class="btn btn-success" id="printBtn">
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
        <!-- Row ends -->
        @else
        <div class="alert alert-danger">
            <i class="ri-error-warning-line me-2"></i>Data pasien tidak ditemukan.
        </div>
        @endif
    </div>
    <div id="globalSearchResults" class="dropdown-menu" style="display: none; position: fixed; z-index: 1060; border: 1px solid rgba(0,0,0,.15); border-radius: 0.375rem; background-color: white; box-shadow: 0 0.5rem 1rem rgba(0,0,0,.15);">
        <!-- Hasil pencarian akan ditampilkan di sini -->
    </div>
    <!-- App body ends -->
     <!-- Modal untuk memilih pemeriksaan -->
    <div class="modal fade" id="pilihPemeriksaanModal" tabindex="-1" aria-labelledby="pilihPemeriksaanModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pilihPemeriksaanModalLabel">
                        <i class="ri-checkbox-multiple-line me-2"></i>
                        Pilih Pemeriksaan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Info jenis pemeriksaan -->
                    <div class="alert alert-info mb-3">
                        <div class="d-flex align-items-start">
                            <i class="ri-information-line me-2 mt-1"></i>
                            <div>
                                <strong>Petunjuk:</strong>
                                <ul class="mb-0 mt-1">
                                    <li>Centang pemeriksaan yang ingin ditambahkan</li>
                                    <li>Urutan akan mengikuti urutan centangan (yang pertama dicentang muncul pertama)</li>
                                    <li>Gunakan tombol <strong>Terapkan ke Tabel</strong> untuk menambah ke tabel</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Filter pencarian -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="input-group">
                                <span class="input-group-text"><i class="ri-search-line"></i></span>
                                <input type="text" id="searchPemeriksaanInput" class="form-control" placeholder="Cari pemeriksaan...">
                                <button class="btn btn-outline-secondary" type="button" id="clearSearchBtn">
                                    <i class="ri-close-line"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Selected counter -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <span class="badge bg-primary" id="selectedCount">0 dipilih</span>
                        </div>
                        <div>
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="selectAllBtn">
                                <i class="ri-checkbox-line me-1"></i>Centang Semua
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger ms-2" id="deselectAllBtn">
                                <i class="ri-checkbox-blank-line me-1"></i>Hapus Semua
                            </button>
                        </div>
                    </div>

                    <!-- Daftar pemeriksaan -->
                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-hover table-sm">
                            <thead class="sticky-top bg-light">
                                <tr>
                                    <th width="50px" class="text-center">
                                        <input type="checkbox" id="selectAllCheckbox" class="form-check-input">
                                    </th>
                                    <th width="100px">Kode</th>
                                    <th>Nama Pemeriksaan</th>
                                    <th width="80px">Satuan</th>
                                    <th width="120px">Rujukan</th>
                                </tr>
                            </thead>
                            <tbody id="pemeriksaanList">
                                <!-- Data akan diisi oleh JavaScript -->
                            </tbody>
                        </table>
                    </div>

                    <!-- No results -->
                    <div id="noResults" class="text-center py-4" style="display: none;">
                        <i class="ri-search-line display-5 text-muted mb-2"></i>
                        <p class="text-muted">Tidak ditemukan pemeriksaan</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="me-auto">
                        <small class="text-muted">
                            <span id="selectedItemsPreview"></span>
                        </small>
                    </div>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="ri-close-line me-1"></i>Batal
                    </button>
                    <button type="button" class="btn btn-primary" id="applyToTableBtn">
                        <i class="ri-table-line me-1"></i>Terapkan ke Tabel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>


<style>
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
        font-weight: bold;
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

</style>

<!-- Toast Container -->
<div class="toast-container"></div>

@endsection

@section('scripts')
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
        window.updateKeteranganDisplay = function($display, keterangan) {
            // Reset semua kelas
            $display.removeClass('bg-danger bg-opacity-10 bg-primary bg-opacity-10 bg-success bg-opacity-10 text-danger text-primary text-success');

            // Tambahkan kelas sesuai nilai
            if (keterangan === 'H') {
                $display.addClass('bg-danger bg-opacity-10 text-danger')
                    .html('<strong>H</strong>');
            } else if (keterangan === 'L') {
                $display.addClass('bg-primary bg-opacity-10 text-primary')
                    .html('<strong>L</strong>');
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

            console.log('updateKeteranganDisplay - Keterangan:', finalKeterangan);
            console.log('updateKeteranganDisplay - Hidden input updated to:', $hiddenInput.val());
        };

        // Function untuk update keterangan client-side (preview) - GLOBAL SCOPE
        window.updateKeteranganClientSide = function($input) {
            const hasil = $input.val();
            const rujukan = $input.data('rujukan') || $input.attr('data-rujukan') || '';
            const $row = $input.closest('tr');
            const $keteranganDisplay = $row.find('.keterangan-display');
            const $hiddenInput = $row.find('input[name*="[keterangan]"]');

            console.log('=== updateKeteranganClientSide ===');
            console.log('Hasil:', hasil);
            console.log('Rujukan:', rujukan);
            console.log('Input ID:', $input.data('id'));
            console.log('Hidden input sebelum:', $hiddenInput.val());

            // Clear jika kosong
            if (!hasil || hasil.trim() === '') {
                console.log('Hasil kosong, reset keterangan');
                updateKeteranganDisplay($keteranganDisplay, '');
                return;
            }

            if (!rujukan || rujukan.trim() === '' || rujukan.trim() === '-') {
                console.log('Rujukan tidak tersedia atau "-"');
                updateKeteranganDisplay($keteranganDisplay, '-');
                return;
            }

            // Jika rujukan tidak ada
            if (!rujukan || rujukan.trim() === '' || rujukan.trim() === '-') {
                console.log('Rujukan tidak tersedia');
                updateKeteranganDisplay($keteranganDisplay, '-');
                return;
            }

            try {
                const rujukanStr = rujukan.toString().trim();
                const hasilStr = hasil.toString().trim();
                const hasilNum = parseFloat(hasilStr.replace(',', '.'));

                console.log('=== Data untuk perhitungan ===');
                console.log('Rujukan string:', rujukanStr);
                console.log('Hasil string:', hasilStr);
                console.log('Hasil number:', hasilNum);

                // Jika bukan angka, cek kualitatif
                if (isNaN(hasilNum)) {
                    const hasilLower = hasilStr.toLowerCase();
                    const rujukanLower = rujukanStr.toLowerCase();

                    if (rujukanLower.includes('negative') || rujukanLower.includes('negatif')) {
                        if (hasilLower.includes('negative') || hasilLower.includes('negatif') ||
                            hasilLower.includes('reactive') || hasilLower.includes('reaktif')) {
                            updateKeteranganDisplay($keteranganDisplay, '-');
                        } else {
                            updateKeteranganDisplay($keteranganDisplay, 'H');
                        }
                        return;
                    } else if (rujukanLower.includes('positive') || rujukanLower.includes('positif')) {
                        if (hasilLower.includes('positive') || hasilLower.includes('positif') ||
                            hasilLower.includes('reactive') || hasilLower.includes('reaktif')) {
                            updateKeteranganDisplay($keteranganDisplay, '-');
                        } else {
                            updateKeteranganDisplay($keteranganDisplay, 'L');
                        }
                        return;
                    } else {
                        updateKeteranganDisplay($keteranganDisplay, '-');
                    }
                    return;
                }

                // ============================================
                // LOGIKA BARU BERDASARKAN PENJELASAN ANDA
                // ============================================

                // 1. FORMAT RANGE: "1 - 90" atau "1-90"
                if (rujukanStr.includes('-') && !rujukanStr.includes('<') && !rujukanStr.includes('>')) {
                    const parts = rujukanStr.split('-');
                    if (parts.length === 2) {
                        const min = parseFloat(parts[0].trim());
                        const max = parseFloat(parts[1].trim());

                        if (!isNaN(min) && !isNaN(max)) {
                            console.log('=== FORMAT RANGE ===');
                            console.log('Min:', min, 'Max:', max, 'Hasil:', hasilNum);

                            if (hasilNum < min) {
                                console.log(`Hasil (${hasilNum}) < Min (${min}) → L`);
                                updateKeteranganDisplay($keteranganDisplay, 'L');
                            } else if (hasilNum > max) {
                                console.log(`Hasil (${hasilNum}) > Max (${max}) → H`);
                                updateKeteranganDisplay($keteranganDisplay, 'H');
                            } else {
                                console.log(`Hasil (${hasilNum}) dalam range ${min}-${max} → -`);
                                updateKeteranganDisplay($keteranganDisplay, '-');
                            }
                            return;
                        }
                    }
                }

                // 2. FORMAT "< 90" (Normal di ATAS 90)
                if (rujukanStr.startsWith('<')) {
                    const batas = parseFloat(rujukanStr.replace('<', '').trim());
                    if (!isNaN(batas)) {
                        console.log('=== FORMAT "< batas" ===');
                        console.log('Batas:', batas, 'Hasil:', hasilNum);
                        console.log('Interpretasi: Normal jika ≥ batas, Low jika < batas');

                        if (hasilNum >= batas) {
                            console.log(`Hasil (${hasilNum}) ≥ Batas (${batas}) → - (Normal)`);
                            updateKeteranganDisplay($keteranganDisplay, '-');
                        } else {
                            console.log(`Hasil (${hasilNum}) < Batas (${batas}) → L (Low)`);
                            updateKeteranganDisplay($keteranganDisplay, 'L');
                        }
                        return;
                    }
                }

                // Default: tidak ada pola yang cocok
                console.log('=== Tidak ada pola yang cocok ===');
                updateKeteranganDisplay($keteranganDisplay, '-');

            } catch (e) {
                console.error('Error:', e);
                updateKeteranganDisplay($keteranganDisplay, '-');
            }

            console.log('Hidden input sesudah:', $hiddenInput.val());
        };

        // Function untuk menambah request ke queue
        function addToQueue(type, id, field, value, $element) {
            if (!id) return;

            const $row = $element.closest('tr');
            const $keteranganDisplay = $row.find('.keterangan-display');
            const $hiddenInput = $row.find('input[name*="[keterangan]"]');

            // Inisialisasi keterangan
            let keterangan = $hiddenInput.val();

            // Jika kosong atau '-', cek dari display
            if (!keterangan || keterangan === '' || keterangan === '-') {
                keterangan = $keteranganDisplay.data('keterangan') || '-';
            }

            // Ambil rujukan dan hasil saat ini
            const rujukan = $element.data('rujukan') || $element.attr('data-rujukan') || '';
            const hasil = value;

            // HANYA hitung ulang jika rujukan valid dan keterangan masih '-'
            if (keterangan === '-' && hasil && hasil.trim() !== '' &&
                rujukan && rujukan.trim() !== '' && rujukan.trim() !== '-') {

                console.log('addToQueue - Menghitung ulang keterangan karena rujukan valid');
                console.log('Hasil:', hasil, 'Rujukan:', rujukan);

                const hasilStr = hasil.toString().trim();
                const rujukanStr = rujukan.toString().trim();
                const hasilNum = parseFloat(hasilStr.replace(',', '.'));

                // Hanya hitung jika hasil numerik dan rujukan tidak kosong
                if (!isNaN(hasilNum) && rujukanStr) {
                    // 1. FORMAT RANGE: "1 - 90" atau "1-90"
                    if (rujukanStr.includes('-') && !rujukanStr.includes('<') && !rujukanStr.includes('>')) {
                        const parts = rujukanStr.split('-');
                        if (parts.length === 2) {
                            const min = parseFloat(parts[0].trim());
                            const max = parseFloat(parts[1].trim());

                            if (!isNaN(min) && !isNaN(max)) {
                                if (hasilNum < min) {
                                    keterangan = 'L';
                                    console.log(`Hasil (${hasilNum}) < Min (${min}) → L`);
                                } else if (hasilNum > max) {
                                    keterangan = 'H';
                                    console.log(`Hasil (${hasilNum}) > Max (${max}) → H`);
                                } else {
                                    keterangan = '-';
                                    console.log(`Hasil (${hasilNum}) dalam range ${min}-${max} → -`);
                                }
                            }
                        }
                    }
                    // 2. FORMAT "< 90" (Normal jika ≥ batas, Low jika < batas)
                    else if (rujukanStr.startsWith('<')) {
                        const batas = parseFloat(rujukanStr.replace('<', '').trim());
                        if (!isNaN(batas)) {
                            if (hasilNum >= batas) {
                                keterangan = '-';
                                console.log(`Hasil (${hasilNum}) ≥ Batas (${batas}) → -`);
                            } else {
                                keterangan = 'L';
                                console.log(`Hasil (${hasilNum}) < Batas (${batas}) → L`);
                            }
                        }
                    }
                    // Format lainnya, default ke '-'
                    else {
                        keterangan = '-';
                        console.log('Format rujukan tidak dikenali, default ke "-"');
                    }
                } else {
                    // Handle hasil non-numerik atau rujukan tidak valid
                    const hasilLower = hasilStr.toLowerCase();
                    const rujukanLower = rujukanStr.toLowerCase();

                    if (rujukanLower.includes('negative') || rujukanLower.includes('negatif')) {
                        if (hasilLower.includes('negative') || hasilLower.includes('negatif') ||
                            hasilLower.includes('non-reactive') || hasilLower.includes('nonreactive')) {
                            keterangan = '-';
                        } else {
                            keterangan = 'H';
                        }
                    } else if (rujukanLower.includes('positive') || rujukanLower.includes('positif')) {
                        if (hasilLower.includes('positive') || hasilLower.includes('positif') ||
                            hasilLower.includes('reactive') || hasilLower.includes('reaktif')) {
                            keterangan = '-';
                        } else {
                            keterangan = 'L';
                        }
                    } else {
                        keterangan = '-';
                    }
                }

                // Update display di client untuk preview
                updateKeteranganDisplay($keteranganDisplay, keterangan);
            }

            // Pastikan keterangan tidak kosong
            if (!keterangan || keterangan === '') {
                keterangan = '-';
            }

            console.log('addToQueue - Keterangan final:', keterangan,
                'Hidden input:', $hiddenInput.val(),
                'Display data:', $keteranganDisplay.data('keterangan'));

            ajaxQueue.push({
                type: type,
                id: id,
                field: field,
                value: value,
                $element: $element,
                keterangan: keterangan, // SELALU kirim keterangan
                timestamp: Date.now()
            });

            if (!isProcessingQueue) {
                processQueue();
            }
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
                    if (response.success) {
                        request.$element.removeClass('is-changing').addClass('is-changed');
                        request.$element.data('original', request.value);

                        // TIDAK PERLU update keterangan dari server response
                        // Karena kita sudah menghitung dengan benar di client
                        console.log('Server response:', response);

                        // Hanya log jika berbeda, tapi JANGAN update
                        if (response.data.keterangan && response.data.keterangan !== request.keterangan) {
                            console.log('PERHATIAN: Server mengembalikan keterangan berbeda:',
                                'Client:', request.keterangan,
                                'Server:', response.data.keterangan,
                                'Kami tetap menggunakan:', request.keterangan);

                            // Tetap gunakan yang dari client, JANGAN update UI
                            // updateKeteranganDisplay TIDAK dipanggil di sini
                        }

                        if (response.data.updated_at) {
                            $('#lastSaved').text(`Terakhir disimpan: ${response.data.updated_at}`);
                        }

                        pendingChanges = $('.is-changed').length > 0;
                        updateSaveStatus();

                        request.$element.closest('td').addClass('table-success');
                        setTimeout(() => {
                            request.$element.closest('td').removeClass('table-success');
                        }, 1000);

                        showToast('success', 'Data berhasil disimpan');

                        // DEBUG: Tampilkan nilai akhir
                        const $row = request.$element.closest('tr');
                        const $hiddenInput = $row.find('input[name*="[keterangan]"]');
                        const $display = $row.find('.keterangan-display');
                        console.log('processQueue - Keterangan akhir:', {
                            hidden: $hiddenInput.val(),
                            display: $display.data('keterangan'),
                            client: request.keterangan,
                            server: response.data.keterangan
                        });
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
        // INISIALISASI DATA RUJUKAN DARI data_pemeriksaan SAAT PAGE LOAD
        console.log('Initializing rujukan data from data_pemeriksaan...');
        $('.hasil-input').each(function() {
            const $input = $(this);
            const $row = $input.closest('tr');

            // Coba dari rujukan-display terlebih dahulu
            let rujukanDisplay = $row.find('.rujukan-display').text().trim();

            // Jika rujukan display '-', kosong, atau tidak valid
            if (!rujukanDisplay || rujukanDisplay === '-' || rujukanDisplay === '') {
                rujukanDisplay = '';
            }

            // Set data-rujukan
            $input.data('rujukan', rujukanDisplay);
            $input.attr('data-rujukan', rujukanDisplay);

            console.log('Initialized rujukan for input:', {
                id: $input.data('id'),
                type: $input.data('type'),
                rujukan: rujukanDisplay,
                isEmptyOrDash: (!rujukanDisplay || rujukanDisplay === '-')
            });
        });

        // Auto-focus first input
        if ($('.hasil-input').length > 0) {
            $('.hasil-input').first().focus();
        }
    });
</script>

<script>
    $(document).ready(function() {
        const csrfToken = $('#csrf_token').val();

        // Function untuk update kode display
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
                            placeholder="Cari kode pemeriksaan..."
                            value="${dataPemeriksaan || kode}"
                            data-kimia-id="${kimiaId}"
                            data-analysis="${analysis}"
                            data-current-kode="${kode}"
                            autocomplete="off">
                        <div class="kode-search-results dropdown-menu w-100"
                             style="display: none; max-height: 200px; overflow-y: auto;">
                        </div>
                        <input type="hidden"
                            name="kimia[${rowIndex}][kode_pemeriksaan]"
                            class="kode-pemeriksaan-input"
                            value="${kode}">

                        <div class="mt-1 d-flex justify-content-between align-items-center">
                            <small class="text-success">
                                <i class="ri-links-line me-1"></i>Telah dipetakan:
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
                            name="kimia[${rowIndex}][kode_pemeriksaan]"
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

        // Function untuk reset mapping kode
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
                    // Gunakan showToast dari global scope
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

                            // Gunakan fungsi updateKeteranganDisplay dari global scope
                            if (typeof updateKeteranganDisplay === 'function') {
                                updateKeteranganDisplay($keteranganDisplay, '-');
                            }

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

                        // Gunakan showToast dari global scope
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

        // Function untuk handle search kode
        function handleKodeSearch($input) {
            const searchTerm = $input.val().trim();
            const $results = $input.next('.kode-search-results');
            const kimiaId = $input.data('kimia-id');
            const analysis = $input.data('analysis');
            const currentKode = $input.data('current-kode');

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
                        exclude_current: currentKode
                    },
                    beforeSend: function() {
                        $results.html('<div class="dropdown-item text-center py-2"><i class="ri-loader-4-line spin"></i> Mencari...</div>').show();
                    },
                    success: function(response) {
                        $results.empty();

                        if (response.success && response.data && response.data.length > 0) {
                            response.data.forEach(function(item) {
                                const $option = $(`
                                    <button type="button" class="dropdown-item kode-option"
                                            data-kode="${item.kode_pemeriksaan}"
                                            data-data-pemeriksaan="${item.data_pemeriksaan}"
                                            data-satuan="${item.satuan || ''}"
                                            data-rujukan="${item.rujukan || ''}"
                                            data-metode="${item.metode || ''}">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong>${item.kode_pemeriksaan}</strong>
                                                <div class="small text-muted">${item.data_pemeriksaan}</div>
                                            </div>
                                            <i class="ri-arrow-right-s-line"></i>
                                        </div>
                                    </button>
                                `);
                                $results.append($option);
                            });

                            // Tambahkan opsi "Clear mapping" jika sedang edit
                            if (currentKode) {
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
                            $results.html('<div class="dropdown-item text-center py-2 text-muted">Tidak ditemukan</div>');
                        }

                        $results.show();
                    },
                    error: function(xhr) {
                        $results.html('<div class="dropdown-item text-center py-2 text-danger">Error loading data</div>').show();
                    }
                });
            }, 500));
        }

        // Real-time search untuk kode pemeriksaan
        $(document).on('input', '.kode-search-input, .kode-edit-input', function() {
            handleKodeSearch($(this));
        });

        // Pilih kode pemeriksaan
        $(document).on('click', '.kode-option', function() {
            const $option = $(this);
            const kode = $option.data('kode');
            const dataPemeriksaan = $option.data('data-pemeriksaan');
            const satuan = $option.data('satuan');
            const rujukan = $option.data('rujukan');
            const metode = $option.data('metode');

            console.log('Kode option selected:', {
                kode: kode,
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
                    kode_pemeriksaan: kode,
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
                        updateKodeDisplay($row, kode, dataPemeriksaan, true);

                        // Update satuan dan rujukan display
                        $row.find('.satuan-display').text(satuan || '-');
                        $row.find('.rujukan-display').text(rujukan || '-');
                        $row.find('.method-cell').text(metode || '-');

                        // UPDATE PENTING: Set data-rujukan pada input hasil dari data_pemeriksaan
                        const $hasilInput = $row.find('.hasil-input');
                        $hasilInput.data('rujukan', rujukan);
                        $hasilInput.attr('data-rujukan', rujukan);

                        console.log('Updated rujukan on hasil-input:', {
                            id: $hasilInput.data('id'),
                            rujukan: rujukan
                        });

                        // Jika sudah ada nilai hasil, hitung ulang keterangan dan simpan ke database
                        if ($hasilInput.val()) {
                            // Panggil fungsi updateKeteranganClientSide dari global scope
                            if (typeof updateKeteranganClientSide === 'function') {
                                updateKeteranganClientSide($hasilInput);

                                // Ambil keterangan terbaru setelah dihitung
                                const $hiddenInput = $row.find('input[name*="[keterangan]"]');
                                const keterangan = $hiddenInput.val();

                                // Langsung simpan ke database
                                if (keterangan) {
                                    $.ajax({
                                        url: '{{ route("hasil-lab.update-field-ajax") }}',
                                        method: 'POST',
                                        data: {
                                            _token: csrfToken,
                                            type: 'kimia',
                                            id: $hasilInput.data('id'),
                                            field: 'hasil_pengujian',
                                            value: $hasilInput.val(),
                                            keterangan: keterangan
                                        },
                                        success: function(resp) {
                                            console.log('Keterangan updated after kode mapping:', resp);
                                        }
                                    });
                                }
                            }
                        }

                        // Gunakan showToast dari global scope
                        if (typeof showToast === 'function') {
                            showToast('success', 'Kode pemeriksaan berhasil dipetakan');
                        }

                        setTimeout(() => {
                            $row.removeClass('table-success');
                        }, 2000);
                    } else {
                        if (typeof showToast === 'function') {
                            showToast('danger', response.message || 'Gagal memetakan kode pemeriksaan');
                        }
                        $row.removeClass('table-warning');
                        $input.val('');
                    }
                },
                error: function(xhr) {
                    console.error('Update error:', xhr);
                    $row.removeClass('table-warning');

                    let errorMessage = 'Gagal memetakan kode pemeriksaan';
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
<script>
    $(document).ready(function() {
        const csrfToken = $('meta[name="csrf-token"]').attr('content') || $('#csrf_token').val();

        console.log('HasilLain script initialized, CSRF:', csrfToken ? 'Found' : 'Not found');

        // Queue system khusus untuk hasil pemeriksaan lain
        let hasilLainQueue = [];
        let isProcessingHasilLainQueue = false;

        // Variabel untuk input aktif
        let activeSearchInput = null;

        // Function untuk menambah request ke queue - KHUSUS HASIL LAIN
        function addToQueueHasilPemeriksaanLain(type, id, field, value, $element) {
            if (!id) {
                console.error('addToQueueHasilPemeriksaanLain: ID tidak valid', id);
                return;
            }

            const $row = $element.closest('tr');
            const $keteranganDisplay = $row.find('.keterangan-display');
            const $hiddenInput = $row.find('input[name*="[keterangan]"]');

            // Ambil keterangan dari display
            let keterangan = $hiddenInput.val();
            if (!keterangan || keterangan === '' || keterangan === '-') {
                keterangan = $keteranganDisplay.data('keterangan') || '-';
            }

            console.log('addToQueueHasilPemeriksaanLain - Adding to queue:', {
                type: type,
                id: id,
                field: field,
                value: value,
                keterangan: keterangan
            });

            hasilLainQueue.push({
                type: type,
                id: id,
                field: field,
                value: value,
                $element: $element,
                keterangan: keterangan,
                timestamp: Date.now()
            });

            if (!isProcessingHasilLainQueue) {
                processHasilLainQueue();
            }
        }

        // Function untuk memproses queue - KHUSUS HASIL LAIN
        function processHasilLainQueue() {
            if (hasilLainQueue.length === 0 || isProcessingHasilLainQueue) {
                return;
            }

            isProcessingHasilLainQueue = true;
            const request = hasilLainQueue.shift();

            if (!request.$element || !request.$element.length) {
                isProcessingHasilLainQueue = false;
                processHasilLainQueue();
                return;
            }

            console.log('processHasilLainQueue - Processing:', {
                id: request.id,
                field: request.field,
                value: request.value,
                keterangan: request.keterangan
            });

            const postData = {
                _token: csrfToken,
                type: request.type,
                id: request.id,
                field: request.field,
                value: request.value,
                keterangan: request.keterangan
            };

            $.ajax({
                url: '{{ route("hasil-lab.update-field-ajax") }}',
                method: 'POST',
                data: postData,
                beforeSend: function() {
                    request.$element.addClass('is-changing');
                },
                success: function(response) {
                    console.log('processHasilLainQueue - Success:', response);
                    if (response.success) {
                        request.$element.removeClass('is-changing').addClass('is-changed');
                        request.$element.data('original', request.value);

                        // Update save status jika ada
                        if (typeof updateSaveStatus === 'function') {
                            updateSaveStatus();
                        }

                        if (response.data.updated_at) {
                            $('#lastSaved').text(`Terakhir disimpan: ${response.data.updated_at}`);
                        }

                        request.$element.closest('td').addClass('table-success');
                        setTimeout(() => {
                            request.$element.closest('td').removeClass('table-success');
                        }, 1000);

                        if (typeof window.showToast === 'function') {
                            window.showToast('success', 'Data berhasil disimpan');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    console.error('processHasilLainQueue - Error:', {
                        status: status,
                        error: error,
                        response: xhr.responseText
                    });
                    request.$element.removeClass('is-changing').addClass('has-error');

                    let errorMessage = 'Gagal menyimpan perubahan';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }

                    if (xhr.status === 419) {
                        errorMessage = 'Session expired. Silakan refresh halaman.';
                    }

                    if (typeof window.showToast === 'function') {
                        window.showToast('danger', errorMessage);
                    }
                },
                complete: function() {
                    isProcessingHasilLainQueue = false;
                    setTimeout(processHasilLainQueue, 100);
                }
            });
        }

        // Function untuk update UI setelah pemilihan kode
        function updateHasilLainUI($row, kode, dataPemeriksaan, satuan, rujukan) {
            console.log('updateHasilLainUI called:', {
                kode,
                dataPemeriksaan,
                satuan,
                rujukan
            });

            // Update display
            $row.find('input[name*="[jenis_pengujian]"]').val(dataPemeriksaan);
            $row.find('.satuan-display').text(satuan || '-');
            $row.find('.rujukan-display').text(rujukan || '-');

            // Update input hidden
            $row.find('.kode-pemeriksaan-input').val(kode);

            // Update data-rujukan pada input hasil
            const $hasilInput = $row.find('.hasil-input-lain');
            $hasilInput.data('rujukan', rujukan);
            $hasilInput.attr('data-rujukan', rujukan);

            console.log('HasilLain UI updated - Rujukan:', rujukan);

            // Jika ada nilai hasil, hitung ulang keterangan
            if ($hasilInput.val()) {
                if (typeof window.updateKeteranganClientSide === 'function') {
                    console.log('Calling updateKeteranganClientSide for hasil input');
                    window.updateKeteranganClientSide($hasilInput);
                }
            }
        }

        // Function untuk memposisikan dropdown global
        function positionDropdown($input) {
            const inputRect = $input[0].getBoundingClientRect();
            const $dropdown = $('#globalSearchResults');

            // Atur posisi dropdown di bawah input
            const top = inputRect.bottom + 5;
            const left = inputRect.left;
            const width = Math.max(inputRect.width, 500);

            $dropdown.css({
                'top': top + 'px',
                'left': left + 'px',
                'width': width + 'px'
            });
        }

        // Search kode pemeriksaan (hasil lain) dengan dropdown global
        $(document).on('input', '.kode-search-input-lain, .kode-edit-input-lain', function() {
            const $input = $(this);
            const searchTerm = $input.val().trim();
            const $dropdown = $('#globalSearchResults');
            const jenisPemeriksaan = $input.data('jenis-pemeriksaan');
            const currentKode = $input.data('current-kode');

            // Simpan referensi input aktif
            activeSearchInput = $input;

            console.log('Search kode pemeriksaan:', {
                searchTerm: searchTerm,
                jenisPemeriksaan: jenisPemeriksaan
            });

            if (searchTerm.length < 2) {
                $dropdown.hide().empty();
                return;
            }

            clearTimeout($input.data('searchTimer'));
            $input.data('searchTimer', setTimeout(() => {
                console.log('Sending AJAX search request');
                $.ajax({
                    url: '{{ route("hasil-lain.search-kode-pemeriksaan") }}',
                    method: 'POST',
                    data: {
                        _token: csrfToken,
                        search: searchTerm,
                        jenis_pemeriksaan: jenisPemeriksaan
                    },
                    beforeSend: function() {
                        $dropdown.html(
                            '<div class="dropdown-header">' +
                            '<i class="ri-loader-4-line spin me-2"></i> Mencari...' +
                            '</div>'
                        ).show();
                        positionDropdown($input);
                    },
                    success: function(response) {
                        console.log('Search response received:', response);
                        $dropdown.empty();

                        if (response.success && response.data && response.data.length > 0) {
                            console.log('Found', response.data.length, 'results');

                            // Header dengan tombol close
                            const $header = $(`
                            <div class="dropdown-header d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>Hasil Pencarian</strong>
                                    <div class="small text-muted">${response.data.length} hasil ditemukan</div>
                                </div>
                                <button type="button" class="btn-close" aria-label="Close"></button>
                            </div>
                        `);
                            $dropdown.append($header);

                            // Hasil pencarian
                            response.data.forEach(function(item) {
                                const $option = $(`
                                <button type="button" class="kode-option-hasil-lain"
                                        data-kode="${item.kode_pemeriksaan}"
                                        data-data-pemeriksaan="${item.data_pemeriksaan}"
                                        data-satuan="${item.satuan || ''}"
                                        data-rujukan="${item.rujukan || ''}">
                                    <div class="d-flex">
                                        <div class="kode-badge">${item.kode_pemeriksaan}</div>
                                        <div class="flex-grow-1 ms-2">
                                            <div class="fw-bold">${item.data_pemeriksaan}</div>
                                            <div class="small text-muted">${item.jenis_pemeriksaan || 'Umum'}</div>
                                        </div>
                                        ${item.rujukan ? `<div class="rujukan-badge">${item.rujukan}</div>` : ''}
                                    </div>
                                </button>
                            `);
                                $dropdown.append($option);
                            });

                            // Opsi clear mapping jika ada current kode
                            if (currentKode) {
                                const $clearOption = $(`
                                <button type="button" class="kode-option-hasil-lain text-danger clear-mapping-option"
                                        data-kimia-id="${$input.data('row-id')}">
                                    <i class="ri-close-line me-2"></i>
                                    Hapus mapping kode ini
                                </button>
                            `);
                                $dropdown.append($clearOption);
                            }
                        } else {
                            $dropdown.html(`
                            <div class="dropdown-header">
                                Tidak ditemukan
                            </div>
                            <div class="p-3 text-center text-muted">
                                <i class="ri-search-line display-6 mb-2"></i>
                                <div>Tidak ditemukan kode pemeriksaan</div>
                                <small class="text-muted">Coba dengan kata kunci lain</small>
                            </div>
                        `);
                        }

                        $dropdown.show();
                    },
                    error: function(xhr, status, error) {
                        console.error('Search AJAX error:', {
                            status: status,
                            error: error,
                            response: xhr.responseText
                        });
                        $dropdown.html(`
                        <div class="dropdown-header text-danger">
                            <i class="ri-error-warning-line me-2"></i>
                            Error loading data
                        </div>
                    `).show();
                    }
                });
            }, 500));
        });

        // Pilih kode pemeriksaan (hasil lain) dari dropdown global
        $(document).on('click', '.kode-option-hasil-lain:not(.clear-mapping-option)', function() {
            if (!activeSearchInput) return;

            const $option = $(this);
            const kode = $option.data('kode');
            const dataPemeriksaan = $option.data('data-pemeriksaan');
            const satuan = $option.data('satuan');
            const rujukan = $option.data('rujukan');

            const $input = activeSearchInput;
            const $row = $input.closest('tr');

            console.log('Kode option selected:', {
                kode,
                dataPemeriksaan,
                satuan,
                rujukan
            });

            // Sembunyikan dropdown
            $('#globalSearchResults').hide().empty();

            // Update input
            $input.val(dataPemeriksaan);

            // Update UI
            updateHasilLainUI($row, kode, dataPemeriksaan, satuan, rujukan);

            // Simpan ke database
            const id = $row.data('id');
            const hasilPengujian = $row.find('.hasil-input-lain').val();

            console.log('Processing kode selection - Row ID:', id, 'Hasil:', hasilPengujian);

            if (id && id !== '') {
                // Update existing
                console.log('Updating existing record ID:', id);
                $.ajax({
                    url: '{{ url("hasil-lain") }}/' + id + '/update-kode',
                    method: 'POST',
                    data: {
                        _token: csrfToken,
                        kode_pemeriksaan: kode,
                        jenis_pengujian: dataPemeriksaan
                    },
                    success: function(response) {
                        console.log('Update kode response:', response);
                        if (response.success) {
                            if (typeof window.showToast === 'function') {
                                window.showToast('success', 'Kode pemeriksaan berhasil diperbarui');
                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Update kode error:', {
                            status: status,
                            error: error,
                            response: xhr.responseText
                        });
                        if (typeof window.showToast === 'function') {
                            window.showToast('danger', 'Gagal memperbarui kode pemeriksaan');
                        }
                    }
                });
            } else if (hasilPengujian && hasilPengujian.trim() !== '') {
                // Simpan baru jika sudah ada hasil
                console.log('Saving new record with hasil');
                simpanRowBaru($row, kode, dataPemeriksaan, satuan, rujukan);
            } else {
                // Hanya update UI, tunggu user isi hasil
                console.log('Only updating UI, waiting for hasil input');
                if (typeof window.showToast === 'function') {
                    window.showToast('info', 'Pilih kode berhasil. Silakan isi hasil pengujian.');
                }
            }

            activeSearchInput = null;
        });

        // Clear mapping option
        $(document).on('click', '.clear-mapping-option', function() {
            if (!activeSearchInput) return;

            const kimiaId = $(this).data('kimia-id');
            const $input = activeSearchInput;
            const $row = $input.closest('tr');

            if (confirm('Yakin ingin menghapus mapping kode ini?')) {
                // Reset UI
                $input.val('');
                $input.removeData('current-kode');
                $row.find('.kode-pemeriksaan-input').val('');
                $row.find('.satuan-display').text('-');
                $row.find('.rujukan-display').text('-');

                // Reset rujukan pada input hasil
                $row.find('.hasil-input-lain')
                    .removeData('rujukan')
                    .removeAttr('data-rujukan');

                // Update keterangan
                const $keteranganDisplay = $row.find('.keterangan-display');
                const $keteranganHidden = $row.find('input[name*="[keterangan]"]');
                $keteranganHidden.val('-');

                if (typeof window.updateKeteranganDisplay === 'function') {
                    window.updateKeteranganDisplay($keteranganDisplay, '-');
                }

                // Kirim ke server jika ada ID
                const id = $row.data('id');
                if (id) {
                    $.ajax({
                        url: '{{ url("hasil-lain") }}/' + id + '/reset-kode',
                        method: 'POST',
                        data: {
                            _token: csrfToken
                        },
                        success: function(response) {
                            console.log('Reset kode response:', response);
                            if (response.success && typeof window.showToast === 'function') {
                                window.showToast('success', 'Mapping berhasil direset');
                            }
                        },
                        error: function(xhr) {
                            console.error('Reset kode error:', xhr.responseText);
                            if (typeof window.showToast === 'function') {
                                window.showToast('danger', 'Gagal mereset mapping');
                            }
                        }
                    });
                }

                $('#globalSearchResults').hide();
                activeSearchInput = null;
            }
        });

        // Tombol close di header dropdown
        $(document).on('click', '#globalSearchResults .btn-close', function() {
            $('#globalSearchResults').hide();
            activeSearchInput = null;
        });

        // Input hasil (hasil lain)
        $(document).on('input', '.hasil-input-lain', function() {
            const $input = $(this);
            const $row = $input.closest('tr');
            const id = $input.data('id');
            const value = $input.val();
            const kodePemeriksaan = $row.find('.kode-pemeriksaan-input').val();

            console.log('Hasil input changed:', {
                id: id,
                value: value,
                kodePemeriksaan: kodePemeriksaan
            });

            // Update keterangan client-side
            if (typeof window.updateKeteranganClientSide === 'function') {
                console.log('Calling updateKeteranganClientSide');
                window.updateKeteranganClientSide($input);
            }

            // Debounce untuk AJAX call
            clearTimeout($input.data('timer'));
            $input.data('timer', setTimeout(() => {
                if (id) {
                    // Sudah ada di database → UPDATE via QUEUE KHUSUS
                    console.log('Row has ID, calling addToQueueHasilPemeriksaanLain for update');
                    addToQueueHasilPemeriksaanLain('hasil_lain', id, 'hasil_pengujian', value, $input);
                } else if (value && value.trim() !== '' && kodePemeriksaan) {
                    // Belum ada di database tapi sudah ada kode → STORE
                    console.log('New row with kode, saving');
                    simpanRowBaru($row);
                } else if (value && value.trim() !== '' && !kodePemeriksaan) {
                    // Sudah ada hasil tapi belum pilih kode
                    console.log('Has hasil but no kode, asking for kode');
                    if (typeof window.showToast === 'function') {
                        window.showToast('warning', 'Pilih kode pemeriksaan terlebih dahulu');
                    }
                    $row.find('.kode-search-input-lain').focus();
                }
            }, 800));
        });

        // Simpan row baru
        function simpanRowBaru($row, kode = null, dataPemeriksaan = null, satuan = null, rujukan = null) {
            console.log('simpanRowBaru called for row:', $row.data());

            // Gunakan parameter jika diberikan, jika tidak ambil dari form
            const kodePemeriksaan = kode || $row.find('.kode-pemeriksaan-input').val();
            const jenisPengujian = dataPemeriksaan || $row.find('input[name*="[jenis_pengujian]"]').val();
            const hasilPengujian = $row.find('.hasil-input-lain').val();
            const satuanValue = satuan || $row.find('.satuan-display').text();
            const rujukanValue = rujukan || $row.find('.rujukan-display').text();

            console.log('Data to save:', {
                kodePemeriksaan,
                jenisPengujian,
                hasilPengujian,
                satuanValue,
                rujukanValue
            });

            if (!kodePemeriksaan) {
                console.warn('No kode pemeriksaan selected');
                if (typeof window.showToast === 'function') {
                    window.showToast('warning', 'Pilih kode pemeriksaan terlebih dahulu');
                }
                return;
            }

            // Tampilkan loading
            $row.addClass('table-warning');

            $.ajax({
                url: '{{ route("hasil-lain.store") }}',
                method: 'POST',
                data: {
                    _token: csrfToken,
                    no_lab: '{{ $pasien->no_lab }}',
                    kode_pemeriksaan: kodePemeriksaan,
                    jenis_pengujian: jenisPengujian,
                    hasil_pengujian: hasilPengujian,
                    satuan: satuanValue,
                    rujukan: rujukanValue
                },
                success: function(response) {
                    console.log('Store response:', response);
                    $row.removeClass('table-warning');

                    if (response.success) {
                        // Update row dengan ID baru
                        const newId = response.data.id_hasil_lain;
                        $row.data('id', newId);
                        $row.attr('data-id', newId);
                        $row.find('input[name*="[id]"]').val(newId);
                        $row.find('.hasil-input-lain').data('id', newId);

                        // Update UI
                        $row.find('.satuan-display').text(response.data.satuan || '-');
                        $row.find('.rujukan-display').text(response.data.rujukan || '-');

                        // Update keterangan
                        const $keteranganDisplay = $row.find('.keterangan-display');
                        const $keteranganHidden = $row.find('input[name*="[keterangan]"]');
                        $keteranganHidden.val(response.data.keterangan || '-');

                        if (typeof window.updateKeteranganDisplay === 'function') {
                            window.updateKeteranganDisplay($keteranganDisplay, response.data.keterangan || '-');
                        }

                        // Update data-rujukan
                        const $hasilInput = $row.find('.hasil-input-lain');
                        $hasilInput.data('rujukan', response.data.rujukan);
                        $hasilInput.attr('data-rujukan', response.data.rujukan);

                        console.log('Row saved with ID:', newId);

                        if (typeof window.showToast === 'function') {
                            window.showToast('success', 'Pemeriksaan berhasil disimpan');
                        }
                    } else {
                        console.error('Store failed:', response.message);
                        if (typeof window.showToast === 'function') {
                            window.showToast('danger', response.message || 'Gagal menyimpan');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Store AJAX error:', {
                        status: status,
                        error: error,
                        response: xhr.responseText
                    });
                    $row.removeClass('table-warning');

                    let errorMessage = 'Gagal menyimpan pemeriksaan';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }

                    if (typeof window.showToast === 'function') {
                        window.showToast('danger', errorMessage);
                    }
                }
            });
        }

        // Tombol tambah row
        $(document).on('click', '.tambah-row-btn', function(e) {
            e.preventDefault();
            const jenisPemeriksaan = $(this).data('jenis-pemeriksaan');
            const $section = $(`.pemeriksaan-lain-section[data-jenis-pemeriksaan="${jenisPemeriksaan}"]`);

            console.log('Adding new row for jenis:', jenisPemeriksaan);

            // Hitung row index
            const rowCount = $section.find('tbody tr').length;
            const index = rowCount;

            const newRow = `
            <tr data-index="${index}" data-jenis-pemeriksaan="${jenisPemeriksaan}" data-is-new="true">
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
                               name="hasil_lain[${jenisPemeriksaan}][${index}][kode_pemeriksaan]"
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
            </tr>
        `;

            $section.find('tbody').append(newRow);

            console.log('New row added at index:', index);

            // Focus ke input kode
            setTimeout(() => {
                $section.find('tbody tr:last-child .kode-search-input-lain').focus();
            }, 100);
        });

        // Tombol hapus row
        $(document).on('click', '.hapus-row-btn', function(e) {
            e.preventDefault();
            const $row = $(this).closest('tr');
            const id = $row.data('id');
            const jenisPemeriksaan = $row.data('jenis-pemeriksaan');

            console.log('Delete row clicked:', {
                id,
                jenisPemeriksaan
            });

            if (confirm('Yakin ingin menghapus pemeriksaan ini?')) {
                if (id && id !== '') {
                    // Hapus dari database
                    console.log('Deleting from database ID:', id);
                    $.ajax({
                        url: '{{ url("hasil-lain") }}/' + id,
                        method: 'DELETE',
                        data: {
                            _token: csrfToken
                        },
                        success: function(response) {
                            console.log('Delete response:', response);
                            if (response.success) {
                                $row.remove();
                                updateRowIndices(jenisPemeriksaan);
                                if (typeof window.showToast === 'function') {
                                    window.showToast('success', 'Pemeriksaan berhasil dihapus');
                                }
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Delete error:', {
                                status: status,
                                error: error,
                                response: xhr.responseText
                            });
                            if (typeof window.showToast === 'function') {
                                window.showToast('danger', 'Gagal menghapus pemeriksaan');
                            }
                        }
                    });
                } else {
                    // Hapus langsung jika belum tersimpan
                    console.log('Deleting unsaved row');
                    $row.remove();
                    updateRowIndices(jenisPemeriksaan);
                    if (typeof window.showToast === 'function') {
                        window.showToast('info', 'Pemeriksaan dihapus');
                    }
                }
            }
        });

        // Tombol hapus tabel
        $(document).on('click', '.hapus-tabel-btn', function(e) {
            e.preventDefault();
            const jenisPemeriksaan = $(this).data('jenis-pemeriksaan');
            const $section = $(`.pemeriksaan-lain-section[data-jenis-pemeriksaan="${jenisPemeriksaan}"]`);

            console.log('Delete table clicked for:', jenisPemeriksaan);

            if (confirm(`Yakin ingin menghapus seluruh tabel ${jenisPemeriksaan}?`)) {
                // Kumpulkan semua ID yang ada di tabel
                const ids = [];
                $section.find('tr[data-id]').each(function() {
                    const id = $(this).data('id');
                    if (id && id !== '') ids.push(id);
                });

                console.log('IDs to delete:', ids);

                if (ids.length > 0) {
                    // Hapus dari database
                    $.ajax({
                        url: '{{ route("hasil-lain.destroy-multiple") }}',
                        method: 'POST',
                        data: {
                            _token: csrfToken,
                            ids: ids
                        },
                        success: function(response) {
                            console.log('Delete multiple response:', response);
                            if (response.success) {
                                $section.remove();
                                if (typeof window.showToast === 'function') {
                                    window.showToast('success', `Tabel ${jenisPemeriksaan} berhasil dihapus`);
                                }
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Delete multiple error:', {
                                status: status,
                                error: error,
                                response: xhr.responseText
                            });
                            if (typeof window.showToast === 'function') {
                                window.showToast('danger', 'Gagal menghapus tabel');
                            }
                        }
                    });
                } else {
                    // Hapus langsung jika tidak ada data tersimpan
                    console.log('No saved data, removing table directly');
                    $section.remove();
                    if (typeof window.showToast === 'function') {
                        window.showToast('success', `Tabel ${jenisPemeriksaan} dihapus`);
                    }
                }
            }
        });

        // Tombol tambah tabel baru
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
                        <button type="button" class="btn btn-sm btn-outline-success tambah-modal-btn ms-2"
                                data-jenis-pemeriksaan="${jenisPemeriksaan}">
                            <i class="ri-checkbox-multiple-line me-1"></i>Pilih dari Daftar
                        </button>

                        <button type="button" class="btn btn-sm btn-outline-danger hapus-tabel-btn ms-2"
                                data-jenis-pemeriksaan="${jenisPemeriksaan}">
                            <i class="ri-delete-bin-line me-1"></i>Hapus Tabel
                        </button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm pemeriksaan-lain-table">
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

        // Function untuk update row indices
        function updateRowIndices(jenisPemeriksaan) {
            console.log('Updating row indices for jenis:', jenisPemeriksaan);
            const $rows = $(`.pemeriksaan-lain-section[data-jenis-pemeriksaan="${jenisPemeriksaan}"] tbody tr`);
            $rows.each(function(index) {
                const $row = $(this);
                $row.data('index', index);

                // Update name attributes
                const oldPrefix = `hasil_lain[${jenisPemeriksaan}][${$row.data('old-index') || 0}]`;
                const newPrefix = `hasil_lain[${jenisPemeriksaan}][${index}]`;

                $row.find('input, select').each(function() {
                    const $input = $(this);
                    const name = $input.attr('name');
                    if (name && name.includes(oldPrefix)) {
                        $input.attr('name', name.replace(oldPrefix, newPrefix));
                    }
                });
            });
        }

        // Sembunyikan dropdown saat klik di luar
        $(document).on('click', function(e) {
            if (!$(e.target).closest('#globalSearchResults').length &&
                !$(e.target).closest('.kode-search-input-lain, .kode-edit-input-lain').length) {
                $('#globalSearchResults').hide();
                activeSearchInput = null;
            }
        });

        // Update posisi dropdown saat scroll
        $(window).on('scroll resize', function() {
            if (activeSearchInput && $('#globalSearchResults').is(':visible')) {
                positionDropdown(activeSearchInput);
            }
        });

        // Inisialisasi data rujukan untuk hasil_lain yang sudah ada
        $('.hasil-input-lain').each(function() {
            const $input = $(this);
            const $row = $input.closest('tr');
            const rujukan = $row.find('.rujukan-display').text().trim();

            if (rujukan && rujukan !== '-') {
                $input.data('rujukan', rujukan);
                $input.attr('data-rujukan', rujukan);
                console.log('Initialized rujukan for input:', rujukan);
            }
        });

        // Log semua existing rows pada load
        console.log('Initializing existing rows:');
        $('.hasil-input-lain').each(function(index) {
            const $input = $(this);
            const $row = $input.closest('tr');
            console.log(`Row ${index}:`, {
                id: $input.data('id'),
                rujukan: $input.data('rujukan'),
                value: $input.val(),
                rowId: $row.data('id')
            });
        });

        // Tambahkan update save status function jika tidak ada
        if (typeof updateSaveStatus === 'undefined') {
            window.updateSaveStatus = function() {
                const $status = $('#saveStatus');
                const changedCount = $('.is-changed').length;

                if (changedCount > 0) {
                    $status.removeClass('bg-secondary').addClass('bg-warning');
                    $status.html(`<i class="ri-edit-line me-1"></i>${changedCount} perubahan`);
                } else {
                    $status.removeClass('bg-warning').addClass('bg-secondary');
                    $status.html('<i class="ri-check-line me-1"></i>Tersimpan');
                }
            };
        }
    });
</script>

<script>
    $(document).ready(function() {
        // Setup CSRF token untuk AJAX
        const csrfToken = $('#csrf_token').val();

        // ============================================
        // HEMATOLOGY REAL-TIME UPDATES - QUEUE SISTEM KHUSUS
        // ============================================

        // Queue system khusus untuk Hematology
        let hematologyQueue = [];
        let isProcessingHematologyQueue = false;

        // Function untuk update keterangan Hematology
        window.updateHematologyKeterangan = function($input) {
            const hasil = $input.val();
            const rujukan = $input.data('rujukan') || $input.attr('data-rujukan') || '';
            const $row = $input.closest('tr');
            const $keteranganDisplay = $row.find('.keterangan-display');
            const $hiddenInput = $row.find('input[name*="[keterangan]"]');

            console.log('=== HEMATOLOGY - updateKeterangan ===');
            console.log('Hasil:', hasil);
            console.log('Rujukan:', rujukan);

            // Clear jika kosong
            if (!hasil || hasil.trim() === '') {
                console.log('Hasil kosong, reset keterangan');
                if (typeof updateKeteranganDisplay === 'function') {
                    updateKeteranganDisplay($keteranganDisplay, '');
                }
                $hiddenInput.val('');
                return;
            }

            if (!rujukan || rujukan.trim() === '' || rujukan.trim() === '-') {
                console.log('Rujukan tidak tersedia atau "-"');
                if (typeof updateKeteranganDisplay === 'function') {
                    updateKeteranganDisplay($keteranganDisplay, '-');
                }
                $hiddenInput.val('-');
                return;
            }

            try {
                const rujukanStr = rujukan.toString().trim();
                const hasilStr = hasil.toString().trim();
                const hasilNum = parseFloat(hasilStr.replace(',', '.'));

                console.log('=== HEMATOLOGY - Data untuk perhitungan ===');
                console.log('Rujukan string:', rujukanStr);
                console.log('Hasil string:', hasilStr);
                console.log('Hasil number:', hasilNum);

                // Jika bukan angka, cek kualitatif
                if (isNaN(hasilNum)) {
                    const hasilLower = hasilStr.toLowerCase();
                    const rujukanLower = rujukanStr.toLowerCase();

                    if (rujukanLower.includes('negative') || rujukanLower.includes('negatif')) {
                        if (hasilLower.includes('negative') || hasilLower.includes('negatif') ||
                            hasilLower.includes('non-reactive') || hasilLower.includes('nonreactive')) {
                            if (typeof updateKeteranganDisplay === 'function') {
                                updateKeteranganDisplay($keteranganDisplay, '-');
                            }
                            $hiddenInput.val('-');
                        } else {
                            if (typeof updateKeteranganDisplay === 'function') {
                                updateKeteranganDisplay($keteranganDisplay, 'H');
                            }
                            $hiddenInput.val('H');
                        }
                        return;
                    } else if (rujukanLower.includes('positive') || rujukanLower.includes('positif')) {
                        if (hasilLower.includes('positive') || hasilLower.includes('positif') ||
                            hasilLower.includes('reactive') || hasilLower.includes('reaktif')) {
                            if (typeof updateKeteranganDisplay === 'function') {
                                updateKeteranganDisplay($keteranganDisplay, '-');
                            }
                            $hiddenInput.val('-');
                        } else {
                            if (typeof updateKeteranganDisplay === 'function') {
                                updateKeteranganDisplay($keteranganDisplay, 'L');
                            }
                            $hiddenInput.val('L');
                        }
                        return;
                    } else {
                        if (typeof updateKeteranganDisplay === 'function') {
                            updateKeteranganDisplay($keteranganDisplay, '-');
                        }
                        $hiddenInput.val('-');
                    }
                    return;
                }

                // 1. FORMAT RANGE: "1 - 90" atau "1-90"
                if (rujukanStr.includes('-') && !rujukanStr.includes('<') && !rujukanStr.includes('>')) {
                    const parts = rujukanStr.split('-');
                    if (parts.length === 2) {
                        const min = parseFloat(parts[0].trim());
                        const max = parseFloat(parts[1].trim());

                        if (!isNaN(min) && !isNaN(max)) {
                            console.log('=== HEMATOLOGY - FORMAT RANGE ===');
                            console.log('Min:', min, 'Max:', max, 'Hasil:', hasilNum);

                            if (hasilNum < min) {
                                console.log(`Hasil (${hasilNum}) < Min (${min}) → L`);
                                if (typeof updateKeteranganDisplay === 'function') {
                                    updateKeteranganDisplay($keteranganDisplay, 'L');
                                }
                                $hiddenInput.val('L');
                            } else if (hasilNum > max) {
                                console.log(`Hasil (${hasilNum}) > Max (${max}) → H`);
                                if (typeof updateKeteranganDisplay === 'function') {
                                    updateKeteranganDisplay($keteranganDisplay, 'H');
                                }
                                $hiddenInput.val('H');
                            } else {
                                console.log(`Hasil (${hasilNum}) dalam range ${min}-${max} → -`);
                                if (typeof updateKeteranganDisplay === 'function') {
                                    updateKeteranganDisplay($keteranganDisplay, '-');
                                }
                                $hiddenInput.val('-');
                            }
                            return;
                        }
                    }
                }

                // 2. FORMAT "< 90" (Normal di ATAS 90)
                if (rujukanStr.startsWith('<')) {
                    const batas = parseFloat(rujukanStr.replace('<', '').trim());
                    if (!isNaN(batas)) {
                        console.log('=== HEMATOLOGY - FORMAT "< batas" ===');
                        console.log('Batas:', batas, 'Hasil:', hasilNum);

                        if (hasilNum >= batas) {
                            console.log(`Hasil (${hasilNum}) ≥ Batas (${batas}) → - (Normal)`);
                            if (typeof updateKeteranganDisplay === 'function') {
                                updateKeteranganDisplay($keteranganDisplay, '-');
                            }
                            $hiddenInput.val('-');
                        } else {
                            console.log(`Hasil (${hasilNum}) < Batas (${batas}) → L (Low)`);
                            if (typeof updateKeteranganDisplay === 'function') {
                                updateKeteranganDisplay($keteranganDisplay, 'L');
                            }
                            $hiddenInput.val('L');
                        }
                        return;
                    }
                }

                // 3. FORMAT "> 90" (Normal di BAWAH 90)
                if (rujukanStr.startsWith('>')) {
                    const batas = parseFloat(rujukanStr.replace('>', '').trim());
                    if (!isNaN(batas)) {
                        console.log('=== HEMATOLOGY - FORMAT "> batas" ===');
                        console.log('Batas:', batas, 'Hasil:', hasilNum);

                        if (hasilNum <= batas) {
                            console.log(`Hasil (${hasilNum}) ≤ Batas (${batas}) → - (Normal)`);
                            if (typeof updateKeteranganDisplay === 'function') {
                                updateKeteranganDisplay($keteranganDisplay, '-');
                            }
                            $hiddenInput.val('-');
                        } else {
                            console.log(`Hasil (${hasilNum}) > Batas (${batas}) → H (High)`);
                            if (typeof updateKeteranganDisplay === 'function') {
                                updateKeteranganDisplay($keteranganDisplay, 'H');
                            }
                            $hiddenInput.val('H');
                        }
                        return;
                    }
                }

                // Default: tidak ada pola yang cocok
                console.log('=== HEMATOLOGY - Tidak ada pola yang cocok ===');
                if (typeof updateKeteranganDisplay === 'function') {
                    updateKeteranganDisplay($keteranganDisplay, '-');
                }
                $hiddenInput.val('-');

            } catch (e) {
                console.error('HEMATOLOGY - Error:', e);
                if (typeof updateKeteranganDisplay === 'function') {
                    updateKeteranganDisplay($keteranganDisplay, '-');
                }
                $hiddenInput.val('-');
            }
        };

        // Function untuk menambah request ke queue Hematology
        function addToHematologyQueue(type, id, field, value, $element) {
            if (!id) {
                console.error('addToHematologyQueue: ID tidak valid', id);
                return;
            }

            const $row = $element.closest('tr');
            const $keteranganDisplay = $row.find('.keterangan-display');
            const $hiddenInput = $row.find('input[name*="[keterangan]"]');

            // Ambil keterangan dari hidden input
            let keterangan = $hiddenInput.val();

            // Jika kosong, ambil dari display
            if (!keterangan || keterangan === '') {
                keterangan = $keteranganDisplay.data('keterangan') || '-';
            }

            console.log('addToHematologyQueue - Adding to queue:', {
                type: type,
                id: id,
                field: field,
                value: value,
                keterangan: keterangan
            });

            hematologyQueue.push({
                type: type,
                id: id,
                field: field,
                value: value,
                $element: $element,
                keterangan: keterangan,
                timestamp: Date.now()
            });

            if (!isProcessingHematologyQueue) {
                processHematologyQueue();
            }
        }

        // Function untuk memproses queue Hematology
        function processHematologyQueue() {
            if (hematologyQueue.length === 0 || isProcessingHematologyQueue) {
                return;
            }

            isProcessingHematologyQueue = true;
            const request = hematologyQueue.shift();

            if (!request.$element || !request.$element.length) {
                isProcessingHematologyQueue = false;
                processHematologyQueue();
                return;
            }

            console.log('processHematologyQueue - Processing:', {
                id: request.id,
                field: request.field,
                value: request.value,
                keterangan: request.keterangan
            });

            const postData = {
                _token: csrfToken,
                type: request.type,
                id: request.id,
                field: request.field,
                value: request.value,
                keterangan: request.keterangan
            };

            $.ajax({
                url: '{{ route("hasil-lab.update-field-ajax") }}',
                method: 'POST',
                data: postData,
                beforeSend: function() {
                    request.$element.addClass('is-changing');
                },
                success: function(response) {
                    console.log('processHematologyQueue - Success:', response);
                    if (response.success) {
                        request.$element.removeClass('is-changing').addClass('is-changed');
                        request.$element.data('original', request.value);

                        // Update save status jika ada
                        if (typeof updateSaveStatus === 'function') {
                            updateSaveStatus();
                        }

                        if (response.data.updated_at) {
                            $('#lastSaved').text(`Terakhir disimpan: ${response.data.updated_at}`);
                        }

                        request.$element.closest('td').addClass('table-success');
                        setTimeout(() => {
                            request.$element.closest('td').removeClass('table-success');
                        }, 1000);

                        if (typeof window.showToast === 'function') {
                            window.showToast('success', 'Data Hematology berhasil disimpan');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    console.error('processHematologyQueue - Error:', {
                        status: status,
                        error: error,
                        response: xhr.responseText
                    });
                    request.$element.removeClass('is-changing').addClass('has-error');

                    let errorMessage = 'Gagal menyimpan perubahan Hematology';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }

                    if (xhr.status === 419) {
                        errorMessage = 'Session expired. Silakan refresh halaman.';
                    }

                    if (typeof window.showToast === 'function') {
                        window.showToast('danger', errorMessage);
                    }
                },
                complete: function() {
                    isProcessingHematologyQueue = false;
                    setTimeout(processHematologyQueue, 100);
                }
            });
        }

        // Event handler khusus untuk input Hematology
        $(document).on('input', '.hasil-input[data-type="hematology"]', function() {
            const $input = $(this);
            const id = $input.data('id');
            const value = $input.val();

            console.log('====== HEMATOLOGY - INPUT BERUBAH ======');
            console.log('ID:', id, 'Type: hematology');
            console.log('Nilai baru:', value);

            // Update keterangan Hematology secara real-time
            updateHematologyKeterangan($input);

            // Debounce untuk AJAX call
            clearTimeout($input.data('timer'));
            $input.data('timer', setTimeout(() => {
                if (id) {
                    // Gunakan queue system khusus Hematology
                    addToHematologyQueue('hematology', id, 'hasil_pengujian', value, $input);
                }
            }, 800));
        });

        // Excel navigation khusus untuk Hematology
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

            // Excel-like keyboard navigation khusus untuk tabel Hematology
            $('.hasil-input[data-type="hematology"]').on('keydown', function(e) {
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
                            const $nextInput = $nextRow.find('td').eq(cellIndex).find('.hasil-input[data-type="hematology"]');
                            if ($nextInput.length) {
                                $nextInput.focus().select();
                            }
                        }
                        break;

                    case 'ArrowDown':
                        e.preventDefault();
                        const $downRow = $rows.eq(rowIndex + 1);
                        if ($downRow.length) {
                            const $downInput = $downRow.find('td').eq(cellIndex).find('.hasil-input[data-type="hematology"]');
                            if ($downInput.length) {
                                $downInput.focus().select();
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

            // Double click to clear
            $('.hasil-input[data-type="hematology"]').on('dblclick', function() {
                $(this).val('').trigger('input');
            });
        }

        // Function untuk save all perubahan Hematology
        $('#saveAllBtn').on('click', function() {
            // SAVE ALL KHUSUS HEMATOLOGY
            const $hematologyInputs = $('.hasil-input[data-type="hematology"]');
            const hematologyChanges = $hematologyInputs.filter('.is-changed');

            if (hematologyChanges.length > 0) {
                console.log('Menyimpan semua perubahan Hematology:', hematologyChanges.length);
            }
        });

        // Inisialisasi data rujukan untuk Hematology saat page load
        console.log('Initializing hematology rujukan data...');
        $('.hasil-input[data-type="hematology"]').each(function() {
            const $input = $(this);
            const $row = $input.closest('tr');
            const rujukanDisplay = $row.find('.rujukan-cell').text().trim();

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

        // Initialize Excel navigation untuk Hematology
        initHematologyExcelNavigation();

        // Auto-focus first input Hematology jika ada
        if ($('.hasil-input[data-type="hematology"]').length > 0) {
            $('.hasil-input[data-type="hematology"]').first().focus();
        }

        // Tambahkan update save status function jika tidak ada
        if (typeof updateSaveStatus === 'undefined') {
            window.updateSaveStatus = function() {
                const $status = $('#saveStatus');
                // Hitung semua perubahan dari semua tipe
                const hematologyChanges = $('.hasil-input[data-type="hematology"].is-changed').length;
                const kimiaChanges = $('.hasil-input[data-type="kimia"].is-changed').length;
                const hasilLainChanges = $('.hasil-input-lain.is-changed').length;

                const totalChanges = hematologyChanges + kimiaChanges + hasilLainChanges;

                if (totalChanges > 0) {
                    $status.removeClass('bg-secondary').addClass('bg-warning');
                    $status.html(`<i class="ri-edit-line me-1"></i>${totalChanges} perubahan`);
                } else {
                    $status.removeClass('bg-warning').addClass('bg-secondary');
                    $status.html('<i class="ri-check-line me-1"></i>Tersimpan');
                }
            };
        }
    });
</script>
<script>
    // ============================================
    // REALTIME UPDATE SYSTEM - SIMPLE VERSION
    // ============================================

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

            // Format untuk display: DD-MM-YYYY
            const displayFormat = `${day.padStart(2, '0')}-${month.padStart(2, '0')}-${yearNum}`;

            return {
                isValid: true,
                dbFormat: dateStr, // YYYY-MM-DD untuk database
                displayFormat: displayFormat, // DD-MM-YYYY untuk display
                dateObj: inputDate
            };
        }

        // ============================================
        // FUNGSI HITUNG UMUR
        // ============================================

        function calculateAge(birthDateStr) {
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

                // Format hasil
                let result = '';
                if (years > 0) result += years + ' Tahun ';
                if (months > 0 || years > 0) result += months + ' Bulan ';
                result += days + ' Hari';

                return {
                    success: true,
                    umur: result.trim(),
                    dbFormat: validation.dbFormat,
                    displayFormat: validation.displayFormat
                };

            } catch (e) {
                console.error('Error calculateAge:', e);
                return {
                    success: false,
                    umur: '',
                    error: 'Gagal menghitung umur'
                };
            }
        }

        // ============================================
        // FUNGSI FORMAT TANGGAL UNTUK DISPLAY (DD-MM-YYYY)
        // ============================================

        function formatDateForDisplay(dateStr) {
            if (!dateStr) return '';

            const regex = /^(\d{4})-(\d{2})-(\d{2})$/;
            const match = dateStr.match(regex);

            if (match) {
                const [, year, month, day] = match;
                return `${day}-${month}-${year}`;
            }

            return dateStr;
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

        // ============================================
        // EVENT HANDLERS - TANGGAL LAHIR (INPUT TYPE="date")
        // ============================================

        // Event saat tanggal berubah
        $(document).on('change', '.realtime-date', function() {
            const $this = $(this);
            const dateStr = $this.val(); // Format: YYYY-MM-DD

            console.log('📅 Date changed:', dateStr);

            if (!dateStr) {
                // Jika tanggal dihapus
                $('#display_umur').val('');
                $('[data-field="umur"]').val('');
                debounceSave('tgl_lahir', '', $this);
                return;
            }

            // Validasi tanggal
            const validation = validateDateInput(dateStr);

            if (!validation.isValid) {
                alert(validation.error);
                $this.val('').focus();
                return;
            }

            // Hitung umur
            const ageResult = calculateAge(dateStr);

            if (ageResult.success) {
                // Update umur display
                $('#display_umur').val(ageResult.umur);
                $('[data-field="umur"]').val(ageResult.umur);

                console.log('👶 Umur dihitung:', ageResult.umur);

                // Kirim tanggal lahir ke server
                debounceSave('tgl_lahir', dateStr, $this);

                // Juga kirim umur
                setTimeout(() => {
                    debounceSave('umur', ageResult.umur, $('[data-field="umur"]'));
                }, 100);

            } else {
                alert(ageResult.error);
                $this.val('').focus();
            }
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

        // TAMBAHKAN EVENT HANDLER UNTUK DATETIME INPUT
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

            // 1. Hitung umur jika ada tanggal lahir
            const $tglLahir = $('[data-field="tgl_lahir"]');
            const $displayUmur = $('#display_umur');
            const currentUmur = $displayUmur.val();
            const currentDate = $tglLahir.val();

            console.log('Tanggal lahir awal:', currentDate);
            console.log('Umur awal:', currentUmur);

            if (currentDate) {
                // Hitung ulang umur jika kosong atau tidak valid
                if (!currentUmur || currentUmur === '' || currentUmur === '0 Hari' || currentUmur.includes('-')) {
                    const ageResult = calculateAge(currentDate);
                    if (ageResult.success) {
                        $displayUmur.val(ageResult.umur);
                        $('[data-field="umur"]').val(ageResult.umur);
                        console.log('✅ Umur dihitung ulang:', ageResult.umur);
                    }
                }
            }

            // 2. Set original value untuk reset
            $('.realtime-field, .realtime-select, .realtime-date').each(function() {
                $(this).data('original', $(this).val());
            });

            console.log('✅ Inisialisasi selesai');

            // 3. Tampilkan semua field langsung
            // Field sudah ada nilainya dari server, jadi langsung muncul
        }

        // Jalankan setelah page load
        setTimeout(initializePage, 300);

        // ============================================
        // SAVE ALL FUNCTION
        // ============================================

        $('#saveAllBtn').on('click', function() {
            const $btn = $(this);
            const originalText = $btn.html();

            $btn.prop('disabled', true).html('<i class="ri-loader-4-line spin me-1"></i>Menyimpan...');

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
    });
</script>

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
                            const existingRow = currentTableSection.find(`.kode-pemeriksaan-input[value="${item.kode_pemeriksaan}"]`);
                            const isDisabled = existingRow.length > 0;

                            console.log(`Item ${index}:`, {
                                kode: item.kode_pemeriksaan,
                                nama: item.data_pemeriksaan,
                                sudah_ada: isDisabled
                            });

                            html += `
                                <tr class="pemeriksaan-item ${isDisabled ? 'table-light' : ''}"
                                    data-kode="${item.kode_pemeriksaan}"
                                    data-data-pemeriksaan="${item.data_pemeriksaan}"
                                    data-satuan="${item.satuan || ''}"
                                    data-rujukan="${item.rujukan || ''}"
                                    data-metode="${item.metode || ''}">
                                    <td class="text-center">
                                        <div class="form-check">
                                            <input type="checkbox"
                                                class="form-check-input pemeriksaan-checkbox"
                                                id="pemeriksaan_${index}"
                                                data-kode="${item.kode_pemeriksaan}"
                                                ${isDisabled ? 'disabled' : ''}>
                                            <label class="form-check-label" for="pemeriksaan_${index}"></label>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">${item.kode_pemeriksaan}</span>
                                    </td>
                                    <td>
                                        <div class="fw-medium">${item.data_pemeriksaan}</div>
                                        ${isDisabled ?
                                            '<small class="text-warning"><i class="ri-alert-line me-1"></i>Sudah ada di tabel</small>' :
                                            ''}
                                    </td>
                                    <td class="text-muted">${item.satuan || '-'}</td>
                                    <td class="text-muted small">${item.rujukan || '-'}</td>
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

            const newRow = `
            <tr data-index="${index}" data-jenis-pemeriksaan="${jenisPemeriksaan}" data-is-new="true">
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
                            name="hasil_lain[${jenisPemeriksaan}][${index}][kode_pemeriksaan]"
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
            </tr>
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
                                    name="hasil_lain[${currentJenisPemeriksaan}][${rowIndex}][kode_pemeriksaan]"
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
                        <button type="button" class="btn btn-sm btn-outline-success tambah-modal-btn ms-2"
                                data-jenis-pemeriksaan="${jenisPemeriksaan}">
                            <i class="ri-checkbox-multiple-line me-1"></i>Pilih dari Daftar
                        </button>

                        <button type="button" class="btn btn-sm btn-outline-danger hapus-tabel-btn ms-2"
                                data-jenis-pemeriksaan="${jenisPemeriksaan}">
                            <i class="ri-delete-bin-line me-1"></i>Hapus Tabel
                        </button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm pemeriksaan-lain-table">
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
@endsection

@extends('layouts_user.app')

@section('content')
<!-- App hero header starts -->
<div class="app-hero-header d-flex align-items-center">
    <!-- Breadcrumb starts -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <i class="ri-home-8-line lh-1 pe-3 me-3 border-end"></i>
            <a href="{{ url('user/dashboard') }}">Home</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('user.index') }}">Dashboard</a>
        </li>
        <li class="breadcrumb-item text-primary" aria-current="page">
            Hasil Pengujian - View Only
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
                        <div class="d-flex align-items-center">
                            <span class="badge bg-info me-2">View Only</span>
                            <span class="badge bg-secondary">No Edit</span>
                        </div>
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
                                    <label class="form-label">Nama Pasien</label>
                                    <div class="d-flex align-items-center">
                                        <i class="ri-user-line me-2 text-primary"></i>
                                        <span class="fs-6">{{ $pasien->nama_pasien ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xxl-3 col-lg-4 col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Alamat</label>
                                    <div class="d-flex align-items-center">
                                        <i class="ri-map-pin-line me-2 text-primary"></i>
                                        <span class="fs-6">{{ $pasien->alamat ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xxl-3 col-lg-4 col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Tanggal Lahir</label>
                                    <div class="d-flex align-items-center">
                                        <i class="ri-calendar-line me-2 text-primary"></i>
                                        <span class="fs-6">{{ $pasien->tgl_lahir ? \Carbon\Carbon::parse($pasien->tgl_lahir)->format('d/m/Y') : '-' }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xxl-3 col-lg-4 col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Umur</label>
                                    <div class="d-flex align-items-center">
                                        <i class="ri-time-line me-2 text-primary"></i>
                                        <span class="fs-6">{{ $pasien->umur ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xxl-3 col-lg-4 col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Pengirim</label>
                                    <div class="d-flex align-items-center">
                                        <i class="ri-user-settings-line me-2 text-primary"></i>
                                        <span class="fs-6">{{ $pasien->pengirim ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xxl-3 col-lg-4 col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted small mb-1">Penjamin</label>
                                    <div class="d-flex align-items-center">
                                        <i class="ri-shield-check-line me-2 text-primary"></i>
                                        <span class="fs-6">{{ $pasien->penjamin->nama_penjamin ?? ($pasien->nota ?? '-') }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xxl-3 col-lg-4 col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Asal Kunjungan</label>
                                    <div class="d-flex align-items-center">
                                        <i class="ri-building-line me-2 text-primary"></i>
                                        <span class="fs-6">{{ $pasien->ruangan->nama_ruangan ?? ($pasien->ket_klinik ?? '-') }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xxl-3 col-lg-4 col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Waktu Periksa</label>
                                    <div class="d-flex align-items-center">
                                        <i class="ri-time-line me-2 text-primary"></i>
                                        <span class="fs-6">{{ $pasien->created_at ? \Carbon\Carbon::parse($pasien->waktu_periksa)->format('d/m/Y H:i') : '-' }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xxl-3 col-lg-4 col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Waktu Validasi</label>
                                    <div class="d-flex align-items-center">
                                        <i class="ri-check-double-line me-2 text-primary"></i>
                                        <span class="fs-6">{{ $pasien->waktu_validasi ? \Carbon\Carbon::parse($pasien->waktu_validasi)->format('d/m/Y H:i') : '-' }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xxl-3 col-lg-4 col-sm-6">
                                <div class="mb-3">
                                    <label class="form-label">Validator</label>
                                    <div class="d-flex align-items-center">
                                        <i class="ri-user-check-line me-2 text-primary"></i>
                                        <span class="fs-6">{{ $pasien->pemeriksa->nama_pemeriksa ?? 'Belum divalidasi' }}</span>
                                    </div>
                                </div>
                            </div>
                            <!-- Data pasien display ends -->
                        </div>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title mb-0">Hasil Pengujian Laboratorium</h5>
                            <p class="card-subtitle text-muted mb-0">No. Lab: {{ $pasien->nomor_registrasi }} | Nama: {{ $pasien->nama_pasien }}</p>
                        </div>
                        <div>
                            <span class="badge bg-info">
                                <i class="ri-eye-line me-1"></i>View Only
                            </span>
                        </div>
                    </div>

                    <div class="card-body">
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
                                            <th width="15%" class="bg-light">Jenis Pengujian</th>
                                            <th width="15%">Hasil Pengujian</th>
                                            <th width="10%" class="bg-light">Satuan</th>
                                            <th width="15%" class="bg-light">Rujukan</th>
                                            <th width="10%" class="bg-light">CH</th>
                                            <th width="10%" class="bg-light">CL</th>
                                            <th width="15%">Keterangan</th>
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

                                        $ch_value = $item->dataPemeriksaan->ch ?? '-';
                                        $cl_value = $item->dataPemeriksaan->cl ?? '-';
                                        $rujukan_pemeriksaan = $item->dataPemeriksaan->rujukan ?? '-';

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

                                        <tr>
                                            <td class="bg-light">
                                                <strong>{{ $jenis }}</strong>
                                            </td>
                                            <td class="text-center">
                                                <span class="fw-medium">{{ $item->hasil_pengujian ?? '-' }}</span>
                                            </td>
                                            <td class="bg-light text-center">
                                                {{ $item->dataPemeriksaan->satuan ?? '-' }}
                                            </td>
                                            <td class="bg-light text-center">
                                                {{ $rujukan_pemeriksaan }}
                                            </td>
                                            <td class="bg-light text-center">
                                                {{ $ch_value }}
                                            </td>
                                            <td class="bg-light text-center">
                                                {{ $cl_value }}
                                            </td>
                                            <td class="text-center">
                                                <div class="keterangan-display {{ $bgColor }} {{ $textColor }} rounded py-1 px-2 d-inline-block"
                                                    style="min-width: 60px;">
                                                    <strong>{{ $textDisplay }}</strong>
                                                </div>
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
                                <table class="table table-bordered align-middle text-nowrap">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="20%" class="bg-light">Nama Dari Alat</th>
                                            <th width="20%">Nama Standar dari RS</th>
                                            <th width="15%" class="bg-light">Hasil</th>
                                            <th width="10%" class="bg-light">Satuan</th>
                                            <th width="15%" class="bg-light">Rujukan</th>
                                            <th width="5%" class="bg-light">CH</th>
                                            <th width="5%" class="bg-light">CL</th>
                                            <th width="10%">Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($kimia as $index => $item)
                                        @php
                                        $keterangan = $item->keterangan ?? '';
                                        $ch_value = $item->dataPemeriksaan->ch ?? '-';
                                        $cl_value = $item->dataPemeriksaan->cl ?? '-';

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
                                        <tr>
                                            <td class="bg-light">
                                                <strong>{{ $item->analysis ?? '-' }}</strong>
                                            </td>
                                            <td>
                                                <span class="text-dark">{{ $item->dataPemeriksaan->data_pemeriksaan ?? '-' }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="fw-medium">{{ $item->hasil_pengujian ?? '-' }}</span>
                                            </td>
                                            <td class="bg-light text-center">
                                                {{ $item->dataPemeriksaan->satuan ?? '-' }}
                                            </td>
                                            <td class="bg-light text-center">
                                                {{ $item->dataPemeriksaan->rujukan ?? '-' }}
                                            </td>
                                            <td class="bg-light text-center">
                                                {{ $ch_value }}
                                            </td>
                                            <td class="bg-light text-center">
                                                {{ $cl_value }}
                                            </td>
                                            <td class="text-center">
                                                <div class="keterangan-display {{ $bgColor }} {{ $textColor }} rounded py-1 px-2 d-inline-block"
                                                    style="min-width: 60px;">
                                                    <strong>{{ $textDisplay }}</strong>
                                                </div>
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

                        <!-- HASIL LAIN SECTION -->
                        @if($hasil_lain->count() > 0)
                            @foreach($hasil_lain_grouped as $jenis_pemeriksaan => $items)
                                <div class="pt-3 border-top">
                                    <h6 class="mb-3 border-bottom pb-2">
                                        <i class="ri-list-check me-2"></i>{{ $jenis_pemeriksaan }}
                                    </h6>

                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm">
                                            <thead class="table-light">
                                                <tr>
                                                    <th width="25%" class="bg-light">Jenis Pemeriksaan</th>
                                                    <th width="10%" class="bg-light">Satuan</th>
                                                    <th width="15%" class="bg-light">Rujukan</th>
                                                    <th width="5%" class="bg-light">CH</th>
                                                    <th width="5%" class="bg-light">CL</th>
                                                    <th width="20%">Hasil Pengujian</th>
                                                    <th width="10%">Keterangan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($items as $index => $item)
                                                @php
                                                    $keterangan = $item->keterangan ?? '-';
                                                    $ch_value = $item->ch ?? '-';
                                                    $cl_value = $item->cl ?? '-';

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

                                                <tr>
                                                    <td class="bg-light">
                                                        <strong>{{ $item->data_pemeriksaan ?? $item->jenis_pengujian ?? '-' }}</strong>
                                                    </td>
                                                    <td class="bg-light text-center">
                                                        {{ $item->satuan ?? '-' }}
                                                    </td>
                                                    <td class="bg-light text-center">
                                                        {{ $item->rujukan ?? '-' }}
                                                    </td>
                                                    <td class="bg-light text-center">
                                                        {{ $ch_value }}
                                                    </td>
                                                    <td class="bg-light text-center">
                                                        {{ $cl_value }}
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="fw-medium">{{ $item->hasil_pengujian ?? '-' }}</span>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="keterangan-display {{ $bgColor }} {{ $textColor }} rounded py-1 px-2 d-inline-block"
                                                            style="min-width: 60px;">
                                                            <strong>{{ $textDisplay }}</strong>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endforeach
                        @endif

                        <!-- Catatan dan Footer -->
                        <div class="mt-4">
                            <div class="card border-light">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="ri-information-line me-2"></i>Informasi Tambahan
                                    </h6>
                                    <ul class="list-unstyled mb-0">
                                        <li><i class="ri-checkbox-circle-line me-2 text-success"></i>Data hanya dapat dilihat (Read-Only)</li>
                                        <li><i class="ri-checkbox-circle-line me-2 text-success"></i>Tidak ada fungsi edit atau tambah data</li>
                                        <li><i class="ri-checkbox-circle-line me-2 text-success"></i>Keterangan (H/L/-) dihitung otomatis berdasarkan hasil dan rujukan</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer border-top">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <div class="mb-2 mb-md-0">
                                    <label class="form-label small mb-1">
                                        <i class="ri-user-check-line me-1"></i> Status Validasi
                                    </label>
                                    <div class="d-flex align-items-center">
                                        @if($pasien->id_pemeriksa)
                                            <span class="badge bg-success me-2">
                                                <i class="ri-checkbox-circle-line me-1"></i>Sudah Divalidasi
                                            </span>
                                            <span class="text-muted small">
                                                oleh: <strong>{{ $pasien->pemeriksa->nama_pemeriksa }}</strong>
                                            </span>
                                        @else
                                            <span class="badge bg-warning me-2">
                                                <i class="ri-alert-line me-1"></i>Belum Divalidasi
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-md-end">
                                    <span class="text-muted small">
                                        <i class="ri-time-line me-1"></i>
                                        <span>Terakhir update: {{ $pasien->updated_at ? \Carbon\Carbon::parse($pasien->updated_at)->format('d/m/Y H:i') : '-' }}</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <a href="{{ route('pasien.index') }}" class="btn btn-outline-secondary">
                                    <i class="ri-arrow-left-line me-1"></i> Kembali ke Daftar
                                </a>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('user.print', $pasien->no_lab) }}" class="btn btn-success">
                                    <i class="ri-printer-line me-1"></i> Print
                                </a>
                                <button type="button" id="refreshBtn" class="btn btn-outline-secondary">
                                    <i class="ri-refresh-line me-1"></i> Refresh
                                </button>
                            </div>
                        </div>
                    </div>
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
    /* View Only Styling */
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

    /* Table styling for view only */
    .table-bordered {
        border: 1px solid #dee2e6;
    }

    .table-bordered th,
    .table-bordered td {
        border: 1px solid #dee2e6;
    }

    .table-light {
        background-color: #f8f9fa;
    }

    /* Card styling */
    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
    }

    .border-light {
        border-color: #e9ecef !important;
    }
</style>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Simple refresh button
        document.getElementById('refreshBtn').addEventListener('click', function() {
            window.location.reload();
        });

        // Disable all form elements (extra protection)
        document.querySelectorAll('input, select, textarea, button').forEach(function(element) {
            if (element.id !== 'refreshBtn' && !element.classList.contains('btn')) {
                element.disabled = true;
            }
        });

        console.log('View Only Mode Active - No edit functionality available');
    });
</script>
@endsection

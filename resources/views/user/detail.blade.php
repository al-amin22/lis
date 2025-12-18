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
            Hasil Pengujian
        </li>
    </ol>
    <!-- Breadcrumb ends -->

    <!-- Sales stats starts -->
    <!-- <div class="ms-auto d-lg-flex d-none flex-row">
        <div class="d-flex flex-row gap-1 day-sorting">
            <button class="btn btn-sm btn-primary">Today</button>
            <button class="btn btn-sm">7d</button>
            <button class="btn btn-sm">2w</button>
            <button class="btn btn-sm">1m</button>
            <button class="btn btn-sm">3m</button>
            <button class="btn btn-sm">6m</button>
            <button class="btn btn-sm">1y</button>
        </div>
    </div> -->
    <!-- Sales stats ends -->
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
                    <h5 class="card-title">Hasil Pemeriksaan - {{ $pasien->nama_pasien ?? '-' }}</h5>
                </div>
                <div class="card-body">
                    <!-- Data pasien display starts -->
                    <div class="row gx-3">
                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                            <div class="mb-3">
                                <label class="form-label text-muted small mb-1">No. Lab</label>
                                <div class="d-flex align-items-center">
                                    <i class="ri-file-list-3-line me-2 text-primary"></i>
                                    <h6 class="m-0 text-dark">{{ $pasien->no_lab ?? '-' }}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                            <div class="mb-3">
                                <label class="form-label text-muted small mb-1">RM Pasien</label>
                                <div class="d-flex align-items-center">
                                    <i class="ri-user-card-line me-2 text-primary"></i>
                                    <h6 class="m-0 text-dark">{{ $pasien->rm_pasien ?? '-' }}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                            <div class="mb-3">
                                <label class="form-label text-muted small mb-1">Nama Pasien</label>
                                <div class="d-flex align-items-center">
                                    <i class="ri-user-line me-2 text-primary"></i>
                                    <h6 class="m-0 text-dark">{{ $pasien->nama_pasien ?? '-' }}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                            <div class="mb-3">
                                <label class="form-label text-muted small mb-1">Jenis Kelamin</label>
                                <div class="d-flex align-items-center">
                                    <i class="ri-genderless-line me-2 text-primary"></i>
                                    <h6 class="m-0 text-dark">{{ $pasien->jenis_kelamin ?? '-' }}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                            <div class="mb-3">
                                <label class="form-label text-muted small mb-1">Umur</label>
                                <div class="d-flex align-items-center">
                                    <i class="ri-calendar-line me-2 text-primary"></i>
                                    <h6 class="m-0 text-dark">{{ $pasien->umur ?? '-' }}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                            <div class="mb-3">
                                <label class="form-label text-muted small mb-1">Nomor HP</label>
                                <div class="d-flex align-items-center">
                                    <i class="ri-phone-line me-2 text-primary"></i>
                                    <h6 class="m-0 text-dark">{{ $pasien->no_telepon ?? '-' }}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                            <div class="mb-3">
                                <label class="form-label text-muted small mb-1">Tanggal Pengujian</label>
                                <div class="d-flex align-items-center">
                                    <i class="ri-calendar-2-line me-2 text-primary"></i>
                                    <h6 class="m-0 text-dark">{{ \Carbon\Carbon::parse($pasien->updated_at)->format('d/m/Y') ?? '-' }}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-3 col-lg-4 col-sm-6">
                            <div class="mb-3">
                                <label class="form-label text-muted small mb-1">Dokter Spesialis</label>
                                <div class="d-flex align-items-center">
                                    <i class="ri-stethoscope-line me-2 text-primary"></i>
                                    <h6 class="m-0 text-dark">{{ $pasien->dokter->nama_dokter ?? '-' }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Keluhan section -->
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="mb-3">
                                <label class="form-label text-muted small mb-1">Catatan</label>
                                <div class="border rounded p-3 bg-light">
                                    <div class="d-flex align-items-start">
                                        <i class="ri-chat-quote-line me-2 text-primary mt-1"></i>
                                        <p class="m-0 text-dark">{{ $pasien->catatan }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Data pasien display ends -->
                </div>
            </div>
        </div>
    </div>

    <!-- App body ends -->
    <div class="row gx-3">
        <div class="col-sm-12">
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title">Detail Hasil Pengujian Laboratorium</h5>
                </div>
                @if(count(array_filter($hematology_fix)) > 0)
                <div class="card-body">
                    <!-- Table starts -->
                    <div class="table-outer">
                        <div class="table-responsive">
                            <table class="table m-0 align-middle">
                                <thead>
                                    <tr>
                                        <th>Jenis Pengujian</th>
                                        <th>Hasil Pengujian</th>
                                        <th>Satuan</th>
                                        <th>Rujukan</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <!-- Baris keterangan -->
                                    <tr>
                                        <td colspan="5" class="fw-bold text-primary">
                                            Jenis Pengujian: Hematology
                                        </td>
                                    </tr>

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
                                    @endphp

                                    @if($item)
                                    <tr>
                                        <td>{{ $jenis }}</td>
                                        <td>{{ $item->hasil_pengujian ?? '-' }}</td>
                                        <td>{{ $item->satuan_hasil_pengujian ?? '-' }}</td>
                                        <td>{{ $item->rujukan ?? '-' }}</td>
                                        <td>{{ $item->keterangan ?? '' }}</td>
                                    </tr>
                                    @endif

                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
                @else
                <div class="card-body">
                    <p class="text-center"><i>Data hasil pemeriksaan untuk Hematology Belum/Tidak Dilakukan</i></p>
                </div>
                @endif

                @if($kimia->count() > 0)
                <div class="card-body">
                    <!-- Table starts -->
                    <div class="table-outer">
                        <div class="table-responsive">
                            <table class="table m-0 align-middle">
                                <thead>
                                    <tr>
                                        <th>Analysis</th>
                                        <th>Method</th>
                                        <th>Hasil Pengujian</th>
                                        <th>Satuan</th>
                                        <th>Rujukan</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Baris keterangan dari pasien->jenis_pemeriksaan -->
                                    <tr>
                                        <td colspan="5" class="fw-bold text-primary">
                                            Jenis Pengujian: Kimia
                                        </td>
                                    </tr>

                                    @foreach($kimia as $kimia)
                                    <tr>
                                        <td>{{ $kimia->analysis ?? '-' }}</td>
                                        <td>{{ $kimia->method ?? '-' }}</td>
                                        <td>{{ $kimia->hasil_pengujian ?? '-' }}</td>
                                        <td>{{ $kimia->satuan_hasil_pengujian ?? '-' }}</td>
                                        <td>{{ $kimia->rujukan ?? '-' }}</td>
                                        <td>{{ $kimia->keterangan ?? '' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @else
                    <div class="card-body">
                        <p class="text-center"><i>Data hasil pemeriksaan untuk Kimia Belum/Tidak Dilakukan</i></p>
                    </div>
                    @endif
                    <!-- Table ends -->

                    <!-- Action buttons -->
                    <div class="d-flex gap-2 justify-content-end mt-3">
                        <a href="{{ route('user.print', $pasien->no_lab) }}" target="_blank" class="btn btn-success">
                            <i class="ri-printer-line"></i> Print Hasil
                        </a>
                        <!--
                        <button class="btn btn-primary">
                            <i class="ri-send-plane-line"></i> Kirim ke HIS
                        </button> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

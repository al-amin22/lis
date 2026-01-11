@extends('layouts.app')

@section('content')
<!-- App hero header starts -->
<div class="app-hero-header d-flex align-items-center">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <i class="ri-home-8-line lh-1 pe-3 me-3 border-end"></i>
            <a href="{{ url('admin/dashboard') }}">Home</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('pasien.index.jenis.pemeriksaan') }}">Jenis Pemeriksaan</a>
        </li>
        <li class="breadcrumb-item text-primary" aria-current="page">
            Detail Jenis Pemeriksaan - {{ $jenisPemeriksaan->nama_pemeriksaan }}
        </li>
    </ol>
</div>
<!-- App hero header ends -->

<div class="row">
    <div class="col-xl-12">
        <div class="card shadow-sm">
            <div class="card-body">

                <h5 class="mb-3">Informasi Jenis Pemeriksaan</h5>
                <table class="table table-bordered w-50">
                    <tr>
                        <th width="30%">Nama Pemeriksaan</th>
                        <td>{{ $jenisPemeriksaan->nama_pemeriksaan }}</td>
                    </tr>
                </table>

                <hr>

                <!-- Header + Search -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Data Pemeriksaan</h5>
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="ri-search-line"></i>
                            </span>
                            <input type="text" id="searchPemeriksaan" class="form-control"
                                   placeholder="Cari pemeriksaan...">
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="tablePemeriksaan">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Nama Pemeriksaan</th>
                                <th>Satuan</th>
                                <th>Rujukan</th>
                                <th>Metode</th>
                                <th>Detail Kondisi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($jenisPemeriksaan->dataPemeriksaan as $index => $dp)
                                <tr>
                                    <td>{{ $index + 1 }}</td>

                                    {{-- DATA PEMERIKSAAN --}}
                                    <td contenteditable
                                        data-type="data"
                                        data-id="{{ $dp->id_data_pemeriksaan }}"
                                        data-field="kode_pemeriksaan">
                                        {{ $dp->kode_pemeriksaan }}
                                    </td>

                                    <td contenteditable
                                        data-type="data"
                                        data-id="{{ $dp->id_data_pemeriksaan }}"
                                        data-field="data_pemeriksaan">
                                        {{ $dp->data_pemeriksaan }}
                                    </td>

                                    <td contenteditable
                                        data-type="data"
                                        data-id="{{ $dp->id_data_pemeriksaan }}"
                                        data-field="satuan">
                                        {{ $dp->satuan }}
                                    </td>

                                    <td contenteditable
                                        data-type="data"
                                        data-id="{{ $dp->id_data_pemeriksaan }}"
                                        data-field="rujukan">
                                        {{ $dp->rujukan }}
                                    </td>

                                    <td contenteditable
                                        data-type="data"
                                        data-id="{{ $dp->id_data_pemeriksaan }}"
                                        data-field="metode">
                                        {{ $dp->metode }}
                                    </td>

                                    {{-- DETAIL DATA PEMERIKSAAN --}}
                                    <td>
                                        @if($dp->detailConditions->count())
                                            <table class="table table-sm mb-0">
                                                @foreach ($dp->detailConditions as $detail)
                                                    <tr>
                                                        <td contenteditable
                                                            data-type="detail"
                                                            data-id="{{ $detail->id_detail_data_pemeriksaan }}"
                                                            data-field="jenis_kelamin">
                                                            {{ $detail->jenis_kelamin }}
                                                        </td>
                                                        <td contenteditable
                                                            data-type="detail"
                                                            data-id="{{ $detail->id_detail_data_pemeriksaan }}"
                                                            data-field="umur">
                                                            {{ $detail->umur }}
                                                        </td>
                                                        <td contenteditable
                                                            data-type="detail"
                                                            data-id="{{ $detail->id_detail_data_pemeriksaan }}"
                                                            data-field="rujukan">
                                                            {{ $detail->rujukan }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </table>
                                        @else
                                            <span class="text-muted">Tidak ada</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <small class="text-muted">
                    Klik sel → edit → klik di luar untuk simpan otomatis
                </small>

            </div>
        </div>
    </div>
</div>

<!-- SEARCH -->
<script>
document.getElementById('searchPemeriksaan').addEventListener('keyup', function () {
    const keyword = this.value.toLowerCase();
    document.querySelectorAll('#tablePemeriksaan tbody tr')
        .forEach(row => {
            row.style.display = row.innerText.toLowerCase().includes(keyword) ? '' : 'none';
        });
});
</script>

<!-- INLINE SAVE -->
<script>
document.querySelectorAll('[contenteditable]').forEach(cell => {
    cell.addEventListener('blur', function () {

        fetch(
            this.dataset.type === 'data'
                ? "{{ route('data-pemeriksaan.update-inline') }}"
                : "{{ route('detail-data-pemeriksaan.update-inline') }}",
            {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    id: this.dataset.id,
                    field: this.dataset.field,
                    value: this.innerText.trim()
                })
            }
        ).then(() => {
            this.style.backgroundColor = '#e6fffa';
            setTimeout(() => this.style.backgroundColor = '', 300);
        });
    });
});
</script>
@endsection

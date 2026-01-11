@extends('layouts.app')

@section('content')
<div class="app-hero-header d-flex align-items-center">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('pasien.index.jenis.pemeriksaan') }}">Jenis Pemeriksaan</a>
        </li>
        <li class="breadcrumb-item text-primary">
            {{ $dataPemeriksaan->data_pemeriksaan }}
        </li>
    </ol>
</div>

<div class="card">
    <div class="card-body">

        <h5 class="mb-3">Data Pemeriksaan</h5>

        <div class="table-responsive">
            <table class="table table-striped table-hover" id="tablePemeriksaan">
                <thead>
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

                    {{-- DATA PEMERIKSAAN --}}
                    <tr>
                        <td>1</td>
                        <td contenteditable
                            data-type="data"
                            data-id="{{ $dataPemeriksaan->id_data_pemeriksaan }}"
                            data-field="kode_pemeriksaan">
                            {{ $dataPemeriksaan->kode_pemeriksaan }}
                        </td>

                        <td contenteditable
                            data-type="data"
                            data-id="{{ $dataPemeriksaan->id_data_pemeriksaan }}"
                            data-field="data_pemeriksaan">
                            {{ $dataPemeriksaan->data_pemeriksaan }}
                        </td>

                        <td contenteditable
                            data-type="data"
                            data-id="{{ $dataPemeriksaan->id_data_pemeriksaan }}"
                            data-field="satuan">
                            {{ $dataPemeriksaan->satuan }}
                        </td>

                        <td contenteditable
                            data-type="data"
                            data-id="{{ $dataPemeriksaan->id_data_pemeriksaan }}"
                            data-field="rujukan">
                            {{ $dataPemeriksaan->rujukan }}
                        </td>

                        <td contenteditable
                            data-type="data"
                            data-id="{{ $dataPemeriksaan->id_data_pemeriksaan }}"
                            data-field="metode">
                            {{ $dataPemeriksaan->metode }}
                        </td>

                        <td>
                            <ul class="mb-0 ps-3">
                                @foreach ($dataPemeriksaan->detailConditions as $detail)
                                    <li>
                                        <span contenteditable
                                            data-type="detail"
                                            data-id="{{ $detail->id_detail_data_pemeriksaan }}"
                                            data-field="jenis_kelamin">
                                            {{ $detail->jenis_kelamin }}
                                        </span>
                                        |
                                        <span contenteditable
                                            data-type="detail"
                                            data-id="{{ $detail->id_detail_data_pemeriksaan }}"
                                            data-field="umur">
                                            {{ $detail->umur }}
                                        </span>
                                        |
                                        <span contenteditable
                                            data-type="detail"
                                            data-id="{{ $detail->id_detail_data_pemeriksaan }}"
                                            data-field="rujukan">
                                            {{ $detail->rujukan }}
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>

        <small class="text-muted">
            Klik sel → edit → klik di luar untuk simpan otomatis
        </small>

    </div>
</div>

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
        );

        this.style.background = '#e6fffa';
        setTimeout(() => this.style.background = '', 300);
    });
});
</script>
@endsection

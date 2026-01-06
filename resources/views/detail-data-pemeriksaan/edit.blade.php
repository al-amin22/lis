@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-warning text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-edit me-2"></i>Edit Detail Data Pemeriksaan
                    </h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('detail-data-pemeriksaan.update', $detail->id_detail_data_pemeriksaan) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Data Pemeriksaan <span class="text-danger">*</span></label>
                                    <select class="form-select" name="id_data_pemeriksaan" required>
                                        <option value="">Pilih Data Pemeriksaan</option>
                                        @foreach($dataPemeriksaanList as $data)
                                            <option value="{{ $data->id_data_pemeriksaan }}"
                                                {{ $detail->id_data_pemeriksaan == $data->id_data_pemeriksaan ? 'selected' : '' }}>
                                                {{ $data->data_pemeriksaan }} ({{ $data->jenisPemeriksaan->nama_pemeriksaan ?? '-' }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Urutan</label>
                                    <input type="number" class="form-control" name="urutan"
                                           value="{{ $detail->urutan }}" placeholder="Masukkan urutan" min="0">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Umur</label>
                                    <input type="text" class="form-control" name="umur"
                                           value="{{ $detail->umur }}" placeholder="Contoh: 0-1 tahun, 2-5 tahun">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Jenis Kelamin</label>
                                    <select class="form-select" name="jenis_kelamin">
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="Laki-laki" {{ $detail->jenis_kelamin == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="Perempuan" {{ $detail->jenis_kelamin == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                        <option value="Lainnya" {{ $detail->jenis_kelamin == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Rujukan</label>
                                    <input type="text" class="form-control" name="rujukan"
                                           value="{{ $detail->rujukan }}" placeholder="Masukkan nilai rujukan">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Satuan</label>
                                    <input type="text" class="form-control" name="satuan"
                                           value="{{ $detail->satuan }}" placeholder="Contoh: mg/dL, U/L">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Metode</label>
                                    <input type="text" class="form-control" name="metode"
                                           value="{{ $detail->metode }}" placeholder="Masukkan metode pemeriksaan">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">CH (Critical High)</label>
                                    <input type="text" class="form-control" name="ch"
                                           value="{{ $detail->ch }}" placeholder="Nilai critical high">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label class="form-label">CL (Critical Low)</label>
                                    <input type="text" class="form-control" name="cl"
                                           value="{{ $detail->cl }}" placeholder="Nilai critical low">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('detail-data-pemeriksaan.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

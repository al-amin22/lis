@extends('layouts.app')

@section('content')

<div id="laboratorium-preview-wrapper">
    <!-- Semua konten akan berada di dalam div ini -->
</div>

<!-- Styles yang dibungkus dalam satu style tag -->
<style>
    #laboratorium-preview-wrapper {
        all: initial !important;
        /* Reset semua properti */
    }

    #laboratorium-preview-wrapper * {
        all: revert !important;
        box-sizing: border-box !important;
    }

    /* Semua CSS dari kode asli dimulai dari sini */
    #laboratorium-preview-wrapper {
        margin: 0 !important;
        padding: 0 !important;
        font-family: Arial, sans-serif !important;
        padding: 20px !important;
        background-color: #f5f5f5 !important;
    }

    #laboratorium-preview-wrapper .container {
        max-width: 1200px !important;
        margin: 0 auto !important;
        background-color: white !important;
        border-radius: 8px !important;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1) !important;
        overflow: hidden !important;
    }

    #laboratorium-preview-wrapper .header {
        background-color: #2c3e50 !important;
        color: white !important;
        padding: 20px !important;
        text-align: center !important;
    }

    #laboratorium-preview-wrapper .header h1 {
        font-size: 24px !important;
        margin-bottom: 10px !important;
    }

    #laboratorium-preview-wrapper .controls {
        background-color: #34495e !important;
        padding: 15px 20px !important;
        display: flex !important;
        gap: 10px !important;
        flex-wrap: wrap !important;
        align-items: center !important;
    }

    #laboratorium-preview-wrapper .btn {
        padding: 10px 20px !important;
        border: none !important;
        border-radius: 4px !important;
        cursor: pointer !important;
        font-weight: bold !important;
        transition: all 0.3s ease !important;
        display: flex !important;
        align-items: center !important;
        gap: 8px !important;
    }

    #laboratorium-preview-wrapper .btn-primary {
        background-color: #3498db !important;
        color: white !important;
    }

    #laboratorium-preview-wrapper .btn-primary:hover {
        background-color: #2980b9 !important;
    }

    #laboratorium-preview-wrapper .btn-success {
        background-color: #27ae60 !important;
        color: white !important;
    }

    #laboratorium-preview-wrapper .btn-success:hover {
        background-color: #219653 !important;
    }

    #laboratorium-preview-wrapper .btn-warning {
        background-color: #f39c12 !important;
        color: white !important;
    }

    #laboratorium-preview-wrapper .btn-warning:hover {
        background-color: #d68910 !important;
    }

    #laboratorium-preview-wrapper .preview-container {
        padding: 20px !important;
        background-color: #ecf0f1 !important;
        min-height: 500px !important;
        display: flex !important;
        justify-content: center !important;
        align-items: center !important;
    }

    /* UKURAN A4 TETAP DI SINI */
    #laboratorium-preview-wrapper .iframe-wrapper {
        width: 210mm !important;
        /* Lebar A4 */
        height: 297mm !important;
        /* Tinggi A4 */
        border: 1px solid #ddd !important;
        background-color: white !important;
        overflow: hidden !important;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2) !important;
        margin: 0 auto !important;
        /* Pusatkan */
    }

    #laboratorium-preview-wrapper #resultPreview {
        width: 100% !important;
        height: 100% !important;
        border: none !important;
        transform: scale(1) !important;
        /* Pastikan tidak ada scaling */
    }

    #laboratorium-preview-wrapper .loading {
        display: none !important;
        text-align: center !important;
        padding: 20px !important;
        color: #7f8c8d !important;
    }

    #laboratorium-preview-wrapper .status-bar {
        padding: 10px 20px !important;
        background-color: #ecf0f1 !important;
        border-top: 1px solid #ddd !important;
        display: flex !important;
        justify-content: space-between !important;
        color: #666 !important;
        font-size: 14px !important;
    }

    /* IMPORTANT: Print media query untuk menjaga CSS saat print */
    @media print {

        /* Sembunyikan hanya elemen kontrol, bukan konten */
        #laboratorium-preview-wrapper .header,
        #laboratorium-preview-wrapper .controls,
        #laboratorium-preview-wrapper .status-bar,
        #laboratorium-preview-wrapper .loading {
            display: none !important;
        }

        #laboratorium-preview-wrapper {
            padding: 0 !important;
            margin: 0 !important;
            background: white !important;
        }

        #laboratorium-preview-wrapper .container {
            max-width: 100% !important;
            box-shadow: none !important;
            border-radius: 0 !important;
        }

        #laboratorium-preview-wrapper .preview-container {
            padding: 0 !important;
            background-color: white !important;
            min-height: auto !important;
            display: block !important;
        }

        #laboratorium-preview-wrapper .iframe-wrapper {
            border: none !important;
            box-shadow: none !important;
            width: 100% !important;
            height: auto !important;
            margin: 0 !important;
            transform: none !important;
        }

        #laboratorium-preview-wrapper #resultPreview {
            height: auto !important;
            min-height: 297mm !important;
        }
    }

    @media (max-width: 1200px) {
        #laboratorium-preview-wrapper .iframe-wrapper {
            width: 210mm !important;
            height: 297mm !important;
            transform: scale(0.85) !important;
            /* Scale down untuk layar kecil */
            transform-origin: center !important;
        }
    }

    @media (max-width: 992px) {
        #laboratorium-preview-wrapper .iframe-wrapper {
            transform: scale(0.75) !important;
        }
    }

    @media (max-width: 768px) {
        #laboratorium-preview-wrapper .controls {
            flex-direction: column !important;
            align-items: stretch !important;
        }

        #laboratorium-preview-wrapper .btn {
            width: 100% !important;
            justify-content: center !important;
        }

        #laboratorium-preview-wrapper .iframe-wrapper {
            transform: scale(0.6) !important;
        }
    }

    @media (max-width: 576px) {
        #laboratorium-preview-wrapper .iframe-wrapper {
            transform: scale(0.5) !important;
        }
    }

    #laboratorium-preview-wrapper .info-text {
        color: white !important;
        font-size: 14px !important;
        margin-left: auto !important;
    }

    /* TAMBAHKAN DI STYLE BAGIAN ATAS */
    #laboratorium-preview-wrapper .table-responsive {
        width: 100% !important;
        max-width: 760px !important;
        margin: 0 auto !important;
        box-sizing: border-box !important;
    }

    /* Pastikan tabel tidak melebihi lebar */
    #laboratorium-preview-wrapper table {
        width: 100% !important;
        max-width: 100% !important;
        box-sizing: border-box !important;
        border-collapse: collapse !important;
    }

    #laboratorium-preview-wrapper td,
    #laboratorium-preview-wrapper th {
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
    }

    /* CSS untuk konten iframe juga perlu di-scoped */
    #laboratorium-preview-wrapper #resultPreview .tableheader {
        height: 30px;
        font: 13px Arial, Helvetica, sans-serif;
    }

    #laboratorium-preview-wrapper #resultPreview #column_padding {
        padding-left: 1%;
        padding-bottom: 0.5%;
        padding-top: 0.5%;
    }

    #laboratorium-preview-wrapper #resultPreview td a {
        text-decoration: none;
        color: #0033CC;
    }

    #laboratorium-preview-wrapper #resultPreview td a:hover {
        color: yellow;
        text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;
    }

    #laboratorium-preview-wrapper #resultPreview #rowHover:hover {
        background: #CCCCCC;
    }

    #laboratorium-preview-wrapper #resultPreview #removeborder {
        border: 0px;
        height: 35px;
    }

    #laboratorium-preview-wrapper #resultPreview .form {
        margin: 0px;
        margin-left: 15px;
    }

    #laboratorium-preview-wrapper #resultPreview input[type="text"] {
        width: 95%;
    }

    #laboratorium-preview-wrapper #resultPreview input[type="radio"] {
        width: 20%;
    }

    #laboratorium-preview-wrapper #resultPreview .tableadd {
        background: #CCCCCC;
        padding: 20px;
        border: solid 1px;
    }

    #laboratorium-preview-wrapper #resultPreview #berhasil {
        width: 20%;
        background: #009933;
        color: #fff;
        text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;
    }

    /* #laboratorium-preview-wrapper #resultPreview #row_button {} */

    #laboratorium-preview-wrapper #resultPreview #button_tambah {
        background: blue;
        color: #FFFFFF;
        padding: 3px;
        border: solid 1px yellow;
        margin-left: 1%;
    }

    #laboratorium-preview-wrapper #resultPreview .mypg {
        width: 210mm;
        height: 280mm;
        margin: 0 auto;
        background: #ffffff;
    }

    /* Tambahan untuk memastikan print baik */
    @media print {
        #laboratorium-preview-wrapper #resultPreview body {
            margin: 0 !important;
            padding: 0 !important;
            width: 210mm !important;
            height: 297mm !important;
        }

        #laboratorium-preview-wrapper #resultPreview .mypg {
            width: 100% !important;
            height: auto !important;
            min-height: 297mm !important;
            margin: 0 !important;
        }

        /* Pastikan tabel tidak terpotong */
        #laboratorium-preview-wrapper #resultPreview table {
            page-break-inside: auto !important;
        }

        #laboratorium-preview-wrapper #resultPreview tr {
            page-break-inside: avoid !important;
            page-break-after: auto !important;
        }

        /* Pastikan gambar tidak terpotong */
        #laboratorium-preview-wrapper #resultPreview img {
            max-width: 100% !important;
            height: auto !important;
        }
    }
</style>

<!-- Konten HTML dimasukkan ke dalam wrapper -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const wrapper = document.getElementById('laboratorium-preview-wrapper');

        // Masukkan semua konten HTML ke dalam wrapper
        wrapper.innerHTML = `
        <div class="container">
            <div class="header">
                <h1><i class="fas fa-file-medical-alt"></i> PREVIEW HASIL LABORATORIUM</h1>
                <p>Hasil Pemeriksaan Laboratorium - {{ $nama ?? 'Nama Pasien' }}</p>
            </div>

            <div class="controls">
                <button class="btn btn-success" onclick="downloadPDF()">
                    <i class="fas fa-print"></i> Print Hasil
                </button>

                <button class="btn btn-warning" onclick="refreshPreview()">
                    <i class="fas fa-sync-alt"></i> Refresh Preview
                </button>

                <div class="info-text">
                    No. Lab: <strong>{{ $no_lab ?? '-' }}</strong> |
                    Ukuran: <strong>A4 (210mm × 297mm)</strong>
                </div>
            </div>

            <div class="preview-container">
                <div class="loading" id="loading">
                    <i class="fas fa-spinner fa-spin fa-2x"></i>
                    <p>Memuat hasil laboratorium...</p>
                </div>

                <div class="iframe-wrapper">
                    <!-- HTML hasil laboratorium akan dimuat di sini -->
                    <iframe id="resultPreview" src="about:blank"></iframe>
                </div>
            </div>

            <div class="status-bar">
                <div>
                    <i class="fas fa-info-circle"></i>
                    Dokter Penanggung Jawab: <strong>{{ $dokter_penanggung_jawab ?? '-' }}</strong>
                </div>
                <div>
                    Status: <span id="statusText">Ready</span> |
                    Preview Scale: <span id="scaleText">100%</span>
                </div>
            </div>
        </div>
        `;

        // Include library setelah wrapper diisi
        const script = document.createElement('script');
        script.src = 'https://html2canvas.hertzen.com/dist/html2canvas.min.js';
        document.head.appendChild(script);
    });
</script>

<!-- JavaScript tetap seperti semula -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<script>
    // Inisialisasi jsPDF
    const {
        jsPDF
    } = window.jspdf;

    // Fungsi untuk memuat konten HTML ke dalam iframe
    function loadContentToIframe() {
        const iframe = document.getElementById('resultPreview');
        const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;

        // Ambil konten HTML dari kode yang diberikan
        const htmlContent = `<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!--
Template Name: News Magazine
Author: <a href="http://www.os-templates.com/">OS Templates</a>
Author URI: http://www.os-templates.com/
Licence: Free to use under our free template licence terms
Licence URI: http://www.os-templates.com/template-terms
-->
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <title>Citramed - Hasil Lab {{ $no_lab }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <style type="text/css">
        /* CSS ASLI TETAP ADA - SANGAT PENTING UNTUK PRINT/DOWNLOAD */
        .tableheader {
            background: ;
            color: ;
            text-shadow: ;
            height: 30px;
            font: 13px Arial, Helvetica, sans-serif;
        }

        #column_padding {
            padding-left: 1%;
            padding-bottom: 0.5%;
            padding-top: 0.5%;
        }

        td a {
            text-decoration: none;
            color: #0033CC;
        }

        td a:hover {
            color: yellow;
            text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;
        }

        #rowHover:hover {
            background: #CCCCCC;
        }

        #removeborder {
            border: 0px;
            height: 35px;
        }

        .form {
            margin: 0px;
            margin-left: 15px;
        }

        input[type="text"] {
            width: 95%;
        }

        input[type="radio"] {
            width: 20%;
        }

        .tableadd {
            background: #CCCCCC;
            padding: 20px;
            border: solid 1px;
        }

        #berhasil {
            width: 20%;
            background: #009933;
            color: #fff;
            text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;
        }

        #button_tambah {
            background: blue;
            color: #FFFFFF;
            padding: 3px;
            border: solid 1px yellow;
            margin-left: 1%;
        }

        .mypg {
            width: 210mm;
            height: 280mm;
            margin: 0 auto;
            background: #ffffff;
        }

        /* Tambahan untuk memastikan print baik */
        @media print {
            body {
                margin: 0 !important;
                padding: 0 !important;
                width: 210mm !important;
                height: 297mm !important;
            }

            .mypg {
                width: 100% !important;
                height: auto !important;
                min-height: 297mm !important;
                margin: 0 !important;
            }

            /* Pastikan tabel tidak terpotong */
            table {
                page-break-inside: auto !important;
            }

            tr {
                page-break-inside: avoid !important;
                page-break-after: auto !important;
            }

            /* Pastikan gambar tidak terpotong */
            img {
                max-width: 100% !important;
                height: auto !important;
            }
        }
    </style>
</head>

<body id='faktur'>
    <div class="mypg">
        <div class="tableheader">

            <!-- ####################################################################################################### -->

            <!--content-->
            <table style='border-collapse:collapse; ' width='1500' border='0' align='center'>
                <tr>
                    <td style='height:6px;' colspan='5'></td>
                </tr>

                <tr>
                    <td style='height:15px;' colspan='5'>
                        @if(file_exists(public_path('image/kop_surat.png')))
                        <img src="{{ asset('image/kop_surat.png') }}" style='width:750px;'>
                        @else
                        <img src="image/kop_surat.png" style='width:750px;'>
                        @endif
                    </td>
                </tr>

                <tr>
                    <td style='width:40px'>
                    </td>
                    <td align='center'>

                    </td>
                </tr>
                <tr>

                    <td align='center'>
                        <b></b>
                    </td>
                </tr>
            </table>

            <table style='border-collapse:collapse;' width='760' border='0'>
                <tr>
                    <td colspan='11' align='center'>
                        <h2 style='margin: 0; padding: 10px 0;'>HASIL PEMERIKSAAN LABORATORIUM</h2>
                        <div style='height:1px; border-bottom: solid #000000;border-width: thin; width: 100%;'></div>
                    </td>
                </tr>

                <tr>
                    <td style='width:10px'></td>
                    <td style='width:100px'><b>Nama</b></td>
                    <td style='width:10px' align='center'><b>:</b></td>
                    <td style='width:200px'><b>{{ $nama }} / ({{ $sex }})</b></td>
                    <td style='width:50px'></td>
                    <!-- Data Laboratorium (kanan) -->
                    <td style='width:100px'><b>No. Lab</b></td>
                    <td style='width:10px' align='center'><b>:</b></td>
                    <td style='width:180px'><b>{{ $no_lab }} / {{ $no_rm }}</b></td>
                    <td style='width:10px'></td>
                </tr>

                <tr>
                    <td style='width:10px'></td>
                    <td><b>Tgl Lahir/Umur</b></td>
                    <td align='center'><b>:</b></td>
                    <td style='white-space: nowrap;'>
                        <b>{{ $tgl_lahir }} / {{ $umur_tahun }} Tahun {{ $umur_bulan }} Bulan {{ $umur_hari }} Hari</b>
                    </td>
                    <td></td>
                    <td><b>Waktu Periksa</b></td>
                    <td align='center'><b>:</b></td>
                    <td><b>{{ $created_at }}</b></td>
                    <td></td>
                </tr>

                <tr>
                    <td></td>
                    <td><b>Alamat</b></td>
                    <td align='center'><b>:</b></td>
                    <td><b>{{ $alamat }}</b></td>
                    <td></td>
                    <td><b>Waktu Validasi</b></td>
                    <td align='center'><b>:</b></td>
                    <td><b>{{ $updated_at }}</b></td>
                    <td></td>
                </tr>

                <tr>
                    <td></td>
                    <td><b>Pengirim</b></td>
                    <td align='center'><b>:</b></td>
                    <td><b>{{ $dokter }}</b></td>
                    <td></td>
                    <td><b>Asal Kunjungan</b></td>
                    <td align='center'><b>:</b></td>
                    <td><b>{{ $ruang }}</b></td>
                    <td></td>
                </tr>

                <tr>
                    <td></td>
                    <td><b>Penjamin</b></td>
                    <td align='center'><b>:</b></td>
                    <td><b>{{ $penjamin }}</b></td>
                    <td></td>
                    <td colspan='4'></td>
                    <td></td>
                </tr>

            </table>
            <p></p>

            <table style='border-collapse:collapse;' width='760' border='0' bordercolor='#000000'>
    <tr bgcolor="#E1E1E1">
        <td align='center' style='width:200px; padding-left: 15px; border: 1px solid #EEEEEF;'><b>PEMERIKSAAN</b></td>
        <td align='center' style='width:200px; border: 1px solid #EEEEEF;'><b>HASIL</b></td>
        <td align='center' style='width:100px; border: 1px solid #EEEEEF;'><b>NILAI RUJUKAN</b></td>
        <td align='center' style='width:50px; border: 1px solid #EEEEEF;'><b>SATUAN</b></td>
        <td align='center' style='width:50px; border: 1px solid #EEEEEF;'><b>KETERANGAN</b></td>
    </tr>

    @php $baris = 1; @endphp

    {{-- ====================== HEMATOLOGY ====================== --}}
    @if(!empty($hematology_fix) && count($hematology_fix) > 0)
        @php $adaHematology = false; @endphp
        @foreach($hematology_fix as $item)
            @if($item)
                @php $adaHematology = true; @endphp
            @endif
        @endforeach

        @if($adaHematology)
            <tr>
                <td colspan="5" style="padding-left: 15px;"><b>HEMATOLOGY</b></td>
            </tr>

            @php
                $baris = 1;
                $jenis_pemeriksaan_list = [
                    'WBC', 'Neutrofil%', 'Limfosit%', 'Monosit%', 'Eosinofil%',
                    'Basofil%', 'RBC', 'HGB', 'HCT', 'MCV', 'MCH', 'MCHC',
                    'RDW-CV', 'RDW-SD', 'PLT', 'MPV', 'PDW', 'PCT'
                ];
            @endphp

            @foreach($hematology_fix as $index => $item)
                @if($item)

                    @php
                        $warna = ($baris % 2 == 1) ? "#EEEEEF" : "#F4F5F8";
                        $baris++;

                        $keterangan = $item->keterangan ?? '';
                        if (strtoupper($keterangan) === 'H') {
                            $textColor = 'red';
                            $textDisplay = 'H';
                        } elseif (strtoupper($keterangan) === 'L') {
                            $textColor = 'blue';
                            $textDisplay = 'L';
                        } elseif ($keterangan === '-') {
                            $textColor = 'green';
                            $textDisplay = '';
                        } else {
                            $textColor = 'black';
                            $textDisplay = $keterangan;
                        }
                    @endphp

                    <tr bgcolor="{{ $warna }}">
                        <td style='width:200px; height:20px; padding-left: 15px;'>{{ $item->dataPemeriksaan->data_pemeriksaan ?? '' }}</td>
                        <td align='center' style='width:200px'>{{ $item->hasil_pengujian ?? '' }}</td>
                        <td align='center' style='width:100px'>{{ $item->dataPemeriksaan->rujukan ?? '' }}</td>
                        <td align='center' style='width:50px'>{{ $item->dataPemeriksaan->satuan ?? '' }}</td>
                        <td align='center' style='width:50px; color: {{ $textColor }}'>
                            {{ $textDisplay }}
                        </td>
                    </tr>
                @endif
            @endforeach
        @endif
    @endif



    {{-- ====================== KIMIA ====================== --}}
    @if(!empty($kimia) && $kimia->count() > 0)
        @php $adaKimia = false; @endphp
        @foreach($kimia as $item)
            @if($item && !empty($item->analysis))
                @php $adaKimia = true; @endphp
            @endif
        @endforeach

        @if($adaKimia)
            <tr>
                <td colspan="5" style="padding-left: 15px;"><b>KIMIA</b></td>
            </tr>

            @foreach($kimia as $item)
                @if($item)

                    @php
                        $warna = ($baris % 2 == 1) ? "#EEEEEF" : "#F4F5F8";
                        $baris++;
                    @endphp

                    <tr bgcolor="{{ $warna }}">
                        <td style='width:200px; height:20px; padding-left: 15px;'>{{ $item->dataPemeriksaan->data_pemeriksaan ?? '' }}</td>
                        <td align='center' style='width:200px'>{{ $item->hasil_pengujian ?? '' }}</td>
                        <td align='center' style='width:100px'>{{ $item->dataPemeriksaan->rujukan ?? '' }}</td>
                        <td align='center' style='width:50px'>{{ $item->dataPemeriksaan->satuan ?? '' }}</td>

                        @php
                            $ket = strtoupper($item->keterangan ?? '');
                            $warnaKet = $ket == 'H' ? 'red' : ($ket == 'L' ? 'blue' : 'black');
                        @endphp

                        <td align='center' style='width:50px; color: {{ $warnaKet }}'>
                            {{ $item->keterangan ?? '' }}
                        </td>
                    </tr>

                @endif
            @endforeach

        @endif
    @endif



    {{-- ====================== HASIL LAIN ====================== --}}
    @if(!empty($hasil_lain_grouped) && count($hasil_lain_grouped) > 0)
        @foreach($hasil_lain_grouped as $nama_jenis => $items)
            @if(count($items) > 0)
                <tr>
                    <td colspan="5" style="padding-left: 15px;">
                        <b>{{ strtoupper($nama_jenis) }}</b>
                    </td>
                </tr>

                @foreach($items as $item)
                    @if($item)
                        @php
                            $warna = ($baris % 2 == 1) ? "#EEEEEF" : "#F4F5F8";
                            $baris++;

                            $ket = strtoupper($item->keterangan ?? '');
                            $warnaKet = $ket == 'H' ? 'red' :
                                        ($ket == 'L' ? 'blue' : 'black');
                            $displayKet = ($ket == '-') ? '' : ($item->keterangan ?? '');
                        @endphp

                        <tr bgcolor="{{ $warna }}">
                            <td style='width:200px; height:20px; padding-left: 15px;'>
                                {{ $item->data_pemeriksaan ?? '' }}
                            </td>

                            <td align='center' style='width:200px'>
                                {{ $item->hasil_pengujian ?? '' }}
                            </td>

                            <td align='center' style='width:100px'>
                                {{ $item->rujukan_pemeriksaan ?? '' }}
                            </td>

                            <td align='center' style='width:50px'>
                                {{ $item->satuan_pemeriksaan ?? '' }}
                            </td>

                            <td align='center' style='width:50px; color: {{ $warnaKet }}'>
                                {{ $displayKet }}
                            </td>
                        </tr>
                    @endif
                @endforeach
            @endif
        @endforeach
    @endif

</table>


            <p></p>
            <p></p>
                <table style="border-collapse: collapse; width: 760px;" border="0">
                    <!-- Baris Validasi -->
                    <tr>
                        <td colspan="2" style="padding-left: 100px;">
                            <b>Divalidasi Oleh :</b> {{ $validator }}
                        </td>
                    </tr>

                    <!-- Spasi -->
                    <tr><td colspan="2">&nbsp;</td></tr>

                    <!-- Baris Keterangan dan Jambi/Dokter -->
                    <tr>
                        <!-- Kolom Keterangan dengan sub-tabel -->
                       <td style="width: 400px; vertical-align: top; padding-left: 40px;">
                            <b>Keterangan:</b><br>
                            <div style="
                                display: grid;
                                grid-template-columns: 10px 1fr 25px 1fr;
                                row-gap: 5px;
                                column-gap: 5px;
                                align-items: center;
                            ">
                                <div>L</div>
                                <div>: Hasil dibawah nilai normal</div>
                                <div>CL</div>
                                <div>: Critical Low</div>

                                <div>H</div>
                                <div>: Hasil diatas nilai normal</div>
                                <div>CH</div>
                                <div>: Critical High</div>
                            </div>
                        </td>


                        <!-- Kolom Jambi dan Dokter -->
                        <td style="width: 200px; vertical-align: top; text-align: left; padding-left: 15px;">
                            Jambi, {{ $today }}<br>
                            Dokter Penanggung Jawab,<br>
                            <img src="{{ $qrCodePath }}" alt="QR Code" width="90" height="90"><br>
                            <b>{{ $dokter_penanggung_jawab }}</b>
                        </td>
                    </tr>
                </table>



            <!-- FOOTER -->
        </div>
    </div>
</body>
</html>`;

        iframeDoc.open();
        iframeDoc.write(htmlContent);
        iframeDoc.close();

        // Update scale text
        updateScaleText();
    }

    // Fungsi untuk download PDF dengan hasil sama persis
    async function downloadPDF() {
        document.getElementById('statusText').textContent = 'Menyiapkan PDF...';
        document.getElementById('loading').style.display = 'block';

        try {
            const iframe = document.getElementById('resultPreview');
            const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;

            // AMBIL SELURUH HTML DARI IFRAME - SAMA PERSIS
            const fullHtml = iframeDoc.documentElement.outerHTML;

            // Buat temporary iframe untuk render
            const tempIframe = document.createElement('iframe');
            tempIframe.style.position = 'absolute';
            tempIframe.style.left = '-9999px';
            tempIframe.style.top = '0';
            tempIframe.style.width = '210mm';
            tempIframe.style.height = '297mm';
            tempIframe.style.border = 'none';
            document.body.appendChild(tempIframe);

            // Tulis HTML yang sama persis ke temporary iframe
            const tempDoc = tempIframe.contentDocument || tempIframe.contentWindow.document;
            tempDoc.open();
            tempDoc.write(fullHtml);
            tempDoc.close();

            // Tunggu konten load
            await new Promise(resolve => {
                tempIframe.onload = resolve;
                // Fallback timeout
                setTimeout(resolve, 1000);
            });

            // Ambil elemen .mypg dari temporary iframe
            const contentElement = tempDoc.querySelector('.mypg');

            if (!contentElement) {
                throw new Error('Konten tidak ditemukan');
            }

            // Konfigurasi html2canvas - SKALA TINGGI UNTUK KUALITAS
            const scale = 3;

            const canvas = await html2canvas(contentElement, {
                scale: scale,
                useCORS: true,
                allowTaint: true,
                backgroundColor: '#ffffff',
                logging: false,
                width: 210 * 3.78 * (scale / 3), // A4 width in pixels
                windowWidth: 210 * 3.78 * (scale / 3),
                x: 0,
                y: 0,
                scrollX: 0,
                scrollY: 0,
                onclone: function(clonedDoc, clonedElement) {
                    // Pastikan styling tetap sama
                    const clonedBody = clonedDoc.body;
                    const clonedMypg = clonedElement;

                    // Terapkan style A4
                    clonedBody.style.width = '210mm';
                    clonedBody.style.margin = '0';
                    clonedBody.style.padding = '0';
                    clonedBody.style.background = '#ffffff';
                    clonedBody.style.overflow = 'hidden';

                    clonedMypg.style.width = '210mm';
                    clonedMypg.style.minHeight = '297mm';
                    clonedMypg.style.margin = '0 auto';
                    clonedMypg.style.padding = '5mm';
                    clonedMypg.style.boxSizing = 'border-box';

                    // Pastikan semua tabel sesuai
                    const tables = clonedMypg.querySelectorAll('table');
                    tables.forEach(table => {
                        table.style.width = '100%';
                        table.style.maxWidth = '100%';
                    });

                    // Pastikan gambar load
                    const images = clonedMypg.getElementsByTagName('img');
                    Array.from(images).forEach(img => {
                        if (!img.complete) {
                            // Tambahkan event handler untuk gambar
                            img.crossOrigin = "anonymous";
                        }
                    });
                }
            });

            // Buat PDF
            const {
                jsPDF
            } = window.jspdf;
            const pdf = new jsPDF({
                orientation: 'portrait',
                unit: 'mm',
                format: 'a4',
                compress: true
            });

            // Konversi canvas ke image
            const imgData = canvas.toDataURL('image/jpeg', 1.0);

            // Hitung dimensi untuk A4
            const imgWidth = 210; // A4 width in mm
            const imgHeight = (canvas.height * imgWidth) / canvas.width;

            // Tambahkan gambar ke PDF
            pdf.addImage(imgData, 'JPEG', 0, 0, imgWidth, imgHeight, '', 'FAST');

            // Simpan PDF
            pdf.save(`Hasil-Lab-{{ $no_lab }}-{{ date('Y-m-d') }}.pdf`);

            // Bersihkan temporary iframe
            document.body.removeChild(tempIframe);

            document.getElementById('statusText').textContent = 'PDF Downloaded';

        } catch (error) {
            console.error('Error generating PDF:', error);
            document.getElementById('statusText').textContent = 'Error';

            // Fallback ke print
            setTimeout(() => {
                printResult();
            }, 500);
        } finally {
            document.getElementById('loading').style.display = 'none';
        }
    }

    // Fungsi untuk refresh preview
    function refreshPreview() {
        document.getElementById('loading').style.display = 'block';
        document.getElementById('statusText').textContent = 'Memuat ulang...';

        setTimeout(() => {
            loadContentToIframe();
            document.getElementById('loading').style.display = 'none';
            document.getElementById('statusText').textContent = 'Ready';
        }, 500);
    }

    // Fungsi untuk menangani perubahan ukuran layar
    function adjustIframeSize() {
        const iframeWrapper = document.querySelector('.iframe-wrapper');
        const container = document.querySelector('.preview-container');
        const scaleText = document.getElementById('scaleText');

        // Dapatkan ukuran container
        const containerWidth = container.clientWidth - 40; // dikurangi padding
        const containerHeight = container.clientHeight;

        // Ukuran asli A4
        const a4Width = 210; // mm
        const a4Height = 297; // mm

        // Hitung scale yang dibutuhkan
        const widthScale = (containerWidth / a4Width) * 100;
        const heightScale = (containerHeight / a4Height) * 100;

        // Gunakan scale terkecil agar muat di kedua dimensi
        let scale = Math.min(widthScale, heightScale) / 100;

        // Batasi scale maksimal 100% (tidak zoom in)
        scale = Math.min(scale, 1);

        // Batasi scale minimal 30%
        scale = Math.max(scale, 0.3);

        // Terapkan scale
        iframeWrapper.style.transform = `scale(${scale})`;

        // Update teks scale
        const scalePercent = Math.round(scale * 100);
        scaleText.textContent = `${scalePercent}%`;
    }

    // Fungsi untuk update teks scale
    function updateScaleText() {
        const iframeWrapper = document.querySelector('.iframe-wrapper');
        const transform = window.getComputedStyle(iframeWrapper).transform;

        if (transform === 'none') {
            document.getElementById('scaleText').textContent = '100%';
        } else {
            const matrix = transform.match(/matrix\(([^)]+)\)/);
            if (matrix) {
                const values = matrix[1].split(', ');
                const scale = parseFloat(values[0]);
                const scalePercent = Math.round(scale * 100);
                document.getElementById('scaleText').textContent = `${scalePercent}%`;
            }
        }
    }

    // Fungsi untuk print hasil - MENGGUNAKAN IFRAME YANG SAMA
    function printResult() {
        document.getElementById('statusText').textContent = 'Printing...';

        const iframe = document.getElementById('resultPreview');
        const iframeWindow = iframe.contentWindow || iframe.contentDocument.defaultView;

        // Tunggu iframe selesai loading
        setTimeout(() => {
            try {
                // Fokus ke iframe dan langsung print
                iframeWindow.focus();
                iframeWindow.print();

                document.getElementById('statusText').textContent = 'Printed';
            } catch (error) {
                console.error('Print error:', error);
                document.getElementById('statusText').textContent = 'Print Error';
            }

            // Reset status setelah 2 detik
            setTimeout(() => {
                document.getElementById('statusText').textContent = 'Ready';
            }, 2000);
        }, 500); // Beri waktu lebih untuk iframe siap
    }

    // Inisialisasi saat halaman dimuat
    document.addEventListener('DOMContentLoaded', function() {
        loadContentToIframe();
        window.addEventListener('resize', adjustIframeSize);

        // Set initial size
        setTimeout(adjustIframeSize, 100);

        // Update scale text setiap 500ms
        setInterval(updateScaleText, 500);

        // Auto print jika ada parameter print
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('print') === 'true') {
            setTimeout(printResult, 1000);
        }
    });

    // Tangani pesan dari iframe (jika ada)
    window.addEventListener('message', function(event) {
        if (event.data === 'loaded') {
            document.getElementById('loading').style.display = 'none';
            document.getElementById('statusText').textContent = 'Loaded';
        }
    });
</script>

@endsection

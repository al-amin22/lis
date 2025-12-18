<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Citramed - Hasil Lab {{ $no_lab }}</title>
    <style type="text/css">
        /* CSS SAMA PERSIS DENGAN YANG DI IFRAME */
        body {
            width: 210mm;
            margin: 0 auto;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            background: #ffffff;
        }

        .mypg {
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            padding: 5mm;
            box-sizing: border-box;
        }

        table {
            width: 100%;
            max-width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        h2 {
            font-size: 14px;
            margin: 5px 0;
        }

        /* Styling untuk tabel hasil */
        .table-hasil {
            width: 100%;
            border: 1px solid #000;
        }

        .table-hasil th {
            background-color: #E1E1E1;
            border: 1px solid #EEEEEF;
            padding: 5px;
            text-align: center;
        }

        .table-hasil td {
            border: 1px solid #EEEEEF;
            padding: 5px;
            vertical-align: top;
        }

        .col-pemeriksaan {
            width: 25%;
        }

        .col-hasil {
            width: 25%;
            text-align: center;
        }

        .col-rujukan {
            width: 20%;
            text-align: center;
        }

        .col-satuan {
            width: 15%;
            text-align: center;
        }

        .col-keterangan {
            width: 15%;
            text-align: center;
        }

        /* Alternating row colors */
        .row-even {
            background-color: #EEEEEF;
        }

        .row-odd {
            background-color: #F4F5F8;
        }

        /* Group header */
        .group-header {
            background-color: #D0D0D0;
            font-weight: bold;
            padding: 5px;
        }

        /* Media print untuk browser */
        @media print {
            body {
                margin: 0 !important;
                padding: 0 !important;
                width: 210mm !important;
                height: 297mm !important;
            }

            .mypg {
                width: 100% !important;
                min-height: 297mm !important;
                margin: 0 !important;
                padding: 5mm !important;
            }
        }
    </style>
</head>

<body id='faktur'>
    <div class="mypg">
        <!-- KOP SURAT -->
        <table style='border-collapse:collapse; width:100%' border='0' align='center'>
            <tr>
                <td style='height:6px;' colspan='5'></td>
            </tr>
            <tr>
                <td style='height:15px;' colspan='5'>
                    @if(file_exists(public_path('image/kop_surat.png')))
                    <img src="{{ public_path('image/kop_surat.png') }}" style='max-width:100%; height: auto;'>
                    @else
                    <!-- Fallback jika gambar tidak ada -->
                    <div style="text-align: center; font-weight: bold; font-size: 16px;">
                        CITRAMED LABORATORIUM
                    </div>
                    @endif
                </td>
            </tr>
        </table>

        <!-- JUDUL -->
        <table style='border-collapse:collapse; width:100%' border='0'>
            <tr>
                <td colspan='9' align='center' style='height:1px; border-bottom: solid #000000;border-width: thin;'>
                    <h2>HASIL PEMERIKSAAN LABORATORIUM</h2>
                </td>
            </tr>
        </table>

        <!-- DATA PASIEN -->
        <table style='border-collapse:collapse; width:100%' border='0'>
            <tr>
                <td style='width:10px'></td>
                <td style='width:75px'><b>Nama</b></td>
                <td style='width:10px' align='center'><b>:</b></td>
                <td style='width:150px'><b>{{ $nama }} ({{ $sex }})</b></td>
                <td style='width:50px'></td>
                <td style='width:100px'><b>No. Lab</b></td>
                <td style='width:10px' align='center'><b>:</b></td>
                <td style='width:150px'><b>{{ $no_lab }} / {{ $no_rm }}</b></td>
                <td style='width:10px'></td>
            </tr>

            <tr>
                <td></td>
                <td><b>Umur</b></td>
                <td align='center'><b>:</b></td>
                <td colspan='2'><b>{{ $susun_tgl_lahir }} / {{ $umur_tahun }} Tahun {{ $umur_bulan }} Bulan {{ $umur_hari }} Hari</b></td>

                <td><b>Pemeriksaan</b></td>
                <td align='center'><b>:</b></td>
                <td><b>{{ $periksa_tgl }}</b></td>
                <td></td>
            </tr>

            <tr>
                <td></td>
                <td><b>Alamat</b></td>
                <td align='center'><b>:</b></td>
                <td colspan='2'><b>{{ $alamat }}</b></td>

                <td><b>Cetak Hasil</b></td>
                <td align='center'><b>:</b></td>
                <td><b>{{ $print_tanggal }}</b></td>
                <td></td>
            </tr>

            <tr>
                <td></td>
                <td><b>Pengirim</b></td>
                <td align='center'><b>:</b></td>
                <td><b>{{ $dokter }}</b></td>
                <td></td>
                <td><b>Ruang & Kelas</b></td>
                <td align='center'><b>:</b></td>
                <td><b>{{ $ruang }} / {{ $kelas }}</b></td>
                <td></td>
            </tr>
        </table>

        <!-- HASIL PEMERIKSAAN -->
        <table class="table-hasil">
            <tr>
                <th class="col-pemeriksaan">PEMERIKSAAN</th>
                <th class="col-hasil">HASIL</th>
                <th class="col-rujukan">NILAI RUJUKAN</th>
                <th class="col-satuan">SATUAN</th>
                <th class="col-keterangan">KETERANGAN</th>
            </tr>

            @php $baris = 1; @endphp
            @foreach($pemeriksaanData as $group)
            <tr>
                <td colspan="5" class="group-header">
                    {{ $group['group_name'] }}
                </td>
            </tr>

            @foreach($group['items'] as $item)
            <tr class="{{ $baris % 2 == 0 ? 'row-even' : 'row-odd' }}">
                @php $baris++; @endphp
                <td>{{ $item['parameter'] }}</td>
                <td style="text-align: center;">{{ $item['hasil'] }}</td>
                <td style="text-align: center;">{{ $item['rujukan'] }}</td>
                <td style="text-align: center;">{{ $item['satuan'] }}</td>
                <td style="text-align: center;">{!! $item['flag_html'] !!}</td>
            </tr>
            @endforeach
            @endforeach
        </table>

        <!-- CATATAN DAN TTD -->
        <table style='border-collapse:collapse; width:100%; margin-top: 15px;' border='0'>
            <tr>
                <td colspan='3' style='padding-left: 15px;'>
                    <b>Diagnosa :<br></b> {{ $catatan1 }}<br>{{ $catatan2 }}
                </td>
                <td align='center'></td>
            </tr>
            <tr>
                <td style='width:180px' rowspan='2'></td>
                <td style='width:180px' rowspan='2'></td>
                <td style='width:180px; ' rowspan='2'></td>
                <td align='left' style='padding-left: 15px;'>
                    Jambi, {{ $pecah_tanggal[2] }}-{{ $pecah_tanggal[1] }}-{{ $pecah_tanggal[0] }}<br>
                    Dokter Penanggung Jawab,<br><br>
                    @if(file_exists(public_path('ttd.png')))
                    <img src="{{ public_path('ttd.png') }}" style='max-height: 40px;'><br>
                    @endif
                    <b>{{ $dokter_penanggung_jawab }}</b>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>

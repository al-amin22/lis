<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <style type="text/css">
        /* CSS STANDAR RUMAH SAKIT */
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 13px;
            background: #ffffff;
            width: 210mm;
        }

        .mypg {
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            background: #ffffff;
            position: relative;
        }

        .page {
            page-break-after: always;
            padding: 5mm; /* Margin 1cm semua sisi */
            min-height: 277mm; /* 297mm - 20mm margin */
            box-sizing: border-box;
            position: relative;
        }

        .page:last-child {
            page-break-after: auto;
        }

        /* FOOTER POSITIONING */
        .footer-left {
            position: absolute;
            bottom: 3mm; /* 0.3 cm dari bawah */
            left: 15mm; /* Sesuaikan dengan margin kiri */
            font-size: 11px;
            color: #666;
            font-family: Arial, Helvetica, sans-serif;
            z-index: 100;
        }

        .footer-right {
            position: absolute;
            bottom: 3mm; /* 0.3 cm dari bawah */
            right: 15mm; /* Sesuaikan dengan margin kanan */
            font-size: 11px;
            color: #666;
            font-family: Arial, Helvetica, sans-serif;
            z-index: 100;
        }

        /* STYLE TABEL STANDAR TANPA BORDER */
        table {
            border-collapse: collapse;
            width: 100%;
            font-size: 13px;
            border: none !important;
        }

        td, th {
            border: none !important;
        }

        tr[bgcolor="#E1E1E1"] td {
            padding: 6px 8px;
            font-weight: bold;
            text-align: center;
            white-space: nowrap;
            overflow: hidden;
        }

        tr[bgcolor="#EEEEEF"] td,
        tr[bgcolor="#F4F5F8"] td {
            padding: 4px 8px;
            height: 20px;
        }

        /* KONTAINER UTAMA UNTUK PENGATURAN LEBAR */
        .content-container {
            width: 190mm; /* 210mm - 20mm margin */
            margin: 0 auto;
            position: relative;
            min-height: 257mm; /* 277mm - footer space */
        }

        /* GARIS BAWAH JUDUL YANG SEJAJAR */
        .title-line {
            height: 1px;
            background-color: #000000;
            width: 100%;
            margin-top: 5px;
            display: block;
        }

        /* HEADER TABEL TIDAK WRAP */
        .table-header {
            white-space: nowrap !important;
            overflow: hidden !important;
            text-overflow: ellipsis !important;
        }

        /* JUDUL RESPONSIF */
        .judul-container {
            width: 100%;
            box-sizing: border-box;
        }

        h2 {
            margin: 0;
            padding: 10px 0;
            width: 100%;
            box-sizing: border-box;
            text-align: center;
        }

        /* KOP SURAT RESPONSIF */
        .kop-surat-container {
            width: 100%;
            max-width: 190mm;
            margin: 0 auto;
            height: auto;
            min-height: 50px;
            margin-bottom: 5mm;
        }

        .kop-surat-container img {
            width: 100%;
            height: auto;
            max-height: 120px;
            display: block;
        }

        /* SPACING UNTUK HALAMAN SELANJUTNYA */
        .page-spacing {
            margin-top: 10mm;
        }

        /* CONTENT WRAPPER UNTUK MENGATUR SPASI ANTARA KONTEN DAN FOOTER */
        .content-wrapper {
            min-height: 220mm; /* Tinggi minimum untuk konten sebelum footer */
        }

        /* PRINT STYLES */
        @media print {
            body {
                margin: 0 !important;
                padding: 0 !important;
                width: 210mm !important;
                background: white !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .mypg {
                width: 100% !important;
                height: auto !important;
                min-height: 297mm !important;
                margin: 0 !important;
            }

            .page {
                page-break-after: always !important;
                padding: 10mm !important;
                margin: 0 !important;
                position: relative !important;
                min-height: 277mm !important;
            }

            .page:last-child {
                page-break-after: auto !important;
            }

            .content-container {
                width: 190mm !important;
                margin: 0 auto !important;
                min-height: 257mm !important;
            }

            .footer-left {
                position: absolute !important;
                bottom: 3mm !important;
                left: 15mm !important;
                font-size: 11px !important;
                color: #666 !important;
                z-index: 100 !important;
            }

            .footer-right {
                position: absolute !important;
                bottom: 3mm !important;
                right: 15mm !important;
                font-size: 11px !important;
                color: #666 !important;
                z-index: 100 !important;
            }

            .title-line {
                width: 100% !important;
            }

            .judul-container {
                width: 100% !important;
            }

            .kop-surat-container {
                width: 100% !important;
                max-width: 190mm !important;
            }

            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
    </style>
</head>

<body id='faktur'>
    <div class="mypg">
        @php
            // Set waktu lokal Indonesia
            date_default_timezone_set('Asia/Jakarta');
            $print_date = date('Y-m-d H:i');

            // Logika untuk membagi data ke dalam halaman berdasarkan posisi
            $all_data = [];

            // 1. HEMATOLOGY - AMBIL DARI $hematology_fix
            if(!empty($hematology_fix) && count(array_filter($hematology_fix)) > 0) {
                $jenis_pemeriksaan_list = [
                    'WBC', 'Neutrofil%', 'Limfosit%', 'Monosit%', 'Eosinofil%',
                    'Basofil%', 'RBC', 'HGB', 'HCT', 'MCV', 'MCH', 'MCHC',
                    'RDW-CV', 'RDW-SD', 'PLT', 'MPV', 'PDW', 'PCT'
                ];

                $all_data[] = ['type' => 'header', 'title' => 'HEMATOLOGY'];

                foreach($jenis_pemeriksaan_list as $index => $jenis) {
                    $item = $hematology_fix[$index] ?? null;
                    if($item && isset($item->id_pemeriksaan_hematology)) {
                        // Prioritaskan rujukan_by_kondisi (bisa array atau string)
                        $rujukan_val = '-';
                        $satuan_val = '-';
                        if (isset($item->rujukan_by_kondisi)) {
                            if (is_array($item->rujukan_by_kondisi)) {
                                $rujukan_val = $item->rujukan_by_kondisi['rujukan'] ?? ($item->dataPemeriksaan->rujukan ?? '-');
                                $satuan_val = $item->rujukan_by_kondisi['satuan'] ?? ($item->dataPemeriksaan->satuan ?? '-');
                            } else {
                                $rujukan_val = $item->rujukan_by_kondisi ?? ($item->dataPemeriksaan->rujukan ?? '-');
                                $satuan_val = $item->satuan ?? ($item->dataPemeriksaan->satuan ?? '-');
                            }
                        } else {
                            $rujukan_val = $item->dataPemeriksaan->rujukan ?? '-';
                            $satuan_val = $item->dataPemeriksaan->satuan ?? '-';
                        }

                        $keterangan_val = $item->calculated_keterangan ?? ($item->keterangan ?? '-');

                        $all_data[] = [
                            'type' => 'row',
                            'data_pemeriksaan' => $jenis,
                            'hasil_pengujian' => $item->hasil_pengujian ?? '',
                            'rujukan' => $rujukan_val,
                            'satuan' => $satuan_val,
                            'keterangan' => $keterangan_val
                        ];
                    }
                }
            }

            // 2. KIMIA - AMBIL DARI $kimia
            if(!empty($kimia) && $kimia->count() > 0) {
                $all_data[] = ['type' => 'header', 'title' => 'KIMIA'];

                foreach($kimia as $item) {
                    if($item && !empty($item->analysis)) {
                        // Prioritaskan rujukan_by_kondisi jika tersedia
                        $rujukan_val = '-';
                        $satuan_val = '-';
                        if (isset($item->rujukan_by_kondisi)) {
                            if (is_array($item->rujukan_by_kondisi)) {
                                $rujukan_val = $item->rujukan_by_kondisi['rujukan'] ?? ($item->dataPemeriksaan->rujukan ?? '-');
                                $satuan_val = $item->rujukan_by_kondisi['satuan'] ?? ($item->dataPemeriksaan->satuan ?? '-');
                            } else {
                                $rujukan_val = $item->rujukan_by_kondisi ?? ($item->dataPemeriksaan->rujukan ?? '-');
                                $satuan_val = $item->satuan_hasil_pengujian ?? ($item->dataPemeriksaan->satuan ?? '-');
                            }
                        } else {
                            $rujukan_val = $item->dataPemeriksaan->rujukan ?? '-';
                            $satuan_val = $item->dataPemeriksaan->satuan ?? ($item->satuan_hasil_pengujian ?? '-');
                        }

                        $keterangan_val = $item->calculated_keterangan ?? ($item->keterangan ?? '-');

                        $all_data[] = [
                            'type' => 'row',
                            'data_pemeriksaan' => $item->dataPemeriksaan->data_pemeriksaan ?? $item->analysis,
                            'hasil_pengujian' => $item->hasil_pengujian ?? '',
                            'rujukan' => $rujukan_val,
                            'satuan' => $satuan_val,
                            'keterangan' => $keterangan_val
                        ];
                    }
                }
            }

            // 3. HASIL LAIN - AMBIL DARI $hasil_lain_grouped
            if(!empty($hasil_lain_grouped) && count($hasil_lain_grouped) > 0) {
                foreach($hasil_lain_grouped as $nama_jenis => $items) {
                    if(count($items) > 0) {
                        $all_data[] = ['type' => 'header', 'title' => strtoupper($nama_jenis)];

                        // Convert to array and maintain index
                        $items_array = [];
                        foreach($items as $index => $item) {
                            if($item) {
                                $items_array[] = [
                                    'index' => $index,
                                    'data' => $item
                                ];
                            }
                        }

                        // Sort by index to maintain order from detail.blade.php
                        usort($items_array, function($a, $b) {
                            return $a['index'] <=> $b['index'];
                        });

                        foreach($items_array as $item_array) {
                            $item = $item_array['data'];

                            // Ambil rujukan/satuan/keterangan dengan prioritas kondisi (hasil_lain di-controller sudah menyediakan nilai)
                            $rujukan_val = $item->rujukan_by_kondisi ?? ($item->rujukan_pemeriksaan ?? ($item->rujukan ?? '-'));
                            $satuan_val = $item->satuan_by_kondisi ?? ($item->satuan_pemeriksaan ?? ($item->satuan_hasil_pengujian ?? '-'));
                            $keterangan_val = $item->calculated_keterangan ?? ($item->keterangan ?? '-');

                            $all_data[] = [
                                'type' => 'row',
                                'data_pemeriksaan' => $item->jenis_pengujian ?? $item->data_pemeriksaan ?? '',
                                'hasil_pengujian' => $item->hasil_pengujian ?? '',
                                'rujukan' => $rujukan_val,
                                'satuan' => $satuan_val,
                                'keterangan' => $keterangan_val
                            ];
                        }
                    }
                }
            }

            // Hitung jumlah baris maksimal per halaman
            // Halaman pertama membutuhkan lebih banyak ruang untuk data pasien
            // Jadi kita sesuaikan jumlah baris per halaman
            $max_rows_page1 = 22; // Lebih sedikit karena ada data pasien
            $max_rows_other = 27; // Lebih banyak untuk halaman selanjutnya

            // Bagi data ke dalam halaman
            $pages = [];
            $current_page = [];
            $current_row_count = 0;
            $is_first_page = true;

            foreach($all_data as $item) {
                if($item['type'] == 'header') {
                    // Header grup dihitung sebagai 1 baris
                    if($current_row_count >= ($is_first_page ? $max_rows_page1 : $max_rows_other) && !empty($current_page)) {
                        $pages[] = $current_page;
                        $current_page = [];
                        $current_row_count = 0;
                        $is_first_page = false;
                    }
                    $current_page[] = $item;
                    $current_row_count++;
                } else {
                    // Baris data biasa
                    $max_rows = $is_first_page ? $max_rows_page1 : $max_rows_other;
                    if($current_row_count >= $max_rows) {
                        $pages[] = $current_page;
                        $current_page = [];
                        $current_row_count = 0;
                        $is_first_page = false;
                    }
                    $current_page[] = $item;
                    $current_row_count++;
                }
            }

            // Tambahkan halaman terakhir jika ada data
            if(!empty($current_page)) {
                $pages[] = $current_page;
            }

            $total_pages = count($pages);
            $page_counter = 1;
        @endphp

        @if(count($pages) == 0)
            <!-- Tampilkan pesan jika tidak ada data -->
            <div class="page">
                <div class="content-container">
                    <div class="kop-surat-container">
                        @if(file_exists(public_path('image/kop_surat.png')))
                        <img src="{{ asset('image/kop_surat.png') }}">
                        @else
                        <img src="image/kop_surat.png">
                        @endif
                    </div>

                    <div class="judul-container">
                        <h2>HASIL PEMERIKSAAN LABORATORIUM</h2>
                        <div class="title-line"></div>
                    </div>

                    <table style='border-collapse:collapse; border:none;' border='0' width='100%'>
                        <tr>
                            <td style='width:10px'></td>
                            <td style='width:100px'><b>Nama</b></td>
                            <td style='width:10px' align='center'><b>:</b></td>
                            <td style='width:200px'><b>{{ $nama }} / ({{ $sex }})</b></td>
                            <td style='width:50px'></td>
                            <td style='width:100px'><b>No. Lab</b></td>
                            <td style='width:10px' align='center'><b>:</b></td>
                            <td style='width:180px'><b>{{ $no_lab }} / {{ $no_rm }}</b></td>
                            <td style='width:10px'></td>
                        </tr>
                    </table>

                    <p></p>
                    <table style='border-collapse:collapse; border:none;' border='0' width='100%'>
                        <tr bgcolor="#E1E1E1">
                            <td align="center" style="width:200px; padding-left: 15px; white-space: nowrap; overflow: hidden;">JENIS PEMERIKSAAN</td>
                            <td align="center" style="width:200px; white-space: nowrap; overflow: hidden;">HASIL</td>
                            <td align="center" style="width:100px; white-space: nowrap; overflow: hidden;">NILAI RUJUKAN</td>
                            <td align="center" style="width:50px; white-space: nowrap; overflow: hidden;">SATUAN</td>
                            <td align="center" style="width:50px; white-space: nowrap; overflow: hidden;">KETERANGAN</td>
                        </tr>
                        <tr>
                            <td colspan="5" align="center" style="padding: 50px;">
                                <b>TIDAK ADA DATA HASIL PEMERIKSAAN</b>
                            </td>
                        </tr>
                    </table>

                    <div class="footer-left">
                        Print Date : {{ $print_date }}
                    </div>
                    <div class="footer-right">
                        Halaman 1 dari 1
                    </div>
                </div>
            </div>
        @else
            @foreach($pages as $page_data)
            <div class="page">
                <div class="content-container">
                    <!-- KOP SURAT DI SETIAP HALAMAN -->
                    <div class="kop-surat-container">
                        @if(file_exists(public_path('image/kop_surat.png')))
                        <img src="{{ asset('image/kop_surat.png') }}">
                        @else
                        <img src="image/kop_surat.png">
                        @endif
                    </div>

                    <!-- JUDUL DAN DATA PASIEN HANYA DI HALAMAN PERTAMA -->
                    @if($page_counter == 1)
                    <div class="judul-container">
                        <h2>HASIL PEMERIKSAAN LABORATORIUM</h2>
                        <div class="title-line"></div>
                    </div>

                    <table style='border-collapse:collapse; border:none;' border='0' width='100%'>
                        <tr>
                            <td style='width:10px'></td>
                            <td style='width:100px'><b>Nama</b></td>
                            <td style='width:10px' align='center'><b>:</b></td>
                            <td style='width:200px'><b>{{ $nama }} / ({{ $sex }})</b></td>
                            <td style='width:50px'></td>
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
                            <td><b>{{ $waktu_validasi }}</b></td>
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
                            <td style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"><b>{{ $ruang }}</b></td>
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
                    @endif

                    <!-- TABEL HASIL PEMERIKSAAN -->
                    <table style='border-collapse:collapse; border:none;' border='0' width='100%'>
                        @if($page_counter == 1)
                        <!-- Header tabel untuk halaman pertama -->
                        <tr bgcolor="#E1E1E1">
                            <td align="center" style="width:200px; padding-left: 15px; white-space: nowrap; overflow: hidden;">JENIS PEMERIKSAAN</td>
                            <td align="center" style="width:200px; white-space: nowrap; overflow: hidden;">HASIL</td>
                            <td align="center" style="width:100px; white-space: nowrap; overflow: hidden;">NILAI RUJUKAN</td>
                            <td align="center" style="width:50px; white-space: nowrap; overflow: hidden;">SATUAN</td>
                            <td align="center" style="width:50px; white-space: nowrap; overflow: hidden;">KETERANGAN</td>
                        </tr>
                        @else
                        <!-- Tambahkan spacer untuk halaman selanjutnya -->
                        <tr><td colspan="5" style="height: 20px;"></td></tr>
                        <!-- Header tabel untuk halaman berikutnya -->
                        <tr bgcolor="#E1E1E1">
                            <td align="center" style="width:200px; padding-left: 15px; white-space: nowrap; overflow: hidden;">JENIS PEMERIKSAAN</td>
                            <td align="center" style="width:200px; white-space: nowrap; overflow: hidden;">HASIL</td>
                            <td align="center" style="width:100px; white-space: nowrap; overflow: hidden;">NILAI RUJUKAN</td>
                            <td align="center" style="width:50px; white-space: nowrap; overflow: hidden;">SATUAN</td>
                            <td align="center" style="width:50px; white-space: nowrap; overflow: hidden;">KETERANGAN</td>
                        </tr>
                        @endif

                        @php
                            $baris = 1;
                        @endphp

                        @foreach($page_data as $item)
                            @if($item['type'] == 'header')
                                <tr>
                                    <td colspan="5" style="padding-left: 15px; white-space: nowrap; padding-top: 5px; padding-bottom: 5px;"><b>{{ $item['title'] }}</b></td>
                                </tr>
                                @php $baris = 1; @endphp
                            @elseif($item['type'] == 'row')
                                @php
                                    $warna = ($baris % 2 == 1) ? "#EEEEEF" : "#F4F5F8";
                                    $baris++;

                                    $keterangan = strtoupper(trim($item['keterangan'] ?? ''));

                                    // PRIORITAS TERTINGGI → CRITICAL
                                    if ($keterangan === 'CH') {
                                        $textColor = 'red';
                                        $textDisplay = 'CH';
                                    } elseif ($keterangan === 'CL') {
                                        $textColor = 'blue';
                                        $textDisplay = 'CL';
                                    }
                                    // HIGH / LOW NORMAL
                                    elseif ($keterangan === 'H') {
                                        $textColor = 'red';
                                        $textDisplay = 'H';
                                    } elseif ($keterangan === 'L') {
                                        $textColor = 'blue';
                                        $textDisplay = 'L';
                                    }
                                    // NORMAL / KOSONG
                                    elseif ($keterangan === '-' || $keterangan === '') {
                                        $textColor = 'green';
                                        $textDisplay = '';
                                    }
                                    // LAINNYA (jaga-jaga)
                                    else {
                                        $textColor = 'black';
                                        $textDisplay = $keterangan;
                                    }
                                @endphp

                                <tr bgcolor="{{ $warna }}">
                                    <td style='width:200px; height:20px; padding-left: 15px; white-space: nowrap; overflow: hidden;'>{{ $item['data_pemeriksaan'] ?? '' }}</td>
                                    <td align='center' style='width:200px; white-space: nowrap; overflow: hidden;'>{{ $item['hasil_pengujian'] ?? '' }}</td>
                                    <td align='center' style='width:100px; white-space: nowrap; overflow: hidden;'>{{ $item['rujukan'] ?? '' }}</td>
                                    <td align='center' style='width:50px; white-space: nowrap; overflow: hidden;'>{{ $item['satuan'] ?? '' }}</td>
                                    <td align='center' style='width:50px; white-space: nowrap; overflow: hidden; color: {{ $textColor }}; font-weight: bold;'>
                                        {{ $textDisplay }}
                                    </td>
                                </tr>
                            @endif
                        @endforeach

                        <!-- TAMBAHKAN BARIS KOSONG UNTUK MEMASTIKAN FOOTER DI POSISI YANG SAMA -->
                        @if($page_counter == $total_pages)
                            <!-- Untuk halaman terakhir, tambahkan spasi untuk footer -->
                            <tr><td colspan="5" style="height: 30px;"></td></tr>
                        @endif
                    </table>

                    <!-- FOOTER HANYA DI HALAMAN TERAKHIR -->
                    @if($page_counter == $total_pages)
                    <p></p>
                    <p></p>
                    <table style="border-collapse: collapse; width: 100%; border:none;" border="0">
                        <tr>
                            <td colspan="2" style="padding-left: 100px;">
                                <b>Divalidasi Oleh :</b> {{ $validator }}
                            </td>
                        </tr>
                        <tr><td colspan="2">&nbsp;</td></tr>
                        <tr>
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
                            <td style="width: 200px; vertical-align: top; text-align: left; padding-left: 15px;">
                                Jambi, {{ $today }}<br>
                                Dokter Penanggung Jawab,<br>
                                <img src="{{ $qrCodePath }}" alt="QR Code" width="90" height="90"><br>
                                <b>{{ $dokter_penanggung_jawab }}</b>
                            </td>
                        </tr>
                    </table>
                    @else
                        <!-- Untuk halaman yang bukan terakhir, tambahkan spasi agar footer konsisten -->
                        <div style="height: 30px;"></div>
                    @endif
                </div>

                <!-- FOOTER PADA SETIAP HALAMAN -->
                <div class="footer-left">
                    Print Date : {{ $print_date }}
                </div>
                <div class="footer-right">
                    Halaman {{ $page_counter }} dari {{ $total_pages }}
                </div>
            </div>
            @php $page_counter++; @endphp
            @endforeach
        @endif
    </div>
</body>
</html>

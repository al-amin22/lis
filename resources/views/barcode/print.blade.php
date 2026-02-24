<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

<style>
@page {
    size: 40mm 22mm;
    margin: 0;
}

html, body {
    width: 40mm;
    height: 22mm;
    margin: 0;
    overflow: hidden;
    font-family: Arial, Helvetica, sans-serif;
}

.label {
    width: 40mm;
    height: 22mm;
    padding: 0.8mm;
    box-sizing: border-box;
}

/* NAMA */
.nama {
    font-size: 2.6mm;
    font-weight: bold;
    line-height: 1.05;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* INFO */
.info {
    font-size: 1.6mm;
    margin-top: 0.3mm;
}

/* BARCODE */
.barcode {
    margin-top: 1 mm;
    text-align: center;
}
.barcode img {
    max-width: 30mm;
    max-height: 15mm;
    height: auto;
    display: block;
    margin-left: auto;
    margin-right: auto;
}

/* NO LAB */
.no-lab {
    font-size: 2.0mm;
    font-weight: bold;
    margin-top: 0.3mm;
}

/* DOKTER */
.dokter {
    font-size: 1.6mm;
    margin-top: 0.2mm;
}

.tgl-lahir {
    float: right;
    font-size: 2.0mm;   /* BESARKAN di sini */
    font-weight: bold;  /* opsional biar lebih jelas */
}
</style>
</head>

<body onload="window.print()">

<div class="label">

    <div class="nama">{{ $nama }}</div>

    <div class="info">
        {{ $ruang }}
        <span class="tgl-lahir">{{ $sex }} {{ $tgl_lahir }}</span>
        <div style="clear:both;"></div>
    </div>

    <div class="barcode">
        {!! $barcode_svg !!}
    </div>

    <div class="no-lab">
        {{ $no_lab }} | RM {{ $no_rm }}
    </div>

    <div class="dokter">
        {{ $dokter }}
    </div>

</div>

</body>
</html>

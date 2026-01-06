<!-- resources/views/label/print.blade.php -->
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

<style>
@page {
    size: 50mm 20mm;
    margin: 0;
}

body {
    margin: 0;
    padding: 0;
    font-family: Arial, Helvetica, sans-serif;
}

.label {
    width: 50mm;
    height: 20mm;
    padding: 1mm;
    box-sizing: border-box;
    font-size: 1.8mm;
    line-height: 1.2;
    position: relative;
}

.left-line {
    position: absolute;
    left: 5mm;
    right: 1mm;
}

.barcode img {
    width: 40mm;  /* 4 cm */
    height: 8mm;  /* 0.8 cm */
    display: block;
}
</style>
</head>

<body onload="window.print()">

<div class="label">
    <div class="left-line">

        <div style="font-weight:bold;">{{ $nama }}</div>

        <div style="font-size:1.6mm;">
            {{ $ruang }}
            <span style="float:right;">{{ $sex }} {{ $tgl_lahir }}</span>
            <div style="clear:both;"></div>
        </div>

        <div class="barcode" style="margin-top:0.5mm;">
            <img src="data:image/png;base64,{{ $barcode }}">
        </div>

        <div style="font-size:1.6mm;">
            {{ $no_lab }} &nbsp; RM. {{ $no_rm }}
        </div>

        <div style="font-size:1.6mm;">
            {{ $dokter }}
        </div>

    </div>
</div>

</body>
</html>

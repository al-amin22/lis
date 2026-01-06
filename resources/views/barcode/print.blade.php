<table border="" style="border-color:#ffffff;border-collapse:collapse;border-style:solid;">
<tr>
<td style="width:48mm;height:18mm;font-family:Arial,Helvetica,sans-serif;font-size:2mm;">
<div align="left">
<br>

{{ $nama }}
<br>

<!-- TEKS INI MEWARISI STYLE YANG SAMA PERSIS -->
<div style="width:100%;">
    <span style="float:left;">
        {{ $ruang }}
    </span>
    <span style="float:right;">
        {{ $sex }} {{ $tgl_lahir }}
    </span>
    <div style="clear:both;"></div>
</div>

<img
    src="data:image/png;base64,{{ $barcode }}"
    style="width:43mm;height:8mm;"
>
<br>

{{ $no_lab }}&nbsp;&nbsp;&nbsp;&nbsp;RM. {{ $no_rm }}<br>
{{ $dokter }}

</div>
</td>
</tr>
</table>

import win32print

# ==============================
# GANTI SESUAI PRINTER WINDOWS
# ==============================
PRINTER_NAME = "ZDesigner GT800"   # HARUS SAMA PERSIS
BARCODE_DATA = "123456789012"      # ANGKA SAJA (TEST)

# ==============================
# ZPL BARCODE (ROKOK STYLE)
# ==============================
zpl = f"""
^XA
^PW400
^LL160
^LH0,0
^BY3,2,80
^FO40,40^BCN,80,Y,N,N^FD{BARCODE_DATA}^FS
^XZ
"""

try:
    # Buka printer
    hPrinter = win32print.OpenPrinter(PRINTER_NAME)

    try:
        # Mulai dokumen RAW
        hJob = win32print.StartDocPrinter(
            hPrinter,
            1,
            ("ZPL_TEST", None, "RAW")
        )

        win32print.StartPagePrinter(hPrinter)

        # Kirim ZPL
        win32print.WritePrinter(hPrinter, zpl.encode("ascii"))

        win32print.EndPagePrinter(hPrinter)
        win32print.EndDocPrinter(hPrinter)

        print("✅ BARCODE TEST TERKIRIM KE PRINTER")

    finally:
        win32print.ClosePrinter(hPrinter)

except Exception as e:
    print("❌ GAGAL CETAK:", e)

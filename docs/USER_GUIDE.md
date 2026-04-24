# User Guide

## Persiapan Awal

Sebelum memakai aplikasi:

1. Pastikan database sudah aktif dan dikonfigurasi di file .env.
2. Pastikan migration sudah dijalankan.
3. Pastikan akun dengan role yang sesuai sudah tersedia.

## Login

1. Buka halaman /login.
2. Masukkan email dan password.
3. Klik masuk.
4. Setelah berhasil, sistem mengarahkan ke halaman yang sesuai dengan role.

## Penggunaan untuk Admin

### Master Data

Admin dapat mengelola:

- dokter
- pemeriksa
- ruangan
- kelas
- jenis pemeriksaan

### Log Aktivitas

Admin dapat melihat riwayat aktivitas pengguna melalui menu log activity.

### Laporan

Admin dapat membuka menu laporan untuk melihat:

- laporan lengkap
- laporan harian
- laporan bulanan
- laporan tahunan
- laporan berdasarkan jenis pemeriksaan
- laporan berdasarkan pengirim
- laporan berdasarkan pemeriksa

## Penggunaan untuk Operator Pasien

### Melihat Daftar Pasien

1. Buka halaman pasien.
2. Gunakan filter tanggal, RM, nama, dokter, asal kunjungan, penjamin, dan status.
3. Lihat daftar pasien yang sedang diproses atau sudah selesai.

### Melihat Detail Hasil Lab

1. Pilih nomor laboratorium.
2. Buka halaman hasil lab.
3. Periksa data hasil pemeriksaan dan status validasi.

### Mencetak Barcode atau PDF

1. Buka detail pasien.
2. Pilih menu cetak barcode atau PDF.
3. Simpan atau cetak file yang dihasilkan.

### Update Hasil Pemeriksaan

1. Buka data pemeriksaan yang ingin diubah.
2. Gunakan form edit atau inline update.
3. Simpan perubahan.

## Penggunaan untuk Operator Data Pemeriksaan

### Menambah Data Pemeriksaan

1. Buka menu data pemeriksaan.
2. Tambahkan data baru secara tunggal atau batch.
3. Isi field seperti jenis pemeriksaan, nama pemeriksaan, satuan, rujukan, metode, urutan, dan kode uji.
4. Simpan data.

### Mengubah Data Pemeriksaan

1. Pilih data yang ingin diubah.
2. Ubah field yang diperlukan.
3. Simpan perubahan.

### Inline Update

Beberapa field dapat diperbarui langsung dari tabel tanpa membuka form penuh.

## Penggunaan untuk Hasil Lain

1. Buka menu hasil lain.
2. Cari pemeriksaan berdasarkan jenis atau kode.
3. Simpan data manual bila diperlukan.
4. Perbarui atau hapus data sesuai kebutuhan.

## Tips Penggunaan

- gunakan nomor laboratorium sebagai referensi utama saat mencari data
- pastikan jenis pemeriksaan dan data pemeriksaan sesuai sebelum validasi
- manfaatkan filter laporan untuk rekap yang lebih akurat
- gunakan update inline untuk perubahan kecil dan cepat

## Masalah Umum

### Tidak bisa login

- periksa email dan password
- pastikan role akun sesuai

### Data pasien tidak muncul

- cek filter tanggal dan pencarian
- pastikan data sudah tersimpan dan divalidasi

### PDF gagal dicetak

- pastikan dependency PDF terpasang
- periksa data pasien dan hasil lab yang diminta

### Barcode tidak tampil

- pastikan no_lab tersedia
- pastikan data pasien valid

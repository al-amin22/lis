# lis

lis adalah aplikasi Laboratory Information System berbasis Laravel 11 untuk pengelolaan data pasien, jenis pemeriksaan, data pemeriksaan, hasil laboratorium, laporan, serta integrasi barcode, QR code, PDF, dan dokumen pendukung.

## Ringkasan

Sistem ini digunakan untuk mendukung alur kerja laboratorium, mulai dari input data pasien, pengisian hasil pemeriksaan, validasi, pengelolaan referensi pemeriksaan, hingga pembuatan laporan operasional.

## Fitur Utama

- autentikasi login dan logout
- dashboard berbasis role admin dan pengguna
- manajemen pasien dan hasil lab
- barcode pasien dan hasil laboratorium
- pengelolaan jenis pemeriksaan
- pengelolaan data pemeriksaan dan detail pemeriksaan
- input manual, batch, dan inline update
- integrasi mapping pemeriksaan LIS
- pengelolaan dokter, pemeriksa, kelas, ruangan, dan penjamin
- laporan harian, bulanan, tahunan, dan lengkap
- log aktivitas admin
- export dan cetak PDF

## Teknologi

- Laravel 11
- PHP 8.2+
- Eloquent ORM
- barryvdh/laravel-dompdf
- barryvdh/laravel-snappy
- endroid/qr-code
- intervention/image
- milon/barcode
- phpoffice/phpword
- fpdi / fpdf
- simple-qrcode

## Struktur Modul

- AuthController: login, session, logout
- PasienController: data pasien, hasil lab, barcode, PDF, dan update hasil pemeriksaan
- DataPemeriksaanController: data pemeriksaan dan detail inline update
- DetailDataPemeriksaanController: pengelolaan detail pemeriksaan
- JenisPemeriksaanController: master jenis pemeriksaan
- HasilLainController: pemeriksaan lain dan hasil manual
- LisMappingController: mapping kode pemeriksaan ke LIS
- DokterController: master dokter
- PemeriksaController: master pemeriksa
- RuanganController: master ruangan
- KelasController: master kelas/kelas pemeriksaan
- LogActivityController: audit log aktivitas
- LaporanController: laporan statistik dan rekap laboratorium

## Rute Penting

- /login: halaman login
- /: redirect ke login
- /hasil-lab/{no_lab}: detail hasil laboratorium
- /pasien/{no_lab}/pdf: cetak PDF hasil lab
- /laporan/*: laporan rekap dan statistik
- /admin/*: area admin untuk master data dan log aktivitas

## Cara Menjalankan

1. Salin .env.example menjadi .env
2. Sesuaikan konfigurasi database dan koneksi layanan pendukung
3. Jalankan composer install
4. Jalankan php artisan key:generate
5. Jalankan php artisan migrate
6. Jalankan php artisan serve

## Dokumentasi Tambahan

- [Project Overview](docs/PROJECT_OVERVIEW.md)
- [User Guide](docs/USER_GUIDE.md)

## Catatan Teknis

- sistem menggunakan kode laboratorium no_lab sebagai identitas utama hasil pemeriksaan
- data pemeriksaan dapat diperbarui secara inline untuk mempercepat operasional
- laporan mendukung filter per jenis pemeriksaan, pengirim, pemeriksa, harian, bulanan, dan tahunan
- integrasi barcode dan QR code dipakai untuk pencetakan label dan identifikasi hasil

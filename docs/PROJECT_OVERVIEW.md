# Project Overview

## Gambaran Umum

lis adalah aplikasi Laboratory Information System yang dirancang untuk mengelola proses pemeriksaan laboratorium secara terstruktur. Sistem ini mencakup data pasien, master pemeriksaan, hasil pemeriksaan, laporan statistik, serta pengelolaan referensi seperti dokter, pemeriksa, ruangan, kelas, dan penjamin.

## Tujuan Sistem

- mempercepat input dan validasi data laboratorium
- menyatukan data pasien dan hasil pemeriksaan dalam satu sistem
- memudahkan operator memperbarui hasil secara manual atau inline
- menyediakan laporan operasional untuk kebutuhan manajemen
- mendukung cetak hasil, barcode, dan dokumen PDF

## Arsitektur Aplikasi

Proyek ini mengikuti pola MVC Laravel:

- Model menyimpan entitas operasional laboratorium
- Controller menangani alur bisnis, validasi, pencarian, dan ekspor
- View menampilkan form input, detail hasil, laporan, dan dashboard

## Komponen Utama

### 1. Autentikasi

`AuthController` menangani:

- tampilan login
- proses autentikasi
- arah dashboard berdasarkan role
- logout

Role yang dikenali pada kode saat ini:

- admin
- pengguna

### 2. Pasien dan Hasil Lab

`PasienController` adalah inti sistem. Controller ini menangani:

- daftar pasien harian
- pencarian dan filter pasien
- status order dan status proses
- barcode hasil laboratorium
- detail hasil lab berdasarkan `no_lab`
- update hasil pemeriksaan
- export PDF dan tampilan cetak
- pengiriman hasil ke alat atau kanal lain

### 3. Data Pemeriksaan

`DataPemeriksaanController` mengelola master data pemeriksaan, termasuk:

- input tunggal
- input batch
- update inline
- update detail inline
- pembaruan data pemeriksaan per jenis

### 4. Detail Data Pemeriksaan

`DetailDataPemeriksaanController` digunakan untuk menyimpan detail turunan pemeriksaan dan mendukung operasi massal.

### 5. Master Referensi

Sistem juga memiliki modul master untuk:

- jenis pemeriksaan
- dokter
- pemeriksa
- ruangan
- kelas
- penjamin

### 6. Hasil Lain dan LIS Mapping

`HasilLainController` menangani pemeriksaan tambahan di luar kategori utama.

`LisMappingController` digunakan untuk memetakan kode pemeriksaan ke sistem LIS dan memperbarui hasil secara real-time.

### 7. Laporan

`LaporanController` menyediakan laporan operasional dan statistik, seperti:

- laporan lengkap
- laporan per jenis pemeriksaan
- laporan per pengirim
- laporan per pemeriksa
- laporan harian
- laporan bulanan
- laporan tahunan

### 8. Audit Log

`LogActivityController` menampilkan aktivitas pengguna dan admin untuk kebutuhan audit.

## Model Data

Beberapa model inti yang digunakan:

- `Pasien`
- `UjiPemeriksaan`
- `DataPemeriksaan`
- `DetailDataPemeriksaan`
- `JenisPemeriksaan`
- `HasilPemeriksaanLain`
- `Pemeriksa`
- `Dokter`
- `Ruangan`
- `Kelas`
- `Penjamin`
- `LisMapping`
- `LogActivity`

## Alur Kerja Sistem

1. Operator login ke sistem.
2. Operator mencari atau menambahkan pasien.
3. Operator memilih jenis pemeriksaan dan data pemeriksaan.
4. Hasil lab diisi secara manual, batch, inline, atau dari LIS mapping.
5. Data divalidasi dan disimpan.
6. Operator mencetak barcode, PDF, atau hasil lab.
7. Manajemen dapat melihat laporan statistik dari menu laporan.

## Integrasi Output

Sistem mendukung beberapa output penting:

- barcode untuk label pasien
- QR code untuk identifikasi cepat
- PDF hasil laboratorium
- dokumen Word dan format pendukung lain melalui library tambahan

## Strategi Data

- nomor laboratorium digunakan sebagai kunci utama hasil
- master data dipisahkan dari data transaksi agar lebih mudah dirawat
- update inline dipakai untuk mempercepat koreksi data operasional
- log aktivitas menjaga jejak perubahan data

## Keunggulan Implementasi

- cocok untuk operasional laboratorium harian
- mendukung input cepat dan koreksi cepat
- ada laporan statistik yang lengkap
- mendukung integrasi barcode, QR, dan PDF
- pengelolaan master data lebih rapi dan terpisah

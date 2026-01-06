<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\UjiLabController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\JenisPemeriksaanController;
use App\Http\Controllers\DataPemeriksaanController;
use App\Http\Controllers\LisMappingController;
use App\Http\Controllers\HasilLainController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\PemeriksaController;
use App\Http\Controllers\RuanganController;
use App\Http\Controllers\LogActivityController;
use App\Http\Controllers\DetailDataPemeriksaanController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
// Route::get('/pasien/kirim-hasil/{no_lab}', [PasienController::class, 'kirimHasilKeSimrs'])
//     ->withoutMiddleware('*');
// Route::get('/pasien/ambil-order', [PasienController::class, 'ambilOrderDariSimrs'])
//     ->withoutMiddleware('*'); // optional tanpa auth untuk testing

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
Route::post('/hematology/update-ajax', [PasienController::class, 'updateHematologyAjax'])->name('hematology.update.ajax');
Route::post('/kimia/update-ajax', [PasienController::class, 'updateKimiaAjax'])->name('kimia.update.ajax');
Route::post('/hematology/bulk-update-ajax', [PasienController::class, 'bulkUpdateHematologyAjax'])->name('hematology.bulk.update.ajax');
Route::post('/kirim-ke-alat/{no_lab}', [PasienController::class, 'kirimKeAlat'])->name('kirim.ke.alat');
// Route untuk update hasil lab
Route::get('/check-file/{no_lab}', [PasienController::class, 'checkFile']);
Route::prefix('hasil-lab')->group(function () {
    Route::get('/{no_lab}', [PasienController::class, 'show'])->name('hasil-lab.show');
    Route::put('/{no_lab}', [PasienController::class, 'update'])->name('hasil-lab.update');
    Route::post('/update-field-ajax', [PasienController::class, 'updateFieldAjax'])->name('hasil-lab.update-field-ajax');
    Route::post('/get-data-pemeriksaan', [PasienController::class, 'getDataPemeriksaan'])->name('hasil-lab.get-data-pemeriksaan');
    Route::post('/get-rujukan-hematology', [PasienController::class, 'getRujukanHematologyByKondisi'])->name('hasil-lab.get-rujukan-hematology');
});

Route::get('/pasien/rujukan-by-kondisi', [PasienController::class, 'getRujukanHematologyByKondisi'])->name('pasien.get-rujukan-by-kondisi');

// AJAX & custom routes WAJIB di atas
Route::get(
    '/detail-data-pemeriksaan/data-pemeriksaan',
    [DetailDataPemeriksaanController::class, 'getDataPemeriksaan']
)->name('detail-data-pemeriksaan.get-data-pemeriksaan');

Route::post(
    '/detail-data-pemeriksaan/store-multiple',
    [DetailDataPemeriksaanController::class, 'storeMultiple']
)->name('detail-data-pemeriksaan.store-multiple');

Route::delete(
    '/detail-data-pemeriksaan/destroy-multiple',
    [DetailDataPemeriksaanController::class, 'destroyMultiple']
)->name('detail-data-pemeriksaan.destroy-multiple');

// TERAKHIR BARU resource
Route::resource('detail-data-pemeriksaan', DetailDataPemeriksaanController::class);


// Tambahkan route untuk pencarian pemeriksa
Route::get('/pemeriksa/search', [PemeriksaController::class, 'search'])->name('pemeriksa.search');

Route::prefix('hasil-lab')->group(function () {
    Route::get('/print/{no_lab}', [PasienController::class, 'cetakHasilLab'])
    ->name('hasil-lab.print');

    Route::get('/html-content/{no_lab}', [PasienController::class, 'getHtmlContent'])
        ->name('hasil-lab.html-content');
});
// Route untuk hapus pemeriksaan lain
// Hasil Lain Routes
Route::prefix('hasil-lain')->group(function () {
    Route::get('/get-pemeriksaan-by-jenis', [HasilLainController::class, 'getPemeriksaanByJenis'])->name('hasil-lain.get-pemeriksaan-by-jenis');
    Route::post('/search-data-pemeriksaan', [HasilLainController::class, 'searchDataPemeriksaan'])->name('hasil-lain.search-data-pemeriksaan');
    Route::get('/get-pemeriksaan-by-kode', [HasilLainController::class, 'getPemeriksaanByKode'])->name('hasil-lain.get-pemeriksaan-by-kode');
    Route::post('/store-manual', [HasilLainController::class, 'storeManual'])->name('hasil-lain.store-manual');
    Route::put('/update-hasil-pengujian/{id}', [HasilLainController::class, 'updateHasilPengujian'])->name('hasil-lain.update-hasil-pengujian');
    Route::post('/destroy-multiple', [HasilLainController::class, 'destroyMultiple'])->name('hasil-lain.destroy-multiple');
    Route::delete('/destroy/{id}', [HasilLainController::class, 'destroy'])->name('hasil-lain.destroy');
    Route::post('/search-kode-pemeriksaan', [HasilLainController::class, 'searchKodePemeriksaan'])->name('hasil-lain.search-kode-pemeriksaan');
});
Route::get('/jenis-pemeriksaan/search',[JenisPemeriksaanController::class, 'search'])->name('jenis-pemeriksaan.search');
Route::get('/search/pemeriksa', [PemeriksaController::class, 'searchPemeriksa'])->name('pemeriksa.search');
Route::post('/hasil-lab/history-hover-detailed', [PasienController::class, 'getHistoryHover'])->name('hasil-lab.get-history-hover');
Route::post('/hasil-lab/update-keterangan-batch', [PasienController::class, 'updateKeteranganBatch'])->name('hasil-lab.update-keterangan-batch');
// Routes untuk Penjamin
Route::get('/penjamin/search', [PasienController::class, 'searchPenjamin'])->name('penjamin.search');
Route::post('/pasien/update-penjamin', [PasienController::class, 'updatePenjamin'])->name('pasien.update.penjamin');
Route::post('/pasien/update-ruangan', [PasienController::class, 'updateRuangan'])->name('pasien.update.ruangan');
// Routes untuk Ruangan
Route::get('/ruangan/search', [PasienController::class, 'searchRuangan'])->name('ruangan.search');

Route::post('/pasien/update-realtime', [PasienController::class, 'updateRealtime'])->name('update.realtime');
Route::get('/dokter/search', [DokterController::class, 'searchDokter'])->name('dokter.search');
Route::post('/dokter/create', [DokterController::class, 'createDokter'])->name('dokter.create');

Route::get('/pasien/data/{no_lab}', [PasienController::class, 'getDataPasienDetail'])->name('data.get');

Route::post('/kimia/search-kode-pemeriksaan', [LisMappingController::class, 'searchKodePemeriksaan'])
    ->name('kimia.search-kode-pemeriksaan');

Route::post('/kimia/update-kode-pemeriksaan', [LisMappingController::class, 'updateKodePemeriksaan'])
    ->name('kimia.update-kode-pemeriksaan');

Route::post('/kimia/reset-kode-pemeriksaan', [LisMappingController::class, 'resetKodePemeriksaan'])
    ->name('kimia.reset-kode-pemeriksaan');

Route::post('/hasil-lab/pemeriksaan-lain', [PasienController::class, 'savePemeriksaanLain'])->name('hasil-lab.save-pemeriksaan-lain');
Route::delete('/hasil-lab/pemeriksaan-lain/{id}', [PasienController::class, 'deletePemeriksaanLain'])->name('hasil-lab.delete-pemeriksaan-lain');


Route::get('/', function () {
    return redirect('/login');
});

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('pasien.')
    ->group(function () {
        Route::get('/logs', [LogActivityController::class, 'index']);
        Route::get('/logs/{id}', [LogActivityController::class, 'show']);
        Route::get('/logs/modules/list', [LogActivityController::class, 'getModules']);
        Route::get('/logs/actions/list', [LogActivityController::class, 'getActions']);
        // routes/web.php
        Route::get('/{no_lab}/barcode', [PasienController::class, 'generate'])->name('barcode');

        // INDEX — daftar dokter
        Route::get('/dokter', [DokterController::class, 'index'])->name('dokter.index');
        Route::post('/dokter', [DokterController::class, 'store'])->name('dokter.store');
        Route::put('/dokter/{id}', [DokterController::class, 'update'])->name('dokter.update');
        Route::delete('/dokter/{id}', [DokterController::class, 'destroy'])->name('dokter.destroy');
        Route::post('dokter/multiple', [DokterController::class, 'storeMultiple'])->name('dokter.store.multiple');

        Route::get('/kelas', [KelasController::class, 'index'])->name('kelas.index');
        Route::post('/kelas', [KelasController::class, 'store'])->name('kelas.store');
        Route::put('/kelas/{id}', [KelasController::class, 'update'])->name('kelas.update');
        Route::delete('/kelas/{id}', [KelasController::class, 'destroy'])->name('kelas.destroy');
        Route::post('kelas/multiple', [KelasController::class, 'storeMultiple'])->name('kelas.store.multiple');

        Route::get('/pemeriksa', [PemeriksaController::class, 'index'])->name('pemeriksa.index');
        Route::post('/pemeriksa', [PemeriksaController::class, 'store'])->name('pemeriksa.store');
        Route::put('/pemeriksa/{id}', [PemeriksaController::class, 'update'])->name('pemeriksa.update');
        Route::delete('/pemeriksa/{id}', [PemeriksaController::class, 'destroy'])->name('pemeriksa.destroy');
        Route::post('pemeriksa/multiple', [PemeriksaController::class, 'storeMultiple'])->name('pemeriksa.store.multiple');

        Route::get('/ruangan', [RuanganController::class, 'index'])->name('ruangan.index');
        Route::post('/ruangan', [RuanganController::class, 'store'])->name('ruangan.store');
        Route::put('/ruangan/{id}', [RuanganController::class, 'update'])->name('ruangan.update');
        Route::delete('/ruangan/{id}', [RuanganController::class, 'destroy'])->name('ruangan.destroy');
        Route::post('ruangan/multiple', [RuanganController::class, 'storeMultiple'])->name('ruangan.store.multiple');

        Route::get('create', [PasienController::class, 'create'])->name('create');
        Route::get('create', [PasienController::class, 'create'])->name('create');
        Route::get('/search-dropdown', [PasienController::class, 'searchDropdown'])->name('search.dropdown');
        Route::get('/dashboard', [PasienController::class, 'index'])->name('index');
        Route::get('/search', [PasienController::class, 'search'])->name('search');
        Route::get('/hasil/uji-lab', [UjiLabController::class, 'index'])->name('hasil-lab');
        Route::post('/', [PasienController::class, 'store'])->name('store');
        Route::get('/{no_lab}', [PasienController::class, 'show'])->name('show');
        Route::get('/{no_lab}/edit', [PasienController::class, 'edit'])->name('edit');
        Route::put('/{no_lab}', [PasienController::class, 'update'])->name('update');
        Route::post('/{no_lab}', [PasienController::class, 'updateDataPasien'])->name('update.data.validator');
        Route::get('/pasien/{pasien}/pemeriksa', [PasienController::class, 'getPemeriksa'])->name('pemeriksa');
        Route::post('/pasien/{no_lab}/update-pemeriksa', [PasienController::class, 'updateDataValidator'])->name('update.data.validator');

        Route::get('/search', [PasienController::class, 'search'])->name('search');
        Route::delete('/{no_lab}', [PasienController::class, 'destroy'])->name('destroy');
        Route::get('/data/jenis-pemeriksaan/', [JenisPemeriksaanController::class, 'index'])->name('index.jenis.pemeriksaan');
        Route::post('/jenis-pemeriksaan', [JenisPemeriksaanController::class, 'store'])->name('store.jenis.pemeriksaan');
        Route::post('/jenis-pemeriksaan/store-batch', [JenisPemeriksaanController::class, 'storeBatch'])->name('store.batch.jenis.pemeriksaan');
        Route::put('/jenis-pemeriksaan/update/{id}', [JenisPemeriksaanController::class, 'update'])->name('update.jenis.pemeriksaan');
        Route::delete('/jenis-pemeriksaan/destroy/{id}', [JenisPemeriksaanController::class, 'destroy'])->name('destroy.jenis.pemeriksaan');

        Route::get('/data/data-pemeriksaan/', [DataPemeriksaanController::class, 'index'])->name('index.data.pemeriksaan');
        Route::post('/data-pemeriksaan', [DataPemeriksaanController::class, 'store'])->name('store.data.pemeriksaan');
        Route::post('/data-pemeriksaan/store-batch', [DataPemeriksaanController::class, 'storeBatch'])->name('store.batch.data.pemeriksaan');
        Route::put('/data-pemeriksaan/update/{id_data_pemeriksaan}', [DataPemeriksaanController::class, 'update'])->name('update.data.pemeriksaan');
        Route::put('/data-pemeriksaan/update-batch/jenis/{idJenis}',[DataPemeriksaanController::class, 'updateBatchByJenis'])->name('data-pemeriksaan.update-batch-jenis');
        Route::delete('/data-pemeriksaan/destroy/{id_data_pemeriksaan}', [DataPemeriksaanController::class, 'destroy'])->name('destroy.data.pemeriksaan');

        Route::get('/pasien/history/{rm_pasien}', [PasienController::class, 'history'])->name('history');
        Route::get('hasil-lab/html-content', [PasienController::class,''])->name('');
        Route::get('/print/{no_lab}', [PasienController::class, 'cetakHasilLab'])->name('print');
        Route::get('/lab/download/{no_lab}', [PasienController::class, 'downloadLabPDF'])->name('lab.download');
        Route::get('/download-pdf/{no_lab}', [PasienController::class, 'downloadPdf'])->name('downloadPdf');
        Route::get('/generate-pdf/{no_lab}', [PasienController::class, 'generatePdf'])->name('generatePdf');
        Route::get('/laboratorium/print/{no_lab}', [PasienController::class, 'printLaboratorium'])->name('laboratorium.print');
    });


Route::middleware(['auth', 'role:pengguna'])
    ->prefix('user')
    ->name('user.')
    ->group(function () {
        Route::get('/dashboard', [UserController::class, 'index'])->name('index');
        Route::get('/search', [UserController::class, 'search'])->name('search');
        Route::get('/{no_lab}', [UserController::class, 'show'])->name('show');
        Route::get('/hasil/uji-lab', [UjiLabController::class, 'index'])->name('hasil-lab');
        Route::get('/pasien/history/{rm_pasien}', [UserController::class, 'history'])->name('history');
        Route::get('/print/{no_lab}', [UserController::class, 'cetakHasilLab'])->name('print');
        Route::get('/generate-pdf/{no_lab}', [UserController::class, 'generatePdf'])->name('generatePdf');
        Route::get('/laboratorium/print/{no_lab}', [UserController::class, 'printLaboratorium'])->name('laboratorium.print');
    });

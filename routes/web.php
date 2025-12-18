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
// Route untuk update hasil lab
Route::get('/check-file/{no_lab}', [PasienController::class, 'checkFile']);
Route::prefix('hasil-lab')->group(function () {
    Route::get('/{no_lab}', [PasienController::class, 'show'])->name('hasil-lab.show');
    Route::put('/{no_lab}', [PasienController::class, 'update'])->name('hasil-lab.update');
    Route::post('/update-field-ajax', [PasienController::class, 'updateFieldAjax'])->name('hasil-lab.update-field-ajax');
    Route::post('/get-data-pemeriksaan', [PasienController::class, 'getDataPemeriksaan'])->name('hasil-lab.get-data-pemeriksaan');
});
// Route untuk hapus pemeriksaan lain
// Hasil Lain Routes
Route::prefix('hasil-lain')->group(function () {
    Route::post('/search-kode-pemeriksaan', [HasilLainController::class, 'searchKodePemeriksaan'])->name('hasil-lain.search-kode-pemeriksaan');
    Route::post('/store', [HasilLainController::class, 'store'])->name('hasil-lain.store');
    Route::post('/{id}/update-kode', [HasilLainController::class, 'updateKodePemeriksaan'])->name('hasil-lain.update-kode');
    Route::delete('/{id}', [HasilLainController::class, 'destroy'])->name('hasil-lain.destroy');
    Route::post('/destroy-multiple', [HasilLainController::class, 'destroyMultiple'])->name('hasil-lain.destroy-multiple');
    Route::get('/jenis-pemeriksaan', [HasilLainController::class, 'getJenisPemeriksaanList'])->name('hasil-lain.jenis-pemeriksaan');
    Route::get('/hasil-lain/get-pemeriksaan-by-jenis', [HasilLainController::class, 'getPemeriksaanByJenis'])->name('hasil-lain.get-pemeriksaan-by-jenis');
});

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
        Route::put('/data-pemeriksaan/update/{kode_pemeriksaan}', [DataPemeriksaanController::class, 'update'])->name('update.data.pemeriksaan');
        Route::delete('/data-pemeriksaan/destroy/{kode_pemeriksaan}', [DataPemeriksaanController::class, 'destroy'])->name('destroy.data.pemeriksaan');

        Route::get('/pasien/history/{rm_pasien}', [PasienController::class, 'history'])->name('history');
        Route::get('/print/{no_lab}', [PasienController::class, 'cetakHasilLab'])->name('print');
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
        Route::get('/print/{no_lab}', [UserController::class, 'printPdf'])->name('print');
        Route::get('/generate-pdf/{no_lab}', [UserController::class, 'generatePdf'])->name('generatePdf');
        Route::get('/laboratorium/print/{no_lab}', [UserController::class, 'printLaboratorium'])->name('laboratorium.print');
    });

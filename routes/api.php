<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PasienController;

// Ambil order dari SIMRS
Route::post('/pasien/ambil-order', [PasienController::class, 'ambilOrderDariSimrs'])
    ->withoutMiddleware('*'); // optional tanpa auth untuk testing

// Kirim hasil lab ke SIMRS
Route::get('/pasien/hasil', [PasienController::class, 'listHasil'])
    ->withoutMiddleware('*'); // sementara tanpa auth

<?php

use App\Http\Controllers\PublicPmbController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// PMB — Pendaftaran Mandiri (publik, tanpa login; data dikelola petugas PMB di panel admin)
Route::controller(PublicPmbController::class)->prefix('pmb')->name('pmb.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('/', 'store')->name('store');
    Route::get('/status', 'status')->name('status');
    Route::post('/status', 'check')->name('check');
    Route::get('/sukses/{registrationNumber}', 'success')->name('success');
});

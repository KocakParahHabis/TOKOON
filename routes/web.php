<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ProdukController;

Route::get('/', function () {
    return redirect()->route('backend.login');
});

// beranda
Route::get('backend/beranda', [BerandaController::class, 'berandaBackend'])->name('backend.beranda')->middleware('auth');

// login
Route::get('backend/login', [LoginController::class, 'loginBackend'])->name('backend.login');
Route::post('backend/login', [LoginController::class, 'authenticateBackend'])->name('login');
Route::post('backend/logout', [LoginController::class, 'logoutBackend'])->name('backend.logout');

// user
Route::resource('backend/user', UserController::class, ['as' => 'backend'])->middleware('auth');

// kategori
Route::resource('backend/kategori', KategoriController::class, ['as' => 'backend'])->middleware('auth');

// produk
Route::resource('backend/produk', ProdukController::class, ['as' => 'backend'])->middleware('auth');

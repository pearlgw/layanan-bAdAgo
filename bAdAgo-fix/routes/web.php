<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\RedirectController;
use App\Http\Controllers\TransaksiController;
use App\Models\Transaksi;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

//  jika user belum login
Route::group(['middleware' => 'guest'], function () {
    Route::get('/', [BarangController::class, 'index']);

    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'dologin']);
});

Route::group(['middleware' => ['auth', 'checkrole:1']], function () {
    Route::get('/barang', [BarangController::class, 'index']);
    Route::post('/keranjang', [BarangController::class, 'store']);
    Route::get('/keranjang', [BarangController::class, 'showAll']);

    Route::delete('/delete/barang/{id}', [TransaksiController::class, 'hapusBarang']);
    Route::post('/checkout', [TransaksiController::class, 'checkout']);

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/redirect', [RedirectController::class, 'cek']);
});
<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\CheckOngkirController;
use App\Http\Controllers\DetailTransaksiController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\RedirectController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\UserController;
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
    Route::get('/register', [UserController::class, 'index']);
    Route::post('/register', [UserController::class, 'store']);
});

Route::group(['middleware' => ['auth', 'checkrole:1']], function () {
    Route::get('/barang', [BarangController::class, 'index']);
    Route::post('/keranjang', [BarangController::class, 'store']);
    Route::get('/keranjang', [BarangController::class, 'showAll']);

    Route::delete('/delete/barang/{id}', [TransaksiController::class, 'hapusBarang']);
    Route::post('/checkout', [TransaksiController::class, 'checkout']);
    Route::get('/checkout', [TransaksiController::class, 'showTransaksi']);

    Route::post('/check-ongkir', [CheckOngkirController::class, 'checkOngkir'])->name('check-ongkir');
    Route::post('/checked-total-keseluruhan', [TransaksiController::class, 'checkedTotalKeseluruhan']);
    Route::post('/checked-total-ongkir', [TransaksiController::class, 'checkedTotalOngkir']);
    Route::put('/update-status-paid/{transaksiId}', [TransaksiController::class, 'updateStatusPaid']);

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/redirect', [RedirectController::class, 'cek']);

    Route::resource('/history', HistoryController::class);
});

<?php

use App\Http\Controllers\DashboardController;
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

Route::get('/', [DashboardController::class, 'index'])->name('home');
Route::get('/obat/create-form', [DashboardController::class, 'create_obat_form'])->name('obat_create_form');
Route::post('/obat/create', [DashboardController::class, 'store'])->name('obat_create');
Route::get('/obat/{obat}/edit', [DashboardController::class, 'edit_obat'])->name('obat_edit');
Route::patch('/obat/{obat}', [DashboardController::class, 'update_obat'])->name('obat_update');
Route::delete('/obat/{obat}', [DashboardController::class, 'destroy_obat'])->name('obat_destroy');
Route::post('/obat', [DashboardController::class, 'store'])->name('obat_store');

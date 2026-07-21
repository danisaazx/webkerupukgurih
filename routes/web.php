<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BahanBakuController;
use App\Http\Controllers\ProduksiController;
use App\Http\Controllers\LaporanController;
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

Route::get('/', function () {
    return redirect()->route('login');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('products', ProductController::class);
    Route::resource('bahan_baku', BahanBakuController::class);
    Route::resource('produksi', ProduksiController::class);
    Route::resource('transactions', TransactionController::class);

    Route::get('/laporan/bulanan', [LaporanController::class, 'index'])->name('laporan.bulanan');
    Route::get('/laporan/bulanan/export-excel', [LaporanController::class, 'exportExcel'])->name('laporan.bulanan.excel');
    Route::get('/laporan/bulanan/export-pdf', [LaporanController::class, 'exportPdf'])->name('laporan.bulanan.pdf');
});

require __DIR__.'/auth.php';

Auth::routes();

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

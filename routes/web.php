<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminKategoriController;
use App\Http\Controllers\AdminProdukController;
use App\Http\Controllers\AdminTransaksiController;
use App\Http\Controllers\AdminTransaksiDetailController;
use App\Http\Controllers\SesiController;
use App\Http\Controllers\KasirController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ================== AUTH ROUTES ==================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'index'])->name('login');
    Route::post('/login/do', [AdminAuthController::class, 'doLogin']);
});

Route::get('/logout', [AdminAuthController::class, 'logout'])->middleware('auth')->name('logout');


// ================== ADMIN UNIVERSAL ROUTES ==================
Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    
    // User Management
    Route::resource('/user', AdminController::class)->except(['show']);
    
    // Transaksi
    Route::get('/transaksi/detail/selesai/{id}', [AdminTransaksiDetailController::class, 'done'])->name('admin.transaksi.detail.done');
    Route::get('/transaksi/detail/delete', [AdminTransaksiDetailController::class, 'delete'])->name('admin.transaksi.detail.delete');
    Route::post('/transaksi/detail/create', [AdminTransaksiDetailController::class, 'create'])->name('admin.transaksi.detail.create');
    Route::resource('/transaksi', AdminTransaksiController::class, ['as' => 'admin']);
    
    // Produk & Kategori
    Route::resource('/produk', AdminProdukController::class, ['as' => 'admin']);
    Route::resource('/kategori', AdminKategoriController::class, ['as' => 'admin']);
});


// ================== OPERATOR ROUTES (BACKWARD COMPATIBILITY) ==================
Route::prefix('operator')->middleware(['auth', 'role:operator'])->group(function () {
    Route::get('/dashboard', function() {
        return redirect('/admin/dashboard');
    })->name('operator.dashboard');
    Route::get('/user', function() {
        return redirect('/admin/user');
    });
});


// ================== KEUANGAN ROUTES (BACKWARD COMPATIBILITY) ==================
Route::prefix('keuangan')->middleware(['auth', 'role:keuangan'])->group(function () {
    Route::get('/dashboard', function() {
        return redirect('/admin/dashboard');
    })->name('keuangan.dashboard');
    Route::get('/transaksi', function() {
        return redirect('/admin/transaksi');
    });
    Route::get('/produk', function() {
        return redirect('/admin/produk');
    });
    Route::get('/kategori', function() {
        return redirect('/admin/kategori');
    });
});


// ================== UMUM / HALAMAN TAMBAHAN ==================
Route::get('/template', function () {
    return view('template');
});


// ================== REDIRECT & DEFAULT ==================
Route::get('/', function () {
    return redirect('/login');
});
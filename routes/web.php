<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\KategoriController as AdminKategoriController;
use App\Http\Controllers\Admin\ProdukController as AdminProdukController;
use App\Http\Controllers\Admin\TransaksiController as AdminTransaksiController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\Operator\DashboardController as OperatorDashboardController;
use App\Http\Controllers\Operator\UserController as OperatorUserController;
use App\Http\Controllers\Operator\KategoriController as OperatorKategoriController;
use App\Http\Controllers\Operator\ProdukController as OperatorProdukController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ================== AUTH ROUTES ==================
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login/do', [AuthController::class, 'doLogin']);
});

Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');


// ================== ADMIN ROUTES ==================
// Routes for administrative tasks (user, produk, kategori). Only users
// with the `admin` role may access these. Financial (keuangan) and
// operator roles are not permitted here.
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    // Dashboard for admin
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    // User Management
    Route::resource('/user', AdminUserController::class)->except(['show']);
    // Produk & Kategori management
    Route::resource('/produk', AdminProdukController::class);
    Route::resource('/kategori', AdminKategoriController::class);
});

// ================== KASIR / KEUANGAN ROUTES ==================
// Routes for financial (keuangan) users. Only users with the
// `keuangan` role may access these. Transaction management lives
// here and not under the admin or operator prefixes.
Route::prefix('kasir')->middleware(['auth', 'role:keuangan'])->group(function () {
    // Dashboard for keuangan
    Route::get('/dashboard', [KasirController::class, 'index'])->name('kasir.dashboard');
    // Transaction routes
    Route::resource('/transaksi', AdminTransaksiController::class, ['as' => 'kasir']);
    // Additional endpoints for transaction details
    Route::get('/transaksi/detail/selesai/{id}', [AdminTransaksiController::class, 'complete'])->name('kasir.transaksi.detail.done');
    Route::get('/transaksi/detail/delete', [AdminTransaksiController::class, 'removeDetail'])->name('kasir.transaksi.detail.delete');
    Route::post('/transaksi/detail/create', [AdminTransaksiController::class, 'addDetail'])->name('kasir.transaksi.detail.create');
});

// ================== OPERATOR ROUTES ==================
// Routes for operator users. Operators manage users, products and
// categories but cannot access transactions. The prefix `operator`
// keeps their URLs distinct from admin and kasir routes.
Route::prefix('operator')->middleware(['auth', 'role:operator'])->group(function () {
    // Dashboard for operator
    Route::get('/dashboard', [OperatorDashboardController::class, 'index'])->name('operator.dashboard');
    // User management
    Route::resource('/user', OperatorUserController::class)->except(['show']);
    // Produk & Kategori management
    Route::resource('/produk', OperatorProdukController::class);
    Route::resource('/kategori', OperatorKategoriController::class);
});


// ================== UMUM / HALAMAN TAMBAHAN ==================
Route::get('/template', function () {
    return view('template');
});


// ================== REDIRECT & DEFAULT ==================
Route::get('/', function () {
    return redirect('/login');
});
<?php

use Illuminate\Support\Facades\Route;
// Import authentication and transaction controllers from the Admin namespace.
// Administrative controllers (Dashboard, User, Kategori, Produk) are no longer
// imported because the "admin" role has been merged into the operator role.
use App\Http\Controllers\Admin\AuthController;
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



// NOTE: There is no separate "admin" role anymore. Administrative tasks are handled
// within the operator prefix. The following admin route group has been removed.

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
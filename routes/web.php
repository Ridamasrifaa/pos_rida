<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JenisController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'auth'])->name('auth');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // --- MANAJEMEN PENJUALAN ---
    Route::get('/penjualan', [PenjualanController::class, 'index'])->name('penjualan.index');
    Route::get('/penjualan/create', [PenjualanController::class, 'create'])->name('penjualan.create');
    Route::post('/penjualan', [PenjualanController::class, 'store'])->name('penjualan.store');
    Route::get('/penjualan/{penjualan}', [PenjualanController::class, 'show'])->name('penjualan.show');
    Route::get('/penjualan/{penjualan}/edit', [PenjualanController::class, 'edit'])->name('penjualan.edit');
    Route::put('/penjualan/{penjualan}', [PenjualanController::class, 'update'])->name('penjualan.update');
    Route::delete('/penjualan/{penjualan}', [PenjualanController::class, 'destroy'])->name('penjualan.destroy');

    // --- MANAJEMEN PRODUK (Bisa diakses Admin & Kasir) ---
    Route::get('/produk', [ProductController::class, 'index'])->name('produk');
    Route::get('/produk/create', [ProductController::class, 'create'])->name('produk.create');
    Route::post('/produk/store', [ProductController::class, 'store'])->name('produk.store');
    Route::get('/produk/detail/{product}', [ProductController::class, 'show'])->name('produk.show');
    Route::get('/produk/edit/{product}', [ProductController::class, 'edit'])->name('produk.edit');
    Route::put('/produk/update/{product}', [ProductController::class, 'update'])->name('produk.update');
    Route::delete('/produk/delete/{product}', [ProductController::class, 'destroy'])->name('produk.destroy');

    // --- MANAJEMEN USER & JENIS (Khusus Admin) ---
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users/store', [UserController::class, 'store'])->name('users.store');
        Route::match(['get', 'post'], '/users/edit/{user}', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/update/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/delete/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    // --- LAPORAN / REKAP (Admin) ---
    Route::prefix('admin')->name('admin.reports.')->group(function () {
        Route::get('/rekap-bulanan', [ReportController::class, 'monthlyIndex'])->name('monthly');
        Route::get('/rekap-mingguan', [ReportController::class, 'weeklyIndex'])->name('weekly');
        Route::get('/rekap-harian/{year}/{month}', [ReportController::class, 'dailyDetail'])->name('daily');

        Route::get('/rekap-bulanan/pdf/{year}', [ReportController::class, 'downloadMonthlyPdf'])->name('monthly.pdf');
        Route::get('/rekap-mingguan/pdf/{year}', [ReportController::class, 'downloadWeeklyPdf'])->name('weekly.pdf');
        Route::get('/rekap-harian/pdf/{year}/{month}', [ReportController::class, 'downloadDailyPdf'])->name('daily.pdf');
    });

    // --- MANAJEMEN JENIS (Khusus Admin) ---
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('jenis', JenisController::class)->parameters([
            'jenis' => 'jenis',
        ]);
    });
});

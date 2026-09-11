<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Semua route dashboard admin SISUKAT (/admin/*) didaftarkan di sini.
| File ini di-require dari routes/web.php dengan prefix 'admin' dan
| name prefix 'admin.'. CRUD masing-masing modul (pages, books,
| tutorials, instruments, uploads, users, settings, activity-logs)
| akan ditambahkan bertahap pada Phase 5, 6, 7, 8, 9.
|
*/

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.store');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
});

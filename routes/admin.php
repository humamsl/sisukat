<?php

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InstrumentController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TutorialController;
use App\Http\Controllers\Admin\UploadController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Semua route dashboard admin SISUKAT (/admin/*), di-require dari
| routes/web.php dengan prefix 'admin' dan name prefix 'admin.'.
| Login/logout/register kini berada di routes/web.php (dipakai
| bersama oleh akun staf maupun akun user publik).
|
*/

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('pages/{page:slug}/edit', [PageController::class, 'edit'])->name('pages.edit');
    Route::put('pages/{page:slug}', [PageController::class, 'update'])->name('pages.update');

    Route::resource('books', BookController::class)->except(['show']);
    Route::resource('tutorials', TutorialController::class)->except(['show']);
    Route::resource('instruments', InstrumentController::class)->except(['show']);

    Route::get('uploads', [UploadController::class, 'index'])->name('uploads.index');
    Route::get('uploads/{upload}', [UploadController::class, 'show'])->name('uploads.show');
    Route::patch('uploads/{upload}/status', [UploadController::class, 'updateStatus'])->name('uploads.status');
    Route::get('uploads/{upload}/download', [UploadController::class, 'download'])->name('uploads.download');
    Route::delete('uploads/{upload}', [UploadController::class, 'destroy'])->name('uploads.destroy');

    Route::get('users/import', [UserController::class, 'import'])->name('users.import');
    Route::post('users/import', [UserController::class, 'storeImport'])->name('users.import.store');
    Route::get('users/import/template', [UserController::class, 'importTemplate'])->name('users.import.template');
    Route::resource('users', UserController::class)->except(['show']);

    Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
    Route::put('settings', [SettingController::class, 'update'])->name('settings.update');

    Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
});

<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InstrumentController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\TutorialController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/pendahuluan', [PageController::class, 'pendahuluan'])->name('pages.pendahuluan');
Route::get('/petunjuk-penggunaan', [PageController::class, 'petunjuk'])->name('pages.petunjuk');

Route::prefix('buku-saku')->name('books.')->group(function () {
    Route::get('/', [BookController::class, 'index'])->name('index');
    Route::get('/{book:slug}', [BookController::class, 'show'])->name('show');
    Route::get('/{book:slug}/baca', [BookController::class, 'read'])->name('read');
    Route::get('/{book:slug}/file', [BookController::class, 'file'])->name('file');
    Route::get('/{book:slug}/cover', [BookController::class, 'cover'])->name('cover');
    Route::get('/{book:slug}/download', [BookController::class, 'download'])->name('download');
});

Route::prefix('tutorial')->name('tutorials.')->group(function () {
    Route::get('/', [TutorialController::class, 'index'])->name('index');
    Route::get('/{tutorial:slug}', [TutorialController::class, 'show'])->name('show');
    Route::get('/{tutorial:slug}/file', [TutorialController::class, 'file'])->name('file');
    Route::get('/{tutorial:slug}/thumbnail', [TutorialController::class, 'thumbnail'])->name('thumbnail');
});

Route::prefix('instrumen')->name('instruments.')->group(function () {
    Route::get('/', [InstrumentController::class, 'index'])->name('index');
    Route::get('/{instrument:slug}/preview', [InstrumentController::class, 'preview'])->name('preview');
    Route::get('/{instrument:slug}/file', [InstrumentController::class, 'file'])->name('file');
    Route::get('/{instrument:slug}/download', [InstrumentController::class, 'download'])->name('download');
});

/*
|--------------------------------------------------------------------------
| Placeholder routes
|--------------------------------------------------------------------------
|
| Modul di bawah ini akan dibangun penuh pada Phase 9 (Upload Dokumen).
| Didaftarkan sekarang agar navbar/footer/tautan Home bisa memakai
| route() tanpa error sebelum modulnya selesai.
|
*/
Route::get('/upload', fn () => view('shared.coming-soon', ['title' => 'Upload Dokumen']))->name('upload.create');
Route::get('/pencarian', fn () => view('shared.coming-soon', ['title' => 'Pencarian']))->name('search.index');

Route::prefix('admin')->name('admin.')->group(base_path('routes/admin.php'));

<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
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

/*
|--------------------------------------------------------------------------
| Placeholder routes
|--------------------------------------------------------------------------
|
| Modul di bawah ini akan dibangun penuh pada Phase 7 (Tutorial),
| Phase 8 (Instrumen), dan Phase 9 (Upload Dokumen). Didaftarkan
| sekarang agar navbar/footer/tautan Home bisa memakai route() tanpa
| error sebelum modulnya selesai.
|
*/
Route::get('/tutorial', fn () => view('shared.coming-soon', ['title' => 'Tutorial']))->name('tutorials.index');
Route::get('/instrumen', fn () => view('shared.coming-soon', ['title' => 'Download Instrumen Supervisi']))->name('instruments.index');
Route::get('/upload', fn () => view('shared.coming-soon', ['title' => 'Upload Dokumen']))->name('upload.create');
Route::get('/pencarian', fn () => view('shared.coming-soon', ['title' => 'Pencarian']))->name('search.index');

Route::prefix('admin')->name('admin.')->group(base_path('routes/admin.php'));

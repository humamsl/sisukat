<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InstrumentController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TutorialController;
use App\Http\Controllers\UploadController;
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

Route::prefix('upload')->name('upload.')->group(function () {
    Route::get('/', [UploadController::class, 'create'])->name('create');
    Route::post('/', [UploadController::class, 'store'])->name('store')->middleware('throttle:6,1');
});

Route::get('/pencarian', [SearchController::class, 'index'])->name('search.index')->middleware('throttle:30,1');

Route::prefix('admin')->name('admin.')->group(base_path('routes/admin.php'));

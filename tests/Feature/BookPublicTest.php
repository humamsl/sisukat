<?php

use App\Models\Book;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('local');
    $this->user = User::factory()->create();
});

test('guest is redirected to login', function () {
    $this->get(route('books.index'))->assertRedirect(route('login'));
});

test('published books are listed on the public index', function () {
    Book::create(['title' => 'Buku Publik', 'slug' => 'buku-publik', 'status' => 'published']);
    Book::create(['title' => 'Buku Draft', 'slug' => 'buku-draft', 'status' => 'draft']);

    $response = $this->actingAs($this->user)->get(route('books.index'));

    $response->assertOk()->assertSee('Buku Publik')->assertDontSee('Buku Draft');
});

test('draft book detail page returns 404', function () {
    $book = Book::create(['title' => 'Buku Draft', 'slug' => 'buku-draft', 'status' => 'draft']);

    $this->actingAs($this->user)->get(route('books.show', $book))->assertNotFound();
});

test('downloading a book increments its counter and streams the file', function () {
    Storage::disk('local')->put('books/sample.pdf', '%PDF-1.4 fake content');
    $book = Book::create(['title' => 'Buku Unduh', 'slug' => 'buku-unduh', 'file' => 'books/sample.pdf', 'status' => 'published', 'download_count' => 0]);

    $response = $this->actingAs($this->user)->get(route('books.download', $book));

    $response->assertOk();
    expect($book->fresh()->download_count)->toBe(1);
});

test('downloading a book with a missing file does not increment the counter', function () {
    $book = Book::create(['title' => 'Buku Rusak', 'slug' => 'buku-rusak', 'file' => 'books/missing.pdf', 'status' => 'published', 'download_count' => 0]);

    $this->actingAs($this->user)->get(route('books.download', $book))->assertNotFound();

    expect($book->fresh()->download_count)->toBe(0);
});

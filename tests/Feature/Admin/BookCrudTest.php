<?php

use App\Models\Book;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('local');
    $this->admin = User::factory()->create();
});

test('admin can create a book with a pdf file and cover', function () {
    $category = Category::create(['name' => 'Panduan', 'slug' => 'panduan', 'type' => 'book']);

    $response = $this->actingAs($this->admin)->post(route('admin.books.store'), [
        'category_id' => $category->id,
        'title' => 'Buku Uji Coba',
        'description' => 'Deskripsi buku',
        'author' => 'Penulis Uji',
        'year' => 2026,
        'pages_count' => 40,
        'file' => UploadedFile::fake()->create('buku.pdf', 500, 'application/pdf'),
        'cover' => UploadedFile::fake()->image('cover.jpg'),
        'status' => 'published',
    ]);

    $response->assertRedirect(route('admin.books.index'));

    $book = Book::firstWhere('title', 'Buku Uji Coba');
    expect($book)->not->toBeNull();
    expect($book->slug)->toBe('buku-uji-coba');
    expect($book->file)->not->toBeNull();
    Storage::disk('local')->assertExists($book->file);
    Storage::disk('local')->assertExists($book->cover);
});

test('book creation rejects non-pdf files', function () {
    $response = $this->actingAs($this->admin)->post(route('admin.books.store'), [
        'title' => 'Buku Invalid',
        'file' => UploadedFile::fake()->create('malware.php', 10, 'application/x-php'),
        'status' => 'published',
    ]);

    $response->assertSessionHasErrors('file');
    expect(Book::where('title', 'Buku Invalid')->exists())->toBeFalse();
});

test('admin can update a book and replace its file without orphaning the old one', function () {
    $book = Book::create([
        'title' => 'Buku Lama', 'slug' => 'buku-lama', 'file' => 'books/old.pdf', 'status' => 'draft',
    ]);
    Storage::disk('local')->put('books/old.pdf', 'dummy');

    $response = $this->actingAs($this->admin)->put(route('admin.books.update', $book), [
        'title' => 'Buku Lama',
        'file' => UploadedFile::fake()->create('baru.pdf', 300, 'application/pdf'),
        'status' => 'published',
    ]);

    $response->assertRedirect(route('admin.books.index'));

    $book->refresh();
    expect($book->file)->not->toBe('books/old.pdf');
    Storage::disk('local')->assertMissing('books/old.pdf');
    Storage::disk('local')->assertExists($book->file);
});

test('admin can delete a book and its files are removed', function () {
    $book = Book::create(['title' => 'Buku Hapus', 'slug' => 'buku-hapus', 'file' => 'books/hapus.pdf', 'status' => 'published']);
    Storage::disk('local')->put('books/hapus.pdf', 'dummy');

    $response = $this->actingAs($this->admin)->delete(route('admin.books.destroy', $book));

    $response->assertRedirect(route('admin.books.index'));
    expect(Book::find($book->id))->toBeNull();
    Storage::disk('local')->assertMissing('books/hapus.pdf');
});

test('guest cannot manage books', function () {
    $this->get(route('admin.books.index'))->assertRedirect(route('login'));
    $this->post(route('admin.books.store'), [])->assertRedirect(route('login'));
});

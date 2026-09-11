<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreBookRequest;
use App\Http\Requests\Admin\UpdateBookRequest;
use App\Models\Book;
use App\Models\Category;
use App\Services\ActivityLogger;
use App\Services\FileUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BookController extends Controller
{
    public function __construct(private readonly FileUploadService $files) {}

    public function index(): View
    {
        $this->authorize('viewAny', Book::class);

        $books = Book::query()
            ->with('category')
            ->search(request('search'))
            ->when(request('category'), fn ($q) => $q->where('category_id', request('category')))
            ->when(request('status'), fn ($q) => $q->where('status', request('status')))
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.books.index', [
            'books' => $books,
            'categories' => Category::query()->ofType('book')->orderBy('name')->get(),
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', Book::class);

        return view('admin.books.create', [
            'categories' => Category::query()->ofType('book')->orderBy('name')->get(),
        ]);
    }

    public function store(StoreBookRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = Book::uniqueSlug($data['title']);
        $data['file'] = $this->files->store($request->file('file'), 'books');

        if ($request->hasFile('cover')) {
            $data['cover'] = $this->files->store($request->file('cover'), 'covers');
        }

        $book = Book::create($data);

        ActivityLogger::log('create', "Menambahkan buku \"{$book->title}\"", $book);

        return redirect()->route('admin.books.index')->with('status', 'Buku berhasil ditambahkan.');
    }

    public function edit(Book $book): View
    {
        $this->authorize('update', $book);

        return view('admin.books.edit', [
            'book' => $book,
            'categories' => Category::query()->ofType('book')->orderBy('name')->get(),
        ]);
    }

    public function update(UpdateBookRequest $request, Book $book): RedirectResponse
    {
        $data = $request->validated();

        if ($data['title'] !== $book->title) {
            $data['slug'] = Book::uniqueSlug($data['title'], $book->id);
        }

        if ($request->hasFile('file')) {
            $data['file'] = $this->files->replace($request->file('file'), 'books', $book->file);
        }

        if ($request->hasFile('cover')) {
            $data['cover'] = $this->files->replace($request->file('cover'), 'covers', $book->cover);
        }

        $book->update($data);

        ActivityLogger::log('update', "Memperbarui buku \"{$book->title}\"", $book);

        return redirect()->route('admin.books.index')->with('status', 'Buku berhasil diperbarui.');
    }

    public function destroy(Book $book): RedirectResponse
    {
        $this->authorize('delete', $book);

        $this->files->delete($book->file);
        $this->files->delete($book->cover);

        $title = $book->title;
        $book->delete();

        ActivityLogger::log('delete', "Menghapus buku \"{$title}\"");

        return redirect()->route('admin.books.index')->with('status', 'Buku berhasil dihapus.');
    }
}

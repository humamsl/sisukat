<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BookController extends Controller
{
    public function index(): View
    {
        $books = Book::query()
            ->published()
            ->with('category')
            ->search(request('search'))
            ->when(request('category'), fn ($q) => $q->where('category_id', request('category')))
            ->when(request('year'), fn ($q) => $q->where('year', request('year')))
            ->latest('id')
            ->paginate(9)
            ->withQueryString();

        return view('books.index', [
            'books' => $books,
            'categories' => Category::query()->ofType('book')->orderBy('name')->get(),
            'years' => Book::query()->published()->whereNotNull('year')->distinct()->orderByDesc('year')->pluck('year'),
        ]);
    }

    public function show(Book $book): View
    {
        abort_unless($book->status === 'published', 404);

        return view('books.show', ['book' => $book]);
    }

    public function read(Book $book): View
    {
        abort_unless($book->status === 'published' && $book->file, 404);

        return view('books.read', ['book' => $book]);
    }

    public function file(Book $book): StreamedResponse
    {
        abort_unless($book->status === 'published' && $book->file, 404);
        abort_unless(Storage::disk('local')->exists($book->file), 404);

        return Storage::disk('local')->response($book->file, null, [
            'Content-Disposition' => 'inline',
        ]);
    }

    public function cover(Book $book): StreamedResponse|Response
    {
        if (! $book->cover || ! Storage::disk('local')->exists($book->cover)) {
            abort(404);
        }

        return Storage::disk('local')->response($book->cover, null, [
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }

    public function download(Book $book): StreamedResponse
    {
        abort_unless($book->status === 'published' && $book->file, 404);
        abort_unless(Storage::disk('local')->exists($book->file), 404);

        $book->increment('download_count');

        $filename = Str::slug($book->title).'.pdf';

        return Storage::disk('local')->download($book->file, $filename);
    }
}

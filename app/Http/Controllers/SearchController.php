<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Instrument;
use App\Models\Tutorial;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function index(): View
    {
        $term = trim((string) request('q', ''));
        $page = (int) request('page', 1);
        $perPage = 10;

        $results = collect();

        if ($term !== '') {
            $results = $results
                ->concat(
                    Book::query()->published()->search($term)->get()->map(fn (Book $book) => [
                        'type' => 'Buku Panduan',
                        'icon' => 'book-open',
                        'title' => $book->title,
                        'description' => $book->description,
                        'url' => route('books.show', $book),
                        'updated_at' => $book->updated_at,
                    ])
                )
                ->concat(
                    Tutorial::query()->published()->search($term)->get()->map(fn (Tutorial $tutorial) => [
                        'type' => 'Tutorial',
                        'icon' => 'video',
                        'title' => $tutorial->title,
                        'description' => $tutorial->description,
                        'url' => route('tutorials.show', $tutorial),
                        'updated_at' => $tutorial->updated_at,
                    ])
                )
                ->concat(
                    Instrument::query()->published()->search($term)->get()->map(fn (Instrument $instrument) => [
                        'type' => 'Instrumen',
                        'icon' => 'clipboard-list',
                        'title' => $instrument->title,
                        'description' => $instrument->description,
                        'url' => route('instruments.preview', $instrument),
                        'updated_at' => $instrument->updated_at,
                    ])
                )
                ->sortByDesc('updated_at')
                ->values();
        }

        $paginated = new LengthAwarePaginator(
            $results->forPage($page, $perPage)->values(),
            $results->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('search.index', ['results' => $paginated, 'term' => $term]);
    }
}

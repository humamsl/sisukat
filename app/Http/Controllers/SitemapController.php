<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Instrument;
use App\Models\Tutorial;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $urls = collect([
            ['url' => route('home'), 'updated_at' => now()],
            ['url' => route('pages.pendahuluan'), 'updated_at' => now()],
            ['url' => route('pages.petunjuk'), 'updated_at' => now()],
            ['url' => route('books.index'), 'updated_at' => now()],
            ['url' => route('tutorials.index'), 'updated_at' => now()],
            ['url' => route('instruments.index'), 'updated_at' => now()],
            ['url' => route('upload.create'), 'updated_at' => now()],
        ])
            ->concat(Book::query()->published()->get()->map(fn (Book $b) => ['url' => route('books.show', $b), 'updated_at' => $b->updated_at]))
            ->concat(Tutorial::query()->published()->get()->map(fn (Tutorial $t) => ['url' => route('tutorials.show', $t), 'updated_at' => $t->updated_at]))
            ->concat(Instrument::query()->published()->get()->map(fn (Instrument $i) => ['url' => route('instruments.preview', $i), 'updated_at' => $i->updated_at]));

        $xml = view('sitemap', ['urls' => $urls])->render();

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }
}

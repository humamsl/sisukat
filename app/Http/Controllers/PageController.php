<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\View\View;

class PageController extends Controller
{
    public function pendahuluan(): View
    {
        $page = Page::query()->published()->where('slug', 'pendahuluan')->firstOrFail();

        return view('pages.pendahuluan', ['page' => $page]);
    }

    public function petunjuk(): View
    {
        $page = Page::query()->published()->where('slug', 'petunjuk-penggunaan')->firstOrFail();

        return view('pages.petunjuk', ['page' => $page]);
    }
}

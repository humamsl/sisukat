<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $about = Page::query()->published()->where('slug', 'tentang-sisukat')->first();

        return view('home.index', ['about' => $about]);
    }
}

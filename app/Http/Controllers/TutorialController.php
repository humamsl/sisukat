<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Tutorial;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TutorialController extends Controller
{
    public function index(): View
    {
        $tutorials = Tutorial::query()
            ->published()
            ->with('category')
            ->search(request('search'))
            ->when(request('category'), fn ($q) => $q->where('category_id', request('category')))
            ->when(request('type'), fn ($q) => $q->where('type', request('type')))
            ->latest('id')
            ->paginate(9)
            ->withQueryString();

        return view('tutorials.index', [
            'tutorials' => $tutorials,
            'categories' => Category::query()->ofType('tutorial')->orderBy('name')->get(),
        ]);
    }

    public function show(Tutorial $tutorial): View
    {
        abort_unless($tutorial->status === 'published', 404);

        return view('tutorials.show', ['tutorial' => $tutorial]);
    }

    public function file(Tutorial $tutorial): StreamedResponse
    {
        abort_unless($tutorial->status === 'published' && $tutorial->file, 404);
        abort_unless(Storage::disk('local')->exists($tutorial->file), 404);

        return Storage::disk('local')->response($tutorial->file, null, ['Content-Disposition' => 'inline']);
    }

    public function thumbnail(Tutorial $tutorial): StreamedResponse
    {
        abort_unless($tutorial->thumbnail && Storage::disk('local')->exists($tutorial->thumbnail), 404);

        return Storage::disk('local')->response($tutorial->thumbnail, null, ['Cache-Control' => 'public, max-age=86400']);
    }
}

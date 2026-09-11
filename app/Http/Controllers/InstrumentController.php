<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Instrument;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class InstrumentController extends Controller
{
    public function index(): View
    {
        $instruments = Instrument::query()
            ->published()
            ->with('category')
            ->search(request('search'))
            ->when(request('category'), fn ($q) => $q->where('category_id', request('category')))
            ->when(request('format'), fn ($q) => $q->where('file_type', request('format')))
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('instruments.index', [
            'instruments' => $instruments,
            'categories' => Category::query()->ofType('instrument')->orderBy('name')->get(),
        ]);
    }

    public function preview(Instrument $instrument): View
    {
        abort_unless($instrument->status === 'published', 404);

        return view('instruments.preview', ['instrument' => $instrument]);
    }

    public function file(Instrument $instrument): StreamedResponse
    {
        abort_unless($instrument->status === 'published', 404);
        abort_unless(Storage::disk('local')->exists($instrument->file), 404);

        return Storage::disk('local')->response($instrument->file, null, ['Content-Disposition' => 'inline']);
    }

    public function download(Instrument $instrument): StreamedResponse
    {
        abort_unless($instrument->status === 'published', 404);
        abort_unless(Storage::disk('local')->exists($instrument->file), 404);

        $instrument->increment('download_count');

        $filename = Str::slug($instrument->title).'.'.$instrument->file_type;

        return Storage::disk('local')->download($instrument->file, $filename);
    }
}

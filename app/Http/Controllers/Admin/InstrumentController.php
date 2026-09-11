<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreInstrumentRequest;
use App\Http\Requests\Admin\UpdateInstrumentRequest;
use App\Models\Category;
use App\Models\Instrument;
use App\Services\ActivityLogger;
use App\Services\FileUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\View\View;

class InstrumentController extends Controller
{
    public function __construct(private readonly FileUploadService $files) {}

    public function index(): View
    {
        $this->authorize('viewAny', Instrument::class);

        $instruments = Instrument::query()
            ->with('category')
            ->search(request('search'))
            ->when(request('category'), fn ($q) => $q->where('category_id', request('category')))
            ->when(request('status'), fn ($q) => $q->where('status', request('status')))
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.instruments.index', ['instruments' => $instruments]);
    }

    public function create(): View
    {
        $this->authorize('create', Instrument::class);

        return view('admin.instruments.create', [
            'categories' => Category::query()->ofType('instrument')->orderBy('name')->get(),
        ]);
    }

    public function store(StoreInstrumentRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = Instrument::uniqueSlug($data['title']);

        $file = $request->file('file');
        $data['file'] = $this->files->store($file, 'instruments');
        $data['file_type'] = $this->fileType($file);
        $data['file_size'] = $file->getSize();

        $instrument = Instrument::create($data);

        ActivityLogger::log('create', "Menambahkan instrumen \"{$instrument->title}\"", $instrument);

        return redirect()->route('admin.instruments.index')->with('status', 'Instrumen berhasil ditambahkan.');
    }

    public function edit(Instrument $instrument): View
    {
        $this->authorize('update', $instrument);

        return view('admin.instruments.edit', [
            'instrument' => $instrument,
            'categories' => Category::query()->ofType('instrument')->orderBy('name')->get(),
        ]);
    }

    public function update(UpdateInstrumentRequest $request, Instrument $instrument): RedirectResponse
    {
        $data = $request->validated();

        if ($data['title'] !== $instrument->title) {
            $data['slug'] = Instrument::uniqueSlug($data['title'], $instrument->id);
        }

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $data['file'] = $this->files->replace($file, 'instruments', $instrument->file);
            $data['file_type'] = $this->fileType($file);
            $data['file_size'] = $file->getSize();
        }

        $instrument->update($data);

        ActivityLogger::log('update', "Memperbarui instrumen \"{$instrument->title}\"", $instrument);

        return redirect()->route('admin.instruments.index')->with('status', 'Instrumen berhasil diperbarui.');
    }

    public function destroy(Instrument $instrument): RedirectResponse
    {
        $this->authorize('delete', $instrument);

        $this->files->delete($instrument->file);

        $title = $instrument->title;
        $instrument->delete();

        ActivityLogger::log('delete', "Menghapus instrumen \"{$title}\"");

        return redirect()->route('admin.instruments.index')->with('status', 'Instrumen berhasil dihapus.');
    }

    private function fileType(UploadedFile $file): string
    {
        return strtolower($file->getClientOriginalExtension());
    }
}

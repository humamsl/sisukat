<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTutorialRequest;
use App\Http\Requests\Admin\UpdateTutorialRequest;
use App\Models\Category;
use App\Models\Tutorial;
use App\Services\ActivityLogger;
use App\Services\FileUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TutorialController extends Controller
{
    public function __construct(private readonly FileUploadService $files) {}

    public function index(): View
    {
        $this->authorize('viewAny', Tutorial::class);

        $tutorials = Tutorial::query()
            ->with('category')
            ->search(request('search'))
            ->when(request('type'), fn ($q) => $q->where('type', request('type')))
            ->when(request('status'), fn ($q) => $q->where('status', request('status')))
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('admin.tutorials.index', ['tutorials' => $tutorials]);
    }

    public function create(): View
    {
        $this->authorize('create', Tutorial::class);

        return view('admin.tutorials.create', [
            'categories' => Category::query()->ofType('tutorial')->orderBy('name')->get(),
        ]);
    }

    public function store(StoreTutorialRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = Tutorial::uniqueSlug($data['title']);

        if ($request->hasFile('file')) {
            $data['file'] = $this->files->store($request->file('file'), 'tutorials');
        }

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $this->files->store($request->file('thumbnail'), 'thumbnails');
        }

        $tutorial = Tutorial::create($data);

        ActivityLogger::log('create', "Menambahkan tutorial \"{$tutorial->title}\"", $tutorial);

        return redirect()->route('admin.tutorials.index')->with('status', 'Tutorial berhasil ditambahkan.');
    }

    public function edit(Tutorial $tutorial): View
    {
        $this->authorize('update', $tutorial);

        return view('admin.tutorials.edit', [
            'tutorial' => $tutorial,
            'categories' => Category::query()->ofType('tutorial')->orderBy('name')->get(),
        ]);
    }

    public function update(UpdateTutorialRequest $request, Tutorial $tutorial): RedirectResponse
    {
        $data = $request->validated();

        if ($data['title'] !== $tutorial->title) {
            $data['slug'] = Tutorial::uniqueSlug($data['title'], $tutorial->id);
        }

        if ($request->hasFile('file')) {
            $data['file'] = $this->files->replace($request->file('file'), 'tutorials', $tutorial->file);
        }

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $this->files->replace($request->file('thumbnail'), 'thumbnails', $tutorial->thumbnail);
        }

        $tutorial->update($data);

        ActivityLogger::log('update', "Memperbarui tutorial \"{$tutorial->title}\"", $tutorial);

        return redirect()->route('admin.tutorials.index')->with('status', 'Tutorial berhasil diperbarui.');
    }

    public function destroy(Tutorial $tutorial): RedirectResponse
    {
        $this->authorize('delete', $tutorial);

        $this->files->delete($tutorial->file);
        $this->files->delete($tutorial->thumbnail);

        $title = $tutorial->title;
        $tutorial->delete();

        ActivityLogger::log('delete', "Menghapus tutorial \"{$title}\"");

        return redirect()->route('admin.tutorials.index')->with('status', 'Tutorial berhasil dihapus.');
    }
}

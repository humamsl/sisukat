<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Upload;
use App\Services\ActivityLogger;
use App\Services\FileUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class UploadController extends Controller
{
    public function __construct(private readonly FileUploadService $files) {}

    public function index(): View
    {
        $this->authorize('viewAny', Upload::class);

        $uploads = Upload::query()
            ->when(request('search'), function ($q) {
                $term = '%'.mb_strtolower(request('search')).'%';
                $q->where(fn ($q2) => $q2
                    ->whereRaw('LOWER(name) LIKE ?', [$term])
                    ->orWhereRaw('LOWER(school) LIKE ?', [$term])
                    ->orWhereRaw('LOWER(document_type) LIKE ?', [$term]));
            })
            ->when(request('status'), fn ($q) => $q->where('status', request('status')))
            ->latest('uploaded_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.uploads.index', ['uploads' => $uploads]);
    }

    public function show(Upload $upload): View
    {
        $this->authorize('viewAny', Upload::class);

        return view('admin.uploads.show', ['upload' => $upload]);
    }

    public function updateStatus(Request $request, Upload $upload): RedirectResponse
    {
        $this->authorize('update', $upload);

        $validated = $request->validate([
            'status' => ['required', Rule::in(['pending', 'reviewed', 'archived'])],
        ]);

        $upload->update($validated);

        ActivityLogger::log('update', "Mengubah status dokumen \"{$upload->original_filename}\" menjadi {$validated['status']}", $upload);

        return back()->with('status', 'Status dokumen berhasil diperbarui.');
    }

    public function download(Upload $upload): StreamedResponse
    {
        $this->authorize('viewAny', Upload::class);
        abort_unless(Storage::disk('local')->exists($upload->file), 404);

        ActivityLogger::log('download', "Mengunduh dokumen \"{$upload->original_filename}\"", $upload);

        return Storage::disk('local')->download($upload->file, $upload->original_filename);
    }

    public function destroy(Upload $upload): RedirectResponse
    {
        $this->authorize('delete', $upload);

        $this->files->delete($upload->file);

        $name = $upload->original_filename;
        $upload->delete();

        ActivityLogger::log('delete', "Menghapus dokumen \"{$name}\"");

        return redirect()->route('admin.uploads.index')->with('status', 'Dokumen berhasil dihapus.');
    }
}

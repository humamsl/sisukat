<?php

namespace App\Http\Controllers;

use App\Http\Requests\Public\UploadDocumentRequest;
use App\Models\Upload;
use App\Models\User;
use App\Notifications\NewUploadSubmitted;
use App\Services\ActivityLogger;
use App\Services\FileUploadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;

class UploadController extends Controller
{
    public function __construct(private readonly FileUploadService $files) {}

    public function create(): View
    {
        return view('upload.create');
    }

    public function store(UploadDocumentRequest $request): RedirectResponse|JsonResponse
    {
        $data = $request->validated();
        unset($data['agreement']);

        $file = $request->file('file');
        $data['file'] = $this->files->store($file, 'uploads');
        $data['original_filename'] = $file->getClientOriginalName();
        $data['file_size'] = $file->getSize();
        $data['mime_type'] = $file->getMimeType();
        $data['status'] = 'pending';
        $data['ip_address'] = $request->ip();
        $data['uploaded_at'] = now();

        $upload = Upload::create($data);

        ActivityLogger::log('upload', "Dokumen baru dikirim oleh {$upload->name}", $upload);

        Notification::send(
            User::query()->where('is_active', true)->whereIn('role', [User::ROLE_SUPER_ADMIN, User::ROLE_ADMIN])->get(),
            new NewUploadSubmitted($upload)
        );

        $message = 'Dokumen berhasil dikirim.';

        if ($request->wantsJson()) {
            return response()->json(['message' => $message, 'redirect' => route('upload.create')]);
        }

        return redirect()->route('upload.create')->with('status', $message);
    }
}

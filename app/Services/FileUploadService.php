<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadService
{
    public const DISK = 'local';

    /**
     * Store an uploaded file under storage/app/private/{directory} with a
     * random filename (never the client-supplied original name), and
     * return the stored relative path.
     */
    public function store(UploadedFile $file, string $directory): string
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $filename = Str::uuid()->toString().($extension ? '.'.$extension : '');

        return $file->storeAs($directory, $filename, self::DISK);
    }

    /**
     * Replace an existing stored file with a newly uploaded one, deleting
     * the old file only after the new one is safely stored.
     */
    public function replace(UploadedFile $file, string $directory, ?string $oldPath): string
    {
        $newPath = $this->store($file, $directory);

        $this->delete($oldPath);

        return $newPath;
    }

    public function delete(?string $path): void
    {
        if ($path && Storage::disk(self::DISK)->exists($path)) {
            Storage::disk(self::DISK)->delete($path);
        }
    }
}

<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadService
{
    /**
     * Upload a file to storage
     *
     * @param UploadedFile $file
     * @param string $path
     * @param string|null $oldPath
     * @return string
     */
    public function upload(UploadedFile $file, string $path, ?string $oldPath = null): string
    {
        // Delete old file if exists
        if ($oldPath && Storage::disk('public')->exists($oldPath)) {
            Storage::disk('public')->delete($oldPath);
        }

        // Generate unique filename
        $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();

        // Store file
        $filePath = $file->storeAs($path, $filename, 'public');

        return $filePath;
    }

    /**
     * Upload multiple files
     *
     * @param array $files
     * @param string $path
     * @return array
     */
    public function uploadMultiple(array $files, string $path): array
    {
        $uploadedFiles = [];

        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                $uploadedFiles[] = $this->upload($file, $path);
            }
        }

        return $uploadedFiles;
    }

    /**
     * Delete file from storage
     *
     * @param string $path
     * @return bool
     */
    public function delete(string $path): bool
    {
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }

        return false;
    }

    /**
     * Get file URL
     *
     * @param string $path
     * @return string
     */
    public function getUrl(string $path): string
    {
        return Storage::disk('public')->url($path);
    }
}

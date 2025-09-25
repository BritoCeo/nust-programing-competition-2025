<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadService
{
    /**
     * Upload a single file
     */
    public function uploadFile(UploadedFile $file, string $directory = 'uploads', string $disk = 'public'): array
    {
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $filename = Str::uuid() . '.' . $extension;
        
        $path = $file->storeAs($directory, $filename, $disk);
        
        return [
            'original_name' => $originalName,
            'filename' => $filename,
            'path' => $path,
            'size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'extension' => $extension,
            'url' => Storage::disk($disk)->url($path)
        ];
    }

    /**
     * Upload multiple files
     */
    public function uploadFiles(array $files, string $directory = 'uploads', string $disk = 'public'): array
    {
        $uploadedFiles = [];
        
        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                $uploadedFiles[] = $this->uploadFile($file, $directory, $disk);
            }
        }
        
        return $uploadedFiles;
    }

    /**
     * Delete a file
     */
    public function deleteFile(string $path, string $disk = 'public'): bool
    {
        if (Storage::disk($disk)->exists($path)) {
            return Storage::disk($disk)->delete($path);
        }
        
        return false;
    }

    /**
     * Get file info
     */
    public function getFileInfo(string $path, string $disk = 'public'): array
    {
        if (!Storage::disk($disk)->exists($path)) {
            return [];
        }
        
        return [
            'path' => $path,
            'size' => Storage::disk($disk)->size($path),
            'mime_type' => Storage::disk($disk)->mimeType($path),
            'last_modified' => Storage::disk($disk)->lastModified($path),
            'url' => Storage::disk($disk)->url($path)
        ];
    }

    /**
     * Validate file type
     */
    public function validateFileType(UploadedFile $file, array $allowedTypes = ['pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx']): bool
    {
        $extension = strtolower($file->getClientOriginalExtension());
        return in_array($extension, $allowedTypes);
    }

    /**
     * Validate file size
     */
    public function validateFileSize(UploadedFile $file, int $maxSizeInMB = 10): bool
    {
        $maxSizeInBytes = $maxSizeInMB * 1024 * 1024;
        return $file->getSize() <= $maxSizeInBytes;
    }

    /**
     * Generate secure filename
     */
    public function generateSecureFilename(string $originalName): string
    {
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        return Str::uuid() . '.' . $extension;
    }

    /**
     * Create directory if it doesn't exist
     */
    public function ensureDirectoryExists(string $directory, string $disk = 'public'): bool
    {
        if (!Storage::disk($disk)->exists($directory)) {
            return Storage::disk($disk)->makeDirectory($directory);
        }
        
        return true;
    }
}

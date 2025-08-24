<?php

namespace App\Traits;

use App\Services\ImageOptimizationService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait HandlesImageUploads
{
    /**
     * Upload and optimize an image
     */
    protected function uploadOptimizedImage(UploadedFile $file, string $directory = 'images'): array
    {
        $imageService = app(ImageOptimizationService::class);
        return $imageService->processImage($file, $directory);
    }

    /**
     * Delete an optimized image and all its variants
     */
    protected function deleteOptimizedImage(string $imagePath): void
    {
        $imageService = app(ImageOptimizationService::class);
        $imageService->deleteImage($imagePath);
    }

    /**
     * Validate image upload
     */
    protected function validateImageUpload(UploadedFile $file): bool
    {
        // Check file size (max 10MB)
        if ($file->getSize() > 10 * 1024 * 1024) {
            return false;
        }

        // Check file type
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
        if (!in_array($file->getMimeType(), $allowedTypes)) {
            return false;
        }

        // Check image dimensions (max 4000x4000)
        $imageInfo = getimagesize($file->getPathname());
        if ($imageInfo && ($imageInfo[0] > 4000 || $imageInfo[1] > 4000)) {
            return false;
        }

        return true;
    }

    /**
     * Get validation rules for image uploads
     */
    protected function getImageValidationRules(): array
    {
        return [
            'image' => 'required|image|mimes:jpeg,jpg,png,webp|max:10240|dimensions:max_width=4000,max_height=4000',
        ];
    }

    /**
     * Get validation messages for image uploads
     */
    protected function getImageValidationMessages(): array
    {
        return [
            'image.required' => 'Please select an image to upload.',
            'image.image' => 'The file must be an image.',
            'image.mimes' => 'The image must be a JPEG, PNG, or WebP file.',
            'image.max' => 'The image must not be larger than 10MB.',
            'image.dimensions' => 'The image dimensions must not exceed 4000x4000 pixels.',
        ];
    }
}
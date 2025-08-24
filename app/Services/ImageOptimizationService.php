<?php

namespace App\Services;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class ImageOptimizationService
{
    protected ImageManager $manager;
    
    protected array $sizes;

    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());
        $this->sizes = array_merge(
            config('portfolio.images.optimization.sizes', []),
            ['original' => null] // Keep original size but optimize
        );
    }

    /**
     * Process and optimize an uploaded image
     */
    public function processImage(UploadedFile $file, string $directory = 'images'): array
    {
        $filename = $this->generateFilename($file);
        $paths = [];

        foreach ($this->sizes as $size => $dimensions) {
            $image = $this->manager->read($file->getPathname());
            
            if ($dimensions) {
                // Resize while maintaining aspect ratio
                $image->scaleDown($dimensions['width'], $dimensions['height']);
            }

            // Optimize image quality
            $quality = $this->getQualityForSize($size);
            
            // Save as WebP for better compression
            $webpPath = "{$directory}/{$size}_{$filename}.webp";
            $webpContent = $image->toWebp($quality);
            Storage::disk('public')->put($webpPath, $webpContent);
            $paths[$size]['webp'] = $webpPath;

            // Also save as original format for fallback
            $originalExt = strtolower($file->getClientOriginalExtension());
            $fallbackPath = "{$directory}/{$size}_{$filename}.{$originalExt}";
            
            if ($originalExt === 'jpg' || $originalExt === 'jpeg') {
                $fallbackContent = $image->toJpeg($quality);
            } elseif ($originalExt === 'png') {
                $fallbackContent = $image->toPng();
            } else {
                $fallbackContent = $image->toJpeg($quality);
            }
            
            Storage::disk('public')->put($fallbackPath, $fallbackContent);
            $paths[$size]['fallback'] = $fallbackPath;
        }

        return $paths;
    }

    /**
     * Generate a unique filename
     */
    protected function generateFilename(UploadedFile $file): string
    {
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $sanitized = preg_replace('/[^a-zA-Z0-9_-]/', '', $originalName);
        return $sanitized . '_' . time() . '_' . uniqid();
    }

    /**
     * Get quality setting based on image size
     */
    protected function getQualityForSize(string $size): int
    {
        $qualities = config('portfolio.images.optimization.quality', [
            'thumbnail' => 75,
            'medium' => 85,
            'large' => 90,
            'original' => 95,
        ]);

        return $qualities[$size] ?? 85;
    }

    /**
     * Delete all versions of an image
     */
    public function deleteImage(string $imagePath): void
    {
        $pathInfo = pathinfo($imagePath);
        $directory = $pathInfo['dirname'];
        $filename = $pathInfo['filename'];

        foreach ($this->sizes as $size => $dimensions) {
            // Remove size prefix if it exists
            $cleanFilename = preg_replace('/^(thumbnail|medium|large|original)_/', '', $filename);
            
            // Delete WebP versions
            $webpPath = "{$directory}/{$size}_{$cleanFilename}.webp";
            if (Storage::disk('public')->exists($webpPath)) {
                Storage::disk('public')->delete($webpPath);
            }

            // Delete fallback versions
            foreach (['jpg', 'jpeg', 'png'] as $ext) {
                $fallbackPath = "{$directory}/{$size}_{$cleanFilename}.{$ext}";
                if (Storage::disk('public')->exists($fallbackPath)) {
                    Storage::disk('public')->delete($fallbackPath);
                }
            }
        }
    }

    /**
     * Get responsive image sources for a given image path
     */
    public function getResponsiveImageSources(string $imagePath): array
    {
        $pathInfo = pathinfo($imagePath);
        $directory = $pathInfo['dirname'];
        $filename = $pathInfo['filename'];
        
        // Remove size prefix if it exists
        $cleanFilename = preg_replace('/^(thumbnail|medium|large|original)_/', '', $filename);
        
        $sources = [];
        
        foreach ($this->sizes as $size => $dimensions) {
            $webpPath = "{$directory}/{$size}_{$cleanFilename}.webp";
            $fallbackPath = "{$directory}/{$size}_{$cleanFilename}.jpg";
            
            if (Storage::disk('public')->exists($webpPath)) {
                $sources[$size] = [
                    'webp' => Storage::url($webpPath),
                    'fallback' => Storage::disk('public')->exists($fallbackPath) 
                        ? Storage::url($fallbackPath) 
                        : Storage::url($imagePath),
                    'width' => $dimensions['width'] ?? null,
                    'height' => $dimensions['height'] ?? null,
                ];
            }
        }
        
        return $sources;
    }
}
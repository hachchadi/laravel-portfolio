<?php

namespace App\Models;

use App\Services\ImageOptimizationService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'image_path',
        'alt_text',
        'sort_order',
    ];

    /**
     * Get the project that owns the image.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the full URL for the image.
     */
    public function getImageUrlAttribute(): string
    {
        return asset('storage/' . $this->image_path);
    }

    /**
     * Get responsive image sources for this image.
     */
    public function getResponsiveSourcesAttribute(): array
    {
        $imageService = app(ImageOptimizationService::class);
        return $imageService->getResponsiveImageSources($this->image_path);
    }

    /**
     * Get the optimized image URL for a specific size.
     */
    public function getOptimizedUrl(string $size = 'medium', string $format = 'webp'): string
    {
        $sources = $this->responsive_sources;
        
        if (isset($sources[$size][$format])) {
            return $sources[$size][$format];
        }
        
        if (isset($sources[$size]['fallback'])) {
            return $sources[$size]['fallback'];
        }
        
        return $this->image_url;
    }

    /**
     * Check if WebP version exists for a specific size.
     */
    public function hasWebPVersion(string $size = 'medium'): bool
    {
        $sources = $this->responsive_sources;
        return isset($sources[$size]['webp']);
    }
}

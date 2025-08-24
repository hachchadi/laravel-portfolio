<?php

namespace App\View\Components;

use App\Services\ImageOptimizationService;
use Illuminate\View\Component;
use Illuminate\View\View;

class ResponsiveImage extends Component
{
    public string $src;
    public string $alt;
    public string $class;
    public bool $lazy;
    public string $sizes;
    public array $imageSources;

    /**
     * Create a new component instance.
     */
    public function __construct(
        string $src,
        string $alt = '',
        string $class = '',
        bool $lazy = true,
        string $sizes = '(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 33vw'
    ) {
        $this->src = $src;
        $this->alt = $alt;
        $this->class = $class;
        $this->lazy = $lazy;
        $this->sizes = $sizes;
        
        // Get responsive image sources
        $imageService = app(ImageOptimizationService::class);
        $this->imageSources = $imageService->getResponsiveImageSources($src);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('components.responsive-image');
    }
}
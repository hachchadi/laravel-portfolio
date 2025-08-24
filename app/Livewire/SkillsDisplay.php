<?php

namespace App\Livewire;

use App\Models\Skill;
use App\Services\PortfolioCacheService;
use Livewire\Component;

class SkillsDisplay extends Component
{
    public $animateOnLoad = false;

    protected PortfolioCacheService $cacheService;

    public function boot(PortfolioCacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    public function mount()
    {
        // Don't store the collection in a property to avoid Livewire serialization issues
        // Instead, we'll load it fresh in the render method
        
        // Trigger animation after component mounts
        $this->dispatch('skills-loaded');
    }
    
    public function getSkillsByCategoryProperty()
    {
        return $this->cacheService->getSkillsByCategory();
    }

    public function startAnimation()
    {
        $this->animateOnLoad = true;
    }

    public function render()
    {
        return view('livewire.skills-display');
    }
}

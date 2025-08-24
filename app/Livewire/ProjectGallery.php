<?php

namespace App\Livewire;

use App\Models\Project;
use App\Services\PortfolioCacheService;
use Livewire\Component;

class ProjectGallery extends Component
{
    public $projects;
    public $selectedProject = null;
    public $showModal = false;
    public $currentImageIndex = 0;
    public $filterTechnology = '';
    public $animateOnLoad = false;

    protected PortfolioCacheService $cacheService;

    public function boot(PortfolioCacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    public function mount()
    {
        $this->loadProjects();
        $this->dispatch('projects-loaded');
    }

    public function loadProjects()
    {
        if ($this->filterTechnology) {
            // For filtered results, we can't use the cache effectively
            $this->projects = Project::published()
                ->with(['images' => function ($query) {
                    $query->orderBy('sort_order');
                }])
                ->whereJsonContains('technologies', $this->filterTechnology)
                ->orderBy('sort_order')
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            // Use cached projects for unfiltered results
            $this->projects = $this->cacheService->getProjects();
        }
    }

    public function filterByTechnology($technology)
    {
        $this->filterTechnology = $technology === $this->filterTechnology ? '' : $technology;
        $this->loadProjects();
    }

    public function showProject($projectId)
    {
        $this->selectedProject = $this->cacheService->getProject($projectId);
        $this->currentImageIndex = 0;
        $this->showModal = true;
        $this->dispatch('modal-opened');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->selectedProject = null;
        $this->currentImageIndex = 0;
        $this->dispatch('modal-closed');
    }

    public function nextImage()
    {
        if ($this->selectedProject && $this->selectedProject->images->count() > 0) {
            $this->currentImageIndex = ($this->currentImageIndex + 1) % $this->selectedProject->images->count();
        }
    }

    public function previousImage()
    {
        if ($this->selectedProject && $this->selectedProject->images->count() > 0) {
            $this->currentImageIndex = $this->currentImageIndex === 0 
                ? $this->selectedProject->images->count() - 1 
                : $this->currentImageIndex - 1;
        }
    }

    public function setCurrentImage($index)
    {
        $this->currentImageIndex = $index;
    }

    public function startAnimation()
    {
        $this->animateOnLoad = true;
    }

    public function getAllTechnologies()
    {
        return $this->cacheService->getAllTechnologies();
    }

    /**
     * Computed property for technologies to minimize DOM updates
     */
    public function getComputedTechnologiesProperty()
    {
        return $this->getAllTechnologies();
    }

    /**
     * Computed property for filtered projects count
     */
    public function getProjectsCountProperty()
    {
        return $this->projects->count();
    }

    /**
     * Check if we have any projects
     */
    public function getHasProjectsProperty()
    {
        return $this->projects->isNotEmpty();
    }

    public function render()
    {
        return view('livewire.project-gallery');
    }
}

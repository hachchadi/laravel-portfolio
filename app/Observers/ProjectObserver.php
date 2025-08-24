<?php

namespace App\Observers;

use App\Models\Project;
use App\Services\PortfolioCacheService;

class ProjectObserver
{
    protected PortfolioCacheService $cacheService;

    public function __construct(PortfolioCacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    /**
     * Handle the Project "created" event.
     */
    public function created(Project $project): void
    {
        $this->cacheService->clearProjectCaches();
    }

    /**
     * Handle the Project "updated" event.
     */
    public function updated(Project $project): void
    {
        $this->cacheService->clearProjectCache($project->id);
    }

    /**
     * Handle the Project "deleted" event.
     */
    public function deleted(Project $project): void
    {
        $this->cacheService->clearProjectCache($project->id);
    }

    /**
     * Handle the Project "restored" event.
     */
    public function restored(Project $project): void
    {
        $this->cacheService->clearProjectCaches();
    }

    /**
     * Handle the Project "force deleted" event.
     */
    public function forceDeleted(Project $project): void
    {
        $this->cacheService->clearProjectCache($project->id);
    }
}
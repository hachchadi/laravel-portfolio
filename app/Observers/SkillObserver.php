<?php

namespace App\Observers;

use App\Models\Skill;
use App\Services\PortfolioCacheService;

class SkillObserver
{
    protected PortfolioCacheService $cacheService;

    public function __construct(PortfolioCacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    /**
     * Handle the Skill "created" event.
     */
    public function created(Skill $skill): void
    {
        $this->cacheService->clearSkillsCache();
    }

    /**
     * Handle the Skill "updated" event.
     */
    public function updated(Skill $skill): void
    {
        $this->cacheService->clearSkillsCache();
    }

    /**
     * Handle the Skill "deleted" event.
     */
    public function deleted(Skill $skill): void
    {
        $this->cacheService->clearSkillsCache();
    }

    /**
     * Handle the Skill "restored" event.
     */
    public function restored(Skill $skill): void
    {
        $this->cacheService->clearSkillsCache();
    }

    /**
     * Handle the Skill "force deleted" event.
     */
    public function forceDeleted(Skill $skill): void
    {
        $this->cacheService->clearSkillsCache();
    }
}
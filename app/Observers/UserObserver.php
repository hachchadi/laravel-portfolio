<?php

namespace App\Observers;

use App\Models\User;
use App\Services\PortfolioCacheService;

class UserObserver
{
    protected PortfolioCacheService $cacheService;

    public function __construct(PortfolioCacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        $this->cacheService->clearUserCache();
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        $this->cacheService->clearUserCache();
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        $this->cacheService->clearUserCache();
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        $this->cacheService->clearUserCache();
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        $this->cacheService->clearUserCache();
    }
}
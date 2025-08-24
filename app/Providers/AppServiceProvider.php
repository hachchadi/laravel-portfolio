<?php

namespace App\Providers;

use App\Models\Project;
use App\Models\Skill;
use App\Models\User;
use App\Observers\ProjectObserver;
use App\Observers\SkillObserver;
use App\Observers\UserObserver;
use App\Services\ImageOptimizationService;
use App\Services\PortfolioCacheService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register services as singletons for better performance
        $this->app->singleton(ImageOptimizationService::class);
        $this->app->singleton(PortfolioCacheService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register model observers for cache invalidation
        Project::observe(ProjectObserver::class);
        Skill::observe(SkillObserver::class);
        User::observe(UserObserver::class);
    }
}

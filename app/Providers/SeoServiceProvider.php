<?php

namespace App\Providers;

use App\Services\SeoService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class SeoServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(SeoService::class, function ($app) {
            return new SeoService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Share SEO service with all views
        View::composer('*', function ($view) {
            $view->with('seoService', app(SeoService::class));
        });
    }
}

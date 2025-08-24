<?php

namespace App\Console\Commands;

use App\Services\PortfolioCacheService;
use Illuminate\Console\Command;

class ClearPortfolioCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'portfolio:cache:clear {--type=all : Type of cache to clear (all, projects, skills, user)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear portfolio cache data';

    /**
     * Execute the console command.
     */
    public function handle(PortfolioCacheService $cacheService)
    {
        $type = $this->option('type');

        $this->info('Clearing portfolio cache...');

        switch ($type) {
            case 'projects':
                $cacheService->clearProjectCaches();
                $this->info('Projects cache cleared successfully.');
                break;
            
            case 'skills':
                $cacheService->clearSkillsCache();
                $this->info('Skills cache cleared successfully.');
                break;
            
            case 'user':
                $cacheService->clearUserCache();
                $this->info('User cache cleared successfully.');
                break;
            
            case 'all':
            default:
                $cacheService->clearAll();
                $this->info('All portfolio cache cleared successfully.');
                break;
        }

        return 0;
    }
}

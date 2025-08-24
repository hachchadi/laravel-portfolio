<?php

namespace App\Console\Commands;

use App\Services\PortfolioCacheService;
use Illuminate\Console\Command;

class WarmPortfolioCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'portfolio:cache:warm';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Warm up portfolio cache with frequently accessed data';

    /**
     * Execute the console command.
     */
    public function handle(PortfolioCacheService $cacheService)
    {
        $this->info('Warming up portfolio cache...');

        $startTime = microtime(true);
        
        $cacheService->warmUp();
        
        $endTime = microtime(true);
        $duration = round(($endTime - $startTime) * 1000, 2);

        $this->info("Portfolio cache warmed up successfully in {$duration}ms.");

        // Show cache statistics
        $stats = $cacheService->getStats();
        $this->table(
            ['Metric', 'Value'],
            [
                ['Cached Keys', $stats['cached_keys']],
                ['Total Keys', $stats['total_keys']],
                ['Cache Hit Ratio', $stats['cache_hit_ratio'] . '%'],
            ]
        );

        return 0;
    }
}

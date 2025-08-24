<?php

namespace App\Console\Commands;

use App\Services\PortfolioCacheService;
use App\Models\Project;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PerformanceBenchmark extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'portfolio:benchmark {--iterations=10 : Number of iterations to run}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run performance benchmarks for portfolio operations';

    /**
     * Execute the console command.
     */
    public function handle(PortfolioCacheService $cacheService)
    {
        $iterations = (int) $this->option('iterations');
        
        $this->info("Running performance benchmarks with {$iterations} iterations...");
        $this->newLine();

        $results = [];

        // Benchmark database queries without cache
        $this->info('Testing database queries without cache...');
        $cacheService->clearAll();
        $results['db_without_cache'] = $this->benchmarkDatabaseQueries($iterations);

        // Benchmark database queries with cache
        $this->info('Testing database queries with cache...');
        $cacheService->warmUp();
        $results['db_with_cache'] = $this->benchmarkCachedQueries($cacheService, $iterations);

        // Benchmark image operations
        $this->info('Testing image operations...');
        $results['image_operations'] = $this->benchmarkImageOperations($iterations);

        // Display results
        $this->displayResults($results);

        return 0;
    }

    /**
     * Benchmark database queries without cache
     */
    private function benchmarkDatabaseQueries(int $iterations): array
    {
        $times = [];
        
        for ($i = 0; $i < $iterations; $i++) {
            $start = microtime(true);
            
            // Simulate typical portfolio page load
            Project::published()->with('images')->get();
            Skill::orderBy('category')->orderBy('sort_order')->get();
            User::first();
            
            $end = microtime(true);
            $times[] = ($end - $start) * 1000; // Convert to milliseconds
        }

        return [
            'avg' => round(array_sum($times) / count($times), 2),
            'min' => round(min($times), 2),
            'max' => round(max($times), 2),
            'total_queries' => DB::getQueryLog() ? count(DB::getQueryLog()) : 'N/A'
        ];
    }

    /**
     * Benchmark cached queries
     */
    private function benchmarkCachedQueries(PortfolioCacheService $cacheService, int $iterations): array
    {
        $times = [];
        
        for ($i = 0; $i < $iterations; $i++) {
            $start = microtime(true);
            
            // Simulate typical portfolio page load with cache
            $cacheService->getProjects();
            $cacheService->getSkillsByCategory();
            $cacheService->getUserProfile();
            
            $end = microtime(true);
            $times[] = ($end - $start) * 1000; // Convert to milliseconds
        }

        return [
            'avg' => round(array_sum($times) / count($times), 2),
            'min' => round(min($times), 2),
            'max' => round(max($times), 2),
            'cache_hit_ratio' => $cacheService->getStats()['cache_hit_ratio'] . '%'
        ];
    }

    /**
     * Benchmark image operations
     */
    private function benchmarkImageOperations(int $iterations): array
    {
        $times = [];
        
        // Create a small test image in memory
        $testImagePath = storage_path('app/test_image.jpg');
        if (!file_exists($testImagePath)) {
            // Create a simple test image
            $image = imagecreate(100, 100);
            $white = imagecolorallocate($image, 255, 255, 255);
            imagejpeg($image, $testImagePath);
            imagedestroy($image);
        }

        for ($i = 0; $i < min($iterations, 3); $i++) { // Limit image operations
            $start = microtime(true);
            
            // Simulate image optimization operations
            if (file_exists($testImagePath)) {
                $imageInfo = getimagesize($testImagePath);
                // Simulate some image processing time
                usleep(1000); // 1ms
            }
            
            $end = microtime(true);
            $times[] = ($end - $start) * 1000;
        }

        // Clean up test image
        if (file_exists($testImagePath)) {
            unlink($testImagePath);
        }

        return [
            'avg' => count($times) > 0 ? round(array_sum($times) / count($times), 2) : 0,
            'min' => count($times) > 0 ? round(min($times), 2) : 0,
            'max' => count($times) > 0 ? round(max($times), 2) : 0,
            'operations' => count($times)
        ];
    }

    /**
     * Display benchmark results
     */
    private function displayResults(array $results): void
    {
        $this->newLine();
        $this->info('Benchmark Results:');
        $this->newLine();

        // Database without cache
        $this->table(
            ['Metric', 'Value'],
            [
                ['Database Queries (No Cache) - Average', $results['db_without_cache']['avg'] . 'ms'],
                ['Database Queries (No Cache) - Min', $results['db_without_cache']['min'] . 'ms'],
                ['Database Queries (No Cache) - Max', $results['db_without_cache']['max'] . 'ms'],
            ]
        );

        // Database with cache
        $this->table(
            ['Metric', 'Value'],
            [
                ['Database Queries (With Cache) - Average', $results['db_with_cache']['avg'] . 'ms'],
                ['Database Queries (With Cache) - Min', $results['db_with_cache']['min'] . 'ms'],
                ['Database Queries (With Cache) - Max', $results['db_with_cache']['max'] . 'ms'],
                ['Cache Hit Ratio', $results['db_with_cache']['cache_hit_ratio']],
            ]
        );

        // Performance improvement
        $improvement = round((($results['db_without_cache']['avg'] - $results['db_with_cache']['avg']) / $results['db_without_cache']['avg']) * 100, 1);
        $this->info("Performance improvement with cache: {$improvement}%");

        // Image operations
        $this->newLine();
        $this->table(
            ['Metric', 'Value'],
            [
                ['Image Operations - Average', $results['image_operations']['avg'] . 'ms'],
                ['Image Operations - Min', $results['image_operations']['min'] . 'ms'],
                ['Image Operations - Max', $results['image_operations']['max'] . 'ms'],
                ['Operations Tested', $results['image_operations']['operations']],
            ]
        );
    }
}

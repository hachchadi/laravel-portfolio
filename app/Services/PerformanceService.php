<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PerformanceService
{
    protected array $metrics = [];
    protected float $startTime;

    public function __construct()
    {
        $this->startTime = microtime(true);
    }

    /**
     * Start timing a specific operation
     */
    public function startTimer(string $operation): void
    {
        $this->metrics[$operation] = [
            'start' => microtime(true),
            'memory_start' => memory_get_usage(true),
        ];
    }

    /**
     * End timing a specific operation
     */
    public function endTimer(string $operation): float
    {
        if (!isset($this->metrics[$operation])) {
            return 0;
        }

        $endTime = microtime(true);
        $endMemory = memory_get_usage(true);

        $this->metrics[$operation]['end'] = $endTime;
        $this->metrics[$operation]['memory_end'] = $endMemory;
        $this->metrics[$operation]['duration'] = $endTime - $this->metrics[$operation]['start'];
        $this->metrics[$operation]['memory_used'] = $endMemory - $this->metrics[$operation]['memory_start'];

        return $this->metrics[$operation]['duration'];
    }

    /**
     * Get performance metrics
     */
    public function getMetrics(): array
    {
        $totalTime = microtime(true) - $this->startTime;
        $peakMemory = memory_get_peak_usage(true);
        $currentMemory = memory_get_usage(true);

        return [
            'total_time' => round($totalTime * 1000, 2), // Convert to milliseconds
            'peak_memory' => $this->formatBytes($peakMemory),
            'current_memory' => $this->formatBytes($currentMemory),
            'operations' => $this->metrics,
        ];
    }

    /**
     * Log performance metrics
     */
    public function logMetrics(string $context = 'general'): void
    {
        $metrics = $this->getMetrics();
        
        Log::info("Performance metrics for {$context}", $metrics);

        // Store in cache for monitoring dashboard
        $cacheKey = "performance.{$context}." . date('Y-m-d-H');
        $cachedMetrics = Cache::get($cacheKey, []);
        $cachedMetrics[] = [
            'timestamp' => now()->toISOString(),
            'metrics' => $metrics,
        ];

        // Keep only last 100 entries per hour
        if (count($cachedMetrics) > 100) {
            $cachedMetrics = array_slice($cachedMetrics, -100);
        }

        Cache::put($cacheKey, $cachedMetrics, 3600); // Cache for 1 hour
    }

    /**
     * Check Core Web Vitals thresholds
     */
    public function checkCoreWebVitals(array $vitals): array
    {
        $results = [];

        // Largest Contentful Paint (LCP)
        if (isset($vitals['lcp'])) {
            $results['lcp'] = [
                'value' => $vitals['lcp'],
                'rating' => $vitals['lcp'] <= 2500 ? 'good' : ($vitals['lcp'] <= 4000 ? 'needs-improvement' : 'poor'),
                'threshold' => 2500,
            ];
        }

        // First Input Delay (FID)
        if (isset($vitals['fid'])) {
            $results['fid'] = [
                'value' => $vitals['fid'],
                'rating' => $vitals['fid'] <= 100 ? 'good' : ($vitals['fid'] <= 300 ? 'needs-improvement' : 'poor'),
                'threshold' => 100,
            ];
        }

        // Cumulative Layout Shift (CLS)
        if (isset($vitals['cls'])) {
            $results['cls'] = [
                'value' => $vitals['cls'],
                'rating' => $vitals['cls'] <= 0.1 ? 'good' : ($vitals['cls'] <= 0.25 ? 'needs-improvement' : 'poor'),
                'threshold' => 0.1,
            ];
        }

        // First Contentful Paint (FCP)
        if (isset($vitals['fcp'])) {
            $results['fcp'] = [
                'value' => $vitals['fcp'],
                'rating' => $vitals['fcp'] <= 1800 ? 'good' : ($vitals['fcp'] <= 3000 ? 'needs-improvement' : 'poor'),
                'threshold' => 1800,
            ];
        }

        return $results;
    }

    /**
     * Generate performance recommendations
     */
    public function generateRecommendations(array $metrics): array
    {
        $recommendations = [];

        // Check total page load time
        if ($metrics['total_time'] > 3000) {
            $recommendations[] = [
                'type' => 'performance',
                'priority' => 'high',
                'message' => 'Page load time exceeds 3 seconds. Consider optimizing database queries and enabling caching.',
            ];
        }

        // Check memory usage
        $memoryMB = $this->bytesToMB($metrics['current_memory']);
        if ($memoryMB > 128) {
            $recommendations[] = [
                'type' => 'memory',
                'priority' => 'medium',
                'message' => 'High memory usage detected. Consider optimizing data loading and using pagination.',
            ];
        }

        // Check for slow operations
        foreach ($metrics['operations'] as $operation => $data) {
            if (isset($data['duration']) && $data['duration'] > 1) {
                $recommendations[] = [
                    'type' => 'operation',
                    'priority' => 'medium',
                    'message' => "Operation '{$operation}' is taking longer than 1 second. Consider optimization.",
                ];
            }
        }

        return $recommendations;
    }

    /**
     * Format bytes to human readable format
     */
    private function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, 2) . ' ' . $units[$pow];
    }

    /**
     * Convert formatted bytes string to MB
     */
    private function bytesToMB(string $bytes): float
    {
        $value = (float) $bytes;
        
        if (strpos($bytes, 'GB') !== false) {
            return $value * 1024;
        } elseif (strpos($bytes, 'KB') !== false) {
            return $value / 1024;
        } elseif (strpos($bytes, 'B') !== false && strpos($bytes, 'MB') === false) {
            return $value / (1024 * 1024);
        }
        
        return $value; // Assume MB
    }
}
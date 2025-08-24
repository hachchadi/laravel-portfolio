<?php

namespace App\Http\Middleware;

use App\Services\PerformanceService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PerformanceMonitoringMiddleware
{
    public function __construct(
        protected PerformanceService $performanceService
    ) {}

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip monitoring for admin routes and assets
        if ($request->is('admin/*') || $request->is('_debugbar/*') || $request->is('assets/*')) {
            return $next($request);
        }

        // Start performance monitoring
        $this->performanceService->startTimer('total_request');
        $this->performanceService->startTimer('database_queries');

        $response = $next($request);

        // End performance monitoring
        $this->performanceService->endTimer('database_queries');
        $totalTime = $this->performanceService->endTimer('total_request');

        // Log metrics if enabled and request took longer than threshold
        if (config('portfolio.performance.monitoring') && $totalTime > 1) {
            $context = $request->path() ?: 'homepage';
            $this->performanceService->logMetrics($context);
        }

        // Add performance headers in development
        if (config('app.debug')) {
            $metrics = $this->performanceService->getMetrics();
            $response->headers->set('X-Performance-Time', $metrics['total_time'] . 'ms');
            $response->headers->set('X-Performance-Memory', $metrics['peak_memory']);
        }

        return $response;
    }
}

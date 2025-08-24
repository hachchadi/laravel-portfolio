<?php

namespace App\Services;

use App\Models\Project;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class PortfolioCacheService
{
    protected int $defaultTtl;
    protected int $longTtl;
    protected string $cachePrefix;

    public function __construct()
    {
        $this->defaultTtl = config('portfolio.cache.default_ttl', 3600);
        $this->longTtl = config('portfolio.cache.long_ttl', 86400);
        $this->cachePrefix = config('portfolio.cache.prefix', 'portfolio');
    }

    /**
     * Get cached projects with images
     */
    public function getProjects(bool $publishedOnly = true): \Illuminate\Database\Eloquent\Collection
    {
        $cacheKey = $publishedOnly ? "{$this->cachePrefix}.projects.published" : "{$this->cachePrefix}.projects.all";
        
        return Cache::remember($cacheKey, $this->defaultTtl, function () use ($publishedOnly) {
            $query = Project::with(['images' => function ($query) {
                $query->orderBy('sort_order');
            }])->orderBy('sort_order')->orderBy('created_at', 'desc');
            
            if ($publishedOnly) {
                $query->published();
            }
            
            return $query->get();
        });
    }

    /**
     * Get cached featured projects
     */
    public function getFeaturedProjects(): \Illuminate\Database\Eloquent\Collection
    {
        return Cache::remember('portfolio.projects.featured', $this->defaultTtl, function () {
            return Project::published()
                ->featured()
                ->with(['images' => function ($query) {
                    $query->orderBy('sort_order');
                }])
                ->orderBy('sort_order')
                ->orderBy('created_at', 'desc')
                ->get();
        });
    }

    /**
     * Get cached skills grouped by category
     */
    public function getSkillsByCategory(): \Illuminate\Support\Collection
    {
        return Cache::remember('portfolio.skills.by_category', $this->defaultTtl, function () {
            return Skill::orderBy('category')
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get()
                ->groupBy('category');
        });
    }

    /**
     * Get cached user profile
     */
    public function getUserProfile(): ?User
    {
        return Cache::remember('portfolio.user.profile', $this->longTtl, function () {
            return User::first();
        });
    }

    /**
     * Get cached project technologies
     */
    public function getAllTechnologies(): \Illuminate\Support\Collection
    {
        return Cache::remember('portfolio.technologies.all', $this->defaultTtl, function () {
            return Project::published()
                ->whereNotNull('technologies')
                ->pluck('technologies')
                ->flatten()
                ->unique()
                ->sort()
                ->values();
        });
    }

    /**
     * Get cached project by ID
     */
    public function getProject(int $projectId): ?Project
    {
        $cacheKey = "portfolio.project.{$projectId}";
        
        return Cache::remember($cacheKey, $this->defaultTtl, function () use ($projectId) {
            return Project::with(['images' => function ($query) {
                $query->orderBy('sort_order');
            }])->find($projectId);
        });
    }

    /**
     * Clear all portfolio cache
     */
    public function clearAll(): void
    {
        $keys = [
            'portfolio.projects.published',
            'portfolio.projects.all',
            'portfolio.projects.featured',
            'portfolio.skills.by_category',
            'portfolio.user.profile',
            'portfolio.technologies.all',
        ];

        foreach ($keys as $key) {
            Cache::forget($key);
        }

        // Clear individual project caches
        $this->clearProjectCaches();
    }

    /**
     * Clear project-related cache
     */
    public function clearProjectCaches(): void
    {
        Cache::forget('portfolio.projects.published');
        Cache::forget('portfolio.projects.all');
        Cache::forget('portfolio.projects.featured');
        Cache::forget('portfolio.technologies.all');

        // Clear individual project caches
        $projectIds = Project::pluck('id');
        foreach ($projectIds as $id) {
            Cache::forget("portfolio.project.{$id}");
        }
    }

    /**
     * Clear skills cache
     */
    public function clearSkillsCache(): void
    {
        Cache::forget('portfolio.skills.by_category');
    }

    /**
     * Clear user profile cache
     */
    public function clearUserCache(): void
    {
        Cache::forget('portfolio.user.profile');
    }

    /**
     * Clear specific project cache
     */
    public function clearProjectCache(int $projectId): void
    {
        Cache::forget("portfolio.project.{$projectId}");
        $this->clearProjectCaches(); // Also clear list caches
    }

    /**
     * Warm up cache with frequently accessed data
     */
    public function warmUp(): void
    {
        // Warm up main data
        $this->getProjects();
        $this->getFeaturedProjects();
        $this->getSkillsByCategory();
        $this->getUserProfile();
        $this->getAllTechnologies();

        // Warm up individual project caches for featured projects
        $featuredProjects = $this->getFeaturedProjects();
        foreach ($featuredProjects as $project) {
            $this->getProject($project->id);
        }
    }

    /**
     * Get cache statistics
     */
    public function getStats(): array
    {
        $keys = [
            'portfolio.projects.published',
            'portfolio.projects.all',
            'portfolio.projects.featured',
            'portfolio.skills.by_category',
            'portfolio.user.profile',
            'portfolio.technologies.all',
        ];

        $stats = [
            'cached_keys' => 0,
            'total_keys' => count($keys),
            'cache_hit_ratio' => 0,
        ];

        foreach ($keys as $key) {
            if (Cache::has($key)) {
                $stats['cached_keys']++;
            }
        }

        $stats['cache_hit_ratio'] = $stats['total_keys'] > 0 
            ? round(($stats['cached_keys'] / $stats['total_keys']) * 100, 2) 
            : 0;

        return $stats;
    }
}
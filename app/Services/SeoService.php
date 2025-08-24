<?php

namespace App\Services;

use App\Models\User;
use App\Models\Project;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\URL;

class SeoService
{
    protected array $defaultMeta = [];
    protected array $meta = [];
    protected array $structuredData = [];

    public function __construct()
    {
        $this->initializeDefaults();
    }

    /**
     * Initialize default meta tags
     */
    protected function initializeDefaults(): void
    {
        $this->defaultMeta = [
            'title' => config('app.name', 'Laravel Portfolio'),
            'description' => 'Professional portfolio showcasing skills, projects, and experience',
            'keywords' => 'laravel, php, web development, portfolio, developer',
            'author' => 'Laravel Developer',
            'robots' => 'index, follow',
            'canonical' => URL::current(),
            'og:type' => 'website',
            'og:locale' => app()->getLocale(),
            'twitter:card' => 'summary_large_image',
        ];
    }

    /**
     * Set page-specific meta data
     */
    public function setMeta(array $meta): self
    {
        $this->meta = array_merge($this->defaultMeta, $meta);
        return $this;
    }

    /**
     * Get meta tag for specific key
     */
    public function getMeta(string $key, string $default = ''): string
    {
        return $this->meta[$key] ?? $this->defaultMeta[$key] ?? $default;
    }

    /**
     * Get all meta tags
     */
    public function getAllMeta(): array
    {
        return array_merge($this->defaultMeta, $this->meta);
    }

    /**
     * Generate meta tags for homepage
     */
    public function generateHomepageMeta(): array
    {
        try {
            $user = $this->getDeveloperProfile();
            
            $meta = [
                'title' => $user ? "{$user->name} - {$user->title}" : 'Senior Laravel Developer Portfolio',
                'description' => $user ? $user->bio : 'Professional Laravel developer specializing in web applications, Livewire, and modern PHP development.',
                'keywords' => 'laravel developer, php developer, web development, livewire, portfolio',
                'og:title' => $user ? "{$user->name} - Portfolio" : 'Laravel Developer Portfolio',
                'og:description' => $user ? $user->bio : 'Professional Laravel developer portfolio',
                'og:url' => URL::to('/'),
                'twitter:title' => $user ? "{$user->name} - Portfolio" : 'Laravel Developer Portfolio',
                'twitter:description' => $user ? $user->bio : 'Professional Laravel developer portfolio',
            ];

            if ($user && $user->avatar) {
                $meta['og:image'] = asset('storage/' . $user->avatar);
                $meta['twitter:image'] = asset('storage/' . $user->avatar);
            }

            return $this->setMeta($meta)->getAllMeta();
        } catch (\Exception $e) {
            \Log::error('SEO meta generation error: ' . $e->getMessage());
            return $this->defaultMeta;
        }
    }

    /**
     * Generate meta tags for project page
     */
    public function generateProjectMeta(Project $project): array
    {
        $meta = [
            'title' => "{$project->title} - Project Portfolio",
            'description' => $project->description,
            'keywords' => implode(', ', array_merge($project->technologies ?? [], ['project', 'portfolio'])),
            'og:title' => $project->title,
            'og:description' => $project->description,
            'og:type' => 'article',
            'og:url' => URL::current(),
            'twitter:title' => $project->title,
            'twitter:description' => $project->description,
        ];

        // Add project image if available
        $firstImage = $project->images()->first();
        if ($firstImage) {
            $meta['og:image'] = asset('storage/' . $firstImage->image_path);
            $meta['twitter:image'] = asset('storage/' . $firstImage->image_path);
        }

        return $this->setMeta($meta)->getAllMeta();
    }

    /**
     * Generate structured data for developer profile
     */
    public function generateDeveloperStructuredData(): array
    {
        try {
            $user = $this->getDeveloperProfile();
            
            if (!$user) {
                return [];
            }

            $structuredData = [
                '@context' => 'https://schema.org',
                '@type' => 'Person',
                'name' => $user->name,
                'jobTitle' => $user->title,
                'description' => $user->bio,
                'url' => URL::to('/'),
                'sameAs' => array_filter([
                    $user->linkedin_url,
                    $user->github_url,
                ]),
            ];

            if ($user->avatar) {
                $structuredData['image'] = asset('storage/' . $user->avatar);
            }

            if ($user->location) {
                $structuredData['address'] = [
                    '@type' => 'PostalAddress',
                    'addressLocality' => $user->location,
                ];
            }

            if ($user->email) {
                $structuredData['email'] = $user->email;
            }

            if ($user->phone) {
                $structuredData['telephone'] = $user->phone;
            }

            return $structuredData;
        } catch (\Exception $e) {
            \Log::error('Developer structured data generation error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Generate structured data for projects
     */
    public function generateProjectsStructuredData(): array
    {
        try {
            $projects = Project::where('status', 'published')
                ->where('featured', true)
                ->with('images')
                ->get();
            
            if ($projects->isEmpty()) {
                return [];
            }

            $structuredData = [
                '@context' => 'https://schema.org',
                '@type' => 'ItemList',
                'name' => 'Portfolio Projects',
                'description' => 'Featured projects and work samples',
                'numberOfItems' => $projects->count(),
                'itemListElement' => [],
            ];

            foreach ($projects as $index => $project) {
                $projectData = [
                    '@type' => 'CreativeWork',
                    'position' => $index + 1,
                    'name' => $project->title,
                    'description' => $project->description,
                    'url' => $project->demo_url,
                ];

                if ($project->github_url) {
                    $projectData['codeRepository'] = $project->github_url;
                }

                if ($project->technologies) {
                    $projectData['keywords'] = implode(', ', $project->technologies);
                }

                $firstImage = $project->images->first();
                if ($firstImage) {
                    $projectData['image'] = asset('storage/' . $firstImage->image_path);
                }

                $structuredData['itemListElement'][] = $projectData;
            }

            return $structuredData;
        } catch (\Exception $e) {
            \Log::error('Projects structured data generation error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Generate XML sitemap
     */
    public function generateSitemap(): string
    {
        return Cache::remember('portfolio.sitemap', 3600, function () {
            $urls = [
                [
                    'loc' => URL::to('/'),
                    'lastmod' => now()->toISOString(),
                    'changefreq' => 'weekly',
                    'priority' => '1.0',
                ],
                [
                    'loc' => URL::to('/#about'),
                    'lastmod' => now()->toISOString(),
                    'changefreq' => 'monthly',
                    'priority' => '0.8',
                ],
                [
                    'loc' => URL::to('/#skills'),
                    'lastmod' => now()->toISOString(),
                    'changefreq' => 'monthly',
                    'priority' => '0.8',
                ],
                [
                    'loc' => URL::to('/#projects'),
                    'lastmod' => now()->toISOString(),
                    'changefreq' => 'weekly',
                    'priority' => '0.9',
                ],
                [
                    'loc' => URL::to('/#contact'),
                    'lastmod' => now()->toISOString(),
                    'changefreq' => 'monthly',
                    'priority' => '0.7',
                ],
            ];

            $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
            $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

            foreach ($urls as $url) {
                $xml .= '  <url>' . PHP_EOL;
                $xml .= '    <loc>' . htmlspecialchars($url['loc']) . '</loc>' . PHP_EOL;
                $xml .= '    <lastmod>' . $url['lastmod'] . '</lastmod>' . PHP_EOL;
                $xml .= '    <changefreq>' . $url['changefreq'] . '</changefreq>' . PHP_EOL;
                $xml .= '    <priority>' . $url['priority'] . '</priority>' . PHP_EOL;
                $xml .= '  </url>' . PHP_EOL;
            }

            $xml .= '</urlset>';

            return $xml;
        });
    }

    /**
     * Get developer profile with caching
     */
    protected function getDeveloperProfile(): ?User
    {
        try {
            return Cache::remember('portfolio.developer_profile', 3600, function () {
                return User::where('is_admin', true)->first() ?? User::first();
            });
        } catch (\Exception $e) {
            \Log::error('Developer profile retrieval error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Clear SEO cache
     */
    public function clearCache(): void
    {
        Cache::forget('portfolio.developer_profile');
        Cache::forget('portfolio.sitemap');
    }
}
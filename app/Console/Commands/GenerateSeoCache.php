<?php

namespace App\Console\Commands;

use App\Services\SeoService;
use Illuminate\Console\Command;

class GenerateSeoCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seo:cache {--clear : Clear existing SEO cache}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate and cache SEO data for better performance';

    /**
     * Execute the console command.
     */
    public function handle(SeoService $seoService): int
    {
        if ($this->option('clear')) {
            $this->info('Clearing SEO cache...');
            $seoService->clearCache();
            $this->info('SEO cache cleared successfully.');
            return self::SUCCESS;
        }

        $this->info('Generating SEO cache...');

        try {
            // Generate homepage meta tags
            $this->line('Generating homepage meta tags...');
            $seoService->generateHomepageMeta();

            // Generate structured data
            $this->line('Generating structured data...');
            $seoService->generateDeveloperStructuredData();
            $seoService->generateProjectsStructuredData();

            // Generate sitemap
            $this->line('Generating sitemap...');
            $seoService->generateSitemap();

            $this->info('SEO cache generated successfully!');
            return self::SUCCESS;

        } catch (\Exception $e) {
            $this->error('Failed to generate SEO cache: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}

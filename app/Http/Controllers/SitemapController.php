<?php

namespace App\Http\Controllers;

use App\Services\SeoService;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __construct(
        protected SeoService $seoService
    ) {}

    /**
     * Generate and return XML sitemap
     */
    public function index(): Response
    {
        $sitemap = $this->seoService->generateSitemap();

        return response($sitemap, 200, [
            'Content-Type' => 'application/xml',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }
}

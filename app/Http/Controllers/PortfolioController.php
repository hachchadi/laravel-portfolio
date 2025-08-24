<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Project;
use App\Models\Skill;
use App\Services\SeoService;

class PortfolioController extends Controller
{
    public function __construct(
        protected SeoService $seoService
    ) {}

    public function index()
    {
        try {
            // Get the developer's profile (assuming first user is the portfolio owner)
            $developer = User::first();
            
            // Get featured projects
            $featuredProjects = Project::where('featured', true)
                ->where('status', 'published')
                ->orderBy('sort_order')
                ->take(6)
                ->get();
            
            // Get skills grouped by category
            $skills = Skill::orderBy('category')
                ->orderBy('sort_order')
                ->get()
                ->groupBy('category');

            

            // Generate SEO meta tags and structured data
            $meta = $this->seoService->generateHomepageMeta();
            $structuredData = [
                $this->seoService->generateDeveloperStructuredData(),
                $this->seoService->generateProjectsStructuredData(),
            ];

            // Filter out empty structured data
            $structuredData = array_filter($structuredData);

            // dd($featuredProjects,$skills,$structuredData);
            
            return view('portfolio.index', compact('developer', 'featuredProjects', 'skills', 'meta', 'structuredData'));
        } catch (\Exception $e) {
            // Log the error and return a simple view
            \Illuminate\Support\Facades\Log::error('Portfolio index error: ' . $e->getMessage());
            
            // Return with default data
            $developer = null;
            $featuredProjects = collect();
            $skills = collect();
            $meta = [];
            $structuredData = [];
            
            return view('portfolio.index', compact('developer', 'featuredProjects', 'skills', 'meta', 'structuredData'));
        }
    }

    public function show()
    {
        return $this->index();
    }
}

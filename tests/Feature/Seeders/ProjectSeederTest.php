<?php

namespace Tests\Feature\Seeders;

use App\Models\Project;
use App\Models\ProjectImage;
use Database\Seeders\ProjectSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProjectSeederTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_project_seeder_creates_projects(): void
    {
        $this->seed(ProjectSeeder::class);

        $this->assertGreaterThan(0, Project::count());
        $this->assertEquals(10, Project::count()); // Should create 10 projects
    }

    public function test_project_seeder_creates_featured_projects(): void
    {
        $this->seed(ProjectSeeder::class);

        $featuredProjects = Project::where('featured', true)->get();
        
        $this->assertGreaterThan(0, $featuredProjects->count());
        
        foreach ($featuredProjects as $project) {
            $this->assertTrue($project->featured);
        }
    }

    public function test_project_seeder_creates_project_with_required_fields(): void
    {
        $this->seed(ProjectSeeder::class);

        $project = Project::first();

        $this->assertNotEmpty($project->title);
        $this->assertNotEmpty($project->description);
        $this->assertIsArray($project->technologies);
        $this->assertGreaterThan(0, count($project->technologies));
        $this->assertEquals('published', $project->status);
        $this->assertIsInt($project->sort_order);
    }

    public function test_project_seeder_creates_project_images(): void
    {
        $this->seed(ProjectSeeder::class);

        $projects = Project::all();

        foreach ($projects as $project) {
            $this->assertGreaterThan(0, $project->images()->count());
        }
    }

    public function test_project_seeder_creates_featured_hero_images(): void
    {
        $this->seed(ProjectSeeder::class);

        $featuredProjects = Project::where('featured', true)->get();

        foreach ($featuredProjects as $project) {
            $heroImage = $project->images()->where('sort_order', 0)->first();
            $this->assertNotNull($heroImage);
            $this->assertStringContains('featured-hero', $heroImage->image_path);
        }
    }

    public function test_project_seeder_creates_image_files(): void
    {
        $this->seed(ProjectSeeder::class);

        $images = ProjectImage::all();

        foreach ($images as $image) {
            Storage::disk('public')->assertExists($image->image_path);
        }
    }

    public function test_project_seeder_creates_valid_image_svg(): void
    {
        $this->seed(ProjectSeeder::class);

        $image = ProjectImage::first();
        $imageContent = Storage::disk('public')->get($image->image_path);

        $this->assertStringContainsString('<svg', $imageContent);
        $this->assertStringContainsString('</svg>', $imageContent);
    }

    public function test_project_seeder_creates_project_directories(): void
    {
        $this->seed(ProjectSeeder::class);

        $projects = Project::all();

        foreach ($projects as $project) {
            Storage::disk('public')->assertDirectoryExists("projects/{$project->id}");
        }
    }

    public function test_project_seeder_creates_images_with_proper_alt_text(): void
    {
        $this->seed(ProjectSeeder::class);

        $images = ProjectImage::all();

        foreach ($images as $image) {
            $this->assertNotEmpty($image->alt_text);
            $this->assertStringContainsString($image->project->title, $image->alt_text);
        }
    }

    public function test_project_seeder_creates_images_with_sort_order(): void
    {
        $this->seed(ProjectSeeder::class);

        $project = Project::first();
        $images = $project->images()->orderBy('sort_order')->get();

        $expectedOrder = 0;
        foreach ($images as $image) {
            $this->assertEquals($expectedOrder, $image->sort_order);
            $expectedOrder++;
        }
    }

    public function test_project_seeder_can_run_multiple_times(): void
    {
        $this->seed(ProjectSeeder::class);
        $initialCount = Project::count();
        
        $this->seed(ProjectSeeder::class);
        
        // Should create new projects, not update existing ones
        $this->assertEquals($initialCount * 2, Project::count());
    }

    public function test_project_seeder_creates_projects_with_github_urls(): void
    {
        $this->seed(ProjectSeeder::class);

        $projects = Project::all();

        foreach ($projects as $project) {
            $this->assertNotEmpty($project->github_url);
            $this->assertStringStartsWith('https://github.com/', $project->github_url);
        }
    }

    public function test_project_seeder_creates_projects_with_valid_technologies(): void
    {
        $this->seed(ProjectSeeder::class);

        $projects = Project::all();

        foreach ($projects as $project) {
            $this->assertIsArray($project->technologies);
            $this->assertGreaterThan(0, count($project->technologies));
            
            foreach ($project->technologies as $technology) {
                $this->assertIsString($technology);
                $this->assertNotEmpty($technology);
            }
        }
    }
}
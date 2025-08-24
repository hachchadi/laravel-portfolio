<?php

namespace Tests\Unit;

use App\Models\Project;
use App\Models\ProjectImage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_project_has_fillable_attributes(): void
    {
        $fillable = [
            'title',
            'description',
            'technologies',
            'github_url',
            'demo_url',
            'featured',
            'sort_order',
            'status',
        ];

        $project = new Project();
        $this->assertEquals($fillable, $project->getFillable());
    }

    public function test_project_casts_technologies_to_array(): void
    {
        $project = Project::create([
            'title' => 'Test Project',
            'description' => 'Test Description',
            'technologies' => ['Laravel', 'PHP', 'MySQL'],
        ]);

        $this->assertIsArray($project->technologies);
        $this->assertEquals(['Laravel', 'PHP', 'MySQL'], $project->technologies);
    }

    public function test_project_casts_featured_to_boolean(): void
    {
        $project = Project::create([
            'title' => 'Test Project',
            'description' => 'Test Description',
            'technologies' => ['Laravel'],
            'featured' => 1,
        ]);

        $this->assertIsBool($project->featured);
        $this->assertTrue($project->featured);
    }

    public function test_project_has_many_images(): void
    {
        $project = Project::create([
            'title' => 'Test Project',
            'description' => 'Test Description',
            'technologies' => ['Laravel'],
        ]);

        $image = ProjectImage::create([
            'project_id' => $project->id,
            'image_path' => 'test/image.jpg',
            'alt_text' => 'Test Image',
        ]);

        $this->assertTrue($project->images()->exists());
        $this->assertEquals(1, $project->images()->count());
        $this->assertEquals($image->id, $project->images()->first()->id);
    }

    public function test_featured_scope_returns_only_featured_projects(): void
    {
        Project::create([
            'title' => 'Featured Project',
            'description' => 'Test Description',
            'technologies' => ['Laravel'],
            'featured' => true,
        ]);

        Project::create([
            'title' => 'Regular Project',
            'description' => 'Test Description',
            'technologies' => ['Laravel'],
            'featured' => false,
        ]);

        $featuredProjects = Project::featured()->get();
        $this->assertEquals(1, $featuredProjects->count());
        $this->assertEquals('Featured Project', $featuredProjects->first()->title);
    }

    public function test_published_scope_returns_only_published_projects(): void
    {
        Project::create([
            'title' => 'Published Project',
            'description' => 'Test Description',
            'technologies' => ['Laravel'],
            'status' => 'published',
        ]);

        Project::create([
            'title' => 'Draft Project',
            'description' => 'Test Description',
            'technologies' => ['Laravel'],
            'status' => 'draft',
        ]);

        $publishedProjects = Project::published()->get();
        $this->assertEquals(1, $publishedProjects->count());
        $this->assertEquals('Published Project', $publishedProjects->first()->title);
    }
}

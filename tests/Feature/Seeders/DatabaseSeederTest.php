<?php

namespace Tests\Feature\Seeders;

use App\Models\ContactMessage;
use App\Models\Project;
use App\Models\ProjectImage;
use App\Models\Skill;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DatabaseSeederTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_database_seeder_runs_all_seeders(): void
    {
        $this->seed(DatabaseSeeder::class);

        // Check that all models have data
        $this->assertGreaterThan(0, User::count());
        $this->assertGreaterThan(0, Skill::count());
        $this->assertGreaterThan(0, Project::count());
        $this->assertGreaterThan(0, ContactMessage::count());
        $this->assertGreaterThan(0, ProjectImage::count());
    }

    public function test_database_seeder_creates_complete_portfolio_data(): void
    {
        $this->seed(DatabaseSeeder::class);

        // Check for main developer profile
        $mainUser = User::where('email', 'john.developer@example.com')->first();
        $this->assertNotNull($mainUser);
        $this->assertTrue($mainUser->is_admin);

        // Check for skills in different categories
        $this->assertGreaterThan(0, Skill::where('category', 'Backend')->count());
        $this->assertGreaterThan(0, Skill::where('category', 'Frontend')->count());
        $this->assertGreaterThan(0, Skill::where('category', 'Database')->count());

        // Check for featured projects
        $this->assertGreaterThan(0, Project::where('featured', true)->count());

        // Check for contact messages with different statuses
        $this->assertGreaterThan(0, ContactMessage::where('status', 'unread')->count());
        $this->assertGreaterThan(0, ContactMessage::where('status', 'read')->count());
    }

    public function test_database_seeder_creates_relationships(): void
    {
        $this->seed(DatabaseSeeder::class);

        // Check project-image relationships
        $projects = Project::with('images')->get();
        foreach ($projects as $project) {
            $this->assertGreaterThan(0, $project->images->count());
        }

        // Check that project images have valid project relationships
        $images = ProjectImage::with('project')->get();
        foreach ($images as $image) {
            $this->assertNotNull($image->project);
            $this->assertEquals($image->project_id, $image->project->id);
        }
    }

    public function test_database_seeder_creates_files(): void
    {
        $this->seed(DatabaseSeeder::class);

        // Check avatar files
        Storage::disk('public')->assertExists('avatars/john-developer.svg');
        Storage::disk('public')->assertExists('avatars/admin-user.svg');
        Storage::disk('public')->assertExists('avatars/test-user.svg');

        // Check project image files
        $images = ProjectImage::all();
        foreach ($images as $image) {
            Storage::disk('public')->assertExists($image->image_path);
        }
    }

    public function test_database_seeder_creates_admin_users(): void
    {
        $this->seed(DatabaseSeeder::class);

        $adminUsers = User::where('is_admin', true)->get();
        
        $this->assertGreaterThanOrEqual(2, $adminUsers->count());
        
        $emails = $adminUsers->pluck('email')->toArray();
        $this->assertContains('john.developer@example.com', $emails);
        $this->assertContains('admin@portfolio.test', $emails);
    }

    public function test_database_seeder_creates_portfolio_ready_data(): void
    {
        $this->seed(DatabaseSeeder::class);

        // Check that we have enough data for a complete portfolio
        $this->assertGreaterThanOrEqual(5, Project::count());
        $this->assertGreaterThanOrEqual(20, Skill::count());
        $this->assertGreaterThanOrEqual(5, ContactMessage::count());

        // Check that featured projects exist for homepage
        $featuredProjects = Project::where('featured', true)->count();
        $this->assertGreaterThanOrEqual(3, $featuredProjects);

        // Check that we have skills in major categories
        $categories = ['Backend', 'Frontend', 'Database', 'Tools'];
        foreach ($categories as $category) {
            $this->assertGreaterThan(0, Skill::where('category', $category)->count());
        }
    }

    public function test_database_seeder_can_run_multiple_times(): void
    {
        $this->seed(DatabaseSeeder::class);
        $initialCounts = [
            'users' => User::count(),
            'skills' => Skill::count(),
            'projects' => Project::count(),
            'messages' => ContactMessage::count(),
        ];

        $this->seed(DatabaseSeeder::class);

        // Users should not duplicate (updateOrCreate)
        $this->assertEquals($initialCounts['users'], User::count());
        
        // Other models should create new records
        $this->assertGreaterThan($initialCounts['skills'], Skill::count());
        $this->assertGreaterThan($initialCounts['projects'], Project::count());
        $this->assertGreaterThan($initialCounts['messages'], ContactMessage::count());
    }

    public function test_database_seeder_creates_valid_data_integrity(): void
    {
        $this->seed(DatabaseSeeder::class);

        // Check that all project images belong to existing projects
        $images = ProjectImage::all();
        foreach ($images as $image) {
            $this->assertNotNull(Project::find($image->project_id));
        }

        // Check that all users have valid email addresses
        $users = User::all();
        foreach ($users as $user) {
            $this->assertStringContainsString('@', $user->email);
            $this->assertStringContainsString('.', $user->email);
        }

        // Check that all contact messages have valid email addresses
        $messages = ContactMessage::all();
        foreach ($messages as $message) {
            $this->assertStringContainsString('@', $message->email);
            $this->assertStringContainsString('.', $message->email);
        }
    }

    public function test_database_seeder_creates_seo_friendly_data(): void
    {
        $this->seed(DatabaseSeeder::class);

        // Check that projects have SEO-friendly content
        $projects = Project::all();
        foreach ($projects as $project) {
            $this->assertGreaterThan(100, strlen($project->description)); // Substantial descriptions
            $this->assertNotEmpty($project->title);
            $this->assertIsArray($project->technologies);
        }

        // Check that project images have alt text
        $images = ProjectImage::all();
        foreach ($images as $image) {
            $this->assertNotEmpty($image->alt_text);
        }
    }

    public function test_database_seeder_creates_demo_ready_portfolio(): void
    {
        $this->seed(DatabaseSeeder::class);

        // Check main developer profile is complete
        $mainUser = User::where('email', 'john.developer@example.com')->first();
        $this->assertNotEmpty($mainUser->name);
        $this->assertNotEmpty($mainUser->title);
        $this->assertNotEmpty($mainUser->bio);
        $this->assertNotEmpty($mainUser->avatar);
        $this->assertNotEmpty($mainUser->linkedin_url);
        $this->assertNotEmpty($mainUser->github_url);

        // Check we have diverse project types
        $projects = Project::all();
        $technologies = $projects->flatMap(function ($project) {
            return $project->technologies;
        })->unique();
        
        $this->assertGreaterThan(10, $technologies->count()); // Diverse tech stack

        // Check we have realistic contact messages for admin demo
        $messages = ContactMessage::all();
        $this->assertGreaterThan(0, $messages->where('status', 'unread')->count());
        $this->assertGreaterThan(0, $messages->where('status', 'read')->count());
    }
}
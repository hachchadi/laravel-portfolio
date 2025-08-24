<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Project;
use App\Models\Skill;
use App\Models\ProjectImage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class PortfolioBrowsingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test user with portfolio data
        $this->user = User::factory()->create([
            'name' => 'John Doe',
            'title' => 'Senior Laravel Developer',
            'bio' => 'Experienced developer with 5+ years in Laravel',
            'email' => 'john@example.com',
            'linkedin_url' => 'https://linkedin.com/in/johndoe',
            'github_url' => 'https://github.com/johndoe',
            'phone' => '+1234567890',
            'location' => 'Remote'
        ]);

        // Create test projects
        $this->projects = Project::factory()->count(3)->create([
            'status' => 'published'
        ]);

        // Create test skills
        $this->skills = Skill::factory()->count(8)->create();
    }

    /** @test */
    public function it_displays_homepage_with_hero_section()
    {
        $response = $this->get('/');

        $response->assertStatus(200)
            ->assertSee($this->user->name)
            ->assertSee($this->user->title)
            ->assertSee('Welcome to my portfolio')
            ->assertSee('Get In Touch');
    }

    /** @test */
    public function it_displays_about_section_with_developer_info()
    {
        $response = $this->get('/');

        $response->assertStatus(200)
            ->assertSee($this->user->bio)
            ->assertSee($this->user->location)
            ->assertSee('About Me');
    }

    /** @test */
    public function it_displays_skills_section_with_categories()
    {
        $response = $this->get('/');

        $response->assertStatus(200)
            ->assertSee('Skills & Technologies')
            ->assertSee('Backend')
            ->assertSee('Frontend')
            ->assertSee('Database')
            ->assertSee('Tools');
    }

    /** @test */
    public function it_displays_projects_section_with_published_projects()
    {
        $response = $this->get('/');

        $response->assertStatus(200)
            ->assertSee('Featured Projects');

        foreach ($this->projects as $project) {
            $response->assertSee($project->title)
                ->assertSee($project->description);
        }
    }

    /** @test */
    public function it_does_not_display_draft_projects()
    {
        $draftProject = Project::factory()->create([
            'title' => 'Draft Project',
            'status' => 'draft'
        ]);

        $response = $this->get('/');

        $response->assertStatus(200)
            ->assertDontSee('Draft Project');
    }

    /** @test */
    public function it_displays_contact_section()
    {
        $response = $this->get('/');

        $response->assertStatus(200)
            ->assertSee('Get In Touch')
            ->assertSee('Name')
            ->assertSee('Email')
            ->assertSee('Subject')
            ->assertSee('Message')
            ->assertSee('Send Message');
    }

    /** @test */
    public function it_displays_social_media_links()
    {
        $response = $this->get('/');

        $response->assertStatus(200)
            ->assertSee($this->user->linkedin_url)
            ->assertSee($this->user->github_url);
    }

    /** @test */
    public function it_has_responsive_navigation()
    {
        $response = $this->get('/');

        $response->assertStatus(200)
            ->assertSee('Home')
            ->assertSee('About')
            ->assertSee('Skills')
            ->assertSee('Projects')
            ->assertSee('Contact')
            ->assertSeeHtml('mobile-menu-button');
    }

    /** @test */
    public function it_includes_seo_meta_tags()
    {
        $response = $this->get('/');

        $response->assertStatus(200)
            ->assertSeeHtml('<meta name="description"')
            ->assertSeeHtml('<meta property="og:title"')
            ->assertSeeHtml('<meta property="og:description"')
            ->assertSeeHtml('<meta name="twitter:card"');
    }

    /** @test */
    public function it_includes_structured_data()
    {
        $response = $this->get('/');

        $response->assertStatus(200)
            ->assertSeeHtml('application/ld+json')
            ->assertSeeHtml('"@type": "Person"')
            ->assertSee($this->user->name);
    }

    /** @test */
    public function it_loads_page_within_acceptable_time()
    {
        $startTime = microtime(true);
        
        $response = $this->get('/');
        
        $endTime = microtime(true);
        $loadTime = $endTime - $startTime;

        $response->assertStatus(200);
        $this->assertLessThan(3.0, $loadTime, 'Page should load within 3 seconds');
    }

    /** @test */
    public function it_includes_accessibility_features()
    {
        $response = $this->get('/');

        $response->assertStatus(200)
            ->assertSeeHtml('alt=')
            ->assertSeeHtml('aria-label=')
            ->assertSeeHtml('role=')
            ->assertSeeHtml('<h1')
            ->assertSeeHtml('<h2');
    }

    /** @test */
    public function it_includes_proper_html_structure()
    {
        $response = $this->get('/');

        $response->assertStatus(200)
            ->assertSeeHtml('<!DOCTYPE html>')
            ->assertSeeHtml('<html lang="en">')
            ->assertSeeHtml('<head>')
            ->assertSeeHtml('<body>')
            ->assertSeeHtml('<main>')
            ->assertSeeHtml('<footer>');
    }

    /** @test */
    public function it_includes_css_and_js_assets()
    {
        $response = $this->get('/');

        $response->assertStatus(200)
            ->assertSeeHtml('app.css')
            ->assertSeeHtml('app.js')
            ->assertSeeHtml('@livewireStyles')
            ->assertSeeHtml('@livewireScripts');
    }

    /** @test */
    public function it_handles_missing_user_data_gracefully()
    {
        // Delete the user to test graceful handling
        $this->user->delete();

        $response = $this->get('/');

        $response->assertStatus(200)
            ->assertSee('Portfolio'); // Should still show basic structure
    }

    /** @test */
    public function it_displays_project_technologies()
    {
        $project = Project::factory()->create([
            'technologies' => ['Laravel', 'Vue.js', 'MySQL'],
            'status' => 'published'
        ]);

        $response = $this->get('/');

        $response->assertStatus(200)
            ->assertSee('Laravel')
            ->assertSee('Vue.js')
            ->assertSee('MySQL');
    }

    /** @test */
    public function it_displays_project_links_when_available()
    {
        $project = Project::factory()->create([
            'github_url' => 'https://github.com/user/project',
            'demo_url' => 'https://demo.example.com',
            'status' => 'published'
        ]);

        $response = $this->get('/');

        $response->assertStatus(200)
            ->assertSee('View Code')
            ->assertSee('Live Demo');
    }

    /** @test */
    public function it_includes_smooth_scrolling_functionality()
    {
        $response = $this->get('/');

        $response->assertStatus(200)
            ->assertSeeHtml('scroll-behavior: smooth')
            ->assertSeeHtml('href="#home"')
            ->assertSeeHtml('href="#about"')
            ->assertSeeHtml('href="#skills"')
            ->assertSeeHtml('href="#projects"')
            ->assertSeeHtml('href="#contact"');
    }
}
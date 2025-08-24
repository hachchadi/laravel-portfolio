<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Project;
use App\Models\Skill;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ResponsiveDesignBrowserTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test data
        User::factory()->create([
            'name' => 'John Doe',
            'title' => 'Senior Laravel Developer',
            'bio' => 'Experienced developer with 5+ years in Laravel'
        ]);

        Project::factory()->count(3)->create(['status' => 'published']);
        Skill::factory()->count(8)->create();
    }

    /** @test */
    public function it_displays_correctly_on_desktop_viewport()
    {
        $response = $this->get('/');

        $response->assertStatus(200)
            ->assertSee('John Doe')
            ->assertSee('Senior Laravel Developer')
            ->assertSeeHtml('desktop-navigation')
            ->assertSeeHtml('hero-section')
            ->assertSeeHtml('about-section')
            ->assertSeeHtml('skills-section')
            ->assertSeeHtml('projects-section')
            ->assertSeeHtml('contact-section');
    }

    /** @test */
    public function it_includes_responsive_design_classes()
    {
        $response = $this->get('/');

        $response->assertStatus(200)
            ->assertSeeHtml('mobile-menu-button')
            ->assertSeeHtml('md:hidden')
            ->assertSeeHtml('lg:block')
            ->assertSeeHtml('grid-cols-1')
            ->assertSeeHtml('md:grid-cols-2')
            ->assertSeeHtml('lg:grid-cols-3');
    }

    /** @test */
    public function it_includes_mobile_navigation_structure()
    {
        $response = $this->get('/');

        $response->assertStatus(200)
            ->assertSeeHtml('mobile-menu')
            ->assertSee('Home')
            ->assertSee('About')
            ->assertSee('Skills')
            ->assertSee('Projects')
            ->assertSee('Contact');
    }

    /** @test */
    public function it_includes_mobile_menu_toggle_functionality()
    {
        $response = $this->get('/');

        $response->assertStatus(200)
            ->assertSeeHtml('wire:click="toggleMobileMenu"')
            ->assertSeeHtml('x-show="mobileMenuOpen"')
            ->assertSeeHtml('@click.away="mobileMenuOpen = false"');
    }

    /** @test */
    public function it_includes_smooth_scrolling_functionality()
    {
        $response = $this->get('/');

        $response->assertStatus(200)
            ->assertSeeHtml('href="#about"')
            ->assertSeeHtml('href="#skills"')
            ->assertSeeHtml('href="#projects"')
            ->assertSeeHtml('href="#contact"')
            ->assertSeeHtml('scroll-behavior: smooth');
    }

    /** @test */
    public function it_includes_navigation_active_state_functionality()
    {
        $response = $this->get('/');

        $response->assertStatus(200)
            ->assertSeeHtml('wire:click="setActiveSection')
            ->assertSeeHtml('activeSection')
            ->assertSeeHtml('nav-link active');
    }

    /** @test */
    public function it_includes_project_modal_functionality()
    {
        $response = $this->get('/');

        $response->assertStatus(200)
            ->assertSeeHtml('project-modal')
            ->assertSeeHtml('wire:click="showProject')
            ->assertSeeHtml('wire:click="closeModal"')
            ->assertSee('View Code')
            ->assertSee('Live Demo');
    }

    /** @test */
    public function it_includes_modal_keyboard_and_backdrop_functionality()
    {
        $response = $this->get('/');

        $response->assertStatus(200)
            ->assertSeeHtml('@keydown.escape="closeModal"')
            ->assertSeeHtml('@click.away="closeModal"')
            ->assertSeeHtml('modal-backdrop');
    }

    /** @test */
    public function it_includes_contact_form_validation_display()
    {
        $response = $this->get('/');

        $response->assertStatus(200)
            ->assertSeeHtml('contact-form')
            ->assertSeeHtml('@error')
            ->assertSeeHtml('border-red-500')
            ->assertSeeHtml('text-red-600')
            ->assertSeeHtml('wire:submit="submit"');
    }

    /** @test */
    public function it_includes_contact_form_success_and_loading_states()
    {
        $response = $this->get('/');

        $response->assertStatus(200)
            ->assertSee('Message Sent Successfully!')
            ->assertSee('Sending...')
            ->assertSeeHtml('wire:loading')
            ->assertSeeHtml('loading-spinner')
            ->assertSeeHtml('x-show="isSubmitted"');
    }

    /** @test */
    public function it_includes_skills_section_with_progress_bars()
    {
        $response = $this->get('/');

        $response->assertStatus(200)
            ->assertSeeHtml('skills-grid')
            ->assertSeeHtml('skill-item')
            ->assertSeeHtml('progress-bar')
            ->assertSeeHtml('skill-name')
            ->assertSeeHtml('skill-percentage');
    }

    /** @test */
    public function it_includes_scroll_animations()
    {
        $response = $this->get('/');

        $response->assertStatus(200)
            ->assertSeeHtml('IntersectionObserver')
            ->assertSeeHtml('animate-in')
            ->assertSeeHtml('fade-in')
            ->assertSeeHtml('slide-in');
    }

    /** @test */
    public function it_includes_lazy_loading_functionality()
    {
        $response = $this->get('/');

        $response->assertStatus(200)
            ->assertSeeHtml('loading="lazy"')
            ->assertSeeHtml('data-src');
    }

    /** @test */
    public function it_includes_dark_mode_functionality()
    {
        $response = $this->get('/');

        $response->assertStatus(200)
            ->assertSeeHtml('dark-mode-toggle')
            ->assertSeeHtml('dark:bg-')
            ->assertSeeHtml('dark:text-');
    }

    /** @test */
    public function it_includes_keyboard_navigation_support()
    {
        $response = $this->get('/');

        $response->assertStatus(200)
            ->assertSeeHtml('tabindex=')
            ->assertSeeHtml('role=')
            ->assertSeeHtml('aria-label=');
    }

    /** @test */
    public function it_loads_within_acceptable_time()
    {
        $startTime = microtime(true);
        
        $response = $this->get('/');
        
        $endTime = microtime(true);
        $loadTime = $endTime - $startTime;

        $response->assertStatus(200);
        $this->assertLessThan(3.0, $loadTime, 'Page should load within 3 seconds');
    }

    /** @test */
    public function it_includes_animation_and_transition_classes()
    {
        $response = $this->get('/');

        $response->assertStatus(200)
            ->assertSeeHtml('transition-')
            ->assertSeeHtml('duration-')
            ->assertSeeHtml('ease-')
            ->assertSeeHtml('transform');
    }

    /** @test */
    public function it_includes_proper_social_media_link_attributes()
    {
        $response = $this->get('/');

        $response->assertStatus(200)
            ->assertSeeHtml('target="_blank"')
            ->assertSeeHtml('rel="noopener noreferrer"');
    }

    /** @test */
    public function it_includes_comprehensive_accessibility_features()
    {
        $response = $this->get('/');

        $response->assertStatus(200)
            ->assertSeeHtml('aria-describedby')
            ->assertSeeHtml('for="name"')
            ->assertSeeHtml('for="email"')
            ->assertSeeHtml('for="subject"')
            ->assertSeeHtml('for="message"')
            ->assertSeeHtml('alt=')
            ->assertSeeHtml('aria-label=');
    }
}
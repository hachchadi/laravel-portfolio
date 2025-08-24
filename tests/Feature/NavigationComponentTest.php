<?php

namespace Tests\Feature;

use Tests\TestCase;
use Livewire\Livewire;
use App\Livewire\Navigation;

class NavigationComponentTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        
        // Use in-memory SQLite database for testing
        config(['database.default' => 'testing']);
        config(['database.connections.testing' => [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]]);
    }

    /** @test */
    public function it_renders_navigation_component()
    {
        Livewire::test(Navigation::class)
            ->assertStatus(200)
            ->assertSee('Portfolio')
            ->assertSee('Home')
            ->assertSee('About')
            ->assertSee('Skills')
            ->assertSee('Projects')
            ->assertSee('Contact');
    }

    /** @test */
    public function it_initializes_with_home_as_active_section()
    {
        Livewire::test(Navigation::class)
            ->assertSet('activeSection', 'home')
            ->assertSet('mobileMenuOpen', false);
    }

    /** @test */
    public function it_can_set_active_section()
    {
        Livewire::test(Navigation::class)
            ->call('setActiveSection', 'about')
            ->assertSet('activeSection', 'about');
    }

    /** @test */
    public function it_can_toggle_mobile_menu()
    {
        Livewire::test(Navigation::class)
            ->assertSet('mobileMenuOpen', false)
            ->call('toggleMobileMenu')
            ->assertSet('mobileMenuOpen', true)
            ->call('toggleMobileMenu')
            ->assertSet('mobileMenuOpen', false);
    }

    /** @test */
    public function it_closes_mobile_menu_when_section_is_selected()
    {
        Livewire::test(Navigation::class)
            ->set('mobileMenuOpen', true)
            ->call('setActiveSection', 'projects')
            ->assertSet('activeSection', 'projects')
            ->assertSet('mobileMenuOpen', false);
    }

    /** @test */
    public function it_can_close_mobile_menu_explicitly()
    {
        Livewire::test(Navigation::class)
            ->set('mobileMenuOpen', true)
            ->call('closeMobileMenu')
            ->assertSet('mobileMenuOpen', false);
    }

    /** @test */
    public function it_responds_to_set_active_section_event()
    {
        Livewire::test(Navigation::class)
            ->dispatch('setActiveSection', section: 'skills')
            ->assertSet('activeSection', 'skills');
    }

    /** @test */
    public function it_shows_correct_active_state_for_desktop_navigation()
    {
        Livewire::test(Navigation::class)
            ->set('activeSection', 'projects')
            ->assertSeeHtml('nav-link active')
            ->assertSee('Projects');
    }

    /** @test */
    public function it_shows_correct_active_state_for_mobile_navigation()
    {
        Livewire::test(Navigation::class)
            ->set('activeSection', 'contact')
            ->set('mobileMenuOpen', true)
            ->assertSeeHtml('mobile-nav-link active')
            ->assertSee('Contact');
    }

    /** @test */
    public function it_displays_mobile_menu_when_open()
    {
        Livewire::test(Navigation::class)
            ->set('mobileMenuOpen', true)
            ->assertSeeHtml('x-show="mobileMenuOpen"');
    }

    /** @test */
    public function it_has_proper_aria_attributes()
    {
        Livewire::test(Navigation::class)
            ->set('mobileMenuOpen', true)
            ->assertSeeHtml(':aria-expanded="mobileMenuOpen"')
            ->assertSeeHtml('aria-current="page"');
    }

    /** @test */
    public function it_includes_accessibility_features()
    {
        Livewire::test(Navigation::class)
            ->assertSeeHtml('aria-label="Go to home section"')
            ->assertSeeHtml('aria-label="Toggle mobile menu"')
            ->assertSeeHtml('<span class="sr-only">Open main menu</span>');
    }

    /** @test */
    public function it_validates_section_names()
    {
        $validSections = ['home', 'about', 'skills', 'projects', 'contact'];
        
        foreach ($validSections as $section) {
            Livewire::test(Navigation::class)
                ->call('setActiveSection', $section)
                ->assertSet('activeSection', $section);
        }
    }

    /** @test */
    public function it_handles_invalid_section_gracefully()
    {
        Livewire::test(Navigation::class)
            ->call('setActiveSection', 'invalid-section')
            ->assertSet('activeSection', 'invalid-section'); // Component should still accept it
    }

    /** @test */
    public function mobile_menu_has_proper_transitions()
    {
        Livewire::test(Navigation::class)
            ->assertSeeHtml('x-transition:enter="transition ease-out duration-200"')
            ->assertSeeHtml('x-transition:leave="transition ease-in duration-150"');
    }

    /** @test */
    public function navigation_links_have_proper_href_attributes()
    {
        Livewire::test(Navigation::class)
            ->assertSeeHtml('href="#home"')
            ->assertSeeHtml('href="#about"')
            ->assertSeeHtml('href="#skills"')
            ->assertSeeHtml('href="#projects"')
            ->assertSeeHtml('href="#contact"');
    }

    /** @test */
    public function it_includes_click_away_functionality()
    {
        Livewire::test(Navigation::class)
            ->assertSeeHtml('@click.away="mobileMenuOpen = false"');
    }

    /** @test */
    public function navigation_has_proper_styling_classes()
    {
        Livewire::test(Navigation::class)
            ->assertSeeHtml('fixed top-0 left-0 right-0 z-50')
            ->assertSeeHtml('bg-white/90 backdrop-blur-md')
            ->assertSeeHtml('border-b border-gray-200');
    }
}
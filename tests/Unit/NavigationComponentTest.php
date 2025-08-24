<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Livewire\Navigation;
use Livewire\Livewire;

class NavigationComponentTest extends TestCase
{
    /** @test */
    public function it_has_correct_initial_state()
    {
        $component = new Navigation();
        $component->mount();
        
        $this->assertEquals('home', $component->activeSection);
        $this->assertFalse($component->mobileMenuOpen);
    }

    /** @test */
    public function toggle_mobile_menu_changes_state()
    {
        $component = new Navigation();
        
        // Initially false
        $this->assertFalse($component->mobileMenuOpen);
        
        // Toggle to true
        $component->toggleMobileMenu();
        $this->assertTrue($component->mobileMenuOpen);
        
        // Toggle back to false
        $component->toggleMobileMenu();
        $this->assertFalse($component->mobileMenuOpen);
    }

    /** @test */
    public function set_active_section_updates_section_and_closes_mobile_menu()
    {
        $component = new Navigation();
        $component->mobileMenuOpen = true;
        
        $component->setActiveSection('about');
        
        $this->assertEquals('about', $component->activeSection);
        $this->assertFalse($component->mobileMenuOpen);
    }

    /** @test */
    public function set_active_section_when_mobile_menu_closed()
    {
        $component = new Navigation();
        $component->mobileMenuOpen = false;
        
        $component->setActiveSection('skills');
        
        $this->assertEquals('skills', $component->activeSection);
        $this->assertFalse($component->mobileMenuOpen);
    }

    /** @test */
    public function update_active_section_only_changes_section()
    {
        $component = new Navigation();
        $component->mobileMenuOpen = true;
        $component->activeSection = 'home';
        
        $component->updateActiveSection('projects');
        
        $this->assertEquals('projects', $component->activeSection);
        $this->assertTrue($component->mobileMenuOpen); // Should remain open
    }

    /** @test */
    public function close_mobile_menu_sets_to_false()
    {
        $component = new Navigation();
        $component->mobileMenuOpen = true;
        
        $component->closeMobileMenu();
        
        $this->assertFalse($component->mobileMenuOpen);
    }

    /** @test */
    public function component_has_correct_listeners()
    {
        $component = new Navigation();
        $reflection = new \ReflectionClass($component);
        $listenersProperty = $reflection->getProperty('listeners');
        $listenersProperty->setAccessible(true);
        $listeners = $listenersProperty->getValue($component);
        
        $this->assertContains('setActiveSection', $listeners);
    }

    /** @test */
    public function component_renders_correct_view()
    {
        $component = new Navigation();
        
        $view = $component->render();
        
        $this->assertEquals('livewire.navigation', $view->getName());
    }

    /** @test */
    public function section_names_are_preserved_correctly()
    {
        $component = new Navigation();
        $sections = ['home', 'about', 'skills', 'projects', 'contact'];
        
        foreach ($sections as $section) {
            $component->setActiveSection($section);
            $this->assertEquals($section, $component->activeSection);
        }
    }

    /** @test */
    public function component_handles_empty_section_name()
    {
        $component = new Navigation();
        
        $component->setActiveSection('');
        
        $this->assertEquals('', $component->activeSection);
    }

    /** @test */
    public function component_handles_special_characters_in_section()
    {
        $component = new Navigation();
        
        $component->setActiveSection('test-section_123');
        
        $this->assertEquals('test-section_123', $component->activeSection);
    }
}
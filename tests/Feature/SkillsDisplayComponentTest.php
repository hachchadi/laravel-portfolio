<?php

namespace Tests\Feature;

use Tests\TestCase;
use Livewire\Livewire;
use App\Livewire\SkillsDisplay;

class SkillsDisplayComponentTest extends TestCase
{

    /** @test */
    public function it_renders_skills_display_component()
    {
        Livewire::test(SkillsDisplay::class)
            ->assertStatus(200)
            ->assertSee('Technical Skills')
            ->assertSee('No Skills Available'); // Should show empty state when no skills
    }

    /** @test */
    public function it_can_trigger_animation_start()
    {
        Livewire::test(SkillsDisplay::class)
            ->assertSet('animateOnLoad', false)
            ->call('startAnimation')
            ->assertSet('animateOnLoad', true);
    }

    /** @test */
    public function it_shows_empty_state_when_no_skills()
    {
        Livewire::test(SkillsDisplay::class)
            ->assertSee('No Skills Available')
            ->assertSee('Skills will be displayed here once they are added to the portfolio.');
    }

    /** @test */
    public function it_includes_alpine_js_animation_attributes()
    {
        Livewire::test(SkillsDisplay::class)
            ->assertSeeHtml('x-data')
            ->assertSeeHtml('x-show="isVisible"')
            ->assertSeeHtml('x-transition:enter')
            ->assertSeeHtml('x-init');
    }

    /** @test */
    public function it_includes_intersection_observer_for_scroll_animations()
    {
        Livewire::test(SkillsDisplay::class)
            ->assertSeeHtml('IntersectionObserver')
            ->assertSeeHtml('threshold: 0.2');
    }

    /** @test */
    public function it_includes_responsive_grid_classes()
    {
        Livewire::test(SkillsDisplay::class)
            ->assertSeeHtml('grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3');
    }

    /** @test */
    public function it_includes_dark_mode_classes()
    {
        Livewire::test(SkillsDisplay::class)
            ->assertSeeHtml('dark:text-white')
            ->assertSeeHtml('dark:bg-gray-800')
            ->assertSeeHtml('dark:text-gray-300');
    }

    /** @test */
    public function it_includes_accessibility_features()
    {
        Livewire::test(SkillsDisplay::class)
            ->assertSeeHtml('Technical Skills') // Proper heading
            ->assertSeeHtml('rounded-xl') // Proper visual design
            ->assertSeeHtml('transition-all'); // Smooth transitions
    }
}
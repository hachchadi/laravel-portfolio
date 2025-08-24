<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Livewire\SkillsDisplay;

class SkillsDisplayComponentTest extends TestCase
{
    /** @test */
    public function it_can_start_animation()
    {
        $component = new SkillsDisplay();
        
        $this->assertFalse($component->animateOnLoad);
        
        $component->startAnimation();
        
        $this->assertTrue($component->animateOnLoad);
    }

    /** @test */
    public function it_renders_correct_view()
    {
        $component = new SkillsDisplay();
        
        $view = $component->render();
        
        $this->assertEquals('livewire.skills-display', $view->getName());
    }

    /** @test */
    public function component_initializes_with_default_values()
    {
        $component = new SkillsDisplay();
        
        $this->assertFalse($component->animateOnLoad);
        $this->assertNull($component->skillsByCategory);
    }

    /** @test */
    public function component_has_required_properties()
    {
        $component = new SkillsDisplay();
        
        $this->assertObjectHasProperty('skillsByCategory', $component);
        $this->assertObjectHasProperty('animateOnLoad', $component);
    }
}
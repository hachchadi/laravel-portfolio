<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Livewire\ProjectGallery;

class ProjectGalleryComponentTest extends TestCase
{
    /** @test */
    public function it_initializes_with_default_values()
    {
        $component = new ProjectGallery();
        
        $this->assertNull($component->projects);
        $this->assertNull($component->selectedProject);
        $this->assertFalse($component->showModal);
        $this->assertEquals(0, $component->currentImageIndex);
        $this->assertEquals('', $component->filterTechnology);
        $this->assertFalse($component->animateOnLoad);
    }

    /** @test */
    public function it_can_start_animation()
    {
        $component = new ProjectGallery();
        
        $this->assertFalse($component->animateOnLoad);
        
        $component->startAnimation();
        
        $this->assertTrue($component->animateOnLoad);
    }

    /** @test */
    public function it_can_close_modal()
    {
        $component = new ProjectGallery();
        $component->showModal = true;
        $component->selectedProject = (object) ['id' => 1];
        $component->currentImageIndex = 2;
        
        $component->closeModal();
        
        $this->assertFalse($component->showModal);
        $this->assertNull($component->selectedProject);
        $this->assertEquals(0, $component->currentImageIndex);
    }

    /** @test */
    public function it_can_set_current_image()
    {
        $component = new ProjectGallery();
        
        $component->setCurrentImage(3);
        
        $this->assertEquals(3, $component->currentImageIndex);
    }

    /** @test */
    public function it_renders_correct_view()
    {
        $component = new ProjectGallery();
        
        $view = $component->render();
        
        $this->assertEquals('livewire.project-gallery', $view->getName());
    }

    /** @test */
    public function it_handles_filter_technology_property_changes()
    {
        $component = new ProjectGallery();
        
        // Test initial state
        $this->assertEquals('', $component->filterTechnology);
        
        // Test setting filter technology
        $component->filterTechnology = 'Laravel';
        $this->assertEquals('Laravel', $component->filterTechnology);
        
        // Test clearing filter technology
        $component->filterTechnology = '';
        $this->assertEquals('', $component->filterTechnology);
    }

    /** @test */
    public function it_has_required_properties()
    {
        $component = new ProjectGallery();
        
        $this->assertObjectHasProperty('projects', $component);
        $this->assertObjectHasProperty('selectedProject', $component);
        $this->assertObjectHasProperty('showModal', $component);
        $this->assertObjectHasProperty('currentImageIndex', $component);
        $this->assertObjectHasProperty('filterTechnology', $component);
        $this->assertObjectHasProperty('animateOnLoad', $component);
    }

    /** @test */
    public function it_handles_image_navigation_with_no_images()
    {
        $component = new ProjectGallery();
        $component->selectedProject = (object) ['images' => collect()];
        $component->currentImageIndex = 0;
        
        $component->nextImage();
        $this->assertEquals(0, $component->currentImageIndex);
        
        $component->previousImage();
        $this->assertEquals(0, $component->currentImageIndex);
    }

    /** @test */
    public function it_handles_image_navigation_with_single_image()
    {
        $component = new ProjectGallery();
        $component->selectedProject = (object) ['images' => collect([
            (object) ['id' => 1]
        ])];
        $component->currentImageIndex = 0;
        
        $component->nextImage();
        $this->assertEquals(0, $component->currentImageIndex);
        
        $component->previousImage();
        $this->assertEquals(0, $component->currentImageIndex);
    }

    /** @test */
    public function it_handles_image_navigation_with_multiple_images()
    {
        $component = new ProjectGallery();
        $component->selectedProject = (object) ['images' => collect([
            (object) ['id' => 1],
            (object) ['id' => 2],
            (object) ['id' => 3]
        ])];
        $component->currentImageIndex = 0;
        
        // Test next image
        $component->nextImage();
        $this->assertEquals(1, $component->currentImageIndex);
        
        $component->nextImage();
        $this->assertEquals(2, $component->currentImageIndex);
        
        // Test wrapping to beginning
        $component->nextImage();
        $this->assertEquals(0, $component->currentImageIndex);
        
        // Test previous image
        $component->previousImage();
        $this->assertEquals(2, $component->currentImageIndex);
        
        $component->previousImage();
        $this->assertEquals(1, $component->currentImageIndex);
    }
}
<?php

namespace Tests\Feature;

use Tests\TestCase;
use Livewire\Livewire;
use App\Livewire\ProjectGallery;

class ProjectGalleryComponentTest extends TestCase
{
    /** @test */
    public function it_renders_project_gallery_component()
    {
        Livewire::test(ProjectGallery::class)
            ->assertStatus(200)
            ->assertSee('Featured Projects')
            ->assertSee('No Projects Available'); // Should show empty state when no projects
    }

    /** @test */
    public function it_can_trigger_animation_start()
    {
        Livewire::test(ProjectGallery::class)
            ->assertSet('animateOnLoad', false)
            ->call('startAnimation')
            ->assertSet('animateOnLoad', true);
    }

    /** @test */
    public function it_can_close_modal()
    {
        Livewire::test(ProjectGallery::class)
            ->set('showModal', true)
            ->set('selectedProject', (object) ['id' => 1])
            ->set('currentImageIndex', 2)
            ->call('closeModal')
            ->assertSet('showModal', false)
            ->assertSet('selectedProject', null)
            ->assertSet('currentImageIndex', 0);
    }

    /** @test */
    public function it_can_set_current_image()
    {
        Livewire::test(ProjectGallery::class)
            ->call('setCurrentImage', 3)
            ->assertSet('currentImageIndex', 3);
    }

    /** @test */
    public function it_shows_empty_state_when_no_projects()
    {
        Livewire::test(ProjectGallery::class)
            ->assertSee('No Projects Available')
            ->assertSee('Projects will be displayed here once they are added to the portfolio.');
    }

    /** @test */
    public function it_includes_alpine_js_animation_attributes()
    {
        Livewire::test(ProjectGallery::class)
            ->assertSeeHtml('x-data')
            ->assertSeeHtml('x-show="isVisible"')
            ->assertSeeHtml('x-transition:enter')
            ->assertSeeHtml('x-init');
    }

    /** @test */
    public function it_includes_intersection_observer_for_scroll_animations()
    {
        Livewire::test(ProjectGallery::class)
            ->assertSeeHtml('IntersectionObserver')
            ->assertSeeHtml('threshold: 0.1');
    }

    /** @test */
    public function it_includes_keyboard_navigation_for_modal()
    {
        Livewire::test(ProjectGallery::class)
            ->assertSeeHtml('keydown')
            ->assertSeeHtml('ArrowLeft')
            ->assertSeeHtml('ArrowRight')
            ->assertSeeHtml('Escape');
    }

    /** @test */
    public function it_includes_responsive_grid_classes()
    {
        Livewire::test(ProjectGallery::class)
            ->assertSeeHtml('grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3');
    }

    /** @test */
    public function it_includes_dark_mode_classes()
    {
        Livewire::test(ProjectGallery::class)
            ->assertSeeHtml('dark:text-white')
            ->assertSeeHtml('dark:bg-gray-800')
            ->assertSeeHtml('dark:text-gray-300');
    }

    /** @test */
    public function it_includes_modal_functionality()
    {
        Livewire::test(ProjectGallery::class)
            ->assertSeeHtml('x-show="showModal"')
            ->assertSeeHtml('fixed inset-0 z-50')
            ->assertSeeHtml('bg-black bg-opacity-75');
    }

    /** @test */
    public function it_includes_filter_functionality()
    {
        Livewire::test(ProjectGallery::class)
            ->assertSeeHtml('filter-btn')
            ->assertSeeHtml('All Projects');
    }

    /** @test */
    public function it_includes_project_card_hover_effects()
    {
        Livewire::test(ProjectGallery::class)
            ->assertSeeHtml('group-hover:scale-110')
            ->assertSeeHtml('hover:shadow-xl')
            ->assertSeeHtml('transition-transform');
    }

    /** @test */
    public function it_includes_accessibility_features()
    {
        Livewire::test(ProjectGallery::class)
            ->assertSeeHtml('Featured Projects') // Proper heading
            ->assertSeeHtml('alt=') // Alt text for images
            ->assertSeeHtml('loading="lazy"') // Lazy loading
            ->assertSeeHtml('aria-') // ARIA attributes
            ->assertSeeHtml('transition-all'); // Smooth transitions
    }

    /** @test */
    public function it_handles_filter_technology_changes()
    {
        Livewire::test(ProjectGallery::class)
            ->assertSet('filterTechnology', '')
            ->call('filterByTechnology', 'Laravel')
            ->assertSet('filterTechnology', 'Laravel')
            ->call('filterByTechnology', 'Laravel') // Toggle off
            ->assertSet('filterTechnology', '');
    }

    /** @test */
    public function it_includes_image_navigation_controls()
    {
        Livewire::test(ProjectGallery::class)
            ->assertSeeHtml('previousImage')
            ->assertSeeHtml('nextImage')
            ->assertSeeHtml('setCurrentImage');
    }

    /** @test */
    public function it_includes_project_links()
    {
        Livewire::test(ProjectGallery::class)
            ->assertSeeHtml('github_url')
            ->assertSeeHtml('demo_url')
            ->assertSeeHtml('target="_blank"');
    }

    /** @test */
    public function it_includes_technology_tags()
    {
        Livewire::test(ProjectGallery::class)
            ->assertSeeHtml('technologies')
            ->assertSeeHtml('bg-blue-100')
            ->assertSeeHtml('rounded-full');
    }

    /** @test */
    public function it_includes_staggered_animation_delays()
    {
        Livewire::test(ProjectGallery::class)
            ->assertSeeHtml('transition-delay')
            ->assertSeeHtml('$loop->index * 150');
    }

    /** @test */
    public function it_includes_modal_body_scroll_control()
    {
        Livewire::test(ProjectGallery::class)
            ->assertSeeHtml('@modal-opened.window="document.body.style.overflow = \'hidden\'"')
            ->assertSeeHtml('@modal-closed.window="document.body.style.overflow = \'auto\'"');
    }
}
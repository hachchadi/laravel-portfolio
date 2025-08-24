<?php

namespace Tests\Unit\Admin;

use App\Livewire\Admin\ProjectManager;
use App\Models\Project;
use App\Models\ProjectImage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProjectManagerUnitTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $component;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::factory()->create([
            'email' => 'admin@example.com',
            'is_admin' => true,
        ]);

        $this->actingAs($this->admin);
        $this->component = new ProjectManager();
        $this->component->mount();
    }

    /** @test */
    public function it_initializes_with_correct_default_values()
    {
        $component = new ProjectManager();
        
        $this->assertNull($component->editing);
        $this->assertFalse($component->showForm);
        $this->assertFalse($component->showDeleteModal);
        $this->assertNull($component->projectToDelete);
        $this->assertEquals('', $component->title);
        $this->assertEquals('', $component->description);
        $this->assertEquals([], $component->technologies);
        $this->assertEquals('', $component->github_url);
        $this->assertEquals('', $component->demo_url);
        $this->assertFalse($component->featured);
        $this->assertEquals('published', $component->status);
        $this->assertEquals(0, $component->sort_order);
        $this->assertEquals([], $component->newImages);
        $this->assertEquals([], $component->existingImages);
        $this->assertEquals([], $component->imagesToDelete);
    }

    /** @test */
    public function it_loads_projects_correctly()
    {
        $project1 = Project::factory()->create(['sort_order' => 1]);
        $project2 = Project::factory()->create(['sort_order' => 0]);
        
        $this->component->loadProjects();
        
        $this->assertCount(2, $this->component->projects);
        // Should be ordered by sort_order first
        $this->assertEquals($project2->id, $this->component->projects->first()->id);
    }

    /** @test */
    public function create_method_resets_form_and_shows_form()
    {
        // Set some values first
        $this->component->title = 'Some title';
        $this->component->editing = 123;
        
        $this->component->create();
        
        $this->assertNull($this->component->editing);
        $this->assertTrue($this->component->showForm);
        $this->assertEquals('', $this->component->title);
    }

    /** @test */
    public function edit_method_loads_project_data()
    {
        $project = Project::factory()->create([
            'title' => 'Test Project',
            'description' => 'Test Description',
            'technologies' => ['Laravel', 'Vue.js'],
            'github_url' => 'https://github.com/test',
            'demo_url' => 'https://demo.test',
            'featured' => true,
            'status' => 'draft',
            'sort_order' => 5,
        ]);

        $image = ProjectImage::factory()->create([
            'project_id' => $project->id,
            'alt_text' => 'Test Image',
            'sort_order' => 0,
        ]);

        $this->component->edit($project->id);

        $this->assertEquals($project->id, $this->component->editing);
        $this->assertTrue($this->component->showForm);
        $this->assertEquals('Test Project', $this->component->title);
        $this->assertEquals('Test Description', $this->component->description);
        $this->assertEquals(['Laravel', 'Vue.js'], $this->component->technologies);
        $this->assertEquals('https://github.com/test', $this->component->github_url);
        $this->assertEquals('https://demo.test', $this->component->demo_url);
        $this->assertTrue($this->component->featured);
        $this->assertEquals('draft', $this->component->status);
        $this->assertEquals(5, $this->component->sort_order);
        $this->assertCount(1, $this->component->existingImages);
        $this->assertEquals($image->id, $this->component->existingImages[0]['id']);
    }

    /** @test */
    public function confirm_delete_sets_modal_state()
    {
        $project = Project::factory()->create();
        
        $this->component->confirmDelete($project->id);
        
        $this->assertEquals($project->id, $this->component->projectToDelete);
        $this->assertTrue($this->component->showDeleteModal);
    }

    /** @test */
    public function cancel_delete_resets_modal_state()
    {
        $this->component->projectToDelete = 123;
        $this->component->showDeleteModal = true;
        
        $this->component->cancelDelete();
        
        $this->assertNull($this->component->projectToDelete);
        $this->assertFalse($this->component->showDeleteModal);
    }

    /** @test */
    public function cancel_resets_form_and_hides_form()
    {
        $this->component->title = 'Some title';
        $this->component->showForm = true;
        $this->component->editing = 123;
        
        $this->component->cancel();
        
        $this->assertEquals('', $this->component->title);
        $this->assertFalse($this->component->showForm);
        $this->assertNull($this->component->editing);
    }

    /** @test */
    public function add_technology_adds_empty_string_to_array()
    {
        $this->component->technologies = ['Laravel'];
        
        $this->component->addTechnology();
        
        $this->assertCount(2, $this->component->technologies);
        $this->assertEquals('', $this->component->technologies[1]);
    }

    /** @test */
    public function remove_technology_removes_correct_index()
    {
        $this->component->technologies = ['Laravel', 'Vue.js', 'React'];
        
        $this->component->removeTechnology(1);
        
        $this->assertCount(2, $this->component->technologies);
        $this->assertEquals(['Laravel', 'React'], array_values($this->component->technologies));
    }

    /** @test */
    public function remove_existing_image_adds_to_delete_list()
    {
        $this->component->existingImages = [
            ['id' => 1, 'image_path' => 'test1.jpg'],
            ['id' => 2, 'image_path' => 'test2.jpg'],
        ];
        
        $this->component->removeExistingImage(1);
        
        $this->assertContains(1, $this->component->imagesToDelete);
        $this->assertCount(1, $this->component->existingImages);
        $this->assertEquals(2, array_values($this->component->existingImages)[0]['id']);
    }

    /** @test */
    public function update_image_order_updates_sort_order()
    {
        $this->component->existingImages = [
            ['id' => 1, 'sort_order' => 0],
            ['id' => 2, 'sort_order' => 1],
        ];

        $orderedImages = [
            ['id' => 2],
            ['id' => 1],
        ];

        $this->component->updateImageOrder($orderedImages);

        $this->assertEquals(0, $this->component->existingImages[1]['sort_order']);
        $this->assertEquals(1, $this->component->existingImages[0]['sort_order']);
    }

    /** @test */
    public function validation_rules_are_correct()
    {
        $rules = $this->component->getRules();
        
        $this->assertArrayHasKey('title', $rules);
        $this->assertArrayHasKey('description', $rules);
        $this->assertArrayHasKey('technologies', $rules);
        $this->assertArrayHasKey('github_url', $rules);
        $this->assertArrayHasKey('demo_url', $rules);
        $this->assertArrayHasKey('featured', $rules);
        $this->assertArrayHasKey('status', $rules);
        $this->assertArrayHasKey('sort_order', $rules);
        $this->assertArrayHasKey('newImages.*', $rules);
        
        $this->assertStringContainsString('required', $rules['title']);
        $this->assertStringContainsString('required', $rules['description']);
        $this->assertStringContainsString('nullable|url', $rules['github_url']);
        $this->assertStringContainsString('nullable|url', $rules['demo_url']);
        $this->assertStringContainsString('boolean', $rules['featured']);
        $this->assertStringContainsString('in:draft,published', $rules['status']);
    }

    /** @test */
    public function validation_messages_are_defined()
    {
        // Access the protected messages property using reflection
        $reflection = new \ReflectionClass($this->component);
        $property = $reflection->getProperty('messages');
        $property->setAccessible(true);
        $messages = $property->getValue($this->component);
        
        $this->assertArrayHasKey('title.required', $messages);
        $this->assertArrayHasKey('description.required', $messages);
        $this->assertArrayHasKey('github_url.url', $messages);
        $this->assertArrayHasKey('demo_url.url', $messages);
        $this->assertArrayHasKey('newImages.*.image', $messages);
        $this->assertArrayHasKey('newImages.*.max', $messages);
    }

    /** @test */
    public function handle_image_uploads_creates_project_images()
    {
        Storage::fake('public');
        
        $project = Project::factory()->create();
        $image1 = UploadedFile::fake()->image('test1.jpg');
        $image2 = UploadedFile::fake()->image('test2.jpg');
        
        $this->component->title = 'Test Project';
        $this->component->newImages = [$image1, $image2];
        
        // Use reflection to call private method
        $reflection = new \ReflectionClass($this->component);
        $method = $reflection->getMethod('handleImageUploads');
        $method->setAccessible(true);
        $method->invoke($this->component, $project);
        
        $this->assertCount(2, $project->fresh()->images);
        
        foreach ($project->fresh()->images as $projectImage) {
            Storage::disk('public')->assertExists($projectImage->image_path);
        }
    }

    /** @test */
    public function handle_image_deletions_removes_images()
    {
        Storage::fake('public');
        
        $image = ProjectImage::factory()->create([
            'image_path' => 'projects/test.jpg',
        ]);
        
        Storage::disk('public')->put('projects/test.jpg', 'fake-content');
        
        $this->component->imagesToDelete = [$image->id];
        
        // Use reflection to call private method
        $reflection = new \ReflectionClass($this->component);
        $method = $reflection->getMethod('handleImageDeletions');
        $method->setAccessible(true);
        $method->invoke($this->component);
        
        $this->assertDatabaseMissing('project_images', ['id' => $image->id]);
        Storage::disk('public')->assertMissing('projects/test.jpg');
    }

    /** @test */
    public function update_existing_images_updates_metadata()
    {
        $image = ProjectImage::factory()->create([
            'alt_text' => 'Old Alt Text',
            'sort_order' => 0,
        ]);
        
        $project = $image->project;
        
        $this->component->existingImages = [
            [
                'id' => $image->id,
                'alt_text' => 'New Alt Text',
                'sort_order' => 5,
            ]
        ];
        
        // Use reflection to call private method
        $reflection = new \ReflectionClass($this->component);
        $method = $reflection->getMethod('updateExistingImages');
        $method->setAccessible(true);
        $method->invoke($this->component, $project);
        
        $image->refresh();
        $this->assertEquals('New Alt Text', $image->alt_text);
        $this->assertEquals(5, $image->sort_order);
    }

    /** @test */
    public function reset_form_clears_all_properties()
    {
        // Set all properties to non-default values
        $this->component->title = 'Test';
        $this->component->description = 'Test';
        $this->component->technologies = ['Laravel'];
        $this->component->github_url = 'https://github.com';
        $this->component->demo_url = 'https://demo.com';
        $this->component->featured = true;
        $this->component->status = 'draft';
        $this->component->sort_order = 5;
        $this->component->newImages = ['image'];
        $this->component->existingImages = [['id' => 1]];
        $this->component->imagesToDelete = [1];
        $this->component->editing = 123;
        
        // Use reflection to call private method
        $reflection = new \ReflectionClass($this->component);
        $method = $reflection->getMethod('resetForm');
        $method->setAccessible(true);
        $method->invoke($this->component);
        
        $this->assertEquals('', $this->component->title);
        $this->assertEquals('', $this->component->description);
        $this->assertEquals([], $this->component->technologies);
        $this->assertEquals('', $this->component->github_url);
        $this->assertEquals('', $this->component->demo_url);
        $this->assertFalse($this->component->featured);
        $this->assertEquals('published', $this->component->status);
        $this->assertEquals(0, $this->component->sort_order);
        $this->assertEquals([], $this->component->newImages);
        $this->assertEquals([], $this->component->existingImages);
        $this->assertEquals([], $this->component->imagesToDelete);
        $this->assertNull($this->component->editing);
    }
}
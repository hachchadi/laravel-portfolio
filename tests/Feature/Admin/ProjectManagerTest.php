<?php

namespace Tests\Feature\Admin;

use App\Livewire\Admin\ProjectManager;
use App\Models\Project;
use App\Models\ProjectImage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class ProjectManagerTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::factory()->create([
            'email' => 'admin@example.com',
            'is_admin' => true,
        ]);
    }

    /** @test */
    public function it_can_render_the_component()
    {
        $this->actingAs($this->admin);

        Livewire::test(ProjectManager::class)
            ->assertStatus(200)
            ->assertSee('Project Management')
            ->assertSee('Add New Project');
    }

    /** @test */
    public function it_displays_existing_projects()
    {
        $this->actingAs($this->admin);

        $project = Project::factory()->create([
            'title' => 'Test Project',
            'description' => 'Test Description',
            'status' => 'published',
        ]);

        Livewire::test(ProjectManager::class)
            ->assertSee('Test Project')
            ->assertSee('Test Description')
            ->assertSee('Published');
    }

    /** @test */
    public function it_can_create_a_new_project()
    {
        $this->actingAs($this->admin);

        Livewire::test(ProjectManager::class)
            ->call('create')
            ->assertSet('showForm', true)
            ->assertSet('editing', null)
            ->set('title', 'New Project')
            ->set('description', 'New project description')
            ->set('technologies', ['Laravel', 'Vue.js'])
            ->set('github_url', 'https://github.com/user/repo')
            ->set('demo_url', 'https://demo.example.com')
            ->set('featured', true)
            ->set('status', 'published')
            ->call('save')
            ->assertSet('showForm', false);

        $this->assertDatabaseHas('projects', [
            'title' => 'New Project',
            'description' => 'New project description',
            'github_url' => 'https://github.com/user/repo',
            'demo_url' => 'https://demo.example.com',
            'featured' => true,
            'status' => 'published',
        ]);
    }

    /** @test */
    public function it_can_edit_an_existing_project()
    {
        $this->actingAs($this->admin);

        $project = Project::factory()->create([
            'title' => 'Original Title',
            'description' => 'Original Description',
            'technologies' => ['PHP'],
            'status' => 'draft',
        ]);

        Livewire::test(ProjectManager::class)
            ->call('edit', $project->id)
            ->assertSet('editing', $project->id)
            ->assertSet('showForm', true)
            ->assertSet('title', 'Original Title')
            ->assertSet('description', 'Original Description')
            ->assertSet('technologies', ['PHP'])
            ->assertSet('status', 'draft')
            ->set('title', 'Updated Title')
            ->set('description', 'Updated Description')
            ->set('technologies', ['PHP', 'Laravel'])
            ->set('status', 'published')
            ->call('save')
            ->assertSet('showForm', false);

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'title' => 'Updated Title',
            'description' => 'Updated Description',
            'status' => 'published',
        ]);
    }

    /** @test */
    public function it_can_delete_a_project()
    {
        $this->actingAs($this->admin);

        $project = Project::factory()->create([
            'title' => 'Project to Delete',
        ]);

        Livewire::test(ProjectManager::class)
            ->call('confirmDelete', $project->id)
            ->assertSet('showDeleteModal', true)
            ->assertSet('projectToDelete', $project->id)
            ->call('delete')
            ->assertSet('showDeleteModal', false)
            ->assertSet('projectToDelete', null);

        $this->assertDatabaseMissing('projects', [
            'id' => $project->id,
        ]);
    }

    /** @test */
    public function it_can_cancel_delete_operation()
    {
        $this->actingAs($this->admin);

        $project = Project::factory()->create();

        Livewire::test(ProjectManager::class)
            ->call('confirmDelete', $project->id)
            ->assertSet('showDeleteModal', true)
            ->call('cancelDelete')
            ->assertSet('showDeleteModal', false)
            ->assertSet('projectToDelete', null);

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
        ]);
    }

    /** @test */
    public function it_validates_required_fields()
    {
        $this->actingAs($this->admin);

        Livewire::test(ProjectManager::class)
            ->call('create')
            ->set('title', '')
            ->set('description', '')
            ->call('save')
            ->assertHasErrors(['title', 'description']);
    }

    /** @test */
    public function it_validates_url_fields()
    {
        $this->actingAs($this->admin);

        Livewire::test(ProjectManager::class)
            ->call('create')
            ->set('title', 'Test Project')
            ->set('description', 'Test Description')
            ->set('github_url', 'invalid-url')
            ->set('demo_url', 'also-invalid')
            ->call('save')
            ->assertHasErrors(['github_url', 'demo_url']);
    }

    /** @test */
    public function it_can_add_and_remove_technologies()
    {
        $this->actingAs($this->admin);

        Livewire::test(ProjectManager::class)
            ->call('create')
            ->assertSet('technologies', [])
            ->call('addTechnology')
            ->assertCount('technologies', 1)
            ->set('technologies.0', 'Laravel')
            ->call('addTechnology')
            ->assertCount('technologies', 2)
            ->set('technologies.1', 'Vue.js')
            ->call('removeTechnology', 0)
            ->assertCount('technologies', 1)
            ->assertSet('technologies.0', 'Vue.js');
    }

    /** @test */
    public function it_can_handle_image_uploads()
    {
        Storage::fake('public');
        $this->actingAs($this->admin);

        $image1 = UploadedFile::fake()->image('project1.jpg', 800, 600);
        $image2 = UploadedFile::fake()->image('project2.jpg', 800, 600);

        Livewire::test(ProjectManager::class)
            ->call('create')
            ->set('title', 'Project with Images')
            ->set('description', 'Project description')
            ->set('newImages', [$image1, $image2])
            ->call('save');

        $project = Project::where('title', 'Project with Images')->first();
        $this->assertNotNull($project);
        $this->assertCount(2, $project->images);

        // Verify images were stored
        foreach ($project->images as $projectImage) {
            Storage::disk('public')->assertExists($projectImage->image_path);
        }
    }

    /** @test */
    public function it_can_remove_existing_images()
    {
        Storage::fake('public');
        $this->actingAs($this->admin);

        $project = Project::factory()->create();
        $image = ProjectImage::factory()->create([
            'project_id' => $project->id,
            'image_path' => 'projects/test-image.jpg',
        ]);

        // Create the fake file
        Storage::disk('public')->put('projects/test-image.jpg', 'fake-image-content');

        Livewire::test(ProjectManager::class)
            ->call('edit', $project->id)
            ->call('removeExistingImage', $image->id)
            ->call('save');

        $this->assertDatabaseMissing('project_images', [
            'id' => $image->id,
        ]);

        Storage::disk('public')->assertMissing('projects/test-image.jpg');
    }

    /** @test */
    public function it_deletes_project_images_when_project_is_deleted()
    {
        Storage::fake('public');
        $this->actingAs($this->admin);

        $project = Project::factory()->create();
        $image = ProjectImage::factory()->create([
            'project_id' => $project->id,
            'image_path' => 'projects/test-image.jpg',
        ]);

        // Create the fake file
        Storage::disk('public')->put('projects/test-image.jpg', 'fake-image-content');

        Livewire::test(ProjectManager::class)
            ->call('confirmDelete', $project->id)
            ->call('delete');

        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
        $this->assertDatabaseMissing('project_images', ['id' => $image->id]);
        Storage::disk('public')->assertMissing('projects/test-image.jpg');
    }

    /** @test */
    public function it_can_cancel_form_editing()
    {
        $this->actingAs($this->admin);

        Livewire::test(ProjectManager::class)
            ->call('create')
            ->assertSet('showForm', true)
            ->set('title', 'Some title')
            ->set('description', 'Some description')
            ->call('cancel')
            ->assertSet('showForm', false)
            ->assertSet('title', '')
            ->assertSet('description', '');
    }

    /** @test */
    public function it_loads_existing_images_when_editing()
    {
        $this->actingAs($this->admin);

        $project = Project::factory()->create();
        $image1 = ProjectImage::factory()->create([
            'project_id' => $project->id,
            'alt_text' => 'Test Image 1',
            'sort_order' => 0,
        ]);
        $image2 = ProjectImage::factory()->create([
            'project_id' => $project->id,
            'alt_text' => 'Test Image 2',
            'sort_order' => 1,
        ]);

        Livewire::test(ProjectManager::class)
            ->call('edit', $project->id)
            ->assertCount('existingImages', 2)
            ->assertSet('existingImages.0.id', $image1->id)
            ->assertSet('existingImages.0.alt_text', 'Test Image 1')
            ->assertSet('existingImages.1.id', $image2->id)
            ->assertSet('existingImages.1.alt_text', 'Test Image 2');
    }

    /** @test */
    public function it_validates_image_file_types()
    {
        Storage::fake('public');
        $this->actingAs($this->admin);

        $invalidFile = UploadedFile::fake()->create('document.pdf', 1000);

        Livewire::test(ProjectManager::class)
            ->call('create')
            ->set('title', 'Test Project')
            ->set('description', 'Test Description')
            ->set('newImages', [$invalidFile])
            ->call('save')
            ->assertHasErrors(['newImages.0']);
    }

    /** @test */
    public function it_validates_image_file_size()
    {
        Storage::fake('public');
        $this->actingAs($this->admin);

        $largeImage = UploadedFile::fake()->image('large.jpg')->size(3000); // 3MB

        Livewire::test(ProjectManager::class)
            ->call('create')
            ->set('title', 'Test Project')
            ->set('description', 'Test Description')
            ->set('newImages', [$largeImage])
            ->call('save')
            ->assertHasErrors(['newImages.0']);
    }

    /** @test */
    public function it_shows_empty_state_when_no_projects_exist()
    {
        $this->actingAs($this->admin);

        Livewire::test(ProjectManager::class)
            ->assertSee('No projects')
            ->assertSee('Get started by creating your first project');
    }

    /** @test */
    public function it_displays_project_status_correctly()
    {
        $this->actingAs($this->admin);

        $publishedProject = Project::factory()->create([
            'title' => 'Published Project',
            'status' => 'published',
        ]);

        $draftProject = Project::factory()->create([
            'title' => 'Draft Project',
            'status' => 'draft',
        ]);

        Livewire::test(ProjectManager::class)
            ->assertSee('Published Project')
            ->assertSee('Published')
            ->assertSee('Draft Project')
            ->assertSee('Draft');
    }
}
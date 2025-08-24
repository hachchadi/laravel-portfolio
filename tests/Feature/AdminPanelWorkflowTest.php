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
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use App\Livewire\Admin\ProfileEditor;
use App\Livewire\Admin\ProjectManager;
use App\Livewire\Admin\SkillsManager;

class AdminPanelWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create admin user
        $this->adminUser = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'is_admin' => true
        ]);

        Storage::fake('public');
    }

    /** @test */
    public function it_requires_authentication_to_access_admin_panel()
    {
        $response = $this->get('/admin');

        $response->assertRedirect('/login');
    }

    /** @test */
    public function it_allows_admin_user_to_access_admin_panel()
    {
        $response = $this->actingAs($this->adminUser)->get('/admin');

        $response->assertStatus(200)
            ->assertSee('Admin Dashboard')
            ->assertSee('Profile Management')
            ->assertSee('Project Management')
            ->assertSee('Skills Management');
    }

    /** @test */
    public function it_prevents_non_admin_users_from_accessing_admin_panel()
    {
        $regularUser = User::factory()->create(['is_admin' => false]);

        $response = $this->actingAs($regularUser)->get('/admin');

        $response->assertStatus(403);
    }

    /** @test */
    public function admin_can_update_profile_information()
    {
        $this->actingAs($this->adminUser);

        $updatedData = [
            'name' => 'Updated Name',
            'title' => 'Updated Title',
            'bio' => 'Updated bio content',
            'linkedin_url' => 'https://linkedin.com/in/updated',
            'github_url' => 'https://github.com/updated',
            'phone' => '+1234567890',
            'location' => 'Updated Location'
        ];

        Livewire::test(ProfileEditor::class)
            ->set('name', $updatedData['name'])
            ->set('title', $updatedData['title'])
            ->set('bio', $updatedData['bio'])
            ->set('linkedin_url', $updatedData['linkedin_url'])
            ->set('github_url', $updatedData['github_url'])
            ->set('phone', $updatedData['phone'])
            ->set('location', $updatedData['location'])
            ->call('save')
            ->assertHasNoErrors()
            ->assertSet('saved', true);

        $this->assertDatabaseHas('users', $updatedData);
    }

    /** @test */
    public function admin_can_upload_avatar_image()
    {
        $this->actingAs($this->adminUser);

        $file = UploadedFile::fake()->image('avatar.jpg', 300, 300);

        Livewire::test(ProfileEditor::class)
            ->set('avatar', $file)
            ->call('save')
            ->assertHasNoErrors();

        Storage::disk('public')->assertExists('avatars/' . $file->hashName());
    }

    /** @test */
    public function admin_can_create_new_project()
    {
        $this->actingAs($this->adminUser);

        $projectData = [
            'title' => 'New Test Project',
            'description' => 'This is a test project description',
            'technologies' => ['Laravel', 'Vue.js', 'MySQL'],
            'github_url' => 'https://github.com/user/project',
            'demo_url' => 'https://demo.example.com',
            'featured' => true,
            'status' => 'published'
        ];

        Livewire::test(ProjectManager::class)
            ->call('create')
            ->set('title', $projectData['title'])
            ->set('description', $projectData['description'])
            ->set('technologies', $projectData['technologies'])
            ->set('github_url', $projectData['github_url'])
            ->set('demo_url', $projectData['demo_url'])
            ->set('featured', $projectData['featured'])
            ->set('status', $projectData['status'])
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('projects', [
            'title' => $projectData['title'],
            'description' => $projectData['description'],
            'github_url' => $projectData['github_url'],
            'demo_url' => $projectData['demo_url'],
            'featured' => $projectData['featured'],
            'status' => $projectData['status']
        ]);
    }

    /** @test */
    public function admin_can_edit_existing_project()
    {
        $this->actingAs($this->adminUser);

        $project = Project::factory()->create([
            'title' => 'Original Title',
            'description' => 'Original description'
        ]);

        $updatedData = [
            'title' => 'Updated Project Title',
            'description' => 'Updated project description'
        ];

        Livewire::test(ProjectManager::class)
            ->call('edit', $project->id)
            ->set('title', $updatedData['title'])
            ->set('description', $updatedData['description'])
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'title' => $updatedData['title'],
            'description' => $updatedData['description']
        ]);
    }

    /** @test */
    public function admin_can_delete_project()
    {
        $this->actingAs($this->adminUser);

        $project = Project::factory()->create();

        Livewire::test(ProjectManager::class)
            ->call('delete', $project->id)
            ->assertHasNoErrors();

        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    }

    /** @test */
    public function admin_can_upload_project_images()
    {
        $this->actingAs($this->adminUser);

        $project = Project::factory()->create();
        $image = UploadedFile::fake()->image('project.jpg', 800, 600);

        Livewire::test(ProjectManager::class)
            ->call('edit', $project->id)
            ->set('newImages', [$image])
            ->call('uploadImages')
            ->assertHasNoErrors();

        Storage::disk('public')->assertExists('projects/' . $image->hashName());
        
        $this->assertDatabaseHas('project_images', [
            'project_id' => $project->id
        ]);
    }

    /** @test */
    public function admin_can_manage_project_status()
    {
        $this->actingAs($this->adminUser);

        $project = Project::factory()->create(['status' => 'draft']);

        Livewire::test(ProjectManager::class)
            ->call('edit', $project->id)
            ->set('status', 'published')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'status' => 'published'
        ]);
    }

    /** @test */
    public function admin_can_create_new_skill()
    {
        $this->actingAs($this->adminUser);

        $skillData = [
            'name' => 'New Skill',
            'category' => 'Backend',
            'proficiency' => 85
        ];

        Livewire::test(SkillsManager::class)
            ->call('create')
            ->set('name', $skillData['name'])
            ->set('category', $skillData['category'])
            ->set('proficiency', $skillData['proficiency'])
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('skills', $skillData);
    }

    /** @test */
    public function admin_can_edit_existing_skill()
    {
        $this->actingAs($this->adminUser);

        $skill = Skill::factory()->create([
            'name' => 'Original Skill',
            'proficiency' => 70
        ]);

        $updatedData = [
            'name' => 'Updated Skill',
            'proficiency' => 90
        ];

        Livewire::test(SkillsManager::class)
            ->call('edit', $skill->id)
            ->set('name', $updatedData['name'])
            ->set('proficiency', $updatedData['proficiency'])
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('skills', [
            'id' => $skill->id,
            'name' => $updatedData['name'],
            'proficiency' => $updatedData['proficiency']
        ]);
    }

    /** @test */
    public function admin_can_delete_skill()
    {
        $this->actingAs($this->adminUser);

        $skill = Skill::factory()->create();

        Livewire::test(SkillsManager::class)
            ->call('delete', $skill->id)
            ->assertHasNoErrors();

        $this->assertDatabaseMissing('skills', ['id' => $skill->id]);
    }

    /** @test */
    public function admin_can_bulk_import_skills()
    {
        $this->actingAs($this->adminUser);

        $skillsData = [
            ['name' => 'PHP', 'category' => 'Backend', 'proficiency' => 95],
            ['name' => 'JavaScript', 'category' => 'Frontend', 'proficiency' => 85],
            ['name' => 'MySQL', 'category' => 'Database', 'proficiency' => 80]
        ];

        Livewire::test(SkillsManager::class)
            ->set('bulkSkillsData', json_encode($skillsData))
            ->call('bulkImport')
            ->assertHasNoErrors();

        foreach ($skillsData as $skill) {
            $this->assertDatabaseHas('skills', $skill);
        }
    }

    /** @test */
    public function admin_can_reorder_skills()
    {
        $this->actingAs($this->adminUser);

        $skills = Skill::factory()->count(3)->create();
        $newOrder = $skills->pluck('id')->reverse()->toArray();

        Livewire::test(SkillsManager::class)
            ->call('reorderSkills', $newOrder)
            ->assertHasNoErrors();

        foreach ($newOrder as $index => $skillId) {
            $this->assertDatabaseHas('skills', [
                'id' => $skillId,
                'sort_order' => $index
            ]);
        }
    }

    /** @test */
    public function admin_panel_validates_required_fields()
    {
        $this->actingAs($this->adminUser);

        // Test project validation
        Livewire::test(ProjectManager::class)
            ->call('create')
            ->call('save')
            ->assertHasErrors(['title', 'description']);

        // Test skill validation
        Livewire::test(SkillsManager::class)
            ->call('create')
            ->call('save')
            ->assertHasErrors(['name', 'category']);
    }

    /** @test */
    public function admin_panel_validates_url_formats()
    {
        $this->actingAs($this->adminUser);

        Livewire::test(ProfileEditor::class)
            ->set('linkedin_url', 'invalid-url')
            ->set('github_url', 'also-invalid')
            ->call('save')
            ->assertHasErrors(['linkedin_url', 'github_url']);
    }

    /** @test */
    public function admin_panel_validates_image_uploads()
    {
        $this->actingAs($this->adminUser);

        $invalidFile = UploadedFile::fake()->create('document.pdf', 1000);

        Livewire::test(ProfileEditor::class)
            ->set('avatar', $invalidFile)
            ->call('save')
            ->assertHasErrors(['avatar']);
    }

    /** @test */
    public function admin_can_view_contact_messages()
    {
        $this->actingAs($this->adminUser);

        $response = $this->get('/admin/messages');

        $response->assertStatus(200)
            ->assertSee('Contact Messages')
            ->assertSee('Unread')
            ->assertSee('Read')
            ->assertSee('Replied');
    }

    /** @test */
    public function admin_can_mark_messages_as_read()
    {
        $this->actingAs($this->adminUser);

        $message = \App\Models\ContactMessage::factory()->create([
            'status' => 'unread'
        ]);

        $response = $this->patch("/admin/messages/{$message->id}/read");

        $response->assertStatus(200);
        
        $this->assertDatabaseHas('contact_messages', [
            'id' => $message->id,
            'status' => 'read'
        ]);
    }

    /** @test */
    public function admin_panel_includes_security_features()
    {
        $this->actingAs($this->adminUser);

        $response = $this->get('/admin');

        $response->assertStatus(200)
            ->assertSeeHtml('@csrf')
            ->assertSee('Logout');
    }

    /** @test */
    public function admin_can_logout_successfully()
    {
        $this->actingAs($this->adminUser);

        $response = $this->post('/admin/logout');

        $response->assertRedirect('/');
        $this->assertGuest();
    }

    /** @test */
    public function admin_panel_shows_dashboard_statistics()
    {
        $this->actingAs($this->adminUser);

        // Create test data
        Project::factory()->count(5)->create(['status' => 'published']);
        Project::factory()->count(2)->create(['status' => 'draft']);
        Skill::factory()->count(10)->create();
        \App\Models\ContactMessage::factory()->count(3)->create(['status' => 'unread']);

        $response = $this->get('/admin');

        $response->assertStatus(200)
            ->assertSee('5') // Published projects
            ->assertSee('2') // Draft projects
            ->assertSee('10') // Total skills
            ->assertSee('3'); // Unread messages
    }
}
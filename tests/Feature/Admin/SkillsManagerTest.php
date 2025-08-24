<?php

namespace Tests\Feature\Admin;

use App\Livewire\Admin\SkillsManager;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class SkillsManagerTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();
        
        Storage::fake('local');
        
        // Create admin user
        $this->adminUser = User::factory()->create(['is_admin' => true]);
    }

    /** @test */
    public function admin_can_access_skills_manager()
    {
        $this->actingAs($this->adminUser);
        
        $component = Livewire::test(SkillsManager::class);
        
        $component->assertStatus(200);
        $component->assertSee('Skills Management');
        $component->assertSee('Add New Skill');
        $component->assertSee('Import Skills');
    }

    /** @test */
    public function it_displays_skills_grouped_by_category()
    {
        $this->actingAs($this->adminUser);
        
        Skill::factory()->create(['name' => 'Laravel', 'category' => 'Backend']);
        Skill::factory()->create(['name' => 'React', 'category' => 'Frontend']);
        Skill::factory()->create(['name' => 'MySQL', 'category' => 'Database']);
        
        $component = Livewire::test(SkillsManager::class);
        
        $component->assertSee('Backend');
        $component->assertSee('Frontend');
        $component->assertSee('Database');
        $component->assertSee('Laravel');
        $component->assertSee('React');
        $component->assertSee('MySQL');
    }

    /** @test */
    public function admin_can_create_new_skill()
    {
        $this->actingAs($this->adminUser);
        
        $component = Livewire::test(SkillsManager::class);
        
        $component->call('create')
                  ->assertSet('showForm', true)
                  ->set('name', 'Vue.js')
                  ->set('category', 'Frontend')
                  ->set('proficiency', 85)
                  ->set('sort_order', 1)
                  ->call('save');
        
        $this->assertDatabaseHas('skills', [
            'name' => 'Vue.js',
            'category' => 'Frontend',
            'proficiency' => 85,
            'sort_order' => 1
        ]);
        
        $component->assertSet('showForm', false);
        $component->assertSessionHas('message', 'Skill created successfully!');
    }

    /** @test */
    public function admin_can_edit_existing_skill()
    {
        $this->actingAs($this->adminUser);
        
        $skill = Skill::factory()->create([
            'name' => 'Laravel',
            'category' => 'Backend',
            'proficiency' => 90
        ]);
        
        $component = Livewire::test(SkillsManager::class);
        
        $component->call('edit', $skill->id)
                  ->assertSet('showForm', true)
                  ->assertSet('name', 'Laravel')
                  ->set('name', 'Laravel Framework')
                  ->set('proficiency', 95)
                  ->call('save');
        
        $this->assertDatabaseHas('skills', [
            'id' => $skill->id,
            'name' => 'Laravel Framework',
            'proficiency' => 95
        ]);
        
        $component->assertSessionHas('message', 'Skill updated successfully!');
    }

    /** @test */
    public function admin_can_delete_skill()
    {
        $this->actingAs($this->adminUser);
        
        $skill = Skill::factory()->create(['name' => 'Old Skill']);
        
        $component = Livewire::test(SkillsManager::class);
        
        $component->call('confirmDelete', $skill->id)
                  ->assertSet('showDeleteModal', true)
                  ->call('delete');
        
        $this->assertDatabaseMissing('skills', ['id' => $skill->id]);
        $component->assertSessionHas('message', 'Skill deleted successfully!');
    }

    /** @test */
    public function form_validation_works_correctly()
    {
        $this->actingAs($this->adminUser);
        
        $component = Livewire::test(SkillsManager::class);
        
        // Test required fields
        $component->call('save')
                  ->assertHasErrors(['name', 'category', 'proficiency']);
        
        // Test proficiency range
        $component->set('name', 'Test Skill')
                  ->set('category', 'Backend')
                  ->set('proficiency', 150)
                  ->call('save')
                  ->assertHasErrors(['proficiency']);
        
        $component->set('proficiency', 0)
                  ->call('save')
                  ->assertHasErrors(['proficiency']);
        
        // Test valid data
        $component->set('proficiency', 85)
                  ->call('save')
                  ->assertHasNoErrors();
    }

    /** @test */
    public function admin_can_import_skills_from_json()
    {
        $this->actingAs($this->adminUser);
        
        $jsonData = [
            [
                'name' => 'Angular',
                'category' => 'Frontend',
                'proficiency' => 80,
                'sort_order' => 0
            ],
            [
                'name' => 'Docker',
                'category' => 'Tools',
                'proficiency' => 75,
                'sort_order' => 1
            ]
        ];
        
        $file = UploadedFile::fake()->createWithContent(
            'skills.json',
            json_encode($jsonData)
        );
        
        $component = Livewire::test(SkillsManager::class);
        
        $component->call('showImport')
                  ->assertSet('showImportModal', true)
                  ->set('importFile', $file)
                  ->set('importType', 'json')
                  ->call('processImport');
        
        $this->assertCount(2, $component->get('importPreview'));
        
        $component->call('confirmImport');
        
        $this->assertDatabaseHas('skills', ['name' => 'Angular', 'category' => 'Frontend']);
        $this->assertDatabaseHas('skills', ['name' => 'Docker', 'category' => 'Tools']);
        
        $component->assertSessionHas('message');
    }

    /** @test */
    public function admin_can_import_skills_from_csv()
    {
        $this->actingAs($this->adminUser);
        
        $csvContent = "name,category,proficiency,sort_order\n" .
                     "React,Frontend,85,0\n" .
                     "PostgreSQL,Database,90,1";
        
        $file = UploadedFile::fake()->createWithContent('skills.csv', $csvContent);
        
        $component = Livewire::test(SkillsManager::class);
        
        $component->call('showImport')
                  ->set('importFile', $file)
                  ->set('importType', 'csv')
                  ->call('processImport');
        
        $this->assertCount(2, $component->get('importPreview'));
        
        $component->call('confirmImport');
        
        $this->assertDatabaseHas('skills', ['name' => 'React', 'category' => 'Frontend']);
        $this->assertDatabaseHas('skills', ['name' => 'PostgreSQL', 'category' => 'Database']);
    }

    /** @test */
    public function import_validates_data_correctly()
    {
        $this->actingAs($this->adminUser);
        
        $invalidData = [
            [
                'name' => '', // Invalid: empty name
                'category' => 'Frontend',
                'proficiency' => 150, // Invalid: over 100
                'sort_order' => 0
            ],
            [
                'name' => 'Valid Skill',
                'category' => 'Backend',
                'proficiency' => 85,
                'sort_order' => 1
            ]
        ];
        
        $file = UploadedFile::fake()->createWithContent(
            'invalid_skills.json',
            json_encode($invalidData)
        );
        
        $component = Livewire::test(SkillsManager::class);
        
        $component->call('showImport')
                  ->set('importFile', $file)
                  ->set('importType', 'json')
                  ->call('processImport');
        
        $preview = $component->get('importPreview');
        
        // First skill should be invalid
        $this->assertFalse($preview[0]['valid']);
        $this->assertNotEmpty($preview[0]['errors']);
        
        // Second skill should be valid
        $this->assertTrue($preview[1]['valid']);
        $this->assertEmpty($preview[1]['errors']);
    }

    /** @test */
    public function import_skips_duplicate_skills()
    {
        $this->actingAs($this->adminUser);
        
        // Create existing skill
        Skill::factory()->create([
            'name' => 'Laravel',
            'category' => 'Backend'
        ]);
        
        $importData = [
            [
                'name' => 'Laravel', // Duplicate
                'category' => 'Backend',
                'proficiency' => 95,
                'sort_order' => 0
            ],
            [
                'name' => 'New Skill',
                'category' => 'Frontend',
                'proficiency' => 80,
                'sort_order' => 1
            ]
        ];
        
        $component = Livewire::test(SkillsManager::class);
        
        $component->set('importPreview', array_map(function($skill) {
            return array_merge($skill, ['valid' => true, 'errors' => []]);
        }, $importData));
        
        $component->call('confirmImport');
        
        // Should only have 2 skills total (1 existing + 1 new)
        $this->assertEquals(2, Skill::count());
        $this->assertDatabaseHas('skills', ['name' => 'New Skill']);
        
        $component->assertSessionHas('message');
    }

    /** @test */
    public function admin_can_reorder_skills()
    {
        $this->actingAs($this->adminUser);
        
        $skill1 = Skill::factory()->create(['name' => 'Skill 1', 'sort_order' => 0]);
        $skill2 = Skill::factory()->create(['name' => 'Skill 2', 'sort_order' => 1]);
        $skill3 = Skill::factory()->create(['name' => 'Skill 3', 'sort_order' => 2]);
        
        $component = Livewire::test(SkillsManager::class);
        
        // Reorder: skill3, skill1, skill2
        $newOrder = [
            ['id' => $skill3->id],
            ['id' => $skill1->id],
            ['id' => $skill2->id]
        ];
        
        $component->call('updateSkillOrder', $newOrder);
        
        $this->assertEquals(0, $skill3->fresh()->sort_order);
        $this->assertEquals(1, $skill1->fresh()->sort_order);
        $this->assertEquals(2, $skill2->fresh()->sort_order);
        
        $component->assertSessionHas('message', 'Skill order updated successfully!');
    }

    /** @test */
    public function it_displays_skill_proficiency_bars()
    {
        $this->actingAs($this->adminUser);
        
        $skill = Skill::factory()->create([
            'name' => 'Laravel',
            'proficiency' => 85
        ]);
        
        $component = Livewire::test(SkillsManager::class);
        
        $component->assertSee('85%');
        $component->assertSee('Laravel');
    }

    /** @test */
    public function cancel_actions_work_correctly()
    {
        $this->actingAs($this->adminUser);
        
        $skill = Skill::factory()->create();
        
        $component = Livewire::test(SkillsManager::class);
        
        // Test cancel form
        $component->call('create')
                  ->set('name', 'Test')
                  ->call('cancel')
                  ->assertSet('showForm', false)
                  ->assertSet('name', '');
        
        // Test cancel delete
        $component->call('confirmDelete', $skill->id)
                  ->call('cancelDelete')
                  ->assertSet('showDeleteModal', false)
                  ->assertSet('skillToDelete', null);
        
        // Test cancel import
        $component->call('showImport')
                  ->call('cancelImport')
                  ->assertSet('showImportModal', false);
    }

    /** @test */
    public function it_handles_file_upload_errors()
    {
        $this->actingAs($this->adminUser);
        
        $component = Livewire::test(SkillsManager::class);
        
        // Test without file
        $component->call('showImport')
                  ->call('processImport')
                  ->assertHasErrors(['importFile']);
        
        // Test invalid JSON
        $invalidJson = UploadedFile::fake()->createWithContent(
            'invalid.json',
            'invalid json content'
        );
        
        $component->set('importFile', $invalidJson)
                  ->set('importType', 'json')
                  ->call('processImport');
        
        $component->assertSessionHas('error');
    }

    /** @test */
    public function it_shows_available_categories_in_form()
    {
        $this->actingAs($this->adminUser);
        
        $component = Livewire::test(SkillsManager::class);
        
        $component->call('create');
        
        $availableCategories = $component->get('availableCategories');
        
        $this->assertContains('Backend', $availableCategories);
        $this->assertContains('Frontend', $availableCategories);
        $this->assertContains('Database', $availableCategories);
        $this->assertContains('Tools', $availableCategories);
        $this->assertContains('DevOps', $availableCategories);
    }
}
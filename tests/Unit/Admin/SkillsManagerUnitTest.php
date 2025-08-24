<?php

namespace Tests\Unit\Admin;

use App\Livewire\Admin\SkillsManager;
use App\Models\Skill;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class SkillsManagerUnitTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
    }

    /** @test */
    public function it_can_render_the_component()
    {
        $component = Livewire::test(SkillsManager::class);
        
        $component->assertStatus(200);
        $component->assertViewIs('livewire.admin.skills-manager');
    }

    /** @test */
    public function it_loads_skills_on_mount()
    {
        $skills = Skill::factory()->count(3)->create();
        
        $component = Livewire::test(SkillsManager::class);
        
        // Skills are loaded in render method, so we check the view data
        $component->assertViewHas('skills');
        $component->assertViewHas('skillsByCategory');
    }

    /** @test */
    public function it_can_show_create_form()
    {
        $component = Livewire::test(SkillsManager::class);
        
        $component->call('create');
        
        $component->assertSet('showForm', true);
        $component->assertSet('editing', null);
        $component->assertSet('name', '');
        $component->assertSet('category', '');
        $component->assertSet('proficiency', 80);
    }

    /** @test */
    public function it_can_show_edit_form()
    {
        $skill = Skill::factory()->create([
            'name' => 'Laravel',
            'category' => 'Backend',
            'proficiency' => 95,
            'sort_order' => 1
        ]);
        
        $component = Livewire::test(SkillsManager::class);
        
        $component->call('edit', $skill->id);
        
        $component->assertSet('showForm', true);
        $component->assertSet('editing', $skill->id);
        $component->assertSet('name', 'Laravel');
        $component->assertSet('category', 'Backend');
        $component->assertSet('proficiency', 95);
        $component->assertSet('sort_order', 1);
    }

    /** @test */
    public function it_can_create_a_new_skill()
    {
        $component = Livewire::test(SkillsManager::class);
        
        $component->set('name', 'React')
                  ->set('category', 'Frontend')
                  ->set('proficiency', 85)
                  ->set('sort_order', 2)
                  ->call('save');
        
        $this->assertDatabaseHas('skills', [
            'name' => 'React',
            'category' => 'Frontend',
            'proficiency' => 85,
            'sort_order' => 2
        ]);
        
        $component->assertSet('showForm', false);
        $component->assertSessionHas('message', 'Skill created successfully!');
    }

    /** @test */
    public function it_can_update_an_existing_skill()
    {
        $skill = Skill::factory()->create([
            'name' => 'Laravel',
            'category' => 'Backend',
            'proficiency' => 90
        ]);
        
        $component = Livewire::test(SkillsManager::class);
        
        $component->call('edit', $skill->id)
                  ->set('name', 'Laravel Framework')
                  ->set('proficiency', 95)
                  ->call('save');
        
        $this->assertDatabaseHas('skills', [
            'id' => $skill->id,
            'name' => 'Laravel Framework',
            'proficiency' => 95
        ]);
        
        $component->assertSet('showForm', false);
        $component->assertSessionHas('message', 'Skill updated successfully!');
    }

    /** @test */
    public function it_validates_required_fields()
    {
        $component = Livewire::test(SkillsManager::class);
        
        $component->call('save');
        
        $component->assertHasErrors(['name', 'category', 'proficiency']);
    }

    /** @test */
    public function it_validates_proficiency_range()
    {
        $component = Livewire::test(SkillsManager::class);
        
        $component->set('name', 'Test Skill')
                  ->set('category', 'Backend')
                  ->set('proficiency', 150)
                  ->call('save');
        
        $component->assertHasErrors(['proficiency']);
        
        $component->set('proficiency', 0)
                  ->call('save');
        
        $component->assertHasErrors(['proficiency']);
    }

    /** @test */
    public function it_can_show_delete_confirmation()
    {
        $skill = Skill::factory()->create();
        
        $component = Livewire::test(SkillsManager::class);
        
        $component->call('confirmDelete', $skill->id);
        
        $component->assertSet('showDeleteModal', true);
        $component->assertSet('skillToDelete', $skill->id);
    }

    /** @test */
    public function it_can_delete_a_skill()
    {
        $skill = Skill::factory()->create();
        
        $component = Livewire::test(SkillsManager::class);
        
        $component->call('confirmDelete', $skill->id)
                  ->call('delete');
        
        $this->assertDatabaseMissing('skills', ['id' => $skill->id]);
        $component->assertSet('showDeleteModal', false);
        $component->assertSessionHas('message', 'Skill deleted successfully!');
    }

    /** @test */
    public function it_can_cancel_delete()
    {
        $skill = Skill::factory()->create();
        
        $component = Livewire::test(SkillsManager::class);
        
        $component->call('confirmDelete', $skill->id)
                  ->call('cancelDelete');
        
        $component->assertSet('showDeleteModal', false);
        $component->assertSet('skillToDelete', null);
        $this->assertDatabaseHas('skills', ['id' => $skill->id]);
    }

    /** @test */
    public function it_can_cancel_form()
    {
        $component = Livewire::test(SkillsManager::class);
        
        $component->set('name', 'Test')
                  ->set('showForm', true)
                  ->call('cancel');
        
        $component->assertSet('showForm', false);
        $component->assertSet('name', '');
        $component->assertSet('category', '');
        $component->assertSet('proficiency', 80);
    }

    /** @test */
    public function it_can_show_import_modal()
    {
        $component = Livewire::test(SkillsManager::class);
        
        $component->call('showImport');
        
        $component->assertSet('showImportModal', true);
        $component->assertSet('importType', 'json');
        $component->assertSet('importData', []);
        $component->assertSet('importPreview', []);
    }

    /** @test */
    public function it_can_process_json_import()
    {
        $jsonContent = json_encode([
            [
                'name' => 'Vue.js',
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
        ]);
        
        $file = UploadedFile::fake()->createWithContent('skills.json', $jsonContent);
        
        $component = Livewire::test(SkillsManager::class);
        
        $component->set('importFile', $file)
                  ->set('importType', 'json')
                  ->call('processImport');
        
        $this->assertCount(2, $component->get('importPreview'));
        $this->assertEquals('Vue.js', $component->get('importPreview')[0]['name']);
        $this->assertEquals('Docker', $component->get('importPreview')[1]['name']);
    }

    /** @test */
    public function it_can_process_csv_import()
    {
        $csvContent = "name,category,proficiency,sort_order\nReact,Frontend,85,0\nMySQL,Database,90,1";
        
        $file = UploadedFile::fake()->createWithContent('skills.csv', $csvContent);
        
        $component = Livewire::test(SkillsManager::class);
        
        $component->set('importFile', $file)
                  ->set('importType', 'csv')
                  ->call('processImport');
        
        $this->assertCount(2, $component->get('importPreview'));
        $this->assertEquals('React', $component->get('importPreview')[0]['name']);
        $this->assertEquals('MySQL', $component->get('importPreview')[1]['name']);
    }

    /** @test */
    public function it_validates_import_data()
    {
        $jsonContent = json_encode([
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
        ]);
        
        $file = UploadedFile::fake()->createWithContent('skills.json', $jsonContent);
        
        $component = Livewire::test(SkillsManager::class);
        
        $component->set('importFile', $file)
                  ->set('importType', 'json')
                  ->call('processImport');
        
        $preview = $component->get('importPreview');
        $this->assertFalse($preview[0]['valid']);
        $this->assertTrue($preview[1]['valid']);
        $this->assertNotEmpty($preview[0]['errors']);
    }

    /** @test */
    public function it_can_confirm_import()
    {
        $component = Livewire::test(SkillsManager::class);
        
        // Set up valid import preview
        $component->set('importPreview', [
            [
                'name' => 'Angular',
                'category' => 'Frontend',
                'proficiency' => 80,
                'sort_order' => 0,
                'valid' => true,
                'errors' => []
            ],
            [
                'name' => 'PostgreSQL',
                'category' => 'Database',
                'proficiency' => 85,
                'sort_order' => 1,
                'valid' => true,
                'errors' => []
            ]
        ]);
        
        $component->call('confirmImport');
        
        $this->assertDatabaseHas('skills', ['name' => 'Angular', 'category' => 'Frontend']);
        $this->assertDatabaseHas('skills', ['name' => 'PostgreSQL', 'category' => 'Database']);
        
        $component->assertSet('showImportModal', false);
        $component->assertSessionHas('message');
    }

    /** @test */
    public function it_can_update_skill_order()
    {
        $skill1 = Skill::factory()->create(['sort_order' => 0]);
        $skill2 = Skill::factory()->create(['sort_order' => 1]);
        
        $component = Livewire::test(SkillsManager::class);
        
        $orderedSkills = [
            ['id' => $skill2->id],
            ['id' => $skill1->id]
        ];
        
        $component->call('updateSkillOrder', $orderedSkills);
        
        $this->assertEquals(0, $skill2->fresh()->sort_order);
        $this->assertEquals(1, $skill1->fresh()->sort_order);
        
        $component->assertSessionHas('message', 'Skill order updated successfully!');
    }

    /** @test */
    public function it_handles_errors_gracefully()
    {
        $component = Livewire::test(SkillsManager::class);
        
        // Try to edit non-existent skill
        $component->call('edit', 999);
        
        // Should handle the error without crashing
        $component->assertStatus(200);
    }
}
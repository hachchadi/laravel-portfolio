<?php

namespace Tests\Feature\Seeders;

use App\Models\Skill;
use Database\Seeders\SkillSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SkillSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_skill_seeder_creates_skills(): void
    {
        $this->seed(SkillSeeder::class);

        $this->assertGreaterThan(0, Skill::count());
    }

    public function test_skill_seeder_creates_skills_with_required_fields(): void
    {
        $this->seed(SkillSeeder::class);

        $skill = Skill::first();

        $this->assertNotEmpty($skill->name);
        $this->assertNotEmpty($skill->category);
        $this->assertIsInt($skill->proficiency);
        $this->assertGreaterThanOrEqual(0, $skill->proficiency);
        $this->assertLessThanOrEqual(100, $skill->proficiency);
        $this->assertIsInt($skill->sort_order);
    }

    public function test_skill_seeder_creates_backend_skills(): void
    {
        $this->seed(SkillSeeder::class);

        $backendSkills = Skill::where('category', 'Backend')->get();

        $this->assertGreaterThan(0, $backendSkills->count());
        
        $skillNames = $backendSkills->pluck('name')->toArray();
        $this->assertContains('PHP', $skillNames);
        $this->assertContains('Laravel', $skillNames);
    }

    public function test_skill_seeder_creates_frontend_skills(): void
    {
        $this->seed(SkillSeeder::class);

        $frontendSkills = Skill::where('category', 'Frontend')->get();

        $this->assertGreaterThan(0, $frontendSkills->count());
        
        $skillNames = $frontendSkills->pluck('name')->toArray();
        $this->assertContains('JavaScript', $skillNames);
        $this->assertContains('Vue.js', $skillNames);
        $this->assertContains('Livewire', $skillNames);
    }

    public function test_skill_seeder_creates_database_skills(): void
    {
        $this->seed(SkillSeeder::class);

        $databaseSkills = Skill::where('category', 'Database')->get();

        $this->assertGreaterThan(0, $databaseSkills->count());
        
        $skillNames = $databaseSkills->pluck('name')->toArray();
        $this->assertContains('MySQL', $skillNames);
        $this->assertContains('PostgreSQL', $skillNames);
    }

    public function test_skill_seeder_creates_tools_skills(): void
    {
        $this->seed(SkillSeeder::class);

        $toolsSkills = Skill::where('category', 'Tools')->get();

        $this->assertGreaterThan(0, $toolsSkills->count());
        
        $skillNames = $toolsSkills->pluck('name')->toArray();
        $this->assertContains('Git', $skillNames);
        $this->assertContains('Docker', $skillNames);
    }

    public function test_skill_seeder_creates_testing_skills(): void
    {
        $this->seed(SkillSeeder::class);

        $testingSkills = Skill::where('category', 'Testing')->get();

        $this->assertGreaterThan(0, $testingSkills->count());
        
        $skillNames = $testingSkills->pluck('name')->toArray();
        $this->assertContains('PHPUnit', $skillNames);
    }

    public function test_skill_seeder_creates_architecture_skills(): void
    {
        $this->seed(SkillSeeder::class);

        $architectureSkills = Skill::where('category', 'Architecture')->get();

        $this->assertGreaterThan(0, $architectureSkills->count());
        
        $skillNames = $architectureSkills->pluck('name')->toArray();
        $this->assertContains('MVC Pattern', $skillNames);
        $this->assertContains('SOLID Principles', $skillNames);
    }

    public function test_skill_seeder_creates_management_skills(): void
    {
        $this->seed(SkillSeeder::class);

        $managementSkills = Skill::where('category', 'Management')->get();

        $this->assertGreaterThan(0, $managementSkills->count());
        
        $skillNames = $managementSkills->pluck('name')->toArray();
        $this->assertContains('Agile', $skillNames);
        $this->assertContains('Team Leadership', $skillNames);
    }

    public function test_skill_seeder_creates_skills_with_valid_proficiency(): void
    {
        $this->seed(SkillSeeder::class);

        $skills = Skill::all();

        foreach ($skills as $skill) {
            $this->assertGreaterThanOrEqual(0, $skill->proficiency);
            $this->assertLessThanOrEqual(100, $skill->proficiency);
        }
    }

    public function test_skill_seeder_creates_skills_with_sort_order(): void
    {
        $this->seed(SkillSeeder::class);

        $categories = Skill::distinct('category')->pluck('category');

        foreach ($categories as $category) {
            $skills = Skill::where('category', $category)->orderBy('sort_order')->get();
            
            $this->assertGreaterThan(0, $skills->count());
            
            // Check that sort orders are sequential starting from 1
            $expectedOrder = 1;
            foreach ($skills as $skill) {
                $this->assertEquals($expectedOrder, $skill->sort_order);
                $expectedOrder++;
            }
        }
    }

    public function test_skill_seeder_can_run_multiple_times(): void
    {
        $this->seed(SkillSeeder::class);
        $initialCount = Skill::count();
        
        $this->seed(SkillSeeder::class);
        
        // Should create new skills, not update existing ones
        $this->assertEquals($initialCount * 2, Skill::count());
    }

    public function test_skill_seeder_creates_skills_grouped_by_category(): void
    {
        $this->seed(SkillSeeder::class);

        $skillsByCategory = Skill::getByCategory();

        $this->assertArrayHasKey('Backend', $skillsByCategory);
        $this->assertArrayHasKey('Frontend', $skillsByCategory);
        $this->assertArrayHasKey('Database', $skillsByCategory);
        $this->assertArrayHasKey('Tools', $skillsByCategory);
        $this->assertArrayHasKey('Testing', $skillsByCategory);
        $this->assertArrayHasKey('Architecture', $skillsByCategory);
        $this->assertArrayHasKey('Management', $skillsByCategory);
    }

    public function test_skill_seeder_creates_high_proficiency_core_skills(): void
    {
        $this->seed(SkillSeeder::class);

        $phpSkill = Skill::where('name', 'PHP')->first();
        $laravelSkill = Skill::where('name', 'Laravel')->first();

        $this->assertGreaterThanOrEqual(90, $phpSkill->proficiency);
        $this->assertGreaterThanOrEqual(90, $laravelSkill->proficiency);
    }
}
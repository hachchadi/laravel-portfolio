<?php

namespace Database\Seeders;

use App\Models\Skill;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $skills = [
            // Backend Skills
            ['name' => 'PHP', 'category' => 'Backend', 'proficiency' => 95, 'sort_order' => 1],
            ['name' => 'Laravel', 'category' => 'Backend', 'proficiency' => 95, 'sort_order' => 2],
            ['name' => 'Symfony', 'category' => 'Backend', 'proficiency' => 80, 'sort_order' => 3],
            ['name' => 'Node.js', 'category' => 'Backend', 'proficiency' => 85, 'sort_order' => 4],
            ['name' => 'Express.js', 'category' => 'Backend', 'proficiency' => 80, 'sort_order' => 5],
            ['name' => 'Python', 'category' => 'Backend', 'proficiency' => 80, 'sort_order' => 6],
            ['name' => 'Django', 'category' => 'Backend', 'proficiency' => 75, 'sort_order' => 7],
            ['name' => 'REST APIs', 'category' => 'Backend', 'proficiency' => 90, 'sort_order' => 8],
            ['name' => 'GraphQL', 'category' => 'Backend', 'proficiency' => 75, 'sort_order' => 9],
            ['name' => 'Microservices', 'category' => 'Backend', 'proficiency' => 80, 'sort_order' => 10],

            // Frontend Skills
            ['name' => 'JavaScript', 'category' => 'Frontend', 'proficiency' => 90, 'sort_order' => 1],
            ['name' => 'TypeScript', 'category' => 'Frontend', 'proficiency' => 85, 'sort_order' => 2],
            ['name' => 'Vue.js', 'category' => 'Frontend', 'proficiency' => 90, 'sort_order' => 3],
            ['name' => 'React', 'category' => 'Frontend', 'proficiency' => 85, 'sort_order' => 4],
            ['name' => 'Livewire', 'category' => 'Frontend', 'proficiency' => 95, 'sort_order' => 5],
            ['name' => 'Alpine.js', 'category' => 'Frontend', 'proficiency' => 90, 'sort_order' => 6],
            ['name' => 'Tailwind CSS', 'category' => 'Frontend', 'proficiency' => 95, 'sort_order' => 7],
            ['name' => 'Bootstrap', 'category' => 'Frontend', 'proficiency' => 85, 'sort_order' => 8],
            ['name' => 'HTML5/CSS3', 'category' => 'Frontend', 'proficiency' => 95, 'sort_order' => 9],
            ['name' => 'SASS/SCSS', 'category' => 'Frontend', 'proficiency' => 85, 'sort_order' => 10],
            ['name' => 'Webpack', 'category' => 'Frontend', 'proficiency' => 80, 'sort_order' => 11],
            ['name' => 'Vite', 'category' => 'Frontend', 'proficiency' => 85, 'sort_order' => 12],

            // Database Skills
            ['name' => 'MySQL', 'category' => 'Database', 'proficiency' => 95, 'sort_order' => 1],
            ['name' => 'PostgreSQL', 'category' => 'Database', 'proficiency' => 90, 'sort_order' => 2],
            ['name' => 'SQLite', 'category' => 'Database', 'proficiency' => 85, 'sort_order' => 3],
            ['name' => 'Redis', 'category' => 'Database', 'proficiency' => 85, 'sort_order' => 4],
            ['name' => 'MongoDB', 'category' => 'Database', 'proficiency' => 75, 'sort_order' => 5],
            ['name' => 'Elasticsearch', 'category' => 'Database', 'proficiency' => 70, 'sort_order' => 6],
            ['name' => 'Database Design', 'category' => 'Database', 'proficiency' => 90, 'sort_order' => 7],
            ['name' => 'Query Optimization', 'category' => 'Database', 'proficiency' => 85, 'sort_order' => 8],

            // Tools & DevOps
            ['name' => 'Git', 'category' => 'Tools', 'proficiency' => 95, 'sort_order' => 1],
            ['name' => 'GitHub', 'category' => 'Tools', 'proficiency' => 90, 'sort_order' => 2],
            ['name' => 'GitLab', 'category' => 'Tools', 'proficiency' => 85, 'sort_order' => 3],
            ['name' => 'Docker', 'category' => 'Tools', 'proficiency' => 85, 'sort_order' => 4],
            ['name' => 'Docker Compose', 'category' => 'Tools', 'proficiency' => 80, 'sort_order' => 5],
            ['name' => 'AWS', 'category' => 'Tools', 'proficiency' => 80, 'sort_order' => 6],
            ['name' => 'DigitalOcean', 'category' => 'Tools', 'proficiency' => 85, 'sort_order' => 7],
            ['name' => 'Linux', 'category' => 'Tools', 'proficiency' => 90, 'sort_order' => 8],
            ['name' => 'Ubuntu', 'category' => 'Tools', 'proficiency' => 85, 'sort_order' => 9],
            ['name' => 'Nginx', 'category' => 'Tools', 'proficiency' => 85, 'sort_order' => 10],
            ['name' => 'Apache', 'category' => 'Tools', 'proficiency' => 80, 'sort_order' => 11],
            ['name' => 'CI/CD', 'category' => 'Tools', 'proficiency' => 80, 'sort_order' => 12],
            ['name' => 'GitHub Actions', 'category' => 'Tools', 'proficiency' => 85, 'sort_order' => 13],
            ['name' => 'Jenkins', 'category' => 'Tools', 'proficiency' => 70, 'sort_order' => 14],

            // Testing & Quality
            ['name' => 'PHPUnit', 'category' => 'Testing', 'proficiency' => 90, 'sort_order' => 1],
            ['name' => 'Pest', 'category' => 'Testing', 'proficiency' => 85, 'sort_order' => 2],
            ['name' => 'Laravel Dusk', 'category' => 'Testing', 'proficiency' => 80, 'sort_order' => 3],
            ['name' => 'Jest', 'category' => 'Testing', 'proficiency' => 75, 'sort_order' => 4],
            ['name' => 'Cypress', 'category' => 'Testing', 'proficiency' => 70, 'sort_order' => 5],
            ['name' => 'TDD', 'category' => 'Testing', 'proficiency' => 85, 'sort_order' => 6],
            ['name' => 'Code Review', 'category' => 'Testing', 'proficiency' => 90, 'sort_order' => 7],
            ['name' => 'PHP CS Fixer', 'category' => 'Testing', 'proficiency' => 85, 'sort_order' => 8],

            // Architecture & Patterns
            ['name' => 'MVC Pattern', 'category' => 'Architecture', 'proficiency' => 95, 'sort_order' => 1],
            ['name' => 'Repository Pattern', 'category' => 'Architecture', 'proficiency' => 90, 'sort_order' => 2],
            ['name' => 'Service Layer', 'category' => 'Architecture', 'proficiency' => 90, 'sort_order' => 3],
            ['name' => 'SOLID Principles', 'category' => 'Architecture', 'proficiency' => 85, 'sort_order' => 4],
            ['name' => 'Design Patterns', 'category' => 'Architecture', 'proficiency' => 80, 'sort_order' => 5],
            ['name' => 'Clean Code', 'category' => 'Architecture', 'proficiency' => 90, 'sort_order' => 6],
            ['name' => 'API Design', 'category' => 'Architecture', 'proficiency' => 85, 'sort_order' => 7],
            ['name' => 'Database Design', 'category' => 'Architecture', 'proficiency' => 90, 'sort_order' => 8],

            // Project Management
            ['name' => 'Agile', 'category' => 'Management', 'proficiency' => 85, 'sort_order' => 1],
            ['name' => 'Scrum', 'category' => 'Management', 'proficiency' => 80, 'sort_order' => 2],
            ['name' => 'Jira', 'category' => 'Management', 'proficiency' => 85, 'sort_order' => 3],
            ['name' => 'Trello', 'category' => 'Management', 'proficiency' => 90, 'sort_order' => 4],
            ['name' => 'Team Leadership', 'category' => 'Management', 'proficiency' => 80, 'sort_order' => 5],
            ['name' => 'Mentoring', 'category' => 'Management', 'proficiency' => 85, 'sort_order' => 6],
            ['name' => 'Code Review', 'category' => 'Management', 'proficiency' => 90, 'sort_order' => 7],
        ];

        foreach ($skills as $skill) {
            Skill::create($skill);
        }
    }
}

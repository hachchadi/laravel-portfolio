<?php

namespace Database\Factories;

use App\Models\Skill;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Skill>
 */
class SkillFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = ['Backend', 'Frontend', 'Database', 'Tools', 'DevOps', 'Mobile', 'Other'];
        
        $skillsByCategory = [
            'Backend' => ['Laravel', 'PHP', 'Node.js', 'Python', 'Java', 'C#', 'Ruby'],
            'Frontend' => ['React', 'Vue.js', 'Angular', 'JavaScript', 'TypeScript', 'HTML', 'CSS'],
            'Database' => ['MySQL', 'PostgreSQL', 'MongoDB', 'Redis', 'SQLite', 'Oracle'],
            'Tools' => ['Git', 'Docker', 'VS Code', 'PhpStorm', 'Postman', 'Figma'],
            'DevOps' => ['AWS', 'Linux', 'Nginx', 'Apache', 'Jenkins', 'GitHub Actions'],
            'Mobile' => ['React Native', 'Flutter', 'Swift', 'Kotlin', 'Ionic'],
            'Other' => ['REST APIs', 'GraphQL', 'Microservices', 'Testing', 'Agile']
        ];

        $category = $this->faker->randomElement($categories);
        $skills = $skillsByCategory[$category];
        
        return [
            'name' => $this->faker->randomElement($skills),
            'category' => $category,
            'proficiency' => $this->faker->numberBetween(60, 100),
            'sort_order' => $this->faker->numberBetween(0, 100),
        ];
    }

    /**
     * Create a skill with specific category.
     */
    public function category(string $category): static
    {
        return $this->state(fn (array $attributes) => [
            'category' => $category,
        ]);
    }

    /**
     * Create a skill with specific proficiency.
     */
    public function proficiency(int $proficiency): static
    {
        return $this->state(fn (array $attributes) => [
            'proficiency' => $proficiency,
        ]);
    }

    /**
     * Create a beginner level skill (60-70% proficiency).
     */
    public function beginner(): static
    {
        return $this->state(fn (array $attributes) => [
            'proficiency' => $this->faker->numberBetween(60, 70),
        ]);
    }

    /**
     * Create an intermediate level skill (71-85% proficiency).
     */
    public function intermediate(): static
    {
        return $this->state(fn (array $attributes) => [
            'proficiency' => $this->faker->numberBetween(71, 85),
        ]);
    }

    /**
     * Create an expert level skill (86-100% proficiency).
     */
    public function expert(): static
    {
        return $this->state(fn (array $attributes) => [
            'proficiency' => $this->faker->numberBetween(86, 100),
        ]);
    }
}
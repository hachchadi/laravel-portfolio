<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\Helpers\ImagePlaceholderGenerator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create the main developer profile
        $mainUser = User::updateOrCreate(
            ['email' => 'john.developer@example.com'],
            [
                'name' => 'John Developer',
                'email' => 'john.developer@example.com',
                'password' => Hash::make('password'),
                'title' => 'Senior Laravel Developer',
                'bio' => 'Passionate Senior Laravel Developer with 8+ years of experience building scalable web applications. Specialized in Laravel, Vue.js, and modern web technologies. I love creating elegant solutions to complex problems and mentoring junior developers. When I\'m not coding, you can find me contributing to open-source projects or exploring new technologies.',
                'avatar' => 'avatars/john-developer.svg',
                'linkedin_url' => 'https://linkedin.com/in/johndeveloper',
                'github_url' => 'https://github.com/johndeveloper',
                'phone' => '+1 (555) 123-4567',
                'location' => 'San Francisco, CA',
                'is_admin' => true,
            ]
        );

        // Create avatar placeholder for main user
        ImagePlaceholderGenerator::createAvatarPlaceholder('avatars/john-developer.svg', $mainUser->name);

        // Create admin user for testing
        $adminUser = User::updateOrCreate(
            ['email' => 'admin@portfolio.test'],
            [
                'name' => 'Admin User',
                'email' => 'admin@portfolio.test',
                'password' => Hash::make('admin123'),
                'title' => 'Portfolio Administrator',
                'bio' => 'Administrator account for portfolio management and testing purposes.',
                'avatar' => 'avatars/admin-user.svg',
                'location' => 'Remote',
                'is_admin' => true,
            ]
        );

        // Create avatar placeholder for admin user
        ImagePlaceholderGenerator::createAvatarPlaceholder('avatars/admin-user.svg', $adminUser->name);

        // Create a test user for contact form testing
        $testUser = User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => Hash::make('password'),
                'title' => 'Test Account',
                'bio' => 'Test user account for development and testing purposes.',
                'avatar' => 'avatars/test-user.svg',
                'location' => 'Test Location',
                'is_admin' => false,
            ]
        );

        // Create avatar placeholder for test user
        ImagePlaceholderGenerator::createAvatarPlaceholder('avatars/test-user.svg', $testUser->name);
    }
}

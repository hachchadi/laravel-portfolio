<?php

namespace Tests\Feature\Seeders;

use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserSeederTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_user_seeder_creates_main_developer_profile(): void
    {
        $this->seed(UserSeeder::class);

        $user = User::where('email', 'john.developer@example.com')->first();

        $this->assertNotNull($user);
        $this->assertEquals('John Developer', $user->name);
        $this->assertEquals('Senior Laravel Developer', $user->title);
        $this->assertTrue($user->is_admin);
        $this->assertNotEmpty($user->bio);
        $this->assertEquals('avatars/john-developer.svg', $user->avatar);
        $this->assertEquals('https://linkedin.com/in/johndeveloper', $user->linkedin_url);
        $this->assertEquals('https://github.com/johndeveloper', $user->github_url);
        $this->assertEquals('+1 (555) 123-4567', $user->phone);
        $this->assertEquals('San Francisco, CA', $user->location);
    }

    public function test_user_seeder_creates_admin_user(): void
    {
        $this->seed(UserSeeder::class);

        $user = User::where('email', 'admin@portfolio.test')->first();

        $this->assertNotNull($user);
        $this->assertEquals('Admin User', $user->name);
        $this->assertEquals('Portfolio Administrator', $user->title);
        $this->assertTrue($user->is_admin);
        $this->assertEquals('avatars/admin-user.svg', $user->avatar);
    }

    public function test_user_seeder_creates_test_user(): void
    {
        $this->seed(UserSeeder::class);

        $user = User::where('email', 'test@example.com')->first();

        $this->assertNotNull($user);
        $this->assertEquals('Test User', $user->name);
        $this->assertEquals('Test Account', $user->title);
        $this->assertFalse($user->is_admin);
        $this->assertEquals('avatars/test-user.svg', $user->avatar);
    }

    public function test_user_seeder_creates_avatar_files(): void
    {
        $this->seed(UserSeeder::class);

        Storage::disk('public')->assertExists('avatars/john-developer.svg');
        Storage::disk('public')->assertExists('avatars/admin-user.svg');
        Storage::disk('public')->assertExists('avatars/test-user.svg');
    }

    public function test_user_seeder_passwords_are_hashed(): void
    {
        $this->seed(UserSeeder::class);

        $mainUser = User::where('email', 'john.developer@example.com')->first();
        $adminUser = User::where('email', 'admin@portfolio.test')->first();
        $testUser = User::where('email', 'test@example.com')->first();

        $this->assertTrue(Hash::check('password', $mainUser->password));
        $this->assertTrue(Hash::check('admin123', $adminUser->password));
        $this->assertTrue(Hash::check('password', $testUser->password));
    }

    public function test_user_seeder_can_run_multiple_times(): void
    {
        $this->seed(UserSeeder::class);
        $this->seed(UserSeeder::class);

        $this->assertEquals(3, User::count());
    }

    public function test_user_seeder_creates_valid_avatar_svg(): void
    {
        $this->seed(UserSeeder::class);

        $avatarContent = Storage::disk('public')->get('avatars/john-developer.svg');
        
        $this->assertStringContainsString('<svg', $avatarContent);
        $this->assertStringContainsString('JD', $avatarContent); // Initials
        $this->assertStringContainsString('</svg>', $avatarContent);
    }
}
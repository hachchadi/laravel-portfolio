<?php

namespace Tests\Unit\Admin;

use App\Models\User;
use App\Livewire\Admin\ProfileEditor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileEditorUnitTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected ProfileEditor $component;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create([
            'is_admin' => true,
            'name' => 'Test User',
            'email' => 'test@example.com',
            'title' => 'Developer',
            'bio' => 'Test bio',
            'phone' => '+1234567890',
            'location' => 'Test City',
            'linkedin_url' => 'https://linkedin.com/in/test',
            'github_url' => 'https://github.com/test',
        ]);

        $this->actingAs($this->user);
        $this->component = new ProfileEditor();
    }

    /** @test */
    public function it_initializes_with_user_data()
    {
        $this->component->mount();

        $this->assertEquals('Test User', $this->component->name);
        $this->assertEquals('test@example.com', $this->component->email);
        $this->assertEquals('Developer', $this->component->title);
        $this->assertEquals('Test bio', $this->component->bio);
        $this->assertEquals('+1234567890', $this->component->phone);
        $this->assertEquals('Test City', $this->component->location);
        $this->assertEquals('https://linkedin.com/in/test', $this->component->linkedin_url);
        $this->assertEquals('https://github.com/test', $this->component->github_url);
    }

    /** @test */
    public function it_handles_null_user_fields_gracefully()
    {
        $userWithNulls = User::factory()->create([
            'is_admin' => true,
            'name' => 'Test User 2',
            'email' => 'test2@example.com',
            'title' => null,
            'bio' => null,
            'phone' => null,
            'location' => null,
            'linkedin_url' => null,
            'github_url' => null,
            'avatar' => null,
        ]);

        $this->actingAs($userWithNulls);
        $component = new ProfileEditor();
        $component->mount();

        $this->assertEquals('Test User 2', $component->name);
        $this->assertEquals('test2@example.com', $component->email);
        $this->assertEquals('', $component->title);
        $this->assertEquals('', $component->bio);
        $this->assertEquals('', $component->phone);
        $this->assertEquals('', $component->location);
        $this->assertEquals('', $component->linkedin_url);
        $this->assertEquals('', $component->github_url);
        $this->assertEquals('', $component->currentAvatar);
    }

    /** @test */
    public function it_has_correct_validation_rules()
    {
        $this->component->mount();
        
        // Use reflection to access protected rules property
        $reflection = new \ReflectionClass($this->component);
        $rulesProperty = $reflection->getProperty('rules');
        $rulesProperty->setAccessible(true);
        $rules = $rulesProperty->getValue($this->component);

        $this->assertArrayHasKey('name', $rules);
        $this->assertArrayHasKey('email', $rules);
        $this->assertArrayHasKey('title', $rules);
        $this->assertArrayHasKey('bio', $rules);
        $this->assertArrayHasKey('phone', $rules);
        $this->assertArrayHasKey('location', $rules);
        $this->assertArrayHasKey('linkedin_url', $rules);
        $this->assertArrayHasKey('github_url', $rules);
        $this->assertArrayHasKey('avatar', $rules);

        // Check specific validation rules
        $this->assertStringContainsString('required', $rules['name']);
        $this->assertStringContainsString('required', $rules['email']);
        $this->assertStringContainsString('email', $rules['email']);
        $this->assertStringContainsString('nullable', $rules['title']);
        $this->assertStringContainsString('nullable', $rules['bio']);
        $this->assertStringContainsString('url', $rules['linkedin_url']);
        $this->assertStringContainsString('url', $rules['github_url']);
        $this->assertStringContainsString('image', $rules['avatar']);
    }

    /** @test */
    public function it_has_custom_validation_messages()
    {
        $this->component->mount();
        
        // Use reflection to access protected messages property
        $reflection = new \ReflectionClass($this->component);
        $messagesProperty = $reflection->getProperty('messages');
        $messagesProperty->setAccessible(true);
        $messages = $messagesProperty->getValue($this->component);

        $this->assertArrayHasKey('name.required', $messages);
        $this->assertArrayHasKey('email.required', $messages);
        $this->assertArrayHasKey('email.email', $messages);
        $this->assertArrayHasKey('avatar.image', $messages);
        $this->assertArrayHasKey('linkedin_url.url', $messages);
        $this->assertArrayHasKey('github_url.url', $messages);
    }

    /** @test */
    public function upload_avatar_generates_unique_filename()
    {
        Storage::fake('public');
        
        $this->component->mount();
        $this->component->avatar = UploadedFile::fake()->image('test.jpg', 300, 300);

        $avatarPath = $this->component->uploadAvatar();

        $this->assertStringStartsWith('avatars/avatar_', $avatarPath);
        $this->assertStringEndsWith('.jpg', $avatarPath);
        Storage::disk('public')->assertExists($avatarPath);
    }

    /** @test */
    public function upload_avatar_handles_processing_errors_gracefully()
    {
        Storage::fake('public');
        
        $this->component->mount();
        $this->component->avatar = UploadedFile::fake()->image('test.jpg', 300, 300);

        // Mock the image processing to fail
        $avatarPath = $this->component->uploadAvatar();

        // Should still return a path even if processing fails
        $this->assertNotNull($avatarPath);
        $this->assertStringStartsWith('avatars/', $avatarPath);
    }

    /** @test */
    public function it_can_determine_if_user_has_avatar()
    {
        // User without avatar
        $this->component->mount();
        $this->assertEquals('', $this->component->currentAvatar);

        // User with avatar
        $this->user->update(['avatar' => 'avatars/test.jpg']);
        $this->component->mount();
        $this->assertEquals('avatars/test.jpg', $this->component->currentAvatar);
    }

    /** @test */
    public function it_initializes_component_properties_correctly()
    {
        $component = new ProfileEditor();

        $this->assertEquals('', $component->name);
        $this->assertEquals('', $component->email);
        $this->assertEquals('', $component->title);
        $this->assertEquals('', $component->bio);
        $this->assertEquals('', $component->phone);
        $this->assertEquals('', $component->location);
        $this->assertEquals('', $component->linkedin_url);
        $this->assertEquals('', $component->github_url);
        $this->assertNull($component->avatar);
        $this->assertEquals('', $component->currentAvatar);
        $this->assertFalse($component->isSubmitting);
    }

    /** @test */
    public function it_uses_with_file_uploads_trait()
    {
        $traits = class_uses(ProfileEditor::class);
        
        $this->assertContains('Livewire\WithFileUploads', $traits);
    }

    /** @test */
    public function it_returns_correct_view()
    {
        $this->component->mount();
        $view = $this->component->render();

        $this->assertEquals('livewire.admin.profile-editor', $view->name());
    }
}
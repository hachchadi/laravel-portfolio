<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;
use App\Livewire\Admin\ProfileEditor;

class ProfileEditorTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->adminUser = User::factory()->create([
            'is_admin' => true,
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'title' => 'Senior Developer',
            'bio' => 'Experienced developer',
            'phone' => '+1234567890',
            'location' => 'San Francisco, CA',
            'linkedin_url' => 'https://linkedin.com/in/admin',
            'github_url' => 'https://github.com/admin',
        ]);
    }

    /** @test */
    public function it_can_render_profile_editor_component()
    {
        $this->actingAs($this->adminUser);

        Livewire::test(ProfileEditor::class)
            ->assertStatus(200)
            ->assertSee('Edit Profile')
            ->assertSee('Update your personal information');
    }

    /** @test */
    public function it_loads_user_data_on_mount()
    {
        $this->actingAs($this->adminUser);

        Livewire::test(ProfileEditor::class)
            ->assertSet('name', 'Admin User')
            ->assertSet('email', 'admin@example.com')
            ->assertSet('title', 'Senior Developer')
            ->assertSet('bio', 'Experienced developer')
            ->assertSet('phone', '+1234567890')
            ->assertSet('location', 'San Francisco, CA')
            ->assertSet('linkedin_url', 'https://linkedin.com/in/admin')
            ->assertSet('github_url', 'https://github.com/admin');
    }

    /** @test */
    public function it_validates_required_fields()
    {
        $this->actingAs($this->adminUser);

        Livewire::test(ProfileEditor::class)
            ->set('name', '')
            ->set('email', '')
            ->call('save')
            ->assertHasErrors(['name' => 'required', 'email' => 'required']);
    }

    /** @test */
    public function it_validates_email_format()
    {
        $this->actingAs($this->adminUser);

        Livewire::test(ProfileEditor::class)
            ->set('email', 'invalid-email')
            ->call('save')
            ->assertHasErrors(['email' => 'email']);
    }

    /** @test */
    public function it_validates_url_fields()
    {
        $this->actingAs($this->adminUser);

        Livewire::test(ProfileEditor::class)
            ->set('linkedin_url', 'invalid-url')
            ->set('github_url', 'not-a-url')
            ->call('save')
            ->assertHasErrors(['linkedin_url' => 'url', 'github_url' => 'url']);
    }

    /** @test */
    public function it_validates_bio_length()
    {
        $this->actingAs($this->adminUser);

        $longBio = str_repeat('a', 1001);

        Livewire::test(ProfileEditor::class)
            ->set('bio', $longBio)
            ->call('save')
            ->assertHasErrors(['bio' => 'max']);
    }

    /** @test */
    public function it_can_update_profile_successfully()
    {
        $this->actingAs($this->adminUser);

        Livewire::test(ProfileEditor::class)
            ->set('name', 'Updated Name')
            ->set('email', 'updated@example.com')
            ->set('title', 'Lead Developer')
            ->set('bio', 'Updated bio')
            ->set('phone', '+9876543210')
            ->set('location', 'New York, NY')
            ->set('linkedin_url', 'https://linkedin.com/in/updated')
            ->set('github_url', 'https://github.com/updated')
            ->call('save')
            ->assertHasNoErrors()
            ->assertDispatched('profile-updated');

        $this->adminUser->refresh();

        $this->assertEquals('Updated Name', $this->adminUser->name);
        $this->assertEquals('updated@example.com', $this->adminUser->email);
        $this->assertEquals('Lead Developer', $this->adminUser->title);
        $this->assertEquals('Updated bio', $this->adminUser->bio);
        $this->assertEquals('+9876543210', $this->adminUser->phone);
        $this->assertEquals('New York, NY', $this->adminUser->location);
        $this->assertEquals('https://linkedin.com/in/updated', $this->adminUser->linkedin_url);
        $this->assertEquals('https://github.com/updated', $this->adminUser->github_url);
    }

    /** @test */
    public function it_can_upload_avatar()
    {
        Storage::fake('public');
        $this->actingAs($this->adminUser);

        $file = UploadedFile::fake()->image('avatar.jpg', 400, 400);

        Livewire::test(ProfileEditor::class)
            ->set('avatar', $file)
            ->call('save')
            ->assertHasNoErrors();

        $this->adminUser->refresh();
        $this->assertNotNull($this->adminUser->avatar);
        Storage::disk('public')->assertExists($this->adminUser->avatar);
    }

    /** @test */
    public function it_validates_avatar_file_type()
    {
        Storage::fake('public');
        $this->actingAs($this->adminUser);

        $file = UploadedFile::fake()->create('document.pdf', 1000);

        Livewire::test(ProfileEditor::class)
            ->set('avatar', $file)
            ->assertHasErrors(['avatar']);
    }

    /** @test */
    public function it_validates_avatar_file_size()
    {
        Storage::fake('public');
        $this->actingAs($this->adminUser);

        // Create a file larger than 2MB
        $file = UploadedFile::fake()->image('large-avatar.jpg')->size(3000);

        Livewire::test(ProfileEditor::class)
            ->set('avatar', $file)
            ->assertHasErrors(['avatar']);
    }

    /** @test */
    public function it_can_remove_avatar()
    {
        Storage::fake('public');
        
        // Create an avatar file
        $avatarPath = 'avatars/test-avatar.jpg';
        Storage::disk('public')->put($avatarPath, 'fake-image-content');
        
        $this->adminUser->update(['avatar' => $avatarPath]);
        $this->actingAs($this->adminUser);

        Livewire::test(ProfileEditor::class)
            ->call('removeAvatar')
            ->assertDispatched('avatar-removed');

        $this->adminUser->refresh();
        $this->assertNull($this->adminUser->avatar);
        Storage::disk('public')->assertMissing($avatarPath);
    }

    /** @test */
    public function it_deletes_old_avatar_when_uploading_new_one()
    {
        Storage::fake('public');
        
        // Create an old avatar file
        $oldAvatarPath = 'avatars/old-avatar.jpg';
        Storage::disk('public')->put($oldAvatarPath, 'old-image-content');
        
        $this->adminUser->update(['avatar' => $oldAvatarPath]);
        $this->actingAs($this->adminUser);

        $newFile = UploadedFile::fake()->image('new-avatar.jpg', 300, 300);

        Livewire::test(ProfileEditor::class)
            ->set('avatar', $newFile)
            ->call('save')
            ->assertHasNoErrors();

        // Old avatar should be deleted
        Storage::disk('public')->assertMissing($oldAvatarPath);
        
        // New avatar should exist
        $this->adminUser->refresh();
        $this->assertNotNull($this->adminUser->avatar);
        Storage::disk('public')->assertExists($this->adminUser->avatar);
    }

    /** @test */
    public function it_shows_loading_state_during_submission()
    {
        $this->actingAs($this->adminUser);

        Livewire::test(ProfileEditor::class)
            ->assertSet('isSubmitting', false)
            ->call('save')
            ->assertSet('isSubmitting', false); // Should be false after completion
    }

    /** @test */
    public function it_allows_empty_optional_fields()
    {
        $this->actingAs($this->adminUser);

        Livewire::test(ProfileEditor::class)
            ->set('title', '')
            ->set('bio', '')
            ->set('phone', '')
            ->set('location', '')
            ->set('linkedin_url', '')
            ->set('github_url', '')
            ->call('save')
            ->assertHasNoErrors();

        $this->adminUser->refresh();
        $this->assertEquals('', $this->adminUser->title);
        $this->assertEquals('', $this->adminUser->bio);
        $this->assertEquals('', $this->adminUser->phone);
        $this->assertEquals('', $this->adminUser->location);
        $this->assertEquals('', $this->adminUser->linkedin_url);
        $this->assertEquals('', $this->adminUser->github_url);
    }
}
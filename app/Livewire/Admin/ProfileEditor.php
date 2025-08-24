<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProfileEditor extends Component
{
    use WithFileUploads;

    public User $user;
    public $name = '';
    public $email = '';
    public $title = '';
    public $bio = '';
    public $phone = '';
    public $location = '';
    public $linkedin_url = '';
    public $github_url = '';
    public $avatar;
    public $currentAvatar = '';
    public $isSubmitting = false;

    protected $rules = [
        'name' => 'required|string|min:2|max:255',
        'email' => 'required|email|max:255',
        'title' => 'nullable|string|max:255',
        'bio' => 'nullable|string|max:1000',
        'phone' => 'nullable|string|max:20',
        'location' => 'nullable|string|max:255',
        'linkedin_url' => 'nullable|url|max:255',
        'github_url' => 'nullable|url|max:255',
        'avatar' => 'nullable|image|max:2048|mimes:jpeg,png,jpg,gif',
    ];

    protected $messages = [
        'name.required' => 'Name is required.',
        'name.min' => 'Name must be at least 2 characters.',
        'email.required' => 'Email is required.',
        'email.email' => 'Please enter a valid email address.',
        'avatar.image' => 'Avatar must be an image file.',
        'avatar.max' => 'Avatar file size must not exceed 2MB.',
        'avatar.mimes' => 'Avatar must be a JPEG, PNG, JPG, or GIF file.',
        'linkedin_url.url' => 'LinkedIn URL must be a valid URL.',
        'github_url.url' => 'GitHub URL must be a valid URL.',
        'bio.max' => 'Bio must not exceed 1000 characters.',
    ];

    public function mount()
    {
        $this->user = User::find(auth()->id());
        $this->name = $this->user->name ?? '';
        $this->email = $this->user->email ?? '';
        $this->title = $this->user->title ?? '';
        $this->bio = $this->user->bio ?? '';
        $this->phone = $this->user->phone ?? '';
        $this->location = $this->user->location ?? '';
        $this->linkedin_url = $this->user->linkedin_url ?? '';
        $this->github_url = $this->user->github_url ?? '';
        $this->currentAvatar = $this->user->avatar ?? '';
    }

    public function updatedAvatar()
    {
        $this->validateOnly('avatar');
    }

    public function save()
    {
        $this->isSubmitting = true;

        try {
            $this->validate();

            $data = [
                'name' => $this->name,
                'email' => $this->email,
                'title' => $this->title,
                'bio' => $this->bio,
                'phone' => $this->phone,
                'location' => $this->location,
                'linkedin_url' => $this->linkedin_url,
                'github_url' => $this->github_url,
            ];

            // Handle avatar upload
            if ($this->avatar) {
                $avatarPath = $this->uploadAvatar();
                if ($avatarPath) {
                    $data['avatar'] = $avatarPath;
                    
                    // Delete old avatar if exists
                    if ($this->currentAvatar && Storage::disk('public')->exists($this->currentAvatar)) {
                        Storage::disk('public')->delete($this->currentAvatar);
                    }
                }
            }

            $this->user->update($data);

            // Update current avatar reference
            if (isset($data['avatar'])) {
                $this->currentAvatar = $data['avatar'];
                $this->avatar = null;
            }

            $this->dispatch('profile-updated');
            session()->flash('success', 'Profile updated successfully!');
            
        } catch (\Exception $exception) {
            session()->flash('error', 'An error occurred while updating your profile. Please try again.');
            \Log::error('Profile update error: ' . $exception->getMessage());
        } finally {
            $this->isSubmitting = false;
        }
    }

    public function uploadAvatar()
    {
        try {
            // Create unique filename
            $filename = 'avatar_' . $this->user->id . '_' . time() . '.' . $this->avatar->getClientOriginalExtension();
            
            // Store the original file temporarily
            $tempPath = $this->avatar->store('temp', 'public');
            
            // Process and resize the image
            $manager = new ImageManager(new Driver());
            $image = $manager->read(Storage::disk('public')->path($tempPath));
            
            // Resize to 300x300 while maintaining aspect ratio
            $image->cover(300, 300);
            
            // Save the processed image
            $avatarPath = 'avatars/' . $filename;
            $image->save(Storage::disk('public')->path($avatarPath), 90);
            
            // Delete temporary file
            Storage::disk('public')->delete($tempPath);
            
            return $avatarPath;
            
        } catch (\Exception $exception) {
            // If image processing fails, store original file
            \Log::error('Avatar upload error: ' . $exception->getMessage());
            return $this->avatar->store('avatars', 'public');
        }
    }

    public function removeAvatar()
    {
        if ($this->currentAvatar && Storage::disk('public')->exists($this->currentAvatar)) {
            Storage::disk('public')->delete($this->currentAvatar);
        }

        $this->user->update(['avatar' => null]);
        $this->currentAvatar = '';
        $this->avatar = null;

        $this->dispatch('avatar-removed');
        session()->flash('success', 'Avatar removed successfully!');
    }

    public function render()
    {
        return view('livewire.admin.profile-editor');
    }
}
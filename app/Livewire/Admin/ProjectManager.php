<?php

namespace App\Livewire\Admin;

use App\Models\Project;
use App\Models\ProjectImage;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class ProjectManager extends Component
{
    use WithFileUploads, WithPagination;

    // Component state
    public $projects;
    public $editing = null;
    public $showForm = false;
    public $showDeleteModal = false;
    public $projectToDelete = null;

    // Form fields
    public $title = '';
    public $description = '';
    public $technologies = [];
    public $github_url = '';
    public $demo_url = '';
    public $featured = false;
    public $status = 'published';
    public $sort_order = 0;

    // Image handling
    public $newImages = [];
    public $existingImages = [];
    public $imagesToDelete = [];

    // Validation rules
    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'technologies' => 'array',
        'github_url' => 'nullable|url',
        'demo_url' => 'nullable|url',
        'featured' => 'boolean',
        'status' => 'required|in:draft,published',
        'sort_order' => 'integer|min:0',
        'newImages.*' => 'nullable|image|max:2048', // 2MB max per image
    ];

    protected $messages = [
        'title.required' => 'Project title is required.',
        'description.required' => 'Project description is required.',
        'github_url.url' => 'GitHub URL must be a valid URL.',
        'demo_url.url' => 'Demo URL must be a valid URL.',
        'newImages.*.image' => 'Each file must be an image.',
        'newImages.*.max' => 'Each image must be less than 2MB.',
    ];

    public function mount()
    {
        $this->loadProjects();
    }

    public function render()
    {
        return view('livewire.admin.project-manager');
    }

    public function loadProjects()
    {
        $this->projects = Project::with('images')
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function create()
    {
        $this->resetForm();
        $this->editing = null;
        $this->showForm = true;
    }

    public function edit($projectId)
    {
        $project = Project::with('images')->findOrFail($projectId);
        
        $this->editing = $projectId;
        $this->title = $project->title;
        $this->description = $project->description;
        $this->technologies = $project->technologies ?? [];
        $this->github_url = $project->github_url ?? '';
        $this->demo_url = $project->demo_url ?? '';
        $this->featured = $project->featured;
        $this->status = $project->status;
        $this->sort_order = $project->sort_order;
        
        // Load existing images
        $this->existingImages = $project->images->map(function ($image) {
            return [
                'id' => $image->id,
                'image_path' => $image->image_path,
                'alt_text' => $image->alt_text,
                'sort_order' => $image->sort_order,
                'url' => $image->image_url,
            ];
        })->toArray();
        
        $this->showForm = true;
    }

    public function save()
    {
        $this->validate();

        try {
            if ($this->editing) {
                $project = Project::findOrFail($this->editing);
                $project->update([
                    'title' => $this->title,
                    'description' => $this->description,
                    'technologies' => $this->technologies,
                    'github_url' => $this->github_url ?: null,
                    'demo_url' => $this->demo_url ?: null,
                    'featured' => $this->featured,
                    'status' => $this->status,
                    'sort_order' => $this->sort_order,
                ]);
                $message = 'Project updated successfully!';
            } else {
                $project = Project::create([
                    'title' => $this->title,
                    'description' => $this->description,
                    'technologies' => $this->technologies,
                    'github_url' => $this->github_url ?: null,
                    'demo_url' => $this->demo_url ?: null,
                    'featured' => $this->featured,
                    'status' => $this->status,
                    'sort_order' => $this->sort_order,
                ]);
                $message = 'Project created successfully!';
            }

            // Handle image uploads
            $this->handleImageUploads($project);

            // Handle image deletions
            $this->handleImageDeletions();

            // Update existing image metadata
            $this->updateExistingImages($project);

            $this->loadProjects();
            $this->resetForm();
            $this->showForm = false;

            session()->flash('message', $message);
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while saving the project: ' . $e->getMessage());
        }
    }

    public function confirmDelete($projectId)
    {
        $this->projectToDelete = $projectId;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        if ($this->projectToDelete) {
            try {
                $project = Project::with('images')->findOrFail($this->projectToDelete);
                
                // Delete associated images from storage
                foreach ($project->images as $image) {
                    Storage::disk('public')->delete($image->image_path);
                }
                
                // Delete the project (images will be deleted via cascade)
                $project->delete();
                
                $this->loadProjects();
                $this->showDeleteModal = false;
                $this->projectToDelete = null;
                
                session()->flash('message', 'Project deleted successfully!');
            } catch (\Exception $e) {
                session()->flash('error', 'An error occurred while deleting the project: ' . $e->getMessage());
            }
        }
    }

    public function cancelDelete()
    {
        $this->showDeleteModal = false;
        $this->projectToDelete = null;
    }

    public function cancel()
    {
        $this->resetForm();
        $this->showForm = false;
    }

    public function addTechnology()
    {
        $this->technologies[] = '';
    }

    public function removeTechnology($index)
    {
        unset($this->technologies[$index]);
        $this->technologies = array_values($this->technologies);
    }

    public function removeExistingImage($imageId)
    {
        $this->imagesToDelete[] = $imageId;
        $this->existingImages = array_filter($this->existingImages, function ($image) use ($imageId) {
            return $image['id'] != $imageId;
        });
    }

    public function updateImageOrder($orderedImages)
    {
        foreach ($orderedImages as $index => $imageData) {
            if (isset($imageData['id'])) {
                // Update existing image order
                foreach ($this->existingImages as &$existingImage) {
                    if ($existingImage['id'] == $imageData['id']) {
                        $existingImage['sort_order'] = $index;
                        break;
                    }
                }
            }
        }
    }

    private function handleImageUploads($project)
    {
        if (!empty($this->newImages)) {
            foreach ($this->newImages as $index => $image) {
                if ($image) {
                    $path = $image->store('projects', 'public');
                    
                    ProjectImage::create([
                        'project_id' => $project->id,
                        'image_path' => $path,
                        'alt_text' => $this->title . ' - Image ' . ($index + 1),
                        'sort_order' => count($this->existingImages) + $index,
                    ]);
                }
            }
        }
    }

    private function handleImageDeletions()
    {
        if (!empty($this->imagesToDelete)) {
            foreach ($this->imagesToDelete as $imageId) {
                $image = ProjectImage::find($imageId);
                if ($image) {
                    Storage::disk('public')->delete($image->image_path);
                    $image->delete();
                }
            }
        }
    }

    private function updateExistingImages($project)
    {
        foreach ($this->existingImages as $imageData) {
            if (isset($imageData['id'])) {
                ProjectImage::where('id', $imageData['id'])
                    ->update([
                        'alt_text' => $imageData['alt_text'] ?? $this->title,
                        'sort_order' => $imageData['sort_order'] ?? 0,
                    ]);
            }
        }
    }

    private function resetForm()
    {
        $this->title = '';
        $this->description = '';
        $this->technologies = [];
        $this->github_url = '';
        $this->demo_url = '';
        $this->featured = false;
        $this->status = 'published';
        $this->sort_order = 0;
        $this->newImages = [];
        $this->existingImages = [];
        $this->imagesToDelete = [];
        $this->editing = null;
    }
}
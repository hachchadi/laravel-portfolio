<?php

namespace App\Livewire\Admin;

use App\Models\Skill;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithFileUploads;

class SkillsManager extends Component
{
    use WithFileUploads;

    // Component state
    public $editing = null;
    public $showForm = false;
    public $showDeleteModal = false;
    public $skillToDelete = null;
    public $showImportModal = false;

    // Form fields
    public $name = '';
    public $category = '';
    public $proficiency = 80;
    public $sort_order = 0;

    // Import functionality
    public $importFile;
    public $importType = 'json'; // json or csv
    public $importData = [];
    public $importPreview = [];

    // Available categories
    public $availableCategories = [
        'Backend',
        'Frontend', 
        'Database',
        'Tools',
        'DevOps',
        'Mobile',
        'Other'
    ];

    // Validation rules
    protected $rules = [
        'name' => 'required|string|max:255',
        'category' => 'required|string|max:100',
        'proficiency' => 'integer|min:1|max:100',
        'sort_order' => 'integer|min:0',
        'importFile' => 'nullable|file|mimes:json,csv,txt|max:1024', // 1MB max
    ];

    protected $messages = [
        'name.required' => 'Skill name is required.',
        'category.required' => 'Category is required.',
        'proficiency.required' => 'Proficiency level is required.',
        'proficiency.min' => 'Proficiency must be at least 1.',
        'proficiency.max' => 'Proficiency cannot exceed 100.',
        'importFile.mimes' => 'Import file must be JSON or CSV format.',
        'importFile.max' => 'Import file must be less than 1MB.',
    ];

    public function mount()
    {
        // No need to load skills here, they'll be loaded in render
    }

    public function render()
    {
        return view('livewire.admin.skills-manager', [
            'skills' => Skill::ordered()->get(),
            'skillsByCategory' => Skill::getByCategory()
        ]);
    }

    public function create()
    {
        $this->resetForm();
        $this->editing = null;
        $this->showForm = true;
    }

    public function edit($skillId)
    {
        try {
            $skill = Skill::findOrFail($skillId);
            
            $this->editing = $skillId;
            $this->name = $skill->name;
            $this->category = $skill->category;
            $this->proficiency = $skill->proficiency;
            $this->sort_order = $skill->sort_order;
            
            $this->showForm = true;
        } catch (\Exception $e) {
            session()->flash('error', 'Skill not found.');
        }
    }

    public function save()
    {
        $this->validate();

        try {
            if ($this->editing) {
                $skill = Skill::findOrFail($this->editing);
                $skill->update([
                    'name' => $this->name,
                    'category' => $this->category,
                    'proficiency' => $this->proficiency,
                    'sort_order' => $this->sort_order,
                ]);
                $message = 'Skill updated successfully!';
            } else {
                Skill::create([
                    'name' => $this->name,
                    'category' => $this->category,
                    'proficiency' => $this->proficiency,
                    'sort_order' => $this->sort_order,
                ]);
                $message = 'Skill created successfully!';
            }

            $this->resetForm();
            $this->showForm = false;

            session()->flash('message', $message);
        } catch (\Exception $e) {
            session()->flash('error', 'An error occurred while saving the skill: ' . $e->getMessage());
        }
    }

    public function confirmDelete($skillId)
    {
        $this->skillToDelete = $skillId;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        if ($this->skillToDelete) {
            try {
                $skill = Skill::findOrFail($this->skillToDelete);
                $skill->delete();
                
                $this->showDeleteModal = false;
                $this->skillToDelete = null;
                
                session()->flash('message', 'Skill deleted successfully!');
            } catch (\Exception $e) {
                session()->flash('error', 'An error occurred while deleting the skill: ' . $e->getMessage());
            }
        }
    }

    public function cancelDelete()
    {
        $this->showDeleteModal = false;
        $this->skillToDelete = null;
    }

    public function cancel()
    {
        $this->resetForm();
        $this->showForm = false;
    }

    public function showImport()
    {
        $this->resetImport();
        $this->showImportModal = true;
    }

    public function processImport()
    {
        $this->validate(['importFile' => 'required|file|mimes:json,csv,txt|max:1024']);

        try {
            $content = file_get_contents($this->importFile->getRealPath());
            
            if ($this->importType === 'json') {
                $this->importData = json_decode($content, true);
                
                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new \Exception('Invalid JSON format: ' . json_last_error_msg());
                }
            } else {
                // CSV processing
                $lines = str_getcsv($content, "\n");
                $header = str_getcsv(array_shift($lines));
                
                $this->importData = [];
                foreach ($lines as $line) {
                    if (trim($line)) {
                        $row = str_getcsv($line);
                        $this->importData[] = array_combine($header, $row);
                    }
                }
            }

            $this->validateImportData();
            $this->generateImportPreview();
            
        } catch (\Exception $e) {
            session()->flash('error', 'Import failed: ' . $e->getMessage());
        }
    }

    public function confirmImport()
    {
        try {
            $imported = 0;
            $skipped = 0;

            foreach ($this->importPreview as $skillData) {
                if ($skillData['valid']) {
                    // Check if skill already exists
                    $existing = Skill::where('name', $skillData['name'])
                                   ->where('category', $skillData['category'])
                                   ->first();
                    
                    if (!$existing) {
                        Skill::create([
                            'name' => $skillData['name'],
                            'category' => $skillData['category'],
                            'proficiency' => $skillData['proficiency'],
                            'sort_order' => $skillData['sort_order'] ?? 0,
                        ]);
                        $imported++;
                    } else {
                        $skipped++;
                    }
                }
            }

            $this->resetImport();
            $this->showImportModal = false;

            session()->flash('message', "Import completed! {$imported} skills imported, {$skipped} skipped (duplicates).");
        } catch (\Exception $e) {
            session()->flash('error', 'Import failed: ' . $e->getMessage());
        }
    }

    public function cancelImport()
    {
        $this->resetImport();
        $this->showImportModal = false;
    }

    public function updateSkillOrder($orderedSkills)
    {
        try {
            foreach ($orderedSkills as $index => $skillData) {
                if (isset($skillData['id'])) {
                    Skill::where('id', $skillData['id'])
                         ->update(['sort_order' => $index]);
                }
            }
            
            session()->flash('message', 'Skill order updated successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update skill order: ' . $e->getMessage());
        }
    }

    private function validateImportData()
    {
        if (empty($this->importData)) {
            throw new \Exception('No data found in import file.');
        }

        // Check required fields
        $requiredFields = ['name', 'category'];
        $firstRow = $this->importData[0];
        
        foreach ($requiredFields as $field) {
            if (!array_key_exists($field, $firstRow)) {
                throw new \Exception("Required field '{$field}' not found in import data.");
            }
        }
    }

    private function generateImportPreview()
    {
        $this->importPreview = [];
        
        foreach ($this->importData as $index => $skillData) {
            $preview = [
                'name' => $skillData['name'] ?? '',
                'category' => $skillData['category'] ?? '',
                'proficiency' => isset($skillData['proficiency']) ? (int)$skillData['proficiency'] : 80,
                'sort_order' => isset($skillData['sort_order']) ? (int)$skillData['sort_order'] : 0,
                'valid' => true,
                'errors' => [],
            ];

            // Validate each field
            $validator = Validator::make($preview, [
                'name' => 'required|string|max:255',
                'category' => 'required|string|max:100',
                'proficiency' => 'integer|min:1|max:100',
                'sort_order' => 'integer|min:0',
            ]);

            if ($validator->fails()) {
                $preview['valid'] = false;
                $preview['errors'] = $validator->errors()->all();
            }

            $this->importPreview[] = $preview;
        }
    }

    private function resetForm()
    {
        $this->name = '';
        $this->category = '';
        $this->proficiency = 80;
        $this->sort_order = 0;
        $this->editing = null;
    }

    private function resetImport()
    {
        $this->importFile = null;
        $this->importType = 'json';
        $this->importData = [];
        $this->importPreview = [];
    }
}
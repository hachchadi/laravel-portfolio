<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class Navigation extends Component
{
    public $activeSection = 'home';
    public $mobileMenuOpen = false;

    protected $listeners = ['setActiveSection'];

    public function mount()
    {
        // Initialize with home section
        $this->activeSection = 'home';
    }

    public function toggleMobileMenu()
    {
        $this->mobileMenuOpen = !$this->mobileMenuOpen;
    }

    public function setActiveSection($section)
    {
        $this->activeSection = $section;
        
        // Close mobile menu when section is selected
        if ($this->mobileMenuOpen) {
            $this->mobileMenuOpen = false;
        }
    }

    #[On('setActiveSection')]
    public function updateActiveSection($section)
    {
        $this->activeSection = $section;
    }

    public function scrollToSection($section)
    {
        $this->setActiveSection($section);
        $this->dispatch('scroll-to-section', section: $section);
    }

    public function closeMobileMenu()
    {
        $this->mobileMenuOpen = false;
    }

    public function render()
    {
        return view('livewire.navigation');
    }
}

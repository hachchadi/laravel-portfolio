<div>
    <nav class="fixed top-0 left-0 right-0 z-50 bg-white/90 backdrop-blur-md border-b border-gray-200 dark:bg-gray-900/90 dark:border-gray-700 transition-all duration-300"
         x-data="portfolioNavigation"
         @click.away="mobileMenuOpen = false">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo/Brand -->
                <div class="flex-shrink-0">
                    <a href="#home" 
                       wire:click="setActiveSection('home')"
                       class="text-xl font-bold text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 rounded-md px-2 py-1"
                       aria-label="Go to home section">
                        <span class="bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                            Hicham Hachchadi
                        </span>
                    </a>
                </div>

                <!-- Desktop Navigation -->
                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-1">
                        @foreach(['home' => 'Home', 'about' => 'About', 'skills' => 'Skills', 'projects' => 'Projects', 'contact' => 'Contact'] as $section => $label)
                            <a href="#{{ $section }}" 
                               wire:click="scrollToSection('{{ $section }}')"
                               @click.prevent="$dispatch('scroll-to-section', { section: '{{ $section }}' })"
                               class="nav-link {{ $activeSection === $section ? 'active' : '' }}"
                               aria-current="{{ $activeSection === $section ? 'page' : 'false' }}">
                                {{ $label }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen"
                            class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500 transition-colors duration-200"
                            :aria-expanded="mobileMenuOpen"
                            aria-label="Toggle mobile menu">
                        <span class="sr-only">Open main menu</span>
                        <!-- Hamburger icon -->
                        <svg class="block h-6 w-6 transition-transform duration-200" 
                             :class="{ 'rotate-90 opacity-0': mobileMenuOpen, 'rotate-0 opacity-100': !mobileMenuOpen }"
                             xmlns="http://www.w3.org/2000/svg" 
                             fill="none" 
                             viewBox="0 0 24 24" 
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <!-- Close icon -->
                        <svg class="block h-6 w-6 absolute transition-transform duration-200" 
                             :class="{ 'rotate-0 opacity-100': mobileMenuOpen, '-rotate-90 opacity-0': !mobileMenuOpen }"
                             xmlns="http://www.w3.org/2000/svg" 
                             fill="none" 
                             viewBox="0 0 24 24" 
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation Menu -->
        <div class="md:hidden"
             x-show="mobileMenuOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="transform opacity-0 scale-95 -translate-y-2"
             x-transition:enter-end="transform opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="transform opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="transform opacity-0 scale-95 -translate-y-2"
             style="display: none;">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700 shadow-lg">
                @foreach(['home' => 'Home', 'about' => 'About', 'skills' => 'Skills', 'projects' => 'Projects', 'contact' => 'Contact'] as $section => $label)
                    <a href="#{{ $section }}" 
                       wire:click="scrollToSection('{{ $section }}')"
                       @click.prevent="mobileMenuOpen = false; $dispatch('scroll-to-section', { section: '{{ $section }}' })"
                       class="mobile-nav-link {{ $activeSection === $section ? 'active' : '' }}"
                       aria-current="{{ $activeSection === $section ? 'page' : 'false' }}">
                        <span class="flex items-center">
                            <span class="w-2 h-2 rounded-full mr-3 transition-colors duration-200 {{ $activeSection === $section ? 'bg-blue-500' : 'bg-gray-300 dark:bg-gray-600' }}"></span>
                            {{ $label }}
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    </nav>
</div>

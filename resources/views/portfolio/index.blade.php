<x-portfolio-layout>
    <x-slot name="title">{{ $developer->name ?? 'Portfolio' }} - Senior Laravel Developer</x-slot>
    <x-slot name="description">{{ $developer->bio ?? 'Professional portfolio showcasing skills, projects, and experience' }}</x-slot>

    <!-- Hero Section -->
    <section id="home" class="portfolio-section relative overflow-hidden bg-gradient-to-br from-blue-50 via-white to-purple-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
        <div class="absolute inset-0 bg-grid-pattern opacity-5"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-32">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Hero Content -->
                <div class="section-content">
                    <div class="space-y-6">
                        <div class="space-y-2">
                            <p class="text-blue-600 dark:text-blue-400 font-medium text-lg">Hello, I'm</p>
                            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-gray-900 dark:text-white leading-tight">
                                {{ $developer->name ?? 'Your Name' }}
                            </h1>
                            <h2 class="text-xl sm:text-2xl lg:text-3xl text-gray-600 dark:text-gray-300 font-medium">
                                {{ $developer->title ?? 'Senior Laravel Developer' }}
                            </h2>
                        </div>
                        
                        <p class="text-lg text-gray-600 dark:text-gray-400 leading-relaxed max-w-xl">
                            {{ $developer->bio ?? 'Passionate about creating robust, scalable web applications with clean code and exceptional user experiences.' }}
                        </p>
                        
                        <div class="flex flex-col sm:flex-row gap-4">
                            <a href="#projects" 
                               @click.prevent="$dispatch('scroll-to-section', { section: 'projects' })"
                               class="inline-flex items-center justify-center px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                View My Work
                                <svg class="ml-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                            </a>
                            <a href="#contact" 
                               @click.prevent="$dispatch('scroll-to-section', { section: 'contact' })"
                               class="inline-flex items-center justify-center px-8 py-3 border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:border-blue-600 hover:text-blue-600 dark:hover:border-blue-400 dark:hover:text-blue-400 font-medium rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                Get In Touch
                            </a>
                        </div>
                        
                        <!-- Social Links -->
                        @if($developer && ($developer->github_url || $developer->linkedin_url))
                        <div class="flex space-x-4 pt-4">
                            @if($developer->github_url)
                            <a href="{{ $developer->github_url }}" 
                               target="_blank" 
                               rel="noopener noreferrer"
                               class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-200">
                                <span class="sr-only">GitHub</span>
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                                </svg>
                            </a>
                            @endif
                            
                            @if($developer->linkedin_url)
                            <a href="{{ $developer->linkedin_url }}" 
                               target="_blank" 
                               rel="noopener noreferrer"
                               class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-200">
                                <span class="sr-only">LinkedIn</span>
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                </svg>
                            </a>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
                
                <!-- Hero Image/Avatar -->
                <div class="section-content lg:order-first">
                    <div class="relative">
                        <div class="relative w-80 h-80 mx-auto">
                            <!-- Background decoration -->
                            <div class="absolute inset-0 bg-gradient-to-r from-blue-400 to-purple-500 rounded-full blur-3xl opacity-20 animate-pulse"></div>
                            
                            <!-- Avatar container -->
                            <div class="relative w-full h-full rounded-full overflow-hidden border-4 border-white dark:border-gray-700 shadow-2xl">
                                @if($developer && $developer->avatar)
                                    <img src="{{ asset('storage/' . $developer->avatar) }}" 
                                         alt="{{ $developer->name }}"
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                                        <span class="text-6xl font-bold text-white">
                                            {{ substr($developer->name ?? 'D', 0, 1) }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Floating elements -->
                            <div class="absolute -top-4 -right-4 w-16 h-16 bg-yellow-400 rounded-full flex items-center justify-center shadow-lg animate-bounce">
                                <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Scroll indicator -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
            <a href="#about" 
               @click.prevent="$dispatch('scroll-to-section', { section: 'about' })"
               class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                </svg>
            </a>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="portfolio-section bg-white dark:bg-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="section-content">
                <!-- Section Header -->
                <div class="text-center mb-16">
                    <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white mb-4">
                        About Me
                    </h2>
                    <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                        Get to know more about my background, experience, and what drives my passion for development.
                    </p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <!-- About Content -->
                    <div class="stagger-animation">
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">
                                    Professional Background
                                </h3>
                                <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                                    {{ $developer->bio ?? 'With years of experience in web development, I specialize in creating robust, scalable applications using Laravel and modern web technologies. My passion lies in writing clean, maintainable code and delivering exceptional user experiences.' }}
                                </p>
                            </div>

                            <div>
                                <h3 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">
                                    What I Do
                                </h3>
                                <ul class="space-y-3 text-gray-600 dark:text-gray-400">
                                    <li class="flex items-start">
                                        <svg class="w-5 h-5 text-blue-500 mt-1 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        Full-stack web application development
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-5 h-5 text-blue-500 mt-1 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        API design and development
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-5 h-5 text-blue-500 mt-1 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        Database design and optimization
                                    </li>
                                    <li class="flex items-start">
                                        <svg class="w-5 h-5 text-blue-500 mt-1 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        Performance optimization and scaling
                                    </li>
                                </ul>
                            </div>

                            <!-- Contact Information -->
                            @if($developer && ($developer->location || $developer->phone))
                            <div>
                                <h3 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4">
                                    Contact Information
                                </h3>
                                <div class="space-y-3">
                                    @if($developer->location)
                                    <div class="flex items-center text-gray-600 dark:text-gray-400">
                                        <svg class="w-5 h-5 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        {{ $developer->location }}
                                    </div>
                                    @endif
                                    
                                    @if($developer->phone)
                                    <div class="flex items-center text-gray-600 dark:text-gray-400">
                                        <svg class="w-5 h-5 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                        </svg>
                                        {{ $developer->phone }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Stats/Achievements -->
                    <div class="stagger-animation">
                        <div class="grid grid-cols-2 gap-6">
                            <div class="text-center p-6 bg-gray-50 dark:bg-gray-700 rounded-xl">
                                <div class="text-3xl font-bold text-blue-600 dark:text-blue-400 mb-2">8+</div>
                                <div class="text-gray-600 dark:text-gray-400">Years Experience</div>
                            </div>
                            <div class="text-center p-6 bg-gray-50 dark:bg-gray-700 rounded-xl">
                                <div class="text-3xl font-bold text-blue-600 dark:text-blue-400 mb-2">{{ $featuredProjects->count() }}+</div>
                                <div class="text-gray-600 dark:text-gray-400">Projects Completed</div>
                            </div>
                            <div class="text-center p-6 bg-gray-50 dark:bg-gray-700 rounded-xl">
                                <div class="text-3xl font-bold text-blue-600 dark:text-blue-400 mb-2">{{ $skills->count() }}+</div>
                                <div class="text-gray-600 dark:text-gray-400">Technologies</div>
                            </div>
                            <div class="text-center p-6 bg-gray-50 dark:bg-gray-700 rounded-xl">
                                <div class="text-3xl font-bold text-blue-600 dark:text-blue-400 mb-2">100%</div>
                                <div class="text-gray-600 dark:text-gray-400">Client Satisfaction</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Skills Section -->
    <section id="skills" class="portfolio-section bg-gray-50 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            @livewire('skills-display')
        </div>
    </section>

    <!-- Projects Section -->
    <section id="projects" class="portfolio-section bg-white dark:bg-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            @livewire('project-gallery')
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="portfolio-section bg-gray-50 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            @livewire('contact-form')
        </div>
    </section>
</x-portfolio-layout>
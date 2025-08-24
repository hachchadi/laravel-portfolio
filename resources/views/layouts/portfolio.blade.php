<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- SEO Meta Tags -->
        <x-seo-meta :meta="$meta ?? []" :structured-data="$structuredData ?? []" />

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="font-sans antialiased bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100"
          x-data="portfolioApp()" 
          x-init="init()"
          @scroll.window="updateActiveSection()">
        
        <!-- Skip Links for Accessibility -->
        <x-skip-links />
        
        <!-- Navigation -->
        <nav id="navigation" role="navigation" aria-label="Main navigation">
            @livewire('navigation')
        </nav>

        <!-- Main Content -->
        <main id="main-content" class="relative" role="main">
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer id="footer" class="bg-gray-50 dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700" role="contentinfo">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Contact Info -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Get In Touch</h3>
                        <div class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                            <p>Ready to work together?</p>
                            <p>Let's create something amazing.</p>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                        <nav class="space-y-2">
                            @foreach(['home' => 'Home', 'about' => 'About', 'skills' => 'Skills', 'projects' => 'Projects', 'contact' => 'Contact'] as $section => $label)
                                <a href="#{{ $section }}" 
                                   @click="scrollToSection('{{ $section }}')"
                                   class="block text-sm text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200">
                                    {{ $label }}
                                </a>
                            @endforeach
                        </nav>
                    </div>

                    <!-- Social Links -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Connect</h3>
                        <div class="flex space-x-4">
                            <a href="#" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-200">
                                <span class="sr-only">GitHub</span>
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                                </svg>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-200">
                                <span class="sr-only">LinkedIn</span>
                                <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                </svg>
                            </a>
                            <a href="#" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-200">
                                <span class="sr-only">Email</span>
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Copyright -->
                <div class="mt-8 pt-8 border-t border-gray-200 dark:border-gray-700">
                    <p class="text-center text-sm text-gray-500 dark:text-gray-400">
                        © {{ date('Y') }} Portfolio. All rights reserved.
                    </p>
                </div>
            </div>
        </footer>

        @livewireScripts

        <script>
            function portfolioApp() {
                return {
                    activeSection: 'home',
                    sections: ['home', 'about', 'skills', 'projects', 'contact'],
                    
                    init() {
                        // Initialize intersection observer for section detection
                        this.initIntersectionObserver();
                        
                        // Set initial active section
                        this.updateActiveSection();
                        
                        // Initialize keyboard navigation
                        this.initKeyboardNavigation();
                        
                        // Initialize performance monitoring
                        this.initPerformanceMonitoring();
                    },

                    initIntersectionObserver() {
                        const options = {
                            root: null,
                            rootMargin: '-20% 0px -70% 0px',
                            threshold: 0
                        };

                        const observer = new IntersectionObserver((entries) => {
                            entries.forEach(entry => {
                                if (entry.isIntersecting) {
                                    const sectionId = entry.target.id;
                                    if (this.sections.includes(sectionId)) {
                                        this.setActiveSection(sectionId);
                                    }
                                }
                            });
                        }, options);

                        // Observe all sections
                        this.sections.forEach(sectionId => {
                            const element = document.getElementById(sectionId);
                            if (element) {
                                observer.observe(element);
                            }
                        });
                    },

                    updateActiveSection() {
                        // Fallback method for scroll-based detection
                        const scrollPosition = window.scrollY + 100;
                        
                        for (let i = this.sections.length - 1; i >= 0; i--) {
                            const section = document.getElementById(this.sections[i]);
                            if (section && section.offsetTop <= scrollPosition) {
                                this.setActiveSection(this.sections[i]);
                                break;
                            }
                        }
                    },

                    setActiveSection(section) {
                        if (this.activeSection !== section) {
                            this.activeSection = section;
                            // Notify Livewire navigation component
                            Livewire.dispatch('setActiveSection', { section: section });
                        }
                    },

                    scrollToSection(sectionId) {
                        const element = document.getElementById(sectionId);
                        if (element) {
                            const navHeight = 64; // Height of fixed navigation
                            const targetPosition = element.offsetTop - navHeight;
                            
                            window.scrollTo({
                                top: targetPosition,
                                behavior: 'smooth'
                            });
                            
                            this.setActiveSection(sectionId);
                            
                            // Focus the section for screen readers
                            element.focus({ preventScroll: true });
                        }
                    },

                    initKeyboardNavigation() {
                        document.addEventListener('keydown', (e) => {
                            // Handle keyboard navigation
                            if (e.key === 'Tab') {
                                // Ensure focus is visible
                                document.body.classList.add('keyboard-navigation');
                            }
                            
                            // Arrow key navigation for sections
                            if (e.altKey) {
                                const currentIndex = this.sections.indexOf(this.activeSection);
                                let newIndex = currentIndex;
                                
                                if (e.key === 'ArrowDown' || e.key === 'ArrowRight') {
                                    e.preventDefault();
                                    newIndex = (currentIndex + 1) % this.sections.length;
                                } else if (e.key === 'ArrowUp' || e.key === 'ArrowLeft') {
                                    e.preventDefault();
                                    newIndex = currentIndex > 0 ? currentIndex - 1 : this.sections.length - 1;
                                }
                                
                                if (newIndex !== currentIndex) {
                                    this.scrollToSection(this.sections[newIndex]);
                                }
                            }
                        });

                        // Remove keyboard navigation class on mouse use
                        document.addEventListener('mousedown', () => {
                            document.body.classList.remove('keyboard-navigation');
                        });
                    },

                    initPerformanceMonitoring() {
                        // Monitor Core Web Vitals if supported
                        if ('web-vital' in window) {
                            // This would integrate with a real Web Vitals library
                            console.log('Performance monitoring initialized');
                        }

                        // Monitor page load performance
                        window.addEventListener('load', () => {
                            const perfData = performance.getEntriesByType('navigation')[0];
                            if (perfData) {
                                const loadTime = perfData.loadEventEnd - perfData.loadEventStart;
                                if (loadTime > 3000) {
                                    console.warn('Page load time exceeds 3 seconds:', loadTime + 'ms');
                                }
                            }
                        });

                        // Monitor layout shifts
                        if ('PerformanceObserver' in window) {
                            const observer = new PerformanceObserver((list) => {
                                for (const entry of list.getEntries()) {
                                    if (entry.entryType === 'layout-shift' && !entry.hadRecentInput) {
                                        console.log('Layout shift detected:', entry.value);
                                    }
                                }
                            });
                            
                            try {
                                observer.observe({ entryTypes: ['layout-shift'] });
                            } catch (e) {
                                // Layout shift monitoring not supported
                            }
                        }
                    }
                }
            }
        </script>
    </body>
</html>
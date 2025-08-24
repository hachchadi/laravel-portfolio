<div class="project-gallery-container"
     x-data="{ 
         animateOnLoad: @entangle('animateOnLoad'),
         showModal: @entangle('showModal'),
         isVisible: false,
         init() {
             // Intersection Observer for scroll-triggered animations
             const observer = new IntersectionObserver((entries) => {
                 entries.forEach(entry => {
                     if (entry.isIntersecting) {
                         this.isVisible = true;
                         this.$wire.startAnimation();
                     }
                 });
             }, { threshold: 0.1 });
             
             observer.observe(this.$el);
             
             // Handle keyboard navigation for modal
             document.addEventListener('keydown', (e) => {
                 if (this.showModal) {
                     if (e.key === 'Escape') {
                         this.$wire.closeModal();
                     } else if (e.key === 'ArrowLeft') {
                         this.$wire.previousImage();
                     } else if (e.key === 'ArrowRight') {
                         this.$wire.nextImage();
                     }
                 }
             });
         }
     }"
     @projects-loaded.window="setTimeout(() => { isVisible = true; }, 100)"
     @modal-opened.window="document.body.style.overflow = 'hidden'"
     @modal-closed.window="document.body.style.overflow = 'auto'">

    <!-- Projects Section Header -->
    <div class="text-center mb-12">
        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4"
            x-show="isVisible"
            x-transition:enter="transition ease-out duration-700 delay-100"
            x-transition:enter-start="opacity-0 transform translate-y-8"
            x-transition:enter-end="opacity-100 transform translate-y-0">
            Featured Projects
        </h2>
        <p class="text-lg text-gray-600 dark:text-gray-300 max-w-2xl mx-auto"
           x-show="isVisible"
           x-transition:enter="transition ease-out duration-700 delay-200"
           x-transition:enter-start="opacity-0 transform translate-y-8"
           x-transition:enter-end="opacity-100 transform translate-y-0">
            A showcase of my recent work and projects, demonstrating various technologies and development approaches.
        </p>
    </div>

    <!-- Technology Filter -->
    @if($projects->isNotEmpty())
        <div class="mb-8"
             x-show="isVisible"
             x-transition:enter="transition ease-out duration-700 delay-300"
             x-transition:enter-start="opacity-0 transform translate-y-8"
             x-transition:enter-end="opacity-100 transform translate-y-0">
            <div class="flex flex-wrap justify-center gap-2 mb-6">
                <button wire:click="filterByTechnology('')"
                        class="px-4 py-2 rounded-full text-sm font-medium transition-all duration-200 border {{ $filterTechnology === '' ? 'bg-blue-600 border-blue-600 text-white' : 'bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                    All Projects
                </button>
                @foreach($this->getAllTechnologies() as $tech)
                    <button wire:click="filterByTechnology('{{ $tech }}')"
                            class="px-4 py-2 rounded-full text-sm font-medium transition-all duration-200 border {{ $filterTechnology === $tech ? 'bg-blue-600 border-blue-600 text-white' : 'bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                        {{ $tech }}
                    </button>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Projects Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($projects as $project)
            <div class="project-card bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 overflow-hidden group cursor-pointer"
                 wire:click="showProject({{ $project->id }})"
                 x-show="isVisible"
                 x-transition:enter="transition ease-out duration-700"
                 x-transition:enter-start="opacity-0 transform translate-y-12 scale-95"
                 x-transition:enter-end="opacity-100 transform translate-y-0 scale-100"
                 style="transition-delay: {{ $loop->index * 150 }}ms;">
                
                <!-- Project Image -->
                <div class="relative h-48 bg-gray-200 dark:bg-gray-700 overflow-hidden">
                    @if($project->images->isNotEmpty())
                        <x-responsive-image 
                            :src="$project->images->first()->image_path"
                            :alt="$project->images->first()->alt_text ?? $project->title"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500 will-change-transform"
                            sizes="(max-width: 768px) 100vw, (max-width: 1024px) 50vw, 33vw"
                        />
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    @endif
                    
                    <!-- Overlay -->
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300 flex items-center justify-center">
                        <div class="transform translate-y-4 group-hover:translate-y-0 opacity-0 group-hover:opacity-100 transition-all duration-300">
                            <div class="bg-white dark:bg-gray-800 rounded-full p-3 shadow-lg">
                                <svg class="w-6 h-6 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Project Content -->
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                        {{ $project->title }}
                    </h3>
                    
                    <p class="text-gray-600 dark:text-gray-300 text-sm mb-4 line-clamp-3">
                        {{ Str::limit($project->description, 120) }}
                    </p>
                    
                    <!-- Technologies -->
                    <div class="flex flex-wrap gap-2 mb-4">
                        @foreach(array_slice($project->technologies ?? [], 0, 3) as $tech)
                            <span class="px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 text-xs rounded-full">
                                {{ $tech }}
                            </span>
                        @endforeach
                        @if(count($project->technologies ?? []) > 3)
                            <span class="px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 text-xs rounded-full">
                                +{{ count($project->technologies) - 3 }} more
                            </span>
                        @endif
                    </div>
                    
                    <!-- Project Links -->
                    <div class="flex items-center justify-between">
                        <div class="flex space-x-3">
                            @if($project->github_url)
                                <a href="{{ $project->github_url }}" 
                                   target="_blank" 
                                   class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                                   onclick="event.stopPropagation()">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                                    </svg>
                                </a>
                            @endif
                            @if($project->demo_url)
                                <a href="{{ $project->demo_url }}" 
                                   target="_blank" 
                                   class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors"
                                   onclick="event.stopPropagation()">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                </a>
                            @endif
                        </div>
                        
                        <span class="text-xs text-gray-500 dark:text-gray-400">
                            Click to view details
                        </span>
                    </div>
                </div>
            </div>
        @empty
            <!-- Empty State -->
            <div class="col-span-full text-center py-16">
                <div class="w-24 h-24 mx-auto mb-6 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center">
                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">No Projects Available</h3>
                <p class="text-gray-600 dark:text-gray-400">
                    @if($filterTechnology)
                        No projects found using "{{ $filterTechnology }}". Try a different filter.
                    @else
                        Projects will be displayed here once they are added to the portfolio.
                    @endif
                </p>
                @if($filterTechnology)
                    <button wire:click="filterByTechnology('')" 
                            class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Show All Projects
                    </button>
                @endif
            </div>
        @endforelse
    </div>

    <!-- Project Modal -->
    @if($selectedProject)
        <div class="fixed inset-0 z-50 overflow-y-auto"
             x-show="showModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             style="display: none;">
            
            <!-- Modal Backdrop -->
            <div class="fixed inset-0 bg-black bg-opacity-75 transition-opacity"
                 @click="$wire.closeModal()"></div>
            
            <!-- Modal Content -->
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative bg-white dark:bg-gray-800 rounded-xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 transform scale-100"
                     x-transition:leave-end="opacity-0 transform scale-95">
                    
                    <!-- Close Button -->
                    <button @click="$wire.closeModal()"
                            class="absolute top-4 right-4 z-10 bg-black bg-opacity-50 hover:bg-opacity-75 text-white rounded-full p-2 transition-all">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                    
                    <div class="flex flex-col lg:flex-row max-h-[90vh]">
                        <!-- Image Gallery Section -->
                        <div class="lg:w-2/3 relative bg-gray-100 dark:bg-gray-900">
                            @if($selectedProject->images->isNotEmpty())
                                <div class="relative h-64 lg:h-96">
                                    <x-responsive-image 
                                        :src="$selectedProject->images[$currentImageIndex]->image_path"
                                        :alt="$selectedProject->images[$currentImageIndex]->alt_text ?? $selectedProject->title"
                                        class="w-full h-full object-cover"
                                        :lazy="false"
                                        sizes="(max-width: 1024px) 100vw, 66vw"
                                    />
                                    
                                    <!-- Navigation Arrows -->
                                    @if($selectedProject->images->count() > 1)
                                        <button wire:click="previousImage"
                                                class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 hover:bg-opacity-75 text-white rounded-full p-2 transition-all">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                            </svg>
                                        </button>
                                        
                                        <button wire:click="nextImage"
                                                class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 hover:bg-opacity-75 text-white rounded-full p-2 transition-all">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </button>
                                        
                                        <!-- Image Indicators -->
                                        <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2">
                                            @foreach($selectedProject->images as $index => $image)
                                                <button wire:click="setCurrentImage({{ $index }})"
                                                        class="w-3 h-3 rounded-full transition-all {{ $index === $currentImageIndex ? 'bg-white' : 'bg-white bg-opacity-50' }}">
                                                </button>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Thumbnail Strip -->
                                @if($selectedProject->images->count() > 1)
                                    <div class="flex space-x-2 p-4 overflow-x-auto">
                                        @foreach($selectedProject->images as $index => $image)
                                            <button wire:click="setCurrentImage({{ $index }})"
                                                    class="flex-shrink-0 w-16 h-16 rounded-lg overflow-hidden border-2 transition-all {{ $index === $currentImageIndex ? 'border-blue-500' : 'border-transparent' }}">
                                                <x-responsive-image 
                                                    :src="$image->image_path"
                                                    :alt="$image->alt_text ?? $selectedProject->title"
                                                    class="w-full h-full object-cover"
                                                    sizes="64px"
                                                />
                                            </button>
                                        @endforeach
                                    </div>
                                @endif
                            @else
                                <div class="h-64 lg:h-96 flex items-center justify-center">
                                    <svg class="w-24 h-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Project Details Section -->
                        <div class="lg:w-1/3 p-6 overflow-y-auto">
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">
                                {{ $selectedProject->title }}
                            </h2>
                            
                            <p class="text-gray-600 dark:text-gray-300 mb-6 leading-relaxed">
                                {{ $selectedProject->description }}
                            </p>
                            
                            <!-- Technologies -->
                            <div class="mb-6">
                                <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Technologies Used</h3>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($selectedProject->technologies ?? [] as $tech)
                                        <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 text-sm rounded-full">
                                            {{ $tech }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                            
                            <!-- Project Links -->
                            <div class="flex space-x-4">
                                @if($selectedProject->github_url)
                                    <a href="{{ $selectedProject->github_url }}" 
                                       target="_blank" 
                                       class="flex items-center px-4 py-2 bg-gray-900 dark:bg-gray-700 text-white rounded-lg hover:bg-gray-800 dark:hover:bg-gray-600 transition-colors">
                                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                                        </svg>
                                        View Code
                                    </a>
                                @endif
                                
                                @if($selectedProject->demo_url)
                                    <a href="{{ $selectedProject->demo_url }}" 
                                       target="_blank" 
                                       class="flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                        </svg>
                                        Live Demo
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

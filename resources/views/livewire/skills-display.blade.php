<div class="skills-container"
    x-data="portfolioSkills"
    @skills-loaded.window="setTimeout(() => { this.isVisible = true; }, 100)">

    <!-- Skills Section Header -->
    <div class="text-center mb-12">
        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4"
            x-show="isVisible"
            x-transition:enter="transition ease-out duration-700 delay-100"
            x-transition:enter-start="opacity-0 transform translate-y-8"
            x-transition:enter-end="opacity-100 transform translate-y-0">
            Technical Skills
        </h2>
        <p class="text-lg text-gray-600 dark:text-gray-300 max-w-2xl mx-auto"
            x-show="isVisible"
            x-transition:enter="transition ease-out duration-700 delay-200"
            x-transition:enter-start="opacity-0 transform translate-y-8"
            x-transition:enter-end="opacity-100 transform translate-y-0">
            A comprehensive overview of my technical expertise and proficiency levels across various technologies and tools.
        </p>
    </div>

    <!-- Skills Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($this->skillsByCategory as $category => $skills)
        <div class="skill-category bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 p-6 border border-gray-100 dark:border-gray-700"
            x-show="isVisible"
            x-transition:enter="transition ease-out duration-700"
            x-transition:enter-start="opacity-0 transform translate-y-12 scale-95"
            x-transition:enter-end="opacity-100 transform translate-y-0 scale-100"
            style="transition-delay: {{ $loop->index * 150 }}ms;">

            <!-- Category Header -->
            <div class="flex items-center mb-6">
                <div class="skill-category-icon w-10 h-10 rounded-lg bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center mr-3">
                    @switch($category)
                    @case('Backend')
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z" />
                    </svg>
                    @break
                    @case('Frontend')
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 5a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2h-2.22l.123.489.804.804A1 1 0 0113 18H7a1 1 0 01-.707-1.707l.804-.804L7.22 15H5a2 2 0 01-2-2V5zm5.771 7H5V5h10v7H8.771z" clip-rule="evenodd" />
                    </svg>
                    @break
                    @case('Database')
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 12v3c0 1.657 3.134 3 7 3s7-1.343 7-3v-3c0 1.657-3.134 3-7 3s-7-1.343-7-3z" />
                        <path d="M3 7v3c0 1.657 3.134 3 7 3s7-1.343 7-3V7c0 1.657-3.134 3-7 3S3 8.657 3 7z" />
                        <path d="M17 5c0 1.657-3.134 3-7 3S3 6.657 3 5s3.134-3 7-3 7 1.343 7 3z" />
                    </svg>
                    @break
                    @case('Tools')
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                    </svg>
                    @break
                    @default
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    @endswitch
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $category }}</h3>
            </div>

            <!-- Skills List -->
            <div class="space-y-4">
                @foreach($skills as $skill)
                <div class="skill-item">
                    <!-- Skill Name and Proficiency -->
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $skill->name }}</span>
                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ $skill->proficiency }}%</span>
                    </div>

                    <!-- Progress Bar -->
                    <div class="skill-progress-container w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 overflow-hidden">
                        <div class="skill-progress-bar h-full bg-gradient-to-r from-blue-500 to-purple-600 rounded-full transition-all duration-1000 ease-out"
                            x-data="skillProgress({{ $skill->proficiency }}, {{ ($loop->parent->index * 150) + ($loop->index * 100) + 300 }})"
                            :style="`width: ${width}%`">
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>

    <!-- Skills Summary Stats (if we have skills) -->
    @if($this->skillsByCategory->isNotEmpty())
    <div class="mt-16 grid grid-cols-2 md:grid-cols-4 gap-6"
        x-show="isVisible"
        x-transition:enter="transition ease-out duration-700 delay-1000"
        x-transition:enter-start="opacity-0 transform translate-y-8"
        x-transition:enter-end="opacity-100 transform translate-y-0">

        <div class="text-center p-4 bg-gradient-to-br from-blue-50 to-purple-50 dark:from-blue-900/20 dark:to-purple-900/20 rounded-lg">
            <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $this->skillsByCategory->flatten()->count() }}</div>
            <div class="text-sm text-gray-600 dark:text-gray-400">Total Skills</div>
        </div>

        <div class="text-center p-4 bg-gradient-to-br from-green-50 to-blue-50 dark:from-green-900/20 dark:to-blue-900/20 rounded-lg">
            <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $this->skillsByCategory->count() }}</div>
            <div class="text-sm text-gray-600 dark:text-gray-400">Categories</div>
        </div>

        <div class="text-center p-4 bg-gradient-to-br from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 rounded-lg">
            <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $this->skillsByCategory->flatten()->where('proficiency', '>=', 90)->count() }}</div>
            <div class="text-sm text-gray-600 dark:text-gray-400">Expert Level</div>
        </div>

        <div class="text-center p-4 bg-gradient-to-br from-orange-50 to-red-50 dark:from-orange-900/20 dark:to-red-900/20 rounded-lg">
            <div class="text-2xl font-bold text-orange-600 dark:text-orange-400">{{ round($this->skillsByCategory->flatten()->avg('proficiency')) }}%</div>
            <div class="text-sm text-gray-600 dark:text-gray-400">Avg Proficiency</div>
        </div>
    </div>
    @endif

    <!-- Empty State -->
    @if($this->skillsByCategory->isEmpty())
    <div class="text-center py-16">
        <div class="w-24 h-24 mx-auto mb-6 bg-gray-100 dark:bg-gray-800 rounded-full flex items-center justify-center">
            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
            </svg>
        </div>
        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">No Skills Available</h3>
        <p class="text-gray-600 dark:text-gray-400">Skills will be displayed here once they are added to the portfolio.</p>
    </div>
    @endif
</div>
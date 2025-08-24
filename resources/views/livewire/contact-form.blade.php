<div class="contact-form-container"
     x-data="portfolioContact"
     @contact-form-loaded.window="setTimeout(() => { this.isVisible = true; }, 100)"
     @auto-hide-success.window="setTimeout(() => { this.isSubmitted = false; }, 5000)">

    <!-- Contact Section Header -->
    <div class="text-center mb-12">
        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4"
            x-show="isVisible"
            x-transition:enter="transition ease-out duration-700 delay-100"
            x-transition:enter-start="opacity-0 transform translate-y-8"
            x-transition:enter-end="opacity-100 transform translate-y-0">
            Get In Touch
        </h2>
        <p class="text-lg text-gray-600 dark:text-gray-300 max-w-2xl mx-auto"
           x-show="isVisible"
           x-transition:enter="transition ease-out duration-700 delay-200"
           x-transition:enter-start="opacity-0 transform translate-y-8"
           x-transition:enter-end="opacity-100 transform translate-y-0">
            Have a project in mind or want to discuss opportunities? I'd love to hear from you. Send me a message and I'll get back to you as soon as possible.
        </p>
    </div>

    <div class="max-w-2xl mx-auto">
        <!-- Success Message -->
        <div x-show="isSubmitted && !$wire.emailError"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95"
             class="mb-8 p-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl"
             style="display: none;">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-green-800 dark:text-green-200">Message Sent Successfully!</h3>
                    <p class="mt-1 text-sm text-green-700 dark:text-green-300">
                        @if($emailSent)
                            Thank you for reaching out. Your message has been saved and an email notification has been sent. I'll get back to you within 24 hours.
                        @else
                            Thank you for reaching out. Your message has been saved and I'll get back to you within 24 hours.
                        @endif
                    </p>
                </div>
                <div class="ml-auto pl-3">
                    <button @click="isSubmitted = false"
                            class="inline-flex text-green-400 hover:text-green-600 focus:outline-none focus:text-green-600 transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Email Error Message -->
        <div x-show="isSubmitted && $wire.emailError"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95"
             class="mb-8 p-6 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-xl"
             style="display: none;">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">Message Saved with Email Issue</h3>
                    <p class="mt-1 text-sm text-yellow-700 dark:text-yellow-300">
                        Your message has been saved successfully, but there was an issue sending the email notification. I'll still receive your message and get back to you within 24 hours.
                    </p>
                </div>
                <div class="ml-auto pl-3">
                    <button @click="isSubmitted = false"
                            class="inline-flex text-yellow-400 hover:text-yellow-600 focus:outline-none focus:text-yellow-600 transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Contact Form -->
        <form wire:submit="submit"
              class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8 border border-gray-100 dark:border-gray-700"
              x-show="isVisible"
              x-transition:enter="transition ease-out duration-700 delay-300"
              x-transition:enter-start="opacity-0 transform translate-y-12 scale-95"
              x-transition:enter-end="opacity-100 transform translate-y-0 scale-100">
            
            <!-- Form Error -->
            @error('form')
                <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-800 dark:text-red-200">{{ $message }}</p>
                        </div>
                    </div>
                </div>
            @enderror

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Name Field -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="name"
                           wire:model.live.debounce.300ms="name"
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-colors @error('name') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                           placeholder="Your full name"
                           autocomplete="name">
                    @error('name')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" 
                           id="email"
                           wire:model.live.debounce.300ms="email"
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-colors @error('email') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                           placeholder="your.email@example.com"
                           autocomplete="email">
                    @error('email')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Subject Field -->
            <div class="mb-6">
                <label for="subject" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Subject <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="subject"
                       wire:model.live.debounce.300ms="subject"
                       class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-colors @error('subject') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                       placeholder="What's this about?">
                @error('subject')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Message Field -->
            <div class="mb-6">
                <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Message <span class="text-red-500">*</span>
                </label>
                <textarea id="message"
                          wire:model.live.debounce.300ms="message"
                          rows="6"
                          class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-colors resize-none @error('message') border-red-500 focus:ring-red-500 focus:border-red-500 @enderror"
                          placeholder="Tell me about your project, ideas, or just say hello..."></textarea>
                @error('message')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
                
                <!-- Character Counter -->
                <div class="mt-2 text-right">
                    <span class="text-sm text-gray-500 dark:text-gray-400">
                        {{ strlen($message) }}/2000 characters
                    </span>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    <span class="text-red-500">*</span> Required fields
                </div>
                
                <div class="flex space-x-4">
                    <!-- Reset Button -->
                    <button type="button"
                            wire:click="resetForm"
                            class="px-6 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors"
                            :disabled="$wire.isSubmitting">
                        Reset
                    </button>
                    
                    <!-- Submit Button -->
                    <button type="submit"
                            class="px-8 py-3 bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors flex items-center"
                            :disabled="$wire.isSubmitting">
                        <span wire:loading.remove wire:target="submit">Send Message</span>
                        <span wire:loading wire:target="submit" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Sending...
                        </span>
                    </button>
                </div>
            </div>

            <!-- CSRF Protection Notice -->
            <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    Your information is secure and will never be shared with third parties.
                </div>
            </div>
        </form>

        <!-- Contact Information -->
        <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-8"
             x-show="isVisible"
             x-transition:enter="transition ease-out duration-700 delay-500"
             x-transition:enter-start="opacity-0 transform translate-y-8"
             x-transition:enter-end="opacity-100 transform translate-y-0">
            
            <!-- Email -->
            <div class="text-center p-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700">
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Email</h3>
                <p class="text-gray-600 dark:text-gray-300">contact@hachchadi.com</p>
            </div>

            <!-- Response Time -->
            <div class="text-center p-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700">
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Response Time</h3>
                <p class="text-gray-600 dark:text-gray-300">Within 24 hours</p>
            </div>

            <!-- Location -->
            <div class="text-center p-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700">
                <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Location</h3>
                <p class="text-gray-600 dark:text-gray-300">Available Remotely</p>
            </div>
        </div>
    </div>
</div>

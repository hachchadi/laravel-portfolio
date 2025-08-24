@php
    $skipLinks = [
        ['href' => '#main-content', 'text' => 'Skip to main content'],
        ['href' => '#navigation', 'text' => 'Skip to navigation'],
        ['href' => '#footer', 'text' => 'Skip to footer'],
    ];
@endphp

<div class="sr-only focus-within:not-sr-only">
    <div class="fixed top-0 left-0 z-50 bg-blue-600 text-white p-2 rounded-br-lg shadow-lg">
        @foreach($skipLinks as $link)
            <a href="{{ $link['href'] }}" 
               class="block px-4 py-2 text-sm font-medium hover:bg-blue-700 focus:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-blue-600 rounded transition-colors duration-200">
                {{ $link['text'] }}
            </a>
        @endforeach
    </div>
</div>
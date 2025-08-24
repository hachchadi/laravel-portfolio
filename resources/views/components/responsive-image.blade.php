@if(count($imageSources) > 0)
    <picture class="responsive-image-container">
        @foreach($imageSources as $size => $source)
            @if($size !== 'thumbnail')
                <source 
                    srcset="{{ $source['webp'] }}" 
                    type="image/webp"
                    @if(isset($source['width']))
                        media="(min-width: {{ $size === 'large' ? '1024' : ($size === 'medium' ? '640' : '0') }}px)"
                    @endif
                >
                <source 
                    srcset="{{ $source['fallback'] }}" 
                    @if(isset($source['width']))
                        media="(min-width: {{ $size === 'large' ? '1024' : ($size === 'medium' ? '640' : '0') }}px)"
                    @endif
                >
            @endif
        @endforeach
        
        <img 
            @if($lazy)
                loading="lazy"
                data-src="{{ $imageSources['medium']['fallback'] ?? $src }}"
                src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 {{ $imageSources['medium']['width'] ?? 600 }} {{ $imageSources['medium']['height'] ?? 400 }}'%3E%3C/svg%3E"
            @else
                src="{{ $imageSources['medium']['fallback'] ?? $src }}"
            @endif
            alt="{{ $alt }}"
            class="lazy-image {{ $class }}"
            sizes="{{ $sizes }}"
            @if(isset($imageSources['medium']['width']))
                width="{{ $imageSources['medium']['width'] }}"
                height="{{ $imageSources['medium']['height'] }}"
            @endif
        >
    </picture>
@else
    <img 
        @if($lazy)
            loading="lazy"
            data-src="{{ $src }}"
            src="{{ $src }}"
        @else
            src="{{ $src }}"
        @endif
        alt="{{ $alt }}"
        class="lazy-image {{ $class }}"
        sizes="{{ $sizes }}"
        style="opacity: 1;"
    >
@endif
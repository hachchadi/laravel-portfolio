@props(['meta' => [], 'structuredData' => []])

@php
    $meta = $meta ?: [];
@endphp

<!-- Basic Meta Tags -->
<title>{{ $meta['title'] ?? config('app.name') }}</title>
<meta name="description" content="{{ $meta['description'] ?? '' }}">
<meta name="keywords" content="{{ $meta['keywords'] ?? '' }}">
<meta name="author" content="{{ $meta['author'] ?? '' }}">
<meta name="robots" content="{{ $meta['robots'] ?? 'index, follow' }}">

<!-- Canonical URL -->
@if(isset($meta['canonical']))
<link rel="canonical" href="{{ $meta['canonical'] }}">
@endif

<!-- Open Graph Meta Tags -->
<meta property="og:title" content="{{ $meta['og:title'] ?? $meta['title'] ?? config('app.name') }}">
<meta property="og:description" content="{{ $meta['og:description'] ?? $meta['description'] ?? '' }}">
<meta property="og:type" content="{{ $meta['og:type'] ?? 'website' }}">
<meta property="og:url" content="{{ $meta['og:url'] ?? url()->current() }}">
<meta property="og:site_name" content="{{ config('app.name') }}">
<meta property="og:locale" content="{{ $meta['og:locale'] ?? app()->getLocale() }}">

@if(isset($meta['og:image']))
<meta property="og:image" content="{{ $meta['og:image'] }}">
<meta property="og:image:alt" content="{{ $meta['og:image:alt'] ?? $meta['title'] ?? config('app.name') }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
@endif

<!-- Twitter Card Meta Tags -->
<meta name="twitter:card" content="{{ $meta['twitter:card'] ?? 'summary_large_image' }}">
<meta name="twitter:title" content="{{ $meta['twitter:title'] ?? $meta['title'] ?? config('app.name') }}">
<meta name="twitter:description" content="{{ $meta['twitter:description'] ?? $meta['description'] ?? '' }}">

@if(isset($meta['twitter:image']))
<meta name="twitter:image" content="{{ $meta['twitter:image'] }}">
<meta name="twitter:image:alt" content="{{ $meta['twitter:image:alt'] ?? $meta['title'] ?? config('app.name') }}">
@endif

@if(isset($meta['twitter:site']))
<meta name="twitter:site" content="{{ $meta['twitter:site'] }}">
@endif

@if(isset($meta['twitter:creator']))
<meta name="twitter:creator" content="{{ $meta['twitter:creator'] }}">
@endif

<!-- Additional Meta Tags -->
<meta name="theme-color" content="#3B82F6">
<meta name="msapplication-TileColor" content="#3B82F6">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="default">
<meta name="apple-mobile-web-app-title" content="{{ config('app.name') }}">

<!-- Structured Data (JSON-LD) -->
@if(!empty($structuredData))
<script type="application/ld+json">
{!! json_encode($structuredData, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endif

<!-- Preconnect to external domains for performance -->
<link rel="preconnect" href="https://fonts.bunny.net">
<link rel="dns-prefetch" href="https://fonts.bunny.net">

<!-- Favicon and App Icons -->
<link rel="icon" type="image/x-icon" href="/favicon.ico">
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
<link rel="manifest" href="/site.webmanifest">
<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Portfolio Performance Settings
    |--------------------------------------------------------------------------
    |
    | These settings control various performance optimizations for the
    | portfolio application including caching, image optimization, and
    | other performance-related configurations.
    |
    */

    'cache' => [
        /*
         * Default cache TTL in seconds
         */
        'default_ttl' => env('PORTFOLIO_CACHE_TTL', 3600), // 1 hour

        /*
         * Long cache TTL for rarely changing data
         */
        'long_ttl' => env('PORTFOLIO_LONG_CACHE_TTL', 86400), // 24 hours

        /*
         * Enable/disable automatic cache warming
         */
        'auto_warm' => env('PORTFOLIO_AUTO_WARM_CACHE', true),

        /*
         * Cache key prefix
         */
        'prefix' => env('PORTFOLIO_CACHE_PREFIX', 'portfolio'),
    ],

    'images' => [
        /*
         * Image optimization settings
         */
        'optimization' => [
            'enabled' => env('PORTFOLIO_IMAGE_OPTIMIZATION', true),
            'quality' => [
                'thumbnail' => 75,
                'medium' => 85,
                'large' => 90,
                'original' => 95,
            ],
            'sizes' => [
                'thumbnail' => ['width' => 300, 'height' => 200],
                'medium' => ['width' => 600, 'height' => 400],
                'large' => ['width' => 1200, 'height' => 800],
            ],
        ],

        /*
         * WebP conversion settings
         */
        'webp' => [
            'enabled' => env('PORTFOLIO_WEBP_ENABLED', true),
            'fallback' => true,
        ],

        /*
         * Lazy loading settings
         */
        'lazy_loading' => [
            'enabled' => env('PORTFOLIO_LAZY_LOADING', true),
            'threshold' => '50px',
        ],

        /*
         * Upload validation
         */
        'validation' => [
            'max_size' => 10240, // 10MB in KB
            'max_width' => 4000,
            'max_height' => 4000,
            'allowed_types' => ['jpeg', 'jpg', 'png', 'webp'],
        ],
    ],

    'performance' => [
        /*
         * Enable performance monitoring
         */
        'monitoring' => env('PORTFOLIO_PERFORMANCE_MONITORING', false),

        /*
         * Database query optimization
         */
        'database' => [
            'eager_loading' => true,
            'query_caching' => true,
        ],

        /*
         * Asset optimization
         */
        'assets' => [
            'minification' => env('APP_ENV') === 'production',
            'compression' => true,
            'versioning' => true,
        ],

        /*
         * HTTP caching headers
         */
        'http_cache' => [
            'static_assets_ttl' => 31536000, // 1 year
            'dynamic_content_ttl' => 3600, // 1 hour
        ],
    ],

    'livewire' => [
        /*
         * Livewire optimization settings
         */
        'lazy_loading' => true,
        'defer_loading' => true,
        'polling_disabled' => true,
        
        /*
         * Component-specific settings
         */
        'components' => [
            'project_gallery' => [
                'items_per_page' => 12,
                'preload_images' => 3,
            ],
            'skills_display' => [
                'animation_delay' => 100,
                'stagger_animation' => true,
            ],
        ],
    ],

    'seo' => [
        /*
         * SEO optimization settings
         */
        'meta_caching' => true,
        'structured_data' => true,
        'sitemap_caching' => true,
    ],

    'accessibility' => [
        /*
         * Accessibility compliance settings
         */
        'skip_links' => env('PORTFOLIO_SKIP_LINKS', true),
        'high_contrast_mode' => env('PORTFOLIO_HIGH_CONTRAST', false),
        'keyboard_navigation' => env('PORTFOLIO_KEYBOARD_NAV', true),
        'screen_reader_support' => env('PORTFOLIO_SCREEN_READER', true),
        
        /*
         * Color contrast validation
         */
        'validate_contrast' => env('PORTFOLIO_VALIDATE_CONTRAST', true),
        'min_contrast_ratio' => env('PORTFOLIO_MIN_CONTRAST', 4.5),
        
        /*
         * Form accessibility
         */
        'required_labels' => true,
        'error_announcements' => true,
        'focus_management' => true,
    ],

    'performance_monitoring' => [
        /*
         * Core Web Vitals thresholds
         */
        'lcp_threshold' => 2500, // Largest Contentful Paint (ms)
        'fid_threshold' => 100,  // First Input Delay (ms)
        'cls_threshold' => 0.1,  // Cumulative Layout Shift
        'fcp_threshold' => 1800, // First Contentful Paint (ms)
        
        /*
         * Performance budgets
         */
        'max_page_size' => 1024, // KB
        'max_js_size' => 256,    // KB
        'max_css_size' => 64,    // KB
        'max_image_size' => 512, // KB per image
        
        /*
         * Monitoring settings
         */
        'log_slow_requests' => true,
        'slow_request_threshold' => 1000, // ms
        'memory_limit_warning' => 128,    // MB
    ],
];
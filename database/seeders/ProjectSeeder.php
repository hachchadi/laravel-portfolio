<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\ProjectImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = [
            [
                'title' => 'E-Commerce Platform',
                'description' => 'A full-featured e-commerce platform built with Laravel and Vue.js. Features include user authentication, product catalog, shopping cart, payment integration with Stripe, order management, and admin dashboard. The platform supports multiple payment methods, inventory management, and real-time notifications. Built with modern architecture patterns including Repository pattern, Service classes, and comprehensive testing suite.',
                'technologies' => ['Laravel 10', 'Vue.js 3', 'MySQL', 'Stripe API', 'Redis', 'Tailwind CSS', 'Docker', 'PHPUnit'],
                'github_url' => 'https://github.com/johndeveloper/ecommerce-platform',
                'demo_url' => 'https://demo-ecommerce.johndeveloper.com',
                'featured' => true,
                'sort_order' => 1,
                'status' => 'published',
            ],
            [
                'title' => 'Task Management System',
                'description' => 'A collaborative task management application similar to Trello with advanced features. Built with Laravel backend and React frontend. Features include drag-and-drop task boards, team collaboration, file attachments, due dates, notifications, and real-time updates using WebSockets. Includes time tracking, project templates, and comprehensive reporting dashboard.',
                'technologies' => ['Laravel 10', 'React 18', 'PostgreSQL', 'WebSockets', 'Docker', 'Pusher', 'AWS S3'],
                'github_url' => 'https://github.com/johndeveloper/task-manager',
                'demo_url' => 'https://tasks.johndeveloper.com',
                'featured' => true,
                'sort_order' => 2,
                'status' => 'published',
            ],
            [
                'title' => 'Real Estate Portal',
                'description' => 'A comprehensive real estate listing platform with advanced search capabilities, property management, and agent profiles. Includes map integration with Google Maps API, property comparison tools, mortgage calculator, and lead management system for real estate agents. Features virtual tour integration and mobile-responsive design.',
                'technologies' => ['Laravel 10', 'Livewire 3', 'Alpine.js', 'MySQL', 'Google Maps API', 'Tailwind CSS'],
                'github_url' => 'https://github.com/johndeveloper/real-estate-portal',
                'demo_url' => null,
                'featured' => true,
                'sort_order' => 3,
                'status' => 'published',
            ],
            [
                'title' => 'Learning Management System',
                'description' => 'An online learning platform with comprehensive course creation, student enrollment, progress tracking, and assessment tools. Features video streaming with adaptive bitrate, interactive quizzes, discussion forums, certificate generation, and payment processing. Includes instructor dashboard and detailed analytics.',
                'technologies' => ['Laravel 10', 'Vue.js 3', 'MySQL', 'FFmpeg', 'AWS S3', 'Stripe', 'WebRTC'],
                'github_url' => 'https://github.com/johndeveloper/lms-platform',
                'demo_url' => 'https://learn.johndeveloper.com',
                'featured' => false,
                'sort_order' => 4,
                'status' => 'published',
            ],
            [
                'title' => 'Restaurant Management System',
                'description' => 'A complete restaurant management solution with POS system, inventory management, staff scheduling, and customer ordering. Includes mobile app for customers and tablet interface for staff. Features real-time order tracking, kitchen display system, and comprehensive reporting for business analytics.',
                'technologies' => ['Laravel 10', 'React Native', 'MySQL', 'Pusher', 'Stripe', 'PWA'],
                'github_url' => 'https://github.com/johndeveloper/restaurant-pos',
                'demo_url' => null,
                'featured' => false,
                'sort_order' => 5,
                'status' => 'published',
            ],
            [
                'title' => 'Social Media Analytics Dashboard',
                'description' => 'A comprehensive analytics dashboard for social media management. Tracks engagement metrics, follower growth, content performance, and provides insights across multiple social platforms including Facebook, Twitter, Instagram, and LinkedIn. Features automated reporting and competitor analysis.',
                'technologies' => ['Laravel 10', 'Chart.js', 'Redis', 'MySQL', 'Social Media APIs', 'Vue.js 3'],
                'github_url' => 'https://github.com/johndeveloper/social-analytics',
                'demo_url' => 'https://analytics.johndeveloper.com',
                'featured' => true,
                'sort_order' => 6,
                'status' => 'published',
            ],
            [
                'title' => 'Healthcare Management System',
                'description' => 'A HIPAA-compliant healthcare management system for medical practices. Features patient management, appointment scheduling, electronic health records, billing integration, and secure messaging. Built with security-first approach and comprehensive audit logging.',
                'technologies' => ['Laravel 10', 'Livewire 3', 'MySQL', 'Encryption', 'HIPAA Compliance', 'Tailwind CSS'],
                'github_url' => 'https://github.com/johndeveloper/healthcare-system',
                'demo_url' => null,
                'featured' => true,
                'sort_order' => 7,
                'status' => 'published',
            ],
            [
                'title' => 'Inventory Management API',
                'description' => 'A robust REST API for inventory management with multi-warehouse support. Features real-time stock tracking, automated reordering, barcode scanning integration, and comprehensive reporting. Built with API-first approach and extensive documentation.',
                'technologies' => ['Laravel 10', 'API Resources', 'MySQL', 'Redis', 'Swagger', 'JWT Auth'],
                'github_url' => 'https://github.com/johndeveloper/inventory-api',
                'demo_url' => 'https://api-docs.johndeveloper.com/inventory',
                'featured' => false,
                'sort_order' => 8,
                'status' => 'published',
            ],
            [
                'title' => 'Event Management Platform',
                'description' => 'A comprehensive event management platform for organizing conferences, workshops, and meetups. Features event creation, ticket sales, attendee management, speaker profiles, and live streaming integration. Includes mobile app for attendees and organizer dashboard.',
                'technologies' => ['Laravel 10', 'Vue.js 3', 'PostgreSQL', 'Stripe', 'WebRTC', 'PWA'],
                'github_url' => 'https://github.com/johndeveloper/event-platform',
                'demo_url' => 'https://events.johndeveloper.com',
                'featured' => false,
                'sort_order' => 9,
                'status' => 'published',
            ],
            [
                'title' => 'Portfolio Website (This Site)',
                'description' => 'A modern, responsive portfolio website built with Laravel and Livewire. Features dynamic content management, contact form with email notifications, project showcase, skills display, and admin panel for content updates. Optimized for performance and SEO.',
                'technologies' => ['Laravel 10', 'Livewire 3', 'Alpine.js', 'Tailwind CSS', 'MySQL', 'Redis'],
                'github_url' => 'https://github.com/johndeveloper/laravel-portfolio',
                'demo_url' => 'https://johndeveloper.com',
                'featured' => true,
                'sort_order' => 10,
                'status' => 'published',
            ],
        ];

        foreach ($projects as $projectData) {
            $project = Project::create($projectData);
            
            // Create sample images for each project
            $this->createSampleImages($project);
        }
    }

    /**
     * Create sample images for a project
     */
    private function createSampleImages(Project $project): void
    {
        // Create project directory if it doesn't exist
        $projectDir = "projects/{$project->id}";
        if (!Storage::disk('public')->exists($projectDir)) {
            Storage::disk('public')->makeDirectory($projectDir);
        }

        // Define sample images for each project
        $imageCount = rand(2, 5);
        $imageTypes = ['dashboard', 'mobile', 'admin', 'features', 'api'];
        
        // Create a featured image (first image) if project is featured
        if ($project->featured) {
            $featuredPath = "{$projectDir}/featured-hero.svg";
            \Database\Seeders\Helpers\ImagePlaceholderGenerator::createPlaceholder(
                $featuredPath,
                1200,
                800,
                $project->title . ' - Hero'
            );
            
            ProjectImage::create([
                'project_id' => $project->id,
                'image_path' => $featuredPath,
                'alt_text' => "{$project->title} - Featured hero image",
                'sort_order' => 0,
            ]);
        }
        
        for ($i = 1; $i <= $imageCount; $i++) {
            $imageType = $imageTypes[($i - 1) % count($imageTypes)];
            $imagePath = "{$projectDir}/screenshot-{$imageType}-{$i}.svg";
            
            // Create placeholder image
            \Database\Seeders\Helpers\ImagePlaceholderGenerator::createPlaceholder(
                $imagePath,
                800,
                600,
                $project->title . ' - ' . ucfirst($imageType)
            );
            
            ProjectImage::create([
                'project_id' => $project->id,
                'image_path' => $imagePath,
                'alt_text' => "{$project->title} - {$imageType} view screenshot",
                'sort_order' => $i,
            ]);
        }
    }
}

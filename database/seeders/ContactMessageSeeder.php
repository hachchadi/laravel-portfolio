<?php

namespace Database\Seeders;

use App\Models\ContactMessage;
use Illuminate\Database\Seeder;

class ContactMessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $messages = [
            [
                'name' => 'Sarah Johnson',
                'email' => 'sarah.johnson@techcorp.com',
                'subject' => 'Laravel Development Project Inquiry',
                'message' => 'Hi John, I came across your portfolio and I\'m impressed with your Laravel expertise. We have an exciting e-commerce project that requires a senior Laravel developer. The project involves building a multi-vendor marketplace with complex payment integrations. Would you be interested in discussing this opportunity? We\'re looking to start within the next 2 weeks.',
                'status' => 'unread',
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],
            [
                'name' => 'Michael Chen',
                'email' => 'michael@startupxyz.com',
                'subject' => 'Full-Stack Development Opportunity',
                'message' => 'Hello! I\'m the CTO at StartupXYZ and we\'re looking for a talented full-stack developer to join our team. Your experience with Laravel and Vue.js is exactly what we need. We\'re building a SaaS platform for project management and would love to have you on board. Are you open to remote work opportunities?',
                'status' => 'read',
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(4),
            ],
            [
                'name' => 'Emily Rodriguez',
                'email' => 'emily.rodriguez@digitalagency.com',
                'subject' => 'Website Redesign Project',
                'message' => 'Hi John, we\'re a digital agency looking for a Laravel developer to help with a client\'s website redesign. The project involves migrating from an old PHP system to modern Laravel with Livewire. Your portfolio shows exactly the kind of work we need. Could we schedule a call to discuss the details and timeline?',
                'status' => 'replied',
                'created_at' => now()->subWeek(),
                'updated_at' => now()->subDays(6),
            ],
            [
                'name' => 'David Thompson',
                'email' => 'david@freelanceconnect.com',
                'subject' => 'Long-term Partnership Opportunity',
                'message' => 'Hello John, I represent FreelanceConnect and we\'re always looking for top-tier Laravel developers to add to our network. Your skills and portfolio quality are impressive. We have several high-paying projects that would be perfect for your expertise. Would you be interested in a partnership where we send you qualified leads?',
                'status' => 'unread',
                'created_at' => now()->subDays(1),
                'updated_at' => now()->subDays(1),
            ],
            [
                'name' => 'Lisa Wang',
                'email' => 'lisa.wang@edutech.org',
                'subject' => 'Educational Platform Development',
                'message' => 'Hi there! I\'m working on an educational technology platform and need a Laravel expert to help build the backend API and admin panel. The project involves user management, course creation, progress tracking, and payment processing. Your experience with learning management systems caught my attention. Are you available for a 3-month project starting next month?',
                'status' => 'read',
                'created_at' => now()->subDays(8),
                'updated_at' => now()->subDays(7),
            ],
            [
                'name' => 'Robert Martinez',
                'email' => 'robert@healthcaretech.com',
                'subject' => 'Healthcare Application Development',
                'message' => 'John, we\'re developing a healthcare management system and need a senior Laravel developer who understands HIPAA compliance and security best practices. Your portfolio demonstrates the level of professionalism we require. The project involves patient management, appointment scheduling, and secure messaging. Would you be interested in learning more?',
                'status' => 'unread',
                'created_at' => now()->subHours(6),
                'updated_at' => now()->subHours(6),
            ],
            [
                'name' => 'Jennifer Adams',
                'email' => 'jennifer@creativestudio.com',
                'subject' => 'Portfolio Website Collaboration',
                'message' => 'Hello! I\'m a UI/UX designer and I love your portfolio website. I have several clients who need similar portfolio sites built. Would you be interested in collaborating? I would handle the design and you would handle the Laravel development. I think we could create some amazing projects together!',
                'status' => 'replied',
                'created_at' => now()->subDays(12),
                'updated_at' => now()->subDays(10),
            ],
            [
                'name' => 'Alex Kumar',
                'email' => 'alex@techstartup.io',
                'subject' => 'API Development Project',
                'message' => 'Hi John, we need to build a robust REST API for our mobile application. The API needs to handle user authentication, real-time notifications, file uploads, and third-party integrations. Your experience with Laravel API development is exactly what we need. The project has a tight deadline - can we discuss if you\'re available?',
                'status' => 'read',
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(2),
            ],
        ];

        foreach ($messages as $messageData) {
            ContactMessage::create($messageData);
        }
    }
}
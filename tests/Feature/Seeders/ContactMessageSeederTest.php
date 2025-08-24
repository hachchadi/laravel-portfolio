<?php

namespace Tests\Feature\Seeders;

use App\Models\ContactMessage;
use Database\Seeders\ContactMessageSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactMessageSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_message_seeder_creates_messages(): void
    {
        $this->seed(ContactMessageSeeder::class);

        $this->assertGreaterThan(0, ContactMessage::count());
        $this->assertEquals(8, ContactMessage::count()); // Should create 8 messages
    }

    public function test_contact_message_seeder_creates_messages_with_required_fields(): void
    {
        $this->seed(ContactMessageSeeder::class);

        $message = ContactMessage::first();

        $this->assertNotEmpty($message->name);
        $this->assertNotEmpty($message->email);
        $this->assertNotEmpty($message->subject);
        $this->assertNotEmpty($message->message);
        $this->assertNotEmpty($message->status);
        $this->assertNotNull($message->created_at);
        $this->assertNotNull($message->updated_at);
    }

    public function test_contact_message_seeder_creates_messages_with_valid_emails(): void
    {
        $this->seed(ContactMessageSeeder::class);

        $messages = ContactMessage::all();

        foreach ($messages as $message) {
            $this->assertStringContainsString('@', $message->email);
            $this->assertStringContainsString('.', $message->email);
        }
    }

    public function test_contact_message_seeder_creates_messages_with_valid_statuses(): void
    {
        $this->seed(ContactMessageSeeder::class);

        $messages = ContactMessage::all();
        $validStatuses = ['unread', 'read', 'replied'];

        foreach ($messages as $message) {
            $this->assertContains($message->status, $validStatuses);
        }
    }

    public function test_contact_message_seeder_creates_unread_messages(): void
    {
        $this->seed(ContactMessageSeeder::class);

        $unreadMessages = ContactMessage::where('status', 'unread')->get();

        $this->assertGreaterThan(0, $unreadMessages->count());
    }

    public function test_contact_message_seeder_creates_read_messages(): void
    {
        $this->seed(ContactMessageSeeder::class);

        $readMessages = ContactMessage::where('status', 'read')->get();

        $this->assertGreaterThan(0, $readMessages->count());
    }

    public function test_contact_message_seeder_creates_replied_messages(): void
    {
        $this->seed(ContactMessageSeeder::class);

        $repliedMessages = ContactMessage::where('status', 'replied')->get();

        $this->assertGreaterThan(0, $repliedMessages->count());
    }

    public function test_contact_message_seeder_creates_messages_with_different_timestamps(): void
    {
        $this->seed(ContactMessageSeeder::class);

        $messages = ContactMessage::orderBy('created_at')->get();

        // Check that messages have different timestamps (not all the same)
        $timestamps = $messages->pluck('created_at')->unique();
        $this->assertGreaterThan(1, $timestamps->count());
    }

    public function test_contact_message_seeder_creates_realistic_message_content(): void
    {
        $this->seed(ContactMessageSeeder::class);

        $messages = ContactMessage::all();

        foreach ($messages as $message) {
            // Check that messages are substantial (not just test data)
            $this->assertGreaterThan(50, strlen($message->message));
            $this->assertGreaterThan(10, strlen($message->subject));
            
            // Check for professional content
            $this->assertStringContainsStringIgnoringCase('project', $message->message);
        }
    }

    public function test_contact_message_seeder_creates_messages_from_different_senders(): void
    {
        $this->seed(ContactMessageSeeder::class);

        $messages = ContactMessage::all();
        $senderNames = $messages->pluck('name')->unique();
        $senderEmails = $messages->pluck('email')->unique();

        // Should have multiple unique senders
        $this->assertGreaterThan(1, $senderNames->count());
        $this->assertGreaterThan(1, $senderEmails->count());
    }

    public function test_contact_message_seeder_can_run_multiple_times(): void
    {
        $this->seed(ContactMessageSeeder::class);
        $initialCount = ContactMessage::count();
        
        $this->seed(ContactMessageSeeder::class);
        
        // Should create new messages, not update existing ones
        $this->assertEquals($initialCount * 2, ContactMessage::count());
    }

    public function test_contact_message_seeder_creates_messages_with_professional_subjects(): void
    {
        $this->seed(ContactMessageSeeder::class);

        $messages = ContactMessage::all();
        $professionalKeywords = ['project', 'development', 'opportunity', 'collaboration', 'inquiry'];

        foreach ($messages as $message) {
            $subjectLower = strtolower($message->subject);
            $hasKeyword = false;
            
            foreach ($professionalKeywords as $keyword) {
                if (str_contains($subjectLower, $keyword)) {
                    $hasKeyword = true;
                    break;
                }
            }
            
            $this->assertTrue($hasKeyword, "Subject '{$message->subject}' should contain professional keywords");
        }
    }

    public function test_contact_message_seeder_scopes_work_correctly(): void
    {
        $this->seed(ContactMessageSeeder::class);

        $unreadCount = ContactMessage::unread()->count();
        $readCount = ContactMessage::read()->count();
        $totalCount = ContactMessage::count();

        $this->assertGreaterThan(0, $unreadCount);
        $this->assertGreaterThan(0, $readCount);
        $this->assertEquals($totalCount, $unreadCount + $readCount + ContactMessage::where('status', 'replied')->count());
    }
}
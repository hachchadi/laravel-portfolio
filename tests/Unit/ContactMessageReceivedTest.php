<?php

namespace Tests\Unit;

use App\Mail\ContactMessageReceived;
use App\Models\ContactMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactMessageReceivedTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_message_received_mail_can_be_built(): void
    {
        $contactMessage = ContactMessage::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'subject' => 'Test Subject',
            'message' => 'This is a test message.',
        ]);

        $mail = new ContactMessageReceived($contactMessage);

        $this->assertInstanceOf(ContactMessageReceived::class, $mail);
        $this->assertEquals($contactMessage, $mail->contactMessage);
    }

    public function test_contact_message_received_mail_has_correct_subject(): void
    {
        $contactMessage = ContactMessage::factory()->create([
            'subject' => 'Project Inquiry',
        ]);

        $mail = new ContactMessageReceived($contactMessage);
        $envelope = $mail->envelope();

        $this->assertEquals('New Contact Message: Project Inquiry', $envelope->subject);
    }

    public function test_contact_message_received_mail_has_reply_to(): void
    {
        $contactMessage = ContactMessage::factory()->create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
        ]);

        $mail = new ContactMessageReceived($contactMessage);
        $envelope = $mail->envelope();

        $this->assertNotEmpty($envelope->replyTo);
        // Check that the reply-to contains the contact's email
        $replyToEmails = array_keys($envelope->replyTo);
        $this->assertContains('jane@example.com', $replyToEmails);
    }

    public function test_contact_message_received_mail_uses_correct_template(): void
    {
        $contactMessage = ContactMessage::factory()->create();

        $mail = new ContactMessageReceived($contactMessage);
        $content = $mail->content();

        $this->assertEquals('emails.contact-message', $content->markdown);
        $this->assertArrayHasKey('contactMessage', $content->with);
        $this->assertEquals($contactMessage, $content->with['contactMessage']);
    }

    public function test_contact_message_received_mail_is_queued(): void
    {
        $contactMessage = ContactMessage::factory()->create();

        $mail = new ContactMessageReceived($contactMessage);

        $this->assertContains('Illuminate\Contracts\Queue\ShouldQueue', class_implements($mail));
    }

    public function test_contact_message_received_mail_uses_emails_queue(): void
    {
        $contactMessage = ContactMessage::factory()->create();

        $mail = new ContactMessageReceived($contactMessage);

        $this->assertEquals('emails', $mail->queue);
    }
}

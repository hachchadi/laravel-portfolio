<?php

namespace Tests\Unit;

use App\Jobs\SendContactMessageEmail;
use App\Mail\ContactMessageReceived;
use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class SendContactMessageEmailJobTest extends TestCase
{
    use RefreshDatabase;

    public function test_job_sends_email_to_developer(): void
    {
        Mail::fake();

        $user = User::factory()->create(['email' => 'developer@example.com']);
        $contactMessage = ContactMessage::factory()->create();

        $job = new SendContactMessageEmail($contactMessage);
        $job->handle();

        Mail::assertQueued(ContactMessageReceived::class, function ($mail) use ($contactMessage, $user) {
            return $mail->hasTo($user->email) && 
                   $mail->contactMessage->id === $contactMessage->id;
        });
    }

    public function test_job_uses_config_email_when_no_user_exists(): void
    {
        Mail::fake();
        
        config(['mail.from.address' => 'fallback@example.com']);
        
        $contactMessage = ContactMessage::factory()->create();

        $job = new SendContactMessageEmail($contactMessage);
        $job->handle();

        Mail::assertQueued(ContactMessageReceived::class, function ($mail) use ($contactMessage) {
            return $mail->hasTo('fallback@example.com') && 
                   $mail->contactMessage->id === $contactMessage->id;
        });
    }

    public function test_job_handles_successful_execution(): void
    {
        Mail::fake();

        $user = User::factory()->create(['email' => 'developer@example.com']);
        $contactMessage = ContactMessage::factory()->create();

        $job = new SendContactMessageEmail($contactMessage);
        
        // Should not throw any exceptions
        $this->assertNull($job->handle());
        
        Mail::assertQueued(ContactMessageReceived::class);
    }

    public function test_job_handles_no_email_configured(): void
    {
        Mail::fake();

        config(['mail.from.address' => null]);
        
        $contactMessage = ContactMessage::factory()->create();

        $job = new SendContactMessageEmail($contactMessage);
        $job->handle();

        Mail::assertNothingQueued();
    }

    public function test_job_handles_mail_exception(): void
    {
        $user = User::factory()->create(['email' => 'developer@example.com']);
        $contactMessage = ContactMessage::factory()->create();

        // Mock Mail facade to throw exception
        Mail::shouldReceive('to')->andThrow(new \Exception('Mail service unavailable'));

        $job = new SendContactMessageEmail($contactMessage);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Mail service unavailable');

        $job->handle();
    }

    public function test_job_is_queued_on_emails_queue(): void
    {
        $contactMessage = ContactMessage::factory()->create();

        $job = new SendContactMessageEmail($contactMessage);

        $this->assertEquals('emails', $job->queue);
    }

    public function test_job_has_correct_retry_configuration(): void
    {
        $contactMessage = ContactMessage::factory()->create();

        $job = new SendContactMessageEmail($contactMessage);

        $this->assertEquals(3, $job->tries);
        $this->assertEquals(60, $job->timeout);
    }

    public function test_job_has_failed_method(): void
    {
        $contactMessage = ContactMessage::factory()->create();
        $job = new SendContactMessageEmail($contactMessage);
        $exception = new \Exception('Permanent failure');

        // Should not throw any exceptions when calling failed method
        $this->assertNull($job->failed($exception));
    }
}

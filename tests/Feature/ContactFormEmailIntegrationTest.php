<?php

namespace Tests\Feature;

use App\Jobs\SendContactMessageEmail;
use App\Livewire\ContactForm;
use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Livewire\Livewire;
use Tests\TestCase;

class ContactFormEmailIntegrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_form_dispatches_email_job_on_successful_submission(): void
    {
        Queue::fake();

        $formData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'subject' => 'Test Subject',
            'message' => 'This is a test message that is long enough to pass validation.',
        ];

        Livewire::test(ContactForm::class)
            ->set('name', $formData['name'])
            ->set('email', $formData['email'])
            ->set('subject', $formData['subject'])
            ->set('message', $formData['message'])
            ->call('submit')
            ->assertSet('isSubmitted', true)
            ->assertSet('emailSent', true)
            ->assertSet('emailError', false);

        // Assert contact message was created
        $this->assertDatabaseHas('contact_messages', [
            'name' => $formData['name'],
            'email' => $formData['email'],
            'subject' => $formData['subject'],
            'message' => $formData['message'],
            'status' => 'unread',
        ]);

        // Assert email job was dispatched
        Queue::assertPushed(SendContactMessageEmail::class, function ($job) use ($formData) {
            return $job->contactMessage->name === $formData['name'] &&
                   $job->contactMessage->email === $formData['email'] &&
                   $job->queue === 'emails';
        });
    }

    public function test_contact_form_handles_email_job_dispatch_failure(): void
    {
        Queue::fake();
        
        // Mock Queue::push to throw an exception
        Queue::shouldReceive('push')->andThrow(new \Exception('Queue service unavailable'));

        $formData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'subject' => 'Test Subject',
            'message' => 'This is a test message that is long enough to pass validation.',
        ];

        Livewire::test(ContactForm::class)
            ->set('name', $formData['name'])
            ->set('email', $formData['email'])
            ->set('subject', $formData['subject'])
            ->set('message', $formData['message'])
            ->call('submit')
            ->assertSet('isSubmitted', true)
            ->assertSet('emailSent', false)
            ->assertSet('emailError', true);

        // Assert contact message was still created
        $this->assertDatabaseHas('contact_messages', [
            'name' => $formData['name'],
            'email' => $formData['email'],
            'subject' => $formData['subject'],
            'message' => $formData['message'],
            'status' => 'unread',
        ]);
    }

    public function test_contact_form_resets_email_status_on_form_reset(): void
    {
        Queue::fake();

        $formData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'subject' => 'Test Subject',
            'message' => 'This is a test message that is long enough to pass validation.',
        ];

        Livewire::test(ContactForm::class)
            ->set('name', $formData['name'])
            ->set('email', $formData['email'])
            ->set('subject', $formData['subject'])
            ->set('message', $formData['message'])
            ->call('submit')
            ->assertSet('isSubmitted', true)
            ->assertSet('emailSent', true)
            ->call('resetForm')
            ->assertSet('isSubmitted', false)
            ->assertSet('emailSent', false)
            ->assertSet('emailError', false);
    }

    public function test_email_job_processes_successfully_with_user(): void
    {
        Queue::fake();

        $user = User::factory()->create(['email' => 'developer@example.com']);
        $contactMessage = ContactMessage::factory()->create();

        SendContactMessageEmail::dispatch($contactMessage);

        Queue::assertPushed(SendContactMessageEmail::class, function ($job) use ($contactMessage) {
            return $job->contactMessage->id === $contactMessage->id;
        });
    }

    public function test_contact_form_shows_correct_success_message_when_email_sent(): void
    {
        Queue::fake();

        $formData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'subject' => 'Test Subject',
            'message' => 'This is a test message that is long enough to pass validation.',
        ];

        $component = Livewire::test(ContactForm::class)
            ->set('name', $formData['name'])
            ->set('email', $formData['email'])
            ->set('subject', $formData['subject'])
            ->set('message', $formData['message'])
            ->call('submit');

        $component->assertSet('emailSent', true);
        $component->assertSee('Your message has been saved and an email notification has been sent');
    }

    public function test_contact_form_shows_warning_message_when_email_fails(): void
    {
        Queue::fake();
        
        // Mock the dispatch to throw an exception
        Queue::shouldReceive('push')->andThrow(new \Exception('Queue service unavailable'));

        $formData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'subject' => 'Test Subject',
            'message' => 'This is a test message that is long enough to pass validation.',
        ];

        $component = Livewire::test(ContactForm::class)
            ->set('name', $formData['name'])
            ->set('email', $formData['email'])
            ->set('subject', $formData['subject'])
            ->set('message', $formData['message'])
            ->call('submit');

        $component->assertSet('emailError', true);
        $component->assertSee('there was an issue sending the email notification');
    }
}

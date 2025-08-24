<?php

namespace App\Livewire;

use App\Models\ContactMessage;
use Livewire\Component;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class ContactForm extends Component
{
    #[Validate('required|min:2|max:255')]
    public $name = '';

    #[Validate('required|email|max:255')]
    public $email = '';

    #[Validate('required|min:5|max:255')]
    public $subject = '';

    #[Validate('required|min:10|max:2000')]
    public $message = '';

    public $isSubmitting = false;
    public $isSubmitted = false;
    public $animateOnLoad = false;
    public $emailSent = false;
    public $emailError = false;

    protected $messages = [
        'name.required' => 'Please enter your name.',
        'name.min' => 'Your name must be at least 2 characters.',
        'name.max' => 'Your name cannot exceed 255 characters.',
        'email.required' => 'Please enter your email address.',
        'email.email' => 'Please enter a valid email address.',
        'email.max' => 'Your email cannot exceed 255 characters.',
        'subject.required' => 'Please enter a subject.',
        'subject.min' => 'The subject must be at least 5 characters.',
        'subject.max' => 'The subject cannot exceed 255 characters.',
        'message.required' => 'Please enter your message.',
        'message.min' => 'Your message must be at least 10 characters.',
        'message.max' => 'Your message cannot exceed 2000 characters.',
    ];

    public function mount()
    {
        $this->dispatch('contact-form-loaded');
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function submit()
    {
        // Check rate limiting
        $key = 'contact-form:' . request()->ip();
        
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            throw ValidationException::withMessages([
                'email' => "Too many contact attempts. Please try again in {$seconds} seconds."
            ]);
        }

        $this->isSubmitting = true;

        try {
            $this->validate();

            // Create contact message
            $contactMessage = ContactMessage::create([
                'name' => $this->name,
                'email' => $this->email,
                'subject' => $this->subject,
                'message' => $this->message,
                'status' => 'unread',
            ]);

            // Increment rate limiter
            RateLimiter::hit($key, 300); // 5 minutes

            // Dispatch job for email sending
            try {
                \App\Jobs\SendContactMessageEmail::dispatch($contactMessage);
                $this->emailSent = true;
                $this->emailError = false;
            } catch (\Exception $e) {
                $this->emailSent = false;
                $this->emailError = true;
                \Illuminate\Support\Facades\Log::error('Failed to dispatch contact email job', [
                    'error' => $e->getMessage(),
                    'contact_message_id' => $contactMessage->id
                ]);
            }

            // Reset form and show success
            $this->reset(['name', 'email', 'subject', 'message']);
            $this->isSubmitted = true;
            $this->isSubmitting = false;

            // Auto-hide success message after 5 seconds
            $this->dispatch('auto-hide-success');

        } catch (ValidationException $e) {
            $this->isSubmitting = false;
            throw $e;
        } catch (\Exception $e) {
            $this->isSubmitting = false;
            $this->addError('form', 'An error occurred while sending your message. Please try again.');
        }
    }

    public function resetForm()
    {
        $this->reset(['name', 'email', 'subject', 'message', 'isSubmitted', 'emailSent', 'emailError']);
        $this->resetErrorBag();
    }

    public function startAnimation()
    {
        $this->animateOnLoad = true;
    }

    public function render()
    {
        return view('livewire.contact-form');
    }
}

<?php

namespace App\Jobs;

use App\Mail\ContactMessageReceived;
use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendContactMessageEmail implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;

    /**
     * The number of times the job may be attempted.
     */
    public $tries = 3;

    /**
     * The maximum number of seconds the job can run.
     */
    public $timeout = 60;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public ContactMessage $contactMessage
    ) {
        $this->onQueue('emails');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Get the developer's email (first user or from config)
            $developerEmail = User::first()?->email ?? config('mail.from.address');
            
            if (!$developerEmail) {
                Log::error('No developer email found for contact message notification', [
                    'contact_message_id' => $this->contactMessage->id
                ]);
                return;
            }

            // Send email notification to developer
            Mail::to($developerEmail)->send(new ContactMessageReceived($this->contactMessage));

            Log::info('Contact message email sent successfully', [
                'contact_message_id' => $this->contactMessage->id,
                'to' => $developerEmail
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send contact message email', [
                'contact_message_id' => $this->contactMessage->id,
                'error' => $e->getMessage()
            ]);
            
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Contact message email job failed permanently', [
            'contact_message_id' => $this->contactMessage->id,
            'error' => $exception->getMessage()
        ]);
    }
}

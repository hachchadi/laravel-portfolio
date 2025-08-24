<x-mail::message>
# New Contact Message Received

You have received a new contact message through your portfolio website.

## Message Details

**From:** {{ $contactMessage->name }}  
**Email:** {{ $contactMessage->email }}  
**Subject:** {{ $contactMessage->subject }}  
**Received:** {{ $contactMessage->created_at->format('F j, Y \a\t g:i A') }}

## Message Content

{{ $contactMessage->message }}

---

<x-mail::button :url="config('app.url') . '/admin/contact-messages'" color="primary">
View in Admin Panel
</x-mail::button>

You can reply directly to this email to respond to {{ $contactMessage->name }}.

Best regards,<br>
{{ config('app.name') }} System
</x-mail::message>

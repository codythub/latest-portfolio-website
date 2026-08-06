<x-mail::message>
# New Contact Message

**Name:** {{ $contactMessage->name }}

**Email:** {{ $contactMessage->email }}

**Phone:** {{ $contactMessage->phone }}

@if ($contactMessage->company)
**Company:** {{ $contactMessage->company }}
@endif

**Message**

{{ $contactMessage->message }}

<x-mail::button :url="route('admin.contact-messages.show', $contactMessage)">
View Message
</x-mail::button>
</x-mail::message>

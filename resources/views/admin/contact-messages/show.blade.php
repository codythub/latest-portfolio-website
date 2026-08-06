@extends("layouts.admin")

@section("title", "Contact Message")

@section("content")
    <div class="-mt-3 mb-8">
        <a
            href="{{ route('admin.contact-messages.index') }}"
            class="inline-flex items-center border-b border-[#303136] pb-1 text-base font-bold text-[#303136] transition hover:text-[#08a9f4]"
        >
            <span class="mr-2 text-2xl leading-none" aria-hidden="true">&larr;</span>
            Back to messages
        </a>
    </div>

    <article class="rounded-[22px] border border-[#dedfe4] bg-white px-5 py-6 shadow-[0_18px_55px_rgba(17,18,22,0.04)] sm:px-8 lg:px-9 lg:py-8">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
            <div class="min-w-0">
                <div class="mb-4 flex flex-wrap gap-3">
                    <span
                        @class([
                            'inline-flex min-h-9 items-center rounded-full border px-4 text-base font-bold',
                            'border-[#dedfe4] text-[#747984]' => $contactMessage->is_read,
                            'border-[#a7dcff] text-[#149cf2]' => ! $contactMessage->is_read,
                        ])
                    >
                        {{ $contactMessage->is_read ? 'Read' : 'Unread' }}
                    </span>

                    @if ($contactMessage->is_archived)
                        <span class="inline-flex min-h-9 items-center rounded-full border border-[#dedfe4] px-4 text-base font-bold text-[#747984]">
                            Archived
                        </span>
                    @endif

                    @if ($contactMessage->company)
                        <span class="inline-flex min-h-9 items-center rounded-full border border-[#dedfe4] px-4 text-base font-bold text-[#747984]">
                            {{ $contactMessage->company }}
                        </span>
                    @endif
                </div>

                <h2 class="font-syne text-[30px] font-bold leading-tight text-[#111216]">
                    {{ $contactMessage->name }}
                </h2>

                <p class="mt-2 break-all text-[17px] font-medium text-[#747984]">
                    {{ $contactMessage->email }}
                </p>
            </div>

            <div class="flex flex-wrap gap-3 self-end lg:self-auto">
                <form method="POST" action="{{ $contactMessage->is_read ? route('admin.contact-messages.unread', $contactMessage) : route('admin.contact-messages.read', $contactMessage) }}">
                    @csrf
                    @method('PATCH')

                    <button
                        type="submit"
                        aria-label="{{ $contactMessage->is_read ? 'Mark unread' : 'Mark read' }}"
                        class="inline-flex h-12 w-12 items-center justify-center rounded-full border border-[#dedfe4] bg-white text-[#747984] transition hover:border-[#191a1e] hover:text-[#191a1e]"
                    >
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4 6h16v12H4V6Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                            <path d="m4 7 8 6 8-6" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </form>

                <form method="POST" action="{{ $contactMessage->is_archived ? route('admin.contact-messages.unarchive', $contactMessage) : route('admin.contact-messages.archive', $contactMessage) }}">
                    @csrf
                    @method('PATCH')

                    <button
                        type="submit"
                        aria-label="{{ $contactMessage->is_archived ? 'Unarchive message' : 'Archive message' }}"
                        class="inline-flex h-12 w-12 items-center justify-center rounded-full border border-[#dedfe4] bg-white text-[#747984] transition hover:border-[#191a1e] hover:text-[#191a1e]"
                    >
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
                            <path d="M5 7h14l-1 13H6L5 7ZM8 7l1-3h6l1 3" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                            <path d="M9 11h6" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </button>
                </form>

                <form
                    method="POST"
                    action="{{ route('admin.contact-messages.destroy', $contactMessage) }}"
                    onsubmit="return confirm('Are you sure you want to delete this message?')"
                >
                    @csrf
                    @method('DELETE')

                    <button
                        type="submit"
                        aria-label="Delete message from {{ $contactMessage->name }}"
                        class="inline-flex h-12 w-12 items-center justify-center rounded-full border border-[#dedfe4] bg-white transition hover:border-[#ef233c] hover:bg-[#fff0f2]"
                    >
                        <img
                            src="{{ asset('images/icons/admin-delete.svg') }}"
                            alt=""
                            aria-hidden="true"
                            class="h-5 w-5"
                        >
                    </button>
                </form>
            </div>
        </div>

        <dl class="mt-8 grid gap-5 rounded-[18px] bg-[#f4f5f7] px-5 py-5 text-base sm:grid-cols-3">
            <div class="min-w-0">
                <dt class="font-bold text-[#8b9099]">Received</dt>
                <dd class="mt-1 break-words text-[17px] font-bold leading-6 text-[#191a1e]">
                    {{ $contactMessage->created_at->format('d M Y, H:i') }}
                </dd>
            </div>

            <div class="min-w-0">
                <dt class="font-bold text-[#8b9099]">Phone</dt>
                <dd class="mt-1 break-words text-[17px] font-bold leading-6 text-[#191a1e]">
                    {{ $contactMessage->phone ?: '—' }}
                </dd>
            </div>

            <div class="min-w-0">
                <dt class="font-bold text-[#8b9099]">Company</dt>
                <dd class="mt-1 break-words text-[17px] font-bold leading-6 text-[#191a1e]">
                    {{ $contactMessage->company ?: '—' }}
                </dd>
            </div>
        </dl>

        <div class="mt-8 whitespace-pre-line text-[18px] font-medium leading-8 text-[#191a1e]">
            {{ $contactMessage->message }}
        </div>

        <a
            href="mailto:{{ rawurlencode($contactMessage->email) }}?subject={{ rawurlencode('Re: Portfolio enquiry') }}"
            class="mt-9 inline-flex min-h-14 items-center justify-center gap-3 rounded-full bg-[#191a1e] px-7 text-[17px] font-bold text-white transition hover:bg-[#303136]"
        >
            <span class="text-2xl leading-none" aria-hidden="true">&larr;</span>
            Reply by email
        </a>
    </article>
@endsection

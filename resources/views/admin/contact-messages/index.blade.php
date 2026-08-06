@extends("layouts.admin")

@section("title", "Contact Messages")

@section("content")
    @php
        $selectedMessage = $messages->firstWhere('id', (int) request('message')) ?? $messages->first();
        $filterLabels = [
            'inbox' => 'Inbox',
            'unread' => 'Unread',
            'archived' => 'Archived',
        ];
    @endphp

    <p class="-mt-4 mb-10 text-[18px] font-medium leading-7 text-[#747984]">
        {{ $unreadCount }}
        {{ Str::plural('unread message', $unreadCount) }}
        in your inbox.
    </p>

    <div class="mb-8 flex flex-col gap-4 xl:flex-row xl:items-center">
        <form
            method="GET"
            action="{{ route('admin.contact-messages.index') }}"
            class="min-w-0 flex-1"
        >
            <input type="hidden" name="filter" value="{{ $filter }}">

            <label class="flex min-h-14 items-center rounded-full border border-[#dedfe4] bg-white px-5 shadow-[0_18px_55px_rgba(17,18,22,0.03)]">
                <svg class="mr-4 h-5 w-5 shrink-0 text-[#747984]" viewBox="0 0 24 24" fill="none" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10.5 18a7.5 7.5 0 1 1 5.3-12.8A7.5 7.5 0 0 1 10.5 18ZM16 16l4 4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>

                <span class="sr-only">Search contact messages</span>
                <input
                    type="search"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Search by name, email or content"
                    class="h-12 min-w-0 flex-1 border-0 bg-transparent p-0 text-[17px] font-medium text-[#191a1e] placeholder:text-[#8b9099] focus:ring-0"
                >
            </label>
        </form>

        <nav class="flex flex-wrap gap-3">
            @foreach ($filterLabels as $filterKey => $filterLabel)
                <a
                    href="{{ route('admin.contact-messages.index', array_filter(['filter' => $filterKey, 'search' => $search])) }}"
                    @class([
                        'inline-flex min-h-14 items-center justify-center rounded-full px-6 text-[17px] font-bold transition',
                        'bg-[#191a1e] text-white' => $filter === $filterKey,
                        'border border-[#dedfe4] bg-white text-[#747984] hover:border-[#191a1e] hover:text-[#191a1e]' => $filter !== $filterKey,
                    ])
                >
                    {{ $filterLabel }}
                </a>
            @endforeach
        </nav>
    </div>

    @if ($messages->isEmpty())
        <div class="rounded-[22px] border border-dashed border-[#dedfe4] bg-white px-6 py-10 text-center shadow-[0_18px_55px_rgba(17,18,22,0.04)]">
            <p class="text-base font-medium text-[#747984]">No contact messages found.</p>
        </div>
    @else
        <div class="grid gap-7 xl:grid-cols-[minmax(0,0.95fr)_minmax(520px,1.35fr)]">
            <section class="space-y-4">
                @foreach ($messages as $message)
                    @php
                        $isSelected = $selectedMessage?->id === $message->id;
                    @endphp

                    <article
                        @class([
                            'relative rounded-[22px] border bg-white px-5 py-5 transition shadow-[0_18px_55px_rgba(17,18,22,0.03)]',
                            'border-[#54b9ff] bg-[#f5f6f8]' => $isSelected,
                            'border-[#dedfe4] hover:border-[#54b9ff]' => ! $isSelected,
                        ])
                    >
                        <a
                            href="{{ route('admin.contact-messages.index', array_filter([
                                'filter' => $filter,
                                'search' => $search,
                                'message' => $message->id,
                            ])) }}"
                            class="absolute inset-0 hidden rounded-[22px] lg:block"
                            aria-current="{{ $isSelected ? 'page' : 'false' }}"
                        >
                            <span class="sr-only">Select message from {{ $message->name }}</span>
                        </a>

                        <a
                            href="{{ route('admin.contact-messages.show', $message) }}"
                            class="absolute inset-0 rounded-[22px] lg:hidden"
                        >
                            <span class="sr-only">Open message from {{ $message->name }}</span>
                        </a>

                        <div>
                            <div class="flex items-center gap-3">
                                @unless ($message->is_read)
                                    <span class="h-2.5 w-2.5 shrink-0 rounded-full bg-[#149cf2]" aria-label="Unread"></span>
                                @endunless

                                <h2 class="font-syne text-[18px] font-bold leading-tight text-[#111216]">
                                    {{ $message->name }}
                                </h2>
                            </div>

                            <p class="mt-2 truncate text-base font-medium text-[#747984]">
                                {{ $message->email }}
                            </p>

                            <p class="mt-4 text-[17px] font-medium leading-7 text-[#747984]">
                                {{ Str::limit($message->message, 135) }}
                            </p>

                            <p class="mt-4 text-base font-medium text-[#747984]">
                                {{ $message->created_at->format('d M Y, H:i') }}
                            </p>
                        </div>
                    </article>
                @endforeach

                <div class="pt-4">
                    {{ $messages->links() }}
                </div>
            </section>

            <section class="hidden xl:block">
                @if ($selectedMessage)
                    <article class="min-h-[540px] rounded-[22px] border border-[#dedfe4] bg-white px-8 py-8 shadow-[0_18px_55px_rgba(17,18,22,0.04)]">
                        <div class="flex items-start justify-between gap-6">
                            <div class="min-w-0">
                                <h2 class="font-syne text-[26px] font-bold leading-tight text-[#111216]">
                                    {{ $selectedMessage->name }}
                                </h2>

                                <p class="mt-2 truncate text-[17px] font-medium text-[#747984]">
                                    {{ $selectedMessage->email }}
                                </p>

                                <div class="mt-4 flex flex-wrap gap-3">
                                    @if ($selectedMessage->company)
                                        <span class="inline-flex min-h-9 items-center rounded-full border border-[#dedfe4] bg-white px-4 text-base font-bold text-[#747984]">
                                            {{ $selectedMessage->company }}
                                        </span>
                                    @else
                                        <span class="inline-flex min-h-9 items-center rounded-full border border-[#dedfe4] bg-white px-4 text-base font-bold text-[#747984]">
                                            No company
                                        </span>
                                    @endif

                                    @unless ($selectedMessage->is_read)
                                        <span class="inline-flex min-h-9 items-center rounded-full border border-[#a7dcff] bg-white px-4 text-base font-bold text-[#149cf2]">
                                            Unread
                                        </span>
                                    @endunless

                                    @if ($selectedMessage->is_archived)
                                        <span class="inline-flex min-h-9 items-center rounded-full border border-[#dedfe4] bg-white px-4 text-base font-bold text-[#747984]">
                                            Archived
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="flex shrink-0 items-center gap-3">
                                <form method="POST" action="{{ $selectedMessage->is_read ? route('admin.contact-messages.unread', $selectedMessage) : route('admin.contact-messages.read', $selectedMessage) }}">
                                    @csrf
                                    @method('PATCH')

                                    <button
                                        type="submit"
                                        aria-label="{{ $selectedMessage->is_read ? 'Mark unread' : 'Mark read' }}"
                                        class="inline-flex h-12 w-12 items-center justify-center rounded-full border border-[#dedfe4] bg-white text-[#747984] transition hover:border-[#191a1e] hover:text-[#191a1e]"
                                    >
                                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M4 6h16v12H4V6Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                            <path d="m4 7 8 6 8-6" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                        </svg>
                                    </button>
                                </form>

                                <form method="POST" action="{{ $selectedMessage->is_archived ? route('admin.contact-messages.unarchive', $selectedMessage) : route('admin.contact-messages.archive', $selectedMessage) }}">
                                    @csrf
                                    @method('PATCH')

                                    <button
                                        type="submit"
                                        aria-label="{{ $selectedMessage->is_archived ? 'Unarchive message' : 'Archive message' }}"
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
                                    action="{{ route('admin.contact-messages.destroy', $selectedMessage) }}"
                                    onsubmit="return confirm('Are you sure you want to delete this message?')"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        aria-label="Delete message from {{ $selectedMessage->name }}"
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

                        <dl class="mt-8 grid gap-5 rounded-[18px] bg-[#f4f5f7] px-5 py-5 sm:grid-cols-3">
                            <div class="min-w-0">
                                <dt class="text-base font-bold text-[#8b9099]">Received</dt>
                                <dd class="mt-1 break-words text-[17px] font-bold leading-6 text-[#191a1e]">
                                    {{ $selectedMessage->created_at->format('d M Y, H:i') }}
                                </dd>
                            </div>

                            <div class="min-w-0">
                                <dt class="text-base font-bold text-[#8b9099]">Phone</dt>
                                <dd class="mt-1 break-words text-[17px] font-bold leading-6 text-[#191a1e]">
                                    {{ $selectedMessage->phone ?: '—' }}
                                </dd>
                            </div>

                            <div class="min-w-0">
                                <dt class="text-base font-bold text-[#8b9099]">Company</dt>
                                <dd class="mt-1 break-words text-[17px] font-bold leading-6 text-[#191a1e]">
                                    {{ $selectedMessage->company ?: '—' }}
                                </dd>
                            </div>
                        </dl>

                        <div class="mt-8 whitespace-pre-line text-[18px] font-medium leading-8 text-[#191a1e]">
                            {{ $selectedMessage->message }}
                        </div>

                        <a
                            href="mailto:{{ rawurlencode($selectedMessage->email) }}?subject={{ rawurlencode('Re: Portfolio enquiry') }}"
                            class="mt-9 inline-flex min-h-14 items-center justify-center gap-3 rounded-full bg-[#191a1e] px-7 text-[17px] font-bold text-white transition hover:bg-[#303136]"
                        >
                            <span class="text-2xl leading-none" aria-hidden="true">&larr;</span>
                            Reply by email
                        </a>
                    </article>
                @endif
            </section>
        </div>
    @endif
@endsection

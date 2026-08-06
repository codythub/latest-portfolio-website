<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="lg:h-full lg:overflow-hidden">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <title>@yield('title', 'Admin') - Lanre Malumi</title>
</head>

<body class="admin-interface overflow-x-hidden bg-[#fbfbfc] font-inter font-normal text-[#191a1e] antialiased lg:h-screen lg:overflow-hidden">
    @php
        $adminUnreadContactMessages = \Illuminate\Support\Facades\Schema::hasTable('contact_messages')
            ? \App\Models\ContactMessage::query()
                ->where('is_archived', false)
                ->where('is_read', false)
                ->latest()
                ->limit(5)
                ->get()
            : collect();

        $adminUnreadContactCount = \Illuminate\Support\Facades\Schema::hasTable('contact_messages')
            ? \App\Models\ContactMessage::query()
                ->where('is_archived', false)
                ->where('is_read', false)
                ->count()
            : 0;
    @endphp

    @include('admin.partials.toasts')

    <div
        x-data="{ sidebarOpen: false }"
        x-effect="document.documentElement.classList.toggle('overflow-hidden', sidebarOpen); document.body.classList.toggle('overflow-hidden', sidebarOpen)"
        class="min-h-screen lg:fixed lg:inset-0 lg:grid lg:h-screen lg:grid-cols-[260px_minmax(0,1fr)] lg:overflow-hidden"
    >
        <div
            x-show="sidebarOpen"
            x-transition.opacity
            class="fixed inset-0 z-40 bg-black/30 lg:hidden"
            @click="sidebarOpen = false"
        ></div>

        @include('admin.partials.sidebar')

        <div class="min-w-0 lg:flex lg:h-screen lg:min-h-0 lg:overflow-hidden lg:flex-col">
            <header class="sticky top-0 z-30 flex h-16 shrink-0 items-center justify-between border-b border-[#e3e4e8] bg-[#fbfbfc]/95 px-5 backdrop-blur sm:px-8 lg:static lg:justify-end lg:border-b-0 lg:px-10 lg:pt-8">
                <button
                    type="button"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-[#e1e3e8] text-[#747984] lg:hidden"
                    aria-label="Open admin navigation"
                    @click="sidebarOpen = true"
                >
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 7H20M4 12H20M4 17H20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </button>

                <div class="ml-auto flex items-center gap-2 sm:gap-3">
                    <div
                        x-data="{ open: false }"
                        class="relative order-2 lg:order-1"
                    >
                        <button
                            type="button"
                            aria-label="Unread contact messages"
                            class="relative inline-flex h-10 w-10 items-center justify-center rounded-full text-[#747984] transition hover:bg-[#f0f1f4]"
                            @click="open = ! open"
                        >
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
                                <path d="M18 9a6 6 0 0 0-12 0c0 7-3 7-3 7h18s-3 0-3-7ZM13.7 20a2 2 0 0 1-3.4 0" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>

                            @if ($adminUnreadContactCount > 0)
                                <span class="absolute -right-1 -top-1 inline-flex min-h-5 min-w-5 items-center justify-center rounded-full bg-[#ef233c] px-1.5 text-xs font-bold text-white">
                                    {{ $adminUnreadContactCount }}
                                </span>
                            @endif
                        </button>

                        <div
                            x-show="open"
                            x-transition.origin.top.right
                            @click.outside="open = false"
                            class="absolute right-0 z-40 mt-3 w-[min(360px,calc(100vw-2rem))] rounded-[18px] border border-[#dedfe4] bg-white p-3 shadow-[0_18px_55px_rgba(17,18,22,0.12)]"
                            style="display: none;"
                        >
                            <div class="border-b border-[#e3e4e8] px-2 pb-3">
                                <p class="font-syne text-lg font-bold text-[#111216]">Unread messages</p>
                            </div>

                            <div class="max-h-[320px] overflow-y-auto py-2">
                                @forelse ($adminUnreadContactMessages as $message)
                                    <a
                                        href="{{ route('admin.contact-messages.show', $message) }}"
                                        class="block rounded-[12px] px-3 py-3 transition hover:bg-[#f4f5f7]"
                                    >
                                        <div class="flex items-center justify-between gap-3">
                                            <p class="truncate text-base font-bold text-[#191a1e]">{{ $message->name }}</p>
                                            <span class="shrink-0 text-xs font-bold text-[#8b9099]">{{ $message->created_at->diffForHumans() }}</span>
                                        </div>

                                        <p class="mt-1 text-sm font-medium leading-5 text-[#747984]">
                                            {{ \Illuminate\Support\Str::limit($message->message, 90) }}
                                        </p>
                                    </a>
                                @empty
                                    <p class="px-3 py-5 text-sm font-medium text-[#747984]">
                                        No unread contact messages.
                                    </p>
                                @endforelse
                            </div>

                            <a
                                href="{{ route('admin.contact-messages.index') }}"
                                class="mt-2 flex min-h-10 items-center justify-center rounded-full bg-[#191a1e] px-4 text-sm font-bold text-white transition hover:bg-[#303136]"
                            >
                                View all messages
                            </a>
                        </div>
                    </div>

                    <button
                        type="button"
                        aria-label="Toggle dark mode"
                        class="order-1 inline-flex h-10 w-10 items-center justify-center rounded-full text-[#747984] transition hover:bg-[#f0f1f4] lg:order-2"
                    >
                        <img
                            src="{{ asset('images/icons/dark-mode.svg') }}"
                            alt=""
                            aria-hidden="true"
                            class="h-7 w-7 brightness-0 opacity-60"
                        >
                    </button>
                </div>
            </header>

            <main
                data-admin-main
                class="overflow-x-hidden px-5 pb-14 pt-8 sm:px-8 lg:min-h-0 lg:flex-1 lg:overflow-y-auto lg:overscroll-contain lg:px-14 lg:pb-16 lg:pt-10"
            >
                <div class="mx-auto max-w-[1500px]">
                    @hasSection('title')
                        <div class="mb-8">
                            <p class="mb-5 text-base font-medium text-[#747984]">
                                Admin
                                <span class="mx-2 text-[#b0b3bb]" aria-hidden="true">&gt;</span>
                                <span class="text-[#191a1e]">@yield('title')</span>
                            </p>

                            <h1 class="font-syne text-[34px] font-bold leading-tight tracking-normal text-[#111216] sm:text-[44px]">
                                @yield('title')
                            </h1>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>

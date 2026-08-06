@php
    $contactUnreadCount = \Illuminate\Support\Facades\Schema::hasTable('contact_messages')
        ? \App\Models\ContactMessage::query()
            ->where('is_archived', false)
            ->where('is_read', false)
            ->count()
        : 0;

    $links = [
        [
            'label' => 'Dashboard',
            'route' => 'admin.dashboard',
            'active' => request()->routeIs('admin.dashboard'),
            'icon' => 'dashboard',
        ],
        [
            'label' => 'Projects',
            'route' => 'admin.projects.index',
            'active' => request()->routeIs('admin.projects.*'),
            'icon' => 'projects',
        ],
        [
            'label' => 'Posts',
            'route' => 'admin.posts.index',
            'active' => request()->routeIs('admin.posts.*'),
            'icon' => 'posts',
        ],
        [
            'label' => 'Categories',
            'route' => 'admin.categories.index',
            'active' => request()->routeIs('admin.categories.*'),
            'icon' => 'categories',
        ],
        [
            'label' => 'Expertise',
            'route' => 'admin.expertise.index',
            'active' => request()->routeIs('admin.expertise.*'),
            'icon' => 'expertise',
        ],
        [
            'label' => 'Profile',
            'route' => 'profile.edit',
            'active' => request()->routeIs('profile.*'),
            'icon' => 'profile',
        ],
        [
            'label' => 'Contact Messages',
            'route' => 'admin.contact-messages.index',
            'active' => request()->routeIs('admin.contact-messages.*'),
            'icon' => 'messages',
            'badge' => $contactUnreadCount,
        ],
    ];
@endphp

<aside
    class="fixed inset-y-0 left-0 z-50 flex h-[100dvh] max-h-[100dvh] w-[260px] -translate-x-full flex-col overflow-hidden border-r border-[#e3e4e8] bg-white px-4 py-6 transition duration-200 lg:static lg:h-screen lg:max-h-screen lg:min-h-0 lg:translate-x-0"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
>
    <div class="mb-10 flex shrink-0 items-center gap-3 px-1">
        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-[#191a1e] font-syne text-base font-bold text-white">
            LM
        </div>

        <div>
            <p class="text-base font-bold text-[#191a1e]">Lanre Malumi</p>
            <p class="text-base font-medium text-[#7a7f89]">Portfolio admin</p>
        </div>
    </div>

    <nav class="min-h-0 flex-1 space-y-2 overflow-y-auto overscroll-contain pr-1">
        @foreach ($links as $link)
            @php
                $active = $link['active'] ?? false;
                $disabled = $link['disabled'] ?? false;
                $itemClass = $active
                    ? 'bg-[#191a1e] text-white'
                    : 'text-[#747984] hover:bg-[#f4f5f7] hover:text-[#191a1e]';
            @endphp

            @if ($disabled)
                <span class="flex h-12 items-center justify-between rounded-full px-5 text-base font-medium text-[#747984] opacity-70">
                    <span class="flex items-center gap-3">
                        @include('admin.partials.sidebar-icon', ['name' => $link['icon']])
                        {{ $link['label'] }}
                    </span>

                    @if (! empty($link['badge']))
                        <span class="rounded-full bg-[#ffe7eb] px-3 py-1 text-sm font-bold text-[#ef233c]">
                            {{ $link['badge'] }}
                        </span>
                    @endif
                </span>
            @else
                <a
                    href="{{ route($link['route']) }}"
                    class="flex h-12 items-center justify-between gap-3 rounded-full px-5 text-base font-bold transition {{ $itemClass }}"
                >
                    <span class="flex items-center gap-3">
                        @include('admin.partials.sidebar-icon', ['name' => $link['icon']])
                        {{ $link['label'] }}
                    </span>

                    @if (! empty($link['badge']))
                        <span
                            @class([
                                'rounded-full px-3 py-1 text-sm font-bold',
                                'bg-white/15 text-white' => $active,
                                'bg-[#ffe7eb] text-[#ef233c]' => ! $active,
                            ])
                        >
                            {{ $link['badge'] }}
                        </span>
                    @endif
                </a>
            @endif
        @endforeach
    </nav>

    <div class="mt-8 shrink-0 border-t border-[#e3e4e8] pt-6">
        <a
            href="{{ route('admin.site-settings.edit') }}"
            class="flex h-12 items-center gap-3 rounded-full px-5 text-base font-bold transition {{ request()->routeIs('admin.site-settings.*') ? 'bg-[#191a1e] text-white' : 'text-[#747984] hover:bg-[#f4f5f7] hover:text-[#191a1e]' }}"
        >
            @include('admin.partials.sidebar-icon', ['name' => 'settings'])
            Site Settings
        </a>

        <a
            href="{{ route('home') }}"
            class="flex h-12 items-center gap-3 rounded-full px-5 text-base font-medium text-[#747984] transition hover:bg-[#f4f5f7] hover:text-[#191a1e]"
        >
            @include('admin.partials.sidebar-icon', ['name' => 'external'])
            View Portfolio
        </a>

        <form method="POST" action="{{ route('logout') }}" class="mt-2">
            @csrf

            <button
                type="submit"
                class="flex h-12 w-full items-center gap-3 rounded-full px-5 text-left text-base font-medium text-[#ef233c] transition hover:bg-[#fff0f2]"
            >
                @include('admin.partials.sidebar-icon', ['name' => 'logout'])
                Logout
            </button>
        </form>
    </div>
</aside>

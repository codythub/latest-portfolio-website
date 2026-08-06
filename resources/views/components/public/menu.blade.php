@props([
    'buttonClass' => 'flex h-10 w-10 items-center justify-center rounded-full bg-white text-[#2f3035] shadow-sm',
    'size' => 'md',
])

@php
    $menuId = 'public-menu-' . uniqid();
    $iconBoxClass = $size === 'lg' ? 'h-4 w-[25px]' : 'h-3 w-[19px]';
    $topOpenClass = $size === 'lg' ? 'translate-y-[7px]' : 'translate-y-[5px]';
    $bottomOpenClass = $size === 'lg' ? '-translate-y-[7px]' : '-translate-y-[5px]';
    $links = [
        ['key' => 'work', 'label' => 'Work', 'route' => route('home')],
        ['key' => 'blog', 'label' => 'Blog', 'route' => route('home', ['type' => 'blog'])],
        ['key' => 'about', 'label' => 'About', 'route' => route('about')],
        ['key' => 'contact', 'label' => 'Get in Touch', 'route' => route('contact')],
    ];

    $active = match (true) {
        request()->routeIs('about') => 'about',
        request()->routeIs('contact') => 'contact',
        request()->routeIs('blog.*') => 'blog',
        request()->routeIs('home') && request('type') === 'blog' => 'blog',
        default => 'work',
    };
@endphp

<div
    x-data="{
        menuOpen: false,
        menuLinksVisible: false,
        prefersReducedMotion: window.matchMedia('(prefers-reduced-motion: reduce)').matches,
        openMenu() {
            this.menuOpen = true;
            this.menuLinksVisible = false;
            this.$nextTick(() => {
                requestAnimationFrame(() => {
                    this.menuLinksVisible = true;
                });
            });
        },
        closeMenu() {
            this.menuLinksVisible = false;
            this.menuOpen = false;
        },
        toggleMenu() {
            this.menuOpen ? this.closeMenu() : this.openMenu();
        },
    }"
    x-effect="document.documentElement.classList.toggle('overflow-hidden', menuOpen); document.body.classList.toggle('overflow-hidden', menuOpen)"
    @keydown.escape.window="closeMenu()"
>
    <button
        type="button"
        :aria-label="menuOpen ? 'Close menu' : 'Open menu'"
        aria-controls="{{ $menuId }}"
        :aria-expanded="menuOpen.toString()"
        class="{{ $buttonClass }} relative z-[90] transition-colors duration-200 motion-reduce:transition-none"
        :style="menuOpen ? 'background-color: transparent; box-shadow: none; color: #ffffff;' : ''"
        @click="toggleMenu()"
    >
        <span class="relative block {{ $iconBoxClass }}" aria-hidden="true">
            <span
                class="public-menu-line absolute left-0 top-0 h-[2px] w-full rounded-full transition duration-300 ease-out motion-reduce:transition-none"
                :class="menuOpen ? '{{ $topOpenClass }} rotate-45' : 'translate-y-0 rotate-0'"
            ></span>
            <span
                class="public-menu-line absolute left-0 top-1/2 h-[2px] w-full -translate-y-1/2 rounded-full transition duration-200 ease-out motion-reduce:transition-none"
                :class="menuOpen ? 'opacity-0 scale-x-0' : 'opacity-100 scale-x-100'"
            ></span>
            <span
                class="public-menu-line absolute bottom-0 left-0 h-[2px] w-full rounded-full transition duration-300 ease-out motion-reduce:transition-none"
                :class="menuOpen ? '{{ $bottomOpenClass }} -rotate-45' : 'translate-y-0 rotate-0'"
            ></span>
        </span>
    </button>

    <template x-teleport="body">
        <div
            id="{{ $menuId }}"
            x-show="menuOpen"
            x-transition:enter="motion-safe:transition motion-safe:duration-200 motion-safe:ease-out"
            x-transition:enter-start="motion-safe:opacity-0"
            x-transition:enter-end="motion-safe:opacity-100"
            x-transition:leave="motion-safe:transition motion-safe:delay-[280ms] motion-safe:duration-200 motion-safe:ease-in"
            x-transition:leave-start="motion-safe:opacity-100"
            x-transition:leave-end="motion-safe:opacity-0"
            class="fixed inset-0 z-[100] bg-[#28282b] text-white"
            role="dialog"
            aria-modal="true"
            aria-label="Site navigation"
            style="display: none;"
        >
            <button
                type="button"
                aria-label="Close menu"
                class="absolute right-8 top-10 flex h-12 w-12 items-center justify-center text-white sm:right-[220px] sm:top-[86px] sm:h-[78px] sm:w-[78px]"
                @click="closeMenu()"
            >
                <img
                    src="{{ asset('images/icons/menu-close.svg') }}"
                    alt=""
                    aria-hidden="true"
                    class="h-8 w-8 sm:h-[62px] sm:w-[62px]"
                >
            </button>

            <nav class="flex min-h-[100dvh] flex-col items-center px-8 pt-[142px] text-center sm:pt-[230px]">
                @foreach ($links as $index => $link)
                    @php
                        $openDelay = [
                            'delay-[80ms]',
                            'delay-[150ms]',
                            'delay-[220ms]',
                            'delay-[290ms]',
                        ][$index];
                        $closeDelay = [
                            'delay-[210ms]',
                            'delay-[140ms]',
                            'delay-[70ms]',
                            'delay-0',
                        ][$index];
                    @endphp

                    <a
                        href="{{ $link['route'] }}"
                        class="font-syne text-[38px] font-bold leading-[1.65] tracking-normal text-white transition duration-300 ease-out motion-reduce:translate-y-0 motion-reduce:transition-none sm:text-[48px] sm:leading-[1.75]"
                        :class="menuLinksVisible ? 'opacity-100 translate-y-0 {{ $openDelay }}' : 'opacity-0 translate-y-3 {{ $closeDelay }}'"
                        @if ($active === $link['key']) aria-current="page" @endif
                        @click="closeMenu()"
                    >
                        @if ($active === $link['key'])
                            <span class="public-active-dot mr-3 inline-block h-2.5 w-2.5 rounded-full bg-white align-middle sm:h-3 sm:w-3" aria-hidden="true"></span>
                        @endif
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </nav>
        </div>
    </template>
</div>

<!DOCTYPE html>
<html lang="en">
<head>
    @php
        $siteSettings = \App\Models\SiteSetting::current();
        $siteHeroBackgroundImage = $siteSettings->heroBackgroundUrl();
    @endphp

    <x-public.head :settings="$siteSettings" :title="$siteSettings->default_seo_title" />
</head>

<body class="bg-[#f7f7f8] font-inter font-normal text-[#33343a] antialiased">
    <div class="min-h-screen">
        <header
            class="site-hero relative h-[350px] overflow-hidden bg-[#020815] text-white"
            style="--site-hero-image: url('{{ $siteHeroBackgroundImage }}');"
        >
            <nav class="relative z-10 mx-auto flex w-full max-w-[1320px] items-center justify-end px-6 pt-12 sm:px-16 sm:pt-[72px]">
                <div class="flex items-center gap-4">
                    {{-- Placeholder: site search is visual only until backend search is added. --}}
                    <label class="hidden h-12 items-center rounded-full bg-white px-5 text-[#757982] shadow-sm sm:flex">
                        <img
                            src="{{ asset('images/icons/search.svg') }}"
                            alt=""
                            aria-hidden="true"
                            class="mr-3 h-5 w-5"
                        >
                        <span class="sr-only">Search site</span>
                        <input
                            type="search"
                            placeholder="Search site"
                            class="h-full w-40 border-0 bg-transparent p-0 text-base font-medium text-[#34363b] placeholder:text-[#9a9da5] focus:ring-0"
                        >
                    </label>

                    <button
                        type="button"
                        aria-label="Search site"
                        class="flex h-9 w-9 items-center justify-center rounded-full bg-white/15 shadow-sm backdrop-blur sm:hidden"
                    >
                        <img
                            src="{{ asset('images/icons/search.svg') }}"
                            alt=""
                            aria-hidden="true"
                            class="h-5 w-5 invert"
                        >
                    </button>

                    {{-- Placeholder: dark-mode behaviour is not wired yet. --}}
                    <button
                        type="button"
                        aria-label="Toggle dark mode"
                        data-public-theme-toggle
                        aria-pressed="false"
                        class="flex h-10 w-10 items-center justify-center rounded-full transition hover:bg-white/10 sm:h-12 sm:w-12"
                    >
                        <img
                            src="{{ asset('images/icons/dark-mode.svg') }}"
                            alt=""
                            aria-hidden="true"
                            class="public-theme-icon public-theme-icon-moon h-8 w-8"
                        >
                        <img
                            src="{{ asset('images/icons/sun.svg') }}"
                            alt=""
                            aria-hidden="true"
                            class="public-theme-icon public-theme-icon-sun h-8 w-8"
                        >
                    </button>

                    <x-public.menu button-class="flex h-12 w-12 items-center justify-center rounded-full bg-white text-[#2f3035] shadow-sm" />
                </div>
            </nav>

            <div class="relative z-10 mx-auto flex max-w-[1320px] flex-col items-center px-6 pb-8 pt-12 text-center sm:block sm:px-16 sm:pb-10 sm:pt-10 sm:text-left">
                <h1 class="font-syne text-[34px] font-bold leading-tight tracking-normal text-white sm:text-[48px]">
                    {{ $profile?->name ?? 'Lanre Malumi' }}
                </h1>

                <p class="mt-5 text-base font-medium leading-6 text-white/85 sm:text-[18px]">
                    {{ $profile?->professional_title ?? 'Product Designer & Laravel Developer' }}
                </p>

                <div class="mt-5 flex gap-4">
                    <x-public.social-links :profile="$profile" variant="hero" />
                </div>
            </div>
        </header>

        <main class="relative mx-auto max-w-[1320px] px-6 py-9 sm:px-16 sm:py-[108px]">
            <div class="home-skeleton-layer fixed inset-x-0 top-[350px] z-30 max-h-[calc(100dvh-350px)] overflow-hidden bg-[#f7f7f8] px-6 py-9 sm:px-16 sm:py-[108px]" aria-hidden="true">
                <div class="-mx-6 mb-14 flex gap-3 overflow-hidden px-6 pb-1 lg:hidden">
                    <div class="skeleton-shimmer h-11 w-24 shrink-0 rounded-full"></div>
                    <div class="skeleton-shimmer h-11 w-28 shrink-0 rounded-full"></div>
                    <div class="skeleton-shimmer h-11 w-24 shrink-0 rounded-full"></div>
                    <div class="skeleton-shimmer h-11 w-20 shrink-0 rounded-full"></div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-[280px_minmax(0,1fr)] lg:gap-[92px]">
                    <aside class="hidden border-r border-[#e2e2e5] pr-16 lg:block">
                        <section>
                            <div class="skeleton-shimmer h-8 w-32 rounded-full"></div>
                            <div class="mt-8 space-y-5">
                                <div class="skeleton-shimmer h-5 w-28 rounded-full"></div>
                                <div class="skeleton-shimmer h-5 w-36 rounded-full"></div>
                                <div class="skeleton-shimmer h-5 w-24 rounded-full"></div>
                            </div>
                        </section>

                        <section class="mt-28">
                            <div class="skeleton-shimmer h-8 w-24 rounded-full"></div>
                            <div class="mt-8 space-y-5">
                                @for ($i = 0; $i < 3; $i++)
                                    <div class="flex items-center gap-3">
                                        <div class="skeleton-shimmer h-5 w-5 rounded-full"></div>
                                        <div class="skeleton-shimmer h-5 w-24 rounded-full"></div>
                                    </div>
                                @endfor
                            </div>
                        </section>

                        <section class="mt-28">
                            <div class="skeleton-shimmer h-8 w-24 rounded-full"></div>
                            <div class="mt-8 space-y-5">
                                <div class="skeleton-shimmer h-5 w-36 rounded-full"></div>
                                <div class="skeleton-shimmer h-5 w-44 rounded-full"></div>
                                <div class="skeleton-shimmer h-5 w-32 rounded-full"></div>
                            </div>
                        </section>

                        <section class="mt-28">
                            <div class="skeleton-shimmer h-8 w-20 rounded-full"></div>
                            <div class="mt-8 space-y-5">
                                <div class="skeleton-shimmer h-5 w-40 rounded-full"></div>
                                <div class="skeleton-shimmer h-5 w-28 rounded-full"></div>
                                <div class="skeleton-shimmer h-5 w-24 rounded-full"></div>
                            </div>
                        </section>
                    </aside>

                    <section>
                        <div class="mb-8 flex items-center justify-between sm:mb-12">
                            <div class="skeleton-shimmer h-5 w-24 rounded-full"></div>
                            <div class="skeleton-shimmer h-11 w-32 rounded-full"></div>
                        </div>

                        <div class="space-y-16 lg:space-y-0">
                            @for ($i = 0; $i < 3; $i++)
                                <article class="grid gap-6 border-b border-[#e3e3e6] pb-12 last:border-b-0 lg:grid-cols-[240px_minmax(0,1fr)] lg:gap-14 lg:pb-16 lg:pt-16 first:lg:pt-0">
                                    <div class="skeleton-shimmer aspect-[187/116] w-full rounded-[16px] lg:mt-9 lg:h-[116px] lg:w-[187px]"></div>

                                    <div>
                                        @if ($showBlog)
                                            <div class="mb-5 flex flex-wrap items-center gap-3 sm:gap-6">
                                                <div class="skeleton-shimmer h-8 w-36 rounded-full"></div>
                                                <div class="skeleton-shimmer h-5 w-44 rounded-full"></div>
                                            </div>
                                        @else
                                            <div class="skeleton-shimmer mb-7 h-8 w-28 rounded-full"></div>
                                        @endif

                                        <div class="skeleton-shimmer h-10 w-11/12 rounded-full sm:h-14"></div>
                                        <div class="skeleton-shimmer mt-4 h-10 w-3/4 rounded-full sm:h-14"></div>
                                        <div class="mt-6 max-w-[610px] space-y-3">
                                            <div class="skeleton-shimmer h-5 w-full rounded-full"></div>
                                            <div class="skeleton-shimmer h-5 w-10/12 rounded-full"></div>
                                        </div>
                                        <div class="mt-6 flex gap-3">
                                            <div class="skeleton-shimmer h-8 w-20 rounded-full"></div>
                                            <div class="skeleton-shimmer h-8 w-24 rounded-full"></div>
                                            <div class="skeleton-shimmer h-8 w-20 rounded-full"></div>
                                        </div>
                                        <div class="skeleton-shimmer mt-6 h-6 w-28 rounded-full"></div>
                                    </div>
                                </article>
                            @endfor
                        </div>
                    </section>
                </div>
            </div>

            <div class="motion-reveal -mx-6 mb-14 flex gap-3 overflow-x-auto px-6 pb-1 lg:hidden">
                <a
                    href="{{ route('home') }}"
                    @class([
                        'shrink-0 rounded-full px-4 py-2.5 text-base',
                        'bg-[#303136] font-medium text-white' => ! $showBlog && ! $selectedCategory,
                        'border border-[#c9cbd2] font-normal text-[#a6a9b1]' => $showBlog || $selectedCategory,
                    ])
                    @if (! $selectedCategory) aria-current="page" @endif
                >
                    All Work
                </a>

                @foreach ($categories as $category)
                    <a
                        href="{{ route('home', ['category' => $category->slug]) }}"
                        @class([
                            'shrink-0 rounded-full px-4 py-2.5 text-base',
                            'bg-[#303136] font-medium text-white' => $selectedCategory === $category->slug,
                            'border border-[#c9cbd2] font-normal text-[#a6a9b1]' => $selectedCategory !== $category->slug,
                        ])
                        @if ($selectedCategory === $category->slug) aria-current="page" @endif
                    >
                        {{ $category->name }}
                    </a>
                @endforeach

                    <a
                        href="{{ route('home', ['type' => 'blog']) }}"
                        @class([
                            'shrink-0 rounded-full px-4 py-2.5 text-base',
                            'bg-[#303136] font-medium text-white' => $showBlog && ! $selectedCategory,
                            'border border-[#c9cbd2] font-normal text-[#a6a9b1]' => ! $showBlog || $selectedCategory,
                        ])
                        @if ($showBlog && ! $selectedCategory) aria-current="page" @endif
                    >
                        Blog
                    </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-[280px_minmax(0,1fr)] lg:gap-[92px]">
                <aside class="hidden border-r border-[#e2e2e5] pr-16 lg:block">
                    <section class="motion-reveal">
	                        <h2 class="font-syne text-2xl font-semibold leading-tight text-[#303136]">
	                             <a
	                                href="{{ route('home') }}"
	                                class="inline-flex items-center gap-2"
	                                @if (! $showBlog && ! $selectedCategory)
	                                    aria-current="page"
	                                @endif
	                            >
	                                @if (! $showBlog && ! $selectedCategory)
	                                    <span class="homepage-sidebar-active-dot h-2 w-2 rounded-full" aria-hidden="true"></span>
	                                @endif
	                                All Work
	                            </a>
	                        </h2>

                        <nav class="mt-8 space-y-5 text-base font-medium text-[#46484f]">
                            @foreach ($categories as $category)
	                                <a
	                                    href="{{ route('home', ['category' => $category->slug]) }}"
	                                    class="flex items-center gap-2 transition hover:text-[#08a9f4]"
	                                    @if (! $showBlog && $selectedCategory === $category->slug) aria-current="page" @endif
	                                >
	                                    @if (! $showBlog && $selectedCategory === $category->slug)
	                                        <span class="homepage-sidebar-active-dot h-1.5 w-1.5 rounded-full" aria-hidden="true"></span>
	                                    @endif
	                                    {{ $category->name }}
	                                </a>
	                            @endforeach
                        </nav>
                    </section>

	                     <section
	                        class="motion-reveal mt-28"
	                        x-data="{ expanded: @js($blogCategories->slice(3)->contains('slug', $selectedCategory)) }"
	                    >
                        <h2 class="font-syne text-2xl font-semibold leading-tight text-[#303136]">
                            Tools
                        </h2>

                        <ul class="mt-8 space-y-5 text-base font-medium text-[#46484f]">
                            @foreach ($tools->take(3) as $tool)
                                <li class="flex items-center gap-3">
                                    @if ($tool->logo_path)
                                        <img
                                            src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($tool->logo_path) }}"
                                            alt=""
                                            aria-hidden="true"
                                            class="h-5 w-5 shrink-0 object-contain"
                                        >
                                    @else
                                        <span class="inline-flex h-5 w-5 shrink-0 items-center justify-center rounded-full border border-[#c9cbd2] text-[#a7a9b1]">
                                            <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M12 3v4M12 17v4M3 12h4M17 12h4M6 6l3 3M15 15l3 3M18 6l-3 3M9 15l-3 3" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
                                            </svg>
                                        </span>
                                    @endif

                                    {{ $tool->name }}
                                </li>
                            @endforeach
                        </ul>

                        @if ($tools->count() > 3)
                            <ul
                                x-show="expanded"
                                x-transition:enter="motion-safe:transition motion-safe:duration-200 motion-safe:ease-out"
                                x-transition:enter-start="motion-safe:opacity-0 motion-safe:-translate-y-1"
                                x-transition:enter-end="motion-safe:opacity-100 motion-safe:translate-y-0"
                                x-transition:leave="motion-safe:transition motion-safe:duration-150 motion-safe:ease-in"
                                x-transition:leave-start="motion-safe:opacity-100 motion-safe:translate-y-0"
                                x-transition:leave-end="motion-safe:opacity-0 motion-safe:-translate-y-1"
                                class="mt-5 space-y-5 text-base font-medium text-[#46484f]"
                                style="display: none;"
                            >
                                @foreach ($tools->slice(3) as $tool)
                                    <li class="flex items-center gap-3">
                                        @if ($tool->logo_path)
                                            <img
                                                src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($tool->logo_path) }}"
                                                alt=""
                                                aria-hidden="true"
                                                class="h-5 w-5 shrink-0 object-contain"
                                            >
                                        @else
                                            <span class="inline-flex h-5 w-5 shrink-0 items-center justify-center rounded-full border border-[#c9cbd2] text-[#a7a9b1]">
                                                <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M12 3v4M12 17v4M3 12h4M17 12h4M6 6l3 3M15 15l3 3M18 6l-3 3M9 15l-3 3" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
                                                </svg>
                                            </span>
                                        @endif

                                        {{ $tool->name }}
                                    </li>
                                @endforeach
                            </ul>

                            <button
                                type="button"
                                class="mt-5 inline-flex items-center gap-2 text-base font-medium text-[#a7a9b1] transition hover:text-[#303136]"
                                :aria-expanded="expanded.toString()"
                                @click="expanded = ! expanded"
                            >
                                <span x-text="expanded ? 'Show less' : 'See all'">See all</span>
                                <img
                                    src="{{ asset('images/icons/admin-order-down.svg') }}"
                                    alt=""
                                    aria-hidden="true"
                                    class="h-4 w-4 transition-transform motion-reduce:transition-none"
                                    :class="{ 'rotate-180': expanded }"
                                >
                            </button>
                        @endif
                    </section>

                    <section
                        class="motion-reveal mt-28"
                        x-data="{ expanded: false }"
                    >
                        <h2 class="font-syne text-2xl font-semibold leading-tight text-[#303136]">
                            Skills
                        </h2>

                        <ul class="mt-8 space-y-5 text-base font-medium text-[#46484f]">
                            @foreach ($skills->take(3) as $skill)
                                <li>{{ $skill->name }}</li>
                            @endforeach
                        </ul>

                        @if ($skills->count() > 3)
                            <ul
                                x-show="expanded"
                                x-transition:enter="motion-safe:transition motion-safe:duration-200 motion-safe:ease-out"
                                x-transition:enter-start="motion-safe:opacity-0 motion-safe:-translate-y-1"
                                x-transition:enter-end="motion-safe:opacity-100 motion-safe:translate-y-0"
                                x-transition:leave="motion-safe:transition motion-safe:duration-150 motion-safe:ease-in"
                                x-transition:leave-start="motion-safe:opacity-100 motion-safe:translate-y-0"
                                x-transition:leave-end="motion-safe:opacity-0 motion-safe:-translate-y-1"
                                class="mt-5 space-y-5 text-base font-medium text-[#46484f]"
                                style="display: none;"
                            >
                                @foreach ($skills->slice(3) as $skill)
                                    <li>{{ $skill->name }}</li>
                                @endforeach
                            </ul>

                            <button
                                type="button"
                                class="mt-5 inline-flex items-center gap-2 text-base font-medium text-[#a7a9b1] transition hover:text-[#303136]"
                                :aria-expanded="expanded.toString()"
                                @click="expanded = ! expanded"
                            >
                                <span x-text="expanded ? 'Show less' : 'See all'">See all</span>
                                <img
                                    src="{{ asset('images/icons/admin-order-down.svg') }}"
                                    alt=""
                                    aria-hidden="true"
                                    class="h-4 w-4 transition-transform motion-reduce:transition-none"
                                    :class="{ 'rotate-180': expanded }"
                                >
                            </button>
                        @endif
                    </section>

                     <section
                        class="motion-reveal mt-28"
                        x-data="{ expanded: false }"
                    >
                        <h2 class="font-syne text-2xl font-semibold leading-tight text-[#303136]">

	                            <a
	                                href="{{ route('home', ['type' => 'blog']) }}"
	                                class="inline-flex items-center gap-2"
	                                @if ($showBlog && ! $selectedCategory)
	                                    aria-current="page"
	                                @endif
	                            >
	                                @if ($showBlog && ! $selectedCategory)
	                                    <span class="homepage-sidebar-active-dot h-2 w-2 rounded-full" aria-hidden="true"></span>
	                                @endif
	                                Blog
	                            </a>
	                        </h2>

                        <nav class="mt-8 space-y-5 text-base font-medium text-[#46484f]">
                            @foreach ($blogCategories->take(3) as $category)
	                                <a
	                                    href="{{ route('home', ['category' => $category->slug]) }}"
	                                    class="flex items-center gap-2 transition hover:text-[#08a9f4]"
	                                    @if ($selectedCategory === $category->slug)
	                                        aria-current="page"
	                                    @endif
	                                >
	                                    @if ($selectedCategory === $category->slug)
	                                        <span class="homepage-sidebar-active-dot h-1.5 w-1.5 rounded-full" aria-hidden="true"></span>
	                                    @endif
	                                    {{ $category->name }}
	                                </a>
	                            @endforeach

                            @if ($blogCategories->count() > 3)
                                <div
                                    x-show="expanded"
                                    x-transition:enter="motion-safe:transition motion-safe:duration-200 motion-safe:ease-out"
                                    x-transition:enter-start="motion-safe:opacity-0 motion-safe:-translate-y-1"
                                    x-transition:enter-end="motion-safe:opacity-100 motion-safe:translate-y-0"
                                    x-transition:leave="motion-safe:transition motion-safe:duration-150 motion-safe:ease-in"
                                    x-transition:leave-start="motion-safe:opacity-100 motion-safe:translate-y-0"
                                    x-transition:leave-end="motion-safe:opacity-0 motion-safe:-translate-y-1"
                                    class="space-y-5"
                                    style="display: none;"
                                >
                                    @foreach ($blogCategories->slice(3) as $category)
	                                        <a
	                                            href="{{ route('home', ['category' => $category->slug]) }}"
	                                            class="flex items-center gap-2 transition hover:text-[#08a9f4]"
	                                            @if ($selectedCategory === $category->slug)
	                                                aria-current="page"
	                                            @endif
	                                        >
	                                            @if ($selectedCategory === $category->slug)
	                                                <span class="homepage-sidebar-active-dot h-1.5 w-1.5 rounded-full" aria-hidden="true"></span>
	                                            @endif
	                                            {{ $category->name }}
	                                        </a>
                                    @endforeach
                                </div>

                                <button
                                    type="button"
                                    class="inline-flex items-center gap-2 text-base font-medium text-[#a7a9b1] transition hover:text-[#303136]"
                                    :aria-expanded="expanded.toString()"
                                    @click="expanded = ! expanded"
                                >
                                    <span x-text="expanded ? 'Show less' : 'See all'">See all</span>
                                    <img
                                        src="{{ asset('images/icons/admin-order-down.svg') }}"
                                        alt=""
                                        aria-hidden="true"
                                        class="h-4 w-4 transition-transform motion-reduce:transition-none"
                                        :class="{ 'rotate-180': expanded }"
                                    >
                                </button>
                            @endif
                        </nav>
                    </section>

                </aside>

                <section class="motion-reveal">
                    @php
                        $sortBaseQuery = request()->except([
                            'sort',
                            'project_page',
                            'blog_page',
                        ]);

                        $sortOptions = [
                            [
                                'value' => 'latest',
                                'label' => 'Latest',
                                'href' => route('home', array_merge($sortBaseQuery, [
                                    'sort' => 'latest',
                                ])),
                            ],
                            [
                                'value' => 'earliest',
                                'label' => 'Earliest',
                                'href' => route('home', array_merge($sortBaseQuery, [
                                    'sort' => 'earliest',
                                ])),
                            ],
                        ];
                    @endphp

                    <div class="mb-8 flex items-center justify-between sm:mb-12">
                
                    <p class="text-base font-medium text-[#6d7079]">
                        @if ($showBlog)
                            {{ $posts->total() }}
                            {{ $posts->total() === 1 ? 'post' : 'posts' }}
                        @else
                            {{ $projects->total() }}
                            {{ $projects->total() === 1 ? 'project' : 'projects' }}
                        @endif
                    </p>

                        <div
                            x-data="{ open: false }"
                            class="relative"
                        >
                            <button
                                type="button"
                                class="inline-flex h-11 items-center gap-3 rounded-full bg-white px-5 text-base font-medium text-[#303136] shadow-sm"
                                aria-label="Sort work"
                                :aria-expanded="open.toString()"
                                @click="open = ! open"
                                @keydown.escape.window="open = false"
                            >
                                Sort by
                                <img
                                    src="{{ asset('images/icons/admin-order-down.svg') }}"
                                    alt=""
                                    aria-hidden="true"
                                    class="h-5 w-5 transition-transform"
                                    :class="{ 'rotate-180': open }"
                                >
                            </button>

                            <div
                                x-show="open"
                                x-transition.opacity.scale.origin.top.right
                                @click.outside="open = false"
                                class="absolute right-0 z-20 mt-4 min-w-[150px] overflow-hidden rounded-[14px] bg-white py-3 shadow-[0_18px_40px_rgba(21,24,32,0.12)]"
                                style="display: none;"
                            >
                                @foreach ($sortOptions as $sortOption)
                                    <a
                                        href="{{ $sortOption['href'] }}"
                                        @class([
                                            'flex items-center justify-between gap-4 px-6 py-2.5 text-base text-[#303136] transition hover:bg-[#f7f7f8]',
                                            'font-medium' => ($selectedSort ?? 'latest') === $sortOption['value'],
                                            'font-normal' => ($selectedSort ?? 'latest') !== $sortOption['value'],
                                        ])
                                        @if (($selectedSort ?? 'latest') === $sortOption['value']) aria-current="true" @endif
                                    >
                                        <span>{{ $sortOption['label'] }}</span>

                                        @if (($selectedSort ?? 'latest') === $sortOption['value'])
                                            <span class="text-[18px] leading-none" aria-hidden="true">&#10003;</span>
                                            <span class="sr-only">selected</span>
                                        @endif
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>


                    @if ($showBlog)
                        @if ($posts->isNotEmpty())
                            <div class="space-y-16 lg:space-y-0">
                                @foreach ($posts as $post)
                                    <article class="motion-card motion-reveal grid gap-6 border-b border-[#e3e3e6] pb-12 last:border-b-0 lg:grid-cols-[240px_minmax(0,1fr)] lg:gap-14 lg:pb-16 lg:pt-16 first:lg:pt-0">

                                        {{-- Blog thumbnail --}}
                                        <a
                                            href="{{ route('blog.show', $post) }}"
                                            @class([
                                                'motion-card-media block aspect-[187/116] w-full flex-none overflow-hidden rounded-[16px] bg-white shadow-[0_18px_28px_rgba(21,24,32,0.12)] transition-transform duration-300 ease-out hover:rotate-0 lg:mt-9 lg:aspect-auto lg:h-[116px] lg:w-[187px] lg:hover:[transform:rotate(0deg)]',
                                                'lg:[transform:rotate(-6deg)]' => $loop->odd,
                                                'lg:[transform:rotate(5deg)]' => $loop->even,
                                            ])
                                        >
                                            @if ($post->thumbnail)
                                                <img
                                                    src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($post->thumbnail) }}"
                                                    alt="{{ $post->title }} thumbnail"
                                                    class="motion-card-image h-full w-full object-cover"
                                                >
                                            @else
                                                <div class="flex h-full w-full items-center justify-center bg-[#e9eaee] px-4 text-center text-base font-medium text-[#8c8f98]">
                                                    {{ $post->title }}
                                                </div>
                                            @endif
                                        </a>

                                        <div>
                                            {{-- Category, date and reading time --}}
                                            <div class="mb-5 flex flex-wrap items-center gap-3 sm:gap-6">
                                                @if ($post->category)
                                                    <a
                                                        href="{{ route('home', ['category' => $post->category->slug]) }}"
                                                        class="inline-flex rounded-full border px-3 py-1 text-base font-normal leading-6"
                                                        style="border-color: #6366F1; color: #6366F1;"
                                                    >
                                                        {{ $post->category->name }}
                                                    </a>
                                                @endif

                                                <div class="flex items-center gap-2 text-base font-normal leading-6">
                                                    <span class="text-[#a1a1aa]">
                                                        {{ $post->published_at->format('d F, Y') }}
                                                    </span>

                                                    <span aria-hidden="true" class="text-[#a1a1aa]">·</span>

                                                    <span class="text-[#52525b]">
                                                        {{ $post->reading_time }} min read
                                                    </span>
                                                </div>
                                            </div>

                                            <h2 class="font-syne text-[34px] font-bold leading-tight tracking-normal text-[#303136] sm:text-[48px]">
                                                <a href="{{ route('blog.show', $post) }}">
                                                    {{ $post->title }}
                                                </a>
                                            </h2>

                                            @if ($post->excerpt)
                                                <p class="mt-6 max-w-[610px] text-base font-normal leading-6 text-[#414349]">
                                                    {{ $post->excerpt }}
                                                </p>
                                            @endif

                                            @if (! empty($post->tags))
                                                <div class="mt-6 flex flex-wrap gap-3">
                                                    @foreach ($post->tags as $tag)
                                                        <span class="rounded-full border border-[#c5c7ce] px-4 py-1.5 text-base font-normal leading-none text-[#a6a9b1]">
                                                            {{ $tag }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @endif

                                            <a
                                                href="{{ route('blog.show', $post) }}"
                                                class="motion-underline-link mt-6 inline-flex border-b border-[#303136] pb-1 text-base font-medium text-[#303136] transition hover:text-[#111216]"
                                            >
                                                Read Blog
                                                <span class="motion-link-arrow ml-1" aria-hidden="true">&nearr;</span>
                                            </a>
                                        </div>
                                    </article>
                                @endforeach
                            </div>
                        @else
                            <p class="text-base font-normal leading-6 text-[#414349]">
                                No published blog posts yet.
                            </p>
                        @endif
                    @endif


                    @if (! $showBlog && $projects->isNotEmpty())
                        <div class="space-y-16 lg:space-y-0">
                            @foreach ($projects as $project)
                                <article class="motion-card motion-reveal grid gap-6 border-b border-[#e3e3e6] pb-12 last:border-b-0 lg:grid-cols-[240px_minmax(0,1fr)] lg:gap-14 lg:pb-16 lg:pt-16 first:lg:pt-0">
                                    <a
                                        href="{{ route('projects.show', $project) }}"
                                        @class([
                                            'motion-card-media block aspect-[187/116] w-full flex-none overflow-hidden rounded-[16px] bg-white shadow-[0_18px_28px_rgba(21,24,32,0.12)] transition-transform duration-300 ease-out hover:rotate-0 lg:mt-9 lg:aspect-auto lg:h-[116px] lg:w-[187px] lg:hover:[transform:rotate(0deg)]',
                                            'lg:[transform:rotate(5deg)]' => $loop->odd,
                                            'lg:[transform:rotate(-5deg)]' => $loop->even,
                                        ])
                                    >
                                        @if ($project->thumbnail)
                                            <img
                                                src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($project->thumbnail) }}"
                                                alt="{{ $project->title }} thumbnail"
                                                class="motion-card-image h-full w-full object-cover"
                                            >
                                        @else
                                            <div class="flex h-full w-full items-center justify-center bg-[#e9eaee] text-base font-medium text-[#8c8f98]">
                                                {{ $project->title }}
                                            </div>
                                        @endif
                                    </a>

                                    <div>
                                        
                                    @if ($project->category)
                                        <p
                                            class="mb-7 inline-flex rounded-full border px-4 py-1.5 text-base font-normal leading-none"
                                            style="border-color: {{ $project->category->projectClassificationColor() }}; color: {{ $project->category->projectClassificationColor() }};"
                                        >
                                            {{ $project->category->name }}
                                        </p>
                                    @endif

                                        <h2 class="font-syne text-[34px] font-bold leading-tight tracking-normal text-[#303136] sm:text-[48px]">
                                            {{ $project->title }}
                                        </h2>

                                        @if ($project->subtitle)
                                            <p
                                                class="mt-6 text-base font-medium leading-6"
                                                @if ($project->category)
                                                    style="color: {{ $project->category->projectClassificationColor() }};"
                                                @endif
                                            >
                                                {{ $project->subtitle }}
                                            </p>
                                        @endif

                                        @if ($project->summary)
                                            <p class="mt-6 max-w-[610px] text-base font-normal leading-6 text-[#414349]">
                                                {{ $project->summary }}
                                            </p>
                                        @endif

                                        @if (!empty($project->tags))
                                            <div class="mt-6 flex flex-wrap gap-3">
                                                @foreach ($project->tags as $tag)
                                                    <span class="rounded-full border border-[#c5c7ce] px-4 py-1.5 text-base font-normal leading-none text-[#a6a9b1]">
                                                        {{ $tag }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @endif

                                        <a
                                            href="{{ route('projects.show', $project) }}"
                                            class="motion-underline-link mt-6 inline-flex border-b border-[#303136] pb-1 text-base font-medium text-[#303136] transition hover:text-[#111216]"
                                        >
                                            View Project
                                            <span class="motion-link-arrow ml-1" aria-hidden="true">&nearr;</span>
                                        </a>
                                    </div>
                                </article>
                            @endforeach
                            
                        </div>
                        @elseif (! $showBlog)
                            <p class="text-base font-normal leading-6 text-[#414349]">
                                No published projects yet.
                            </p>
                        @endif

                            {{-- makes the button appear only when there are more than six matching items --}}
                            {{-- this would also include a previous button if the user is on page 2 or higher --}}

                            @php
                                $paginator = $showBlog ? $posts : $projects;
                            @endphp

                            @if ($paginator->hasPages())
                                <div class="flex items-center justify-between pb-6 pt-20 sm:pb-0 sm:pt-28">
                                    @if ($paginator->onFirstPage())
                                        <span></span>
                                    @else
                                        <a
                                            href="{{ $paginator->previousPageUrl() }}"
                                            class="inline-flex items-center border-b border-[#303136] pb-2 text-base font-medium uppercase tracking-normal text-[#303136]"
                                            aria-label="Previous page"
                                        >
                                            <span class="mr-3 text-3xl font-normal leading-none" aria-hidden="true">←</span>
                                            Previous Page
                                        </a>
                                    @endif

                                    @if ($paginator->hasMorePages())
                                        <a
                                            href="{{ $paginator->nextPageUrl() }}"
                                            class="inline-flex items-center border-b border-[#303136] pb-2 text-base font-medium uppercase tracking-normal text-[#303136]"
                                            aria-label="Next page"
                                        >
                                            Next Page
                                            <span class="ml-3 text-3xl font-normal leading-none" aria-hidden="true">→</span>
                                        </a>
                                    @endif
                                </div>
                            @endif
                    </div>
                </section>
            </div>
        </main>

        <x-public.footer
            :active="$showBlog ? 'blog' : 'work'"
            :profile="$profile"
            :settings="$siteSettings"
        />
    </div>
</body>
</html>

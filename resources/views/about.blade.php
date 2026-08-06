<!DOCTYPE html>
<html lang="en">
<head>
    @php
        $siteSettings = \App\Models\SiteSetting::current();
        $aboutBackgroundImage = $siteSettings->aboutBackgroundUrl();
        $aboutText = $profile->about_intro ?: $profile->bio;
        $resumeExists = $profile->resume_path
            && \Illuminate\Support\Facades\Storage::disk('public')->exists($profile->resume_path);
    @endphp

    <x-public.head
        :settings="$siteSettings"
        :title="'About - ' . $profile->name"
        :description="$aboutText"
    />
</head>

<body class="bg-[#f7f7f8] font-inter font-normal text-[#303136] antialiased">
    <div class="flex min-h-screen flex-col bg-[#f7f7f8]">
        <main class="flex-1 lg:grid lg:grid-cols-[35%_minmax(0,1fr)]">
            <section
                class="site-hero relative h-[300px] overflow-hidden bg-[#020815] lg:h-auto lg:min-h-full"
                style="--site-hero-image: url('{{ $aboutBackgroundImage }}');"
                aria-label="About page visual"
            >
                <nav class="relative z-10 mx-auto flex w-full max-w-[1320px] items-center justify-end px-6 pt-12 sm:px-16 sm:pt-[72px] lg:hidden">
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
            </section>

            <section class="relative min-h-[600px] bg-[#f7f7f8] lg:min-h-0">
                <nav class="relative z-10 mx-auto hidden w-full max-w-[1320px] items-center justify-end px-6 pt-12 sm:px-16 sm:pt-[72px] lg:flex">
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

                        {{-- Placeholder: dark-mode behaviour is not wired yet. --}}
                        <button
                            type="button"
                            aria-label="Toggle dark mode"
                            data-public-theme-toggle
                            aria-pressed="false"
                            class="flex h-10 w-10 items-center justify-center rounded-full transition hover:bg-white sm:h-12 sm:w-12"
                        >
                            <img
                                src="{{ asset('images/icons/dark-mode.svg') }}"
                                alt=""
                                aria-hidden="true"
                                class="public-theme-icon public-theme-icon-moon h-8 w-8 brightness-0 opacity-60"
                            >
                            <img
                                src="{{ asset('images/icons/sun.svg') }}"
                                alt=""
                                aria-hidden="true"
                                class="public-theme-icon public-theme-icon-sun h-8 w-8"
                            >
                        </button>

                        <x-public.menu button-class="flex h-12 w-12 items-center justify-center rounded-full bg-[#ececef] text-[#303136]" />
                    </div>
                </nav>

                <div class="px-9 pb-24 pt-24 sm:px-16 lg:px-0 lg:pb-20 lg:pt-[140px]">
                    <div class="max-w-[610px] lg:ml-[108px]">
                        <p class="text-[18px] font-semibold leading-7 text-[#303136] sm:text-[20px]">
                            Hello, my name is
                        </p>

                        <h1 class="mt-5 font-syne text-[44px] font-bold leading-tight tracking-normal text-[#303136] sm:text-[56px] lg:mt-6 lg:text-[34px]">
                            {{ $profile->name }}
                        </h1>

                        <div class="mt-5 h-1 w-[100px] bg-[#08a9f4] lg:mt-6"></div>

                        @if ($aboutText)
                            <p class="mt-6 text-base font-normal leading-7 text-[#303136] sm:text-[18px] sm:leading-8">
                                {{ $aboutText }}
                            </p>
                        @elseif ($profile->professional_title)
                            <p class="mt-6 text-base font-normal leading-7 text-[#303136] sm:text-[18px] sm:leading-8">
                                {{ $profile->professional_title }}
                            </p>
                        @endif

                        @if ($resumeExists)
                            <a
                                href="{{ asset('storage/' . $profile->resume_path) }}"
                                download="{{ basename($profile->resume_path) }}"
                                class="mt-8 inline-flex border-b border-[#303136] pb-1 text-base font-medium text-[#303136] transition hover:text-[#111216]"
                            >
                                Download Résumé
                                <span class="ml-1" aria-hidden="true">&darr;</span>
                            </a>
                        @endif
                    </div>
                </div>
            </section>
        </main>

        <x-public.footer
            active="about"
            :profile="$profile"
            :settings="$siteSettings"
            footer-class="bg-[#191a1e] px-9 py-9 text-base font-medium text-[#8f929b] sm:px-16 sm:py-10"
            nav-class="flex flex-wrap gap-6 text-[20px] sm:gap-4 sm:text-base"
            credit-class="font-medium text-[20px] sm:text-base"
        />
    </div>
</body>
</html>

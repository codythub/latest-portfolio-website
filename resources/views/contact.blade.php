<!DOCTYPE html>
<html lang="en">
<head>
    @php
        $siteSettings = \App\Models\SiteSetting::current();
        $siteHeroBackgroundImage = $siteSettings->heroBackgroundUrl();
        $whatsappDigits = preg_replace('/\D+/', '', (string) $profile->whatsapp);
    @endphp

    <x-public.head
        :settings="$siteSettings"
        :title="$siteSettings->contact_heading . ' - ' . $profile->name"
        :description="$siteSettings->contact_description"
    />
</head>

<body class="bg-[#f7f7f8] font-inter font-normal text-[#303136] antialiased">
    <div class="min-h-screen">
        <header
            class="site-hero relative h-[420px] overflow-hidden bg-[#020815] text-white"
            style="--site-hero-image: url('{{ $siteHeroBackgroundImage }}');"
        >
            <nav class="relative z-10 mx-auto flex w-full max-w-[1248px] items-center justify-end px-6 pt-12 sm:px-8 sm:pt-16">
                <div class="flex items-center gap-4">
                    {{-- Placeholder: site search is visual only until backend search is added. --}}
                    <label class="hidden h-11 items-center rounded-full bg-white px-4 text-[#757982] shadow-sm sm:flex">
                        <img
                            src="{{ asset('images/icons/search.svg') }}"
                            alt=""
                            aria-hidden="true"
                            class="mr-2 h-4 w-4"
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
                        class="flex h-9 w-9 items-center justify-center rounded-full bg-white/15 text-white shadow-sm backdrop-blur sm:hidden"
                    >
                        <img
                            src="{{ asset('images/icons/search.svg') }}"
                            alt=""
                            aria-hidden="true"
                            class="h-4 w-4 invert"
                        >
                    </button>

                    {{-- Placeholder: dark-mode behaviour is not wired yet. --}}
                    <button
                        type="button"
                        aria-label="Toggle dark mode"
                        data-public-theme-toggle
                        aria-pressed="false"
                        class="flex h-9 w-9 items-center justify-center rounded-full text-white transition hover:bg-white/10 sm:h-10 sm:w-10"
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

                    <x-public.menu button-class="flex h-10 w-10 items-center justify-center rounded-full bg-white text-[#2f3035] shadow-sm" />
                </div>
            </nav>

            <div class="relative z-10 mx-auto flex max-w-[820px] flex-col items-center px-6 pb-10 pt-20 text-center sm:pb-12 sm:pt-24">
                <h1 class="font-syne text-[48px] font-bold leading-tight tracking-normal text-white">
                    {{ $siteSettings->contact_heading }}
                </h1>

                <p class="mx-auto mt-6 max-w-[650px] text-[21px] font-medium leading-[1.55] text-white/90 sm:text-[20px]">
                    {{ $siteSettings->contact_description }}
                </p>
            </div>
        </header>

        <main class="relative z-20 bg-transparent pb-0 sm:-mt-[118px] sm:pb-[88px]">
            <div class="relative mx-auto sm:max-w-[1320px] sm:px-8">
                <div class="grid bg-white shadow-none sm:grid-cols-[minmax(0,1fr)_390px] sm:shadow-[0_18px_38px_rgba(17,18,22,0.15)]">
                <section class="px-8 pb-[98px] pt-8 sm:px-[88px] sm:pb-[58px] sm:pt-[54px]">
                    <h2 class="font-syne text-[24px] font-semibold leading-tight text-[#303136]">
                        Send me a Message
                    </h2>

                    @if (session('success'))
                        <div class="mt-8 rounded-[12px] border border-[#b8f3d1] bg-[#effdf5] px-5 py-4 text-base font-bold text-[#167a3f]">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form
                        method="POST"
                        action="{{ route('contact.store') }}"
                        class="mt-[54px] sm:mt-[42px]"
                        novalidate
                    >
                        @csrf

                        <div class="hidden" aria-hidden="true">
                            <label for="website">Website</label>
                            <input
                                id="website"
                                type="text"
                                name="website"
                                tabindex="-1"
                                autocomplete="off"
                            >
                        </div>

                        <div class="grid gap-y-[52px] sm:grid-cols-2 sm:gap-x-9 sm:gap-y-[44px]">
                            <div>
                                <div class="relative">
                                    <input
                                        id="name"
                                        type="text"
                                        name="name"
                                        value="{{ old('name') }}"
                                        required
                                        placeholder=" "
                                        class="peer block h-16 w-full border-0 border-b-2 border-[#bfc1c8] bg-transparent px-0 pb-2 pt-6 text-[23px] font-bold text-[#303136] placeholder:text-transparent focus:border-[#303136] focus:ring-0"
                                    >
                                    <label
                                        for="name"
                                        class="pointer-events-none absolute left-0 top-6 origin-left text-[21px] font-bold leading-none text-[#a9acb4] transition-all duration-200 ease-out peer-focus:top-1 peer-focus:scale-75 peer-focus:text-[#303136] peer-[:not(:placeholder-shown)]:top-1 peer-[:not(:placeholder-shown)]:scale-75 peer-[:not(:placeholder-shown)]:text-[#303136]"
                                    >
                                        Your Name *
                                    </label>
                                </div>
                                @error('name')
                                    <p class="mt-2 text-sm font-semibold text-[#ef233c]">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <div class="relative">
                                    <input
                                        id="email"
                                        type="email"
                                        name="email"
                                        value="{{ old('email') }}"
                                        required
                                        placeholder=" "
                                        class="peer block h-16 w-full border-0 border-b-2 border-[#bfc1c8] bg-transparent px-0 pb-2 pt-6 text-[23px] font-bold text-[#303136] placeholder:text-transparent focus:border-[#303136] focus:ring-0"
                                    >
                                    <label
                                        for="email"
                                        class="pointer-events-none absolute left-0 top-6 origin-left text-[21px] font-bold leading-none text-[#a9acb4] transition-all duration-200 ease-out peer-focus:top-1 peer-focus:scale-75 peer-focus:text-[#303136] peer-[:not(:placeholder-shown)]:top-1 peer-[:not(:placeholder-shown)]:scale-75 peer-[:not(:placeholder-shown)]:text-[#303136]"
                                    >
                                        Email Address *
                                    </label>
                                </div>
                                @error('email')
                                    <p class="mt-2 text-sm font-semibold text-[#ef233c]">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <div class="relative">
                                    <input
                                        id="phone"
                                        type="text"
                                        name="phone"
                                        value="{{ old('phone') }}"
                                        required
                                        placeholder=" "
                                        class="peer block h-16 w-full border-0 border-b-2 border-[#bfc1c8] bg-transparent px-0 pb-2 pt-6 text-[23px] font-bold text-[#303136] placeholder:text-transparent focus:border-[#303136] focus:ring-0"
                                    >
                                    <label
                                        for="phone"
                                        class="pointer-events-none absolute left-0 top-6 origin-left text-[21px] font-bold leading-none text-[#a9acb4] transition-all duration-200 ease-out peer-focus:top-1 peer-focus:scale-75 peer-focus:text-[#303136] peer-[:not(:placeholder-shown)]:top-1 peer-[:not(:placeholder-shown)]:scale-75 peer-[:not(:placeholder-shown)]:text-[#303136]"
                                    >
                                        Phone*
                                    </label>
                                </div>
                                @error('phone')
                                    <p class="mt-2 text-sm font-semibold text-[#ef233c]">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <div class="relative">
                                    <input
                                        id="company"
                                        type="text"
                                        name="company"
                                        value="{{ old('company') }}"
                                        placeholder=" "
                                        class="peer block h-16 w-full border-0 border-b-2 border-[#bfc1c8] bg-transparent px-0 pb-2 pt-6 text-[23px] font-bold text-[#303136] placeholder:text-transparent focus:border-[#303136] focus:ring-0"
                                    >
                                    <label
                                        for="company"
                                        class="pointer-events-none absolute left-0 top-6 origin-left text-[21px] font-bold leading-none text-[#a9acb4] transition-all duration-200 ease-out peer-focus:top-1 peer-focus:scale-75 peer-focus:text-[#303136] peer-[:not(:placeholder-shown)]:top-1 peer-[:not(:placeholder-shown)]:scale-75 peer-[:not(:placeholder-shown)]:text-[#303136]"
                                    >
                                        Company
                                    </label>
                                </div>
                                @error('company')
                                    <p class="mt-2 text-sm font-semibold text-[#ef233c]">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-[52px] sm:mt-[44px]">
                            <div class="relative">
                                <textarea
                                    id="message"
                                    name="message"
                                    rows="2"
                                    required
                                    placeholder=" "
                                    class="peer block min-h-16 w-full resize-y border-0 border-b-2 border-[#bfc1c8] bg-transparent px-0 pb-2 pt-6 text-[23px] font-bold leading-8 text-[#303136] placeholder:text-transparent focus:border-[#303136] focus:ring-0"
                                >{{ old('message') }}</textarea>
                                <label
                                    for="message"
                                    class="pointer-events-none absolute left-0 top-6 origin-left text-[21px] font-bold leading-none text-[#a9acb4] transition-all duration-200 ease-out peer-focus:top-1 peer-focus:scale-75 peer-focus:text-[#303136] peer-[:not(:placeholder-shown)]:top-1 peer-[:not(:placeholder-shown)]:scale-75 peer-[:not(:placeholder-shown)]:text-[#303136]"
                                >
                                    Message*
                                </label>
                            </div>
                            @error('message')
                                <p class="mt-2 text-sm font-semibold text-[#ef233c]">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mt-[52px] flex flex-col gap-9 sm:mt-[50px] sm:flex-row sm:items-center sm:justify-between">
                            <button
                                type="reset"
                                class="order-2 w-fit border-b-2 border-[#a9acb4] pb-1 text-[18px] font-medium uppercase leading-none text-[#a9acb4] sm:order-1"
                            >
                                Clear Form
                            </button>

                            <button
                                type="submit"
                                class="order-1 w-fit border-b-2 border-[#303136] pb-1 text-[18px] font-medium uppercase leading-none text-[#303136] transition hover:text-[#111216] sm:order-2"
                            >
                                Send Message
                                <span class="ml-2" aria-hidden="true">&rarr;</span>
                            </button>
                        </div>
                    </form>
                </section>

                <aside class="bg-[#20a9df] px-8 py-[78px] text-center text-white sm:px-[58px] sm:py-[54px] sm:text-left">
                    <h2 class="whitespace-nowrap font-syne text-[24px] font-semibold leading-tight text-white">
                        Contact details
                    </h2>

                    <div class="mt-[50px] space-y-[42px] text-[21px] font-medium leading-7 text-white sm:mt-[44px] sm:space-y-8 sm:text-[19px]">
                        @if ($profile->location)
                            <div class="flex flex-col items-center gap-5 sm:flex-row sm:items-start sm:gap-5">
                                <svg class="h-8 w-8 shrink-0 sm:h-7 sm:w-7" viewBox="0 0 24 24" fill="none" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 21s7-5.1 7-11a7 7 0 1 0-14 0c0 5.9 7 11 7 11Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                                    <path d="M12 12.5a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z" stroke="currentColor" stroke-width="1.8"/>
                                </svg>
                                <p>{{ $profile->location }}</p>
                            </div>
                        @endif

                        @if ($profile->whatsapp)
                            <div class="flex flex-col items-center gap-5 sm:flex-row sm:items-start sm:gap-5">
                                <svg class="h-8 w-8 shrink-0 sm:h-7 sm:w-7" viewBox="0 0 24 24" fill="none" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M6.6 18.2 5 22l4-1.4a9 9 0 1 0-2.4-2.4Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                                    <path d="M9.3 8.6c.2-.4.4-.4.7-.4h.6c.2 0 .5 0 .7.5.2.5.8 1.9.9 2 .1.2.1.4 0 .6-.1.2-.2.3-.4.5l-.5.5c-.2.2-.3.3-.1.7.2.4.8 1.3 1.7 2 .9.8 1.7 1 2.1 1.2.3.1.5.1.7-.1l.8-.9c.2-.3.5-.2.8-.1l1.9.9c.3.2.5.3.6.5.1.2.1 1.1-.3 1.7-.4.6-1.8 1.1-2.5 1.1-.7 0-2.1-.3-3.8-1.2-3.2-1.7-5.3-4.7-5.5-5-.2-.3-1.3-1.7-1.3-3.2s.8-2.3 1.1-2.6Z" fill="currentColor"/>
                                </svg>
                                @if ($whatsappDigits)
                                    <a
                                        href="https://wa.me/{{ $whatsappDigits }}"
                                        class="transition hover:text-white/80"
                                    >
                                        {{ $profile->whatsapp }}
                                    </a>
                                @else
                                    <p>{{ $profile->whatsapp }}</p>
                                @endif
                            </div>
                        @endif

                        @if ($profile->email)
                            <div class="flex flex-col items-center gap-5 sm:flex-row sm:items-start sm:gap-5">
                                <svg class="h-8 w-8 shrink-0 sm:h-7 sm:w-7" viewBox="0 0 24 24" fill="none" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4 6h16v12H4V6Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                                    <path d="m4 7 8 6 8-6" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/>
                                </svg>
                                <a
                                    href="mailto:{{ $profile->email }}"
                                    class="break-words transition hover:text-white/80"
                                >
                                    {{ $profile->email }}
                                </a>
                            </div>
                        @endif
                    </div>
                </aside>
                </div>
            </div>
        </main>

        <x-public.footer
            :profile="$profile"
            :settings="$siteSettings"
        />
    </div>
</body>
</html>

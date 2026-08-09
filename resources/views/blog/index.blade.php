<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script>
        (() => {
            let savedTheme = null;

            try {
                savedTheme = localStorage.getItem('public-theme');
            } catch (error) {
                savedTheme = null;
            }

            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const useDarkTheme = savedTheme ? savedTheme === 'dark' : prefersDark;

            document.documentElement.classList.toggle('public-dark', useDarkTheme);
            document.documentElement.style.colorScheme = useDarkTheme ? 'dark' : 'light';
            document.documentElement.classList.add('js');
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <title>Blog</title>
</head>

<body class="bg-[#f7f7f8] font-inter font-normal text-[#33343a] antialiased">
    @php
        // Temporary source. Later this can come from Admin -> Site Settings.
        $siteHeroBackgroundImage = \Illuminate\Support\Facades\Storage::disk('public')->url('projects/thumbnails/4AHr0Js5j51L9Z3XpyDVjqhuWpBqZflpT1mQ5PkF.png');
    @endphp

    <header
        class="site-hero relative h-[350px] overflow-hidden bg-[#020815] text-white"
        style="--site-hero-image: url('{{ $siteHeroBackgroundImage }}');"
    >
        <nav class="relative z-10 mx-auto flex w-full max-w-[1130px] items-center justify-between px-4 pt-8 sm:px-8 sm:pt-16">
            <a
                href="{{ route('home') }}"
                class="inline-flex h-9 items-center gap-2 rounded-full bg-white px-4 text-base font-medium text-[#2f3035] shadow-sm transition hover:bg-slate-100 sm:h-10 sm:px-5"
            >
                <span aria-hidden="true">&lt;-</span>
                <span>Home</span>
            </a>

            <div class="flex items-center gap-3">
                <label class="hidden h-10 items-center rounded-full bg-white px-4 text-[#757982] shadow-sm sm:flex">
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
                        class="h-full w-36 border-0 bg-transparent p-0 text-base font-medium text-[#34363b] placeholder:text-[#9a9da5] focus:ring-0"
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

                <button
                    type="button"
                    aria-label="Toggle theme"
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

        <div class="relative z-10 mx-auto flex h-[180px] max-w-[900px] flex-col items-center justify-center px-6 pb-7 pt-8 text-center">
            <h1 class="font-syne text-[31px] font-bold leading-tight tracking-normal text-white sm:text-[48px]">
                Blog
            </h1>
        </div>
    </header>

    <main>
        <nav>
            <a
                href="{{ route('home', ['type' => 'blog']) }}"
                @if (! $selectedCategory) aria-current="page" @endif
            >
                All
            </a>

            @foreach ($categories as $category)
                <a
                    href="{{ route('blog.index', ['category' => $category->slug]) }}"
                    @if ($selectedCategory === $category->slug)
                        aria-current="page"
                    @endif
                >
                    {{ $category->name }}
                </a>
            @endforeach
        </nav>

        <br>

        @if ($posts->isEmpty())
            <p>No published blog posts yet.</p>
        @else
            <section>
                @foreach ($posts as $post)
                    <article>
                        @if ($post->thumbnail)
                            <a href="{{ route('blog.show', $post) }}">
                                <img
                                    src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($post->thumbnail) }}"
                                    alt="{{ $post->title }}"
                                    width="400"
                                >
                            </a>
                        @endif

                        @if ($post->category)
                            <p style="color: #6366F1;">
                                {{ $post->category->name }}
                            </p>
                        @endif

                        <h2>
                            <a href="{{ route('blog.show', $post) }}">
                                {{ $post->title }}
                            </a>
                        </h2>

                        @if ($post->excerpt)
                            <p>{{ $post->excerpt }}</p>
                        @endif

                        <p>
                            {{ $post->published_at->format('F j, Y') }}
                            · {{ $post->reading_time }} min read
                        </p>

                        <a href="{{ route('blog.show', $post) }}">
                            Read article
                        </a>
                    </article>

                    <hr>
                @endforeach
            </section>

            {{ $posts->links() }}
        @endif
    </main>

</body>
</html>

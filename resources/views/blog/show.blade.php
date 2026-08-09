<!DOCTYPE html>
<html lang="en">
<head>
    @php
        $siteSettings = \App\Models\SiteSetting::current();
        $blogHeroImage = $post->thumbnail
            ? \Illuminate\Support\Facades\Storage::disk('public')->url($post->thumbnail)
            : \App\Models\SiteSetting::fallbackHeroBackgroundUrl();
    @endphp

    <x-public.head
        :settings="$siteSettings"
        :title="$post->title"
        :description="$post->excerpt"
        :csrf="true"
    />
</head>

<body class="bg-[#f7f7f8] font-inter font-normal text-[#33343a] antialiased">
    @php
        $content = json_decode($post->body, true);
        $blocks = $content['blocks'] ?? [];
        $hasTwitterEmbed = collect($blocks)->contains(function ($block) {
            $data = $block['data'] ?? [];
            $source = $data['source'] ?? '';
            $sourceHost = strtolower(parse_url($source, PHP_URL_HOST) ?: '');

            return ($block['type'] ?? '') === 'embed'
                && ($data['service'] ?? '') === 'twitter'
                && filter_var($source, FILTER_VALIDATE_URL)
                && in_array($sourceHost, ['twitter.com', 'www.twitter.com', 'x.com', 'www.x.com'], true)
                && preg_match('#/status/\d+#', parse_url($source, PHP_URL_PATH) ?: '');
        });

        $hasInstagramEmbed = collect($blocks)->contains(function ($block) {
            $data = $block['data'] ?? [];
            $source = $data['source'] ?? '';
            $sourceHost = strtolower(parse_url($source, PHP_URL_HOST) ?: '');
            $sourcePath = parse_url($source, PHP_URL_PATH) ?: '';

            return ($block['type'] ?? '') === 'embed'
                && ($data['service'] ?? '') === 'instagram'
                && filter_var($source, FILTER_VALIDATE_URL)
                && in_array($sourceHost, ['instagram.com', 'www.instagram.com'], true)
                && preg_match('#^/(p|reel)/[^/]+/?$#', $sourcePath);
        });
    @endphp

    <div class="min-h-screen">
        <header
            class="site-hero relative h-[430px] overflow-hidden bg-[#020815] text-white"
            style="--site-hero-image: url('{{ $blogHeroImage }}');"
        >
            <nav class="relative z-10 mx-auto flex w-full max-w-[1320px] items-center justify-between px-6 pt-12 sm:px-16 sm:pt-[72px]">
                <button
                    type="button"
                    onclick="if (window.history.length > 1) { window.history.back(); } else { window.location.href = '{{ route('home', ['type' => 'blog']) }}'; }"
                    class="inline-flex h-10 items-center gap-2 rounded-full bg-white px-4 text-base font-medium text-[#2f3035] shadow-sm transition hover:bg-slate-100 sm:h-11 sm:px-5"
                >
                    <svg class="h-4 w-4 shrink-0" viewBox="0 0 20 20" fill="none" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12.5 4.5L7 10L12.5 15.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span>Back</span>
                </button>

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

            <div class="relative z-10 mx-auto flex max-w-[820px] flex-col items-center px-6 pb-8 pt-12 text-center sm:px-16 sm:pb-10 sm:pt-10">
                @if ($post->category)
                    <a
                        href="{{ route('blog.index', [
                            'category' => $post->category->slug,
                        ]) }}"
                        class="mb-5 rounded-full px-4 py-1.5 text-base font-medium leading-none text-white shadow-sm"
                        style="background-color: #6366F1;"
                    >
                        {{ $post->category->name }}
                    </a>
                @endif

                <h1 class="font-syne text-[31px] font-bold leading-tight tracking-normal text-white sm:text-[48px]">
                    {{ $post->title }}
                </h1>

                @if ($post->published_at)
                    <p class="mt-5 text-base font-medium leading-6 text-white/55">
                        {{ $post->published_at->format('d M, Y') }}
                        <span class="sr-only">, {{ $post->reading_time }} min read</span>
                    </p>
                @endif
            </div>
        </header>

        <main>
            <article>
                @if ($post->excerpt)
                    <section class="mx-auto max-w-[832px] px-6 pt-12 sm:px-8 sm:pt-16">
                        <p class="text-base font-normal leading-6 text-[#414349]">
                            {{ $post->excerpt }}
                        </p>
                    </section>
                @endif

                @if (! empty($blocks))
                    <section class="mx-auto max-w-[832px] px-6 pt-8 sm:px-8 sm:pt-12">
                        <div class="space-y-6 text-base font-normal leading-6 text-[#414349]">
                            @foreach ($blocks as $block)
                                @php
                                    $type = $block['type'] ?? '';
                                    $data = $block['data'] ?? [];
                                @endphp

                                @if ($type === 'paragraph')
                                    <p>
                                        {!! $data['text'] ?? '' !!}
                                    </p>

                                @elseif ($type === 'header')
                                    @php
                                        $level = $data['level'] ?? 2;

                                        $level = in_array(
                                            $level,
                                            [2, 3, 4, 5, 6]
                                        ) ? $level : 2;
                                    @endphp

                                    <h{{ $level }} class="font-syne pt-4 text-[26px] font-semibold leading-tight tracking-normal text-[#303136] sm:text-[34px]">
                                        {!! $data['text'] ?? '' !!}
                                    </h{{ $level }}>

                                @elseif ($type === 'quote')
                                    <blockquote class="border-l-4 border-[#08a9f4] pl-5 text-[#303136]">
                                        <p>
                                            {!! $data['text'] ?? '' !!}
                                        </p>

                                        @if (! empty($data['caption']))
                                            <cite class="mt-3 block text-base font-medium not-italic text-[#8f929b]">
                                                {{ $data['caption'] }}
                                            </cite>
                                        @endif
                                    </blockquote>

                                @elseif ($type === 'list')
                                    @php
                                        $listTag = ($data['style'] ?? '') === 'ordered'
                                            ? 'ol'
                                            : 'ul';
                                    @endphp

                                    <{{ $listTag }} class="{{ $listTag === 'ol' ? 'list-decimal' : 'list-disc' }} space-y-2 pl-6">
                                        @foreach ($data['items'] ?? [] as $item)
                                            <li>
                                                {!! is_array($item)
                                                    ? ($item['content'] ?? '')
                                                    : $item !!}
                                            </li>
                                        @endforeach
                                    </{{ $listTag }}>

                                @elseif ($type === 'code')
                                    <pre class="mx-auto w-full max-w-[720px] overflow-x-auto rounded-[8px] bg-[#191a1e] p-5 text-sm leading-6 text-white"><code>{{ $data['code'] ?? '' }}</code></pre>

                                @elseif ($type === 'image')
                                    @if (! empty($data['file']['url']))
                                        <figure class="my-10 flex flex-col items-center">
                                            <img
                                                src="{{ $data['file']['url'] }}"
                                                alt="{{ $data['caption'] ?? $post->title }}"
                                                class="block h-auto max-h-[520px] w-auto max-w-full rounded-[8px] object-contain shadow-[0_18px_28px_rgba(21,24,32,0.12)] sm:max-w-[680px]"
                                            >

                                            @if (! empty($data['caption']))
                                                <figcaption class="mt-3 text-center text-sm font-medium text-[#8f929b]">
                                                    {{ $data['caption'] }}
                                                </figcaption>
                                            @endif
                                        </figure>
                                    @endif

                                @elseif ($type === 'embed')
                                    @php
                                        $service = $data['service'] ?? '';
                                        $source = $data['source'] ?? '';
                                        $embed = $data['embed'] ?? '';
                                        $allowedServices = ['twitter', 'instagram', 'facebook'];
                                        $isApprovedService = in_array($service, $allowedServices, true);

                                        $urlHost = function (?string $url): string {
                                            return strtolower(parse_url($url ?: '', PHP_URL_HOST) ?: '');
                                        };

                                        $hostMatches = function (string $host, array $domains): bool {
                                            foreach ($domains as $domain) {
                                                if ($host === $domain || str_ends_with($host, '.' . $domain)) {
                                                    return true;
                                                }
                                            }

                                            return false;
                                        };

                                        $embedScheme = parse_url($embed, PHP_URL_SCHEME);
                                        $embedHost = $urlHost($embed);
                                        $embedPath = parse_url($embed, PHP_URL_PATH) ?: '';
                                        $sourceHost = $urlHost($source);
                                        $hasValidSourceUrl = (bool) filter_var($source, FILTER_VALIDATE_URL);

                                        $isSafeSource = $isApprovedService && $hasValidSourceUrl && match ($service) {
                                            'twitter' => $hostMatches($sourceHost, ['twitter.com', 'x.com'])
                                                && preg_match('#/status/\d+#', parse_url($source, PHP_URL_PATH) ?: ''),
                                            'instagram' => $hostMatches($sourceHost, ['instagram.com']),
                                            'facebook' => $hostMatches($sourceHost, ['facebook.com']),
                                            default => false,
                                        };

                                        $isSafeEmbed = $isApprovedService && $embedScheme === 'https' && match ($service) {
                                            'twitter' => $embedHost === 'platform.twitter.com'
                                                && $embedPath === '/embed/Tweet.html',
                                            'instagram' => $embedHost === 'www.instagram.com'
                                                && preg_match('#^/(p|reel)/[^/]+/embed/?$#', $embedPath),
                                            'facebook' => $embedHost === 'www.facebook.com'
                                                && in_array($embedPath, ['/plugins/post.php', '/plugins/video.php'], true),
                                            default => false,
                                        };

                                        $embedWidth = match ($service) {
                                            'twitter' => 560,
                                            'instagram' => 540,
                                            'facebook' => 560,
                                            default => 560,
                                        };

                                        $embedHeight = match ($service) {
                                            'twitter' => 520,
                                            'facebook' => 560,
                                            default => 420,
                                        };

                                        $embedTitle = match ($service) {
                                            'twitter' => 'Embedded X/Twitter post',
                                            'instagram' => 'Embedded Instagram post',
                                            'facebook' => 'Embedded Facebook post',
                                            default => 'Embedded social post',
                                        };

                                        $fallbackLabel = match ($service) {
                                            'twitter' => 'Open post on X/Twitter',
                                            'instagram' => 'Open post on Instagram',
                                            'facebook' => 'Open post on Facebook',
                                            default => 'Open embedded post',
                                        };

                                        $isTwitterEmbed = $service === 'twitter' && $isSafeSource;
                                        $isInstagramEmbed = $service === 'instagram' && $isSafeSource;
                                        $isIframeEmbed = $isSafeEmbed && $service !== 'instagram';
                                    @endphp

                                    @if ($isIframeEmbed || $isSafeSource)
                                        <figure class="my-10">
                                            @if ($isTwitterEmbed)
                                                <div class="mx-auto flex w-full max-w-[560px] justify-center">
                                                    <blockquote class="twitter-tweet" data-dnt="true">
                                                        <a href="{{ $source }}"></a>
                                                    </blockquote>
                                                </div>
                                            @elseif ($isInstagramEmbed)
                                                <div class="mx-auto w-full" style="max-width: 540px;">
                                                    <blockquote
                                                        class="instagram-media"
                                                        data-instgrm-permalink="{{ $source }}"
                                                        data-instgrm-version="14"
                                                        style="background: #fff; border: 0; margin: 0 auto; max-width: 540px; min-width: 0 !important; width: 100%;"
                                                    >
                                                        <a href="{{ $source }}" target="_blank" rel="noopener noreferrer"></a>
                                                    </blockquote>
                                                </div>
                                            @elseif ($isIframeEmbed)
                                                <div
                                                    class="mx-auto w-full overflow-hidden rounded-[8px] bg-white shadow-[0_18px_28px_rgba(21,24,32,0.12)]"
                                                    style="max-width: {{ $embedWidth }}px;"
                                                >
                                                    <iframe
                                                        src="{{ $embed }}"
                                                        title="{{ $embedTitle }}"
                                                        class="block w-full"
                                                        style="height: {{ $embedHeight }}px;"
                                                        loading="lazy"
                                                        referrerpolicy="strict-origin-when-cross-origin"
                                                        allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"
                                                        allowfullscreen
                                                    ></iframe>
                                                </div>
                                            @endif

                                            @if (! empty($data['caption']))
                                                <figcaption class="mt-3 text-center text-sm font-medium text-[#8f929b]">
                                                    {{ $data['caption'] }}
                                                </figcaption>
                                            @endif

                                            @if ($isSafeSource)
                                                <p class="mt-3 text-center text-sm font-medium">
                                                    <a
                                                        href="{{ $source }}"
                                                        target="_blank"
                                                        rel="noopener noreferrer"
                                                        class="border-b border-[#303136] text-[#303136] transition hover:text-[#08a9f4]"
                                                    >
                                                        {{ $fallbackLabel }}
                                                    </a>
                                                </p>
                                            @endif
                                        </figure>
                                    @endif
                                @endif
                            @endforeach
                        </div>
                    </section>
                @endif

                @if (! empty($post->tags))
                    <section class="mx-auto mt-32 max-w-[832px] px-6 sm:mt-44 sm:px-8">
                        <div class="flex flex-wrap gap-3">
                            @foreach ($post->tags as $tag)
                                <span class="rounded-full border border-[#c5c7ce] px-4 py-1.5 text-base font-normal leading-none text-[#a6a9b1]">
                                    {{ $tag }}
                                </span>
                            @endforeach
                        </div>
                    </section>
                @endif

                <section class="mx-auto mt-7 max-w-[832px] px-6 sm:px-8">
                    <div class="flex items-center gap-5 text-base font-normal leading-none text-[#a6a9b1]">
                        <button
                            id="like-button"
                            type="button"
                            data-url="{{ route('blog.like', $post) }}"
                            aria-pressed="{{ $hasLiked ? 'true' : 'false' }}"
                            @disabled($hasLiked)
                            class="blog-like-button inline-flex items-center gap-2 transition hover:text-[#ff3b3f] disabled:cursor-default disabled:opacity-100"
                        >
                            <svg
                                id="like-icon"
                                class="h-7 w-7 text-[#dc2626] transition"
                                viewBox="0 0 24 24"
                                fill="none"
                                aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path class="blog-like-icon-heart" d="M16.696 3C14.652 3 12.887 4.197 12 5.943C11.113 4.197 9.348 3 7.304 3C4.374 3 2 5.457 2 8.481C2 11.505 3.817 14.277 6.165 16.554C8.513 18.831 12 21 12 21C12 21 15.374 18.867 17.835 16.554C20.46 14.088 22 11.514 22 8.481C22 5.448 19.626 3 16.696 3Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <span id="like-label" class="sr-only">
                                {{ $hasLiked ? 'Already liked' : 'Like' }}
                            </span>

                            <span id="likes-count">
                                {{ $post->likes_count }}
                            </span>
                        </button>

                        <button
                            id="share-button"
                            type="button"
                            data-url="{{ route('blog.share', $post) }}"
                            data-title="{{ $post->title }}"
                            data-share-url="{{ route('blog.show', $post) }}"
                            class="inline-flex items-center gap-2 transition hover:text-[#303136] disabled:opacity-60"
                        >
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
                                <path d="M15.75 9H17.625C18.1223 9 18.5992 9.19754 18.9508 9.54917C19.3025 9.90081 19.5 10.3777 19.5 10.875V19.875C19.5 20.3723 19.3025 20.8492 18.9508 21.2008C18.5992 21.5525 18.1223 21.75 17.625 21.75H6.375C5.87772 21.75 5.40081 21.5525 5.04917 21.2008C4.69754 20.8492 4.5 20.3723 4.5 19.875V10.875C4.5 10.3777 4.69754 9.90081 5.04917 9.54917C5.40081 9.19754 5.87772 9 6.375 9H8.25M8.25 6L12 2.25L15.75 6M12 2.25V15.0469" stroke="#27272A" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <span class="sr-only">Share</span>

                            <span id="shares-count">
                                {{ $post->shares_count }}
                            </span>
                        </button>
                    </div>

                    <p
                        id="share-message"
                        class="mt-3 text-base font-medium text-[#8f929b]"
                        hidden
                    ></p>
                </section>

                @if ($post->author)
                    <hr class="mx-auto mt-14 max-w-[832px] border-[#e3e3e6] sm:mt-16">

                    <section class="mx-auto max-w-[832px] px-6 pt-12 sm:flex sm:items-center sm:gap-8 sm:px-8">
                        @if ($post->author->avatar)
                            <img
                                src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($post->author->avatar) }}"
                                alt="{{ $post->author->name }}"
                                class="h-[90px] w-[90px] rounded-full object-cover sm:h-[145px] sm:w-[145px]"
                            >
                        @endif

                        <div class="mt-6 sm:mt-0">
                            <h2 class="font-syne text-[26px] font-bold leading-tight tracking-normal text-[#303136] sm:text-[36px]">
                                {{ $post->author->name }}
                            </h2>

                            @if ($post->author->website)
                                <p class="mt-3 text-base font-medium leading-6">
                                    <a
                                        href="{{ $post->author->website }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="border-b border-[#303136] text-[#303136] transition hover:text-[#08a9f4]"
                                    >
                                        {{ $post->author->website }}
                                    </a>
                                </p>
                            @endif

                            @if ($post->author->bio)
                                <p class="mt-3 max-w-[580px] text-base font-medium leading-6 text-[#8f929b]">
                                    {{ $post->author->bio }}
                                </p>
                            @endif
                        </div>
                    </section>
                @endif

                @if ($relatedPosts->isNotEmpty())
                    <section class="mx-auto max-w-[1248px] px-6 pb-20 pt-20 sm:px-8 sm:pb-24 sm:pt-24">
                        <h2 class="text-center font-syne text-[26px] font-bold leading-tight tracking-normal text-[#303136] sm:text-[28px]">
                            You might also like...
                        </h2>

                        <div class="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-3">
                            @foreach ($relatedPosts as $relatedPost)
                                <article class="rounded-[8px] bg-white px-7 py-7 shadow-[0_18px_34px_rgba(21,24,32,0.14)]">
                                    <div class="flex items-start justify-between gap-4">
                                        @if ($relatedPost->category)
                                            <a
                                                href="{{ route('blog.index', [
                                                    'category' => $relatedPost->category->slug,
                                                ]) }}"
                                                class="inline-flex rounded-full border px-3 py-1 text-base font-normal leading-none"
                                                style="border-color: #6366F1; color: #6366F1;"
                                            >
                                                {{ $relatedPost->category->name }}
                                            </a>
                                        @endif

                                        @if ($relatedPost->published_at)
                                            <p class="shrink-0 text-base font-normal leading-6 text-[#a6a9b1]">
                                                {{ $relatedPost->published_at->format('d M, Y') }}
                                            </p>
                                        @endif
                                    </div>

                                    <h3 class="mt-8 font-syne text-[24px] font-bold leading-tight tracking-normal text-[#303136]">
                                        <a href="{{ route('blog.show', $relatedPost) }}">
                                            {{ $relatedPost->title }}
                                        </a>
                                    </h3>

                                    @if ($relatedPost->excerpt)
                                        <p class="mt-5 text-base font-normal leading-6 text-[#414349]">
                                            {{ Illuminate\Support\Str::limit($relatedPost->excerpt, 150) }}
                                        </p>
                                    @endif

                                    <div class="mt-6 flex items-end justify-between gap-4">
                                        <a
                                            href="{{ route('blog.show', $relatedPost) }}"
                                            class="inline-flex border-b border-[#303136] pb-1 text-base font-medium text-[#303136] transition hover:text-[#111216]"
                                        >
                                            Read Blog
                                            <span class="ml-1" aria-hidden="true">&nearr;</span>
                                        </a>

                                        <p class="inline-flex items-center gap-2 text-base font-normal leading-none text-[#a6a9b1]">
                                            <img
                                                src="{{ asset('images/icons/si_heart-line.svg') }}"
                                                alt=""
                                                aria-hidden="true"
                                                class="h-7 w-7"
                                            >
                                            {{ $relatedPost->likes_count }}
                                        </p>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </section>
                @else
                    <div class="h-24 sm:h-32" aria-hidden="true"></div>
                @endif
            </article>
        </main>

        <x-public.footer
            :settings="$siteSettings"
        />
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const csrfToken = document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute('content');

            const likeButton = document.getElementById('like-button');
            const likeLabel = document.getElementById('like-label');
            const likesCount = document.getElementById('likes-count');

            const shareButton = document.getElementById('share-button');
            const sharesCount = document.getElementById('shares-count');
            const shareMessage = document.getElementById('share-message');

            async function postInteraction(url) {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                });

                if (! response.ok) {
                    throw new Error('Request failed.');
                }

                return response.json();
            }

            likeButton.addEventListener('click', async () => {
                if (likeButton.disabled) {
                    return;
                }

                likeButton.disabled = true;

                try {
                    const data = await postInteraction(
                        likeButton.dataset.url
                    );

                    likeLabel.textContent = data.liked
                        ? 'Already liked'
                        : 'Like';

                    likesCount.textContent = data.likes_count;

                    likeButton.setAttribute(
                        'aria-pressed',
                        data.liked ? 'true' : 'false'
                    );

                    likeButton.disabled = data.liked;
                } catch (error) {
                    alert('The like could not be updated.');
                    likeButton.disabled = false;
                } finally {
                    if (likeButton.getAttribute('aria-pressed') === 'true') {
                        likeButton.disabled = true;
                    }
                }
            });

            shareButton.addEventListener('click', async () => {
                const title = shareButton.dataset.title;
                const url = shareButton.dataset.shareUrl;

                shareButton.disabled = true;
                shareMessage.hidden = true;

                try {
                    if (navigator.share) {
                        await navigator.share({
                            title,
                            url,
                        });
                    } else {
                        await navigator.clipboard.writeText(url);

                        shareMessage.textContent =
                            'Link copied to clipboard.';

                        shareMessage.hidden = false;
                    }

                    const data = await postInteraction(
                        shareButton.dataset.url
                    );

                    sharesCount.textContent = data.shares_count;
                } catch (error) {
                    if (error.name !== 'AbortError') {
                        shareMessage.textContent =
                            'The link could not be shared.';

                        shareMessage.hidden = false;
                    }
                } finally {
                    shareButton.disabled = false;
                }
            });
        });
    </script>

    @if ($hasTwitterEmbed)
        <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
    @endif

    @if ($hasInstagramEmbed)
        <script async src="https://www.instagram.com/embed.js"></script>
    @endif

</body>
</html>

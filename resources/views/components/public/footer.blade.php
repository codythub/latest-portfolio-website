@props([
    'active' => 'work',
    'profile' => null,
    'settings' => null,
    'footerClass' => 'bg-[#191a1e] px-6 py-8 text-base font-medium text-[#8f929b] sm:px-16 sm:py-10',
    'containerClass' => 'mx-auto flex max-w-[1320px] flex-col gap-6 sm:flex-row sm:items-center sm:justify-between',
    'navClass' => 'flex flex-wrap gap-4',
    'creditClass' => 'font-medium',
    'socialSize' => 'md',
])

@php
    $settings = $settings ?? \App\Models\SiteSetting::current();
    $creditText = $settings->footer_credit_text ?: \App\Models\SiteSetting::DEFAULT_FOOTER_CREDIT_TEXT;
    $links = [
        ['key' => 'work', 'label' => 'Work', 'href' => route('home')],
        ['key' => 'blog', 'label' => 'Blog', 'href' => route('home', ['type' => 'blog'])],
        ['key' => 'about', 'label' => 'About', 'href' => route('about')],
        ['key' => 'contact', 'label' => 'Get in Touch', 'href' => route('contact')],
    ];

    $heart = '♥';
    $heartPosition = strpos($creditText, $heart);
@endphp

<footer class="{{ $footerClass }}" data-public-footer>
    <div class="{{ $containerClass }}">
        <nav class="{{ $navClass }}">
            @foreach ($links as $link)
                <a
                    href="{{ $link['href'] }}"
                    @class([
                        'font-medium text-white' => $active === $link['key'],
                        'transition hover:text-white' => $active !== $link['key'],
                    ])
                >
                    {{ $link['label'] }}
                </a>
            @endforeach
        </nav>

        <p class="{{ $creditClass }}">
            @if ($heartPosition !== false)
                {{ substr($creditText, 0, $heartPosition) }}<span class="text-white" aria-hidden="true">{{ $heart }}</span>{{ substr($creditText, $heartPosition + strlen($heart)) }}
            @else
                {{ $creditText }}
            @endif
        </p>

        <div class="flex gap-4 text-white">
            <x-public.social-links
                :profile="$profile"
                :size="$socialSize"
            />
        </div>
    </div>
</footer>

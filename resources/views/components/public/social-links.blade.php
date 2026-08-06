@props([
    'profile' => null,
    'size' => 'md',
    'variant' => 'footer',
])

@php
    $profile = $profile ?? \App\Models\User::query()->first();

    $buttonClass = match ($variant) {
        'hero' => 'flex h-9 w-9 items-center justify-center rounded-full bg-white text-base font-medium text-[#18181b] shadow-sm sm:h-8 sm:w-8',
        default => $size === 'sm'
            ? 'flex h-7 w-7 items-center justify-center rounded-full bg-white text-xs font-medium text-[#191a1e]'
            : 'flex h-8 w-8 items-center justify-center rounded-full bg-white text-sm font-medium text-[#191a1e]',
    };

    $iconClasses = [
        'linkedin' => $size === 'sm' ? 'h-5 w-5' : 'h-[22px] w-[22px]',
        'twitter' => $size === 'sm' ? 'h-4 w-4' : 'h-[18px] w-[18px]',
        'github' => $size === 'sm' ? 'h-[22px] w-[22px]' : 'h-6 w-6',
    ];

    $links = [
        [
            'url' => $profile?->linkedin_url,
            'label' => 'LinkedIn',
            'icon' => 'linkedin',
        ],
        [
            'url' => $profile?->twitter_url,
            'label' => 'X',
            'icon' => 'twitter',
        ],
        [
            'url' => $profile?->github_url,
            'label' => 'GitHub',
            'icon' => 'github',
        ],
    ];
@endphp

@foreach ($links as $link)
    @if ($link['url'])
        <a
            href="{{ $link['url'] }}"
            target="_blank"
            rel="noopener noreferrer"
            aria-label="{{ $link['label'] }}"
            class="{{ $buttonClass }}"
        >
            <img
                src="{{ asset('images/icons/' . $link['icon'] . '.svg') }}"
                alt=""
                aria-hidden="true"
                class="{{ $iconClasses[$link['icon']] }}"
            >
        </a>
    @endif
@endforeach

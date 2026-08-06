@props([
    'title' => null,
    'description' => null,
    'settings' => null,
    'csrf' => false,
])

@php
    $settings = $settings ?? \App\Models\SiteSetting::current();
    $resolvedTitle = $title ?: $settings->default_seo_title;
    $resolvedDescription = $description ?: $settings->default_meta_description;
    $faviconUrl = $settings->faviconUrl();
@endphp

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

@if ($csrf)
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endif

@if ($resolvedDescription)
    <meta name="description" content="{{ $resolvedDescription }}">
@endif

@if ($faviconUrl)
    <link rel="icon" href="{{ $faviconUrl }}">
@endif

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
        document.documentElement.classList.add('js', 'public-loading');
    })();
</script>

@vite(['resources/css/app.css', 'resources/js/app.js'])

<title>{{ $resolvedTitle }}</title>

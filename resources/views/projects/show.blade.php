<!DOCTYPE html>
<html lang="en">
<head>
    @php
        $siteSettings = \App\Models\SiteSetting::current();
    @endphp

    <x-public.head
        :settings="$siteSettings"
        :title="$project->title"
        :description="$project->summary"
    />
</head>

<body class="bg-[#f7f7f8] font-inter font-normal text-[#33343a] antialiased">
    @php
        $heroImage = $project->cover_image ?: $project->thumbnail;
        $visibleSections = $project->sections->where('is_visible', true)->values();
        $projectCategoryColor = $project->category?->projectClassificationColor()
            ?? \App\Models\Category::DEVELOPMENT_COLOR;
    @endphp

    <div class="min-h-screen">
        <header
            class="site-hero relative h-[350px] overflow-hidden bg-[#020815] text-white"
            @if ($heroImage)
                style="--site-hero-image: url('{{ asset('storage/' . $heroImage) }}');"
            @endif
        >
            <nav class="relative z-10 mx-auto flex w-full max-w-[1130px] items-center justify-between px-4 pt-8 sm:px-8 sm:pt-16">
                <button
                    type="button"
                    onclick="if (window.history.length > 1) { window.history.back(); } else { window.location.href = '{{ url('/') }}'; }"
                    class="inline-flex h-9 items-center gap-2 rounded-full bg-white px-4 text-base font-medium text-[#2f3035] shadow-sm transition hover:bg-slate-100 sm:h-10 sm:px-5"
                >
                    <span aria-hidden="true">&lt;-</span>
                    <span>Back</span>
                </button>

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
                
                @if ($project->category)
                    <p
                        class="mb-5 rounded-full px-4 py-1.5 text-base font-medium leading-none text-white shadow-sm"
                        style="background-color: {{ $projectCategoryColor }};"
                    >
                        {{ $project->category->name }}
                    </p>
                @endif

                <h1 class="font-syne text-[31px] font-bold leading-tight tracking-normal text-white sm:text-[48px]">
                    {{ $project->title }}
                </h1>

                @if ($project->subtitle)
                    <p class="mt-4 text-base font-medium leading-6 text-white/55">
                        {{ $project->subtitle }}
                    </p>
                @endif
            </div>
        </header>

        <main class="mx-auto grid max-w-[1130px] grid-cols-1 px-4 py-6 sm:px-8 sm:py-20 lg:grid-cols-[245px_minmax(0,1fr)] lg:gap-24">
            <aside class="lg:border-r lg:border-[#e5e5e8] lg:pr-14">
                <dl class="grid grid-cols-2 gap-x-12 gap-y-5 text-base sm:grid-cols-4 lg:block lg:space-y-7">
                    <div>
                        <dt class="mb-2 font-medium text-[#b5b7bf]">Role</dt>
                        <dd class="font-medium text-[#3e3f44]">{{ $project->role }}</dd>
                    </div>

                    <div>
                        <dt class="mb-2 font-medium text-[#b5b7bf]">Timeline</dt>
                        <dd class="font-medium text-[#3e3f44]">{{ $project->timeline }}</dd>
                    </div>

                    <div>
                        <dt class="mb-2 font-medium text-[#b5b7bf]">Industry</dt>
                        <dd class="font-medium text-[#3e3f44]">{{ $project->industry }}</dd>
                    </div>

                    <div>
                        <dt class="mb-2 font-medium text-[#b5b7bf]">Year</dt>
                        <dd class="font-medium text-[#3e3f44]">{{ $project->year }}</dd>
                    </div>
                </dl>

                @if (!empty($project->tags))
                    <div class="mt-6 sm:mt-8">
                        <h2 class="mb-3 text-base font-medium text-[#b5b7bf]">{{ $project->tag_label ?: 'Tags' }}</h2>

                        <div class="flex flex-wrap gap-2">
                            @foreach ($project->tags as $tag)
                                <span class="rounded-full border border-[#c5c7ce] px-3 py-1 text-base font-normal leading-none text-[#a6a9b1]">
                                    {{ $tag }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($project->external_link_url)
                    <a
                        href="{{ $project->external_link_url }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="mt-6 inline-flex border-b border-[#3e3f44] pb-1 text-base font-medium text-[#3e3f44] transition hover:text-[#111216] sm:mt-8"
                    >
                        {{ $project->external_link_label ?: 'View project' }}
                        <span class="ml-1" aria-hidden="true">&nearr;</span>
                    </a>
                @endif
            </aside>

            <div class="mt-10 lg:mt-0">
                <div class="space-y-11 sm:space-y-14">
                    @foreach ($visibleSections as $section)
                        <section @class([
                            'border-b border-[#e3e3e6] pb-11 sm:pb-14',
                            'pt-0' => $loop->first,
                        ])>
                            @if ($loop->first)
                                <h2
                                    class="mb-7 text-base font-medium"
                                    style="color: {{ $projectCategoryColor }};"
                                >
                                    {{ $section->title }}
                                </h2>
                            @else
                                <h2 class="font-syne mb-7 text-[28px] font-semibold leading-tight tracking-normal text-[#303136] sm:text-[36px]">
                                    {{ $section->title }}
                                </h2>
                            @endif

                            <p class="max-w-[770px] whitespace-pre-line text-base font-normal leading-6 text-[#414349]">
                                {{ $section->body }}
                            </p>
                        </section>
                    @endforeach
                </div>

                @if ($project->galleryImages->isNotEmpty())
                    @php
                        $galleryItems = $project->galleryImages
                            ->map(function ($galleryImage) {
                                return [
                                    'src' => asset('storage/' . $galleryImage->image),
                                    'alt' => $galleryImage->alt_text,
                                ];
                            })
                            ->values()
                            ->all();
                    @endphp

                    <section
                        class="pt-11 sm:pt-14"
                        x-data="{
                            images: {{ Illuminate\Support\Js::from($galleryItems) }},
                            isOpen: false,
                            currentIndex: 0,
                            galleryIndex: 0,

                            openLightbox(index) {
                                this.currentIndex = index;
                                this.isOpen = true;
                            },

                            closeLightbox() {
                                this.isOpen = false;
                            },

                            previousImage() {
                                this.currentIndex =
                                    this.currentIndex === 0
                                        ? this.images.length - 1
                                        : this.currentIndex - 1;
                            },

                            nextImage() {
                                this.currentIndex =
                                    this.currentIndex === this.images.length - 1
                                        ? 0
                                        : this.currentIndex + 1;
                            },

                            previousGalleryImage() {
                                this.galleryIndex =
                                    this.galleryIndex === 0
                                        ? this.images.length - 1
                                        : this.galleryIndex - 1;
                            },

                            nextGalleryImage() {
                                this.galleryIndex =
                                    this.galleryIndex === this.images.length - 1
                                        ? 0
                                        : this.galleryIndex + 1;
                            },

                            visibleGalleryIndexes() {
                                if (this.images.length < 2) {
                                    return [this.galleryIndex];
                                }

                                return [
                                    this.galleryIndex,
                                    (this.galleryIndex + 1) % this.images.length
                                ];
                            }
                        }"
                        @keydown.escape.window="closeLightbox()"
                        @keydown.left.window="isOpen && previousImage()"
                        @keydown.right.window="isOpen && nextImage()"
                    >
                        <h2 class="font-syne mb-7 text-[28px] font-semibold leading-tight tracking-normal text-[#303136] sm:text-[36px]">
                            Gallery
                        </h2>

	                        <div class="relative mx-auto max-w-[320px] sm:max-w-none">
	                            <button
	                                type="button"
	                                @click="previousGalleryImage()"
	                                aria-label="Previous gallery image"
	                                class="absolute left-2 top-1/2 z-10 flex h-9 w-9 -translate-y-1/2 items-center justify-center rounded-full bg-white text-2xl leading-none text-[#9b9ea7] shadow-sm ring-1 ring-black/5 transition hover:text-[#303136] sm:-left-8 sm:-translate-x-1/2"
	                            >
	                                &lsaquo;
	                            </button>

	                            <div class="grid min-h-0 grid-cols-1 gap-4 sm:min-h-[215px] sm:grid-cols-2 sm:gap-5">
	                                <template x-for="(imageIndex, renderedIndex) in visibleGalleryIndexes()" :key="imageIndex">
	                                    <button
	                                        type="button"
	                                        @click="openLightbox(imageIndex)"
	                                        :class="renderedIndex === 1 ? 'hidden sm:block' : 'block'"
	                                        class="group mx-auto aspect-[187/116] w-full max-w-[280px] overflow-hidden rounded-[16px] bg-white shadow-[0_16px_28px_rgba(21,24,32,0.12)] sm:mx-0 sm:aspect-auto sm:max-w-none sm:rounded-[10px]"
	                                    >
	                                        <img
	                                            :src="images[imageIndex].src"
	                                            :alt="images[imageIndex].alt"
	                                            class="h-full w-full object-contain transition duration-300 group-hover:scale-[1.03] sm:h-[215px] sm:object-cover"
	                                        >
	                                    </button>
	                                </template>
                            </div>

	                            <button
	                                type="button"
	                                @click="nextGalleryImage()"
	                                aria-label="Next gallery image"
	                                class="absolute right-2 top-1/2 z-10 flex h-9 w-9 -translate-y-1/2 items-center justify-center rounded-full bg-white text-2xl leading-none text-[#9b9ea7] shadow-sm ring-1 ring-black/5 transition hover:text-[#303136] sm:-right-8 sm:translate-x-1/2"
	                            >
	                                &rsaquo;
	                            </button>
                        </div>

                        <div class="mt-6 flex justify-center gap-2">
                            <template x-for="(_, index) in images" :key="index">
                                <button
                                    type="button"
                                    @click="galleryIndex = index"
                                    aria-label="Show gallery image"
                                    :class="galleryIndex === index ? 'bg-[#303136]' : 'bg-[#d6d7db]'"
                                    class="h-1.5 w-1.5 rounded-full transition"
                                ></button>
                            </template>
                        </div>

                        <div
                            x-show="isOpen"
                            x-cloak
                            x-transition.opacity
                            @click.self="closeLightbox()"
                            class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/90 px-4 py-10"
                        >
                            <button
                                type="button"
                                @click="closeLightbox()"
                                aria-label="Close gallery"
                                class="absolute right-5 top-5 flex h-10 w-10 items-center justify-center rounded-full bg-white text-2xl leading-none text-[#303136] shadow-sm transition hover:bg-slate-100"
                            >
                                &times;
                            </button>

                            <button
                                type="button"
                                @click="previousImage()"
                                aria-label="Previous image"
                                class="absolute left-4 top-1/2 flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full bg-white text-3xl leading-none text-[#303136] shadow-sm transition hover:bg-slate-100 sm:left-8"
                            >
                                &lsaquo;
                            </button>

                            <div class="max-w-[88vw] text-center">
                                <img
                                    :src="images[currentIndex].src"
                                    :alt="images[currentIndex].alt"
                                    class="mx-auto max-h-[78vh] max-w-full rounded-[10px] object-contain shadow-2xl"
                                >

                                <p
                                    x-text="images[currentIndex].alt"
                                    class="mt-4 text-base font-medium leading-6 text-white/80"
                                ></p>

                                <p class="mt-2 text-base font-medium leading-6 text-white/70">
                                    <span x-text="currentIndex + 1"></span>
                                    /
                                    <span x-text="images.length"></span>
                                </p>
                            </div>

                            <button
                                type="button"
                                @click="nextImage()"
                                aria-label="Next image"
                                class="absolute right-4 top-1/2 flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full bg-white text-3xl leading-none text-[#303136] shadow-sm transition hover:bg-slate-100 sm:right-8"
                            >
                                &rsaquo;
                            </button>
                        </div>
                    </section>
                @endif

                @if ($nextProject)
                    <div class="flex justify-center py-16 sm:py-20">
                        <a
                            href="{{ route('projects.show', $nextProject) }}"
                            class="inline-flex items-center border-b border-[#303136] pb-2 text-base font-medium uppercase tracking-normal text-[#303136] transition hover:text-[#111216]"
                        >
                            View next project
                            <span class="ml-3 text-2xl font-normal leading-none" aria-hidden="true">-></span>
                        </a>
                    </div>
                @endif
            </div>
        </main>

        <x-public.footer
            active="work"
            :settings="$siteSettings"
            footer-class="bg-[#191a1e] px-4 py-8 text-base font-medium text-[#8f929b] sm:px-8 sm:py-10"
            container-class="mx-auto flex max-w-[1130px] flex-col gap-5 sm:flex-row sm:items-center sm:justify-between"
            social-size="sm"
        />
    </div>
</body>
</html>

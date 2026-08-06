@extends("layouts.admin")

@section("title", "Categories")

@section("content")
    @php
        $categorySections = [
            [
                'title' => 'Project categories',
                'description' => 'Used by the work filters.',
                'type' => 'project',
                'placeholder' => 'New project category',
                'error' => 'project_category',
                'categories' => $projectCategories,
                'count' => 'projects_count',
            ],
            [
                'title' => 'Blog categories',
                'description' => 'Used by the blog filters.',
                'type' => 'blog',
                'placeholder' => 'New blog category',
                'error' => 'blog_category',
                'categories' => $blogCategories,
                'count' => 'posts_count',
            ],
        ];
    @endphp

    <p class="-mt-4 mb-10 max-w-3xl text-[18px] font-medium leading-7 text-[#747984]">
        Group projects and blog posts into the filters shown on the public site.
    </p>

    <div class="space-y-8 lg:space-y-10">
        @foreach ($categorySections as $section)
            <section class="rounded-[22px] border border-[#dedfe4] bg-white px-5 py-6 shadow-[0_18px_55px_rgba(17,18,22,0.04)] sm:px-8 lg:px-9 lg:py-8">
                <div class="mb-7">
                    <h2 class="font-syne text-[22px] font-bold leading-tight text-[#111216]">
                        {{ $section['title'] }}
                    </h2>

                    <p class="mt-3 text-[17px] font-medium leading-6 text-[#747984]">
                        {{ $section['description'] }}
                    </p>
                </div>

                <form
                    method="POST"
                    action="{{ route('admin.categories.store') }}"
                    class="mb-9 flex flex-col gap-3 sm:flex-row"
                >
                    @csrf

                    <input
                        type="hidden"
                        name="type"
                        value="{{ $section['type'] }}"
                    >

                    <input
                        type="text"
                        name="name"
                        value="{{ old('type') === $section['type'] ? old('name') : '' }}"
                        placeholder="{{ $section['placeholder'] }}"
                        required
                        class="min-h-14 flex-1 rounded-full border border-[#dedfe4] bg-white px-5 text-[17px] font-medium text-[#191a1e] outline-none transition placeholder:text-[#8b9099] focus:border-[#191a1e]"
                    >

                    @if ($section['type'] === 'project')
                        <select
                            name="project_classification"
                            required
                            class="min-h-14 rounded-full border border-[#dedfe4] bg-white px-5 text-[17px] font-medium text-[#191a1e] outline-none transition focus:border-[#191a1e] sm:w-[210px]"
                        >
                            <option value="">Choose type</option>
                            <option
                                value="design"
                                @selected(old('type') === 'project' && old('project_classification') === 'design')
                            >
                                Design
                            </option>
                            <option
                                value="development"
                                @selected(old('type') === 'project' && old('project_classification') === 'development')
                            >
                                Development
                            </option>
                        </select>
                    @endif

                    <button
                        type="submit"
                        class="inline-flex min-h-14 items-center justify-center gap-3 rounded-full border border-[#dedfe4] bg-white px-7 text-[17px] font-bold text-[#191a1e] transition hover:border-[#191a1e] hover:bg-[#f6f7f8]"
                    >
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 5V19M5 12H19" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/>
                        </svg>
                        Add
                    </button>

                    @error($section['error'])
                        <p class="text-sm font-semibold text-[#ef233c] sm:hidden">{{ $message }}</p>
                    @enderror

                    @if ($section['type'] === 'project')
                        @error('project_classification')
                            <p class="text-sm font-semibold text-[#ef233c] sm:hidden">{{ $message }}</p>
                        @enderror
                    @endif
                </form>

                @error($section['error'])
                    <p class="-mt-6 mb-6 hidden text-sm font-semibold text-[#ef233c] sm:block">{{ $message }}</p>
                @enderror

                @if ($section['type'] === 'project')
                    @error('project_classification')
                        <p class="-mt-6 mb-6 hidden text-sm font-semibold text-[#ef233c] sm:block">{{ $message }}</p>
                    @enderror
                @endif

                @if ($section['categories']->isEmpty())
                    <p class="rounded-[8px] border border-dashed border-[#dedfe4] px-5 py-5 text-base font-medium text-[#747984]">
                        No {{ $section['type'] }} categories yet.
                    </p>
                @else
                    <div class="divide-y divide-[#dedfe4]">
                        @foreach ($section['categories'] as $category)
                            <article class="py-6 first:pt-0 last:pb-0">
                                <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                                    <div class="flex flex-wrap items-center gap-4">
                                        <strong class="text-[19px] font-bold leading-tight text-[#111216]">
                                            {{ $category->name }}
                                        </strong>

                                        <span class="inline-flex min-h-9 items-center rounded-full border border-[#dedfe4] bg-white px-4 text-base font-bold text-[#8b9099]">
                                            {{ $category->{$section['count']} }}
                                            {{ Str::plural('item', $category->{$section['count']}) }}
                                        </span>

                                        @if ($section['type'] === 'project')
                                            <span
                                                class="inline-flex min-h-9 items-center rounded-full border px-4 text-base font-bold"
                                                style="border-color: {{ $category->projectClassificationColor() }}; color: {{ $category->projectClassificationColor() }};"
                                            >
                                                {{ $category->projectClassificationLabel() }}
                                            </span>
                                        @endif
                                    </div>

                                    <div class="flex w-full flex-wrap items-center justify-end gap-3 sm:gap-4 lg:w-auto">
                                        <span class="mr-1 text-[17px] font-bold text-[#111216]">
                                            {{ $category->is_visible ? 'Visible' : 'Hidden' }}
                                        </span>

                                        <form
                                            method="POST"
                                            action="{{ route('admin.categories.visibility', $category) }}"
                                        >
                                            @csrf
                                            @method('PATCH')

                                            <button
                                                type="submit"
                                                aria-label="{{ $category->is_visible ? 'Hide category' : 'Show category' }}"
                                                class="relative h-10 w-[70px] rounded-full transition {{ $category->is_visible ? 'bg-[#149cf2]' : 'bg-[#d8dbe1]' }}"
                                            >
                                                <span
                                                    class="absolute top-1 h-8 w-8 rounded-full bg-white shadow-sm transition {{ $category->is_visible ? 'right-1' : 'left-1' }}"
                                                ></span>
                                            </button>
                                        </form>

                                        <form
                                            method="POST"
                                            action="{{ route('admin.categories.move', $category) }}"
                                        >
                                            @csrf
                                            @method('PATCH')

                                            <input
                                                type="hidden"
                                                name="direction"
                                                value="up"
                                            >

                                            <button
                                                type="submit"
                                                aria-label="Move {{ $category->name }} up"
                                                class="inline-flex h-12 w-12 items-center justify-center rounded-full border border-[#dedfe4] bg-white text-[#747984] transition hover:border-[#191a1e] hover:text-[#191a1e]"
                                            >
                                                <img
                                                    src="{{ asset('images/icons/admin-order-down.svg') }}"
                                                    alt=""
                                                    aria-hidden="true"
                                                    class="h-5 w-5 rotate-180"
                                                >
                                            </button>
                                        </form>

                                        <form
                                            method="POST"
                                            action="{{ route('admin.categories.move', $category) }}"
                                        >
                                            @csrf
                                            @method('PATCH')

                                            <input
                                                type="hidden"
                                                name="direction"
                                                value="down"
                                            >

                                            <button
                                                type="submit"
                                                aria-label="Move {{ $category->name }} down"
                                                class="inline-flex h-12 w-12 items-center justify-center rounded-full border border-[#dedfe4] bg-white text-[#747984] transition hover:border-[#191a1e] hover:text-[#191a1e]"
                                            >
                                                <img
                                                    src="{{ asset('images/icons/admin-order-down.svg') }}"
                                                    alt=""
                                                    aria-hidden="true"
                                                    class="h-5 w-5"
                                                >
                                            </button>
                                        </form>

                                        <details class="relative w-full sm:w-auto">
                                            <summary
                                                aria-label="Edit {{ $category->name }}"
                                                class="ml-auto flex h-12 w-12 cursor-pointer list-none items-center justify-center rounded-full border border-[#dedfe4] bg-white text-[#747984] transition hover:border-[#191a1e] hover:text-[#191a1e] [&::-webkit-details-marker]:hidden"
                                            >
                                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M5 19L8.5 18.3L18.6 8.2C19.4 7.4 19.4 6.1 18.6 5.3C17.8 4.5 16.5 4.5 15.7 5.3L5.7 15.4L5 19Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                                </svg>
                                                <span class="sr-only">Edit</span>
                                            </summary>

                                            <div class="mt-4 w-full rounded-[18px] border border-[#dedfe4] bg-white p-4 shadow-[0_18px_55px_rgba(17,18,22,0.08)] sm:absolute sm:right-0 sm:z-10 sm:w-[360px]">
                                                <form
                                                    method="POST"
                                                    action="{{ route('admin.categories.update', $category) }}"
                                                    class="flex flex-col gap-3"
                                                >
                                                    @csrf
                                                    @method('PATCH')

                                                    <input
                                                        type="text"
                                                        name="name"
                                                        value="{{ old(
                                                            'name',
                                                            $category->name
                                                        ) }}"
                                                        required
                                                        class="min-h-12 rounded-full border border-[#dedfe4] px-4 text-base font-medium text-[#191a1e] outline-none transition focus:border-[#191a1e]"
                                                    >

                                                    @error('name')
                                                        <p class="text-sm font-semibold text-[#ef233c]">{{ $message }}</p>
                                                    @enderror

                                                    @if ($section['type'] === 'project')
                                                        <select
                                                            name="project_classification"
                                                            required
                                                            class="min-h-12 rounded-full border border-[#dedfe4] px-4 text-base font-medium text-[#191a1e] outline-none transition focus:border-[#191a1e]"
                                                        >
                                                            <option
                                                                value="design"
                                                                @selected(old('project_classification', $category->resolvedProjectClassification()) === 'design')
                                                            >
                                                                Design
                                                            </option>
                                                            <option
                                                                value="development"
                                                                @selected(old('project_classification', $category->resolvedProjectClassification()) === 'development')
                                                            >
                                                                Development
                                                            </option>
                                                        </select>
                                                    @endif

                                                    <button
                                                        type="submit"
                                                        class="inline-flex min-h-12 items-center justify-center rounded-full bg-[#191a1e] px-5 text-base font-bold text-white transition hover:bg-[#303136]"
                                                    >
                                                        Save
                                                    </button>
                                                </form>

                                                @error('category_' . $category->id)
                                                    <p class="mt-3 text-sm font-semibold text-[#ef233c]">{{ $message }}</p>
                                                @enderror

                                                @if ($section['type'] === 'project')
                                                    @error('project_classification')
                                                        <p class="mt-3 text-sm font-semibold text-[#ef233c]">{{ $message }}</p>
                                                    @enderror
                                                @endif
                                            </div>
                                        </details>

                                        <form
                                            method="POST"
                                            action="{{ route('admin.categories.destroy', $category) }}"
                                            onsubmit="return confirm('Are you sure you want to delete this category?')"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                aria-label="Delete {{ $category->name }}"
                                                class="inline-flex h-12 w-12 items-center justify-center rounded-full border border-[#dedfe4] bg-white text-[#ef233c] transition hover:border-[#ef233c] hover:bg-[#fff0f2]"
                                            >
                                                <img
                                                    src="{{ asset('images/icons/admin-delete.svg') }}"
                                                    alt=""
                                                    aria-hidden="true"
                                                    class="h-5 w-5"
                                                >
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif
            </section>
        @endforeach
    </div>
@endsection

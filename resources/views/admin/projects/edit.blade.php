@extends("layouts.admin")

@section("title", "Edit Project")

@section("content")
    @php
        $savedSections = $project->sections
            ->map(function ($section) {
                return [
                    'title' => $section->title,
                    'body' => $section->body,
                    'is_visible' => $section->is_visible,
                ];
            })
            ->values()
            ->all();

        $formSections = old('sections', $savedSections);

        if (empty($formSections)) {
            $formSections = [
                [
                    'title' => '',
                    'body' => '',
                    'is_visible' => true,
                ],
            ];
        }

        $formGalleryItems = collect(old('gallery', [
            [
                'alt_text' => '',
            ],
        ]))
            ->map(fn ($item) => [
                'alt_text' => $item['alt_text'] ?? '',
            ])
            ->values()
            ->all();

        if (empty($formGalleryItems)) {
            $formGalleryItems = [
                [
                    'alt_text' => '',
                ],
            ];
        }

        $inputClass = 'min-h-14 w-full rounded-[18px] border border-[#dedfe4] bg-white px-5 text-base font-medium text-[#191a1e] outline-none transition placeholder:text-[#8b9099] focus:border-[#191a1e]';
        $textareaClass = 'w-full rounded-[18px] border border-[#dedfe4] bg-white px-5 py-4 text-base font-medium leading-7 text-[#191a1e] outline-none transition placeholder:text-[#8b9099] focus:border-[#191a1e]';
        $sectionClass = 'rounded-[22px] border border-[#dedfe4] bg-white px-5 py-6 shadow-[0_18px_55px_rgba(17,18,22,0.04)] sm:px-8 lg:px-9 lg:py-8';
    @endphp

    <form
        method="POST"
        action="{{ route('admin.projects.update', $project) }}"
        enctype="multipart/form-data"
    >
        @csrf
        @method('PUT')

        <div class="-mt-3 mb-10">
            <a
                href="{{ route('admin.projects.index') }}"
                class="mb-5 inline-flex items-center text-base font-bold text-[#747984] transition hover:text-[#191a1e]"
            >
                Back to projects
            </a>

            <p class="max-w-3xl text-[18px] font-medium leading-7 text-[#747984]">
                Everything here maps directly to the public case study page.
            </p>
        </div>

        @if ($errors->any())
            <div class="mb-6 rounded-[18px] border border-[#fecdd3] bg-[#fff1f2] px-5 py-4">
                <p class="text-base font-bold text-[#ef233c]">
                    Please correct the errors below.
                </p>
            </div>
        @endif

        <div class="max-w-[980px] space-y-6 pb-12">
            <section class="{{ $sectionClass }}">
                <div class="mb-7">
                    <h2 class="font-syne text-[22px] font-bold leading-tight text-[#111216]">
                        Basics
                    </h2>

                    <p class="mt-2 text-base font-bold text-[#8b9099]">
                        Shown on the portfolio grid and the case study hero.
                    </p>
                </div>

                <div class="space-y-6">
                    <div>
                        <label for="title" class="mb-3 block text-base font-bold text-[#191a1e]">
                            Project title <span class="text-[#ef233c]">*</span>
                        </label>

                        <input
                            type="text"
                            id="title"
                            name="title"
                            value="{{ old('title', $project->title) }}"
                            required
                            class="{{ $inputClass }}"
                        >

                        @error('title')
                            <p class="mt-2 text-sm font-semibold text-[#ef233c]">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="category_id" class="mb-3 block text-base font-bold text-[#191a1e]">
                            Project category <span class="text-[#ef233c]">*</span>
                        </label>

                        <select
                            id="category_id"
                            name="category_id"
                            required
                            class="{{ $inputClass }}"
                        >
                            <option value="">Choose project category</option>

                            @foreach ($categories as $category)
                                <option
                                    value="{{ $category->id }}"
                                    @selected(old('category_id', $project->category_id) == $category->id)
                                >
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('category_id')
                            <p class="mt-2 text-sm font-semibold text-[#ef233c]">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="subtitle" class="mb-3 block text-base font-bold text-[#191a1e]">
                            Subtitle
                        </label>

                        <input
                            type="text"
                            id="subtitle"
                            name="subtitle"
                            value="{{ old('subtitle', $project->subtitle) }}"
                            class="{{ $inputClass }}"
                        >
                    </div>

                    <div>
                        <label for="summary" class="mb-3 block text-base font-bold text-[#191a1e]">
                            Short summary
                        </label>

                        <textarea
                            id="summary"
                            name="summary"
                            rows="5"
                            class="{{ $textareaClass }}"
                        >{{ old('summary', $project->summary) }}</textarea>

                        <p class="mt-2 text-sm font-bold text-[#8b9099]">
                            Used on the project card. Keep it concise.
                        </p>
                    </div>
                </div>
            </section>

            <section class="{{ $sectionClass }}">
                <h2 class="mb-7 font-syne text-[22px] font-bold leading-tight text-[#111216]">
                    Project details
                </h2>

                <div class="grid gap-6 md:grid-cols-2">
                    <div>
                        <label for="role" class="mb-3 block text-base font-bold text-[#191a1e]">Your role</label>
                        <input type="text" id="role" name="role" value="{{ old('role', $project->role) }}" class="{{ $inputClass }}">
                    </div>

                    <div>
                        <label for="timeline" class="mb-3 block text-base font-bold text-[#191a1e]">Timeline</label>
                        <input type="text" id="timeline" name="timeline" value="{{ old('timeline', $project->timeline) }}" class="{{ $inputClass }}">
                    </div>

                    <div>
                        <label for="industry" class="mb-3 block text-base font-bold text-[#191a1e]">Industry</label>
                        <input type="text" id="industry" name="industry" value="{{ old('industry', $project->industry) }}" class="{{ $inputClass }}">
                    </div>

                    <div>
                        <label for="year" class="mb-3 block text-base font-bold text-[#191a1e]">Year</label>
                        <input type="text" id="year" name="year" value="{{ old('year', $project->year) }}" class="{{ $inputClass }}">
                    </div>
                </div>
            </section>

            <section
                x-data="{
                    sections: {{ Illuminate\Support\Js::from($formSections) }},

                    addSection() {
                        this.sections.push({
                            title: '',
                            body: '',
                            is_visible: true
                        });
                    },

                    removeSection(index) {
                        this.sections.splice(index, 1);
                    },

                    moveSectionUp(index) {
                        if (index === 0) {
                            return;
                        }

                        const section = this.sections[index];

                        this.sections[index] = this.sections[index - 1];
                        this.sections[index - 1] = section;
                    },

                    moveSectionDown(index) {
                        if (index === this.sections.length - 1) {
                            return;
                        }

                        const section = this.sections[index];

                        this.sections[index] = this.sections[index + 1];
                        this.sections[index + 1] = section;
                    }
                }"
                class="{{ $sectionClass }}"
            >
                <div class="mb-7">
                    <h2 class="font-syne text-[22px] font-bold leading-tight text-[#111216]">
                        Case study sections
                    </h2>

                    <p class="mt-2 text-base font-bold text-[#8b9099]">
                        Overview, problem, research, outcome, or any order.
                    </p>
                </div>

                <div class="space-y-4">
                    <template x-for="(section, index) in sections" :key="index">
                        <div class="rounded-[18px] border border-[#dedfe4] bg-white p-4">
                            <div class="mb-4 flex flex-wrap items-center gap-3">
                                <input
                                    type="text"
                                    :id="'section_' + index + '_title'"
                                    :name="'sections[' + index + '][title]'"
                                    x-model="section.title"
                                    placeholder="Overview"
                                    class="{{ $inputClass }}"
                                >

                                <button
                                    type="button"
                                    x-show="index > 0"
                                    @click="moveSectionUp(index)"
                                    aria-label="Move section up"
                                    class="inline-flex h-12 w-12 shrink-0 items-center justify-center rounded-full border border-[#dedfe4] bg-white text-[#747984] transition hover:border-[#191a1e] hover:text-[#191a1e]"
                                >
                                    <img src="{{ asset('images/icons/admin-order-down.svg') }}" alt="" aria-hidden="true" class="h-5 w-5 rotate-180">
                                </button>

                                <button
                                    type="button"
                                    x-show="index < sections.length - 1"
                                    @click="moveSectionDown(index)"
                                    aria-label="Move section down"
                                    class="inline-flex h-12 w-12 shrink-0 items-center justify-center rounded-full border border-[#dedfe4] bg-white text-[#747984] transition hover:border-[#191a1e] hover:text-[#191a1e]"
                                >
                                    <img src="{{ asset('images/icons/admin-order-down.svg') }}" alt="" aria-hidden="true" class="h-5 w-5">
                                </button>

                                <button
                                    type="button"
                                    x-show="sections.length > 1"
                                    @click="removeSection(index)"
                                    aria-label="Remove section"
                                    class="inline-flex h-12 w-12 shrink-0 items-center justify-center rounded-full border border-[#dedfe4] bg-white text-[#ef233c] transition hover:border-[#ef233c] hover:bg-[#fff0f2]"
                                >
                                    <img src="{{ asset('images/icons/admin-delete.svg') }}" alt="" aria-hidden="true" class="h-5 w-5">
                                </button>
                            </div>

                            <label :for="'section_' + index + '_body'" class="sr-only">Section content</label>
                            <textarea
                                :id="'section_' + index + '_body'"
                                :name="'sections[' + index + '][body]'"
                                x-model="section.body"
                                rows="6"
                                placeholder="Write the section content..."
                                class="{{ $textareaClass }}"
                            ></textarea>

                            <input type="hidden" :name="'sections[' + index + '][is_visible]'" value="0">

                            <label class="mt-4 flex items-center gap-3 text-base font-bold text-[#191a1e]">
                                <input
                                    type="checkbox"
                                    :name="'sections[' + index + '][is_visible]'"
                                    value="1"
                                    x-model="section.is_visible"
                                    class="h-5 w-5 rounded border-[#dedfe4] text-[#191a1e] focus:ring-[#191a1e]"
                                >
                                Visible on the public page
                            </label>
                        </div>
                    </template>
                </div>

                <button
                    type="button"
                    @click="addSection()"
                    class="mt-5 inline-flex min-h-12 items-center justify-center rounded-full border border-[#dedfe4] bg-white px-5 text-base font-bold text-[#191a1e] transition hover:border-[#191a1e] hover:bg-[#f6f7f8]"
                >
                    Add section
                </button>
            </section>

            <section class="{{ $sectionClass }}">
                <h2 class="mb-7 font-syne text-[22px] font-bold leading-tight text-[#111216]">
                    Tags
                </h2>

                <div class="space-y-5">
                    <div>
                        <label for="tag_label" class="mb-3 block text-base font-bold text-[#191a1e]">Tag section name</label>
                        <input
                            type="text"
                            id="tag_label"
                            name="tag_label"
                            value="{{ old('tag_label', $project->tag_label) }}"
                            placeholder="Focus Areas or Tech Stack"
                            class="{{ $inputClass }}"
                        >
                    </div>

                    <div class="grid gap-4 md:grid-cols-3">
                        <div>
                            <label for="tag_1" class="sr-only">Tag 1</label>
                            <input type="text" id="tag_1" name="tags[]" value="{{ old('tags.0', $project->tags[0] ?? '') }}" placeholder="Tag 1" class="{{ $inputClass }}">
                        </div>

                        <div>
                            <label for="tag_2" class="sr-only">Tag 2</label>
                            <input type="text" id="tag_2" name="tags[]" value="{{ old('tags.1', $project->tags[1] ?? '') }}" placeholder="Tag 2" class="{{ $inputClass }}">
                        </div>

                        <div>
                            <label for="tag_3" class="sr-only">Tag 3</label>
                            <input type="text" id="tag_3" name="tags[]" value="{{ old('tags.2', $project->tags[2] ?? '') }}" placeholder="Tag 3" class="{{ $inputClass }}">
                        </div>
                    </div>
                </div>
            </section>

            <section class="{{ $sectionClass }}">
                <h2 class="mb-7 font-syne text-[22px] font-bold leading-tight text-[#111216]">
                    Media
                </h2>

                <div class="grid gap-6 md:grid-cols-[260px_minmax(0,1fr)] md:items-center">
                    @if ($project->thumbnail)
                        <div class="aspect-[187/116] overflow-hidden rounded-[18px] bg-[#f4f5f7]">
                            <img
                                src="{{ asset('storage/' . $project->thumbnail) }}"
                                alt="{{ $project->title }} thumbnail"
                                class="h-full w-full object-contain"
                            >
                        </div>
                    @else
                        <div class="flex aspect-[187/116] items-center justify-center rounded-[18px] bg-[#f4f5f7] text-[#a6a9b1]">
                            <svg class="h-10 w-10" viewBox="0 0 24 24" fill="none" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
                                <path d="M4 5H20V19H4V5Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                <path d="M8 13L10.5 10.5L14 14L15.5 12.5L20 17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M8.5 8.5H8.51" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
                            </svg>
                        </div>
                    @endif

                    <div>
                        <label for="thumbnail" class="mb-3 block text-base font-bold text-[#191a1e]">
                            Replace project thumbnail
                        </label>

                        <input
                            type="file"
                            id="thumbnail"
                            name="thumbnail"
                            accept="image/png,image/jpeg,image/webp"
                            class="block w-full rounded-[18px] border border-[#dedfe4] bg-white px-5 py-4 text-sm font-bold text-[#747984]"
                        >

                        @error('thumbnail')
                            <p class="mt-2 text-sm font-semibold text-[#ef233c]">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </section>

            <section class="{{ $sectionClass }}">
                <div class="mb-7">
                    <h2 class="font-syne text-[22px] font-bold leading-tight text-[#111216]">
                        Existing gallery
                    </h2>

                    <p class="mt-2 text-base font-bold text-[#8b9099]">
                        Existing images can only be removed from this form.
                    </p>
                </div>

                @if ($project->galleryImages->isNotEmpty())
                    <div class="grid gap-4 md:grid-cols-2">
                        @foreach ($project->galleryImages as $galleryImage)
                            <div class="rounded-[18px] border border-[#dedfe4] bg-white p-4">
                                <div class="aspect-video overflow-hidden rounded-[14px] bg-[#f4f5f7]">
                                    <img
                                        src="{{ asset('storage/' . $galleryImage->image) }}"
                                        alt="{{ $galleryImage->alt_text }}"
                                        class="h-full w-full object-contain"
                                    >
                                </div>

                                <p class="mt-4 rounded-[14px] border border-[#dedfe4] px-4 py-3 text-base font-medium text-[#191a1e]">
                                    {{ $galleryImage->alt_text ?: 'No alt text' }}
                                </p>

                                <label class="mt-4 flex min-h-11 items-center justify-center gap-3 rounded-full border border-[#fecdd3] bg-white px-5 text-sm font-bold text-[#ef233c] transition hover:bg-[#fff0f2]">
                                    <input
                                        type="checkbox"
                                        name="remove_gallery_images[]"
                                        value="{{ $galleryImage->id }}"
                                        class="h-4 w-4 rounded border-[#fecdd3] text-[#ef233c] focus:ring-[#ef233c]"
                                    >
                                    Remove this image
                                </label>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="rounded-[8px] border border-dashed border-[#dedfe4] px-5 py-5 text-base font-medium text-[#747984]">
                        No gallery images yet.
                    </p>
                @endif
            </section>

            <section
                x-data="{
                    galleryItems: {{ Illuminate\Support\Js::from($formGalleryItems) }},

                    addGalleryItem() {
                        this.galleryItems.push({
                            alt_text: ''
                        });
                    },

                    removeGalleryItem(index) {
                        this.galleryItems.splice(index, 1);
                    }
                }"
                class="{{ $sectionClass }}"
            >
                <div class="mb-7">
                    <h2 class="font-syne text-[22px] font-bold leading-tight text-[#111216]">
                        Add gallery images
                    </h2>

                    <p class="mt-2 text-base font-bold text-[#8b9099]">
                        New images are appended to the existing gallery.
                    </p>
                </div>

                <div class="space-y-4">
                    <template x-for="(item, index) in galleryItems" :key="index">
                        <div class="rounded-[18px] border border-[#dedfe4] bg-white p-4">
                            <div class="grid gap-4 md:grid-cols-2">
                                <div>
                                    <label :for="'new_gallery_' + index + '_image'" class="mb-3 block text-base font-bold text-[#191a1e]">Image</label>
                                    <input
                                        type="file"
                                        :id="'new_gallery_' + index + '_image'"
                                        :name="'gallery[' + index + '][image]'"
                                        accept="image/png,image/jpeg,image/webp"
                                        class="block w-full rounded-[18px] border border-[#dedfe4] bg-white px-5 py-4 text-sm font-bold text-[#747984]"
                                    >
                                </div>

                                <div>
                                    <label :for="'new_gallery_' + index + '_alt_text'" class="mb-3 block text-base font-bold text-[#191a1e]">Alt text</label>
                                    <input
                                        type="text"
                                        :id="'new_gallery_' + index + '_alt_text'"
                                        :name="'gallery[' + index + '][alt_text]'"
                                        x-model="item.alt_text"
                                        placeholder="Describe what appears in the image"
                                        class="{{ $inputClass }}"
                                    >
                                </div>
                            </div>

                            <button
                                type="button"
                                x-show="galleryItems.length > 1"
                                @click="removeGalleryItem(index)"
                                class="mt-4 inline-flex min-h-11 items-center justify-center rounded-full border border-[#fecdd3] bg-white px-5 text-sm font-bold text-[#ef233c] transition hover:bg-[#fff0f2]"
                            >
                                Remove new gallery image
                            </button>
                        </div>
                    </template>
                </div>

                <button
                    type="button"
                    @click="addGalleryItem()"
                    class="mt-5 inline-flex min-h-12 items-center justify-center rounded-full border border-[#dedfe4] bg-white px-5 text-base font-bold text-[#191a1e] transition hover:border-[#191a1e] hover:bg-[#f6f7f8]"
                >
                    Add gallery image
                </button>
            </section>

            <section class="{{ $sectionClass }}">
                <h2 class="mb-7 font-syne text-[22px] font-bold leading-tight text-[#111216]">
                    External link
                </h2>

                <div class="grid gap-6 md:grid-cols-2">
                    <div>
                        <label for="external_link_label" class="mb-3 block text-base font-bold text-[#191a1e]">External link label</label>
                        <input
                            type="text"
                            id="external_link_label"
                            name="external_link_label"
                            value="{{ old('external_link_label', $project->external_link_label) }}"
                            placeholder="View on Behance or Visit Live Site"
                            class="{{ $inputClass }}"
                        >
                    </div>

                    <div>
                        <label for="external_link_url" class="mb-3 block text-base font-bold text-[#191a1e]">External link URL</label>
                        <input
                            type="url"
                            id="external_link_url"
                            name="external_link_url"
                            value="{{ old('external_link_url', $project->external_link_url) }}"
                            placeholder="https://example.com"
                            class="{{ $inputClass }}"
                        >

                        @error('external_link_url')
                            <p class="mt-2 text-sm font-semibold text-[#ef233c]">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </section>

            <section class="{{ $sectionClass }}">
                <h2 class="mb-7 font-syne text-[22px] font-bold leading-tight text-[#111216]">
                    Publishing
                </h2>

                <div class="grid gap-6 md:grid-cols-2">
                    <div>
                        <label for="display_order" class="mb-3 block text-base font-bold text-[#191a1e]">Display order</label>
                        <input
                            type="number"
                            id="display_order"
                            name="display_order"
                            value="{{ old('display_order', $project->display_order) }}"
                            min="0"
                            class="{{ $inputClass }}"
                        >
                    </div>

                    <div class="rounded-[18px] bg-[#f4f5f7] px-5 py-4">
                        <label class="flex h-full items-center gap-3 text-base font-bold text-[#191a1e]">
                            <input
                                type="checkbox"
                                name="is_published"
                                value="1"
                                @checked(old('is_published', $project->is_published))
                                class="h-5 w-5 rounded border-[#dedfe4] text-[#191a1e] focus:ring-[#191a1e]"
                            >
                            Publish this project
                        </label>
                    </div>
                </div>
            </section>

            <button
                type="submit"
                class="inline-flex min-h-14 w-full items-center justify-center rounded-full bg-[#191a1e] px-8 text-[17px] font-bold text-white transition hover:bg-[#303136]"
            >
                Update Project
            </button>
        </div>
    </form>
@endsection

@extends("layouts.admin")

@section("title", "Edit " . $post->title)

@section("content")
    <form
        id="post-form"
        method="POST"
        action="{{ route('admin.posts.update', $post) }}"
        enctype="multipart/form-data"
    >
        @csrf
        @method('PUT')

        <div class="-mt-3 mb-10">
            <div>
                <a
                    href="{{ route('admin.posts.index') }}"
                    class="mb-5 inline-flex items-center text-base font-bold text-[#747984] transition hover:text-[#191a1e]"
                >
                    Back to posts
                </a>

                <p class="max-w-3xl text-[18px] font-medium leading-7 text-[#747984]">
                    Content and publishing details for this blog article.
                </p>
            </div>
        </div>

        @if ($errors->any())
            <div class="mb-6 rounded-[18px] border border-[#fecdd3] bg-[#fff1f2] px-5 py-4">
                <p class="text-base font-bold text-[#ef233c]">
                    Please correct the errors below.
                </p>
            </div>
        @endif

        <div class="max-w-[980px] space-y-6 pb-12">
            <section class="rounded-[22px] border border-[#dedfe4] bg-white px-5 py-6 shadow-[0_18px_55px_rgba(17,18,22,0.04)] sm:px-8 lg:px-9 lg:py-8">
                <div class="mb-7">
                    <h2 class="font-syne text-[22px] font-bold leading-tight text-[#111216]">
                        Content
                    </h2>
                </div>

                <div class="space-y-6">
                    <div>
                        <label
                            for="title"
                            class="mb-3 block text-base font-bold text-[#191a1e]"
                        >
                            Post title <span class="text-[#ef233c]">*</span>
                        </label>

                        <input
                            type="text"
                            id="title"
                            name="title"
                            value="{{ old('title', $post->title) }}"
                            required
                            class="min-h-14 w-full rounded-[18px] border border-[#dedfe4] bg-white px-5 text-base font-medium text-[#191a1e] outline-none transition placeholder:text-[#8b9099] focus:border-[#191a1e]"
                        >

                        @error('title')
                            <p class="mt-2 text-sm font-semibold text-[#ef233c]">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label
                            for="excerpt"
                            class="mb-3 block text-base font-bold text-[#191a1e]"
                        >
                            Excerpt
                        </label>

                        <textarea
                            id="excerpt"
                            name="excerpt"
                            rows="5"
                            class="w-full rounded-[18px] border border-[#dedfe4] bg-white px-5 py-4 text-base font-medium leading-7 text-[#191a1e] outline-none transition placeholder:text-[#8b9099] focus:border-[#191a1e]"
                        >{{ old('excerpt', $post->excerpt) }}</textarea>

                        <p class="mt-2 text-sm font-bold text-[#8b9099]">
                            Shown on the blog listing card.
                        </p>

                        @error('excerpt')
                            <p class="mt-2 text-sm font-semibold text-[#ef233c]">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label
                            for="post-editor"
                            class="mb-3 block text-base font-bold text-[#191a1e]"
                        >
                            Body <span class="text-[#ef233c]">*</span>
                        </label>

                        {{-- Editor.js renders inside this container. --}}
                        <div
                            id="post-editor"
                            data-autofocus="false"
                            class="min-h-[540px] rounded-[18px] border border-[#dedfe4] bg-white px-5 py-4 text-base font-medium leading-7 text-[#191a1e] outline-none transition"
                        ></div>

                        {{-- Stores the Editor.js JSON before submission. --}}
                        <input
                            type="hidden"
                            id="body"
                            name="body"
                            value="{{ old('body', $post->body) }}"
                        >

                        @error('body')
                            <p class="mt-2 text-sm font-semibold text-[#ef233c]">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </section>

            <section class="rounded-[22px] border border-[#dedfe4] bg-white px-5 py-6 shadow-[0_18px_55px_rgba(17,18,22,0.04)] sm:px-8 lg:px-9 lg:py-8">
                <h2 class="mb-7 font-syne text-[22px] font-bold leading-tight text-[#111216]">
                    Publishing
                </h2>

                <div class="grid gap-6 md:grid-cols-2">
                    <div>
                        <label
                            for="category_id"
                            class="mb-3 block text-base font-bold text-[#191a1e]"
                        >
                            Category <span class="text-[#ef233c]">*</span>
                        </label>

                        <select
                            id="category_id"
                            name="category_id"
                            required
                            class="min-h-14 w-full rounded-[18px] border border-[#dedfe4] bg-white px-5 text-base font-medium text-[#191a1e] outline-none transition focus:border-[#191a1e]"
                        >
                            <option value="">Select a blog category</option>

                            @foreach ($categories as $category)
                                <option
                                    value="{{ $category->id }}"
                                    @selected(
                                        old('category_id', $post->category_id) == $category->id
                                    )
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
                        <label
                            for="display_order"
                            class="mb-3 block text-base font-bold text-[#191a1e]"
                        >
                            Display order <span class="text-[#ef233c]">*</span>
                        </label>

                        <input
                            type="number"
                            id="display_order"
                            name="display_order"
                            value="{{ old('display_order', $post->display_order) }}"
                            min="0"
                            required
                            class="min-h-14 w-full rounded-[18px] border border-[#dedfe4] bg-white px-5 text-base font-medium text-[#191a1e] outline-none transition focus:border-[#191a1e]"
                        >

                        @error('display_order')
                            <p class="mt-2 text-sm font-semibold text-[#ef233c]">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="rounded-[18px] bg-[#f4f5f7] px-5 py-4 md:col-span-2">
                        <label class="flex items-center justify-between gap-4">
                            <span>
                                <span class="block text-base font-bold text-[#191a1e]">
                                    Publish status
                                </span>
                                <span class="mt-1 block text-sm font-bold text-[#8b9099]">
                                    {{ $post->is_published ? 'Currently published' : 'Currently saved as draft' }}
                                </span>
                            </span>

                            <input
                                type="checkbox"
                                name="is_published"
                                value="1"
                                @checked(old('is_published', $post->is_published))
                                class="h-5 w-5 rounded border-[#dedfe4] text-[#191a1e] focus:ring-[#191a1e]"
                            >
                        </label>
                    </div>
                </div>
            </section>

            <section class="rounded-[22px] border border-[#dedfe4] bg-white px-5 py-6 shadow-[0_18px_55px_rgba(17,18,22,0.04)] sm:px-8 lg:px-9 lg:py-8">
                <h2 class="mb-7 font-syne text-[22px] font-bold leading-tight text-[#111216]">
                    Tags
                </h2>

                <div class="grid gap-4 md:grid-cols-3">
                    <div>
                        <label
                            for="tag_1"
                            class="sr-only"
                        >
                            Tag 1
                        </label>

                        <input
                            type="text"
                            id="tag_1"
                            name="tags[]"
                            value="{{ old('tags.0', $post->tags[0] ?? '') }}"
                            placeholder="Tag 1"
                            class="min-h-14 w-full rounded-[18px] border border-[#dedfe4] bg-white px-5 text-base font-medium text-[#191a1e] outline-none transition placeholder:text-[#8b9099] focus:border-[#191a1e]"
                        >

                        @error('tags.0')
                            <p class="mt-2 text-sm font-semibold text-[#ef233c]">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label
                            for="tag_2"
                            class="sr-only"
                        >
                            Tag 2
                        </label>

                        <input
                            type="text"
                            id="tag_2"
                            name="tags[]"
                            value="{{ old('tags.1', $post->tags[1] ?? '') }}"
                            placeholder="Tag 2"
                            class="min-h-14 w-full rounded-[18px] border border-[#dedfe4] bg-white px-5 text-base font-medium text-[#191a1e] outline-none transition placeholder:text-[#8b9099] focus:border-[#191a1e]"
                        >

                        @error('tags.1')
                            <p class="mt-2 text-sm font-semibold text-[#ef233c]">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label
                            for="tag_3"
                            class="sr-only"
                        >
                            Tag 3
                        </label>

                        <input
                            type="text"
                            id="tag_3"
                            name="tags[]"
                            value="{{ old('tags.2', $post->tags[2] ?? '') }}"
                            placeholder="Tag 3"
                            class="min-h-14 w-full rounded-[18px] border border-[#dedfe4] bg-white px-5 text-base font-medium text-[#191a1e] outline-none transition placeholder:text-[#8b9099] focus:border-[#191a1e]"
                        >

                        @error('tags.2')
                            <p class="mt-2 text-sm font-semibold text-[#ef233c]">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </section>

            <section class="rounded-[22px] border border-[#dedfe4] bg-white px-5 py-6 shadow-[0_18px_55px_rgba(17,18,22,0.04)] sm:px-8 lg:px-9 lg:py-8">
                <h2 class="mb-7 font-syne text-[22px] font-bold leading-tight text-[#111216]">
                    Cover image
                </h2>

                <div class="grid gap-6 md:grid-cols-[260px_minmax(0,1fr)] md:items-center">
                    @if ($post->thumbnail)
                        <div class="aspect-video overflow-hidden rounded-[18px] bg-[#f4f5f7]">
                            <img
                                src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($post->thumbnail) }}"
                                alt="{{ $post->title }}"
                                class="h-full w-full object-contain"
                            >
                        </div>
                    @else
                        <div class="flex aspect-video items-center justify-center rounded-[18px] bg-[#f4f5f7] text-[#a6a9b1]">
                            <svg class="h-10 w-10" viewBox="0 0 24 24" fill="none" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
                                <path d="M4 5H20V19H4V5Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                <path d="M8 13L10.5 10.5L14 14L15.5 12.5L20 17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M8.5 8.5H8.51" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
                            </svg>
                        </div>
                    @endif

                    <div>
                        <label
                            for="thumbnail"
                            class="mb-3 block text-base font-bold text-[#191a1e]"
                        >
                            Replace cover image
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

            <button
                type="submit"
                class="inline-flex min-h-14 w-full items-center justify-center rounded-full bg-[#191a1e] px-8 text-[17px] font-bold text-white transition hover:bg-[#303136]"
            >
                Update Post
            </button>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            window.requestAnimationFrame(() => {
                const adminMain = document.querySelector('[data-admin-main]');

                if (adminMain) {
                    adminMain.scrollTop = 0;
                    adminMain.scrollLeft = 0;
                }

                window.scrollTo(0, 0);
            });
        });
    </script>
@endpush

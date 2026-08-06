@extends('layouts.admin')

@section('title', 'Site Settings')

@section('content')
    @php
        $inputClass = 'min-h-14 w-full rounded-[18px] border border-[#dedfe4] bg-white px-5 text-base font-medium text-[#191a1e] outline-none transition placeholder:text-[#8b9099] focus:border-[#191a1e]';
        $textareaClass = 'w-full rounded-[18px] border border-[#dedfe4] bg-white px-5 py-4 text-base font-medium leading-7 text-[#191a1e] outline-none transition placeholder:text-[#8b9099] focus:border-[#191a1e]';
        $labelClass = 'mb-3 block text-base font-bold text-[#191a1e]';
        $errorClass = 'mt-2 text-sm font-semibold text-[#ef233c]';
        $sectionClass = 'rounded-[22px] border border-[#dedfe4] bg-white px-5 py-6 shadow-[0_18px_55px_rgba(17,18,22,0.04)] sm:px-8 lg:px-9 lg:py-8';
        $fileClass = 'min-h-14 w-full rounded-[18px] border border-[#dedfe4] bg-white px-5 py-3 text-base font-medium text-[#747984] outline-none transition file:mr-4 file:rounded-full file:border-0 file:bg-[#f4f5f7] file:px-4 file:py-2 file:text-sm file:font-bold file:text-[#191a1e] hover:file:bg-[#e8eaee] focus:border-[#191a1e]';

        $backgroundSettings = [
            [
                'label' => 'Homepage & Contact hero background',
                'description' => 'Used on the homepage hero and the public Get in Touch hero.',
                'name' => 'hero_background_image',
                'url' => $siteSettings->heroBackgroundUrl(),
                'path' => $siteSettings->hero_background_image,
            ],
            [
                'label' => 'About page background',
                'description' => 'Used only on the public About page image panel.',
                'name' => 'about_background_image',
                'url' => $siteSettings->aboutBackgroundUrl(),
                'path' => $siteSettings->about_background_image,
            ],
        ];
    @endphp

    <p class="-mt-4 mb-10 max-w-3xl text-[18px] font-medium leading-7 text-[#747984]">
        Manage public page backgrounds, footer copy, contact page copy, default SEO and favicon.
    </p>

    @if ($errors->any())
        <div class="mb-6 max-w-[980px] rounded-[18px] border border-[#fecdd3] bg-[#fff1f2] px-5 py-4">
            <p class="text-base font-bold text-[#ef233c]">
                Please correct the errors below.
            </p>
        </div>
    @endif

    <form
        method="POST"
        action="{{ route('admin.site-settings.update') }}"
        enctype="multipart/form-data"
        class="max-w-[980px] space-y-6 pb-12"
    >
        @csrf
        @method('PATCH')

        <section class="{{ $sectionClass }}">
            <div class="mb-7">
                <h2 class="font-syne text-[22px] font-bold leading-tight text-[#111216]">
                    Page Backgrounds
                </h2>

                <p class="mt-2 text-base font-bold text-[#8b9099]">
                    Uploaded files replace the current fallback image only on these public pages.
                </p>
            </div>

            <div class="space-y-7">
                @foreach ($backgroundSettings as $background)
                    <div>
                        <label for="{{ $background['name'] }}" class="{{ $labelClass }}">
                            {{ $background['label'] }}
                        </label>

                        <p class="-mt-1 mb-3 text-sm font-bold text-[#8b9099]">
                            {{ $background['description'] }}
                        </p>

                        <div class="grid gap-4 sm:grid-cols-[210px_minmax(0,1fr)] sm:items-center">
                            <div class="overflow-hidden rounded-[18px] border border-[#dedfe4] bg-[#f4f5f7]">
                                <img
                                    src="{{ $background['url'] }}"
                                    alt="{{ $background['label'] }} preview"
                                    class="h-[118px] w-full object-cover"
                                >
                            </div>

                            <div>
                                <input
                                    id="{{ $background['name'] }}"
                                    type="file"
                                    name="{{ $background['name'] }}"
                                    accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                                    class="{{ $fileClass }}"
                                >

                                <p class="mt-2 text-sm font-bold text-[#8b9099]">
                                    {{ $background['path'] ? 'Custom image uploaded.' : 'Using the current fallback image.' }}
                                </p>

                                @error($background['name'])
                                    <p class="{{ $errorClass }}">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="{{ $sectionClass }}">
            <div class="mb-7">
                <h2 class="font-syne text-[22px] font-bold leading-tight text-[#111216]">
                    Footer
                </h2>

                <p class="mt-2 text-base font-bold text-[#8b9099]">
                    Social URLs stay managed from Profile.
                </p>
            </div>

            <div>
                <label for="footer_credit_text" class="{{ $labelClass }}">
                    Footer credit text <span class="text-[#ef233c]">*</span>
                </label>

                <input
                    id="footer_credit_text"
                    type="text"
                    name="footer_credit_text"
                    value="{{ old('footer_credit_text', $siteSettings->footer_credit_text) }}"
                    required
                    class="{{ $inputClass }}"
                >

                @error('footer_credit_text')
                    <p class="{{ $errorClass }}">{{ $message }}</p>
                @enderror
            </div>
        </section>

        <section class="{{ $sectionClass }}">
            <div class="mb-7">
                <h2 class="font-syne text-[22px] font-bold leading-tight text-[#111216]">
                    Contact Page
                </h2>

                <p class="mt-2 text-base font-bold text-[#8b9099]">
                    Heading and intro copy shown above the public contact form.
                </p>
            </div>

            <div class="space-y-6">
                <div>
                    <label for="contact_heading" class="{{ $labelClass }}">
                        Contact page heading <span class="text-[#ef233c]">*</span>
                    </label>

                    <input
                        id="contact_heading"
                        type="text"
                        name="contact_heading"
                        value="{{ old('contact_heading', $siteSettings->contact_heading) }}"
                        required
                        class="{{ $inputClass }}"
                    >

                    @error('contact_heading')
                        <p class="{{ $errorClass }}">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="contact_description" class="{{ $labelClass }}">
                        Contact page description <span class="text-[#ef233c]">*</span>
                    </label>

                    <textarea
                        id="contact_description"
                        name="contact_description"
                        rows="3"
                        required
                        class="{{ $textareaClass }}"
                    >{{ old('contact_description', $siteSettings->contact_description) }}</textarea>

                    @error('contact_description')
                        <p class="{{ $errorClass }}">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </section>

        <section class="{{ $sectionClass }}">
            <div class="mb-7">
                <h2 class="font-syne text-[22px] font-bold leading-tight text-[#111216]">
                    Default SEO
                </h2>

                <p class="mt-2 text-base font-bold text-[#8b9099]">
                    Used when a public page does not provide a more specific title or description.
                </p>
            </div>

            <div class="space-y-6">
                <div>
                    <label for="default_seo_title" class="{{ $labelClass }}">
                        Default SEO title <span class="text-[#ef233c]">*</span>
                    </label>

                    <input
                        id="default_seo_title"
                        type="text"
                        name="default_seo_title"
                        value="{{ old('default_seo_title', $siteSettings->default_seo_title) }}"
                        required
                        class="{{ $inputClass }}"
                    >

                    @error('default_seo_title')
                        <p class="{{ $errorClass }}">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="default_meta_description" class="{{ $labelClass }}">
                        Default meta description <span class="text-[#ef233c]">*</span>
                    </label>

                    <textarea
                        id="default_meta_description"
                        name="default_meta_description"
                        rows="3"
                        required
                        class="{{ $textareaClass }}"
                    >{{ old('default_meta_description', $siteSettings->default_meta_description) }}</textarea>

                    @error('default_meta_description')
                        <p class="{{ $errorClass }}">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </section>

        <section class="{{ $sectionClass }}">
            <div class="mb-7">
                <h2 class="font-syne text-[22px] font-bold leading-tight text-[#111216]">
                    Favicon
                </h2>

                <p class="mt-2 text-base font-bold text-[#8b9099]">
                    Upload an ICO, PNG, SVG, JPG or WebP file.
                </p>
            </div>

            <div class="grid gap-4 sm:grid-cols-[120px_minmax(0,1fr)] sm:items-center">
                <div class="flex h-[100px] w-[100px] items-center justify-center rounded-[18px] border border-[#dedfe4] bg-[#f4f5f7]">
                    @if ($siteSettings->faviconUrl())
                        <img
                            src="{{ $siteSettings->faviconUrl() }}"
                            alt="Current favicon"
                            class="h-12 w-12 object-contain"
                        >
                    @else
                        <span class="font-syne text-[28px] font-bold text-[#8b9099]">LM</span>
                    @endif
                </div>

                <div>
                    <input
                        id="favicon"
                        type="file"
                        name="favicon"
                        accept=".ico,.png,.svg,.jpg,.jpeg,.webp,image/x-icon,image/png,image/svg+xml,image/jpeg,image/webp"
                        class="{{ $fileClass }}"
                    >

                    <p class="mt-2 text-sm font-bold text-[#8b9099]">
                        {{ $siteSettings->favicon_path ? 'Custom favicon uploaded.' : 'No custom favicon uploaded yet.' }}
                    </p>

                    @error('favicon')
                        <p class="{{ $errorClass }}">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </section>

        <div class="flex justify-end">
            <button
                type="submit"
                class="inline-flex min-h-14 w-full items-center justify-center rounded-full bg-[#191a1e] px-8 text-[17px] font-bold text-white transition hover:bg-[#303136] sm:w-auto"
            >
                Save settings
            </button>
        </div>
    </form>
@endsection

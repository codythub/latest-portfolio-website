@php
    $inputClass = 'min-h-14 w-full rounded-[18px] border border-[#dedfe4] bg-white px-5 text-base font-medium text-[#191a1e] outline-none transition placeholder:text-[#8b9099] focus:border-[#191a1e]';
    $textareaClass = 'w-full rounded-[18px] border border-[#dedfe4] bg-white px-5 py-4 text-base font-medium leading-7 text-[#191a1e] outline-none transition placeholder:text-[#8b9099] focus:border-[#191a1e]';
    $labelClass = 'mb-3 block text-base font-bold text-[#191a1e]';
    $errorClass = 'mt-2 text-sm font-semibold text-[#ef233c]';
    $cardClass = 'rounded-[22px] border border-[#dedfe4] bg-white px-5 py-6 shadow-[0_18px_55px_rgba(17,18,22,0.04)] sm:px-8 lg:px-9 lg:py-8';
@endphp

<section>
    <form
        id="send-verification"
        method="post"
        action="{{ route('verification.send') }}"
    >
        @csrf
    </form>

    <form
        method="post"
        action="{{ route('profile.update') }}"
        enctype="multipart/form-data"
        class="space-y-6"
    >
        @csrf
        @method('patch')

        <section class="{{ $cardClass }}">
            <div class="mb-7">
                <h2 class="font-syne text-[22px] font-bold leading-tight text-[#111216]">
                    Identity
                </h2>

                <p class="mt-2 text-base font-bold text-[#8b9099]">
                    Core author details used on the homepage, About page and blog posts.
                </p>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <div>
                    <label for="name" class="{{ $labelClass }}">
                        Name <span class="text-[#ef233c]">*</span>
                    </label>

                    <input
                        id="name"
                        name="name"
                        type="text"
                        value="{{ old('name', $user->name) }}"
                        required
                        autofocus
                        autocomplete="name"
                        class="{{ $inputClass }}"
                    >

                    @error('name')
                        <p class="{{ $errorClass }}">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="professional_title" class="{{ $labelClass }}">
                        Professional title
                    </label>

                    <input
                        id="professional_title"
                        name="professional_title"
                        type="text"
                        value="{{ old('professional_title', $user->professional_title) }}"
                        placeholder="Product Designer & Laravel Developer"
                        class="{{ $inputClass }}"
                    >

                    @error('professional_title')
                        <p class="{{ $errorClass }}">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6">
                <label for="bio" class="{{ $labelClass }}">
                    Author bio
                </label>

                <textarea
                    id="bio"
                    name="bio"
                    rows="5"
                    placeholder="Write a short third-person author bio..."
                    class="{{ $textareaClass }}"
                >{{ old('bio', $user->bio) }}</textarea>

                <p class="mt-2 text-sm font-bold text-[#8b9099]">
                    Used in the public blog author section.
                </p>

                @error('bio')
                    <p class="{{ $errorClass }}">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-6">
                <label for="about_intro" class="{{ $labelClass }}">
                    About introduction
                </label>

                <textarea
                    id="about_intro"
                    name="about_intro"
                    rows="7"
                    placeholder="Write the first-person introduction shown on the About page..."
                    class="{{ $textareaClass }}"
                >{{ old('about_intro', $user->about_intro) }}</textarea>

                <p class="mt-2 text-sm font-bold text-[#8b9099]">
                    Longer version used on the About page.
                </p>

                @error('about_intro')
                    <p class="{{ $errorClass }}">{{ $message }}</p>
                @enderror
            </div>
        </section>

        <section class="{{ $cardClass }}">
            <div class="mb-7">
                <h2 class="font-syne text-[22px] font-bold leading-tight text-[#111216]">
                    Profile image
                </h2>

                <p class="mt-2 text-base font-bold text-[#8b9099]">
                    Replacing this image updates the blog author avatar.
                </p>
            </div>

            <div class="flex flex-col gap-6 sm:flex-row sm:items-center">
                @if ($user->avatar)
                    <img
                        src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($user->avatar) }}"
                        alt="{{ $user->name }}"
                        class="h-28 w-28 rounded-full object-cover"
                    >
                @else
                    <div class="flex h-28 w-28 shrink-0 items-center justify-center rounded-full border border-[#dedfe4] bg-[#f4f5f7] font-syne text-[28px] font-bold text-[#8b9099]">
                        {{ Str::of($user->name)->substr(0, 1)->upper() }}
                    </div>
                @endif

                <div class="min-w-0 flex-1">
                    <label for="avatar" class="{{ $labelClass }}">
                        Replacement image
                    </label>

                    <input
                        id="avatar"
                        name="avatar"
                        type="file"
                        accept="image/png,image/jpeg,image/webp"
                        class="min-h-14 w-full rounded-[18px] border border-[#dedfe4] bg-white px-5 py-3 text-base font-medium text-[#747984] outline-none transition file:mr-4 file:rounded-full file:border-0 file:bg-[#f4f5f7] file:px-4 file:py-2 file:text-sm file:font-bold file:text-[#191a1e] hover:file:bg-[#e8eaee] focus:border-[#191a1e]"
                    >

                    <p class="mt-2 text-sm font-bold text-[#8b9099]">
                        PNG, JPG or WebP. Max 2MB.
                    </p>

                    @error('avatar')
                        <p class="{{ $errorClass }}">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </section>

        <section class="{{ $cardClass }}">
            <div class="mb-7">
                <h2 class="font-syne text-[22px] font-bold leading-tight text-[#111216]">
                    Résumé
                </h2>

                <p class="mt-2 text-base font-bold text-[#8b9099]">
                    Upload the PDF shown as a download on the public About page.
                </p>
            </div>

            <div>
                <label for="resume" class="{{ $labelClass }}">
                    PDF résumé
                </label>

                @if ($user->resume_path)
                    <p class="mb-3 rounded-[18px] border border-[#dedfe4] bg-[#f4f5f7] px-5 py-3 text-base font-bold text-[#747984]">
                        Current file: <span class="text-[#191a1e]">{{ basename($user->resume_path) }}</span>
                    </p>
                @endif

                <input
                    id="resume"
                    name="resume"
                    type="file"
                    accept="application/pdf,.pdf"
                    class="min-h-14 w-full rounded-[18px] border border-[#dedfe4] bg-white px-5 py-3 text-base font-medium text-[#747984] outline-none transition file:mr-4 file:rounded-full file:border-0 file:bg-[#f4f5f7] file:px-4 file:py-2 file:text-sm file:font-bold file:text-[#191a1e] hover:file:bg-[#e8eaee] focus:border-[#191a1e]"
                >

                <p class="mt-2 text-sm font-bold text-[#8b9099]">
                    PDF only. Max 5MB.
                </p>

                @error('resume')
                    <p class="{{ $errorClass }}">{{ $message }}</p>
                @enderror
            </div>
        </section>

        <section class="{{ $cardClass }}">
            <div class="mb-7">
                <h2 class="font-syne text-[22px] font-bold leading-tight text-[#111216]">
                    Contact and links
                </h2>

                <p class="mt-2 text-base font-bold text-[#8b9099]">
                    Public contact details and footer social links.
                </p>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <div>
                    <label for="email" class="{{ $labelClass }}">
                        Email <span class="text-[#ef233c]">*</span>
                    </label>

                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email', $user->email) }}"
                        required
                        autocomplete="username"
                        class="{{ $inputClass }}"
                    >

                    @error('email')
                        <p class="{{ $errorClass }}">{{ $message }}</p>
                    @enderror

                    @if (
                        $user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail
                        && ! $user->hasVerifiedEmail()
                    )
                        <p class="mt-3 text-sm font-medium text-[#747984]">
                            {{ __('Your email address is unverified.') }}

                            <button
                                form="send-verification"
                                class="font-bold text-[#191a1e] underline transition hover:text-[#08a9f4]"
                            >
                                {{ __('Click here to re-send the verification email.') }}
                            </button>
                        </p>
                    @endif
                </div>

                <div>
                    <label for="website" class="{{ $labelClass }}">
                        Website
                    </label>

                    <input
                        id="website"
                        name="website"
                        type="url"
                        value="{{ old('website', $user->website) }}"
                        placeholder="https://lanremalumi.com"
                        class="{{ $inputClass }}"
                    >

                    @error('website')
                        <p class="{{ $errorClass }}">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="location" class="{{ $labelClass }}">
                        Location
                    </label>

                    <input
                        id="location"
                        name="location"
                        type="text"
                        value="{{ old('location', $user->location) }}"
                        placeholder="Lagos, Nigeria"
                        class="{{ $inputClass }}"
                    >

                    @error('location')
                        <p class="{{ $errorClass }}">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="whatsapp" class="{{ $labelClass }}">
                        WhatsApp
                    </label>

                    <input
                        id="whatsapp"
                        name="whatsapp"
                        type="text"
                        value="{{ old('whatsapp', $user->whatsapp) }}"
                        placeholder="+234..."
                        class="{{ $inputClass }}"
                    >

                    @error('whatsapp')
                        <p class="{{ $errorClass }}">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="linkedin_url" class="{{ $labelClass }}">
                        LinkedIn URL
                    </label>

                    <input
                        id="linkedin_url"
                        name="linkedin_url"
                        type="url"
                        value="{{ old('linkedin_url', $user->linkedin_url) }}"
                        placeholder="https://linkedin.com/in/..."
                        class="{{ $inputClass }}"
                    >

                    @error('linkedin_url')
                        <p class="{{ $errorClass }}">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="twitter_url" class="{{ $labelClass }}">
                        X/Twitter URL
                    </label>

                    <input
                        id="twitter_url"
                        name="twitter_url"
                        type="url"
                        value="{{ old('twitter_url', $user->twitter_url) }}"
                        placeholder="https://x.com/..."
                        class="{{ $inputClass }}"
                    >

                    @error('twitter_url')
                        <p class="{{ $errorClass }}">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="github_url" class="{{ $labelClass }}">
                        GitHub URL
                    </label>

                    <input
                        id="github_url"
                        name="github_url"
                        type="url"
                        value="{{ old('github_url', $user->github_url) }}"
                        placeholder="https://github.com/..."
                        class="{{ $inputClass }}"
                    >

                    @error('github_url')
                        <p class="{{ $errorClass }}">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </section>

        <div class="flex justify-end">
            <button
                type="submit"
                class="inline-flex min-h-14 w-full items-center justify-center rounded-full bg-[#191a1e] px-7 text-[17px] font-bold text-white transition hover:bg-[#303136] sm:w-auto"
            >
                Save Changes
            </button>
        </div>
    </form>
</section>

@extends("layouts.admin")

@section("title", "Expertise")

@section("content")
    @php
        $expertiseSections = [
            [
                'title' => 'Tools',
                'description' => 'Manage the tools shown on the public homepage.',
                'items' => $tools,
                'empty' => 'No tools have been added yet.',
                'placeholder' => 'New tool',
                'type' => 'tool',
                'store' => 'admin.expertise.tools.store',
                'update' => 'admin.expertise.tools.update',
                'visibility' => 'admin.expertise.tools.visibility',
                'move' => 'admin.expertise.tools.move',
                'destroy' => 'admin.expertise.tools.destroy',
                'has_logo_upload' => true,
            ],
            [
                'title' => 'Skills',
                'description' => 'Manage the skills shown on the public homepage.',
                'items' => $skills,
                'empty' => 'No skills have been added yet.',
                'placeholder' => 'New skill',
                'type' => 'skill',
                'store' => 'admin.expertise.skills.store',
                'update' => 'admin.expertise.skills.update',
                'visibility' => 'admin.expertise.skills.visibility',
                'move' => 'admin.expertise.skills.move',
                'destroy' => 'admin.expertise.skills.destroy',
                'has_logo_upload' => false,
            ],
        ];
    @endphp

    <p class="-mt-4 mb-10 max-w-3xl text-[18px] font-medium leading-7 text-[#747984]">
        Manage the tools and skills displayed in the homepage sidebar.
    </p>

    <div class="space-y-8 lg:space-y-10">
        @foreach ($expertiseSections as $section)
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
                    action="{{ route($section['store']) }}"
                    class="mb-9 flex flex-col gap-3 lg:flex-row"
                    @if ($section['has_logo_upload']) enctype="multipart/form-data" @endif
                >
                    @csrf

                    <input type="hidden" name="form_type" value="{{ $section['type'] }}_store">

                    <input
                        type="text"
                        name="name"
                        value="{{ old('form_type') === $section['type'] . '_store' ? old('name') : '' }}"
                        placeholder="{{ $section['placeholder'] }}"
                        required
                        class="min-h-14 flex-1 rounded-full border border-[#dedfe4] bg-white px-5 text-[17px] font-medium text-[#191a1e] outline-none transition placeholder:text-[#8b9099] focus:border-[#191a1e]"
                    >

                    @if ($section['has_logo_upload'])
                        <input
                            type="file"
                            name="logo"
                            accept=".svg,.png,.jpg,.jpeg,.webp,image/svg+xml,image/png,image/jpeg,image/webp"
                            class="min-h-14 rounded-full border border-[#dedfe4] bg-white px-5 py-3 text-[15px] font-medium text-[#747984] outline-none transition file:mr-4 file:rounded-full file:border-0 file:bg-[#f4f5f7] file:px-4 file:py-2 file:text-sm file:font-bold file:text-[#191a1e] hover:file:bg-[#e8eaee] focus:border-[#191a1e] lg:w-[300px]"
                        >
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
                </form>

                @if (old('form_type') === $section['type'] . '_store')
                    @error('name')
                        <p class="-mt-6 mb-6 text-sm font-semibold text-[#ef233c]">{{ $message }}</p>
                    @enderror

                    @if ($section['has_logo_upload'])
                        @error('logo')
                            <p class="-mt-5 mb-6 text-sm font-semibold text-[#ef233c]">{{ $message }}</p>
                        @enderror
                    @endif
                @endif

                @if ($section['items']->isEmpty())
                    <p class="rounded-[8px] border border-dashed border-[#dedfe4] px-5 py-5 text-base font-medium text-[#747984]">
                        {{ $section['empty'] }}
                    </p>
                @else
                    <div class="divide-y divide-[#dedfe4]">
                        @foreach ($section['items'] as $item)
                            <article class="py-6 first:pt-0 last:pb-0">
                                <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                                    <div class="flex items-center gap-4">
                                        @if ($section['has_logo_upload'])
                                            @if ($item->logo_path)
                                                <img
                                                    src="{{ asset('storage/' . $item->logo_path) }}"
                                                    alt=""
                                                    aria-hidden="true"
                                                    class="h-11 w-11 rounded-full border border-[#dedfe4] bg-white object-contain p-2"
                                                >
                                            @else
                                                <span class="inline-flex h-11 w-11 items-center justify-center rounded-full border border-[#dedfe4] bg-[#f4f5f7] text-[#8b9099]">
                                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M12 3v4M12 17v4M3 12h4M17 12h4M6 6l3 3M15 15l3 3M18 6l-3 3M9 15l-3 3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                                    </svg>
                                                </span>
                                            @endif
                                        @endif

                                        <strong class="text-[19px] font-bold leading-tight text-[#111216]">
                                            {{ $item->name }}
                                        </strong>
                                    </div>

                                    <div class="flex w-full flex-wrap items-center justify-end gap-3 sm:gap-4 lg:w-auto">
                                        <span class="mr-1 text-[17px] font-bold text-[#111216]">
                                            {{ $item->is_visible ? 'Visible' : 'Hidden' }}
                                        </span>

                                        <form
                                            method="POST"
                                            action="{{ route($section['visibility'], $item) }}"
                                        >
                                            @csrf
                                            @method('PATCH')

                                            <button
                                                type="submit"
                                                aria-label="{{ $item->is_visible ? 'Hide item' : 'Show item' }}"
                                                class="relative h-10 w-[70px] rounded-full transition {{ $item->is_visible ? 'bg-[#149cf2]' : 'bg-[#d8dbe1]' }}"
                                            >
                                                <span
                                                    class="absolute top-1 h-8 w-8 rounded-full bg-white shadow-sm transition {{ $item->is_visible ? 'right-1' : 'left-1' }}"
                                                ></span>
                                            </button>
                                        </form>

                                        <form
                                            method="POST"
                                            action="{{ route($section['move'], $item) }}"
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
                                                aria-label="Move {{ $item->name }} up"
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
                                            action="{{ route($section['move'], $item) }}"
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
                                                aria-label="Move {{ $item->name }} down"
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
                                                aria-label="Edit {{ $item->name }}"
                                                class="ml-auto flex h-12 w-12 cursor-pointer list-none items-center justify-center rounded-full border border-[#dedfe4] bg-white text-[#747984] transition hover:border-[#191a1e] hover:text-[#191a1e] [&::-webkit-details-marker]:hidden"
                                            >
                                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M5 19L8.5 18.3L18.6 8.2C19.4 7.4 19.4 6.1 18.6 5.3C17.8 4.5 16.5 4.5 15.7 5.3L5.7 15.4L5 19Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                                </svg>
                                                <span class="sr-only">Edit</span>
                                            </summary>

                                            <div class="mt-4 w-full rounded-[18px] border border-[#dedfe4] bg-white p-4 shadow-[0_18px_55px_rgba(17,18,22,0.08)] sm:absolute sm:right-0 sm:z-10 sm:w-[380px]">
                                                <form
                                                    method="POST"
                                                    action="{{ route($section['update'], $item) }}"
                                                    class="flex flex-col gap-3"
                                                    @if ($section['has_logo_upload']) enctype="multipart/form-data" @endif
                                                >
                                                    @csrf
                                                    @method('PATCH')

                                                    <input type="hidden" name="form_type" value="{{ $section['type'] }}_update">
                                                    <input type="hidden" name="form_id" value="{{ $item->id }}">

                                                    <input
                                                        type="text"
                                                        name="name"
                                                        value="{{ old('form_type') === $section['type'] . '_update' && (int) old('form_id') === $item->id ? old('name') : $item->name }}"
                                                        required
                                                        class="min-h-12 rounded-full border border-[#dedfe4] px-4 text-base font-medium text-[#191a1e] outline-none transition focus:border-[#191a1e]"
                                                    >

                                                    @if ($section['has_logo_upload'])
                                                        @if ($item->logo_path)
                                                            <div class="flex items-center gap-3 rounded-[14px] border border-[#dedfe4] p-3">
                                                                <img
                                                                    src="{{ asset('storage/' . $item->logo_path) }}"
                                                                    alt=""
                                                                    aria-hidden="true"
                                                                    class="h-10 w-10 rounded-full bg-white object-contain p-1.5"
                                                                >
                                                                <span class="text-sm font-medium text-[#747984]">Current logo</span>
                                                            </div>
                                                        @endif

                                                        <input
                                                            type="file"
                                                            name="logo"
                                                            accept=".svg,.png,.jpg,.jpeg,.webp,image/svg+xml,image/png,image/jpeg,image/webp"
                                                            class="min-h-12 rounded-full border border-[#dedfe4] bg-white px-4 py-2 text-sm font-medium text-[#747984] outline-none transition file:mr-3 file:rounded-full file:border-0 file:bg-[#f4f5f7] file:px-3 file:py-2 file:text-sm file:font-bold file:text-[#191a1e] hover:file:bg-[#e8eaee] focus:border-[#191a1e]"
                                                        >
                                                    @endif

                                                    <button
                                                        type="submit"
                                                        class="inline-flex min-h-12 items-center justify-center rounded-full bg-[#191a1e] px-5 text-base font-bold text-white transition hover:bg-[#303136]"
                                                    >
                                                        Save
                                                    </button>
                                                </form>

                                                @if (old('form_type') === $section['type'] . '_update' && (int) old('form_id') === $item->id)
                                                    @error('name')
                                                        <p class="mt-3 text-sm font-semibold text-[#ef233c]">{{ $message }}</p>
                                                    @enderror

                                                    @if ($section['has_logo_upload'])
                                                        @error('logo')
                                                            <p class="mt-3 text-sm font-semibold text-[#ef233c]">{{ $message }}</p>
                                                        @enderror
                                                    @endif
                                                @endif
                                            </div>
                                        </details>

                                        <form
                                            method="POST"
                                            action="{{ route($section['destroy'], $item) }}"
                                            onsubmit="return confirm('Are you sure you want to delete this item?')"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                aria-label="Delete {{ $item->name }}"
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

@extends("layouts.admin")

@section("title", "Projects")

@section("content")
    <div class="-mt-3 mb-10 flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
        <p class="max-w-3xl text-[18px] font-medium leading-7 text-[#747984]">
            Manage the case studies shown on the portfolio.
        </p>

        <a
            href="{{ route('admin.projects.create') }}"
            class="inline-flex min-h-14 items-center justify-center gap-3 rounded-full bg-[#191a1e] px-7 text-[17px] font-bold text-white transition hover:bg-[#303136] lg:self-start"
        >
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 5V19M5 12H19" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/>
            </svg>
            Add new project
        </a>
    </div>

    <p class="mb-6 text-[17px] font-bold text-[#747984]">
        {{ $projects->count() }}
        {{ Str::plural('project', $projects->count()) }}
    </p>

    @if ($projects->isEmpty())
        <div class="rounded-[22px] border border-dashed border-[#dedfe4] bg-white px-6 py-10 text-center shadow-[0_18px_55px_rgba(17,18,22,0.04)]">
            <p class="text-base font-medium text-[#747984]">No projects have been added yet.</p>
        </div>
    @else
        <div class="space-y-5">
            @foreach ($projects as $project)
                <article class="rounded-[22px] border border-[#dedfe4] bg-white px-5 py-5 shadow-[0_18px_55px_rgba(17,18,22,0.04)] sm:px-6">
                    <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                        <div class="flex min-w-0 flex-col gap-5 sm:flex-row sm:items-center">
                            <div class="aspect-[187/116] w-full overflow-hidden rounded-[16px] bg-[#f4f5f7] sm:aspect-auto sm:h-[96px] sm:w-[132px]">
                                @if ($project->thumbnail)
                                    <img
                                        src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($project->thumbnail) }}"
                                        alt="{{ $project->title }} thumbnail"
                                        class="h-full w-full object-cover"
                                    >
                                @else
                                    <div class="flex h-full w-full items-center justify-center text-[#a6a9b1]">
                                        <svg class="h-9 w-9" viewBox="0 0 24 24" fill="none" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M4 5H20V19H4V5Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                            <path d="M8 13L10.5 10.5L14 14L15.5 12.5L20 17" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M8.5 8.5H8.51" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <div class="min-w-0">
                                <div class="mb-3 flex flex-wrap items-center gap-3">
                                    @if ($project->category)
                                        <span
                                            class="inline-flex min-h-9 items-center rounded-full border px-4 text-base font-bold"
                                            style="border-color: {{ $project->category->projectClassificationColor() }}; color: {{ $project->category->projectClassificationColor() }};"
                                        >
                                            {{ $project->category->name }}
                                        </span>
                                    @else
                                        <span class="inline-flex min-h-9 items-center rounded-full border border-[#dedfe4] px-4 text-base font-bold text-[#747984]">
                                            No category
                                        </span>
                                    @endif

                                    <span
                                        @class([
                                            'inline-flex min-h-9 items-center gap-2 rounded-full border px-4 text-base font-bold',
                                            'border-[#a7dcff] text-[#149cf2]' => $project->is_published,
                                            'border-[#dedfe4] text-[#747984]' => ! $project->is_published,
                                        ])
                                    >
                                        <span
                                            @class([
                                                'h-2 w-2 rounded-full',
                                                'bg-[#149cf2]' => $project->is_published,
                                                'bg-[#747984]' => ! $project->is_published,
                                            ])
                                        ></span>
                                        {{ $project->is_published ? 'Published' : 'Draft' }}
                                    </span>
                                </div>

                                <h2 class="font-syne text-[22px] font-bold leading-tight text-[#111216]">
                                    {{ $project->title }}
                                </h2>

                                @if ($project->subtitle)
                                    <p class="mt-2 text-[17px] font-medium leading-6 text-[#747984]">
                                        {{ $project->subtitle }}
                                    </p>
                                @endif

                                <div class="mt-3 flex flex-wrap items-center gap-x-2 gap-y-2 text-base font-medium text-[#747984]">
                                    @if (! empty($project->tags))
                                        @foreach ($project->tags as $tag)
                                            <span>{{ $tag }}</span>
                                            @unless ($loop->last)
                                                <span aria-hidden="true">·</span>
                                            @endunless
                                        @endforeach
                                    @else
                                        <span>No tags</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="flex shrink-0 flex-wrap gap-3 self-end lg:self-auto lg:justify-end">
                            <a
                                href="{{ route('admin.projects.edit', $project) }}"
                                aria-label="Edit {{ $project->title }}"
                                class="inline-flex h-12 w-12 items-center justify-center rounded-full border border-[#dedfe4] bg-white text-[#747984] transition hover:border-[#191a1e] hover:text-[#191a1e]"
                            >
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M5 19L8.5 18.3L18.6 8.2C19.4 7.4 19.4 6.1 18.6 5.3C17.8 4.5 16.5 4.5 15.7 5.3L5.7 15.4L5 19Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                                </svg>
                            </a>

                            <form
                                method="POST"
                                action="{{ route('admin.projects.destroy', $project) }}"
                                onsubmit="return confirm('Are you sure you want to delete this project?')"
                            >
                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    aria-label="Delete {{ $project->title }}"
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
@endsection

@extends("layouts.admin")

@section("title", "Dashboard")

@section("content")
    @php
        $cardClass = 'rounded-[22px] border border-[#dedfe4] bg-white px-5 py-6 shadow-[0_18px_55px_rgba(17,18,22,0.04)] sm:px-6';
        $metricClass = 'rounded-[18px] border border-[#dedfe4] bg-white px-5 py-5 shadow-[0_18px_55px_rgba(17,18,22,0.03)]';
        $periodLabels = [
            '7' => '7 days',
            '30' => '30 days',
            '12m' => '12 months',
        ];
    @endphp

    <p class="-mt-4 mb-9 max-w-4xl text-[18px] font-medium leading-7 text-[#747984]">
        Real portfolio traffic, content health and messages that need attention.
    </p>

    <div class="space-y-9 pb-10">
        <section>
            <div class="mb-5">
                <h2 class="font-syne text-[22px] font-bold leading-tight text-[#111216]">
                    Portfolio analytics
                </h2>

                <p class="mt-2 text-base font-medium text-[#747984]">
                    Based on public homepage, project detail and blog detail visits.
                </p>
            </div>

            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <article class="{{ $metricClass }}">
                    <p class="text-sm font-bold uppercase text-[#8b9099]">Total views</p>
                    <p class="mt-4 font-syne text-[34px] font-bold leading-none text-[#111216]">
                        {{ number_format($analytics['total_views']) }}
                    </p>
                    <p class="mt-3 text-sm font-bold text-[#8b9099]">All tracked public views</p>
                </article>

                <article class="{{ $metricClass }}">
                    <p class="text-sm font-bold uppercase text-[#8b9099]">Unique visitors</p>
                    <p class="mt-4 font-syne text-[34px] font-bold leading-none text-[#149cf2]">
                        {{ number_format($analytics['unique_visitors']) }}
                    </p>
                    <p class="mt-3 text-sm font-bold text-[#8b9099]">Estimated browser sessions</p>
                </article>

                <article class="{{ $metricClass }}">
                    <p class="text-sm font-bold uppercase text-[#8b9099]">Views this month</p>
                    <p class="mt-4 font-syne text-[34px] font-bold leading-none text-[#111216]">
                        {{ number_format($analytics['views_this_month']) }}
                    </p>
                    <p class="mt-3 text-sm font-bold text-[#8b9099]">Current calendar month</p>
                </article>

                <article class="{{ $metricClass }}">
                    <p class="text-sm font-bold uppercase text-[#8b9099]">Unread messages</p>
                    <p class="mt-4 font-syne text-[34px] font-bold leading-none text-[#ef233c]">
                        {{ number_format($analytics['unread_messages']) }}
                    </p>
                    <p class="mt-3 text-sm font-bold text-[#8b9099]">Waiting for a reply</p>
                </article>
            </div>
        </section>

        <section class="{{ $cardClass }}">
            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div>
                    <h2 class="font-syne text-[22px] font-bold leading-tight text-[#111216]">
                        Traffic overview
                    </h2>

                    <p class="mt-2 text-base font-medium text-[#747984]">
                        Portfolio views over time.
                    </p>
                </div>

                <nav class="flex w-full rounded-full border border-[#dedfe4] bg-[#f4f5f7] p-1.5 sm:w-auto">
                    @foreach ($periodLabels as $periodKey => $periodLabel)
                        @php
                            $isActivePeriod = $period === $periodKey;
                        @endphp

                        <a
                            href="{{ route('admin.dashboard', ['period' => $periodKey]) }}"
                            aria-pressed="{{ $isActivePeriod ? 'true' : 'false' }}"
                            @if ($isActivePeriod) aria-current="true" @endif
                            @class([
                                'flex min-h-10 flex-1 items-center justify-center rounded-full border px-4 text-sm font-bold transition sm:flex-none',
                                'border-[#dedfe4] bg-white text-[#191a1e] shadow-[0_8px_22px_rgba(17,18,22,0.16)]' => $isActivePeriod,
                                'border-transparent text-[#747984] hover:text-[#191a1e]' => ! $isActivePeriod,
                            ])
                            @if ($isActivePeriod) style="background-color: #ffffff; box-shadow: 0 8px 22px rgba(17, 18, 22, 0.16);" @endif
                        >
                            {{ $periodLabel }}
                        </a>
                    @endforeach
                </nav>
            </div>

            <div class="h-[280px] sm:h-[340px]">
                <canvas id="traffic-chart" aria-label="Portfolio traffic chart" role="img"></canvas>
            </div>
        </section>

        <section class="{{ $cardClass }}">
            <div class="mb-6 flex items-start justify-between gap-4">
                <div>
                    <h2 class="font-syne text-[22px] font-bold leading-tight text-[#111216]">
                        Most viewed content
                    </h2>

                    <p class="mt-2 text-base font-medium text-[#747984]">
                        Projects and blog posts ranked by real views.
                    </p>
                </div>

                <span class="inline-flex min-h-9 items-center rounded-full border border-[#a7dcff] px-4 text-sm font-bold text-[#149cf2]">
                    Top 3
                </span>
            </div>

            <div class="divide-y divide-[#dedfe4]">
                @forelse ($mostViewedContent as $index => $item)
                    <a
                        href="{{ $item['url'] }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="grid gap-4 py-5 transition hover:bg-[#fbfbfc] sm:grid-cols-[40px_minmax(0,1fr)_auto] sm:items-center"
                    >
                        <span class="text-base font-bold text-[#8b9099]">{{ $index + 1 }}</span>

                        <span class="min-w-0">
                            <span class="block truncate text-base font-bold text-[#191a1e]">{{ $item['title'] }}</span>
                            <span class="mt-1 block text-sm font-bold text-[#8b9099]">{{ $item['type'] }}</span>
                        </span>

                        <span class="inline-flex items-center gap-2 text-base font-bold text-[#747984]">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2.5 12S6 5.5 12 5.5S21.5 12 21.5 12 18 18.5 12 18.5 2.5 12 2.5 12Z" stroke="currentColor" stroke-width="2"/>
                                <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" stroke="currentColor" stroke-width="2"/>
                            </svg>
                            {{ number_format($item['views_count']) }}
                        </span>
                    </a>
                @empty
                    <p class="py-6 text-base font-medium text-[#747984]">
                        No public content views have been recorded yet.
                    </p>
                @endforelse
            </div>
        </section>

        <section>
            <div class="mb-5">
                <h2 class="font-syne text-[22px] font-bold leading-tight text-[#111216]">
                    Content overview
                </h2>

                <p class="mt-2 text-base font-medium text-[#747984]">
                    What exists in the portfolio right now.
                </p>
            </div>

            <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
                @foreach ([
                    'Total projects' => $contentOverview['total_projects'],
                    'Published projects' => $contentOverview['published_projects'],
                    'Draft projects' => $contentOverview['draft_projects'],
                    'Blog posts' => $contentOverview['blog_posts'],
                    'Draft blog posts' => $contentOverview['draft_blog_posts'],
                ] as $label => $value)
                    <article class="{{ $metricClass }}">
                        <p class="text-sm font-bold text-[#8b9099]">{{ $label }}</p>
                        <p class="mt-3 font-syne text-[26px] font-bold text-[#111216]">{{ number_format($value) }}</p>
                    </article>
                @endforeach
            </div>
        </section>

        <section class="{{ $cardClass }}">
            <div class="mb-6 flex items-start justify-between gap-4">
                <div>
                    <h2 class="font-syne text-[22px] font-bold leading-tight text-[#111216]">
                        Latest contact messages
                    </h2>

                    <p class="mt-2 text-base font-medium text-[#747984]">
                        Most recent enquiries from the portfolio contact form.
                    </p>
                </div>

                <a
                    href="{{ route('admin.contact-messages.index') }}"
                    class="shrink-0 text-base font-bold text-[#149cf2] transition hover:text-[#111216]"
                >
                    View all
                    <span aria-hidden="true">&rarr;</span>
                </a>
            </div>

            <div class="divide-y divide-[#dedfe4]">
                @forelse ($latestMessages as $message)
                    <a
                        href="{{ route('admin.contact-messages.show', $message) }}"
                        class="block py-5 transition hover:bg-[#fbfbfc]"
                    >
                        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                            <div class="flex min-w-0 items-center gap-3">
                                @unless ($message->is_read)
                                    <span class="h-2.5 w-2.5 shrink-0 rounded-full bg-[#ef233c]" aria-hidden="true"></span>
                                @endunless

                                <p class="truncate text-base font-bold text-[#191a1e]">{{ $message->name }}</p>

                                @unless ($message->is_read)
                                    <span class="inline-flex min-h-7 items-center rounded-full border border-[#ffb6c0] px-3 text-sm font-bold text-[#ef233c]">
                                        Unread
                                    </span>
                                @endunless
                            </div>

                            <p class="shrink-0 text-sm font-bold text-[#8b9099]">{{ $message->created_at->diffForHumans() }}</p>
                        </div>

                        <p class="mt-2 text-base font-medium leading-6 text-[#747984]">
                            {{ Str::limit($message->message, 150) }}
                        </p>
                    </a>
                @empty
                    <p class="py-6 text-base font-medium text-[#747984]">
                        No contact messages yet.
                    </p>
                @endforelse
            </div>
        </section>

        <section class="{{ $cardClass }}">
            <div class="mb-6">
                <h2 class="font-syne text-[22px] font-bold leading-tight text-[#111216]">
                    Needs attention
                </h2>

                <p class="mt-2 text-base font-medium text-[#747984]">
                    Open tasks across the portfolio.
                </p>
            </div>

            <div class="grid gap-4 lg:grid-cols-2">
                @forelse ($needsAttention as $item)
                    <a
                        href="{{ $item['href'] }}"
                        class="flex min-h-14 items-center justify-between gap-4 rounded-[14px] bg-[#f4f5f7] px-5 py-4 transition hover:bg-[#eceef2]"
                    >
                        <span class="text-base font-bold text-[#191a1e]">{{ $item['label'] }}</span>
                        <span class="inline-flex min-h-8 shrink-0 items-center rounded-full border border-[#dedfe4] bg-white px-4 text-sm font-bold text-[#747984]">
                            {{ $item['action'] }}
                        </span>
                    </a>
                @empty
                    <p class="rounded-[14px] bg-[#f4f5f7] px-5 py-4 text-base font-medium text-[#747984]">
                        Nothing needs attention right now.
                    </p>
                @endforelse
            </div>
        </section>

        <section class="{{ $cardClass }}">
            <div class="mb-6">
                <h2 class="font-syne text-[22px] font-bold leading-tight text-[#111216]">
                    Recently updated content
                </h2>

                <p class="mt-2 text-base font-medium text-[#747984]">
                    Latest edits to projects and posts.
                </p>
            </div>

            <div class="divide-y divide-[#dedfe4]">
                @forelse ($recentContent as $item)
                    <a
                        href="{{ $item['href'] }}"
                        class="flex flex-col gap-2 py-5 transition hover:bg-[#fbfbfc] sm:flex-row sm:items-center sm:justify-between"
                    >
                        <span>
                            <span class="block text-base font-bold text-[#191a1e]">{{ $item['title'] }}</span>
                            <span class="mt-1 block text-sm font-bold text-[#8b9099]">
                                {{ $item['type'] }} · {{ $item['status'] }}
                            </span>
                        </span>

                        <span class="text-sm font-bold text-[#8b9099]">{{ $item['date']->format('Y-m-d') }}</span>
                    </a>
                @empty
                    <p class="py-6 text-base font-medium text-[#747984]">
                        No content has been updated yet.
                    </p>
                @endforelse
            </div>
        </section>

        <section class="{{ $cardClass }}">
            <div class="mb-6">
                <h2 class="font-syne text-[22px] font-bold leading-tight text-[#111216]">
                    Quick actions
                </h2>

                <p class="mt-2 text-base font-medium text-[#747984]">
                    Jump straight into common tasks.
                </p>
            </div>

            <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
                <a
                    href="{{ route('admin.projects.create') }}"
                    class="inline-flex min-h-14 items-center justify-center gap-3 rounded-full bg-[#191a1e] px-6 text-base font-bold text-white transition hover:bg-[#303136]"
                >
                    <span class="text-xl" aria-hidden="true">+</span>
                    Add project
                </a>

                <a
                    href="{{ route('admin.posts.create') }}"
                    class="inline-flex min-h-14 items-center justify-center gap-3 rounded-full border border-[#dedfe4] bg-white px-6 text-base font-bold text-[#191a1e] transition hover:border-[#191a1e]"
                >
                    Add blog post
                </a>

                <a
                    href="{{ route('profile.edit') }}"
                    class="inline-flex min-h-14 items-center justify-center gap-3 rounded-full border border-[#dedfe4] bg-white px-6 text-base font-bold text-[#191a1e] transition hover:border-[#191a1e]"
                >
                    Edit profile
                </a>

                <a
                    href="{{ route('home') }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="inline-flex min-h-14 items-center justify-center gap-3 rounded-full border border-[#dedfe4] bg-white px-6 text-base font-bold text-[#191a1e] transition hover:border-[#191a1e]"
                >
                    View portfolio
                </a>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const canvas = document.getElementById('traffic-chart');

            if (! canvas || ! window.Chart) {
                return;
            }

            const chartData = @json($chartData);
            const context = canvas.getContext('2d');
            const gradient = context.createLinearGradient(0, 0, 0, 320);
            gradient.addColorStop(0, 'rgba(20, 156, 242, 0.22)');
            gradient.addColorStop(1, 'rgba(20, 156, 242, 0)');

            new window.Chart(canvas, {
                type: 'line',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        data: chartData.values,
                        borderColor: '#149cf2',
                        backgroundColor: gradient,
                        borderWidth: 3,
                        fill: true,
                        tension: 0.38,
                        pointRadius: 0,
                        pointHitRadius: 10,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            displayColors: false,
                            callbacks: {
                                label: (context) => `${context.parsed.y} views`,
                            },
                        },
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: {
                                color: '#8b9099',
                                font: { weight: 700 },
                            },
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0,
                                color: '#8b9099',
                                font: { weight: 700 },
                            },
                            grid: { color: '#e5e7eb' },
                        },
                    },
                },
            });
        });
    </script>
@endpush

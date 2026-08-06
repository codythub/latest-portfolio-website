@php
    $statusMessages = [
        'profile-updated' => ['type' => 'success', 'message' => 'Profile updated successfully.'],
        'password-updated' => ['type' => 'success', 'message' => 'Password updated successfully.'],
        'verification-link-sent' => ['type' => 'success', 'message' => 'Verification link sent successfully.'],
    ];

    $statusToast = session('status')
        ? ($statusMessages[session('status')] ?? [
            'type' => 'info',
            'message' => session('status'),
        ])
        : null;

    $toasts = collect([
        ['type' => 'success', 'message' => session('success')],
        ['type' => 'error', 'message' => session('error')],
        ['type' => 'warning', 'message' => session('warning')],
        ['type' => 'info', 'message' => session('info')],
        $statusToast,
    ])
        ->filter(fn ($toast) => $toast && filled($toast['message']))
        ->values();

    $toastStyles = [
        'success' => [
            'icon' => 'bg-[#191a1e] text-white',
        ],
        'error' => [
            'icon' => 'bg-[#ef233c] text-white',
        ],
        'warning' => [
            'icon' => 'bg-amber-500 text-white',
        ],
        'info' => [
            'icon' => 'bg-[#149cf2] text-white',
        ],
    ];
@endphp

@if ($toasts->isNotEmpty())
    <div class="fixed right-4 top-4 z-[90] flex w-[calc(100%-2rem)] max-w-[380px] flex-col items-end gap-3 sm:right-6 sm:top-6">
        @foreach ($toasts as $index => $toast)
            @php
                $style = $toastStyles[$toast['type']] ?? $toastStyles['info'];
            @endphp

            <div
                x-data="{ show: true }"
                x-show="show"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="translate-y-2 opacity-0 sm:translate-x-3 sm:translate-y-0"
                x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="translate-y-2 opacity-0 sm:translate-x-3 sm:translate-y-0"
                x-init="setTimeout(() => show = false, {{ 4200 + ($index * 450) }})"
                class="w-fit max-w-full rounded-[10px] border border-[#e9eaee] bg-white shadow-[0_14px_35px_rgba(17,18,22,0.14)]"
                role="status"
            >
                <div class="flex items-center gap-3 px-5 py-4">
                    <span class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-full {{ $style['icon'] }}">
                        @if ($toast['type'] === 'success')
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
                                <path d="M5 12.5L10 17L19 7" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        @elseif ($toast['type'] === 'error')
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 8V12M12 16H12.01M5 19L12 5L19 19H5Z" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        @elseif ($toast['type'] === 'warning')
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 8V12M12 16H12.01M5 19L12 5L19 19H5Z" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        @else
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 11V17M12 7H12.01M21 12A9 9 0 1 1 3 12A9 9 0 0 1 21 12Z" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        @endif
                    </span>

                    <p class="min-w-0 max-w-[280px] flex-1 text-[18px] font-bold leading-6 text-[#191a1e] sm:max-w-[310px]">
                        {{ $toast['message'] }}
                    </p>

                    <button
                        type="button"
                        class="-mr-1 inline-flex h-7 w-7 shrink-0 items-center justify-center rounded-full text-[#a6a9b1] transition hover:bg-[#f4f5f7] hover:text-[#191a1e]"
                        aria-label="Close notification"
                        @click="show = false"
                    >
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
                            <path d="M6 6L18 18M18 6L6 18" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
                        </svg>
                    </button>
                </div>
            </div>
        @endforeach
    </div>
@endif

@switch($name)
    @case('dashboard')
        <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"><path d="M4 13a8 8 0 1 1 16 0M12 13l4-4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
        @break

    @case('projects')
        <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"><path d="M8 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2M4 9h16v10H4V9Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><path d="M9 9v10M15 9v10" stroke="currentColor" stroke-width="2"/></svg>
        @break

    @case('posts')
        <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"><path d="M7 3h7l3 3v15H7V3Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><path d="M10 11h4M10 15h4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
        @break

    @case('categories')
        <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"><path d="M4 5h6v6H4V5ZM14 5h6v6h-6V5ZM4 15h6v4H4v-4ZM14 15h6v4h-6v-4Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></svg>
        @break

    @case('tools')
        <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"><path d="m14 7 3-3 3 3-3 3M4 20l7-7M13 11l-2-2 5-5 2 2-5 5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        @break

    @case('skills')
        <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"><path d="M12 3v4M12 17v4M3 12h4M17 12h4M6 6l3 3M15 15l3 3M18 6l-3 3M9 15l-3 3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
        @break

    @case('expertise')
        <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"><path d="M12 3v4M12 17v4M3 12h4M17 12h4M6 6l3 3M15 15l3 3M18 6l-3 3M9 15l-3 3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/><path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" stroke="currentColor" stroke-width="2"/></svg>
        @break

    @case('profile')
        <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"><path d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8ZM5 21a7 7 0 0 1 14 0" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
        @break

    @case('messages')
        <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"><path d="M4 6h16v12H4V6Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/><path d="m4 7 8 6 8-6" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></svg>
        @break

    @case('settings')
        <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"><path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" stroke="currentColor" stroke-width="2"/><path d="M19 12a7 7 0 0 0-.1-1.2l2-1.5-2-3.5-2.4 1a7 7 0 0 0-2-1.1L14 3h-4l-.5 2.7a7 7 0 0 0-2 1.1l-2.4-1-2 3.5 2 1.5A7 7 0 0 0 5 12c0 .4 0 .8.1 1.2l-2 1.5 2 3.5 2.4-1a7 7 0 0 0 2 1.1L10 21h4l.5-2.7a7 7 0 0 0 2-1.1l2.4 1 2-3.5-2-1.5c.1-.4.1-.8.1-1.2Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></svg>
        @break

    @case('external')
        <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"><path d="M14 4h6v6M20 4l-9 9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M20 14v5H5V4h5" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/></svg>
        @break

    @case('logout')
        <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"><path d="M10 6H5v12h5M14 8l4 4-4 4M8 12h10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        @break
@endswitch

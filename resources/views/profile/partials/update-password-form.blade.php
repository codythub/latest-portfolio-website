<section class="rounded-[22px] border border-[#dedfe4] bg-white px-5 py-6 shadow-[0_18px_55px_rgba(17,18,22,0.04)] sm:px-8 lg:px-9 lg:py-8">
    <header class="mb-7">
        <h2 class="font-syne text-[22px] font-bold leading-tight text-[#111216]">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-2 text-base font-bold text-[#8b9099]">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="mb-3 block text-base font-bold text-[#191a1e]">
                {{ __('Current Password') }}
            </label>

            <input
                id="update_password_current_password"
                name="current_password"
                type="password"
                autocomplete="current-password"
                class="min-h-14 w-full rounded-[18px] border border-[#dedfe4] bg-white px-5 text-base font-medium text-[#191a1e] outline-none transition focus:border-[#191a1e]"
            >

            @foreach ($errors->updatePassword->get('current_password') as $message)
                <p class="mt-2 text-sm font-semibold text-[#ef233c]">{{ $message }}</p>
            @endforeach
        </div>

        <div>
            <label for="update_password_password" class="mb-3 block text-base font-bold text-[#191a1e]">
                {{ __('New Password') }}
            </label>

            <input
                id="update_password_password"
                name="password"
                type="password"
                autocomplete="new-password"
                class="min-h-14 w-full rounded-[18px] border border-[#dedfe4] bg-white px-5 text-base font-medium text-[#191a1e] outline-none transition focus:border-[#191a1e]"
            >

            @foreach ($errors->updatePassword->get('password') as $message)
                <p class="mt-2 text-sm font-semibold text-[#ef233c]">{{ $message }}</p>
            @endforeach
        </div>

        <div>
            <label for="update_password_password_confirmation" class="mb-3 block text-base font-bold text-[#191a1e]">
                {{ __('Confirm Password') }}
            </label>

            <input
                id="update_password_password_confirmation"
                name="password_confirmation"
                type="password"
                autocomplete="new-password"
                class="min-h-14 w-full rounded-[18px] border border-[#dedfe4] bg-white px-5 text-base font-medium text-[#191a1e] outline-none transition focus:border-[#191a1e]"
            >

            @foreach ($errors->updatePassword->get('password_confirmation') as $message)
                <p class="mt-2 text-sm font-semibold text-[#ef233c]">{{ $message }}</p>
            @endforeach
        </div>

        <div class="flex justify-end">
            <button
                type="submit"
                class="inline-flex min-h-14 w-full items-center justify-center rounded-full bg-[#191a1e] px-7 text-[17px] font-bold text-white transition hover:bg-[#303136] sm:w-auto"
            >
                {{ __('Update Password') }}
            </button>
        </div>
    </form>
</section>

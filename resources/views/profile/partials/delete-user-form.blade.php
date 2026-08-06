<section class="rounded-[22px] border border-[#fecdd3] bg-white px-5 py-6 shadow-[0_18px_55px_rgba(17,18,22,0.04)] sm:px-8 lg:px-9 lg:py-8">
    <header>
        <h2 class="font-syne text-[22px] font-bold leading-tight text-[#ef233c]">
            {{ __('Delete Account') }}
        </h2>

        <p class="mt-2 max-w-3xl text-base font-bold leading-6 text-[#8b9099]">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <button
        type="button"
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="mt-7 inline-flex min-h-14 w-full items-center justify-center gap-3 rounded-full border border-[#fecdd3] bg-white px-7 text-[17px] font-bold text-[#ef233c] transition hover:bg-[#fff0f2] sm:w-auto"
    >
        <img
            src="{{ asset('images/icons/admin-delete.svg') }}"
            alt=""
            aria-hidden="true"
            class="h-5 w-5"
        >
        {{ __('Delete Account') }}
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="font-syne text-[24px] font-bold leading-tight text-[#111216]">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>

            <p class="mt-3 text-base font-medium leading-6 text-[#747984]">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>

            <div class="mt-6">
                <label for="password" class="sr-only">
                    {{ __('Password') }}
                </label>

                <input
                    id="password"
                    name="password"
                    type="password"
                    class="min-h-14 w-full rounded-[18px] border border-[#dedfe4] bg-white px-5 text-base font-medium text-[#191a1e] outline-none transition placeholder:text-[#8b9099] focus:border-[#191a1e]"
                    placeholder="{{ __('Password') }}"
                >

                @foreach ($errors->userDeletion->get('password') as $message)
                    <p class="mt-2 text-sm font-semibold text-[#ef233c]">{{ $message }}</p>
                @endforeach
            </div>

            <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:justify-end">
                <button
                    type="button"
                    x-on:click="$dispatch('close')"
                    class="inline-flex min-h-12 items-center justify-center rounded-full border border-[#dedfe4] bg-white px-5 text-base font-bold text-[#747984] transition hover:border-[#191a1e] hover:text-[#191a1e]"
                >
                    {{ __('Cancel') }}
                </button>

                <button
                    type="submit"
                    class="inline-flex min-h-12 items-center justify-center gap-3 rounded-full border border-[#fecdd3] bg-white px-5 text-base font-bold text-[#ef233c] transition hover:bg-[#fff0f2]"
                >
                    <img
                        src="{{ asset('images/icons/admin-delete.svg') }}"
                        alt=""
                        aria-hidden="true"
                        class="h-5 w-5"
                    >
                    {{ __('Delete Account') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>

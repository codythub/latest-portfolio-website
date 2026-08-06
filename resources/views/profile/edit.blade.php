@extends("layouts.admin")

@section("title", "Profile")

@section("content")
    <div class="-mt-3 mb-10">
        <p class="max-w-3xl text-[18px] font-medium leading-7 text-[#747984]">
            Your name, bio and links as they appear across the public portfolio.
        </p>
    </div>

    <div class="max-w-[980px] space-y-6 pb-12">
        @include('profile.partials.update-profile-information-form')
        @include('profile.partials.update-password-form')
        @include('profile.partials.delete-user-form')
    </div>
@endsection

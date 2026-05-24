<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <label for="nama" class="form-label font-bold text-slate-700">Nama Lengkap</label>
            <input id="nama" name="nama" type="text" class="form-input-custom border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 mt-1 block w-full" value="{{ old('nama', $user->nama) }}" required autofocus autocomplete="nama" />
            @error('nama') <span class="text-xs text-red-650 font-bold block mt-1.5">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="email" class="form-label font-bold text-slate-700">Email</label>
            <input id="email" name="email" type="email" class="form-input-custom border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 mt-1 block w-full" value="{{ old('email', $user->email) }}" required autocomplete="username" />
            @error('email') <span class="text-xs text-red-650 font-bold block mt-1.5">{{ $message }}</span> @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <button type="submit" class="btn-primary">{{ __('Simpan') }}</button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>

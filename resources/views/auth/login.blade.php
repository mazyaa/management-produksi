<x-guest-layout>
    <div class="bg-white/80 backdrop-blur-md rounded-3xl border border-white/20 shadow-2xl p-8 transition-all duration-300 hover:shadow-primary-500/5">
        <!-- Brand Header -->
        <div class="text-center mb-8">
            <img src="{{ asset('img/logomitsuba.svg') }}" alt="Logo Mitsuba" class="w-16 h-16 mx-auto mb-4 object-contain drop-shadow-md" />
            <h2 class="text-xl font-bold text-slate-800 tracking-tight">PT Mitsuba Indonesia</h2>
            <p class="text-xs font-semibold text-primary-600 uppercase tracking-widest mt-1">Press-3 Department</p>
            <h1 class="text-sm text-slate-500 mt-2 font-medium">Sistem Informasi Produksi Harian</h1>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Form -->
        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <!-- Username -->
            <div>
                <label for="username" class="form-label font-semibold text-slate-700">Username</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </span>
                    <input id="username" type="text" name="username" value="{{ old('username') }}" required autofocus autocomplete="username" 
                        placeholder="Masukkan username"
                        class="form-input-custom pl-11 py-3 bg-white/50 border border-slate-200 focus:bg-white focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20" />
                </div>
                <x-input-error :messages="$errors->get('username')" class="mt-1.5" />
            </div>

            <!-- Password -->
            <div x-data="{ show: false }">
                <div class="flex justify-between items-center mb-1">
                    <label for="password" class="form-label font-semibold text-slate-700 mb-0">Password</label>
                </div>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </span>
                    <input id="password" :type="show ? 'text' : 'password'" name="password" required autocomplete="current-password" 
                        placeholder="Masukkan password"
                        class="form-input-custom pl-11 pr-10 py-3 bg-white/50 border border-slate-200 focus:bg-white focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20" />
                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!show">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="show" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                        </svg>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-1.5" />
            </div>

            <!-- Remember Me -->
            <div class="flex items-center">
                <input id="remember_me" type="checkbox" name="remember" 
                    class="rounded border-slate-300 text-primary-600 shadow-sm focus:ring-primary-500 focus:ring-offset-0">
                <label for="remember_me" class="ml-2 text-sm text-slate-600 font-medium">Ingat saya di perangkat ini</label>
            </div>

            <!-- Submit -->
            <div>
                <button type="submit" class="w-full btn-primary py-3 hover:scale-[1.01] active:scale-[0.99] shadow-primary-500/25">
                    Masuk ke Sistem
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>

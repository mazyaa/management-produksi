<x-app-layout>
    @section('title', 'Edit User')
    @section('page-title', 'Edit Pengguna')

    <x-page-header title="Edit Pengguna" subtitle="Perbarui data profil, peran akses, atau ganti sandi akun pengguna.">
        <a href="{{ route('users.index') }}" class="btn-secondary flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali
        </a>
    </x-page-header>

    <div class="max-w-2xl">
        <x-card>
            <form method="POST" action="{{ route('users.update', $user->id) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Nama Lengkap -->
                <x-form-input 
                    label="Nama Lengkap" 
                    name="nama" 
                    value="{{ $user->nama }}" 
                    required 
                />

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Username -->
                    <x-form-input 
                        label="Username" 
                        name="username" 
                        value="{{ $user->username }}" 
                        required 
                    />

                    <!-- Email -->
                    <x-form-input 
                        label="Email" 
                        name="email" 
                        type="email"
                        value="{{ $user->email }}" 
                        required 
                    />
                </div>

                <!-- Password hint banner -->
                <div class="bg-slate-50 border border-slate-200/60 rounded-xl p-4 text-xs font-semibold text-slate-500">
                    <span class="text-primary-600 font-bold block mb-1">Informasi Penggantian Sandi</span>
                    Kosongkan kolom password di bawah ini jika tidak berniat mengubah password akun.
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Password -->
                    <x-form-input 
                        label="Password Baru" 
                        name="password" 
                        type="password"
                        placeholder="Masukkan password baru" 
                    />

                    <!-- Password Confirmation -->
                    <x-form-input 
                        label="Konfirmasi Password Baru" 
                        name="password_confirmation" 
                        type="password"
                        placeholder="Masukkan ulang password" 
                    />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Role select -->
                    <x-form-select label="Role Akses" name="role" required placeholder="Pilih Role...">
                        @foreach($roles as $role)
                            <option value="{{ $role->value }}" {{ $user->role === $role ? 'selected' : '' }} {{ $user->id === auth()->id() && $user->role !== $role ? 'disabled' : '' }}>
                                {{ $role->label() }}
                            </option>
                        @endforeach
                    </x-form-select>

                    <!-- Active toggle status -->
                    <div class="flex flex-col justify-end pb-1.5">
                        <span class="form-label font-bold text-slate-700">Status Akun</span>
                        <div class="flex items-center mt-2.5">
                            <input id="is_active" type="checkbox" name="is_active" value="1" {{ $user->is_active ? 'checked' : '' }} {{ $user->id === auth()->id() ? 'disabled' : '' }}
                                class="rounded border-slate-350 text-primary-600 shadow-sm focus:ring-primary-500 focus:ring-offset-0 w-4 h-4">
                            <label for="is_active" class="ml-2.5 text-sm text-slate-700 font-semibold">Aktifkan akun pengguna</label>
                        </div>
                    </div>
                </div>

                <!-- Submit buttons -->
                <div class="flex justify-end gap-3 border-t border-slate-100 pt-6 mt-8">
                    <a href="{{ route('users.index') }}" class="btn-secondary">
                        Batal
                    </a>
                    <button type="submit" class="btn-primary shadow-primary-500/20">
                        Perbarui Akun
                    </button>
                </div>
            </form>
        </x-card>
    </div>
</x-app-layout>

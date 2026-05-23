<x-app-layout>
    @section('title', 'Tambah User')
    @section('page-title', 'Tambah User Baru')

    <x-page-header title="Tambah Pengguna" subtitle="Daftarkan akun pengguna baru ke dalam sistem dengan hak akses teratur.">
        <a href="{{ route('users.index') }}" class="btn-secondary flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali
        </a>
    </x-page-header>

    <div class="max-w-2xl">
        <x-card>
            <form method="POST" action="{{ route('users.store') }}" class="space-y-6">
                @csrf

                <!-- Nama Lengkap -->
                <x-form-input 
                    label="Nama Lengkap" 
                    name="nama" 
                    placeholder="Masukkan nama lengkap" 
                    required 
                />

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Username -->
                    <x-form-input 
                        label="Username" 
                        name="username" 
                        placeholder="Masukkan username" 
                        required 
                    />

                    <!-- Email -->
                    <x-form-input 
                        label="Email" 
                        name="email" 
                        type="email"
                        placeholder="user@mitsuba.co.id" 
                        required 
                    />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Password -->
                    <x-form-input 
                        label="Password" 
                        name="password" 
                        type="password"
                        placeholder="Minimal 8 karakter" 
                        required 
                    />

                    <!-- Password Confirmation -->
                    <x-form-input 
                        label="Konfirmasi Password" 
                        name="password_confirmation" 
                        type="password"
                        placeholder="Masukkan ulang password" 
                        required 
                    />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Role select -->
                    <x-form-select label="Role Akses" name="role" required placeholder="Pilih Role...">
                        @foreach($roles as $role)
                            <option value="{{ $role->value }}">{{ $role->label() }}</option>
                        @endforeach
                    </x-form-select>

                    <!-- Active toggle status -->
                    <div class="flex flex-col justify-end pb-1.5">
                        <span class="form-label font-bold text-slate-700">Status Akun</span>
                        <div class="flex items-center mt-2.5">
                            <input id="is_active" type="checkbox" name="is_active" value="1" checked
                                class="rounded border-slate-350 text-primary-600 shadow-sm focus:ring-primary-500 focus:ring-offset-0 w-4 h-4">
                            <label for="is_active" class="ml-2.5 text-sm text-slate-700 font-semibold">Aktifkan akun pengguna langsung setelah dibuat</label>
                        </div>
                    </div>
                </div>

                <!-- Submit buttons -->
                <div class="flex justify-end gap-3 border-t border-slate-100 pt-6 mt-8">
                    <a href="{{ route('users.index') }}" class="btn-secondary">
                        Batal
                    </a>
                    <button type="submit" class="btn-primary shadow-primary-500/20">
                        Simpan Akun
                    </button>
                </div>
            </form>
        </x-card>
    </div>
</x-app-layout>

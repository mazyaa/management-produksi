<x-modal
    name="create-user"
    title="Tambah User Baru"
    max-width="lg"
    :initial-open="$errors->any() && !old('_method')"
>
    <form method="POST" action="{{ route('users.store') }}" class="space-y-5">
        @csrf

        <x-form-input
            label="Nama Lengkap"
            name="nama"
            placeholder="Masukkan nama lengkap"
            required
        />

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <x-form-input
                label="Username"
                name="username"
                placeholder="Masukkan username"
                required
            />
            <x-form-input
                label="Email"
                name="email"
                type="email"
                placeholder="user@mitsuba.co.id"
                required
            />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <x-form-input
                label="Password"
                name="password"
                type="password"
                placeholder="Minimal 8 karakter"
                required
            />
            <x-form-input
                label="Konfirmasi Password"
                name="password_confirmation"
                type="password"
                placeholder="Masukkan ulang password"
                required
            />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <x-form-select label="Role Akses" name="role" required placeholder="Pilih Role...">
                @foreach($roles as $role)
                    <option value="{{ $role->value }}">{{ $role->label() }}</option>
                @endforeach
            </x-form-select>

            <div class="flex items-end pb-1">
                <div class="flex items-center">
                    <input id="is_active_create" type="checkbox" name="is_active" value="1" checked
                        class="rounded border-slate-350 text-primary-600 shadow-sm focus:ring-primary-500 focus:ring-offset-0 w-4 h-4">
                    <label for="is_active_create" class="ml-2.5 text-sm text-slate-700 font-semibold">Aktifkan segera</label>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
            <button type="button" @click="$dispatch('close-modal', { name: 'create-user' })" class="btn-secondary">Batal</button>
            <button type="submit" class="btn-primary">Simpan Akun</button>
        </div>
    </form>
</x-modal>

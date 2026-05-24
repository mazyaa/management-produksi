<x-modal
    name="edit-user-{{ $item->id }}"
    title="Edit Data User"
    max-width="lg"
    :initial-open="$errors->any() && old('_method') === 'PUT' && old('id') == $item->id"
>
    <form method="POST" action="{{ route('users.update', $item->id) }}" class="space-y-5">
        @csrf
        @method('PUT')
        <input type="hidden" name="id" value="{{ $item->id }}">

        <x-form-input
            label="Nama Lengkap"
            name="nama"
            placeholder="Masukkan nama lengkap"
            value="{{ old('nama', $item->nama) }}"
            required
        />

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <x-form-input
                label="Username"
                name="username"
                placeholder="Masukkan username"
                value="{{ old('username', $item->username) }}"
                required
            />
            <x-form-input
                label="Email"
                name="email"
                type="email"
                placeholder="user@mitsuba.co.id"
                value="{{ old('email', $item->email) }}"
                required
            />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <x-form-input
                label="Password (biarkan kosong jika tidak diubah)"
                name="password"
                type="password"
                placeholder="Minimal 8 karakter"
            />
            <x-form-input
                label="Konfirmasi Password"
                name="password_confirmation"
                type="password"
                placeholder="Masukkan ulang password"
            />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <x-form-select label="Role Akses" name="role" required placeholder="Pilih Role...">
                @foreach($roles as $role)
                    <option value="{{ $role->value }}" {{ old('role', $item->role->value) === $role->value ? 'selected' : '' }}>
                        {{ $role->label() }}
                    </option>
                @endforeach
            </x-form-select>

            <div class="flex items-end pb-1">
                <div class="flex items-center">
                    <input id="is_active_{{ $item->id }}" type="checkbox" name="is_active" value="1"
                        {{ old('is_active', $item->is_active) ? 'checked' : '' }}
                        class="rounded border-slate-350 text-primary-600 shadow-sm focus:ring-primary-500 focus:ring-offset-0 w-4 h-4">
                    <label for="is_active_{{ $item->id }}" class="ml-2.5 text-sm text-slate-700 font-semibold">Akun aktif</label>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
            <button type="button" @click="$dispatch('close-modal', { name: 'edit-user-{{ $item->id }}' })" class="btn-secondary">Batal</button>
            <button type="submit" class="btn-primary">Simpan Perubahan</button>
        </div>
    </form>
</x-modal>

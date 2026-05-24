<x-modal
    name="create-mesin"
    title="Daftarkan Mesin Baru"
    max-width="md"
    :initial-open="$errors->any() && !old('_method')"
>
    <form method="POST" action="{{ route('master.mesins.store') }}" class="space-y-5">
        @csrf

        <x-form-input
            label="Kode Mesin"
            name="kode_mesin"
            placeholder="Contoh: PR3-001, PR3-002"
            required
        />

        <x-form-input
            label="Nama Mesin"
            name="nama_mesin"
            placeholder="Contoh: Press Machine 80T B"
            required
        />

        <x-form-input
            label="Line Kerja"
            name="line"
            placeholder="Contoh: Line 3A, Line 3B"
            required
        />

        <div class="flex items-center">
            <input id="is_active_create" type="checkbox" name="is_active" value="1" checked
                class="rounded border-slate-350 text-primary-600 shadow-sm focus:ring-primary-500 focus:ring-offset-0 w-4 h-4">
            <label for="is_active_create" class="ml-2.5 text-sm text-slate-700 font-semibold">Tandai mesin ini aktif dan siap digunakan operator</label>
        </div>

        <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
            <button type="button" @click="$dispatch('close-modal', { name: 'create-mesin' })" class="btn-secondary">Batal</button>
            <button type="submit" class="btn-primary">Daftarkan Mesin</button>
        </div>
    </form>
</x-modal>

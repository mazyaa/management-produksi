<x-modal
    name="edit-mesin-{{ $item->id }}"
    title="Edit Data Mesin"
    max-width="md"
    :initial-open="$errors->any() && old('_method') === 'PUT' && old('id') == $item->id"
>
    <form method="POST" action="{{ route('master.mesins.update', $item->id) }}" class="space-y-5">
        @csrf
        @method('PUT')
        <input type="hidden" name="id" value="{{ $item->id }}">

        <x-form-input
            label="Kode Mesin"
            name="kode_mesin"
            placeholder="Contoh: PR3-001, PR3-002"
            value="{{ old('kode_mesin', $item->kode_mesin) }}"
            required
        />

        <x-form-input
            label="Nama Mesin"
            name="nama_mesin"
            placeholder="Contoh: Press Machine 80T B"
            value="{{ old('nama_mesin', $item->nama_mesin) }}"
            required
        />

        <x-form-input
            label="Line Kerja"
            name="line"
            placeholder="Contoh: Line 3A, Line 3B"
            value="{{ old('line', $item->line) }}"
            required
        />

        <div class="flex items-center">
            <input id="is_active_{{ $item->id }}" type="checkbox" name="is_active" value="1"
                {{ old('is_active', $item->is_active) ? 'checked' : '' }}
                class="rounded border-slate-350 text-primary-600 shadow-sm focus:ring-primary-500 focus:ring-offset-0 w-4 h-4">
            <label for="is_active_{{ $item->id }}" class="ml-2.5 text-sm text-slate-700 font-semibold">Tandai mesin ini aktif</label>
        </div>

        <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
            <button type="button" @click="$dispatch('close-modal', { name: 'edit-mesin-{{ $item->id }}' })" class="btn-secondary">Batal</button>
            <button type="submit" class="btn-primary">Simpan Perubahan</button>
        </div>
    </form>
</x-modal>

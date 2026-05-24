<x-modal
    name="edit-part-{{ $item->id }}"
    title="Edit Data Part"
    max-width="md"
    :initial-open="$errors->any() && old('_method') === 'PUT' && old('id') == $item->id"
>
    <form method="POST" action="{{ route('master.parts.update', $item->id) }}" class="space-y-5">
        @csrf
        @method('PUT')
        <input type="hidden" name="id" value="{{ $item->id }}">

        <x-form-input
            label="Nomor Part"
            name="nomor_part"
            placeholder="Contoh: MIT-P3-1001"
            value="{{ old('nomor_part', $item->nomor_part) }}"
            required
        />

        <x-form-input
            label="Nama Part"
            name="nama_part"
            placeholder="Contoh: Arm Bracket Comp"
            value="{{ old('nama_part', $item->nama_part) }}"
            required
        />

        <x-form-input
            label="Kategori Part"
            name="kategori"
            placeholder="Contoh: Press Parts, Wiper Parts"
            value="{{ old('kategori', $item->kategori) }}"
            required
        />

        <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
            <button type="button" @click="$dispatch('close-modal', { name: 'edit-part-{{ $item->id }}' })" class="btn-secondary">Batal</button>
            <button type="submit" class="btn-primary">Simpan Perubahan</button>
        </div>
    </form>
</x-modal>

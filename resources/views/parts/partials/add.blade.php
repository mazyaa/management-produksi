<x-modal
    name="create-part"
    title="Daftarkan Part Baru"
    max-width="md"
    :initial-open="$errors->any() && !old('_method')"
>
    <form method="POST" action="{{ route('master.parts.store') }}" class="space-y-5">
        @csrf

        <x-form-input
            label="Nomor Part"
            name="nomor_part"
            placeholder="Contoh: MIT-P3-1001"
            required
        />

        <x-form-input
            label="Nama Part"
            name="nama_part"
            placeholder="Contoh: Arm Bracket Comp"
            required
        />

        <x-form-input
            label="Kategori Part"
            name="kategori"
            placeholder="Contoh: Press Parts, Wiper Parts"
            required
        />

        <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
            <button type="button" @click="$dispatch('close-modal', { name: 'create-part' })" class="btn-secondary">Batal</button>
            <button type="submit" class="btn-primary">Simpan Part</button>
        </div>
    </form>
</x-modal>

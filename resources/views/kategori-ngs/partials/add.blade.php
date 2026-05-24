<x-modal
    name="create-kategori-ng"
    title="Tambah Kategori NG Baru"
    max-width="md"
    :initial-open="$errors->any() && !old('_method')"
>
    <form method="POST" action="{{ route('master.kategori-ngs.store') }}" class="space-y-5">
        @csrf

        <x-form-input
            label="Kode NG"
            name="kode_ng"
            placeholder="Contoh: NG-001, NG-002"
            required
        />

        <x-form-input
            label="Jenis NG / Cacat"
            name="nama_ng"
            placeholder="Contoh: Burry, Crack, Dent"
            required
        />

        <x-form-select label="Tingkat Keparahan (Severity)" name="severity" required placeholder="Pilih tingkat severity...">
            @foreach($severities as $severity)
                <option value="{{ $severity->value }}">{{ $severity->label() }}</option>
            @endforeach
        </x-form-select>

        <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
            <button type="button" @click="$dispatch('close-modal', { name: 'create-kategori-ng' })" class="btn-secondary">Batal</button>
            <button type="submit" class="btn-primary">Simpan Kategori NG</button>
        </div>
    </form>
</x-modal>

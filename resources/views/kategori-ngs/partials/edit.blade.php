<x-modal
    name="edit-kategori-ng-{{ $item->id }}"
    title="Edit Kategori NG"
    max-width="md"
    :initial-open="$errors->any() && old('_method') === 'PUT' && old('id') == $item->id"
>
    <form method="POST" action="{{ route('master.kategori-ngs.update', $item->id) }}" class="space-y-5">
        @csrf
        @method('PUT')
        <input type="hidden" name="id" value="{{ $item->id }}">

        <x-form-input
            label="Kode NG"
            name="kode_ng"
            placeholder="Contoh: NG-001, NG-002"
            value="{{ old('kode_ng', $item->kode_ng) }}"
            required
        />

        <x-form-input
            label="Jenis NG / Cacat"
            name="nama_ng"
            placeholder="Contoh: Burry, Crack, Dent"
            value="{{ old('nama_ng', $item->nama_ng) }}"
            required
        />

        <x-form-select label="Tingkat Keparahan (Severity)" name="severity" required placeholder="Pilih tingkat severity...">
            @foreach($severities as $severity)
                <option value="{{ $severity->value }}" {{ old('severity', $item->severity->value) === $severity->value ? 'selected' : '' }}>
                    {{ $severity->label() }}
                </option>
            @endforeach
        </x-form-select>

        <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
            <button type="button" @click="$dispatch('close-modal', { name: 'edit-kategori-ng-{{ $item->id }}' })" class="btn-secondary">Batal</button>
            <button type="submit" class="btn-primary">Simpan Perubahan</button>
        </div>
    </form>
</x-modal>

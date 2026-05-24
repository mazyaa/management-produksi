<x-modal
    name="edit-shift-{{ $item->id }}"
    title="Edit Shift Kerja"
    max-width="md"
    :initial-open="$errors->any() && old('_method') === 'PUT' && old('id') == $item->id"
>
    <form method="POST" action="{{ route('master.shifts.update', $item->id) }}" class="space-y-5">
        @csrf
        @method('PUT')
        <input type="hidden" name="id" value="{{ $item->id }}">

        <x-form-input
            label="Nama Shift"
            name="nama_shift"
            placeholder="Contoh: Shift 1, Shift 2, Lembur..."
            value="{{ old('nama_shift', $item->nama_shift) }}"
            required
        />

        <div class="grid grid-cols-2 gap-4">
            <div class="space-y-1">
                <label class="form-label font-bold text-slate-700 text-sm">Jam Masuk</label>
                <input type="time" name="jam_masuk" required class="form-input-custom" value="{{ old('jam_masuk', substr($item->jam_masuk, 0, 5)) }}">
            </div>
            <div class="space-y-1">
                <label class="form-label font-bold text-slate-700 text-sm">Jam Selesai</label>
                <input type="time" name="jam_selesai" required class="form-input-custom" value="{{ old('jam_selesai', substr($item->jam_selesai, 0, 5)) }}">
            </div>
        </div>

        <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
            <button type="button" @click="$dispatch('close-modal', { name: 'edit-shift-{{ $item->id }}' })" class="btn-secondary">
                Batal
            </button>
            <button type="submit" class="btn-primary">Simpan Perubahan</button>
        </div>
    </form>
</x-modal>

<x-modal
    name="create-shift"
    title="Tambah Shift Kerja Baru"
    max-width="md"
    :initial-open="$errors->any() && !old('_method')"
>
    <form method="POST" action="{{ route('master.shifts.store') }}" class="space-y-5">
        @csrf

        <x-form-input
            label="Nama Shift"
            name="nama_shift"
            placeholder="Contoh: Shift 1, Shift 2, Lembur..."
            required
        />

        <div class="grid grid-cols-2 gap-4">
            <div class="space-y-1">
                <label class="form-label font-bold text-slate-700 text-sm">Jam Masuk</label>
                <input type="time" name="jam_masuk" required class="form-input-custom" value="{{ old('jam_masuk') }}">
            </div>
            <div class="space-y-1">
                <label class="form-label font-bold text-slate-700 text-sm">Jam Selesai</label>
                <input type="time" name="jam_selesai" required class="form-input-custom" value="{{ old('jam_selesai') }}">
            </div>
        </div>

        <div class="text-[11px] font-semibold text-slate-400 bg-slate-50 border border-slate-200/50 rounded-xl p-3">
            <span class="text-primary-600 block mb-0.5 font-bold">Catatan Format</span>
            Masukkan waktu dalam format 24 jam (HH:MM), contoh: 06:00, 14:00, 22:30.
        </div>

        <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
            <button type="button" @click="$dispatch('close-modal', { name: 'create-shift' })" class="btn-secondary">
                Batal
            </button>
            <button type="submit" class="btn-primary">Simpan Shift</button>
        </div>
    </form>
</x-modal>

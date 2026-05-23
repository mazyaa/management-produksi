<x-app-layout>
    @section('title', 'Tambah Shift')
    @section('page-title', 'Tambah Shift Baru')

    <x-page-header title="Tambah Shift Kerja" subtitle="Daftarkan kelompok jam kerja shift produksi baru.">
        <a href="{{ route('master.shifts.index') }}" class="btn-secondary flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali
        </a>
    </x-page-header>

    <div class="max-w-md">
        <x-card>
            <form method="POST" action="{{ route('master.shifts.store') }}" class="space-y-6">
                @csrf

                <!-- Nama Shift -->
                <x-form-input 
                    label="Nama Shift" 
                    name="nama_shift" 
                    placeholder="Contoh: Shift 1, Shift 2, Lembur..." 
                    required 
                />

                <div class="grid grid-cols-2 gap-4">
                    <!-- Jam Masuk -->
                    <x-form-input 
                        label="Jam Masuk" 
                        name="jam_masuk" 
                        type="text"
                        placeholder="06:00" 
                        required 
                    />

                    <!-- Jam Selesai -->
                    <x-form-input 
                        label="Jam Selesai" 
                        name="jam_selesai" 
                        type="text"
                        placeholder="14:00" 
                        required 
                    />
                </div>

                <div class="text-[11px] font-semibold text-slate-400 bg-slate-50 border border-slate-200/50 rounded-xl p-3">
                    <span class="text-primary-600 block mb-0.5 font-bold">Catatan Format</span>
                    Masukkan waktu dalam format 24 jam (HH:MM), contoh: 06:00, 14:00, 22:30.
                </div>

                <!-- Submit buttons -->
                <div class="flex justify-end gap-3 border-t border-slate-100 pt-6 mt-8">
                    <a href="{{ route('master.shifts.index') }}" class="btn-secondary">
                        Batal
                    </a>
                    <button type="submit" class="btn-primary shadow-primary-500/20">
                        Simpan Shift
                    </button>
                </div>
            </form>
        </x-card>
    </div>
</x-app-layout>

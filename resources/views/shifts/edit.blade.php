<x-app-layout>
    @section('title', 'Edit Shift')
    @section('page-title', 'Edit Shift Kerja')

    <x-page-header title="Edit Shift Kerja" subtitle="Perbarui pengaturan jam masuk atau selesai untuk shift kerja terpilih.">
        <a href="{{ route('master.shifts.index') }}" class="btn-secondary flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali
        </a>
    </x-page-header>

    <div class="max-w-md">
        <x-card>
            <form method="POST" action="{{ route('master.shifts.update', $shift->id) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Nama Shift -->
                <x-form-input 
                    label="Nama Shift" 
                    name="nama_shift" 
                    value="{{ $shift->nama_shift }}" 
                    required 
                />

                <div class="grid grid-cols-2 gap-4">
                    <!-- Jam Masuk -->
                    <x-form-input 
                        label="Jam Masuk" 
                        name="jam_masuk" 
                        value="{{ substr($shift->jam_masuk, 0, 5) }}" 
                        placeholder="06:00" 
                        required 
                    />

                    <!-- Jam Selesai -->
                    <x-form-input 
                        label="Jam Selesai" 
                        name="jam_selesai" 
                        value="{{ substr($shift->jam_selesai, 0, 5) }}" 
                        placeholder="14:00" 
                        required 
                    />
                </div>

                <!-- Submit buttons -->
                <div class="flex justify-end gap-3 border-t border-slate-100 pt-6 mt-8">
                    <a href="{{ route('master.shifts.index') }}" class="btn-secondary">
                        Batal
                    </a>
                    <button type="submit" class="btn-primary shadow-primary-500/20">
                        Perbarui Shift
                    </button>
                </div>
            </form>
        </x-card>
    </div>
</x-app-layout>

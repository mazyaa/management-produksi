<x-app-layout>
    @section('title', 'Daftarkan Mesin')
    @section('page-title', 'Daftar Mesin Baru')

    <x-page-header title="Daftarkan Mesin Baru" subtitle="Daftarkan identitas mesin press baru ke dalam database master.">
        <a href="{{ route('master.mesins.index') }}" class="btn-secondary flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali
        </a>
    </x-page-header>

    <div class="max-w-md">
        <x-card>
            <form method="POST" action="{{ route('master.mesins.store') }}" class="space-y-6">
                @csrf

                <!-- Kode Mesin -->
                <x-form-input 
                    label="Kode Mesin" 
                    name="kode_mesin" 
                    placeholder="Contoh: PR3-001, PR3-002" 
                    required 
                />

                <!-- Nama Mesin -->
                <x-form-input 
                    label="Nama Mesin" 
                    name="nama_mesin" 
                    placeholder="Contoh: Press Machine 80T B" 
                    required 
                />

                <!-- Line Kerja -->
                <x-form-input 
                    label="Line Kerja" 
                    name="line" 
                    placeholder="Contoh: Line 3A, Line 3B" 
                    required 
                />

                <!-- Active Status -->
                <div class="flex flex-col pb-1">
                    <span class="form-label font-bold text-slate-700">Status Keaktifan</span>
                    <div class="flex items-center mt-2.5">
                        <input id="is_active" type="checkbox" name="is_active" value="1" checked
                            class="rounded border-slate-350 text-primary-600 shadow-sm focus:ring-primary-500 focus:ring-offset-0 w-4 h-4">
                        <label for="is_active" class="ml-2.5 text-sm text-slate-700 font-semibold">Tandai mesin ini aktif dan siap digunakan operator</label>
                    </div>
                </div>

                <!-- Submit buttons -->
                <div class="flex justify-end gap-3 border-t border-slate-100 pt-6 mt-8">
                    <a href="{{ route('master.mesins.index') }}" class="btn-secondary">
                        Batal
                    </a>
                    <button type="submit" class="btn-primary shadow-primary-500/20">
                        Daftarkan Mesin
                    </button>
                </div>
            </form>
        </x-card>
    </div>
</x-app-layout>

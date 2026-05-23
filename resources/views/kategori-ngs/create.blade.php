<x-app-layout>
    @section('title', 'Tambah Kategori NG')
    @section('page-title', 'Tambah Kategori Non-Good')

    <x-page-header title="Tambah Kategori Cacat" subtitle="Daftarkan jenis kecacatan komponen baru ke dalam database master.">
        <a href="{{ route('master.kategori-ngs.index') }}" class="btn-secondary flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali
        </a>
    </x-page-header>

    <div class="max-w-md">
        <x-card>
            <form method="POST" action="{{ route('master.kategori-ngs.store') }}" class="space-y-6">
                @csrf

                <!-- Kode NG -->
                <x-form-input 
                    label="Kode NG" 
                    name="kode_ng" 
                    placeholder="Contoh: NG-001, NG-002" 
                    required 
                />

                <!-- Nama NG -->
                <x-form-input 
                    label="Jenis NG / Cacat" 
                    name="nama_ng" 
                    placeholder="Contoh: Burry, Crack, Dent" 
                    required 
                />

                <!-- Severity Level Select -->
                <x-form-select label="Tingkat Keparahan (Severity)" name="severity" required placeholder="Pilih tingkat severity...">
                    @foreach($severities as $severity)
                        <option value="{{ $severity->value }}">{{ $severity->label() }}</option>
                    @endforeach
                </x-form-select>

                <!-- Submit buttons -->
                <div class="flex justify-end gap-3 border-t border-slate-100 pt-6 mt-8">
                    <a href="{{ route('master.kategori-ngs.index') }}" class="btn-secondary">
                        Batal
                    </a>
                    <button type="submit" class="btn-primary shadow-primary-500/20">
                        Simpan Kategori NG
                    </button>
                </div>
            </form>
        </x-card>
    </div>
</x-app-layout>

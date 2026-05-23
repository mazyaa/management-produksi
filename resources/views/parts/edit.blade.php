<x-app-layout>
    @section('title', 'Edit Part')
    @section('page-title', 'Edit Part Nomor')

    <x-page-header title="Edit Part Nomor" subtitle="Perbarui deskripsi atau kode part nomor terpilih.">
        <a href="{{ route('master.parts.index') }}" class="btn-secondary flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali
        </a>
    </x-page-header>

    <div class="max-w-md">
        <x-card>
            <form method="POST" action="{{ route('master.parts.update', $part->id) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Nomor Part -->
                <x-form-input 
                    label="Nomor Part" 
                    name="nomor_part" 
                    value="{{ $part->nomor_part }}" 
                    required 
                />

                <!-- Nama Part -->
                <x-form-input 
                    label="Nama Part" 
                    name="nama_part" 
                    value="{{ $part->nama_part }}" 
                    required 
                />

                <!-- Kategori Part -->
                <x-form-input 
                    label="Kategori Part" 
                    name="kategori" 
                    value="{{ $part->kategori }}" 
                    required 
                />

                <!-- Submit buttons -->
                <div class="flex justify-end gap-3 border-t border-slate-100 pt-6 mt-8">
                    <a href="{{ route('master.parts.index') }}" class="btn-secondary">
                        Batal
                    </a>
                    <button type="submit" class="btn-primary shadow-primary-500/20">
                        Perbarui Part
                    </button>
                </div>
            </form>
        </x-card>
    </div>
</x-app-layout>

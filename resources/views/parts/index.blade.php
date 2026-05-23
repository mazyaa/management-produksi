<x-app-layout>
    @section('title', 'Master Part')
    @section('page-title', 'Master Part Nomor')

    <!-- Header Section -->
    <x-page-header title="Data Nomor Part" subtitle="Daftar nomor komponen otomotif yang diproduksi di lini Press-3.">
        <a href="{{ route('master.parts.create') }}" class="btn-primary flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
            </svg>
            Daftarkan Part Baru
        </a>
    </x-page-header>

    <!-- Filters Section -->
    <x-filter-section>
        <form method="GET" action="{{ route('master.parts.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
            <!-- Search -->
            <x-form-input 
                label="Cari Nomor/Nama Part" 
                name="search" 
                placeholder="Nomor part, nama, kategori..." 
                value="{{ request('search') }}" 
            />

            <div class="col-span-1"></div>

            <!-- Action buttons -->
            <div class="flex gap-2">
                <button type="submit" class="flex-1 btn-primary py-2.5">
                    Terapkan
                </button>
                <a href="{{ route('master.parts.index') }}" class="btn-secondary py-2.5 inline-flex justify-center items-center">
                    Reset
                </a>
            </div>
        </form>
    </x-filter-section>

    <!-- Content Card -->
    <x-card :padding="false">
        @if($parts->count() > 0)
            <x-data-table :headers="['Nomor Part', 'Nama Part', 'Kategori Komponen', 'Aksi']">
                @foreach($parts as $part)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-primary-50 text-primary-700 border border-primary-200/50 tracking-wider">
                                {{ $part->nomor_part }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-800 text-sm">{{ $part->nama_part }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-slate-550 text-xs font-semibold">{{ $part->kategori }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <!-- Edit -->
                                <a href="{{ route('master.parts.edit', $part->id) }}" 
                                   class="text-slate-400 hover:text-primary-600 transition-colors p-1.5 rounded-lg border border-slate-200 hover:bg-white hover:shadow-sm"
                                   title="Edit Data Part"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </a>

                                <!-- Delete -->
                                <x-confirm-delete 
                                    :action="route('master.parts.destroy', $part->id)" 
                                    title="Hapus Part Nomor?" 
                                    text="Komponen {{ $part->nomor_part }} akan dihapus jika belum digunakan dalam data produksi harian."
                                    class="text-slate-400 hover:text-red-650 transition-colors p-1.5 rounded-lg border border-slate-200 hover:bg-white hover:shadow-sm"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </x-confirm-delete>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </x-data-table>

            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/20">
                {{ $parts->links() }}
            </div>
        @else
            <div class="p-6">
                <x-empty-state 
                    title="Part Tidak Ditemukan" 
                    message="Tidak ada data part nomor yang cocok dengan pencarian Anda."
                >
                    <a href="{{ route('master.parts.create') }}" class="btn-primary btn-sm flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                        </svg>
                        Daftarkan Part
                    </a>
                </x-empty-state>
            </div>
        @endif
    </x-card>
</x-app-layout>

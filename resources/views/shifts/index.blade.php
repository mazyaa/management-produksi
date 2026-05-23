<x-app-layout>
    @section('title', 'Master Shift')
    @section('page-title', 'Master Shift Produksi')

    <!-- Header Section -->
    <x-page-header title="Data Shift Kerja" subtitle="Daftar jam kerja shift produksi PT Mitsuba Indonesia Press-3.">
        <a href="{{ route('master.shifts.create') }}" class="btn-primary flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Shift
        </a>
    </x-page-header>

    <!-- Content Card -->
    <x-card :padding="false">
        @if($shifts->count() > 0)
            <x-data-table :headers="['Nama Shift', 'Jam Masuk', 'Jam Selesai', 'Durasi Kerja', 'Aksi']">
                @foreach($shifts as $shift)
                    @php
                        $masuk = \Carbon\Carbon::parse($shift->jam_masuk);
                        $selesai = \Carbon\Carbon::parse($shift->jam_selesai);
                        $durasi = $masuk->diffInHours($selesai);
                        if ($selesai < $masuk) {
                            $durasi = 24 - $masuk->diffInHours($selesai); // handle overnight shifts
                        }
                    @endphp
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-800">{{ $shift->nama_shift }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-slate-800 text-sm font-semibold">{{ substr($shift->jam_masuk, 0, 5) }} WIB</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-slate-800 text-sm font-semibold">{{ substr($shift->jam_selesai, 0, 5) }} WIB</div>
                        </td>
                        <td class="px-6 py-4 text-xs font-semibold text-slate-500">
                            {{ $durasi }} Jam
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <!-- Edit -->
                                <a href="{{ route('master.shifts.edit', $shift->id) }}" 
                                   class="text-slate-400 hover:text-primary-600 transition-colors p-1.5 rounded-lg border border-slate-200 hover:bg-white hover:shadow-sm"
                                   title="Edit Data Shift"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </a>

                                <!-- Delete -->
                                <x-confirm-delete 
                                    :action="route('master.shifts.destroy', $shift->id)" 
                                    title="Hapus Shift Kerja?" 
                                    text="Shift {{ $shift->nama_shift }} akan dihapus permanen jika belum digunakan dalam data produksi."
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
        @else
            <div class="p-6">
                <x-empty-state 
                    title="Shift Tidak Ditemukan" 
                    message="Belum ada data shift kerja yang didaftarkan."
                >
                    <a href="{{ route('master.shifts.create') }}" class="btn-primary btn-sm flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Shift
                    </a>
                </x-empty-state>
            </div>
        @endif
    </x-card>
</x-app-layout>

<x-app-layout>
    @section('title', 'Master Shift')
    @section('page-title', 'Master Shift Produksi')

    <x-page-header title="Data Shift Kerja" subtitle="Daftar jam kerja shift produksi PT Mitsuba Indonesia Press-3.">
        <button type="button"
            @click="$dispatch('open-modal', { name: 'create-shift' })"
            class="btn-primary flex items-center gap-2"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Shift
        </button>
    </x-page-header>

    <x-filter-section>
        <form method="GET" action="{{ route('master.shifts.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
            <x-form-input
                label="Cari Shift"
                name="search"
                placeholder="Cari berdasarkan nama shift..."
                value="{{ request('search') }}"
            />
            <div class="md:col-span-2 flex justify-end gap-2">
                <a href="{{ route('master.shifts.index') }}" class="btn-secondary">Reset</a>
                <button type="submit" class="btn-primary">Terapkan Filter</button>
            </div>
        </form>
    </x-filter-section>

    <x-card :padding="false">
        @if($shifts->count() > 0)
            <x-data-table :headers="['Nama Shift', 'Jam Masuk', 'Jam Selesai', 'Aksi']">
                @foreach($shifts as $shift)
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
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <button type="button"
                                    @click="$dispatch('open-modal', { name: 'edit-shift-{{ $shift->id }}' })"
                                    class="text-slate-400 hover:text-primary-600 transition-colors p-1.5 rounded-lg border border-slate-200 hover:bg-white hover:shadow-sm"
                                    title="Edit Data Shift"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </button>

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

            @foreach($shifts as $shift)
                @include('shifts.partials.edit', ['item' => $shift])
            @endforeach

            <div class="flex items-center justify-between px-6 py-4 border-t border-slate-100 bg-slate-50/20">
                <div class="flex items-center gap-2 text-sm">
                    <form method="GET" action="{{ route('master.shifts.index') }}">
                        @foreach(request()->except('limit', 'page') as $key => $value)
                            @if(is_array($value))
                                @foreach($value as $k => $v)
                                    <input type="hidden" name="{{ $key }}[{{ $k }}]" value="{{ $v }}">
                                @endforeach
                            @else
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endif
                        @endforeach
                        <label class="font-medium text-slate-600">Tampilkan</label>
                        <select name="limit" onchange="this.form.submit()" class="text-xs rounded-lg border-slate-300 text-slate-700 font-semibold py-1.5 px-2 focus:ring-primary-500 focus:border-primary-500">
                            <option value="10" {{ request('limit') == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('limit') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('limit') == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('limit') == 100 ? 'selected' : '' }}>100</option>
                        </select>
                        <span class="text-slate-500">data</span>
                    </form>
                </div>
                <div>
                    {{ $shifts->links() }}
                </div>
            </div>
        @else
            <div class="p-6">
                <x-empty-state
                    title="Shift Tidak Ditemukan"
                    message="Belum ada data shift kerja yang didaftarkan."
                >
                    <button type="button"
                        @click="$dispatch('open-modal', { name: 'create-shift' })"
                        class="btn-primary btn-sm flex items-center gap-1.5"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Shift
                    </button>
                </x-empty-state>
            </div>
        @endif
    </x-card>

    @include('shifts.partials.add')
</x-app-layout>

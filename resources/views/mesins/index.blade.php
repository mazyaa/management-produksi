<x-app-layout>
    @section('title', 'Master Mesin')
    @section('page-title', 'Master Mesin Produksi')

    <x-page-header title="Data Mesin Press" subtitle="Kelola daftar mesin press dan status aktif mesin di area Press-3.">
        <button type="button"
            @click="$dispatch('open-modal', { name: 'create-mesin' })"
            class="btn-primary flex items-center gap-2"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
            </svg>
            Daftarkan Mesin
        </button>
    </x-page-header>

    <x-filter-section>
        <form method="GET" action="{{ route('master.mesins.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <x-form-input
                label="Cari Mesin"
                name="search"
                placeholder="Kode mesin, nama, line..."
                value="{{ request('search') }}"
            />

            <x-form-select label="Filter Status" name="status" placeholder="Semua Status">
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Non-Aktif</option>
            </x-form-select>

            <div class="flex gap-2">
                <button type="submit" class="flex-1 btn-primary py-2.5">Terapkan</button>
                <a href="{{ route('master.mesins.index') }}" class="btn-secondary py-2.5 inline-flex justify-center items-center">Reset</a>
            </div>
        </form>
    </x-filter-section>

    <x-card :padding="false">
        @if($mesins->count() > 0)
            <x-data-table :headers="['Kode Mesin', 'Nama Mesin', 'Line Kerja', 'Status Keaktifan', 'Aksi']">
                @foreach($mesins as $mesin)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-primary-50 text-primary-700 border border-primary-200/50 tracking-wider">
                                {{ $mesin->kode_mesin }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-800 text-sm">{{ $mesin->nama_mesin }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-slate-550 text-xs font-semibold">{{ $mesin->line }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold border {{ $mesin->is_active ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-red-50 text-red-700 border-red-200' }}">
                                <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $mesin->is_active ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                                {{ $mesin->is_active ? 'Aktif' : 'Non-Aktif' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <button type="button"
                                    @click="$dispatch('open-modal', { name: 'edit-mesin-{{ $mesin->id }}' })"
                                    class="text-slate-400 hover:text-primary-600 transition-colors p-1.5 rounded-lg border border-slate-200 hover:bg-white hover:shadow-sm"
                                    title="Edit Data Mesin"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </button>

                                <x-confirm-delete
                                    :action="route('master.mesins.destroy', $mesin->id)"
                                    title="Hapus Mesin Produksi?"
                                    text="Mesin {{ $mesin->kode_mesin }} akan dihapus permanen jika belum digunakan dalam data produksi harian."
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

            @foreach($mesins as $mesin)
                @include('mesins.partials.edit', ['item' => $mesin])
            @endforeach

            <div class="flex items-center justify-between px-6 py-4 border-t border-slate-100 bg-slate-50/20">
                <div class="flex items-center gap-2 text-sm">
                    <form method="GET" action="{{ route('master.mesins.index') }}">
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
                    {{ $mesins->links() }}
                </div>
            </div>
        @else
            <div class="p-6">
                <x-empty-state
                    title="Mesin Tidak Ditemukan"
                    message="Tidak ada data mesin yang cocok dengan kriteria pencarian Anda."
                >
                    <button type="button"
                        @click="$dispatch('open-modal', { name: 'create-mesin' })"
                        class="btn-primary btn-sm flex items-center gap-1.5"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                        </svg>
                        Daftarkan Mesin
                    </button>
                </x-empty-state>
            </div>
        @endif
    </x-card>

    @include('mesins.partials.add')
</x-app-layout>

<x-app-layout>
    @section('title', 'Kategori NG')
    @section('page-title', 'Master Kategori Non-Good')

    <x-page-header title="Data Kategori Cacat (NG)" subtitle="Daftar jenis defect / produk non-good untuk operator saat input produksi.">
        <button type="button"
            @click="$dispatch('open-modal', { name: 'create-kategori-ng' })"
            class="btn-primary flex items-center gap-2"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Kategori NG
        </button>
    </x-page-header>

    <x-filter-section>
        <form method="GET" action="{{ route('master.kategori-ngs.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <x-form-input
                label="Cari Cacat/Defect"
                name="search"
                placeholder="Kode NG, nama cacat..."
                value="{{ request('search') }}"
            />

            <x-form-select label="Filter Tingkat Keparahan" name="severity" placeholder="Semua Severity">
                @foreach($severities as $severity)
                    <option value="{{ $severity->value }}" {{ request('severity') === $severity->value ? 'selected' : '' }}>
                        {{ $severity->label() }}
                    </option>
                @endforeach
            </x-form-select>

            <div class="flex gap-2">
                <button type="submit" class="flex-1 btn-primary py-2.5">Terapkan</button>
                <a href="{{ route('master.kategori-ngs.index') }}" class="btn-secondary py-2.5 inline-flex justify-center items-center">Reset</a>
            </div>
        </form>
    </x-filter-section>

    <x-card :padding="false">
        @if($kategoriNgs->count() > 0)
            <x-data-table :headers="['Kode NG', 'Jenis NG / Cacat', 'Severity Level', 'Aksi']">
                @foreach($kategoriNgs as $kategoriNg)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-red-50 text-red-750 border border-red-200/50 tracking-wider">
                                {{ $kategoriNg->kode_ng }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-800 text-sm">{{ $kategoriNg->nama_ng }}</div>
                        </td>
                        <td class="px-6 py-4">
                                @php
                                $badgeColor = match($kategoriNg->severity->value) {
                                    'low' => 'bg-slate-50 text-slate-700 border-slate-200',
                                    'medium' => 'bg-primary-50 text-primary-700 border-primary-200',
                                    'high' => 'bg-secondary-50 text-secondary-700 border-secondary-200',
                                    'critical' => 'bg-red-50 text-red-700 border-red-200',
                                    default => 'bg-slate-50 text-slate-700 border-slate-200'
                                };
                            @endphp
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold border {{ $badgeColor }}">
                                {{ $kategoriNg->severity->label() }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <button type="button"
                                    @click="$dispatch('open-modal', { name: 'edit-kategori-ng-{{ $kategoriNg->id }}' })"
                                    class="text-slate-400 hover:text-primary-600 transition-colors p-1.5 rounded-lg border border-slate-200 hover:bg-white hover:shadow-sm"
                                    title="Edit Kategori NG"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </button>

                                <x-confirm-delete
                                    :action="route('master.kategori-ngs.destroy', $kategoriNg->id)"
                                    title="Hapus Kategori Cacat?"
                                    text="Jenis cacat {{ $kategoriNg->nama_ng }} akan dihapus jika belum pernah digunakan dalam data produksi harian."
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

            @foreach($kategoriNgs as $kategoriNg)
                @include('kategori-ngs.partials.edit', ['item' => $kategoriNg])
            @endforeach

            <div class="flex items-center justify-between px-6 py-4 border-t border-slate-100 bg-slate-50/20">
                <div class="flex items-center gap-2 text-sm">
                    <form method="GET" action="{{ route('master.kategori-ngs.index') }}">
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
                    {{ $kategoriNgs->links() }}
                </div>
            </div>
        @else
            <div class="p-6">
                <x-empty-state
                    title="Kategori Cacat Tidak Ditemukan"
                    message="Tidak ada data kategori NG yang cocok dengan pencarian Anda."
                >
                    <button type="button"
                        @click="$dispatch('open-modal', { name: 'create-kategori-ng' })"
                        class="btn-primary btn-sm flex items-center gap-1.5"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah Cacat
                    </button>
                </x-empty-state>
            </div>
        @endif
    </x-card>

    @include('kategori-ngs.partials.add')
</x-app-layout>

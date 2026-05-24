<x-app-layout>
    @section('title', 'Laporan Produksi')
    @section('page-title', 'Laporan Harian Produksi')

    <x-page-header title="Laporan Produksi Harian" subtitle="Rekap data produksi berdasarkan rentang tanggal dan filter yang dipilih.">
        <a href="{{ route('laporans.index', array_merge(request()->query(), ['print' => 'true'])) }}"
           target="_blank"
           class="btn-secondary flex items-center gap-2"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
            </svg>
            Cetak Laporan
        </a>
    </x-page-header>

    <!-- Filter Section -->
    <x-filter-section>
        <form method="GET" action="{{ route('laporans.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-8 gap-4 items-end">
            <x-form-input
                label="Tanggal Mulai"
                name="start_date"
                type="date"
                value="{{ request('start_date', $startDate->format('Y-m-d')) }}"
            />
            <x-form-input
                label="Tanggal Selesai"
                name="end_date"
                type="date"
                value="{{ request('end_date', $endDate->format('Y-m-d')) }}"
            />
            <x-form-select label="Shift" name="shift_id" placeholder="Semua Shift">
                @foreach($shifts as $shift)
                    <option value="{{ $shift->id }}" {{ request('shift_id') == $shift->id ? 'selected' : '' }}>
                        {{ $shift->nama_shift }}
                    </option>
                @endforeach
            </x-form-select>
            <x-form-select label="Mesin" name="mesin_id" placeholder="Semua Mesin">
                @foreach($mesins as $mesin)
                    <option value="{{ $mesin->id }}" {{ request('mesin_id') == $mesin->id ? 'selected' : '' }}>
                        {{ $mesin->kode_mesin }}
                    </option>
                @endforeach
            </x-form-select>
            <x-form-select label="Part" name="part_id" placeholder="Semua Part">
                @foreach($parts as $part)
                    <option value="{{ $part->id }}" {{ request('part_id') == $part->id ? 'selected' : '' }}>
                        {{ $part->nomor_part }} - {{ $part->nama_part }}
                    </option>
                @endforeach
            </x-form-select>
            <x-form-select label="Status" name="status" placeholder="Semua (excl. Draft)">
                @foreach($statuses as $status)
                    <option value="{{ $status->value }}" {{ request('status') === $status->value ? 'selected' : '' }}>
                        {{ $status->label() }}
                    </option>
                @endforeach
            </x-form-select>
            <div class="flex gap-2 md:col-span-2 lg:col-span-1">
                <button type="submit" class="flex-1 btn-primary py-2.5">Terapkan</button>
                <a href="{{ route('laporans.index') }}" class="btn-secondary py-2.5 inline-flex justify-center items-center">Reset</a>
            </div>
        </form>
    </x-filter-section>

    <!-- Summary Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <x-stat-card
                title="Total Records"
                value="{{ number_format($totalRecords) }}"
                color="primary"
                subtitle="{{ $startDate->format('d M') }} – {{ $endDate->format('d M Y') }}"
            >
            <x-slot name="icon">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </x-slot>
        </x-stat-card>

        <x-stat-card
            title="Total Target"
            value="{{ number_format($totalTarget) }}"
            color="primary"
            subtitle="pcs"
        >
            <x-slot name="icon">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </x-slot>
        </x-stat-card>

        <x-stat-card
            title="Total Good (OK)"
            value="{{ number_format($totalGood) }}"
            color="success"
            trend="{{ $totalTarget > 0 ? round(($totalGood / $totalTarget) * 100, 1) . '%' : '0%' }}"
            subtitle="Achievement"
        >
            <x-slot name="icon">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </x-slot>
        </x-stat-card>

        <x-stat-card
            title="Total NG"
            value="{{ number_format($totalNg) }}"
            color="danger"
            trend="{{ ($totalGood + $totalNg) > 0 ? round(($totalNg / ($totalGood + $totalNg)) * 100, 1) . '%' : '0%' }}"
            :trendUp="false"
            subtitle="Defect rate"
        >
            <x-slot name="icon">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </x-slot>
        </x-stat-card>
    </div>

    <!-- Table -->
    <x-card :padding="false">
        @if($produksis->count() > 0)
            <x-data-table :headers="['Tanggal', 'Shift', 'Mesin', 'Part', 'Operator', 'Target', 'Good', 'NG', 'Achievement', 'Status']">
                @foreach($produksis as $produksi)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-4 py-3 whitespace-nowrap">
                            <div class="text-xs font-bold text-slate-800">{{ $produksi->tanggal_produksi->format('d M Y') }}</div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="text-xs font-semibold text-slate-700">{{ $produksi->shift->nama_shift }}</div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-primary-50 text-primary-700 border border-primary-200/50">
                                {{ $produksi->mesin->kode_mesin }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="text-xs font-semibold text-slate-700">{{ $produksi->part->nomor_part }}</div>
                            <div class="text-[10px] text-slate-400">{{ $produksi->part->nama_part }}</div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="text-xs font-semibold text-slate-700">{{ $produksi->operator->nama }}</div>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="text-xs font-bold text-slate-800">{{ number_format($produksi->target_qty) }}</div>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="text-xs font-bold text-emerald-600">{{ number_format($produksi->good_qty) }}</div>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="text-xs font-bold {{ $produksi->total_ng_qty > 0 ? 'text-red-500' : 'text-slate-300' }}">
                                {{ number_format($produksi->total_ng_qty) }}
                            </div>
                        </td>
                        <td class="px-4 py-3 text-right">
                            @php $rate = $produksi->achievement_rate; @endphp
                            <span class="text-xs font-bold {{ $rate >= 100 ? 'text-emerald-600' : ($rate >= 80 ? 'text-secondary-600' : 'text-red-500') }}">
                                {{ $rate }}%
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <x-badge :type="$produksi->status->value" />
                        </td>
                    </tr>
                @endforeach
            </x-data-table>

            <!-- Footer Totals -->
            <div class="px-6 py-4 border-t border-slate-200 bg-slate-50/50">
                <div class="flex flex-wrap items-center gap-6 text-xs font-bold text-slate-600">
                    <span>Total: <span class="text-slate-800">{{ $totalRecords }} records</span></span>
                    <span>Target: <span class="text-slate-800">{{ number_format($totalTarget) }} pcs</span></span>
                    <span>Good: <span class="text-emerald-600">{{ number_format($totalGood) }} pcs</span></span>
                    <span>NG: <span class="text-red-500">{{ number_format($totalNg) }} pcs</span></span>
                    <span>Achievement: <span class="text-primary-600">{{ $totalTarget > 0 ? round(($totalGood / $totalTarget) * 100, 1) : 0 }}%</span></span>
                </div>
            </div>

            <div class="flex items-center justify-between px-6 py-4 border-t border-slate-100 bg-slate-50/20">
                <div class="flex items-center gap-2 text-sm">
                    <form method="GET" action="{{ route('laporans.index') }}">
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
                    {{ $produksis->links() }}
                </div>
            </div>
        @else
            <div class="p-6">
                <x-empty-state
                    title="Tidak Ada Data Laporan"
                    message="Tidak ada data produksi yang sesuai dengan filter yang dipilih."
                />
            </div>
        @endif
    </x-card>
</x-app-layout>

<x-app-layout>
    @section('title', 'Produksi Harian')
    @section('page-title', 'Pencatatan Produksi Harian')

    <x-page-header title="Produksi Harian" subtitle="Daftar pencatatan produksi harian Press-3 PT Mitsuba Indonesia.">
        @can('create', App\Models\Produksi::class)
            <button type="button"
                @click="$dispatch('open-modal', { name: 'create-produksi' })"
                class="btn-primary flex items-center gap-2"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                </svg>
                Input Produksi
            </button>
        @endcan
    </x-page-header>

    <x-filter-section>
        <form method="GET" action="{{ route('produksis.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
            <x-form-input
                label="Tanggal Produksi"
                name="tanggal_produksi"
                type="date"
                value="{{ request('tanggal_produksi') }}"
            />

            <x-form-select label="Filter Shift" name="shift_id" placeholder="Semua Shift">
                @foreach($shifts as $shift)
                    <option value="{{ $shift->id }}" {{ request('shift_id') == $shift->id ? 'selected' : '' }}>
                        {{ $shift->nama_shift }}
                    </option>
                @endforeach
            </x-form-select>

            <x-form-select label="Filter Mesin" name="mesin_id" placeholder="Semua Mesin">
                @foreach($mesins as $mesin)
                    <option value="{{ $mesin->id }}" {{ request('mesin_id') == $mesin->id ? 'selected' : '' }}>
                        {{ $mesin->kode_mesin }}
                    </option>
                @endforeach
            </x-form-select>

            <x-form-select label="Filter Status" name="status" placeholder="Semua Status">
                @foreach($statuses as $status)
                    <option value="{{ $status->value }}" {{ request('status') === $status->value ? 'selected' : '' }}>
                        {{ $status->label() }}
                    </option>
                @endforeach
            </x-form-select>

            <div class="flex gap-2">
                <button type="submit" class="flex-1 btn-primary py-2.5">Terapkan</button>
                <a href="{{ route('produksis.index') }}" class="btn-secondary py-2.5 inline-flex justify-center items-center">Reset</a>
            </div>
        </form>

        @if(!auth()->user()->isOperator())
            <div class="mt-4 pt-4 border-t border-slate-100">
                <form method="GET" action="{{ route('produksis.index') }}" class="flex gap-3 items-end">
                    @foreach(request()->except('operator_search') as $key => $val)
                        <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                    @endforeach
                    <div class="flex-1 max-w-xs">
                        <x-form-input
                            label="Cari Operator"
                            name="operator_search"
                            placeholder="Nama atau username operator..."
                            value="{{ request('operator_search') }}"
                        />
                    </div>
                    <button type="submit" class="btn-secondary py-2.5 px-4">Cari</button>
                </form>
            </div>
        @endif
    </x-filter-section>

    <x-card :padding="false">
        @if($produksis->count() > 0)
            <x-data-table :headers="['Tanggal', 'Shift / Mesin', 'Part', 'Operator', 'Target', 'Good / NG', 'Status', 'Aksi']">
                @foreach($produksis as $produksi)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-slate-800">{{ $produksi->tanggal_produksi->format('d M Y') }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-xs font-bold text-slate-700">{{ $produksi->shift->nama_shift }}</div>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-primary-50 text-primary-700 border border-primary-200/50 mt-0.5">
                                {{ $produksi->mesin->kode_mesin }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-xs font-bold text-slate-700">{{ $produksi->part->nomor_part }}</div>
                            <div class="text-[11px] text-slate-400 font-medium">{{ $produksi->part->nama_part }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-xs font-semibold text-slate-700">{{ $produksi->operator->nama }}</div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="text-sm font-bold text-slate-800">{{ number_format($produksi->target_qty) }}</div>
                            <div class="text-[10px] text-slate-400 font-semibold">pcs</div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="text-sm font-bold text-emerald-600">{{ number_format($produksi->good_qty) }} <span class="text-[10px] font-semibold text-slate-400">OK</span></div>
                            @if($produksi->total_ng_qty > 0)
                                <div class="text-xs font-bold text-red-500">{{ number_format($produksi->total_ng_qty) }} <span class="text-[10px] font-semibold">NG</span></div>
                            @else
                                <div class="text-[10px] font-semibold text-slate-300">0 NG</div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <x-badge :type="$produksi->status->value" />
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-1.5">
                                <button type="button"
                                    @click="$dispatch('open-modal', { name: 'detail-produksi-{{ $produksi->id }}' })"
                                    class="text-slate-400 hover:text-primary-600 transition-colors p-1.5 rounded-lg border border-slate-200 hover:bg-white hover:shadow-sm"
                                    title="Lihat Detail"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>

                                @can('update', $produksi)
                                    <button type="button"
                                        @click="$dispatch('open-modal', { name: 'edit-produksi-{{ $produksi->id }}' })"
                                        class="text-slate-400 hover:text-secondary-600 transition-colors p-1.5 rounded-lg border border-slate-200 hover:bg-white hover:shadow-sm"
                                        title="Edit Produksi"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </button>
                                @endcan

                                @can('delete', $produksi)
                                    <x-confirm-delete
                                        :action="route('produksis.destroy', $produksi->id)"
                                        title="Hapus Data Produksi?"
                                        text="Data produksi tanggal {{ $produksi->tanggal_produksi->format('d M Y') }} akan dihapus permanen."
                                        class="text-slate-400 hover:text-red-600 transition-colors p-1.5 rounded-lg border border-slate-200 hover:bg-white hover:shadow-sm"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </x-confirm-delete>
                                @endcan
                            </div>
                        </td>
                    </tr>

                @endforeach
            </x-data-table>

            @foreach($produksis as $produksi)
                @include('produksis.partials.detail', ['item' => $produksi])
                @include('produksis.partials.edit', ['item' => $produksi])
            @endforeach

            <div class="flex items-center justify-between px-6 py-4 border-t border-slate-100 bg-slate-50/20">
                <div class="flex items-center gap-2 text-sm">
                    <form method="GET" action="{{ route('produksis.index') }}">
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
                    title="Data Produksi Tidak Ditemukan"
                    message="Belum ada data produksi yang sesuai dengan filter yang dipilih."
                >
                    @can('create', App\Models\Produksi::class)
                        <button type="button"
                            @click="$dispatch('open-modal', { name: 'create-produksi' })"
                            class="btn-primary btn-sm flex items-center gap-1.5"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                            </svg>
                            Input Produksi Baru
                        </button>
                    @endcan
                </x-empty-state>
            </div>
        @endif
    </x-card>

    @include('produksis.partials.add')

    @push('scripts')
    <script>
        function produksiForm(initialData) {
            const hasOld = initialData && Object.keys(initialData).length > 0;
            const ngData = (initialData && initialData.ng) || [];

            return {
                targetQty: parseInt(initialData.target_qty) || 0,
                goodQty: parseInt(initialData.good_qty) || 0,
                totalNg: 0,
                ngRows: ngData.length > 0 ? ngData.map(r => ({ kategori_ng_id: r.kategori_ng_id || '', qty: r.qty || '', catatan: r.catatan || '' })) : [{ kategori_ng_id: '', qty: '', catatan: '' }],

                get achievementRate() {
                    if (this.targetQty <= 0) return 0;
                    return Math.round((this.goodQty / this.targetQty) * 1000) / 10;
                },

                addNgRow() {
                    this.ngRows.push({ kategori_ng_id: '', qty: '', catatan: '' });
                },

                removeNgRow(index) {
                    this.ngRows.splice(index, 1);
                    this.calcTotalNg();
                },

                calcTotalNg() {
                    this.totalNg = this.ngRows.reduce((sum, row) => sum + (parseInt(row.qty) || 0), 0);
                },

                init() {
                    this.calcTotalNg();
                    this.$watch('ngRows', () => this.calcTotalNg(), { deep: true });
                }
            }
        }

        function confirmAction(formId, title, text) {
            Swal.fire({
                title: title,
                text: text,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#4f46e5',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Submit!',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-2xl shadow-xl border border-slate-100',
                    confirmButton: 'rounded-xl px-5 py-2.5 font-semibold text-sm',
                    cancelButton: 'rounded-xl px-5 py-2.5 font-semibold text-sm'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
        }

        function confirmVerify(formId) {
            Swal.fire({
                title: 'Verifikasi Produksi?',
                text: 'Data produksi ini akan ditandai sebagai Verified.',
                icon: 'question',
                input: 'textarea',
                inputLabel: 'Catatan Verifikasi (Opsional)',
                inputPlaceholder: 'Tambahkan catatan jika diperlukan...',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Verifikasi!',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-2xl shadow-xl border border-slate-100',
                    confirmButton: 'rounded-xl px-5 py-2.5 font-semibold text-sm',
                    cancelButton: 'rounded-xl px-5 py-2.5 font-semibold text-sm'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById(formId);
                    form.querySelector('.verify-catatan').value = result.value || '';
                    form.submit();
                }
            });
        }

        function confirmReject(formId) {
            Swal.fire({
                title: 'Reject / Minta Revisi?',
                text: 'Berikan catatan revisi yang jelas untuk operator.',
                icon: 'warning',
                input: 'textarea',
                inputLabel: 'Catatan Revisi (Wajib)',
                inputPlaceholder: 'Jelaskan alasan rejection atau perbaikan yang diperlukan...',
                inputAttributes: { required: true },
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Reject!',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-2xl shadow-xl border border-slate-100',
                    confirmButton: 'rounded-xl px-5 py-2.5 font-semibold text-sm',
                    cancelButton: 'rounded-xl px-5 py-2.5 font-semibold text-sm'
                },
                preConfirm: (catatan) => {
                    if (!catatan || catatan.trim().length < 5) {
                        Swal.showValidationMessage('Catatan revisi minimal 5 karakter.');
                    }
                    return catatan;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById(formId);
                    form.querySelector('.reject-catatan').value = result.value;
                    form.submit();
                }
            });
        }
    </script>
    @endpush
</x-app-layout>

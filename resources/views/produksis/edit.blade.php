<x-app-layout>
    @section('title', 'Edit Produksi')
    @section('page-title', 'Edit Data Produksi')

    <x-page-header title="Edit Produksi Harian" subtitle="Perbarui data produksi harian. Hanya tersedia untuk status Draft, Rejected, atau Revised.">
        <a href="{{ route('produksis.show', $produksi->id) }}" class="btn-secondary flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali
        </a>
    </x-page-header>

    <form method="POST" action="{{ route('produksis.update', $produksi->id) }}" x-data="produksiEditForm()" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Main Data -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Data Produksi Card -->
                <x-card title="Data Produksi">
                    <div class="space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <x-form-input
                                label="Tanggal Produksi"
                                name="tanggal_produksi"
                                type="date"
                                value="{{ old('tanggal_produksi', $produksi->tanggal_produksi->format('Y-m-d')) }}"
                                required
                            />

                            <x-form-select label="Shift Kerja" name="shift_id" required placeholder="Pilih shift...">
                                @foreach($shifts as $shift)
                                    <option value="{{ $shift->id }}" {{ old('shift_id', $produksi->shift_id) == $shift->id ? 'selected' : '' }}>
                                        {{ $shift->nama_shift }} ({{ substr($shift->jam_masuk, 0, 5) }} - {{ substr($shift->jam_selesai, 0, 5) }})
                                    </option>
                                @endforeach
                            </x-form-select>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <x-form-select label="Mesin" name="mesin_id" required placeholder="Pilih mesin...">
                                @foreach($mesins as $mesin)
                                    <option value="{{ $mesin->id }}" {{ old('mesin_id', $produksi->mesin_id) == $mesin->id ? 'selected' : '' }}>
                                        {{ $mesin->kode_mesin }} - {{ $mesin->nama_mesin }}
                                    </option>
                                @endforeach
                            </x-form-select>

                            <x-form-select label="Nomor Part" name="part_id" required placeholder="Pilih part...">
                                @foreach($parts as $part)
                                    <option value="{{ $part->id }}" {{ old('part_id', $produksi->part_id) == $part->id ? 'selected' : '' }}>
                                        {{ $part->nomor_part }} - {{ $part->nama_part }}
                                    </option>
                                @endforeach
                            </x-form-select>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <x-form-input
                                label="Target Quantity (pcs)"
                                name="target_qty"
                                type="number"
                                value="{{ old('target_qty', $produksi->target_qty) }}"
                                required
                                x-model.number="targetQty"
                            />

                            <x-form-input
                                label="Good Quantity (pcs)"
                                name="good_qty"
                                type="number"
                                value="{{ old('good_qty', $produksi->good_qty) }}"
                                required
                                x-model.number="goodQty"
                            />
                        </div>

                        <x-form-textarea
                            label="Catatan (Opsional)"
                            name="catatan"
                            value="{{ old('catatan', $produksi->catatan) }}"
                            rows="2"
                        />
                    </div>
                </x-card>

                <!-- Detail NG Card -->
                <x-card>
                    <x-slot name="header">
                        <h3 class="text-sm font-bold text-slate-800 tracking-tight">Detail Non-Good (NG)</h3>
                        <button type="button" @click="addNgRow()" class="btn-secondary btn-sm flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                            </svg>
                            Tambah NG
                        </button>
                    </x-slot>

                    <div class="space-y-3">
                        <template x-for="(row, index) in ngRows" :key="index">
                            <div class="flex items-start gap-3 p-4 bg-red-50/40 border border-red-100 rounded-xl">
                                <div class="flex-1">
                                    <label class="form-label font-bold text-slate-700">Kategori NG <span class="text-red-500">*</span></label>
                                    <select
                                        :name="`ng[${index}][kategori_ng_id]`"
                                        x-model="row.kategori_ng_id"
                                        class="form-input-custom border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20"
                                        required
                                    >
                                        <option value="">Pilih kategori NG...</option>
                                        @foreach($kategoriNgs as $ng)
                                            <option value="{{ $ng->id }}">{{ $ng->kode_ng }} - {{ $ng->nama_ng }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="w-28">
                                    <label class="form-label font-bold text-slate-700">Qty NG <span class="text-red-500">*</span></label>
                                    <input
                                        type="number"
                                        :name="`ng[${index}][qty]`"
                                        x-model.number="row.qty"
                                        @input="calcTotalNg()"
                                        min="1"
                                        placeholder="0"
                                        class="form-input-custom border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20"
                                        required
                                    />
                                </div>

                                <div class="flex-1">
                                    <label class="form-label font-bold text-slate-700">Catatan NG</label>
                                    <input
                                        type="text"
                                        :name="`ng[${index}][catatan]`"
                                        x-model="row.catatan"
                                        placeholder="Keterangan tambahan..."
                                        class="form-input-custom border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20"
                                    />
                                </div>

                                <div class="pt-6">
                                    <button type="button" @click="removeNgRow(index)" class="p-1.5 text-red-400 hover:text-red-600 hover:bg-red-100 rounded-lg transition-colors border border-red-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </template>

                        <div x-show="ngRows.length === 0" class="text-center py-6 text-slate-400 text-sm font-semibold">
                            Tidak ada NG — klik "Tambah NG" jika ada produk cacat.
                        </div>
                    </div>
                </x-card>
            </div>

            <!-- Right Column: Summary + Actions -->
            <div class="space-y-6">
                <!-- Status Info -->
                <x-card title="Status Saat Ini">
                    <div class="flex items-center gap-3">
                        <x-badge :type="$produksi->status->value" />
                        <span class="text-xs text-slate-500 font-semibold">{{ $produksi->status->label() }}</span>
                    </div>
                    @if($produksi->isRejected())
                        @php $lastVerifikasi = $produksi->latestVerifikasi; @endphp
                        @if($lastVerifikasi && $lastVerifikasi->catatan)
                            <div class="mt-3 p-3 bg-red-50 border border-red-100 rounded-xl">
                                <p class="text-xs font-bold text-red-700 mb-1">Catatan Rejection:</p>
                                <p class="text-xs text-red-600">{{ $lastVerifikasi->catatan }}</p>
                            </div>
                        @endif
                    @endif
                </x-card>

                <!-- Ringkasan -->
                <x-card title="Ringkasan Produksi">
                    <div class="space-y-4">
                        <div class="flex justify-between items-center py-2 border-b border-slate-100">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Target</span>
                            <span class="text-sm font-extrabold text-slate-800" x-text="targetQty.toLocaleString() + ' pcs'"></span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-slate-100">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Good (OK)</span>
                            <span class="text-sm font-extrabold text-emerald-600" x-text="goodQty.toLocaleString() + ' pcs'"></span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-slate-100">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total NG</span>
                            <span class="text-sm font-extrabold text-red-500" x-text="totalNg.toLocaleString() + ' pcs'"></span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-slate-100">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Achievement</span>
                            <span class="text-sm font-extrabold text-primary-600" x-text="achievementRate + '%'"></span>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Defect Rate</span>
                            <span class="text-sm font-extrabold text-amber-600" x-text="defectRate + '%'"></span>
                        </div>
                    </div>
                </x-card>

                <!-- Action Buttons -->
                <x-card title="Simpan Perubahan">
                    <div class="space-y-3">
                        @if($produksi->isDraft() || $produksi->isRevised())
                            <button type="submit" name="action" value="draft" class="w-full btn-secondary flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                                </svg>
                                Simpan Draft
                            </button>
                        @endif
                        @if($produksi->isDraft() || $produksi->isRevised() || $produksi->isRejected())
                            <button type="submit" name="action" value="submit" class="w-full btn-primary flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Submit untuk Verifikasi
                            </button>
                        @endif
                        <a href="{{ route('produksis.show', $produksi->id) }}" class="w-full btn-secondary flex items-center justify-center gap-2">
                            Batal
                        </a>
                    </div>
                </x-card>
            </div>
        </div>
    </form>

    @push('scripts')
    <script>
        function produksiEditForm() {
            return {
                targetQty: {{ old('target_qty', $produksi->target_qty) }},
                goodQty: {{ old('good_qty', $produksi->good_qty) }},
                totalNg: 0,
                ngRows: @json(old('ng', $produksi->detailNgProduksis->map(fn($d) => ['kategori_ng_id' => $d->kategori_ng_id, 'qty' => $d->qty, 'catatan' => $d->catatan ?? '']))),

                get achievementRate() {
                    if (this.targetQty <= 0) return 0;
                    return Math.round((this.goodQty / this.targetQty) * 1000) / 10;
                },

                get defectRate() {
                    const total = this.goodQty + this.totalNg;
                    if (total <= 0) return 0;
                    return Math.round((this.totalNg / total) * 1000) / 10;
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
    </script>
    @endpush
</x-app-layout>

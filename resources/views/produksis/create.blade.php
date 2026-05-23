<x-app-layout>
    @section('title', 'Input Produksi')
    @section('page-title', 'Input Produksi Harian')

    <x-page-header title="Input Produksi Harian" subtitle="Catat data produksi harian termasuk detail produk NG (Non-Good).">
        <a href="{{ route('produksis.index') }}" class="btn-secondary flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali
        </a>
    </x-page-header>

    <form method="POST" action="{{ route('produksis.store') }}" x-data="produksiForm()" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Main Data -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Data Produksi Card -->
                <x-card title="Data Produksi">
                    <div class="space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <!-- Tanggal Produksi -->
                            <x-form-input
                                label="Tanggal Produksi"
                                name="tanggal_produksi"
                                type="date"
                                value="{{ date('Y-m-d') }}"
                                required
                            />

                            <!-- Shift -->
                            <x-form-select label="Shift Kerja" name="shift_id" required placeholder="Pilih shift...">
                                @foreach($shifts as $shift)
                                    <option value="{{ $shift->id }}" {{ old('shift_id') == $shift->id ? 'selected' : '' }}>
                                        {{ $shift->nama_shift }} ({{ substr($shift->jam_masuk, 0, 5) }} - {{ substr($shift->jam_selesai, 0, 5) }})
                                    </option>
                                @endforeach
                            </x-form-select>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <!-- Mesin -->
                            <x-form-select label="Mesin" name="mesin_id" required placeholder="Pilih mesin...">
                                @foreach($mesins as $mesin)
                                    <option value="{{ $mesin->id }}" {{ old('mesin_id') == $mesin->id ? 'selected' : '' }}>
                                        {{ $mesin->kode_mesin }} - {{ $mesin->nama_mesin }}
                                    </option>
                                @endforeach
                            </x-form-select>

                            <!-- Part -->
                            <x-form-select label="Nomor Part" name="part_id" required placeholder="Pilih part...">
                                @foreach($parts as $part)
                                    <option value="{{ $part->id }}" {{ old('part_id') == $part->id ? 'selected' : '' }}>
                                        {{ $part->nomor_part }} - {{ $part->nama_part }}
                                    </option>
                                @endforeach
                            </x-form-select>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <!-- Target Qty -->
                            <x-form-input
                                label="Target Quantity (pcs)"
                                name="target_qty"
                                type="number"
                                placeholder="0"
                                value="{{ old('target_qty') }}"
                                required
                                x-model.number="targetQty"
                            />

                            <!-- Good Qty -->
                            <x-form-input
                                label="Good Quantity (pcs)"
                                name="good_qty"
                                type="number"
                                placeholder="0"
                                value="{{ old('good_qty') }}"
                                required
                                x-model.number="goodQty"
                            />
                        </div>

                        <!-- Catatan -->
                        <x-form-textarea
                            label="Catatan (Opsional)"
                            name="catatan"
                            placeholder="Catatan tambahan mengenai produksi hari ini..."
                            value="{{ old('catatan') }}"
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

                    <div class="space-y-3" id="ng-rows">
                        <template x-for="(row, index) in ngRows" :key="index">
                            <div class="flex items-start gap-3 p-4 bg-red-50/40 border border-red-100 rounded-xl">
                                <!-- Kategori NG -->
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

                                <!-- Qty NG -->
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

                                <!-- Catatan NG -->
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

                                <!-- Remove Button -->
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

            <!-- Right Column: Summary -->
            <div class="space-y-6">
                <x-card title="Ringkasan Produksi">
                    <div class="space-y-4">
                        <div class="flex justify-between items-center py-2 border-b border-slate-100">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Target</span>
                            <span class="text-sm font-extrabold text-slate-800" x-text="targetQty.toLocaleString() + ' pcs'">0 pcs</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-slate-100">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Good (OK)</span>
                            <span class="text-sm font-extrabold text-emerald-600" x-text="goodQty.toLocaleString() + ' pcs'">0 pcs</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-slate-100">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total NG</span>
                            <span class="text-sm font-extrabold text-red-500" x-text="totalNg.toLocaleString() + ' pcs'">0 pcs</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-slate-100">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Achievement</span>
                            <span class="text-sm font-extrabold text-primary-600" x-text="achievementRate + '%'">0%</span>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Defect Rate</span>
                            <span class="text-sm font-extrabold text-amber-600" x-text="defectRate + '%'">0%</span>
                        </div>
                    </div>
                </x-card>

                <!-- Action Buttons -->
                <x-card title="Simpan Data">
                    <div class="space-y-3">
                        <button type="submit" name="action" value="draft" class="w-full btn-secondary flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                            </svg>
                            Simpan sebagai Draft
                        </button>
                        <button type="submit" name="action" value="submit" class="w-full btn-primary flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Submit untuk Verifikasi
                        </button>
                        <a href="{{ route('produksis.index') }}" class="w-full btn-secondary flex items-center justify-center gap-2">
                            Batal
                        </a>
                    </div>
                </x-card>
            </div>
        </div>
    </form>

    @push('scripts')
    <script>
        function produksiForm() {
            return {
                targetQty: {{ old('target_qty', 0) }},
                goodQty: {{ old('good_qty', 0) }},
                totalNg: 0,
                ngRows: @json(old('ng', [])),

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

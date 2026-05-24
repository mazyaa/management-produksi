<x-modal
    name="edit-produksi-{{ $item->id }}"
    title="Edit Produksi Harian"
    max-width="2xl"
    :initial-open="$errors->any() && old('_method') === 'PUT' && old('id') == $item->id"
>
    @php
        $editFormData = old('_method') === 'PUT' && old('id') == $item->id
            ? old()
            : [
                'tanggal_produksi' => $item->tanggal_produksi->format('Y-m-d'),
                'shift_id' => (string)$item->shift_id,
                'mesin_id' => (string)$item->mesin_id,
                'part_id' => (string)$item->part_id,
                'target_qty' => (string)$item->target_qty,
                'good_qty' => (string)$item->good_qty,
                'catatan' => $item->catatan,
                'ng' => $item->detailNgProduksis->map(fn($d) => ['kategori_ng_id' => (string)$d->kategori_ng_id, 'qty' => (string)$d->qty, 'catatan' => $d->catatan ?? ''])->toArray()
            ];
    @endphp
    <div x-data='produksiForm(@json($editFormData))'>
        <form method="POST" action="{{ route('produksis.update', $item->id) }}" class="space-y-5">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" value="{{ $item->id }}">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <x-form-input
                    label="Tanggal Produksi"
                    name="tanggal_produksi"
                    type="date"
                    value="{{ old('tanggal_produksi', $item->tanggal_produksi->format('Y-m-d')) }}"
                    required
                />
                <x-form-select label="Shift" name="shift_id" required placeholder="Pilih Shift...">
                    @foreach($shifts as $shift)
                        <option value="{{ $shift->id }}" {{ old('shift_id', $item->shift_id) == $shift->id ? 'selected' : '' }}>{{ $shift->nama_shift }}</option>
                    @endforeach
                </x-form-select>
                <x-form-select label="Mesin" name="mesin_id" required placeholder="Pilih Mesin...">
                    @foreach($mesins as $mesin)
                        <option value="{{ $mesin->id }}" {{ old('mesin_id', $item->mesin_id) == $mesin->id ? 'selected' : '' }}>{{ $mesin->kode_mesin }}</option>
                    @endforeach
                </x-form-select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-form-select label="Part" name="part_id" required placeholder="Pilih Part...">
                    @foreach($parts as $part)
                        <option value="{{ $part->id }}" {{ old('part_id', $item->part_id) == $part->id ? 'selected' : '' }}>{{ $part->nomor_part }} - {{ $part->nama_part }}</option>
                    @endforeach
                </x-form-select>
                <x-form-input
                    label="Catatan"
                    name="catatan"
                    placeholder="Opsional"
                    value="{{ old('catatan', $item->catatan) }}"
                />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-form-input
                    label="Target Quantity"
                    name="target_qty"
                    type="number"
                    min="1"
                    placeholder="0"
                    x-model.number="targetQty"
                    required
                />
                <x-form-input
                    label="Good Quantity (OK)"
                    name="good_qty"
                    type="number"
                    min="0"
                    placeholder="0"
                    x-model.number="goodQty"
                    required
                />
            </div>

            <div class="bg-slate-50/50 border border-slate-200/60 rounded-xl p-4">
                <div class="flex items-center justify-between mb-3">
                    <h4 class="text-xs font-bold text-slate-600 uppercase tracking-wider">Detail Non-Good (NG)</h4>
                    <button type="button" @click="addNgRow()" class="btn-secondary btn-sm text-xs px-3 py-1.5 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah NG
                    </button>
                </div>

                <template x-for="(row, index) in ngRows" :key="index">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-3 mb-3 items-end">
                        <div class="md:col-span-1">
                            <label class="form-label font-bold text-slate-700 text-xs">Kategori NG</label>
                            <select x-model.number="row.kategori_ng_id" :name="'ng['+index+'][kategori_ng_id]'" required
                                class="form-input-custom border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 w-full text-xs">
                                <option value="">Pilih...</option>
                                @foreach($kategoriNgs as $ng)
                                    <option value="{{ $ng->id }}">{{ $ng->kode_ng }} - {{ $ng->nama_ng }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="form-label font-bold text-slate-700 text-xs">Qty</label>
                            <input type="number" x-model.number="row.qty" @input.debounce="calcTotalNg()" :name="'ng['+index+'][qty]'" min="1" required
                                class="form-input-custom border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 w-full text-xs">
                        </div>
                        <div>
                            <label class="form-label font-bold text-slate-700 text-xs">Catatan</label>
                            <input type="text" x-model="row.catatan" :name="'ng['+index+'][catatan]'"
                                class="form-input-custom border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 w-full text-xs">
                        </div>
                        <div>
                            <button type="button" @click="removeNgRow(index)" class="text-red-500 hover:text-red-700 p-2" x-show="ngRows.length > 1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </template>

                <div class="flex justify-between items-center mt-3 pt-3 border-t border-slate-200/60">
                    <div class="text-xs text-slate-500 font-semibold">
                        Total NG: <span class="text-red-600 font-bold" x-text="totalNg"></span> pcs
                    </div>
                    <div class="text-xs text-slate-500 font-semibold">
                        Achievement: <span class="text-primary-600 font-bold" x-text="achievementRate"></span>%
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-slate-100">
                <button type="button" @click="$dispatch('close-modal', { name: 'edit-produksi-{{ $item->id }}' })" class="btn-secondary">Batal</button>
                <button type="submit" name="action" value="save" class="btn-secondary">Simpan sebagai Draft</button>
                <button type="submit" name="action" value="submit" class="btn-primary">Simpan & Submit</button>
            </div>
        </form>
    </div>
</x-modal>

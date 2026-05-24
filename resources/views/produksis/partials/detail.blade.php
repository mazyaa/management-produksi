<x-modal
    name="detail-produksi-{{ $item->id }}"
    title="Detail Produksi Harian"
    max-width="4xl"
>
    <div class="space-y-6">
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Tanggal</p>
                <p class="text-sm font-bold text-slate-800">{{ $item->tanggal_produksi->format('d M Y') }}</p>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Shift</p>
                <p class="text-sm font-bold text-slate-800">{{ $item->shift->nama_shift }}</p>
                <p class="text-xs text-slate-400 font-medium">{{ substr($item->shift->jam_masuk, 0, 5) }} - {{ substr($item->shift->jam_selesai, 0, 5) }}</p>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Mesin</p>
                <p class="text-sm font-bold text-slate-800">{{ $item->mesin->kode_mesin }}</p>
                <p class="text-xs text-slate-400 font-medium">{{ $item->mesin->nama_mesin }}</p>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Part</p>
                <p class="text-sm font-bold text-slate-800">{{ $item->part->nomor_part }}</p>
                <p class="text-xs text-slate-400 font-medium">{{ $item->part->nama_part }}</p>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Operator</p>
                <p class="text-sm font-bold text-slate-800">{{ $item->operator->nama }}</p>
                <p class="text-xs text-slate-400 font-medium">{{ $item->operator->role->label() }}</p>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Status</p>
                <x-badge :type="$item->status->value" />
            </div>
        </div>

        @if($item->catatan)
            <div class="pt-3 border-t border-slate-100">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Catatan</p>
                <p class="text-sm text-slate-700 font-medium">{{ $item->catatan }}</p>
            </div>
        @endif

        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <div class="bg-white rounded-xl border border-slate-200/60 border-l-4 border-l-primary-500 p-3 shadow-sm">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Target</p>
                <p class="text-lg font-extrabold text-slate-800 mt-1">{{ number_format($item->target_qty) }}</p>
                <p class="text-[10px] text-slate-400 font-semibold">pcs</p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200/60 border-l-4 border-l-emerald-500 p-3 shadow-sm">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Good (OK)</p>
                <p class="text-lg font-extrabold text-emerald-600 mt-1">{{ number_format($item->good_qty) }}</p>
                <p class="text-[10px] text-slate-400 font-semibold">pcs</p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200/60 border-l-4 border-l-red-500 p-3 shadow-sm">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total NG</p>
                <p class="text-lg font-extrabold text-red-500 mt-1">{{ number_format($item->total_ng_qty) }}</p>
                <p class="text-[10px] text-slate-400 font-semibold">pcs</p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200/60 border-l-4 border-l-amber-500 p-3 shadow-sm">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Achievement</p>
                <p class="text-lg font-extrabold text-amber-600 mt-1">{{ $item->achievement_rate }}%</p>
                <p class="text-[10px] text-slate-400 font-semibold">dari target</p>
            </div>
        </div>

        @if($item->detailNgProduksis->count() > 0)
            <div>
                <h4 class="text-xs font-bold text-slate-700 mb-2 uppercase tracking-wider">Detail Non-Good (NG)</h4>
                <div class="overflow-x-auto border border-slate-200/60 rounded-xl">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-slate-50/80 border-b border-slate-100">
                                <th class="text-left py-2 px-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Kode NG</th>
                                <th class="text-left py-2 px-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Jenis Cacat</th>
                                <th class="text-left py-2 px-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Severity</th>
                                <th class="text-right py-2 px-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Qty</th>
                                <th class="text-left py-2 px-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Catatan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($item->detailNgProduksis as $detail)
                                <tr class="hover:bg-slate-50/50">
                                    <td class="py-2.5 px-3">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-red-50 text-red-700 border border-red-200/50">
                                            {{ $detail->kategoriNg->kode_ng }}
                                        </span>
                                    </td>
                                    <td class="py-2.5 px-3 font-semibold text-slate-700">{{ $detail->kategoriNg->nama_ng }}</td>
                                    <td class="py-2.5 px-3">
                                        @php
                                            $sevColor = match($detail->kategoriNg->severity->value) {
                                                'low' => 'bg-slate-50 text-slate-700 border-slate-200',
                                                'medium' => 'bg-blue-50 text-blue-700 border-blue-200',
                                                'high' => 'bg-amber-50 text-amber-700 border-amber-200',
                                                'critical' => 'bg-red-50 text-red-700 border-red-200',
                                                default => 'bg-slate-50 text-slate-700 border-slate-200'
                                            };
                                        @endphp
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold border {{ $sevColor }}">
                                            {{ $detail->kategoriNg->severity->label() }}
                                        </span>
                                    </td>
                                    <td class="py-2.5 px-3 text-right font-bold text-red-600">{{ number_format($detail->qty) }}</td>
                                    <td class="py-2.5 px-3 text-xs text-slate-500">{{ $detail->catatan ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <div>
            <h4 class="text-xs font-bold text-slate-700 mb-2 uppercase tracking-wider">Histori Verifikasi</h4>
            @if($item->verifikasiProduksis->count() > 0)
                <div class="space-y-3">
                    @foreach($item->verifikasiProduksis->sortByDesc('verified_at') as $verifikasi)
                        <div class="flex gap-3 bg-slate-50/50 rounded-xl p-3 border border-slate-100">
                            <div class="flex-shrink-0 mt-0.5">
                                @if($verifikasi->status === 'verified')
                                    <div class="w-7 h-7 rounded-full bg-emerald-100 border border-emerald-200 flex items-center justify-center">
                                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                @else
                                    <div class="w-7 h-7 rounded-full bg-red-100 border border-red-200 flex items-center justify-center">
                                        <svg class="w-3.5 h-3.5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="text-xs font-bold text-slate-800">{{ $verifikasi->verifier->nama }}</span>
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold {{ $verifikasi->status === 'verified' ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700' }}">
                                        {{ $verifikasi->status === 'verified' ? 'Verified' : 'Rejected' }}
                                    </span>
                                </div>
                                <p class="text-[11px] text-slate-400 font-medium mt-0.5">{{ $verifikasi->verified_at->format('d M Y, H:i') }} WIB</p>
                                @if($verifikasi->catatan)
                                    <p class="text-xs text-slate-600 mt-1.5 bg-white rounded-lg p-2 border border-slate-100">{{ $verifikasi->catatan }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-slate-400 font-semibold text-center py-4">Belum ada histori verifikasi.</p>
            @endif
        </div>

        <div class="flex gap-2 pt-2">
            @can('submit', $item)
                <button type="button"
                    onclick="confirmAction('submit-form-{{ $item->id }}', 'Submit Produksi?', 'Data produksi akan disubmit ke Leader untuk diverifikasi.')"
                    class="btn-primary btn-sm flex items-center gap-1.5"
                >
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Submit
                </button>
                <form id="submit-form-{{ $item->id }}" method="POST" action="{{ route('produksis.submit', $item->id) }}" class="hidden">
                    @csrf @method('PATCH')
                </form>
            @endcan
            @can('verify', $item)
                <button type="button"
                    onclick="confirmVerify('verify-form-{{ $item->id }}')"
                    class="btn-success btn-sm flex items-center gap-1.5"
                >
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Verifikasi
                </button>
                <form id="verify-form-{{ $item->id }}" method="POST" action="{{ route('produksis.verify', $item->id) }}" class="hidden">
                    @csrf @method('PATCH')
                    <input type="hidden" name="catatan" class="verify-catatan">
                </form>
            @endcan
            @can('reject', $item)
                <button type="button"
                    onclick="confirmReject('reject-form-{{ $item->id }}')"
                    class="btn-danger btn-sm flex items-center gap-1.5"
                >
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Reject
                </button>
                <form id="reject-form-{{ $item->id }}" method="POST" action="{{ route('produksis.reject', $item->id) }}" class="hidden">
                    @csrf @method('PATCH')
                    <input type="hidden" name="catatan_reject" class="reject-catatan">
                </form>
            @endcan
        </div>
    </div>
</x-modal>

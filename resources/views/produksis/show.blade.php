<x-app-layout>
    @section('title', 'Detail Produksi')
    @section('page-title', 'Detail Produksi Harian')

    <x-page-header
        title="Detail Produksi Harian"
        subtitle="Informasi lengkap data produksi beserta histori verifikasi."
    >
        <div class="flex items-center gap-2">
            @can('update', $produksi)
                <a href="{{ route('produksis.edit', $produksi->id) }}" class="btn-secondary flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                    Edit
                </a>
            @endcan
            <a href="{{ route('produksis.index') }}" class="btn-secondary flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>
    </x-page-header>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left: Main Info -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Info Produksi -->
            <x-card>
                <x-slot name="header">
                    <div class="flex items-center gap-3">
                        <h3 class="text-sm font-bold text-slate-800 tracking-tight">Informasi Produksi</h3>
                        <x-badge :type="$produksi->status->value" />
                    </div>
                </x-slot>

                <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Tanggal</p>
                        <p class="text-sm font-bold text-slate-800">{{ $produksi->tanggal_produksi->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Shift</p>
                        <p class="text-sm font-bold text-slate-800">{{ $produksi->shift->nama_shift }}</p>
                        <p class="text-xs text-slate-400 font-medium">{{ substr($produksi->shift->jam_masuk, 0, 5) }} - {{ substr($produksi->shift->jam_selesai, 0, 5) }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Mesin</p>
                        <p class="text-sm font-bold text-slate-800">{{ $produksi->mesin->kode_mesin }}</p>
                        <p class="text-xs text-slate-400 font-medium">{{ $produksi->mesin->nama_mesin }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Part</p>
                        <p class="text-sm font-bold text-slate-800">{{ $produksi->part->nomor_part }}</p>
                        <p class="text-xs text-slate-400 font-medium">{{ $produksi->part->nama_part }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Operator</p>
                        <p class="text-sm font-bold text-slate-800">{{ $produksi->operator->nama }}</p>
                        <p class="text-xs text-slate-400 font-medium">{{ $produksi->operator->role->label() }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Dibuat</p>
                        <p class="text-sm font-bold text-slate-800">{{ $produksi->created_at->format('d M Y') }}</p>
                        <p class="text-xs text-slate-400 font-medium">{{ $produksi->created_at->format('H:i') }} WIB</p>
                    </div>
                </div>

                @if($produksi->catatan)
                    <div class="mt-5 pt-5 border-t border-slate-100">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Catatan</p>
                        <p class="text-sm text-slate-700 font-medium">{{ $produksi->catatan }}</p>
                    </div>
                @endif
            </x-card>

            <!-- Statistik Produksi -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white rounded-2xl border border-slate-200/60 border-l-4 border-l-primary-500 p-4 shadow-sm">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Target</p>
                    <p class="text-xl font-extrabold text-slate-800 mt-1">{{ number_format($produksi->target_qty) }}</p>
                    <p class="text-[10px] text-slate-400 font-semibold">pcs</p>
                </div>
                <div class="bg-white rounded-2xl border border-slate-200/60 border-l-4 border-l-emerald-500 p-4 shadow-sm">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Good (OK)</p>
                    <p class="text-xl font-extrabold text-emerald-600 mt-1">{{ number_format($produksi->good_qty) }}</p>
                    <p class="text-[10px] text-slate-400 font-semibold">pcs</p>
                </div>
                <div class="bg-white rounded-2xl border border-slate-200/60 border-l-4 border-l-red-500 p-4 shadow-sm">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total NG</p>
                    <p class="text-xl font-extrabold text-red-500 mt-1">{{ number_format($produksi->total_ng_qty) }}</p>
                    <p class="text-[10px] text-slate-400 font-semibold">pcs</p>
                </div>
                <div class="bg-white rounded-2xl border border-slate-200/60 border-l-4 border-l-amber-500 p-4 shadow-sm">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Achievement</p>
                    <p class="text-xl font-extrabold text-amber-600 mt-1">{{ $produksi->achievement_rate }}%</p>
                    <p class="text-[10px] text-slate-400 font-semibold">dari target</p>
                </div>
            </div>

            <!-- Detail NG -->
            @if($produksi->detailNgProduksis->count() > 0)
                <x-card title="Detail Non-Good (NG)">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-slate-100">
                                    <th class="text-left py-2 px-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Kode NG</th>
                                    <th class="text-left py-2 px-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Jenis Cacat</th>
                                    <th class="text-left py-2 px-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Severity</th>
                                    <th class="text-right py-2 px-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Qty</th>
                                    <th class="text-left py-2 px-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Catatan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach($produksi->detailNgProduksis as $detail)
                                    <tr class="hover:bg-slate-50/50">
                                        <td class="py-3 px-3">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold bg-red-50 text-red-700 border border-red-200/50">
                                                {{ $detail->kategoriNg->kode_ng }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-3 font-semibold text-slate-700">{{ $detail->kategoriNg->nama_ng }}</td>
                                        <td class="py-3 px-3">
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
                                        <td class="py-3 px-3 text-right font-bold text-red-600">{{ number_format($detail->qty) }}</td>
                                        <td class="py-3 px-3 text-xs text-slate-500">{{ $detail->catatan ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr class="border-t border-slate-200 bg-slate-50/50">
                                    <td colspan="3" class="py-3 px-3 text-xs font-bold text-slate-600 uppercase tracking-wider">Total NG</td>
                                    <td class="py-3 px-3 text-right font-extrabold text-red-600">{{ number_format($produksi->total_ng_qty) }}</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </x-card>
            @else
                <x-card title="Detail Non-Good (NG)">
                    <p class="text-sm text-slate-400 font-semibold text-center py-4">Tidak ada produk NG pada produksi ini.</p>
                </x-card>
            @endif
        </div>

        <!-- Right: Actions + Verifikasi History -->
        <div class="space-y-6">

            <!-- Action Buttons -->
            <x-card title="Aksi">
                <div class="space-y-3">
                    @can('submit', $produksi)
                        <button type="button"
                            onclick="confirmAction('submit-form-{{ $produksi->id }}', 'Submit Produksi?', 'Data produksi akan disubmit ke Leader untuk diverifikasi.')"
                            class="w-full btn-primary flex items-center justify-center gap-2"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Submit untuk Verifikasi
                        </button>
                        <form id="submit-form-{{ $produksi->id }}" method="POST" action="{{ route('produksis.submit', $produksi->id) }}" class="hidden">
                            @csrf
                            @method('PATCH')
                        </form>
                    @endcan

                    @can('verify', $produksi)
                        <button type="button"
                            onclick="confirmVerify()"
                            class="w-full btn-success flex items-center justify-center gap-2"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Verifikasi Produksi
                        </button>

                        <form id="verify-form" method="POST" action="{{ route('produksis.verify', $produksi->id) }}" class="hidden">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="catatan" id="verify-catatan">
                        </form>
                    @endcan

                    @can('reject', $produksi)
                        <button type="button"
                            onclick="confirmReject()"
                            class="w-full btn-danger flex items-center justify-center gap-2"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Reject / Minta Revisi
                        </button>

                        <form id="reject-form" method="POST" action="{{ route('produksis.reject', $produksi->id) }}" class="hidden">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="catatan_reject" id="reject-catatan">
                        </form>
                    @endcan

                    @can('delete', $produksi)
                        <x-confirm-delete
                            :action="route('produksis.destroy', $produksi->id)"
                            title="Hapus Data Produksi?"
                            text="Data produksi ini akan dihapus permanen beserta seluruh detail NG."
                            class="w-full btn-danger flex items-center justify-center gap-2"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Hapus Data
                        </x-confirm-delete>
                    @endcan
                </div>
            </x-card>

            <!-- Histori Verifikasi -->
            <x-card title="Histori Verifikasi">
                @if($produksi->verifikasiProduksis->count() > 0)
                    <div class="space-y-4">
                        @foreach($produksi->verifikasiProduksis->sortByDesc('verified_at') as $verifikasi)
                            <div class="flex gap-3">
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
                                        <p class="text-xs text-slate-600 mt-1.5 bg-slate-50 rounded-lg p-2 border border-slate-100">{{ $verifikasi->catatan }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-slate-400 font-semibold text-center py-4">Belum ada histori verifikasi.</p>
                @endif
            </x-card>
        </div>
    </div>

    @push('scripts')
    <script>
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

        function confirmVerify() {
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
                    document.getElementById('verify-catatan').value = result.value || '';
                    document.getElementById('verify-form').submit();
                }
            });
        }

        function confirmReject() {
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
                    document.getElementById('reject-catatan').value = result.value;
                    document.getElementById('reject-form').submit();
                }
            });
        }
    </script>
    @endpush
</x-app-layout>

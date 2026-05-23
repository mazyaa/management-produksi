<x-app-layout>
    @section('title', 'Detail User')
    @section('page-title', 'Detail Pengguna')

    <x-page-header title="Detail Pengguna" subtitle="Informasi lengkap akun pengguna dan aktivitas produksi.">
        <div class="flex items-center gap-2">
            <a href="{{ route('users.edit', $user->id) }}" class="btn-secondary flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                </svg>
                Edit
            </a>
            <a href="{{ route('users.index') }}" class="btn-secondary flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>
    </x-page-header>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile Card -->
        <div class="lg:col-span-1">
            <x-card>
                <div class="text-center">
                    <!-- Avatar -->
                    <div class="w-20 h-20 rounded-2xl bg-gradient-to-tr from-primary-100 to-indigo-50 text-primary-700 font-extrabold border border-primary-200/50 flex items-center justify-center text-2xl shadow-sm mx-auto mb-4">
                        {{ strtoupper(substr($user->nama, 0, 2)) }}
                    </div>

                    <h2 class="text-lg font-extrabold text-slate-800">{{ $user->nama }}</h2>
                    <p class="text-sm text-slate-500 font-medium">@{{ $user->username }}</p>

                    <div class="mt-3 flex items-center justify-center gap-2">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-extrabold uppercase tracking-wider {{ $user->isAdmin() ? 'bg-indigo-50 text-indigo-700 border border-indigo-200/50' : ($user->isLeader() ? 'bg-amber-50 text-amber-700 border border-amber-200/50' : ($user->isOperator() ? 'bg-slate-50 text-slate-700 border border-slate-200/50' : 'bg-emerald-50 text-emerald-700 border border-emerald-200/50')) }}">
                            {{ $user->role->label() }}
                        </span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold border {{ $user->is_active ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-red-50 text-red-700 border-red-200' }}">
                            <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $user->is_active ? 'bg-emerald-500' : 'bg-red-500' }}"></span>
                            {{ $user->is_active ? 'Aktif' : 'Non-Aktif' }}
                        </span>
                    </div>
                </div>

                <div class="mt-6 space-y-3 border-t border-slate-100 pt-5">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Email</p>
                        <p class="text-sm font-semibold text-slate-700 mt-0.5">{{ $user->email }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Bergabung</p>
                        <p class="text-sm font-semibold text-slate-700 mt-0.5">{{ $user->created_at->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Total Produksi</p>
                        <p class="text-sm font-semibold text-slate-700 mt-0.5">{{ $user->produksis()->count() }} records</p>
                    </div>
                </div>
            </x-card>
        </div>

        <!-- Recent Activity -->
        <div class="lg:col-span-2">
            <x-card title="Aktivitas Produksi Terakhir">
                @php $recentProduksis = $user->produksis()->with(['shift', 'mesin', 'part'])->latest()->limit(10)->get(); @endphp
                @if($recentProduksis->count() > 0)
                    <div class="divide-y divide-slate-100">
                        @foreach($recentProduksis as $produksi)
                            <div class="py-3.5 flex items-center justify-between">
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-bold text-slate-800">{{ $produksi->tanggal_produksi->format('d M Y') }}</span>
                                        <span class="text-xs text-slate-400">•</span>
                                        <span class="text-xs font-semibold text-slate-600">{{ $produksi->shift->nama_shift }}</span>
                                        <span class="text-xs text-slate-400">•</span>
                                        <span class="text-xs font-bold text-primary-600">{{ $produksi->mesin->kode_mesin }}</span>
                                    </div>
                                    <p class="text-[11px] text-slate-400 font-medium">{{ $produksi->part->nomor_part }} — {{ $produksi->part->nama_part }}</p>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="text-right">
                                        <span class="block text-xs font-bold text-emerald-600">{{ number_format($produksi->good_qty) }} OK</span>
                                        @if($produksi->total_ng_qty > 0)
                                            <span class="block text-[10px] font-bold text-red-500">{{ number_format($produksi->total_ng_qty) }} NG</span>
                                        @endif
                                    </div>
                                    <x-badge :type="$produksi->status->value" />
                                    <a href="{{ route('produksis.show', $produksi->id) }}" class="text-slate-400 hover:text-primary-600 transition-colors p-1.5 rounded-lg border border-transparent hover:border-slate-200 hover:bg-white">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <x-empty-state
                        title="Belum Ada Aktivitas"
                        message="Pengguna ini belum memiliki data produksi."
                    />
                @endif
            </x-card>
        </div>
    </div>
</x-app-layout>

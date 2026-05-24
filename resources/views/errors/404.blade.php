<x-guest-layout>
    <div class="bg-white/85 backdrop-blur-lg rounded-3xl border border-white/20 shadow-2xl p-8 sm:p-10 text-center">
        <div class="w-20 h-20 rounded-3xl bg-primary-500/10 text-primary-600 flex items-center justify-center mx-auto mb-5">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M12 6.5a6.5 6.5 0 016.5 6.5v1.5a2 2 0 01-2 2H7.5a2 2 0 01-2-2V13a6.5 6.5 0 016.5-6.5z" />
            </svg>
        </div>
        <div class="text-5xl font-extrabold text-slate-800 tracking-tight">404</div>
        <div class="mt-2 text-lg font-bold text-slate-700">Halaman Tidak Ditemukan</div>
        <p class="mt-2 text-sm text-slate-500">Halaman yang Anda cari mungkin sudah dipindahkan atau tidak tersedia.</p>

        <div class="mt-6 flex items-center justify-center gap-3">
            <a href="{{ route('dashboard') }}" class="btn-primary">Kembali ke Dashboard</a>
            <a href="{{ url('/') }}" class="btn-secondary">Ke Halaman Utama</a>
        </div>
    </div>
</x-guest-layout>

<x-guest-layout>
    <div class="bg-white/85 backdrop-blur-lg rounded-3xl border border-white/20 shadow-2xl p-8 sm:p-10 text-center">
        <div class="w-20 h-20 rounded-3xl bg-red-500/10 text-red-600 flex items-center justify-center mx-auto mb-5">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 9v2m0 4h.01M5.07 19h13.86c1.38 0 2.25-1.5 1.53-2.67L13.53 4.67c-.69-1.18-2.37-1.18-3.06 0L3.54 16.33c-.72 1.17.15 2.67 1.53 2.67z" />
            </svg>
        </div>
        <div class="text-5xl font-extrabold text-slate-800 tracking-tight">500</div>
        <div class="mt-2 text-lg font-bold text-slate-700">Terjadi Kesalahan</div>
        <p class="mt-2 text-sm text-slate-500">Server mengalami kendala. Silakan coba lagi beberapa saat lagi.</p>

        <div class="mt-6 flex items-center justify-center gap-3">
            <a href="{{ route('dashboard') }}" class="btn-primary">Kembali ke Dashboard</a>
            <a href="{{ url()->previous() }}" class="btn-secondary">Coba Lagi</a>
        </div>
    </div>
</x-guest-layout>

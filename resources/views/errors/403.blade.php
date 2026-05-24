<x-guest-layout>
    <div class="bg-white/85 backdrop-blur-lg rounded-3xl border border-white/20 shadow-2xl p-8 sm:p-10 text-center">
        <div class="w-20 h-20 rounded-3xl bg-secondary-500/10 text-secondary-600 flex items-center justify-center mx-auto mb-5">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>
        <div class="text-5xl font-extrabold text-slate-800 tracking-tight">403</div>
        <div class="mt-2 text-lg font-bold text-slate-700">Akses Ditolak</div>
        <p class="mt-2 text-sm text-slate-500">Anda tidak memiliki izin untuk mengakses halaman ini.</p>

        <div class="mt-6 flex items-center justify-center gap-3">
            <a href="{{ route('dashboard') }}" class="btn-primary">Kembali ke Dashboard</a>
            <a href="{{ url()->previous() }}" class="btn-secondary">Kembali</a>
        </div>
    </div>
</x-guest-layout>

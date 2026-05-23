@props([
    'title',
    'message',
    'icon' => null
])

<div class="flex flex-col items-center justify-center p-8 md:p-12 text-center bg-white border border-dashed border-slate-350 rounded-2xl shadow-sm">
    <div class="w-16 h-16 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-400 mb-4 shadow-inner">
        @if($icon)
            {{ $icon }}
        @else
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0V9a2 2 0 00-2-2M5 13V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
        @endif
    </div>

    <h3 class="text-sm font-bold text-slate-800 tracking-tight mb-1">{{ $title }}</h3>
    <p class="text-xs text-slate-550 max-w-sm mb-6 font-medium leading-relaxed">{{ $message }}</p>

    @if($slot->isNotEmpty())
        <div>
            {{ $slot }}
        </div>
    @endif
</div>

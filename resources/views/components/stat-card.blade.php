@props([
    'title',
    'value',
    'icon',
    'color' => 'primary',
    'subtitle' => null,
    'trend' => null,
    'trendUp' => true
])

@php
    $colorClasses = match ($color) {
        'success' => [
            'border' => 'border-l-emerald-500',
            'bgIcon' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
        ],
        'danger' => [
            'border' => 'border-l-red-500',
            'bgIcon' => 'bg-red-50 text-red-600 border-red-100',
        ],
        'warning' => [
            'border' => 'border-l-amber-500',
            'bgIcon' => 'bg-amber-50 text-amber-600 border-amber-100',
        ],
        default => [
            'border' => 'border-l-primary-500',
            'bgIcon' => 'bg-primary-50 text-primary-600 border-primary-100',
        ],
    };
@endphp

<div class="bg-white rounded-2xl shadow-sm border border-slate-200/60 border-l-4 {{ $colorClasses['border'] }} p-6 flex items-center justify-between transition-all duration-200 hover:shadow-md">
    <div class="space-y-1.5">
        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest block">{{ $title }}</span>
        <h3 class="text-2xl font-extrabold text-slate-800 tracking-tight leading-none">{{ $value }}</h3>
        
        @if($trend || $subtitle)
            <div class="flex items-center gap-1.5 mt-2">
                @if($trend)
                    <span class="inline-flex items-center text-xs font-bold {{ $trendUp ? 'text-emerald-600' : 'text-red-600' }}">
                        @if($trendUp)
                            <svg class="w-3.5 h-3.5 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                            </svg>
                        @else
                            <svg class="w-3.5 h-3.5 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                            </svg>
                        @endif
                        {{ $trend }}
                    </span>
                @endif
                
                @if($subtitle)
                    <span class="text-xs text-slate-500 font-semibold">{{ $subtitle }}</span>
                @endif
            </div>
        @endif
    </div>

    <div class="w-12 h-12 rounded-2xl border flex items-center justify-center {{ $colorClasses['bgIcon'] }} shadow-sm">
        {{ $icon }}
    </div>
</div>

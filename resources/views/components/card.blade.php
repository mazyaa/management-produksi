@props([
    'title' => null,
    'padding' => true
])

<div {{ $attributes->merge(['class' => 'bg-white rounded-2xl shadow-sm border border-slate-200/60 overflow-hidden']) }}>
    @if($title || isset($header))
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/20">
            @if(isset($header))
                {{ $header }}
            @else
                <h3 class="text-sm font-bold text-slate-800 tracking-tight">{{ $title }}</h3>
            @endif
        </div>
    @endif

    <div class="{{ $padding ? 'p-6' : '' }}">
        {{ $slot }}
    </div>
</div>

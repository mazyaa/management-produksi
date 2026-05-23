@props([
    'title',
    'subtitle' => null
])

<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
    <div class="space-y-1">
        <h1 class="text-xl md:text-2xl font-extrabold text-slate-800 tracking-tight leading-none">
            {{ $title }}
        </h1>
        
        @if($subtitle)
            <p class="text-xs md:text-sm text-slate-500 font-medium">
                {{ $subtitle }}
            </p>
        @endif
    </div>

    @if($slot->isNotEmpty())
        <div class="flex items-center gap-3 shrink-0">
            {{ $slot }}
        </div>
    @endif
</div>

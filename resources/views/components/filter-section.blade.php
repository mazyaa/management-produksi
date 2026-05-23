<div x-data="{ expanded: true }" class="bg-white rounded-2xl border border-slate-200/60 overflow-hidden shadow-sm mb-6 transition-all duration-200">
    <!-- Header -->
    <div 
        @click="expanded = !expanded" 
        class="px-6 py-4 border-b border-slate-100 flex items-center justify-between cursor-pointer hover:bg-slate-50/30 transition-colors"
    >
        <div class="flex items-center gap-2 text-slate-700">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
            </svg>
            <h3 class="text-xs font-bold uppercase tracking-wider">Panel Filter</h3>
        </div>
        
        <button class="text-slate-400 hover:text-slate-600 transition-colors">
            <svg class="w-5 h-5 transform transition-transform duration-200" :class="{ 'rotate-180': expanded }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </button>
    </div>

    <!-- Panel Content -->
    <div 
        x-show="expanded" 
        x-collapse
        x-transition:enter="transition ease-out duration-250"
        x-transition:enter-start="opacity-0 max-h-0"
        x-transition:enter-end="opacity-100 max-h-[1000px]"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 max-h-[1000px]"
        x-transition:leave-end="opacity-0 max-h-0"
        class="px-6 py-5 border-t border-slate-50"
    >
        {{ $slot }}
    </div>
</div>

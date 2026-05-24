@props([
    'type' => 'draft',
    'text' => null
])

@php
    $classes = match (strtolower($type)) {
        'draft' => 'bg-slate-100 text-slate-700 border-slate-200',
        'submitted' => 'bg-primary-50 text-primary-700 border-primary-200/50',
        'verified' => 'bg-emerald-50 text-emerald-700 border-emerald-200/50',
        'rejected' => 'bg-red-50 text-red-700 border-red-200/50',
        'revised' => 'bg-secondary-50 text-secondary-700 border-secondary-200/50',
        default => 'bg-slate-50 text-slate-700 border-slate-200/50'
    };

    $labelText = $text ?? match (strtolower($type)) {
        'draft' => 'Draft',
        'submitted' => 'Submitted',
        'verified' => 'Verified',
        'rejected' => 'Rejected',
        'revised' => 'Revised',
        default => ucfirst($type)
    };
@endphp

<span class="inline-flex p-1.5 items-center rounded-full text-xs font-bold border {{ $classes }} tracking-wide">
    <span class="rounded-full {{ str_replace('text-', 'bg-', explode(' ', $classes)[1] ?? 'bg-slate-500') }}"></span>
    {{ $labelText }}
</span>

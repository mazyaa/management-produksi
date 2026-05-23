@props([
    'label',
    'name',
    'required' => false,
    'disabled' => false,
    'placeholder' => 'Pilih...'
])

<div>
    <label for="{{ $name }}" class="form-label font-bold text-slate-700">
        {{ $label }}
        @if($required)
            <span class="text-red-500">*</span>
        @endif
    </label>
    
    <select 
        id="{{ $name }}" 
        name="{{ $name }}" 
        {{ $required ? 'required' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        {{ $attributes->merge(['class' => 'form-input-custom border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 ' . ($errors->has($name) ? 'border-red-350 focus:border-red-500 focus:ring-red-500/20' : '')]) }}
    >
        @if($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif
        {{ $slot }}
    </select>
    
    @error($name)
        <span class="text-xs text-red-650 font-bold block mt-1.5 leading-none">
            {{ $message }}
        </span>
    @enderror
</div>

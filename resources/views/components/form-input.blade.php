@props([
    'label',
    'name',
    'type' => 'text',
    'value' => '',
    'placeholder' => '',
    'required' => false,
    'disabled' => false
])

<div>
    <label for="{{ $name }}" class="form-label font-bold text-slate-700">
        {{ $label }}
        @if($required)
            <span class="text-red-500">*</span>
        @endif
    </label>
    
    <input 
        id="{{ $name }}" 
        type="{{ $type }}" 
        name="{{ $name }}" 
        value="{{ old($name, $value) }}" 
        placeholder="{{ $placeholder }}"
        {{ $required ? 'required' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        {{ $attributes->merge(['class' => 'form-input-custom border-slate-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 ' . ($errors->has($name) ? 'border-red-350 focus:border-red-500 focus:ring-red-500/20' : '')]) }}
    />
    
    @error($name)
        <span class="text-xs text-red-650 font-bold block mt-1.5 leading-none">
            {{ $message }}
        </span>
    @enderror
</div>

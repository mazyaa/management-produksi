@props([
    'action',
    'id' => null,
    'title' => 'Apakah Anda yakin?',
    'text' => 'Data yang dihapus tidak dapat dikembalikan.'
])

@php
    $formId = 'delete-form-' . ($id ?? Str::random(8));
@endphp

<form id="{{ $formId }}" action="{{ $action }}" method="POST" class="inline">
    @csrf
    @method('DELETE')
    
    <button 
        type="button" 
        {{ $attributes->merge(['class' => '']) }}
        onclick="confirmDelete('{{ $formId }}', '{{ $title }}', '{{ $text }}')"
    >
        {{ $slot }}
    </button>
</form>

@once
    @push('scripts')
        <script>
            function confirmDelete(formId, title, text) {
                Swal.fire({
                    title: title,
                    text: text,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    customClass: {
                        popup: 'rounded-2xl shadow-xl border border-slate-100',
                        confirmButton: 'rounded-xl px-5 py-2.5 font-semibold text-sm shadow-sm',
                        cancelButton: 'rounded-xl px-5 py-2.5 font-semibold text-sm shadow-sm'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById(formId).submit();
                    }
                });
            }
        </script>
    @endpush
@endonce

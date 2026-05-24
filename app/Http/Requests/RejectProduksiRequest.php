<?php

namespace App\Http\Requests;

use App\Enums\Role;
use Illuminate\Foundation\Http\FormRequest;

class RejectProduksiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->hasRole(Role::ADMIN->value, Role::LEADER->value);
    }

    public function rules(): array
    {
        return [
            'catatan_reject' => ['required', 'string', 'min:5'],
        ];
    }

    public function messages(): array
    {
        return [
            'catatan_reject.required' => 'Catatan revisi/rejection wajib diisi.',
            'catatan_reject.min' => 'Catatan revisi minimal 5 karakter.',
        ];
    }
}

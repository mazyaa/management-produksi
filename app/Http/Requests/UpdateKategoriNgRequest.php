<?php

namespace App\Http\Requests;

use App\Enums\Role;
use App\Enums\Severity;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateKategoriNgRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->role === Role::ADMIN;
    }

    public function rules(): array
    {
        $ngId = $this->route('kategori_ng')->id;

        return [
            'kode_ng' => ['required', 'string', 'max:255', Rule::unique('kategori_ngs', 'kode_ng')->ignore($ngId)],
            'nama_ng' => ['required', 'string', 'max:255'],
            'severity' => ['required', 'string', Rule::in(array_column(Severity::cases(), 'value'))],
        ];
    }

    public function messages(): array
    {
        return [
            'kode_ng.required' => 'Kode NG wajib diisi.',
            'kode_ng.unique' => 'Kode NG sudah terdaftar.',
            'nama_ng.required' => 'Nama NG wajib diisi.',
            'severity.required' => 'Severity wajib diisi.',
            'severity.in' => 'Severity tidak valid.',
        ];
    }
}

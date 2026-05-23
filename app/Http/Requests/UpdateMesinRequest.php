<?php

namespace App\Http\Requests;

use App\Enums\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMesinRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->role === Role::ADMIN;
    }

    public function rules(): array
    {
        $mesinId = $this->route('mesin')->id;

        return [
            'kode_mesin' => ['required', 'string', 'max:255', Rule::unique('mesins', 'kode_mesin')->ignore($mesinId)],
            'nama_mesin' => ['required', 'string', 'max:255'],
            'line' => ['required', 'string', 'max:255'],
            'is_active' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'kode_mesin.required' => 'Kode mesin wajib diisi.',
            'kode_mesin.unique' => 'Kode mesin sudah terdaftar.',
            'nama_mesin.required' => 'Nama mesin wajib diisi.',
            'line.required' => 'Line wajib diisi.',
        ];
    }
}

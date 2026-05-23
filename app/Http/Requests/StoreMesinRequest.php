<?php

namespace App\Http\Requests;

use App\Enums\Role;
use Illuminate\Foundation\Http\FormRequest;

class StoreMesinRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->role === Role::ADMIN;
    }

    public function rules(): array
    {
        return [
            'kode_mesin' => ['required', 'string', 'max:255', 'unique:mesins,kode_mesin'],
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

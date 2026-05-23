<?php

namespace App\Http\Requests;

use App\Enums\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->role === Role::ADMIN;
    }

    public function rules(): array
    {
        $partId = $this->route('part')->id;

        return [
            'nomor_part' => ['required', 'string', 'max:255', Rule::unique('parts', 'nomor_part')->ignore($partId)],
            'nama_part' => ['required', 'string', 'max:255'],
            'kategori' => ['required', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'nomor_part.required' => 'Nomor part wajib diisi.',
            'nomor_part.unique' => 'Nomor part sudah terdaftar.',
            'nama_part.required' => 'Nama part wajib diisi.',
            'kategori.required' => 'Kategori part wajib diisi.',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProduksiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Authorized via Policy in controller
    }

    public function rules(): array
    {
        return [
            'tanggal_produksi' => ['required', 'date'],
            'shift_id' => ['required', 'exists:shifts,id'],
            'mesin_id' => ['required', 'exists:mesins,id'],
            'part_id' => ['required', 'exists:parts,id'],
            'target_qty' => ['required', 'integer', 'min:1'],
            'good_qty' => ['required', 'integer', 'min:0'],
            'catatan' => ['nullable', 'string'],
            
            // Dynamic NG details validation
            'ng' => ['nullable', 'array'],
            'ng.*.kategori_ng_id' => ['required', 'exists:kategori_ngs,id'],
            'ng.*.qty' => ['required', 'integer', 'min:1'],
            'ng.*.catatan' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $goodQty = (int) $this->input('good_qty');
            $targetQty = (int) $this->input('target_qty');
            $totalNg = 0;

            if ($this->has('ng') && is_array($this->input('ng'))) {
                foreach ($this->input('ng') as $ngItem) {
                    $totalNg += (int) ($ngItem['qty'] ?? 0);
                }
            }

            if (($goodQty + $totalNg) > $targetQty) {
                $validator->errors()->add('good_qty', 'Total good (' . $goodQty . ') + NG (' . $totalNg . ') tidak boleh melebihi target (' . $targetQty . ').');
            }
        });
    }

    public function messages(): array
    {
        return [
            'tanggal_produksi.required' => 'Tanggal produksi wajib diisi.',
            'tanggal_produksi.date' => 'Tanggal produksi harus berupa tanggal yang valid.',
            'shift_id.required' => 'Shift wajib dipilih.',
            'shift_id.exists' => 'Shift tidak valid.',
            'mesin_id.required' => 'Mesin wajib dipilih.',
            'mesin_id.exists' => 'Mesin tidak valid.',
            'part_id.required' => 'Part nomor wajib dipilih.',
            'part_id.exists' => 'Part nomor tidak valid.',
            'target_qty.required' => 'Target quantity wajib diisi.',
            'target_qty.integer' => 'Target quantity harus berupa angka.',
            'target_qty.min' => 'Target quantity minimal 1.',
            'good_qty.required' => 'Good quantity wajib diisi.',
            'good_qty.integer' => 'Good quantity harus berupa angka.',
            'good_qty.min' => 'Good quantity minimal 0.',
            'ng.*.kategori_ng_id.required' => 'Kategori NG wajib dipilih.',
            'ng.*.kategori_ng_id.exists' => 'Kategori NG tidak valid.',
            'ng.*.qty.required' => 'Quantity NG wajib diisi.',
            'ng.*.qty.integer' => 'Quantity NG harus berupa angka.',
            'ng.*.qty.min' => 'Quantity NG minimal 1.',
        ];
    }
}

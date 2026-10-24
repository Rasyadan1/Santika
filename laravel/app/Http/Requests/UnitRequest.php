<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UnitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        $unitId = $this->route('unit') ? $this->route('unit')->id : null;

        return [
            'kode_unit' => [
                'required',
                'string',
                'max:50',
                Rule::unique('units', 'kode_unit')->ignore($unitId),
            ],
            'nama_unit' => ['required', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string', 'max:1000'],
            'tarif_per_hari' => ['required', 'integer', 'min:0'],
            'categories' => ['required', 'array', 'min:1'],
            'categories.*' => ['integer', 'distinct', 'exists:categories,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'kode_unit.required' => 'Kode unit wajib diisi.',
            'kode_unit.unique' => 'Kode unit sudah digunakan.',
            'nama_unit.required' => 'Nama unit wajib diisi.',
            'tarif_per_hari.required' => 'Tarif per hari wajib diisi.',
            'tarif_per_hari.min' => 'Tarif per hari tidak boleh negatif.',
            'categories.required' => 'Minimal satu kategori harus dipilih.',
            'categories.min' => 'Minimal satu kategori harus dipilih.',
            'categories.*.exists' => 'Kategori yang dipilih tidak valid.',
        ];
    }
}

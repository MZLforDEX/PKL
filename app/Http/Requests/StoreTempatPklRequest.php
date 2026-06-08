<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTempatPklRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_tempat' => ['required', 'string', 'max:255', Rule::unique('tempat_pkl', 'nama_tempat')],
            'alamat' => 'required|string',
            'bidang_usaha' => 'required|string|max:100',
            'kontak_person' => 'nullable|string|max:100',
            'no_hp' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'kuota' => 'required|integer|min:1',
            'keterangan' => 'nullable|string',
        ];
    }
}

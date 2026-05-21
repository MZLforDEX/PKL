<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePenilaianPklRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nilai_sikap' => 'required|integer|min:0|max:100',
            'nilai_keterampilan' => 'required|integer|min:0|max:100',
            'nilai_laporan' => 'required|integer|min:0|max:100',
            'catatan_evaluasi' => 'nullable|string',
        ];
    }
}

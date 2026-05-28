<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePembimbingIndustriRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $pembimbing = $this->route('pembimbing_industri');
        if (!$pembimbing) {
            return [];
        }
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $pembimbing->user_id,
            'password' => 'nullable|string|min:8',
            'tempat_pkl_id' => 'required|exists:tempat_pkl,id',
            'no_hp' => 'nullable|string|max:20',
            'jabatan' => 'nullable|string|max:100',
        ];
    }
}

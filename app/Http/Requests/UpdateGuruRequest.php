<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGuruRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $guru = $this->route('guru');
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $guru->user_id,
            'password' => 'nullable|string|min:8',
            'nip' => 'required|string|unique:guru,nip,' . $guru->id,
            'alamat' => 'nullable|string',
            'no_hp' => 'nullable|string|max:20',
        ];
    }
}

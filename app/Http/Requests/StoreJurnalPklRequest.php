<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Validation\Rule;

class StoreJurnalPklRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $siswa = auth()->user()->siswa;
        $pengajuan = $siswa ? \App\Models\PengajuanPkl::where('siswa_id', $siswa->id)
            ->whereIn('status', ['disetujui', 'sedang_pkl'])
            ->first() : null;
        $pengajuanId = $pengajuan ? $pengajuan->id : null;

        $tanggalRules = [
            'required',
            'date',
            'before_or_equal:today',
        ];

        if ($pengajuan) {
            $tanggalRules[] = 'after_or_equal:' . $pengajuan->tanggal_mulai;
            $tanggalRules[] = 'before_or_equal:' . $pengajuan->tanggal_selesai;
            $tanggalRules[] = Rule::unique('jurnal_pkl', 'tanggal')->where(function ($query) use ($pengajuanId) {
                return $query->where('pengajuan_pkl_id', $pengajuanId);
            });
        }

        return [
            'tanggal' => $tanggalRules,
            'kegiatan' => 'required|string',
            'kendala' => 'nullable|string',
            'dokumentasi' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'tanggal.unique' => 'Anda sudah membuat jurnal untuk tanggal ini.',
        ];
    }
}

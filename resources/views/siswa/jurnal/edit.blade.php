<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('siswa.jurnal.index') }}" class="p-2 rounded-lg hover:bg-surface-100 text-surface-400 hover:text-surface-600 transition-colors">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <h2 class="font-extrabold text-lg md:text-2xl text-surface-900 tracking-tight">Edit Jurnal PKL</h2>
        </div>
    </x-slot>

    <div class="py-6 md:py-10 animate-fade-in">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="card-premium p-6 sm:p-8">
                @if($jurnalPkl->status === 'revisi' && ($jurnalPkl->catatan_guru || $jurnalPkl->catatan_pembimbing))
                    <div class="mb-6 p-4 bg-rose-50 border border-rose-100 rounded-2xl">
                        <h4 class="font-bold text-rose-700 text-sm flex items-center gap-1.5 mb-2">
                            <i data-lucide="info" class="w-4 h-4"></i>
                            Catatan Revisi
                        </h4>
                        @if($jurnalPkl->catatan_guru)
                            <p class="text-xs text-rose-600 mb-1"><span class="font-bold">Guru Pembimbing:</span> {{ $jurnalPkl->catatan_guru }}</p>
                        @endif
                        @if($jurnalPkl->catatan_pembimbing)
                            <p class="text-xs text-rose-600"><span class="font-bold">Pembimbing Industri:</span> {{ $jurnalPkl->catatan_pembimbing }}</p>
                        @endif
                    </div>
                @endif
                <form action="{{ route('siswa.jurnal.update', $jurnalPkl) }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label class="form-label">Tanggal</label>
                            <input type="date" name="tanggal" value="{{ old('tanggal', $jurnalPkl->tanggal) }}" class="form-input-premium mt-1.5" required>
                            @error('tanggal') <p class="text-rose-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="form-label">Kegiatan</label>
                            <textarea name="kegiatan" rows="4" class="form-input-premium mt-1.5" required>{{ old('kegiatan', $jurnalPkl->kegiatan) }}</textarea>
                            @error('kegiatan') <p class="text-rose-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="form-label">Kendala <span class="text-surface-400 font-normal">(opsional)</span></label>
                            <textarea name="kendala" rows="2" class="form-input-premium mt-1.5">{{ old('kendala', $jurnalPkl->kendala) }}</textarea>
                            @error('kendala') <p class="text-rose-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="form-label">Dokumentasi Visual <span class="text-surface-400 font-normal">(biarkan kosong jika tidak diubah)</span></label>
                            <input type="file" name="dokumentasi" class="mt-2 block w-full text-sm text-surface-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-brand-50 file:text-brand-600 hover:file:bg-brand-100">
                            @error('dokumentasi') <p class="text-rose-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div class="mt-8 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                        <a href="{{ route('siswa.jurnal.index') }}" class="btn-secondary w-full sm:w-auto text-center">Batal</a>
                        <button type="submit" class="btn-primary w-full sm:w-auto flex items-center justify-center gap-1.5">
                            <i data-lucide="save" class="w-4 h-4 shrink-0"></i>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

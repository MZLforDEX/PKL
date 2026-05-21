<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('siswa.pengajuan.index') }}"
                class="p-2 rounded-lg hover:bg-surface-100 text-surface-400 hover:text-surface-600 transition-colors">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <h2 class="font-extrabold text-lg md:text-2xl text-surface-900 tracking-tight">Buat Pengajuan PKL</h2>
        </div>
    </x-slot>

    <div class="py-6 md:py-10 animate-fade-in">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="card-premium p-6 sm:p-8">
                <form action="{{ route('siswa.pengajuan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label class="form-label">Tempat PKL</label>
                            <select name="tempat_pkl_id" class="form-input-premium mt-1.5 !py-2.5 text-sm" required>
                                <option value="">Pilih Tempat PKL</option>
                                @foreach($tempatPkl as $t)
                                    <option value="{{ $t->id }}" @selected(old('tempat_pkl_id') == $t->id)>
                                        {{ $t->nama_tempat }} ({{ $t->bidang_usaha }})</option>
                                @endforeach
                            </select>
                            @error('tempat_pkl_id') <p class="text-rose-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label class="form-label">Tanggal Mulai</label>
                                <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}"
                                    class="form-input-premium mt-1.5" required>
                                @error('tanggal_mulai') <p class="text-rose-500 text-xs mt-1.5">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="form-label">Tanggal Selesai</label>
                                <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}"
                                    class="form-input-premium mt-1.5" required>
                                @error('tanggal_selesai') <p class="text-rose-500 text-xs mt-1.5">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div>
                            <label class="form-label">Alasan Pemilihan</label>
                            <textarea name="alasan" rows="3" class="form-input-premium mt-1.5"
                                required>{{ old('alasan') }}</textarea>
                            @error('alasan') <p class="text-rose-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="form-label">File Dokumen Pendukung (PDF/DOC/DOCX, max 2MB)</label>
                            <input type="file" name="file_dokumen"
                                class="mt-2 block w-full text-sm text-surface-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-brand-50 file:text-brand-600 hover:file:bg-brand-100">
                            @error('file_dokumen') <p class="text-rose-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div class="mt-8 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                        <a href="{{ route('siswa.pengajuan.index') }}"
                            class="btn-secondary w-full sm:w-auto text-center">Batal</a>
                        <button type="submit"
                            class="btn-primary w-full sm:w-auto flex items-center justify-center gap-1.5">
                            <i data-lucide="send" class="w-4 h-4 shrink-0"></i>
                            Kirim Usulan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('siswa.laporan.index') }}" class="p-2 rounded-lg hover:bg-surface-100 text-surface-400 hover:text-surface-600 transition-colors">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <h2 class="font-extrabold text-lg md:text-2xl text-surface-900 tracking-tight">Perbaiki Laporan PKL</h2>
        </div>
    </x-slot>

    <div class="py-6 md:py-10 animate-fade-in">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="card-premium p-6 sm:p-8">
                @if($laporanPkl->catatan_guru)
                <div class="mb-6 p-4 bg-rose-50 rounded-2xl border border-rose-100 flex items-start gap-3">
                    <i data-lucide="alert-circle" class="w-5 h-5 text-rose-500 shrink-0 mt-0.5"></i>
                    <div>
                        <p class="text-sm font-bold text-rose-800">Catatan revisi dari guru</p>
                        <p class="text-sm text-rose-700 mt-1">{{ $laporanPkl->catatan_guru }}</p>
                    </div>
                </div>
                @endif

                <div class="mb-6 p-4 bg-surface-50 rounded-2xl border border-surface-200/60 flex items-center gap-3">
                    <i data-lucide="info" class="w-5 h-5 text-brand-500 shrink-0"></i>
                    <p class="text-sm text-surface-700">Unggah ulang file PDF laporan yang sudah diperbaiki untuk pengajuan di <strong>{{ $laporanPkl->pengajuanPkl?->tempatPkl?->nama_tempat ?? '-' }}</strong>.</p>
                </div>

                <form action="{{ route('siswa.laporan.update', $laporanPkl) }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div>
                        <label class="form-label">File Laporan Baru <span class="text-surface-400 font-normal">(PDF, max 5MB)</span></label>
                        <input type="file" name="file_laporan" accept=".pdf" class="mt-2 block w-full text-sm text-surface-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-brand-50 file:text-brand-600 hover:file:bg-brand-100" required>
                        @error('file_laporan') <p class="text-rose-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                    </div>
                    <div class="mt-8 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                        <a href="{{ route('siswa.laporan.index') }}" class="btn-secondary w-full sm:w-auto text-center">Batal</a>
                        <button type="submit" class="btn-primary w-full sm:w-auto flex items-center justify-center gap-1.5">
                            <i data-lucide="upload" class="w-4 h-4 shrink-0"></i>
                            Kirim Ulang Laporan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

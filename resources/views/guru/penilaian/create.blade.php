<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('guru.penilaian.index') }}" class="p-2 rounded-lg hover:bg-surface-100 text-surface-400 hover:text-surface-600 transition-colors">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <h2 class="font-extrabold text-lg md:text-2xl text-surface-900 tracking-tight">Beri Penilaian PKL</h2>
        </div>
    </x-slot>

    <div class="py-6 md:py-10 animate-fade-in">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="card-premium p-6 sm:p-8">
                <div class="mb-8 p-4.5 bg-surface-50 rounded-2xl border border-surface-200/60">
                    <div class="flex items-center gap-3.5">
                        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-brand-500 to-brand-600 flex items-center justify-center text-white font-bold text-base shadow-sm shrink-0">
                            {{ substr($pengajuanPkl->siswa?->user?->name ?? '-', 0, 1) }}
                        </div>
                        <div>
                            <p class="font-bold text-surface-900 leading-snug">{{ $pengajuanPkl->siswa?->user?->name ?? '-' }}</p>
                            <p class="text-xs text-surface-400 font-medium mt-0.5">{{ $pengajuanPkl->tempatPkl?->nama_tempat ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('guru.penilaian.store', $pengajuanPkl) }}" method="POST">
                    @csrf
                    <div class="space-y-6">
                        <div>
                            <label class="form-label">Nilai Sikap (0-100)</label>
                            <input type="number" name="nilai_sikap" min="0" max="100" value="{{ old('nilai_sikap') }}" class="form-input-premium mt-1.5" required>
                            @error('nilai_sikap') <p class="text-rose-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="form-label">Nilai Keterampilan (0-100)</label>
                            <input type="number" name="nilai_keterampilan" min="0" max="100" value="{{ old('nilai_keterampilan') }}" class="form-input-premium mt-1.5" required>
                            @error('nilai_keterampilan') <p class="text-rose-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="form-label">Nilai Laporan (0-100)</label>
                            <input type="number" name="nilai_laporan" min="0" max="100" value="{{ old('nilai_laporan') }}" class="form-input-premium mt-1.5" required>
                            @error('nilai_laporan') <p class="text-rose-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="form-label">Catatan Evaluasi</label>
                            <textarea name="catatan_evaluasi" rows="4" class="form-input-premium mt-1.5">{{ old('catatan_evaluasi') }}</textarea>
                            @error('catatan_evaluasi') <p class="text-rose-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div class="mt-8 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                        <a href="{{ route('guru.penilaian.index') }}" class="btn-secondary w-full sm:w-auto text-center">Batal</a>
                        <button type="submit" class="btn-primary w-full sm:w-auto flex items-center justify-center gap-1.5">
                            <i data-lucide="save" class="w-4 h-4 shrink-0"></i>
                            Simpan Nilai
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

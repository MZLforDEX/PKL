<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.periode-pkl.index') }}" class="p-2 bg-surface-100 hover:bg-surface-200 text-surface-600 rounded-lg transition-colors">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
            </a>
            <div>
                <h2 class="font-extrabold text-lg md:text-2xl text-surface-900 tracking-tight">Ubah Periode PKL</h2>
                <p class="text-sm text-surface-50 mt-0.5">Edit detail periode pelaksanaan PKL.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6 md:py-10 animate-fade-in">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="card-premium p-6 md:p-8">
                <form action="{{ route('admin.periode-pkl.update', $periodePkl) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        {{-- Nama Periode --}}
                        <div>
                            <label for="nama_periode" class="form-label">Nama Periode</label>
                            <input type="text" name="nama_periode" id="nama_periode" value="{{ old('nama_periode', $periodePkl->nama_periode) }}" placeholder="Contoh: PKL 2025/2026" class="form-input-premium w-full @error('nama_periode') border-rose-500 @enderror" required>
                            @error('nama_periode')
                                <p class="text-rose-600 text-xs mt-1.5 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tanggal --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                                <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ old('tanggal_mulai', $periodePkl->tanggal_mulai ? $periodePkl->tanggal_mulai->format('Y-m-d') : '') }}" class="form-input-premium w-full @error('tanggal_mulai') border-rose-500 @enderror" required>
                                @error('tanggal_mulai')
                                    <p class="text-rose-600 text-xs mt-1.5 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                                <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ old('tanggal_selesai', $periodePkl->tanggal_selesai ? $periodePkl->tanggal_selesai->format('Y-m-d') : '') }}" class="form-input-premium w-full @error('tanggal_selesai') border-rose-500 @enderror" required>
                                @error('tanggal_selesai')
                                    <p class="text-rose-600 text-xs mt-1.5 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Status Aktif --}}
                        <div class="p-4 bg-surface-50 dark:bg-zinc-800/30 rounded-xl border border-surface-100 flex items-center justify-between">
                            <div>
                                <h4 class="text-sm font-bold text-surface-900">Aktifkan Periode Ini</h4>
                                <p class="text-xs text-surface-500 mt-0.5">Jika diaktifkan, periode ini akan menjadi periode aktif utama dan menonaktifkan periode lainnya.</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer select-none">
                                <input type="checkbox" name="status_aktif" value="1" class="sr-only peer" {{ old('status_aktif', $periodePkl->status_aktif) ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-surface-200 peer-focus:outline-none rounded-full peer dark:bg-zinc-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-surface-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-zinc-600 peer-checked:bg-indigo-600"></div>
                            </label>
                        </div>
                    </div>

                    <div class="mt-8 pt-6 border-t border-surface-100 flex justify-end gap-3">
                        <a href="{{ route('admin.periode-pkl.index') }}" class="btn-secondary">
                            Batal
                        </a>
                        <button type="submit" class="btn-primary">
                            Perbarui Periode
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

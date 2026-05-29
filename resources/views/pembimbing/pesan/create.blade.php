<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('pembimbing.hubungi-sekolah.index') }}" class="p-2 rounded-lg hover:bg-surface-100 text-surface-400 hover:text-surface-600 transition-colors">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <h2 class="font-extrabold text-lg md:text-2xl text-surface-900 tracking-tight">Kirim Pesan Baru</h2>
        </div>
    </x-slot>

    <div class="py-6 md:py-10 animate-fade-in">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="card-premium p-6 sm:p-8">
                <form action="{{ route('pembimbing.hubungi-sekolah.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label class="form-label">Subjek Pesan</label>
                            <input type="text" name="subjek" value="{{ old('subjek') }}" class="form-input-premium mt-1.5" placeholder="Contoh: Pertanyaan Laporan Akhir Siswa / Kendala Absensi" required>
                            @error('subjek') <p class="text-rose-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="form-label">Kategori Masalah</label>
                            <select name="kategori" class="form-input-premium mt-1.5" required>
                                <option value="" disabled selected>-- Pilih Kategori --</option>
                                <option value="administrasi" {{ old('kategori') == 'administrasi' ? 'selected' : '' }}>Administrasi / Dokumen</option>
                                <option value="kendala_siswa" {{ old('kategori') == 'kendala_siswa' ? 'selected' : '' }}>Kendala Sikap / Kehadiran Siswa</option>
                                <option value="teknis" {{ old('kategori') == 'teknis' ? 'selected' : '' }}>Masalah Teknis Aplikasi</option>
                                <option value="lainnya" {{ old('kategori') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                            @error('kategori') <p class="text-rose-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="form-label">Isi Pesan / Keluhan</label>
                            <textarea name="pesan" rows="5" class="form-input-premium mt-1.5" placeholder="Tuliskan keluhan atau pesan Anda secara detail di sini..." required>{{ old('pesan') }}</textarea>
                            @error('pesan') <p class="text-rose-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="form-label">File Lampiran <span class="text-surface-400 font-normal">(opsional - JPG/PNG/PDF, max 2MB)</span></label>
                            <input type="file" name="lampiran" class="mt-2 block w-full text-sm text-surface-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-brand-50 file:text-brand-600 hover:file:bg-brand-100">
                            @error('lampiran') <p class="text-rose-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="mt-8 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                        <a href="{{ route('pembimbing.hubungi-sekolah.index') }}" class="btn-secondary w-full sm:w-auto text-center">Batal</a>
                        <button type="submit" class="btn-primary w-full sm:w-auto flex items-center justify-center gap-1.5 bg-indigo-600 hover:bg-indigo-700">
                            <i data-lucide="send" class="w-4 h-4 shrink-0"></i>
                            Kirim Pesan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

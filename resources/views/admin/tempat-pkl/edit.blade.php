<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.tempat-pkl.index') }}" class="p-2 rounded-lg hover:bg-surface-100 text-surface-400 hover:text-surface-600 transition-colors">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <h2 class="font-extrabold text-lg md:text-2xl text-surface-900 tracking-tight">Edit Tempat PKL</h2>
        </div>
    </x-slot>

    <div class="py-6 md:py-10 animate-fade-in">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="card-premium p-6 md:p-8">
                <form action="{{ route('admin.tempat-pkl.update', $tempatPkl) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div class="sm:col-span-2">
                            <label class="form-label">Nama Tempat</label>
                            <input type="text" name="nama_tempat" value="{{ old('nama_tempat', $tempatPkl->nama_tempat) }}" class="form-input-premium" required>
                            @error('nama_tempat') <p class="text-rose-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                        </div>
                        <div class="sm:col-span-2">
                            <label class="form-label">Alamat</label>
                            <textarea name="alamat" rows="3" class="form-input-premium" required>{{ old('alamat', $tempatPkl->alamat) }}</textarea>
                            @error('alamat') <p class="text-rose-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="form-label">Bidang Usaha</label>
                            <input type="text" name="bidang_usaha" value="{{ old('bidang_usaha', $tempatPkl->bidang_usaha) }}" class="form-input-premium" required>
                            @error('bidang_usaha') <p class="text-rose-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="form-label">Kuota</label>
                            <input type="number" name="kuota" value="{{ old('kuota', $tempatPkl->kuota) }}" class="form-input-premium" required>
                            @error('kuota') <p class="text-rose-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="form-label">Kontak Person</label>
                            <input type="text" name="kontak_person" value="{{ old('kontak_person', $tempatPkl->kontak_person) }}" class="form-input-premium">
                        </div>
                        <div>
                            <label class="form-label">No. HP</label>
                            <input type="text" name="no_hp" value="{{ old('no_hp', $tempatPkl->no_hp) }}" class="form-input-premium">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" value="{{ old('email', $tempatPkl->email) }}" class="form-input-premium">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="form-label">Keterangan</label>
                            <textarea name="keterangan" rows="3" class="form-input-premium">{{ old('keterangan', $tempatPkl->keterangan) }}</textarea>
                        </div>
                    </div>
                    <div class="mt-8 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                        <a href="{{ route('admin.tempat-pkl.index') }}" class="btn-secondary w-full sm:w-auto text-center">Batal</a>
                        <button type="submit" class="btn-primary w-full sm:w-auto">
                            <i data-lucide="save" class="w-4 h-4 mr-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

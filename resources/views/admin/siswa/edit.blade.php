<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.siswa.index') }}" class="p-2 rounded-lg hover:bg-surface-100 text-surface-400 hover:text-surface-600 transition-colors">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <h2 class="font-extrabold text-lg md:text-2xl text-surface-900 tracking-tight">Edit Siswa</h2>
        </div>
    </x-slot>

    <div class="py-6 md:py-10 animate-fade-in">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="card-premium p-6 md:p-8">
                <form action="{{ route('admin.siswa.update', $siswa) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div class="sm:col-span-2">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name', $siswa->user->name) }}" class="form-input-premium" required>
                            @error('name') <p class="text-rose-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                        </div>
                        <div class="sm:col-span-2">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" value="{{ old('email', $siswa->user->email) }}" class="form-input-premium" required>
                            @error('email') <p class="text-rose-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                        </div>
                        <div class="sm:col-span-2">
                            <label class="form-label">Password <span class="text-surface-400 font-normal">(kosongkan jika tidak diubah)</span></label>
                            <input type="password" name="password" class="form-input-premium" placeholder="••••••••">
                            @error('password') <p class="text-rose-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="form-label">NIS</label>
                            <input type="text" name="nis" value="{{ old('nis', $siswa->nis) }}" class="form-input-premium" required>
                            @error('nis') <p class="text-rose-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="form-label">Kelas</label>
                            <input type="text" name="kelas" value="{{ old('kelas', $siswa->kelas) }}" class="form-input-premium" required>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="form-label">Jurusan</label>
                            <input type="text" name="jurusan" value="{{ old('jurusan', $siswa->jurusan) }}" class="form-input-premium" required>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="form-label">Alamat</label>
                            <textarea name="alamat" rows="3" class="form-input-premium">{{ old('alamat', $siswa->alamat) }}</textarea>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="form-label">No. HP</label>
                            <input type="text" name="no_hp" value="{{ old('no_hp', $siswa->no_hp) }}" class="form-input-premium">
                        </div>
                    </div>
                    <div class="mt-8 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                        <a href="{{ route('admin.siswa.index') }}" class="btn-secondary w-full sm:w-auto text-center">Batal</a>
                        <button type="submit" class="btn-primary w-full sm:w-auto">
                            <i data-lucide="save" class="w-4 h-4 mr-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

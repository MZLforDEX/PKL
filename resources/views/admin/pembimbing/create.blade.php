<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.pembimbing-industri.index') }}" class="p-2 rounded-lg hover:bg-surface-100 text-surface-400 hover:text-surface-600 transition-colors">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <h2 class="font-extrabold text-lg md:text-2xl text-surface-900 tracking-tight">Tambah Pembimbing Industri</h2>
        </div>
    </x-slot>

    <div class="py-6 md:py-10 animate-fade-in">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="card-premium p-6 md:p-8">
                <form action="{{ route('admin.pembimbing-industri.store') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div class="sm:col-span-2">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name') }}" class="form-input-premium" placeholder="Nama lengkap pembimbing" required>
                            @error('name') <p class="text-rose-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                        </div>
                        <div class="sm:col-span-2">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="form-input-premium" placeholder="nama@perusahaan.com" required>
                            @error('email') <p class="text-rose-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                        </div>
                        <div class="sm:col-span-2">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-input-premium" placeholder="Minimal 8 karakter" required>
                            @error('password') <p class="text-rose-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                        </div>
                        <div class="sm:col-span-2">
                            <label class="form-label">Tempat PKL / Perusahaan</label>
                            <select name="tempat_pkl_id" class="form-input-premium" required>
                                <option value="">Pilih Perusahaan Mitra</option>
                                @foreach($tempatPkl as $t)
                                    <option value="{{ $t->id }}" @selected(old('tempat_pkl_id') == $t->id)>{{ $t->nama_tempat }}</option>
                                @endforeach
                            </select>
                            @error('tempat_pkl_id') <p class="text-rose-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="form-label">Jabatan / Posisi</label>
                            <input type="text" name="jabatan" value="{{ old('jabatan') }}" class="form-input-premium" placeholder="Contoh: HRD, Supervisor, Mentor IT">
                            @error('jabatan') <p class="text-rose-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="form-label">No. HP</label>
                            <input type="text" name="no_hp" value="{{ old('no_hp') }}" class="form-input-premium" placeholder="08xxxxxxxxxx">
                            @error('no_hp') <p class="text-rose-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div class="mt-8 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                        <a href="{{ route('admin.pembimbing-industri.index') }}" class="btn-secondary w-full sm:w-auto text-center">Batal</a>
                        <button type="submit" class="btn-primary w-full sm:w-auto">
                            <i data-lucide="save" class="w-4 h-4 mr-2"></i>Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

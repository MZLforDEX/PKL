<x-app-layout>
    <div class="py-5 md:py-10">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumbs -->
            <nav class="flex mb-4 md:mb-8 text-xs font-bold text-surface-400 uppercase tracking-widest" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route(match(Auth::user()->role) {'admin' => 'admin.dashboard', 'guru' => 'guru.dashboard', 'siswa' => 'siswa.dashboard', 'pembimbing_industri' => 'pembimbing.dashboard', default => 'dashboard'}) }}" class="hover:text-brand-600 transition-colors">Dashboard</a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i data-lucide="chevron-right" class="w-3 h-3 mx-1"></i>
                            <span class="text-surface-800">Profil Saya</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 md:gap-8">
                <!-- Left Column: User Card & Navigation -->
                <div class="lg:col-span-4 space-y-4 md:space-y-6">
                    <div class="card-premium overflow-hidden text-center p-6 md:p-8">
                        <div class="relative inline-block group mb-6">
                            <div class="w-24 h-24 md:w-32 md:h-32 rounded-3xl overflow-hidden shadow-2xl ring-4 ring-white ring-offset-4 ring-offset-surface-50 mx-auto transition-transform duration-500 group-hover:scale-105">
                                @if($user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-brand-500 to-brand-700 flex items-center justify-center text-white text-3xl md:text-5xl font-bold">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            <label for="avatar-input" class="absolute bottom-0 right-0 p-2 md:p-3 bg-white text-brand-600 rounded-2xl shadow-xl border border-surface-100 cursor-pointer hover:bg-brand-50 transition-all transform hover:scale-110 active:scale-95 group-hover:translate-x-1 group-hover:translate-y-1">
                                <i data-lucide="camera" class="w-4 h-4 md:w-5 md:h-5"></i>
                            </label>
                        </div>

                        <h3 class="text-lg md:text-xl font-bold text-surface-800 leading-tight mb-1">{{ $user->name }}</h3>
                        <p class="text-xs font-bold text-surface-400 uppercase tracking-widest mb-6">{{ $user->role }}</p>

                        <div class="flex items-center justify-center space-x-2 py-3 px-4 bg-brand-50 rounded-2xl border border-brand-100">
                            <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                            <span class="text-xs font-bold text-brand-700 uppercase tracking-tight">Akun Aktif</span>
                        </div>
                    </div>

                    <!-- Navigation Links -->
                    <div class="card-premium p-2">
                        <a href="{{ route('profile.edit') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-bold bg-brand-50 text-brand-700 border border-brand-100 transition-all">
                            <i data-lucide="user" class="w-5 h-5"></i>
                            <span>Informasi Akun</span>
                        </a>
                        <a href="{{ route('profile.security') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-bold text-surface-600 hover:bg-surface-50 hover:text-surface-800 transition-all mt-1">
                            <i data-lucide="lock" class="w-5 h-5"></i>
                            <span>Keamanan</span>
                        </a>
                    </div>
                </div>

                <!-- Right Column: Forms -->
                <div class="lg:col-span-8 space-y-4 md:space-y-8">
                    <!-- Main Profile Form -->
                    <div id="info" class="card-premium p-4 sm:p-6 md:p-8">
                        <div class="flex items-center justify-between mb-8 pb-6 border-b border-surface-100">
                            <div>
                                <h2 class="text-lg md:text-2xl font-bold text-surface-800 tracking-tight">Data Profil</h2>
                                <p class="text-xs md:text-sm text-surface-500 mt-1">Kelola informasi identitas Anda di sini.</p>
                            </div>
                            <div class="w-10 h-10 md:w-12 md:h-12 bg-brand-50 text-brand-600 rounded-2xl flex items-center justify-center">
                                <i data-lucide="edit-3" class="w-5 h-5 md:w-6 md:h-6"></i>
                            </div>
                        </div>

                        <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-4 md:space-y-8">
                            @csrf
                            @method('patch')

                            <!-- Avatar Hidden Input -->
                            <input type="file" id="avatar-input" name="avatar" class="hidden" accept="image/*" onchange="previewImage(this)">

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                                <!-- Name -->
                                <div class="space-y-2">
                                    <x-input-label for="name" :value="__('Nama Lengkap')" class="text-[10px] font-bold text-surface-400 uppercase tracking-widest" />
                                    <x-text-input id="name" name="name" type="text" class="w-full rounded-xl border-surface-200 focus:ring-brand-500 focus:border-brand-500 text-sm font-medium" :value="old('name', $user->name)" required autofocus />
                                    <x-input-error class="text-xs mt-1" :messages="$errors->get('name')" />
                                </div>

                                <!-- Email -->
                                <div class="space-y-2">
                                    <x-input-label for="email" :value="__('Alamat Email')" class="text-[10px] font-bold text-surface-400 uppercase tracking-widest" />
                                    <x-text-input id="email" name="email" type="email" class="w-full rounded-xl border-surface-200 focus:ring-brand-500 focus:border-brand-500 text-sm font-medium" :value="old('email', $user->email)" required />
                                    <x-input-error class="text-xs mt-1" :messages="$errors->get('email')" />
                                </div>

                                @if($user->role === 'siswa' && $user->siswa)
                                    <!-- NIS -->
                                    <div class="space-y-2">
                                        <x-input-label for="nis" :value="__('Nomor Induk Siswa (NIS)')" class="text-[10px] font-bold text-surface-400 uppercase tracking-widest" />
                                        <x-text-input id="nis" name="nis" type="text" class="w-full rounded-xl border-surface-200 focus:ring-brand-500 focus:border-brand-500 text-sm font-medium" :value="old('nis', $user->siswa->nis)" required />
                                        <x-input-error class="text-xs mt-1" :messages="$errors->get('nis')" />
                                    </div>

                                    <!-- Kelas -->
                                    <div class="space-y-2">
                                        <x-input-label for="kelas" :value="__('Kelas')" class="text-[10px] font-bold text-surface-400 uppercase tracking-widest" />
                                        <x-text-input id="kelas" name="kelas" type="text" class="w-full rounded-xl border-surface-200 focus:ring-brand-500 focus:border-brand-500 text-sm font-medium" :value="old('kelas', $user->siswa->kelas)" required />
                                        <x-input-error class="text-xs mt-1" :messages="$errors->get('kelas')" />
                                    </div>

                                    <!-- Jurusan -->
                                    <div class="space-y-2 md:col-span-2">
                                        <x-input-label for="jurusan" :value="__('Jurusan')" class="text-[10px] font-bold text-surface-400 uppercase tracking-widest" />
                                        <x-text-input id="jurusan" name="jurusan" type="text" class="w-full rounded-xl border-surface-200 focus:ring-brand-500 focus:border-brand-500 text-sm font-medium" :value="old('jurusan', $user->siswa->jurusan)" required />
                                        <x-input-error class="text-xs mt-1" :messages="$errors->get('jurusan')" />
                                    </div>

                                    <!-- No HP -->
                                    <div class="space-y-2">
                                        <x-input-label for="no_hp" :value="__('Nomor WhatsApp')" class="text-[10px] font-bold text-surface-400 uppercase tracking-widest" />
                                        <x-text-input id="no_hp" name="no_hp" type="text" class="w-full rounded-xl border-surface-200 focus:ring-brand-500 focus:border-brand-500 text-sm font-medium" :value="old('no_hp', $user->siswa->no_hp)" required />
                                        <x-input-error class="text-xs mt-1" :messages="$errors->get('no_hp')" />
                                    </div>

                                    <!-- Alamat -->
                                    <div class="space-y-2 md:col-span-2">
                                        <x-input-label for="alamat" :value="__('Alamat Lengkap')" class="text-[10px] font-bold text-surface-400 uppercase tracking-widest" />
                                        <textarea id="alamat" name="alamat" rows="3" class="w-full rounded-xl border-surface-200 focus:ring-brand-500 focus:border-brand-500 text-sm font-medium">{{ old('alamat', $user->siswa->alamat) }}</textarea>
                                        <x-input-error class="text-xs mt-1" :messages="$errors->get('alamat')" />
                                    </div>
                                @endif

                                @if($user->role === 'guru' && $user->guru)
                                    <!-- NIP -->
                                    <div class="space-y-2">
                                        <x-input-label for="nip" :value="__('NIP')" class="text-[10px] font-bold text-surface-400 uppercase tracking-widest" />
                                        <x-text-input id="nip" name="nip" type="text" class="w-full rounded-xl border-surface-200 focus:ring-brand-500 focus:border-brand-500 text-sm font-medium" :value="old('nip', $user->guru->nip)" required />
                                        <x-input-error class="text-xs mt-1" :messages="$errors->get('nip')" />
                                    </div>

                                    <!-- No HP -->
                                    <div class="space-y-2">
                                        <x-input-label for="no_hp" :value="__('Nomor WhatsApp')" class="text-[10px] font-bold text-surface-400 uppercase tracking-widest" />
                                        <x-text-input id="no_hp" name="no_hp" type="text" class="w-full rounded-xl border-surface-200 focus:ring-brand-500 focus:border-brand-500 text-sm font-medium" :value="old('no_hp', $user->guru->no_hp)" required />
                                        <x-input-error class="text-xs mt-1" :messages="$errors->get('no_hp')" />
                                    </div>

                                    <!-- Alamat -->
                                    <div class="space-y-2 md:col-span-2">
                                        <x-input-label for="alamat" :value="__('Alamat Lengkap')" class="text-[10px] font-bold text-surface-400 uppercase tracking-widest" />
                                        <textarea id="alamat" name="alamat" rows="3" class="w-full rounded-xl border-surface-200 focus:ring-brand-500 focus:border-brand-500 text-sm font-medium">{{ old('alamat', $user->guru->alamat) }}</textarea>
                                        <x-input-error class="text-xs mt-1" :messages="$errors->get('alamat')" />
                                    </div>
                                @endif

                                @if($user->role === 'pembimbing_industri' && $user->pembimbingIndustri)
                                    <!-- Jabatan -->
                                    <div class="space-y-2">
                                        <x-input-label for="jabatan" :value="__('Jabatan / Posisi')" class="text-[10px] font-bold text-surface-400 uppercase tracking-widest" />
                                        <x-text-input id="jabatan" name="jabatan" type="text" class="w-full rounded-xl border-surface-200 focus:ring-brand-500 focus:border-brand-500 text-sm font-medium" :value="old('jabatan', $user->pembimbingIndustri->jabatan)" required />
                                        <x-input-error class="text-xs mt-1" :messages="$errors->get('jabatan')" />
                                    </div>

                                    <!-- No HP -->
                                    <div class="space-y-2">
                                        <x-input-label for="no_hp" :value="__('Nomor WhatsApp')" class="text-[10px] font-bold text-surface-400 uppercase tracking-widest" />
                                        <x-text-input id="no_hp" name="no_hp" type="text" class="w-full rounded-xl border-surface-200 focus:ring-brand-500 focus:border-brand-500 text-sm font-medium" :value="old('no_hp', $user->pembimbingIndustri->no_hp)" required />
                                        <x-input-error class="text-xs mt-1" :messages="$errors->get('no_hp')" />
                                    </div>

                                    <!-- Tanda Tangan -->
                                    <div class="space-y-2 md:col-span-2">
                                        <x-input-label for="tanda_tangan" :value="__('File Tanda Tangan (PNG Transparan Direkomendasikan)')" class="text-[10px] font-bold text-surface-400 uppercase tracking-widest" />
                                        <div class="flex items-center gap-6 p-4 bg-surface-50 rounded-2xl border border-surface-200/60">
                                            @if($user->pembimbingIndustri->tanda_tangan)
                                                <div class="w-32 h-20 bg-white border border-surface-200 rounded-xl overflow-hidden p-1 flex items-center justify-center shadow-sm shrink-0">
                                                    <img src="{{ asset('storage/' . $user->pembimbingIndustri->tanda_tangan) }}" alt="Tanda Tangan" class="max-w-full max-h-full object-contain">
                                                </div>
                                            @else
                                                <div class="w-32 h-20 bg-white border border-dashed border-surface-300 rounded-xl flex items-center justify-center text-xs text-surface-400 font-bold shrink-0">
                                                    Belum Ada TTD
                                                </div>
                                            @endif
                                            <div class="flex-1 space-y-1">
                                                <input id="tanda_tangan" name="tanda_tangan" type="file" class="w-full text-xs font-bold text-surface-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-extrabold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100 transition-all" accept="image/*" />
                                                <p class="text-[10px] text-surface-400 font-medium leading-normal">Unggah foto tanda tangan digital Anda. Format JPG/PNG, maks 2MB.</p>
                                                <x-input-error class="text-xs mt-1" :messages="$errors->get('tanda_tangan')" />
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Logo Industri -->
                                    <div class="space-y-2 md:col-span-2">
                                        <x-input-label for="logo" :value="__('Logo Perusahaan / Industri')" class="text-[10px] font-bold text-surface-400 uppercase tracking-widest" />
                                        <div class="flex items-center gap-6 p-4 bg-surface-50 rounded-2xl border border-surface-200/60">
                                            @if($user->pembimbingIndustri->logo)
                                                <div class="w-32 h-20 bg-white border border-surface-200 rounded-xl overflow-hidden p-1 flex items-center justify-center shadow-sm shrink-0">
                                                    <img src="{{ asset('storage/' . $user->pembimbingIndustri->logo) }}" alt="Logo Industri" class="max-w-full max-h-full object-contain">
                                                </div>
                                            @else
                                                <div class="w-32 h-20 bg-white border border-dashed border-surface-300 rounded-xl flex items-center justify-center text-xs text-surface-400 font-bold shrink-0">
                                                    Belum Ada Logo
                                                </div>
                                            @endif
                                            <div class="flex-1 space-y-1">
                                                <input id="logo" name="logo" type="file" class="w-full text-xs font-bold text-surface-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-extrabold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100 transition-all" accept="image/*" />
                                                <p class="text-[10px] text-surface-400 font-medium leading-normal">Unggah logo perusahaan/industri Anda. Format JPG/PNG, maks 2MB.</p>
                                                <x-input-error class="text-xs mt-1" :messages="$errors->get('logo')" />
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="flex items-center gap-4 pt-4">
                                <button type="submit" class="btn-primary px-8 py-3">
                                    <i data-lucide="check" class="w-4 h-4 mr-2"></i>
                                    Simpan Perubahan
                                </button>

                                @if (session('status') === 'profile-updated')
                                    <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm font-bold text-emerald-600 flex items-center">
                                        <i data-lucide="check-circle" class="w-4 h-4 mr-1"></i>
                                        Berhasil disimpan!
                                    </p>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function previewImage(input) {
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        // Form will be submitted when save is clicked
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }
        </script>
    @endpush
</x-app-layout>
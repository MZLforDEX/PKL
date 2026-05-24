<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <h2 class="text-xl font-extrabold text-surface-900 mb-1">Buat Akun Baru</h2>
        <p class="text-sm text-surface-500 mb-6">Daftar sebagai siswa untuk memulai program PKL.</p>

        <div class="space-y-4">
            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-semibold text-surface-700 mb-1.5">Nama Lengkap</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                    class="block w-full rounded-xl bg-white border border-surface-200 text-surface-900 placeholder-surface-400 px-4 py-3 text-sm focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 transition-all"
                    placeholder="Nama lengkap Anda">
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-sm font-semibold text-surface-700 mb-1.5">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                    class="block w-full rounded-xl bg-white border border-surface-200 text-surface-900 placeholder-surface-400 px-4 py-3 text-sm focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 transition-all"
                    placeholder="nama@email.com">
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- NIS & Kelas Row -->
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="nis" class="block text-sm font-semibold text-surface-700 mb-1.5">NIS</label>
                    <input id="nis" type="text" name="nis" value="{{ old('nis') }}" required
                        class="block w-full rounded-xl bg-white border border-surface-200 text-surface-900 placeholder-surface-400 px-4 py-3 text-sm focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 transition-all"
                        placeholder="123456">
                    <x-input-error :messages="$errors->get('nis')" class="mt-2" />
                </div>
                <div>
                    <label for="kelas" class="block text-sm font-semibold text-surface-700 mb-1.5">Kelas</label>
                    <input id="kelas" type="text" name="kelas" value="{{ old('kelas') }}" required
                        class="block w-full rounded-xl bg-white border border-surface-200 text-surface-900 placeholder-surface-400 px-4 py-3 text-sm focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 transition-all"
                        placeholder="XII RPL 1">
                    <x-input-error :messages="$errors->get('kelas')" class="mt-2" />
                </div>
            </div>

            <!-- Jurusan -->
            <div>
                <label for="jurusan" class="block text-sm font-semibold text-surface-700 mb-1.5">Jurusan</label>
                <input id="jurusan" type="text" name="jurusan" value="{{ old('jurusan') }}" required
                    class="block w-full rounded-xl bg-white border border-surface-200 text-surface-900 placeholder-surface-400 px-4 py-3 text-sm focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 transition-all"
                    placeholder="Rekayasa Perangkat Lunak">
                <x-input-error :messages="$errors->get('jurusan')" class="mt-2" />
            </div>

            <!-- Alamat & No HP Row -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label for="alamat" class="block text-sm font-semibold text-surface-700 mb-1.5">Alamat</label>
                    <input id="alamat" type="text" name="alamat" value="{{ old('alamat') }}" required
                        class="block w-full rounded-xl bg-white border border-surface-200 text-surface-900 placeholder-surface-400 px-4 py-3 text-sm focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 transition-all"
                        placeholder="Alamat lengkap">
                    <x-input-error :messages="$errors->get('alamat')" class="mt-2" />
                </div>
                <div>
                    <label for="no_hp" class="block text-sm font-semibold text-surface-700 mb-1.5">No HP</label>
                    <input id="no_hp" type="text" name="no_hp" value="{{ old('no_hp') }}" required
                        class="block w-full rounded-xl bg-white border border-surface-200 text-surface-900 placeholder-surface-400 px-4 py-3 text-sm focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 transition-all"
                        placeholder="08xxxxxxxxxx">
                    <x-input-error :messages="$errors->get('no_hp')" class="mt-2" />
                </div>
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-semibold text-surface-700 mb-1.5">Password</label>
                <input id="password" type="password" name="password" required autocomplete="new-password"
                    class="block w-full rounded-xl bg-white border border-surface-200 text-surface-900 placeholder-surface-400 px-4 py-3 text-sm focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 transition-all"
                    placeholder="Minimal 8 karakter">
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-sm font-semibold text-surface-700 mb-1.5">Konfirmasi Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                    class="block w-full rounded-xl bg-white border border-surface-200 text-surface-900 placeholder-surface-400 px-4 py-3 text-sm focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 transition-all"
                    placeholder="Ulangi password">
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>
        </div>

        <button type="submit" class="btn-primary w-full mt-7 !py-3 text-base">
            Daftar Sekarang
        </button>

        <p class="text-center text-sm text-surface-500 mt-6">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="text-brand-600 hover:text-brand-700 font-semibold transition-colors">Masuk</a>
        </p>
    </form>
</x-guest-layout>

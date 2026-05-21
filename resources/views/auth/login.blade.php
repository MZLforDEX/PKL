<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <h2 class="text-xl font-extrabold text-white mb-1">Selamat Datang</h2>
        <p class="text-sm text-surface-400 mb-8">Masuk ke akun SPARTA Anda untuk melanjutkan.</p>

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-semibold text-surface-300 mb-1.5">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                class="block w-full rounded-xl bg-white/[0.06] border border-white/[0.1] text-white placeholder-surface-500 px-4 py-3 text-sm focus:border-brand-400 focus:ring-4 focus:ring-brand-500/20 transition-all"
                placeholder="nama@email.com">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-5">
            <label for="password" class="block text-sm font-semibold text-surface-300 mb-1.5">Password</label>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                class="block w-full rounded-xl bg-white/[0.06] border border-white/[0.1] text-white placeholder-surface-500 px-4 py-3 text-sm focus:border-brand-400 focus:ring-4 focus:ring-brand-500/20 transition-all"
                placeholder="••••••••">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between mt-5">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded bg-white/[0.06] border-white/[0.15] text-brand-500 shadow-sm focus:ring-brand-500/30 focus:ring-offset-0" name="remember">
                <span class="ms-2 text-sm text-surface-400">Ingat saya</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-brand-400 hover:text-brand-300 font-medium transition-colors" href="{{ route('password.request') }}">
                    Lupa password?
                </a>
            @endif
        </div>

        <button type="submit" class="btn-primary w-full mt-7 !py-3 text-base">
            Masuk
        </button>

        <p class="text-center text-sm text-surface-500 mt-6">
            Belum punya akun?
            <a href="{{ route('register') }}" class="text-brand-400 hover:text-brand-300 font-semibold transition-colors">Daftar sekarang</a>
        </p>
    </form>
</x-guest-layout>

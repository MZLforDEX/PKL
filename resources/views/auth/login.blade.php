<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <h2 class="text-xl font-extrabold text-surface-900 mb-1">Selamat Datang</h2>
        <p class="text-sm text-surface-500 mb-8">Masuk ke akun SiPKL Anda untuk melanjutkan.</p>

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-semibold text-surface-700 mb-1.5">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                class="block w-full rounded-xl bg-white border border-surface-200 text-surface-900 placeholder-surface-400 px-4 py-3 text-sm focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 transition-all"
                placeholder="nama@email.com">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-5">
            <label for="password" class="block text-sm font-semibold text-surface-700 mb-1.5">Password</label>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                class="block w-full rounded-xl bg-white border border-surface-200 text-surface-900 placeholder-surface-400 px-4 py-3 text-sm focus:border-brand-500 focus:ring-4 focus:ring-brand-500/10 transition-all"
                placeholder="••••••••">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between mt-5">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded bg-white border-surface-300 text-brand-600 shadow-sm focus:ring-brand-500/20 focus:ring-offset-0" name="remember">
                <span class="ms-2 text-sm text-surface-500">Ingat saya</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-brand-600 hover:text-brand-700 font-medium transition-colors" href="{{ route('password.request') }}">
                    Lupa password?
                </a>
            @endif
        </div>

        <button type="submit" class="btn-primary w-full mt-7 !py-3 text-base">
            Masuk
        </button>

        <p class="text-center text-sm text-surface-500 mt-6">
            Belum punya akun?
            <a href="{{ route('register') }}" class="text-brand-600 hover:text-brand-700 font-semibold transition-colors">Daftar sekarang</a>
        </p>
    </form>
</x-guest-layout>

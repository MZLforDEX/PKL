<section>
    <form method="post" action="{{ route('password.update') }}" class="space-y-5">
        @csrf
        @method('put')

        <div>
            <label class="form-label" for="update_password_current_password">Kata Sandi Saat Ini</label>
            <input id="update_password_current_password" name="current_password" type="password" class="form-input-premium mt-1.5" autocomplete="current-password" placeholder="••••••••">
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2 text-rose-500 text-xs" />
        </div>

        <div>
            <label class="form-label" for="update_password_password">Kata Sandi Baru</label>
            <input id="update_password_password" name="password" type="password" class="form-input-premium mt-1.5" autocomplete="new-password" placeholder="Min. 8 karakter">
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2 text-rose-500 text-xs" />
        </div>

        <div>
            <label class="form-label" for="update_password_password_confirmation">Konfirmasi Kata Sandi Baru</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-input-premium mt-1.5" autocomplete="new-password" placeholder="Ulangi kata sandi baru">
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2 text-rose-500 text-xs" />
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="btn-primary">Simpan Kata Sandi</button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm font-bold text-emerald-600 flex items-center">
                    <i data-lucide="check-circle" class="w-4 h-4 mr-1"></i>
                    Berhasil disimpan!
                </p>
            @endif
        </div>
    </form>
</section>

<section class="space-y-6">
    <p class="text-sm text-surface-500 leading-relaxed">
        Setelah akun Anda dihapus, semua data dan riwayat pengajuan, jurnal, serta laporan Anda di sistem SiPKL akan dihapus secara permanen.
    </p>

    <button type="button" x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')" class="btn-danger">
        Hapus Akun Saya
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 sm:p-8">
            @csrf
            @method('delete')

            <h3 class="text-lg font-extrabold text-surface-900">
                Apakah Anda yakin ingin menghapus akun?
            </h3>

            <p class="mt-2 text-sm text-surface-500 leading-relaxed">
                Tindakan ini tidak dapat dibatalkan. Masukkan kata sandi Anda untuk mengonfirmasi bahwa Anda ingin menghapus akun ini secara permanen.
            </p>

            <div class="mt-6">
                <label for="password" class="form-label sr-only">Kata Sandi</label>
                <input id="password" name="password" type="password" class="form-input-premium block w-full sm:w-3/4" placeholder="Masukkan kata sandi Anda untuk konfirmasi">
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2 text-rose-500 text-xs" />
            </div>

            <div class="mt-8 flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')" class="btn-secondary">
                    Batal
                </button>
                <button type="submit" class="btn-danger">
                    Ya, Hapus Akun
                </button>
            </div>
        </form>
    </x-modal>
</section>

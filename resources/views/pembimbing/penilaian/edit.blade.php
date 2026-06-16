<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('pembimbing.penilaian.index') }}" class="p-2 rounded-lg hover:bg-surface-100 text-surface-400 hover:text-surface-600 transition-colors">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <h2 class="font-extrabold text-lg md:text-2xl text-surface-900 tracking-tight">Edit Penilaian PKL</h2>
        </div>
    </x-slot>

    @php
        $items = $penilaianPkl->detail_nilai;
        if (empty($items)) {
            $items = [
                ['nama' => 'Aspek Sikap dan Kedisiplinan Kerja', 'nilai' => $penilaianPkl->nilai_sikap ?? 0],
                ['nama' => 'Aspek Keterampilan Teknik & Kerja Lapangan', 'nilai' => $penilaianPkl->nilai_keterampilan ?? 0],
                ['nama' => 'Kualitas Penulisan & Pemaparan Laporan Akhir', 'nilai' => $penilaianPkl->nilai_laporan ?? 0],
            ];
        }
    @endphp

    <div class="py-6 md:py-10 animate-fade-in">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="card-premium p-6 sm:p-8">
                <!-- Siswa Info Banner -->
                <div class="mb-8 p-4.5 bg-surface-50 rounded-2xl border border-surface-200/60 flex items-center justify-between">
                    <div class="flex items-center gap-3.5">
                        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-brand-500 to-brand-600 flex items-center justify-center text-white font-bold text-base shadow-sm shrink-0">
                            {{ substr($pengajuanPkl->siswa?->user?->name ?? '-', 0, 1) }}
                        </div>
                        <div>
                            <p class="font-bold text-surface-900 leading-snug">{{ $pengajuanPkl->siswa?->user?->name ?? '-' }}</p>
                            <p class="text-xs text-surface-400 font-medium mt-0.5">NIS: {{ $pengajuanPkl->siswa?->nis ?? '-' }} • Jurusan: {{ $pengajuanPkl->siswa?->jurusan ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('pembimbing.penilaian.update', $penilaianPkl) }}" method="POST"
                    x-data="{
                        items: {{ json_encode($items) }},
                        addItem() {
                            this.items.push({ nama: '', nilai: 80 });
                        },
                        removeItem(index) {
                            if (this.items.length > 1) {
                                this.items.splice(index, 1);
                            } else {
                                alert('Minimal harus ada 1 komponen penilaian.');
                            }
                        },
                        get averageScore() {
                            if (this.items.length === 0) return 0;
                            let sum = this.items.reduce((acc, item) => acc + (parseInt(item.nilai) || 0), 0);
                            return (sum / this.items.length).toFixed(2);
                        }
                    }">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        <div class="flex items-center justify-between border-b border-surface-100 pb-3">
                            <h3 class="text-sm font-bold text-surface-900 uppercase tracking-wider">Komponen & Nilai Aspek</h3>
                            <button type="button" @click="addItem()" class="btn-secondary inline-flex items-center gap-1.5 !px-3 !py-1.5 !text-xs !bg-brand-50 !text-brand-600 !border-brand-100 hover:!bg-brand-100">
                                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                                Tambah Aspek
                            </button>
                        </div>

                        <!-- Dynamic Row Container -->
                        <div class="space-y-4">
                            <template x-for="(item, index) in items" :key="index">
                                <div class="flex items-center gap-4 p-4 bg-surface-50/50 border border-surface-200/50 rounded-2xl relative">
                                    <div class="flex-1 grid grid-cols-1 md:grid-cols-4 gap-4">
                                        <div class="md:col-span-3">
                                            <label class="text-[10px] font-bold text-surface-400 uppercase tracking-widest block mb-1">Nama Aspek / Kriteria</label>
                                            <input type="text" :name="'items['+index+'][nama]'" x-model="item.nama" class="form-input-premium w-full" placeholder="Contoh: Kedisiplinan / Kerjasama" required>
                                        </div>
                                        <div>
                                            <label class="text-[10px] font-bold text-surface-400 uppercase tracking-widest block mb-1">Nilai (0-100)</label>
                                            <input type="number" :name="'items['+index+'][nilai]'" x-model.number="item.nilai" min="0" max="100" class="form-input-premium w-full" required>
                                        </div>
                                    </div>
                                    <div class="pt-5 shrink-0">
                                        <button type="button" @click="removeItem(index)" class="p-2.5 text-rose-500 hover:bg-rose-50 rounded-xl transition-colors" title="Hapus Aspek">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Real-time Average -->
                        <div class="flex items-center justify-between p-4.5 bg-indigo-50/50 border border-indigo-100/60 rounded-2xl">
                            <span class="text-sm font-semibold text-indigo-700">Estimasi Nilai Rata-rata:</span>
                            <span class="text-2xl font-extrabold text-indigo-700" x-text="averageScore">0.00</span>
                        </div>

                        <!-- Catatan Evaluasi -->
                        <div>
                            <label class="form-label">Catatan Evaluasi / Rekomendasi</label>
                            <textarea name="catatan_evaluasi" rows="4" class="form-input-premium mt-1.5" placeholder="Berikan saran atau catatan mengenai kinerja siswa selama PKL...">{{ old('catatan_evaluasi', $penilaianPkl->catatan_evaluasi) }}</textarea>
                            @error('catatan_evaluasi') <p class="text-rose-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="mt-8 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                        <a href="{{ route('pembimbing.penilaian.index') }}" class="btn-secondary w-full sm:w-auto text-center">Batal</a>
                        <button type="submit" class="btn-primary w-full sm:w-auto flex items-center justify-center gap-1.5">
                            <i data-lucide="save" class="w-4 h-4 shrink-0"></i>
                            Perbarui Penilaian
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

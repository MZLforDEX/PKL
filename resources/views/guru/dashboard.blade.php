<x-app-layout>
    <div class="py-6 md:py-10 animate-fade-in">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="mb-8 md:mb-10">
                <div class="flex items-center gap-3 mb-1">
                    <div class="w-2 h-8 rounded-full bg-gradient-to-b from-emerald-500 to-teal-400"></div>
                    <h1 class="text-2xl md:text-3xl font-extrabold text-surface-900 tracking-tight">Panel Pembimbing
                    </h1>
                </div>
                <p class="text-surface-500 mt-1 ml-5 text-sm md:text-base">Kelola dan pantau progres siswa bimbingan
                    Anda.</p>
            </div>

            {{-- Stats Grid --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 stagger-children">
                {{-- Total Bimbingan --}}
                <div class="stat-card">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="icon-box bg-brand-50 text-brand-600 group-hover:bg-brand-600 group-hover:text-white group-hover:shadow-neon">
                            <i data-lucide="users" class="w-5 h-5"></i>
                        </div>
                        <span
                            class="text-[10px] font-semibold text-surface-400 uppercase tracking-widest">Bimbingan</span>
                    </div>
                    <h3 class="text-3xl md:text-4xl font-extrabold text-surface-900 tracking-tight">
                        {{ $totalBimbingan }}</h3>
                    <div class="mt-3 text-xs text-brand-600 font-medium">Siswa aktif PKL</div>
                </div>

                {{-- Pengajuan Menunggu --}}
                <div class="stat-card border-b-[3px] border-b-amber-400">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="icon-box bg-amber-50 text-amber-600 group-hover:bg-amber-600 group-hover:text-white">
                            <i data-lucide="file-warning" class="w-5 h-5"></i>
                        </div>
                        <span class="relative flex h-2.5 w-2.5">
                            <span
                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-amber-500"></span>
                        </span>
                    </div>
                    <h3 class="text-3xl md:text-4xl font-extrabold text-surface-900 tracking-tight">
                        {{ $menungguValidasi }}</h3>
                    <div class="mt-3 text-xs text-amber-600 font-medium">Menunggu persetujuan</div>
                </div>

                {{-- Jurnal Menunggu --}}
                <div class="stat-card border-b-[3px] border-b-orange-400">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="icon-box bg-orange-50 text-orange-600 group-hover:bg-orange-600 group-hover:text-white">
                            <i data-lucide="book-open" class="w-5 h-5"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-surface-400 uppercase tracking-widest">Jurnal</span>
                    </div>
                    <h3 class="text-3xl md:text-4xl font-extrabold text-surface-900 tracking-tight">
                        {{ $jurnalMenunggu }}</h3>
                    <div class="mt-3 text-xs text-orange-600 font-medium">Belum divalidasi</div>
                </div>

                {{-- Penilaian Menunggu --}}
                <div class="stat-card border-b-[3px] border-b-cyan-400">
                    <div class="flex items-center justify-between mb-4">
                        <div class="icon-box bg-cyan-50 text-cyan-600 group-hover:bg-cyan-600 group-hover:text-white">
                            <i data-lucide="award" class="w-5 h-5"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-surface-400 uppercase tracking-widest">Nilai</span>
                    </div>
                    <h3 class="text-3xl md:text-4xl font-extrabold text-surface-900 tracking-tight">
                        {{ $penilaianMenunggu }}</h3>
                    <div class="mt-3 text-xs text-cyan-600 font-medium">Menunggu penilaian</div>
                </div>
            </div>

            {{-- Bottom Section --}}
            <div class="mt-8 md:mt-12 grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6">

                {{-- Pending Tasks --}}
                <div class="lg:col-span-2 card-premium p-5 sm:p-6 md:p-8 animate-slide-up"
                    style="animation-delay: 200ms">
                    <div class="flex items-center gap-2.5 mb-6">
                        <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center text-amber-600">
                            <i data-lucide="list-checks" class="w-4 h-4"></i>
                        </div>
                        <h2 class="text-base md:text-lg font-bold text-surface-900">Tugas Pending</h2>
                    </div>
                    <div class="space-y-3">
                        @if($menungguValidasi > 0)
                            <div
                                class="p-4 bg-amber-50/60 rounded-xl flex items-center justify-between border border-amber-100/60">
                                <div class="flex items-center gap-3">
                                    <div class="w-2 h-2 bg-amber-400 rounded-full animate-pulse"></div>
                                    <span class="text-sm text-amber-800 font-medium">{{ $menungguValidasi }} Pengajuan PKL
                                        belum Anda tinjau</span>
                                </div>
                                <a href="{{ route('guru.pengajuan.index') }}"
                                    class="text-xs font-bold text-amber-700 hover:underline shrink-0 ml-3">Tinjau →</a>
                            </div>
                        @endif
                        @if($jurnalMenunggu > 0)
                            <div
                                class="p-4 bg-orange-50/60 rounded-xl flex items-center justify-between border border-orange-100/60">
                                <div class="flex items-center gap-3">
                                    <div class="w-2 h-2 bg-orange-400 rounded-full animate-pulse"></div>
                                    <span class="text-sm text-orange-800 font-medium">{{ $jurnalMenunggu }} Jurnal harian
                                        menunggu validasi</span>
                                </div>
                                <a href="{{ route('guru.jurnal.index') }}"
                                    class="text-xs font-bold text-orange-700 hover:underline shrink-0 ml-3">Validasi →</a>
                            </div>
                        @endif
                        @if($laporanMenunggu > 0)
                            <div
                                class="p-4 bg-emerald-50/60 rounded-xl flex items-center justify-between border border-emerald-100/60">
                                <div class="flex items-center gap-3">
                                    <div class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></div>
                                    <span class="text-sm text-emerald-800 font-medium">{{ $laporanMenunggu }} Laporan akhir
                                        menunggu review</span>
                                </div>
                                <a href="{{ route('guru.laporan.index') }}"
                                    class="text-xs font-bold text-emerald-700 hover:underline shrink-0 ml-3">Review →</a>
                            </div>
                        @endif
                        @if($penilaianMenunggu > 0)
                            <div
                                class="p-4 bg-cyan-50/60 rounded-xl flex items-center justify-between border border-cyan-100/60">
                                <div class="flex items-center gap-3">
                                    <div class="w-2 h-2 bg-cyan-400 rounded-full animate-pulse"></div>
                                    <span class="text-sm text-cyan-800 font-medium">{{ $penilaianMenunggu }} Siswa belum dinilai oleh industri</span>
                                </div>
                                <a href="{{ route('guru.penilaian.index') }}"
                                    class="text-xs font-bold text-cyan-700 hover:underline shrink-0 ml-3">Lihat →</a>
                            </div>
                        @endif
                        @if($menungguValidasi == 0 && $jurnalMenunggu == 0 && $laporanMenunggu == 0 && $penilaianMenunggu == 0)
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i data-lucide="check-circle-2" class="w-8 h-8"></i>
                                </div>
                                <p class="text-surface-400 font-medium">Semua tugas bimbingan telah selesai! 🎉</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Help Card --}}
                <div class="card-premium p-6 md:p-8 bg-gradient-to-br from-brand-600 via-brand-600 to-brand-700 text-white border-brand-500 animate-slide-up"
                    style="animation-delay: 300ms">
                    <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center mb-5">
                        <i data-lucide="life-buoy" class="w-5 h-5 text-white"></i>
                    </div>
                    <h2 class="text-lg font-bold mb-2">Butuh Bantuan?</h2>
                    <p class="text-brand-100 text-sm leading-relaxed">
                        Jika Anda mengalami kendala dalam memberikan penilaian atau validasi jurnal, silakan hubungi
                        Administrator sekolah secara langsung.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<x-app-layout>
    <div class="py-6 md:py-10 animate-fade-in">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="mb-8 md:mb-10">
                <div class="flex items-center gap-3 mb-1">
                    <div class="w-2 h-8 rounded-full bg-gradient-to-b from-purple-500 to-indigo-400"></div>
                    <h1 class="text-2xl md:text-3xl font-extrabold text-surface-900 tracking-tight">Panel Pembimbing Industri</h1>
                </div>
                <p class="text-surface-500 mt-1 ml-5 text-sm md:text-base">Selamat datang, <strong>{{ auth()->user()->name }}</strong> dari <strong>{{ $pembimbing->tempatPkl?->nama_tempat ?? '-' }}</strong>.</p>
            </div>

            {{-- Stats Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6 stagger-children">
                {{-- Total Bimbingan --}}
                <div class="stat-card">
                    <div class="flex items-center justify-between mb-4">
                        <div class="icon-box bg-purple-50 text-purple-600 group-hover:bg-purple-600 group-hover:text-white group-hover:shadow-neon">
                            <i data-lucide="users" class="w-5 h-5"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-surface-400 uppercase tracking-widest">Siswa PKL</span>
                    </div>
                    <h3 class="text-3xl md:text-4xl font-extrabold text-surface-900 tracking-tight">{{ $totalBimbingan }}</h3>
                    <div class="mt-3 text-xs text-purple-600 font-medium">Siswa terdaftar di perusahaan Anda</div>
                </div>

                {{-- Jurnal Menunggu --}}
                <div class="stat-card border-b-[3px] border-b-orange-400">
                    <div class="flex items-center justify-between mb-4">
                        <div class="icon-box bg-orange-50 text-orange-600 group-hover:bg-orange-600 group-hover:text-white">
                            <i data-lucide="book-open" class="w-5 h-5"></i>
                        </div>
                        @if($jurnalMenunggu > 0)
                            <span class="relative flex h-2.5 w-2.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-orange-500"></span>
                            </span>
                        @else
                            <span class="text-[10px] font-semibold text-surface-400 uppercase tracking-widest">Jurnal</span>
                        @endif
                    </div>
                    <h3 class="text-3xl md:text-4xl font-extrabold text-surface-900 tracking-tight">{{ $jurnalMenunggu }}</h3>
                    <div class="mt-3 text-xs text-orange-600 font-medium">Jurnal harian belum divalidasi</div>
                </div>
            </div>

            {{-- Bottom Section --}}
            <div class="mt-8 md:mt-12 grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6">

                {{-- Pending Tasks --}}
                <div class="lg:col-span-2 card-premium p-5 sm:p-6 md:p-8 animate-slide-up" style="animation-delay: 200ms">
                    <div class="flex items-center gap-2.5 mb-6">
                        <div class="w-8 h-8 rounded-lg bg-orange-50 flex items-center justify-center text-orange-600">
                            <i data-lucide="list-checks" class="w-4 h-4"></i>
                        </div>
                        <h2 class="text-base md:text-lg font-bold text-surface-900">Tugas Pending</h2>
                    </div>
                    <div class="space-y-3">
                        @if($jurnalMenunggu > 0)
                            <div class="p-4 bg-orange-50/60 rounded-xl flex items-center justify-between border border-orange-100/60">
                                <div class="flex items-center gap-3">
                                    <div class="w-2 h-2 bg-orange-400 rounded-full animate-pulse"></div>
                                    <span class="text-sm text-orange-800 font-medium">{{ $jurnalMenunggu }} Jurnal harian menunggu validasi industri</span>
                                </div>
                                <a href="{{ route('pembimbing.jurnal.index') }}" class="text-xs font-bold text-orange-700 hover:underline shrink-0 ml-3">Validasi Jurnal →</a>
                            </div>
                        @else
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i data-lucide="check-circle-2" class="w-8 h-8 text-emerald-500"></i>
                                </div>
                                <p class="text-surface-400 font-medium">Semua jurnal bimbingan telah divalidasi! 🎉</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Help Card --}}
                <div class="card-premium p-6 md:p-8 bg-gradient-to-br from-indigo-600 via-indigo-650 to-purple-700 text-white border-indigo-500 animate-slide-up" style="animation-delay: 300ms">
                    <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center mb-5">
                        <i data-lucide="briefcase" class="w-5 h-5 text-white"></i>
                    </div>
                    <h2 class="text-lg font-bold mb-2">Mitra Industri</h2>
                    <p class="text-indigo-100 text-sm leading-relaxed">
                        Terima kasih atas kontribusi Anda dalam membimbing siswa PKL SiPKL. Hubungi pihak sekolah secara langsung jika terdapat kendala operasional atau administratif.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

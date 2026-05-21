<x-app-layout>
    <div class="py-6 md:py-10 animate-fade-in">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Welcome Header --}}
            <div class="mb-8 md:mb-10">
                <div class="flex items-center gap-3 mb-1">
                    <div class="w-2 h-8 rounded-full bg-gradient-to-b from-brand-500 to-brand-400"></div>
                    <h1 class="text-2xl md:text-3xl font-extrabold text-surface-900 tracking-tight">Ringkasan Admin</h1>
                </div>
                <p class="text-surface-500 mt-1 ml-5 text-sm md:text-base">Pantau perkembangan program PKL SMK secara real-time.</p>
            </div>

            {{-- Stats Grid --}}
            <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6 stagger-children">
                {{-- Total Siswa --}}
                <div class="stat-card">
                    <div class="flex items-center justify-between mb-4">
                        <div class="icon-box bg-brand-50 text-brand-600 group-hover:bg-brand-600 group-hover:text-white group-hover:shadow-neon">
                            <i data-lucide="users" class="w-5 h-5"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-surface-400 uppercase tracking-widest">Siswa</span>
                    </div>
                    <h3 class="text-3xl md:text-4xl font-extrabold text-surface-900 tracking-tight">{{ $totalSiswa }}</h3>
                    <div class="mt-3 flex items-center text-xs text-emerald-600 font-medium">
                        <i data-lucide="trending-up" class="w-3.5 h-3.5 mr-1"></i>
                        Aktif mengikuti program
                    </div>
                </div>

                {{-- Total Guru --}}
                <div class="stat-card">
                    <div class="flex items-center justify-between mb-4">
                        <div class="icon-box bg-emerald-50 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white">
                            <i data-lucide="user-check" class="w-5 h-5"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-surface-400 uppercase tracking-widest">Guru</span>
                    </div>
                    <h3 class="text-3xl md:text-4xl font-extrabold text-surface-900 tracking-tight">{{ $totalGuru }}</h3>
                    <div class="mt-3 flex items-center text-xs text-emerald-600 font-medium">
                        <i data-lucide="shield" class="w-3.5 h-3.5 mr-1"></i>
                        Pembimbing terdaftar
                    </div>
                </div>

                {{-- Total Tempat PKL --}}
                <div class="stat-card">
                    <div class="flex items-center justify-between mb-4">
                        <div class="icon-box bg-orange-50 text-orange-600 group-hover:bg-orange-600 group-hover:text-white">
                            <i data-lucide="building-2" class="w-5 h-5"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-surface-400 uppercase tracking-widest">Mitra</span>
                    </div>
                    <h3 class="text-3xl md:text-4xl font-extrabold text-surface-900 tracking-tight">{{ $totalTempatPkl }}</h3>
                    <div class="mt-3 flex items-center text-xs text-orange-600 font-medium">
                        <i data-lucide="map-pin" class="w-3.5 h-3.5 mr-1"></i>
                        Mitra industri aktif
                    </div>
                </div>

                {{-- Total Pengajuan --}}
                <div class="stat-card">
                    <div class="flex items-center justify-between mb-4">
                        <div class="icon-box bg-blue-50 text-blue-600 group-hover:bg-blue-600 group-hover:text-white">
                            <i data-lucide="file-text" class="w-5 h-5"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-surface-400 uppercase tracking-widest">Berkas</span>
                    </div>
                    <h3 class="text-3xl md:text-4xl font-extrabold text-surface-900 tracking-tight">{{ $totalPengajuan }}</h3>
                    <div class="mt-3 flex items-center text-xs text-blue-600 font-medium">
                        <i data-lucide="clock" class="w-3.5 h-3.5 mr-1"></i>
                        Total semua berkas
                    </div>
                </div>

                {{-- Menunggu Persetujuan --}}
                <div class="stat-card border-l-[3px] border-l-amber-400">
                    <div class="flex items-center justify-between mb-4">
                        <div class="icon-box bg-amber-50 text-amber-600 group-hover:bg-amber-600 group-hover:text-white">
                            <i data-lucide="alert-circle" class="w-5 h-5"></i>
                        </div>
                        <span class="relative flex h-2.5 w-2.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-amber-500"></span>
                        </span>
                    </div>
                    <h3 class="text-3xl md:text-4xl font-extrabold text-surface-900 tracking-tight">{{ $totalMenunggu }}</h3>
                    <div class="mt-3 flex items-center text-xs text-amber-600 font-medium">
                        <i data-lucide="bell" class="w-3.5 h-3.5 mr-1"></i>
                        Perlu tindakan segera
                    </div>
                </div>

                {{-- Selesai --}}
                <div class="stat-card border-l-[3px] border-l-brand-400">
                    <div class="flex items-center justify-between mb-4">
                        <div class="icon-box bg-brand-50 text-brand-600 group-hover:bg-brand-600 group-hover:text-white">
                            <i data-lucide="check-circle" class="w-5 h-5"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-surface-400 uppercase tracking-widest">Done</span>
                    </div>
                    <h3 class="text-3xl md:text-4xl font-extrabold text-surface-900 tracking-tight">{{ $totalSelesai }}</h3>
                    <div class="mt-3 flex items-center text-xs text-brand-600 font-medium">
                        <i data-lucide="award" class="w-3.5 h-3.5 mr-1"></i>
                        Program terselesaikan
                    </div>
                </div>
            </div>

            {{-- Recent Activity --}}
            <div class="mt-8 md:mt-12 animate-slide-up" style="animation-delay: 300ms">
                <div class="card-premium p-5 sm:p-6 md:p-8">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-lg bg-brand-50 flex items-center justify-center text-brand-600">
                                <i data-lucide="activity" class="w-4 h-4"></i>
                            </div>
                            <h2 class="text-base md:text-lg font-bold text-surface-900">Aktivitas Terbaru</h2>
                        </div>
                        <span class="text-xs font-medium text-surface-400">Live</span>
                    </div>
                    <div class="space-y-4">
                        <div class="flex items-center gap-4 p-3 rounded-xl hover:bg-surface-50 transition-colors">
                            <div class="w-10 h-10 rounded-xl bg-brand-50 flex items-center justify-center text-brand-500 shrink-0">
                                <i data-lucide="user" class="w-5 h-5"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-surface-800">Siswa baru terdaftar</p>
                                <p class="text-xs text-surface-400">2 menit yang lalu</p>
                            </div>
                            <div class="w-2 h-2 rounded-full bg-emerald-400 shrink-0"></div>
                        </div>
                        <div class="flex items-center gap-4 p-3 rounded-xl hover:bg-surface-50 transition-colors">
                            <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-500 shrink-0">
                                <i data-lucide="file-check" class="w-5 h-5"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-surface-800">Pengajuan PKL disetujui</p>
                                <p class="text-xs text-surface-400">15 menit yang lalu</p>
                            </div>
                            <div class="w-2 h-2 rounded-full bg-emerald-400 shrink-0"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

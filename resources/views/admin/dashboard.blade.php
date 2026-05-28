<x-app-layout>
    <div class="py-6 md:py-10 animate-fade-in">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Welcome Header --}}
            <div class="mb-8 md:mb-10">
                <div class="flex items-center gap-3 mb-1">
                    <div class="w-2 h-8 rounded-full bg-gradient-to-b from-indigo-500 to-indigo-400"></div>
                    <h1 class="text-2xl md:text-3xl font-extrabold text-surface-900 tracking-tight">Ringkasan Admin</h1>
                </div>
                <p class="text-surface-500 mt-1 ml-5 text-sm md:text-base">Pantau perkembangan program PKL SMK secara real-time.</p>
            </div>

            {{-- Unapproved Users Banner --}}
            @if($unapprovedUsersCount > 0)
            <div class="mb-8 p-4 bg-amber-50 dark:bg-amber-950/20 rounded-2xl border border-amber-100 dark:border-amber-900/60 flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-sm animate-pulse-subtle">
                <div class="flex items-start sm:items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-100 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0">
                        <i data-lucide="user-plus" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-amber-800 dark:text-amber-200">Pendaftar Akun Baru</p>
                        <p class="text-xs text-amber-700 dark:text-amber-300 mt-0.5">Terdapat {{ $unapprovedUsersCount }} pendaftar baru yang menunggu persetujuan administrator.</p>
                    </div>
                </div>
                <a href="{{ route('admin.users.unapproved') }}" class="inline-flex items-center justify-center px-4 py-2 bg-amber-600 hover:bg-amber-500 text-white rounded-xl text-xs font-bold shrink-0 transition-colors">
                    Kelola Pendaftar
                </a>
            </div>
            @endif

            {{-- Stats Grid --}}
            <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6 stagger-children">
                {{-- Total Siswa --}}
                <div class="stat-card">
                    <div class="flex items-center justify-between mb-4">
                        <div class="icon-box bg-indigo-50 text-indigo-600 dark:bg-indigo-950/40 dark:text-indigo-400">
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
                        <div class="icon-box bg-emerald-50 text-emerald-600 dark:bg-emerald-950/40 dark:text-emerald-400">
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
                        <div class="icon-box bg-orange-50 text-orange-600 dark:bg-orange-950/40 dark:text-orange-400">
                            <i data-lucide="building" class="w-5 h-5"></i>
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
                        <div class="icon-box bg-blue-50 text-blue-600 dark:bg-blue-950/40 dark:text-blue-400">
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
                        <div class="icon-box bg-amber-50 text-amber-600 dark:bg-amber-950/40 dark:text-amber-400">
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
                <div class="stat-card border-l-[3px] border-l-indigo-400">
                    <div class="flex items-center justify-between mb-4">
                        <div class="icon-box bg-indigo-50 text-indigo-600 dark:bg-indigo-950/40 dark:text-indigo-400">
                            <i data-lucide="check-circle" class="w-5 h-5"></i>
                        </div>
                        <span class="text-[10px] font-semibold text-surface-400 uppercase tracking-widest">Selesai</span>
                    </div>
                    <h3 class="text-3xl md:text-4xl font-extrabold text-surface-900 tracking-tight">{{ $totalSelesai }}</h3>
                    <div class="mt-3 flex items-center text-xs text-indigo-600 font-medium">
                        <i data-lucide="award" class="w-3.5 h-3.5 mr-1"></i>
                        Program terselesaikan
                    </div>
                </div>
            </div>

            {{-- Dynamic Columns --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8 mt-8 md:mt-12">
                
                {{-- Recent Pengajuan --}}
                <div class="lg:col-span-2 space-y-6">
                    <div class="card-premium p-5 sm:p-6 md:p-8">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-950/40 flex items-center justify-center text-indigo-600 dark:text-indigo-400">
                                    <i data-lucide="file-text" class="w-4 h-4"></i>
                                </div>
                                <h2 class="text-base md:text-lg font-bold text-surface-900">Pengajuan Terbaru</h2>
                            </div>
                            <a href="{{ route('admin.pengajuan.index') }}" class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:underline">Lihat Semua</a>
                        </div>
                        @if($pengajuanTerbaru->count() > 0)
                        <div class="space-y-4">
                            @foreach($pengajuanTerbaru as $item)
                            @php
                                $statusBadgeColor = match($item->status) {
                                    'draft' => 'bg-gray-100 text-gray-800 dark:bg-zinc-800 dark:text-zinc-300',
                                    'menunggu_persetujuan' => 'bg-amber-50 text-amber-700 border-amber-100 dark:bg-amber-950/30 dark:text-amber-400 dark:border-amber-900/30',
                                    'disetujui' => 'bg-emerald-50 text-emerald-700 border-emerald-100 dark:bg-emerald-950/30 dark:text-emerald-400 dark:border-emerald-900/30',
                                    'sedang_pkl' => 'bg-indigo-50 text-indigo-700 border-indigo-100 dark:bg-indigo-950/30 dark:text-indigo-400 dark:border-indigo-900/30',
                                    'selesai' => 'bg-sky-50 text-sky-700 border-sky-100 dark:bg-sky-950/30 dark:text-sky-400 dark:border-sky-900/30',
                                    default => 'bg-rose-50 text-rose-700 border-rose-100 dark:bg-rose-950/30 dark:text-rose-400 dark:border-rose-900/30',
                                };
                            @endphp
                            <div class="flex items-center gap-4 p-3 rounded-xl hover:bg-surface-50 dark:hover:bg-zinc-800/40 transition-colors">
                                <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-950/30 flex items-center justify-center text-indigo-500 shrink-0">
                                    <i data-lucide="user" class="w-5 h-5"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-surface-800">{{ $item->siswa?->user?->name ?? 'Siswa' }}</p>
                                    <p class="text-xs text-surface-400 mt-0.5 truncate">{{ $item->tempatPkl?->nama_tempat ?? '-' }}</p>
                                </div>
                                <div class="flex flex-col items-end gap-1.5 shrink-0">
                                    <span class="inline-flex items-center rounded-md px-1.5 py-0.5 text-[9px] font-bold ring-1 ring-inset {{ $statusBadgeColor }} capitalize">
                                        {{ str_replace('_', ' ', $item->status) }}
                                    </span>
                                    <span class="text-[9px] text-gray-400 dark:text-zinc-500">{{ $item->updated_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="py-8 text-center">
                            <p class="text-sm text-surface-400">Belum ada pengajuan.</p>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Right Column Stats --}}
                <div class="space-y-6">
                    <!-- Quota Monitoring -->
                    <div class="card-premium p-5 sm:p-6">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-8 h-8 rounded-lg bg-orange-50 dark:bg-orange-950/40 flex items-center justify-center text-orange-600">
                                <i data-lucide="building" class="w-4 h-4"></i>
                            </div>
                            <h2 class="text-sm font-bold text-surface-900">Kapasitas Tempat PKL</h2>
                        </div>
                        <div class="space-y-4">
                            @forelse($tempatPklList as $tempat)
                                @php
                                    $occupied = $tempat->pengajuan_pkl_count;
                                    $total = $tempat->kuota;
                                    $percentage = $total > 0 ? min(100, round(($occupied / $total) * 100)) : 0;
                                    $barColor = $percentage >= 90 ? 'bg-rose-500' : ($percentage >= 70 ? 'bg-amber-500' : 'bg-indigo-600');
                                @endphp
                                <div>
                                    <div class="flex justify-between items-center text-xs mb-1">
                                        <span class="font-semibold text-gray-800 dark:text-zinc-200 truncate max-w-[150px]">{{ $tempat->nama_tempat }}</span>
                                        <span class="text-gray-400 dark:text-zinc-500">{{ $occupied }}/{{ $total }} Kuota</span>
                                    </div>
                                    <div class="w-full bg-gray-100 dark:bg-zinc-800 rounded-full h-1.5 overflow-hidden">
                                        <div class="h-full rounded-full {{ $barColor }}" style="width: {{ $percentage }}%"></div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-xs text-surface-400 text-center py-4">Belum ada data kapasitas.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Bidang Usaha Stats -->
                    <div class="card-premium p-5 sm:p-6">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-950/40 flex items-center justify-center text-indigo-600">
                                <i data-lucide="pie-chart" class="w-4 h-4"></i>
                            </div>
                            <h2 class="text-sm font-bold text-surface-900">Bidang Usaha Mitra</h2>
                        </div>
                        <div class="space-y-3">
                            @forelse($bidangUsahaStats as $stat)
                                <div class="flex items-center justify-between p-2 rounded-lg bg-gray-50 dark:bg-zinc-800/40 border border-gray-100 dark:border-zinc-800/60">
                                    <span class="text-xs font-semibold text-gray-700 dark:text-zinc-300 capitalize truncate max-w-[160px]">{{ $stat->bidang_usaha ?: 'Lainnya' }}</span>
                                    <span class="inline-flex items-center rounded-md bg-indigo-50 dark:bg-indigo-950/30 px-2 py-0.5 text-xs font-bold text-indigo-700 dark:text-indigo-400">
                                        {{ $stat->total }} Mitra
                                    </span>
                                </div>
                            @empty
                                <p class="text-xs text-surface-400 text-center py-4">Belum ada data bidang usaha.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>

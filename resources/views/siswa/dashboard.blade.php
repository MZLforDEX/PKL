<x-app-layout>
    <div class="py-6 md:py-10 animate-fade-in">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Welcome Header --}}
            <div class="mb-6 md:mb-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-3 mb-1">
                        <div class="w-2 h-8 rounded-full bg-gradient-to-b from-brand-500 to-cyan-400"></div>
                        <h1 class="text-2xl md:text-3xl font-extrabold text-surface-900 tracking-tight">Halo, {{ Auth::user()->name }}! 👋</h1>
                    </div>
                    <p class="text-surface-500 mt-1 ml-5 text-sm md:text-base">Selamat datang kembali di portal PKL Anda.</p>
                </div>
                @if(!$pengajuan)
                    <a href="{{ route('siswa.pengajuan.create') }}" class="btn-primary">
                        <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                        Buat Pengajuan
                    </a>
                @endif
            </div>

            @if($pengajuan)
                {{-- Statistik Kehadiran & Progres PKL --}}
                <div class="card-premium p-5 sm:p-6 md:p-8 mb-6 md:mb-8 animate-slide-up">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 gap-4">
                        <div>
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-lg bg-brand-50 flex items-center justify-center text-brand-600">
                                    <i data-lucide="bar-chart-3" class="w-4.5 h-4.5"></i>
                                </div>
                                <h2 class="text-base md:text-lg font-bold text-surface-900">Statistik & Progres PKL</h2>
                            </div>
                            <div class="flex flex-wrap items-center gap-2 mt-2.5 ml-[42px] text-xs md:text-sm text-surface-500">
                                <span>Periode: <span class="font-semibold text-surface-700">{{ $periode }}</span></span>
                                <span class="text-surface-300">•</span>
                                <span>Status:</span>
                                @php
                                    $statusColor = match($pengajuan->status) {
                                        'draft' => 'text-surface-500 bg-surface-100 border-surface-200',
                                        'menunggu_persetujuan', 'menunggu_penilaian' => 'text-amber-700 bg-amber-50 border-amber-200',
                                        'disetujui', 'sedang_pkl' => 'text-blue-700 bg-blue-50 border-blue-200',
                                        'selesai' => 'text-emerald-700 bg-emerald-50 border-emerald-200',
                                        'ditolak', 'revisi' => 'text-rose-700 bg-rose-50 border-rose-200',
                                        default => 'text-surface-500 bg-surface-100 border-surface-200',
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider border {{ $statusColor }}">
                                    {{ str_replace('_', ' ', ucwords($pengajuan->status)) }}
                                </span>
                            </div>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-brand-50 flex items-center justify-center text-brand-600 shadow-[0_2px_8px_rgba(99,102,241,0.15)] self-end sm:self-center">
                            <i data-lucide="trending-up" class="w-5 h-5"></i>
                        </div>
                    </div>

                    @php
                        $displayProgress = match($pengajuan->status) {
                            'draft' => 10,
                            'menunggu_persetujuan' => 20,
                            'revisi' => 15,
                            'disetujui' => 35,
                            'sedang_pkl', 'menunggu_penilaian' => $progressPersen,
                            'selesai' => 100,
                            default => 5,
                        };
                    @endphp

                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-xs font-semibold text-surface-500">Total Progres</span>
                            <span class="text-xs font-bold text-brand-600 bg-brand-50 px-2 py-0.5 rounded-md">{{ $displayProgress }}% Selesai</span>
                        </div>
                        <div class="overflow-hidden h-2.5 rounded-full bg-surface-100">
                            <div style="width: {{ $displayProgress }}%" class="h-full rounded-full bg-gradient-to-r from-brand-600 to-brand-400 transition-all duration-700 ease-out shadow-[0_0_8px_rgba(99,102,241,0.4)]"></div>
                        </div>
                    </div>

                    <hr class="border-surface-200/60 my-5" />

                    <div class="grid grid-cols-2 gap-4 pt-2">
                        <div>
                            <p class="text-xs font-bold text-surface-400 uppercase tracking-wider">Kehadiran</p>
                            <div class="flex items-baseline gap-1 mt-1.5">
                                <span class="text-2xl md:text-3xl font-extrabold text-surface-900 tracking-tight">{{ $jmlValidJurnal }}</span>
                                <span class="text-xs md:text-sm font-medium text-surface-400">/ {{ $totalHari }} Hari</span>
                            </div>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-surface-400 uppercase tracking-wider">Jurnal Terisi</p>
                            <div class="flex items-baseline gap-1 mt-1.5">
                                <span class="text-2xl md:text-3xl font-extrabold text-surface-900 tracking-tight">{{ $jmlJurnal }}</span>
                                <span class="text-xs md:text-sm font-medium text-surface-400">Entri</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Info Grid --}}
                <div class="grid grid-cols-2 gap-4 md:gap-6 stagger-children">
                    {{-- Company Card --}}
                    <div class="stat-card">
                        <div class="icon-box bg-blue-50 text-blue-600 mb-3">
                            <i data-lucide="building" class="w-5 h-5"></i>
                        </div>
                        <h3 class="text-xs font-semibold text-surface-400 uppercase tracking-wider">Tempat PKL</h3>
                        <p class="text-base md:text-lg font-bold text-surface-900 mt-1.5 leading-tight">{{ $pengajuan->tempatPkl->nama_tempat ?? '-' }}</p>
                        <p class="text-xs text-surface-400 mt-2.5 flex items-center">
                            <i data-lucide="map-pin" class="w-3 h-3 mr-1 shrink-0"></i>
                            <span class="truncate">{{ $pengajuan->tempatPkl->alamat ?? 'Lokasi belum diatur' }}</span>
                        </p>
                    </div>

                    {{-- Mentor Card --}}
                    <div class="stat-card">
                        <div class="icon-box bg-emerald-50 text-emerald-600 mb-3">
                            <i data-lucide="user-check" class="w-5 h-5"></i>
                        </div>
                        <h3 class="text-xs font-semibold text-surface-400 uppercase tracking-wider">Guru Pembimbing</h3>
                        <p class="text-base md:text-lg font-bold text-surface-900 mt-1.5 leading-tight">{{ $pengajuan->guru?->user?->name ?? '-' }}</p>
                        <p class="text-xs text-surface-400 mt-2.5 flex items-center">
                            <i data-lucide="mail" class="w-3 h-3 mr-1 shrink-0"></i>
                            <span class="truncate">{{ $pengajuan->guru?->user?->email ?? 'Email belum diatur' }}</span>
                        </p>
                    </div>

                    {{-- Journal Stats --}}
                    <div class="stat-card">
                        <div class="icon-box bg-orange-50 text-orange-600 mb-3">
                            <i data-lucide="book-open" class="w-5 h-5"></i>
                        </div>
                        <h3 class="text-xs font-semibold text-surface-400 uppercase tracking-wider">Jumlah Jurnal</h3>
                        <p class="text-3xl md:text-4xl font-extrabold text-surface-900 mt-1.5 tracking-tight">{{ $jmlJurnal }}</p>
                        <a href="{{ route('siswa.jurnal.index') }}" class="text-xs text-brand-600 mt-3 font-semibold flex items-center hover:underline">
                            Lihat Jurnal <i data-lucide="chevron-right" class="w-3 h-3 ml-1"></i>
                        </a>
                    </div>

                    {{-- Final Grade --}}
                    <div class="stat-card @if($pengajuan->status === 'selesai') bg-gradient-to-br from-brand-50 to-white border-brand-200 @endif">
                        <div class="icon-box bg-brand-100 text-brand-700 mb-3">
                            <i data-lucide="award" class="w-5 h-5"></i>
                        </div>
                        <h3 class="text-xs font-semibold text-surface-400 uppercase tracking-wider">Nilai Akhir</h3>
                        <p class="text-3xl md:text-4xl font-extrabold text-surface-900 mt-1.5 tracking-tight">{{ $pengajuan->penilaianPkl->nilai_akhir ?? '-' }}</p>
                        @if($pengajuan->status === 'selesai')
                            <div class="mt-4">
                                <a href="{{ route('siswa.pengajuan.sertifikat', $pengajuan) }}" target="_blank" class="btn-primary w-full text-xs !py-2.5">
                                    <i data-lucide="printer" class="w-3.5 h-3.5 mr-1.5"></i>
                                    Cetak Sertifikat
                                </a>
                            </div>
                        @elseif($pengajuan->penilaianPkl)
                            <p class="text-xs text-emerald-600 mt-3 font-semibold flex items-center">
                                <i data-lucide="star" class="w-3 h-3 mr-1"></i>
                                {{ $pengajuan->penilaianPkl->predikat }}
                            </p>
                        @endif
                    </div>
                </div>
            @else
                {{-- Empty State --}}
                <div class="card-premium p-8 md:p-16 text-center animate-scale-in">
                    <div class="empty-state-icon mx-auto">
                        <i data-lucide="clipboard-list" class="w-10 h-10"></i>
                    </div>
                    <h2 class="text-xl md:text-2xl font-extrabold text-surface-900 mt-2">Belum Ada Pengajuan</h2>
                    <p class="text-surface-500 mt-3 max-w-md mx-auto text-sm leading-relaxed">Anda belum membuat pengajuan PKL. Segera pilih tempat PKL impianmu dan mulai pengalaman kerjamu!</p>
                    <div class="mt-8">
                        <a href="{{ route('siswa.pengajuan.create') }}" class="btn-primary text-base !px-8 !py-3">
                            <i data-lucide="plus" class="w-5 h-5 mr-2"></i>
                            Buat Pengajuan Sekarang
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

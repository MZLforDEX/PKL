<x-app-layout>
    <div class="py-6 md:py-10 animate-fade-in">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Student Profile & Welcome Banner --}}
            <div class="card-premium p-6 mb-6 md:mb-8 overflow-hidden relative bg-dots">
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-brand-500/10 rounded-full blur-2xl"></div>
                <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-cyan-500/10 rounded-full blur-2xl"></div>
                
                <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-full bg-gradient-to-tr from-brand-600 to-cyan-500 flex items-center justify-center text-white text-2xl font-black shadow-neon shrink-0">
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-brand-600 bg-brand-550/10 dark:bg-brand-500/20 px-2.5 py-1 rounded uppercase tracking-wider">
                                Siswa PKL
                            </span>
                            <h1 class="text-xl md:text-2xl font-extrabold text-surface-900 mt-1 tracking-tight">
                                {{ Auth::user()->name }}
                            </h1>
                            <p class="text-xs md:text-sm text-surface-500 mt-0.5">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 md:gap-6 border-t md:border-t-0 md:border-l border-surface-200/60 pt-4 md:pt-0 md:pl-6">
                        <div>
                            <span class="text-[10px] font-bold text-surface-400 uppercase tracking-wider block">NIS</span>
                            <p class="text-sm font-semibold text-surface-700 mt-0.5">{{ $siswa->nis ?? '-' }}</p>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-surface-400 uppercase tracking-wider block">Kelas</span>
                            <p class="text-sm font-semibold text-surface-700 mt-0.5">{{ $siswa->kelas ?? '-' }}</p>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-surface-400 uppercase tracking-wider block">Jurusan</span>
                            <p class="text-sm font-semibold text-surface-700 mt-0.5">{{ $siswa->jurusan ?? '-' }}</p>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-surface-400 uppercase tracking-wider block">No. HP</span>
                            <p class="text-sm font-semibold text-surface-700 mt-0.5">{{ $siswa->no_hp ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Journey Stepper --}}
            @php
                $currentStatus = $pengajuan?->status ?? 'belum_pengajuan';
                
                $steps = [
                    ['key' => 'pengajuan', 'label' => 'Pengajuan', 'desc' => 'Review & Approval'],
                    ['key' => 'disetujui', 'label' => 'Disetujui', 'desc' => 'Tempat Diterima'],
                    ['key' => 'sedang_pkl', 'label' => 'Sedang PKL', 'desc' => 'Absensi & Jurnal'],
                    ['key' => 'penilaian', 'label' => 'Penilaian', 'desc' => 'Input Nilai'],
                    ['key' => 'selesai', 'label' => 'Selesai', 'desc' => 'Cetak Sertifikat'],
                ];

                $activeStepIndex = 0;
                if ($currentStatus === 'disetujui') {
                    $activeStepIndex = 1;
                } elseif ($currentStatus === 'sedang_pkl') {
                    $activeStepIndex = 2;
                } elseif ($currentStatus === 'menunggu_penilaian') {
                    $activeStepIndex = 3;
                } elseif ($currentStatus === 'selesai') {
                    $activeStepIndex = 4;
                } else {
                    $activeStepIndex = 0;
                }
            @endphp
            <div class="card-premium p-5 sm:p-6 mb-6 md:mb-8">
                <h3 class="text-xs font-bold text-surface-400 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <i data-lucide="milestone" class="w-4 h-4 text-brand-600"></i> Alur Perjalanan PKL Anda
                </h3>
                
                <div class="relative">
                    <!-- Connecting Line -->
                    <div class="absolute top-5 left-6 right-6 hidden md:block h-0.5 bg-surface-200">
                        <div class="h-full bg-gradient-to-r from-brand-500 to-cyan-400 transition-all duration-500" style="width: {{ ($activeStepIndex / 4) * 100 }}%"></div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-6 md:gap-2 relative z-10">
                        @foreach($steps as $idx => $step)
                            @php
                                $isCompleted = $idx < $activeStepIndex;
                                $isActive = $idx === $activeStepIndex;
                                $isUpcoming = $idx > $activeStepIndex;
                                
                                if ($isActive) {
                                    $circleClass = 'bg-brand-600 text-white ring-4 ring-brand-100 dark:ring-brand-500/20';
                                    $textClass = 'text-brand-600 font-bold';
                                } elseif ($isCompleted) {
                                    $circleClass = 'bg-cyan-500 text-white';
                                    $textClass = 'text-surface-700 font-semibold';
                                } else {
                                    $circleClass = 'bg-surface-100 text-surface-400 border border-surface-200';
                                    $textClass = 'text-surface-400 font-medium';
                                }
                            @endphp
                            
                            <div class="flex md:flex-col items-center md:text-center gap-3 md:gap-2">
                                <!-- Step Circle -->
                                <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 font-bold text-xs {{ $circleClass }}">
                                    @if($isCompleted)
                                        <i data-lucide="check" class="w-5 h-5"></i>
                                    @else
                                        {{ $idx + 1 }}
                                    @endif
                                </div>
                                
                                <!-- Step Text -->
                                <div class="flex flex-col">
                                    <span class="text-xs sm:text-sm {{ $textClass }} leading-tight">{{ $step['label'] }}</span>
                                    <span class="text-[10px] text-surface-400 mt-0.5">{{ $step['desc'] }}</span>
                                    
                                    @if($idx === 0 && in_array($currentStatus, ['revisi', 'ditolak', 'menunggu_persetujuan']))
                                        <div class="mt-1">
                                            @if($currentStatus === 'revisi')
                                                <span class="px-1.5 py-0.5 text-[9px] font-bold text-amber-700 bg-amber-50 rounded border border-amber-200">Revisi</span>
                                            @elseif($currentStatus === 'ditolak')
                                                <span class="px-1.5 py-0.5 text-[9px] font-bold text-rose-700 bg-rose-50 rounded border border-rose-200">Ditolak</span>
                                            @elseif($currentStatus === 'menunggu_persetujuan')
                                                <span class="px-1.5 py-0.5 text-[9px] font-bold text-blue-700 bg-blue-50 rounded border border-blue-200">Review</span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            @if($pengajuan)
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8 stagger-children">
                    
                    {{-- Left / Center Column: Stats & Tables (col-span 2) --}}
                    <div class="lg:col-span-2 space-y-6 md:space-y-8">
                        

                        {{-- Jurnal Terbaru --}}
                        <div class="card-premium overflow-hidden">
                            <div class="p-5 border-b border-surface-100 flex items-center justify-between">
                                <h3 class="text-base font-bold text-surface-900 flex items-center gap-2">
                                    <i data-lucide="book-open" class="w-5 h-5 text-orange-500"></i>
                                    Jurnal Terbaru Anda
                                </h3>
                                <a href="{{ route('siswa.jurnal.index') }}" class="text-xs font-semibold text-brand-600 hover:underline flex items-center gap-0.5">
                                    Lihat Semua <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                                </a>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="table-premium">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Kegiatan</th>
                                            <th>Status</th>
                                            <th>Catatan Guru</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($jurnalTerbaru as $j)
                                            <tr>
                                                <td class="whitespace-nowrap font-semibold text-surface-800 text-xs">
                                                    {{ \Carbon\Carbon::parse($j->tanggal)->format('d M Y') }}
                                                </td>
                                                <td class="max-w-[180px] sm:max-w-xs truncate text-xs text-surface-600" title="{{ $j->kegiatan }}">
                                                    {{ $j->kegiatan }}
                                                </td>
                                                <td>
                                                    @php
                                                        $jColor = match($j->status) {
                                                            'valid' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                                            'revisi' => 'bg-rose-50 text-rose-600 border-rose-100',
                                                            default => 'bg-amber-50 text-amber-600 border border-amber-100',
                                                        };
                                                    @endphp
                                                    <span class="status-badge {{ $jColor }}">
                                                        {{ str_replace('_', ' ', $j->status) }}
                                                    </span>
                                                </td>
                                                <td class="max-w-[150px] truncate text-xs text-surface-500 italic" title="{{ $j->catatan_guru }}">
                                                    {{ $j->catatan_guru ?: '-' }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center">
                                                    <div class="py-6 text-surface-400 text-xs">
                                                        Belum ada jurnal yang diinput.
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- Absensi Terbaru --}}
                        <div class="card-premium overflow-hidden">
                            <div class="p-5 border-b border-surface-100 flex items-center justify-between">
                                <h3 class="text-base font-bold text-surface-900 flex items-center gap-2">
                                    <i data-lucide="fingerprint" class="w-5 h-5 text-purple-500"></i>
                                    Absensi Terbaru Anda
                                </h3>
                                <a href="{{ route('siswa.absensi.index') }}" class="text-xs font-semibold text-brand-600 hover:underline flex items-center gap-0.5">
                                    Lihat Semua <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                                </a>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="table-premium">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Jam</th>
                                            <th>Selfie</th>
                                            <th>Lokasi</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($absensiTerbaru as $ab)
                                            <tr>
                                                <td class="whitespace-nowrap font-semibold text-surface-800 text-xs">
                                                    {{ \Carbon\Carbon::parse($ab->tanggal)->format('d M Y') }}
                                                </td>
                                                <td class="font-mono text-xs text-surface-600">
                                                    {{ $ab->jam_masuk }}
                                                </td>
                                                <td>
                                                    <div class="w-8 h-8 rounded overflow-hidden border border-surface-200 hover:scale-110 transition-transform">
                                                        <a href="{{ asset('storage/' . $ab->foto_selfie) }}" target="_blank">
                                                            <img src="{{ asset('storage/' . $ab->foto_selfie) }}" class="w-full h-full object-cover">
                                                        </a>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($ab->latitude && $ab->longitude)
                                                        <a href="https://www.google.com/maps?q={{ $ab->latitude }},{{ $ab->longitude }}" target="_blank" class="inline-flex items-center text-[10px] text-brand-600 hover:text-brand-700 font-semibold gap-0.5">
                                                            <i data-lucide="map-pin" class="w-3 h-3 text-emerald-500"></i>
                                                            Maps
                                                        </a>
                                                    @else
                                                        <span class="text-[10px] text-surface-400">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="status-badge {{ $ab->status === 'hadir' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-rose-50 text-rose-600 border border-rose-100' }}">
                                                        {{ ucfirst($ab->status) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">
                                                    <div class="py-6 text-surface-400 text-xs">
                                                        Belum ada data absensi.
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>

                    {{-- Right Column: Sidebar (col-span 1) --}}
                    <div class="space-y-6 md:space-y-8">
                        
                        {{-- Quick Attendance --}}
                        @if(in_array($pengajuan->status, ['sedang_pkl', 'disetujui']))
                            <div class="card-premium p-5 overflow-hidden relative">
                                <h3 class="text-sm font-bold text-surface-900 mb-3.5 flex items-center gap-2">
                                    <i data-lucide="calendar-check-2" class="w-[18px] h-[18px] text-brand-500"></i>
                                    Kehadiran Hari Ini
                                </h3>
                                @if($absensiHariIni)
                                    <div class="p-4 bg-emerald-50/60 border border-emerald-100 rounded-xl flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-emerald-500 text-white flex items-center justify-center shadow shrink-0">
                                            <i data-lucide="check" class="w-5 h-5"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-emerald-800 text-xs">Sudah Absen</h4>
                                            <p class="text-[10px] text-emerald-600 font-mono mt-0.5">Jam Masuk: {{ $absensiHariIni->jam_masuk }} WIB</p>
                                        </div>
                                    </div>
                                @else
                                    <div class="p-4 bg-amber-50/70 border border-amber-100 rounded-xl mb-3 flex items-start gap-3">
                                        <div class="w-9 h-9 rounded-full bg-amber-500 text-white flex items-center justify-center shrink-0 animate-pulse-soft">
                                            <i data-lucide="alert-triangle" class="w-5 h-5"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-amber-800 text-xs">Belum Absen</h4>
                                            <p class="text-[10px] text-amber-600 mt-0.5 leading-relaxed">Harap lakukan absensi masuk hari ini sebelum jam 08:00 WIB.</p>
                                        </div>
                                    </div>
                                    <a href="{{ route('siswa.absensi.index') }}" class="btn-primary w-full text-xs py-2.5 flex items-center justify-center gap-2">
                                        <i data-lucide="fingerprint" class="w-4 h-4"></i> Lakukan Absensi Sekarang
                                    </a>
                                @endif
                            </div>
                        @else
                            <div class="card-premium p-5 bg-surface-50">
                                <h3 class="text-sm font-bold text-surface-900 mb-2 flex items-center gap-2">
                                    <i data-lucide="calendar-check-2" class="w-[18px] h-[18px] text-surface-400"></i>
                                    Kehadiran Hari Ini
                                </h3>
                                <p class="text-xs text-surface-400 leading-relaxed">
                                    Fitur absensi harian akan diaktifkan setelah pengajuan PKL Anda disetujui.
                                </p>
                            </div>
                        @endif

                        {{-- Tempat PKL --}}
                        <div class="card-premium p-5">
                            <h3 class="text-xs font-bold text-surface-400 uppercase tracking-wider mb-4 flex items-center gap-2">
                                <i data-lucide="building" class="w-4 h-4 text-blue-500"></i> Tempat PKL
                            </h3>
                            <div>
                                <h4 class="text-base font-bold text-surface-900 leading-snug">
                                    {{ $pengajuan->tempatPkl?->nama_tempat ?? '-' }}
                                </h4>
                                <p class="text-xs text-surface-500 mt-1.5 flex items-start">
                                    <i data-lucide="map-pin" class="w-3.5 h-3.5 mr-1.5 text-surface-400 shrink-0 mt-0.5"></i>
                                    <span>{{ $pengajuan->tempatPkl?->alamat ?? 'Lokasi belum diatur' }}</span>
                                </p>
                                @if($pengajuan->tempatPkl?->alamat)
                                    <a href="https://www.google.com/maps?q={{ urlencode(($pengajuan->tempatPkl?->nama_tempat ?? '') . ' ' . ($pengajuan->tempatPkl?->alamat ?? '')) }}" target="_blank" class="mt-3 inline-flex items-center text-xs text-brand-600 hover:text-brand-700 font-semibold gap-1">
                                        <i data-lucide="map" class="w-3.5 h-3.5 text-emerald-500"></i>
                                        Buka Google Maps
                                    </a>
                                @endif
                            </div>
                        </div>

                        {{-- Guru Pembimbing --}}
                        <div class="card-premium p-5">
                            <h3 class="text-xs font-bold text-surface-400 uppercase tracking-wider mb-4 flex items-center gap-2">
                                <i data-lucide="user-check" class="w-4 h-4 text-emerald-500"></i> Guru Pembimbing
                            </h3>
                            <div>
                                <h4 class="text-base font-bold text-surface-900 leading-snug">
                                    {{ $pengajuan->guru?->user?->name ?? '-' }}
                                </h4>
                                <p class="text-xs text-surface-500 mt-1.5 flex items-center">
                                    <i data-lucide="mail" class="w-3.5 h-3.5 mr-1.5 text-surface-400 shrink-0"></i>
                                    <span class="truncate">{{ $pengajuan->guru?->user?->email ?? 'Email belum diatur' }}</span>
                                </p>
                                @if($pengajuan->guru?->user?->email)
                                    <a href="mailto:{{ $pengajuan->guru?->user?->email }}" class="mt-3 inline-flex items-center text-xs text-brand-600 hover:text-brand-700 font-semibold gap-1">
                                        <i data-lucide="message-square" class="w-3.5 h-3.5 text-brand-500"></i>
                                        Hubungi Guru
                                    </a>
                                @endif
                            </div>
                        </div>

                        {{-- Penilaian & Sertifikat --}}
                        <div class="card-premium p-5 overflow-hidden">
                            <h3 class="text-xs font-bold text-surface-400 uppercase tracking-wider mb-4 flex items-center gap-2">
                                <i data-lucide="award" class="w-4 h-4 text-brand-500"></i> Penilaian Akhir
                            </h3>
                            @if($pengajuan->penilaianPkl)
                                <div class="space-y-4">
                                    <div class="flex items-center justify-between pb-3 border-b border-surface-100">
                                        <div>
                                            <span class="text-[10px] font-bold text-surface-400 uppercase block">Nilai Akhir</span>
                                            <span class="text-3xl font-black text-brand-600 tracking-tight">{{ number_format($pengajuan->penilaianPkl->nilai_akhir, 2) }}</span>
                                        </div>
                                        <span class="px-2.5 py-1 rounded text-xs font-bold bg-emerald-50 text-emerald-600 border border-emerald-100 dark:bg-emerald-500/20 dark:text-emerald-400">
                                            {{ $pengajuan->penilaianPkl->predikat }}
                                        </span>
                                    </div>

                                    @if($pengajuan->penilaianPkl->detail_nilai)
                                        <div class="space-y-2 py-1.5">
                                            @foreach($pengajuan->penilaianPkl->detail_nilai as $item)
                                                <div class="flex justify-between items-center text-xs font-semibold p-2 bg-surface-50 border border-surface-100 rounded-lg">
                                                    <span class="text-surface-600 truncate mr-2" title="{{ $item['nama'] }}">{{ $item['nama'] }}</span>
                                                    <span class="text-brand-600 font-extrabold shrink-0">{{ $item['nilai'] }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="grid grid-cols-3 gap-2 py-1.5 text-center">
                                            <div class="p-2 bg-surface-50 border border-surface-100 rounded-lg">
                                                <span class="text-[9px] font-bold text-surface-400 uppercase block">Sikap</span>
                                                <span class="text-xs font-extrabold text-surface-700 mt-1 block">{{ $pengajuan->penilaianPkl->nilai_sikap }}</span>
                                            </div>
                                            <div class="p-2 bg-surface-50 border border-surface-100 rounded-lg">
                                                <span class="text-[9px] font-bold text-surface-400 uppercase block">Keterampilan</span>
                                                <span class="text-xs font-extrabold text-surface-700 mt-1 block">{{ $pengajuan->penilaianPkl->nilai_keterampilan }}</span>
                                            </div>
                                            <div class="p-2 bg-surface-50 border border-surface-100 rounded-lg">
                                                <span class="text-[9px] font-bold text-surface-400 uppercase block">Laporan</span>
                                                <span class="text-xs font-extrabold text-surface-700 mt-1 block">{{ $pengajuan->penilaianPkl->nilai_laporan }}</span>
                                            </div>
                                        </div>
                                    @endif

                                    @if($pengajuan->penilaianPkl->catatan_evaluasi)
                                        <div class="p-3 bg-brand-50/50 border border-brand-100/60 rounded-lg">
                                            <span class="text-[9px] font-bold text-brand-600 uppercase block mb-1">Catatan Evaluasi:</span>
                                            <p class="text-xs text-surface-600 italic leading-relaxed">
                                                "{{ $pengajuan->penilaianPkl->catatan_evaluasi }}"
                                            </p>
                                        </div>
                                    @endif

                                    @if($pengajuan->status === 'selesai')
                                        <a href="{{ route('siswa.pengajuan.sertifikat', $pengajuan) }}" target="_blank" class="btn-primary w-full text-xs !py-2.5 flex items-center justify-center gap-1.5 mt-2">
                                            <i data-lucide="printer" class="w-4 h-4"></i> Cetak Sertifikat
                                        </a>
                                    @endif
                                </div>
                            @else
                                <div class="text-center py-5 text-surface-400">
                                    <div class="w-10 h-10 rounded-full bg-surface-100 flex items-center justify-center mx-auto mb-2 text-surface-300">
                                        <i data-lucide="award" class="w-5 h-5"></i>
                                    </div>
                                    <p class="text-xs font-medium">Nilai belum dimasukkan oleh Pembimbing Industri.</p>
                                </div>
                            @endif
                        </div>

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

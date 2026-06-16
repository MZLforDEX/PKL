<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('pembimbing.siswa.index') }}" class="p-2 rounded-lg hover:bg-surface-100 text-surface-400 hover:text-surface-600 transition-colors">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <h2 class="font-extrabold text-lg md:text-2xl text-surface-900 tracking-tight">Detail Siswa PKL</h2>
        </div>
    </x-slot>

    <div class="py-6 md:py-10 animate-fade-in">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Siswa Information -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="card-premium p-6 md:p-8">
                        <div class="flex items-start justify-between mb-6 pb-6 border-b border-surface-100">
                            <div class="flex items-center">
                                <div class="w-12 h-12 md:w-16 md:h-16 rounded-full bg-gradient-to-br from-purple-500 to-indigo-500 flex items-center justify-center text-white font-bold text-lg md:text-2xl mr-4 shadow-md">
                                    {{ substr($pengajuanPkl->siswa?->user?->name ?? '-', 0, 1) }}
                                </div>
                                <div>
                                    <h3 class="text-lg md:text-xl font-bold text-surface-900">{{ $pengajuanPkl->siswa?->user?->name ?? '-' }}</h3>
                                    <p class="text-sm text-surface-500">NIS: {{ $pengajuanPkl->siswa?->nis ?? '-' }} • Kelas: {{ $pengajuanPkl->siswa?->kelas ?? '-' }}</p>
                                </div>
                            </div>
                            @php
                                $statusClasses = [
                                    'disetujui' => 'bg-indigo-50 text-indigo-600 border border-indigo-100',
                                    'sedang_pkl' => 'bg-emerald-50 text-emerald-600 border border-emerald-100',
                                    'menunggu_penilaian' => 'bg-cyan-50 text-cyan-600 border border-cyan-100',
                                    'selesai' => 'bg-blue-50 text-blue-600 border border-blue-100',
                                ];
                                $class = $statusClasses[$pengajuanPkl->status] ?? 'bg-slate-100 text-slate-600';
                            @endphp
                            <span class="status-badge {{ $class }}">
                                {{ str_replace('_', ' ', $pengajuanPkl->status) }}
                            </span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="text-[10px] font-bold text-surface-400 uppercase tracking-widest block mb-1">Jurusan</label>
                                <span class="text-sm font-semibold text-surface-800">{{ $pengajuanPkl->siswa?->jurusan ?? '-' }}</span>
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-surface-400 uppercase tracking-widest block mb-1">No. HP Siswa</label>
                                <span class="text-sm font-semibold text-surface-800">{{ $pengajuanPkl->siswa?->no_hp ?? '-' }}</span>
                            </div>
                            <div class="md:col-span-2">
                                <label class="text-[10px] font-bold text-surface-400 uppercase tracking-widest block mb-1">Alamat Siswa</label>
                                <span class="text-sm text-surface-700 block mt-0.5 leading-relaxed">{{ $pengajuanPkl->siswa?->alamat ?? '-' }}</span>
                            </div>
                            <div class="pt-4 border-t border-surface-100 md:col-span-2"></div>
                            <div>
                                <label class="text-[10px] font-bold text-surface-400 uppercase tracking-widest block mb-1">Periode PKL</label>
                                <div class="flex items-center text-sm text-surface-800 font-semibold mt-1">
                                    <i data-lucide="calendar" class="w-4 h-4 mr-2 text-indigo-500"></i>
                                    {{ \Carbon\Carbon::parse($pengajuanPkl->tanggal_mulai)->format('d M') }} - {{ \Carbon\Carbon::parse($pengajuanPkl->tanggal_selesai)->format('d M Y') }}
                                </div>
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-surface-400 uppercase tracking-widest block mb-1">Guru Pembimbing Sekolah</label>
                                <div class="flex items-center text-sm text-surface-800 font-semibold mt-1">
                                    <i data-lucide="user-check" class="w-4 h-4 mr-2 text-indigo-500"></i>
                                    {{ $pengajuanPkl->guru ? ($pengajuanPkl->guru?->user?->name ?? '-') : 'Belum ditentukan' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Penilaian Sidebar -->
                <div class="space-y-6">
                    @if($pengajuanPkl->penilaianPkl)
                        <div class="card-premium p-6 border-t-4 border-indigo-500">
                            <h4 class="font-bold text-surface-900 mb-4 flex items-center">
                                <i data-lucide="award" class="w-4 h-4 mr-2 text-indigo-500"></i>
                                Nilai Akhir PKL
                            </h4>
                            
                            <div class="flex flex-col items-center justify-center p-4 bg-indigo-50/50 border border-indigo-100/60 rounded-2xl mb-5">
                                <span class="text-xs font-semibold text-indigo-500 uppercase tracking-wider">Nilai Rata-rata</span>
                                <span class="text-4xl font-extrabold text-indigo-700 mt-1">{{ number_format($pengajuanPkl->penilaianPkl->nilai_akhir, 2) }}</span>
                            </div>

                            <div class="space-y-3 pb-4 border-b border-surface-100">
                                @if($pengajuanPkl->penilaianPkl->detail_nilai)
                                    @foreach($pengajuanPkl->penilaianPkl->detail_nilai as $item)
                                        <div class="flex justify-between text-sm">
                                            <span class="text-surface-500">{{ $item['nama'] }}:</span>
                                            <span class="font-bold text-surface-800">{{ $item['nilai'] }}</span>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="flex justify-between text-sm">
                                        <span class="text-surface-500">Nilai Sikap:</span>
                                        <span class="font-bold text-surface-800">{{ $pengajuanPkl->penilaianPkl->nilai_sikap }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-surface-500">Nilai Keterampilan:</span>
                                        <span class="font-bold text-surface-800">{{ $pengajuanPkl->penilaianPkl->nilai_keterampilan }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-surface-500">Nilai Laporan:</span>
                                        <span class="font-bold text-surface-800">{{ $pengajuanPkl->penilaianPkl->nilai_laporan }}</span>
                                    </div>
                                @endif
                            </div>

                            @if($pengajuanPkl->penilaianPkl->catatan_evaluasi)
                                <div class="mt-4">
                                    <label class="text-[10px] font-bold text-surface-400 uppercase tracking-widest">Catatan Evaluasi:</label>
                                    <p class="text-xs text-surface-600 italic mt-1 bg-surface-50 p-2.5 rounded-lg border border-surface-200/50">
                                        "{{ $pengajuanPkl->penilaianPkl->catatan_evaluasi }}"
                                    </p>
                                </div>
                            @endif

                            <div class="mt-5">
                                <a href="{{ route('pembimbing.penilaian.edit', $pengajuanPkl->penilaianPkl) }}" class="btn-secondary w-full text-center inline-flex items-center justify-center gap-1.5 !text-xs">
                                    <i data-lucide="edit" class="w-3.5 h-3.5"></i>
                                    Edit Penilaian
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="card-premium p-6 bg-surface-50/50">
                            <h4 class="font-bold text-surface-900 mb-3 flex items-center">
                                <i data-lucide="info" class="w-4 h-4 mr-2 text-surface-400"></i>
                                Penilaian PKL
                            </h4>
                            @if($pengajuanPkl->status === 'menunggu_penilaian')
                                <p class="text-xs text-surface-500 leading-relaxed mb-4">
                                    Siswa ini telah menyelesaikan PKL dan siap untuk dinilai.
                                </p>
                                <a href="{{ route('pembimbing.penilaian.create', $pengajuanPkl) }}" class="btn-primary w-full text-center inline-flex items-center justify-center gap-1.5 !text-xs">
                                    <i data-lucide="award" class="w-3.5 h-3.5"></i>
                                    Beri Penilaian
                                </a>
                            @else
                                <p class="text-xs text-surface-500 leading-relaxed">
                                    Siswa ini belum dinilai. Penilaian akhir dapat diberikan setelah kegiatan PKL siswa selesai dan status berubah menjadi menunggu penilaian.
                                </p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-extrabold text-lg md:text-2xl text-surface-900 tracking-tight">Semua Pengajuan PKL</h2>
            <p class="text-sm text-surface-500 mt-0.5 hidden sm:block">Daftar seluruh pengajuan PKL dari semua siswa.</p>
        </div>
    </x-slot>

    <div class="py-6 md:py-10 animate-fade-in">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="card-premium overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="table-premium">
                        <thead>
                            <tr>
                                <th>Siswa</th>
                                <th>Tempat PKL</th>
                                <th>Guru Pembimbing</th>
                                <th>Status</th>
                                <th class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pengajuan as $p)
                            <tr>
                                <td class="whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-brand-500 to-brand-600 flex items-center justify-center text-white font-bold text-sm shadow-sm shrink-0">
                                            {{ substr($p->siswa?->user?->name ?? '-', 0, 1) }}
                                        </div>
                                        <span class="text-sm font-bold text-surface-800">{{ $p->siswa?->user?->name ?? '-' }}</span>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap">
                                    <div class="flex items-center text-sm text-surface-600 gap-2">
                                        <i data-lucide="building" class="w-4 h-4 text-surface-400 shrink-0"></i>
                                        {{ $p->tempatPkl?->nama_tempat ?? '-' }}
                                    </div>
                                </td>
                                <td class="whitespace-nowrap">
                                    @if($p->guru)
                                        <span class="flex items-center gap-2 text-sm text-surface-600">
                                            <span class="w-2 h-2 rounded-full bg-emerald-400 shrink-0"></span>
                                            {{ $p->guru?->user?->name ?? '-' }}
                                        </span>
                                    @else
                                        <span class="text-sm text-surface-400 italic">Belum ditentukan</span>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap">
                                    @php
                                        $statusClasses = [
                                            'draft' => 'bg-surface-100 text-surface-500 border-surface-200',
                                            'menunggu_persetujuan' => 'bg-amber-50 text-amber-600 border-amber-100',
                                            'disetujui' => 'bg-brand-50 text-brand-600 border-brand-100',
                                            'ditolak' => 'bg-rose-50 text-rose-600 border-rose-100',
                                            'revisi' => 'bg-orange-50 text-orange-600 border-orange-100',
                                            'sedang_pkl' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                            'menunggu_penilaian' => 'bg-cyan-50 text-cyan-600 border-cyan-100',
                                            'selesai' => 'bg-blue-50 text-blue-600 border-blue-100',
                                        ];
                                        $class = $statusClasses[$p->status] ?? 'bg-surface-100 text-surface-600';
                                    @endphp
                                    <span class="status-badge {{ $class }}">
                                        {{ str_replace('_', ' ', $p->status) }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap text-right">
                                    <a href="{{ route('admin.pengajuan.show', $p) }}" class="inline-flex items-center gap-1 text-brand-600 hover:text-brand-800 font-bold text-xs uppercase tracking-widest transition-colors">
                                        Detail
                                        <i data-lucide="chevron-right" class="w-3 h-3"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state">
                                        <div class="empty-state-icon mx-auto">
                                            <i data-lucide="file-text" class="w-8 h-8"></i>
                                        </div>
                                        <p class="text-surface-400 font-medium mt-3">Belum ada data pengajuan.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($pengajuan->hasPages())
                <div class="p-4 sm:p-5 md:p-6 border-t border-surface-100 bg-surface-50/30">
                    {{ $pengajuan->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

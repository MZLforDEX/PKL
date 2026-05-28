<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-extrabold text-lg md:text-2xl text-surface-900 tracking-tight">Penilaian PKL</h2>
            <p class="text-sm text-surface-500 mt-0.5 hidden sm:block">Kelola penilaian akhir bagi siswa yang menyelesaikan program PKL.</p>
        </div>
    </x-slot>

    <div class="py-6 md:py-10 animate-fade-in">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="alert-success mb-6">
                    <i data-lucide="check-circle" class="w-5 h-5 shrink-0"></i>
                    {{ session('success') }}
                </div>
            @endif

            <div class="card-premium overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="table-premium">
                        <thead>
                            <tr>
                                <th>Siswa</th>
                                <th>Tempat PKL</th>
                                <th>Status</th>
                                <th>Nilai Akhir</th>
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
                                        <div>
                                            <div class="text-sm font-bold text-surface-900">{{ $p->siswa?->user?->name ?? '-' }}</div>
                                            <div class="text-[11px] text-surface-400 font-medium">NIS: {{ $p->siswa?->nis ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-sm text-surface-700 font-medium">{{ $p->tempatPkl?->nama_tempat ?? '-' }}</div>
                                </td>
                                <td class="whitespace-nowrap">
                                    @php
                                        $statusClasses = [
                                            'menunggu_penilaian' => 'bg-cyan-50 text-cyan-600 border border-cyan-100',
                                            'selesai' => 'bg-emerald-50 text-emerald-600 border border-emerald-100',
                                        ];
                                        $class = $statusClasses[$p->status] ?? 'bg-surface-100 text-surface-600';
                                    @endphp
                                    <span class="status-badge {{ $class }}">
                                        {{ str_replace('_', ' ', $p->status) }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap">
                                    @if($p->penilaianPkl)
                                        <span class="font-extrabold text-sm text-brand-600 bg-brand-50/60 px-2.5 py-1 rounded-lg border border-brand-100/50">{{ $p->penilaianPkl->nilai_akhir }}</span>
                                    @else
                                        <span class="text-surface-300 font-bold">-</span>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap text-right">
                                    @if($p->penilaianPkl)
                                        <span class="text-xs text-surface-400 font-semibold bg-surface-50 border border-surface-100 px-3 py-1.5 rounded-xl">Sudah Dinilai</span>
                                    @else
                                        <a href="{{ route('guru.penilaian.create', $p) }}" class="btn-primary inline-flex items-center gap-1.5 !px-4 !py-2 !text-xs">
                                            <i data-lucide="edit" class="w-3.5 h-3.5 shrink-0"></i>
                                            Beri Nilai
                                        </a>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state">
                                        <div class="empty-state-icon mx-auto">
                                            <i data-lucide="award" class="w-8 h-8"></i>
                                        </div>
                                        <p class="text-surface-400 font-medium mt-3">Belum ada data penilaian siswa bimbingan.</p>
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

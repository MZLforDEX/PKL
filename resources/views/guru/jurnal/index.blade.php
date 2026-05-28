<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-extrabold text-lg md:text-2xl text-surface-900 tracking-tight">Validasi Jurnal PKL</h2>
            <p class="text-sm text-surface-500 mt-0.5 hidden sm:block">Periksa dan berikan validasi pada jurnal harian siswa.</p>
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
                                <th>Siswa & Tanggal</th>
                                <th>Intisari Kegiatan</th>
                                <th>Status</th>
                                <th class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($jurnal as $j)
                            <tr>
                                <td class="whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-brand-500 to-brand-600 flex items-center justify-center text-white font-bold text-sm shadow-sm shrink-0">
                                            {{ substr($j->pengajuanPkl?->siswa?->user?->name ?? '-', 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-surface-900">{{ $j->pengajuanPkl?->siswa?->user?->name ?? '-' }}</div>
                                            <div class="text-[11px] text-surface-400 font-medium uppercase tracking-wider flex items-center mt-0.5">
                                                <i data-lucide="calendar" class="w-3 h-3 mr-1 shrink-0"></i>
                                                {{ \Carbon\Carbon::parse($j->tanggal)->format('d M Y') }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-sm text-surface-600 line-clamp-1 leading-relaxed">{{ Str::limit($j->kegiatan, 60) }}</div>
                                </td>
                                <td class="whitespace-nowrap">
                                    @php
                                        $statusClasses = [
                                            'menunggu_validasi' => 'bg-amber-50 text-amber-600 border-amber-100',
                                            'valid' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                            'revisi' => 'bg-rose-50 text-rose-600 border-rose-100',
                                        ];
                                        $class = $statusClasses[$j->status] ?? 'bg-surface-100 text-surface-600';
                                    @endphp
                                    <span class="status-badge {{ $class }}">
                                        {{ str_replace('_', ' ', $j->status) }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap text-right">
                                    <a href="{{ route('guru.jurnal.show', $j) }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-white border border-surface-200 hover:border-brand-200 hover:text-brand-600 rounded-xl text-xs font-bold text-surface-700 transition-all shadow-sm">
                                        <i data-lucide="file-search" class="w-3.5 h-3.5 text-amber-500 shrink-0"></i>
                                        Validasi
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4">
                                    <div class="empty-state">
                                        <div class="empty-state-icon mx-auto">
                                            <i data-lucide="book" class="w-8 h-8"></i>
                                        </div>
                                        <p class="text-surface-400 font-medium mt-3">Belum ada jurnal yang masuk untuk divalidasi.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($jurnal->hasPages())
                <div class="p-4 sm:p-5 md:p-6 border-t border-surface-100 bg-surface-50/30">
                    {{ $jurnal->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

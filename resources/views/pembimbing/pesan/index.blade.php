<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-lg md:text-2xl text-surface-900 tracking-tight">Hubungi Sekolah</h2>
                <p class="text-sm text-surface-500 mt-0.5 hidden sm:block">Kirim laporan, aduan, atau pesan langsung ke pihak sekolah.</p>
            </div>
            <div>
                <a href="{{ route('pembimbing.hubungi-sekolah.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 text-white rounded-xl text-xs font-bold hover:bg-indigo-700 transition-colors shadow-lg">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    Pesan Baru
                </a>
            </div>
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
                                <th>Subjek & Tanggal</th>
                                <th>Kategori</th>
                                <th>Status</th>
                                <th class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pesan as $p)
                            <tr>
                                <td class="whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center text-white shrink-0 shadow-sm">
                                            <i data-lucide="mail" class="w-4.5 h-4.5"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-surface-900">{{ $p->subjek }}</div>
                                            <div class="text-[11px] text-surface-400 font-medium flex items-center mt-0.5">
                                                <i data-lucide="calendar" class="w-3 h-3 mr-1 shrink-0"></i>
                                                {{ $p->created_at->format('d M Y, H:i') }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-800 dark:bg-zinc-800 dark:text-zinc-300">
                                        {{ ucfirst($p->kategori) }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap">
                                    @php
                                        $statusClasses = [
                                            'menunggu_tanggapan' => 'bg-amber-50 text-amber-600 border-amber-100 dark:bg-amber-950/20 dark:text-amber-400 dark:border-amber-900/30',
                                            'proses' => 'bg-blue-50 text-blue-600 border-blue-100 dark:bg-blue-950/20 dark:text-blue-400 dark:border-blue-900/30',
                                            'selesai' => 'bg-emerald-50 text-emerald-600 border-emerald-100 dark:bg-emerald-950/20 dark:text-emerald-400 dark:border-emerald-900/30',
                                        ];
                                        $class = $statusClasses[$p->status] ?? 'bg-surface-100 text-surface-600';
                                        $label = match($p->status) {
                                            'menunggu_tanggapan' => 'Menunggu Tanggapan',
                                            'proses' => 'Sedang Diproses',
                                            'selesai' => 'Selesai',
                                            default => $p->status,
                                        };
                                    @endphp
                                    <span class="status-badge {{ $class }}">
                                        {{ $label }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap text-right">
                                    <a href="{{ route('pembimbing.hubungi-sekolah.show', $p->id) }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-white border border-surface-200 hover:border-indigo-200 hover:text-indigo-600 rounded-xl text-xs font-bold text-surface-700 dark:bg-zinc-900 dark:border-zinc-800 dark:text-zinc-300 dark:hover:text-indigo-400 transition-all shadow-sm">
                                        <i data-lucide="eye" class="w-3.5 h-3.5 text-indigo-500 shrink-0"></i>
                                        Detail
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4">
                                    <div class="empty-state">
                                        <div class="empty-state-icon mx-auto">
                                            <i data-lucide="message-square-off" class="w-8 h-8"></i>
                                        </div>
                                        <p class="text-surface-400 font-medium mt-3">Belum ada riwayat pesan yang dikirim.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($pesan->hasPages())
                <div class="p-4 sm:p-5 md:p-6 border-t border-surface-100 bg-surface-50/30">
                    {{ $pesan->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

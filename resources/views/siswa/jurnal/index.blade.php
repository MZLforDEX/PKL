<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-extrabold text-lg md:text-2xl text-surface-900 tracking-tight">Jurnal PKL</h2>
                <p class="text-sm text-surface-500 mt-0.5 hidden sm:block">Catat dan pantau aktivitas harian pelaksanaan PKL Anda.</p>
            </div>
            <a href="{{ route('siswa.jurnal.create') }}" class="btn-primary w-full sm:w-auto text-center flex items-center justify-center gap-1.5">
                <i data-lucide="plus" class="w-4 h-4 shrink-0"></i>
                Tambah Jurnal
            </a>
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
            @if($errors->any())
                <div class="mb-6 p-4 bg-rose-50 border border-rose-100 text-rose-700 rounded-2xl flex items-center shadow-sm text-sm">
                    <i data-lucide="alert-circle" class="w-5 h-5 mr-3 shrink-0"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="card-premium overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="table-premium">
                        <thead>
                            <tr>
                                <th>Waktu</th>
                                <th>Deskripsi Kegiatan</th>
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
                                            <i data-lucide="calendar" class="w-5 h-5"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-surface-900">{{ \Carbon\Carbon::parse($j->tanggal)->format('d M Y') }}</div>
                                            <div class="text-[10px] text-surface-400 font-medium uppercase tracking-wider mt-0.5">{{ \Carbon\Carbon::parse($j->tanggal)->format('l') }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <p class="text-sm text-surface-600 leading-relaxed line-clamp-2 max-w-xl">{{ $j->kegiatan }}</p>
                                </td>
                                <td class="whitespace-nowrap">
                                    @php
                                        $statusClasses = [
                                            'menunggu_validasi' => 'bg-amber-50 text-amber-600 border border-amber-100',
                                            'valid' => 'bg-emerald-50 text-emerald-600 border border-emerald-100',
                                            'revisi' => 'bg-rose-50 text-rose-600 border border-rose-100',
                                        ];
                                        $class = $statusClasses[$j->status] ?? 'bg-surface-100 text-surface-600';
                                    @endphp
                                    <span class="status-badge {{ $class }}">
                                        {{ str_replace('_', ' ', $j->status) }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap text-right">
                                    <div class="flex justify-end items-center">
                                        @if($j->status === 'menunggu_validasi')
                                            <a href="{{ route('siswa.jurnal.edit', $j) }}" class="p-2 text-brand-500 hover:text-brand-600 hover:bg-brand-50 rounded-lg transition-colors" title="Edit Jurnal">
                                                <i data-lucide="edit-3" class="w-4 h-4"></i>
                                            </a>
                                        @else
                                            <span class="p-2 text-surface-300" title="Terkunci (Sudah divalidasi)">
                                                <i data-lucide="lock" class="w-4 h-4"></i>
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4">
                                    <div class="empty-state">
                                        <div class="empty-state-icon mx-auto">
                                            <i data-lucide="book-open" class="w-8 h-8"></i>
                                        </div>
                                        <p class="text-surface-400 font-medium mt-3">Belum ada catatan jurnal.</p>
                                        <a href="{{ route('siswa.jurnal.create') }}" class="mt-2.5 text-brand-600 font-bold hover:underline inline-block">Catat Kegiatan Pertama &rarr;</a>
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

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-extrabold text-lg md:text-2xl text-surface-900 tracking-tight">Laporan Akhir PKL</h2>
                <p class="text-sm text-surface-500 mt-0.5 hidden sm:block">Unggah dokumen laporan akhir Anda setelah menyelesaikan PKL.</p>
            </div>
            @if($canUpload)
            <a href="{{ route('siswa.laporan.create') }}" class="btn-primary w-full sm:w-auto text-center flex items-center justify-center gap-1.5">
                <i data-lucide="upload-cloud" class="w-4 h-4 shrink-0"></i>
                Upload Laporan
            </a>
            @endif
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
                                <th>Dokumen Laporan</th>
                                <th>Status</th>
                                <th>Catatan Guru</th>
                                <th class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($laporan as $l)
                            <tr>
                                <td class="whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-red-50 flex items-center justify-center text-red-600 font-bold mr-3 shadow-sm shrink-0">
                                            <i data-lucide="file-text" class="w-5 h-5"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-surface-900">Laporan Akhir PKL</div>
                                            <div class="text-[10px] text-surface-400 font-medium uppercase tracking-wider mt-0.5">PDF Document</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap">
                                    @php
                                        $statusClasses = [
                                            'menunggu_review' => 'bg-amber-50 text-amber-600 border border-amber-100',
                                            'diterima' => 'bg-emerald-50 text-emerald-600 border border-emerald-100',
                                            'revisi' => 'bg-rose-50 text-rose-600 border border-rose-100',
                                        ];
                                        $class = $statusClasses[$l->status] ?? 'bg-surface-100 text-surface-600';
                                    @endphp
                                    <span class="status-badge {{ $class }}">
                                        {{ str_replace('_', ' ', $l->status) }}
                                    </span>
                                </td>
                                <td>
                                    <p class="text-xs text-surface-500 font-medium">{{ $l->catatan_guru ?? 'Belum ada catatan' }}</p>
                                </td>
                                <td class="whitespace-nowrap text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ asset('storage/' . $l->file_laporan) }}" target="_blank" class="btn-secondary inline-flex items-center gap-1.5 !px-3.5 !py-2 !text-xs">
                                            <i data-lucide="eye" class="w-3.5 h-3.5 shrink-0 text-brand-500"></i>
                                            Lihat File
                                        </a>
                                        @if($l->status === 'revisi')
                                        <a href="{{ route('siswa.laporan.edit', $l) }}" class="btn-primary inline-flex items-center gap-1.5 !px-3.5 !py-2 !text-xs">
                                            <i data-lucide="pencil" class="w-3.5 h-3.5 shrink-0"></i>
                                            Perbaiki
                                        </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4">
                                    <div class="empty-state">
                                        <div class="empty-state-icon mx-auto">
                                            <i data-lucide="file-up" class="w-8 h-8"></i>
                                        </div>
                                        <p class="text-surface-400 font-medium mt-3">Belum ada laporan yang diunggah.</p>
                                        <a href="{{ route('siswa.laporan.create') }}" class="mt-2.5 text-brand-600 font-bold hover:underline inline-block">Upload Laporan Sekarang &rarr;</a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($laporan->hasPages())
                <div class="p-4 sm:p-5 md:p-6 border-t border-surface-100 bg-surface-50/30">
                    {{ $laporan->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

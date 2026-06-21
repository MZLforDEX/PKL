<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('guru.laporan.index') }}" class="p-2 rounded-lg hover:bg-surface-100 text-surface-400 hover:text-surface-600 transition-colors">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <h2 class="font-extrabold text-lg md:text-2xl text-surface-900 tracking-tight">Review Laporan Akhir</h2>
        </div>
    </x-slot>

    <div class="py-6 md:py-10 animate-fade-in">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="card-premium p-5 sm:p-6 md:p-8">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 pb-6 border-b border-surface-100">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0">
                                    <i data-lucide="file-text" class="w-6 h-6"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-surface-400 uppercase tracking-widest">Jenis Dokumen</p>
                                    <h3 class="text-base md:text-lg font-bold text-surface-900 mt-0.5">Laporan Akhir Siswa</h3>
                                </div>
                            </div>
                            @php
                                $statusClasses = [
                                    'menunggu_review' => 'bg-amber-50 text-amber-600 border border-amber-100',
                                    'diterima' => 'bg-emerald-50 text-emerald-600 border border-emerald-100',
                                    'revisi' => 'bg-rose-50 text-rose-600 border border-rose-100',
                                ];
                                $class = $statusClasses[$laporanPkl->status] ?? 'bg-surface-100 text-surface-600';
                            @endphp
                            <span class="status-badge {{ $class }}">
                                {{ str_replace('_', ' ', $laporanPkl->status) }}
                            </span>
                        </div>

                        <div class="space-y-6">
                            <div class="p-8 bg-surface-50 rounded-2xl border border-surface-200/60 text-center">
                                <i data-lucide="file-text" class="w-16 h-16 text-surface-300 mx-auto mb-4"></i>
                                <h4 class="text-surface-800 font-bold mb-2">Dokumen PDF Laporan</h4>
                                <p class="text-xs text-surface-500 mb-6">Silakan periksa kelengkapan dan format penulisan laporan siswa.</p>
                                <a href="{{ asset('storage/' . $laporanPkl->file_laporan) }}" target="_blank" class="btn-secondary inline-flex items-center !px-6 !py-3">
                                    <i data-lucide="external-link" class="w-4 h-4 mr-2 text-brand-500"></i>
                                    Buka Dokumen Laporan
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Sidebar -->
                <div class="space-y-6">
                    <div class="card-premium p-5">
                        <h4 class="font-bold text-surface-900 mb-4 flex items-center">
                            <i data-lucide="user" class="w-4 h-4 mr-2 text-brand-500"></i>
                            Penyusun Laporan
                        </h4>
                        <div class="flex items-center p-3.5 bg-surface-50 rounded-2xl border border-surface-100">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-brand-500 to-brand-600 flex items-center justify-center text-white font-bold mr-3 shadow-sm shrink-0">
                                {{ substr($laporanPkl->pengajuanPkl?->siswa?->user?->name ?? '-', 0, 1) }}
                            </div>
                            <div>
                                <p class="text-sm font-bold text-surface-900">{{ $laporanPkl->pengajuanPkl?->siswa?->user?->name ?? '-' }}</p>
                                <p class="text-[11px] text-surface-400 font-medium">NIS: {{ $laporanPkl->pengajuanPkl?->siswa?->nis ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    @if($laporanPkl->status === 'menunggu_review')
                    <div class="card-premium p-5 border-t-4 border-brand-500">
                        <h4 class="font-bold text-surface-900 mb-6 flex items-center">
                            <i data-lucide="clipboard-check" class="w-4 h-4 mr-2 text-brand-500"></i>
                            Hasil Review
                        </h4>
                        
                        <div class="space-y-6">
                            <!-- Accept Form -->
                            <form action="{{ route('guru.laporan.terima', $laporanPkl) }}" method="POST">
                                @csrf @method('PUT')
                                <input type="text" name="catatan_guru" placeholder="Catatan apresiasi (opsional)" class="form-input-premium mb-3 !py-2.5 text-sm">
                                <button type="submit" class="w-full btn-primary !py-3">
                                    <i data-lucide="check-circle" class="w-4 h-4 mr-2"></i>
                                    Terima Laporan
                                </button>
                            </form>

                            <div class="relative py-2">
                                <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-surface-200"></div></div>
                                <div class="relative flex justify-center text-[10px] uppercase font-bold text-surface-400 bg-white px-2">Atau</div>
                            </div>

                            <!-- Revision Form -->
                            <form action="{{ route('guru.laporan.revisi', $laporanPkl) }}" method="POST">
                                @csrf @method('PUT')
                                <textarea name="catatan_guru" placeholder="Detail bagian yang perlu direvisi..." class="form-input-premium mb-3 !py-2.5 text-sm" rows="3" required></textarea>
                                <button type="submit" class="w-full btn-secondary !py-3 !text-rose-600 hover:!bg-rose-50/50 hover:!border-rose-200">
                                    <i data-lucide="alert-triangle" class="w-4 h-4 mr-2"></i>
                                    Minta Perbaikan
                                </button>
                            </form>
                        </div>
                    </div>
                    @else
                    <div class="card-premium p-5">
                        <h4 class="font-bold text-surface-900 mb-4 flex items-center">
                            <i data-lucide="message-square" class="w-4 h-4 mr-2 text-brand-500"></i>
                            Catatan Review
                        </h4>
                        <div class="p-4 bg-surface-50 rounded-2xl border border-surface-200/60 italic text-sm text-surface-600 mb-6">
                            "{{ $laporanPkl->catatan_guru ?? 'Tidak ada catatan khusus.' }}"
                        </div>

                        @if($laporanPkl->pengajuanPkl?->status === 'menunggu_penilaian')
                        <a href="{{ route('guru.penilaian.index') }}" class="btn-primary w-full !py-3.5">
                            <i data-lucide="eye" class="w-4 h-4 mr-2"></i>
                            Pantau Nilai
                        </a>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('pembimbing.hubungi-sekolah.index') }}" class="p-2 rounded-lg hover:bg-surface-100 text-surface-400 hover:text-surface-600 transition-colors">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <h2 class="font-extrabold text-lg md:text-2xl text-surface-900 tracking-tight">Detail Pesan</h2>
        </div>
    </x-slot>

    <div class="py-6 md:py-10 animate-fade-in">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                {{-- Detail Aduan --}}
                <div class="lg:col-span-2 space-y-6">
                    <div class="card-premium p-6">
                        <div class="flex items-center justify-between border-b border-surface-100 pb-4 mb-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 dark:bg-indigo-950/20 dark:text-indigo-400">
                                Kategori: {{ ucfirst($pesan->kategori) }}
                            </span>
                            <span class="text-xs text-surface-400 font-medium">
                                Dikirim: {{ $pesan->created_at->format('d M Y, H:i') }}
                            </span>
                        </div>

                        <h3 class="text-lg font-bold text-surface-900 mb-3">{{ $pesan->subjek }}</h3>
                        <p class="text-sm text-surface-600 leading-relaxed whitespace-pre-line">{{ $pesan->pesan }}</p>

                        @if($pesan->lampiran)
                            <div class="mt-6 border-t border-surface-100 pt-4">
                                <span class="text-xs font-bold text-surface-400 block mb-2">Lampiran Dokumen/Gambar:</span>
                                @php
                                    $extension = pathinfo($pesan->lampiran, PATHINFO_EXTENSION);
                                    $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png']);
                                @endphp
                                
                                @if($isImage)
                                    <div class="mb-3 max-w-md rounded-xl overflow-hidden border border-surface-200">
                                        <img src="{{ asset('storage/' . $pesan->lampiran) }}" class="w-full object-contain max-h-60" alt="Lampiran">
                                    </div>
                                @endif

                                <a href="{{ asset('storage/' . $pesan->lampiran) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 rounded-xl text-xs font-bold transition-all shadow-sm">
                                    <i data-lucide="download" class="w-3.5 h-3.5 shrink-0"></i>
                                    Unduh Lampiran ({{ strtoupper($extension) }})
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Status & Tanggapan --}}
                <div class="space-y-6">
                    {{-- Status Card --}}
                    <div class="card-premium p-6">
                        <h4 class="text-xs font-bold text-surface-400 uppercase tracking-wider mb-3">Status Pengajuan</h4>
                        @php
                            $statusClasses = [
                                'menunggu_tanggapan' => 'bg-amber-50 text-amber-600 border-amber-100 dark:bg-amber-950/20 dark:text-amber-400 dark:border-amber-900/30',
                                'proses' => 'bg-blue-50 text-blue-600 border-blue-100 dark:bg-blue-950/20 dark:text-blue-400 dark:border-blue-900/30',
                                'selesai' => 'bg-emerald-50 text-emerald-600 border-emerald-100 dark:bg-emerald-950/20 dark:text-emerald-400 dark:border-emerald-900/30',
                            ];
                            $class = $statusClasses[$pesan->status] ?? 'bg-surface-100 text-surface-600';
                            $label = match($pesan->status) {
                                'menunggu_tanggapan' => 'Menunggu Tanggapan',
                                'proses' => 'Sedang Diproses',
                                'selesai' => 'Selesai',
                                default => $pesan->status,
                            };
                        @endphp
                        <span class="status-badge {{ $class }} w-full text-center py-2 text-sm font-semibold rounded-xl inline-block border">
                            {{ $label }}
                        </span>
                    </div>

                    {{-- Tanggapan Card --}}
                    <div class="card-premium p-6">
                        <h4 class="text-xs font-bold text-surface-400 uppercase tracking-wider mb-4">Tanggapan Pihak Sekolah</h4>
                        
                        @if($pesan->tanggapan)
                            <div class="space-y-3">
                                <div class="p-4 bg-emerald-50/60 dark:bg-emerald-950/10 border border-emerald-100/60 rounded-xl">
                                    <p class="text-sm text-surface-700 leading-relaxed whitespace-pre-line">{{ $pesan->tanggapan }}</p>
                                </div>
                                <div class="text-[10px] text-surface-400 font-medium">
                                    <p class="font-bold text-surface-700 dark:text-zinc-300">Dibalas Oleh: {{ $pesan->dibalasOleh?->name ?? 'Admin Sekolah' }}</p>
                                    <p class="mt-0.5">Tanggal: {{ $pesan->dibalas_pada?->format('d M Y, H:i') ?? '-' }}</p>
                                </div>
                            </div>
                        @else
                            <div class="empty-state py-6">
                                <div class="empty-state-icon mx-auto w-10 h-10 bg-amber-50 text-amber-500 rounded-xl flex items-center justify-center">
                                    <i data-lucide="clock" class="w-5 h-5 animate-spin"></i>
                                </div>
                                <p class="text-surface-400 text-xs font-medium mt-3 text-center">Menunggu tanggapan dari tim admin/guru pembimbing sekolah.</p>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>

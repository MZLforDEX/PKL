<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('pembimbing.jurnal.index') }}" class="p-2 rounded-lg hover:bg-surface-100 text-surface-400 hover:text-surface-600 transition-colors">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <h2 class="font-extrabold text-lg md:text-2xl text-surface-900 tracking-tight">Validasi Jurnal (Industri)</h2>
        </div>
    </x-slot>

    <div class="py-6 md:py-10 animate-fade-in">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Content Area -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="card-premium p-5 sm:p-6 md:p-8">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 pb-6 border-b border-surface-100">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center shrink-0">
                                    <i data-lucide="calendar" class="w-6 h-6"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-surface-400 uppercase tracking-widest">Tanggal Kegiatan</p>
                                    <h3 class="text-base md:text-lg font-bold text-surface-900 mt-0.5">{{ \Carbon\Carbon::parse($jurnalPkl->tanggal)->format('l, d F Y') }}</h3>
                                </div>
                            </div>
                            @php
                                $statusClasses = [
                                    'menunggu_validasi' => 'bg-amber-50 text-amber-600 border border-amber-100',
                                    'valid' => 'bg-emerald-50 text-emerald-600 border border-emerald-100',
                                    'revisi' => 'bg-rose-50 text-rose-600 border border-rose-100',
                                ];
                                $class = $statusClasses[$jurnalPkl->status] ?? 'bg-surface-100 text-surface-600';
                            @endphp
                            <span class="status-badge {{ $class }}">
                                {{ str_replace('_', ' ', $jurnalPkl->status) }}
                            </span>
                        </div>

                        <div class="space-y-6">
                            <section>
                                <label class="form-label mb-2 block">Deskripsi Kegiatan</label>
                                <div class="p-5 bg-surface-50 rounded-2xl border border-surface-200/60 text-surface-700 leading-relaxed text-sm md:text-base">
                                    {{ $jurnalPkl->kegiatan }}
                                </div>
                            </section>

                            @if($jurnalPkl->kendala)
                            <section>
                                <label class="form-label mb-2 block text-rose-500">Kendala yang Dihadapi</label>
                                <div class="p-5 bg-rose-50/40 rounded-2xl border border-rose-100/60 text-rose-700 leading-relaxed text-sm md:text-base">
                                    {{ $jurnalPkl->kendala }}
                                </div>
                            </section>
                            @endif

                            @if($jurnalPkl->dokumentasi)
                            <section>
                                <label class="form-label mb-2 block">Dokumentasi Visual</label>
                                <div class="group relative rounded-2xl overflow-hidden border border-surface-200 shadow-sm transition-all hover:shadow-md">
                                    <img src="{{ asset('storage/' . $jurnalPkl->dokumentasi) }}" class="w-full h-auto object-cover max-h-96">
                                    <a href="{{ asset('storage/' . $jurnalPkl->dokumentasi) }}" target="_blank" class="absolute inset-0 bg-surface-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white font-bold">
                                        <i data-lucide="maximize" class="w-6 h-6 mr-2"></i>
                                        Lihat Ukuran Penuh
                                    </a>
                                </div>
                            </section>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Validation Sidebar -->
                <div class="space-y-6">
                    <div class="card-premium p-5">
                        <h4 class="font-bold text-surface-900 mb-4 flex items-center">
                            <i data-lucide="user" class="w-4 h-4 mr-2 text-purple-500"></i>
                            Identitas Siswa
                        </h4>
                        <div class="flex items-center p-3.5 bg-surface-50 rounded-2xl border border-surface-100">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-500 to-indigo-500 flex items-center justify-center text-white font-bold mr-3 shadow-sm shrink-0">
                                {{ substr($jurnalPkl->pengajuanPkl?->siswa?->user?->name ?? '-', 0, 1) }}
                            </div>
                            <div>
                                <p class="text-sm font-bold text-surface-900">{{ $jurnalPkl->pengajuanPkl?->siswa?->user?->name ?? '-' }}</p>
                                <p class="text-[11px] text-surface-400 font-medium font-mono">NIS: {{ $jurnalPkl->pengajuanPkl?->siswa?->nis ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    @if($jurnalPkl->status === 'menunggu_validasi')
                    <div class="card-premium p-5 border-t-4 border-purple-500">
                        <h4 class="font-bold text-surface-900 mb-6 flex items-center">
                            <i data-lucide="shield-check" class="w-4 h-4 mr-2 text-purple-500"></i>
                            Tindakan Validasi Industri
                        </h4>
                        
                        <div class="space-y-6">
                            <!-- Valid Form -->
                            <form action="{{ route('pembimbing.jurnal.valid', $jurnalPkl) }}" method="POST">
                                @csrf @method('PUT')
                                <input type="text" name="catatan_guru" placeholder="Catatan industri (opsional)" class="form-input-premium mb-3 !py-2.5 text-sm">
                                <button type="submit" class="btn-primary w-full !py-3 !bg-purple-600 hover:!bg-purple-700">
                                    <i data-lucide="check" class="w-4 h-4 mr-2"></i>
                                    Validasi Jurnal
                                </button>
                            </form>

                            <div class="relative py-2">
                                <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-surface-200"></div></div>
                                <div class="relative flex justify-center text-[10px] uppercase font-bold text-surface-400 bg-white px-2">Atau</div>
                            </div>

                            <!-- Revisi Form -->
                            <form action="{{ route('pembimbing.jurnal.revisi', $jurnalPkl) }}" method="POST">
                                @csrf @method('PUT')
                                <textarea name="catatan_guru" placeholder="Instruksi revisi dari industri..." class="form-input-premium mb-3 !py-2.5 text-sm" rows="3" required></textarea>
                                <button type="submit" class="btn-secondary w-full !py-3 !text-rose-600 hover:!bg-rose-50/50 hover:!border-rose-200">
                                    <i data-lucide="refresh-cw" class="w-4 h-4 mr-2"></i>
                                    Minta Revisi
                                </button>
                            </form>
                        </div>
                    </div>
                    @else
                    <div class="card-premium p-5">
                        <h4 class="font-bold text-surface-900 mb-4 flex items-center">
                            <i data-lucide="message-square" class="w-4 h-4 mr-2 text-purple-500"></i>
                            Catatan Validasi Industri
                        </h4>
                        <div class="p-4 bg-surface-50 rounded-2xl border border-surface-200/60 italic text-sm text-surface-600">
                            "{{ $jurnalPkl->catatan_guru ?? 'Tidak ada catatan' }}"
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

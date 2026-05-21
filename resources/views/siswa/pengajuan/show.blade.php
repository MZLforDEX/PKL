<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('siswa.pengajuan.index') }}" class="p-2 rounded-lg hover:bg-surface-100 text-surface-400 hover:text-surface-600 transition-colors">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <h2 class="font-extrabold text-lg md:text-2xl text-surface-900 tracking-tight">Detail Pengajuan</h2>
        </div>
    </x-slot>

    <div class="py-6 md:py-10 animate-fade-in">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Main Info Card -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="card-premium p-5 sm:p-6 md:p-8">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 pb-6 border-b border-surface-100">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-brand-50 text-brand-600 flex items-center justify-center shrink-0">
                                    <i data-lucide="building-2" class="w-6 h-6"></i>
                                </div>
                                <div>
                                    <h3 class="text-base md:text-lg font-bold text-surface-900 leading-snug">{{ $pengajuan->tempatPkl->nama_tempat }}</h3>
                                    <p class="text-xs text-surface-400 font-medium mt-0.5">{{ $pengajuan->tempatPkl->alamat }}</p>
                                </div>
                            </div>
                            @php
                                $statusClasses = [
                                    'draft' => 'bg-surface-100 text-surface-500 border-surface-200',
                                    'menunggu_persetujuan' => 'bg-amber-50 text-amber-600 border border-amber-100',
                                    'disetujui' => 'bg-brand-50 text-brand-600 border border-brand-100',
                                    'ditolak' => 'bg-rose-50 text-rose-600 border border-rose-100',
                                    'revisi' => 'bg-orange-50 text-orange-600 border border-orange-100',
                                    'sedang_pkl' => 'bg-emerald-50 text-emerald-600 border border-emerald-100',
                                    'menunggu_penilaian' => 'bg-cyan-50 text-cyan-600 border border-cyan-100',
                                    'selesai' => 'bg-blue-50 text-blue-600 border border-blue-100',
                                ];
                                $class = $statusClasses[$pengajuan->status] ?? 'bg-surface-100 text-surface-600';
                            @endphp
                            <span class="status-badge {{ $class }}">
                                {{ str_replace('_', ' ', $pengajuan->status) }}
                            </span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 py-2">
                            <div>
                                <label class="form-label">Periode Pelaksanaan</label>
                                <div class="flex items-center text-surface-800 font-bold mt-1 text-sm md:text-base">
                                    <i data-lucide="calendar-range" class="w-4 h-4 mr-2 text-brand-500 shrink-0"></i>
                                    {{ \Carbon\Carbon::parse($pengajuan->tanggal_mulai)->format('d M Y') }} - {{ \Carbon\Carbon::parse($pengajuan->tanggal_selesai)->format('d M Y') }}
                                </div>
                            </div>
                            <div>
                                <label class="form-label">Guru Pembimbing</label>
                                <div class="flex items-center text-surface-800 font-bold mt-1 text-sm md:text-base">
                                    <i data-lucide="user-check" class="w-4 h-4 mr-2 text-brand-500 shrink-0"></i>
                                    {{ $pengajuan->guru?->user?->name ?? 'Menunggu penugasan admin' }}
                                </div>
                            </div>
                            <div class="md:col-span-2 mt-2">
                                <label class="form-label">Alasan Pengajuan</label>
                                <div class="p-4 bg-surface-50 rounded-2xl text-surface-600 text-sm leading-relaxed border border-surface-200/60 mt-1">
                                    {{ $pengajuan->alasan }}
                                </div>
                            </div>
                        </div>

                        @if($pengajuan->file_dokumen)
                        <div class="mt-4 pt-6 border-t border-surface-100">
                            <label class="form-label mb-3 block">Dokumen Pendukung</label>
                            <a href="{{ asset('storage/' . $pengajuan->file_dokumen) }}" target="_blank" class="btn-primary inline-flex items-center !px-5 !py-3">
                                <i data-lucide="file-text" class="w-5 h-5 mr-2"></i>
                                Lihat Dokumen Terlampir
                            </a>
                        </div>
                        @endif
                    </div>

                    @if($pengajuan->penilaianPkl)
                    <div class="card-premium p-5 sm:p-6 md:p-8 border-l-4 border-emerald-500">
                        <div class="flex items-center mb-6">
                            <i data-lucide="award" class="w-6 h-6 text-emerald-500 mr-2 shrink-0"></i>
                            <h3 class="text-base md:text-lg font-bold text-surface-900 uppercase tracking-tight">Hasil Penilaian Akhir</h3>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                            <div class="p-4 bg-emerald-50/50 rounded-2xl text-center border border-emerald-100/50">
                                <span class="block text-[10px] font-bold text-emerald-600 uppercase mb-1">Sikap</span>
                                <span class="text-lg md:text-2xl font-extrabold text-emerald-700">{{ $pengajuan->penilaianPkl->nilai_sikap }}</span>
                            </div>
                            <div class="p-4 bg-emerald-50/50 rounded-2xl text-center border border-emerald-100/50">
                                <span class="block text-[10px] font-bold text-emerald-600 uppercase mb-1">Skill</span>
                                <span class="text-lg md:text-2xl font-extrabold text-emerald-700">{{ $pengajuan->penilaianPkl->nilai_keterampilan }}</span>
                            </div>
                            <div class="p-4 bg-emerald-50/50 rounded-2xl text-center border border-emerald-100/50">
                                <span class="block text-[10px] font-bold text-emerald-600 uppercase mb-1">Laporan</span>
                                <span class="text-lg md:text-2xl font-extrabold text-emerald-700">{{ $pengajuan->penilaianPkl->nilai_laporan }}</span>
                            </div>
                            <div class="p-4 bg-gradient-to-br from-emerald-600 to-teal-500 rounded-2xl text-center shadow-lg shadow-emerald-100/50">
                                <span class="block text-[10px] font-bold text-emerald-100 uppercase mb-1">Akhir</span>
                                <span class="text-lg md:text-2xl font-extrabold text-white">{{ $pengajuan->penilaianPkl->nilai_akhir }}</span>
                            </div>
                        </div>
                        @if($pengajuan->penilaianPkl->catatan_evaluasi)
                        <div class="p-4 bg-surface-50 rounded-2xl border border-surface-200/60 italic text-sm text-surface-600">
                            "{{ $pengajuan->penilaianPkl->catatan_evaluasi }}"
                        </div>
                        @endif
                    </div>
                    @endif
                </div>

                <!-- Sidebar / Action Card -->
                <div class="space-y-6">
                    <div class="card-premium p-5">
                        <h4 class="font-bold text-surface-900 mb-4 flex items-center">
                            <i data-lucide="zap" class="w-4 h-4 mr-2 text-amber-500"></i>
                            Tindakan Cepat
                        </h4>
                        <div class="space-y-3">
                            @if($pengajuan->status === 'selesai')
                                <a href="{{ route('siswa.pengajuan.sertifikat', $pengajuan) }}" target="_blank" class="w-full py-3 bg-blue-600 text-white rounded-xl font-bold text-sm hover:bg-blue-700 transition-all shadow-lg shadow-blue-100 flex items-center justify-center">
                                    <i data-lucide="printer" class="w-4 h-4 mr-2"></i>
                                    Cetak Sertifikat
                                </a>
                            @endif
                            @if(in_array($pengajuan->status, ['draft', 'revisi']))
                                <form action="{{ route('siswa.pengajuan.ajukan', $pengajuan) }}" method="POST">
                                    @csrf @method('PUT')
                                    <button type="submit" class="w-full btn-primary !py-3 flex justify-center items-center">
                                        <i data-lucide="send" class="w-4 h-4 mr-2"></i>
                                        Kirim Pengajuan
                                    </button>
                                </form>
                                <a href="{{ route('siswa.pengajuan.edit', $pengajuan) }}" class="btn-secondary w-full text-center block !py-3">
                                    Edit Data
                                </a>
                            @else
                                <div class="p-5 bg-surface-50 rounded-2xl text-center border border-surface-200/60">
                                    <i data-lucide="info" class="w-8 h-8 text-surface-400 mx-auto mb-2"></i>
                                    <p class="text-xs text-surface-500 leading-relaxed">Pengajuan sudah dikirim dan tidak dapat diubah lagi.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if($pengajuan->catatan && $pengajuan->status === 'revisi')
                    <div class="bg-rose-50 border border-rose-100 p-5 rounded-2xl">
                        <div class="flex items-center text-rose-600 font-bold mb-2">
                            <i data-lucide="alert-circle" class="w-4 h-4 mr-2 shrink-0"></i>
                            Catatan Revisi
                        </div>
                        <p class="text-sm text-rose-500 leading-relaxed font-medium">{{ $pengajuan->catatan }}</p>
                    </div>
                    @endif

                    <div class="card-premium p-5">
                        <h4 class="font-bold text-surface-800 mb-4 flex items-center">
                            <i data-lucide="clock" class="w-4 h-4 mr-2 text-brand-500"></i>
                            Riwayat
                        </h4>
                        <div class="text-xs text-surface-500 space-y-4 font-medium">
                            <div class="flex items-start">
                                <div class="w-2 h-2 rounded-full bg-emerald-500 mt-1 mr-3 shrink-0"></div>
                                <div>
                                    <p class="font-bold text-surface-700">Dibuat pada</p>
                                    <p class="text-surface-400 mt-0.5">{{ $pengajuan->created_at->format('d M Y, H:i') }}</p>
                                </div>
                            </div>
                            @if($pengajuan->updated_at != $pengajuan->created_at)
                            <div class="flex items-start">
                                <div class="w-2 h-2 rounded-full bg-brand-500 mt-1 mr-3 shrink-0"></div>
                                <div>
                                    <p class="font-bold text-surface-700">Terakhir diperbarui</p>
                                    <p class="text-surface-400 mt-0.5">{{ $pengajuan->updated_at->format('d M Y, H:i') }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

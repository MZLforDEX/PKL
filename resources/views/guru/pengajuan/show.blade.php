<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-3 sm:space-y-0 sm:space-x-3">
            <a href="{{ route('guru.pengajuan.index') }}" class="p-2 bg-white rounded-xl shadow-sm hover:bg-slate-50 transition-colors">
                <i data-lucide="arrow-left" class="w-5 h-5 text-slate-600"></i>
            </a>
            <h2 class="font-bold text-lg md:text-2xl text-slate-800 tracking-tight">{{ __('Validasi Pengajuan') }}</h2>
        </div>
    </x-slot>

    <div class="py-6 md:py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 md:mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center shadow-sm">
                    <i data-lucide="check-circle" class="w-5 h-5 mr-3"></i>
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-8">
                <!-- Siswa & Pengajuan Detail -->
                <div class="lg:col-span-2 space-y-4 md:space-y-8">
                    <div class="card-premium p-4 sm:p-6 md:p-8">
                        <div class="flex items-start justify-between mb-4 md:mb-8">
                            <div class="flex items-center">
                                <div class="w-10 h-10 md:w-14 md:h-14 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 font-bold mr-4 border-2 border-white shadow-sm text-base md:text-xl">
                                    {{ substr($pengajuanPkl->siswa?->user?->name ?? '-', 0, 1) }}
                                </div>
                                <div>
                                    <h3 class="text-base md:text-xl font-bold text-slate-800">{{ $pengajuanPkl->siswa?->user?->name ?? '-' }}</h3>
                                    <p class="text-sm text-slate-500">NIS: {{ $pengajuanPkl->siswa?->nis ?? '-' }} • {{ $pengajuanPkl->siswa?->kelas ?? '-' }}</p>
                                </div>
                            </div>
                            @php
                                $statusClasses = [
                                    'draft' => 'bg-slate-100 text-slate-600',
                                    'menunggu_persetujuan' => 'bg-amber-50 text-amber-600 border border-amber-100',
                                    'disetujui' => 'bg-indigo-50 text-indigo-600 border border-indigo-100',
                                    'ditolak' => 'bg-rose-50 text-rose-600 border border-rose-100',
                                    'revisi' => 'bg-orange-50 text-orange-600 border border-orange-100',
                                    'sedang_pkl' => 'bg-emerald-50 text-emerald-600 border border-emerald-100',
                                    'selesai' => 'bg-blue-50 text-blue-600 border border-blue-100',
                                ];
                                $class = $statusClasses[$pengajuanPkl->status] ?? 'bg-slate-100 text-slate-600';
                            @endphp
                            <span class="status-badge {{ $class }}">
                                {{ str_replace('_', ' ', $pengajuanPkl->status) }}
                            </span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-8 py-8 border-t border-slate-200">
                            <div>
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-2">Tempat PKL Tujuan</label>
                                <div class="flex items-center text-slate-700 font-bold">
                                    <i data-lucide="building-2" class="w-4 h-4 mr-2 text-indigo-500"></i>
                                    {{ $pengajuanPkl->tempatPkl?->nama_tempat ?? '-' }}
                                </div>
                                <p class="text-xs text-slate-500 mt-1 ml-6">{{ $pengajuanPkl->tempatPkl?->bidang_usaha ?? '-' }}</p>
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-2">Rencana Periode</label>
                                <div class="flex items-center text-slate-700 font-bold">
                                    <i data-lucide="calendar" class="w-4 h-4 mr-2 text-indigo-500"></i>
                                    {{ \Carbon\Carbon::parse($pengajuanPkl->tanggal_mulai)->format('d M') }} - {{ \Carbon\Carbon::parse($pengajuanPkl->tanggal_selesai)->format('d M Y') }}
                                </div>
                            </div>
                            <div class="md:col-span-2">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-2">Alasan / Motivasi Siswa</label>
                                <div class="p-4 bg-slate-50 rounded-2xl text-slate-600 text-sm leading-relaxed border border-slate-200 italic">
                                    "{{ $pengajuanPkl->alasan }}"
                                </div>
                            </div>
                        </div>

                        @if($pengajuanPkl->file_dokumen)
                        <div class="mt-4 pt-6 border-t border-slate-200">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-3">Dokumen Pendukung</label>
                            <a href="{{ asset('storage/' . $pengajuanPkl->file_dokumen) }}" target="_blank" class="inline-flex items-center px-4 py-3 bg-white border border-slate-200 text-slate-700 rounded-xl font-bold text-sm hover:bg-slate-50 transition-all shadow-sm">
                                <i data-lucide="file-text" class="w-5 h-5 mr-2 text-red-500"></i>
                                Buka Berkas Pengajuan (PDF)
                            </a>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Validation Sidebar -->
                <div class="space-y-3 md:space-y-6">
                    @if($pengajuanPkl->status === 'menunggu_persetujuan')
                    <div class="card-premium p-4 sm:p-5 md:p-6 bg-slate-50/50">
                        <h4 class="font-bold text-slate-800 mb-4 flex items-center">
                            <i data-lucide="info" class="w-4 h-4 mr-2 text-slate-400"></i>
                            Status Saat Ini
                        </h4>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Pengajuan ini masih dalam status <strong>menunggu persetujuan</strong> dari Administrator.
                        </p>
                    </div>
                    @elseif($pengajuanPkl->status === 'draft')
                    <div class="card-premium p-4 sm:p-5 md:p-6 bg-slate-50/50">
                        <h4 class="font-bold text-slate-800 mb-4 flex items-center">
                            <i data-lucide="info" class="w-4 h-4 mr-2 text-slate-400"></i>
                            Status Saat Ini
                        </h4>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Pengajuan ini masih dalam status <strong>draft</strong>. Siswa belum mengirimkannya untuk diproses.
                        </p>
                    </div>
                    @else
                    <div class="card-premium p-4 sm:p-5 md:p-6 bg-slate-50/50">
                        <h4 class="font-bold text-slate-800 mb-4 flex items-center">
                            <i data-lucide="info" class="w-4 h-4 mr-2 text-slate-400"></i>
                            Status Saat Ini
                        </h4>
                        <p class="text-xs text-slate-500 leading-relaxed">
                            Keputusan telah diambil oleh Administrator.
                        </p>
                        @if($pengajuanPkl->catatan)
                        <div class="mt-4 p-4 bg-white rounded-xl border border-slate-200">
                            <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">Catatan Keputusan:</p>
                            <p class="text-xs text-slate-600 italic">"{{ $pengajuanPkl->catatan }}"</p>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

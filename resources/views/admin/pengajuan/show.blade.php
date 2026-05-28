<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.pengajuan.index') }}" class="p-2 rounded-lg hover:bg-surface-100 text-surface-400 hover:text-surface-600 transition-colors">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <h2 class="font-extrabold text-lg md:text-2xl text-surface-900 tracking-tight">Kelola Pengajuan</h2>
        </div>
    </x-slot>

    <div class="py-6 md:py-10 animate-fade-in">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="alert-success mb-6">
                    <i data-lucide="check-circle" class="w-5 h-5 shrink-0"></i>
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Info Section -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="card-premium p-5 sm:p-6 md:p-8">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 pb-6 border-b border-surface-100">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 md:w-14 md:h-14 rounded-2xl bg-gradient-to-br from-brand-500 to-brand-600 flex items-center justify-center text-white font-bold text-base md:text-xl shadow-sm shrink-0">
                                    {{ substr($pengajuanPkl->siswa?->user?->name ?? '-', 0, 1) }}
                                </div>
                                <div>
                                    <h3 class="text-base md:text-lg font-bold text-surface-900 leading-snug">{{ $pengajuanPkl->siswa?->user?->name ?? '-' }}</h3>
                                    <p class="text-xs md:text-sm text-surface-400 font-medium">NIS: {{ $pengajuanPkl->siswa?->nis ?? '-' }} • {{ $pengajuanPkl->siswa?->kelas ?? '-' }}</p>
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
                                    'selesai' => 'bg-blue-50 text-blue-600 border border-blue-100',
                                ];
                                $class = $statusClasses[$pengajuanPkl->status] ?? 'bg-surface-100 text-surface-600';
                            @endphp
                            <span class="status-badge {{ $class }}">
                                {{ str_replace('_', ' ', $pengajuanPkl->status) }}
                            </span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 py-2">
                            <div>
                                <label class="form-label">Target Tempat PKL</label>
                                <div class="flex items-center text-surface-800 font-bold mt-1 text-sm md:text-base">
                                    <i data-lucide="map-pin" class="w-4 h-4 mr-2 text-brand-500"></i>
                                    {{ $pengajuanPkl->tempatPkl?->nama_tempat ?? '-' }}
                                </div>
                            </div>
                            <div>
                                <label class="form-label">Periode Usulan</label>
                                <div class="flex items-center text-surface-800 font-bold mt-1 text-sm md:text-base">
                                    <i data-lucide="calendar" class="w-4 h-4 mr-2 text-brand-500"></i>
                                    {{ \Carbon\Carbon::parse($pengajuanPkl->tanggal_mulai)->format('d M') }} - {{ \Carbon\Carbon::parse($pengajuanPkl->tanggal_selesai)->format('d M Y') }}
                                </div>
                            </div>
                            <div class="md:col-span-2 mt-2">
                                <label class="form-label">Dokumen Kelengkapan</label>
                                @if($pengajuanPkl->file_dokumen)
                                <a href="{{ asset('storage/' . $pengajuanPkl->file_dokumen) }}" target="_blank" class="inline-flex items-center text-brand-600 font-bold text-sm hover:underline mt-1">
                                    <i data-lucide="file-check-2" class="w-4 h-4 mr-2"></i>
                                    Unduh/Lihat Dokumen Siswa
                                </a>
                                @else
                                <span class="text-xs text-surface-400 italic block mt-1">Tidak ada dokumen dilampirkan</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Assignment Sidebar -->
                <div class="space-y-6">
                    <div class="card-premium p-5 sm:p-6 {{ $pengajuanPkl->guru_id ? 'border-brand-200' : 'border-t-4 border-amber-500' }}">
                        <h4 class="font-bold text-surface-900 mb-6 flex items-center">
                            <i data-lucide="user-plus" class="w-4 h-4 mr-2 text-brand-500"></i>
                            Guru Pembimbing
                        </h4>
                        
                        @if($pengajuanPkl->guru_id)
                        <div class="flex items-center p-4 bg-brand-50/50 rounded-2xl border border-brand-100/60 mb-4">
                            <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-brand-500 to-brand-600 flex items-center justify-center text-white font-bold text-sm mr-3 shrink-0">
                                {{ substr($pengajuanPkl->guru?->user?->name ?? '-', 0, 1) }}
                            </div>
                            <div>
                                <p class="text-sm font-bold text-surface-900">{{ $pengajuanPkl->guru?->user?->name ?? '-' }}</p>
                                <p class="text-[11px] text-surface-400 font-medium">NIP: {{ $pengajuanPkl->guru?->nip ?? '-' }}</p>
                            </div>
                        </div>
                        <p class="text-[10px] text-surface-400 text-center italic font-medium">Guru sudah ditentukan oleh sistem</p>
                        @else
                        <form action="{{ route('admin.pengajuan.assign-guru', $pengajuanPkl) }}" method="POST" class="space-y-4">
                            @csrf @method('PUT')
                            <div>
                                <label class="form-label mb-2 block tracking-widest text-center text-[10px]">Pilih Guru untuk Membimbing</label>
                                <select name="guru_id" class="form-input-premium !py-2.5 text-sm" required>
                                    <option value="">-- Pilih Guru --</option>
                                    @foreach($guru as $g)
                                        <option value="{{ $g->id }}">{{ $g->user->name }} ({{ $g->nip }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn-primary w-full !py-3">
                                Simpan Penugasan
                            </button>
                        </form>
                        @endif
                    </div>

                    <div class="card-premium p-5 sm:p-6">
                        <h4 class="font-bold text-surface-800 mb-4 flex items-center text-xs uppercase tracking-widest">
                            <i data-lucide="history" class="w-3.5 h-3.5 mr-2 text-surface-400"></i>
                            Log Pengajuan
                        </h4>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-surface-400">Didaftarkan</span>
                                <span class="text-surface-700 font-bold">{{ $pengajuanPkl->created_at->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex items-center justify-between text-xs">
                                <span class="text-surface-400">Update Terakhir</span>
                                <span class="text-surface-700 font-bold">{{ $pengajuanPkl->updated_at->format('d/m/Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

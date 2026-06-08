<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-extrabold text-lg md:text-2xl text-surface-900 tracking-tight">Pengajuan PKL Saya</h2>
                <p class="text-sm text-surface-500 mt-0.5 hidden sm:block">Kelola usulan penempatan praktik kerja lapangan Anda.</p>
            </div>
            <a href="{{ route('siswa.pengajuan.create') }}" class="btn-primary w-full sm:w-auto text-center flex items-center justify-center gap-1.5">
                <i data-lucide="plus" class="w-4 h-4 shrink-0"></i>
                Buat Pengajuan
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

            <div class="card-premium overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="table-premium">
                        <thead>
                            <tr>
                                <th>Tempat PKL</th>
                                <th>Periode & Guru</th>
                                <th>Status</th>
                                <th class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pengajuan as $p)
                            <tr>
                                <td class="whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-brand-500 to-brand-600 flex items-center justify-center text-white font-bold text-sm shadow-sm shrink-0">
                                            <i data-lucide="building-2" class="w-5 h-5"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-surface-900">{{ $p->tempatPkl?->nama_tempat ?? '-' }}</div>
                                            <div class="text-xs text-surface-400 mt-0.5 leading-snug">{{ $p->tempatPkl ? Str::limit($p->tempatPkl->alamat, 30) : '-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap">
                                    <div class="text-sm text-surface-700 font-semibold">
                                        {{ \Carbon\Carbon::parse($p->tanggal_mulai)->format('d M') }} - {{ \Carbon\Carbon::parse($p->tanggal_selesai)->format('d M Y') }}
                                    </div>
                                    <div class="text-xs text-surface-400 mt-1 flex items-center">
                                        <i data-lucide="user-check" class="w-3 h-3 mr-1 shrink-0"></i>
                                        {{ $p->guru?->user?->name ?? 'Belum ada pembimbing' }}
                                    </div>
                                </td>
                                <td class="whitespace-nowrap">
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
                                        $class = $statusClasses[$p->status] ?? 'bg-surface-100 text-surface-600';
                                    @endphp
                                    <span class="status-badge {{ $class }}">
                                        {{ str_replace('_', ' ', $p->status) }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap text-right">
                                    <div class="flex justify-end items-center gap-1">
                                        <a href="{{ route('siswa.pengajuan.show', $p) }}" class="p-2 text-surface-400 hover:text-surface-600 hover:bg-surface-100 rounded-lg transition-colors" title="Detail">
                                            <i data-lucide="eye" class="w-4 h-4"></i>
                                        </a>
                                        @if(in_array($p->status, ['draft', 'revisi']))
                                            <a href="{{ route('siswa.pengajuan.edit', $p) }}" class="p-2 text-amber-500 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Edit">
                                                <i data-lucide="edit-3" class="w-4 h-4"></i>
                                            </a>
                                            <form id="ajukan-form-{{ $p->id }}" action="{{ route('siswa.pengajuan.ajukan', $p) }}" method="POST" class="inline">
                                                @csrf @method('PUT')
                                                <button type="button" 
                                                    onclick="confirmAction('Kirim Pengajuan?', 'Setelah dikirim, data tidak dapat diubah sampai diverifikasi admin.', 'info', 'Ya, Kirim Sekarang', () => document.getElementById('ajukan-form-{{ $p->id }}').submit())"
                                                    class="p-2 text-brand-500 hover:text-brand-600 hover:bg-brand-50 rounded-lg transition-colors" title="Ajukan Sekarang">
                                                    <i data-lucide="send" class="w-4 h-4"></i>
                                                </button>
                                            </form>
                                        @endif
                                        @if($p->status === 'selesai')
                                            <a href="{{ route('siswa.pengajuan.sertifikat', $p) }}" target="_blank" class="p-2 text-blue-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Cetak Sertifikat">
                                                <i data-lucide="printer" class="w-4 h-4"></i>
                                            </a>
                                        @endif
                                        @if(in_array($p->status, ['draft', 'ditolak']))
                                            <form id="delete-form-{{ $p->id }}" action="{{ route('siswa.pengajuan.destroy', $p) }}" method="POST" class="inline">
                                                @csrf @method('DELETE')
                                                <button type="button" 
                                                    onclick="confirmAction('Hapus Pengajuan?', 'Tindakan ini tidak dapat dibatalkan.', 'warning', 'Ya, Hapus Data', () => document.getElementById('delete-form-{{ $p->id }}').submit())"
                                                    class="p-2 text-rose-500 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Hapus">
                                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4">
                                    <div class="empty-state">
                                        <div class="empty-state-icon mx-auto">
                                            <i data-lucide="file-question" class="w-8 h-8"></i>
                                        </div>
                                        <p class="text-surface-400 font-medium mt-3">Belum ada pengajuan PKL.</p>
                                        <a href="{{ route('siswa.pengajuan.create') }}" class="mt-2.5 text-brand-600 font-bold hover:underline inline-block">Buat pengajuan pertama Anda &rarr;</a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($pengajuan->hasPages())
                <div class="p-4 sm:p-5 md:p-6 border-t border-surface-100 bg-surface-50/30">
                    {{ $pengajuan->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

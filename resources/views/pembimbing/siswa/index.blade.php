<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-lg md:text-2xl text-surface-900 tracking-tight">Siswa PKL Bimbingan</h2>
        <p class="text-sm text-surface-500 mt-0.5">Daftar siswa yang sedang atau telah menyelesaikan kegiatan PKL di perusahaan Anda.</p>
    </x-slot>

    <div class="py-6 md:py-10 animate-fade-in">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="card-premium overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="table-premium">
                        <thead>
                            <tr>
                                <th>Siswa Bimbingan</th>
                                <th>Kelas / Jurusan</th>
                                <th>Guru Pembimbing Sekolah</th>
                                <th>Status PKL</th>
                                <th class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($siswa as $s)
                            <tr>
                                <td class="whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-purple-500 to-indigo-500 flex items-center justify-center text-white font-bold text-sm shadow-sm shrink-0">
                                            {{ substr($s->siswa?->user?->name ?? '-', 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-surface-900">{{ $s->siswa?->user?->name ?? '-' }}</div>
                                            <div class="text-[10px] text-surface-400 font-medium font-mono uppercase tracking-wider">{{ $s->siswa?->nis ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap text-sm text-surface-700">
                                    {{ $s->siswa?->kelas ?? '-' }} / {{ $s->siswa?->jurusan ?? '-' }}
                                </td>
                                <td class="whitespace-nowrap text-sm text-surface-600">
                                    {{ $s->guru ? ($s->guru?->user?->name ?? '-') : 'Belum ditentukan' }}
                                </td>
                                <td class="whitespace-nowrap">
                                    @php
                                        $statusClasses = [
                                            'disetujui' => 'bg-indigo-50 text-indigo-600 border border-indigo-100',
                                            'sedang_pkl' => 'bg-emerald-50 text-emerald-600 border border-emerald-100',
                                            'menunggu_penilaian' => 'bg-cyan-50 text-cyan-600 border border-cyan-100',
                                            'selesai' => 'bg-blue-50 text-blue-600 border border-blue-100',
                                        ];
                                        $class = $statusClasses[$s->status] ?? 'bg-slate-100 text-slate-600';
                                    @endphp
                                    <span class="status-badge {{ $class }}">
                                        {{ str_replace('_', ' ', $s->status) }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap text-right">
                                    <a href="{{ route('pembimbing.siswa.show', $s) }}" class="inline-flex items-center px-3.5 py-1.5 bg-brand-50 text-brand-600 rounded-lg hover:bg-brand-100 text-xs font-bold transition-all gap-1.5">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                        Detail
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state">
                                        <div class="empty-state-icon">
                                            <i data-lucide="users" class="w-8 h-8"></i>
                                        </div>
                                        <p class="text-surface-400 font-medium mt-3">Belum ada siswa PKL yang terdaftar di tempat Anda.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($siswa->hasPages())
                <div class="p-4 sm:p-5 md:p-6 border-t border-surface-100 bg-surface-50/30">
                    {{ $siswa->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

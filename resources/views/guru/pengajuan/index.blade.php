<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-lg md:text-2xl text-slate-800 tracking-tight">{{ __('Validasi Pengajuan PKL') }}</h2>
    </x-slot>

    <div class="py-6 md:py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 md:mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center shadow-sm">
                    <i data-lucide="check-circle" class="w-5 h-5 mr-3"></i>
                    {{ session('success') }}
                </div>
            @endif

            <div class="card-premium overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th class="px-3 py-3 md:px-6 md:py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-widest">Siswa Bimbingan</th>
                                <th class="px-3 py-3 md:px-6 md:py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-widest">Target Instansi</th>
                                <th class="px-3 py-3 md:px-6 md:py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-widest">Status</th>
                                <th class="px-3 py-3 md:px-6 md:py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-widest">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($pengajuan as $p)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-3 py-3 md:px-6 md:py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 font-bold mr-3 border border-slate-200">
                                            {{ substr($p->siswa->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-slate-800">{{ $p->siswa->user->name }}</div>
                                            <div class="text-[10px] text-slate-400 font-medium uppercase tracking-wider">{{ $p->siswa->nis }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-3 py-3 md:px-6 md:py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-slate-700">{{ $p->tempatPkl->nama_tempat }}</div>
                                    <div class="text-[11px] text-slate-400 mt-0.5">{{ $p->tempatPkl->bidang_usaha }}</div>
                                </td>
                                <td class="px-3 py-3 md:px-6 md:py-4 whitespace-nowrap">
                                    @php
                                        $statusClasses = [
                                            'draft' => 'bg-slate-100 text-slate-600',
                                            'menunggu_persetujuan' => 'bg-amber-50 text-amber-600 border border-amber-100',
                                            'disetujui' => 'bg-indigo-50 text-indigo-600 border border-indigo-100',
                                            'ditolak' => 'bg-rose-50 text-rose-600 border border-rose-100',
                                            'revisi' => 'bg-orange-50 text-orange-600 border border-orange-100',
                                            'sedang_pkl' => 'bg-emerald-50 text-emerald-600 border border-emerald-100',
                                            'menunggu_penilaian' => 'bg-cyan-50 text-cyan-600 border border-cyan-100',
                                            'selesai' => 'bg-blue-50 text-blue-600 border border-blue-100',
                                        ];
                                        $class = $statusClasses[$p->status] ?? 'bg-slate-100 text-slate-600';
                                    @endphp
                                    <span class="status-badge {{ $class }}">
                                        {{ str_replace('_', ' ', $p->status) }}
                                    </span>
                                </td>
                                <td class="px-3 py-3 md:px-6 md:py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('guru.pengajuan.show', $p) }}" class="inline-flex items-center px-4 py-2 bg-white border border-slate-200 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-50 transition-colors shadow-sm">
                                        <i data-lucide="shield-check" class="w-3.5 h-3.5 mr-2 text-indigo-600"></i>
                                        Review
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-3 py-6 md:px-6 md:py-12 text-center text-slate-500">
                                    <div class="flex flex-col items-center">
                                        <i data-lucide="clipboard-list" class="w-8 h-8 md:w-12 md:h-12 mb-4 opacity-20"></i>
                                        <p class="text-sm font-medium">Tidak ada pengajuan yang perlu diproses.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($pengajuan->hasPages())
                <div class="p-4 sm:p-5 md:p-6 border-t border-slate-200 bg-slate-50/30">
                    {{ $pengajuan->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

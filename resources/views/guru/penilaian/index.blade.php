<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-extrabold text-lg md:text-2xl text-surface-900 tracking-tight">Penilaian PKL</h2>
            <p class="text-sm text-surface-500 mt-0.5 hidden sm:block">Kelola penilaian akhir bagi siswa yang
                menyelesaikan program PKL.</p>
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
                                <th>Siswa</th>
                                <th>Tempat PKL</th>
                                <th>Status</th>
                                <th>Nilai Akhir</th>
                                <th class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pengajuan as $p)
                                <tr>
                                    <td class="whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-9 h-9 rounded-xl bg-gradient-to-br from-brand-500 to-brand-600 flex items-center justify-center text-white font-bold text-sm shadow-sm shrink-0">
                                                {{ substr($p->siswa?->user?->name ?? '-', 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="text-sm font-bold text-surface-900">
                                                    {{ $p->siswa?->user?->name ?? '-' }}</div>
                                                <div class="text-[11px] text-surface-400 font-medium">NIS:
                                                    {{ $p->siswa?->nis ?? '-' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-sm text-surface-700 font-medium">
                                            {{ $p->tempatPkl?->nama_tempat ?? '-' }}</div>
                                    </td>
                                    <td class="whitespace-nowrap">
                                        @php
                                            $statusClasses = [
                                                'menunggu_penilaian' => 'bg-cyan-50 text-cyan-600 border border-cyan-100',
                                                'selesai' => 'bg-emerald-50 text-emerald-600 border border-emerald-100',
                                            ];
                                            $class = $statusClasses[$p->status] ?? 'bg-surface-100 text-surface-600';
                                        @endphp
                                        <span class="status-badge {{ $class }}">
                                            {{ str_replace('_', ' ', $p->status) }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap">
                                        @if($p->penilaianPkl)
                                            <span
                                                class="font-extrabold text-sm text-brand-600 bg-brand-50/60 px-2.5 py-1 rounded-lg border border-brand-100/50">{{ $p->penilaianPkl->nilai_akhir }}</span>
                                        @else
                                            <span class="text-surface-300 font-bold">-</span>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap text-right">
                                        @if($p->penilaianPkl)
                                            <button type="button" 
                                                onclick="showDetail('{{ addslashes($p->siswa?->user?->name ?? '-') }}', {{ json_encode($p->penilaianPkl->detail_nilai ?? []) }}, '{{ number_format($p->penilaianPkl->nilai_akhir, 2) }}', '{{ addslashes($p->penilaianPkl->catatan_evaluasi ?? '-') }}', {{ $p->penilaianPkl->nilai_sikap ?? 0 }}, {{ $p->penilaianPkl->nilai_keterampilan ?? 0 }}, {{ $p->penilaianPkl->nilai_laporan ?? 0 }})" 
                                                class="btn-secondary inline-flex items-center gap-1.5 !px-3 !py-1.5 !text-xs cursor-pointer">
                                                <i data-lucide="eye" class="w-3.5 h-3.5 shrink-0"></i>
                                                Detail Nilai
                                            </button>
                                        @else
                                            <span
                                                class="text-xs text-surface-400 font-semibold bg-surface-50 border border-surface-100 px-3 py-1.5 rounded-xl">Belum
                                                Dinilai</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">
                                        <div class="empty-state">
                                            <div class="empty-state-icon mx-auto">
                                                <i data-lucide="award" class="w-8 h-8"></i>
                                            </div>
                                            <p class="text-surface-400 font-medium mt-3">Belum ada data penilaian siswa
                                                bimbingan.</p>
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

    @push('scripts')
    <script>
        function showDetail(siswaName, detailNilai, nilaiAkhir, catatanEvaluasi, nilaiSikap, nilaiKeterampilan, nilaiLaporan) {
            const colors = getSwalColors();
            let items = detailNilai;
            if (!items || items.length === 0) {
                items = [
                    { nama: 'Nilai Sikap', nilai: nilaiSikap },
                    { nama: 'Nilai Keterampilan', nilai: nilaiKeterampilan },
                    { nama: 'Nilai Laporan', nilai: nilaiLaporan }
                ];
            }
            
            let detailHtml = `
                <div class="text-left space-y-4">
                    <div class="border-b border-surface-100 dark:border-zinc-800 pb-3">
                        <p class="text-[10px] text-surface-400 dark:text-zinc-500 font-bold uppercase tracking-wider">Rincian Nilai</p>
                        <div class="mt-2 space-y-2">
            `;
            
            items.forEach(item => {
                detailHtml += `
                    <div class="flex justify-between items-center bg-surface-50 dark:bg-zinc-800/40 p-2.5 rounded-xl border border-surface-100 dark:border-zinc-800/50">
                        <span class="text-xs font-semibold text-surface-600 dark:text-zinc-300">${item.nama}</span>
                        <span class="text-xs font-extrabold text-brand-600 bg-brand-50/60 dark:bg-brand-950/20 px-2.5 py-0.5 rounded-lg">${item.nilai}</span>
                    </div>
                `;
            });
            
            detailHtml += `
                        </div>
                    </div>
                    <div class="border-b border-surface-100 dark:border-zinc-800 pb-3">
                        <p class="text-[10px] text-surface-400 dark:text-zinc-500 font-bold uppercase tracking-wider">Catatan Evaluasi</p>
                        <p class="mt-1.5 text-xs text-surface-600 dark:text-zinc-300 leading-relaxed italic bg-surface-50 dark:bg-zinc-800/40 p-3 rounded-xl border border-surface-100 dark:border-zinc-800/50">${catatanEvaluasi || '-'}</p>
                    </div>
                    <div class="flex justify-between items-center pt-2">
                        <span class="text-xs font-bold text-surface-800 dark:text-zinc-200">Nilai Akhir Rata-rata:</span>
                        <span class="text-sm font-extrabold text-brand-600 bg-brand-50 dark:bg-brand-950/30 px-3.5 py-1.5 rounded-xl border border-brand-100 dark:border-brand-950/40">${nilaiAkhir}</span>
                    </div>
                </div>
            `;

            Swal.fire({
                title: `<span class="text-sm md:text-base font-extrabold text-surface-900 dark:text-zinc-100">Penilaian PKL: ${siswaName}</span>`,
                html: detailHtml,
                confirmButtonText: 'Tutup',
                confirmButtonColor: '#6366f1',
                background: colors.background,
                buttonsStyling: true,
                customClass: {
                    confirmButton: 'btn-primary !px-5 !py-2.5 !text-xs'
                }
            });
        }
    </script>
    @endpush
</x-app-layout>
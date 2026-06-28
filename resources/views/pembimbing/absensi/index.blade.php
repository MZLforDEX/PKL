<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-lg md:text-2xl text-surface-900 tracking-tight">Monitoring Absensi (Industri)</h2>
        <p class="text-sm text-surface-500 mt-0.5 font-medium">Pantau data kehadiran harian dari siswa magang di perusahaan Anda.</p>
    </x-slot>

    <div class="py-6 md:py-10 animate-fade-in">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Filter & Cetak Absensi Card --}}
            <div class="card-premium p-6 mb-6">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0">
                            <i data-lucide="file-spreadsheet" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-surface-900">Filter & Cetak Absensi</h3>
                            <p class="text-xs text-surface-500 mt-0.5">Saring data tabel dan unduh rekapan absen siswa magang di perusahaan Anda dalam format Excel (.xls) berdasarkan periode.</p>
                        </div>
                    </div>
                    
                    <form id="filterExportForm" action="{{ route('pembimbing.absensi.index') }}" method="GET" class="flex flex-wrap items-center gap-4">
                        {{-- Periode Select --}}
                        <div class="flex items-center gap-2">
                            <label for="periode_id" class="text-xs font-semibold text-surface-600 dark:text-zinc-400">Periode:</label>
                            <select id="periode_id" name="periode_id" onchange="this.form.submit()" class="rounded-xl border-surface-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 text-xs text-surface-800 focus:border-brand-500 focus:ring-brand-500 py-1.5 pl-3 pr-8 cursor-pointer">
                                <option value="">Semua Periode</option>
                                @foreach($periodes as $p)
                                    <option value="{{ $p->id }}" @selected($selectedPeriodeId == $p->id)>{{ $p->nama_periode }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex items-center gap-2">
                            <button type="button" onclick="submitExport()" class="inline-flex items-center justify-center px-4 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl text-xs font-bold transition-colors gap-1.5">
                                <i data-lucide="download" class="w-4 h-4"></i>
                                Unduh Excel
                            </button>

                            @if(request('periode_id'))
                                <a href="{{ route('pembimbing.absensi.index') }}" class="btn-secondary !py-1.5 !px-3 inline-flex items-center justify-center hover:bg-surface-200" title="Reset">
                                    <i data-lucide="x" class="w-4 h-4"></i>
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <div class="card-premium overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="table-premium">
                        <thead>
                            <tr>
                                <th>Siswa Magang</th>
                                <th>Kelas / Jurusan</th>
                                <th>Tanggal & Waktu</th>
                                <th>Foto Selfie</th>
                                <th>Lokasi (Maps)</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($absensi as $a)
                            <tr>
                                <td class="whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-purple-500 to-indigo-500 flex items-center justify-center text-white font-bold text-sm shadow-sm shrink-0">
                                            {{ substr($a->pengajuanPkl?->siswa?->user?->name ?? '-', 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-surface-900">{{ $a->pengajuanPkl?->siswa?->user?->name ?? '-' }}</div>
                                            <div class="text-[10px] text-surface-400 font-medium font-mono uppercase tracking-wider">{{ $a->pengajuanPkl?->siswa?->nis ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap text-sm text-surface-700">
                                    {{ $a->pengajuanPkl?->siswa?->kelas ?? '-' }} / {{ $a->pengajuanPkl?->siswa?->jurusan ?? '-' }}
                                </td>
                                <td class="whitespace-nowrap text-sm text-surface-800">
                                    <span class="font-semibold">{{ \Carbon\Carbon::parse($a->tanggal)->format('d F Y') }}</span>
                                    <div class="text-xs text-surface-400 mt-0.5 font-mono">{{ $a->jam_masuk }}</div>
                                </td>
                                <td>
                                    <div class="w-10 h-10 rounded-lg overflow-hidden border border-surface-200 shadow-sm hover:scale-105 transition-all">
                                        <a href="{{ asset('storage/' . $a->foto_selfie) }}" target="_blank">
                                            <img src="{{ asset('storage/' . $a->foto_selfie) }}" class="w-full h-full object-cover">
                                        </a>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap">
                                    @if($a->latitude && $a->longitude)
                                        <a href="https://www.google.com/maps?q={{ $a->latitude }},{{ $a->longitude }}" target="_blank" class="inline-flex items-center text-xs text-indigo-600 hover:text-indigo-700 font-semibold gap-1">
                                            <i data-lucide="map-pin" class="w-4 h-4 text-emerald-500 shrink-0"></i>
                                            Lihat Lokasi
                                        </a>
                                    @else
                                        <span class="text-xs text-surface-400 font-medium">Tanpa GPS</span>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap">
                                    <span class="status-badge {{ $a->status === 'hadir' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-rose-50 text-rose-600 border border-rose-100' }}">
                                        {{ ucfirst($a->status) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">
                                        <div class="empty-state-icon">
                                            <i data-lucide="users" class="w-8 h-8"></i>
                                        </div>
                                        <p class="text-surface-400 font-medium mt-3">Belum ada siswa magang yang melakukan absensi hari ini.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($absensi->hasPages())
                <div class="p-4 sm:p-5 border-t border-surface-100 bg-surface-50/30">
                    {{ $absensi->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function submitExport() {
            const form = document.getElementById('filterExportForm');
            const tempForm = document.createElement('form');
            tempForm.action = "{{ route('pembimbing.absensi.export') }}";
            tempForm.method = 'GET';
            
            const originalInput = form.querySelector('[name="periode_id"]');
            if (originalInput) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'periode_id';
                input.value = originalInput.value;
                tempForm.appendChild(input);
            }
            
            document.body.appendChild(tempForm);
            tempForm.submit();
            document.body.removeChild(tempForm);
        }
    </script>
    @endpush
</x-app-layout>

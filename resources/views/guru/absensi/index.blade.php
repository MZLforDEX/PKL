<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-lg md:text-2xl text-surface-900 tracking-tight">Monitoring Absensi Siswa</h2>
        <p class="text-sm text-surface-500 mt-0.5">Pantau data kehadiran harian dari siswa bimbingan Anda.</p>
    </x-slot>

    <div class="py-6 md:py-10 animate-fade-in">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="card-premium overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="table-premium">
                        <thead>
                            <tr>
                                <th>Siswa Bimbingan</th>
                                <th>Tempat PKL</th>
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
                                        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-500 flex items-center justify-center text-white font-bold text-sm shadow-sm shrink-0">
                                            {{ substr($a->pengajuanPkl->siswa->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-surface-900">{{ $a->pengajuanPkl->siswa->user->name }}</div>
                                            <div class="text-[10px] text-surface-400 font-medium font-mono uppercase tracking-wider">{{ $a->pengajuanPkl->siswa->nis }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap text-sm text-surface-700">
                                    {{ $a->pengajuanPkl->tempatPkl->nama_tempat }}
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
                                        <a href="https://www.google.com/maps?q={{ $a->latitude }},{{ $a->longitude }}" target="_blank" class="inline-flex items-center text-xs text-brand-600 hover:text-brand-700 font-semibold gap-1">
                                            <i data-lucide="map-pin" class="w-4 h-4 text-emerald-500 shrink-0"></i>
                                            Lihat Lokasi
                                        </a>
                                    @else
                                        <span class="text-xs text-surface-400">Tanpa GPS</span>
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
                                        <p class="text-surface-400 font-medium mt-3">Belum ada siswa bimbingan yang melakukan absensi hari ini.</p>
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
</x-app-layout>

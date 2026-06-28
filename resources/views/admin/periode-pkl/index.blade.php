<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-extrabold text-lg md:text-2xl text-surface-900 tracking-tight">Manajemen Periode PKL</h2>
                <p class="text-sm text-surface-50 mt-0.5 hidden sm:block">Kelola periode tahun ajaran pelaksanaan Praktik Kerja Lapangan.</p>
            </div>
            <a href="{{ route('admin.periode-pkl.create') }}" class="btn-primary w-full sm:w-auto">
                <i data-lucide="calendar-plus" class="w-4 h-4 mr-2"></i>
                Tambah Periode
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

            @if($errors->any())
                <div class="p-4 mb-6 bg-rose-50 border border-rose-100 text-rose-700 rounded-lg flex items-start gap-3 text-sm font-medium">
                    <i data-lucide="alert-triangle" class="w-5 h-5 shrink-0 mt-0.5"></i>
                    <div>
                        <ul class="list-disc pl-4 space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="card-premium overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="table-premium">
                        <thead>
                            <tr>
                                <th>Nama Periode</th>
                                <th>Tanggal Mulai</th>
                                <th>Tanggal Selesai</th>
                                <th>Status</th>
                                <th class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($periodes as $p)
                            <tr>
                                <td class="whitespace-nowrap font-bold text-surface-900">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center text-white font-bold text-sm shadow-sm shrink-0">
                                            {{ substr($p->nama_periode, 0, 2) }}
                                        </div>
                                        <span>{{ $p->nama_periode }}</span>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap font-semibold text-surface-700">
                                    {{ $p->tanggal_mulai->format('d M Y') }}
                                </td>
                                <td class="whitespace-nowrap font-semibold text-surface-700">
                                    {{ $p->tanggal_selesai->format('d M Y') }}
                                </td>
                                <td class="whitespace-nowrap">
                                    @if($p->status_aktif)
                                        <span class="status-badge bg-emerald-100 text-emerald-700 border-emerald-200">Aktif</span>
                                    @else
                                        <span class="status-badge bg-surface-100 text-surface-500 border-surface-200">Tidak Aktif</span>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap text-right">
                                    <div class="flex justify-end gap-1.5">
                                        <a href="{{ route('admin.periode-pkl.edit', $p) }}" class="p-2 bg-brand-50 text-brand-600 rounded-lg hover:bg-brand-100 transition-colors" title="Edit">
                                            <i data-lucide="edit-2" class="w-4 h-4"></i>
                                        </a>
                                        @if(!$p->status_aktif)
                                        <form action="{{ route('admin.periode-pkl.destroy', $p) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus periode ini? Data pengajuan yang dikaitkan akan kehilangan periodenya.')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-2 bg-rose-50 text-rose-600 rounded-lg hover:bg-rose-100 transition-colors" title="Hapus">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state">
                                        <div class="empty-state-icon mx-auto">
                                            <i data-lucide="calendar" class="w-8 h-8"></i>
                                        </div>
                                        <p class="text-surface-400 font-medium mt-3">Belum ada data periode PKL.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($periodes->hasPages())
                <div class="p-4 sm:p-5 md:p-6 border-t border-surface-100 bg-surface-50/30">
                    {{ $periodes->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

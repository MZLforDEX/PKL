<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-extrabold text-lg md:text-2xl text-surface-900 tracking-tight">Daftar Tempat PKL</h2>
                <p class="text-sm text-surface-500 mt-0.5 hidden sm:block">Kelola mitra industri untuk program PKL.</p>
            </div>
            <a href="{{ route('admin.tempat-pkl.create') }}" class="btn-primary w-full sm:w-auto">
                <i data-lucide="plus-circle" class="w-4 h-4 mr-2"></i>
                Tambah Tempat PKL
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
                                <th>Nama Instansi</th>
                                <th>Bidang Usaha</th>
                                <th>Kuota</th>
                                <th class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tempatPkl as $t)
                            <tr>
                                <td class="whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-orange-500 to-amber-500 flex items-center justify-center text-white shrink-0">
                                            <i data-lucide="building-2" class="w-4 h-4"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-surface-900">{{ $t->nama_tempat }}</div>
                                            <div class="text-[11px] text-surface-400 font-medium">#{{ str_pad($t->id, 4, '0', STR_PAD_LEFT) }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap text-sm text-surface-600 font-medium">
                                    {{ $t->bidang_usaha }}
                                </td>
                                <td class="whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold bg-brand-50 text-brand-600 border border-brand-100">
                                        <i data-lucide="users" class="w-3 h-3"></i>
                                        {{ $t->kuota }} Siswa
                                    </span>
                                </td>
                                <td class="whitespace-nowrap text-right">
                                    <div class="flex justify-end gap-1.5">
                                        <a href="{{ route('admin.tempat-pkl.edit', $t) }}" class="p-2 bg-amber-50 text-amber-600 rounded-lg hover:bg-amber-100 transition-colors" title="Edit">
                                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                                        </a>
                                        <form action="{{ route('admin.tempat-pkl.destroy', $t) }}" method="POST" onsubmit="return confirm('Yakin hapus?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-2 bg-rose-50 text-rose-600 rounded-lg hover:bg-rose-100 transition-colors" title="Hapus">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4">
                                    <div class="empty-state">
                                        <div class="empty-state-icon mx-auto">
                                            <i data-lucide="database" class="w-8 h-8"></i>
                                        </div>
                                        <p class="text-surface-400 font-medium mt-3">Belum ada data tempat PKL.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($tempatPkl->hasPages())
                <div class="p-4 sm:p-5 md:p-6 border-t border-surface-100 bg-surface-50/30">
                    {{ $tempatPkl->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

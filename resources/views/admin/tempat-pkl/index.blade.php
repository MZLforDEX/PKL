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

            {{-- Search & Filter Form --}}
            <form method="GET" action="{{ route('admin.tempat-pkl.index') }}" class="mb-5 flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1 max-w-sm">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-surface-400">
                        <i data-lucide="search" class="w-4 h-4"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari tempat PKL..." class="form-input-premium !pl-9 !py-2 text-sm w-full">
                </div>

                <div class="flex items-center gap-2">
                    <select name="periode_id" onchange="this.form.submit()" class="form-input-premium !py-2 text-sm cursor-pointer">
                        <option value="">Semua Periode</option>
                        @foreach($periodes as $p)
                            <option value="{{ $p->id }}" {{ $selectedPeriodeId == $p->id ? 'selected' : '' }}>
                                {{ $p->nama_periode }}
                            </option>
                        @endforeach
                    </select>

                    @if(request('search') || request('periode_id'))
                        <a href="{{ route('admin.tempat-pkl.index') }}" class="btn-secondary !py-2 !px-3 inline-flex items-center justify-center hover:bg-surface-200" title="Reset">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </a>
                    @endif
                    <button type="submit" class="btn-primary !py-2 !px-4 text-sm font-semibold">Filter</button>
                </div>
            </form>

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

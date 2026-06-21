<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-extrabold text-lg md:text-2xl text-surface-900 tracking-tight">Manajemen Data Siswa</h2>
                <p class="text-sm text-surface-500 mt-0.5 hidden sm:block">Kelola seluruh data siswa yang terdaftar di sistem.</p>
            </div>
            <a href="{{ route('admin.siswa.create') }}" class="btn-primary w-full sm:w-auto">
                <i data-lucide="user-plus" class="w-4 h-4 mr-2"></i>
                Tambah Siswa
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

            {{-- Search Form --}}
            <form method="GET" action="{{ route('admin.siswa.index') }}" class="mb-5 flex gap-2 max-w-sm">
                <div class="relative flex-1">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-surface-400">
                        <i data-lucide="search" class="w-4 h-4"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari siswa..." class="form-input-premium !pl-9 !py-2 text-sm w-full">
                </div>
                @if(request('search'))
                    <a href="{{ route('admin.siswa.index') }}" class="btn-secondary !py-2 !px-3 inline-flex items-center justify-center hover:bg-surface-200" title="Reset">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </a>
                @endif
                <button type="submit" class="btn-primary !py-2 !px-4 text-sm font-semibold">Cari</button>
            </form>

            <div class="card-premium overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="table-premium">
                        <thead>
                            <tr>
                                <th>Siswa</th>
                                <th>NIS</th>
                                <th>Kelas & Jurusan</th>
                                <th class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($siswa as $s)
                            <tr>
                                <td class="whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-brand-500 to-brand-600 flex items-center justify-center text-white font-bold text-sm shadow-sm shrink-0">
                                            {{ substr($s->user?->name ?? '-', 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-surface-900">{{ $s->user?->name ?? '-' }}</div>
                                            <div class="text-[11px] text-surface-400 font-medium">{{ $s->user?->email ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap">
                                    <span class="text-sm text-surface-600 font-mono bg-surface-50 px-2.5 py-1 rounded-lg">{{ $s->nis }}</span>
                                </td>
                                <td class="whitespace-nowrap">
                                    <div class="text-sm font-bold text-surface-800">{{ $s->kelas }}</div>
                                    <div class="text-[11px] text-surface-400 font-medium uppercase tracking-wider">{{ $s->jurusan }}</div>
                                </td>
                                <td class="whitespace-nowrap text-right">
                                    <div class="flex justify-end gap-1.5">
                                        <a href="{{ route('admin.siswa.edit', $s) }}" class="p-2 bg-brand-50 text-brand-600 rounded-lg hover:bg-brand-100 transition-colors" title="Edit">
                                            <i data-lucide="edit-2" class="w-4 h-4"></i>
                                        </a>
                                        <form action="{{ route('admin.siswa.destroy', $s) }}" method="POST" onsubmit="return confirm('Yakin hapus?')">
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
                                            <i data-lucide="users" class="w-8 h-8"></i>
                                        </div>
                                        <p class="text-surface-400 font-medium mt-3">Belum ada data siswa.</p>
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

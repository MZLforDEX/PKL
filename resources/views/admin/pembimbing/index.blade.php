<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-extrabold text-lg md:text-2xl text-surface-900 tracking-tight">Manajemen Pembimbing Industri</h2>
                <p class="text-sm text-surface-500 mt-0.5 hidden sm:block">Kelola seluruh akun pembimbing dari mitra industri PKL.</p>
            </div>
            <a href="{{ route('admin.pembimbing-industri.create') }}" class="btn-primary w-full sm:w-auto">
                <i data-lucide="user-plus" class="w-4 h-4 mr-2"></i>
                Tambah Pembimbing
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
                                <th>Nama Pembimbing</th>
                                <th>Tempat PKL / Perusahaan</th>
                                <th>Jabatan / Posisi</th>
                                <th>Kontak / Email</th>
                                <th class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pembimbing as $p)
                            <tr>
                                <td class="whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-indigo-500 to-cyan-500 flex items-center justify-center text-white font-bold text-sm shadow-sm shrink-0">
                                            {{ substr($p->user->name, 0, 1) }}
                                        </div>
                                        <span class="text-sm font-bold text-surface-900">{{ $p->user->name }}</span>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap text-sm text-surface-700">
                                    {{ $p->tempatPkl->nama_tempat }}
                                </td>
                                <td class="whitespace-nowrap text-sm text-surface-600">
                                    {{ $p->jabatan ?? '-' }}
                                </td>
                                <td class="whitespace-nowrap text-sm text-surface-500">
                                    {{ $p->user->email }}
                                </td>
                                <td class="whitespace-nowrap text-right">
                                    <div class="flex justify-end gap-1.5">
                                        <a href="{{ route('admin.pembimbing-industri.edit', $p) }}" class="p-2 bg-brand-50 text-brand-600 rounded-lg hover:bg-brand-100 transition-colors" title="Edit">
                                            <i data-lucide="edit" class="w-4 h-4"></i>
                                        </a>
                                        <form id="delete-form-{{ $p->id }}" action="{{ route('admin.pembimbing-industri.destroy', $p) }}" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="button"
                                                onclick="confirmAction('Konfirmasi Hapus', 'Apakah Anda yakin ingin menghapus akun Pembimbing Industri ini?', 'warning', 'Ya, Hapus', () => document.getElementById('delete-form-{{ $p->id }}').submit())"
                                                class="p-2 bg-rose-50 text-rose-600 rounded-lg hover:bg-rose-100 transition-colors" title="Hapus">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state">
                                        <div class="empty-state-icon mx-auto">
                                            <i data-lucide="user-x" class="w-8 h-8"></i>
                                        </div>
                                        <p class="text-surface-400 font-medium mt-3">Belum ada data pembimbing industri.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($pembimbing->hasPages())
                <div class="p-4 sm:p-5 md:p-6 border-t border-surface-100 bg-surface-50/30">
                    {{ $pembimbing->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

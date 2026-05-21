<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="font-extrabold text-lg md:text-2xl text-surface-900 tracking-tight">Persetujuan Akun Pendaftar</h2>
                <p class="text-sm text-surface-500 mt-0.5 hidden sm:block">Daftar pengguna baru yang memerlukan persetujuan masuk.</p>
            </div>
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
                                <th>Nama / Email</th>
                                <th>Tanggal Daftar</th>
                                <th>Status</th>
                                <th class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                            <tr>
                                <td class="whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-brand-500 to-cyan-500 flex items-center justify-center text-white font-bold text-sm shadow-sm shrink-0">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-surface-900">{{ $user->name }}</div>
                                            <div class="text-[11px] text-surface-400 font-medium tracking-wide">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="whitespace-nowrap text-sm text-surface-600">
                                    {{ $user->created_at->format('d M Y H:i') }}
                                </td>
                                <td class="whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-amber-50 text-amber-600 border border-amber-100">
                                        Menunggu
                                    </span>
                                </td>
                                <td class="whitespace-nowrap text-right">
                                    <div class="flex justify-end gap-1.5">
                                        <form id="approve-form-{{ $user->id }}" action="{{ route('admin.users.approve', $user) }}" method="POST">
                                            @csrf @method('PUT')
                                            <button type="button" onclick="confirmAction('Setujui Akun', 'Apakah Anda yakin ingin menyetujui akun pendaftar ini?', 'info', 'Ya, Setujui', () => document.getElementById('approve-form-{{ $user->id }}').submit())" class="p-2 bg-emerald-50 text-emerald-600 rounded-lg hover:bg-emerald-100 transition-colors" title="Setujui">
                                                <i data-lucide="check" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                        <form id="reject-form-{{ $user->id }}" action="{{ route('admin.users.destroy', $user) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button type="button" onclick="confirmAction('Tolak & Hapus', 'Apakah Anda yakin ingin menolak dan menghapus akun pendaftar ini?', 'warning', 'Ya, Tolak', () => document.getElementById('reject-form-{{ $user->id }}').submit())" class="p-2 bg-rose-50 text-rose-600 rounded-lg hover:bg-rose-100 transition-colors" title="Tolak & Hapus">
                                                <i data-lucide="x" class="w-4 h-4"></i>
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
                                            <i data-lucide="user-check" class="w-8 h-8"></i>
                                        </div>
                                        <p class="text-surface-400 font-medium mt-3">Tidak ada pendaftar yang menunggu persetujuan.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($users->hasPages())
                <div class="p-4 sm:p-5 md:p-6 border-t border-surface-100 bg-surface-50/30">
                    {{ $users->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

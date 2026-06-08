<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-lg md:text-2xl text-surface-900 tracking-tight">Notifikasi Anda</h2>
                <p class="text-xs md:text-sm text-surface-500 mt-1">Pantau pembaruan status pengajuan, jurnal, dan laporan Anda secara real-time.</p>
            </div>
            @if(auth()->user()->unreadNotifications->count() > 0)
                <form action="{{ route('notifications.mark-all-read') }}" method="POST" class="shrink-0">
                    @csrf
                    <button type="submit" class="btn-secondary w-full sm:w-auto flex items-center justify-center gap-1.5 py-2 px-4 text-xs font-bold">
                        <i data-lucide="check-check" class="w-4 h-4"></i>
                        Tandai Semua Dibaca
                    </button>
                </form>
            @endif
        </div>
    </x-slot>

    <div class="py-6 md:py-10 animate-fade-in">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-800 text-sm font-medium flex items-center gap-2.5">
                    <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if($notifications->isEmpty())
                <div class="card-premium p-12 text-center flex flex-col items-center justify-center">
                    <div class="w-16 h-16 rounded-full bg-surface-50 flex items-center justify-center text-surface-400 mb-4 border border-surface-100">
                        <i data-lucide="bell-off" class="w-8 h-8"></i>
                    </div>
                    <h3 class="text-base md:text-lg font-bold text-surface-900">Belum ada notifikasi</h3>
                    <p class="text-sm text-surface-500 mt-1.5 max-w-sm">Notifikasi mengenai pengajuan, jurnal, dan laporan akan muncul di halaman ini.</p>
                </div>
            @else
                <div class="space-y-3.5">
                    @foreach($notifications as $n)
                        @php
                            $isUnread = is_null($n->read_at);
                            $data = $n->data;
                            
                            // Map icon based on title or content
                            $iconName = 'bell';
                            $iconColor = 'text-brand-600 bg-brand-50 border-brand-100';
                            
                            if (isset($data['title'])) {
                                if (str_contains($data['title'], 'Pengajuan')) {
                                    if (isset($data['status'])) {
                                        if ($data['status'] === 'disetujui') {
                                            $iconName = 'check';
                                            $iconColor = 'text-emerald-600 bg-emerald-50 border-emerald-100';
                                        } elseif (in_array($data['status'], ['ditolak', 'revisi'])) {
                                            $iconName = 'alert-triangle';
                                            $iconColor = 'text-rose-600 bg-rose-50 border-rose-100';
                                        } else {
                                            $iconName = 'file-text';
                                            $iconColor = 'text-blue-600 bg-blue-50 border-blue-100';
                                        }
                                    }
                                } elseif (str_contains($data['title'], 'Jurnal')) {
                                    $iconName = 'book-open';
                                    $iconColor = 'text-indigo-600 bg-indigo-50 border-indigo-100';
                                } elseif (str_contains($data['title'], 'Laporan')) {
                                    $iconName = 'file-check';
                                    $iconColor = 'text-amber-600 bg-amber-50 border-amber-100';
                                }
                            }
                        @endphp
                        
                        <div class="card-premium p-4 md:p-5 transition-all duration-300 flex items-start gap-4 {{ $isUnread ? 'bg-indigo-50/15 border-indigo-200/50 shadow-sm' : 'opacity-85' }}">
                            {{-- Icon --}}
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 border {{ $iconColor }}">
                                <i data-lucide="{{ $iconName }}" class="w-5 h-5"></i>
                            </div>

                            {{-- Content --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-2">
                                    <h4 class="text-sm font-bold text-surface-900 truncate">
                                        {{ $data['title'] ?? 'Notifikasi Baru' }}
                                    </h4>
                                    <span class="text-[10px] md:text-xs text-surface-400 shrink-0 font-medium">
                                        {{ $n->created_at->diffForHumans() }}
                                    </span>
                                </div>
                                <p class="text-xs md:text-sm text-surface-600 mt-1 leading-relaxed">
                                    {{ $data['message'] ?? '' }}
                                </p>
                                
                                @if(isset($data['catatan']) && $data['catatan'])
                                    <div class="mt-2.5 p-2.5 rounded-lg bg-surface-50 border border-surface-200/60 text-xs text-surface-500 italic">
                                        Catatan: "{{ $data['catatan'] }}"
                                    </div>
                                @endif
                                
                                <div class="flex items-center gap-3 mt-3.5">
                                    @if(isset($data['pengajuan_id']))
                                        @php
                                            $role = auth()->user()->role;
                                            $routeName = match($role) {
                                                'admin' => 'admin.pengajuan.show',
                                                'guru' => 'guru.pengajuan.show',
                                                'siswa' => 'siswa.pengajuan.show',
                                                'pembimbing_industri' => 'pembimbing.siswa.show',
                                                default => null,
                                            };
                                        @endphp
                                        @if($routeName)
                                            <a href="{{ route($routeName, $data['pengajuan_id']) }}" class="inline-flex items-center text-xs font-bold text-brand-600 hover:text-brand-700 transition-colors gap-1">
                                                <span>Lihat Detail</span>
                                                <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                                            </a>
                                        @endif
                                    @elseif(isset($data['jurnal_id']))
                                        @php
                                            $role = auth()->user()->role;
                                            $routeName = match($role) {
                                                'guru' => 'guru.jurnal.index',
                                                'pembimbing_industri' => 'pembimbing.jurnal.show',
                                                'siswa' => 'siswa.jurnal.index',
                                                default => null,
                                            };
                                            $routeParam = $routeName === 'pembimbing.jurnal.show' ? $data['jurnal_id'] : null;
                                        @endphp
                                        @if($routeName)
                                            <a href="{{ $routeParam ? route($routeName, $routeParam) : route($routeName) }}" class="inline-flex items-center text-xs font-bold text-brand-600 hover:text-brand-700 transition-colors gap-1">
                                                <span>Lihat Jurnal</span>
                                                <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                                            </a>
                                        @endif
                                    @elseif(isset($data['laporan_id']))
                                        @php
                                            $role = auth()->user()->role;
                                            $routeName = match($role) {
                                                'guru' => 'guru.laporan.index',
                                                'siswa' => 'siswa.laporan.index',
                                                default => null,
                                            };
                                        @endphp
                                        @if($routeName)
                                            <a href="{{ route($routeName) }}" class="inline-flex items-center text-xs font-bold text-brand-600 hover:text-brand-700 transition-colors gap-1">
                                                <span>Lihat Laporan</span>
                                                <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                                            </a>
                                        @endif

                                    @endif
                                </div>
                            </div>

                            {{-- Mark as Read Button --}}
                            @if($isUnread)
                                <form action="{{ route('notifications.read', $n->id) }}" method="POST" class="shrink-0 self-center">
                                    @csrf
                                    <button type="submit" class="p-2 text-surface-400 hover:text-brand-600 rounded-lg hover:bg-brand-50 transition-all" title="Tandai telah dibaca">
                                        <i data-lucide="check" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $notifications->links() }}
                </div>
            @endif

        </div>
    </div>
</x-app-layout>

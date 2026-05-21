@php
    $role = Auth::user()->role;
    $dashboardRoute = match($role) {
        'admin' => 'admin.dashboard',
        'guru' => 'guru.dashboard',
        'siswa' => 'siswa.dashboard',
        default => 'dashboard',
    };

    $navItems = match($role) {
        'admin' => [
            ['route' => 'admin.siswa.index', 'label' => 'Siswa', 'icon' => 'users', 'pattern' => 'admin/siswa*'],
            ['route' => 'admin.guru.index', 'label' => 'Guru', 'icon' => 'user-check', 'pattern' => 'admin/guru*'],
            ['route' => 'admin.tempat-pkl.index', 'label' => 'Tempat PKL', 'icon' => 'building-2', 'pattern' => 'admin/tempat-pkl*'],
            ['route' => 'admin.pengajuan.index', 'label' => 'Pengajuan', 'icon' => 'file-text', 'pattern' => 'admin/pengajuan*'],
            ['route' => 'admin.users.unapproved', 'label' => 'Pendaftar', 'icon' => 'user-plus', 'pattern' => 'admin/users*'],
        ],
        'guru' => [
            ['route' => 'guru.pengajuan.index', 'label' => 'Pengajuan', 'icon' => 'file-check', 'pattern' => 'guru/pengajuan*'],
            ['route' => 'guru.jurnal.index', 'label' => 'Jurnal', 'icon' => 'book-open', 'pattern' => 'guru/jurnal*'],
            ['route' => 'guru.laporan.index', 'label' => 'Laporan', 'icon' => 'file-spreadsheet', 'pattern' => 'guru/laporan*'],
            ['route' => 'guru.penilaian.index', 'label' => 'Penilaian', 'icon' => 'award', 'pattern' => 'guru/penilaian*'],
        ],
        'siswa' => [
            ['route' => 'siswa.pengajuan.index', 'label' => 'Pengajuan', 'icon' => 'file-plus', 'pattern' => 'siswa/pengajuan*'],
            ['route' => 'siswa.jurnal.index', 'label' => 'Jurnal', 'icon' => 'book-open', 'pattern' => 'siswa/jurnal*'],
            ['route' => 'siswa.laporan.index', 'label' => 'Laporan', 'icon' => 'file-check', 'pattern' => 'siswa/laporan*'],
        ],
        default => [],
    };

    $roleLabel = match($role) {
        'admin' => 'Administrator',
        'guru' => 'Guru Pembimbing',
        'siswa' => 'Siswa',
        default => 'User',
    };

    $roleColor = match($role) {
        'admin' => 'from-rose-500 to-orange-500',
        'guru' => 'from-emerald-500 to-teal-500',
        'siswa' => 'from-brand-500 to-cyan-500',
        default => 'from-brand-500 to-brand-600',
    };
@endphp

<div x-data="{ sidebarOpen: false }" x-init="$watch('sidebarOpen', val => { document.body.style.overflow = val ? 'hidden' : ''; })" @keydown.window.escape="sidebarOpen = false">
    {{-- Overlay backdrop (mobile only) --}}
    <div x-show="sidebarOpen" @click="sidebarOpen = false" x-cloak x-transition.opacity class="fixed inset-0 z-30 bg-surface-950/40 backdrop-blur-sm lg:hidden"></div>

    {{-- Mobile header --}}
    <div class="fixed top-0 left-0 right-0 z-20 h-14 bg-white/90 backdrop-blur-xl border-b border-surface-200/60 flex items-center justify-between px-4 shadow-sm lg:hidden safe-top">
        <a href="{{ route($dashboardRoute) }}" class="flex items-center gap-2 group">
            <x-application-logo class="h-9 w-auto transition-all group-hover:scale-105 duration-300" />
        </a>
        <button @click="sidebarOpen = !sidebarOpen" class="p-2.5 text-surface-500 hover:text-surface-700 rounded-xl hover:bg-brand-50 transition-all active:scale-95" aria-label="Toggle menu">
            <i data-lucide="menu" class="w-5 h-5"></i>
        </button>
    </div>

    {{-- Sidebar --}}
    <nav class="sidebar max-w-[85vw] -translate-x-full lg:translate-x-0 shadow-2xl lg:shadow-none" :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

        {{-- Logo Area --}}
        <div class="flex items-center justify-between h-16 px-5 border-b border-white/[0.06] shrink-0">
            <a href="{{ route($dashboardRoute) }}" class="flex items-center gap-3 group">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br {{ $roleColor }} flex items-center justify-center shadow-lg">
                    <i data-lucide="hexagon" class="w-5 h-5 text-white"></i>
                </div>
                <div>
                    <span class="text-base font-bold text-white tracking-tight">SPARTA</span>
                    <span class="block text-[10px] text-surface-500 font-medium -mt-0.5">PKL System</span>
                </div>
            </a>
            <button @click="sidebarOpen = false" class="lg:hidden p-2 text-surface-500 hover:text-white rounded-xl hover:bg-white/10 transition-all" aria-label="Close sidebar">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        {{-- Role Badge --}}
        <div class="px-5 pt-5 pb-2">
            <div class="px-3 py-2 rounded-xl bg-white/[0.04] border border-white/[0.06]">
                <span class="text-[10px] font-semibold text-surface-500 uppercase tracking-widest">Role Aktif</span>
                <p class="text-sm font-bold text-white mt-0.5">{{ $roleLabel }}</p>
            </div>
        </div>

        {{-- Nav links --}}
        <div class="flex-1 overflow-y-auto py-3 px-3 space-y-0.5">
            <p class="px-4 pt-2 pb-2 text-[10px] font-semibold text-surface-600 uppercase tracking-widest">Menu Utama</p>

            <a href="{{ route($dashboardRoute) }}" @class([
                'sidebar-link',
                'active' => request()->routeIs('*.dashboard'),
            ])>
                <i data-lucide="layout-dashboard" class="w-[18px] h-[18px] shrink-0"></i>
                <span>Dashboard</span>
            </a>

            <div class="my-2 mx-4 border-t border-white/[0.06]"></div>
            <p class="px-4 pt-2 pb-2 text-[10px] font-semibold text-surface-600 uppercase tracking-widest">Kelola</p>

            @foreach($navItems as $item)
                @php $isActive = request()->routeIs($item['pattern']); @endphp
                <a href="{{ route($item['route']) }}" @class([
                    'sidebar-link',
                    'active' => $isActive,
                ])>
                    <i data-lucide="{{ $item['icon'] }}" class="w-[18px] h-[18px] shrink-0"></i>
                    <span>{{ $item['label'] }}</span>
                    @if($isActive)
                        <span class="ml-auto w-1.5 h-1.5 rounded-full bg-brand-400 animate-pulse-soft"></span>
                    @endif
                </a>
            @endforeach
        </div>

        {{-- User footer --}}
        <div class="shrink-0 border-t border-white/[0.06] p-4 safe-bottom">
            <div class="flex items-center gap-3 px-2 mb-3">
                @if(Auth::user()->avatar)
                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}" class="w-9 h-9 rounded-full object-cover ring-2 ring-white/20 shrink-0">
                @else
                    <div class="w-9 h-9 rounded-full bg-gradient-to-br {{ $roleColor }} flex items-center justify-center text-white font-bold text-sm ring-2 ring-white/10 shrink-0">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                @endif
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-bold text-white truncate">{{ Auth::user()->name }}</p>
                    <p class="text-[10px] font-medium text-surface-500 truncate">{{ Auth::user()->email }}</p>
                </div>
            </div>
            <div class="flex gap-1.5">
                <a href="{{ route('profile.edit') }}" class="flex-1 text-center px-3 py-2 text-xs font-semibold text-surface-400 rounded-lg hover:bg-white/[0.06] hover:text-white transition-all">
                    <i data-lucide="settings" class="w-3.5 h-3.5 inline -mt-0.5 mr-1"></i>Profile
                </a>
                <form id="logout-form" method="POST" action="{{ route('logout') }}" class="flex-1">
                    @csrf
                    <button type="button"
                        onclick="confirmAction('Konfirmasi Keluar', 'Apakah kamu yakin ingin mengakhiri sesi ini?', 'warning', 'Ya, Keluar Sekarang', () => document.getElementById('logout-form').submit())"
                        class="w-full text-center px-3 py-2 text-xs font-semibold text-rose-400 rounded-lg hover:bg-rose-500/10 hover:text-rose-300 transition-all">
                        <i data-lucide="log-out" class="w-3.5 h-3.5 inline -mt-0.5 mr-1"></i>Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>
</div>

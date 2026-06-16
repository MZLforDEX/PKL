@php
    $role = Auth::user()->role;
    $dashboardRoute = match ($role) {
        'admin' => 'admin.dashboard',
        'guru' => 'guru.dashboard',
        'siswa' => 'siswa.dashboard',
        'pembimbing_industri' => 'pembimbing.dashboard',
        default => 'dashboard',
    };

    $navItems = match ($role) {
        'admin' => [
            ['route' => 'admin.siswa.index', 'label' => 'Siswa', 'icon' => 'users', 'pattern' => 'admin/siswa*'],
            ['route' => 'admin.guru.index', 'label' => 'Guru', 'icon' => 'user-check', 'pattern' => 'admin/guru*'],
            ['route' => 'admin.tempat-pkl.index', 'label' => 'Tempat PKL', 'icon' => 'building', 'pattern' => 'admin/tempat-pkl*'],
            ['route' => 'admin.pembimbing-industri.index', 'label' => 'Pembimbing Industri', 'icon' => 'briefcase', 'pattern' => 'admin/pembimbing-industri*'],
            ['route' => 'admin.pengajuan.index', 'label' => 'Pengajuan', 'icon' => 'file-text', 'pattern' => 'admin/pengajuan*'],
            ['route' => 'admin.users.unapproved', 'label' => 'Pendaftar', 'icon' => 'user-plus', 'pattern' => 'admin/users*'],
            ['route' => 'notifications.index', 'label' => 'Notifikasi', 'icon' => 'bell', 'pattern' => 'notifications*'],
        ],
        'guru' => [
            ['route' => 'guru.pengajuan.index', 'label' => 'Pengajuan', 'icon' => 'file-check', 'pattern' => 'guru/pengajuan*'],
            ['route' => 'guru.jurnal.index', 'label' => 'Jurnal', 'icon' => 'book-open', 'pattern' => 'guru/jurnal*'],
            ['route' => 'guru.laporan.index', 'label' => 'Laporan', 'icon' => 'file-spreadsheet', 'pattern' => 'guru/laporan*'],
            ['route' => 'guru.absensi.index', 'label' => 'Absensi', 'icon' => 'calendar', 'pattern' => 'guru/absensi*'],
            ['route' => 'notifications.index', 'label' => 'Notifikasi', 'icon' => 'bell', 'pattern' => 'notifications*'],
        ],
        'siswa' => [
            ['route' => 'siswa.pengajuan.index', 'label' => 'Pengajuan', 'icon' => 'file-plus', 'pattern' => 'siswa/pengajuan*'],
            ['route' => 'siswa.jurnal.index', 'label' => 'Jurnal', 'icon' => 'book-open', 'pattern' => 'siswa/jurnal*'],
            ['route' => 'siswa.laporan.index', 'label' => 'Laporan', 'icon' => 'file-check', 'pattern' => 'siswa/laporan*'],
            ['route' => 'siswa.absensi.index', 'label' => 'Absensi', 'icon' => 'calendar', 'pattern' => 'siswa/absensi*'],
            ['route' => 'notifications.index', 'label' => 'Notifikasi', 'icon' => 'bell', 'pattern' => 'notifications*'],
        ],
        'pembimbing_industri' => [
            ['route' => 'pembimbing.siswa.index', 'label' => 'Siswa PKL', 'icon' => 'users', 'pattern' => 'pembimbing/siswa*'],
            ['route' => 'pembimbing.jurnal.index', 'label' => 'Jurnal PKL', 'icon' => 'book-open', 'pattern' => 'pembimbing/jurnal*'],
            ['route' => 'pembimbing.absensi.index', 'label' => 'Absensi PKL', 'icon' => 'calendar', 'pattern' => 'pembimbing/absensi*'],
            ['route' => 'pembimbing.penilaian.index', 'label' => 'Penilaian PKL', 'icon' => 'award', 'pattern' => 'pembimbing/penilaian*'],
            ['route' => 'notifications.index', 'label' => 'Notifikasi', 'icon' => 'bell', 'pattern' => 'notifications*'],
        ],
        default => [],
    };

    $roleLabel = match ($role) {
        'admin' => 'Administrator',
        'guru' => 'Guru Pembimbing',
        'siswa' => 'Siswa',
        'pembimbing_industri' => 'Pembimbing Industri',
        default => 'User',
    };
@endphp

<!-- Mobile Sidebar Backdrop -->
<div x-show="mobileSidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300"
    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
    x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0" @click="mobileSidebarOpen = false"
    class="fixed inset-0 z-40 bg-zinc-950/40 dark:bg-zinc-950/60 backdrop-blur-sm lg:hidden" x-cloak></div>

<!-- Sidebar Container -->
<aside :class="mobileSidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
    class="fixed top-0 bottom-0 left-0 z-40 w-64 bg-white dark:bg-zinc-900 border-r border-gray-200/80 dark:border-zinc-800 flex flex-col transition-transform duration-300 ease-in-out">

    <!-- Branding Header -->
    <div class="h-16 flex items-center px-6 border-b border-gray-100 dark:border-zinc-800 shrink-0">
        <a href="{{ route($dashboardRoute) }}" class="flex items-center gap-2.5">
            <div
                class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center text-white shadow-sm ring-4 ring-indigo-500/10">
                <i data-lucide="graduation-cap" class="w-[18px] h-[18px]"></i>
            </div>
            <div>
                <span class="text-sm font-extrabold tracking-tight text-gray-900 dark:text-white">SiPKL</span>
                <span
                    class="inline-flex items-center rounded-md bg-indigo-50 dark:bg-indigo-950/30 px-1.5 py-0.5 text-[9px] font-semibold text-indigo-700 dark:text-indigo-400 ring-1 ring-inset ring-indigo-700/10 dark:ring-indigo-400/20 ml-1.5">PKL</span>
            </div>
        </a>
    </div>

    <!-- Active Access Badge -->
    <div class="px-6 py-4 shrink-0">
        <div class="rounded-xl border border-gray-100 dark:border-zinc-800 bg-gray-50/50 dark:bg-zinc-800/30 p-3">
            <span class="text-[9px] font-bold text-gray-400 dark:text-zinc-500 uppercase tracking-wider">Akses
                Sebagai</span>
            <p class="text-xs font-bold text-gray-800 dark:text-zinc-200 mt-0.5">{{ $roleLabel }}</p>
        </div>
    </div>

    <!-- Sidebar Links Scrollable -->
    <nav class="flex-1 overflow-y-auto px-4 py-2 space-y-6">

        <!-- Dashboard Link -->
        <div>
            <a href="{{ route($dashboardRoute) }}"
                class="flex items-center gap-3 px-3 py-2 text-xs font-semibold rounded-lg transition-colors {{ request()->routeIs('*.dashboard') || request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-950/30 dark:text-indigo-400' : 'text-gray-600 dark:text-zinc-400 hover:bg-gray-50 dark:hover:bg-zinc-800/50 hover:text-gray-950 dark:hover:text-white' }}">
                <i data-lucide="layout-dashboard" class="w-[18px] h-[18px] shrink-0"></i>
                <span>Dashboard</span>
            </a>
        </div>

        <!-- Menu Section Group -->
        <div class="space-y-1">
            <span
                class="px-3 text-[10px] font-bold text-gray-400 dark:text-zinc-500 uppercase tracking-wider block mb-2">Layanan
                & Kelola</span>

            @foreach($navItems as $item)
                @php
                    $isActive = request()->is($item['pattern']) || request()->routeIs($item['route']);
                    $unreadCount = ($item['route'] === 'notifications.index') ? auth()->user()->unreadNotifications()->count() : 0;
                @endphp
                <a href="{{ route($item['route']) }}"
                    class="flex items-center gap-3 px-3 py-2 text-xs font-medium rounded-lg transition-colors {{ $isActive ? 'bg-indigo-50 text-indigo-600 dark:bg-indigo-950/30 dark:text-indigo-400 font-semibold' : 'text-gray-600 dark:text-zinc-400 hover:bg-gray-50 dark:hover:bg-zinc-800/50 hover:text-gray-950 dark:hover:text-white' }}">
                    <i data-lucide="{{ $item['icon'] }}" class="w-[18px] h-[18px] shrink-0"></i>
                    <span class="truncate">{{ $item['label'] }}</span>
                    @if($unreadCount > 0)
                        <span class="ml-auto bg-rose-500 text-white rounded-full px-1.5 py-0.5 text-[9px] font-bold">
                            {{ $unreadCount }}
                        </span>
                    @endif
                </a>
            @endforeach
        </div>

    </nav>

    <!-- Sidebar Bottom User Info -->
    <div class="border-t border-gray-100 dark:border-zinc-800 p-4 bg-gray-50/30 dark:bg-zinc-900/50 shrink-0">
        <div class="flex items-center gap-3 px-2">
            @if(auth()->user()->avatar)
                <img class="w-8 h-8 rounded-full object-cover ring-2 ring-indigo-500/10"
                    src="{{ asset('storage/' . auth()->user()->avatar) }}">
            @else
                <div
                    class="w-8 h-8 rounded-full bg-indigo-600 text-white font-bold text-xs flex items-center justify-center ring-2 ring-indigo-500/10">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
            @endif
            <div class="min-w-0 flex-1">
                <p class="text-xs font-bold text-gray-800 dark:text-zinc-200 truncate">{{ auth()->user()->name }}</p>
                <p class="text-[9px] font-medium text-gray-400 dark:text-zinc-500 truncate mt-0.5">
                    {{ auth()->user()->email }}</p>
            </div>
        </div>
    </div>

</aside>
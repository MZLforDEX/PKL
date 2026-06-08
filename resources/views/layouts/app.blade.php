<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ 
    mobileSidebarOpen: false, 
    darkMode: localStorage.getItem('theme') === 'dark' 
}" :class="{ 'dark': darkMode }">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SiPKL') }} — Sistem PKL</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Icons -->
    <script src="https://unpkg.com/lucide@0.400.0"></script>

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" type="text/css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }
        /* SweetAlert2 cancel button text color override */
        .swal2-styled.swal2-cancel {
            color: #374151 !important;
        }
        .dark .swal2-styled.swal2-cancel {
            color: #e4e4e7 !important;
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-900 dark:bg-zinc-950 dark:text-zinc-50 min-h-screen flex antialiased">

    <!-- Sidebar wrapper -->
    @include('layouts.navigation')

    <!-- Main Content Area -->
    <div class="flex-1 min-w-0 flex flex-col min-h-screen lg:pl-64">
        
        <!-- Topbar -->
        <header class="h-16 border-b border-gray-200/80 dark:border-zinc-800 bg-white dark:bg-zinc-900 sticky top-0 z-30 flex items-center justify-between px-4 sm:px-6">
            
            <div class="flex items-center gap-4">
                <!-- Hamburger menu button -->
                <button @click="mobileSidebarOpen = !mobileSidebarOpen" class="p-2 -ml-2 rounded-lg text-gray-500 hover:bg-gray-100 hover:text-gray-900 dark:text-zinc-400 dark:hover:bg-zinc-800 dark:hover:text-white lg:hidden">
                    <i data-lucide="menu" class="w-5 h-5"></i>
                </button>

                <!-- Breadcrumbs -->
                @php
                    $role = Auth::user()->role;
                    $dashboardRoute = match($role) {
                        'admin' => 'admin.dashboard',
                        'guru' => 'guru.dashboard',
                        'siswa' => 'siswa.dashboard',
                        'pembimbing_industri' => 'pembimbing.dashboard',
                        default => 'dashboard',
                    };
                    $segments = request()->segments();
                    $breadcrumbs = [];
                    $url = '';
                    foreach ($segments as $segment) {
                        $url .= '/' . $segment;
                        $breadcrumbs[] = [
                            'name' => ucwords(str_replace('-', ' ', $segment)),
                            'url' => url($url),
                        ];
                    }
                @endphp
                <nav class="hidden sm:flex items-center text-xs font-medium text-gray-500 dark:text-zinc-400">
                    <ol class="inline-flex items-center space-x-1 md:space-x-2">
                        <li class="inline-flex items-center">
                            <a href="{{ route($dashboardRoute) }}" class="inline-flex items-center hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                <i data-lucide="home" class="w-3.5 h-3.5 mr-1.5"></i>
                                Home
                            </a>
                        </li>
                        @foreach($breadcrumbs as $breadcrumb)
                            @if(!in_array(strtolower($breadcrumb['name']), ['admin', 'guru', 'siswa', 'pembimbing']))
                                <li class="inline-flex items-center">
                                    <i data-lucide="chevron-right" class="w-3 h-3 text-gray-400 dark:text-zinc-600 mx-1"></i>
                                    <a href="{{ $breadcrumb['url'] }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors capitalize">
                                        {{ $breadcrumb['name'] }}
                                    </a>
                                </li>
                            @endif
                        @endforeach
                    </ol>
                </nav>
            </div>

            <!-- Topbar Right Actions -->
            <div class="flex items-center gap-2">
                
                <!-- Dark Mode Toggle -->
                <button @click="darkMode = !darkMode; localStorage.setItem('theme', darkMode ? 'dark' : 'light')" class="p-2 text-gray-500 hover:bg-gray-100 dark:text-zinc-400 dark:hover:bg-zinc-800 rounded-lg transition-colors" title="Toggle theme">
                    <span x-show="!darkMode"><i data-lucide="moon" class="w-5 h-5"></i></span>
                    <span x-show="darkMode" x-cloak><i data-lucide="sun" class="w-5 h-5"></i></span>
                </button>

                <!-- Notifications Dropdown -->
                @php
                    $unreadNotifications = auth()->user()->unreadNotifications()->take(5)->get();
                    $unreadCount = auth()->user()->unreadNotifications()->count();
                @endphp
                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    <button @click="open = !open" class="p-2 text-gray-500 hover:bg-gray-100 dark:text-zinc-400 dark:hover:bg-zinc-800 rounded-lg transition-colors relative">
                        <i data-lucide="bell" class="w-5 h-5"></i>
                        @if($unreadCount > 0)
                            <span class="absolute top-1.5 right-1.5 w-4 h-4 bg-indigo-600 text-white rounded-full text-[9px] font-bold flex items-center justify-center">
                                {{ $unreadCount }}
                            </span>
                        @endif
                    </button>

                    <!-- Notifications Menu -->
                    <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute right-0 mt-2 w-80 bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 rounded-xl shadow-xl z-50 overflow-hidden">
                        <div class="px-4 py-3 border-b border-gray-200 dark:border-zinc-800 bg-gray-50 dark:bg-zinc-800/50 flex justify-between items-center">
                            <span class="text-xs font-semibold text-gray-800 dark:text-zinc-200">Notifikasi</span>
                            @if($unreadCount > 0)
                                <form action="{{ route('notifications.mark-all-read') }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-[10px] text-indigo-600 dark:text-indigo-400 hover:underline font-semibold">Tandai semua dibaca</button>
                                </form>
                            @endif
                        </div>
                        <div class="max-h-64 overflow-y-auto divide-y divide-gray-100 dark:divide-zinc-800">
                            @forelse($unreadNotifications->take(5) as $notification)
                                <form action="{{ route('notifications.read', $notification->id) }}" method="POST" id="read-notif-{{ $notification->id }}" class="hidden">
                                    @csrf
                                </form>
                                <a href="#" onclick="event.preventDefault(); document.getElementById('read-notif-{{ $notification->id }}').submit();" class="block px-4 py-3 hover:bg-gray-50 dark:hover:bg-zinc-800/50 transition-colors">
                                    <p class="text-xs text-gray-800 dark:text-zinc-200 font-medium leading-relaxed">{{ $notification->data['message'] ?? '' }}</p>
                                    <span class="text-[9px] text-gray-400 dark:text-zinc-500 mt-1 block">{{ $notification->created_at->diffForHumans() }}</span>
                                </a>
                            @empty
                                <div class="px-4 py-6 text-center text-xs text-gray-400 dark:text-zinc-500">
                                    Tidak ada notifikasi baru
                                </div>
                            @endforelse
                        </div>
                        <div class="p-2 border-t border-gray-200 dark:border-zinc-800 bg-gray-50 dark:bg-zinc-800/30 text-center">
                            <a href="{{ route('notifications.index') }}" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline font-medium">Lihat Semua Notifikasi</a>
                        </div>
                    </div>
                </div>

                <div class="w-px h-6 bg-gray-200 dark:bg-zinc-800 mx-1"></div>

                <!-- User Dropdown Menu -->
                <div class="relative" x-data="{ open: false }" @click.away="open = false">
                    <button @click="open = !open" class="flex items-center gap-2.5 p-1 rounded-lg hover:bg-gray-100 dark:hover:bg-zinc-800 transition-colors text-left">
                        @if(auth()->user()->avatar)
                            <img class="w-8 h-8 rounded-full object-cover ring-2 ring-indigo-500/10" src="{{ asset('storage/' . auth()->user()->avatar) }}">
                        @else
                            <div class="w-8 h-8 rounded-full bg-indigo-600 text-white font-semibold text-xs flex items-center justify-center ring-2 ring-indigo-500/10">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                        @endif
                        <span class="hidden md:block text-xs font-semibold text-gray-700 dark:text-zinc-300">{{ auth()->user()->name }}</span>
                        <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-gray-400"></i>
                    </button>

                    <!-- Dropdown Panel -->
                    <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute right-0 mt-2 w-48 bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 rounded-xl shadow-xl z-50 overflow-hidden">
                        <div class="px-4 py-2.5 bg-gray-50/50 dark:bg-zinc-800/30 border-b border-gray-100 dark:border-zinc-800">
                            <p class="text-xs text-gray-500 dark:text-zinc-400">Masuk sebagai</p>
                            <p class="text-xs font-bold text-gray-800 dark:text-zinc-200 truncate">{{ auth()->user()->email }}</p>
                        </div>
                        <div class="p-1">
                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-3 py-2 text-xs font-medium text-gray-700 hover:bg-gray-50 dark:text-zinc-300 dark:hover:bg-zinc-800 rounded-lg transition-colors">
                                <i data-lucide="user" class="w-4 h-4 text-gray-400"></i>
                                Profil Saya
                            </a>
                            <a href="{{ route('profile.security') }}" class="flex items-center gap-2 px-3 py-2 text-xs font-medium text-gray-700 hover:bg-gray-50 dark:text-zinc-300 dark:hover:bg-zinc-800 rounded-lg transition-colors">
                                <i data-lucide="lock" class="w-4 h-4 text-gray-400"></i>
                                Keamanan
                            </a>
                        </div>
                        <div class="border-t border-gray-100 dark:border-zinc-800 p-1">
                            <form id="logout-form" method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="button" onclick="confirmAction('Konfirmasi Keluar', 'Apakah kamu yakin ingin mengakhiri sesi ini?', 'warning', 'Ya, Keluar Sekarang', () => document.getElementById('logout-form').submit())" class="w-full flex items-center gap-2 px-3 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50 dark:text-rose-400 dark:hover:bg-rose-950/20 rounded-lg transition-colors text-left">
                                    <i data-lucide="log-out" class="w-4 h-4 text-rose-400"></i>
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>

        </header>

        <!-- Page Content -->
        <main class="flex-1 px-4 sm:px-6 py-8">
            <div class="mx-auto max-w-[1600px] w-full">
                @isset($header)
                    <div class="mb-6">
                        {{ $header }}
                    </div>
                @endisset
                {{ $slot }}
            </div>
        </main>

        <!-- Footer -->
        <footer class="h-14 border-t border-gray-200/60 dark:border-zinc-800 bg-white dark:bg-zinc-900 flex items-center justify-center px-4">
            <span class="text-xs text-gray-400 dark:text-zinc-500">&copy; {{ date('Y') }} SiPKL — Sistem Informasi PKL. All rights reserved.</span>
        </footer>

    </div>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        lucide.createIcons();

        // Sync dark mode style on SweetAlert2 globally
        function getSwalColors() {
            const isDark = document.documentElement.classList.contains('dark');
            return {
                background: isDark ? '#18181b' : '#ffffff',
                titleColor: isDark ? '#fafafa' : '#1f2937',
                htmlColor: isDark ? '#a1a1aa' : '#4b5563',
                confirmBtnColor: '#6366f1',
                cancelBtnColor: isDark ? '#27272a' : '#e5e7eb',
                cancelBtnTextColor: isDark ? '#e4e4e7' : '#374151'
            };
        }

        function confirmAction(title, text, icon, confirmText, callback) {
            const colors = getSwalColors();
            Swal.fire({
                title: `<span style="color: ${colors.titleColor}; font-weight: 700; font-size: 1.25rem;">${title}</span>`,
                html: `<p style="color: ${colors.htmlColor}; font-size: 0.875rem; margin-bottom: 0;">${text}</p>`,
                icon: icon,
                showCancelButton: true,
                confirmButtonText: confirmText,
                cancelButtonText: 'Batal',
                confirmButtonColor: colors.confirmBtnColor,
                cancelButtonColor: colors.cancelBtnColor,
                background: colors.background,
                buttonsStyling: true,
                customClass: {
                    confirmButton: 'px-4 py-2 font-semibold text-sm rounded-lg text-white transition-all',
                    cancelButton: 'px-4 py-2 font-semibold text-sm rounded-lg transition-all ml-2 text-gray-700 dark:text-zinc-300'
                }
            }).then((result) => {
                if (result.isConfirmed && typeof callback === 'function') {
                    callback();
                }
            });
        }
    </script>
    @stack('scripts')
</body>

</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SPARTA') }} — Sistem PKL</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Icons -->
        <script src="https://unpkg.com/lucide@latest"></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-surface-50">
        @include('layouts.navigation')

        <div class="lg:ml-[272px] pt-14 lg:pt-0 min-h-screen flex flex-col">
            @isset($header)
                <div class="page-header shrink-0">
                    <div class="page-header-content">
                        {{ $header }}
                    </div>
                </div>
            @endisset

            <main class="flex-1 w-full max-w-full overflow-x-hidden safe-bottom">
                {{ $slot }}
            </main>

            <!-- Footer -->
            <footer class="border-t border-surface-100 py-4 px-6">
                <div class="max-w-7xl mx-auto text-center text-xs text-surface-400">
                    &copy; {{ date('Y') }} SPARTA — Sistem Informasi PKL. All rights reserved.
                </div>
            </footer>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            lucide.createIcons();

            function confirmAction(title, text, icon, confirmText, callback) {
                Swal.fire({
                    title: `<span class="text-surface-800 font-extrabold">${title}</span>`,
                    html: `<p class="text-surface-500 font-medium">${text}</p>`,
                    icon: icon,
                    iconColor: icon === 'success' ? '#10b981' : (icon === 'warning' ? '#f59e0b' : '#6366f1'),
                    showCancelButton: true,
                    confirmButtonText: confirmText,
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#4f46e5',
                    cancelButtonColor: '#f1f5f9',
                    background: '#ffffff',
                    customClass: {
                        popup: 'rounded-2xl border-none shadow-2xl p-8',
                        confirmButton: 'px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-indigo-500 rounded-xl font-bold text-sm shadow-lg transition-all text-white ml-2 hover:from-indigo-700 hover:to-indigo-600',
                        cancelButton: 'px-6 py-2.5 bg-surface-50 rounded-xl font-bold text-sm text-surface-500 hover:bg-surface-100 transition-all mr-2 border border-surface-200'
                    },
                    buttonsStyling: false,
                    showClass: { popup: 'animate__animated animate__fadeInDown animate__faster' },
                    hideClass: { popup: 'animate__animated animate__fadeOutUp animate__faster' }
                }).then((result) => {
                    if (result.isConfirmed && typeof callback === 'function') {
                        callback();
                    }
                });
            }
        </script>
    </body>
</html>

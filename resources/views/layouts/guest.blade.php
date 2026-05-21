<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SPARTA') }} — Login</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-surface-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative overflow-hidden bg-surface-950">

            {{-- Background --}}
            <div class="absolute inset-0">
                <div class="absolute inset-0 bg-dots opacity-[0.03]"></div>
                <div class="absolute -top-40 -left-40 w-[500px] h-[500px] bg-brand-600/20 rounded-full blur-[100px]"></div>
                <div class="absolute -bottom-40 -right-40 w-[500px] h-[500px] bg-cyan-500/15 rounded-full blur-[100px]"></div>
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[300px] h-[300px] bg-brand-400/10 rounded-full blur-[80px]"></div>
            </div>

            {{-- Logo --}}
            <div class="relative z-10 mb-8">
                <a href="/" class="flex flex-col items-center group">
                    <div class="w-16 h-16 rounded-2xl bg-surface-900 border border-white/[0.08] flex items-center justify-center shadow-2xl shadow-brand-500/10 group-hover:scale-105 transition-transform duration-300 overflow-hidden">
                        <x-application-logo class="w-16 h-16 object-contain" />
                    </div>
                    <span class="mt-4 text-2xl font-extrabold tracking-tight text-white">SPARTA</span>
                    <span class="text-xs text-surface-500 font-medium mt-0.5">Sistem Informasi PKL</span>
                </a>
            </div>

            {{-- Card --}}
            <div class="w-full sm:max-w-md relative z-10 px-4 sm:px-0">
                <div class="bg-white/[0.04] backdrop-blur-2xl border border-white/[0.08] rounded-2xl px-8 sm:px-10 py-10 shadow-2xl">
                    {{ $slot }}
                </div>

                <div class="mt-8 text-center text-xs text-surface-600">
                    &copy; {{ date('Y') }} SPARTA — Sistem Informasi PKL. All rights reserved.
                </div>
            </div>
        </div>
    </body>
</html>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    x-data="{ darkMode: localStorage.getItem('theme') === 'dark' }" :class="{ 'dark': darkMode }">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SiPKL — Sistem Informasi PKL SMK</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <!-- Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] {
            display: none !important;
        }

        body {
            font-family: 'Inter', sans-serif;
        }

        /* Essential styles for the full-screen background slider */
        .slider-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
        }

        .slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            opacity: 0;
            transition: opacity 1.5s ease-in-out;
        }

        .slide.active {
            opacity: 1;
        }

        /* Dark overlay to ensure text readability */
        .slider-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to bottom, rgba(255,255,255,0.85) 0%, rgba(255,255,255,0.7) 100%);
            z-index: 0;
            transition: background 0.3s ease;
        }

        .dark .slider-overlay {
            background: linear-gradient(to bottom, rgba(9, 9, 11, 0.9) 0%, rgba(9, 9, 11, 0.8) 100%);
        }

        /* Glassmorphism card styles from Stitch design */
        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: background 0.3s ease, border-color 0.3s ease;
        }

        .dark .glass-card {
            background: rgba(24, 24, 27, 0.8);
            border-color: rgba(255, 255, 255, 0.05);
        }
    </style>
</head>

<body
    class="bg-slate-50 text-slate-900 dark:bg-zinc-950 dark:text-zinc-50 antialiased selection:bg-indigo-500/30 transition-colors duration-300 min-h-screen flex flex-col">

    <!-- BEGIN: BackgroundSlider -->
    <div class="slider-container" data-purpose="background-slider">
        <div class="slide active" style="background-image: url('{{ asset('images/slider-siswa.jpg') }}');"></div>
        <div class="slide" style="background-image: url('{{ asset('images/slider-sekolah.jpg') }}');"></div>
        <div class="slider-overlay"></div>
    </div>
    <!-- END: BackgroundSlider -->    <div class="relative min-h-screen flex flex-col justify-between z-10">

        <!-- Header / Navigation -->
        <header class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between z-10">
            <div class="flex items-center gap-3">
                <div
                    class="w-9 h-9 rounded-lg bg-indigo-600 flex items-center justify-center text-white shadow-sm ring-4 ring-indigo-500/10">
                    <i data-lucide="graduation-cap" class="w-5 h-5"></i>
                </div>
                <div>
                    <span class="text-base font-extrabold tracking-tight text-gray-900 dark:text-white">SiPKL</span>
                    <span
                        class="inline-flex items-center rounded bg-indigo-50 dark:bg-indigo-950/30 px-1.5 py-0.5 text-[10px] font-medium text-indigo-700 dark:text-indigo-400 ring-1 ring-inset ring-indigo-700/10 dark:ring-indigo-400/20 ml-1.5">PKL</span>
                </div>
            </div>

            <div class="flex items-center gap-3 sm:gap-4">
                <!-- Theme toggle -->
                <button @click="darkMode = !darkMode; localStorage.setItem('theme', darkMode ? 'dark' : 'light')"
                    class="p-2 text-gray-500 hover:bg-gray-100 dark:text-zinc-400 dark:hover:bg-zinc-900 rounded-lg transition-colors">
                    <span x-show="!darkMode"><i data-lucide="moon" class="w-5 h-5"></i></span>
                    <span x-show="darkMode" x-cloak><i data-lucide="sun" class="w-5 h-5"></i></span>
                </button>

                @auth
                    <a href="{{ url('/dashboard') }}" class="btn-primary">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn-secondary">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="btn-primary hidden sm:inline-flex">
                        Daftar
                    </a>
                @endauth
            </div>
        </header>

        <!-- Hero Section -->
        <main
            class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-20 flex flex-col items-center text-center z-10">

            <!-- Badges -->
            <div
                class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-50 dark:bg-indigo-950/40 border border-indigo-100 dark:border-indigo-900 text-xs font-semibold text-indigo-700 dark:text-indigo-400 mb-6 animate-fade-in shadow-sm">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                SiPKL PKL Management
            </div>

            <!-- Title -->
            <h1
                class="text-4xl sm:text-5xl md:text-6xl font-black tracking-tight text-gray-900 dark:text-white max-w-4xl leading-tight">
                Transformasi Digital Program <br class="hidden sm:inline" />
                <span
                    class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 via-violet-600 to-indigo-500 dark:from-indigo-400 dark:via-violet-400 dark:to-indigo-300">Kerja Lapangan</span> Siswa.
            </h1>

            <!-- Subtitle -->
            <p class="text-base sm:text-lg text-gray-500 dark:text-zinc-400 max-w-2xl mt-4 leading-relaxed">
                Platform modern untuk pengajuan tempat PKL, validasi jurnal harian, absensi kehadiran, dan penilaian akhir secara transparan dan akurat.
            </p>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3.5 mt-8 w-full sm:w-auto">
                <a href="{{ route('login') }}"
                    class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl font-bold text-sm shadow-md hover:shadow-indigo-500/20 active:scale-[0.98] transition-all">
                    Mulai Sekarang
                    <i data-lucide="arrow-right" class="w-4 h-4 ml-2"></i>
                </a>
                <a href="{{ route('guide') }}"
                    class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3.5 bg-white dark:bg-zinc-900 hover:bg-gray-50 dark:hover:bg-zinc-800 text-gray-700 dark:text-zinc-200 rounded-xl font-bold text-sm border border-gray-200 dark:border-zinc-800 active:scale-[0.98] transition-all">
                    <i data-lucide="book-open" class="w-4 h-4 mr-2"></i>
                    Panduan Penggunaan
                </a>
            </div>

            <!-- Features Grid (Glassmorphism from Stitch) -->
            <div class="grid sm:grid-cols-3 gap-6 max-w-4xl w-full mt-28">
                <!-- Card 1 -->
                <div class="glass-card p-8 rounded-2xl text-left shadow-lg transition-transform hover:-translate-y-1 duration-300">
                    <div class="w-10 h-10 bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 rounded-lg flex items-center justify-center mb-6">
                        <i data-lucide="file-text" class="w-5 h-5"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-3">Pengajuan Online</h3>
                    <p class="text-gray-500 dark:text-zinc-400 text-xs leading-relaxed">
                        Ajukan tempat PKL mandiri, upload kelengkapan berkas, dan pantau status persetujuan secara real-time.
                    </p>
                </div>

                <!-- Card 2 -->
                <div class="glass-card p-8 rounded-2xl text-left shadow-lg transition-transform hover:-translate-y-1 duration-300">
                    <div class="w-10 h-10 bg-purple-50 dark:bg-purple-950/40 text-purple-600 dark:text-purple-400 rounded-lg flex items-center justify-center mb-6">
                        <i data-lucide="book-open" class="w-5 h-5"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-3">Jurnal &amp; Absensi</h3>
                    <p class="text-gray-500 dark:text-zinc-400 text-xs leading-relaxed">
                        Catat jurnal harian lengkap dengan foto dokumentasi dan lakukan absensi harian dengan akurat.
                    </p>
                </div>

                <!-- Card 3 -->
                <div class="glass-card p-8 rounded-2xl text-left shadow-lg transition-transform hover:-translate-y-1 duration-300">
                    <div class="w-10 h-10 bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 rounded-lg flex items-center justify-center mb-6">
                        <i data-lucide="award" class="w-5 h-5"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-3">Penilaian &amp; Sertifikat</h3>
                    <p class="text-gray-500 dark:text-zinc-400 text-xs leading-relaxed">
                        Perolehan nilai akhir otomatis hasil akumulasi pembimbing dan cetak sertifikat resmi digital.
                    </p>
                </div>
            </div>

        </main>

        <!-- Footer -->
        <footer
            class="w-full border-t border-gray-200/60 dark:border-zinc-800 py-6 text-center text-xs text-gray-400 dark:text-zinc-500 z-10">
            &copy; {{ date('Y') }} SiPKL — Sistem PKL. All rights reserved.
        </footer>

    </div>

    <script>
        lucide.createIcons();

        // Background Image Slider Logic
        document.addEventListener('DOMContentLoaded', function() {
            const slides = document.querySelectorAll('.slide');
            let currentSlide = 0;
            const slideInterval = 5000; // 5 seconds

            function nextSlide() {
                // Remove active class from current slide
                slides[currentSlide].classList.remove('active');
                
                // Increment slide index
                currentSlide = (currentSlide + 1) % slides.length;
                
                // Add active class to new slide
                slides[currentSlide].classList.add('active');
            }

            // Initialize the interval
            setInterval(nextSlide, slideInterval);
        });
    </script>
</body>

</html>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Panduan Penggunaan - PKL SMK</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }

        .hero-gradient {
            background: radial-gradient(circle at 50% 50%, rgba(79, 70, 229, 0.05) 0%, rgba(255, 255, 255, 0) 50%);
        }
    </style>
</head>

<body class="antialiased bg-white text-slate-900 hero-gradient min-h-screen">
    <!-- Navigation -->
    <nav class="max-w-7xl mx-auto px-6 py-8 flex items-center justify-between">
        <a href="/" class="flex items-center space-x-3 group">
            <div
                class="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center group-hover:bg-indigo-100 transition-colors">
                <i data-lucide="arrow-left" class="w-6 h-6 text-indigo-600"></i>
            </div>
            <span class="text-xl font-bold tracking-tight text-slate-800">Kembali ke Beranda</span>
        </a>
    </nav>

    <main class="max-w-5xl mx-auto px-6 py-12 md:py-20">
        <div class="text-center mb-16 md:mb-24">
            <div
                class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-indigo-50 border border-indigo-100 text-indigo-600 text-xs font-bold uppercase tracking-wider mb-6">
                <span>Panduan Lengkap Siswa</span>
            </div>
            <h1 class="text-4xl md:text-6xl font-bold text-slate-900 mb-6">Cara Menggunakan Aplikasi</h1>
            <p class="text-lg text-slate-500 max-w-2xl mx-auto leading-relaxed italic">
                Ikuti alur kerja berikut untuk memastikan proses Praktik Kerja Lapangan (PKL) Anda berjalan lancar dan
                terdata dengan baik.
            </p>
        </div>

        <!-- Step by Step Guide -->
        <div class="space-y-12 md:space-y-24">
            <!-- Step 1 -->
            <div class="grid md:grid-cols-2 gap-8 md:gap-16 items-center">
                <div class="order-2 md:order-1">
                    <div
                        class="w-16 h-16 bg-indigo-600 text-white rounded-2xl flex items-center justify-center text-2xl font-bold mb-6 shadow-xl shadow-indigo-200">
                        1</div>
                    <h2 class="text-3xl font-bold text-slate-900 mb-4">Cari & Ajukan Tempat PKL</h2>
                    <p class="text-slate-500 text-lg leading-relaxed mb-6">
                        Langkah pertama adalah mencari mitra industri yang sesuai dengan jurusan Anda. Setelah menemukan
                        tempat yang cocok, kirimkan pengajuan secara online melalui dashboard siswa.
                    </p>
                    <ul class="space-y-3">
                        <li class="flex items-center space-x-3 text-slate-700 font-medium text-sm md:text-base">
                            <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-500"></i>
                            <span>Pilih dari daftar mitra yang tersedia</span>
                        </li>
                        <li class="flex items-center space-x-3 text-slate-700 font-medium text-sm md:text-base">
                            <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-500"></i>
                            <span>Unggah dokumen pendukung jika diperlukan</span>
                        </li>
                    </ul>
                </div>
                <div class="order-1 md:order-2 card-premium p-4 md:p-8 bg-slate-50 border-slate-100 rotate-2">
                    <div
                        class="aspect-video bg-white rounded-xl shadow-inner flex items-center justify-center border border-slate-200 overflow-hidden">
                        <i data-lucide="search" class="w-20 h-20 text-indigo-100"></i>
                    </div>
                </div>
            </div>

            <!-- Step 2 -->
            <div class="grid md:grid-cols-2 gap-8 md:gap-16 items-center">
                <div class="card-premium p-4 md:p-8 bg-slate-50 border-slate-100 -rotate-2">
                    <div
                        class="aspect-video bg-white rounded-xl shadow-inner flex items-center justify-center border border-slate-200 overflow-hidden">
                        <i data-lucide="clock" class="w-20 h-20 text-amber-100"></i>
                    </div>
                </div>
                <div>
                    <div
                        class="w-16 h-16 bg-amber-500 text-white rounded-2xl flex items-center justify-center text-2xl font-bold mb-6 shadow-xl shadow-amber-200">
                        2</div>
                    <h2 class="text-3xl font-bold text-slate-900 mb-4">Pantau Status & Guru Pembimbing</h2>
                    <p class="text-slate-500 text-lg leading-relaxed mb-6">
                        Setelah mengajukan, pantau status pengajuan Anda secara berkala. Admin akan memverifikasi dan
                        sistem akan menugaskan Guru Pembimbing untuk Anda.
                    </p>
                    <div class="p-4 bg-amber-50 rounded-xl border border-amber-100">
                        <p class="text-sm text-amber-800 font-medium">Tips: Anda hanya bisa mulai mengisi jurnal setelah
                            status berubah menjadi "Sedang PKL".</p>
                    </div>
                </div>
            </div>

            <!-- Step 3 -->
            <div class="grid md:grid-cols-2 gap-8 md:gap-16 items-center">
                <div class="order-2 md:order-1">
                    <div
                        class="w-16 h-16 bg-indigo-600 text-white rounded-2xl flex items-center justify-center text-2xl font-bold mb-6 shadow-xl shadow-indigo-200">
                        3</div>
                    <h2 class="text-3xl font-bold text-slate-900 mb-4">Pengisian Jurnal Harian</h2>
                    <p class="text-slate-500 text-lg leading-relaxed mb-6">
                        Selama masa PKL, Anda wajib mengisi jurnal kegiatan setiap hari. Ceritakan apa yang Anda
                        kerjakan, pelajari, dan alami di tempat industri.
                    </p>
                    <div class="flex items-center space-x-4">
                        <div class="flex -space-x-2">
                            <div
                                class="w-8 h-8 rounded-full bg-indigo-100 border-2 border-white flex items-center justify-center text-[10px] font-bold">
                                MON</div>
                            <div
                                class="w-8 h-8 rounded-full bg-indigo-200 border-2 border-white flex items-center justify-center text-[10px] font-bold">
                                TUE</div>
                            <div
                                class="w-8 h-8 rounded-full bg-indigo-300 border-2 border-white flex items-center justify-center text-[10px] font-bold">
                                WED</div>
                        </div>
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Wajib diisi setiap hari
                            kerja</span>
                    </div>
                </div>
                <div class="order-1 md:order-2 card-premium p-4 md:p-8 bg-slate-50 border-slate-100 rotate-1">
                    <div
                        class="aspect-video bg-white rounded-xl shadow-inner flex items-center justify-center border border-slate-200 overflow-hidden">
                        <i data-lucide="book-open" class="w-20 h-20 text-indigo-100"></i>
                    </div>
                </div>
            </div>

            <!-- Step 4 -->
            <div class="grid md:grid-cols-2 gap-8 md:gap-16 items-center">
                <div class="card-premium p-4 md:p-8 bg-slate-50 border-slate-100 -rotate-1">
                    <div
                        class="aspect-video bg-white rounded-xl shadow-inner flex items-center justify-center border border-slate-200 overflow-hidden">
                        <i data-lucide="upload-cloud" class="w-20 h-20 text-indigo-100"></i>
                    </div>
                </div>
                <div>
                    <div
                        class="w-16 h-16 bg-indigo-600 text-white rounded-2xl flex items-center justify-center text-2xl font-bold mb-6 shadow-xl shadow-indigo-200">
                        4</div>
                    <h2 class="text-3xl font-bold text-slate-900 mb-4">Pengumpulan Laporan Akhir</h2>
                    <p class="text-slate-500 text-lg leading-relaxed mb-6">
                        Di akhir masa PKL, susunlah laporan sesuai format yang ditentukan oleh sekolah dan unggah file
                        PDF-nya ke dalam sistem.
                    </p>
                    <div
                        class="flex items-center space-x-2 px-3 py-2 bg-emerald-50 text-emerald-700 rounded-lg text-sm font-bold w-fit">
                        <i data-lucide="file-text" class="w-4 h-4"></i>
                        <span>Format File: PDF (Max 5MB)</span>
                    </div>
                </div>
            </div>

            <!-- Step 5 -->
            <div class="grid md:grid-cols-2 gap-8 md:gap-16 items-center">
                <div>
                    <div
                        class="w-16 h-16 bg-emerald-600 text-white rounded-2xl flex items-center justify-center text-2xl font-bold mb-6 shadow-xl shadow-emerald-200">
                        5</div>
                    <h2 class="text-3xl font-bold text-slate-900 mb-4">Penilaian & Cetak Sertifikat</h2>
                    <p class="text-slate-500 text-lg leading-relaxed mb-6">
                        Setelah laporan diverifikasi dan nilai diberikan oleh Guru, Anda dapat langsung melihat hasil
                        penilaian dan mencetak sertifikat PKL profesional Anda.
                    </p>
                    <a href="{{ route('login') }}"
                        class="inline-flex items-center space-x-2 text-indigo-600 font-bold hover:underline transition-all">
                        <span>Mulai pengalaman Anda sekarang</span>
                        <i data-lucide="chevron-right" class="w-5 h-5"></i>
                    </a>
                </div>
                <div class="card-premium p-4 md:p-8 bg-slate-50 border-slate-100 rotate-3">
                    <div
                        class="aspect-video bg-white rounded-xl shadow-inner flex items-center justify-center border border-slate-200 overflow-hidden">
                        <i data-lucide="award" class="w-20 h-20 text-emerald-100"></i>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer
        class="max-w-7xl mx-auto px-6 py-12 border-t border-slate-100 text-center text-slate-400 text-sm font-medium">
        &copy; {{ date('Y') }} PKL SMK - Sistem Informasi Pengajuan PKL Terintegrasi
    </footer>

    <script>
        lucide.createIcons();
    </script>
</body>

</html>
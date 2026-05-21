<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SPARTA — Sistem Informasi Pengajuan dan Pemantauan PKL</title>
    <meta name="description" content="Platform terintegrasi untuk pengajuan, pemantauan, validasi jurnal, hingga sertifikasi industri secara profesional.">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
        }
        h1, h2, h3, .font-display {
            font-family: 'Outfit', sans-serif;
        }

        /* Ambient Glowing Mesh Backgrounds */
        .ambient-mesh {
            background-color: #020617;
            background-image: 
                radial-gradient(at 10% 10%, rgba(99, 102, 241, 0.15) 0px, transparent 50%),
                radial-gradient(at 90% 20%, rgba(168, 85, 247, 0.12) 0px, transparent 50%),
                radial-gradient(at 50% 80%, rgba(6, 182, 212, 0.1) 0px, transparent 50%),
                radial-gradient(at 80% 90%, rgba(236, 72, 153, 0.08) 0px, transparent 50%);
        }

        .glow-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(100px);
            opacity: 0.6;
            mix-blend-mode: screen;
            pointer-events: none;
            animation: orb-bounce 25s ease-in-out infinite alternate;
        }
        .glow-orb-indigo { width: 500px; height: 500px; background: radial-gradient(circle, rgba(99,102,241,0.2) 0%, transparent 70%); top: -10%; left: -10%; }
        .glow-orb-violet { width: 450px; height: 450px; background: radial-gradient(circle, rgba(139,92,246,0.15) 0%, transparent 70%); bottom: 15%; right: -5%; animation-delay: -6s; }
        .glow-orb-cyan { width: 350px; height: 350px; background: radial-gradient(circle, rgba(6,182,212,0.15) 0%, transparent 70%); top: 35%; left: 35%; animation-delay: -12s; }

        @keyframes orb-bounce {
            0%   { transform: translate(0, 0) scale(1) rotate(0deg); }
            50%  { transform: translate(80px, 60px) scale(1.1) rotate(180deg); }
            100% { transform: translate(-40px, 80px) scale(0.9) rotate(360deg); }
        }

        /* Glassmorphism Styles */
        .glass-panel {
            background: rgba(15, 23, 42, 0.45);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.06);
        }
        .glass-panel-hover {
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .glass-panel-hover:hover {
            transform: translateY(-5px);
            background: rgba(15, 23, 42, 0.6);
            border-color: rgba(99, 102, 241, 0.25);
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.5), 0 0 25px rgba(99, 102, 241, 0.1);
        }

        /* Text gradient enhancements */
        .text-neon-gradient {
            background: linear-gradient(135deg, #a5b4fc 0%, #c084fc 50%, #22d3ee 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Glow effects */
        .shadow-neon-glow {
            box-shadow: 0 0 30px rgba(99, 102, 241, 0.25);
        }
        .shadow-cyan-glow {
            box-shadow: 0 0 30px rgba(6, 182, 212, 0.2);
        }

        /* Custom Scrollbar for Portal view */
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(255,255,255,0.02);
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.1);
            border-radius: 99px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(99, 102, 241, 0.4);
        }

        /* 3D tilt container spacing */
        .tilt-card {
            transform-style: preserve-3d;
            perspective: 1000px;
            transition: transform 0.5s cubic-bezier(0.25, 1, 0.5, 1);
        }
        .tilt-card-inner {
            transform: translateZ(30px);
        }

        /* FAQ Accordion Transitions */
        .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s cubic-bezier(0, 1, 0, 1);
        }
        .faq-item.active .faq-answer {
            max-height: 1000px;
            transition: max-height 0.4s cubic-bezier(1, 0, 1, 0);
        }
        .faq-item .faq-icon {
            transition: transform 0.3s ease;
        }
        .faq-item.active .faq-icon {
            transform: rotate(180deg);
        }

        /* Timeline Connector line glow */
        .timeline-connector {
            background: linear-gradient(to bottom, #4f46e5, #06b6d4, #4f46e5);
            box-shadow: 0 0 10px rgba(99, 102, 241, 0.5);
        }

        /* floating badges animation delay */
        @keyframes float-badge {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }
        .float-badge-1 { animation: float-badge 6s ease-in-out infinite; }
        .float-badge-2 { animation: float-badge 7s ease-in-out infinite; animation-delay: -2s; }
        .float-badge-3 { animation: float-badge 8s ease-in-out infinite; animation-delay: -4s; }
    </style>
</head>

<body class="antialiased bg-surface-950 text-surface-100 selection:bg-brand-500/30 selection:text-white overflow-x-hidden">
    <div class="relative min-h-screen ambient-mesh">
        {{-- Animated background nodes --}}
        <div class="glow-orb glow-orb-indigo"></div>
        <div class="glow-orb glow-orb-violet"></div>
        <div class="glow-orb glow-orb-cyan"></div>
        <div class="absolute inset-0 bg-dots opacity-[0.07]" style="mask-image: radial-gradient(ellipse at center, black 30%, transparent 80%);"></div>

        {{-- Navigation --}}
        <nav class="sticky top-0 z-50 bg-surface-950/70 backdrop-blur-xl border-b border-surface-800/40 px-6 py-4 transition-all duration-300" id="navbar">
            <div class="max-w-7xl mx-auto flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-brand-600 via-brand-500 to-cyan-500 flex items-center justify-center shadow-lg shadow-brand-500/25 relative group overflow-hidden">
                        <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                        <img src="{{ asset('logo.png') }}" alt="Logo SPARTA" class="h-6 w-auto brightness-0 invert relative z-10 transition-transform duration-300 group-hover:scale-110">
                    </div>
                    <div>
                        <span class="text-lg font-extrabold text-white tracking-tight font-display">SPARTA</span>
                        <span class="hidden sm:block text-[10px] text-surface-400 font-medium -mt-0.5 tracking-wider uppercase">PKL Management System</span>
                    </div>
                </div>

                {{-- Desktop Menu Links --}}
                <div class="hidden md:flex items-center gap-8">
                    <a href="#fitur" class="text-sm font-medium text-surface-300 hover:text-white transition-colors duration-200">Fitur</a>
                    <a href="#peran" class="text-sm font-medium text-surface-300 hover:text-white transition-colors duration-200">Portal Peran</a>
                    <a href="#alur" class="text-sm font-medium text-surface-300 hover:text-white transition-colors duration-200">Alur PKL</a>
                    <a href="#faq" class="text-sm font-medium text-surface-300 hover:text-white transition-colors duration-200">FAQ</a>
                    <a href="{{ route('guide') }}" class="text-sm font-medium text-surface-300 hover:text-white transition-colors duration-200 flex items-center gap-1.5">
                        <i data-lucide="book-open" class="w-4 h-4 text-cyan-400"></i> Panduan
                    </a>
                </div>

                <div class="flex items-center gap-3">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="relative group overflow-hidden rounded-xl p-[1px] focus:outline-none focus:ring-2 focus:ring-brand-500">
                            <span class="absolute inset-0 bg-gradient-to-r from-brand-600 to-cyan-500 rounded-xl transition-all duration-300 group-hover:opacity-100"></span>
                            <span class="relative block px-6 py-2.5 rounded-[11px] bg-surface-950 text-sm font-bold text-white transition-colors duration-300 group-hover:bg-transparent">
                                Dashboard
                            </span>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-5 py-2.5 text-sm font-semibold text-surface-300 hover:text-white hover:bg-white/5 rounded-xl transition-all duration-200">Masuk</a>
                        <a href="{{ route('register') }}" class="relative group overflow-hidden rounded-xl p-[1px] focus:outline-none focus:ring-2 focus:ring-brand-500">
                            <span class="absolute inset-0 bg-gradient-to-r from-brand-500 via-purple-500 to-cyan-400 rounded-xl"></span>
                            <span class="relative block px-6 py-2.5 rounded-[11px] bg-surface-950 text-sm font-bold text-white transition-all duration-300 group-hover:bg-transparent shadow-lg shadow-brand-500/10">
                                Daftar Akun
                            </span>
                        </a>
                    @endauth
                </div>
            </div>
        </nav>

        {{-- Hero Section --}}
        <main class="relative z-10 max-w-7xl mx-auto px-6 pt-16 md:pt-24 pb-24">
            <div class="grid lg:grid-cols-12 gap-12 lg:gap-16 items-center">
                {{-- Left Content --}}
                <div class="lg:col-span-7 text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full glass-panel border border-white/5 text-xs font-semibold tracking-wider text-surface-200 mb-8 animate-fade-in">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-cyan-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-cyan-500"></span>
                        </span>
                        <span class="text-brand-300 font-bold">SPARTA v5.1</span> &bull; Sistem Monitoring PKL Generasi Baru
                    </div>

                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-[1.15] text-white mb-6 tracking-tight animate-slide-up">
                        Transformasi Digital<br>
                        <span class="text-neon-gradient font-black">Program Kerja Lapangan</span><br>
                        Siswa Lebih Terarah.
                    </h1>

                    <p class="text-base sm:text-lg text-surface-400 leading-relaxed mb-10 max-w-2xl mx-auto lg:mx-0 animate-slide-up" style="animation-delay: 100ms">
                        Manajemen pengajuan, validasi jurnal harian, absensi kehadiran realtime, pembimbingan terpadu, dan sertifikasi industri dalam satu platform canggih.
                    </p>

                    <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4 mb-14 animate-slide-up" style="animation-delay: 200ms">
                        <button onclick="showCoolAlert()" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 bg-gradient-to-r from-brand-600 via-brand-500 to-cyan-500 text-white rounded-2xl font-bold text-base transition-all duration-300 hover:shadow-neon-glow active:scale-[0.98] group">
                            Mulai Sekarang
                            <i data-lucide="arrow-right" class="w-5 h-5 ml-2 group-hover:translate-x-1.5 transition-transform duration-300"></i>
                        </button>
                        <a href="#peran" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 glass-panel border border-white/10 hover:border-white/20 text-white rounded-2xl font-bold text-base transition-all duration-300 hover:bg-white/[0.06] active:scale-[0.98]">
                            <i data-lucide="layout-grid" class="w-5 h-5 mr-2 text-cyan-400"></i>
                            Eksplor Peran
                        </a>
                    </div>

                    {{-- Stats --}}
                    <div class="grid grid-cols-3 gap-4 sm:gap-6 max-w-xl mx-auto lg:mx-0 animate-slide-up" style="animation-delay: 300ms" id="stats-section">
                        <div class="glass-panel rounded-2xl p-4 sm:p-5 text-center border-white/5 relative group hover:border-brand-500/30 transition-all duration-300">
                            <div class="absolute inset-0 bg-gradient-to-b from-brand-500/5 to-transparent rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            <p class="text-3xl sm:text-4xl font-black text-white font-display" data-target="100">0%</p>
                            <p class="text-[9px] sm:text-[10px] font-bold text-cyan-400 uppercase tracking-widest mt-1">Paperless</p>
                        </div>
                        <div class="glass-panel rounded-2xl p-4 sm:p-5 text-center border-white/5 relative group hover:border-brand-500/30 transition-all duration-300">
                            <div class="absolute inset-0 bg-gradient-to-b from-brand-500/5 to-transparent rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            <p class="text-3xl sm:text-4xl font-black text-white font-display" data-target="500">0</p>
                            <p class="text-[9px] sm:text-[10px] font-bold text-purple-400 uppercase tracking-widest mt-1">Siswa Terdaftar</p>
                        </div>
                        <div class="glass-panel rounded-2xl p-4 sm:p-5 text-center border-white/5 relative group hover:border-brand-500/30 transition-all duration-300">
                            <div class="absolute inset-0 bg-gradient-to-b from-brand-500/5 to-transparent rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            <p class="text-3xl sm:text-4xl font-black text-white font-display" data-target="80">0</p>
                            <p class="text-[9px] sm:text-[10px] font-bold text-emerald-400 uppercase tracking-widest mt-1">Mitra Industri</p>
                        </div>
                    </div>
                </div>

                {{-- Right Visual (Dashboard Mockup) --}}
                <div class="lg:col-span-5 relative mt-8 lg:mt-0 flex justify-center items-center select-none" id="tilt-container">
                    <div class="relative w-full max-w-lg tilt-card">
                        {{-- Ambient glows behind preview --}}
                        <div class="absolute -top-12 -left-12 w-64 h-64 bg-brand-500/10 rounded-full blur-3xl"></div>
                        <div class="absolute -bottom-12 -right-12 w-64 h-64 bg-cyan-500/10 rounded-full blur-3xl"></div>

                        {{-- Frame Border --}}
                        <div class="relative z-10 p-1.5 bg-gradient-to-br from-white/10 to-white/5 rounded-3xl border border-white/10 shadow-[0_0_50px_rgba(99,102,241,0.12)]">
                            <img src="{{ asset('images/hero-system.png') }}" alt="SPARTA Dashboard Preview"
                                class="w-full h-auto rounded-[18px] object-cover transition-transform duration-300">
                        </div>
                    </div>

                    {{-- Floating Glass Cards --}}
                    <div class="absolute -top-6 -right-4 sm:-right-8 float-badge-1 z-20">
                        <div class="glass-panel p-3.5 sm:p-4 rounded-2xl shadow-glass flex items-center gap-3.5 border-white/10">
                            <div class="w-10 h-10 bg-emerald-500/15 text-emerald-400 rounded-xl flex items-center justify-center shrink-0 border border-emerald-500/20 shadow-emerald-500/5">
                                <i data-lucide="check-circle" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <p class="text-[9px] font-bold text-surface-400 uppercase tracking-wider">Jurnal Harian</p>
                                <p class="text-xs sm:text-sm font-extrabold text-white">Disetujui Guru</p>
                            </div>
                        </div>
                    </div>

                    <div class="absolute bottom-10 -left-4 sm:-left-8 float-badge-2 z-20">
                        <div class="glass-panel p-3.5 sm:p-4 rounded-2xl shadow-glass flex items-center gap-3.5 border-white/10">
                            <div class="w-10 h-10 bg-cyan-500/15 text-cyan-400 rounded-xl flex items-center justify-center shrink-0 border border-cyan-500/20 shadow-cyan-500/5">
                                <i data-lucide="award" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <p class="text-[9px] font-bold text-surface-400 uppercase tracking-wider">Sertifikat PKL</p>
                                <p class="text-xs sm:text-sm font-extrabold text-white">Siap Diunduh</p>
                            </div>
                        </div>
                    </div>

                    <div class="absolute bottom-[45%] -right-8 sm:-right-12 float-badge-3 z-20 hidden md:block">
                        <div class="glass-panel p-3 rounded-xl shadow-glass flex items-center gap-2.5 border-white/10">
                            <div class="w-2.5 h-2.5 bg-emerald-500 rounded-full animate-pulse"></div>
                            <span class="text-xs font-semibold text-white">15 Siswa Aktif PKL</span>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        {{-- Core Features Section --}}
        <section id="fitur" class="relative z-10 max-w-7xl mx-auto px-6 py-20 scroll-mt-20">
            <div class="text-center mb-16">
                <span class="text-xs font-bold text-brand-400 uppercase tracking-widest px-3 py-1 rounded-full bg-brand-500/10 border border-brand-500/20">FITUR UNGGULAN</span>
                <h2 class="text-3xl md:text-5xl font-black text-white mt-4 tracking-tight">Menyederhanakan Seluruh Proses PKL</h2>
                <p class="text-surface-400 mt-4 max-w-xl mx-auto text-sm sm:text-base">
                    SPARTA dirancang khusus dengan fitur tangguh untuk mengelola ekosistem PKL secara transparan, akurat, dan terintegrasi.
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                {{-- Feature 1 --}}
                <div class="glass-panel glass-panel-hover rounded-3xl p-8 relative overflow-hidden group">
                    <div class="absolute -top-10 -right-10 w-24 h-24 bg-brand-500/5 rounded-full blur-xl group-hover:bg-brand-500/10 transition-colors"></div>
                    <div class="w-12 h-12 rounded-2xl bg-brand-500/10 text-brand-400 flex items-center justify-center mb-6 border border-brand-500/20">
                        <i data-lucide="file-text" class="w-6 h-6 transition-transform group-hover:scale-110"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Pengajuan Online Mandiri</h3>
                    <p class="text-sm text-surface-400 leading-relaxed">
                        Siswa dapat mengajukan tempat PKL, mengunggah kelengkapan dokumen secara digital, serta memantau status persetujuan secara real-time.
                    </p>
                </div>

                {{-- Feature 2 --}}
                <div class="glass-panel glass-panel-hover rounded-3xl p-8 relative overflow-hidden group">
                    <div class="absolute -top-10 -right-10 w-24 h-24 bg-purple-500/5 rounded-full blur-xl group-hover:bg-purple-500/10 transition-colors"></div>
                    <div class="w-12 h-12 rounded-2xl bg-purple-500/10 text-purple-400 flex items-center justify-center mb-6 border border-purple-500/20">
                        <i data-lucide="book-open" class="w-6 h-6 transition-transform group-hover:scale-110"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Jurnal Kegiatan Terverifikasi</h3>
                    <p class="text-sm text-surface-400 leading-relaxed">
                        Pencatatan harian PKL yang transparan dengan fitur upload foto dokumentasi, langsung ditinjau oleh guru pembimbing dan pembimbing industri.
                    </p>
                </div>

                {{-- Feature 3 --}}
                <div class="glass-panel glass-panel-hover rounded-3xl p-8 relative overflow-hidden group">
                    <div class="absolute -top-10 -right-10 w-24 h-24 bg-cyan-500/5 rounded-full blur-xl group-hover:bg-cyan-500/10 transition-colors"></div>
                    <div class="w-12 h-12 rounded-2xl bg-cyan-500/10 text-cyan-400 flex items-center justify-center mb-6 border border-cyan-500/20">
                        <i data-lucide="clock" class="w-6 h-6 transition-transform group-hover:scale-110"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Absensi Kehadiran Realtime</h3>
                    <p class="text-sm text-surface-400 leading-relaxed">
                        Merekam presensi kehadiran siswa PKL secara terstruktur di sistem untuk menjaga kedisiplinan dan akuntabilitas selama program.
                    </p>
                </div>

                {{-- Feature 4 --}}
                <div class="glass-panel glass-panel-hover rounded-3xl p-8 relative overflow-hidden group">
                    <div class="absolute -top-10 -right-10 w-24 h-24 bg-pink-500/5 rounded-full blur-xl group-hover:bg-pink-500/10 transition-colors"></div>
                    <div class="w-12 h-12 rounded-2xl bg-pink-500/10 text-pink-400 flex items-center justify-center mb-6 border border-pink-500/20">
                        <i data-lucide="users" class="w-6 h-6 transition-transform group-hover:scale-110"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Pembimbingan Terfokus</h3>
                    <p class="text-sm text-surface-400 leading-relaxed">
                        Setiap guru pembimbing dapat melihat siswa bimbingannya secara khusus, memberikan bimbingan langsung, serta melakukan review berkala.
                    </p>
                </div>

                {{-- Feature 5 --}}
                <div class="glass-panel glass-panel-hover rounded-3xl p-8 relative overflow-hidden group">
                    <div class="absolute -top-10 -right-10 w-24 h-24 bg-amber-500/5 rounded-full blur-xl group-hover:bg-amber-500/10 transition-colors"></div>
                    <div class="w-12 h-12 rounded-2xl bg-amber-500/10 text-amber-400 flex items-center justify-center mb-6 border border-amber-500/20">
                        <i data-lucide="bar-chart-3" class="w-6 h-6 transition-transform group-hover:scale-110"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Penilaian Komprehensif</h3>
                    <p class="text-sm text-surface-400 leading-relaxed">
                        Perhitungan nilai akhir otomatis berdasarkan gabungan nilai sikap, keterampilan kerja, dan kualitas laporan PKL secara objektif.
                    </p>
                </div>

                {{-- Feature 6 --}}
                <div class="glass-panel glass-panel-hover rounded-3xl p-8 relative overflow-hidden group">
                    <div class="absolute -top-10 -right-10 w-24 h-24 bg-emerald-500/5 rounded-full blur-xl group-hover:bg-emerald-500/10 transition-colors"></div>
                    <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center mb-6 border border-emerald-500/20">
                        <i data-lucide="qr-code" class="w-6 h-6 transition-transform group-hover:scale-110"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Sertifikat Digital Valid</h3>
                    <p class="text-sm text-surface-400 leading-relaxed">
                        Mendukung pengunduhan sertifikat kelulusan PKL digital setelah siswa menyelesaikan seluruh rangkaian penilaian dengan tanda tangan sekolah.
                    </p>
                </div>
            </div>
        </section>

        {{-- Interactive Portal Showcase --}}
        <section id="peran" class="relative z-10 max-w-7xl mx-auto px-6 py-20 scroll-mt-20">
            <div class="text-center mb-12">
                <span class="text-xs font-bold text-cyan-400 uppercase tracking-widest px-3 py-1 rounded-full bg-cyan-500/10 border border-cyan-500/20">SIMULASI PORTAL</span>
                <h2 class="text-3xl md:text-5xl font-black text-white mt-4 tracking-tight">Satu Platform, Berbagai Peran</h2>
                <p class="text-surface-400 mt-4 max-w-2xl mx-auto text-sm sm:text-base">
                    Klik tab di bawah untuk mensimulasikan alur kerja dan interface masing-masing peran dalam sistem SPARTA.
                </p>
            </div>

            {{-- Tabs Selector --}}
            <div class="flex flex-wrap justify-center gap-3 mb-10 max-w-4xl mx-auto">
                <button onclick="switchRole('siswa')" id="tab-siswa" class="px-6 py-3.5 rounded-2xl text-sm font-bold flex items-center gap-2.5 transition-all duration-300 border bg-brand-600 border-brand-500 text-white shadow-neon-glow">
                    <i data-lucide="user" class="w-4 h-4"></i> Siswa PKL
                </button>
                <button onclick="switchRole('guru')" id="tab-guru" class="px-6 py-3.5 rounded-2xl text-sm font-bold flex items-center gap-2.5 transition-all duration-300 border bg-surface-900/50 border-white/5 text-surface-400 hover:text-white hover:border-white/10">
                    <i data-lucide="user-check" class="w-4 h-4"></i> Guru Pembimbing
                </button>
                <button onclick="switchRole('industri')" id="tab-industri" class="px-6 py-3.5 rounded-2xl text-sm font-bold flex items-center gap-2.5 transition-all duration-300 border bg-surface-900/50 border-white/5 text-surface-400 hover:text-white hover:border-white/10">
                    <i data-lucide="briefcase" class="w-4 h-4"></i> Pembimbing Industri
                </button>
                <button onclick="switchRole('admin')" id="tab-admin" class="px-6 py-3.5 rounded-2xl text-sm font-bold flex items-center gap-2.5 transition-all duration-300 border bg-surface-900/50 border-white/5 text-surface-400 hover:text-white hover:border-white/10">
                    <i data-lucide="shield-alert" class="w-4 h-4"></i> Administrator
                </button>
            </div>

            {{-- Tab Content Box --}}
            <div class="glass-panel rounded-3xl p-6 sm:p-8 border-white/10 shadow-2xl relative overflow-hidden max-w-5xl mx-auto min-h-[480px] flex flex-col justify-between">
                <div class="absolute -right-24 -bottom-24 w-80 h-80 bg-brand-500/5 rounded-full blur-3xl"></div>

                {{-- Siswa Tab view --}}
                <div id="content-siswa" class="role-content transition-all duration-300">
                    <div class="grid lg:grid-cols-12 gap-8 items-center">
                        <div class="lg:col-span-5">
                            <span class="text-xs font-semibold text-brand-400 uppercase tracking-widest">Portal Siswa</span>
                            <h3 class="text-2xl sm:text-3xl font-bold text-white mt-2">Kelola Pengajuan & Catat Jurnal Harian Mudah</h3>
                            <p class="text-sm text-surface-400 mt-4 leading-relaxed">
                                Siswa memiliki hak akses penuh untuk mengajukan tempat PKL pilihan mereka, mencatat aktivitas harian, serta melacak progres bimbingan.
                            </p>
                            <ul class="space-y-3 mt-6 text-sm text-surface-300">
                                <li class="flex items-center gap-2.5">
                                    <i data-lucide="check" class="w-4 h-4 text-emerald-400 shrink-0"></i>
                                    Form Pengajuan interaktif & Upload PDF.
                                </li>
                                <li class="flex items-center gap-2.5">
                                    <i data-lucide="check" class="w-4 h-4 text-emerald-400 shrink-0"></i>
                                    Input kegiatan & lampiran dokumentasi foto.
                                </li>
                                <li class="flex items-center gap-2.5">
                                    <i data-lucide="check" class="w-4 h-4 text-emerald-400 shrink-0"></i>
                                    Unduh Sertifikat secara instan bila lulus.
                                </li>
                            </ul>
                        </div>
                        <div class="lg:col-span-7 bg-surface-950/80 rounded-2xl border border-white/10 p-5 font-mono text-xs text-surface-300 shadow-inner">
                            <div class="flex items-center justify-between pb-3 border-b border-white/10 mb-4">
                                <div class="flex gap-1.5">
                                    <span class="w-3 h-3 rounded-full bg-rose-500"></span>
                                    <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                                    <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                                </div>
                                <span class="text-[10px] text-surface-400">Dashboard Siswa &bull; SPARTA</span>
                            </div>
                            
                            {{-- Mock Siswa Dashboard View --}}
                            <div class="space-y-4">
                                <div class="grid grid-cols-3 gap-3">
                                    <div class="bg-white/[0.02] border border-white/5 p-3 rounded-xl">
                                        <p class="text-[9px] text-surface-400">STATUS PKL</p>
                                        <p class="text-xs font-bold text-amber-400 mt-1">Sedang PKL</p>
                                    </div>
                                    <div class="bg-white/[0.02] border border-white/5 p-3 rounded-xl">
                                        <p class="text-[9px] text-surface-400">KEHADIRAN</p>
                                        <p class="text-xs font-bold text-emerald-400 mt-1">96% (24 Hari)</p>
                                    </div>
                                    <div class="bg-white/[0.02] border border-white/5 p-3 rounded-xl">
                                        <p class="text-[9px] text-surface-400">JURNAL VALID</p>
                                        <p class="text-xs font-bold text-white mt-1">22 dari 24</p>
                                    </div>
                                </div>
                                
                                <div class="bg-white/[0.02] border border-white/5 p-4 rounded-xl space-y-3">
                                    <div class="flex justify-between items-center">
                                        <span class="text-xs font-bold text-white">Form Jurnal Harian</span>
                                        <span class="px-2 py-0.5 rounded bg-brand-500/10 border border-brand-500/20 text-[9px] text-brand-300">Hari ini</span>
                                    </div>
                                    <div class="space-y-2">
                                        <div class="bg-surface-950 p-2.5 rounded border border-white/5 text-[10px] text-surface-400">
                                            Melakukan instalasi jaringan komputer client-server dan konfigurasi IP address statis...
                                        </div>
                                        <div class="flex justify-between items-center text-[10px]">
                                            <span class="text-emerald-400 flex items-center gap-1"><i data-lucide="image" class="w-3.5 h-3.5"></i> router_config.png terlampir</span>
                                            <button class="bg-brand-600 hover:bg-brand-500 text-white font-bold px-3 py-1 rounded">Simpan</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Guru Tab view --}}
                <div id="content-guru" class="role-content transition-all duration-300 hidden">
                    <div class="grid lg:grid-cols-12 gap-8 items-center">
                        <div class="lg:col-span-5">
                            <span class="text-xs font-semibold text-purple-400 uppercase tracking-widest">Portal Guru</span>
                            <h3 class="text-2xl sm:text-3xl font-bold text-white mt-2">Verifikasi Cepat & Monitoring Bimbingan Efektif</h3>
                            <p class="text-sm text-surface-400 mt-4 leading-relaxed">
                                Guru Pembimbing dapat memantau secara berkala absensi, rekap jurnal harian siswa bimbingan, memberikan umpan balik revisi, dan memberikan nilai akhir.
                            </p>
                            <ul class="space-y-3 mt-6 text-sm text-surface-300">
                                <li class="flex items-center gap-2.5">
                                    <i data-lucide="check" class="w-4 h-4 text-emerald-400 shrink-0"></i>
                                    Hanya menampilkan siswa bimbingan masing-masing.
                                </li>
                                <li class="flex items-center gap-2.5">
                                    <i data-lucide="check" class="w-4 h-4 text-emerald-400 shrink-0"></i>
                                    Validasi atau ajukan revisi jurnal harian dalam satu klik.
                                </li>
                                <li class="flex items-center gap-2.5">
                                    <i data-lucide="check" class="w-4 h-4 text-emerald-400 shrink-0"></i>
                                    Input form nilai gabungan (sikap, keterampilan, laporan).
                                </li>
                            </ul>
                        </div>
                        <div class="lg:col-span-7 bg-surface-950/80 rounded-2xl border border-white/10 p-5 font-mono text-xs text-surface-300 shadow-inner">
                            <div class="flex items-center justify-between pb-3 border-b border-white/10 mb-4">
                                <div class="flex gap-1.5">
                                    <span class="w-3 h-3 rounded-full bg-rose-500"></span>
                                    <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                                    <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                                </div>
                                <span class="text-[10px] text-surface-400">Dashboard Guru &bull; SPARTA</span>
                            </div>
                            
                            {{-- Mock Guru Dashboard View --}}
                            <div class="space-y-4">
                                <div class="flex justify-between items-center text-xs text-white">
                                    <span>Menunggu Validasi Jurnal (3)</span>
                                </div>
                                
                                <div class="space-y-2.5">
                                    <div class="bg-white/[0.02] border border-white/5 p-3 rounded-xl space-y-2">
                                        <div class="flex justify-between text-[10px]">
                                            <span class="font-bold text-white">Ahmad Fauzi — XI TKJ 1</span>
                                            <span class="text-surface-400">Kemarin, 16:30</span>
                                        </div>
                                        <p class="text-[10px] text-surface-400 italic">"Membuat dokumentasi sistem manajemen server lokal menggunakan sistem Linux Debian..."</p>
                                        <div class="flex gap-2 justify-end pt-1">
                                            <button class="bg-rose-500/20 text-rose-400 border border-rose-500/20 hover:bg-rose-500/30 px-2.5 py-1 rounded text-[9px] font-bold">Minta Revisi</button>
                                            <button class="bg-emerald-500/20 text-emerald-400 border border-emerald-500/20 hover:bg-emerald-500/30 px-2.5 py-1 rounded text-[9px] font-bold">Setujui Valid</button>
                                        </div>
                                    </div>
                                    <div class="bg-white/[0.02] border border-white/5 p-3 rounded-xl space-y-2 opacity-60">
                                        <div class="flex justify-between text-[10px]">
                                            <span class="font-bold text-white">Rizky Ramadhan — XI TKJ 2</span>
                                            <span class="text-surface-400">Kemarin, 15:45</span>
                                        </div>
                                        <p class="text-[10px] text-surface-400 italic">"Membantu troubleshooting printer di ruangan administrasi kantor..."</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Pembimbing Industri Tab view --}}
                <div id="content-industri" class="role-content transition-all duration-300 hidden">
                    <div class="grid lg:grid-cols-12 gap-8 items-center">
                        <div class="lg:col-span-5">
                            <span class="text-xs font-semibold text-emerald-400 uppercase tracking-widest">Portal Industri</span>
                            <h3 class="text-2xl sm:text-3xl font-bold text-white mt-2">Validasi Instan Aktivitas di Lapangan Kerja</h3>
                            <p class="text-sm text-surface-400 mt-4 leading-relaxed">
                                Pendamping lapangan dari perusahaan dapat memantau presensi dan memberikan persetujuan jurnal harian langsung di tempat kerja tanpa perlu formulir kertas.
                            </p>
                            <ul class="space-y-3 mt-6 text-sm text-surface-300">
                                <li class="flex items-center gap-2.5">
                                    <i data-lucide="check" class="w-4 h-4 text-emerald-400 shrink-0"></i>
                                    Akses cepat rekap kehadiran harian siswa PKL.
                                </li>
                                <li class="flex items-center gap-2.5">
                                    <i data-lucide="check" class="w-4 h-4 text-emerald-400 shrink-0"></i>
                                    Kolaborasi validasi jurnal yang transparan dengan guru.
                                </li>
                                <li class="flex items-center gap-2.5">
                                    <i data-lucide="check" class="w-4 h-4 text-emerald-400 shrink-0"></i>
                                    Evaluasi sikap dan keterampilan teknis siswa di lapangan.
                                </li>
                            </ul>
                        </div>
                        <div class="lg:col-span-7 bg-surface-950/80 rounded-2xl border border-white/10 p-5 font-mono text-xs text-surface-300 shadow-inner">
                            <div class="flex items-center justify-between pb-3 border-b border-white/10 mb-4">
                                <div class="flex gap-1.5">
                                    <span class="w-3 h-3 rounded-full bg-rose-500"></span>
                                    <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                                    <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                                </div>
                                <span class="text-[10px] text-surface-400">Portal Mitra &bull; Telkom Indonesia</span>
                            </div>
                            
                            {{-- Mock Industri Dashboard View --}}
                            <div class="space-y-4">
                                <div class="bg-white/[0.02] border border-white/5 p-4 rounded-xl">
                                    <p class="text-xs font-bold text-white mb-2">Evaluasi Kehadiran Siswa PKL</p>
                                    <div class="space-y-2 text-[10px]">
                                        <div class="flex justify-between items-center p-2 bg-surface-950 rounded">
                                            <span>Ahmad Fauzi</span>
                                            <span class="px-2 py-0.5 bg-emerald-500/10 text-emerald-400 rounded border border-emerald-500/20 font-bold">Hadir Tepat Waktu</span>
                                        </div>
                                        <div class="flex justify-between items-center p-2 bg-surface-950 rounded">
                                            <span>Rizky Ramadhan</span>
                                            <span class="px-2 py-0.5 bg-emerald-500/10 text-emerald-400 rounded border border-emerald-500/20 font-bold">Hadir Tepat Waktu</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="bg-white/[0.02] border border-white/5 p-4 rounded-xl">
                                    <p class="text-xs font-bold text-white mb-2">Penilaian Sikap Industri</p>
                                    <div class="grid grid-cols-2 gap-2 text-[9px]">
                                        <div class="p-2 bg-surface-950 rounded">
                                            <span class="text-surface-400">Kedisiplinan:</span>
                                            <span class="text-white font-bold block text-xs mt-1">92 / 100</span>
                                        </div>
                                        <div class="p-2 bg-surface-950 rounded">
                                            <span class="text-surface-400">Kerjasama Tim:</span>
                                            <span class="text-white font-bold block text-xs mt-1">95 / 100</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Admin Tab view --}}
                <div id="content-admin" class="role-content transition-all duration-300 hidden">
                    <div class="grid lg:grid-cols-12 gap-8 items-center">
                        <div class="lg:col-span-5">
                            <span class="text-xs font-semibold text-rose-400 uppercase tracking-widest">Portal Admin</span>
                            <h3 class="text-2xl sm:text-3xl font-bold text-white mt-2">Kontrol Data Master & Persetujuan Akun Penuh</h3>
                            <p class="text-sm text-surface-400 mt-4 leading-relaxed">
                                Administrator memiliki kendali penuh untuk menyetujui pendaftaran pengguna baru, mengelola database Tempat PKL, dan memetakan ploting guru pembimbing.
                            </p>
                            <ul class="space-y-3 mt-6 text-sm text-surface-300">
                                <li class="flex items-center gap-2.5">
                                    <i data-lucide="check" class="w-4 h-4 text-emerald-400 shrink-0"></i>
                                    CRUD data siswa, guru, instansi, & pembimbing.
                                </li>
                                <li class="flex items-center gap-2.5">
                                    <i data-lucide="check" class="w-4 h-4 text-emerald-400 shrink-0"></i>
                                    Persetujuan pendaftaran user (Siswa/Guru) terintegrasi.
                                </li>
                                <li class="flex items-center gap-2.5">
                                    <i data-lucide="check" class="w-4 h-4 text-emerald-400 shrink-0"></i>
                                    Tinjauan statistik program PKL secara menyeluruh.
                                </li>
                            </ul>
                        </div>
                        <div class="lg:col-span-7 bg-surface-950/80 rounded-2xl border border-white/10 p-5 font-mono text-xs text-surface-300 shadow-inner">
                            <div class="flex items-center justify-between pb-3 border-b border-white/10 mb-4">
                                <div class="flex gap-1.5">
                                    <span class="w-3 h-3 rounded-full bg-rose-500"></span>
                                    <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                                    <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                                </div>
                                <span class="text-[10px] text-surface-400">Control Panel Admin &bull; SPARTA</span>
                            </div>
                            
                            {{-- Mock Admin Dashboard View --}}
                            <div class="space-y-4">
                                <div class="bg-white/[0.02] border border-white/5 p-3 rounded-xl flex items-center justify-between">
                                    <div>
                                        <p class="text-[10px] font-bold text-white">Menunggu Persetujuan Akun</p>
                                        <p class="text-[9px] text-surface-400 mt-1">2 Registrasi Baru (1 Siswa, 1 Guru)</p>
                                    </div>
                                    <button class="bg-brand-600 hover:bg-brand-500 text-white font-bold px-3 py-1 rounded text-[9px] uppercase tracking-wider">Tinjau</button>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-3 text-[10px]">
                                    <div class="bg-white/[0.02] border border-white/5 p-3.5 rounded-xl text-center">
                                        <span class="text-surface-400">Total Industri</span>
                                        <span class="text-white font-black block text-sm mt-1">82 Perusahaan</span>
                                    </div>
                                    <div class="bg-white/[0.02] border border-white/5 p-3.5 rounded-xl text-center">
                                        <span class="text-surface-400">Guru Terploting</span>
                                        <span class="text-white font-black block text-sm mt-1">28 Guru</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Workflow Section --}}
        <section id="alur" class="relative z-10 max-w-7xl mx-auto px-6 py-20 scroll-mt-20">
            <div class="text-center mb-16">
                <span class="text-xs font-bold text-purple-400 uppercase tracking-widest px-3 py-1 rounded-full bg-purple-500/10 border border-purple-500/20">ALUR KERJA</span>
                <h2 class="text-3xl md:text-5xl font-black text-white mt-4 tracking-tight">Proses PKL yang Teratur</h2>
                <p class="text-surface-400 mt-4 max-w-xl mx-auto text-sm sm:text-base">
                    Siklus alur administrasi program PKL mulai dari perencanaan hingga penyelesaian terarah secara sistematis.
                </p>
            </div>

            <div class="relative max-w-5xl mx-auto">
                {{-- Glowing vertical timeline connector (desktop only) --}}
                <div class="hidden md:block absolute left-[50%] -translate-x-[50%] top-6 bottom-6 w-0.5 timeline-connector opacity-50"></div>

                <div class="space-y-12 relative">
                    {{-- Step 1 --}}
                    <div class="grid md:grid-cols-2 gap-8 items-center relative">
                        <div class="md:text-right md:pr-12">
                            <span class="text-xs font-bold text-brand-400 uppercase tracking-wider bg-brand-500/10 px-2.5 py-1 rounded-lg border border-brand-500/20">Langkah 1</span>
                            <h3 class="text-xl font-bold text-white mt-2">Pengajuan Tempat PKL</h3>
                            <p class="text-sm text-surface-400 mt-2 leading-relaxed">
                                Siswa menentukan tempat PKL yang diinginkan dan melengkapi formulir pengajuan digital berserta berkas dokumen pendukung melalui portal siswa.
                            </p>
                        </div>
                        <div class="hidden md:flex absolute left-[50%] -translate-x-[50%] w-10 h-10 rounded-full bg-surface-950 border-2 border-brand-500 items-center justify-center text-brand-400 font-bold z-10 shadow-neon-glow">
                            1
                        </div>
                        <div class="md:pl-12">
                            <div class="glass-panel p-5 rounded-2xl border-white/5 inline-flex items-center gap-4 text-xs font-mono text-surface-300">
                                <i data-lucide="file-plus-2" class="w-8 h-8 text-brand-400 shrink-0"></i>
                                <span>Status: <span class="text-amber-400 font-bold">Menunggu Persetujuan</span></span>
                            </div>
                        </div>
                    </div>

                    {{-- Step 2 --}}
                    <div class="grid md:grid-cols-2 gap-8 items-center relative">
                        <div class="md:order-2 md:pl-12">
                            <span class="text-xs font-bold text-purple-400 uppercase tracking-wider bg-purple-500/10 px-2.5 py-1 rounded-lg border border-purple-500/20">Langkah 2</span>
                            <h3 class="text-xl font-bold text-white mt-2">Verifikasi & Ploting Guru</h3>
                            <p class="text-sm text-surface-400 mt-2 leading-relaxed">
                                Admin sekolah meninjau kuota pengajuan siswa, menyetujui, dan melakukan pemetaan ploting guru pembimbing yang akan mendampingi siswa tersebut.
                            </p>
                        </div>
                        <div class="hidden md:flex absolute left-[50%] -translate-x-[50%] w-10 h-10 rounded-full bg-surface-950 border-2 border-purple-500 items-center justify-center text-purple-400 font-bold z-10 shadow-neon-glow">
                            2
                        </div>
                        <div class="md:order-1 md:text-right md:pr-12">
                            <div class="glass-panel p-5 rounded-2xl border-white/5 inline-flex items-center gap-4 text-xs font-mono text-surface-300">
                                <span>Guru Pembimbing: <span class="text-emerald-400 font-bold">Terploting</span></span>
                                <i data-lucide="user-plus" class="w-8 h-8 text-purple-400 shrink-0"></i>
                            </div>
                        </div>
                    </div>

                    {{-- Step 3 --}}
                    <div class="grid md:grid-cols-2 gap-8 items-center relative">
                        <div class="md:text-right md:pr-12">
                            <span class="text-xs font-bold text-cyan-400 uppercase tracking-wider bg-cyan-500/10 px-2.5 py-1 rounded-lg border border-cyan-500/20">Langkah 3</span>
                            <h3 class="text-xl font-bold text-white mt-2">Kegiatan Harian & Absensi</h3>
                            <p class="text-sm text-surface-400 mt-2 leading-relaxed">
                                Siswa melaksanakan PKL di industri, mengisi jurnal aktivitas harian dengan foto, dan melakukan absensi secara real-time melalui sistem SPARTA.
                            </p>
                        </div>
                        <div class="hidden md:flex absolute left-[50%] -translate-x-[50%] w-10 h-10 rounded-full bg-surface-950 border-2 border-cyan-500 items-center justify-center text-cyan-400 font-bold z-10 shadow-neon-glow">
                            3
                        </div>
                        <div class="md:pl-12">
                            <div class="glass-panel p-5 rounded-2xl border-white/5 inline-flex items-center gap-4 text-xs font-mono text-surface-300">
                                <i data-lucide="calendar" class="w-8 h-8 text-cyan-400 shrink-0"></i>
                                <span>Presensi Hari ini: <span class="text-emerald-400 font-bold">Hadir</span></span>
                            </div>
                        </div>
                    </div>

                    {{-- Step 4 --}}
                    <div class="grid md:grid-cols-2 gap-8 items-center relative">
                        <div class="md:order-2 md:pl-12">
                            <span class="text-xs font-bold text-emerald-400 uppercase tracking-wider bg-emerald-500/10 px-2.5 py-1 rounded-lg border border-emerald-500/20">Langkah 4</span>
                            <h3 class="text-xl font-bold text-white mt-2">Evaluasi & Sertifikasi</h3>
                            <p class="text-sm text-surface-400 mt-2 leading-relaxed">
                                Setelah program selesai, pembimbing industri dan guru memberikan penilaian akhir. Siswa mengunggah laporan akhir dan dapat mengunduh sertifikat digital.
                            </p>
                        </div>
                        <div class="hidden md:flex absolute left-[50%] -translate-x-[50%] w-10 h-10 rounded-full bg-surface-950 border-2 border-emerald-500 items-center justify-center text-emerald-400 font-bold z-10 shadow-neon-glow">
                            4
                        </div>
                        <div class="md:order-1 md:text-right md:pr-12">
                            <div class="glass-panel p-5 rounded-2xl border-white/5 inline-flex items-center gap-4 text-xs font-mono text-surface-300">
                                <span>Nilai Akhir: <span class="text-emerald-400 font-bold">A (Sangat Baik)</span></span>
                                <i data-lucide="award" class="w-8 h-8 text-emerald-400 shrink-0"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Testimonial Section --}}
        <section class="relative z-10 max-w-7xl mx-auto px-6 py-20">
            <div class="text-center mb-16">
                <span class="text-xs font-bold text-cyan-400 uppercase tracking-widest px-3 py-1 rounded-full bg-cyan-500/10 border border-cyan-500/20">TESTIMONI</span>
                <h2 class="text-3xl md:text-5xl font-black text-white mt-4 tracking-tight">Apa Kata Mereka?</h2>
                <p class="text-surface-400 mt-4 max-w-xl mx-auto text-sm sm:text-base">
                    Umpan balik nyata dari para pengguna yang telah merasakan kemudahan sistem manajemen PKL terpadu.
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-6">
                <div class="glass-panel rounded-3xl p-8 relative flex flex-col justify-between border-white/5">
                    <div>
                        <div class="flex gap-1 text-amber-400 mb-5">
                            <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                            <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                            <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                            <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                            <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                        </div>
                        <p class="text-sm text-surface-300 italic leading-relaxed">
                            "Sebelumnya kami kesulitan merekap jurnal fisik yang sering hilang. Dengan SPARTA, pengisian jurnal harian sangat mudah, cukup dari HP. Status approval dari pembimbing juga terlihat jelas!"
                        </p>
                    </div>
                    <div class="flex items-center gap-4 mt-8 pt-6 border-t border-white/5">
                        <div class="w-10 h-10 rounded-full bg-brand-500/10 border border-brand-500/20 flex items-center justify-center text-brand-300 font-bold text-xs">
                            AN
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-white">Andika Nugraha</h4>
                            <p class="text-[10px] text-surface-400">Siswa PKL - TKJ</p>
                        </div>
                    </div>
                </div>

                <div class="glass-panel rounded-3xl p-8 relative flex flex-col justify-between border-white/5">
                    <div>
                        <div class="flex gap-1 text-amber-400 mb-5">
                            <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                            <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                            <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                            <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                            <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                        </div>
                        <p class="text-sm text-surface-300 italic leading-relaxed">
                            "Sebagai guru, saya sangat terbantu untuk memantau kehadiran dan validasi jurnal harian siswa secara berkala. Pemetaan bimbingan juga sangat transparan dan terdokumentasi rapi."
                        </p>
                    </div>
                    <div class="flex items-center gap-4 mt-8 pt-6 border-t border-white/5">
                        <div class="w-10 h-10 rounded-full bg-purple-500/10 border border-purple-500/20 flex items-center justify-center text-purple-300 font-bold text-xs">
                            SR
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-white">Sri Rahayu, S.Pd.</h4>
                            <p class="text-[10px] text-surface-400">Guru Pembimbing Sekolah</p>
                        </div>
                    </div>
                </div>

                <div class="glass-panel rounded-3xl p-8 relative flex flex-col justify-between border-white/5">
                    <div>
                        <div class="flex gap-1 text-amber-400 mb-5">
                            <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                            <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                            <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                            <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                            <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                        </div>
                        <p class="text-sm text-surface-300 italic leading-relaxed">
                            "Akses validasi sangat simpel. Kami hanya tinggal meninjau foto dan laporan di portal kami. Pihak sekolah juga bisa memantau kedisiplinan siswa di kantor kami tanpa harus sering berkunjung."
                        </p>
                    </div>
                    <div class="flex items-center gap-4 mt-8 pt-6 border-t border-white/5">
                        <div class="w-10 h-10 rounded-full bg-cyan-500/10 border border-cyan-500/20 flex items-center justify-center text-cyan-300 font-bold text-xs">
                            HD
                        </div>
                        <div>
                            <h4 class="text-xs font-bold text-white">Heri Dermawan</h4>
                            <p class="text-[10px] text-surface-400">Supervisor IT - Telkom Indonesia</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- FAQ Section --}}
        <section id="faq" class="relative z-10 max-w-4xl mx-auto px-6 py-20 scroll-mt-20">
            <div class="text-center mb-16">
                <span class="text-xs font-bold text-cyan-400 uppercase tracking-widest px-3 py-1 rounded-full bg-cyan-500/10 border border-cyan-500/20">TANYA JAWAB</span>
                <h2 class="text-3xl md:text-5xl font-black text-white mt-4 tracking-tight font-display">Sering Ditanyakan</h2>
                <p class="text-surface-400 mt-4 text-sm sm:text-base">
                    Berikut adalah kumpulan pertanyaan umum dan jawaban ringkas seputar penggunaan platform SPARTA.
                </p>
            </div>

            <div class="space-y-4">
                {{-- FAQ 1 --}}
                <div class="glass-panel faq-item rounded-2xl border-white/5 overflow-hidden transition-colors duration-300 hover:border-white/10">
                    <button class="w-full px-6 py-5 flex justify-between items-center text-left text-white focus:outline-none" onclick="toggleFaq(this)">
                        <span class="text-sm sm:text-base font-bold">Bagaimana siswa mendapatkan akun login untuk SPARTA?</span>
                        <div class="w-7 h-7 rounded-lg bg-white/5 flex items-center justify-center border border-white/5 shrink-0 ml-4 faq-icon">
                            <i data-lucide="chevron-down" class="w-4 h-4 text-surface-400"></i>
                        </div>
                    </button>
                    <div class="faq-answer px-6 pb-5">
                        <p class="text-sm text-surface-400 leading-relaxed pt-2">
                            Siswa dapat mendaftarkan akun baru melalui tombol <strong>Daftar Akun</strong> di halaman utama. Setelah mengisi formulir pendaftaran, akun tidak bisa langsung digunakan login melainkan harus menunggu persetujuan/verifikasi dari admin sekolah demi alasan keamanan data.
                        </p>
                    </div>
                </div>

                {{-- FAQ 2 --}}
                <div class="glass-panel faq-item rounded-2xl border-white/5 overflow-hidden transition-colors duration-300 hover:border-white/10">
                    <button class="w-full px-6 py-5 flex justify-between items-center text-left text-white focus:outline-none" onclick="toggleFaq(this)">
                        <span class="text-sm sm:text-base font-bold">Apakah siswa bisa mengganti pilihan tempat PKL setelah diajukan?</span>
                        <div class="w-7 h-7 rounded-lg bg-white/5 flex items-center justify-center border border-white/5 shrink-0 ml-4 faq-icon">
                            <i data-lucide="chevron-down" class="w-4 h-4 text-surface-400"></i>
                        </div>
                    </button>
                    <div class="faq-answer px-6 pb-5">
                        <p class="text-sm text-surface-400 leading-relaxed pt-2">
                            Siswa hanya dapat melakukan edit pengajuan tempat PKL apabila status pengajuan masih berada dalam bentuk <strong>Draft</strong> atau status <strong>Revisi</strong>. Jika status sudah menjadi <i>Menunggu Persetujuan</i> atau <i>Disetujui</i>, siswa tidak dapat mengubahnya secara sepihak dan harus berkoordinasi dengan admin.
                        </p>
                    </div>
                </div>

                {{-- FAQ 3 --}}
                <div class="glass-panel faq-item rounded-2xl border-white/5 overflow-hidden transition-colors duration-300 hover:border-white/10">
                    <button class="w-full px-6 py-5 flex justify-between items-center text-left text-white focus:outline-none" onclick="toggleFaq(this)">
                        <span class="text-sm sm:text-base font-bold">Berapa batas ukuran maksimal unggah dokumen laporan akhir?</span>
                        <div class="w-7 h-7 rounded-lg bg-white/5 flex items-center justify-center border border-white/5 shrink-0 ml-4 faq-icon">
                            <i data-lucide="chevron-down" class="w-4 h-4 text-surface-400"></i>
                        </div>
                    </button>
                    <div class="faq-answer px-6 pb-5">
                        <p class="text-sm text-surface-400 leading-relaxed pt-2">
                            Berdasarkan kebijakan sistem, batas unggah untuk dokumen lampiran laporan akhir siswa adalah maksimal sebesar <strong>5 MB (5120 KB)</strong> dengan format berkas berupa <strong>PDF</strong>. Dokumen pengajuan maksimal 2 MB, dan dokumentasi jurnal harian maksimal 2 MB.
                        </p>
                    </div>
                </div>

                {{-- FAQ 4 --}}
                <div class="glass-panel faq-item rounded-2xl border-white/5 overflow-hidden transition-colors duration-300 hover:border-white/10">
                    <button class="w-full px-6 py-5 flex justify-between items-center text-left text-white focus:outline-none" onclick="toggleFaq(this)">
                        <span class="text-sm sm:text-base font-bold">Bagaimana proses penilaian akhir PKL dihitung?</span>
                        <div class="w-7 h-7 rounded-lg bg-white/5 flex items-center justify-center border border-white/5 shrink-0 ml-4 faq-icon">
                            <i data-lucide="chevron-down" class="w-4 h-4 text-surface-400"></i>
                        </div>
                    </button>
                    <div class="faq-answer px-6 pb-5">
                        <p class="text-sm text-surface-400 leading-relaxed pt-2">
                            Nilai akhir dihitung secara otomatis oleh sistem menggunakan formula rata-rata: <code>Nilai Akhir = (Nilai Sikap + Nilai Keterampilan + Nilai Laporan) / 3</code>. Setelah nilai akhir dimasukkan oleh guru, status pengajuan siswa akan otomatis dinyatakan selesai dan sertifikat siap dicetak.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        {{-- CTA Section --}}
        <section class="relative z-10 max-w-7xl mx-auto px-6 py-12 md:py-16">
            <div class="glass-panel rounded-[32px] border-white/10 p-8 md:p-14 relative overflow-hidden text-center shadow-2xl">
                {{-- Decorative bright orb in background of CTA card --}}
                <div class="absolute -top-24 -left-24 w-80 h-80 bg-brand-500/20 rounded-full blur-[80px]"></div>
                <div class="absolute -bottom-24 -right-24 w-80 h-80 bg-cyan-500/20 rounded-full blur-[80px]"></div>

                <div class="relative z-10 max-w-2xl mx-auto">
                    <h2 class="text-3xl md:text-5xl font-black text-white leading-tight font-display">Siap Mengelola PKL Secara Profesional?</h2>
                    <p class="text-surface-400 mt-4 text-sm sm:text-base">
                        Bergabunglah dengan ratusan siswa dan puluhan instansi mitra yang telah terhubung dalam sistem SPARTA terintegrasi.
                    </p>
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mt-10">
                        <button onclick="showCoolAlert()" class="w-full sm:w-auto px-8 py-4 bg-gradient-to-r from-brand-600 to-brand-500 text-white rounded-2xl font-bold text-sm shadow-lg hover:shadow-neon-glow transition-all duration-300">
                            Daftar Sekarang
                        </button>
                        <a href="{{ route('guide') }}" class="w-full sm:w-auto px-8 py-4 bg-white/5 border border-white/10 hover:bg-white/10 hover:border-white/20 text-white rounded-2xl font-bold text-sm transition-all duration-300">
                            Pelajari Panduan
                        </a>
                    </div>
                </div>
            </div>
        </section>

        {{-- Footer --}}
        <footer class="relative z-10 border-t border-white/5 py-12 px-6 bg-surface-950/80 backdrop-blur-md">
            <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-10 mb-10">
                <div class="md:col-span-2 space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-brand-600 to-cyan-500 flex items-center justify-center">
                            <img src="{{ asset('logo.png') }}" alt="Logo SPARTA" class="h-4 w-auto brightness-0 invert">
                        </div>
                        <span class="text-base font-extrabold text-white tracking-tight font-display">SPARTA PKL</span>
                    </div>
                    <p class="text-xs text-surface-400 leading-relaxed max-w-sm">
                        Sistem Informasi Pengajuan & Pemantauan PKL Siswa SMK berbasis digital. Meningkatkan koordinasi sekolah, siswa, dan mitra industri.
                    </p>
                    <div class="flex items-center gap-3 pt-2">
                        <a href="#" class="w-8 h-8 rounded-lg bg-white/5 border border-white/5 flex items-center justify-center text-surface-400 hover:text-white hover:bg-white/10 transition-colors">
                            <i data-lucide="globe" class="w-4 h-4"></i>
                        </a>
                        <a href="#" class="w-8 h-8 rounded-lg bg-white/5 border border-white/5 flex items-center justify-center text-surface-400 hover:text-white hover:bg-white/10 transition-colors">
                            <i data-lucide="mail" class="w-4 h-4"></i>
                        </a>
                        <a href="#" class="w-8 h-8 rounded-lg bg-white/5 border border-white/5 flex items-center justify-center text-surface-400 hover:text-white hover:bg-white/10 transition-colors">
                            <i data-lucide="phone" class="w-4 h-4"></i>
                        </a>
                    </div>
                </div>

                <div>
                    <h4 class="text-xs font-bold text-white uppercase tracking-widest mb-4">Navigasi Cepat</h4>
                    <ul class="space-y-2 text-xs text-surface-400">
                        <li><a href="#fitur" class="hover:text-white transition-colors">Fitur Utama</a></li>
                        <li><a href="#peran" class="hover:text-white transition-colors">Portal Peran</a></li>
                        <li><a href="#alur" class="hover:text-white transition-colors">Alur Pelaksanaan</a></li>
                        <li><a href="#faq" class="hover:text-white transition-colors">Tanya Jawab</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-xs font-bold text-white uppercase tracking-widest mb-4">Akses Pengguna</h4>
                    <ul class="space-y-2 text-xs text-surface-400">
                        <li><a href="{{ route('login') }}" class="hover:text-white transition-colors">Halaman Masuk</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-white transition-colors">Registrasi Akun</a></li>
                        <li><a href="{{ route('guide') }}" class="hover:text-white transition-colors">Panduan Sistem</a></li>
                        <li><span class="text-brand-400">v5.1 Production</span></li>
                    </ul>
                </div>
            </div>

            <div class="max-w-7xl mx-auto pt-6 border-t border-white/5 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-surface-400">
                <p>&copy; {{ date('Y') }} SPARTA — SMK PKL System. Hak cipta dilindungi undang-undang.</p>
                <div class="flex items-center gap-6">
                    <span class="text-[10px] bg-white/5 border border-white/5 px-2.5 py-1 rounded text-surface-300 font-mono">Build with Laravel & Tailwind</span>
                </div>
            </div>
        </footer>
    </div>

    <!-- SweetAlert2 & Custom Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Init Lucide
        lucide.createIcons();

        // 1. Role switcher logic
        function switchRole(role) {
            // Target elements
            const contents = document.querySelectorAll('.role-content');
            const tabs = {
                siswa: document.getElementById('tab-siswa'),
                guru: document.getElementById('tab-guru'),
                industri: document.getElementById('tab-industri'),
                admin: document.getElementById('tab-admin'),
            };

            // Hide all content blocks
            contents.forEach(c => c.classList.add('hidden'));

            // Show selected content block
            document.getElementById(`content-${role}`).classList.remove('hidden');

            // Reset tab button styling
            Object.values(tabs).forEach(tab => {
                tab.className = "px-6 py-3.5 rounded-2xl text-sm font-bold flex items-center gap-2.5 transition-all duration-300 border bg-surface-900/50 border-white/5 text-surface-400 hover:text-white hover:border-white/10";
            });

            // Set active tab styling
            tabs[role].className = "px-6 py-3.5 rounded-2xl text-sm font-bold flex items-center gap-2.5 transition-all duration-300 border bg-brand-600 border-brand-500 text-white shadow-neon-glow";

            // Re-create icons in dynamically switched content
            lucide.createIcons();
        }

        // 2. FAQ Accordion toggle
        function toggleFaq(button) {
            const item = button.parentElement;
            const isActive = item.classList.contains('active');

            // Close all items
            document.querySelectorAll('.faq-item').forEach(i => i.classList.remove('active'));

            // Toggle current item
            if (!isActive) {
                item.classList.add('active');
            }
        }

        // 3. SweetAlert dialog with custom theme matching dark-mode
        function showCoolAlert() {
            Swal.fire({
                title: '<span class="text-white font-extrabold font-display">Selamat Datang di SPARTA!</span>',
                html: '<p class="text-slate-400 text-sm font-medium leading-relaxed">Silakan masuk menggunakan akun Anda atau daftarkan akun siswa baru untuk mulai mengajukan PKL.</p>',
                icon: 'info',
                iconColor: '#6366f1',
                showConfirmButton: true,
                showDenyButton: true,
                confirmButtonText: 'Masuk',
                denyButtonText: 'Daftar Baru',
                background: '#0f172a', // deep slate
                color: '#f8fafc',
                customClass: {
                    popup: 'rounded-3xl border border-slate-800 shadow-2xl p-6 md:p-8',
                    confirmButton: 'px-6 py-3 bg-gradient-to-r from-indigo-600 to-indigo-500 rounded-xl font-bold text-sm text-white shadow-lg hover:shadow-indigo-500/20 hover:from-indigo-700 hover:to-indigo-600 transition-all outline-none focus:ring-2 focus:ring-indigo-500 mr-2.5',
                    denyButton: 'px-6 py-3 bg-slate-800 border border-slate-700 text-slate-300 rounded-xl font-bold text-sm hover:bg-slate-700 transition-all outline-none focus:ring-2 focus:ring-slate-600 ml-2.5'
                },
                buttonsStyling: false,
                showClass: { popup: 'animate__animated animate__fadeInUp animate__faster' },
                hideClass: { popup: 'animate__animated animate__fadeOutDown animate__faster' }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('login') }}";
                } else if (result.isDenied) {
                    window.location.href = "{{ route('register') }}";
                }
            });
        }

        // 4. Count Up Stats animation trigger on scroll
        const statsSection = document.getElementById('stats-section');
        const countElements = statsSection.querySelectorAll('[data-target]');
        let animated = false;

        function animateStats() {
            countElements.forEach(el => {
                const target = parseInt(el.getAttribute('data-target'));
                const duration = 2000; // ms
                const stepTime = 30; // ms
                const steps = duration / stepTime;
                const increment = target / steps;
                let current = 0;

                const timer = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        el.innerText = target + (el.innerText.includes('%') || el.innerText.includes('+') ? el.innerText.replace(/[0-9]/g, '') : '');
                        // formatting
                        if (target === 100) el.innerText = "100%";
                        else if (target === 500) el.innerText = "500+";
                        else if (target === 80) el.innerText = "80+";
                        clearInterval(timer);
                    } else {
                        el.innerText = Math.floor(current);
                    }
                }, stepTime);
            });
        }

        // Intersection observer for stats
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !animated) {
                    animateStats();
                    animated = true;
                }
            });
        }, { threshold: 0.5 });

        observer.observe(statsSection);

        // 5. 3D Tilt Effect on main hero card
        const tiltContainer = document.getElementById('tilt-container');
        const tiltCard = tiltContainer.querySelector('.tilt-card');

        if (window.innerWidth > 1024) {
            tiltContainer.addEventListener('mousemove', (e) => {
                const rect = tiltContainer.getBoundingClientRect();
                const x = e.clientX - rect.left - rect.width / 2;
                const y = e.clientY - rect.top - rect.height / 2;
                
                const rotateX = -y / 15;
                const rotateY = x / 15;

                tiltCard.style.transform = `rotateX(${rotateX}deg) rotateY(${rotateY}deg)`;
            });

            tiltContainer.addEventListener('mouseleave', () => {
                tiltCard.style.transform = `rotateX(0deg) rotateY(0deg)`;
            });
        }

        // 6. Navigation bar backdrop blur effect on scroll
        window.addEventListener('scroll', () => {
            const nav = document.getElementById('navbar');
            if (window.scrollY > 50) {
                nav.classList.add('shadow-xl', 'bg-surface-950/90', 'border-white/10');
                nav.classList.remove('bg-surface-950/70', 'border-surface-800/40');
            } else {
                nav.classList.remove('shadow-xl', 'bg-surface-950/90', 'border-white/10');
                nav.classList.add('bg-surface-950/70', 'border-surface-800/40');
            }
        });
    </script>
</body>
</html>
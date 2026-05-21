<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SPARTA — Sistem Informasi Pengajuan dan Pemantauan PKL</title>
    <meta name="description" content="Platform terintegrasi untuk pengajuan, pemantauan, validasi jurnal, hingga sertifikasi industri secara profesional.">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }

        .hero-gradient {
            background: radial-gradient(ellipse at 30% 20%, rgba(99, 102, 241, 0.12) 0%, transparent 50%),
                        radial-gradient(ellipse at 70% 80%, rgba(6, 182, 212, 0.08) 0%, transparent 50%),
                        radial-gradient(ellipse at 50% 50%, rgba(236, 72, 153, 0.05) 0%, transparent 60%);
        }

        .glow-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            animation: orb-float 20s ease-in-out infinite alternate;
        }
        .glow-orb-1 { width: 400px; height: 400px; background: rgba(99,102,241,0.15); top: -10%; left: -5%; }
        .glow-orb-2 { width: 350px; height: 350px; background: rgba(6,182,212,0.1); bottom: 10%; right: -5%; animation-delay: -7s; }
        .glow-orb-3 { width: 200px; height: 200px; background: rgba(236,72,153,0.08); top: 40%; right: 20%; animation-delay: -12s; }

        @keyframes orb-float {
            0%   { transform: translate(0, 0) scale(1); }
            100% { transform: translate(60px, 40px) scale(1.15); }
        }

        .feature-card {
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.8);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .feature-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
            border-color: rgba(99, 102, 241, 0.2);
        }

        .stat-pill {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        @keyframes float-slow {
            0%, 100% { transform: translateY(0) rotate(1deg); }
            50% { transform: translateY(-16px) rotate(-1deg); }
        }
        .float-1 { animation: float-slow 7s ease-in-out infinite; }
        .float-2 { animation: float-slow 8s ease-in-out infinite; animation-delay: -3s; }
    </style>
</head>

<body class="antialiased bg-surface-50 text-surface-900">
    <div class="relative min-h-screen hero-gradient overflow-hidden">
        {{-- Background Elements --}}
        <div class="glow-orb glow-orb-1"></div>
        <div class="glow-orb glow-orb-2"></div>
        <div class="glow-orb glow-orb-3"></div>
        <div class="absolute inset-0 bg-dots opacity-30" style="mask-image: radial-gradient(ellipse at center, black 20%, transparent 75%);"></div>

        {{-- Navigation --}}
        <nav class="sticky top-0 z-50 bg-white/70 backdrop-blur-xl border-b border-surface-200/40 px-6 py-3.5">
            <div class="max-w-7xl mx-auto flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-brand-600 to-brand-500 flex items-center justify-center shadow-lg shadow-brand-500/20">
                        <img src="{{ asset('logo.png') }}" alt="Logo SPARTA" class="h-6 w-auto brightness-0 invert">
                    </div>
                    <div>
                        <span class="text-lg font-extrabold text-surface-900 tracking-tight">SPARTA</span>
                        <span class="hidden sm:block text-[10px] text-surface-400 font-medium -mt-0.5">PKL Management</span>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn-primary text-sm">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="px-5 py-2.5 text-sm font-semibold text-surface-700 hover:text-surface-900 transition-colors">Masuk</a>
                        <a href="{{ route('register') }}" class="btn-primary text-sm">Daftar</a>
                    @endauth
                </div>
            </div>
        </nav>

        {{-- Hero Section --}}
        <main class="relative z-10 max-w-7xl mx-auto px-6 pt-20 md:pt-28 pb-32">
            <div class="grid lg:grid-cols-2 gap-16 lg:gap-20 items-center">
                {{-- Left Content --}}
                <div class="text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-brand-50 border border-brand-100 text-brand-600 text-xs font-bold uppercase tracking-widest mb-8">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-brand-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-brand-600"></span>
                        </span>
                        SPARTA v5.1.5
                    </div>

                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold leading-[1.1] text-surface-900 mb-6 tracking-tight">
                        Sistem Informasi<br>
                        <span class="text-gradient-brand">Pengajuan & Pemantauan</span><br>
                        PKL Siswa.
                    </h1>

                    <p class="text-lg md:text-xl text-surface-500 leading-relaxed mb-10 max-w-xl mx-auto lg:mx-0">
                        Platform terintegrasi untuk mempercepat pengajuan, pemantauan, validasi jurnal, hingga sertifikasi industri secara profesional.
                    </p>

                    <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4 mb-14">
                        <button onclick="showCoolAlert()" class="btn-primary w-full sm:w-auto !px-8 !py-4 text-base group">
                            Mulai Sekarang
                            <i data-lucide="arrow-right" class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform"></i>
                        </button>
                        <a href="{{ route('guide') }}" class="btn-secondary w-full sm:w-auto !px-8 !py-4 text-base">
                            <i data-lucide="book-open" class="w-5 h-5 mr-2"></i>
                            Lihat Panduan
                        </a>
                    </div>

                    {{-- Stats --}}
                    <div class="grid grid-cols-3 gap-4 max-w-md mx-auto lg:mx-0">
                        <div class="stat-pill rounded-2xl p-4 text-center">
                            <p class="text-2xl font-extrabold text-surface-900">100%</p>
                            <p class="text-[10px] font-bold text-surface-400 uppercase tracking-widest mt-1">Digital</p>
                        </div>
                        <div class="stat-pill rounded-2xl p-4 text-center">
                            <p class="text-2xl font-extrabold text-surface-900">500+</p>
                            <p class="text-[10px] font-bold text-surface-400 uppercase tracking-widest mt-1">Siswa</p>
                        </div>
                        <div class="stat-pill rounded-2xl p-4 text-center">
                            <p class="text-2xl font-extrabold text-surface-900">50+</p>
                            <p class="text-[10px] font-bold text-surface-400 uppercase tracking-widest mt-1">Industri</p>
                        </div>
                    </div>
                </div>

                {{-- Right Visual --}}
                <div class="relative mt-12 lg:mt-0 flex justify-center items-center">
                    <div class="relative w-full max-w-2xl">
                        <div class="absolute -top-16 -left-16 w-64 h-64 bg-brand-500/10 rounded-full blur-3xl"></div>
                        <div class="absolute -bottom-16 -right-16 w-64 h-64 bg-cyan-500/10 rounded-full blur-3xl"></div>

                        <img src="{{ asset('images/hero-system.png') }}" alt="SPARTA Dashboard Preview"
                            class="relative z-10 w-full h-auto drop-shadow-2xl animate-float rounded-2xl">
                    </div>

                    {{-- Floating Card 1 --}}
                    <div class="absolute top-8 -right-2 md:-right-8 float-1 z-20">
                        <div class="feature-card p-4 rounded-2xl shadow-glass flex items-center gap-3">
                            <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center shrink-0">
                                <i data-lucide="check-circle" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-surface-400 uppercase tracking-wider">Status</p>
                                <p class="text-sm font-extrabold text-surface-900">Terverifikasi</p>
                            </div>
                        </div>
                    </div>

                    {{-- Floating Card 2 --}}
                    <div class="absolute bottom-8 -left-2 md:-left-8 float-2 z-20">
                        <div class="feature-card p-4 rounded-2xl shadow-glass flex items-center gap-3">
                            <div class="w-10 h-10 bg-brand-50 text-brand-600 rounded-xl flex items-center justify-center shrink-0">
                                <i data-lucide="award" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-surface-400 uppercase tracking-wider">Sertifikat</p>
                                <p class="text-sm font-extrabold text-surface-900">Siap Cetak</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        {{-- Features Section --}}
        <section class="relative z-10 max-w-7xl mx-auto px-6 pb-32">
            <div class="text-center mb-16">
                <span class="text-xs font-bold text-brand-600 uppercase tracking-widest">Fitur Unggulan</span>
                <h2 class="text-3xl md:text-4xl font-extrabold text-surface-900 mt-3 tracking-tight">Semua yang Anda butuhkan</h2>
                <p class="text-surface-500 mt-3 max-w-lg mx-auto">Platform lengkap yang menyederhanakan seluruh alur program PKL dari awal hingga akhir.</p>
            </div>
            <div class="grid md:grid-cols-3 gap-6">
                <div class="feature-card rounded-2xl p-8">
                    <div class="w-12 h-12 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center mb-5">
                        <i data-lucide="file-plus" class="w-6 h-6"></i>
                    </div>
                    <h3 class="text-lg font-bold text-surface-900 mb-2">Pengajuan Digital</h3>
                    <p class="text-sm text-surface-500 leading-relaxed">Ajukan tempat PKL secara online dengan status tracking real-time dari draft hingga persetujuan.</p>
                </div>
                <div class="feature-card rounded-2xl p-8">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center mb-5">
                        <i data-lucide="book-open" class="w-6 h-6"></i>
                    </div>
                    <h3 class="text-lg font-bold text-surface-900 mb-2">Jurnal Harian</h3>
                    <p class="text-sm text-surface-500 leading-relaxed">Catat aktivitas harian PKL dengan dokumentasi foto dan validasi dari guru pembimbing.</p>
                </div>
                <div class="feature-card rounded-2xl p-8">
                    <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center mb-5">
                        <i data-lucide="award" class="w-6 h-6"></i>
                    </div>
                    <h3 class="text-lg font-bold text-surface-900 mb-2">Penilaian & Sertifikat</h3>
                    <p class="text-sm text-surface-500 leading-relaxed">Penilaian terstruktur dengan sertifikat digital yang dapat langsung dicetak setelah selesai.</p>
                </div>
            </div>
        </section>

        {{-- Footer --}}
        <footer class="relative z-10 border-t border-surface-200/40 py-8 px-6 bg-white/40 backdrop-blur-sm">
            <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-sm text-surface-400">&copy; {{ date('Y') }} SPARTA — Sistem Informasi PKL. All rights reserved.</p>
                <div class="flex items-center gap-6 text-sm text-surface-400">
                    <a href="{{ route('guide') }}" class="hover:text-surface-600 transition-colors">Panduan</a>
                    <a href="{{ route('login') }}" class="hover:text-surface-600 transition-colors">Masuk</a>
                </div>
            </div>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        lucide.createIcons();

        function showCoolAlert() {
            Swal.fire({
                title: '<span class="text-surface-900 font-extrabold">Selamat Datang!</span>',
                html: '<p class="text-surface-500 font-medium">Silakan pilih apakah Anda ingin masuk atau membuat akun baru.</p>',
                icon: 'info',
                iconColor: '#6366f1',
                showConfirmButton: true,
                showDenyButton: true,
                confirmButtonText: 'Masuk',
                denyButtonText: 'Buat Akun',
                background: '#ffffff',
                customClass: {
                    popup: 'rounded-2xl border-none shadow-2xl p-8',
                    confirmButton: 'px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-indigo-500 rounded-xl font-bold text-sm shadow-lg transition-all text-white hover:from-indigo-700 hover:to-indigo-600 mr-2',
                    denyButton: 'px-6 py-2.5 bg-white border border-slate-200 text-slate-700 rounded-xl font-bold text-sm hover:bg-slate-50 transition-all ml-2'
                },
                buttonsStyling: false,
                showClass: { popup: 'animate__animated animate__zoomIn animate__faster' },
                hideClass: { popup: 'animate__animated animate__zoomOut animate__faster' }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('login') }}";
                } else if (result.isDenied) {
                    window.location.href = "{{ route('register') }}";
                }
            });
        }
    </script>
</body>
</html>
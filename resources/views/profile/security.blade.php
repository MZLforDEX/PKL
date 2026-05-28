<x-app-layout>
    <div class="py-5 md:py-10">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumbs -->
            <nav class="flex mb-4 md:mb-8 text-xs font-bold text-surface-400 uppercase tracking-widest" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route(match(Auth::user()->role) {'admin' => 'admin.dashboard', 'guru' => 'guru.dashboard', 'siswa' => 'siswa.dashboard', 'pembimbing_industri' => 'pembimbing.dashboard', default => 'dashboard'}) }}" class="hover:text-brand-600 transition-colors">Dashboard</a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i data-lucide="chevron-right" class="w-3 h-3 mx-1"></i>
                            <span class="text-surface-800">Keamanan Akun</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 md:gap-8">
                <!-- Left Column: User Card & Navigation -->
                <div class="lg:col-span-4 space-y-4 md:space-y-6">
                    <div class="card-premium overflow-hidden text-center p-6 md:p-8">
                        <div class="relative inline-block group mb-6">
                            <div class="w-24 h-24 md:w-32 md:h-32 rounded-3xl overflow-hidden shadow-2xl ring-4 ring-white ring-offset-4 ring-offset-surface-50 mx-auto transition-transform duration-500 group-hover:scale-105">
                                @if($user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-brand-500 to-brand-700 flex items-center justify-center text-white text-3xl md:text-5xl font-bold">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <h3 class="text-lg md:text-xl font-bold text-surface-800 leading-tight mb-1">{{ $user->name }}</h3>
                        <p class="text-xs font-bold text-surface-400 uppercase tracking-widest mb-6">{{ $user->role }}</p>

                        <div class="flex items-center justify-center space-x-2 py-3 px-4 bg-brand-50 rounded-2xl border border-brand-100">
                            <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                            <span class="text-xs font-bold text-brand-700 uppercase tracking-tight">Akun Aktif</span>
                        </div>
                    </div>

                    <!-- Navigation Links -->
                    <div class="card-premium p-2">
                        <a href="{{ route('profile.edit') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-bold text-surface-600 hover:bg-surface-50 hover:text-surface-800 transition-all">
                            <i data-lucide="user" class="w-5 h-5"></i>
                            <span>Informasi Akun</span>
                        </a>
                        <a href="{{ route('profile.security') }}" class="flex items-center space-x-3 px-4 py-3 rounded-xl text-sm font-bold bg-brand-50 text-brand-700 border border-brand-100 transition-all mt-1">
                            <i data-lucide="lock" class="w-5 h-5"></i>
                            <span>Keamanan</span>
                        </a>
                    </div>
                </div>

                <!-- Right Column: Security Forms -->
                <div class="lg:col-span-8 space-y-4 md:space-y-8">
                    <!-- Password Update Form -->
                    <div id="password" class="card-premium p-4 sm:p-6 md:p-8">
                        <div class="flex items-center space-x-4 mb-8">
                            <div class="w-10 h-10 md:w-12 md:h-12 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center">
                                <i data-lucide="shield-check" class="w-5 h-5 md:w-6 md:h-6"></i>
                            </div>
                            <div>
                                <h2 class="text-lg md:text-2xl font-bold text-surface-800 tracking-tight">Perbarui Kata Sandi</h2>
                                <p class="text-xs md:text-sm text-surface-500 mt-1">Pastikan akun Anda menggunakan kata sandi yang panjang dan acak agar tetap aman.</p>
                            </div>
                        </div>
                        <div class="max-w-xl">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>

                    <!-- Danger Zone -->
                    <div id="danger" class="card-premium p-4 sm:p-6 md:p-8 border-t-4 border-rose-500">
                        <div class="flex items-center space-x-4 mb-8">
                            <div class="w-10 h-10 md:w-12 md:h-12 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center">
                                <i data-lucide="trash-2" class="w-5 h-5 md:w-6 md:h-6"></i>
                            </div>
                            <div>
                                <h2 class="text-lg md:text-2xl font-bold text-surface-800 tracking-tight">Hapus Akun</h2>
                                <p class="text-xs md:text-sm text-surface-500 mt-1">Setelah akun Anda dihapus, semua sumber daya dan datanya akan dihapus secara permanen.</p>
                            </div>
                        </div>
                        <div class="max-w-xl">
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

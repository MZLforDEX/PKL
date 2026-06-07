<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <h2 class="font-extrabold text-lg md:text-2xl text-surface-900 tracking-tight">Chat Pesan Guru</h2>
        </div>
    </x-slot>

    <div class="py-4 md:py-6 animate-fade-in">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="flex flex-col lg:flex-row h-[calc(100vh-14rem)] border border-surface-200 dark:border-zinc-800 rounded-2xl overflow-hidden bg-white dark:bg-zinc-950 shadow-glass animate-fade-in">
                
                {{-- Sidebar Kiri - Daftar Percakapan --}}
                <div class="w-full lg:w-72 border-b lg:border-b-0 lg:border-r border-surface-200 dark:border-zinc-800 flex flex-col bg-surface-50/50 dark:bg-zinc-900/30 overflow-y-auto shrink-0">
                    <div class="p-4 border-b border-surface-200 dark:border-zinc-800">
                        <h3 class="text-xs font-bold text-surface-400 uppercase tracking-wider">Daftar Chat Guru</h3>
                    </div>
                    <div class="flex-1 divide-y divide-surface-100 dark:divide-zinc-800/50">
                        @foreach($conversations as $c)
                            <a href="{{ route('admin.pesan-guru.show', $c->id) }}" 
                               class="flex items-center gap-3 p-4 hover:bg-surface-100/50 dark:hover:bg-zinc-900/50 transition-colors">
                                <div class="w-10 h-10 rounded-full bg-brand-100 dark:bg-brand-950/40 text-brand-600 dark:text-brand-400 flex items-center justify-center font-bold shrink-0">
                                    {{ strtoupper(substr($c->kategori, 0, 1)) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-xs font-bold text-surface-900 dark:text-zinc-100 truncate pr-2">
                                            {{ $c->guru?->user?->name ?? 'Guru' }}
                                        </h4>
                                        <span class="text-[9px] text-surface-400 font-medium shrink-0">{{ $c->created_at->format('d/m') }}</span>
                                    </div>
                                    <p class="text-xs text-surface-500 dark:text-zinc-400 truncate mt-0.5">{{ $c->pesan }}</p>
                                    
                                    <div class="flex items-center justify-between mt-1.5">
                                        <span class="text-[9px] font-semibold px-2 py-0.5 rounded bg-surface-100 dark:bg-zinc-850 text-surface-600 dark:text-zinc-300">
                                            {{ ucfirst($c->kategori) }}
                                        </span>
                                    </div>
                                </div>
                            </a>
                        @endforeach

                        @if($conversations->isEmpty())
                            <div class="p-6 text-center text-xs text-surface-400 dark:text-zinc-500">
                                Belum ada aduan atau pesan masuk dari Guru Pembimbing.
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Area Utama - Placeholder Obrolan Kosong --}}
                <div class="flex-1 flex flex-col items-center justify-center p-8 bg-surface-50 dark:bg-zinc-950 text-center">
                    <div class="w-16 h-16 rounded-3xl bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 flex items-center justify-center mb-4 border border-indigo-100 dark:border-indigo-900/30">
                        <i data-lucide="message-square" class="w-8 h-8"></i>
                    </div>
                    <h3 class="text-base font-extrabold text-surface-900 dark:text-zinc-100">SiPKL Chat Room</h3>
                    <p class="text-xs text-surface-500 dark:text-zinc-400 mt-2 max-w-sm">Pilih salah satu percakapan di sebelah kiri untuk mulai membaca dan membalas pesan aduan Guru Pembimbing secara real-time.</p>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>

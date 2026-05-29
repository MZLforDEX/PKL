<x-app-layout>
    <x-slot name="header">
        @php
            $routePrefix = auth()->user()->role === 'admin' ? 'admin' : 'guru';
        @endphp
        <div class="flex items-center gap-3">
            <a href="{{ route($routePrefix . '.pesan.index') }}" class="p-2 rounded-lg hover:bg-surface-100 text-surface-400 hover:text-surface-600 transition-colors">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
            </a>
            <h2 class="font-extrabold text-lg md:text-2xl text-surface-900 tracking-tight">Tinjau Pesan Industri</h2>
        </div>
    </x-slot>

    <div class="py-6 md:py-10 animate-fade-in">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                {{-- Detail Aduan --}}
                <div class="lg:col-span-2 space-y-6">
                    <div class="card-premium p-6">
                        <div class="flex items-center justify-between border-b border-surface-100 pb-4 mb-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 dark:bg-indigo-950/20 dark:text-indigo-400">
                                Kategori: {{ ucfirst($pesan->kategori) }}
                            </span>
                            <span class="text-xs text-surface-400 font-medium">
                                Dikirim: {{ $pesan->created_at->format('d M Y, H:i') }}
                            </span>
                        </div>

                        <h3 class="text-lg font-bold text-surface-900 mb-3">{{ $pesan->subjek }}</h3>
                        <p class="text-sm text-surface-600 leading-relaxed whitespace-pre-line">{{ $pesan->pesan }}</p>

                        @if($pesan->lampiran)
                            <div class="mt-6 border-t border-surface-100 pt-4">
                                <span class="text-xs font-bold text-surface-400 block mb-2">Lampiran Dokumen/Gambar:</span>
                                @php
                                    $extension = pathinfo($pesan->lampiran, PATHINFO_EXTENSION);
                                    $isImage = in_array(strtolower($extension), ['jpg', 'jpeg', 'png']);
                                @endphp
                                
                                @if($isImage)
                                    <div class="mb-3 max-w-md rounded-xl overflow-hidden border border-surface-200">
                                        <img src="{{ asset('storage/' . $pesan->lampiran) }}" class="w-full object-contain max-h-60" alt="Lampiran">
                                    </div>
                                @endif

                                <a href="{{ asset('storage/' . $pesan->lampiran) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 rounded-xl text-xs font-bold transition-all shadow-sm">
                                    <i data-lucide="download" class="w-3.5 h-3.5 shrink-0"></i>
                                    Unduh Lampiran ({{ strtoupper($extension) }})
                                </a>
                            </div>
                        @endif
                    </div>

                    {{-- Form Tanggapan --}}
                    <div class="card-premium p-6">
                        <h4 class="text-base font-bold text-surface-900 mb-4 flex items-center gap-2">
                            <i data-lucide="reply" class="w-5 h-5 text-indigo-500"></i>
                            Berikan Tanggapan / Balasan
                        </h4>

                        <form action="{{ route($routePrefix . '.pesan.reply', $pesan->id) }}" method="POST">
                            @csrf
                            <div class="space-y-5">
                                <div>
                                    <label class="form-label">Tanggapan Sekolah</label>
                                    <textarea name="tanggapan" rows="6" class="form-input-premium mt-1.5" placeholder="Tuliskan balasan, solusi, atau instruksi tindak lanjut sekolah..." required>{{ old('tanggapan', $pesan->tanggapan) }}</textarea>
                                    @error('tanggapan') <p class="text-rose-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="form-label">Status Pesan</label>
                                    <select name="status" class="form-input-premium mt-1.5" required>
                                        <option value="proses" {{ old('status', $pesan->status) == 'proses' ? 'selected' : '' }}>Diproses (Sedang ditindaklanjuti)</option>
                                        <option value="selesai" {{ old('status', $pesan->status) == 'selesai' ? 'selected' : '' }}>Selesai (Tiket/Aduan ditutup)</option>
                                    </select>
                                    @error('status') <p class="text-rose-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                                </div>

                                <div class="flex justify-end gap-3 pt-2">
                                    <button type="submit" class="btn-primary flex items-center gap-1.5 bg-emerald-600 hover:bg-emerald-700">
                                        <i data-lucide="check" class="w-4 h-4 shrink-0"></i>
                                        Kirim & Simpan Tanggapan
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Informasi Pengirim --}}
                <div class="space-y-6">
                    {{-- Status Card --}}
                    <div class="card-premium p-6">
                        <h4 class="text-xs font-bold text-surface-400 uppercase tracking-wider mb-3">Status Saat Ini</h4>
                        @php
                            $statusClasses = [
                                'menunggu_tanggapan' => 'bg-amber-50 text-amber-600 border-amber-100 dark:bg-amber-950/20 dark:text-amber-400 dark:border-amber-900/30',
                                'proses' => 'bg-blue-50 text-blue-600 border-blue-100 dark:bg-blue-950/20 dark:text-blue-400 dark:border-blue-900/30',
                                'selesai' => 'bg-emerald-50 text-emerald-600 border-emerald-100 dark:bg-emerald-950/20 dark:text-emerald-400 dark:border-emerald-900/30',
                            ];
                            $class = $statusClasses[$pesan->status] ?? 'bg-surface-100 text-surface-600';
                            $label = match($pesan->status) {
                                'menunggu_tanggapan' => 'Menunggu Tanggapan',
                                'proses' => 'Sedang Diproses',
                                'selesai' => 'Selesai',
                                default => $pesan->status,
                            };
                        @endphp
                        <span class="status-badge {{ $class }} w-full text-center py-2 text-sm font-semibold rounded-xl inline-block border">
                            {{ $label }}
                        </span>
                    </div>

                    {{-- Profil Pengirim --}}
                    <div class="card-premium p-6">
                        <h4 class="text-xs font-bold text-surface-400 uppercase tracking-wider mb-4">Profil Pengirim</h4>
                        
                        <div class="space-y-4">
                            <div>
                                <span class="text-[10px] text-surface-400 font-bold uppercase tracking-wider">Nama Pembimbing</span>
                                <p class="text-sm font-bold text-surface-900 mt-0.5">{{ $pesan->pembimbingIndustri?->user?->name ?? '-' }}</p>
                            </div>
                            <div>
                                <span class="text-[10px] text-surface-400 font-bold uppercase tracking-wider">Perusahaan / Instansi</span>
                                <p class="text-sm font-bold text-surface-900 mt-0.5">{{ $pesan->pembimbingIndustri?->tempatPkl?->nama_tempat ?? '-' }}</p>
                            </div>
                            <div>
                                <span class="text-[10px] text-surface-400 font-bold uppercase tracking-wider">Jabatan</span>
                                <p class="text-sm text-surface-700 mt-0.5">{{ $pesan->pembimbingIndustri?->jabatan ?? '-' }}</p>
                            </div>
                            <div>
                                <span class="text-[10px] text-surface-400 font-bold uppercase tracking-wider">Kontak HP</span>
                                <p class="text-sm text-surface-700 mt-0.5">{{ $pesan->pembimbingIndustri?->no_hp ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Riwayat Tanggapan Sebelumnya --}}
                    @if($pesan->tanggapan)
                        <div class="card-premium p-6 border-l-[3px] border-l-emerald-500">
                            <h4 class="text-xs font-bold text-surface-400 uppercase tracking-wider mb-3">Tanggapan Terakhir</h4>
                            <p class="text-xs text-surface-600 leading-relaxed italic">
                                "{{ Str::limit($pesan->tanggapan, 150) }}"
                            </p>
                            <div class="mt-3 text-[10px] text-surface-400 font-medium">
                                <p class="font-bold text-surface-700 dark:text-zinc-300">Oleh: {{ $pesan->dibalasOleh?->name ?? 'Admin' }}</p>
                                <p class="mt-0.5">Waktu: {{ $pesan->dibalas_pada?->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</x-app-layout>

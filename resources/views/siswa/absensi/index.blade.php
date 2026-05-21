<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-lg md:text-2xl text-surface-900 tracking-tight">Absensi Harian PKL</h2>
        <p class="text-sm text-surface-500 mt-0.5">Catat kehadiran Anda setiap hari kerja di lokasi PKL.</p>
    </x-slot>

    <div class="py-6 md:py-10 animate-fade-in">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="alert-success mb-6">
                    <i data-lucide="check-circle" class="w-5 h-5 shrink-0"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-100 text-rose-800 text-sm font-medium flex items-center gap-2.5">
                    <i data-lucide="alert-circle" class="w-5 h-5 text-rose-600"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Absensi Action Card -->
                <div class="lg:col-span-1 space-y-6">
                    <div class="card-premium p-6">
                        <h3 class="text-base font-bold text-surface-900 mb-4 flex items-center gap-2">
                            <i data-lucide="clock" class="w-5 h-5 text-brand-500"></i>
                            Menu Absensi
                        </h3>

                        @if(!$pengajuan)
                            <div class="text-center py-6">
                                <i data-lucide="alert-triangle" class="w-10 h-10 text-amber-500 mx-auto mb-3"></i>
                                <p class="text-sm text-surface-500 font-medium">Anda belum memulai PKL atau belum memiliki pengajuan yang disetujui.</p>
                            </div>
                        @elseif($sudahAbsenHariIni)
                            <div class="p-5 bg-emerald-50/60 border border-emerald-100 rounded-2xl text-center">
                                <div class="w-12 h-12 rounded-full bg-emerald-500 text-white flex items-center justify-center mx-auto mb-3 shadow-lg shadow-emerald-100">
                                    <i data-lucide="check" class="w-6 h-6"></i>
                                </div>
                                <h4 class="font-bold text-emerald-800 text-sm">Sudah Absen Hari Ini</h4>
                                <p class="text-xs text-emerald-600 mt-1 leading-relaxed">Terima kasih, Anda telah mengisi kehadiran untuk hari ini.</p>
                            </div>
                        @else
                            <form id="attendance-form" action="{{ route('siswa.absensi.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="latitude" id="latitude">
                                <input type="hidden" name="longitude" id="longitude">
                                <input type="hidden" name="foto_selfie" id="foto_selfie">

                                <!-- Webcam Area -->
                                <div class="mb-4">
                                    <label class="form-label mb-2 block">Foto Selfie Kehadiran</label>
                                    <div class="relative w-full aspect-video rounded-2xl bg-surface-900 overflow-hidden border border-surface-200 shadow-inner group">
                                        <!-- Video element for live preview -->
                                        <video id="webcam" class="w-full h-full object-cover" autoplay playsinline></video>
                                        <!-- Canvas element to capture frame -->
                                        <canvas id="photo-canvas" class="hidden w-full h-full object-cover"></canvas>
                                        <!-- Captured Preview Image -->
                                        <img id="photo-preview" class="hidden w-full h-full object-cover">
                                        
                                        <div class="absolute bottom-3 left-1/2 -translate-x-1/2 flex items-center gap-2 z-10">
                                            <button type="button" id="btn-capture" class="px-4 py-2 bg-brand-600 text-white rounded-lg text-xs font-bold hover:bg-brand-700 transition-colors shadow-lg flex items-center gap-1.5">
                                                <i data-lucide="camera" class="w-4 h-4"></i>Ambil Foto
                                            </button>
                                            <button type="button" id="btn-retake" class="hidden px-4 py-2 bg-surface-800 text-white rounded-lg text-xs font-bold hover:bg-surface-700 transition-colors shadow-lg flex items-center gap-1.5">
                                                <i data-lucide="refresh-cw" class="w-4 h-4"></i>Ulangi
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Camera upload fallback (useful if webcam API denied) -->
                                    <div id="fallback-upload" class="hidden mt-3">
                                        <label class="text-xs text-surface-400 font-medium block mb-1">Kamera Error? Gunakan file upload:</label>
                                        <input type="file" id="file-input" accept="image/*" class="form-input-premium !py-1.5 text-xs">
                                    </div>
                                </div>

                                <!-- GPS Status -->
                                <div class="mb-5 p-3.5 bg-surface-50 border border-surface-100 rounded-xl flex items-center justify-between text-xs">
                                    <div class="flex items-center gap-2">
                                        <i data-lucide="map-pin" class="w-4 h-4 text-brand-500"></i>
                                        <span id="gps-status" class="text-surface-500 font-medium">Mencari GPS...</span>
                                    </div>
                                    <span id="gps-badge" class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-600 border border-amber-100">Pending</span>
                                </div>

                                <button type="submit" id="btn-submit-attendance" class="btn-primary w-full !py-3 flex items-center justify-center gap-2" disabled>
                                    <i data-lucide="fingerprint" class="w-5 h-5"></i>
                                    Kirim Kehadiran
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <!-- Absensi History List -->
                <div class="lg:col-span-2">
                    <div class="card-premium overflow-hidden">
                        <div class="p-5 md:p-6 border-b border-surface-100 flex items-center justify-between">
                            <h3 class="text-base font-bold text-surface-900 flex items-center gap-2">
                                <i data-lucide="clipboard-list" class="w-5 h-5 text-purple-500"></i>
                                Riwayat Kehadiran
                            </h3>
                            <span class="text-xs text-surface-400 font-medium">Bulan Ini</span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="table-premium">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Jam Masuk</th>
                                        <th>Foto</th>
                                        <th>Lokasi</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($absensi as $a)
                                    <tr>
                                        <td class="whitespace-nowrap text-sm font-semibold text-surface-800">
                                            {{ \Carbon\Carbon::parse($a->tanggal)->format('d F Y') }}
                                        </td>
                                        <td class="whitespace-nowrap text-sm text-surface-600 font-mono">
                                            {{ $a->jam_masuk }}
                                        </td>
                                        <td>
                                            <div class="w-10 h-10 rounded-lg overflow-hidden border border-surface-200 shadow-sm hover:scale-105 transition-transform">
                                                <a href="{{ asset('storage/' . $a->foto_selfie) }}" target="_blank">
                                                    <img src="{{ asset('storage/' . $a->foto_selfie) }}" class="w-full h-full object-cover">
                                                </a>
                                            </div>
                                        </td>
                                        <td class="whitespace-nowrap">
                                            @if($a->latitude && $a->longitude)
                                                <a href="https://www.google.com/maps?q={{ $a->latitude }},{{ $a->longitude }}" target="_blank" class="inline-flex items-center text-xs text-brand-600 hover:text-brand-700 font-semibold gap-1">
                                                    <i data-lucide="map" class="w-4 h-4 text-emerald-500 shrink-0"></i>
                                                    Lihat Maps
                                                </a>
                                            @else
                                                <span class="text-xs text-surface-400 font-medium">Tanpa GPS</span>
                                            @endif
                                        </td>
                                        <td class="whitespace-nowrap">
                                            <span class="status-badge {{ $a->status === 'hadir' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-rose-50 text-rose-600 border border-rose-100' }}">
                                                {{ ucfirst($a->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5">
                                            <div class="empty-state">
                                                <div class="empty-state-icon">
                                                    <i data-lucide="calendar" class="w-8 h-8"></i>
                                                </div>
                                                <p class="text-surface-400 font-medium mt-3">Belum ada riwayat absensi.</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if($pengajuan && $absensi->hasPages())
                        <div class="p-4 sm:p-5 border-t border-surface-100 bg-surface-50/30">
                            {{ $absensi->links() }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script to handle Geolocation & Camera API -->
    @if($pengajuan && !$sudahAbsenHariIni)
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const video = document.getElementById('webcam');
            const canvas = document.getElementById('photo-canvas');
            const preview = document.getElementById('photo-preview');
            const btnCapture = document.getElementById('btn-capture');
            const btnRetake = document.getElementById('btn-retake');
            const fileInput = document.getElementById('file-input');
            const fallbackUpload = document.getElementById('fallback-upload');
            const btnSubmit = document.getElementById('btn-submit-attendance');

            const latInput = document.getElementById('latitude');
            const lngInput = document.getElementById('longitude');
            const selfieInput = document.getElementById('foto_selfie');
            const gpsStatus = document.getElementById('gps-status');
            const gpsBadge = document.getElementById('gps-badge');

            let stream = null;

            // 1. Initialize Webcam
            navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' } })
                .then(function (mediaStream) {
                    stream = mediaStream;
                    video.srcObject = mediaStream;
                })
                .catch(function (error) {
                    console.error("Camera access failed:", error);
                    video.classList.add('hidden');
                    fallbackUpload.classList.remove('hidden');
                });

            // Capture Frame
            btnCapture.addEventListener('click', function () {
                if (stream) {
                    const context = canvas.getContext('2d');
                    canvas.width = video.videoWidth || 640;
                    canvas.height = video.videoHeight || 480;
                    
                    // Draw mirror image for selfie feel
                    context.translate(canvas.width, 0);
                    context.scale(-1, 1);
                    
                    context.drawImage(video, 0, 0, canvas.width, canvas.height);
                    
                    const dataUrl = canvas.toDataURL('image/jpeg');
                    selfieInput.value = dataUrl;
                    
                    preview.src = dataUrl;
                    preview.classList.remove('hidden');
                    video.classList.add('hidden');

                    btnCapture.classList.add('hidden');
                    btnRetake.classList.remove('hidden');
                    checkSubmitReady();
                }
            });

            // Retake Photo
            btnRetake.addEventListener('click', function () {
                selfieInput.value = '';
                preview.classList.add('hidden');
                video.classList.remove('hidden');
                btnCapture.classList.remove('hidden');
                btnRetake.classList.add('hidden');
                checkSubmitReady();
            });

            // Standard File Input Fallback
            fileInput.addEventListener('change', function (e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (event) {
                        selfieInput.value = event.target.result;
                        preview.src = event.target.result;
                        preview.classList.remove('hidden');
                        video.classList.add('hidden');
                        btnCapture.classList.add('hidden');
                        btnRetake.classList.remove('hidden');
                        checkSubmitReady();
                    };
                    reader.readAsDataURL(file);
                }
            });

            // 2. Geolocation API
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function (position) {
                        latInput.value = position.coords.latitude;
                        lngInput.value = position.coords.longitude;
                        gpsStatus.textContent = "Lokasi terdeteksi";
                        gpsBadge.textContent = "Aktif";
                        gpsBadge.className = "px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-100";
                        checkSubmitReady();
                    },
                    function (error) {
                        console.warn("Geolocation failed:", error);
                        gpsStatus.textContent = "GPS tidak aktif / diblokir";
                        gpsBadge.textContent = "Opsional";
                        gpsBadge.className = "px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-600 border border-rose-100";
                        // Location is optional, still allow attendance
                        checkSubmitReady();
                    },
                    { enableHighAccuracy: true, timeout: 10000 }
                );
            } else {
                gpsStatus.textContent = "Browser tidak mendukung GPS";
                gpsBadge.textContent = "Tidak Didukung";
                gpsBadge.className = "px-2 py-0.5 rounded-full text-[10px] font-bold bg-surface-50 text-surface-400 border border-surface-100";
                checkSubmitReady();
            }

            function checkSubmitReady() {
                if (selfieInput.value) {
                    btnSubmit.removeAttribute('disabled');
                } else {
                    btnSubmit.setAttribute('disabled', 'true');
                }
            }
        });
    </script>
    @endif
</x-app-layout>

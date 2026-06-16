<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sertifikat PKL - {{ $pengajuan->siswa?->user?->name ?? 'Siswa' }}</title>
    <!-- Tailwind CSS v3 CDN -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Source+Serif+4:wght@400;600;700&family=Work+Sans:wght@400;600&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "on-background": "#1a1c1c",
                        "surface-container-high": "#e8e8e8",
                        "error-container": "#ffdad6",
                        "tertiary": "#000000",
                        "surface-tint": "#5e5e5e",
                        "tertiary-fixed": "#b5f39b",
                        "on-surface": "#1a1c1c",
                        "on-tertiary-fixed": "#032100",
                        "primary": "#000000",
                        "on-primary-fixed-variant": "#474747",
                        "outline": "#7e7576",
                        "on-error": "#ffffff",
                        "secondary-fixed-dim": "#ffb3ae",
                        "error": "#ba1a1a",
                        "on-secondary-fixed-variant": "#8f1019",
                        "outline-variant": "#cfc4c5",
                        "on-secondary-fixed": "#410004",
                        "tertiary-container": "#032100",
                        "primary-fixed-dim": "#c6c6c6",
                        "primary-container": "#1b1b1b",
                        "on-secondary": "#ffffff",
                        "secondary-container": "#fc635f",
                        "on-tertiary-fixed-variant": "#1c520c",
                        "surface-container-lowest": "#ffffff",
                        "on-secondary-container": "#65000b",
                        "on-primary": "#ffffff",
                        "on-primary-container": "#848484",
                        "inverse-on-surface": "#f0f1f1",
                        "surface-container-low": "#f3f3f4",
                        "background": "#f9f9f9",
                        "primary-fixed": "#e2e2e2",
                        "inverse-surface": "#2f3131",
                        "surface": "#f9f9f9",
                        "on-tertiary": "#ffffff",
                        "on-surface-variant": "#4c4546",
                        "surface-variant": "#e2e2e2",
                        "on-tertiary-container": "#599146",
                        "secondary-fixed": "#ffdad7",
                        "inverse-primary": "#c6c6c6",
                        "tertiary-fixed-dim": "#9ad682",
                        "on-primary-fixed": "#1b1b1b",
                        "secondary": "#b02c2e",
                        "surface-bright": "#f9f9f9",
                        "surface-container": "#eeeeee",
                        "surface-dim": "#dadada",
                        "on-error-container": "#93000a",
                        "surface-container-highest": "#e2e2e2"
                    },
                    "spacing": {
                        "section-gap": "32px",
                        "gutter": "24px",
                        "border-frame": "40px",
                        "content-margin": "80px",
                        "table-cell-padding": "12px"
                    },
                    "fontFamily": {
                        "body-lg": ["Work Sans"],
                        "headline-xl": ["Source Serif 4"],
                        "body-md": ["Work Sans"],
                        "signature": ["Source Serif 4"],
                        "label-sm": ["Work Sans"],
                        "headline-lg": ["Source Serif 4"],
                        "headline-md": ["Source Serif 4"]
                    },
                    "fontSize": {
                        "body-lg": ["18px", {"lineHeight": "28px", "fontWeight": "400"}],
                        "headline-xl": ["48px", {"lineHeight": "56px", "letterSpacing": "-0.02em", "fontWeight": "700"}],
                        "body-md": ["14px", {"lineHeight": "22px", "fontWeight": "400"}],
                        "signature": ["16px", {"lineHeight": "20px", "fontWeight": "600"}],
                        "label-sm": ["12px", {"lineHeight": "16px", "letterSpacing": "0.05em", "fontWeight": "600"}],
                        "headline-lg": ["32px", {"lineHeight": "40px", "fontWeight": "600"}],
                        "headline-md": ["24px", {"lineHeight": "32px", "fontWeight": "600"}]
                    }
                },
            },
        }
    </script>
    
    <style>
        @page {
            size: A4 portrait;
            margin: 0;
        }
        
        body {
            background-color: #cbd5e1;
            margin: 0;
            padding: 40px 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 40px;
            -webkit-print-color-adjust: exact;
        }
        
        .certificate-page {
            width: 210mm;
            height: 297mm;
            position: relative;
            background-color: #ffffff;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            box-sizing: border-box;
            flex-shrink: 0;
        }

        .content-canvas {
            position: relative;
            z-index: 20;
            height: 100%;
            padding: 40px 120px 100px 120px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
            box-sizing: border-box;
        }

        .seal-placeholder {
            width: 120px;
            height: 120px;
            border: 2px dashed #cfc4c5;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0.5;
        }

        .signature-line {
            width: 200px;
            border-bottom: 1.5px solid #000000;
            margin-bottom: 8px;
        }
        
        /* Grades Table Styles */
        .grade-table-wrapper table {
            border-collapse: collapse;
            width: 100%;
        }
        
        .grade-table-wrapper th, .grade-table-wrapper td {
            border: 1px solid black;
            padding: 5px 10px;
        }
        
        /* Controls */
        .no-print {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 100;
            display: flex;
            gap: 10px;
        }
        .btn-print {
            padding: 12px 24px;
            background-color: #0f172a;
            color: white;
            border: none;
            border-radius: 12px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.15);
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 10pt;
            transition: all 0.2s;
        }
        .btn-print:hover { 
            background-color: #1e293b; 
            transform: translateY(-1px);
        }
        .btn-back {
            padding: 12px 20px;
            background-color: #ffffff;
            color: #0f172a;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.05);
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 10pt;
            text-decoration: none;
            transition: all 0.2s;
        }
        .btn-back:hover { 
            background-color: #f8fafc;
            transform: translateY(-1px);
        }

        @media print {
            .no-print { display: none !important; }
            body { 
                background-color: white !important; 
                margin: 0 !important;
                padding: 0 !important;
                gap: 0 !important;
            }
            .certificate-page { 
                margin: 0 !important; 
                border: none !important; 
                box-shadow: none !important; 
                page-break-after: always !important;
                break-after: page !important;
                border-radius: 0 !important;
            }
        }
    </style>
</head>
<body>
    @php
        function penyebut($nilai) {
            $nilai = abs($nilai);
            $huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
            $temp = "";
            if ($nilai < 12) {
                $temp = " " . $huruf[$nilai];
            } else if ($nilai < 20) {
                $temp = penyebut($nilai - 10). " belas";
            } else if ($nilai < 100) {
                $temp = penyebut((int)($nilai/10))." puluh". penyebut($nilai % 10);
            } else if ($nilai < 200) {
                $temp = " seratus" . penyebut($nilai - 100);
            } else if ($nilai < 1000) {
                $temp = penyebut((int)($nilai/100)) . " ratus" . penyebut($nilai % 100);
            }
            return $temp;
        }

        function terbilang($nilai) {
            $nilaiStr = strval($nilai);
            if (strpos($nilaiStr, '.') !== false) {
                list($bulat, $pecahan) = explode('.', $nilaiStr);
                $hasilBulat = terbilang(intval($bulat));
                
                $digits = [
                    '0' => 'nol', '1' => 'satu', '2' => 'dua', '3' => 'tiga', '4' => 'empat',
                    '5' => 'lima', '6' => 'enam', '7' => 'tujuh', '8' => 'delapan', '9' => 'sembilan'
                ];
                $hasilPecahan = [];
                for ($i = 0; $i < strlen($pecahan); $i++) {
                    $char = $pecahan[$i];
                    $hasilPecahan[] = isset($digits[$char]) ? $digits[$char] : $char;
                }
                
                return $hasilBulat . " Koma " . ucwords(implode(' ', $hasilPecahan));
            }

            $nilai = abs(intval($nilai));
            if ($nilai == 0) {
                $hasil = "nol";
            } else {
                $hasil = trim(penyebut($nilai));
            }
            return ucwords($hasil);
        }
        
        $aspekTeknis = [];
        $aspekNonTeknis = [];
        
        $nonTeknisKeywords = ['sikap', 'disiplin', 'kepribadian', 'motivasi', 'tanggung jawab', 'kerja sama', 'kemampuan kerja', 'etika', 'kehadiran', 'perilaku', 'kerajinan', 'loyalitas', 'inisiatif', 'kerapian', 'non teknis', 'non-teknis'];
        
        if ($pengajuan->penilaianPkl && $pengajuan->penilaianPkl->detail_nilai) {
            foreach ($pengajuan->penilaianPkl->detail_nilai as $item) {
                $isNonTeknis = false;
                $namaLower = strtolower($item['nama']);
                foreach ($nonTeknisKeywords as $keyword) {
                    if (str_contains($namaLower, $keyword)) {
                        $isNonTeknis = true;
                        break;
                    }
                }
                if ($isNonTeknis) {
                    $aspekNonTeknis[] = $item;
                } else {
                    $aspekTeknis[] = $item;
                }
            }
        } else {
            // Fallback legacy values
            $aspekNonTeknis[] = ['nama' => 'Aspek Sikap dan Kedisiplinan Kerja', 'nilai' => $pengajuan->penilaianPkl?->nilai_sikap ?? 0];
            $aspekTeknis[] = ['nama' => 'Aspek Keterampilan Teknik & Kerja Lapangan', 'nilai' => $pengajuan->penilaianPkl?->nilai_keterampilan ?? 0];
            $aspekTeknis[] = ['nama' => 'Kualitas Penulisan & Pemaparan Laporan Akhir', 'nilai' => $pengajuan->penilaianPkl?->nilai_laporan ?? 0];
        }
    @endphp

    <div class="no-print">
        <a href="{{ route('siswa.pengajuan.index') }}" class="btn-back">
            <i data-lucide="arrow-left" style="width: 16px; height: 16px;"></i>
            Kembali
        </a>
        <button onclick="window.print()" class="btn-print">
            <i data-lucide="printer" style="width: 16px; height: 16px;"></i>
            Cetak Sertifikat
        </button>
    </div>

    <!-- PAGE 1: CERTIFICATE FRONT -->
    <main class="certificate-page relative" style="background-image: url('{{ asset('images/sertifikat-depan.jpg?v=2') }}'); background-size: 100% 100%; background-position: center; background-repeat: no-repeat;">
        <!-- Certificate Content -->
        <div class="content-canvas text-center">
            <!-- Certificate Header (Logo + SERTIFIKAT + PRAKTIK KERJA INDUSTRI) -->
            <div style="height: 95mm;" class="w-full shrink-0 flex flex-col items-center justify-end pb-8">
                <!-- Logo -->
                <div class="mb-4 flex items-center justify-center h-20">
                    @if($pengajuan->tempatPkl?->pembimbingIndustri?->first()?->logo)
                        <img src="{{ asset('storage/' . $pengajuan->tempatPkl->pembimbingIndustri->first()->logo) }}" alt="Logo Industri" class="h-20 max-w-[150px] object-contain select-none">
                    @else
                        <svg class="w-20 h-20" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <defs>
                                <linearGradient id="gold-grad" x1="0%" y1="0%" x2="100%" y2="100%">
                                    <stop offset="0%" stop-color="#dfba6b" />
                                    <stop offset="50%" stop-color="#c5a85c" />
                                    <stop offset="100%" stop-color="#917335" />
                                </linearGradient>
                            </defs>
                            <!-- Circular Gear -->
                            <path d="M32 6C28.5 6 27.8 8.8 25.8 9.5C23.8 10.2 20.8 8.5 18.5 10.2C16.2 11.9 17.8 14.8 16.4 16.7C15 18.6 11.8 18.5 11.1 20.8C10.4 23.1 12.8 25.1 12.8 27.4C12.8 29.7 10.4 31.7 11.1 34C11.8 36.3 15 36.2 16.4 38.1C17.8 40 16.2 42.9 18.5 44.6C20.8 46.3 23.8 44.6 25.8 45.3C27.8 46 28.5 48.8 32 48.8C35.5 48.8 36.2 46 38.2 45.3C40.2 44.6 43.2 46.3 45.5 44.6C47.8 42.9 46.2 40 47.6 38.1C49 36.2 52.2 36.3 52.9 34C53.6 31.7 51.2 29.7 51.2 27.4C51.2 25.1 53.6 23.1 52.9 20.8C52.2 18.5 49 18.6 47.6 16.7C46.2 14.8 47.8 11.9 45.5 10.2C43.2 8.5 40.2 10.2 38.2 9.5C36.2 8.8 35.5 6 32 6Z" stroke="url(#gold-grad)" stroke-width="2.5" stroke-linejoin="round" />
                            <circle cx="32" cy="27.4" r="14" stroke="url(#gold-grad)" stroke-width="2" />
                            <!-- Factory/Industry Towers inside -->
                            <path d="M24 35V26L28 29V23L32 26V20L36 23V35H24Z" fill="url(#gold-grad)" />
                            <path d="M38 35V24L41 27V35H38Z" fill="url(#gold-grad)" opacity="0.8" />
                            <!-- Laurel Wreath wings below -->
                            <path d="M12 41C16 45 24 47 32 47C40 47 48 45 52 41M16 38C18 41 24 43 32 43C40 43 46 41 48 38" stroke="url(#gold-grad)" stroke-width="1.5" stroke-linecap="round" />
                        </svg>
                    @endif
                </div>
                
                <!-- "SERTIFIKAT" Title -->
                <h1 class="text-[34pt] font-extrabold uppercase tracking-[0.15em] leading-none mb-1.5" style="font-family: 'Source Serif 4', serif; background: linear-gradient(135deg, #dfba6b 0%, #c5a85c 50%, #917335 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; filter: drop-shadow(0px 1px 1px rgba(0,0,0,0.1));">
                    Sertifikat
                </h1>
                
                <!-- Subtitle "PRAKTIK KERJA INDUSTRI" -->
                <div class="flex items-center gap-3">
                    <div class="h-[1.5px] w-6 bg-gradient-to-r from-transparent to-[#c5a85c]"></div>
                    <h2 class="text-[10pt] font-semibold uppercase tracking-[0.35em] text-[#856c36] whitespace-nowrap" style="font-family: 'Work Sans', sans-serif;">
                        Praktik Kerja Industri
                    </h2>
                    <div class="h-[1.5px] w-6 bg-gradient-to-l from-transparent to-[#c5a85c]"></div>
                </div>
            </div>

            <!-- Recipient Section -->
            <div class="w-full flex flex-col items-center">
                <p class="font-body-lg text-body-lg text-on-surface-variant italic mb-2">
                    Diberikan kepada:
                </p>
                <h3 class="font-headline-xl text-headline-xl text-primary border-b-2 border-outline-variant px-8 pb-2 mb-6">
                    {{ strtoupper($pengajuan->siswa?->user?->name ?? 'Siswa') }}
                </h3>
                <p class="font-body-lg text-body-lg text-on-surface max-w-2xl leading-relaxed">
                    Siswa dari <span class="font-bold">SMK Negeri 1 SiPKL</span> telah berhasil menyelesaikan Program Praktik Kerja Industri (Prakerin) di <span class="font-bold">{{ $pengajuan->tempatPkl?->nama_tempat ?? 'Mitra Industri' }}</span> untuk periode <span class="font-bold">{{ \Carbon\Carbon::parse($pengajuan->tanggal_mulai)->translatedFormat('d F Y') }} s/d {{ \Carbon\Carbon::parse($pengajuan->tanggal_selesai)->translatedFormat('d F Y') }}</span>.
                </p>
            </div>
            
            <!-- Validation/Statement Section -->
            <div class="w-full mt-4">
                <p class="font-body-md text-body-md text-on-surface-variant mb-12">
                    Ditetapkan dengan segala hak dan tanggung jawab yang menyertainya sebagai pengakuan atas pencapaian kompetensi industri.
                </p>
            </div>
            
            <!-- Signatures Section -->
            <div class="w-full flex justify-center items-end">
                <!-- Industry Supervisor -->
                <div class="flex flex-col items-center" style="width: 250px;">
                    <p class="font-label-sm text-label-sm text-on-surface-variant mb-1 text-[10px]">
                        {{ \Carbon\Carbon::parse($pengajuan->tanggal_selesai)->translatedFormat('d F Y') }}
                    </p>
                    <p class="font-label-sm text-label-sm text-on-surface-variant uppercase tracking-widest text-[10px] mb-2">
                        Pembimbing Industri
                    </p>
                    
                    <!-- Signature Image Area -->
                    <div class="h-16 w-full flex items-center justify-center relative">
                        @if($pengajuan->tempatPkl?->pembimbingIndustri?->first()?->tanda_tangan)
                            <img src="{{ asset('storage/' . $pengajuan->tempatPkl->pembimbingIndustri->first()->tanda_tangan) }}" alt="Tanda Tangan" class="h-16 max-w-[150px] object-contain select-none z-10 absolute bottom-[-4px]">
                        @else
                            <div class="h-16"></div>
                        @endif
                    </div>

                    <div class="signature-line"></div>
                    <p class="font-signature text-signature text-primary">
                        {{ strtoupper($pengajuan->tempatPkl?->pembimbingIndustri?->first()?->user?->name ?? 'Pembimbing') }}
                    </p>
                    <p class="font-label-sm text-label-sm text-on-surface-variant text-[10px]">
                        {{ $pengajuan->tempatPkl?->pembimbingIndustri?->first()?->jabatan ?? 'Pembimbing Industri' }}
                    </p>
                </div>
            </div>
            
            <!-- Seal Area -->
            <div class="absolute bottom-24 left-1/2 -translate-x-1/2 opacity-20">
                <div class="seal-placeholder">
                    <span class="font-label-sm uppercase text-center text-xs">Stempel Resmi</span>
                </div>
            </div>
        </div>
    </main>

    <!-- PAGE 2: GRADES BACK -->
    <div class="certificate-page bg-cover bg-center bg-no-repeat" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuDYpP4mrc0yGfJnbWpnb_6cGmIfx-Dyk2CnF8d9wcJzU8aQO_pdhwZp7-Ig_8dqcPIH5NihDtcQoa_wpp2QO7dfyKlpyt8-HH8088h4xsyjKlradHEIIjdH9XSKRlmeAMnC24liKdd7M3baG2Tad-IpBJa7cv6CFIHrprXUXUST8NQjmEzxm2iP1TKqW8Yv6WFvY35bbRFFNrFwu6joS-9L2jlTyblyLtAsPOHC4KASZ8fEL3nLVNuC2XwJMT4Pj_0BFEoR2Pq2QxE'); font-family: 'Times New Roman', Times, serif;">
        <!-- Main Content -->
        <div class="content-wrapper" style="padding: 100px 110px; background-color: transparent;">
            <!-- Header Section -->
            <header class="text-center mb-8">
                <h1 class="text-xl font-bold uppercase tracking-wider">Daftar Nilai</h1>
                <h2 class="text-xl font-bold uppercase tracking-wider">Praktik Kerja Industri</h2>
            </header>
            
            <!-- Metadata Grid -->
            <div class="grid grid-cols-2 gap-8 text-[11pt] mb-6 leading-relaxed">
                <div class="space-y-1">
                    <div class="flex"><span class="w-36 font-bold shrink-0">Nama Siswa</span><span class="mr-2">:</span><span>{{ $pengajuan->siswa?->user?->name ?? '-' }}</span></div>
                    <div class="flex"><span class="w-36 font-bold shrink-0">Nomor Induk (NIS)</span><span class="mr-2">:</span><span>{{ $pengajuan->siswa?->nis ?? '-' }}</span></div>
                    <div class="flex"><span class="w-36 font-bold shrink-0">Kompetensi Keahlian</span><span class="mr-2">:</span><span>{{ $pengajuan->siswa?->jurusan ?? '-' }}</span></div>
                </div>
                <div class="space-y-1">
                    <div class="flex"><span class="w-36 font-bold shrink-0">Tempat PKL</span><span class="mr-2">:</span><span>{{ $pengajuan->tempatPkl?->nama_tempat ?? '-' }}</span></div>
                    <div class="flex"><span class="w-36 font-bold shrink-0">Alamat Mitra</span><span class="mr-2">:</span><span>{{ $pengajuan->tempatPkl?->alamat ?? '-' }}</span></div>
                    <div class="flex"><span class="w-36 font-bold shrink-0">Durasi Pelaksanaan</span><span class="mr-2">:</span><span>{{ \Carbon\Carbon::parse($pengajuan->tanggal_mulai)->translatedFormat('d M Y') }} - {{ \Carbon\Carbon::parse($pengajuan->tanggal_selesai)->translatedFormat('d M Y') }}</span></div>
                </div>
            </div>
            
            <!-- Section A: Technical -->
            <section class="mb-6">
                <h3 class="font-bold mb-2 text-[12pt]">A. Penilaian Aspek Teknis</h3>
                <div class="grade-table-wrapper">
                    <table data-purpose="technical-assessment-table">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="w-12 text-center" rowspan="2">No.</th>
                                <th class="text-left" rowspan="2">Uraian Keterampilan</th>
                                <th class="text-center" colspan="2">Nilai</th>
                            </tr>
                            <tr class="bg-gray-50">
                                <th class="w-20 text-center">Angka</th>
                                <th class="text-left">Huruf</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $noTeknis = 1; @endphp
                            @foreach($aspekTeknis as $item)
                                <tr>
                                    <td class="text-center">{{ $noTeknis++ }}</td>
                                    <td class="">{{ $item['nama'] }}</td>
                                    <td class="text-center font-bold">{{ $item['nilai'] }}</td>
                                    <td class="">{{ terbilang($item['nilai']) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- Summary for Section A -->
                <div class="mt-2 border-x border-b border-black p-2 font-bold space-y-1 text-[11pt]">
                    <div class="flex">
                        <span class="w-48">Nilai Akhir ( Rata - rata )</span>
                        <span class="">: {{ $pengajuan->penilaianPkl ? number_format($pengajuan->penilaianPkl->nilai_akhir, 2) : '-' }} ({{ $pengajuan->penilaianPkl ? strtoupper(terbilang($pengajuan->penilaianPkl->nilai_akhir)) : '-' }})</span>
                    </div>
                    <div class="flex">
                        <span class="w-48">Kualifikasi Nilai</span>
                        <span class="">: {{ $pengajuan->penilaianPkl ? strtoupper($pengajuan->penilaianPkl->predikat) : '-' }}</span>
                    </div>
                </div>
            </section>
            
            <!-- Section B: Non Technical -->
            <section class="mb-8">
                <h3 class="font-bold mb-2 text-[12pt]">B. Penilaian Aspek Non Teknis</h3>
                <div class="grade-table-wrapper">
                    <table data-purpose="non-technical-assessment-table">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="w-12 text-center">No.</th>
                                <th class="text-left">Aspek Penilaian</th>
                                <th class="w-24 text-center">Nilai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $noNonTeknis = 1; @endphp
                            @foreach($aspekNonTeknis as $item)
                                <tr>
                                    <td class="text-center">{{ $noNonTeknis++ }}</td>
                                    <td class="">{{ $item['nama'] }}</td>
                                    <td class="text-center font-bold">{{ $item['nilai'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
            
            <!-- FooterInfo -->
            <div class="flex justify-between items-end mt-12">
                <!-- Grading Scale Legend -->
                <div class="text-[10pt]">
                    <h4 class="font-bold underline mb-2">Kualifikasi Nilai ( KN ) :</h4>
                    <div class="grid grid-cols-[100px_min-content_auto] gap-x-2 leading-tight">
                        <span class="">Sangat Baik</span><span class="">:</span><span class="">90 - 100</span>
                        <span class="">Baik</span><span class="">:</span><span class="">80 - 89</span>
                        <span class="">Cukup</span><span class="">:</span><span class="">70 - 79</span>
                        <span class="">Kurang</span><span class="">:</span><span class="">60 - 69</span>
                        <span class="">Sangat Kurang</span><span class="">:</span><span class="">0 - 59</span>
                    </div>
                </div>
                
                <!-- Signature Block -->
                <div class="text-center text-[10pt] flex flex-col items-center" style="width: 250px;">
                    <p class="mb-1">{{ \Carbon\Carbon::parse($pengajuan->tanggal_selesai)->translatedFormat('d F Y') }}</p>
                    <p class="mb-2">Pembimbing Industri</p>
                    
                    <!-- Signature Image Area -->
                    <div class="h-16 w-full flex items-center justify-center relative">
                        @if($pengajuan->tempatPkl?->pembimbingIndustri?->first()?->tanda_tangan)
                            <img src="{{ asset('storage/' . $pengajuan->tempatPkl->pembimbingIndustri->first()->tanda_tangan) }}" alt="Tanda Tangan" class="h-16 max-w-[150px] object-contain select-none z-10 absolute bottom-[-4px]">
                        @else
                            <!-- Signature Placeholder / Wave line fallback -->
                            <div class="absolute -top-12 left-1/2 -translate-x-1/2 opacity-60 italic text-blue-800 pointer-events-none">
                                <svg fill="none" height="40" viewBox="0 0 100 40" width="100" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M10 30C25 10 40 45 55 25C70 5 85 30 95 20" stroke="currentColor" stroke-linecap="round" stroke-width="2"></path>
                                </svg>
                            </div>
                        @endif
                    </div>
                    
                    <div class="signature-line w-48 mt-1 border-b-[1.5px] border-black"></div>
                    
                    <div class="relative inline-block w-full mt-1">
                        <p class="font-bold underline uppercase">
                            {{ strtoupper($pengajuan->tempatPkl?->pembimbingIndustri?->first()?->user?->name ?? 'Pembimbing') }}
                        </p>
                    </div>
                </div>
            </div>
            
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>

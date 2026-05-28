<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sertifikat PKL - {{ $pengajuan->siswa?->user?->name ?? 'Siswa' }}</title>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700;800&family=Great+Vibes&family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        @page {
            size: A4 landscape;
            margin: 0;
        }
        body {
            margin: 0;
            padding: 20px 0;
            background-color: #cbd5e1;
            font-family: 'Montserrat', sans-serif;
            -webkit-print-color-adjust: exact;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
        }
        .certificate-container {
            width: 297mm;
            height: 210mm;
            background-color: #ffffff;
            position: relative;
            overflow: hidden;
            box-shadow: 0 15px 45px rgba(15, 23, 42, 0.2);
            box-sizing: border-box;
            flex-shrink: 0;
        }
        
        .page-break {
            page-break-after: always;
            break-after: page;
        }
        
        /* Inner Pad */
        .certificate-content {
            position: absolute;
            top: 45px;
            left: 45px;
            right: 45px;
            bottom: 45px;
            display: flex;
            flex-direction: column;
            align-items: center;
            box-sizing: border-box;
            z-index: 10;
        }

        /* Top Brand */
        .school-brand {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 15px;
            text-align: center;
        }
        .school-brand h2 {
            font-family: 'Cinzel', serif;
            font-size: 16pt;
            font-weight: 800;
            color: #0f172a;
            margin: 0;
            letter-spacing: 3px;
        }
        .school-brand p {
            font-size: 8pt;
            font-weight: 600;
            color: #64748b;
            margin: 2px 0 0 0;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        /* Title Area */
        .cert-title {
            text-align: center;
            margin-top: 30px;
        }
        .cert-title h1 {
            font-family: 'Cinzel', serif;
            font-size: 36pt;
            font-weight: 700;
            color: #8c6f1c;
            background: linear-gradient(135deg, #c5a880 0%, #8c6f1c 50%, #b2902b 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin: 0;
            letter-spacing: 6px;
        }
        .cert-title p {
            font-size: 10pt;
            font-weight: 700;
            color: #1e293b;
            margin: 6px 0 0 0;
            text-transform: uppercase;
            letter-spacing: 4px;
        }

        .award-to {
            font-size: 11pt;
            font-weight: 500;
            color: #64748b;
            margin-top: 25px;
        }

        /* Student Name */
        .student-name {
            font-family: 'Great Vibes', cursive;
            font-size: 48pt;
            color: #0f172a;
            margin: 10px 0 5px 0;
            text-align: center;
            border-bottom: 1px solid #e2e8f0;
            padding: 0 50px;
        }

        .student-meta {
            font-size: 9.5pt;
            font-weight: 600;
            color: #475569;
            margin-bottom: 25px;
        }

        /* Description Page 1 */
        .cert-desc {
            font-size: 11pt;
            color: #334155;
            line-height: 1.7;
            text-align: center;
            max-width: 220mm;
            margin: 0 auto;
        }
        .cert-desc strong {
            color: #0f172a;
        }

        /* Grades Page 2 Title */
        .grades-title {
            font-family: 'Cinzel', serif;
            font-size: 20pt;
            font-weight: 700;
            color: #0f172a;
            margin-top: 15px;
            letter-spacing: 2px;
            text-align: center;
        }

        /* Table Area Page 2 */
        .page2-meta {
            display: grid;
            grid-template-columns: 1fr 1fr;
            width: 100%;
            padding: 0 50px;
            margin-top: 20px;
            margin-bottom: 20px;
            font-size: 9pt;
            color: #334155;
            line-height: 1.6;
        }
        .page2-meta table {
            width: 100%;
        }
        .page2-meta td {
            padding: 3px 0;
        }
        .page2-meta td.label {
            font-weight: 600;
            color: #64748b;
            width: 130px;
        }
        .page2-meta td.value {
            font-weight: 700;
            color: #0f172a;
        }

        .grades-container {
            display: flex;
            align-items: stretch;
            gap: 40px;
            width: 100%;
            padding: 0 50px;
            box-sizing: border-box;
        }

        .grade-table-wrapper {
            flex: 1;
        }
        .grade-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9pt;
            text-align: left;
        }
        .grade-table th {
            background-color: #0f172a;
            color: #ffffff;
            font-weight: 700;
            padding: 10px 14px;
            border: 1px solid #1e293b;
            text-transform: uppercase;
            font-size: 8.5pt;
        }
        .grade-table td {
            padding: 10px 14px;
            border: 1px solid #e2e8f0;
            color: #334155;
            font-weight: 600;
        }

        .grade-summary-card {
            width: 200px;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .grade-summary-card p {
            margin: 0;
        }
        .grade-summary-card .score-title {
            font-size: 9pt;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .grade-summary-card .score-val {
            font-size: 32pt;
            font-weight: 800;
            color: #8c6f1c;
            margin: 8px 0;
        }
        .grade-summary-card .score-predikat {
            font-size: 10pt;
            font-weight: 700;
            color: #0f172a;
            text-transform: uppercase;
        }

        /* Footer & Signatures */
        .signatures-area {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            width: 100%;
            margin-top: auto;
            padding: 0 50px 10px 50px;
            box-sizing: border-box;
        }
        .signature-box {
            text-align: center;
            width: 200px;
            font-size: 8.5pt;
            color: #475569;
        }
        .signature-line {
            width: 100%;
            height: 1px;
            background-color: #94a3b8;
            margin: 50px auto 8px auto;
        }
        .signature-box .signer-title {
            font-weight: 500;
            margin-bottom: 2px;
        }
        .signature-box .signer-name {
            font-weight: 700;
            color: #0f172a;
            font-size: 10pt;
        }

        /* QR Validation Code Box */
        .qr-validation {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
        }
        .qr-box {
            width: 60px;
            height: 60px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: 4px;
            background-color: white;
        }

        /* Control Box */
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
            .no-print { display: none; }
            body { 
                background-color: white; 
                margin: 0;
                padding: 0;
                gap: 0;
            }
            .certificate-container { 
                box-shadow: none; 
                margin: 0;
                transform: none;
            }
        }
    </style>
</head>
<body>
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

    <!-- PAGE 1: CERTIFICATE -->
    <div class="certificate-container page-break">
        <!-- SVG Guilloche & Golden Ornate Borders -->
        <svg class="absolute inset-0 w-full h-full pointer-events-none" style="position: absolute; top:0; left:0;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1122 793" fill="none">
            <defs>
                <pattern id="guilloche" width="30" height="30" patternUnits="userSpaceOnUse">
                    <path d="M0 15 Q7.5 7.5, 15 15 T30 15" stroke="#f8fafc" stroke-width="0.75" fill="none"/>
                    <path d="M15 0 Q22.5 7.5, 15 15 T15 30" stroke="#f8fafc" stroke-width="0.75" fill="none"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#guilloche)" />
            
            <rect x="20" y="20" width="1082" height="753" rx="14" stroke="#8c6f1c" stroke-width="4.5"/>
            <rect x="30" y="30" width="1062" height="733" rx="10" stroke="#c5a880" stroke-width="1.5"/>
            <rect x="36" y="36" width="1050" height="721" rx="8" stroke="#8c6f1c" stroke-width="1"/>

            <!-- Corners -->
            <path d="M 20,70 L 70,20 L 95,45 Z" fill="#c5a880" opacity="0.2"/>
            <path d="M 20,20 L 100,20 L 100,25 L 25,25 L 25,100 L 20,100 Z" fill="#0f172a"/>
            <circle cx="50" cy="50" r="4" fill="#8c6f1c"/>
            <path d="M 40,40 L 60,40 M 40,40 L 40,60" stroke="#c5a880" stroke-width="1.5"/>

            <path d="M 1102,70 L 1052,20 L 1027,45 Z" fill="#c5a880" opacity="0.2"/>
            <path d="M 1102,20 L 1022,20 L 1022,25 L 1097,25 L 1097,100 L 1102,100 Z" fill="#0f172a"/>
            <circle cx="1072" cy="50" r="4" fill="#8c6f1c"/>
            <path d="M 1082,40 L 1062,40 M 1082,40 L 1082,60" stroke="#c5a880" stroke-width="1.5"/>

            <path d="M 20,723 L 70,773 L 95,748 Z" fill="#c5a880" opacity="0.2"/>
            <path d="M 20,773 L 100,773 L 100,768 L 25,768 L 25,693 L 20,693 Z" fill="#0f172a"/>
            <circle cx="50" cy="743" r="4" fill="#8c6f1c"/>
            <path d="M 40,753 L 60,753 M 40,753 L 40,733" stroke="#c5a880" stroke-width="1.5"/>

            <path d="M 1102,723 L 1052,773 L 1027,748 Z" fill="#c5a880" opacity="0.2"/>
            <path d="M 1102,773 L 1022,773 L 1022,768 L 1097,768 L 1097,693 L 1102,693 Z" fill="#0f172a"/>
            <circle cx="1072" cy="743" r="4" fill="#8c6f1c"/>
            <path d="M 1082,753 L 1062,753 M 1082,753 L 1082,733" stroke="#c5a880" stroke-width="1.5"/>
        </svg>

        <div class="certificate-content">
            <div class="school-brand">
                <h2>SEKOLAH MENENGAH KEJURUAN</h2>
                <p>NPSN: 10403020 &bull; Terakreditasi A &bull; Program Keahlian Teknologi</p>
            </div>

            <div class="cert-title">
                <h1>SERTIFIKAT</h1>
                <p>Praktik Kerja Lapangan (PKL)</p>
            </div>

            <div class="award-to">Diberikan kepada:</div>
            
            <div class="student-name">{{ $pengajuan->siswa?->user?->name ?? 'Siswa' }}</div>
            
            <div class="student-meta">
                NIS: {{ $pengajuan->siswa?->nis ?? '-' }} &nbsp;&bull;&nbsp; Kelas: {{ $pengajuan->siswa?->kelas ?? '-' }} &nbsp;&bull;&nbsp; Jurusan: {{ $pengajuan->siswa?->jurusan ?? '-' }}
            </div>

            <div class="cert-desc">
                Telah berhasil dan sukses melaksanakan program <strong>Praktik Kerja Lapangan (PKL)</strong> sebagai bagian wajib dari kurikulum pendidikan sekolah kejuruan, yang bertempat pada mitra kerja industri <strong>{{ $pengajuan->tempatPkl?->nama_tempat ?? 'Industri Mitra' }}</strong> dari tanggal <strong>{{ \Carbon\Carbon::parse($pengajuan->tanggal_mulai)->format('d M Y') }}</strong> sampai dengan <strong>{{ \Carbon\Carbon::parse($pengajuan->tanggal_selesai)->format('d M Y') }}</strong> dengan predikat kelulusan <strong>{{ $pengajuan->penilaianPkl?->predikat ?? '-' }}</strong>.
            </div>

            <div class="signatures-area">
                <div class="signature-box">
                    <p class="signer-title">Pihak Mitra Kerja,</p>
                    <p style="font-weight: 700;">{{ $pengajuan->tempatPkl?->nama_tempat ?? 'Industri Mitra' }}</p>
                    <div class="signature-line"></div>
                    <p class="signer-name">Pembimbing Industri</p>
                    <p>Tanda Tangan & Stempel</p>
                </div>

                <div class="qr-validation">
                    <div class="qr-box">
                        <svg viewBox="0 0 100 100" style="width: 100%; height: 100%;" xmlns="http://www.w3.org/2000/svg">
                            <rect width="100" height="100" fill="white"/>
                            <rect x="5" y="5" width="25" height="25" fill="#0f172a"/>
                            <rect x="10" y="10" width="15" height="15" fill="white"/>
                            <rect x="13" y="13" width="9" height="9" fill="#0f172a"/>
                            <rect x="70" y="5" width="25" height="25" fill="#0f172a"/>
                            <rect x="75" y="10" width="15" height="15" fill="white"/>
                            <rect x="78" y="13" width="9" height="9" fill="#0f172a"/>
                            <rect x="5" y="70" width="25" height="25" fill="#0f172a"/>
                            <rect x="10" y="75" width="15" height="15" fill="white"/>
                            <rect x="13" y="78" width="9" height="9" fill="#0f172a"/>
                            <rect x="35" y="5" width="5" height="10" fill="#0f172a"/>
                            <rect x="45" y="15" width="10" height="5" fill="#0f172a"/>
                            <rect x="60" y="5" width="5" height="25" fill="#0f172a"/>
                            <rect x="35" y="25" width="15" height="5" fill="#0f172a"/>
                            <rect x="50" y="35" width="20" height="5" fill="#0f172a"/>
                            <rect x="35" y="45" width="5" height="15" fill="#0f172a"/>
                            <rect x="45" y="55" width="15" height="5" fill="#0f172a"/>
                            <rect x="5" y="35" width="10" height="5" fill="#0f172a"/>
                            <rect x="20" y="45" width="5" height="15" fill="#0f172a"/>
                            <rect x="75" y="35" width="10" height="15" fill="#0f172a"/>
                            <rect x="90" y="55" width="5" height="15" fill="#0f172a"/>
                            <rect x="70" y="70" width="15" height="5" fill="#0f172a"/>
                            <rect x="60" y="80" width="5" height="15" fill="#0f172a"/>
                            <rect x="80" y="85" width="15" height="5" fill="#0f172a"/>
                        </svg>
                    </div>
                    <p style="font-size: 6.5pt; font-weight: 700; color: #94a3b8; text-transform: uppercase; margin: 0; letter-spacing: 0.5px;">Digitally Verified</p>
                </div>

                <div class="signature-box">
                    <p class="signer-title">Jawa Barat, {{ \Carbon\Carbon::parse($pengajuan->tanggal_selesai)->format('d F Y') }}</p>
                    <p style="font-weight: 700;">Guru Pembimbing Sekolah</p>
                    <div class="signature-line"></div>
                    <p class="signer-name">{{ $pengajuan->guru?->user?->name ?? 'Nama Guru' }}</p>
                    <p>NIP. {{ $pengajuan->guru?->nip ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- PAGE 2: TRANSCRIPT -->
    <div class="certificate-container">
        <!-- SVG Guilloche & Golden Ornate Borders -->
        <svg class="absolute inset-0 w-full h-full pointer-events-none" style="position: absolute; top:0; left:0;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1122 793" fill="none">
            <defs>
                <pattern id="guilloche2" width="30" height="30" patternUnits="userSpaceOnUse">
                    <path d="M0 15 Q7.5 7.5, 15 15 T30 15" stroke="#f8fafc" stroke-width="0.75" fill="none"/>
                    <path d="M15 0 Q22.5 7.5, 15 15 T15 30" stroke="#f8fafc" stroke-width="0.75" fill="none"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#guilloche2)" />
            
            <rect x="20" y="20" width="1082" height="753" rx="14" stroke="#8c6f1c" stroke-width="4.5"/>
            <rect x="30" y="30" width="1062" height="733" rx="10" stroke="#c5a880" stroke-width="1.5"/>
            <rect x="36" y="36" width="1050" height="721" rx="8" stroke="#8c6f1c" stroke-width="1"/>

            <!-- Corners -->
            <path d="M 20,70 L 70,20 L 95,45 Z" fill="#c5a880" opacity="0.2"/>
            <path d="M 20,20 L 100,20 L 100,25 L 25,25 L 25,100 L 20,100 Z" fill="#0f172a"/>
            <circle cx="50" cy="50" r="4" fill="#8c6f1c"/>
            <path d="M 40,40 L 60,40 M 40,40 L 40,60" stroke="#c5a880" stroke-width="1.5"/>

            <path d="M 1102,70 L 1052,20 L 1027,45 Z" fill="#c5a880" opacity="0.2"/>
            <path d="M 1102,20 L 1022,20 L 1022,25 L 1097,25 L 1097,100 L 1102,100 Z" fill="#0f172a"/>
            <circle cx="1072" cy="50" r="4" fill="#8c6f1c"/>
            <path d="M 1082,40 L 1062,40 M 1082,40 L 1082,60" stroke="#c5a880" stroke-width="1.5"/>

            <path d="M 20,723 L 70,773 L 95,748 Z" fill="#c5a880" opacity="0.2"/>
            <path d="M 20,773 L 100,773 L 100,768 L 25,768 L 25,693 L 20,693 Z" fill="#0f172a"/>
            <circle cx="50" cy="743" r="4" fill="#8c6f1c"/>
            <path d="M 40,753 L 60,753 M 40,753 L 40,733" stroke="#c5a880" stroke-width="1.5"/>

            <path d="M 1102,723 L 1052,773 L 1027,748 Z" fill="#c5a880" opacity="0.2"/>
            <path d="M 1102,773 L 1022,773 L 1022,768 L 1097,768 L 1097,693 L 1102,693 Z" fill="#0f172a"/>
            <circle cx="1072" cy="743" r="4" fill="#8c6f1c"/>
            <path d="M 1082,753 L 1062,753 M 1082,753 L 1082,733" stroke="#c5a880" stroke-width="1.5"/>
        </svg>

        <div class="certificate-content">
            <div class="school-brand">
                <h2>SEKOLAH MENENGAH KEJURUAN SPARTA</h2>
                <p>NPSN: 10403020 &bull; Terakreditasi A &bull; Program Keahlian Teknologi</p>
            </div>

            <div class="grades-title">
                DAFTAR NILAI PRAKTIK KERJA LAPANGAN
            </div>

            <!-- Meta Data Grid -->
            <div class="page2-meta">
                <table>
                    <tr>
                        <td class="label">Nama Siswa</td>
                        <td style="width:10px;">:</td>
                        <td class="value">{{ $pengajuan->siswa?->user?->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Nomor Induk (NIS)</td>
                        <td>:</td>
                        <td class="value">{{ $pengajuan->siswa?->nis ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Kompetensi Keahlian</td>
                        <td>:</td>
                        <td class="value">{{ $pengajuan->siswa?->jurusan ?? '-' }}</td>
                    </tr>
                </table>
                <table>
                    <tr>
                        <td class="label">Tempat PKL</td>
                        <td style="width:10px;">:</td>
                        <td class="value">{{ $pengajuan->tempatPkl?->nama_tempat ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Alamat Mitra</td>
                        <td>:</td>
                        <td class="value">{{ $pengajuan->tempatPkl?->alamat ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">Durasi Pelaksanaan</td>
                        <td>:</td>
                        <td class="value">{{ \Carbon\Carbon::parse($pengajuan->tanggal_mulai)->format('d M Y') }} - {{ \Carbon\Carbon::parse($pengajuan->tanggal_selesai)->format('d M Y') }}</td>
                    </tr>
                </table>
            </div>

            <!-- Grades & Info Row -->
            <div class="grades-container">
                <div class="grade-table-wrapper">
                    <table class="grade-table">
                        <thead>
                            <tr>
                                <th style="width: 10%; text-align: center;">No.</th>
                                <th style="width: 60%">Komponen Penilaian</th>
                                <th style="width: 30%; text-align: center;">Nilai (Skala 1-100)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="text-align: center;">1.</td>
                                <td>Aspek Sikap dan Kedisiplinan Kerja</td>
                                <td style="text-align: center; font-weight: 700; font-size: 11pt;">{{ $pengajuan->penilaianPkl?->nilai_sikap ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td style="text-align: center;">2.</td>
                                <td>Aspek Keterampilan Teknik & Kerja Lapangan</td>
                                <td style="text-align: center; font-weight: 700; font-size: 11pt;">{{ $pengajuan->penilaianPkl?->nilai_keterampilan ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td style="text-align: center;">3.</td>
                                <td>Kualitas Penulisan & Pemaparan Laporan Akhir</td>
                                <td style="text-align: center; font-weight: 700; font-size: 11pt;">{{ $pengajuan->penilaianPkl?->nilai_laporan ?? '-' }}</td>
                            </tr>
                            <tr style="background-color: #f8fafc;">
                                <td colspan="2" style="text-align: right; font-weight: 700;">Rata-rata Nilai</td>
                                <td style="text-align: center; font-weight: 800; font-size: 11pt; color: #8c6f1c;">{{ $pengajuan->penilaianPkl?->nilai_akhir ?? '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="grade-summary-card">
                    <p class="score-title">Predikat Akhir</p>
                    <p class="score-val" style="font-size: 20pt; margin: 15px 0;">{{ $pengajuan->penilaianPkl?->predikat ?? '-' }}</p>
                    <p class="score-predikat" style="font-size: 7.5pt; color: #64748b;">SMK SPARTA OFFICIAL GRADE</p>
                </div>
            </div>

            <!-- Footnotes & Signatures -->
            <div class="signatures-area" style="margin-top: 15px;">
                <div class="signature-box">
                    <p class="signer-title">Pembimbing Industri,</p>
                    <div class="signature-line" style="margin-top: 40px;"></div>
                    <p class="signer-name">Pihak Industri</p>
                    <p>Tanda Tangan & Stempel</p>
                </div>

                <div class="signature-box">
                    <p class="signer-title">Jawa Barat, {{ \Carbon\Carbon::parse($pengajuan->tanggal_selesai)->format('d F Y') }}</p>
                    <p class="signer-title">Guru Pembimbing,</p>
                    <div class="signature-line" style="margin-top: 40px;"></div>
                    <p class="signer-name">{{ $pengajuan->guru?->user?->name ?? 'Guru Pembimbing' }}</p>
                    <p>NIP. {{ $pengajuan->guru?->nip ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>

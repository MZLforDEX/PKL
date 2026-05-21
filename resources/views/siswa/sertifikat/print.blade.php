<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sertifikat PKL - {{ $pengajuan->siswa->user->name }}</title>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@700&family=Great+Vibes&family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        @page {
            size: A4 landscape;
            margin: 0;
        }
        body {
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
            font-family: 'Montserrat', sans-serif;
            -webkit-print-color-adjust: exact;
        }
        .certificate-container {
            width: 297mm;
            height: 210mm;
            background-color: white;
            position: relative;
            margin: 0 auto;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        /* Border and Background */
        .certificate-border {
            position: absolute;
            top: 10mm;
            left: 10mm;
            right: 10mm;
            bottom: 10mm;
            border: 5mm double #1e293b;
            padding: 5mm;
            display: flex;
            flex-direction: column;
            align-items: center;
            background-image: 
                radial-gradient(circle at 0 0, #f8fafc 0%, transparent 20%),
                radial-gradient(circle at 100% 100%, #f1f5f9 0%, transparent 20%);
        }
        .certificate-inner-border {
            border: 1mm solid #94a3b8;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20mm;
            box-sizing: border-box;
            position: relative;
        }
        /* Decorations */
        .corner-decoration {
            position: absolute;
            width: 40mm;
            height: 40mm;
            border: 2mm solid #1e293b;
            z-index: 5;
        }
        .top-left { top: 0; left: 0; border-right: none; border-bottom: none; }
        .top-right { top: 0; right: 0; border-left: none; border-bottom: none; }
        .bottom-left { bottom: 0; left: 0; border-right: none; border-top: none; }
        .bottom-right { bottom: 0; right: 0; border-left: none; border-top: none; }

        /* Content */
        .header {
            text-align: center;
            margin-bottom: 10mm;
        }
        .header h1 {
            font-family: 'Cinzel', serif;
            font-size: 42pt;
            color: #1e293b;
            margin: 0;
            letter-spacing: 5mm;
        }
        .header h2 {
            font-size: 18pt;
            color: #64748b;
            margin: 5mm 0;
            text-transform: uppercase;
            letter-spacing: 2mm;
        }
        .presentation {
            font-size: 14pt;
            color: #334155;
            margin: 5mm 0;
        }
        .name {
            font-family: 'Great Vibes', cursive;
            font-size: 56pt;
            color: #4f46e5;
            margin: 5mm 0;
            border-bottom: 0.5mm solid #e2e8f0;
            padding: 0 20mm;
        }
        .description {
            font-size: 12pt;
            color: #475569;
            line-height: 1.6;
            text-align: center;
            max-width: 200mm;
            margin: 5mm auto;
        }
        .details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 10mm;
            margin: 10mm 0;
            width: 100%;
            text-align: center;
        }
        .detail-item p:first-child {
            font-size: 10pt;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            margin-bottom: 2mm;
        }
        .detail-item p:last-child {
            font-size: 14pt;
            font-weight: 600;
            color: #1e293b;
            margin: 0;
        }
        .grade-badge {
            background-color: #f1f5f9;
            padding: 4mm 10mm;
            border-radius: 4mm;
            border: 0.5mm solid #e2e8f0;
            display: inline-block;
            margin-top: 5mm;
        }
        .grade-badge span {
            font-size: 24pt;
            font-weight: 800;
            color: #4f46e5;
        }

        /* Signatures */
        .signatures {
            display: flex;
            justify-content: space-between;
            width: 100%;
            margin-top: auto;
            padding: 0 20mm;
        }
        .signature-box {
            text-align: center;
            width: 60mm;
        }
        .signature-line {
            border-top: 0.5mm solid #1e293b;
            margin-top: 20mm;
            padding-top: 2mm;
        }
        .signature-box p {
            margin: 0;
            font-size: 10pt;
            color: #475569;
        }
        .signature-box .signer-name {
            font-weight: 700;
            color: #1e293b;
            text-transform: uppercase;
        }

        /* Seal */
        .certificate-seal {
            position: absolute;
            bottom: 25mm;
            left: 50%;
            transform: translateX(-50%);
            width: 40mm;
            height: 40mm;
            background: radial-gradient(circle, #fbbf24 0%, #d97706 100%);
            border-radius: 50%;
            display: flex;
            items-center;
            justify-content: center;
            box-shadow: 0 4mm 10mm rgba(217, 119, 6, 0.3);
            border: 2mm dashed rgba(255,255,255,0.3);
            color: white;
            z-index: 10;
        }

        /* Utility */
        .no-print {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 100;
        }
        .btn-print {
            padding: 12px 24px;
            background-color: #4f46e5;
            color: white;
            border: none;
            border-radius: 12px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.4);
            display: flex;
            items-center;
            gap: 8px;
        }
        .btn-print:hover { background-color: #4338ca; }

        @media print {
            .no-print { display: none; }
            body { background-color: white; }
            .certificate-container { box-shadow: none; margin: 0; }
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()" class="btn-print">
            <i data-lucide="printer" style="width: 18px; height: 18px;"></i>
            Cetak Sertifikat
        </button>
    </div>

    <div class="certificate-container">
        <div class="certificate-border">
            <div class="certificate-inner-border">
                <div class="corner-decoration top-left"></div>
                <div class="corner-decoration top-right"></div>
                <div class="corner-decoration bottom-left"></div>
                <div class="corner-decoration bottom-right"></div>

                <div class="header">
                    <h1>SERTIFIKAT</h1>
                    <h2>Praktek Kerja Lapangan (PKL)</h2>
                </div>

                <p class="presentation">Diberikan kepada:</p>
                
                <div class="name">{{ $pengajuan->siswa->user->name }}</div>
                
                <p class="description">
                    Atas keberhasilannya menyelesaikan program Praktek Kerja Lapangan (PKL) yang diselenggarakan sesuai dengan kurikulum Sekolah Menengah Kejuruan (SMK), dilaksanakan pada:
                </p>

                <div class="details-grid">
                    <div class="detail-item">
                        <p>Tempat PKL</p>
                        <p>{{ $pengajuan->tempatPkl->nama_tempat }}</p>
                    </div>
                    <div class="detail-item">
                        <p>Durasi</p>
                        <p>{{ \Carbon\Carbon::parse($pengajuan->tanggal_mulai)->format('d M Y') }} - {{ \Carbon\Carbon::parse($pengajuan->tanggal_selesai)->format('d M Y') }}</p>
                    </div>
                    <div class="detail-item">
                        <p>Predikat</p>
                        <p>
                            @php
                                $nilai = $pengajuan->penilaianPkl->nilai_akhir;
                                if ($nilai >= 90)
                                    $predikat = 'Sangat Baik';
                                elseif ($nilai >= 80)
                                    $predikat = 'Baik';
                                elseif ($nilai >= 70)
                                    $predikat = 'Cukup';
                                else
                                    $predikat = 'Kurang';
                            @endphp
                            {{ $predikat }}
                        </p>
                    </div>
                </div>

                <div class="grade-badge">
                    <span>NILAI AKHIR: {{ $pengajuan->penilaianPkl->nilai_akhir }}</span>
                </div>

                <div class="certificate-seal">
                    <div style="text-align: center; transform: rotate(-15deg);">
                        <p style="margin: 0; font-size: 8pt; font-weight: 800;">OFFICIAL</p>
                        <p style="margin: 0; font-size: 12pt; font-weight: 900;">SMK</p>
                        <p style="margin: 0; font-size: 8pt; font-weight: 800;">VALIDATED</p>
                    </div>
                </div>

                <div class="signatures">
                    <div class="signature-box">
                        <p>Pembimbing Industri,</p>
                        <div class="signature-line">
                            <p class="signer-name">Pihak Industri</p>
                            <p>Stempel & Tanda Tangan</p>
                        </div>
                    </div>
                    <div class="signature-box">
                        <p>Jawa Barat, {{ now()->format('d F Y') }}</p>
                        <p>Guru Pembimbing,</p>
                        <div class="signature-line">
                            <p class="signer-name">{{ $pengajuan->guru->user->name }}</p>
                            <p>NIP: {{ $pengajuan->guru->nip }}</p>
                        </div>
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

<html xmlns:o="urn:schemas-microsoft-com:office:office"
      xmlns:x="urn:schemas-microsoft-com:office:excel"
      xmlns="http://www.w3.org/TR/REC-html40">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        table {
            border-collapse: collapse;
            font-family: 'Arial', sans-serif;
            font-size: 11px;
        }
        th {
            border: 1px solid #000000;
            background-color: #f3f4f6;
            font-weight: bold;
            text-align: center;
            vertical-align: middle;
        }
        td {
            border: 1px solid #000000;
            text-align: center;
            vertical-align: middle;
        }
        .header-title {
            font-size: 14px;
            font-weight: bold;
            text-align: left;
        }
        .header-meta {
            font-size: 11px;
            text-align: left;
        }
        .weekend {
            background-color: #ffff00;
            font-weight: bold;
        }
        .terlambat {
            background-color: #fef08a; /* Soft Yellow */
            font-weight: bold;
        }
        .alfa {
            background-color: #fee2e2; /* Soft Red */
            color: #b91c1c;
            font-weight: bold;
        }
        .hadir {
            font-weight: bold;
        }
        .summary-col {
            background-color: #e5e7eb;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <table>
        <!-- Title and meta info -->
        <tr>
            <td colspan="{{ $daysInMonth + 6 }}" class="header-title" style="border: none; font-size: 16px;">
                LAPORAN HARIAN ABSENSI SISWA PKL
            </td>
        </tr>
        <tr>
            <td colspan="{{ $daysInMonth + 6 }}" class="header-meta" style="border: none;">
                Periode PKL: {{ $periodeName }}
            </td>
        </tr>
        <tr>
            <td colspan="{{ $daysInMonth + 6 }}" class="header-meta" style="border: none;">
                Bulan: {{ $bulanNama }} {{ $tahun }}
            </td>
        </tr>
        <tr>
            <td colspan="{{ $daysInMonth + 6 }}" class="header-meta" style="border: none; height: 10px;"></td>
        </tr>

        <!-- Main Table Headers -->
        <thead>
            <tr>
                <th rowspan="2" style="width: 250px;">Nama</th>
                <th rowspan="2" style="width: 120px;">No. ID (NIS)</th>
                <th colspan="{{ $daysInMonth }}">Tanggal</th>
                <th colspan="4" class="summary-col">Rekapitulasi</th>
            </tr>
            <tr>
                @for($d = 1; $d <= $daysInMonth; $d++)
                    @php
                        $date = \Carbon\Carbon::createFromDate($tahun, $bulan, $d);
                        $dayOfWeek = $date->dayOfWeek;
                        $dayName = $daysMap[$dayOfWeek];
                    @endphp
                    <th style="width: 35px; @if($date->isWeekend()) background-color: #ffff00; @endif">
                        {{ $d }}<br/>{{ $dayName }}
                    </th>
                @endfor
                <th class="summary-col" style="width: 40px;">H</th>
                <th class="summary-col" style="width: 40px;">T</th>
                <th class="summary-col" style="width: 40px;">A</th>
                <th class="summary-col" style="width: 50px;">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $student)
                @php
                    $countHadir = 0;
                    $countTerlambat = 0;
                    $countAlfa = 0;
                    $today = \Carbon\Carbon::today();
                @endphp
                <tr>
                    <td style="text-align: left; padding: 5px;">{{ $student['nama'] }}</td>
                    <td style="font-family: monospace;">{{ $student['nis'] }}</td>
                    @for($d = 1; $d <= $daysInMonth; $d++)
                        @php
                            $date = \Carbon\Carbon::createFromDate($tahun, $bulan, $d);
                            $isWeekend = $date->isWeekend();
                            
                            $record = $student['absensi']->get($d);
                            
                            $tanggalMulai = $student['tanggal_mulai'] ? \Carbon\Carbon::parse($student['tanggal_mulai'])->startOfDay() : null;
                            $tanggalSelesai = $student['tanggal_selesai'] ? \Carbon\Carbon::parse($student['tanggal_selesai'])->endOfDay() : null;
                            
                            $isWithinPklRange = true;
                            if ($tanggalMulai && $date->lessThan($tanggalMulai)) {
                                $isWithinPklRange = false;
                            }
                            if ($tanggalSelesai && $date->greaterThan($tanggalSelesai)) {
                                $isWithinPklRange = false;
                            }
                        @endphp
                        
                        @if(!$isWithinPklRange)
                            <td></td>
                        @elseif($isWeekend)
                            <td class="weekend">L</td>
                        @elseif($record)
                            @if($record->status === 'hadir')
                                @php $countHadir++; @endphp
                                <td class="hadir">H</td>
                            @elseif($record->status === 'terlambat')
                                @php $countTerlambat++; @endphp
                                <td class="terlambat">T</td>
                            @else
                                <td>-</td>
                            @endif
                        @else
                            @if($date->lessThanOrEqualTo($today))
                                @php $countAlfa++; @endphp
                                <td class="alfa">A</td>
                            @else
                                <td></td>
                            @endif
                        @endif
                    @endfor
                    <td class="summary-col">{{ $countHadir }}</td>
                    <td class="summary-col">{{ $countTerlambat }}</td>
                    <td class="summary-col">{{ $countAlfa }}</td>
                    <td class="summary-col">{{ $countHadir + $countTerlambat }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ $daysInMonth + 6 }}">Belum ada data siswa bimbingan/magang.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>

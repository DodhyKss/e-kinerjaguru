<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekapitulasi Kinerja Guru - {{ $selectedSchool->nama ?? 'Semua Sekolah' }}</title>
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            color: #000;
            line-height: 1.5;
            margin: 0;
            padding: 0;
            font-size: 11pt;
        }
        .container {
            width: 100%;
            max-width: 297mm; /* A4 Landscape */
            margin: 0 auto;
            padding: 20mm;
            box-sizing: border-box;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
        }
        .header h1 {
            font-size: 14pt;
            margin: 0;
            text-transform: uppercase;
        }
        .header h2 {
            font-size: 12pt;
            margin: 5px 0 0 0;
        }
        .info {
            margin-bottom: 15px;
        }
        .info table {
            border: none;
        }
        .info td {
            padding: 2px 5px;
            vertical-align: top;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .data-table th, .data-table td {
            border: 1px solid #000;
            padding: 6px;
            vertical-align: middle;
        }
        .data-table th {
            background-color: #f2f2f2;
            text-align: center;
            font-weight: bold;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .signature-table {
            width: 100%;
            margin-top: 40px;
            border-collapse: collapse;
        }
        .signature-table td {
            width: 50%;
            text-align: center;
            vertical-align: top;
        }
        .signature-space {
            height: 80px;
        }
        .btn-print { display: none; }
        @media screen {
            body { background-color: #f0f0f0; }
            .container { background-color: white; margin-top: 20px; margin-bottom: 20px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
            .btn-print {
                display: block;
                margin: 20px auto;
                padding: 10px 20px;
                background-color: #4F46E5;
                color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                font-size: 14px;
                font-weight: bold;
            }
        }
        @media print {
            @page { size: landscape; margin: 15mm; }
            body { font-size: 10pt; background-color: white; }
            .container { width: 100%; max-width: 100%; margin: 0; padding: 0; box-shadow: none; }
            .btn-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">
    <button class="btn-print" onclick="window.print()">Cetak Dokumen</button>
    <div class="container">
        <div class="header">
            <h1>REKAPITULASI HASIL EVALUASI KINERJA GURU</h1>
            <h2>{{ $selectedSchool->nama ?? 'Semua Sekolah' }}</h2>
        </div>

        <div class="info">
            <table>
                <tr>
                    <td style="width: 120px;"><strong>Periode Evaluasi</strong></td>
                    <td style="width: 10px;">:</td>
                    <td>{{ $selectedPeriod->nama ?? '-' }}</td>
                </tr>
                <tr>
                    <td><strong>Unit Kerja</strong></td>
                    <td>:</td>
                    <td>{{ $selectedSchool->nama ?? 'Semua' }}</td>
                </tr>
                <tr>
                    <td><strong>Total Guru</strong></td>
                    <td>:</td>
                    <td>{{ $gurus->count() }} Orang</td>
                </tr>
            </table>
        </div>

        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 25%;">Nama Guru / NIP</th>
                    <th style="width: 20%;">Mata Pelajaran</th>
                    <th style="width: 15%;">Status Evaluasi</th>
                    <th style="width: 20%;">Asesor / Penilai</th>
                    <th style="width: 7%;">Total Skor</th>
                    <th style="width: 8%;">Nilai Akhir</th>
                </tr>
            </thead>
            <tbody>
                @forelse($gurus as $index => $guru)
                    @php
                        $eval = $guru->evaluations->first();
                    @endphp
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>
                            <strong>{{ $guru->nama }}</strong><br>
                            <span style="font-size: 9pt;">NIP: {{ $guru->nip ?? '-' }}</span>
                        </td>
                        <td>{{ $guru->mata_pelajaran }}</td>
                        <td class="text-center">
                            @if($eval)
                                @if($eval->status == 'approved') Disetujui
                                @elseif($eval->status == 'completed') Selesai
                                @elseif($eval->status == 'in_progress') Proses
                                @else Draft
                                @endif
                            @else
                                Belum Dimulai
                            @endif
                        </td>
                        <td>{{ $eval->penilai->nama ?? '-' }}</td>
                        <td class="text-center">{{ $eval->total_skor ?? '-' }}</td>
                        <td class="text-center">
                            @if($eval && $eval->rata_rata)
                                <strong>{{ number_format($eval->rata_rata, 2) }}</strong>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Tidak ada data guru.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <table class="signature-table" style="page-break-inside: avoid;">
            <tr>
                <td></td>
                <td>
                    {{ $selectedSchool->kabupaten->nama ?? '..........................' }}, {{ date('d F Y') }}<br>
                    Kepala Sekolah<br>
                    <div class="signature-space"></div>
                    <b><u>.......................................</u></b><br>
                    NIP. ....................................
                </td>
            </tr>
        </table>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Evaluasi Kinerja - {{ $evaluation->guru->nama }}</title>
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            color: #000;
            line-height: 1.5;
            margin: 0;
            padding: 0;
            font-size: 12pt;
        }
        .container {
            width: 100%;
            max-width: 210mm;
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
            font-size: 16pt;
            margin: 0;
            text-transform: uppercase;
        }
        .header h2 {
            font-size: 14pt;
            margin: 5px 0 0 0;
        }
        .info-table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        .info-table td {
            padding: 4px;
            vertical-align: top;
        }
        .info-table .label {
            width: 30%;
            font-weight: bold;
        }
        .info-table .colon {
            width: 2%;
        }
        .info-table .value {
            width: 68%;
        }
        .section-title {
            font-weight: bold;
            font-size: 13pt;
            margin-top: 20px;
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .data-table th, .data-table td {
            border: 1px solid #000;
            padding: 8px;
            vertical-align: top;
        }
        .data-table th {
            background-color: #f2f2f2;
            text-align: center;
            font-weight: bold;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .signature-table {
            width: 100%;
            margin-top: 40px;
            border-collapse: collapse;
        }
        .signature-table td {
            width: 33.33%;
            text-align: center;
            vertical-align: top;
        }
        .signature-space {
            height: 80px;
        }
        .summary-box {
            border: 1px solid #000;
            padding: 15px;
            margin-bottom: 20px;
            background-color: #f9f9f9;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .summary-box h3 {
            margin-top: 0;
            font-size: 14pt;
        }
        .btn-print {
            display: none;
        }
        @media screen {
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
                text-align: center;
            }
            .btn-print:hover {
                background-color: #4338CA;
            }
        }
        @media print {
            body {
                font-size: 12pt;
                background-color: white;
            }
            .container {
                width: 100%;
                max-width: 100%;
                margin: 0;
                padding: 0;
            }
            .page-break {
                page-break-before: always;
            }
            .btn-print {
                display: none;
            }
        }
    </style>
</head>
<body onload="window.print()">
    <button class="btn-print" onclick="window.print()">Cetak Laporan</button>
    <div class="container">
        <div class="header">
            <h1>LAPORAN HASIL EVALUASI KINERJA GURU</h1>
            <h2>PERIODE: {{ strtoupper($evaluation->evaluationPeriod->nama) }}</h2>
        </div>

        <table class="info-table">
            <tr>
                <td colspan="3"><div class="section-title">I. IDENTITAS GURU YANG DINILAI</div></td>
            </tr>
            <tr>
                <td class="label">Nama</td>
                <td class="colon">:</td>
                <td class="value">{{ $evaluation->guru->nama }}</td>
            </tr>
            <tr>
                <td class="label">NIP</td>
                <td class="colon">:</td>
                <td class="value">{{ $evaluation->guru->nip ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Mata Pelajaran</td>
                <td class="colon">:</td>
                <td class="value">{{ $evaluation->guru->mata_pelajaran }}</td>
            </tr>
            <tr>
                <td class="label">Unit Kerja (Sekolah)</td>
                <td class="colon">:</td>
                <td class="value">{{ $evaluation->guru->school->nama ?? '-' }}</td>
            </tr>
            
            <tr>
                <td colspan="3"><div class="section-title" style="margin-top: 15px;">II. IDENTITAS PENILAI</div></td>
            </tr>
            <tr>
                <td class="label">Nama Penilai</td>
                <td class="colon">:</td>
                <td class="value">{{ $evaluation->penilai->nama }}</td>
            </tr>
            <tr>
                <td class="label">NIP</td>
                <td class="colon">:</td>
                <td class="value">{{ $evaluation->penilai->nip ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Jabatan</td>
                <td class="colon">:</td>
                <td class="value">{{ $evaluation->penilai->jabatan }}</td>
            </tr>
        </table>

        <div class="summary-box">
            <h3 style="text-align:center;">REKAPITULASI HASIL PENILAIAN</h3>
            <table class="info-table" style="margin-bottom:0;">
                <tr>
                    <td class="label">Total Skor</td>
                    <td class="colon">:</td>
                    <td class="value"><b>{{ $evaluation->total_skor ?? '0' }}</b></td>
                </tr>
                <tr>
                    <td class="label">Rata-rata / Nilai Akhir</td>
                    <td class="colon">:</td>
                    <td class="value"><b>{{ $evaluation->rata_rata ?? '0' }} / 4.0</b></td>
                </tr>
                <tr>
                    <td class="label">Status Evaluasi</td>
                    <td class="colon">:</td>
                    <td class="value">
                        @if($evaluation->status == 'approved')
                            Disetujui Kepala Sekolah
                        @elseif($evaluation->status == 'completed')
                            Selesai (Menunggu Review Kepsek)
                        @else
                            Sedang Diproses
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="label">Rekomendasi Lanjutan</td>
                    <td class="colon">:</td>
                    <td class="value">
                        @if($evaluation->rekomendasi)
                            {{ $evaluation->rekomendasi->rekomendasi }}
                        @else
                            <i>Belum ada rekomendasi</i>
                        @endif
                    </td>
                </tr>
            </table>
        </div>

        <div class="page-break"></div>

        <div class="section-title">III. RINCIAN HASIL PENILAIAN</div>
        
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 25%;">Dimensi / Indikator</th>
                    <th style="width: 55%;">Deskripsi & Kesimpulan</th>
                    <th style="width: 15%;">Nilai (Level)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dimensions as $dim)
                    <tr style="background-color: #e9e9e9; font-weight: bold; -webkit-print-color-adjust: exact; print-color-adjust: exact;">
                        <td style="text-align: center;">{{ $dim->urutan }}</td>
                        <td colspan="3">{{ $dim->nama }}</td>
                    </tr>
                    @foreach($dim->indicators as $ind)
                        @php
                            $result = $resultsMap->get($ind->id);
                        @endphp
                        <tr>
                            <td style="text-align: center;">{{ $ind->kode }}</td>
                            <td>{{ $ind->nama }}</td>
                            <td>
                                <i>Deskripsi:</i><br>
                                {{ $ind->deskripsi }}
                                <div style="margin-top: 10px;">
                                    <i>Kesimpulan Penilai:</i><br>
                                    @if($result && $result->status == 'selesai')
                                        {{ $result->kesimpulan }}
                                    @else
                                        <span style="color: #666;">(Belum dinilai)</span>
                                    @endif
                                </div>
                            </td>
                            <td style="text-align: center; vertical-align: middle; font-size: 14pt; font-weight: bold;">
                                @if($result && $result->status == 'selesai')
                                    {{ $result->level_capaian }}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>

        @if($evaluation->catatan_kepala_sekolah)
        <div style="margin-top: 20px; border: 1px dashed #000; padding: 15px;">
            <b>Catatan / Feedback Kepala Sekolah:</b><br>
            {{ $evaluation->catatan_kepala_sekolah }}
        </div>
        @endif

        <table class="signature-table" style="page-break-inside: avoid;">
            <tr>
                <td>
                    Guru yang Dinilai,<br>
                    <div class="signature-space"></div>
                    <b><u>{{ $evaluation->guru->nama }}</u></b><br>
                    NIP. {{ $evaluation->guru->nip ?? '-' }}
                </td>
                <td>
                    Mengetahui,<br>
                    Kepala Sekolah<br>
                    <div class="signature-space"></div>
                    <b><u>.......................................</u></b><br>
                    NIP. ....................................
                </td>
                <td>
                    Penilai / Asesor,<br>
                    <div class="signature-space"></div>
                    <b><u>{{ $evaluation->penilai->nama }}</u></b><br>
                    NIP. {{ $evaluation->penilai->nip ?? '-' }}
                </td>
            </tr>
        </table>
    </div>
</body>
</html>

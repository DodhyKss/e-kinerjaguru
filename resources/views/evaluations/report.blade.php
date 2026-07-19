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
        .data-table th, table th {
            background-color: #f2f2f2;
            text-align: center !important;
            vertical-align: middle !important;
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
            @page {
                size: A4 portrait;
                margin: 15mm;
            }
            body {
                font-size: 10.5pt;
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
            /* Adjustments to fit everything on one page */
            .header { margin-bottom: 5px; padding-bottom: 2px; }
            .header h1 { font-size: 11pt; }
            .header h2 { font-size: 10pt; margin-top: 1px; }
            .section-title { margin-top: 3px; margin-bottom: 2px; font-size: 10pt; }
            .data-table th, .data-table td { padding: 1px; line-height: 1.1; font-size: 9pt; }
            .info-table td { padding: 0px 2px; line-height: 1.1; font-size: 9.5pt; }
            table[style*="margin-bottom: 20px"] { margin-bottom: 5px !important; }
            .signature-space { height: 45px; }
        }
    </style>
</head>
<body onload="window.print()">
    <button class="btn-print" onclick="window.print()">Cetak Laporan</button>
    <div class="container">
        <div class="header">
            <h1>LAPORAN HASIL EVALUASI KINERJA GURU</h1>
        </div>

        <table style="width: 100%; margin-bottom: 20px; border-collapse: collapse;">
            <tr>
                <td style="width: 49%; vertical-align: top; padding: 0;">
                    <div class="section-title" style="margin-top: 0; font-size: 11pt;">I. IDENTITAS GURU YANG DINILAI</div>
                    <table class="data-table" style="margin-bottom: 0; width: 100%;">
                        <tr><td style="width:40%">Nama</td><td>: {{ $evaluation->guru->nama }}</td></tr>
                        <tr><td>NIP</td><td>: {{ $evaluation->guru->nip ?? '-' }}</td></tr>
                        <tr><td>Pangkat/Golongan</td><td>: {{ $evaluation->guru->pangkatGolongan->nama ?? '-' }} {{ isset($evaluation->guru->pangkatGolongan->golongan) ? '('.$evaluation->guru->pangkatGolongan->golongan.')' : '' }}</td></tr>
                        <tr><td>Mata Pelajaran</td><td>: {{ $evaluation->guru->mataPelajaran->nama ?? $evaluation->guru->mata_pelajaran ?? '-' }}</td></tr>
                        <tr><td>Kelompok Mapel</td><td>: {{ $evaluation->guru->mataPelajaran->kelompokMapel->nama_kelompok_mapel ?? '-' }}</td></tr>
                        <tr><td>Kompetensi Keahlian</td><td>: {{ $evaluation->guru->kompetensiKeahlian->nama ?? '-' }}</td></tr>
                        <tr><td>Unit Kerja (Sekolah)</td><td>: {{ $evaluation->guru->school->nama ?? '-' }}</td></tr>
                        @php
                            $periode = $evaluation->evaluationPeriod->nama ?? '';
                            $tahunAjaran = '-';
                            $semester = '-';
                            
                            if (preg_match('/(\d{4}\/\d{4}|\d{4})/', $periode, $matches)) {
                                $tahunAjaran = $matches[1];
                            }
                            
                            if (preg_match('/semester\s+(ganjil|genap|1|2)/i', $periode, $matches)) {
                                $semester = ucwords(strtolower($matches[1]));
                            }
                        @endphp
                        <tr><td>Tahun Ajaran</td><td>: {{ $tahunAjaran }}</td></tr>
                        <tr><td>Semester</td><td>: {{ $semester }}</td></tr>
                    </table>
                </td>
                <td style="width: 2%; padding: 0;"></td>
                <td style="width: 49%; vertical-align: top; padding: 0;">
                    <div class="section-title" style="margin-top: 0; font-size: 11pt;">II. IDENTITAS PENILAI</div>
                    <table class="data-table" style="margin-bottom: 0; width: 100%;">
                        <tr><td style="width:40%">Nama</td><td>: {{ $evaluation->penilai->nama }}</td></tr>
                        <tr><td>NIP</td><td>: {{ $evaluation->penilai->nip ?? '-' }}</td></tr>
                        <tr><td>Pangkat/Golongan</td><td>: {{ $evaluation->penilai->pangkatGolongan->nama ?? '-' }} {{ isset($evaluation->penilai->pangkatGolongan->golongan) ? '('.$evaluation->penilai->pangkatGolongan->golongan.')' : '' }}</td></tr>
                        <tr><td>Jabatan</td><td>: {{ $evaluation->penilai->jabatan }}</td></tr>
                        <tr><td>Unit Kerja (Sekolah)</td><td>: {{ $evaluation->penilai->school->nama ?? '-' }}</td></tr>
                    </table>
                </td>
            </tr>
        </table>

        @php
            $allIndicators = collect();
            foreach($dimensions as $dim) {
                foreach($dim->indicators as $ind) {
                    $allIndicators->push($ind);
                }
            }
            $totalIndicators = $allIndicators->count();
            
            // Get date for signature
            $date = \Carbon\Carbon::parse($evaluation->tanggal_selesai ?? now())->translatedFormat('d F Y');
            $kepsekName = $evaluation->guru->school->kepala_sekolah ?? '';
            $kepsek = \App\Models\KepalaSekolah::where('nama', $kepsekName)->first();
        @endphp

        <div class="section-title">III. REKAP HASIL PENILAIAN</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 12%; background-color: transparent;">Nomor Butir Kinerja</th>
                    <th style="width: 13%; background-color: transparent;">Kode Butir Kinerja</th>
                    <th style="width: 15%; background-color: transparent;">Nilai (Level Kinerja)</th>
                    <th style="width: 60%; background-color: transparent;">Rekomendasi Tindak Lanjut</th>
                </tr>
            </thead>
            <tbody>
                @foreach($allIndicators as $index => $ind)
                    @php
                        $result = $resultsMap->get($ind->id);
                    @endphp
                    <tr>
                        <td style="text-align: center; vertical-align: middle;">{{ $ind->urutan_keseluruhan }}</td>
                        <td style="text-align: center; vertical-align: middle;">{{ $ind->kode }}</td>
                        <td style="text-align: center; vertical-align: middle;">{{ $result && $result->status == 'selesai' ? $result->level_capaian : '-' }}</td>
                        @if($index == 0)
                        <td rowspan="{{ $totalIndicators }}" style="vertical-align: top; text-align: justify;">
                            @if($evaluation->rekomendasi)
                                {!! nl2br(e($evaluation->rekomendasi->rekomendasi)) !!}
                            @else
                                <i>Belum ada rekomendasi</i>
                            @endif
                        </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div style="text-align: right; margin-bottom: 10px; margin-top: 20px;">
            Makassar, {{ $date }}
        </div>
        <table class="signature-table" style="page-break-inside: avoid; margin-top: 0;">
            <tr>
                <td>
                    GURU YANG DINILAI<br>
                    <div class="signature-space"></div>
                    <b><u>{{ $evaluation->guru->nama }}</u></b><br>
                    NIP. {{ $evaluation->guru->nip ?? '-' }}
                </td>
                <td>
                    PENILAI/EVALUATOR<br>
                    <div class="signature-space"></div>
                    <b><u>{{ $evaluation->penilai->nama }}</u></b><br>
                    NIP. {{ $evaluation->penilai->nip ?? '-' }}
                </td>
                <td>
                    KEPALA SEKOLAH<br>
                    <div class="signature-space"></div>
                    <b><u>{{ $kepsekName ?: '.......................................' }}</u></b><br>
                    NIP. {{ $kepsek->nip ?? '....................................' }}
                </td>
            </tr>
        </table>

        <div class="page-break"></div>

        <div class="section-title">IV. RINCIAN HASIL PENILAIAN</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 10%; background-color: transparent;">Nomor Butir Kinerja</th>
                    <th style="width: 25%; background-color: transparent;">Dimensi Kinerja</th>
                    <th style="width: 50%; background-color: transparent;">Deskripsi Kinerja & Kesimpulan Penilai</th>
                    <th style="width: 15%; background-color: transparent;">Nilai (Level Kinerja)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dimensions as $dim)
                    <tr>
                        <td colspan="4" style="background-color: #f9f9f9; font-weight: bold;">{{ $dim->urutan_romawi ?? $dim->urutan }}. {{ $dim->nama }}</td>
                    </tr>
                    @foreach($dim->indicators as $ind)
                        @php
                            $result = $resultsMap->get($ind->id);
                        @endphp
                        <tr>
                            <td style="text-align: center; vertical-align: middle;">{{ $ind->urutan_keseluruhan }}</td>
                            <td style="text-align: center; vertical-align: middle;">{{ $ind->nama }} ({{ $ind->kode }})</td>
                            <td style="vertical-align: middle; text-align: justify;">
                                <b>Deskripsi:</b><br>
                                <div style="text-align: justify;">{{ $ind->deskripsi }}</div>
                                <div style="margin-top: 10px;">
                                    <b>Kesimpulan Penilai:</b><br>
                                    <div style="text-align: justify;">
                                    @if($result && $result->status == 'selesai')
                                        {{ $result->kesimpulan }}
                                    @else
                                        <span style="color: #666;">(Belum dinilai)</span>
                                    @endif
                                    </div>
                                </div>
                            </td>
                            <td style="text-align: center; vertical-align: middle; font-size: 14pt;">
                                {{ $result && $result->status == 'selesai' ? $result->level_capaian : '-' }}
                            </td>
                        </tr>
                    @endforeach
                @endforeach
                <tr>
                    <td colspan="3" style="text-align: right; font-weight: bold;">Total Skor</td>
                    <td style="text-align: center; font-weight: bold; font-size: 14pt;">{{ $evaluation->total_skor ?? '0' }}</td>
                </tr>
            </tbody>
        </table>

        @if($evaluation->catatan_kepala_sekolah)
        <div style="margin-top: 20px; border: 1px dashed #000; padding: 15px;">
            <b>Catatan / Feedback Kepala Sekolah:</b><br>
            {{ $evaluation->catatan_kepala_sekolah }}
        </div>
        @endif

        <div style="text-align: right; margin-bottom: 10px; margin-top: 30px;">
            Makassar, {{ $date }}
        </div>
        <table class="signature-table" style="page-break-inside: avoid; margin-top: 0;">
            <tr>
                <td>
                    GURU YANG DINILAI<br>
                    <div class="signature-space"></div>
                    <b><u>{{ $evaluation->guru->nama }}</u></b><br>
                    NIP. {{ $evaluation->guru->nip ?? '-' }}
                </td>
                <td>
                    PENILAI/EVALUATOR<br>
                    <div class="signature-space"></div>
                    <b><u>{{ $evaluation->penilai->nama }}</u></b><br>
                    NIP. {{ $evaluation->penilai->nip ?? '-' }}
                </td>
                <td>
                    KEPALA SEKOLAH<br>
                    <div class="signature-space"></div>
                    <b><u>{{ $kepsekName ?: '.......................................' }}</u></b><br>
                    NIP. {{ $kepsek->nip ?? '....................................' }}
                </td>
            </tr>
        </table>
    </div>
</body>
</html>

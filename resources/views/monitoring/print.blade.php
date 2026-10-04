<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Arial, sans-serif; color: #1e293b; margin: 24px; font-size: 12px; }
        h1 { font-size: 18px; margin: 0 0 4px; }
        .meta { color: #64748b; margin-bottom: 4px; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { border: 1px solid #cbd5e1; padding: 6px 8px; text-align: left; }
        th { background: #f1f5f9; font-size: 11px; text-transform: uppercase; }
        td.num { text-align: right; }
        .toolbar { margin-bottom: 12px; }
        .toolbar button { padding: 6px 14px; font-size: 12px; cursor: pointer; }
        @media print { .toolbar { display: none; } }
    </style>
</head>
<body>
    <div class="toolbar">
        <button type="button" onclick="window.print()">Cetak Halaman</button>
    </div>

    <h1>{{ $title }}</h1>
    <p class="meta">Dicetak pada {{ now()->format('d F Y H:i') }}</p>
    <p class="meta">
        Periode: {{ $period_id ? ($periods->firstWhere('id', (int) $period_id)->nama ?? $period_id) : 'Semua Periode' }}
        &nbsp;|&nbsp; Status: {{ $status ?: 'Semua Status' }}
    </p>

    <table>
        <thead>
            <tr>
                @foreach($headers as $header)
                    <th>{{ $header }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse($lines as $line)
                <tr>
                    @foreach($line as $cell)
                        <td>{{ $cell }}</td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($headers) }}">Tidak ada data untuk filter yang dipilih.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
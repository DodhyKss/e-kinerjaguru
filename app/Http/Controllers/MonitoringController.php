<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\EvaluationPeriod;
use App\Models\Kabupaten;
use App\Models\Provinsi;
use App\Models\School;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Monitoring Kinerja Guru per wilayah.
 *
 * Guru & penilai tidak punya kolom wilayah, sehingga agregasi selalu berjalan
 * lewat rantai guru -> sekolah -> kabupaten -> provinsi.
 *
 * Catatan konsistensi data: schools.provinsi_id dan schools.kabupaten_id tidak
 * dijamin saling cocok, jadi setiap agregasi wilayah diturunkan dari
 * kabupaten.provinsi_id (bukan schools.provinsi_id).
 */
class MonitoringController extends Controller
{
    /** Status yang dianggap "sudah selesai dinilai". */
    private const DONE_STATUSES = ['completed', 'approved'];

    public function index(Request $request)
    {
        $filters = $this->filters($request);
        $rows = $this->provinsiRows($filters);

        $totals = $this->sumRows($rows);

        return view('monitoring.index', array_merge($filters, [
            'level' => 'provinsi',
            'rows' => $rows,
            'totals' => $totals,
            'periods' => $this->periods(),
        ]));
    }

    public function kabupaten(Request $request, Provinsi $provinsi)
    {
        $filters = $this->filters($request);
        $rows = $this->kabupatenRows($filters, $provinsi);

        return view('monitoring.kabupaten', array_merge($filters, [
            'level' => 'kabupaten',
            'provinsi' => $provinsi,
            'rows' => $rows,
            'totals' => $this->sumRows($rows),
            'periods' => $this->periods(),
        ]));
    }

    public function sekolah(Request $request, Kabupaten $kabupaten)
    {
        $filters = $this->filters($request);
        $rows = $this->sekolahRows($filters, $kabupaten);

        return view('monitoring.sekolah', array_merge($filters, [
            'level' => 'sekolah',
            'kabupaten' => $kabupaten,
            'rows' => $rows,
            'totals' => $this->sumRows($rows),
            'periods' => $this->periods(),
        ]));
    }

    public function export(Request $request)
    {
        $filters = $this->filters($request);
        $level = $request->query('level', 'provinsi');

        [$title, $headers, $lines] = $this->buildExport($request, $filters, $level);

        $filename = 'monitoring-'.$level.'-'.now()->format('Ymd-His').'.csv';

        return response()->streamDownload(function () use ($title, $headers, $lines) {
            $out = fopen('php://output', 'w');
            // BOM agar Excel membaca UTF-8 dengan benar.
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, [$title]);
            fputcsv($out, $headers);
            foreach ($lines as $line) {
                fputcsv($out, $line);
            }
            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function print(Request $request)
    {
        $filters = $this->filters($request);
        $level = $request->query('level', 'provinsi');

        [$title, $headers, $lines] = $this->buildExport($request, $filters, $level);

        return view('monitoring.print', array_merge($filters, [
            'level' => $level,
            'title' => $title,
            'headers' => $headers,
            'lines' => $lines,
            'periods' => $this->periods(),
        ]));
    }

    // ------------------------------------------------------------------
    // Penyusun data
    // ------------------------------------------------------------------

    /**
     * @return array{0:string,1:array,2:array}
     */
    private function buildExport(Request $request, array $filters, string $level): array
    {
        return match ($level) {
            'kabupaten' => $this->exportKabupaten($request, $filters),
            'sekolah' => $this->exportSekolah($request, $filters),
            default => $this->exportProvinsi($filters),
        };
    }

    private function exportProvinsi(array $filters): array
    {
        $rows = $this->provinsiRows($filters);

        $headers = ['Provinsi', 'Jumlah Kabupaten', 'Jumlah Sekolah', 'Jumlah Guru', 'Jumlah Asesor', 'Jumlah Evaluasi', 'Evaluasi Selesai', 'Persentase Selesai', 'Rata-rata Nilai'];
        $lines = array_map(fn ($r) => $this->exportLine([
            $r['nama'],
            $r['kabupaten'],
            $r['sekolah'],
            $r['guru'],
            $r['asesor'],
            $r['evaluasi'],
            $r['selesai'],
            $this->percent($r),
            $r['rata_rata'],
        ]), $rows);

        return ['Monitoring Kinerja Guru - LEVEL PROVINSI', $headers, $lines];
    }

    private function exportKabupaten(Request $request, array $filters): array
    {
        $provinsi = Provinsi::findOrFail($request->query('provinsi'));
        $rows = $this->kabupatenRows($filters, $provinsi);

        $headers = ['Kabupaten', 'Provinsi', 'Jumlah Sekolah', 'Jumlah Guru', 'Jumlah Asesor', 'Jumlah Evaluasi', 'Evaluasi Selesai', 'Persentase Selesai', 'Rata-rata Nilai'];
        $lines = array_map(fn ($r) => $this->exportLine([
            $r['nama'],
            $provinsi->nama,
            $r['sekolah'],
            $r['guru'],
            $r['asesor'],
            $r['evaluasi'],
            $r['selesai'],
            $this->percent($r),
            $r['rata_rata'],
        ]), $rows);

        return ["Monitoring Kinerja Guru - KABUPATEN ({$provinsi->nama})", $headers, $lines];
    }

    private function exportSekolah(Request $request, array $filters): array
    {
        $kabupaten = Kabupaten::findOrFail($request->query('kabupaten'));
        $rows = $this->sekolahRows($filters, $kabupaten);

        $headers = ['Sekolah', 'Kabupaten', 'NPSN', 'Jumlah Guru', 'Jumlah Asesor', 'Jumlah Evaluasi', 'Evaluasi Selesai', 'Persentase Selesai', 'Rata-rata Nilai'];
        $lines = array_map(fn ($r) => $this->exportLine([
            $r['nama'],
            $kabupaten->nama,
            $r['npsn'],
            $r['guru'],
            $r['asesor'],
            $r['evaluasi'],
            $r['selesai'],
            $this->percent($r),
            $r['rata_rata'],
        ]), $rows);

        return ["Monitoring Kinerja Guru - SEKOLAH ({$kabupaten->nama})", $headers, $lines];
    }

    private function exportLine(array $cells): array
    {
        return array_map(fn ($v) => $v === null ? '' : $v, $cells);
    }

    // ------------------------------------------------------------------
    // Agregasi
    // ------------------------------------------------------------------

    /**
     * Query dasar agregasi evaluasi, sudah dibatasi periode & status.
     *
     * $kabupatenColumn diisi hanya saat agregasi per wilayah kabupaten/provinsi.
     */
    private function baseQuery(array $filters, string $groupBy, ?string $kabupatenColumn = null): Builder
    {
        $query = Evaluation::query()
            ->join('gurus', 'gurus.id', '=', 'evaluations.guru_id')
            ->join('schools', 'schools.id', '=', 'gurus.school_id')
            ->when($filters['period_id'], fn ($q) => $q->where('evaluations.evaluation_period_id', $filters['period_id']))
            ->when($filters['status'], fn ($q) => $q->where('evaluations.status', $filters['status']))
            ->select([
                $groupBy.' as wilayah_id',
                DB::raw('COUNT(*) as total_evaluasi'),
                DB::raw('COUNT(DISTINCT gurus.id) as total_guru'),
                DB::raw('COUNT(DISTINCT evaluations.penilai_id) as total_asesor'),
                DB::raw('COUNT(DISTINCT schools.id) as total_sekolah'),
                DB::raw('SUM(CASE WHEN evaluations.status IN ("completed","approved") THEN 1 ELSE 0 END) as total_selesai'),
                DB::raw('AVG(evaluations.rata_rata) as rata_nilai'),
            ])
            ->groupBy($groupBy);

        if ($kabupatenColumn !== null) {
            $query->join('kabupatens', 'kabupatens.id', '=', $kabupatenColumn);
        }

        return $query;
    }

    /**
     * Level 1: agregasi per provinsi (lewat kabupaten.provinsi_id).
     */
    private function provinsiRows(array $filters): array
    {
        // toBase() wajib: builder di atas adalah Eloquent Builder, sedangkan kita
        // butuh objek aggregate (stdClass), bukan model Evaluation.
        $aggregates = $this->baseQuery($filters, 'kabupatens.provinsi_id', 'schools.kabupaten_id')->toBase()->get();

        $byProvinsi = [];
        foreach ($aggregates as $row) {
            $byProvinsi[$row->wilayah_id] = (array) $row;
        }

        $kabupatenCount = Kabupaten::selectRaw('provinsi_id, COUNT(*) as jml')->groupBy('provinsi_id')->pluck('jml', 'provinsi_id');

        $rows = [];
        foreach (Provinsi::orderBy('nama')->get() as $provinsi) {
            $agg = $byProvinsi[$provinsi->id] ?? null;

            $rows[] = [
                'id' => $provinsi->id,
                'nama' => $provinsi->nama,
                'kabupaten' => (int) ($kabupatenCount[$provinsi->id] ?? 0),
                'sekolah' => (int) ($agg['total_sekolah'] ?? 0),
                'guru' => (int) ($agg['total_guru'] ?? 0),
                'asesor' => (int) ($agg['total_asesor'] ?? 0),
                'evaluasi' => (int) ($agg['total_evaluasi'] ?? 0),
                'selesai' => (int) ($agg['total_selesai'] ?? 0),
                'rata_rata' => ($agg['rata_nilai'] ?? null) !== null ? round((float) $agg['rata_nilai'], 2) : null,
            ];
        }

        return $rows;
    }

    /**
     * Level 2: agregasi per kabupaten dalam satu provinsi.
     */
    private function kabupatenRows(array $filters, Provinsi $provinsi): array
    {
        $aggregates = $this->baseQuery($filters, 'kabupatens.id', 'schools.kabupaten_id')->toBase()->get();

        $byKabupaten = [];
        foreach ($aggregates as $row) {
            $byKabupaten[$row->wilayah_id] = (array) $row;
        }

        $rows = [];
        $kabupatens = Kabupaten::where('provinsi_id', $provinsi->id)->orderBy('nama')->get();
        $sekolahCount = School::whereNotNull('kabupaten_id')
            ->selectRaw('kabupaten_id, COUNT(*) as jml')
            ->groupBy('kabupaten_id')
            ->pluck('jml', 'kabupaten_id');

        foreach ($kabupatens as $kabupaten) {
            $agg = $byKabupaten[$kabupaten->id] ?? null;

            $rows[] = [
                'id' => $kabupaten->id,
                'nama' => $kabupaten->nama,
                'sekolah' => (int) ($sekolahCount[$kabupaten->id] ?? 0),
                'guru' => (int) ($agg['total_guru'] ?? 0),
                'asesor' => (int) ($agg['total_asesor'] ?? 0),
                'evaluasi' => (int) ($agg['total_evaluasi'] ?? 0),
                'selesai' => (int) ($agg['total_selesai'] ?? 0),
                'rata_rata' => ($agg['rata_nilai'] ?? null) !== null ? round((float) $agg['rata_nilai'], 2) : null,
            ];
        }

        return $rows;
    }

    /**
     * Level 3: agregasi per sekolah dalam satu kabupaten.
     */
    private function sekolahRows(array $filters, Kabupaten $kabupaten): array
    {
        $aggregates = $this->baseQuery($filters, 'schools.id')->toBase()->get();

        $bySekolah = [];
        foreach ($aggregates as $row) {
            $bySekolah[$row->wilayah_id] = (array) $row;
        }

        $rows = [];
        $sekolahs = School::where('kabupaten_id', $kabupaten->id)->orderBy('nama')->get();

        foreach ($sekolahs as $sekolah) {
            $agg = $bySekolah[$sekolah->id] ?? null;

            $rows[] = [
                'id' => $sekolah->id,
                'nama' => $sekolah->nama,
                'npsn' => $sekolah->npsn,
                'guru' => (int) ($agg['total_guru'] ?? 0),
                'asesor' => (int) ($agg['total_asesor'] ?? 0),
                'evaluasi' => (int) ($agg['total_evaluasi'] ?? 0),
                'selesai' => (int) ($agg['total_selesai'] ?? 0),
                'rata_rata' => ($agg['rata_nilai'] ?? null) !== null ? round((float) $agg['rata_nilai'], 2) : null,
            ];
        }

        return $rows;
    }

    // ------------------------------------------------------------------
    // Helper
    // ------------------------------------------------------------------

    private function filters(Request $request): array
    {
        return [
            'period_id' => $request->filled('period_id') ? $request->integer('period_id') : null,
            'status' => $request->filled('status') ? $request->string('status')->toString() : null,
        ];
    }

    private function periods()
    {
        return EvaluationPeriod::orderBy('tanggal_mulai', 'desc')->get();
    }

    private function emptyTotals(): array
    {
        return [
            'kabupaten' => 0,
            'sekolah' => 0,
            'guru' => 0,
            'asesor' => 0,
            'evaluasi' => 0,
            'selesai' => 0,
            'rata_rata' => null,
        ];
    }

    /**
     * Jumlahkan sekumpulan baris menjadi satu baris total.
     * Pada level provinsi angka dijumlahkan begitu adanya; jumlah guru per
     * provinsi sudah unik karena agregasi memakai COUNT(DISTINCT).
     */
    private function sumRows(array $rows): array
    {
        $totals = $this->emptyTotals();

        foreach ($rows as $row) {
            $totals = $this->accumulate($totals, $row);
        }

        // Rata-rata total dibobot jumlah evaluasi, bukan rata-rata dari rata-rata.
        if (($totals['_bobot'] ?? 0) > 0) {
            $totals['rata_rata'] = round($totals['_jumlah_nilai'] / $totals['_bobot'], 2);
        }

        unset($totals['_bobot'], $totals['_jumlah_nilai']);

        return $totals;
    }

    private function accumulate(array $totals, array $row): array
    {
        foreach (['kabupaten', 'sekolah', 'guru', 'asesor', 'evaluasi', 'selesai'] as $key) {
            $totals[$key] += (int) ($row[$key] ?? 0);
        }

        // Rata-rata tetap dihitung dari jumlah, bukan rata-rata dari rata-rata.
        if ($row['rata_rata'] !== null && $row['evaluasi'] > 0) {
            $totals['_bobot'] = ($totals['_bobot'] ?? 0) + $row['evaluasi'];
            $totals['_jumlah_nilai'] = ($totals['_jumlah_nilai'] ?? 0) + ((float) $row['rata_rata'] * $row['evaluasi']);
        }

        return $totals;
    }

    private function percent(array $row): ?float
    {
        if (($row['evaluasi'] ?? 0) === 0) {
            return null;
        }

        return round(($row['selesai'] / $row['evaluasi']) * 100, 1);
    }
}

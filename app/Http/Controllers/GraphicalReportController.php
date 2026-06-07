<?php

namespace App\Http\Controllers;

use App\Models\Dimension;
use App\Models\Evaluation;
use App\Models\EvaluationPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GraphicalReportController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user->isAdmin() && !$user->isKepalaSekolah()) {
            abort(403);
        }

        $schoolId = null;
        if ($user->isKepalaSekolah()) {
            $schoolId = $user->school_id;
        } elseif ($user->isAdmin() && $request->filled('school_id')) {
            $schoolId = $request->school_id;
        }
        
        $activePeriod = EvaluationPeriod::where('status', 'aktif')->first();
        
        // 1. Status Distribution (Pie Chart)
        $statusQuery = Evaluation::query();
        if ($schoolId) {
            $statusQuery->whereHas('guru', fn($q) => $q->where('school_id', $schoolId));
        }
        if ($activePeriod) {
            $statusQuery->where('evaluation_period_id', $activePeriod->id);
        }
        $statusCounts = $statusQuery->select('status', DB::raw('count(*) as total'))
                                    ->groupBy('status')
                                    ->pluck('total', 'status')
                                    ->toArray();
                                    
        $chartData['status'] = [
            'labels' => ['Draft', 'Proses Penilaian', 'Menunggu Review', 'Disetujui'],
            'data' => [
                $statusCounts['draft'] ?? 0,
                $statusCounts['in_progress'] ?? 0,
                $statusCounts['completed'] ?? 0,
                $statusCounts['approved'] ?? 0,
            ]
        ];

        // 2. Trend per Period (Line Chart)
        $trendQuery = Evaluation::select('evaluation_period_id', DB::raw('AVG(rata_rata) as avg_score'))
                                ->whereNotNull('rata_rata');
        if ($schoolId) {
            $trendQuery->whereHas('guru', fn($q) => $q->where('school_id', $schoolId));
        }
        $trends = $trendQuery->groupBy('evaluation_period_id')
                             ->with('evaluationPeriod')
                             ->get();
                             
        // Sort by period id or name
        $trends = $trends->sortBy('evaluation_period_id');
        $chartData['trend'] = [
            'labels' => $trends->map(fn($t) => $t->evaluationPeriod->nama)->values()->toArray(),
            'data' => $trends->map(fn($t) => round($t->avg_score, 2))->values()->toArray(),
        ];

        // 3. Average Score per Dimension for Active Period (Bar Chart)
        $dimensionLabels = [];
        $dimensionData = [];
        
        if ($activePeriod) {
            $dimensions = Dimension::with(['indicators.evaluationResults' => function($q) use ($schoolId, $activePeriod) {
                $q->whereHas('evaluation', function($eq) use ($schoolId, $activePeriod) {
                    $eq->where('evaluation_period_id', $activePeriod->id);
                    if ($schoolId) {
                        $eq->whereHas('guru', fn($g) => $g->where('school_id', $schoolId));
                    }
                });
            }])->orderBy('urutan')->get();

            foreach ($dimensions as $dim) {
                // Label bisa disingkat kalau kepanjangan, e.g. ambil kata kunci
                $dimensionLabels[] = "Dimensi " . $dim->urutan; 
                
                $totalScore = 0;
                $count = 0;
                foreach ($dim->indicators as $ind) {
                    foreach ($ind->evaluationResults as $result) {
                        if ($result->level_capaian) {
                            $totalScore += $result->level_capaian;
                            $count++;
                        }
                    }
                }
                $avg = $count > 0 ? round($totalScore / $count, 2) : 0;
                $dimensionData[] = $avg;
            }
        }
        
        $chartData['dimensions'] = [
            'labels' => $dimensionLabels,
            'data' => $dimensionData,
        ];

        $schools = $user->isAdmin() ? \App\Models\School::orderBy('nama')->get() : collect();

        return view('reports.grafik', compact('chartData', 'activePeriod', 'schools', 'schoolId'));
    }
}

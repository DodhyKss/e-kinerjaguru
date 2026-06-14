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
        
        $periods = EvaluationPeriod::orderBy('id', 'desc')->get();
        $selectedPeriodId = $request->filled('evaluation_period_id') ? $request->evaluation_period_id : null;
        
        if ($selectedPeriodId) {
            $activePeriod = EvaluationPeriod::find($selectedPeriodId);
        } else {
            $activePeriod = EvaluationPeriod::where('status', 'aktif')->first();
        }
        $selectedPeriodId = $activePeriod ? $activePeriod->id : null;
        
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

        // 3. Level Distribution per Indicator (Stacked Bar Chart)
        $indicatorLabels = [];
        $level1Data = [];
        $level2Data = [];
        $level3Data = [];
        $level4Data = [];
        
        if ($activePeriod) {
            $indicators = \App\Models\Indicator::with(['evaluationResults' => function($q) use ($schoolId, $activePeriod) {
                $q->whereHas('evaluation', function($eq) use ($schoolId, $activePeriod) {
                    $eq->where('evaluation_period_id', $activePeriod->id);
                    if ($schoolId) {
                        $eq->whereHas('guru', fn($g) => $g->where('school_id', $schoolId));
                    }
                });
            }])->orderBy('urutan')->get();

            foreach ($indicators as $ind) {
                $indicatorLabels[] = $ind->kode; 
                
                $l1 = 0; $l2 = 0; $l3 = 0; $l4 = 0;
                foreach ($ind->evaluationResults as $result) {
                    if ($result->level_capaian == 1) $l1++;
                    elseif ($result->level_capaian == 2) $l2++;
                    elseif ($result->level_capaian == 3) $l3++;
                    elseif ($result->level_capaian == 4) $l4++;
                }
                
                $level1Data[] = $l1;
                $level2Data[] = $l2;
                $level3Data[] = $l3;
                $level4Data[] = $l4;
            }
        }
        
        $chartData['indicators'] = [
            'labels' => $indicatorLabels,
            'level1' => $level1Data,
            'level2' => $level2Data,
            'level3' => $level3Data,
            'level4' => $level4Data,
        ];

        $schools = $user->isAdmin() ? \App\Models\School::orderBy('nama')->get() : collect();

        return view('reports.grafik', compact('chartData', 'activePeriod', 'schools', 'schoolId', 'periods', 'selectedPeriodId'));
    }
}

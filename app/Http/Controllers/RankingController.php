<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\EvaluationPeriod;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RankingController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Only Admin and Kepala Sekolah can access ranking
        if (!$user->isAdmin() && !$user->isKepalaSekolah()) {
            abort(403);
        }

        // Base Query: Only completed or approved evaluations with a score
        $query = Evaluation::with(['guru.school', 'penilai', 'evaluationPeriod'])
                    ->whereIn('status', ['completed', 'approved'])
                    ->whereNotNull('rata_rata');

        // Apply filters
        // 1. School Filter
        $schoolId = null;
        if ($user->isKepalaSekolah()) {
            $schoolId = $user->school_id;
            $query->whereHas('guru', function($q) use ($schoolId) {
                $q->where('school_id', $schoolId);
            });
        } elseif ($user->isAdmin() && $request->filled('school_id')) {
            $schoolId = $request->school_id;
            $query->whereHas('guru', function($q) use ($schoolId) {
                $q->where('school_id', $schoolId);
            });
        }

        // 2. Period Filter
        $periodId = $request->input('period_id');
        if ($periodId) {
            $query->where('evaluation_period_id', $periodId);
        } else {
            // Default to active period if none selected
            $activePeriod = EvaluationPeriod::where('status', 'aktif')->first();
            if ($activePeriod) {
                $periodId = $activePeriod->id;
                $query->where('evaluation_period_id', $periodId);
            }
        }

        // 3. Guru Name Filter
        if ($request->filled('guru_name')) {
            $query->whereHas('guru', function($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->guru_name . '%');
            });
        }

        // Get rankings ordered by average score descending, then total score descending
        // and if there's a tie, maybe by guru name.
        $rankings = $query->orderBy('rata_rata', 'desc')
                          ->orderBy('total_skor', 'desc')
                          ->paginate(15)->withQueryString();

        // Pass filters data to view
        $periods = EvaluationPeriod::orderBy('tanggal_mulai', 'desc')->get();
        $schools = $user->isAdmin() ? School::orderBy('nama')->get() : collect();

        return view('reports.ranking', compact('rankings', 'periods', 'schools', 'periodId', 'schoolId'));
    }
}

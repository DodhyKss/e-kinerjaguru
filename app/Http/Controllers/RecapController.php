<?php

namespace App\Http\Controllers;

use App\Models\EvaluationPeriod;
use App\Models\Guru;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecapController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user->isAdmin() && !$user->isKepalaSekolah()) {
            abort(403);
        }

        // 1. Determine selected period
        $periodId = $request->input('period_id');
        $activePeriod = EvaluationPeriod::where('status', 'aktif')->first();
        if (!$periodId && $activePeriod) {
            $periodId = $activePeriod->id;
        }
        
        $selectedPeriod = null;
        if ($periodId) {
            $selectedPeriod = EvaluationPeriod::find($periodId);
        }

        // 2. Determine selected school
        $schoolId = null;
        if ($user->isKepalaSekolah()) {
            $schoolId = $user->school_id;
        } elseif ($user->isAdmin()) {
            $schoolId = $request->input('school_id');
        }

        $selectedSchool = null;
        if ($schoolId) {
            $selectedSchool = School::find($schoolId);
        }

        // 3. Query Gurus
        $query = Guru::with(['school', 'evaluations' => function($q) use ($periodId) {
            if ($periodId) {
                $q->where('evaluation_period_id', $periodId)->with('penilai');
            }
        }]);

        if ($schoolId) {
            $query->where('school_id', $schoolId);
        }

        if ($request->filled('guru_name')) {
            $query->where('nama', 'like', '%' . $request->guru_name . '%');
        }

        // Fetch all matching gurus. 
        // For Admin, if no school is selected, we return empty collection to force them to select one.
        if (!$schoolId && $user->isAdmin()) {
            $gurus = collect(); 
        } else {
            $gurus = $query->orderBy('nama', 'asc')->get();
        }

        // Supporting data for filters
        $periods = EvaluationPeriod::orderBy('tanggal_mulai', 'desc')->get();
        $schools = $user->isAdmin() ? School::orderBy('nama')->get() : collect();

        // Check if print mode
        if ($request->has('print')) {
            return view('reports.recap-print', compact('gurus', 'selectedPeriod', 'selectedSchool'));
        }

        return view('reports.recap', compact('gurus', 'periods', 'schools', 'periodId', 'schoolId', 'selectedPeriod', 'selectedSchool'));
    }
}

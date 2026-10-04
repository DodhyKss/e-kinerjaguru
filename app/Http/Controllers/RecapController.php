<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\FiltersWilayah;
use App\Models\EvaluationPeriod;
use App\Models\Guru;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class RecapController extends Controller
{
    use FiltersWilayah;

    public function index(Request $request)
    {
        $user = Auth::user();
        if (! $user->isAdmin() && ! $user->isSchoolScoped()) {
            abort(403);
        }

        // 1. Determine selected period
        $periodId = $request->input('period_id');
        $activePeriod = EvaluationPeriod::where('status', 'aktif')->first();
        if (! $periodId && $activePeriod) {
            $periodId = $activePeriod->id;
        }

        $selectedPeriod = null;
        if ($periodId) {
            $selectedPeriod = EvaluationPeriod::find($periodId);
        }

        // 2. Determine selected school
        $schoolId = null;
        if ($user->isSchoolScoped()) {
            $schoolId = $user->school_id;
        } elseif ($user->isAdmin()) {
            $schoolId = $request->input('school_id');
        }

        $selectedSchool = null;
        if ($schoolId) {
            $selectedSchool = School::find($schoolId);
        }

        // 3. Query Gurus
        $query = Guru::with(['school', 'evaluations' => function ($q) use ($periodId) {
            if ($periodId) {
                $q->where('evaluation_period_id', $periodId)->with(['penilai', 'rekomendasi']);
            }
        }]);

        if ($schoolId) {
            $query->where('school_id', $schoolId);
        }

        $this->applyWilayahFilter($query, $request, 'school');

        if ($request->filled('guru_name')) {
            $query->where('nama', 'like', '%'.$request->guru_name.'%');
        }

        // Fetch all matching gurus.
        // For Admin, if no school is selected, we return empty collection to force them to select one.
        if (! $schoolId && $user->isAdmin()) {
            $gurus = new LengthAwarePaginator([], 0, 10);
        } else {
            $gurus = $query->orderBy('nama', 'asc')->paginate(10)->withQueryString();
        }

        // Supporting data for filters
        $periods = $this->scopedPeriods();
        $schools = $user->isAdmin() ? School::orderBy('nama')->get() : collect();

        // Check if print mode
        if ($request->has('print')) {
            return view('reports.recap-print', compact('gurus', 'selectedPeriod', 'selectedSchool'));
        }

        return view('reports.recap', array_merge(
            compact('gurus', 'periods', 'schools', 'periodId', 'schoolId', 'selectedPeriod', 'selectedSchool'),
            $this->wilayahFilterOptions()
        ));
    }
}

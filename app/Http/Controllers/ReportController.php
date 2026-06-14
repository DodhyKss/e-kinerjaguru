<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\EvaluationPeriod;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = Evaluation::with(['guru.school', 'penilai', 'evaluationPeriod', 'rekomendasi']);

        // Base Scope based on role
        if ($user->isKepalaSekolah()) {
            $query->whereHas('guru', function($q) use ($user) {
                $q->where('school_id', $user->school_id);
            });
        } elseif ($user->isPenilai()) {
            $query->where('penilai_id', $user->penilai->id);
        } elseif ($user->isGuru()) {
            $query->where('guru_id', $user->guru->id);
        }
        
        // Apply Filters
        if ($request->filled('period_id')) {
            $query->where('evaluation_period_id', $request->period_id);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('school_id') && $user->isAdmin()) {
            $query->whereHas('guru', function($q) use ($request) {
                $q->where('school_id', $request->school_id);
            });
        }
        
        if ($request->filled('guru_name')) {
            $query->whereHas('guru', function($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->guru_name . '%');
            });
        }

        $evaluations = $query->latest()->paginate(15)->withQueryString();
        
        // Data for filter dropdowns
        $periods = EvaluationPeriod::orderBy('nama', 'desc')->get();
        $schools = $user->isAdmin() ? School::orderBy('nama')->get() : [];

        return view('reports.index', compact('evaluations', 'periods', 'schools'));
    }
}

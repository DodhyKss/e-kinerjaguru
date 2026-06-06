<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\EvaluationPeriod;
use App\Models\Guru;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return match ($user->role) {
            'admin' => $this->adminDashboard(),
            'kepala_sekolah' => $this->kepsekDashboard($user),
            'penilai' => $this->penilaiDashboard($user),
            'guru' => $this->guruDashboard($user),
            default => abort(403),
        };
    }

    private function adminDashboard()
    {
        $stats = [
            'total_schools' => School::count(),
            'total_gurus' => Guru::count(),
            'active_periods' => EvaluationPeriod::where('status', 'aktif')->count(),
            'total_evaluations' => Evaluation::count(),
        ];
        
        $recentSchools = School::latest()->take(5)->get();
        
        return view('dashboard.admin', compact('stats', 'recentSchools'));
    }

    private function kepsekDashboard($user)
    {
        $schoolId = $user->school_id;
        $stats = [
            'total_gurus' => Guru::where('school_id', $schoolId)->count(),
            'evaluations_completed' => Evaluation::whereHas('guru', function($q) use ($schoolId) {
                $q->where('school_id', $schoolId);
            })->where('status', 'completed')->count(),
            'evaluations_approved' => Evaluation::whereHas('guru', function($q) use ($schoolId) {
                $q->where('school_id', $schoolId);
            })->where('status', 'approved')->count(),
        ];

        $recentEvaluations = Evaluation::with(['guru', 'penilai'])
            ->whereHas('guru', function($q) use ($schoolId) {
                $q->where('school_id', $schoolId);
            })
            ->latest('updated_at')
            ->take(5)
            ->get();

        return view('dashboard.kepala_sekolah', compact('stats', 'recentEvaluations'));
    }

    private function penilaiDashboard($user)
    {
        $penilaiId = $user->penilai->id;
        $stats = [
            'total_assigned' => Evaluation::where('penilai_id', $penilaiId)->count(),
            'in_progress' => Evaluation::where('penilai_id', $penilaiId)->whereIn('status', ['draft', 'in_progress'])->count(),
            'completed' => Evaluation::where('penilai_id', $penilaiId)->whereIn('status', ['completed', 'approved'])->count(),
        ];

        $evaluations = Evaluation::with(['guru', 'evaluationPeriod'])
            ->where('penilai_id', $penilaiId)
            ->orderByRaw("FIELD(status, 'in_progress', 'draft', 'completed', 'approved')")
            ->latest()
            ->get();

        return view('dashboard.penilai', compact('stats', 'evaluations'));
    }

    private function guruDashboard($user)
    {
        $guruId = $user->guru->id;
        
        $currentEvaluation = Evaluation::with(['evaluationPeriod', 'penilai'])
            ->where('guru_id', $guruId)
            ->whereHas('evaluationPeriod', function($q) {
                $q->where('status', 'aktif');
            })->first();
            
        $historyEvaluations = Evaluation::with(['evaluationPeriod'])
            ->where('guru_id', $guruId)
            ->whereIn('status', ['completed', 'approved'])
            ->latest()
            ->get();

        return view('dashboard.guru', compact('currentEvaluation', 'historyEvaluations'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\Rekomendasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RekomendasiController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = Evaluation::with(['guru', 'penilai', 'evaluationPeriod', 'rekomendasi'])
            ->whereIn('status', ['completed', 'approved']); // Evaluasi yang sudah selesai

        if ($user->isPenilai()) {
            $query->where('penilai_id', $user->penilai->id);
        } elseif ($user->isKepalaSekolah()) {
            $query->whereHas('guru', fn($q) => $q->where('school_id', $user->school_id));
        }

        if ($request->filled('period_id')) {
            $query->where('evaluation_period_id', $request->period_id);
        }
        if ($request->filled('guru_id')) {
            $query->where('guru_id', $request->guru_id);
        }
        if ($request->filled('penilai_id')) {
            $query->where('penilai_id', $request->penilai_id);
        }
        if ($request->filled('school_id')) {
            $query->whereHas('guru', function($q) use ($request) {
                $q->where('school_id', $request->school_id);
            });
        }

        $evaluations = $query->latest()->paginate(10)->withQueryString();

        $periods = \App\Models\EvaluationPeriod::orderBy('nama', 'desc')->get();
        
        if ($user->isAdmin() || $user->isKepalaSekolah()) {
            $gurus = \App\Models\Guru::when($user->isKepalaSekolah(), function($q) use ($user) {
                $q->where('school_id', $user->school_id);
            })->orderBy('nama')->get();
        } else if ($user->isPenilai()) {
            $gurus = $user->penilai->gurus()->orderBy('nama')->get();
        } else {
            $gurus = collect([]);
        }

        if ($user->isAdmin() || $user->isKepalaSekolah()) {
            $penilais = \App\Models\Penilai::when($user->isKepalaSekolah(), function($q) use ($user) {
                $q->where('school_id', $user->school_id);
            })->orderBy('nama')->get();
        } else {
            $penilais = collect([]);
        }

        if ($user->isAdmin()) {
            $schools = \App\Models\School::orderBy('nama')->get();
        } else {
            $schools = collect([]);
        }

        return view('rekomendasis.index', compact('evaluations', 'periods', 'gurus', 'penilais', 'schools'));
    }

    public function create(Evaluation $evaluation)
    {
        $user = Auth::user();
        if ($user->isPenilai() && $evaluation->penilai_id !== $user->penilai->id) {
            abort(403);
        }
        if ($user->isKepalaSekolah() && $evaluation->guru->school_id !== $user->school_id) {
            abort(403);
        }

        if (!in_array($evaluation->status, ['completed', 'approved'])) {
            return redirect()->route('evaluations.rekomendasis.index')->with('error', 'Evaluasi belum selesai.');
        }

        $rekomendasi = $evaluation->rekomendasi;
        return view('rekomendasis.form', compact('evaluation', 'rekomendasi'));
    }

    public function store(Request $request, Evaluation $evaluation)
    {
        $user = Auth::user();
        if ($user->isPenilai() && $evaluation->penilai_id !== $user->penilai->id) {
            abort(403);
        }
        if ($user->isKepalaSekolah() && $evaluation->guru->school_id !== $user->school_id) {
            abort(403);
        }

        $validated = $request->validate([
            'what' => 'required|string',
            'why' => 'required|string',
            'how' => 'required|string',
            'rekomendasi' => 'required|string',
        ]);

        $evaluation->rekomendasis()->create($validated);

        return redirect()->route('evaluations.rekomendasis.index')->with('success', 'Rekomendasi berhasil disimpan.');
    }
}

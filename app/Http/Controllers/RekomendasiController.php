<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\Rekomendasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RekomendasiController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $query = Evaluation::with(['guru', 'penilai', 'evaluationPeriod', 'rekomendasi'])
            ->whereIn('status', ['completed', 'approved']); // Evaluasi yang sudah selesai

        if ($user->isPenilai()) {
            $query->where('penilai_id', $user->penilai->id);
        } elseif ($user->isKepalaSekolah()) {
            $query->whereHas('guru', fn($q) => $q->where('school_id', $user->school_id));
        }

        $evaluations = $query->latest()->paginate(10);
        return view('rekomendasis.index', compact('evaluations'));
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

        $evaluation->rekomendasi()->updateOrCreate(
            ['evaluation_id' => $evaluation->id],
            $validated
        );

        return redirect()->route('evaluations.rekomendasis.index')->with('success', 'Rekomendasi berhasil disimpan.');
    }
}

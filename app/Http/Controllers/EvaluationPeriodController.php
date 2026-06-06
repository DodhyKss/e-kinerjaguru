<?php

namespace App\Http\Controllers;

use App\Models\EvaluationPeriod;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EvaluationPeriodController extends Controller
{
    public function index()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $periods = EvaluationPeriod::with('school')->latest()->paginate(10);
        return view('evaluation-periods.index', compact('periods'));
    }

    public function create()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $schools = School::where('status', 'aktif')->get();
        return view('evaluation-periods.create', compact('schools'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'nama' => 'required|string|max:255',
            'tahun_ajaran' => 'required|string|max:20',
            'semester' => 'required|in:ganjil,genap',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'status' => 'required|in:aktif,selesai',
        ]);

        EvaluationPeriod::create($validated);

        return redirect()->route('evaluation-periods.index')->with('success', 'Periode Evaluasi berhasil ditambahkan.');
    }

    public function edit(EvaluationPeriod $evaluationPeriod)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $schools = School::where('status', 'aktif')->get();
        return view('evaluation-periods.edit', compact('evaluationPeriod', 'schools'));
    }

    public function update(Request $request, EvaluationPeriod $evaluationPeriod)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'nama' => 'required|string|max:255',
            'tahun_ajaran' => 'required|string|max:20',
            'semester' => 'required|in:ganjil,genap',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'status' => 'required|in:aktif,selesai',
        ]);

        $evaluationPeriod->update($validated);

        return redirect()->route('evaluation-periods.index')->with('success', 'Periode Evaluasi berhasil diperbarui.');
    }

    public function destroy(EvaluationPeriod $evaluationPeriod)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        try {
            $evaluationPeriod->delete();
            return redirect()->route('evaluation-periods.index')->with('success', 'Periode Evaluasi berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('evaluation-periods.index')->with('error', 'Gagal menghapus Periode Evaluasi karena sudah ada data penilaian terkait.');
        }
    }
}

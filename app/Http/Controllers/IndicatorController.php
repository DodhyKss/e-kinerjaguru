<?php

namespace App\Http\Controllers;

use App\Models\Dimension;
use App\Models\Indicator;
use App\Models\EvaluationResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IndicatorController extends Controller
{
    public function index()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $dimensions = Dimension::orderBy('urutan')->get();
        $indicators = Indicator::with('dimension')->orderBy('kode')->paginate(20);
        
        return view('indicators.index', compact('indicators', 'dimensions'));
    }

    public function create()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $dimensions = Dimension::orderBy('urutan')->get();
        return view('indicators.create', compact('dimensions'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'dimension_id' => 'required|exists:dimensions,id',
            'kode' => 'required|string|max:10|unique:indicators,kode',
            'nama' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'urutan' => 'required|integer|min:1',
            'has_observasi' => 'boolean',
            'has_telaah_dokumen' => 'boolean',
            'has_wawancara' => 'boolean',
        ]);

        $validated['has_observasi'] = $request->has('has_observasi');
        $validated['has_telaah_dokumen'] = $request->has('has_telaah_dokumen');
        $validated['has_wawancara'] = $request->has('has_wawancara');

        $indicator = Indicator::create($validated);

        // Auto-create 4 achievement levels placeholder
        for ($i = 1; $i <= 4; $i++) {
            \App\Models\AchievementLevel::create([
                'indicator_id' => $indicator->id,
                'level' => $i,
                'deskripsi' => "Deskripsi level capaian $i belum diisi."
            ]);
        }

        return redirect()->route('indicators.show', $indicator)->with('success', 'Indikator berhasil ditambahkan. Silakan lengkapi Level Capaian dan Aspek Penilaian.');
    }

    public function show(Indicator $indicator)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $indicator->load(['dimension', 'achievementLevels' => function($q) {
            $q->orderBy('level');
        }, 'observationAspects', 'documentReviewAspects', 'interviewAspects']);

        return view('indicators.show', compact('indicator'));
    }

    public function edit(Indicator $indicator)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $dimensions = Dimension::orderBy('urutan')->get();
        return view('indicators.edit', compact('indicator', 'dimensions'));
    }

    public function update(Request $request, Indicator $indicator)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'dimension_id' => 'required|exists:dimensions,id',
            'kode' => 'required|string|max:10|unique:indicators,kode,' . $indicator->id,
            'nama' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'urutan' => 'required|integer|min:1',
        ]);

        $validated['has_observasi'] = $request->has('has_observasi');
        $validated['has_telaah_dokumen'] = $request->has('has_telaah_dokumen');
        $validated['has_wawancara'] = $request->has('has_wawancara');

        $indicator->update($validated);

        return redirect()->route('indicators.show', $indicator)->with('success', 'Data Master Indikator berhasil diperbarui.');
    }

    public function destroy(Indicator $indicator)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        // Cek apakah indikator ini sudah dinilai di tabel evaluation_results dengan status selesai
        $isUsed = EvaluationResult::where('indicator_id', $indicator->id)->where('status', 'selesai')->exists();
        
        if ($isUsed) {
            return redirect()->route('indicators.index')->with('error', 'Tidak dapat menghapus indikator! Indikator ini sudah pernah digunakan dalam penilaian kinerja asesor. Menghapusnya akan merusak riwayat data evaluasi.');
        }

        $indicator->delete(); // Akan menghapus level & aspek karena cascade jika di setting, tapi mari kita pastikan
        
        return redirect()->route('indicators.index')->with('success', 'Indikator beserta semua komponen level dan aspeknya berhasil dihapus.');
    }
}

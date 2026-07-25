<?php

namespace App\Http\Controllers;

use App\Models\JenisDokumen;
use App\Models\AssessmentAspect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JenisDokumenController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $query = JenisDokumen::withCount('assessmentAspects')->latest();
        
        if ($request->filled('search_id')) {
            $query->where('id', $request->search_id);
        }

        $jenisDokumens = $query->paginate(10)->withQueryString();
        $allData = JenisDokumen::orderBy('nama_jenis_dokumen')->get(['id', 'nama_jenis_dokumen']);

        return view('jenis-dokumens.index', compact('jenisDokumens', 'allData'));
    }

    public function create()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        // Ambil semua aspek telaah dokumen untuk mapping
        $aspects = AssessmentAspect::with('indicator')->where('metode', 'telaah_dokumen')->get()->groupBy('indicator_id');
        return view('jenis-dokumens.create', compact('aspects'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'nama_jenis_dokumen' => 'required|string|max:255|unique:jenis_dokumens',
            'aspects' => 'nullable|array',
            'aspects.*' => 'exists:assessment_aspects,id',
        ]);

        $jenisDokumen = JenisDokumen::create([
            'nama_jenis_dokumen' => $validated['nama_jenis_dokumen']
        ]);

        if (!empty($validated['aspects'])) {
            AssessmentAspect::whereIn('id', $validated['aspects'])->update(['jenis_dokumen_id' => $jenisDokumen->id]);
        }

        return redirect()->route('jenis-dokumens.index')->with('success', 'Jenis Dokumen dan pemetaan berhasil ditambahkan.');
    }

    public function edit(JenisDokumen $jenisDokumen)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        $aspects = AssessmentAspect::with('indicator')->where('metode', 'telaah_dokumen')->get()->groupBy('indicator_id');
        $selectedAspects = $jenisDokumen->assessmentAspects->pluck('id')->toArray();
        
        return view('jenis-dokumens.edit', compact('jenisDokumen', 'aspects', 'selectedAspects'));
    }

    public function update(Request $request, JenisDokumen $jenisDokumen)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        $validated = $request->validate([
            'nama_jenis_dokumen' => 'required|string|max:255|unique:jenis_dokumens,nama_jenis_dokumen,' . $jenisDokumen->id,
            'aspects' => 'nullable|array',
            'aspects.*' => 'exists:assessment_aspects,id',
        ]);

        $jenisDokumen->update([
            'nama_jenis_dokumen' => $validated['nama_jenis_dokumen']
        ]);

        // Reset mapping sebelumnya untuk jenis dokumen ini
        AssessmentAspect::where('jenis_dokumen_id', $jenisDokumen->id)->update(['jenis_dokumen_id' => null]);
        
        // Update dengan mapping baru
        if (!empty($validated['aspects'])) {
            AssessmentAspect::whereIn('id', $validated['aspects'])->update(['jenis_dokumen_id' => $jenisDokumen->id]);
        }

        return redirect()->route('jenis-dokumens.index')->with('success', 'Jenis Dokumen dan pemetaan berhasil diperbarui.');
    }

    public function destroy(JenisDokumen $jenisDokumen)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        
        // Karena on delete null diset di migration, menghapus jenis dokumen akan
        // men-set jenis_dokumen_id di tabel aspects menjadi null otomatis.
        $jenisDokumen->delete();

        return redirect()->route('jenis-dokumens.index')->with('success', 'Jenis Dokumen berhasil dihapus.');
    }
}

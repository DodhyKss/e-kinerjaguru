<?php

namespace App\Http\Controllers;

use App\Models\AssessmentAspect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssessmentAspectController extends Controller
{
    public function store(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'indicator_id' => 'required|exists:indicators,id',
            'metode' => 'required|in:observasi,telaah_dokumen,wawancara',
            'aspek' => 'required|string',
            'nama_dokumen' => 'nullable|string',
            'nomor' => 'required|integer|min:1',
        ]);

        AssessmentAspect::create($validated);

        return back()->with('success', 'Aspek penilaian baru berhasil ditambahkan.');
    }

    public function update(Request $request, AssessmentAspect $aspect)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'aspek' => 'required|string',
            'nama_dokumen' => 'nullable|string',
            'nomor' => 'required|integer|min:1',
        ]);

        $aspect->update($validated);

        return back()->with('success', "Aspek nomor {$aspect->nomor} berhasil diperbarui.");
    }

    public function destroy(AssessmentAspect $aspect)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        // Cek apakah aspek ini sudah ada data hasil penilaian (faktual) nya.
        $isUsed = false;
        if ($aspect->metode == 'observasi') {
            $isUsed = \App\Models\ObservationData::where('assessment_aspect_id', $aspect->id)->exists();
        } else if ($aspect->metode == 'telaah_dokumen') {
            $isUsed = \App\Models\DocumentReviewData::where('assessment_aspect_id', $aspect->id)->exists();
        } else if ($aspect->metode == 'wawancara') {
            $isUsed = \App\Models\InterviewData::where('assessment_aspect_id', $aspect->id)->exists();
        }

        if ($isUsed) {
            return back()->with('error', 'Tidak dapat menghapus aspek penilaian ini karena sudah digunakan dan diisi oleh asesor pada proses evaluasi. Menghapusnya akan menghilangkan bukti data faktual.');
        }

        $aspect->delete();

        return back()->with('success', 'Aspek penilaian berhasil dihapus.');
    }
}

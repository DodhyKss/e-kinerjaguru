<?php

namespace App\Http\Controllers;

use App\Models\AssessmentAspect;
use App\Models\Indicator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssessmentAspectController extends Controller
{
    public function store(Request $request, Indicator $indicator)
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
            'target_responden' => 'nullable|array',
            'target_responden.*' => 'in:kepala_wakil,kepala_kompetensi,guru,siswa',
        ]);

        if ($request->metode === 'wawancara') {
            $validated['target_responden'] = $request->input('target_responden', ['kepala_wakil', 'kepala_kompetensi', 'guru', 'siswa']);
        }

        AssessmentAspect::create($validated);

        return back()->with('success', 'Aspek penilaian baru berhasil ditambahkan.');
    }

    public function update(Request $request, Indicator $indicator, AssessmentAspect $aspect)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'aspek' => 'required|string',
            'nama_dokumen' => 'nullable|string',
            'nomor' => 'required|integer|min:1',
            'target_responden' => 'nullable|array',
            'target_responden.*' => 'in:kepala_wakil,kepala_kompetensi,guru,siswa',
        ]);

        if ($aspect->metode === 'wawancara') {
            $validated['target_responden'] = $request->input('target_responden', ['kepala_wakil', 'kepala_kompetensi', 'guru', 'siswa']);
        }

        $aspect->update($validated);

        return back()->with('success', "Aspek nomor {$aspect->nomor} berhasil diperbarui.");
    }

    public function bulkUpdate(Request $request, Indicator $indicator)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'aspects' => 'required|array',
            'aspects.*.aspek' => 'required|string',
            'aspects.*.nomor' => 'required|integer|min:1',
            'aspects.*.nama_dokumen' => 'nullable|string',
            'aspects.*.target_responden' => 'nullable|array',
            'aspects.*.target_responden.*' => 'in:kepala_wakil,kepala_kompetensi,guru,siswa',
        ]);

        foreach ($validated['aspects'] as $aspectId => $data) {
            $aspect = AssessmentAspect::find($aspectId);
            if ($aspect) {
                if ($aspect->metode === 'wawancara' && !isset($data['target_responden'])) {
                    // Jika dikosongkan (tidak ada yang dicentang), maka null.
                    $data['target_responden'] = [];
                }
                $aspect->update($data);
            }
        }

        return back()->with('success', "Semua Aspek Penilaian berhasil diperbarui.");
    }

    public function destroy(Indicator $indicator, AssessmentAspect $aspect)
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

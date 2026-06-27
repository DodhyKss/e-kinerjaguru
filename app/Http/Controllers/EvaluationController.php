<?php

namespace App\Http\Controllers;

use App\Models\Dimension;
use App\Models\Evaluation;
use App\Models\EvaluationPeriod;
use App\Models\EvaluationResult;
use App\Models\Guru;
use App\Models\Indicator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EvaluationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->isAdmin() || $user->isKepalaSekolah()) {
            $evaluations = Evaluation::with(['guru', 'penilai', 'evaluationPeriod'])
                ->when($user->isKepalaSekolah(), function($q) use ($user) {
                    $q->whereHas('guru', fn($g) => $g->where('school_id', $user->school_id));
                })
                ->latest()
                ->paginate(15);
        } else if ($user->isPenilai()) {
            $evaluations = Evaluation::with(['guru', 'evaluationPeriod'])
                ->where('penilai_id', $user->penilai->id)
                ->latest()
                ->paginate(15);
        } else if ($user->isGuru()) {
            $evaluations = Evaluation::with(['penilai', 'evaluationPeriod'])
                ->where('guru_id', $user->guru->id)
                ->latest()
                ->paginate(15);
        } else {
            abort(403);
        }

        return view('evaluations.index', compact('evaluations'));
    }

    public function create()
    {
        $user = Auth::user();
        if (!$user->isAdmin() && !$user->isKepalaSekolah()) {
            abort(403);
        }

        // Ambil periode aktif
        $periods = EvaluationPeriod::where('status', 'aktif')->get();
        
        // Ambil guru dan penilai sesuai scope
        if ($user->isKepalaSekolah()) {
            $gurus = Guru::where('school_id', $user->school_id)->get();
            $penilais = \App\Models\Penilai::where('school_id', $user->school_id)->get();
        } else {
            $gurus = Guru::with('school')->get();
            $penilais = \App\Models\Penilai::with('school')->get();
        }

        return view('evaluations.create', compact('periods', 'gurus', 'penilais'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user->isAdmin() && !$user->isKepalaSekolah()) {
            abort(403);
        }

        $validated = $request->validate([
            'evaluation_period_id' => 'required|exists:evaluation_periods,id',
            'guru_id' => 'required|exists:gurus,id',
            'penilai_id' => 'required|exists:penilais,id',
        ]);

        // Cek apakah sudah ada evaluasi untuk guru ini di periode ini
        $exists = Evaluation::where('evaluation_period_id', $validated['evaluation_period_id'])
                            ->where('guru_id', $validated['guru_id'])
                            ->exists();

        if ($exists) {
            return back()->with('error', 'Guru tersebut sudah memiliki penugasan evaluasi pada periode ini.')->withInput();
        }

        Evaluation::create([
            'evaluation_period_id' => $validated['evaluation_period_id'],
            'guru_id' => $validated['guru_id'],
            'penilai_id' => $validated['penilai_id'],
            'status' => 'draft',
        ]);

        return redirect()->route('evaluations.index')->with('success', 'Penugasan evaluasi berhasil dibuat.');
    }

    public function show(Evaluation $evaluation)
    {
        $user = Auth::user();
        
        // Authorization check
        if ($user->isPenilai() && $evaluation->penilai_id !== $user->penilai->id) {
            abort(403);
        }
        if ($user->isGuru() && $evaluation->guru_id !== $user->guru->id) {
            abort(403);
        }
        if ($user->isKepalaSekolah() && $evaluation->guru->school_id !== $user->school_id) {
            abort(403);
        }

        $evaluation->load(['guru', 'penilai', 'evaluationPeriod', 'results.indicator.dimension']);
        
        // Initialize results if empty
        if ($evaluation->results->isEmpty() && $user->isPenilai() && in_array($evaluation->status, ['draft', 'in_progress'])) {
            $this->initializeResults($evaluation);
            $evaluation->load('results.indicator.dimension');
        }

        $dimensions = Dimension::with(['indicators' => function($q) use ($evaluation) {
            $q->with(['achievementLevels', 'assessmentAspects']);
        }])->orderBy('urutan')->get();

        // Group results by indicator ID for easy access in view
        $resultsMap = $evaluation->results->keyBy('indicator_id');

        return view('evaluations.show', compact('evaluation', 'dimensions', 'resultsMap'));
    }

    public function report(Evaluation $evaluation)
    {
        $user = Auth::user();
        
        // Authorization check
        if ($user->isPenilai() && $evaluation->penilai_id !== $user->penilai->id) {
            abort(403);
        }
        if ($user->isGuru() && $evaluation->guru_id !== $user->guru->id) {
            abort(403);
        }
        if ($user->isKepalaSekolah() && $evaluation->guru->school_id !== $user->school_id) {
            abort(403);
        }

        $evaluation->load(['guru', 'penilai', 'evaluationPeriod', 'rekomendasi', 'results.indicator.dimension']);
        
        $dimensions = Dimension::with(['indicators' => function($q) use ($evaluation) {
            $q->with(['achievementLevels', 'assessmentAspects']);
        }])->orderBy('urutan')->get();

        // Group results by indicator ID for easy access in view
        $resultsMap = $evaluation->results->keyBy('indicator_id');

        return view('evaluations.report', compact('evaluation', 'dimensions', 'resultsMap'));
    }

    private function initializeResults(Evaluation $evaluation)
    {
        $indicators = Indicator::all();
        $results = [];
        foreach ($indicators as $ind) {
            $results[] = [
                'evaluation_id' => $evaluation->id,
                'indicator_id' => $ind->id,
                'status' => 'belum',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        EvaluationResult::insert($results);
        
        if ($evaluation->status === 'draft') {
            $evaluation->update(['status' => 'in_progress']);
        }
    }

    public function indicatorForm(Evaluation $evaluation, Indicator $indicator)
    {
        $user = Auth::user();
        $isAssignedPenilai = $user->isPenilai() && $evaluation->penilai_id === $user->penilai->id;
        $isKepsekOfSchool = $user->isKepalaSekolah() && $evaluation->guru->school_id === $user->school_id;

        if (!$isAssignedPenilai && !$isKepsekOfSchool) {
            abort(403);
        }

        $result = EvaluationResult::firstOrCreate([
            'evaluation_id' => $evaluation->id,
            'indicator_id' => $indicator->id,
        ]);

        $indicator->load(['achievementLevels', 'observationAspects', 'documentReviewAspects', 'interviewAspects']);
        $result->load(['observationData', 'observationNote', 'documentReviewData', 'documentReviewNote', 'interviewData', 'interviewNote']);

        return view('evaluations.indicator-form', compact('evaluation', 'indicator', 'result'));
    }

    public function saveIndicatorForm(Request $request, Evaluation $evaluation, Indicator $indicator)
    {
        $user = Auth::user();
        $isAssignedPenilai = $user->isPenilai() && $evaluation->penilai_id === $user->penilai->id;
        $isKepsekOfSchool = $user->isKepalaSekolah() && $evaluation->guru->school_id === $user->school_id;

        if (!$isAssignedPenilai && !$isKepsekOfSchool) {
            abort(403);
        }

        $request->validate([
            'level_capaian' => 'required|integer|min:1|max:4',
            'kesimpulan' => 'required|string',
        ]);

        // Simple word count check for kesimpulan (> 50 words)
        $wordCount = str_word_count(strip_tags($request->kesimpulan));
        if ($wordCount < 50) {
            return back()->withErrors(['kesimpulan' => "Kesimpulan minimal 50 kata. Saat ini hanya $wordCount kata."])->withInput();
        }

        DB::transaction(function () use ($request, $evaluation, $indicator) {
            $result = EvaluationResult::firstOrCreate([
                'evaluation_id' => $evaluation->id,
                'indicator_id' => $indicator->id,
            ]);

            $result->update([
                'level_capaian' => $request->level_capaian,
                'kesimpulan' => $request->kesimpulan,
                'status' => 'selesai',
            ]);

            // Save Observation Data
            if ($request->has('observation')) {
                foreach ($request->observation as $aspectId => $hasil) {
                    \App\Models\ObservationData::updateOrCreate(
                        ['evaluation_result_id' => $result->id, 'assessment_aspect_id' => $aspectId],
                        ['hasil' => $hasil]
                    );
                }
            }
            if ($request->has('observation_note')) {
                \App\Models\ObservationNote::updateOrCreate(
                    ['evaluation_result_id' => $result->id],
                    ['catatan' => $request->observation_note]
                );
            }

            // Save Document Review Data
            if ($request->has('document_review')) {
                foreach ($request->document_review as $aspectId => $hasil) {
                    \App\Models\DocumentReviewData::updateOrCreate(
                        ['evaluation_result_id' => $result->id, 'assessment_aspect_id' => $aspectId],
                        ['hasil' => $hasil]
                    );
                }
            }
            if ($request->has('document_review_note')) {
                \App\Models\DocumentReviewNote::updateOrCreate(
                    ['evaluation_result_id' => $result->id],
                    ['catatan' => $request->document_review_note]
                );
            }

            // Save Interview Data
            if ($request->has('interview')) {
                foreach ($request->interview as $aspectId => $respondenData) {
                    foreach ($respondenData as $responden => $hasil) {
                        if (!empty($hasil)) {
                            \App\Models\InterviewData::updateOrCreate(
                                [
                                    'evaluation_result_id' => $result->id, 
                                    'assessment_aspect_id' => $aspectId,
                                    'responden' => $responden
                                ],
                                ['hasil' => $hasil]
                            );
                        }
                    }
                }
            }
            if ($request->has('interview_note')) {
                \App\Models\InterviewNote::updateOrCreate(
                    ['evaluation_result_id' => $result->id],
                    ['catatan' => $request->interview_note]
                );
            }

            // Update evaluation score
            $evaluation->calculateScores();
        });

        return redirect()->route('evaluations.show', $evaluation)->with('success', 'Data evaluasi indikator berhasil disimpan.');
    }

    public function uploadDokumenForm(Evaluation $evaluation, Indicator $indicator)
    {
        // Hanya Guru yang dinilai yang boleh akses
        if (!Auth::user()->isGuru() || $evaluation->guru_id !== Auth::user()->guru->id) {
            abort(403);
        }

        if ($evaluation->status !== 'in_progress' && $evaluation->status !== 'draft') {
            return back()->with('error', 'Evaluasi sudah tidak bisa diubah.');
        }

        $result = EvaluationResult::firstOrCreate([
            'evaluation_id' => $evaluation->id,
            'indicator_id' => $indicator->id,
        ]);

        $indicator->load('documentReviewAspects');
        $result->load('documentReviewData');

        return view('evaluations.upload-dokumen', compact('evaluation', 'indicator', 'result'));
    }

    public function storeDokumen(Request $request, Evaluation $evaluation, Indicator $indicator)
    {
        if (!Auth::user()->isGuru() || $evaluation->guru_id !== Auth::user()->guru->id) {
            abort(403);
        }

        $request->validate([
            'dokumen.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $result = EvaluationResult::firstOrCreate([
            'evaluation_id' => $evaluation->id,
            'indicator_id' => $indicator->id,
        ]);

        if ($request->hasFile('dokumen')) {
            foreach ($request->file('dokumen') as $aspectId => $file) {
                if ($file->isValid()) {
                    // Cek data lama
                    $existingData = \App\Models\DocumentReviewData::where('evaluation_result_id', $result->id)
                        ->where('assessment_aspect_id', $aspectId)
                        ->first();

                    // Hapus file lama jika ada di folder public/
                    if ($existingData && $existingData->file_path) {
                        $oldFilePath = public_path($existingData->file_path);
                        if (file_exists($oldFilePath) && is_file($oldFilePath)) {
                            unlink($oldFilePath);
                        }
                    }

                    // Simpan file baru langsung ke folder public/dokumen_guru
                    $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path('dokumen_guru'), $filename);
                    $path = 'dokumen_guru/' . $filename;
                    $originalName = $file->getClientOriginalName();

                    \App\Models\DocumentReviewData::updateOrCreate(
                        ['evaluation_result_id' => $result->id, 'assessment_aspect_id' => $aspectId],
                        ['file_path' => $path, 'original_filename' => $originalName]
                    );
                }
            }
        }

        return redirect()->route('evaluations.show', $evaluation)->with('success', 'Dokumen bukti berhasil diunggah.');
    }

    public function generalUploadForm(Evaluation $evaluation)
    {
        if (!Auth::user()->isGuru() || $evaluation->guru_id !== Auth::user()->guru->id) {
            abort(403);
        }

        if ($evaluation->status !== 'in_progress' && $evaluation->status !== 'draft') {
            return back()->with('error', 'Evaluasi sudah tidak bisa diubah.');
        }

        // Ambil indikator yang memiliki observasi dokumen (has_telaah_dokumen = true)
        // beserta aspek penilaiannya (metode telaah_dokumen)
        $indicators = Indicator::where('indicators.has_telaah_dokumen', true)
            ->with(['documentReviewAspects'])
            ->join('dimensions', 'indicators.dimension_id', '=', 'dimensions.id')
            ->orderBy('dimensions.urutan')
            ->orderBy('indicators.urutan_keseluruhan')
            ->select('indicators.*')
            ->get();

        return view('evaluations.upload-general', compact('evaluation', 'indicators'));
    }

    public function storeGeneralUpload(Request $request, Evaluation $evaluation)
    {
        if (!Auth::user()->isGuru() || $evaluation->guru_id !== Auth::user()->guru->id) {
            abort(403);
        }

        if ($evaluation->status !== 'in_progress' && $evaluation->status !== 'draft') {
            return back()->with('error', 'Evaluasi sudah tidak bisa diubah.');
        }

        $request->validate([
            'dokumen' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'aspect_ids' => 'required|array',
            'aspect_ids.*' => 'exists:assessment_aspects,id',
        ]);

        if ($request->hasFile('dokumen')) {
            $file = $request->file('dokumen');
            if ($file->isValid()) {
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('dokumen_guru'), $filename);
                $path = 'dokumen_guru/' . $filename;
                $originalName = $file->getClientOriginalName();

                // Dapatkan indicator_id dari masing-masing aspect_id
                $aspects = \App\Models\AssessmentAspect::whereIn('id', $request->aspect_ids)->get();

                foreach ($aspects as $aspect) {
                    $result = EvaluationResult::firstOrCreate([
                        'evaluation_id' => $evaluation->id,
                        'indicator_id' => $aspect->indicator_id,
                    ]);

                    // Cek data lama untuk ditimpa file barunya
                    $existingData = \App\Models\DocumentReviewData::where('evaluation_result_id', $result->id)
                        ->where('assessment_aspect_id', $aspect->id)
                        ->first();

                    if ($existingData && $existingData->file_path) {
                        $oldFilePath = public_path($existingData->file_path);
                        // Hapus file lama jika ada yang sama (opsional, tapi karena di-upload general, satu file bisa dipakai barengan
                        // Jadi kita biarkan saja file fisiknya, atau hanya unlink jika ini referensi terakhir.
                        // Namun lebih amannya, biarkan file lamanya tetap ada di storage, hanya update recordnya saja.
                    }

                    \App\Models\DocumentReviewData::updateOrCreate(
                        ['evaluation_result_id' => $result->id, 'assessment_aspect_id' => $aspect->id],
                        ['file_path' => $path, 'original_filename' => $originalName]
                    );
                }
            }
        }

        return redirect()->route('evaluations.show', $evaluation)->with('success', 'Dokumen bukti general berhasil diunggah dan ditautkan ke aspek terpilih.');
    }

    public function submit(Evaluation $evaluation)
    {
        $user = Auth::user();
        $isAssignedPenilai = $user->isPenilai() && $evaluation->penilai_id === $user->penilai->id;
        $isKepsekOfSchool = $user->isKepalaSekolah() && $evaluation->guru->school_id === $user->school_id;

        if (!$isAssignedPenilai && !$isKepsekOfSchool) {
            abort(403);
        }

        // Check if all indicators are completed
        $totalIndicators = Indicator::count();
        $completedResults = $evaluation->results()->where('status', 'selesai')->count();

        if ($completedResults < $totalIndicators) {
            return back()->with('error', "Evaluasi belum selesai. Baru $completedResults dari $totalIndicators indikator yang dinilai.");
        }

        $evaluation->update([
            'status' => 'completed',
            'tanggal_selesai' => now(),
        ]);

        return redirect()->route('evaluations.show', $evaluation)->with('success', 'Evaluasi berhasil disubmit ke Kepala Sekolah.');
    }

    public function approve(Request $request, Evaluation $evaluation)
    {
        if (!Auth::user()->isKepalaSekolah() || $evaluation->guru->school_id !== Auth::user()->school_id) {
            abort(403);
        }

        $request->validate([
            'catatan_kepala_sekolah' => 'nullable|string'
        ]);

        $evaluation->update([
            'status' => 'approved',
            'catatan_kepala_sekolah' => $request->catatan_kepala_sekolah
        ]);

        return back()->with('success', 'Hasil evaluasi berhasil disetujui.');
    }

    public function showIndicator(Evaluation $evaluation, Indicator $indicator)
    {
        $user = Auth::user();
        
        // Authorization check
        if ($user->isPenilai() && $evaluation->penilai_id !== $user->penilai->id) {
            abort(403);
        }
        if ($user->isGuru() && $evaluation->guru_id !== $user->guru->id) {
            abort(403);
        }
        if ($user->isKepalaSekolah() && $evaluation->guru->school_id !== $user->school_id) {
            abort(403);
        }

        $result = EvaluationResult::firstOrCreate([
            'evaluation_id' => $evaluation->id,
            'indicator_id' => $indicator->id,
        ]);

        $indicator->load(['achievementLevels', 'observationAspects', 'documentReviewAspects', 'interviewAspects']);
        $result->load(['observationData', 'observationNote', 'documentReviewData', 'documentReviewNote', 'interviewData', 'interviewNote']);

        $isReadOnly = true;
        return view('evaluations.indicator-form', compact('evaluation', 'indicator', 'result', 'isReadOnly'));
    }
}

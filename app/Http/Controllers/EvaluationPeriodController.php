<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ScopesSchoolData;
use App\Models\EvaluationPeriod;
use App\Models\School;
use Illuminate\Http\Request;

class EvaluationPeriodController extends Controller
{
    use ScopesSchoolData;

    public function index(Request $request)
    {
        $this->authorizeSchoolManagement();
        $this->ensureManagedSchool();

        $query = EvaluationPeriod::with('school')->latest();

        $this->applySchoolScope($query);

        if ($request->filled('search_id')) {
            $query->where('id', $request->search_id);
        }
        // Admin internal selalu terkunci ke sekolahnya sendiri, school_id dari
        // request tidak pernah dipercaya.
        if ($request->filled('school_id') && ! auth()->user()->isAdminInternal()) {
            $query->where('school_id', $request->school_id);
        }

        $periods = $query->paginate(10)->withQueryString();
        $allData = $this->applySchoolScope(EvaluationPeriod::orderBy('nama'))->get(['id', 'nama', 'tahun_ajaran']);
        // Filter sekolah hanya milik Admin Pusat; untuk admin internal daftar
        // sekolah tidak relevan karena halamannya sudah terkunci sekolah sendiri.
        $schools = School::orderBy('nama')->get(['id', 'nama']);

        return view('evaluation-periods.index', compact('periods', 'allData', 'schools'));
    }

    public function create()
    {
        $this->authorizeSchoolManagement();
        $this->ensureManagedSchool();

        $schools = $this->schoolOptions();

        return view('evaluation-periods.create', compact('schools'));
    }

    public function store(Request $request)
    {
        $this->authorizeSchoolManagement();
        $this->ensureManagedSchool();

        // Admin internal tidak mengirim school_id: sekolahnya diambil dari users.school_id.
        $validated = $request->validate([
            'school_id' => $this->schoolIdRule(),
            'nama' => 'required|string|max:255',
            'tahun_ajaran' => 'required|string|max:20',
            'semester' => 'required|in:ganjil,genap',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'status' => 'required|in:aktif,selesai',
        ]);

        $validated['school_id'] = $this->resolveSchoolId($validated);

        EvaluationPeriod::create($validated);

        return redirect()->route('evaluation-periods.index')->with('success', 'Periode Evaluasi berhasil ditambahkan.');
    }

    public function edit(EvaluationPeriod $evaluationPeriod)
    {
        $this->authorizeSchoolManagement();
        $this->ensureManagedSchool();
        $this->authorizeRecordSchool($evaluationPeriod->school_id);

        $schools = $this->schoolOptions();

        return view('evaluation-periods.edit', compact('evaluationPeriod', 'schools'));
    }

    public function update(Request $request, EvaluationPeriod $evaluationPeriod)
    {
        $this->authorizeSchoolManagement();
        $this->ensureManagedSchool();
        $this->authorizeRecordSchool($evaluationPeriod->school_id);

        $validated = $request->validate([
            'school_id' => $this->schoolIdRule(),
            'nama' => 'required|string|max:255',
            'tahun_ajaran' => 'required|string|max:20',
            'semester' => 'required|in:ganjil,genap',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'status' => 'required|in:aktif,selesai',
        ]);

        $validated['school_id'] = $this->resolveSchoolId($validated);

        $evaluationPeriod->update($validated);

        return redirect()->route('evaluation-periods.index')->with('success', 'Periode Evaluasi berhasil diperbarui.');
    }

    public function destroy(EvaluationPeriod $evaluationPeriod)
    {
        $this->authorizeSchoolManagement();
        $this->ensureManagedSchool();
        $this->authorizeRecordSchool($evaluationPeriod->school_id);

        try {
            $evaluationPeriod->delete();

            return redirect()->route('evaluation-periods.index')->with('success', 'Periode Evaluasi berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('evaluation-periods.index')->with('error', 'Gagal menghapus Periode Evaluasi karena sudah ada data penilaian terkait.');
        }
    }

    /**
     * Aturan validasi school_id: wajib untuk Admin Pusat, opsional untuk Admin
     * Internal Sekolah karena sekolahnya sudah terkunci lewat users.school_id.
     */
    protected function schoolIdRule(): string
    {
        return $this->managedSchoolId() !== null
            ? 'nullable|exists:schools,id'
            : 'required|exists:schools,id';
    }

    /**
     * Admin internal tanpa school_id tidak punya cakupan sekolah, tolak permintaannya.
     */
    protected function ensureManagedSchool(): void
    {
        if (auth()->user()->isAdminInternal() && $this->managedSchoolId() === null) {
            abort(403, 'Akun Admin Internal Sekolah belum tertaut ke sekolah.');
        }
    }

    /**
     * Daftar sekolah yang boleh dipilih. Admin internal hanya melihat sekolahnya sendiri.
     */
    protected function schoolOptions()
    {
        $query = School::where('status', 'aktif');

        // Kolom kunci pada tabel schools adalah 'id', bukan 'school_id'.
        return $this->applySchoolScope($query, 'id')->get();
    }
}

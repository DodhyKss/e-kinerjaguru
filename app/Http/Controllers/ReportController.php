<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\FiltersWilayah;
use App\Models\Evaluation;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    use FiltersWilayah;

    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Evaluation::with(['guru.school', 'penilai', 'evaluationPeriod', 'rekomendasi']);

        // Base Scope based on role
        if ($user->isKepalaSekolah()) {
            $query->whereHas('guru', function ($q) use ($user) {
                $q->where('school_id', $user->school_id);
            });
        } elseif ($user->isAdminInternal()) {
            // Admin internal sekolah: hanya evaluasi guru di sekolahnya sendiri.
            $query->whereHas('guru', function ($q) use ($user) {
                $q->where('school_id', $user->school_id);
            });
        } elseif ($user->isPenilai() || $user->isGuru()) {
            $query->where(function ($q) use ($user) {
                if ($user->isPenilai()) {
                    $q->orWhere('penilai_id', $user->penilai->id);
                }
                if ($user->isGuru()) {
                    $q->orWhere('guru_id', $user->guru->id);
                }
            });
        }

        // Apply Filters
        if ($request->filled('period_id')) {
            $query->where('evaluation_period_id', $request->period_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('school_id') && $user->isAdmin()) {
            $query->whereHas('guru', function ($q) use ($request) {
                $q->where('school_id', $request->school_id);
            });
        }

        // Filter pembuktian kosong: Banyak indikator yang belum diisi sama sekali
        // oleh asesor sehingga perlu diperbaiki Admin Pusat.
        if ($request->boolean('pembuktian_kosong')) {
            $query->whereHas('results', function ($q) {
                $q->where(function ($q) {
                    $q->where('status', 'belum')
                        ->orWhereNull('level_capaian')
                        ->orWhereNull('kesimpulan');
                });
            });
        }

        $this->applyWilayahFilter($query, $request);

        if ($request->filled('guru_name')) {
            $query->whereHas('guru', function ($q) use ($request) {
                $q->where('nama', 'like', '%'.$request->guru_name.'%');
            });
        }

        $evaluations = $query->latest()->paginate(15)->withQueryString();

        // Data for filter dropdowns
        // Hanya periode milik sekolahnya yang ditawarkan.
        $periods = $this->scopedPeriods();
        $schools = $user->isAdmin() ? School::orderBy('nama')->get() : [];

        return view('reports.index', array_merge(
            compact('evaluations', 'periods', 'schools'),
            $this->wilayahFilterOptions()
        ));
    }
}

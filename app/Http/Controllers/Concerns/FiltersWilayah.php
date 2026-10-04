<?php

namespace App\Http\Controllers\Concerns;

use App\Models\EvaluationPeriod;
use App\Models\Kabupaten;
use App\Models\Provinsi;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

/**
 * Filter wilayah (provinsi & kabupaten) untuk halaman laporan.
 *
 * Aturan akses:
 * - Hanya Admin Pusat (role 'admin') yang boleh memilih wilayah. Untuk role lain
 *   parameter provinsi/kabupaten diabaikan sepenuhnya, bukan sekadar disembunyikan,
 *   supaya tidak bisa dipakai membaca data sekolah lain.
 * - Opsi dropdown hanya berisi milik sekolah aktor bila bukan Admin Pusat.
 *
 * Catatan konsistensi data: schools.provinsi_id dan schools.kabupaten_id tidak
 * dijamin saling cocok, sehingga filter provinsi sengaja diturunkan lewat
 * kabupaten.provinsi_id dan bukan schools.provinsi_id.
 */
trait FiltersWilayah
{
    /**
     * @param  string  $schoolRelation  Relasi menuju School pada model query.
     *                                  Evaluation -> 'guru.school', Guru -> 'school'.
     */
    protected function applyWilayahFilter($query, Request $request, string $schoolRelation = 'guru.school')
    {
        if (! auth()->user()->isAdmin()) {
            return $query;
        }

        if ($request->filled('provinsi_id')) {
            $query->whereHas($schoolRelation.'.kabupaten', function ($q) use ($request) {
                $q->where('provinsi_id', $request->provinsi_id);
            });
        }

        if ($request->filled('kabupaten_id')) {
            $query->whereHas($schoolRelation, function ($q) use ($request) {
                $q->where('kabupaten_id', $request->kabupaten_id);
            });
        }

        return $query;
    }

    /**
     * Opsi dropdown wilayah. Role selain Admin Pusat hanya melihat wilayah sekolahnya.
     *
     * @return array{provinsis: Collection<int, Provinsi>, kabupatens: Collection<int, Kabupaten>}
     */
    protected function wilayahFilterOptions(): array
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return [
                'provinsis' => Provinsi::orderBy('nama')->get(),
                'kabupatens' => Kabupaten::orderBy('nama')->get(),
            ];
        }

        $kabupatens = $user->school_id
            ? Kabupaten::whereIn('id', School::where('id', $user->school_id)->pluck('kabupaten_id'))->get()
            : collect();

        $provinsis = $kabupatens->isNotEmpty()
            ? Provinsi::whereIn('id', $kabupatens->pluck('provinsi_id'))->orderBy('nama')->get()
            : collect();

        return [
            'provinsis' => $provinsis,
            'kabupatens' => $kabupatens->sortBy('nama')->values(),
        ];
    }

    /**
     * Periode evaluasi yang boleh dipilih: Admin Pusat melihat semua,
     * role lain hanya periode milik sekolahnya.
     *
     * @return Collection<int, EvaluationPeriod>
     */
    protected function scopedPeriods()
    {
        $query = EvaluationPeriod::orderBy('tanggal_mulai', 'desc');

        if (! auth()->user()->isAdmin()) {
            $query->where('school_id', auth()->user()->school_id);
        }

        return $query->get();
    }
}

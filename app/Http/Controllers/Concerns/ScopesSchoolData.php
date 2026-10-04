<?php

namespace App\Http\Controllers\Concerns;

/**
 * Atur cakupan data master guru & penilai.
 *
 * Admin Pusat (role 'admin') bebas akses ke seluruh Indonesia. Admin Internal Sekolah
 * (role 'admin_internal') selalu dipaksa ke sekolahnya sendiri lewat
 * users.school_id, dan school_id dari request tidak pernah dipercaya.
 */
trait ScopesSchoolData
{
    /**
     * Pastikan aktor boleh menambah/mengubah/menghapus data master.
     */
    protected function authorizeSchoolManagement(): void
    {
        if (! auth()->user()->canManageSchoolData()) {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * ID sekolah yang dikelola aktor, atau null bila aksesnya nasional.
     */
    protected function managedSchoolId(): ?int
    {
        $user = auth()->user();

        return $user->isAdminInternal() ? $user->school_id : null;
    }

    /**
     * Pastikan sebuah record berada di sekolah yang dikelola aktor.
     * Melindungi akses via URL (mis. /gurus/{id}/edit milik sekolah lain).
     */
    protected function authorizeRecordSchool(?int $recordSchoolId): void
    {
        $schoolId = $this->managedSchoolId();

        if ($schoolId !== null && $recordSchoolId !== $schoolId) {
            abort(403, 'Anda tidak dapat mengakses data milik sekolah lain.');
        }
    }

    /**
     * Tentukan school_id final: admin internal selalu terkunci ke sekolahnya.
     */
    protected function resolveSchoolId(array $validated): int
    {
        return $this->managedSchoolId() ?? $validated['school_id'];
    }

    /**
     * Batasi query ke sekolah yang dikelola aktor.
     */
    protected function applySchoolScope($query, string $column = 'school_id')
    {
        $schoolId = $this->managedSchoolId();

        return $schoolId !== null ? $query->where($column, $schoolId) : $query;
    }
}

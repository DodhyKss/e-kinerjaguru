<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'school_id', 'avatar', 'is_active',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function guru(): HasOne
    {
        return $this->hasOne(Guru::class);
    }

    public function penilai(): HasOne
    {
        return $this->hasOne(Penilai::class);
    }

    public function kepalaSekolah(): HasOne
    {
        return $this->hasOne(KepalaSekolah::class);
    }

    public function hasRole($role): bool
    {
        if ($this->role === $role) {
            return true;
        }

        if ($role === 'guru') {
            return $this->guru()->count() > 0;
        }
        if ($role === 'penilai') {
            return $this->penilai()->count() > 0;
        }
        if ($role === 'kepala_sekolah') {
            return $this->kepalaSekolah()->count() > 0;
        }

        return false;
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Admin Pusat: yang punya akses penuh ke seluruh data nasional.
     */
    public function isAdminPusat(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Admin Internal Sekolah: yang dibatasi ke sekolahnya sendiri lewat users.school_id.
     */
    public function isAdminInternal(): bool
    {
        return $this->role === 'admin_internal';
    }

    /**
     * Boleh mengelola data master guru & penilai (dengan batas sekolah bila admin internal).
     */
    public function canManageSchoolData(): bool
    {
        return $this->isAdmin() || $this->isAdminInternal();
    }

    /**
     * Role yang hanya boleh melihat data sekolahnya sendiri:
     * kepala sekolah dan admin internal sekolah.
     */
    public function isSchoolScoped(): bool
    {
        return $this->isKepalaSekolah() || $this->isAdminInternal();
    }

    /**
     * Daftar role yang tidak boleh disentuh oleh admin internal sekolah
     * (termasuk akunnya sendiri dan akun admin internal sekolah lain).
     */
    public function isPrivilegedRole(): bool
    {
        return in_array($this->role, ['admin', 'admin_internal'], true);
    }

    /**
     * Apakah $actor boleh mengelola akun ini (reset password / aktif-nonaktif).
     *
     * - Admin Pusat: bebas ke semua akun.
     * - Admin Internal Sekolah: hanya akun non-privileged di sekolahnya sendiri.
     * - Role lain: tidak boleh.
     */
    public function isManageableBy(?User $actor): bool
    {
        if (! $actor) {
            return false;
        }

        if ($actor->isAdmin()) {
            return true;
        }

        if (! $actor->isAdminInternal()) {
            return false;
        }

        return ! $this->isPrivilegedRole() && $this->school_id === $actor->school_id;
    }

    public function isKepalaSekolah(): bool
    {
        return $this->hasRole('kepala_sekolah');
    }

    public function isPenilai(): bool
    {
        return $this->hasRole('penilai');
    }

    public function isGuru(): bool
    {
        return $this->hasRole('guru');
    }

    public function getInitialsAttribute(): string
    {
        $words = explode(' ', $this->name);
        $initials = '';
        foreach (array_slice($words, 0, 2) as $word) {
            $initials .= strtoupper(substr($word, 0, 1));
        }

        return $initials;
    }
}

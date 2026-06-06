<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class School extends Model
{
    protected $fillable = [
        'nama', 'npsn', 'alamat', 'kabupaten', 'provinsi',
        'telepon', 'email', 'kepala_sekolah', 'status',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function gurus(): HasMany
    {
        return $this->hasMany(Guru::class);
    }

    public function penilais(): HasMany
    {
        return $this->hasMany(Penilai::class);
    }

    public function evaluationPeriods(): HasMany
    {
        return $this->hasMany(EvaluationPeriod::class);
    }
}

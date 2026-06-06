<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Guru extends Model
{
    protected $fillable = [
        'user_id', 'school_id', 'nama', 'nip', 'nuptk',
        'mata_pelajaran', 'kompetensi_keahlian', 'pangkat_golongan',
        'jabatan', 'jenis_kelamin', 'no_telepon', 'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function evaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class);
    }
}

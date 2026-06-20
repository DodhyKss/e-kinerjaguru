<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Penilai extends Model
{
    protected $fillable = [
        'user_id', 'school_id', 'pangkat_golongan_id', 'nama', 'nip',
        'jabatan', 'instansi', 'no_telepon', 'status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function pangkatGolongan(): BelongsTo
    {
        return $this->belongsTo(PangkatGolongan::class, 'pangkat_golongan_id');
    }

    public function evaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class);
    }
}

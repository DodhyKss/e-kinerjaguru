<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KepalaSekolah extends Model
{
    protected $fillable = [
        'user_id',
        'school_id',
        'nama',
        'nip',
        'pangkat_golongan_id',
        'no_telepon',
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
        return $this->belongsTo(PangkatGolongan::class);
    }
}

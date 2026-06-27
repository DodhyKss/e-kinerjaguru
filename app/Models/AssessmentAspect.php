<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssessmentAspect extends Model
{
    protected $fillable = ['indicator_id', 'metode', 'nomor', 'aspek', 'nama_dokumen', 'target_responden'];

    protected $casts = [
        'target_responden' => 'array',
    ];

    public function getTargetRespondenListAttribute(): array
    {
        if (is_array($this->target_responden) && !empty($this->target_responden)) {
            return $this->target_responden;
        }
        return ['kepala_wakil', 'kepala_kompetensi', 'guru', 'siswa'];
    }

    public function indicator(): BelongsTo
    {
        return $this->belongsTo(Indicator::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssessmentAspect extends Model
{
    protected $fillable = ['indicator_id', 'metode', 'nomor', 'aspek', 'nama_dokumen'];

    public function indicator(): BelongsTo
    {
        return $this->belongsTo(Indicator::class);
    }
}

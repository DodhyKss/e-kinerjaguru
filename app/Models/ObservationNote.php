<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ObservationNote extends Model
{
    protected $table = 'observation_notes';
    protected $fillable = ['evaluation_result_id', 'catatan'];

    public function evaluationResult(): BelongsTo
    {
        return $this->belongsTo(EvaluationResult::class);
    }
}

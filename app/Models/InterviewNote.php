<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InterviewNote extends Model
{
    protected $table = 'interview_notes';
    protected $fillable = ['evaluation_result_id', 'catatan'];

    public function evaluationResult(): BelongsTo
    {
        return $this->belongsTo(EvaluationResult::class);
    }
}

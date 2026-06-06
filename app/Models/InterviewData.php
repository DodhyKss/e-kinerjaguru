<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InterviewData extends Model
{
    protected $table = 'interview_data';
    protected $fillable = ['evaluation_result_id', 'assessment_aspect_id', 'responden', 'hasil'];

    public function evaluationResult(): BelongsTo
    {
        return $this->belongsTo(EvaluationResult::class);
    }

    public function assessmentAspect(): BelongsTo
    {
        return $this->belongsTo(AssessmentAspect::class);
    }
}

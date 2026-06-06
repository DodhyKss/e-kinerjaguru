<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocumentReviewData extends Model
{
    protected $table = 'document_review_data';
    protected $fillable = ['evaluation_result_id', 'assessment_aspect_id', 'hasil', 'file_path', 'original_filename'];

    public function evaluationResult(): BelongsTo
    {
        return $this->belongsTo(EvaluationResult::class);
    }

    public function assessmentAspect(): BelongsTo
    {
        return $this->belongsTo(AssessmentAspect::class);
    }
}

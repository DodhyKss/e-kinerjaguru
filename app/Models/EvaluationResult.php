<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class EvaluationResult extends Model
{
    protected $fillable = [
        'evaluation_id', 'indicator_id', 'level_capaian', 'kesimpulan', 'status',
        'updated_by_user_id', 'updated_by_name', 'edited_at',
    ];

    protected $casts = [
        'edited_at' => 'datetime',
    ];

    /**
     * Apakah pembuktian ini terakhir disunting oleh Admin Pusat?
     */
    public function wasEditedByAdmin(): bool
    {
        return $this->edited_at !== null;
    }

    public function evaluation(): BelongsTo
    {
        return $this->belongsTo(Evaluation::class);
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by_user_id');
    }

    public function indicator(): BelongsTo
    {
        return $this->belongsTo(Indicator::class);
    }

    public function observationData(): HasMany
    {
        return $this->hasMany(ObservationData::class);
    }

    public function observationNote(): HasOne
    {
        return $this->hasOne(ObservationNote::class);
    }

    public function documentReviewData(): HasMany
    {
        return $this->hasMany(DocumentReviewData::class);
    }

    public function documentReviewNote(): HasOne
    {
        return $this->hasOne(DocumentReviewNote::class);
    }

    public function interviewData(): HasMany
    {
        return $this->hasMany(InterviewData::class);
    }

    public function interviewNote(): HasOne
    {
        return $this->hasOne(InterviewNote::class);
    }
}

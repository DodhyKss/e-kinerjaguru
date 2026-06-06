<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Indicator extends Model
{
    protected $fillable = [
        'dimension_id', 'kode', 'nama', 'deskripsi', 'urutan',
        'has_observasi', 'has_telaah_dokumen', 'has_wawancara',
    ];

    protected $casts = [
        'has_observasi' => 'boolean',
        'has_telaah_dokumen' => 'boolean',
        'has_wawancara' => 'boolean',
    ];

    public function dimension(): BelongsTo
    {
        return $this->belongsTo(Dimension::class);
    }

    public function achievementLevels(): HasMany
    {
        return $this->hasMany(AchievementLevel::class)->orderBy('level');
    }

    public function assessmentAspects(): HasMany
    {
        return $this->hasMany(AssessmentAspect::class)->orderBy('metode')->orderBy('nomor');
    }

    public function observationAspects(): HasMany
    {
        return $this->hasMany(AssessmentAspect::class)->where('metode', 'observasi')->orderBy('nomor');
    }

    public function documentReviewAspects(): HasMany
    {
        return $this->hasMany(AssessmentAspect::class)->where('metode', 'telaah_dokumen')->orderBy('nomor');
    }

    public function interviewAspects(): HasMany
    {
        return $this->hasMany(AssessmentAspect::class)->where('metode', 'wawancara')->orderBy('nomor');
    }
}

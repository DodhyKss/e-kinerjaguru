<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Evaluation extends Model
{
    protected $fillable = [
        'evaluation_period_id', 'guru_id', 'penilai_id',
        'tanggal_mulai', 'tanggal_selesai', 'status',
        'total_skor', 'rata_rata', 'catatan_penilai', 'catatan_kepala_sekolah',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'total_skor' => 'decimal:2',
        'rata_rata' => 'decimal:2',
    ];

    public function evaluationPeriod(): BelongsTo
    {
        return $this->belongsTo(EvaluationPeriod::class);
    }

    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class);
    }

    public function penilai(): BelongsTo
    {
        return $this->belongsTo(Penilai::class);
    }

    public function results(): HasMany
    {
        return $this->hasMany(EvaluationResult::class);
    }

    public function getProgressAttribute(): int
    {
        $total = $this->results()->count();
        if ($total === 0) return 0;
        $completed = $this->results()->where('status', 'selesai')->count();
        return (int) round(($completed / $total) * 100);
    }

    public function calculateScores(): void
    {
        $results = $this->results()->whereNotNull('level_capaian')->get();
        if ($results->isEmpty()) return;

        $this->total_skor = $results->sum('level_capaian');
        $this->rata_rata = $results->avg('level_capaian');
        $this->save();
    }
}

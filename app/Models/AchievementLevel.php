<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AchievementLevel extends Model
{
    protected $fillable = ['indicator_id', 'level', 'deskripsi'];

    public function indicator(): BelongsTo
    {
        return $this->belongsTo(Indicator::class);
    }
}

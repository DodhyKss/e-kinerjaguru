<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rekomendasi extends Model
{
    protected $fillable = ['evaluation_id', 'what', 'why', 'how', 'rekomendasi'];

    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class);
    }
}

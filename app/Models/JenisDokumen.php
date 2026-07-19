<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisDokumen extends Model
{
    protected $fillable = ['nama_jenis_dokumen'];

    public function assessmentAspects()
    {
        return $this->hasMany(AssessmentAspect::class);
    }
}

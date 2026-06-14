<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MataPelajaran extends Model
{
    protected $fillable = ['nama', 'kelompok_mapel_id'];

    public function kelompokMapel()
    {
        return $this->belongsTo(KelompokMapel::class);
    }

    public function gurus()
    {
        return $this->hasMany(Guru::class);
    }
}

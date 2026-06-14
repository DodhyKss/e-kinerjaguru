<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KelompokMapel extends Model
{
    protected $fillable = ['nama_kelompok_mapel'];

    public function mataPelajarans()
    {
        return $this->hasMany(MataPelajaran::class);
    }
}

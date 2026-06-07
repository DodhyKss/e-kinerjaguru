<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PangkatGolongan extends Model
{
    protected $fillable = ['nama'];

    public function gurus()
    {
        return $this->hasMany(Guru::class);
    }
}

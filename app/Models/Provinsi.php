<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provinsi extends Model
{
    protected $fillable = ['nama'];

    public function kabupatens()
    {
        return $this->hasMany(Kabupaten::class);
    }

    public function schools()
    {
        return $this->hasMany(School::class);
    }
}

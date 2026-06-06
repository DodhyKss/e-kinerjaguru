<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dimension extends Model
{
    protected $fillable = ['kode', 'nama', 'deskripsi', 'urutan'];

    public function indicators(): HasMany
    {
        return $this->hasMany(Indicator::class)->orderBy('urutan');
    }
}

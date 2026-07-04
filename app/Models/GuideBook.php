<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuideBook extends Model
{
    protected $fillable = [
        'judul',
        'file_path',
        'original_filename',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}

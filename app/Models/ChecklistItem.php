<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChecklistItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nomor_item',
        'section',
        'deskripsi',
        'is_critical',
        'is_active',
        'urutan',
    ];

    protected $casts = [
        'is_critical' => 'boolean',
        'is_active'   => 'boolean',
    ];
}
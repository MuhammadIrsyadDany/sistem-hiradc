<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemuanFoto extends Model
{
    protected $fillable = [
        'temuan_id',
        'foto_path',
    ];

    public function temuan()
    {
        return $this->belongsTo(Temuan::class, 'temuan_id');
    }
}

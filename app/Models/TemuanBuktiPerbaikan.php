<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemuanBuktiPerbaikan extends Model
{
    protected $fillable = [
        'temuan_id',
        'uploaded_by',
        'foto_path',
        'keterangan',
    ];

    public function temuan()
    {
        return $this->belongsTo(Temuan::class, 'temuan_id');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}

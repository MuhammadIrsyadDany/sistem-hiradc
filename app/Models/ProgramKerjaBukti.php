<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramKerjaBukti extends Model
{
    protected $table = 'program_kerja_bukti';

    protected $fillable = [
        'program_kerja_id',
        'uploaded_by',
        'foto_path',
        'keterangan',
    ];

    public function programKerja()
    {
        return $this->belongsTo(ProgramKerja::class, 'program_kerja_id');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}

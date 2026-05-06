<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class ProgramKerja extends Model
{
    use SoftDeletes;

    protected $table = 'program_kerja';

    protected $fillable = [
        'hiradc_id',
        'created_by',
        'nama_program',
        'pengendalian_risiko',
        'pic',
        'deadline',
        'status',
    ];

    protected $casts = [
        'deadline' => 'date',
    ];

    public function hiradc()
    {
        return $this->belongsTo(HiradcDocument::class, 'hiradc_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function bukti()
    {
        return $this->hasMany(ProgramKerjaBukti::class, 'program_kerja_id');
    }

    public function getStatusBadgeAttribute()
    {
        return match ($this->status) {
            'open'        => '<span class="badge badge-secondary">Open</span>',
            'on_progress' => '<span class="badge badge-info">On Progress</span>',
            'overdue'     => '<span class="badge badge-danger">Overdue</span>',
            'closed'      => '<span class="badge badge-success">Closed</span>',
            default       => '<span class="badge badge-secondary">-</span>',
        };
    }

    public function checkAndUpdateStatus()
    {
        if ($this->status === 'closed') return;

        if ($this->deadline < Carbon::today() && $this->status !== 'closed') {
            $this->update(['status' => 'overdue']);
        }
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LiveAuditFoto extends Model
{
    protected $fillable = [
        'live_audit_id',
        'foto_path',
        'keterangan',
    ];

    public function liveAudit()
    {
        return $this->belongsTo(LiveAudit::class, 'live_audit_id');
    }
}

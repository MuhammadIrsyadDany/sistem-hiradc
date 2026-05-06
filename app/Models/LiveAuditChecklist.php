<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LiveAuditChecklist extends Model
{
    protected $fillable = [
        'live_audit_id',
        'checklist_item_id',
        'jawaban',
    ];

    public function liveAudit()
    {
        return $this->belongsTo(LiveAudit::class, 'live_audit_id');
    }

    public function checklistItem()
    {
        return $this->belongsTo(ChecklistItem::class, 'checklist_item_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LiveAudit extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'created_by',
        'diminta_oleh',
        'nama_pekerjaan',
        'no_work_order',
        'lokasi',
        'perusahaan',
        'tanggal_mulai',
        'tanggal_selesai',
        'unsafe_action_text',
        'unsafe_condition_text',
        'working_permit',
        'status',
        'validated_by_v1',
        'validated_by_v2',
        'validated_at_v1',
        'validated_at_v2',
        'is_stopped',
        'stop_alasan',
        'stopped_at',
    ];

    protected $casts = [
        'tanggal_mulai'   => 'date',
        'tanggal_selesai' => 'date',
        'working_permit'  => 'array',
        'validated_at_v1' => 'datetime',
        'validated_at_v2' => 'datetime',
        'stopped_at'      => 'datetime',
        'is_stopped'      => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function validatorV1()
    {
        return $this->belongsTo(User::class, 'validated_by_v1');
    }

    public function validatorV2()
    {
        return $this->belongsTo(User::class, 'validated_by_v2');
    }

    public function checklists()
    {
        return $this->hasMany(LiveAuditChecklist::class, 'live_audit_id');
    }

    public function getStatusBadgeAttribute()
    {
        return match ($this->status) {
            'draft'      => '<span class="badge badge-secondary">Draft</span>',
            'pending_v1' => '<span class="badge badge-warning">Menunggu Validator 1</span>',
            'pending_v2' => '<span class="badge badge-info">Menunggu Validator 2</span>',
            'approved'   => '<span class="badge badge-success">Disetujui</span>',
            'rejected'   => '<span class="badge badge-danger">Ditolak</span>',
            default      => '<span class="badge badge-secondary">-</span>',
        };
    }

    public function getWorkingPermitListAttribute()
    {
        $labels = [
            'hot_work'            => 'Hot Work',
            'confined_space'      => 'Confined Space',
            'working_at_height'   => 'Working At Height',
            'excavation'          => 'Excavation',
            'isolasi'             => 'Isolasi',
            'vicinity'            => 'Vicinity',
            'near_and_underwater' => 'Near And Underwater',
            'lifting'             => 'Lifting',
            'radiation'           => 'Radiation',
            'chemical_handling'   => 'Chemical Handling',
        ];

        if (!$this->working_permit) return [];

        return collect($this->working_permit)
            ->map(fn($key) => $labels[$key] ?? $key)
            ->toArray();
    }

    public function getScoreAttribute()
    {
        $checklists = $this->checklists;
        $ya   = $checklists->where('jawaban', 'ya')->count();
        $total = $checklists->whereIn('jawaban', ['ya', 'tidak'])->count();
        if ($total === 0) return 0;
        return round(($ya / $total) * 100);
    }
}

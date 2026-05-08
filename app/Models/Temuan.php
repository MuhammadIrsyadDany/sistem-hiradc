<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Temuan extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'live_audit_id',
        'reported_by',
        'distrik',
        'judul_temuan',
        'kondisi',
        'tindak_lanjut',
        'rekomendasi',
        'pic',
        'lokasi',
        'keterangan_lokasi',
        'kategori',
        'ai_kategori',
        'ai_confidence',
        'status',
        'validated_by_v1',
        'validated_by_v2',
        'closed_by',
        'validated_at_v1',
        'validated_at_v2',
        'closed_at',
    ];

    protected $casts = [
        'validated_at_v1' => 'datetime',
        'validated_at_v2' => 'datetime',
        'closed_at'       => 'datetime',
        'ai_confidence'   => 'float',
    ];

    public function liveAudit()
    {
        return $this->belongsTo(LiveAudit::class, 'live_audit_id');
    }

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    public function validatorV1()
    {
        return $this->belongsTo(User::class, 'validated_by_v1');
    }

    public function validatorV2()
    {
        return $this->belongsTo(User::class, 'validated_by_v2');
    }

    public function closedBy()
    {
        return $this->belongsTo(User::class, 'closed_by');
    }

    public function fotos()
    {
        return $this->hasMany(TemuanFoto::class, 'temuan_id');
    }

    public function buktiPerbaikan()
    {
        return $this->hasMany(TemuanBuktiPerbaikan::class, 'temuan_id');
    }

    public function getStatusBadgeAttribute()
    {
        return match ($this->status) {
            'draft'        => '<span class="badge badge-secondary">Draft</span>',
            'open'         => '<span class="badge badge-warning">Open</span>',
            'validated_v1' => '<span class="badge badge-info">Validated V1</span>',
            'validated_v2' => '<span class="badge badge-primary">Validated V2</span>',
            'closed'       => '<span class="badge badge-success">Closed</span>',
            default        => '<span class="badge badge-secondary">-</span>',
        };
    }

    public function getKategoriBadgeAttribute()
    {
        return match ($this->kategori) {
            'unsafe_action'    => '<span class="badge badge-danger">Unsafe Action</span>',
            'unsafe_condition' => '<span class="badge badge-warning">Unsafe Condition</span>',
            'near_miss'        => '<span class="badge badge-info">Near Miss</span>',
            'positive'         => '<span class="badge badge-success">Positive</span>',
            default            => '<span class="badge badge-secondary">-</span>',
        };
    }

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function isLengkap(): bool
    {
        return $this->fotos()->exists()
            && !empty($this->kondisi)
            && !empty($this->lokasi);
    }
}

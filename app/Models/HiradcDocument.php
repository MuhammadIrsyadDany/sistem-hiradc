<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HiradcDocument extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uploaded_by',
        'judul',
        'unit',
        'divisi',
        'area_lokasi',
        'penanggung_jawab',
        'file_path',
        'status',
        'validated_by_v1',
        'validated_by_v2',
        'validated_at_v1',
        'validated_at_v2',
        'catatan_penolakan',
    ];

    protected $casts = [
        'validated_at_v1' => 'datetime',
        'validated_at_v2' => 'datetime',
    ];

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function validatorV1()
    {
        return $this->belongsTo(User::class, 'validated_by_v1');
    }

    public function validatorV2()
    {
        return $this->belongsTo(User::class, 'validated_by_v2');
    }

    public function programKerja()
    {
        return $this->hasMany(ProgramKerja::class, 'hiradc_id');
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
}

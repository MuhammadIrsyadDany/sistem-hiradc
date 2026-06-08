<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HiradcDocument extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uploaded_by',
        'nama_area',
        'unit',
        'divisi',
        'area_lokasi',
        'penanggung_jawab',
        'no_dokumen',
        'tahun',
        'file_path',
    ];

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function aktivitas()
    {
        return $this->hasMany(HiradcAktivitas::class, 'hiradc_id')
            ->orderBy('urutan');
    }

    public function programKerja()
    {
        return $this->hasMany(ProgramKerja::class, 'hiradc_id');
    }

    public function getTotalAspekAttribute(): int
    {
        return $this->aktivitas->sum(fn($a) => $a->aspekBahaya->count());
    }

    public function getRisikoDistribusiAttribute(): array
    {
        $aspeks = $this->aktivitas->flatMap->aspekBahaya;

        return [
            'rendah'        => $aspeks->where('level_risiko', 'rendah')->count(),
            'moderat'       => $aspeks->where('level_risiko', 'moderat')->count(),
            'tinggi'        => $aspeks->where('level_risiko', 'tinggi')->count(),
            'sangat_tinggi' => $aspeks->where('level_risiko', 'sangat_tinggi')->count(),
            'ekstrim'       => $aspeks->where('level_risiko', 'ekstrim')->count(),
        ];
    }
}

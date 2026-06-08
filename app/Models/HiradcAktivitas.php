<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HiradcAktivitas extends Model
{
    protected $table = 'hiradc_aktivitas';

    protected $fillable = [
        'hiradc_id',
        'nama_aktivitas',
        'sumber_bahaya',
        'kondisi',
        'urutan',
    ];

    public function hiradc()
    {
        return $this->belongsTo(HiradcDocument::class, 'hiradc_id');
    }

    public function aspekBahaya()
    {
        return $this->hasMany(HiradcAspekBahaya::class, 'aktivitas_id');
    }

    public function getSumberBaharaLabelAttribute(): string
    {
        return match ($this->sumber_bahaya) {
            'aktivitas'       => 'Aktivitas',
            'peralatan'       => 'Peralatan',
            'lingkungan_kerja' => 'Lingkungan Kerja',
            'proses'          => 'Proses',
            default           => '-',
        };
    }

    public function getKondisiLabelAttribute(): string
    {
        return match ($this->kondisi) {
            'rutin'     => 'Rutin',
            'non_rutin' => 'Non Rutin',
            'abnormal'  => 'Abnormal',
            'darurat'   => 'Darurat',
            default     => '-',
        };
    }
}

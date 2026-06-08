<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HiradcAspekBahaya extends Model
{
    protected $table = 'hiradc_aspek_bahaya';

    protected $fillable = [
        'aktivitas_id',
        'potensi_aspek_lingkungan',
        'potensi_bahaya_k3',
        'peraturan_terkait',
        'pengendalian_existing',
        'level_risiko',
        'level_risiko_akhir',
    ];

    public function aktivitas()
    {
        return $this->belongsTo(HiradcAktivitas::class, 'aktivitas_id');
    }

    public function programKerja()
    {
        return $this->hasMany(ProgramKerja::class, 'aspek_bahaya_id');
    }

    public function getLevelRisikoBadgeAttribute(): string
    {
        return $this->renderRisikoBadge($this->level_risiko);
    }

    public function getLevelRisikoAkhirBadgeAttribute(): string
    {
        if (!$this->level_risiko_akhir) {
            return '<span style="color:#a0aec0; font-size:12px;">Belum dievaluasi</span>';
        }
        return $this->renderRisikoBadge($this->level_risiko_akhir);
    }

    private function renderRisikoBadge(string $level): string
    {
        $config = [
            'rendah'       => ['bg' => '#d4edda', 'color' => '#155724', 'label' => 'Rendah'],
            'moderat'      => ['bg' => '#fff3cd', 'color' => '#856404', 'label' => 'Moderat'],
            'tinggi'       => ['bg' => '#fde8d8', 'color' => '#7d3c00', 'label' => 'Tinggi'],
            'sangat_tinggi' => ['bg' => '#f8d7da', 'color' => '#721c24', 'label' => 'Sangat Tinggi'],
            'ekstrim'      => ['bg' => '#2d3748', 'color' => '#ffffff', 'label' => 'Ekstrim'],
        ];

        $c = $config[$level] ?? ['bg' => '#e2e8f0', 'color' => '#718096', 'label' => '-'];

        return "<span style=\"background:{$c['bg']}; color:{$c['color']};
                    font-size:11px; padding:3px 10px; border-radius:20px;
                    font-weight:700;\">{$c['label']}</span>";
    }

    public function getStatusPenurunanAttribute(): string
    {
        if (!$this->level_risiko_akhir) return 'unchanged';

        $order = [
            'rendah' => 1,
            'moderat' => 2,
            'tinggi' => 3,
            'sangat_tinggi' => 4,
            'ekstrim' => 5,
        ];

        $awal  = $order[$this->level_risiko] ?? 0;
        $akhir = $order[$this->level_risiko_akhir] ?? 0;

        if ($akhir < $awal) return 'turun';
        if ($akhir > $awal) return 'naik';
        return 'tetap';
    }
}

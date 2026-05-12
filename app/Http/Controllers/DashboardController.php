<?php

namespace App\Http\Controllers;

use App\Models\HiradcDocument;
use App\Models\LiveAudit;
use App\Models\ProgramKerja;
use App\Models\Temuan;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // ===============================================================
        // STATS CARDS
        // ===============================================================
        $stats = [
            'hiradc_total'    => HiradcDocument::count(),
            'hiradc_approved' => HiradcDocument::where('status', 'approved')->count(),
            'live_audit_total' => LiveAudit::count(),
            'temuan_open'     => Temuan::whereIn('status', ['open', 'validated_v1', 'validated_v2'])->count(),
            'temuan_closed'   => Temuan::where('status', 'closed')->count(),
            'temuan_draft'    => Temuan::where('status', 'draft')->count(),
            'program_overdue' => ProgramKerja::where('status', 'overdue')->count(),
            'program_open'    => ProgramKerja::whereIn('status', ['open', 'on_progress'])->count(),
        ];

        // ===============================================================
        // GRAFIK UA VS UC PER BULAN (12 bulan terakhir)
        // ===============================================================
        $months      = collect();
        $uaData      = collect();
        $ucData      = collect();
        $nearMissData = collect();

        for ($i = 11; $i >= 0; $i--) {
            $date  = Carbon::now()->subMonths($i);
            $label = $date->format('M Y');
            $months->push($label);

            $uaData->push(
                Temuan::where('kategori', 'unsafe_action')
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count()
            );

            $ucData->push(
                Temuan::where('kategori', 'unsafe_condition')
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count()
            );

            $nearMissData->push(
                Temuan::where('kategori', 'near_miss')
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count()
            );
        }

        $chartUaUc = [
            'labels'   => $months,
            'ua'       => $uaData,
            'uc'       => $ucData,
            'near_miss' => $nearMissData,
        ];

        // ===============================================================
        // GRAFIK STATUS TEMUAN (Donut)
        // ===============================================================
        $chartStatusTemuan = [
            'draft'        => $stats['temuan_draft'],
            'open'         => Temuan::where('status', 'open')->count(),
            'validated_v1' => Temuan::where('status', 'validated_v1')->count(),
            'validated_v2' => Temuan::where('status', 'validated_v2')->count(),
            'closed'       => $stats['temuan_closed'],
        ];

        // ===============================================================
        // GRAFIK TEMUAN PER LOKASI (Top 5)
        // ===============================================================
        $temuanPerLokasi = Temuan::selectRaw('lokasi, COUNT(*) as total')
            ->groupBy('lokasi')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // ===============================================================
        // PROGRAM KERJA PROGRESS
        // ===============================================================
        $programProgress = [
            'open'        => ProgramKerja::where('status', 'open')->count(),
            'on_progress' => ProgramKerja::where('status', 'on_progress')->count(),
            'overdue'     => ProgramKerja::where('status', 'overdue')->count(),
            'closed'      => ProgramKerja::where('status', 'closed')->count(),
        ];

        // ===============================================================
        // TEMUAN TERBARU
        // ===============================================================
        $temuanTerbaru = Temuan::with('reporter')
            ->latest()
            ->limit(5)
            ->get();

        // ===============================================================
        // LIVE AUDIT TERBARU
        // ===============================================================
        $liveAuditTerbaru = LiveAudit::with('creator')
            ->latest()
            ->limit(5)
            ->get();

        // ===============================================================
        // PROGRAM KERJA OVERDUE
        // ===============================================================
        $programOverdue = ProgramKerja::with('hiradc')
            ->where('status', 'overdue')
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'user',
            'stats',
            'chartUaUc',
            'chartStatusTemuan',
            'temuanPerLokasi',
            'programProgress',
            'temuanTerbaru',
            'liveAuditTerbaru',
            'programOverdue',
        ));
    }
}

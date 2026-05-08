<?php

namespace App\Http\Controllers;

use App\Models\LiveAudit;
use App\Models\LiveAuditChecklist;
use App\Models\ChecklistItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\Temuan;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;

class LiveAuditController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('live_audit.view'), 403);

        $liveAudits = LiveAudit::with('creator')
            ->latest()
            ->paginate(10);

        return view('live-audit.index', compact('liveAudits'));
    }

    public function create()
    {
        abort_if(Gate::denies('live_audit.create'), 403);

        $checklistItems = ChecklistItem::where('is_active', true)
            ->orderBy('urutan')
            ->get()
            ->groupBy('section');

        $workingPermits = [
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

        return view('live-audit.create', compact('checklistItems', 'workingPermits'));
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('live_audit.create'), 403);

        $validated = $request->validate([
            'diminta_oleh'          => 'nullable|string|max:255',
            'nama_pekerjaan'        => 'required|string',
            'no_work_order'         => 'nullable|string|max:100',
            'lokasi'                => 'required|string|max:255',
            'perusahaan'            => 'required|string|max:255',
            'tanggal_mulai'         => 'required|date',
            'tanggal_selesai'       => 'required|date|after_or_equal:tanggal_mulai',
            'unsafe_action_text'    => 'nullable|string',
            'unsafe_condition_text' => 'nullable|string',
            'working_permit'        => 'nullable|array',
            'checklists'            => 'required|array',
            'checklists.*'          => 'in:tidak,ya,na',
            'is_stopped'            => 'nullable|boolean',
            'stop_alasan'           => 'nullable|string',
        ]);

        $liveAudit = LiveAudit::create([
            'created_by'            => Auth::id(),
            'diminta_oleh'          => $validated['diminta_oleh'],
            'nama_pekerjaan'        => $validated['nama_pekerjaan'],
            'no_work_order'         => $validated['no_work_order'],
            'lokasi'                => $validated['lokasi'],
            'perusahaan'            => $validated['perusahaan'],
            'tanggal_mulai'         => $validated['tanggal_mulai'],
            'tanggal_selesai'       => $validated['tanggal_selesai'],
            'unsafe_action_text'    => $validated['unsafe_action_text'],
            'unsafe_condition_text' => $validated['unsafe_condition_text'],
            'working_permit'        => $validated['working_permit'] ?? [],
            'is_stopped'            => $request->boolean('is_stopped'),
            'stop_alasan'           => $validated['stop_alasan'],
            'stopped_at'            => $request->boolean('is_stopped') ? now() : null,
            'status'                => 'pending_v1',
        ]);

        // Simpan jawaban checklist
        foreach ($validated['checklists'] as $itemId => $jawaban) {
            LiveAuditChecklist::create([
                'live_audit_id'     => $liveAudit->id,
                'checklist_item_id' => $itemId,
                'jawaban'           => $jawaban,
            ]);
        }

        // Auto-create draft temuan dari UA
        if (
            !empty($validated['unsafe_action_text'])
            && strtolower($validated['unsafe_action_text']) !== 'tidak ada'
        ) {
            Temuan::create([
                'live_audit_id' => $liveAudit->id,
                'reported_by'   => Auth::id(),
                'distrik'       => $validated['lokasi'],
                'judul_temuan'  => $validated['unsafe_action_text'],
                'lokasi'        => $validated['lokasi'],
                'kategori'      => 'unsafe_action',
                'status'        => 'draft',
            ]);
        }

        // Auto-create draft temuan dari UC
        if (
            !empty($validated['unsafe_condition_text'])
            && strtolower($validated['unsafe_condition_text']) !== 'tidak ada'
        ) {
            Temuan::create([
                'live_audit_id' => $liveAudit->id,
                'reported_by'   => Auth::id(),
                'distrik'       => $validated['lokasi'],
                'judul_temuan'  => $validated['unsafe_condition_text'],
                'lokasi'        => $validated['lokasi'],
                'kategori'      => 'unsafe_condition',
                'status'        => 'draft',
            ]);
        }

        return redirect()->route('live-audit.show', $liveAudit)
            ->with('success', 'Live Audit berhasil disimpan. Draft temuan UA/UC otomatis dibuat.');
    }

    public function show(LiveAudit $liveAudit)
    {
        abort_if(Gate::denies('live_audit.view'), 403);

        $liveAudit->load([
            'creator',
            'validatorV1',
            'validatorV2',
            'checklists.checklistItem',
        ]);

        $checklistsBySection = $liveAudit->checklists
            ->groupBy(fn($c) => $c->checklistItem->section);

        return view(
            'live-audit.show',
            compact('liveAudit', 'checklistsBySection')
        );
    }

    public function validateV1(Request $request, LiveAudit $liveAudit)
    {
        abort_if(Gate::denies('live_audit.validate_v1'), 403);

        $request->validate([
            'action' => 'required|in:approve,reject',
        ]);

        if ($request->action === 'approve') {
            $liveAudit->update([
                'status'          => 'pending_v2',
                'validated_by_v1' => Auth::id(),
                'validated_at_v1' => now(),
            ]);
            $message = 'Live Audit disetujui oleh Validator 1.';
        } else {
            $liveAudit->update([
                'status'          => 'rejected',
                'validated_by_v1' => Auth::id(),
                'validated_at_v1' => now(),
            ]);
            $message = 'Live Audit ditolak.';
        }

        return redirect()->route('live-audit.show', $liveAudit)
            ->with('success', $message);
    }

    public function validateV2(Request $request, LiveAudit $liveAudit)
    {
        abort_if(Gate::denies('live_audit.validate_v2'), 403);

        $request->validate([
            'action' => 'required|in:approve,reject',
        ]);

        if ($request->action === 'approve') {
            $liveAudit->update([
                'status'          => 'approved',
                'validated_by_v2' => Auth::id(),
                'validated_at_v2' => now(),
            ]);
            $message = 'Live Audit telah disetujui sepenuhnya.';
        } else {
            $liveAudit->update([
                'status'          => 'rejected',
                'validated_by_v2' => Auth::id(),
                'validated_at_v2' => now(),
            ]);
            $message = 'Live Audit ditolak.';
        }

        return redirect()->route('live-audit.show', $liveAudit)
            ->with('success', $message);
    }

    public function exportPdf(LiveAudit $liveAudit)
    {
        abort_if(Gate::denies('live_audit.view'), 403);

        $liveAudit->load([
            'creator',
            'validatorV1',
            'validatorV2',
            'checklists.checklistItem',
        ]);

        $checklistsBySection = $liveAudit->checklists
            ->groupBy(fn($c) => $c->checklistItem->section);

        $pdf = Pdf::loadView(
            'live-audit.pdf',
            compact('liveAudit', 'checklistsBySection')
        )
            ->setPaper('a4', 'portrait');

        return $pdf->download('live-audit-' . $liveAudit->id . '.pdf');
    }

    public function destroy(LiveAudit $liveAudit)
    {
        abort_if(Gate::denies('live_audit.create'), 403);
        $liveAudit->delete();
        return redirect()->route('live-audit.index')
            ->with('success', 'Live Audit berhasil dihapus.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\HiradcDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;

class HiradcController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('hiradc.view'), 403);
        $documents = HiradcDocument::with('uploader')
            ->latest()
            ->paginate(10);
        return view('hiradc.index', compact('documents'));
    }

    public function create()
    {
        $this->authorize('hiradc.create');
        return view('hiradc.create');
    }

    public function store(Request $request)
    {
        $this->authorize('hiradc.create');

        $validated = $request->validate([
            'judul'            => 'required|string|max:255',
            'unit'             => 'nullable|string|max:255',
            'divisi'           => 'nullable|string|max:255',
            'area_lokasi'      => 'nullable|string|max:255',
            'penanggung_jawab' => 'nullable|string|max:255',
            'file'             => 'required|file|mimes:pdf,xlsx,xls|max:10240',
        ]);

        $filePath = $request->file('file')->store('hiradc', 'public');

        HiradcDocument::create([
            'uploaded_by'      => Auth::id(),
            'judul'            => $validated['judul'],
            'unit'             => $validated['unit'],
            'divisi'           => $validated['divisi'],
            'area_lokasi'      => $validated['area_lokasi'],
            'penanggung_jawab' => $validated['penanggung_jawab'],
            'file_path'        => $filePath,
            'status'           => 'pending_v1',
        ]);

        return redirect()->route('hiradc.index')
            ->with('success', 'Dokumen HIRADC berhasil diupload dan menunggu validasi.');
    }

    public function show(HiradcDocument $hiradc)
    {
        abort_if(Gate::denies('hiradc.view'), 403);
        $hiradc->load(['uploader', 'validatorV1', 'validatorV2', 'programKerja']);
        return view('hiradc.show', compact('hiradc'));
    }

    public function validateV1(Request $request, HiradcDocument $hiradc)
    {
        $this->authorize('hiradc.validate_v1');

        $request->validate([
            'action'            => 'required|in:approve,reject',
            'catatan_penolakan' => 'nullable|string',
        ]);

        if ($request->action === 'approve') {
            $hiradc->update([
                'status'           => 'pending_v2',
                'validated_by_v1'  => Auth::id(),
                'validated_at_v1'  => now(),
                'catatan_penolakan' => null,
            ]);
            $message = 'Dokumen HIRADC disetujui oleh Validator 1.';
        } else {
            $hiradc->update([
                'status'            => 'rejected',
                'validated_by_v1'   => Auth::id(),
                'validated_at_v1'   => now(),
                'catatan_penolakan' => $request->catatan_penolakan,
            ]);
            $message = 'Dokumen HIRADC ditolak.';
        }

        return redirect()->route('hiradc.show', $hiradc)
            ->with('success', $message);
    }

    public function validateV2(Request $request, HiradcDocument $hiradc)
    {
        $this->authorize('hiradc.validate_v2');

        $request->validate([
            'action'            => 'required|in:approve,reject',
            'catatan_penolakan' => 'nullable|string',
        ]);

        if ($request->action === 'approve') {
            $hiradc->update([
                'status'          => 'approved',
                'validated_by_v2' => Auth::id(),
                'validated_at_v2' => now(),
                'catatan_penolakan' => null,
            ]);
            $message = 'Dokumen HIRADC telah disetujui sepenuhnya.';
        } else {
            $hiradc->update([
                'status'            => 'rejected',
                'validated_by_v2'   => Auth::id(),
                'validated_at_v2'   => now(),
                'catatan_penolakan' => $request->catatan_penolakan,
            ]);
            $message = 'Dokumen HIRADC ditolak.';
        }

        return redirect()->route('hiradc.show', $hiradc)
            ->with('success', $message);
    }

    public function destroy(HiradcDocument $hiradc)
    {
        $this->authorize('hiradc.create');
        Storage::disk('public')->delete($hiradc->file_path);
        $hiradc->delete();
        return redirect()->route('hiradc.index')
            ->with('success', 'Dokumen HIRADC berhasil dihapus.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\ProgramKerja;
use App\Models\ProgramKerjaBukti;
use App\Models\HiradcDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class ProgramKerjaController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('program_kerja.view'), 403);

        $programKerja = ProgramKerja::with(['hiradc', 'creator'])
            ->latest()
            ->paginate(10);

        // Auto update status overdue
        $programKerja->each(fn($p) => $p->checkAndUpdateStatus());

        return view('program-kerja.index', compact('programKerja'));
    }

    public function create(Request $request)
    {
        abort_if(Gate::denies('program_kerja.create'), 403);

        $hiradcId = $request->query('hiradc_id');
        $aspekId  = $request->query('aspek_id');

        $hiradc = HiradcDocument::findOrFail($hiradcId);
        $aspek  = $aspekId
            ? \App\Models\HiradcAspekBahaya::find($aspekId)
            : null;

        return view('program-kerja.create', compact('hiradc', 'aspek'));
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('program_kerja.create'), 403);

        $validated = $request->validate([
            'hiradc_id'           => 'required|exists:hiradc_documents,id',
            'aspek_bahaya_id'     => 'nullable|exists:hiradc_aspek_bahaya,id',
            'nama_program'        => 'required|string|max:255',
            'pengendalian_risiko' => 'nullable|string',
            'pic'                 => 'required|string|max:255',
            'deadline'            => 'required|date|after:today',
        ]);

        ProgramKerja::create([
            ...$validated,
            'created_by' => Auth::id(),
            'status'     => 'open',
        ]);

        return redirect()->route('hiradc.show', $validated['hiradc_id'])
            ->with('success', 'Program kerja berhasil ditambahkan.');
    }

    public function show(ProgramKerja $programKerja)
    {
        abort_if(Gate::denies('program_kerja.view'), 403);

        $programKerja->checkAndUpdateStatus();
        $programKerja->load(['hiradc', 'creator', 'bukti.uploader']);

        return view('program-kerja.show', compact('programKerja'));
    }

    public function uploadBukti(Request $request, ProgramKerja $programKerja)
    {
        abort_if(Gate::denies('program_kerja.upload_bukti'), 403);

        $request->validate([
            'foto'       => 'required|image|mimes:jpg,jpeg,png|max:5120',
            'keterangan' => 'required|string|max:500',
        ]);

        $fotoPath = $request->file('foto')->store('program-kerja-bukti', 'public');

        ProgramKerjaBukti::create([
            'program_kerja_id' => $programKerja->id,
            'uploaded_by'      => Auth::id(),
            'foto_path'        => $fotoPath,
            'keterangan'       => $request->keterangan,
        ]);

        // Update status ke on_progress jika masih open
        if ($programKerja->status === 'open') {
            $programKerja->update(['status' => 'on_progress']);
        }

        return redirect()->route('program-kerja.show', $programKerja)
            ->with('success', 'Bukti pelaksanaan berhasil diupload.');
    }

    public function close(Request $request, ProgramKerja $programKerja)
    {
        abort_if(Gate::denies('program_kerja.close'), 403);

        if ($programKerja->bukti->isEmpty()) {
            return redirect()->route('program-kerja.show', $programKerja)
                ->with('error', 'Program kerja tidak bisa di-close tanpa bukti pelaksanaan.');
        }

        $programKerja->update(['status' => 'closed']);

        return redirect()->route('program-kerja.show', $programKerja)
            ->with('success', 'Program kerja berhasil di-close.');
    }

    public function edit(ProgramKerja $programKerja)
    {
        abort_if(Gate::denies('program_kerja.create'), 403);
        return view('program-kerja.edit', compact('programKerja'));
    }

    public function update(Request $request, ProgramKerja $programKerja)
    {
        abort_if(Gate::denies('program_kerja.create'), 403);

        $validated = $request->validate([
            'nama_program'        => 'required|string|max:255',
            'pengendalian_risiko' => 'nullable|string',
            'pic'                 => 'required|string|max:255',
            'deadline'            => 'required|date',
        ]);

        $programKerja->update($validated);

        return redirect()->route('program-kerja.show', $programKerja)
            ->with('success', 'Program kerja berhasil diupdate.');
    }

    public function destroy(ProgramKerja $programKerja)
    {
        abort_if(Gate::denies('program_kerja.create'), 403);

        foreach ($programKerja->bukti as $bukti) {
            Storage::disk('public')->delete($bukti->foto_path);
        }

        $programKerja->delete();

        return redirect()->route('program-kerja.index')
            ->with('success', 'Program kerja berhasil dihapus.');
    }
}

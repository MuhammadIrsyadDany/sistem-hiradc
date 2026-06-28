<?php

namespace App\Http\Controllers;

use App\Models\HiradcDocument;
use App\Models\HiradcAktivitas;
use App\Models\HiradcAspekBahaya;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class HiradcController extends Controller
{
    // ================================================================
    // HIRADC DOCUMENT
    // ================================================================

    public function index(Request $request)
    {
        abort_if(Gate::denies('hiradc.view'), 403);

        $query = HiradcDocument::with([
            'uploader',
            'aktivitas.aspekBahaya',
        ]);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('nama_area', 'like', "%{$search}%")
                  ->orWhere('unit', 'like', "%{$search}%")
                  ->orWhere('divisi', 'like', "%{$search}%")
                  ->orWhere('area_lokasi', 'like', "%{$search}%")
                  ->orWhere('penanggung_jawab', 'like', "%{$search}%")
                  ->orWhere('no_dokumen', 'like', "%{$search}%")
                  ->orWhere('tahun', 'like', "%{$search}%");
            });
        }

        $documents = $query->latest()
            ->paginate(10)
            ->withQueryString();

        return view('hiradc.index', compact('documents'));
    }

    public function create()
    {
        abort_if(Gate::denies('hiradc.create'), 403);

        return view('hiradc.create');
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('hiradc.create'), 403);

        $validated = $request->validate([
            'nama_area'        => 'required|string|max:255',
            'unit'             => 'nullable|string|max:255',
            'divisi'           => 'nullable|string|max:255',
            'area_lokasi'      => 'nullable|string|max:255',
            'penanggung_jawab' => 'nullable|string|max:255',
            'no_dokumen'       => 'nullable|string|max:100',
            'tahun'            => 'nullable|string|max:10',
            'file'             => 'required|file|mimes:pdf,xlsx,xls|max:10240',
        ]);

        $filePath = $request->file('file')->store('hiradc', 'public');

        $hiradc = HiradcDocument::create([
            'uploaded_by'      => Auth::id(),
            'nama_area'        => $validated['nama_area'],
            'unit'             => $validated['unit'],
            'divisi'           => $validated['divisi'],
            'area_lokasi'      => $validated['area_lokasi'],
            'penanggung_jawab' => $validated['penanggung_jawab'],
            'no_dokumen'       => $validated['no_dokumen'],
            'tahun'            => $validated['tahun'],
            'file_path'        => $filePath,
        ]);

        return redirect()->route('hiradc.show', $hiradc)
            ->with('success', 'Dokumen HIRADC berhasil diupload. Silakan tambahkan aktivitas dan aspek bahaya.');
    }

    public function show(HiradcDocument $hiradc)
    {
        abort_if(Gate::denies('hiradc.view'), 403);

        $hiradc->load([
            'uploader',
            'aktivitas.aspekBahaya.programKerja.bukti',
            'programKerja',
        ]);

        $levelOptions = $this->getLevelOptions();
        $sumberOptions = $this->getSumberOptions();
        $kondisiOptions = $this->getKondisiOptions();

        return view('hiradc.show', compact(
            'hiradc',
            'levelOptions',
            'sumberOptions',
            'kondisiOptions',
        ));
    }

    public function destroy(HiradcDocument $hiradc)
    {
        abort_if(Gate::denies('hiradc.create'), 403);

        Storage::disk('public')->delete($hiradc->file_path);
        $hiradc->delete();

        return redirect()->route('hiradc.index')
            ->with('success', 'Dokumen HIRADC berhasil dihapus.');
    }

    // ================================================================
    // AKTIVITAS
    // ================================================================

    public function storeAktivitas(Request $request, HiradcDocument $hiradc)
    {
        abort_if(Gate::denies('hiradc.create'), 403);

        $validated = $request->validate([
            'nama_aktivitas' => 'required|string|max:255',
            'sumber_bahaya'  => 'required|in:aktivitas,peralatan,lingkungan_kerja,proses',
            'kondisi'        => 'required|in:rutin,non_rutin,abnormal,darurat',
        ]);

        $urutan = $hiradc->aktivitas()->max('urutan') + 1;

        HiradcAktivitas::create([
            'hiradc_id'      => $hiradc->id,
            'nama_aktivitas' => $validated['nama_aktivitas'],
            'sumber_bahaya'  => $validated['sumber_bahaya'],
            'kondisi'        => $validated['kondisi'],
            'urutan'         => $urutan,
        ]);

        return redirect()->route('hiradc.show', $hiradc)
            ->with('success', 'Aktivitas berhasil ditambahkan.');
    }

    public function destroyAktivitas(HiradcDocument $hiradc, HiradcAktivitas $aktivitas)
    {
        abort_if(Gate::denies('hiradc.create'), 403);

        $aktivitas->delete();

        return redirect()->route('hiradc.show', $hiradc)
            ->with('success', 'Aktivitas berhasil dihapus.');
    }

    // ================================================================
    // ASPEK BAHAYA
    // ================================================================

    public function storeAspekBahaya(Request $request, HiradcAktivitas $aktivitas)
    {
        abort_if(Gate::denies('hiradc.create'), 403);

        $validated = $request->validate([
            'potensi_aspek_lingkungan' => 'nullable|string',
            'potensi_bahaya_k3'        => 'required|string',
            'peraturan_terkait'        => 'nullable|string',
            'pengendalian_existing'    => 'nullable|string',
            'level_risiko'             => 'required|in:rendah,moderat,tinggi,sangat_tinggi,ekstrim',
        ]);

        HiradcAspekBahaya::create([
            'aktivitas_id'             => $aktivitas->id,
            'potensi_aspek_lingkungan' => $validated['potensi_aspek_lingkungan'],
            'potensi_bahaya_k3'        => $validated['potensi_bahaya_k3'],
            'peraturan_terkait'        => $validated['peraturan_terkait'],
            'pengendalian_existing'    => $validated['pengendalian_existing'],
            'level_risiko'             => $validated['level_risiko'],
        ]);

        return redirect()->route('hiradc.show', $aktivitas->hiradc)
            ->with('success', 'Aspek bahaya berhasil ditambahkan.');
    }

    public function destroyAspekBahaya(HiradcAspekBahaya $aspek)
    {
        abort_if(Gate::denies('hiradc.create'), 403);

        $hiradc = $aspek->aktivitas->hiradc;
        $aspek->delete();

        return redirect()->route('hiradc.show', $hiradc)
            ->with('success', 'Aspek bahaya berhasil dihapus.');
    }

    public function updateLevelRisikoAkhir(Request $request, HiradcAspekBahaya $aspek)
    {
        abort_if(Gate::denies('hiradc.create'), 403);

        $request->validate([
            'level_risiko_akhir' => 'required|in:rendah,moderat,tinggi,sangat_tinggi,ekstrim',
        ]);

        $aspek->update([
            'level_risiko_akhir' => $request->level_risiko_akhir,
        ]);

        return redirect()->route('hiradc.show', $aspek->aktivitas->hiradc)
            ->with('success', 'Level risiko akhir berhasil diupdate.');
    }

    // ================================================================
    // HELPERS
    // ================================================================

    private function getLevelOptions(): array
    {
        return [
            'rendah'        => 'Rendah',
            'moderat'       => 'Moderat',
            'tinggi'        => 'Tinggi',
            'sangat_tinggi' => 'Sangat Tinggi',
            'ekstrim'       => 'Ekstrim',
        ];
    }

    private function getSumberOptions(): array
    {
        return [
            'aktivitas'        => 'Aktivitas',
            'peralatan'        => 'Peralatan',
            'lingkungan_kerja' => 'Lingkungan Kerja',
            'proses'           => 'Proses',
        ];
    }

    private function getKondisiOptions(): array
    {
        return [
            'rutin'     => 'Rutin',
            'non_rutin' => 'Non Rutin',
            'abnormal'  => 'Abnormal',
            'darurat'   => 'Darurat',
        ];
    }
}

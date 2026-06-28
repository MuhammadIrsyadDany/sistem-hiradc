<?php

namespace App\Http\Controllers;

use App\Models\Temuan;
use App\Models\TemuanFoto;
use App\Models\TemuanBuktiPerbaikan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class TemuanController extends Controller
{
    // ===================================================================
    // KONSTANTA
    // ===================================================================

    private const KATEGORI_LIST = [
        'unsafe_action'    => 'Unsafe Action',
        'unsafe_condition' => 'Unsafe Condition',
        'near_miss'        => 'Near Miss',
        'positive'         => 'Positive',
    ];

    private const VALID_KATEGORI = [
        'unsafe_action',
        'unsafe_condition',
        'near_miss',
        'positive',
    ];

    private const GEMINI_ENDPOINT = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent';

    // ===================================================================
    // CRUD UTAMA
    // ===================================================================

    public function index(Request $request)
    {
        abort_if(Gate::denies('temuan.view'), 403);

        $query = Temuan::with(['reporter', 'fotos'])->latest();

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul_temuan', 'like', "%{$search}%")
                  ->orWhere('lokasi', 'like', "%{$search}%")
                  ->orWhere('pic', 'like', "%{$search}%")
                  ->orWhere('distrik', 'like', "%{$search}%")
                  ->orWhere('kondisi', 'like', "%{$search}%");
            });
        }

        $temuans = $query->paginate(10)->withQueryString();

        return view('temuan.index', compact('temuans'));
    }

    public function exportKolektif(Request $request)
    {
        abort_if(Gate::denies('temuan.view'), 403);

        $request->validate([
            'bulan'    => 'required|integer|between:1,12',
            'tahun'    => 'required|integer|min:2020|max:' . (date('Y') + 5),
            'kategori' => 'nullable|string',
            'status'   => 'nullable|string',
        ]);

        $query = Temuan::with(['reporter', 'closedBy'])
            ->whereYear('created_at', $request->tahun)
            ->whereMonth('created_at', $request->bulan);

        if ($request->kategori && $request->kategori !== 'all') {
            $query->where('kategori', $request->kategori);
        }

        if ($request->status && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $temuans = $query->latest()->get();

        $bulanIndo = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        $namaBulan = $bulanIndo[$request->bulan] ?? '';
        $kategoriLabel = self::KATEGORI_LIST[$request->kategori] ?? 'Semua Kategori';
        $statusLabel = ucfirst(str_replace('_', ' ', $request->status ?? 'semua'));

        $pdf = Pdf::loadView('temuan.pdf-kolektif', [
            'temuans'       => $temuans,
            'bulan'         => $request->bulan,
            'namaBulan'     => $namaBulan,
            'tahun'         => $request->tahun,
            'kategori'      => $request->kategori,
            'kategoriLabel' => $kategoriLabel,
            'status'        => $request->status,
            'statusLabel'   => $statusLabel,
        ])->setPaper('a4', 'landscape');

        return $pdf->download("laporan-kolektif-temuan-{$namaBulan}-{$request->tahun}.pdf");
    }

    public function create()
    {
        abort_if(Gate::denies('temuan.create'), 403);

        $kategoriList = self::KATEGORI_LIST;

        return view('temuan.create', compact('kategoriList'));
    }

    public function store(Request $request)
    {
        abort_if(Gate::denies('temuan.create'), 403);

        $validated = $request->validate($this->storeRules());

        [$aiKategori, $aiConfidence] = $this->getAIClassification(
            $validated['judul_temuan']
        );

        $temuan = Temuan::create([
            'reported_by'       => Auth::id(),
            'distrik'           => $validated['distrik'],
            'judul_temuan'      => $validated['judul_temuan'],
            'kondisi'           => $validated['kondisi'],
            'tindak_lanjut'     => $validated['tindak_lanjut'],
            'rekomendasi'       => $validated['rekomendasi'],
            'pic'               => $validated['pic'],
            'lokasi'            => $validated['lokasi'],
            'keterangan_lokasi' => $validated['keterangan_lokasi'],
            'kategori'          => $validated['kategori'],
            'ai_kategori'       => $aiKategori,
            'ai_confidence'     => $aiConfidence,
            'status'            => 'open',
        ]);

        $this->saveFotos($request, $temuan->id);

        return redirect()->route('temuan.show', $temuan)
            ->with('success', 'Temuan berhasil dilaporkan.');
    }

    public function show(Temuan $temuan)
    {
        abort_if(Gate::denies('temuan.view'), 403);

        $temuan->load([
            'reporter',
            'validatorV1',
            'validatorV2',
            'closedBy',
            'fotos',
            'buktiPerbaikan.uploader',
            'liveAudit',
        ]);

        return view('temuan.show', compact('temuan'));
    }

    // ===================================================================
    // DRAFT
    // ===================================================================

    public function completeDraft(Request $request, Temuan $temuan)
    {
        abort_if(Gate::denies('temuan.create'), 403);
        abort_if(!$temuan->isDraft(), 403);

        $validated = $request->validate($this->draftRules());

        [$aiKategori, $aiConfidence] = $this->getAIClassification(
            $temuan->judul_temuan
        );

        $temuan->update([
            'kondisi'           => $validated['kondisi'],
            'tindak_lanjut'     => $validated['tindak_lanjut'],
            'rekomendasi'       => $validated['rekomendasi'],
            'pic'               => $validated['pic'],
            'keterangan_lokasi' => $validated['keterangan_lokasi'],
            'ai_kategori'       => $aiKategori,
            'ai_confidence'     => $aiConfidence,
            'status'            => 'open',
        ]);

        $this->saveFotos($request, $temuan->id);

        return redirect()->route('temuan.show', $temuan)
            ->with('success', 'Draft temuan berhasil dilengkapi dan siap divalidasi.');
    }

    // ===================================================================
    // VALIDASI
    // ===================================================================

    public function validateV1(Request $request, Temuan $temuan)
    {
        abort_if(Gate::denies('temuan.validate_v1'), 403);

        $request->validate(['action' => 'required|in:approve,reject']);

        if ($request->action === 'approve') {
            $temuan->update([
                'status'          => 'validated_v1',
                'validated_by_v1' => Auth::id(),
                'validated_at_v1' => now(),
            ]);
            $message = 'Temuan divalidasi oleh Validator 1.';
        } else {
            $temuan->update([
                'status'          => 'open',
                'validated_by_v1' => null,
                'validated_at_v1' => null,
            ]);
            $message = 'Temuan dikembalikan untuk diperbaiki.';
        }

        return redirect()->route('temuan.show', $temuan)
            ->with('success', $message);
    }

    public function validateV2(Request $request, Temuan $temuan)
    {
        abort_if(Gate::denies('temuan.validate_v2'), 403);

        $request->validate(['action' => 'required|in:approve,reject']);

        if ($request->action === 'approve') {
            $temuan->update([
                'status'          => 'validated_v2',
                'validated_by_v2' => Auth::id(),
                'validated_at_v2' => now(),
            ]);
            $message = 'Temuan divalidasi oleh Validator 2.';
        } else {
            $temuan->update([
                'status'          => 'validated_v1',
                'validated_by_v2' => null,
                'validated_at_v2' => null,
            ]);
            $message = 'Temuan dikembalikan ke Validator 1.';
        }

        return redirect()->route('temuan.show', $temuan)
            ->with('success', $message);
    }

    // ===================================================================
    // BUKTI & CLOSE
    // ===================================================================

    public function uploadBukti(Request $request, Temuan $temuan)
    {
        abort_if(Gate::denies('temuan.close') && Auth::id() !== $temuan->reported_by, 403);

        $request->validate([
            'foto'       => 'required|image|mimes:jpg,jpeg,png|max:5120',
            'keterangan' => 'required|string|max:500',
        ]);

        $path = $request->file('foto')->store('temuan-bukti', 'public');

        TemuanBuktiPerbaikan::create([
            'temuan_id'   => $temuan->id,
            'uploaded_by' => Auth::id(),
            'foto_path'   => $path,
            'keterangan'  => $request->keterangan,
        ]);

        return redirect()->route('temuan.show', $temuan)
            ->with('success', 'Bukti perbaikan berhasil diupload.');
    }

    public function close(Request $request, Temuan $temuan)
    {
        abort_if(Gate::denies('temuan.close') && Auth::id() !== $temuan->reported_by, 403);

        if ($temuan->buktiPerbaikan->isEmpty()) {
            return redirect()->route('temuan.show', $temuan)
                ->with('error', 'Temuan tidak bisa di-close tanpa bukti perbaikan.');
        }

        $temuan->update([
            'status'    => 'closed',
            'closed_by' => Auth::id(),
            'closed_at' => now(),
        ]);

        return redirect()->route('temuan.show', $temuan)
            ->with('success', 'Temuan berhasil di-close.');
    }

    // ===================================================================
    // HELPER METHODS
    // ===================================================================

    /**
     * Simpan multiple foto temuan
     */
    private function saveFotos(Request $request, int $temuanId): void
    {
        if (!$request->hasFile('fotos')) return;

        foreach ($request->file('fotos') as $foto) {
            $path = $foto->store('temuan-fotos', 'public');
            TemuanFoto::create([
                'temuan_id' => $temuanId,
                'foto_path' => $path,
            ]);
        }
    }

    /**
     * Ambil hasil klasifikasi AI, return [kategori, confidence]
     */
    private function getAIClassification(string $judulTemuan): array
    {
        try {
            $result = $this->classifyWithAI($judulTemuan);
            return [
                $result['kategori'] ?? null,
                $result['confidence'] ?? null,
            ];
        } catch (\Exception $e) {
            Log::error('AI Classification error: ' . $e->getMessage());
            return [null, null];
        }
    }

    /**
     * Klasifikasi temuan menggunakan Gemini AI
     */
    private function classifyWithAI(string $judulTemuan): array
    {
        $apiKey = config('services.gemini.key');

        if (empty($apiKey)) {
            Log::warning('GEMINI_API_KEY kosong, AI skip.');
            return ['kategori' => null, 'confidence' => null];
        }

        $response = Http::withHeaders(['Content-Type' => 'application/json'])
            ->timeout(10)
            ->post(self::GEMINI_ENDPOINT . "?key={$apiKey}", [
                'contents' => [[
                    'parts' => [[
                        'text' => $this->buildAIPrompt($judulTemuan),
                    ]],
                ]],
                'generationConfig' => [
                    'temperature'     => 0.1,
                    'maxOutputTokens' => 100,
                ],
            ]);

        Log::info('Gemini status: ' . $response->status());
        Log::info('Gemini body: '   . $response->body());

        if (!$response->successful()) {
            Log::error('Gemini gagal: ' . $response->body());
            return ['kategori' => null, 'confidence' => null];
        }

        return $this->parseAIResponse(
            $response->json('candidates.0.content.parts.0.text')
        );
    }

    /**
     * Buat prompt untuk AI
     */
    private function buildAIPrompt(string $judulTemuan): string
    {
        return "Klasifikasikan temuan K3 berikut ke dalam salah satu kategori: "
            . implode(', ', self::VALID_KATEGORI)
            . ". Berikan confidence score antara 0 sampai 1. "
            . "Jawab HANYA dalam format JSON tanpa markdown: "
            . "{\"kategori\": \"...\", \"confidence\": 0.0}. "
            . "Temuan: \"{$judulTemuan}\"";
    }

    /**
     * Parse dan validasi response AI
     */
    private function parseAIResponse(?string $text): array
    {
        $default = ['kategori' => null, 'confidence' => null];

        if (empty($text)) return $default;

        // Bersihkan markdown jika ada
        $text   = preg_replace('/```json|```/', '', $text);
        $parsed = json_decode(trim($text), true);

        Log::info('Gemini parsed: ' . json_encode($parsed));

        if (
            !is_array($parsed)
            || !isset($parsed['kategori'])
            || !isset($parsed['confidence'])
        ) {
            return $default;
        }

        if (!in_array($parsed['kategori'], self::VALID_KATEGORI)) {
            return $default;
        }

        return [
            'kategori'   => $parsed['kategori'],
            'confidence' => (float) $parsed['confidence'],
        ];
    }

    // ===================================================================
    // VALIDATION RULES
    // ===================================================================

    private function storeRules(): array
    {
        return [
            'distrik'           => 'required|string|max:255',
            'judul_temuan'      => 'required|string|max:255',
            'kondisi'           => 'nullable|string|max:255',
            'tindak_lanjut'     => 'nullable|string',
            'rekomendasi'       => 'nullable|string',
            'pic'               => 'nullable|string|max:255',
            'lokasi'            => 'required|string|max:255',
            'keterangan_lokasi' => 'nullable|string|max:255',
            'kategori'          => 'required|in:' . implode(',', self::VALID_KATEGORI),
            'fotos'             => 'required|array|min:1',
            'fotos.*'           => 'image|mimes:jpg,jpeg,png|max:5120',
        ];
    }

    private function draftRules(): array
    {
        return [
            'kondisi'           => 'required|string|max:255',
            'tindak_lanjut'     => 'nullable|string',
            'rekomendasi'       => 'nullable|string',
            'pic'               => 'nullable|string|max:255',
            'keterangan_lokasi' => 'nullable|string|max:255',
            'fotos'             => 'required|array|min:1',
            'fotos.*'           => 'image|mimes:jpg,jpeg,png|max:5120',
        ];
    }
}

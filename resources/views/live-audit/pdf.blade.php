<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .header-table td {
            padding: 4px;
        }

        h3 {
            text-align: center;
            font-size: 12px;
            margin: 5px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        table th,
        table td {
            border: 1px solid #000;
            padding: 4px;
        }

        table th {
            background: #f0f0f0;
            text-align: center;
        }

        .section-header {
            background: #ddd;
            font-weight: bold;
        }

        .text-center {
            text-align: center;
        }

        .footer-table td {
            padding: 8px;
            vertical-align: top;
        }

        .signature-box {
            border-top: 1px solid #000;
            margin-top: 40px;
        }
    </style>
</head>

<body>
    {{-- Header --}}
    <table class="header-table" style="border: 1px solid #000;">
        <tr>
            <td width="15%" rowspan="2">
                <strong>PLN<br>Nusantara Power</strong>
            </td>
            <td width="55%" style="text-align:center;">
                <strong>PT. PLN NUSANTARA POWER</strong><br>
                INTEGRATED MANAGEMENT SYSTEM<br>
                <strong>FORMULIR LIVE AUDIT/WORK IN PRACTISE</strong>
            </td>
            <td width="30%">
                No Dokumen: ___________<br>
                Tanggal Berlaku: ___________<br>
                Revisi: ___________<br>
                Halaman: 1
            </td>
        </tr>
    </table>

    {{-- Info Pekerjaan --}}
    <table style="border: 1px solid #000;">
        <tr>
            <td width="20%">Diminta Oleh</td>
            <td width="30%">{{ $liveAudit->diminta_oleh ?? '-' }}</td>
            <td width="20%">Rencana Pekerjaan</td>
            <td width="30%">
                Tgl: {{ $liveAudit->tanggal_mulai->format('d M Y') }}
                sd Tgl: {{ $liveAudit->tanggal_selesai->format('d M Y') }}
            </td>
        </tr>
        <tr>
            <td>Untuk Melaksanakan Pekerjaan</td>
            <td>{{ $liveAudit->nama_pekerjaan }}</td>
            <td>No Work Order</td>
            <td>{{ $liveAudit->no_work_order ?? '-' }}</td>
        </tr>
        <tr>
            <td>di lokasi (Unit/daerah)</td>
            <td>{{ $liveAudit->lokasi }}</td>
            <td>Perusahaan/Bidang</td>
            <td>{{ $liveAudit->perusahaan }}</td>
        </tr>
    </table>

    {{-- Checklist --}}
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="60%">Daftar Periksa (Bidang K3)</th>
                <th width="10%">TIDAK</th>
                <th width="10%">YA</th>
                <th width="10%">NA</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($checklistsBySection as $section => $checklists)
                @if ($section !== 'Umum')
                    <tr>
                        <td colspan="5" class="section-header">{{ $section }}</td>
                    </tr>
                @endif
                @foreach ($checklists as $checklist)
                    <tr>
                        <td class="text-center">
                            {{ $checklist->checklistItem->nomor_item }}
                        </td>
                        <td>
                            {{ $checklist->checklistItem->deskripsi }}
                            @if ($checklist->checklistItem->is_critical)
                                (*)
                            @endif
                        </td>
                        <td class="text-center">
                            {{ $checklist->jawaban === 'tidak' ? 'V' : '' }}
                        </td>
                        <td class="text-center">
                            {{ $checklist->jawaban === 'ya' ? 'V' : '' }}
                        </td>
                        <td class="text-center">
                            {{ $checklist->jawaban === 'na' ? 'V' : '' }}
                        </td>
                    </tr>
                @endforeach
            @endforeach
            <tr>
                <td colspan="2" style="text-align:right; font-weight:bold;">Total Nilai</td>
                <td class="text-center font-weight-bold">
                    {{ $liveAudit->checklists->where('jawaban', 'tidak')->count() }}
                </td>
                <td class="text-center font-weight-bold">
                    {{ $liveAudit->checklists->where('jawaban', 'ya')->count() }}
                </td>
                <td class="text-center font-weight-bold">
                    {{ $liveAudit->checklists->where('jawaban', 'na')->count() }}
                </td>
            </tr>
        </tbody>
    </table>

    {{-- Temuan --}}
    <table>
        <tr>
            <td width="50%">
                <strong>Temuan Unsafe Action</strong><br>
                {{ $liveAudit->unsafe_action_text ?? 'tidak ada' }}
            </td>
            <td width="50%">
                <strong>Temuan Unsafe Condition</strong><br>
                {{ $liveAudit->unsafe_condition_text ?? 'tidak ada' }}
            </td>
        </tr>
    </table>

    {{-- Working Permit --}}
    <table>
        <tr>
            <td colspan="2"><strong>Working Permit Pekerjaan</strong></td>
        </tr>
        <tr>
            <td>
                @foreach (['hot_work' => 'Hot Work', 'confined_space' => 'Confined Space', 'working_at_height' => 'Working At Height', 'excavation' => 'Excavation', 'isolasi' => 'Isolasi'] as $key => $label)
                    [{{ in_array($key, $liveAudit->working_permit ?? []) ? 'X' : '  ' }}] {{ $label }}<br>
                @endforeach
            </td>
            <td>
                @foreach (['vicinity' => 'Vicinity', 'near_and_underwater' => 'Near And Underwater', 'lifting' => 'Lifting', 'radiation' => 'Radiation', 'chemical_handling' => 'Chemical Handling'] as $key => $label)
                    [{{ in_array($key, $liveAudit->working_permit ?? []) ? 'X' : '  ' }}] {{ $label }}<br>
                @endforeach
            </td>
        </tr>
    </table>

    {{-- Tanda Tangan --}}
    <table class="footer-table">
        <tr>
            <td width="50%" class="text-center">
                1. Bidang K3<br>
                @if ($liveAudit->validatorV1 && $liveAudit->validatorV1->signature_path)
                    <img src="{{ public_path('storage/' . $liveAudit->validatorV1->signature_path) }}" style="height: 55px; max-width: 140px; object-fit: contain; margin: 5px 0;">
                @else
                    <br><br><br>
                @endif
                <div class="signature-box">
                    {{ $liveAudit->validatorV1?->name ?? '________________________' }}<br>
                    <small>Date/Name & Signature</small>
                </div>
            </td>
            <td width="50%" class="text-center">
                2. Pengawas K3 / Pengawas Pekerjaan<br>
                @if ($liveAudit->validatorV2 && $liveAudit->validatorV2->signature_path)
                    <img src="{{ public_path('storage/' . $liveAudit->validatorV2->signature_path) }}" style="height: 55px; max-width: 140px; object-fit: contain; margin: 5px 0;">
                @else
                    <br><br><br>
                @endif
                <div class="signature-box">
                    {{ $liveAudit->validatorV2?->name ?? '________________________' }}<br>
                    <small>Date/Name & Signature</small>
                </div>
            </td>
        </tr>
    </table>

    @if ($liveAudit->is_stopped)
        <table style="border: 2px solid red; margin-top: 10px;">
            <tr>
                <td style="color:red; font-weight:bold; text-align:center;">
                    PEKERJAAN DI-STOP
                </td>
            </tr>
            <tr>
                <td>Alasan: {{ $liveAudit->stop_alasan }}</td>
            </tr>
        </table>
    @endif

    {{-- Lampiran Foto Dokumentasi Kerja --}}
    @if ($liveAudit->fotos->isNotEmpty())
        <div style="page-break-before: always; margin-top: 15px;">
            <strong>FOTO DOKUMENTASI KERJA:</strong><br><br>
            <table style="border: none; width: 100%;">
                @foreach ($liveAudit->fotos->chunk(2) as $row)
                    <tr>
                        @foreach ($row as $foto)
                            <td style="border: none; padding: 10px; text-align: center; width: 50%;">
                                <img src="{{ public_path('storage/' . $foto->foto_path) }}" style="max-width: 100%; height: 180px; object-fit: cover; border: 1px solid #ccc; border-radius: 4px;">
                            </td>
                        @endforeach
                        @if ($row->count() < 2)
                            <td style="border: none; width: 50%;"></td>
                        @endif
                    </tr>
                @endforeach
            </table>
        </div>
    @endif
</body>

</html>

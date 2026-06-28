@extends('adminlte::page')
@section('title', 'Detail Live Audit')

@section('content_header')
    <x-page-header title="Detail Live Audit" subtitle="Work In Practise — hasil pemeriksaan keselamatan"
        icon="fas fa-clipboard-check" backUrl="{{ route('live-audit.index') }}">
        @if ($liveAudit->status === 'approved')
            <a href="{{ route('live-audit.export-pdf', $liveAudit) }}" class="btn btn-danger">
                <i class="fas fa-file-pdf mr-1"></i> Export PDF
            </a>
        @endif
    </x-page-header>
@endsection

@section('content')
    @if (session('success'))
        <x-alert type="success">{{ session('success') }}</x-alert>
    @endif

    {{-- Banner STOP --}}
    @if ($liveAudit->is_stopped)
        <div
            style="background:linear-gradient(135deg,#f8d7da,#f5c6cb);
                    border:2px solid #dc3545; border-radius:12px;
                    padding:16px 20px; margin-bottom:20px;
                    display:flex; justify-content:space-between; align-items:center; gap:14px;">
            <div style="display:flex; align-items:center; gap:14px;">
                <div
                    style="width:48px; height:48px; border-radius:12px;
                            background:#dc3545; display:flex; align-items:center;
                            justify-content:center; flex-shrink:0;">
                    <i class="fas fa-stop-circle" style="color:#fff; font-size:22px;"></i>
                </div>
                <div>
                    <div
                        style="font-weight:800; color:#721c24; font-size:16px;
                                 text-transform:uppercase; letter-spacing:0.5px;">
                        ⚠ Pekerjaan Di-STOP
                    </div>
                    <div style="font-size:13px; color:#721c24; margin-top:2px;">
                        {{ $liveAudit->stop_alasan }}
                    </div>
                </div>
            </div>
            @can('live_audit.create')
                <form action="{{ route('live-audit.resume', $liveAudit) }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-success btn-sm font-weight-bold">
                        <i class="fas fa-play mr-1"></i> Lanjutkan Pekerjaan
                    </button>
                </form>
            @endcan
        </div>
    @endif

    <div class="row">
        {{-- Kolom Kiri --}}
        <div class="col-md-8">

            {{-- Info Pekerjaan --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-briefcase mr-2" style="color:#006b3f;"></i>
                        Informasi Pekerjaan
                    </h3>
                </div>
                <div class="card-body">
                    {{-- Nama Pekerjaan --}}
                    <div
                        style="background:#f8fafc; border-radius:10px;
                                padding:16px; margin-bottom:20px;">
                        <div
                            style="font-size:16px; font-weight:700;
                                    color:#1a202c; margin-bottom:8px;">
                            {{ $liveAudit->nama_pekerjaan }}
                        </div>
                        <div style="display:flex; gap:8px; flex-wrap:wrap;">
                            {!! $liveAudit->status_badge !!}
                            @if ($liveAudit->no_work_order)
                                <span
                                    style="background:#e8f5ee; color:#006b3f;
                                             font-size:11px; padding:3px 10px;
                                             border-radius:20px; font-weight:600;">
                                    <i class="fas fa-hashtag mr-1"></i>
                                    {{ $liveAudit->no_work_order }}
                                </span>
                            @endif
                            {{-- Skor --}}
                            <span
                                style="background:{{ $liveAudit->score >= 80 ? '#d4edda' : ($liveAudit->score >= 50 ? '#fff3cd' : '#f8d7da') }};
                                         color:{{ $liveAudit->score >= 80 ? '#155724' : ($liveAudit->score >= 50 ? '#856404' : '#721c24') }};
                                         font-size:11px; padding:3px 10px;
                                         border-radius:20px; font-weight:700;">
                                <i class="fas fa-chart-bar mr-1"></i>
                                Skor: {{ $liveAudit->score }}%
                            </span>
                        </div>
                    </div>

                    {{-- Detail Grid --}}
                    <div class="row">
                        @php
                            $details = [
                                [
                                    'icon' => 'fas fa-building',
                                    'label' => 'Perusahaan',
                                    'value' => $liveAudit->perusahaan,
                                ],
                                ['icon' => 'fas fa-map-marker-alt', 'label' => 'Lokasi', 'value' => $liveAudit->lokasi],
                                [
                                    'icon' => 'fas fa-user',
                                    'label' => 'Diminta Oleh',
                                    'value' => $liveAudit->diminta_oleh ?? '-',
                                ],
                                [
                                    'icon' => 'fas fa-user-tie',
                                    'label' => 'Dibuat Oleh',
                                    'value' => $liveAudit->creator->name,
                                ],
                                [
                                    'icon' => 'fas fa-calendar-alt',
                                    'label' => 'Tanggal Mulai',
                                    'value' => $liveAudit->tanggal_mulai->format('d M Y'),
                                ],
                                [
                                    'icon' => 'fas fa-calendar-check',
                                    'label' => 'Tanggal Selesai',
                                    'value' => $liveAudit->tanggal_selesai->format('d M Y'),
                                ],
                            ];
                        @endphp
                        @foreach ($details as $d)
                            <div class="col-md-6 mb-3">
                                <div style="display:flex; gap:12px; align-items:flex-start;">
                                    <div
                                        style="width:34px; height:34px; border-radius:8px;
                                                background:#e8f5ee; display:flex;
                                                align-items:center; justify-content:center;
                                                flex-shrink:0;">
                                        <i class="{{ $d['icon'] }}" style="color:#006b3f; font-size:13px;"></i>
                                    </div>
                                    <div>
                                        <div
                                            style="font-size:10px; color:#a0aec0;
                                                    font-weight:700; text-transform:uppercase;
                                                    letter-spacing:0.5px;">
                                            {{ $d['label'] }}
                                        </div>
                                        <div
                                            style="font-size:13px; font-weight:500;
                                                    color:#2d3748; margin-top:2px;">
                                            {{ $d['value'] }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Skor Progress --}}
                    <div style="background:#f8fafc; border-radius:10px; padding:16px;">
                        <div class="d-flex justify-content-between mb-2">
                            <span style="font-size:13px; font-weight:600; color:#4a5568;">
                                Skor Kepatuhan Checklist
                            </span>
                            <span
                                style="font-size:13px; font-weight:700;
                                         color:{{ $liveAudit->score >= 80 ? '#006b3f' : ($liveAudit->score >= 50 ? '#f0a500' : '#dc3545') }};">
                                {{ $liveAudit->score }}%
                            </span>
                        </div>
                        <div style="background:#e2e8f0; border-radius:20px; height:10px;">
                            <div
                                style="background:{{ $liveAudit->score >= 80 ? 'linear-gradient(90deg,#006b3f,#00a65a)' : ($liveAudit->score >= 50 ? 'linear-gradient(90deg,#f0a500,#ffc107)' : 'linear-gradient(90deg,#c82333,#dc3545)') }};
                                        width:{{ $liveAudit->score }}%;
                                        border-radius:20px; height:10px;
                                        transition:width 1s ease;">
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-2" style="font-size:11px; color:#a0aec0;">
                            <span>Ya: {{ $liveAudit->checklists->where('jawaban', 'ya')->count() }}</span>
                            <span>Tidak: {{ $liveAudit->checklists->where('jawaban', 'tidak')->count() }}</span>
                            <span>NA: {{ $liveAudit->checklists->where('jawaban', 'na')->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Checklist --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-list-check mr-2" style="color:#006b3f;"></i>
                        Hasil Checklist
                    </h3>
                </div>
                <div class="card-body p-0">
                    <table class="table mb-0" style="font-size:13px;">
                        <thead>
                            <tr>
                                <th width="5%" style="text-align:center;">No</th>
                                <th>Action / Condition</th>
                                <th width="10%" style="text-align:center; color:#dc3545;">
                                    Tidak
                                </th>
                                <th width="10%" style="text-align:center; color:#006b3f;">
                                    Ya
                                </th>
                                <th width="10%" style="text-align:center; color:#718096;">
                                    NA
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($checklistsBySection as $section => $checklists)
                                @if ($section !== 'Umum')
                                    <tr>
                                        <td colspan="5"
                                            style="background:linear-gradient(135deg,#e8f5ee,#f0faf4);
                                                   padding:10px 16px;">
                                            <div
                                                style="display:flex; align-items:center;
                                                        gap:8px;">
                                                <div
                                                    style="width:4px; height:18px;
                                                            background:#006b3f;
                                                            border-radius:2px;">
                                                </div>
                                                <span
                                                    style="font-weight:700; color:#004d2e;
                                                             font-size:12px;
                                                             text-transform:uppercase;
                                                             letter-spacing:0.5px;">
                                                    {{ $section }}
                                                </span>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                                @foreach ($checklists as $checklist)
                                    <tr
                                        style="{{ $checklist->checklistItem->is_critical && $checklist->jawaban === 'tidak' ? 'background:#fff5f5;' : '' }}">
                                        <td
                                            style="text-align:center; color:#a0aec0;
                                                   font-size:12px; font-weight:600;">
                                            {{ $checklist->checklistItem->nomor_item }}
                                        </td>
                                        <td>
                                            {{ $checklist->checklistItem->deskripsi }}
                                            @if ($checklist->checklistItem->is_critical)
                                                <span
                                                    style="color:#dc3545;
                                                             font-weight:700;">(*)</span>
                                            @endif
                                        </td>
                                        <td style="text-align:center;">
                                            @if ($checklist->jawaban === 'tidak')
                                                <div
                                                    style="width:28px; height:28px;
                                                            border-radius:50%; background:#fee2e2;
                                                            display:flex; align-items:center;
                                                            justify-content:center; margin:0 auto;">
                                                    <i class="fas fa-times" style="color:#dc3545; font-size:12px;"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td style="text-align:center;">
                                            @if ($checklist->jawaban === 'ya')
                                                <div
                                                    style="width:28px; height:28px;
                                                            border-radius:50%; background:#d4edda;
                                                            display:flex; align-items:center;
                                                            justify-content:center; margin:0 auto;">
                                                    <i class="fas fa-check" style="color:#006b3f; font-size:12px;"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td style="text-align:center;">
                                            @if ($checklist->jawaban === 'na')
                                                <div
                                                    style="width:28px; height:28px;
                                                            border-radius:50%;
                                                            background:#e2e8f0;
                                                            display:flex; align-items:center;
                                                            justify-content:center; margin:0 auto;">
                                                    <span
                                                        style="color:#718096;
                                                                 font-size:10px;
                                                                 font-weight:700;">NA</span>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr style="background:#f0faf4;">
                                <td colspan="2"
                                    style="text-align:right; font-weight:700;
                                           color:#004d2e; padding:12px 14px;">
                                    Total Nilai
                                </td>
                                <td
                                    style="text-align:center; font-weight:800;
                                           color:#dc3545; font-size:16px;">
                                    {{ $liveAudit->checklists->where('jawaban', 'tidak')->count() }}
                                </td>
                                <td
                                    style="text-align:center; font-weight:800;
                                           color:#006b3f; font-size:16px;">
                                    {{ $liveAudit->checklists->where('jawaban', 'ya')->count() }}
                                </td>
                                <td
                                    style="text-align:center; font-weight:800;
                                           color:#718096; font-size:16px;">
                                    {{ $liveAudit->checklists->where('jawaban', 'na')->count() }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            {{-- Temuan & Working Permit --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-flag mr-2" style="color:#006b3f;"></i>
                        Temuan & Working Permit
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div
                                style="background:#fff5f5; border:1.5px solid #fed7d7;
                                        border-radius:10px; padding:14px;">
                                <div
                                    style="font-size:11px; font-weight:700;
                                            color:#dc3545; text-transform:uppercase;
                                            letter-spacing:0.5px; margin-bottom:8px;">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                    Temuan Unsafe Action
                                </div>
                                <div style="font-size:13px; color:#4a5568;">
                                    {{ $liveAudit->unsafe_action_text ?? 'tidak ada' }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div
                                style="background:#fffbf0; border:1.5px solid #fde68a;
                                        border-radius:10px; padding:14px;">
                                <div
                                    style="font-size:11px; font-weight:700;
                                            color:#f0a500; text-transform:uppercase;
                                            letter-spacing:0.5px; margin-bottom:8px;">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    Temuan Unsafe Condition
                                </div>
                                <div style="font-size:13px; color:#4a5568;">
                                    {{ $liveAudit->unsafe_condition_text ?? 'tidak ada' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    @if ($liveAudit->working_permit_list)
                        <div>
                            <div
                                style="font-size:11px; font-weight:700; color:#718096;
                                        text-transform:uppercase; letter-spacing:0.5px;
                                        margin-bottom:10px;">
                                <i class="fas fa-id-card mr-1" style="color:#006b3f;"></i>
                                Working Permit Aktif
                            </div>
                            <div style="display:flex; flex-wrap:wrap; gap:8px;">
                                @foreach ($liveAudit->working_permit_list as $permit)
                                    <span
                                        style="background:#e8f5ee; color:#006b3f;
                                                 padding:5px 14px; border-radius:20px;
                                                 font-size:12px; font-weight:600;
                                                 border:1px solid #c6f6d5;">
                                        <i class="fas fa-check-circle mr-1" style="font-size:10px;"></i>
                                        {{ $permit }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            {{-- Foto Dokumentasi --}}
            @if ($liveAudit->fotos->isNotEmpty())
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-camera mr-2" style="color:#006b3f;"></i>
                            Foto Dokumentasi Kerja
                            <span style="font-size:12px; color:#a0aec0; font-weight:400;">
                                ({{ $liveAudit->fotos->count() }} foto)
                            </span>
                        </h3>
                    </div>
                    <div class="card-body">
                        <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(120px, 1fr)); gap:12px;">
                            @foreach ($liveAudit->fotos as $foto)
                                <div style="position:relative; border-radius:10px; overflow:hidden; border:1px solid #e2e8f0; cursor:pointer;"
                                     onclick="window.open('{{ Storage::url($foto->foto_path) }}', '_blank')">
                                    <img src="{{ Storage::url($foto->foto_path) }}" alt="Foto Dokumentasi"
                                         style="width:100%; height:90px; object-fit:cover;">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Kolom Kanan --}}
        <div class="col-md-4">

            {{-- Timeline Validasi --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-stream mr-2" style="color:#006b3f;"></i>
                        Alur Validasi
                    </h3>
                </div>
                <div class="card-body">
                    @php
                        $steps = [
                            [
                                'label' => 'Dibuat',
                                'user' => $liveAudit->creator->name,
                                'time' => $liveAudit->created_at->format('d M Y H:i'),
                                'done' => true,
                                'color' => '#17a2b8',
                                'icon' => 'fas fa-plus',
                            ],
                            [
                                'label' => 'Validator 1',
                                'user' => $liveAudit->validatorV1->name ?? null,
                                'time' => $liveAudit->validated_at_v1?->format('d M Y H:i'),
                                'done' => !is_null($liveAudit->validated_by_v1),
                                'color' => '#f0a500',
                                'icon' => 'fas fa-check',
                            ],
                            [
                                'label' => 'Validator 2',
                                'user' => $liveAudit->validatorV2->name ?? null,
                                'time' => $liveAudit->validated_at_v2?->format('d M Y H:i'),
                                'done' => !is_null($liveAudit->validated_by_v2),
                                'color' => '#006b3f',
                                'icon' => 'fas fa-check-double',
                            ],
                        ];
                    @endphp

                    @foreach ($steps as $i => $step)
                        <div style="display:flex; gap:14px; align-items:flex-start;">
                            <div
                                style="display:flex; flex-direction:column;
                                        align-items:center;">
                                <div
                                    style="width:36px; height:36px; border-radius:50%;
                                            background:{{ $step['done'] ? $step['color'] : '#e2e8f0' }};
                                            display:flex; align-items:center;
                                            justify-content:center; flex-shrink:0;">
                                    <i class="{{ $step['icon'] }}" style="color:#fff; font-size:13px;"></i>
                                </div>
                                @if ($i < count($steps) - 1)
                                    <div
                                        style="width:2px; height:36px;
                                                background:{{ $step['done'] ? $step['color'] . '40' : '#e2e8f0' }};
                                                margin:4px 0;">
                                    </div>
                                @endif
                            </div>
                            <div
                                style="padding-top:7px; flex:1;
                                        margin-bottom:{{ $i < count($steps) - 1 ? '0' : '0' }};">
                                <div
                                    style="font-size:13px; font-weight:600;
                                            color:{{ $step['done'] ? '#2d3748' : '#a0aec0' }};">
                                    {{ $step['label'] }}
                                </div>
                                @if ($step['done'] && $step['user'])
                                    <div style="font-size:11px; color:#718096;">
                                        {{ $step['user'] }}
                                    </div>
                                    <div style="font-size:10px; color:#a0aec0;">
                                        {{ $step['time'] }}
                                    </div>
                                    {{-- Display Signature if exists --}}
                                    @if ($i === 1 && $liveAudit->validatorV1 && $liveAudit->validatorV1->signature_path)
                                        <div class="mt-1" style="background:#fff; border:1px dashed #e2e8f0; border-radius:4px; padding:4px; display:inline-block;">
                                            <img src="{{ Storage::url($liveAudit->validatorV1->signature_path) }}" style="height:35px; max-width:110px; object-fit:contain;">
                                        </div>
                                    @elseif ($i === 2 && $liveAudit->validatorV2 && $liveAudit->validatorV2->signature_path)
                                        <div class="mt-1" style="background:#fff; border:1px dashed #e2e8f0; border-radius:4px; padding:4px; display:inline-block;">
                                            <img src="{{ Storage::url($liveAudit->validatorV2->signature_path) }}" style="height:35px; max-width:110px; object-fit:contain;">
                                        </div>
                                    @endif
                                @else
                                    <div style="font-size:11px; color:#cbd5e0;">
                                        Menunggu...
                                    </div>
                                @endif
                            </div>
                        </div>
                        @if ($i < count($steps) - 1)
                            <div style="height:4px;"></div>
                        @endif
                    @endforeach
                </div>
            </div>

            {{-- Aksi Validator 1 --}}
            @if ($liveAudit->status === 'pending_v1')
                @can('live_audit.validate_v1')
                    <div class="card mb-3" style="border:2px solid #f0a500 !important;">
                        <div class="card-header"
                            style="background:#fffbf0 !important;
                                    border-bottom:2px solid #ffeeba !important;">
                            <h3 class="card-title" style="color:#856404 !important;">
                                <i class="fas fa-user-check mr-2"></i>
                                Aksi Validator 1
                            </h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('live-audit.validate-v1', $liveAudit) }}" method="POST">
                                @csrf
                                <div class="row" style="gap:0;">
                                    <div class="col-6 pr-1">
                                        <button type="submit" name="action" value="approve"
                                            class="btn btn-success btn-block">
                                            <i class="fas fa-check mr-1"></i>
                                            Setujui
                                        </button>
                                    </div>
                                    <div class="col-6 pl-1">
                                        <button type="submit" name="action" value="reject"
                                            class="btn btn-outline-danger btn-block">
                                            <i class="fas fa-times mr-1"></i>
                                            Tolak
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                @endcan
            @endif

            {{-- Aksi Validator 2 --}}
            @if ($liveAudit->status === 'pending_v2')
                @can('live_audit.validate_v2')
                    <div class="card mb-3" style="border:2px solid #006b3f !important;">
                        <div class="card-header"
                            style="background:#e8f5ee !important;
                                    border-bottom:2px solid #c6f6d5 !important;">
                            <h3 class="card-title" style="color:#006b3f !important;">
                                <i class="fas fa-user-shield mr-2"></i>
                                Aksi Validator 2
                            </h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('live-audit.validate-v2', $liveAudit) }}" method="POST">
                                @csrf
                                <div class="row" style="gap:0;">
                                    <div class="col-6 pr-1">
                                        <button type="submit" name="action" value="approve"
                                            class="btn btn-success btn-block">
                                            <i class="fas fa-check-double mr-1"></i>
                                            Setujui
                                        </button>
                                    </div>
                                    <div class="col-6 pl-1">
                                        <button type="submit" name="action" value="reject"
                                            class="btn btn-outline-danger btn-block">
                                            <i class="fas fa-times mr-1"></i>
                                            Tolak
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                @endcan
            @endif

            {{-- Status Approved --}}
            @if ($liveAudit->status === 'approved')
                <div class="card mb-3" style="border:2px solid #00a65a !important;">
                    <div class="card-body text-center" style="padding:24px;">
                        <div
                            style="width:60px; height:60px; border-radius:50%;
                                    background:linear-gradient(135deg,#006b3f,#00a65a);
                                    display:flex; align-items:center;
                                    justify-content:center; margin:0 auto 12px;">
                            <i class="fas fa-check" style="color:#fff; font-size:24px;"></i>
                        </div>
                        <div
                            style="font-weight:700; font-size:15px;
                                    color:#006b3f; margin-bottom:4px;">
                            Live Audit Disetujui
                        </div>
                        <div style="font-size:12px; color:#718096;">
                            Semua validator telah menyetujui
                        </div>
                        <a href="{{ route('live-audit.export-pdf', $liveAudit) }}" class="btn btn-danger btn-block mt-3">
                            <i class="fas fa-file-pdf mr-1"></i>
                            Download PDF
                        </a>
                    </div>
                </div>
            @endif

        </div>
    </div>
@endsection

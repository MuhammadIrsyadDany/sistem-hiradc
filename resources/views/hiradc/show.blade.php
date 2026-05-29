@extends('adminlte::page')
@section('title', 'Detail HIRADC')

@section('content_header')
    <x-page-header title="Detail Dokumen HIRADC" subtitle="Informasi lengkap dokumen identifikasi bahaya dan penilaian risiko"
        icon="fas fa-file-alt" backUrl="{{ route('hiradc.index') }}">
    </x-page-header>
@endsection

@section('content')
    @if (session('success'))
        <x-alert type="success">{{ session('success') }}</x-alert>
    @endif

    {{-- Status Banner --}}
    @php
        $bannerConfig = [
            'approved' => [
                'bg' => 'linear-gradient(135deg,#d4edda,#c3e6cb)',
                'border' => '#00a65a',
                'icon' => 'fas fa-check-circle',
                'iconBg' => '#00a65a',
                'title' => 'Dokumen Disetujui',
                'sub' => 'Dokumen telah mendapat persetujuan dari semua validator',
            ],
            'rejected' => [
                'bg' => 'linear-gradient(135deg,#f8d7da,#f5c6cb)',
                'border' => '#dc3545',
                'icon' => 'fas fa-times-circle',
                'iconBg' => '#dc3545',
                'title' => 'Dokumen Ditolak',
                'sub' => 'Dokumen perlu diperbaiki sesuai catatan validator',
            ],
            'pending_v1' => [
                'bg' => 'linear-gradient(135deg,#fff3cd,#ffeeba)',
                'border' => '#f0a500',
                'icon' => 'fas fa-clock',
                'iconBg' => '#f0a500',
                'title' => 'Menunggu Validator 1',
                'sub' => 'Dokumen sedang menunggu review dari Asisten Manajer K3',
            ],
            'pending_v2' => [
                'bg' => 'linear-gradient(135deg,#cce5ff,#b8daff)',
                'border' => '#17a2b8',
                'icon' => 'fas fa-clock',
                'iconBg' => '#17a2b8',
                'title' => 'Menunggu Validator 2',
                'sub' => 'Dokumen sedang menunggu review dari Senior Manager',
            ],
        ];
        $banner = $bannerConfig[$hiradc->status] ?? null;
    @endphp

    @if ($banner)
        <div
            style="background:{{ $banner['bg'] }};
                    border:2px solid {{ $banner['border'] }};
                    border-radius:12px; padding:16px 20px;
                    margin-bottom:20px;
                    display:flex; align-items:center; gap:14px;">
            <div
                style="width:48px; height:48px; border-radius:12px;
                        background:{{ $banner['iconBg'] }};
                        display:flex; align-items:center;
                        justify-content:center; flex-shrink:0;">
                <i class="{{ $banner['icon'] }}" style="color:#fff; font-size:22px;"></i>
            </div>
            <div style="flex:1;">
                <div style="font-weight:700; font-size:15px;
                            color:#1a202c;">
                    {{ $banner['title'] }}
                </div>
                <div style="font-size:12px; color:#718096;">
                    {{ $banner['sub'] }}
                </div>
                @if ($hiradc->catatan_penolakan)
                    <div
                        style="margin-top:8px; font-size:13px;
                                color:#721c24; font-weight:600;">
                        Catatan: {{ $hiradc->catatan_penolakan }}
                    </div>
                @endif
            </div>
        </div>
    @endif

    <div class="row">
        {{-- Kolom Kiri --}}
        <div class="col-md-8">

            {{-- Info Dokumen --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-file-alt mr-2" style="color:#006b3f;"></i>
                        Informasi Dokumen
                    </h3>
                </div>
                <div class="card-body">
                    {{-- Judul --}}
                    <div
                        style="background:#f8fafc; border-radius:10px;
                                padding:16px; margin-bottom:20px;">
                        <div
                            style="font-size:18px; font-weight:700;
                                    color:#1a202c; margin-bottom:8px;">
                            {{ $hiradc->judul }}
                        </div>
                        <div style="display:flex; gap:8px; flex-wrap:wrap;">
                            {!! $hiradc->status_badge !!}
                            <span
                                style="background:#e8f5ee; color:#006b3f;
                                         font-size:11px; padding:3px 10px;
                                         border-radius:20px; font-weight:600;">
                                <i class="fas fa-calendar mr-1"></i>
                                {{ $hiradc->created_at->format('d M Y') }}
                            </span>
                        </div>
                    </div>

                    {{-- Detail Grid --}}
                    <div class="row">
                        @php
                            $details = [
                                ['icon' => 'fas fa-industry', 'label' => 'Unit', 'value' => $hiradc->unit ?? '-'],
                                [
                                    'icon' => 'fas fa-sitemap',
                                    'label' => 'Divisi/Bidang',
                                    'value' => $hiradc->divisi ?? '-',
                                ],
                                [
                                    'icon' => 'fas fa-map-marker-alt',
                                    'label' => 'Area/Lokasi',
                                    'value' => $hiradc->area_lokasi ?? '-',
                                ],
                                [
                                    'icon' => 'fas fa-user-tie',
                                    'label' => 'Penanggung Jawab',
                                    'value' => $hiradc->penanggung_jawab ?? '-',
                                ],
                                [
                                    'icon' => 'fas fa-user',
                                    'label' => 'Diupload Oleh',
                                    'value' => $hiradc->uploader->name,
                                ],
                                [
                                    'icon' => 'fas fa-calendar-alt',
                                    'label' => 'Tanggal Upload',
                                    'value' => $hiradc->created_at->format('d M Y H:i'),
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

                    {{-- Download Button --}}
                    <div
                        style="background:#f0faf4; border-radius:10px;
                                padding:14px 16px; display:flex;
                                justify-content:space-between; align-items:center;">
                        <div style="display:flex; align-items:center; gap:10px;">
                            <div
                                style="width:38px; height:38px; border-radius:8px;
                                        background:#006b3f; display:flex;
                                        align-items:center; justify-content:center;">
                                <i class="fas fa-file-alt" style="color:#fff; font-size:16px;"></i>
                            </div>
                            <div>
                                <div
                                    style="font-size:13px; font-weight:600;
                                            color:#2d3748;">
                                    File Dokumen HIRADC
                                </div>
                                <div style="font-size:11px; color:#a0aec0;">
                                    {{ strtoupper(pathinfo($hiradc->file_path, PATHINFO_EXTENSION)) }}
                                    format
                                </div>
                            </div>
                        </div>
                        <a href="{{ Storage::url($hiradc->file_path) }}" target="_blank" class="btn btn-primary"
                            style="padding:8px 18px;">
                            <i class="fas fa-download mr-1"></i>
                            Download
                        </a>
                    </div>
                </div>
            </div>

            {{-- Program Kerja --}}
            @if ($hiradc->status === 'approved')
                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">
                            <i class="fas fa-tasks mr-2" style="color:#006b3f;"></i>
                            Program Kerja
                            <span
                                style="font-size:12px; color:#a0aec0;
                                         font-weight:400;">
                                ({{ $hiradc->programKerja->count() }} program)
                            </span>
                        </h3>
                        @can('program_kerja.create')
                            <a href="{{ route('program-kerja.create', ['hiradc_id' => $hiradc->id]) }}"
                                class="btn btn-sm btn-primary">
                                <i class="fas fa-plus mr-1"></i> Tambah
                            </a>
                        @endcan
                    </div>
                    <div class="card-body p-0">
                        @forelse($hiradc->programKerja as $program)
                            <div style="padding:14px 20px;
                                        border-bottom:1px solid #f0f4f8;
                                        display:flex; justify-content:space-between;
                                        align-items:center; transition:background 0.15s;"
                                onmouseover="this.style.background='#f8fafc'"
                                onmouseout="this.style.background='transparent'">
                                <div style="flex:1;">
                                    <div
                                        style="font-size:13px; font-weight:600;
                                                color:#2d3748; margin-bottom:4px;">
                                        {{ $program->nama_program }}
                                    </div>
                                    <div
                                        style="display:flex; gap:12px;
                                                font-size:11px; color:#a0aec0;">
                                        <span>
                                            <i class="fas fa-user mr-1"></i>
                                            {{ $program->pic }}
                                        </span>
                                        <span>
                                            <i class="fas fa-calendar mr-1"></i>
                                            {{ $program->deadline->format('d M Y') }}
                                        </span>
                                        @if ($program->status !== 'closed' && $program->deadline < now())
                                            <span style="color:#dc3545; font-weight:600;">
                                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                                Overdue
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div style="display:flex; align-items:center; gap:10px;">
                                    {!! $program->status_badge !!}
                                    <a href="{{ route('program-kerja.show', $program) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </div>
                            </div>
                        @empty
                            <x-empty-state icon="fas fa-tasks" message="Belum ada program kerja"
                                sub="Tambahkan program kerja dari dokumen HIRADC ini" />
                        @endforelse
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
                        Alur Persetujuan
                    </h3>
                </div>
                <div class="card-body">
                    @php
                        $steps = [
                            [
                                'label' => 'Upload',
                                'user' => $hiradc->uploader->name,
                                'time' => $hiradc->created_at->format('d M Y H:i'),
                                'done' => true,
                                'color' => '#17a2b8',
                                'icon' => 'fas fa-upload',
                            ],
                            [
                                'label' => 'Validator 1',
                                'user' => $hiradc->validatorV1->name ?? null,
                                'time' => $hiradc->validated_at_v1?->format('d M Y H:i'),
                                'done' => !is_null($hiradc->validated_by_v1),
                                'color' => '#f0a500',
                                'icon' => 'fas fa-check',
                            ],
                            [
                                'label' => 'Validator 2',
                                'user' => $hiradc->validatorV2->name ?? null,
                                'time' => $hiradc->validated_at_v2?->format('d M Y H:i'),
                                'done' => !is_null($hiradc->validated_by_v2),
                                'color' => '#006b3f',
                                'icon' => 'fas fa-check-double',
                            ],
                            [
                                'label' => 'Approved',
                                'user' => $hiradc->status === 'approved' ? 'Dokumen Aktif' : null,
                                'time' => null,
                                'done' => $hiradc->status === 'approved',
                                'color' => '#00a65a',
                                'icon' => 'fas fa-flag',
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
                                        style="width:2px; height:32px;
                                                background:{{ $step['done'] ? $step['color'] . '40' : '#e2e8f0' }};
                                                margin:4px 0;">
                                    </div>
                                @endif
                            </div>
                            <div style="padding-top:6px; flex:1;">
                                <div
                                    style="font-size:13px; font-weight:600;
                                            color:{{ $step['done'] ? '#2d3748' : '#a0aec0' }};">
                                    {{ $step['label'] }}
                                </div>
                                @if ($step['done'] && $step['user'])
                                    <div style="font-size:11px; color:#718096;">
                                        {{ $step['user'] }}
                                    </div>
                                    @if ($step['time'])
                                        <div style="font-size:10px; color:#a0aec0;">
                                            {{ $step['time'] }}
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
                            <div style="height:6px;"></div>
                        @endif
                    @endforeach
                </div>
            </div>

            {{-- Aksi Validator 1 --}}
            @if ($hiradc->status === 'pending_v1')
                @can('hiradc.validate_v1')
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
                            <form action="{{ route('hiradc.validate-v1', $hiradc) }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label style="font-size:12px;">
                                        Catatan (isi jika menolak)
                                    </label>
                                    <textarea name="catatan_penolakan" class="form-control" rows="2" placeholder="Tuliskan catatan..."></textarea>
                                </div>
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
            @if ($hiradc->status === 'pending_v2')
                @can('hiradc.validate_v2')
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
                            <form action="{{ route('hiradc.validate-v2', $hiradc) }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label style="font-size:12px;">
                                        Catatan (isi jika menolak)
                                    </label>
                                    <textarea name="catatan_penolakan" class="form-control" rows="2" placeholder="Tuliskan catatan..."></textarea>
                                </div>
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

        </div>
    </div>
@endsection

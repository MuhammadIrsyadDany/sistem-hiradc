@extends('adminlte::page')
@section('title', 'Detail Program Kerja')

@section('content_header')
    <x-page-header title="Detail Program Kerja" subtitle="Monitoring dan bukti pelaksanaan program pengendalian risiko"
        icon="fas fa-tasks" backUrl="{{ route('program-kerja.index') }}">
    </x-page-header>
@endsection

@section('content')
    @if (session('success'))
        <x-alert type="success">{{ session('success') }}</x-alert>
    @endif
    @if (session('error'))
        <x-alert type="danger">{{ session('error') }}</x-alert>
    @endif

    {{-- Status Banner --}}
    @if ($programKerja->status === 'closed')
        <div
            style="background:linear-gradient(135deg,#d4edda,#c3e6cb);
                    border:2px solid #00a65a; border-radius:12px;
                    padding:16px 20px; margin-bottom:20px;
                    display:flex; align-items:center; gap:14px;">
            <div
                style="width:48px; height:48px; border-radius:12px;
                        background:#00a65a; display:flex; align-items:center;
                        justify-content:center; flex-shrink:0;">
                <i class="fas fa-flag-checkered" style="color:#fff; font-size:22px;"></i>
            </div>
            <div>
                <div style="font-weight:700; color:#155724; font-size:15px;">
                    Program Kerja Selesai
                </div>
                <div style="font-size:12px; color:#155724; opacity:0.8;">
                    Ditutup pada {{ $programKerja->updated_at->format('d M Y H:i') }}
                </div>
            </div>
        </div>
    @elseif($programKerja->status === 'overdue')
        <div
            style="background:linear-gradient(135deg,#f8d7da,#f5c6cb);
                    border:2px solid #dc3545; border-radius:12px;
                    padding:16px 20px; margin-bottom:20px;
                    display:flex; align-items:center; gap:14px;">
            <div
                style="width:48px; height:48px; border-radius:12px;
                        background:#dc3545; display:flex; align-items:center;
                        justify-content:center; flex-shrink:0;">
                <i class="fas fa-exclamation-circle" style="color:#fff; font-size:22px;"></i>
            </div>
            <div>
                <div style="font-weight:700; color:#721c24; font-size:15px;">
                    Program Kerja Overdue!
                </div>
                <div style="font-size:12px; color:#721c24; opacity:0.8;">
                    Deadline {{ $programKerja->deadline->format('d M Y') }}
                    — {{ $programKerja->deadline->diffForHumans() }}
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        {{-- Kolom Kiri --}}
        <div class="col-md-7">

            {{-- Info Program Kerja --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-tasks mr-2" style="color:#006b3f;"></i>
                        Informasi Program Kerja
                    </h3>
                </div>
                <div class="card-body">
                    {{-- Nama Program --}}
                    <div
                        style="background:#f8fafc; border-radius:10px;
                                padding:16px; margin-bottom:20px;">
                        <div
                            style="font-size:17px; font-weight:700;
                                    color:#1a202c; margin-bottom:8px;">
                            {{ $programKerja->nama_program }}
                        </div>
                        <div style="display:flex; gap:8px; flex-wrap:wrap;">
                            {!! $programKerja->status_badge !!}
                        </div>
                    </div>

                    {{-- Detail Grid --}}
                    <div class="row">
                        @php
                            $details = [
                                [
                                    'icon' => 'fas fa-file-alt',
                                    'label' => 'Dokumen HIRADC',
                                    'value' => $programKerja->hiradc->judul,
                                    'link' => route('hiradc.show', $programKerja->hiradc),
                                ],
                                [
                                    'icon' => 'fas fa-user-tie',
                                    'label' => 'PIC',
                                    'value' => $programKerja->pic,
                                    'link' => null,
                                ],
                                [
                                    'icon' => 'fas fa-calendar-alt',
                                    'label' => 'Deadline',
                                    'value' => $programKerja->deadline->format('d M Y'),
                                    'link' => null,
                                ],
                                [
                                    'icon' => 'fas fa-user',
                                    'label' => 'Dibuat Oleh',
                                    'value' => $programKerja->creator->name,
                                    'link' => null,
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
                                            @if ($d['link'])
                                                <a href="{{ $d['link'] }}" style="color:#006b3f;">
                                                    {{ Str::limit($d['value'], 40) }}
                                                </a>
                                            @else
                                                {{ $d['value'] }}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Pengendalian Risiko --}}
                    @if ($programKerja->pengendalian_risiko)
                        <div
                            style="background:#f0faf4; border-radius:10px;
                                    padding:14px 16px; border-left:3px solid #006b3f;">
                            <div
                                style="font-size:11px; color:#a0aec0; font-weight:700;
                                        text-transform:uppercase; letter-spacing:0.5px;
                                        margin-bottom:6px;">
                                <i class="fas fa-shield-alt mr-1" style="color:#006b3f;"></i>
                                Pengendalian Risiko
                            </div>
                            <div style="font-size:13px; color:#4a5568;">
                                {{ $programKerja->pengendalian_risiko }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Bukti Pelaksanaan --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-camera mr-2" style="color:#006b3f;"></i>
                        Bukti Pelaksanaan
                        <span style="font-size:12px; color:#a0aec0; font-weight:400;">
                            ({{ $programKerja->bukti->count() }} bukti)
                        </span>
                    </h3>
                </div>
                <div class="card-body">
                    @forelse($programKerja->bukti as $bukti)
                        <div
                            style="display:flex; gap:16px; align-items:flex-start;
                                    padding:14px; background:#f8fafc;
                                    border-radius:10px; margin-bottom:10px;">
                            <img src="{{ Storage::url($bukti->foto_path) }}" alt="Bukti"
                                style="width:110px; height:85px; object-fit:cover;
                                        border-radius:8px; flex-shrink:0; cursor:pointer;"
                                onclick="window.open('{{ Storage::url($bukti->foto_path) }}','_blank')">
                            <div style="flex:1;">
                                <div
                                    style="font-size:13px; color:#2d3748;
                                            line-height:1.5; margin-bottom:8px;">
                                    {{ $bukti->keterangan }}
                                </div>
                                <div
                                    style="display:flex; align-items:center;
                                            gap:8px; font-size:11px; color:#a0aec0;">
                                    <div
                                        style="width:22px; height:22px; border-radius:50%;
                                                background:#006b3f; color:#fff; font-size:9px;
                                                display:flex; align-items:center;
                                                justify-content:center; font-weight:700;">
                                        {{ strtoupper(substr($bukti->uploader->name, 0, 1)) }}
                                    </div>
                                    {{ $bukti->uploader->name }} ·
                                    {{ $bukti->created_at->format('d M Y H:i') }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <x-empty-state icon="fas fa-camera" message="Belum ada bukti pelaksanaan"
                            sub="Upload foto bukti untuk menunjukkan progress program kerja" />
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Kolom Kanan --}}
        <div class="col-md-5">

            {{-- Deadline Tracker --}}
            <div class="card mb-3">
                <div class="card-body text-center" style="padding:24px;">
                    @php
                        $today = now();
                        $deadline = $programKerja->deadline;
                        $daysLeft = $today->diffInDays($deadline, false);
                        $isOver = $daysLeft < 0;
                    @endphp

                    <div
                        style="width:80px; height:80px; border-radius:50%;
                                background:{{ $isOver ? '#fee2e2' : ($daysLeft <= 7 ? '#fff3cd' : '#e8f5ee') }};
                                display:flex; align-items:center; justify-content:center;
                                margin:0 auto 12px; border:3px solid {{ $isOver ? '#dc3545' : ($daysLeft <= 7 ? '#f0a500' : '#006b3f') }};">
                        <div>
                            <div
                                style="font-size:22px; font-weight:800;
                                        color:{{ $isOver ? '#dc3545' : ($daysLeft <= 7 ? '#f0a500' : '#006b3f') }};
                                        line-height:1;">
                                {{ abs($daysLeft) }}
                            </div>
                            <div style="font-size:9px; color:#718096;">
                                hari
                            </div>
                        </div>
                    </div>

                    <div
                        style="font-size:14px; font-weight:700;
                                color:{{ $isOver ? '#dc3545' : '#2d3748' }};">
                        {{ $isOver ? 'Sudah Terlewat' : ($daysLeft === 0 ? 'Deadline Hari Ini!' : 'Sisa Waktu') }}
                    </div>
                    <div style="font-size:12px; color:#718096; margin-top:4px;">
                        Deadline: {{ $deadline->format('d M Y') }}
                    </div>
                </div>
            </div>

            {{-- Upload Bukti --}}
            @if ($programKerja->status !== 'closed')
                @can('program_kerja.upload_bukti')
                    <div class="card mb-3" style="border:2px solid #006b3f !important;">
                        <div class="card-header"
                            style="background:#e8f5ee !important;
                                    border-bottom:2px solid #c6f6d5 !important;">
                            <h3 class="card-title" style="color:#006b3f !important;">
                                <i class="fas fa-upload mr-2"></i>
                                Upload Bukti Pelaksanaan
                            </h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('program-kerja.upload-bukti', $programKerja) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf

                                {{-- Drop Zone --}}
                                <div style="border:2px dashed #c6f6d5; border-radius:10px;
                                            padding:20px; text-align:center; cursor:pointer;
                                            background:#f0faf4; margin-bottom:12px;"
                                    onclick="document.getElementById('fotoBukti').click()">
                                    <i class="fas fa-cloud-upload-alt"
                                        style="font-size:28px; color:#c6f6d5;
                                              margin-bottom:8px; display:block;"></i>
                                    <div
                                        style="font-size:12px; color:#006b3f;
                                                font-weight:600;">
                                        Klik untuk pilih foto
                                    </div>
                                    <div id="buktiFileName"
                                        style="font-size:11px; color:#a0aec0;
                                                margin-top:4px;">
                                        JPG, PNG · Maks 5MB
                                    </div>
                                </div>

                                <input type="file" name="foto" id="fotoBukti" accept="image/*" style="display:none;"
                                    onchange="document.getElementById('buktiFileName').textContent = this.files[0]?.name ?? 'JPG, PNG · Maks 5MB'">

                                <div class="form-group">
                                    <label>
                                        Keterangan Program Kerja
                                        <span class="text-danger">*</span>
                                    </label>
                                    <textarea name="keterangan" class="form-control" rows="3"
                                        placeholder="Deskripsikan program kerja yang sedang/sudah dilaksanakan..."></textarea>
                                </div>

                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-upload mr-1"></i>
                                    Upload Bukti
                                </button>
                            </form>
                        </div>
                    </div>
                @endcan

                {{-- Close Button --}}
                @can('program_kerja.close')
                    @if ($programKerja->bukti->isNotEmpty())
                        <div class="card mb-3">
                            <div class="card-body">
                                <form action="{{ route('program-kerja.close', $programKerja) }}" method="POST"
                                    onsubmit="return confirm('Yakin ingin menutup program kerja ini? Pastikan semua pekerjaan sudah selesai.')">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-block" style="padding:12px;">
                                        <i class="fas fa-flag-checkered mr-1"></i>
                                        Close Program Kerja
                                    </button>
                                    <div
                                        style="font-size:11px; color:#a0aec0;
                                                text-align:center; margin-top:8px;">
                                        Tindakan ini tidak dapat dibatalkan
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                @endcan
            @endif

        </div>
    </div>
@endsection

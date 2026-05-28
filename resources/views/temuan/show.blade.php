@extends('adminlte::page')
@section('title', 'Detail Temuan')

@section('content_header')
    <x-page-header title="Detail Temuan" subtitle="Informasi lengkap temuan UA/UC" icon="fas fa-exclamation-triangle"
        backUrl="{{ route('temuan.index') }}">
    </x-page-header>
@endsection

@section('content')
    @if (session('success'))
        <x-alert type="success">{{ session('success') }}</x-alert>
    @endif
    @if (session('error'))
        <x-alert type="danger">{{ session('error') }}</x-alert>
    @endif

    {{-- Banner Draft --}}
    @if ($temuan->isDraft())
        <div
            style="background:linear-gradient(135deg,#fff3cd,#ffeeba);
                    border:1px solid #f0a500; border-radius:12px;
                    padding:16px 20px; margin-bottom:20px;
                    display:flex; align-items:center; gap:14px;">
            <div
                style="width:42px; height:42px; border-radius:10px;
                        background:#f0a500; display:flex; align-items:center;
                        justify-content:center; flex-shrink:0;">
                <i class="fas fa-exclamation" style="color:#fff; font-size:18px;"></i>
            </div>
            <div>
                <div style="font-weight:700; color:#856404; font-size:14px;">
                    Temuan ini masih Draft!
                </div>
                <div style="font-size:12px; color:#856404; opacity:0.8;">
                    Dibuat otomatis dari Live Audit. Pelapor perlu melengkapi
                    foto dan detail sebelum dapat diproses lebih lanjut.
                </div>
            </div>
        </div>
    @endif

    {{-- Banner Closed --}}
    @if ($temuan->status === 'closed')
        <div
            style="background:linear-gradient(135deg,#d4edda,#c3e6cb);
                    border:1px solid #00a65a; border-radius:12px;
                    padding:16px 20px; margin-bottom:20px;
                    display:flex; align-items:center; gap:14px;">
            <div
                style="width:42px; height:42px; border-radius:10px;
                        background:#00a65a; display:flex; align-items:center;
                        justify-content:center; flex-shrink:0;">
                <i class="fas fa-check" style="color:#fff; font-size:18px;"></i>
            </div>
            <div>
                <div style="font-weight:700; color:#155724; font-size:14px;">
                    Temuan Sudah Closed
                </div>
                <div style="font-size:12px; color:#155724; opacity:0.8;">
                    Ditutup oleh {{ $temuan->closedBy->name }}
                    pada {{ $temuan->closed_at->format('d M Y H:i') }}
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        {{-- Kolom Kiri --}}
        <div class="col-md-8">

            {{-- Info Temuan --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-info-circle mr-2" style="color:#006b3f;"></i>
                        Informasi Temuan
                    </h3>
                </div>
                <div class="card-body">
                    {{-- Judul & Kategori --}}
                    <div
                        style="background:#f8fafc; border-radius:10px;
                                padding:16px; margin-bottom:20px;">
                        <div
                            style="font-size:18px; font-weight:700;
                                    color:#1a202c; margin-bottom:8px;">
                            {{ $temuan->judul_temuan }}
                        </div>
                        <div style="display:flex; gap:8px; flex-wrap:wrap;">
                            {!! $temuan->kategori_badge !!}
                            {!! $temuan->status_badge !!}
                            @if ($temuan->ai_kategori)
                                <span
                                    style="background:#e8f5ee; color:#006b3f;
                                             font-size:11px; padding:3px 10px;
                                             border-radius:20px; font-weight:600;">
                                    <i class="fas fa-robot mr-1"></i>
                                    AI: {{ strtoupper(str_replace('_', ' ', $temuan->ai_kategori)) }}
                                    ({{ number_format($temuan->ai_confidence * 100, 1) }}%)
                                </span>
                            @endif
                            @if ($temuan->live_audit_id)
                                <a href="{{ route('live-audit.show', $temuan->liveAudit) }}"
                                    style="background:#e8f5ee; color:#006b3f;
                                          font-size:11px; padding:3px 10px;
                                          border-radius:20px; font-weight:600;
                                          text-decoration:none;">
                                    <i class="fas fa-link mr-1"></i>
                                    Dari Live Audit
                                </a>
                            @endif
                        </div>
                    </div>

                    {{-- Detail Grid --}}
                    <div class="row">
                        @php
                            $details = [
                                ['icon' => 'fas fa-map-marker-alt', 'label' => 'Distrik', 'value' => $temuan->distrik],
                                [
                                    'icon' => 'fas fa-map-pin',
                                    'label' => 'Lokasi',
                                    'value' =>
                                        $temuan->lokasi .
                                        ($temuan->keterangan_lokasi ? ' — ' . $temuan->keterangan_lokasi : ''),
                                ],
                                ['icon' => 'fas fa-tag', 'label' => 'Kondisi', 'value' => $temuan->kondisi ?? '-'],
                                ['icon' => 'fas fa-user-tie', 'label' => 'PIC', 'value' => $temuan->pic ?? '-'],
                                ['icon' => 'fas fa-user', 'label' => 'Dilaporkan', 'value' => $temuan->reporter->name],
                                [
                                    'icon' => 'fas fa-calendar',
                                    'label' => 'Tanggal',
                                    'value' => $temuan->created_at->format('d M Y H:i'),
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

                    {{-- Tindak Lanjut & Rekomendasi --}}
                    @if ($temuan->tindak_lanjut || $temuan->rekomendasi)
                        <hr style="border-color:#e8f5ee; margin:16px 0;">
                        <div class="row">
                            @if ($temuan->tindak_lanjut)
                                <div class="col-md-6">
                                    <div
                                        style="font-size:11px; color:#a0aec0; font-weight:700;
                                                text-transform:uppercase; letter-spacing:0.5px;
                                                margin-bottom:6px;">
                                        <i class="fas fa-arrow-right mr-1" style="color:#006b3f;"></i>
                                        Tindak Lanjut
                                    </div>
                                    <div
                                        style="font-size:13px; color:#4a5568;
                                                background:#f8fafc; border-radius:8px;
                                                padding:10px 14px;">
                                        {{ $temuan->tindak_lanjut }}
                                    </div>
                                </div>
                            @endif
                            @if ($temuan->rekomendasi)
                                <div class="col-md-6">
                                    <div
                                        style="font-size:11px; color:#a0aec0; font-weight:700;
                                                text-transform:uppercase; letter-spacing:0.5px;
                                                margin-bottom:6px;">
                                        <i class="fas fa-lightbulb mr-1" style="color:#f0a500;"></i>
                                        Rekomendasi
                                    </div>
                                    <div
                                        style="font-size:13px; color:#4a5568;
                                                background:#f8fafc; border-radius:8px;
                                                padding:10px 14px;">
                                        {{ $temuan->rekomendasi }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            {{-- Foto Temuan --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-camera mr-2" style="color:#006b3f;"></i>
                        Foto Temuan
                        <span style="font-size:12px; color:#a0aec0; font-weight:400;">
                            ({{ $temuan->fotos->count() }} foto)
                        </span>
                    </h3>
                </div>
                <div class="card-body">
                    @if ($temuan->fotos->isNotEmpty())
                        <div
                            style="display:grid;
                                    grid-template-columns:repeat(auto-fill, minmax(150px,1fr));
                                    gap:12px;">
                            @foreach ($temuan->fotos as $foto)
                                <div style="position:relative; cursor:pointer;"
                                    onclick="window.open('{{ Storage::url($foto->foto_path) }}','_blank')">
                                    <img src="{{ Storage::url($foto->foto_path) }}" alt="Foto Temuan"
                                        style="width:100%; height:130px;
                                                object-fit:cover; border-radius:10px;
                                                transition:transform 0.2s;"
                                        onmouseover="this.style.transform='scale(1.03)'"
                                        onmouseout="this.style.transform='scale(1)'">
                                    <div
                                        style="position:absolute; bottom:0; left:0; right:0;
                                                background:linear-gradient(transparent,rgba(0,0,0,0.5));
                                                border-radius:0 0 10px 10px;
                                                padding:8px; text-align:center;">
                                        <i class="fas fa-search-plus" style="color:#fff; font-size:14px;"></i>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <x-empty-state icon="fas fa-camera" message="Belum ada foto temuan"
                            sub="Foto akan muncul setelah pelapor melengkapi draft" />
                    @endif
                </div>
            </div>

            {{-- Bukti Perbaikan --}}
            @if ($temuan->buktiPerbaikan->isNotEmpty())
                <div class="card mb-3">
                    <div class="card-header"
                        style="background:#f0faf4 !important;
                                border-bottom:2px solid #c6f6d5 !important;">
                        <h3 class="card-title" style="color:#006b3f !important;">
                            <i class="fas fa-tools mr-2"></i>
                            Bukti Perbaikan
                            <span
                                style="font-size:12px; color:#a0aec0;
                                         font-weight:400; color:#718096 !important;">
                                ({{ $temuan->buktiPerbaikan->count() }} bukti)
                            </span>
                        </h3>
                    </div>
                    <div class="card-body">
                        @foreach ($temuan->buktiPerbaikan as $bukti)
                            <div
                                style="display:flex; gap:16px; align-items:flex-start;
                                        padding-bottom:16px; margin-bottom:16px;
                                        border-bottom:1px solid #f0f4f8;">
                                <img src="{{ Storage::url($bukti->foto_path) }}" alt="Bukti Perbaikan"
                                    style="width:110px; height:85px; object-fit:cover;
                                            border-radius:10px; flex-shrink:0; cursor:pointer;"
                                    onclick="window.open('{{ Storage::url($bukti->foto_path) }}','_blank')">
                                <div style="flex:1;">
                                    <div
                                        style="font-size:13px; color:#2d3748;
                                                margin-bottom:8px; line-height:1.5;">
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
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- Kolom Kanan (Sidebar) --}}
        <div class="col-md-4">

            {{-- Timeline Validasi --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-stream mr-2" style="color:#006b3f;"></i>
                        Alur Validasi
                    </h3>
                </div>
                <div class="card-body p-0">
                    @php
                        $steps = [
                            [
                                'label' => 'Dilaporkan',
                                'user' => $temuan->reporter->name,
                                'time' => $temuan->created_at->format('d M Y H:i'),
                                'done' => true,
                                'color' => '#17a2b8',
                                'icon' => 'fas fa-flag',
                            ],
                            [
                                'label' => 'Validator 1',
                                'user' => $temuan->validatorV1->name ?? null,
                                'time' => $temuan->validated_at_v1?->format('d M Y H:i'),
                                'done' => !is_null($temuan->validated_by_v1),
                                'color' => '#f0a500',
                                'icon' => 'fas fa-check',
                            ],
                            [
                                'label' => 'Validator 2',
                                'user' => $temuan->validatorV2->name ?? null,
                                'time' => $temuan->validated_at_v2?->format('d M Y H:i'),
                                'done' => !is_null($temuan->validated_by_v2),
                                'color' => '#006b3f',
                                'icon' => 'fas fa-check-double',
                            ],
                            [
                                'label' => 'Closed',
                                'user' => $temuan->closedBy->name ?? null,
                                'time' => $temuan->closed_at?->format('d M Y H:i'),
                                'done' => $temuan->status === 'closed',
                                'color' => '#00a65a',
                                'icon' => 'fas fa-lock',
                            ],
                        ];
                    @endphp

                    <div style="padding:16px;">
                        @foreach ($steps as $i => $step)
                            <div
                                style="display:flex; gap:14px; align-items:flex-start;
                                        margin-bottom:{{ $i < count($steps) - 1 ? '0' : '0' }};">
                                <div
                                    style="display:flex; flex-direction:column;
                                            align-items:center;">
                                    <div
                                        style="width:34px; height:34px; border-radius:50%;
                                                background:{{ $step['done'] ? $step['color'] : '#e2e8f0' }};
                                                display:flex; align-items:center;
                                                justify-content:center; flex-shrink:0;
                                                transition:all 0.3s;">
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
                                        <div style="font-size:10px; color:#a0aec0;">
                                            {{ $step['time'] }}
                                        </div>
                                    @else
                                        <div style="font-size:11px; color:#cbd5e0;">
                                            Menunggu...
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @if ($i < count($steps) - 1)
                                <div style="height:8px;"></div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Form Lengkapi Draft --}}
            @if ($temuan->isDraft())
                @can('temuan.create')
                    <div class="card mb-3" style="border:2px solid #f0a500 !important;">
                        <div class="card-header"
                            style="background:#fffbf0 !important;
                                    border-bottom:2px solid #ffeeba !important;">
                            <h3 class="card-title" style="color:#856404 !important;">
                                <i class="fas fa-edit mr-2"></i>
                                Lengkapi Draft
                            </h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('temuan.complete-draft', $temuan) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label>Kondisi <span class="text-danger">*</span></label>
                                    <input type="text" name="kondisi" class="form-control"
                                        placeholder="tidak aman / aman">
                                </div>
                                <div class="form-group">
                                    <label>Keterangan Lokasi</label>
                                    <input type="text" name="keterangan_lokasi" class="form-control"
                                        placeholder="Contoh: unit 1">
                                </div>
                                <div class="form-group">
                                    <label>PIC</label>
                                    <input type="text" name="pic" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Tindak Lanjut</label>
                                    <textarea name="tindak_lanjut" class="form-control" rows="2"></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Rekomendasi</label>
                                    <textarea name="rekomendasi" class="form-control" rows="2"></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Foto <span class="text-danger">*</span></label>
                                    <div class="custom-file">
                                        <input type="file" name="fotos[]" class="custom-file-input" id="fotoDraft"
                                            accept="image/*" multiple onchange="previewDraft(this)">
                                        <label class="custom-file-label" for="fotoDraft">
                                            Pilih foto
                                        </label>
                                    </div>
                                    <div id="draftPreview"
                                        style="display:flex; flex-wrap:wrap;
                                                gap:6px; margin-top:8px;">
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-warning btn-block">
                                    <i class="fas fa-check mr-1"></i>
                                    Lengkapi & Submit
                                </button>
                            </form>
                        </div>
                    </div>
                @endcan
            @endif

            {{-- Aksi Validator 1 --}}
            @if ($temuan->status === 'open')
                @can('temuan.validate_v1')
                    <div class="card mb-3">
                        <div class="card-header"
                            style="background:#fffbf0 !important;
                                    border-bottom:2px solid #ffeeba !important;">
                            <h3 class="card-title" style="color:#856404 !important;">
                                <i class="fas fa-user-check mr-2"></i>
                                Aksi Validator 1
                            </h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('temuan.validate-v1', $temuan) }}" method="POST">
                                @csrf
                                <div class="row" style="gap:0;">
                                    <div class="col-6 pr-1">
                                        <button type="submit" name="action" value="approve"
                                            class="btn btn-success btn-block">
                                            <i class="fas fa-check mr-1"></i>
                                            Validasi
                                        </button>
                                    </div>
                                    <div class="col-6 pl-1">
                                        <button type="submit" name="action" value="reject"
                                            class="btn btn-outline-danger btn-block">
                                            <i class="fas fa-undo mr-1"></i>
                                            Kembalikan
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                @endcan
            @endif

            {{-- Aksi Validator 2 --}}
            @if ($temuan->status === 'validated_v1')
                @can('temuan.validate_v2')
                    <div class="card mb-3">
                        <div class="card-header"
                            style="background:#e8f5ee !important;
                                    border-bottom:2px solid #c6f6d5 !important;">
                            <h3 class="card-title" style="color:#006b3f !important;">
                                <i class="fas fa-user-shield mr-2"></i>
                                Aksi Validator 2
                            </h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('temuan.validate-v2', $temuan) }}" method="POST">
                                @csrf
                                <div class="row" style="gap:0;">
                                    <div class="col-6 pr-1">
                                        <button type="submit" name="action" value="approve"
                                            class="btn btn-success btn-block">
                                            <i class="fas fa-check-double mr-1"></i>
                                            Validasi
                                        </button>
                                    </div>
                                    <div class="col-6 pl-1">
                                        <button type="submit" name="action" value="reject"
                                            class="btn btn-outline-danger btn-block">
                                            <i class="fas fa-undo mr-1"></i>
                                            Kembalikan
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                @endcan
            @endif

            {{-- Upload Bukti & Close --}}
            @if ($temuan->status === 'validated_v2')
                @can('temuan.close')
                    <div class="card mb-3">
                        <div class="card-header"
                            style="background:#e8f5ee !important;
                                    border-bottom:2px solid #c6f6d5 !important;">
                            <h3 class="card-title" style="color:#006b3f !important;">
                                <i class="fas fa-tools mr-2"></i>
                                Upload Bukti Perbaikan
                            </h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('temuan.upload-bukti', $temuan) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <div class="custom-file">
                                        <input type="file" name="foto" class="custom-file-input" id="fotoBukti"
                                            accept="image/*"
                                            onchange="this.nextElementSibling.textContent = this.files[0]?.name">
                                        <label class="custom-file-label" for="fotoBukti">
                                            Pilih foto
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <textarea name="keterangan" class="form-control" rows="3"
                                        placeholder="Keterangan perbaikan yang dilakukan..."></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-upload mr-1"></i>
                                    Upload Bukti
                                </button>
                            </form>

                            @if ($temuan->buktiPerbaikan->isNotEmpty())
                                <hr style="border-color:#e8f5ee;">
                                <form action="{{ route('temuan.close', $temuan) }}" method="POST"
                                    onsubmit="return confirm('Yakin ingin menutup temuan ini?')">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-block">
                                        <i class="fas fa-lock mr-1"></i>
                                        Close Temuan
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endcan
            @endif

        </div>
    </div>
@endsection

@section('js')
    <script>
        function previewDraft(input) {
            const preview = document.getElementById('draftPreview');
            preview.innerHTML = '';
            input.nextElementSibling.textContent =
                input.files.length + ' foto dipilih';
            Array.from(input.files).forEach(file => {
                const reader = new FileReader();
                reader.onload = e => {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.cssText =
                        'width:70px;height:55px;object-fit:cover;border-radius:6px;';
                    preview.appendChild(img);
                };
                reader.readAsDataURL(file);
            });
        }
    </script>
@endsection

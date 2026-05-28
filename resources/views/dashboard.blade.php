@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="mb-0" style="font-size:22px; font-weight:700; color:#2d3748;">
                <i class="fas fa-tachometer-alt mr-2" style="color:#006b3f;"></i>
                Dashboard
            </h1>
            <small class="text-muted">
                {{ now()->isoFormat('dddd, D MMMM Y') }}
            </small>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge"
                style="background:#e8f5ee; color:#006b3f; font-size:12px;
                         padding:7px 14px; border-radius:20px; font-weight:600;">
                <i class="fas fa-circle mr-1" style="font-size:8px;"></i>
                Sistem Online
            </span>
        </div>
    </div>
@endsection

@section('content')

    {{-- Welcome Banner --}}
    <div class="welcome-banner mb-4"
        style="background: linear-gradient(135deg, #004d2e 0%, #006b3f 60%, #008a50 100%);
                border-radius: 14px; padding: 24px 30px;
                display: flex; justify-content: space-between;
                align-items: center; box-shadow: 0 4px 20px rgba(0,107,63,0.25);">
        <div>
            <h4 style="color:#ffffff; font-weight:700; margin-bottom:4px; font-size:18px;">
                Selamat datang, {{ $user->name }}! 👋
            </h4>
            <p style="color:rgba(255,255,255,0.75); margin:0; font-size:13px;">
                <i class="fas fa-id-badge mr-1"></i>
                {{ $user->jabatan ?? 'PLTU Tanjung Awar-Awar' }} &nbsp;|&nbsp;
                <i class="fas fa-user-tag mr-1"></i>
                {{ implode(', ', $user->getRoleNames()->toArray()) }}
            </p>
        </div>
        <div class="d-none d-md-flex align-items-center" style="gap:12px;">
            <div
                style="text-align:center; background:rgba(255,255,255,0.1);
                        border-radius:10px; padding:12px 20px;">
                <div style="font-size:22px; font-weight:700; color:#fff;">
                    {{ $stats['temuan_open'] }}
                </div>
                <div style="font-size:11px; color:rgba(255,255,255,0.7);">
                    Temuan Open
                </div>
            </div>
            <div
                style="text-align:center; background:rgba(255,255,255,0.1);
                        border-radius:10px; padding:12px 20px;">
                <div style="font-size:22px; font-weight:700; color:#ffc107;">
                    {{ $stats['program_overdue'] }}
                </div>
                <div style="font-size:11px; color:rgba(255,255,255,0.7);">
                    Program Overdue
                </div>
            </div>
        </div>
    </div>

    {{-- Stats Cards Row 1 --}}
    <div class="row">
        @php
            $cards = [
                [
                    'title' => 'HIRADC Approved',
                    'value' => $stats['hiradc_approved'],
                    'sub' => 'dari ' . $stats['hiradc_total'] . ' total',
                    'icon' => 'fas fa-file-alt',
                    'color' => '#17a2b8',
                    'url' => url('hiradc'),
                ],
                [
                    'title' => 'Total Live Audit',
                    'value' => $stats['live_audit_total'],
                    'sub' => 'Audit tercatat',
                    'icon' => 'fas fa-clipboard-check',
                    'color' => '#006b3f',
                    'url' => url('live-audit'),
                ],
                [
                    'title' => 'Temuan Open',
                    'value' => $stats['temuan_open'],
                    'sub' => 'Perlu ditangani',
                    'icon' => 'fas fa-exclamation-triangle',
                    'color' => '#f0a500',
                    'url' => url('temuan'),
                ],
                [
                    'title' => 'Program Overdue',
                    'value' => $stats['program_overdue'],
                    'sub' => 'Melewati deadline',
                    'icon' => 'fas fa-clock',
                    'color' => '#dc3545',
                    'url' => url('program-kerja'),
                ],
            ];
        @endphp

        @foreach ($cards as $card)
            <div class="col-lg-3 col-md-6 mb-3">
                <a href="{{ $card['url'] }}" style="text-decoration:none;">
                    <div class="stat-card"
                        style="background:#fff; border-radius:14px;
                                padding:20px 22px; box-shadow:0 2px 12px rgba(0,0,0,0.07);
                                transition:all 0.25s ease; cursor:pointer;
                                border-left:4px solid {{ $card['color'] }};
                                display:flex; justify-content:space-between;
                                align-items:center;">
                        <div>
                            <div
                                style="font-size:11px; font-weight:700;
                                        text-transform:uppercase; letter-spacing:0.8px;
                                        color:#718096; margin-bottom:6px;">
                                {{ $card['title'] }}
                            </div>
                            <div
                                style="font-size:32px; font-weight:800;
                                        color:#1a202c; line-height:1;">
                                {{ $card['value'] }}
                            </div>
                            <div style="font-size:11px; color:#a0aec0; margin-top:4px;">
                                {{ $card['sub'] }}
                            </div>
                        </div>
                        <div
                            style="width:52px; height:52px; border-radius:12px;
                                    background:{{ $card['color'] }}18;
                                    display:flex; align-items:center;
                                    justify-content:center;">
                            <i class="{{ $card['icon'] }}" style="font-size:22px; color:{{ $card['color'] }};"></i>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

    {{-- Stats Cards Row 2 --}}
    <div class="row mb-4">
        @php
            $cards2 = [
                [
                    'title' => 'Temuan Draft',
                    'value' => $stats['temuan_draft'],
                    'sub' => 'Perlu dilengkapi',
                    'icon' => 'fas fa-edit',
                    'color' => '#6f42c1',
                    'url' => url('temuan'),
                ],
                [
                    'title' => 'Temuan Closed',
                    'value' => $stats['temuan_closed'],
                    'sub' => 'Sudah diselesaikan',
                    'icon' => 'fas fa-check-circle',
                    'color' => '#00a65a',
                    'url' => url('temuan'),
                ],
                [
                    'title' => 'Program Berjalan',
                    'value' => $stats['program_open'],
                    'sub' => 'On progress',
                    'icon' => 'fas fa-spinner',
                    'color' => '#fd7e14',
                    'url' => url('program-kerja'),
                ],
                [
                    'title' => 'Program Selesai',
                    'value' => $programProgress['closed'],
                    'sub' => 'Sudah closed',
                    'icon' => 'fas fa-flag-checkered',
                    'color' => '#17a2b8',
                    'url' => url('program-kerja'),
                ],
            ];
        @endphp

        @foreach ($cards2 as $card)
            <div class="col-lg-3 col-md-6 mb-3">
                <a href="{{ $card['url'] }}" style="text-decoration:none;">
                    <div class="stat-card"
                        style="background:#fff; border-radius:14px;
                                padding:20px 22px; box-shadow:0 2px 12px rgba(0,0,0,0.07);
                                transition:all 0.25s ease; cursor:pointer;
                                border-left:4px solid {{ $card['color'] }};
                                display:flex; justify-content:space-between;
                                align-items:center;">
                        <div>
                            <div
                                style="font-size:11px; font-weight:700;
                                        text-transform:uppercase; letter-spacing:0.8px;
                                        color:#718096; margin-bottom:6px;">
                                {{ $card['title'] }}
                            </div>
                            <div
                                style="font-size:32px; font-weight:800;
                                        color:#1a202c; line-height:1;">
                                {{ $card['value'] }}
                            </div>
                            <div style="font-size:11px; color:#a0aec0; margin-top:4px;">
                                {{ $card['sub'] }}
                            </div>
                        </div>
                        <div
                            style="width:52px; height:52px; border-radius:12px;
                                    background:{{ $card['color'] }}18;
                                    display:flex; align-items:center;
                                    justify-content:center;">
                            <i class="{{ $card['icon'] }}" style="font-size:22px; color:{{ $card['color'] }};"></i>
                        </div>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

    {{-- Charts Row --}}
    <div class="row">
        {{-- Grafik UA vs UC per Bulan --}}
        <div class="col-md-8 mb-3">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">
                        <i class="fas fa-chart-bar mr-2" style="color:#006b3f;"></i>
                        Temuan UA / UC / Near Miss
                    </h3>
                    <span style="font-size:11px; color:#a0aec0;">12 Bulan Terakhir</span>
                </div>
                <div class="card-body">
                    <canvas id="chartUaUc" height="110"></canvas>
                </div>
            </div>
        </div>

        {{-- Donut Status Temuan --}}
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-pie mr-2" style="color:#006b3f;"></i>
                        Status Temuan
                    </h3>
                </div>
                <div class="card-body d-flex flex-column justify-content-center">
                    <canvas id="chartStatusTemuan" height="180"></canvas>
                    <div class="mt-3">
                        @php
                            $statusItems = [
                                ['label' => 'Draft', 'color' => '#6c757d', 'val' => $chartStatusTemuan['draft']],
                                ['label' => 'Open', 'color' => '#ffc107', 'val' => $chartStatusTemuan['open']],
                                [
                                    'label' => 'Validated V1',
                                    'color' => '#17a2b8',
                                    'val' => $chartStatusTemuan['validated_v1'],
                                ],
                                [
                                    'label' => 'Validated V2',
                                    'color' => '#006b3f',
                                    'val' => $chartStatusTemuan['validated_v2'],
                                ],
                                ['label' => 'Closed', 'color' => '#00a65a', 'val' => $chartStatusTemuan['closed']],
                            ];
                        @endphp
                        @foreach ($statusItems as $s)
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span style="font-size:12px; color:#4a5568;">
                                    <span
                                        style="display:inline-block; width:10px; height:10px;
                                                 background:{{ $s['color'] }}; border-radius:50%;
                                                 margin-right:6px;"></span>
                                    {{ $s['label'] }}
                                </span>
                                <span
                                    style="font-size:12px; font-weight:700;
                                             color:#2d3748;">{{ $s['val'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts Row 2 --}}
    <div class="row">
        {{-- Top 5 Lokasi --}}
        <div class="col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-map-marker-alt mr-2" style="color:#006b3f;"></i>
                        Top 5 Lokasi Temuan
                    </h3>
                </div>
                <div class="card-body">
                    <canvas id="chartLokasi" height="180"></canvas>
                </div>
            </div>
        </div>

        {{-- Progress Program Kerja --}}
        <div class="col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-tasks mr-2" style="color:#006b3f;"></i>
                        Progress Program Kerja
                    </h3>
                </div>
                <div class="card-body">
                    @php
                        $totalProgram = array_sum($programProgress);
                        $progressItems = [
                            ['label' => 'Open', 'color' => 'secondary', 'hex' => '#6c757d', 'key' => 'open'],
                            ['label' => 'On Progress', 'color' => 'info', 'hex' => '#17a2b8', 'key' => 'on_progress'],
                            ['label' => 'Overdue', 'color' => 'danger', 'hex' => '#dc3545', 'key' => 'overdue'],
                            ['label' => 'Closed', 'color' => 'success', 'hex' => '#00a65a', 'key' => 'closed'],
                        ];
                    @endphp

                    @foreach ($progressItems as $item)
                        @php
                            $pct =
                                $totalProgram > 0 ? round(($programProgress[$item['key']] / $totalProgram) * 100) : 0;
                        @endphp
                        <div class="mb-4">
                            <div class="d-flex justify-content-between mb-1">
                                <span
                                    style="font-size:13px; font-weight:600;
                                             color:#4a5568;">
                                    <span
                                        style="display:inline-block; width:10px;
                                                 height:10px; background:{{ $item['hex'] }};
                                                 border-radius:3px; margin-right:8px;"></span>
                                    {{ $item['label'] }}
                                </span>
                                <span style="font-size:13px; color:#718096;">
                                    <strong>{{ $programProgress[$item['key']] }}</strong>
                                    program ({{ $pct }}%)
                                </span>
                            </div>
                            <div class="progress" style="height:10px; border-radius:20px;">
                                <div class="progress-bar bg-{{ $item['color'] }}"
                                    style="width:{{ $pct }}%; border-radius:20px;">
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <div style="text-align:right; font-size:12px; color:#a0aec0;">
                        Total: {{ $totalProgram }} program kerja
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tables Row --}}
    <div class="row">
        {{-- Temuan Terbaru --}}
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">
                        <i class="fas fa-exclamation-triangle mr-2" style="color:#006b3f;"></i>
                        Temuan Terbaru
                    </h3>
                    <a href="{{ url('temuan') }}"
                        style="font-size:12px; color:#006b3f;
                              text-decoration:none; font-weight:600;">
                        Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Judul Temuan</th>
                                <th>Kategori</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($temuanTerbaru as $temuan)
                                <tr onclick="window.location='{{ route('temuan.show', $temuan) }}'"
                                    style="cursor:pointer;">
                                    <td>
                                        <div
                                            style="font-size:13px; font-weight:500;
                                                    color:#2d3748;">
                                            {{ Str::limit($temuan->judul_temuan, 35) }}
                                        </div>
                                        <div style="font-size:11px; color:#a0aec0;">
                                            {{ $temuan->reporter->name }} ·
                                            {{ $temuan->created_at->diffForHumans() }}
                                        </div>
                                    </td>
                                    <td>{!! $temuan->kategori_badge !!}</td>
                                    <td>{!! $temuan->status_badge !!}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">
                                        <i class="fas fa-inbox fa-2x mb-2 d-block" style="color:#e2e8f0;"></i>
                                        Belum ada temuan
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Live Audit Terbaru --}}
        <div class="col-md-6 mb-3">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">
                        <i class="fas fa-clipboard-check mr-2" style="color:#006b3f;"></i>
                        Live Audit Terbaru
                    </h3>
                    <a href="{{ url('live-audit') }}"
                        style="font-size:12px; color:#006b3f;
                              text-decoration:none; font-weight:600;">
                        Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Pekerjaan</th>
                                <th>Perusahaan</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($liveAuditTerbaru as $audit)
                                <tr onclick="window.location='{{ route('live-audit.show', $audit) }}'"
                                    style="cursor:pointer;">
                                    <td>
                                        <div
                                            style="font-size:13px; font-weight:500;
                                                    color:#2d3748;">
                                            {{ Str::limit($audit->nama_pekerjaan, 35) }}
                                        </div>
                                        <div style="font-size:11px; color:#a0aec0;">
                                            {{ $audit->creator->name }} ·
                                            {{ $audit->created_at->diffForHumans() }}
                                        </div>
                                    </td>
                                    <td style="font-size:13px;">
                                        {{ Str::limit($audit->perusahaan, 20) }}
                                    </td>
                                    <td>{!! $audit->status_badge !!}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">
                                        <i class="fas fa-inbox fa-2x mb-2 d-block" style="color:#e2e8f0;"></i>
                                        Belum ada live audit
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Program Kerja Overdue --}}
    @if ($programOverdue->isNotEmpty())
        <div class="row">
            <div class="col-12 mb-3">
                <div class="card" style="border-left:4px solid #dc3545 !important;">
                    <div class="card-header d-flex justify-content-between align-items-center"
                        style="background:#fff8f8 !important;
                                border-bottom:2px solid #f8d7da !important;">
                        <h3 class="card-title" style="color:#dc3545 !important;">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            Program Kerja Overdue
                        </h3>
                        <span class="badge badge-danger" style="font-size:12px;">
                            {{ $programOverdue->count() }} program
                        </span>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Nama Program</th>
                                    <th>HIRADC</th>
                                    <th>PIC</th>
                                    <th>Deadline</th>
                                    <th width="8%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($programOverdue as $program)
                                    <tr>
                                        <td>
                                            <div style="font-size:13px; font-weight:500;">
                                                {{ Str::limit($program->nama_program, 45) }}
                                            </div>
                                        </td>
                                        <td>
                                            <small style="color:#718096;">
                                                {{ Str::limit($program->hiradc->judul, 35) }}
                                            </small>
                                        </td>
                                        <td style="font-size:13px;">{{ $program->pic }}</td>
                                        <td>
                                            <span
                                                style="color:#dc3545; font-weight:700;
                                                         font-size:13px;">
                                                {{ $program->deadline->format('d/m/Y') }}
                                            </span>
                                            <div style="font-size:11px; color:#a0aec0;">
                                                {{ $program->deadline->diffForHumans() }}
                                            </div>
                                        </td>
                                        <td>
                                            <a href="{{ route('program-kerja.show', $program) }}"
                                                class="btn btn-sm btn-warning">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection

@section('css')
    <style>
        .stat-card:hover {
            transform: translateY(-3px) !important;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12) !important;
        }
    </style>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <script>
        // Chart defaults
        Chart.defaults.font.family = "'Segoe UI', Tahoma, sans-serif";
        Chart.defaults.font.size = 12;
        Chart.defaults.color = '#718096';

        // ============================================================
        // Chart 1: UA vs UC vs Near Miss per Bulan
        // ============================================================
        new Chart(document.getElementById('chartUaUc'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($chartUaUc['labels']) !!},
                datasets: [{
                        label: 'Unsafe Action',
                        data: {!! json_encode($chartUaUc['ua']) !!},
                        backgroundColor: 'rgba(220,53,69,0.8)',
                        borderColor: '#dc3545',
                        borderWidth: 0,
                        borderRadius: 5,
                    },
                    {
                        label: 'Unsafe Condition',
                        data: {!! json_encode($chartUaUc['uc']) !!},
                        backgroundColor: 'rgba(240,165,0,0.85)',
                        borderColor: '#f0a500',
                        borderWidth: 0,
                        borderRadius: 5,
                    },
                    {
                        label: 'Near Miss',
                        data: {!! json_encode($chartUaUc['near_miss']) !!},
                        backgroundColor: 'rgba(23,162,184,0.8)',
                        borderColor: '#17a2b8',
                        borderWidth: 0,
                        borderRadius: 5,
                    },
                ],
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            pointStyle: 'circle',
                            padding: 20,
                        },
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        },
                        grid: {
                            color: 'rgba(0,0,0,0.05)'
                        },
                    },
                    x: {
                        grid: {
                            display: false
                        },
                    },
                },
            },
        });

        // ============================================================
        // Chart 2: Status Temuan (Donut)
        // ============================================================
        new Chart(document.getElementById('chartStatusTemuan'), {
            type: 'doughnut',
            data: {
                labels: ['Draft', 'Open', 'Validated V1', 'Validated V2', 'Closed'],
                datasets: [{
                    data: [
                        {{ $chartStatusTemuan['draft'] }},
                        {{ $chartStatusTemuan['open'] }},
                        {{ $chartStatusTemuan['validated_v1'] }},
                        {{ $chartStatusTemuan['validated_v2'] }},
                        {{ $chartStatusTemuan['closed'] }},
                    ],
                    backgroundColor: [
                        '#6c757d',
                        '#ffc107',
                        '#17a2b8',
                        '#006b3f',
                        '#00a65a',
                    ],
                    borderWidth: 0,
                    hoverOffset: 6,
                }],
            },
            options: {
                responsive: true,
                cutout: '70%',
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: ctx =>
                                ` ${ctx.label}: ${ctx.parsed} temuan`,
                        },
                    },
                },
            },
        });

        // ============================================================
        // Chart 3: Top 5 Lokasi (Horizontal Bar)
        // ============================================================
        new Chart(document.getElementById('chartLokasi'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($temuanPerLokasi->pluck('lokasi')) !!},
                datasets: [{
                    label: 'Jumlah Temuan',
                    data: {!! json_encode($temuanPerLokasi->pluck('total')) !!},
                    backgroundColor: [
                        'rgba(0,107,63,0.85)',
                        'rgba(0,138,80,0.8)',
                        'rgba(0,166,90,0.75)',
                        'rgba(23,162,184,0.8)',
                        'rgba(111,66,193,0.8)',
                    ],
                    borderWidth: 0,
                    borderRadius: 5,
                }],
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        },
                        grid: {
                            color: 'rgba(0,0,0,0.05)'
                        },
                    },
                    y: {
                        grid: {
                            display: false
                        },
                    },
                },
            },
        });
    </script>
@endsection

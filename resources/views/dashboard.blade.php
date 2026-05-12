@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@endsection

@section('content')
    {{-- Welcome --}}
    <div class="alert alert-info alert-dismissible fade show">
        <i class="fas fa-user mr-1"></i>
        Selamat datang, <strong>{{ $user->name }}</strong>!
        <small class="ml-2 text-muted">
            ({{ implode(', ', $user->getRoleNames()->toArray()) }})
        </small>
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    </div>

    {{-- Stats Cards Row 1 --}}
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $stats['hiradc_approved'] }}
                        <sup style="font-size:16px">/ {{ $stats['hiradc_total'] }}</sup>
                    </h3>
                    <p>HIRADC Approved</p>
                </div>
                <div class="icon"><i class="fas fa-file-alt"></i></div>
                <a href="{{ url('hiradc') }}" class="small-box-footer">
                    Lihat semua <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $stats['live_audit_total'] }}</h3>
                    <p>Total Live Audit</p>
                </div>
                <div class="icon"><i class="fas fa-clipboard-check"></i></div>
                <a href="{{ url('live-audit') }}" class="small-box-footer">
                    Lihat semua <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $stats['temuan_open'] }}</h3>
                    <p>Temuan Open</p>
                </div>
                <div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
                <a href="{{ url('temuan') }}" class="small-box-footer">
                    Lihat semua <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $stats['program_overdue'] }}</h3>
                    <p>Program Kerja Overdue</p>
                </div>
                <div class="icon"><i class="fas fa-tasks"></i></div>
                <a href="{{ url('program-kerja') }}" class="small-box-footer">
                    Lihat semua <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- Stats Cards Row 2 --}}
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box" style="background:#6f42c1; color:white;">
                <div class="inner">
                    <h3>{{ $stats['temuan_draft'] }}</h3>
                    <p>Temuan Draft (Perlu Dilengkapi)</p>
                </div>
                <div class="icon"><i class="fas fa-edit"></i></div>
                <a href="{{ url('temuan') }}" class="small-box-footer" style="background:rgba(0,0,0,0.1);">
                    Lihat semua <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box" style="background:#20c997; color:white;">
                <div class="inner">
                    <h3>{{ $stats['temuan_closed'] }}</h3>
                    <p>Temuan Closed</p>
                </div>
                <div class="icon"><i class="fas fa-check-circle"></i></div>
                <a href="{{ url('temuan') }}" class="small-box-footer" style="background:rgba(0,0,0,0.1);">
                    Lihat semua <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box" style="background:#fd7e14; color:white;">
                <div class="inner">
                    <h3>{{ $stats['program_open'] }}</h3>
                    <p>Program Kerja Berjalan</p>
                </div>
                <div class="icon"><i class="fas fa-spinner"></i></div>
                <a href="{{ url('program-kerja') }}" class="small-box-footer" style="background:rgba(0,0,0,0.1);">
                    Lihat semua <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box" style="background:#17a2b8; color:white;">
                <div class="inner">
                    <h3>{{ $programProgress['closed'] }}</h3>
                    <p>Program Kerja Selesai</p>
                </div>
                <div class="icon"><i class="fas fa-flag-checkered"></i></div>
                <a href="{{ url('program-kerja') }}" class="small-box-footer" style="background:rgba(0,0,0,0.1);">
                    Lihat semua <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- Charts Row --}}
    <div class="row">
        {{-- Grafik UA vs UC per Bulan --}}
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-bar mr-1"></i>
                        Temuan UA / UC / Near Miss (12 Bulan Terakhir)
                    </h3>
                </div>
                <div class="card-body">
                    <canvas id="chartUaUc" height="120"></canvas>
                </div>
            </div>
        </div>

        {{-- Donut Status Temuan --}}
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-pie mr-1"></i>
                        Status Temuan
                    </h3>
                </div>
                <div class="card-body">
                    <canvas id="chartStatusTemuan" height="200"></canvas>
                    <div class="mt-3">
                        <div class="d-flex justify-content-between small mb-1">
                            <span><i class="fas fa-circle text-secondary mr-1"></i>Draft</span>
                            <strong>{{ $chartStatusTemuan['draft'] }}</strong>
                        </div>
                        <div class="d-flex justify-content-between small mb-1">
                            <span><i class="fas fa-circle text-warning mr-1"></i>Open</span>
                            <strong>{{ $chartStatusTemuan['open'] }}</strong>
                        </div>
                        <div class="d-flex justify-content-between small mb-1">
                            <span><i class="fas fa-circle text-info mr-1"></i>Validated V1</span>
                            <strong>{{ $chartStatusTemuan['validated_v1'] }}</strong>
                        </div>
                        <div class="d-flex justify-content-between small mb-1">
                            <span><i class="fas fa-circle text-primary mr-1"></i>Validated V2</span>
                            <strong>{{ $chartStatusTemuan['validated_v2'] }}</strong>
                        </div>
                        <div class="d-flex justify-content-between small">
                            <span><i class="fas fa-circle text-success mr-1"></i>Closed</span>
                            <strong>{{ $chartStatusTemuan['closed'] }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts Row 2 --}}
    <div class="row">
        {{-- Top 5 Lokasi Temuan --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-map-marker-alt mr-1"></i>
                        Top 5 Lokasi Temuan
                    </h3>
                </div>
                <div class="card-body">
                    <canvas id="chartLokasi" height="200"></canvas>
                </div>
            </div>
        </div>

        {{-- Progress Program Kerja --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-tasks mr-1"></i>
                        Progress Program Kerja
                    </h3>
                </div>
                <div class="card-body">
                    @php
                        $totalProgram = array_sum($programProgress);
                    @endphp

                    @foreach ([
            'open' => ['label' => 'Open', 'color' => 'secondary'],
            'on_progress' => ['label' => 'On Progress', 'color' => 'info'],
            'overdue' => ['label' => 'Overdue', 'color' => 'danger'],
            'closed' => ['label' => 'Closed', 'color' => 'success'],
        ] as $key => $item)
                        @php
                            $pct = $totalProgram > 0 ? round(($programProgress[$key] / $totalProgram) * 100) : 0;
                        @endphp
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span>{{ $item['label'] }}</span>
                                <span>
                                    <strong>{{ $programProgress[$key] }}</strong>
                                    ({{ $pct }}%)
                                </span>
                            </div>
                            <div class="progress" style="height: 18px;">
                                <div class="progress-bar bg-{{ $item['color'] }}" style="width: {{ $pct }}%">
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <div class="text-muted small text-right mt-2">
                        Total: {{ $totalProgram }} program kerja
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tables Row --}}
    <div class="row">
        {{-- Temuan Terbaru --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        Temuan Terbaru
                    </h3>
                    <div class="card-tools">
                        <a href="{{ url('temuan') }}" class="btn btn-sm btn-outline-primary">
                            Lihat Semua
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Judul</th>
                                <th>Kategori</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($temuanTerbaru as $temuan)
                                <tr onclick="window.location='{{ route('temuan.show', $temuan) }}'"
                                    style="cursor:pointer;">
                                    <td>{{ Str::limit($temuan->judul_temuan, 30) }}</td>
                                    <td>{!! $temuan->kategori_badge !!}</td>
                                    <td>{!! $temuan->status_badge !!}</td>
                                    <td>
                                        <small>{{ $temuan->created_at->format('d/m/Y') }}</small>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">
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
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-clipboard-check mr-1"></i>
                        Live Audit Terbaru
                    </h3>
                    <div class="card-tools">
                        <a href="{{ url('live-audit') }}" class="btn btn-sm btn-outline-primary">
                            Lihat Semua
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th>Pekerjaan</th>
                                <th>Perusahaan</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($liveAuditTerbaru as $audit)
                                <tr onclick="window.location='{{ route('live-audit.show', $audit) }}'"
                                    style="cursor:pointer;">
                                    <td>{{ Str::limit($audit->nama_pekerjaan, 30) }}</td>
                                    <td>{{ Str::limit($audit->perusahaan, 20) }}</td>
                                    <td>{!! $audit->status_badge !!}</td>
                                    <td>
                                        <small>{{ $audit->created_at->format('d/m/Y') }}</small>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">
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
            <div class="col-12">
                <div class="card border-danger">
                    <div class="card-header bg-danger">
                        <h3 class="card-title text-white">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            Program Kerja Overdue
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-sm table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Nama Program</th>
                                    <th>HIRADC</th>
                                    <th>PIC</th>
                                    <th>Deadline</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($programOverdue as $program)
                                    <tr>
                                        <td>{{ Str::limit($program->nama_program, 40) }}</td>
                                        <td>
                                            <small>
                                                {{ Str::limit($program->hiradc->judul, 30) }}
                                            </small>
                                        </td>
                                        <td>{{ $program->pic }}</td>
                                        <td>
                                            <span class="text-danger font-weight-bold">
                                                {{ $program->deadline->format('d/m/Y') }}
                                            </span>
                                            <small class="text-muted d-block">
                                                {{ $program->deadline->diffForHumans() }}
                                            </small>
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

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <script>
        // ================================================================
        // Chart 1: UA vs UC vs Near Miss per Bulan
        // ================================================================
        const ctxUaUc = document.getElementById('chartUaUc').getContext('2d');
        new Chart(ctxUaUc, {
            type: 'bar',
            data: {
                labels: {!! json_encode($chartUaUc['labels']) !!},
                datasets: [{
                        label: 'Unsafe Action',
                        data: {!! json_encode($chartUaUc['ua']) !!},
                        backgroundColor: 'rgba(220, 53, 69, 0.8)',
                        borderColor: 'rgba(220, 53, 69, 1)',
                        borderWidth: 1,
                    },
                    {
                        label: 'Unsafe Condition',
                        data: {!! json_encode($chartUaUc['uc']) !!},
                        backgroundColor: 'rgba(255, 193, 7, 0.8)',
                        borderColor: 'rgba(255, 193, 7, 1)',
                        borderWidth: 1,
                    },
                    {
                        label: 'Near Miss',
                        data: {!! json_encode($chartUaUc['near_miss']) !!},
                        backgroundColor: 'rgba(23, 162, 184, 0.8)',
                        borderColor: 'rgba(23, 162, 184, 1)',
                        borderWidth: 1,
                    },
                ],
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top'
                    },
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        },
                    },
                },
            },
        });

        // ================================================================
        // Chart 2: Status Temuan (Donut)
        // ================================================================
        const ctxStatus = document.getElementById('chartStatusTemuan').getContext('2d');
        new Chart(ctxStatus, {
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
                        '#007bff',
                        '#28a745',
                    ],
                }],
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                },
                cutout: '65%',
            },
        });

        // ================================================================
        // Chart 3: Top 5 Lokasi Temuan (Horizontal Bar)
        // ================================================================
        const ctxLokasi = document.getElementById('chartLokasi').getContext('2d');
        new Chart(ctxLokasi, {
            type: 'bar',
            data: {
                labels: {!! json_encode($temuanPerLokasi->pluck('lokasi')) !!},
                datasets: [{
                    label: 'Jumlah Temuan',
                    data: {!! json_encode($temuanPerLokasi->pluck('total')) !!},
                    backgroundColor: [
                        'rgba(220, 53, 69, 0.8)',
                        'rgba(255, 193, 7, 0.8)',
                        'rgba(23, 162, 184, 0.8)',
                        'rgba(40, 167, 69, 0.8)',
                        'rgba(111, 66, 193, 0.8)',
                    ],
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
                    },
                },
            },
        });

        // ================================================================
        // Update badge sidebar setiap 60 detik
        // ================================================================
        setInterval(function() {
            fetch('/api/sidebar-counts')
                .then(r => r.json())
                .then(data => {
                    const badge = document.querySelector('.temuan-draft-badge');

                    if (badge && data.draft > 0) {
                        badge.textContent = data.draft;
                        badge.style.display = 'inline';
                    }
                })
                .catch(() => {});
        }, 60000);
    </script>
@endsection

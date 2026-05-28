@extends('adminlte::page')
@section('title', 'Pelaporan Temuan UA/UC')

@section('content_header')
    <x-page-header title="Pelaporan Temuan UA/UC" subtitle="Laporan unsafe action, unsafe condition, near miss & positive"
        icon="fas fa-exclamation-triangle">
        @can('temuan.create')
            <a href="{{ route('temuan.create') }}" class="btn btn-primary">
                <i class="fas fa-plus mr-1"></i> Laporkan Temuan
            </a>
        @endcan
    </x-page-header>
@endsection

@section('content')
    @if (session('success'))
        <x-alert type="success">{{ session('success') }}</x-alert>
    @endif

    {{-- Summary Cards --}}
    <div class="row mb-4">
        @php
            $tDraft = \App\Models\Temuan::where('status', 'draft')->count();
            $tOpen = \App\Models\Temuan::where('status', 'open')->count();
            $tClosed = \App\Models\Temuan::where('status', 'closed')->count();
            $tTotal = \App\Models\Temuan::count();
        @endphp
        <div class="col-md-3 col-6 mb-2">
            <x-stat-mini label="Total" :value="$tTotal" color="#006b3f" />
        </div>
        <div class="col-md-3 col-6 mb-2">
            <x-stat-mini label="Draft" :value="$tDraft" color="#6c757d" />
        </div>
        <div class="col-md-3 col-6 mb-2">
            <x-stat-mini label="Open" :value="$tOpen" color="#f0a500" />
        </div>
        <div class="col-md-3 col-6 mb-2">
            <x-stat-mini label="Closed" :value="$tClosed" color="#00a65a" />
        </div>
    </div>

    {{-- Filter Pills --}}
    @php
        $filterStatus = request('status', 'all');
    @endphp
    <div class="mb-3 d-flex flex-wrap" style="gap:8px;">
        @foreach ([
            'all' => ['label' => 'Semua', 'color' => '#718096'],
            'draft' => ['label' => 'Draft', 'color' => '#6c757d'],
            'open' => ['label' => 'Open', 'color' => '#f0a500'],
            'validated_v1' => ['label' => 'Validated V1', 'color' => '#17a2b8'],
            'validated_v2' => ['label' => 'Validated V2', 'color' => '#006b3f'],
            'closed' => ['label' => 'Closed', 'color' => '#00a65a'],
        ] as $key => $item)
            <a href="{{ route('temuan.index', ['status' => $key]) }}"
                style="padding:6px 16px; border-radius:20px; font-size:12px;
                      font-weight:600; text-decoration:none; transition:all 0.2s;
                      background:{{ $filterStatus === $key ? $item['color'] : '#f4f6f9' }};
                      color:{{ $filterStatus === $key ? '#fff' : '#718096' }};
                      border:1px solid {{ $filterStatus === $key ? $item['color'] : '#e2e8f0' }};">
                {{ $item['label'] }}
            </a>
        @endforeach
    </div>

    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Judul Temuan</th>
                        <th>Lokasi</th>
                        <th>Kategori</th>
                        <th>Dilaporkan Oleh</th>
                        <th>Tanggal</th>
                        <th width="12%">Status</th>
                        <th width="6%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($temuans as $index => $temuan)
                        <tr>
                            <td style="color:#a0aec0; font-size:12px;">
                                {{ $temuans->firstItem() + $index }}
                            </td>
                            <td>
                                <div style="font-size:13px; font-weight:600; color:#2d3748;">
                                    {{ Str::limit($temuan->judul_temuan, 45) }}
                                    @if ($temuan->live_audit_id)
                                        <span
                                            style="background:#e8f5ee; color:#006b3f;
                                                     font-size:10px; padding:2px 7px;
                                                     border-radius:10px; font-weight:600;
                                                     margin-left:4px;">
                                            <i class="fas fa-link" style="font-size:9px;"></i>
                                            WIP
                                        </span>
                                    @endif
                                </div>
                                @if ($temuan->distrik)
                                    <div style="font-size:11px; color:#a0aec0;">
                                        {{ $temuan->distrik }}
                                    </div>
                                @endif
                            </td>
                            <td style="font-size:13px;">
                                {{ $temuan->lokasi ?? '-' }}
                            </td>
                            <td>{!! $temuan->kategori_badge !!}</td>
                            <td>
                                <div style="display:flex; align-items:center; gap:8px;">
                                    <div
                                        style="width:28px; height:28px; border-radius:50%;
                                                background:#006b3f; color:#fff; font-size:11px;
                                                display:flex; align-items:center;
                                                justify-content:center; font-weight:700;
                                                flex-shrink:0;">
                                        {{ strtoupper(substr($temuan->reporter->name, 0, 1)) }}
                                    </div>
                                    <span style="font-size:13px;">
                                        {{ $temuan->reporter->name }}
                                    </span>
                                </div>
                            </td>
                            <td style="font-size:12px; color:#718096;">
                                {{ $temuan->created_at->format('d M Y') }}
                                <div style="font-size:10px; color:#cbd5e0;">
                                    {{ $temuan->created_at->diffForHumans() }}
                                </div>
                            </td>
                            <td>{!! $temuan->status_badge !!}</td>
                            <td class="text-center">
                                <a href="{{ route('temuan.show', $temuan) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <x-empty-state icon="fas fa-exclamation-triangle" message="Belum ada temuan"
                                    sub="Laporkan temuan pertama menggunakan tombol di atas" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($temuans->hasPages())
            <div class="card-footer">
                {{ $temuans->links() }}
            </div>
        @endif
    </div>
@endsection

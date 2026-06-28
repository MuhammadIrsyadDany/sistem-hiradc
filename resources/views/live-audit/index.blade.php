@extends('adminlte::page')
@section('title', 'Live Audit')

@section('content_header')
    <x-page-header title="Live Audit / WIP" subtitle="Work In Practise — pemeriksaan keselamatan pekerjaan"
        icon="fas fa-clipboard-check">
        @can('live_audit.create')
            <a href="{{ route('live-audit.create') }}" class="btn btn-primary">
                <i class="fas fa-plus mr-1"></i> Buat Live Audit
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
            $laTotal = \App\Models\LiveAudit::count();
            $laPending = \App\Models\LiveAudit::whereIn('status', ['pending_v1', 'pending_v2'])->count();
            $laApproved = \App\Models\LiveAudit::where('status', 'approved')->count();
            $laStopped = \App\Models\LiveAudit::where('is_stopped', true)->count();
        @endphp
        <div class="col-md-3 col-6 mb-2">
            <x-stat-mini label="Total" :value="$laTotal" color="#006b3f" />
        </div>
        <div class="col-md-3 col-6 mb-2">
            <x-stat-mini label="Pending" :value="$laPending" color="#f0a500" />
        </div>
        <div class="col-md-3 col-6 mb-2">
            <x-stat-mini label="Approved" :value="$laApproved" color="#00a65a" />
        </div>
        <div class="col-md-3 col-6 mb-2">
            <x-stat-mini label="STOP" :value="$laStopped" color="#dc3545" />
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Daftar Live Audit / WIP</h3>
            <form action="" method="GET" class="form-inline ml-auto">
                <div class="input-group input-group-sm" style="width: 250px;">
                    <input type="text" name="search" class="form-control" placeholder="Cari pekerjaan, perusahaan..." value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Nama Pekerjaan</th>
                        <th>Perusahaan</th>
                        <th>No WO</th>
                        <th>Dibuat Oleh</th>
                        <th>Tanggal Mulai</th>
                        <th width="15%">Status</th>
                        <th width="8%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($liveAudits as $index => $audit)
                        <tr>
                            <td style="color:#a0aec0; font-size:12px;">
                                {{ $liveAudits->firstItem() + $index }}
                            </td>
                            <td>
                                <div style="font-size:13px; font-weight:600; color:#2d3748;">
                                    {{ Str::limit($audit->nama_pekerjaan, 45) }}
                                </div>
                                @if ($audit->is_stopped)
                                    <span
                                        style="background:#f8d7da; color:#dc3545;
                                                 font-size:10px; padding:2px 8px;
                                                 border-radius:10px; font-weight:700;">
                                        <i class="fas fa-stop-circle mr-1"></i>STOP
                                    </span>
                                @endif
                            </td>
                            <td style="font-size:13px;">
                                {{ Str::limit($audit->perusahaan, 25) }}
                            </td>
                            <td style="font-size:12px; color:#718096;">
                                {{ $audit->no_work_order ?? '-' }}
                            </td>
                            <td>
                                <div style="display:flex; align-items:center; gap:8px;">
                                    <div
                                        style="width:28px; height:28px; border-radius:50%;
                                                background:#006b3f; color:#fff; font-size:11px;
                                                display:flex; align-items:center;
                                                justify-content:center; font-weight:700;
                                                flex-shrink:0;">
                                        {{ strtoupper(substr($audit->creator->name, 0, 1)) }}
                                    </div>
                                    <span style="font-size:13px;">
                                        {{ $audit->creator->name }}
                                    </span>
                                </div>
                            </td>
                            <td style="font-size:12px; color:#718096;">
                                {{ $audit->tanggal_mulai->format('d M Y') }}
                            </td>
                            <td>{!! $audit->status_badge !!}</td>
                            <td class="text-center">
                                <a href="{{ route('live-audit.show', $audit) }}" class="btn btn-sm btn-primary"
                                    title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if ($audit->status === 'approved')
                                    <a href="{{ route('live-audit.export-pdf', $audit) }}" class="btn btn-sm btn-danger"
                                        title="Export PDF">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <x-empty-state icon="fas fa-clipboard-check" message="Belum ada data live audit"
                                    sub="Buat live audit pertama menggunakan tombol di atas" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($liveAudits->hasPages())
            <div class="card-footer">
                {{ $liveAudits->links() }}
            </div>
        @endif
    </div>
@endsection

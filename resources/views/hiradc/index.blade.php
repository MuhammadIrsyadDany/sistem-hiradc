@extends('adminlte::page')
@section('title', 'Dokumen HIRADC')

@section('content_header')
    <x-page-header title="Dokumen HIRADC" subtitle="Database identifikasi bahaya dan penilaian risiko per area"
        icon="fas fa-file-alt">
        @can('hiradc.create')
            <a href="{{ route('hiradc.create') }}" class="btn btn-primary">
                <i class="fas fa-plus mr-1"></i> Upload HIRADC
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
            $total = $documents->total();
            $totalAktivitas = \App\Models\HiradcAktivitas::count();
            $totalAspek = \App\Models\HiradcAspekBahaya::count();
            $totalTinggi = \App\Models\HiradcAspekBahaya::whereIn('level_risiko', [
                'tinggi',
                'sangat_tinggi',
                'ekstrim',
            ])->count();
        @endphp
        <div class="col-md-3 col-6 mb-2">
            <x-stat-mini label="Total Dokumen" :value="$total" color="#006b3f" />
        </div>
        <div class="col-md-3 col-6 mb-2">
            <x-stat-mini label="Total Aktivitas" :value="$totalAktivitas" color="#17a2b8" />
        </div>
        <div class="col-md-3 col-6 mb-2">
            <x-stat-mini label="Total Aspek Bahaya" :value="$totalAspek" color="#f0a500" />
        </div>
        <div class="col-md-3 col-6 mb-2">
            <x-stat-mini label="Risiko Tinggi+" :value="$totalTinggi" color="#dc3545" />
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Daftar Dokumen HIRADC</h3>
            <form action="" method="GET" class="form-inline ml-auto">
                <div class="input-group input-group-sm" style="width: 250px;">
                    <input type="text" name="search" class="form-control" placeholder="Cari area, unit, no dok..." value="{{ request('search') }}">
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
                        <th>Nama Area</th>
                        <th>Unit / Divisi</th>
                        <th>Diupload Oleh</th>
                        <th class="text-center">Aktivitas</th>
                        <th class="text-center">Aspek Bahaya</th>
                        <th>Tanggal</th>
                        <th width="8%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($documents as $index => $doc)
                        <tr>
                            <td style="color:#a0aec0; font-size:12px;">
                                {{ $documents->firstItem() + $index }}
                            </td>
                            <td>
                                <div
                                    style="font-size:13px; font-weight:600;
                                            color:#2d3748;">
                                    {{ $doc->nama_area }}
                                </div>
                                @if ($doc->no_dokumen)
                                    <div style="font-size:11px; color:#a0aec0;">
                                        No: {{ $doc->no_dokumen }}
                                        @if ($doc->tahun)
                                            · {{ $doc->tahun }}
                                        @endif
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div style="font-size:13px; color:#2d3748;">
                                    {{ $doc->unit ?? '-' }}
                                </div>
                                <div style="font-size:11px; color:#a0aec0;">
                                    {{ $doc->divisi ?? '-' }}
                                </div>
                            </td>
                            <td>
                                <div style="display:flex; align-items:center; gap:8px;">
                                    <div
                                        style="width:28px; height:28px; border-radius:50%;
                                                background:#006b3f; color:#fff; font-size:11px;
                                                display:flex; align-items:center;
                                                justify-content:center; font-weight:700;">
                                        {{ strtoupper(substr($doc->uploader->name, 0, 1)) }}
                                    </div>
                                    <span style="font-size:13px;">
                                        {{ $doc->uploader->name }}
                                    </span>
                                </div>
                            </td>
                            <td class="text-center">
                                <span
                                    style="background:#e8f5ee; color:#006b3f;
                                             font-size:13px; font-weight:700;
                                             padding:3px 12px; border-radius:20px;">
                                    {{ $doc->aktivitas->count() }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span
                                    style="background:#fff3cd; color:#856404;
                                             font-size:13px; font-weight:700;
                                             padding:3px 12px; border-radius:20px;">
                                    {{ $doc->aktivitas->sum(fn($a) => $a->aspekBahaya->count()) }}
                                </span>
                            </td>
                            <td style="font-size:12px; color:#718096;">
                                {{ $doc->created_at->format('d M Y') }}
                            </td>
                            <td class="text-center">
                                <a href="{{ route('hiradc.show', $doc) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @can('hiradc.create')
                                    <form action="{{ route('hiradc.destroy', $doc) }}" method="POST" class="d-inline"
                                        onsubmit="return confirm('Hapus dokumen ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <x-empty-state icon="fas fa-file-alt" message="Belum ada dokumen HIRADC"
                                    sub="Upload dokumen HIRADC pertama untuk memulai" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($documents->hasPages())
            <div class="card-footer">
                {{ $documents->links() }}
            </div>
        @endif
    </div>
@endsection

@extends('adminlte::page')
@section('title', 'Dokumen HIRADC')

@section('content_header')
    <x-page-header title="Dokumen HIRADC" subtitle="Kelola dokumen identifikasi bahaya dan penilaian risiko"
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
            $approved = \App\Models\HiradcDocument::where('status', 'approved')->count();
            $pending = \App\Models\HiradcDocument::whereIn('status', ['pending_v1', 'pending_v2'])->count();
            $rejected = \App\Models\HiradcDocument::where('status', 'rejected')->count();
        @endphp
        <div class="col-md-3 col-6 mb-2">
            <x-stat-mini label="Total" :value="$total" color="#006b3f" />
        </div>
        <div class="col-md-3 col-6 mb-2">
            <x-stat-mini label="Approved" :value="$approved" color="#00a65a" />
        </div>
        <div class="col-md-3 col-6 mb-2">
            <x-stat-mini label="Pending" :value="$pending" color="#f0a500" />
        </div>
        <div class="col-md-3 col-6 mb-2">
            <x-stat-mini label="Rejected" :value="$rejected" color="#dc3545" />
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Judul Dokumen</th>
                        <th>Area / Lokasi</th>
                        <th>Diupload Oleh</th>
                        <th>Tanggal</th>
                        <th width="18%">Status</th>
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
                                    {{ $doc->judul }}
                                </div>
                                @if ($doc->divisi)
                                    <div style="font-size:11px; color:#a0aec0;">
                                        {{ $doc->divisi }}
                                    </div>
                                @endif
                            </td>
                            <td style="font-size:13px;">
                                {{ $doc->area_lokasi ?? '-' }}
                            </td>
                            <td>
                                <div style="display:flex; align-items:center; gap:8px;">
                                    <div
                                        style="width:28px; height:28px; border-radius:50%;
                                                background:#006b3f; color:#fff; font-size:11px;
                                                display:flex; align-items:center;
                                                justify-content:center; font-weight:700;
                                                flex-shrink:0;">
                                        {{ strtoupper(substr($doc->uploader->name, 0, 1)) }}
                                    </div>
                                    <span style="font-size:13px;">
                                        {{ $doc->uploader->name }}
                                    </span>
                                </div>
                            </td>
                            <td style="font-size:12px; color:#718096;">
                                {{ $doc->created_at->format('d M Y') }}
                            </td>
                            <td>{!! $doc->status_badge !!}</td>
                            <td class="text-center">
                                <a href="{{ route('hiradc.show', $doc) }}" class="btn btn-sm btn-primary"
                                    title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @can('hiradc.create')
                                    @if (in_array($doc->status, ['draft', 'rejected']))
                                        <form action="{{ route('hiradc.destroy', $doc) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('Hapus dokumen ini?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-danger" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
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

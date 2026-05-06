@extends('adminlte::page')

@section('title', 'Live Audit')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Live Audit</h1>
        @can('live_audit.create')
            <a href="{{ route('live-audit.create') }}" class="btn btn-primary">
                <i class="fas fa-plus mr-1"></i> Buat Live Audit
            </a>
        @endcan
    </div>
@endsection

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th width="5%">No</th>
                        <th>Nama Pekerjaan</th>
                        <th>Perusahaan</th>
                        <th>No WO</th>
                        <th>Tgl Mulai</th>
                        <th width="15%">Status</th>
                        <th width="10%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($liveAudits as $index => $audit)
                        <tr>
                            <td>{{ $liveAudits->firstItem() + $index }}</td>
                            <td>
                                {{ Str::limit($audit->nama_pekerjaan, 50) }}
                                @if ($audit->is_stopped)
                                    <span class="badge badge-danger ml-1">STOP</span>
                                @endif
                            </td>
                            <td>{{ $audit->perusahaan }}</td>
                            <td>{{ $audit->no_work_order ?? '-' }}</td>
                            <td>{{ $audit->tanggal_mulai->format('d/m/Y') }}</td>
                            <td>{!! $audit->status_badge !!}</td>
                            <td>
                                <a href="{{ route('live-audit.show', $audit) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if ($audit->status === 'approved')
                                    <a href="{{ route('live-audit.export-pdf', $audit) }}" class="btn btn-sm btn-danger">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">
                                Belum ada data live audit
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-3">
                {{ $liveAudits->links() }}
            </div>
        </div>
    </div>
@endsection

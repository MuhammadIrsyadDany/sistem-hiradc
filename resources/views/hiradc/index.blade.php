@extends('adminlte::page')

@section('title', 'Dokumen HIRADC')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Dokumen HIRADC</h1>
        @can('hiradc.create')
            <a href="{{ route('hiradc.create') }}" class="btn btn-primary">
                <i class="fas fa-plus mr-1"></i> Upload HIRADC
            </a>
        @endcan
    </div>
@endsection

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle mr-1"></i>
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered table-hover" id="table-hiradc">
                <thead class="thead-light">
                    <tr>
                        <th width="5%">No</th>
                        <th>Judul</th>
                        <th>Area/Lokasi</th>
                        <th>Diupload Oleh</th>
                        <th>Tanggal</th>
                        <th width="15%">Status</th>
                        <th width="10%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($documents as $index => $doc)
                        <tr>
                            <td>{{ $documents->firstItem() + $index }}</td>
                            <td>{{ $doc->judul }}</td>
                            <td>{{ $doc->area_lokasi ?? '-' }}</td>
                            <td>{{ $doc->uploader->name }}</td>
                            <td>{{ $doc->created_at->format('d/m/Y') }}</td>
                            <td>{!! $doc->status_badge !!}</td>
                            <td>
                                <a href="{{ route('hiradc.show', $doc) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @can('hiradc.create')
                                    @if ($doc->status === 'draft' || $doc->status === 'rejected')
                                        <form action="{{ route('hiradc.destroy', $doc) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('Hapus dokumen ini?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">
                                Belum ada dokumen HIRADC
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-3">
                {{ $documents->links() }}
            </div>
        </div>
    </div>
@endsection

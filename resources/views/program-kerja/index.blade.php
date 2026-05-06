@extends('adminlte::page')

@section('title', 'Program Kerja')

@section('content_header')
    <h1>Program Kerja</h1>
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
                        <th>Nama Program</th>
                        <th>HIRADC</th>
                        <th>PIC</th>
                        <th>Deadline</th>
                        <th width="12%">Status</th>
                        <th width="8%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($programKerja as $index => $program)
                        <tr>
                            <td>{{ $programKerja->firstItem() + $index }}</td>
                            <td>{{ $program->nama_program }}</td>
                            <td>
                                <small>{{ $program->hiradc->judul }}</small>
                            </td>
                            <td>{{ $program->pic }}</td>
                            <td>
                                {{ $program->deadline->format('d/m/Y') }}
                                @if ($program->status !== 'closed' && $program->deadline < now())
                                    <span class="badge badge-danger ml-1">Lewat</span>
                                @endif
                            </td>
                            <td>{!! $program->status_badge !!}</td>
                            <td>
                                <a href="{{ route('program-kerja.show', $program) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">
                                Belum ada program kerja
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-3">
                {{ $programKerja->links() }}
            </div>
        </div>
    </div>
@endsection

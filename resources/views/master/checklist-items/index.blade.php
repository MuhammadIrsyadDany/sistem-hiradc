@extends('adminlte::page')

@section('title', 'Master Checklist Items')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Master Checklist Items</h1>
        <a href="{{ route('master.checklist-items.create') }}" class="btn btn-primary">
            <i class="fas fa-plus mr-1"></i> Tambah Item
        </a>
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
        <div class="card-body p-0">
            <table class="table table-bordered table-hover mb-0">
                <thead class="thead-light">
                    <tr>
                        <th width="5%">No</th>
                        <th width="15%">Section</th>
                        <th>Deskripsi</th>
                        <th width="8%" class="text-center">Critical</th>
                        <th width="8%" class="text-center">Status</th>
                        <th width="8%" class="text-center">Urutan</th>
                        <th width="10%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        <tr class="{{ $item->trashed() ? 'table-secondary text-muted' : '' }}">
                            <td>{{ $item->nomor_item }}</td>
                            <td>
                                <span class="badge badge-info">
                                    {{ $item->section }}
                                </span>
                            </td>
                            <td>
                                {{ $item->deskripsi }}
                                @if ($item->trashed())
                                    <span class="badge badge-secondary ml-1">Dihapus</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($item->is_critical)
                                    <span class="badge badge-danger">(*) Critical</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($item->trashed())
                                    <span class="badge badge-secondary">Nonaktif</span>
                                @elseif($item->is_active)
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-warning">Tidak Aktif</span>
                                @endif
                            </td>
                            <td class="text-center">{{ $item->urutan }}</td>
                            <td class="text-center">
                                @if ($item->trashed())
                                    <form action="{{ route('master.checklist-items.restore', $item->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-success" title="Aktifkan kembali">
                                            <i class="fas fa-undo"></i>
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('master.checklist-items.edit', $item) }}"
                                        class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('master.checklist-items.destroy', $item) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('Nonaktifkan item ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">
                                Belum ada item checklist
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $items->links() }}
        </div>
    </div>
@endsection

@extends('adminlte::page')

@section('title', 'Pelaporan Temuan UA/UC')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Pelaporan Temuan UA/UC</h1>
        @can('temuan.create')
            <a href="{{ route('temuan.create') }}" class="btn btn-primary">
                <i class="fas fa-plus mr-1"></i> Laporkan Temuan
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

    {{-- Filter Tabs --}}
    <div class="card">
        <div class="card-header p-0">
            <ul class="nav nav-tabs" id="temuanTabs">
                <li class="nav-item">
                    <a class="nav-link active" href="#semua" data-toggle="tab">
                        Semua
                        <span class="badge badge-secondary ml-1">
                            {{ $temuans->total() }}
                        </span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#draft" data-toggle="tab">
                        Draft
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#open" data-toggle="tab">
                        Open
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#closed" data-toggle="tab">
                        Closed
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th width="5%">No</th>
                        <th>Judul Temuan</th>
                        <th>Distrik</th>
                        <th>Kategori</th>
                        <th>Dilaporkan Oleh</th>
                        <th>Tanggal</th>
                        <th width="12%">Status</th>
                        <th width="8%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($temuans as $index => $temuan)
                        <tr>
                            <td>{{ $temuans->firstItem() + $index }}</td>
                            <td>
                                {{ Str::limit($temuan->judul_temuan, 50) }}
                                @if ($temuan->live_audit_id)
                                    <span class="badge badge-secondary ml-1">
                                        <i class="fas fa-link"></i> WIP
                                    </span>
                                @endif
                            </td>
                            <td>{{ $temuan->distrik }}</td>
                            <td>{!! $temuan->kategori_badge !!}</td>
                            <td>{{ $temuan->reporter->name }}</td>
                            <td>{{ $temuan->created_at->format('d/m/Y') }}</td>
                            <td>{!! $temuan->status_badge !!}</td>
                            <td>
                                <a href="{{ route('temuan.show', $temuan) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">
                                Belum ada temuan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-3">
                {{ $temuans->links() }}
            </div>
        </div>
    </div>
@endsection

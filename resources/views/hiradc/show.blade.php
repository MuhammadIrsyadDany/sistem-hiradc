@extends('adminlte::page')

@section('title', 'Detail HIRADC')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Detail Dokumen HIRADC</h1>
        <a href="{{ route('hiradc.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
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

    <div class="row">
        {{-- Info Dokumen --}}
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informasi Dokumen</h3>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <th width="35%">Judul</th>
                            <td>{{ $hiradc->judul }}</td>
                        </tr>
                        <tr>
                            <th>Unit</th>
                            <td>{{ $hiradc->unit ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Divisi/Bidang</th>
                            <td>{{ $hiradc->divisi ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Area/Lokasi</th>
                            <td>{{ $hiradc->area_lokasi ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Penanggung Jawab</th>
                            <td>{{ $hiradc->penanggung_jawab ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Diupload Oleh</th>
                            <td>{{ $hiradc->uploader->name }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Upload</th>
                            <td>{{ $hiradc->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>{!! $hiradc->status_badge !!}</td>
                        </tr>
                        <tr>
                            <th>File</th>
                            <td>
                                <a href="{{ Storage::url($hiradc->file_path) }}" target="_blank"
                                    class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-download mr-1"></i> Download File
                                </a>
                            </td>
                        </tr>
                    </table>

                    @if ($hiradc->catatan_penolakan)
                        <div class="alert alert-danger mt-3">
                            <strong>Catatan Penolakan:</strong>
                            {{ $hiradc->catatan_penolakan }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Timeline Validasi --}}
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Status Validasi</h3>
                </div>
                <div class="card-body">
                    <div class="timeline timeline-inverse">
                        <div class="time-label">
                            <span class="bg-primary">Alur Persetujuan</span>
                        </div>
                        <div>
                            <i class="fas fa-upload bg-blue"></i>
                            <div class="timeline-item">
                                <span class="time">
                                    {{ $hiradc->created_at->format('d/m/Y') }}
                                </span>
                                <h3 class="timeline-header">Upload</h3>
                                <div class="timeline-body">
                                    Oleh: {{ $hiradc->uploader->name }}
                                </div>
                            </div>
                        </div>
                        <div>
                            <i class="fas fa-check bg-{{ $hiradc->validated_by_v1 ? 'green' : 'gray' }}"></i>
                            <div class="timeline-item">
                                <h3 class="timeline-header">Validator 1</h3>
                                <div class="timeline-body">
                                    @if ($hiradc->validatorV1)
                                        {{ $hiradc->validatorV1->name }}
                                        <br>
                                        <small>
                                            {{ $hiradc->validated_at_v1->format('d/m/Y H:i') }}
                                        </small>
                                    @else
                                        <span class="text-muted">Menunggu...</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div>
                            <i class="fas fa-check-double bg-{{ $hiradc->validated_by_v2 ? 'green' : 'gray' }}"></i>
                            <div class="timeline-item">
                                <h3 class="timeline-header">Validator 2</h3>
                                <div class="timeline-body">
                                    @if ($hiradc->validatorV2)
                                        {{ $hiradc->validatorV2->name }}
                                        <br>
                                        <small>
                                            {{ $hiradc->validated_at_v2->format('d/m/Y H:i') }}
                                        </small>
                                    @else
                                        <span class="text-muted">Menunggu...</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tombol Validasi --}}
            @if ($hiradc->status === 'pending_v1')
                @can('hiradc.validate_v1')
                    <div class="card">
                        <div class="card-header bg-warning">
                            <h3 class="card-title">Aksi Validator 1</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('hiradc.validate-v1', $hiradc) }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label>Catatan (opsional)</label>
                                    <textarea name="catatan_penolakan" class="form-control" rows="3" placeholder="Isi jika menolak..."></textarea>
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="submit" name="action" value="approve"
                                        class="btn btn-success btn-block mr-1">
                                        <i class="fas fa-check mr-1"></i> Setujui
                                    </button>
                                    <button type="submit" name="action" value="reject" class="btn btn-danger btn-block">
                                        <i class="fas fa-times mr-1"></i> Tolak
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endcan
            @endif

            @if ($hiradc->status === 'pending_v2')
                @can('hiradc.validate_v2')
                    <div class="card">
                        <div class="card-header bg-info">
                            <h3 class="card-title">Aksi Validator 2</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('hiradc.validate-v2', $hiradc) }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label>Catatan (opsional)</label>
                                    <textarea name="catatan_penolakan" class="form-control" rows="3" placeholder="Isi jika menolak..."></textarea>
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="submit" name="action" value="approve"
                                        class="btn btn-success btn-block mr-1">
                                        <i class="fas fa-check mr-1"></i> Setujui
                                    </button>
                                    <button type="submit" name="action" value="reject" class="btn btn-danger btn-block">
                                        <i class="fas fa-times mr-1"></i> Tolak
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endcan
            @endif
        </div>
    </div>

    {{-- Program Kerja Section --}}
    @if ($hiradc->status === 'approved')
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">Program Kerja</h3>
                @can('program_kerja.create')
                    <a href="{{ route('program-kerja.create', ['hiradc_id' => $hiradc->id]) }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus mr-1"></i> Tambah Program Kerja
                    </a>
                @endcan
            </div>
            <div class="card-body">
                @forelse($hiradc->programKerja as $program)
                    <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                        <div>
                            <strong>{{ $program->nama_program }}</strong>
                            <br>
                            <small class="text-muted">
                                PIC: {{ $program->pic }} |
                                Deadline: {{ $program->deadline->format('d/m/Y') }}
                            </small>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            {!! $program->status_badge !!}
                            <a href="{{ route('program-kerja.show', $program) }}" class="btn btn-sm btn-info ml-2">
                                <i class="fas fa-eye"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <p class="text-muted text-center">Belum ada program kerja</p>
                @endforelse
            </div>
        </div>
    @endif
@endsection

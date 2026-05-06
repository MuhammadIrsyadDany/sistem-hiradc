@extends('adminlte::page')

@section('title', 'Detail Live Audit')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Detail Live Audit</h1>
        <div>
            @if ($liveAudit->status === 'approved')
                <a href="{{ route('live-audit.export-pdf', $liveAudit) }}" class="btn btn-danger mr-2">
                    <i class="fas fa-file-pdf mr-1"></i> Export PDF
                </a>
            @endif
            <a href="{{ route('live-audit.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>
        </div>
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

    @if ($liveAudit->is_stopped)
        <div class="alert alert-danger">
            <i class="fas fa-stop-circle mr-1"></i>
            <strong>PEKERJAAN DI-STOP!</strong>
            {{ $liveAudit->stop_alasan }}
        </div>
    @endif

    <div class="row">
        {{-- Info Pekerjaan --}}
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informasi Pekerjaan</h3>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <th width="35%">Nama Pekerjaan</th>
                            <td>{{ $liveAudit->nama_pekerjaan }}</td>
                        </tr>
                        <tr>
                            <th>No Work Order</th>
                            <td>{{ $liveAudit->no_work_order ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Diminta Oleh</th>
                            <td>{{ $liveAudit->diminta_oleh ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Lokasi</th>
                            <td>{{ $liveAudit->lokasi }}</td>
                        </tr>
                        <tr>
                            <th>Perusahaan</th>
                            <td>{{ $liveAudit->perusahaan }}</td>
                        </tr>
                        <tr>
                            <th>Periode</th>
                            <td>
                                {{ $liveAudit->tanggal_mulai->format('d/m/Y') }} s/d
                                {{ $liveAudit->tanggal_selesai->format('d/m/Y') }}
                            </td>
                        </tr>
                        <tr>
                            <th>Skor Checklist</th>
                            <td>
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar bg-success" style="width: {{ $liveAudit->score }}%">
                                        {{ $liveAudit->score }}%
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>{!! $liveAudit->status_badge !!}</td>
                        </tr>
                    </table>
                </div>
            </div>

            {{-- Checklist --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Hasil Checklist</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered mb-0 table-sm">
                        <thead class="thead-light">
                            <tr>
                                <th width="5%">No</th>
                                <th>Action/Condition</th>
                                <th width="10%" class="text-center">Tidak</th>
                                <th width="10%" class="text-center">Ya</th>
                                <th width="10%" class="text-center">NA</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($checklistsBySection as $section => $checklists)
                                @if ($section !== 'Umum')
                                    <tr class="bg-light">
                                        <td colspan="5">
                                            <strong>{{ $section }}</strong>
                                        </td>
                                    </tr>
                                @endif
                                @foreach ($checklists as $checklist)
                                    <tr @if ($checklist->checklistItem->is_critical && $checklist->jawaban === 'tidak') class="table-danger" @endif>
                                        <td>{{ $checklist->checklistItem->nomor_item }}</td>
                                        <td>
                                            {{ $checklist->checklistItem->deskripsi }}
                                            @if ($checklist->checklistItem->is_critical)
                                                <span class="text-danger">(*)</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($checklist->jawaban === 'tidak')
                                                <i class="fas fa-check text-danger"></i>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($checklist->jawaban === 'ya')
                                                <i class="fas fa-check text-success"></i>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if ($checklist->jawaban === 'na')
                                                <i class="fas fa-check text-muted"></i>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="table-info">
                                <td colspan="2" class="text-right font-weight-bold">
                                    Total Nilai
                                </td>
                                <td class="text-center font-weight-bold">
                                    {{ $liveAudit->checklists->where('jawaban', 'tidak')->count() }}
                                </td>
                                <td class="text-center font-weight-bold">
                                    {{ $liveAudit->checklists->where('jawaban', 'ya')->count() }}
                                </td>
                                <td class="text-center font-weight-bold">
                                    {{ $liveAudit->checklists->where('jawaban', 'na')->count() }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            {{-- Temuan & Working Permit --}}
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Temuan Unsafe Action:</strong>
                            <p>{{ $liveAudit->unsafe_action_text ?? 'tidak ada' }}</p>
                        </div>
                        <div class="col-md-6">
                            <strong>Temuan Unsafe Condition:</strong>
                            <p>{{ $liveAudit->unsafe_condition_text ?? 'tidak ada' }}</p>
                        </div>
                    </div>
                    @if ($liveAudit->working_permit_list)
                        <div>
                            <strong>Working Permit:</strong>
                            <div class="mt-1">
                                @foreach ($liveAudit->working_permit_list as $permit)
                                    <span class="badge badge-info mr-1">{{ $permit }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Sidebar Validasi --}}
        <div class="col-md-4">
            {{-- Timeline --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Status Validasi</h3>
                </div>
                <div class="card-body">
                    <div class="timeline timeline-inverse">
                        <div>
                            <i class="fas fa-upload bg-blue"></i>
                            <div class="timeline-item">
                                <span class="time">
                                    {{ $liveAudit->created_at->format('d/m/Y') }}
                                </span>
                                <h3 class="timeline-header">Dibuat</h3>
                                <div class="timeline-body">
                                    {{ $liveAudit->creator->name }}
                                </div>
                            </div>
                        </div>
                        <div>
                            <i class="fas fa-check bg-{{ $liveAudit->validated_by_v1 ? 'green' : 'gray' }}"></i>
                            <div class="timeline-item">
                                <h3 class="timeline-header">Validator 1</h3>
                                <div class="timeline-body">
                                    @if ($liveAudit->validatorV1)
                                        {{ $liveAudit->validatorV1->name }}
                                        <br>
                                        <small>{{ $liveAudit->validated_at_v1->format('d/m/Y H:i') }}</small>
                                    @else
                                        <span class="text-muted">Menunggu...</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div>
                            <i class="fas fa-check-double bg-{{ $liveAudit->validated_by_v2 ? 'green' : 'gray' }}"></i>
                            <div class="timeline-item">
                                <h3 class="timeline-header">Validator 2</h3>
                                <div class="timeline-body">
                                    @if ($liveAudit->validatorV2)
                                        {{ $liveAudit->validatorV2->name }}
                                        <br>
                                        <small>{{ $liveAudit->validated_at_v2->format('d/m/Y H:i') }}</small>
                                    @else
                                        <span class="text-muted">Menunggu...</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tombol Validasi V1 --}}
            @if ($liveAudit->status === 'pending_v1')
                @can('live_audit.validate_v1')
                    <div class="card">
                        <div class="card-header bg-warning">
                            <h3 class="card-title">Aksi Validator 1</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('live-audit.validate-v1', $liveAudit) }}" method="POST">
                                @csrf
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

            {{-- Tombol Validasi V2 --}}
            @if ($liveAudit->status === 'pending_v2')
                @can('live_audit.validate_v2')
                    <div class="card">
                        <div class="card-header bg-info">
                            <h3 class="card-title">Aksi Validator 2</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('live-audit.validate-v2', $liveAudit) }}" method="POST">
                                @csrf
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
@endsection

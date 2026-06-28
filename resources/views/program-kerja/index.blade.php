@extends('adminlte::page')
@section('title', 'Program Kerja')

@section('content_header')
    <x-page-header title="Program Kerja" subtitle="Monitoring progress pengendalian risiko dari HIRADC" icon="fas fa-tasks">
    </x-page-header>
@endsection

@section('content')
    @if (session('success'))
        <x-alert type="success">{{ session('success') }}</x-alert>
    @endif

    {{-- Summary Cards --}}
    <div class="row mb-4">
        @php
            $pkOpen = \App\Models\ProgramKerja::where('status', 'open')->count();
            $pkProgress = \App\Models\ProgramKerja::where('status', 'on_progress')->count();
            $pkOverdue = \App\Models\ProgramKerja::where('status', 'overdue')->count();
            $pkClosed = \App\Models\ProgramKerja::where('status', 'closed')->count();
        @endphp
        <div class="col-md-3 col-6 mb-2">
            <x-stat-mini label="Open" :value="$pkOpen" color="#6c757d" />
        </div>
        <div class="col-md-3 col-6 mb-2">
            <x-stat-mini label="On Progress" :value="$pkProgress" color="#17a2b8" />
        </div>
        <div class="col-md-3 col-6 mb-2">
            <x-stat-mini label="Overdue" :value="$pkOverdue" color="#dc3545" />
        </div>
        <div class="col-md-3 col-6 mb-2">
            <x-stat-mini label="Closed" :value="$pkClosed" color="#00a65a" />
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Nama Program</th>
                        <th>HIRADC</th>
                        <th>PIC</th>
                        <th>Deadline</th>
                        <th width="12%">Status</th>
                        <th width="6%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($programKerja as $index => $program)
                        <tr>
                            <td style="color:#a0aec0; font-size:12px;">
                                {{ $programKerja->firstItem() + $index }}
                            </td>
                            <td>
                                <div style="font-size:13px; font-weight:600; color:#2d3748;">
                                    {{ Str::limit($program->nama_program, 45) }}
                                </div>
                            </td>
                            <td>
                                <div style="font-size:12px;">
                                    <a href="{{ route('hiradc.show', $program->hiradc_id) }}" style="color:#006b3f; font-weight:600;">
                                        {{ Str::limit($program->hiradc->judul, 35) }}
                                    </a>
                                </div>
                            </td>
                            <td style="font-size:13px;">{{ $program->pic }}</td>
                            <td>
                                <div
                                    style="font-size:13px;
                                            color:{{ $program->status !== 'closed' && $program->deadline < now() ? '#dc3545' : '#2d3748' }};
                                            font-weight:{{ $program->status !== 'closed' && $program->deadline < now() ? '700' : '400' }};">
                                    {{ $program->deadline->format('d M Y') }}
                                </div>
                                @if ($program->status !== 'closed' && $program->deadline < now())
                                    <div style="font-size:10px; color:#dc3545;">
                                        {{ $program->deadline->diffForHumans() }}
                                    </div>
                                @endif
                            </td>
                            <td>{!! $program->status_badge !!}</td>
                            <td class="text-center">
                                <a href="{{ route('program-kerja.show', $program) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <x-empty-state icon="fas fa-tasks" message="Belum ada program kerja"
                                    sub="Program kerja dibuat dari HIRADC yang sudah disetujui" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($programKerja->hasPages())
            <div class="card-footer">
                {{ $programKerja->links() }}
            </div>
        @endif
    </div>
@endsection

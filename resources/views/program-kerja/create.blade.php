@extends('adminlte::page')

@section('title', 'Tambah Program Kerja')

@section('content_header')
    <h1>Tambah Program Kerja</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                HIRADC: {{ $hiradc->judul }}
            </h3>
        </div>
        <div class="card-body">
            <form action="{{ route('program-kerja.store') }}" method="POST">
                @csrf
                <input type="hidden" name="aspek_bahaya_id" value="{{ $aspek?->id }}">

                @if ($aspek)
                    <div
                        style="background:#fff3cd; border:1px solid #fde68a;
                border-radius:8px; padding:12px 16px; margin-bottom:16px;">
                        <div style="font-size:12px; font-weight:700; color:#856404;">
                            <i class="fas fa-biohazard mr-1"></i>
                            Program kerja untuk aspek bahaya:
                        </div>
                        <div style="font-size:13px; color:#2d3748; margin-top:4px;">
                            {{ $aspek->potensi_bahaya_k3 }}
                        </div>
                        <div style="font-size:11px; color:#a0aec0; margin-top:2px;">
                            Level Risiko Awal: {!! $aspek->level_risiko_badge !!}
                        </div>
                    </div>
                @endif

                <div class="form-group">
                    <label>Nama Program Kerja <span class="text-danger">*</span></label>
                    <input type="text" name="nama_program"
                        class="form-control @error('nama_program') is-invalid @enderror" value="{{ old('nama_program') }}"
                        placeholder="Contoh: Pemasangan rambu K3 di area Ash Handling">
                    @error('nama_program')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Pengendalian Risiko</label>
                    <textarea name="pengendalian_risiko" class="form-control" rows="3"
                        placeholder="Deskripsi pengendalian risiko dari HIRADC...">{{ old('pengendalian_risiko') }}</textarea>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>PIC <span class="text-danger">*</span></label>
                            <input type="text" name="pic" class="form-control @error('pic') is-invalid @enderror"
                                value="{{ old('pic') }}" placeholder="Nama penanggung jawab">
                            @error('pic')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Deadline <span class="text-danger">*</span></label>
                            <input type="date" name="deadline"
                                class="form-control @error('deadline') is-invalid @enderror" value="{{ old('deadline') }}"
                                min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                            @error('deadline')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-3">
                    <a href="{{ route('hiradc.show', $hiradc) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

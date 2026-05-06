@extends('adminlte::page')

@section('title', 'Detail Program Kerja')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Detail Program Kerja</h1>
        <a href="{{ route('program-kerja.index') }}" class="btn btn-secondary">
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

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        {{-- Info Program Kerja --}}
        <div class="col-md-7">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informasi Program Kerja</h3>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <th width="40%">Nama Program</th>
                            <td>{{ $programKerja->nama_program }}</td>
                        </tr>
                        <tr>
                            <th>HIRADC</th>
                            <td>
                                <a href="{{ route('hiradc.show', $programKerja->hiradc) }}">
                                    {{ $programKerja->hiradc->judul }}
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <th>Pengendalian Risiko</th>
                            <td>{{ $programKerja->pengendalian_risiko ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>PIC</th>
                            <td>{{ $programKerja->pic }}</td>
                        </tr>
                        <tr>
                            <th>Deadline</th>
                            <td>
                                {{ $programKerja->deadline->format('d/m/Y') }}
                                @if ($programKerja->status !== 'closed' && $programKerja->deadline < now())
                                    <span class="badge badge-danger ml-1">Overdue</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>{!! $programKerja->status_badge !!}</td>
                        </tr>
                        <tr>
                            <th>Dibuat Oleh</th>
                            <td>{{ $programKerja->creator->name }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            {{-- Bukti Pelaksanaan --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Bukti Pelaksanaan</h3>
                </div>
                <div class="card-body">
                    @forelse($programKerja->bukti as $bukti)
                        <div class="d-flex align-items-start border-bottom pb-3 mb-3">
                            <img src="{{ Storage::url($bukti->foto_path) }}" alt="Bukti" class="img-thumbnail mr-3"
                                style="width: 120px; height: 90px; object-fit: cover; cursor: pointer;"
                                onclick="window.open('{{ Storage::url($bukti->foto_path) }}', '_blank')">
                            <div>
                                <p class="mb-1">{{ $bukti->keterangan }}</p>
                                <small class="text-muted">
                                    Oleh: {{ $bukti->uploader->name }} |
                                    {{ $bukti->created_at->format('d/m/Y H:i') }}
                                </small>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted text-center">Belum ada bukti pelaksanaan</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Aksi --}}
        <div class="col-md-5">
            {{-- Upload Bukti --}}
            @if ($programKerja->status !== 'closed')
                @can('program_kerja.upload_bukti')
                    <div class="card">
                        <div class="card-header bg-primary">
                            <h3 class="card-title text-white">Upload Bukti Pelaksanaan</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('program-kerja.upload-bukti', $programKerja) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label>Foto Bukti <span class="text-danger">*</span></label>
                                    <div class="custom-file">
                                        <input type="file" name="foto"
                                            class="custom-file-input @error('foto') is-invalid @enderror" id="fotoBukti"
                                            accept="image/*">
                                        <label class="custom-file-label" for="fotoBukti">
                                            Pilih foto
                                        </label>
                                    </div>
                                    @error('foto')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Keterangan <span class="text-danger">*</span></label>
                                    <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" rows="3"
                                        placeholder="Deskripsikan program kerja yang sedang dilaksanakan...">{{ old('keterangan') }}</textarea>
                                    @error('keterangan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-upload mr-1"></i> Upload Bukti
                                </button>
                            </form>
                        </div>
                    </div>
                @endcan

                {{-- Tombol Close --}}
                @can('program_kerja.close')
                    @if ($programKerja->bukti->isNotEmpty())
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('program-kerja.close', $programKerja) }}" method="POST"
                                    onsubmit="return confirm('Yakin ingin menutup program kerja ini?')">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-block">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Close Program Kerja
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                @endcan
            @else
                <div class="card">
                    <div class="card-body text-center">
                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                        <p class="font-weight-bold">Program Kerja Selesai</p>
                        <small class="text-muted">
                            Ditutup pada:
                            {{ $programKerja->updated_at->format('d/m/Y H:i') }}
                        </small>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('js')
    <script>
        document.getElementById('fotoBukti').addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name || 'Pilih foto';
            this.nextElementSibling.textContent = fileName;
        });
    </script>
@endsection

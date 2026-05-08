@extends('adminlte::page')

@section('title', 'Detail Temuan')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Detail Temuan</h1>
        <a href="{{ route('temuan.index') }}" class="btn btn-secondary">
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

    {{-- Banner Draft --}}
    @if ($temuan->isDraft())
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle mr-1"></i>
            <strong>Temuan ini masih Draft!</strong>
            Dibuat otomatis dari Live Audit.
            Pelapor perlu melengkapi foto dan detail sebelum dapat diproses.
        </div>
    @endif

    <div class="row">
        {{-- Info Temuan --}}
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informasi Temuan</h3>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <th width="35%">Judul Temuan</th>
                            <td>{{ $temuan->judul_temuan }}</td>
                        </tr>
                        <tr>
                            <th>Distrik</th>
                            <td>{{ $temuan->distrik }}</td>
                        </tr>
                        <tr>
                            <th>Kategori</th>
                            <td>
                                {!! $temuan->kategori_badge !!}
                                @if ($temuan->ai_kategori)
                                    <small class="text-muted ml-2">
                                        <i class="fas fa-robot"></i>
                                        AI: {{ strtoupper(str_replace('_', ' ', $temuan->ai_kategori)) }}
                                        ({{ number_format($temuan->ai_confidence * 100, 1) }}%)
                                    </small>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Kondisi</th>
                            <td>{{ $temuan->kondisi ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Lokasi</th>
                            <td>
                                {{ $temuan->lokasi }}
                                @if ($temuan->keterangan_lokasi)
                                    - {{ $temuan->keterangan_lokasi }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>PIC</th>
                            <td>{{ $temuan->pic ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Tindak Lanjut</th>
                            <td>{{ $temuan->tindak_lanjut ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Rekomendasi</th>
                            <td>{{ $temuan->rekomendasi ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Dilaporkan Oleh</th>
                            <td>{{ $temuan->reporter->name }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>{!! $temuan->status_badge !!}</td>
                        </tr>
                        @if ($temuan->liveAudit)
                            <tr>
                                <th>Dari Live Audit</th>
                                <td>
                                    <a href="{{ route('live-audit.show', $temuan->liveAudit) }}">
                                        <i class="fas fa-link mr-1"></i>
                                        {{ $temuan->liveAudit->nama_pekerjaan }}
                                    </a>
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>

            {{-- Foto Temuan --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Foto Temuan</h3>
                </div>
                <div class="card-body">
                    @if ($temuan->fotos->isNotEmpty())
                        <div class="d-flex flex-wrap">
                            @foreach ($temuan->fotos as $foto)
                                <img src="{{ Storage::url($foto->foto_path) }}" alt="Foto Temuan" class="img-thumbnail m-1"
                                    style="width:150px;height:120px;object-fit:cover;cursor:pointer;"
                                    onclick="window.open('{{ Storage::url($foto->foto_path) }}', '_blank')">
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center">
                            <i class="fas fa-camera fa-2x mb-2 d-block"></i>
                            Belum ada foto
                        </p>
                    @endif
                </div>
            </div>

            {{-- Bukti Perbaikan --}}
            @if ($temuan->buktiPerbaikan->isNotEmpty())
                <div class="card">
                    <div class="card-header bg-success">
                        <h3 class="card-title text-white">Bukti Perbaikan</h3>
                    </div>
                    <div class="card-body">
                        @foreach ($temuan->buktiPerbaikan as $bukti)
                            <div class="d-flex align-items-start border-bottom pb-3 mb-3">
                                <img src="{{ Storage::url($bukti->foto_path) }}" alt="Bukti" class="img-thumbnail mr-3"
                                    style="width:120px;height:90px;object-fit:cover;cursor:pointer;"
                                    onclick="window.open('{{ Storage::url($bukti->foto_path) }}', '_blank')">
                                <div>
                                    <p class="mb-1">{{ $bukti->keterangan }}</p>
                                    <small class="text-muted">
                                        Oleh: {{ $bukti->uploader->name }} |
                                        {{ $bukti->created_at->format('d/m/Y H:i') }}
                                    </small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="col-md-4">
            {{-- Form Lengkapi Draft --}}
            @if ($temuan->isDraft())
                @can('temuan.create')
                    <div class="card border-warning">
                        <div class="card-header bg-warning">
                            <h3 class="card-title">Lengkapi Draft Temuan</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('temuan.complete-draft', $temuan) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label>Kondisi <span class="text-danger">*</span></label>
                                    <input type="text" name="kondisi" class="form-control" placeholder="tidak aman / aman">
                                </div>
                                <div class="form-group">
                                    <label>Lokasi Detail</label>
                                    <input type="text" name="keterangan_lokasi" class="form-control"
                                        placeholder="Contoh: unit 1">
                                </div>
                                <div class="form-group">
                                    <label>PIC</label>
                                    <input type="text" name="pic" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Tindak Lanjut</label>
                                    <textarea name="tindak_lanjut" class="form-control" rows="2"></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Rekomendasi</label>
                                    <textarea name="rekomendasi" class="form-control" rows="2"></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Foto <span class="text-danger">*</span></label>
                                    <div class="custom-file">
                                        <input type="file" name="fotos[]" class="custom-file-input" id="fotoDraft"
                                            accept="image/*" multiple onchange="previewDraft(this)">
                                        <label class="custom-file-label" for="fotoDraft">
                                            Pilih foto
                                        </label>
                                    </div>
                                    <div id="draftPreview" class="d-flex flex-wrap mt-2"></div>
                                </div>
                                <button type="submit" class="btn btn-warning btn-block">
                                    <i class="fas fa-check mr-1"></i>
                                    Lengkapi & Submit
                                </button>
                            </form>
                        </div>
                    </div>
                @endcan
            @endif

            {{-- Validasi V1 --}}
            @if ($temuan->status === 'open')
                @can('temuan.validate_v1')
                    <div class="card">
                        <div class="card-header bg-warning">
                            <h3 class="card-title">Aksi Validator 1</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('temuan.validate-v1', $temuan) }}" method="POST">
                                @csrf
                                <div class="d-flex gap-2">
                                    <button type="submit" name="action" value="approve"
                                        class="btn btn-success btn-block mr-1">
                                        <i class="fas fa-check mr-1"></i> Validasi
                                    </button>
                                    <button type="submit" name="action" value="reject" class="btn btn-danger btn-block">
                                        <i class="fas fa-times mr-1"></i> Kembalikan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endcan
            @endif

            {{-- Validasi V2 --}}
            @if ($temuan->status === 'validated_v1')
                @can('temuan.validate_v2')
                    <div class="card">
                        <div class="card-header bg-info">
                            <h3 class="card-title">Aksi Validator 2</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('temuan.validate-v2', $temuan) }}" method="POST">
                                @csrf
                                <div class="d-flex gap-2">
                                    <button type="submit" name="action" value="approve"
                                        class="btn btn-success btn-block mr-1">
                                        <i class="fas fa-check mr-1"></i> Validasi
                                    </button>
                                    <button type="submit" name="action" value="reject" class="btn btn-danger btn-block">
                                        <i class="fas fa-times mr-1"></i> Kembalikan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endcan
            @endif

            {{-- Upload Bukti & Close --}}
            @if ($temuan->status === 'validated_v2')
                @can('temuan.close')
                    <div class="card">
                        <div class="card-header bg-primary">
                            <h3 class="card-title text-white">Upload Bukti Perbaikan</h3>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('temuan.upload-bukti', $temuan) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <div class="custom-file">
                                        <input type="file" name="foto" class="custom-file-input" id="fotoBuktiTemuan"
                                            accept="image/*"
                                            onchange="this.nextElementSibling.textContent = this.files[0]?.name">
                                        <label class="custom-file-label" for="fotoBuktiTemuan">
                                            Pilih foto
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <textarea name="keterangan" class="form-control" rows="3" placeholder="Keterangan perbaikan..."></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-upload mr-1"></i> Upload
                                </button>
                            </form>

                            @if ($temuan->buktiPerbaikan->isNotEmpty())
                                <form action="{{ route('temuan.close', $temuan) }}" method="POST" class="mt-3"
                                    onsubmit="return confirm('Yakin close temuan ini?')">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-block">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Close Temuan
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endcan
            @endif

            {{-- Status Closed --}}
            @if ($temuan->status === 'closed')
                <div class="card">
                    <div class="card-body text-center">
                        <i class="fas fa-check-circle fa-3x text-success mb-2"></i>
                        <p class="font-weight-bold">Temuan Closed</p>
                        <small class="text-muted">
                            Oleh: {{ $temuan->closedBy->name }}<br>
                            {{ $temuan->closed_at->format('d/m/Y H:i') }}
                        </small>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('js')
    <script>
        function previewDraft(input) {
            const preview = document.getElementById('draftPreview');
            preview.innerHTML = '';
            input.nextElementSibling.textContent = input.files.length + ' foto dipilih';
            Array.from(input.files).forEach(file => {
                const reader = new FileReader();
                reader.onload = e => {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.cssText = 'width:80px;height:60px;object-fit:cover;border-radius:4px;margin:2px;';
                    preview.appendChild(img);
                };
                reader.readAsDataURL(file);
            });
        }
    </script>
@endsection

@extends('adminlte::page')
@section('title', 'Upload HIRADC')

@section('content_header')
    <x-page-header title="Upload Dokumen HIRADC" subtitle="Upload dokumen dan isi informasi dasar area HIRADC"
        icon="fas fa-file-alt" backUrl="{{ route('hiradc.index') }}">
    </x-page-header>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"
                    style="background:linear-gradient(135deg,#004d2e,#006b3f) !important;
                            border-bottom:none !important;">
                    <h3 class="card-title text-white">
                        <i class="fas fa-file-upload mr-2"></i>
                        Informasi Dokumen HIRADC
                    </h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('hiradc.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>
                                        Nama Area
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="nama_area"
                                        class="form-control @error('nama_area') is-invalid @enderror"
                                        value="{{ old('nama_area') }}"
                                        placeholder="Contoh: Ash Handling, Coal Yard, Boiler">
                                    @error('nama_area')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tahun</label>
                                    <input type="text" name="tahun" class="form-control"
                                        value="{{ old('tahun', date('Y')) }}" placeholder="2025">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>No Dokumen</label>
                                    <input type="text" name="no_dokumen" class="form-control"
                                        value="{{ old('no_dokumen') }}" placeholder="Contoh: FMZ-08.1.2.1">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Unit</label>
                                    <input type="text" name="unit" class="form-control"
                                        value="{{ old('unit', 'PLTU Tanjung Awar-Awar') }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Divisi / Bidang</label>
                                    <input type="text" name="divisi" class="form-control" value="{{ old('divisi') }}"
                                        placeholder="Contoh: Coal Handling Facility">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Area / Lokasi</label>
                                    <input type="text" name="area_lokasi" class="form-control"
                                        value="{{ old('area_lokasi') }}" placeholder="Contoh: Ash Handling">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Penanggung Jawab</label>
                            <input type="text" name="penanggung_jawab" class="form-control"
                                value="{{ old('penanggung_jawab') }}" placeholder="Nama penanggung jawab dokumen">
                        </div>

                        {{-- Upload File --}}
                        <div class="form-group">
                            <label>
                                File HIRADC
                                <span class="text-danger">*</span>
                            </label>
                            <div id="fileDropZone"
                                style="border:2px dashed #c6f6d5; border-radius:12px;
                                        padding:36px 20px; text-align:center;
                                        cursor:pointer; transition:all 0.2s;
                                        background:#f0faf4;"
                                onclick="document.getElementById('fileHiradc').click()" ondragover="handleFileDrag(event)"
                                ondrop="handleFileDrop(event)">
                                <i class="fas fa-file-alt"
                                    style="font-size:40px; color:#c6f6d5;
                                          margin-bottom:12px; display:block;"></i>
                                <div
                                    style="font-size:14px; font-weight:600;
                                            color:#006b3f; margin-bottom:4px;">
                                    Klik atau drag file ke sini
                                </div>
                                <div style="font-size:12px; color:#a0aec0;">
                                    Format: PDF atau Excel (.xlsx, .xls)
                                    · Maksimal 10MB
                                </div>
                                <div id="fileInfo"
                                    style="display:none; margin-top:12px;
                                            background:#fff; border-radius:8px;
                                            padding:10px 16px; border:1px solid #c6f6d5;">
                                    <i class="fas fa-check-circle mr-2" style="color:#006b3f;"></i>
                                    <span id="fileName"
                                        style="font-size:13px; color:#2d3748;
                                                 font-weight:600;"></span>
                                </div>
                            </div>
                            <input type="file" name="file" id="fileHiradc" style="display:none;"
                                accept=".pdf,.xlsx,.xls" onchange="showFileInfo(this)">
                            @error('file')
                                <div style="color:#dc3545; font-size:12px; margin-top:6px;">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="alert-custom"
                            style="background:#e8f5ee; border-left:4px solid #006b3f;
                                    border-radius:8px; padding:12px 16px; margin-bottom:16px;">
                            <i class="fas fa-info-circle mr-2" style="color:#006b3f;"></i>
                            <span style="font-size:13px; color:#004d2e;">
                                Setelah upload, kamu dapat menambahkan
                                <strong>aktivitas</strong> dan
                                <strong>aspek bahaya</strong>
                                langsung di halaman detail dokumen.
                            </span>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('hiradc.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times mr-1"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary" style="padding:10px 28px;">
                                <i class="fas fa-upload mr-1"></i>
                                Upload Dokumen
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Info Panel --}}
        <div class="col-md-4">
            <div class="card mb-3" style="border:1px solid #c6f6d5 !important;">
                <div class="card-header"
                    style="background:#e8f5ee !important;
                            border-bottom:2px solid #c6f6d5 !important;">
                    <h3 class="card-title" style="color:#006b3f !important;">
                        <i class="fas fa-info-circle mr-2"></i>
                        Alur Input HIRADC
                    </h3>
                </div>
                <div class="card-body" style="padding:16px;">
                    @php
                        $flowSteps = [
                            [
                                'icon' => 'fas fa-upload',
                                'color' => '#17a2b8',
                                'label' => 'Upload Dokumen',
                                'sub' => 'Upload file Excel/PDF + isi info area',
                            ],
                            [
                                'icon' => 'fas fa-running',
                                'color' => '#f0a500',
                                'label' => 'Tambah Aktivitas',
                                'sub' => 'Input setiap aktivitas kerja di area tersebut',
                            ],
                            [
                                'icon' => 'fas fa-biohazard',
                                'color' => '#dc3545',
                                'label' => 'Tambah Aspek Bahaya',
                                'sub' => 'Input potensi bahaya dan level risiko per aktivitas',
                            ],
                            [
                                'icon' => 'fas fa-tasks',
                                'color' => '#6f42c1',
                                'label' => 'Program Kerja',
                                'sub' => 'Tambah program kerja untuk pengendalian risiko',
                            ],
                            [
                                'icon' => 'fas fa-chart-line',
                                'color' => '#00a65a',
                                'label' => 'Evaluasi Risiko',
                                'sub' => 'Update level risiko akhir setelah pengendalian',
                            ],
                        ];
                    @endphp
                    @foreach ($flowSteps as $i => $step)
                        <div style="display:flex; gap:12px; align-items:flex-start;">
                            <div style="display:flex; flex-direction:column; align-items:center;">
                                <div
                                    style="width:32px; height:32px; border-radius:50%;
                                            background:{{ $step['color'] }};
                                            display:flex; align-items:center;
                                            justify-content:center; flex-shrink:0;">
                                    <i class="{{ $step['icon'] }}" style="color:#fff; font-size:12px;"></i>
                                </div>
                                @if ($i < 4)
                                    <div
                                        style="width:2px; height:24px;
                                                background:{{ $step['color'] }}40;
                                                margin:3px 0;">
                                    </div>
                                @endif
                            </div>
                            <div style="padding-top:5px; flex:1;">
                                <div style="font-size:13px; font-weight:600; color:#2d3748;">
                                    {{ $step['label'] }}
                                </div>
                                <div style="font-size:11px; color:#a0aec0;">
                                    {{ $step['sub'] }}
                                </div>
                            </div>
                        </div>
                        @if ($i < 4)
                            <div style="height:2px;"></div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        function showFileInfo(input) {
            if (input.files.length > 0) {
                document.getElementById('fileName').textContent = input.files[0].name;
                document.getElementById('fileInfo').style.display = 'block';
                document.getElementById('fileDropZone').style.borderColor = '#006b3f';
                document.getElementById('fileDropZone').style.background = '#e8f5ee';
            }
        }

        function handleFileDrag(e) {
            e.preventDefault();
            document.getElementById('fileDropZone').style.borderColor = '#006b3f';
        }

        function handleFileDrop(e) {
            e.preventDefault();
            const input = document.getElementById('fileHiradc');
            const dt = new DataTransfer();
            dt.items.add(e.dataTransfer.files[0]);
            input.files = dt.files;
            showFileInfo(input);
        }
    </script>
@endsection

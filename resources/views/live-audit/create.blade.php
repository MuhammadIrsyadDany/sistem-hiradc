@extends('adminlte::page')
@section('title', 'Buat Live Audit')

@section('content_header')
    <x-page-header title="Buat Live Audit / WIP" subtitle="Work In Practise — pemeriksaan keselamatan pekerjaan"
        icon="fas fa-clipboard-check" backUrl="{{ route('live-audit.index') }}">
    </x-page-header>
@endsection

@section('content')
    <form action="{{ route('live-audit.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Header Pekerjaan --}}
        <div class="card mb-3">
            <div class="card-header"
                style="background:linear-gradient(135deg,#004d2e,#006b3f) !important;
                        border-bottom:none !important;">
                <h3 class="card-title text-white">
                    <i class="fas fa-briefcase mr-2"></i>
                    Informasi Pekerjaan
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Diminta Oleh</label>
                            <input type="text" name="diminta_oleh" class="form-control" value="{{ old('diminta_oleh') }}"
                                placeholder="Nama pemohon ijin kerja">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>No Work Order</label>
                            <input type="text" name="no_work_order" class="form-control"
                                value="{{ old('no_work_order') }}" placeholder="Contoh: WO260256">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>
                        Nama / Deskripsi Pekerjaan
                        <span class="text-danger">*</span>
                    </label>
                    <textarea name="nama_pekerjaan" class="form-control @error('nama_pekerjaan') is-invalid @enderror" rows="2"
                        placeholder="Contoh: Jasa penggantian refractory area boiler...">{{ old('nama_pekerjaan') }}</textarea>
                    @error('nama_pekerjaan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Lokasi <span class="text-danger">*</span></label>
                            <input type="text" name="lokasi" class="form-control @error('lokasi') is-invalid @enderror"
                                value="{{ old('lokasi') }}" placeholder="Contoh: PT PLN NP UP TJ.AWAR-AWAR">
                            @error('lokasi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Perusahaan <span class="text-danger">*</span></label>
                            <input type="text" name="perusahaan"
                                class="form-control @error('perusahaan') is-invalid @enderror"
                                value="{{ old('perusahaan') }}" placeholder="Contoh: GUNUNG API MULIA. PT">
                            @error('perusahaan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Tanggal Mulai <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_mulai"
                                class="form-control @error('tanggal_mulai') is-invalid @enderror"
                                value="{{ old('tanggal_mulai') }}">
                            @error('tanggal_mulai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Tanggal Selesai <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_selesai"
                                class="form-control @error('tanggal_selesai') is-invalid @enderror"
                                value="{{ old('tanggal_selesai') }}">
                            @error('tanggal_selesai')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Checklist --}}
        <div class="card mb-3">
            <div class="card-header"
                style="background:linear-gradient(135deg,#004d2e,#006b3f) !important;
                        border-bottom:none !important;">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title text-white mb-0">
                        <i class="fas fa-list-check mr-2"></i>
                        Daftar Periksa Bidang K3
                    </h3>
                    <div style="display:flex; gap:16px; font-size:12px; color:rgba(255,255,255,0.8);">
                        <span>
                            <span
                                style="display:inline-block; width:10px; height:10px;
                                         background:#fff; border-radius:50%;
                                         margin-right:4px;"></span>
                            NA = Tidak Berlaku
                        </span>
                        <span style="color:#ffc107;">
                            <i class="fas fa-star mr-1" style="font-size:10px;"></i>
                            (*) = Critical
                        </span>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table mb-0" style="font-size:13px;">
                    <thead>
                        <tr>
                            <th width="5%"
                                style="background:#e8f5ee; color:#004d2e;
                                       text-align:center;">
                                No</th>
                            <th style="background:#e8f5ee; color:#004d2e;">
                                Action / Condition
                            </th>
                            <th width="12%"
                                style="background:#fde8e8; color:#721c24;
                                       text-align:center;">
                                Tidak</th>
                            <th width="12%"
                                style="background:#d4edda; color:#155724;
                                       text-align:center;">
                                Ya</th>
                            <th width="12%"
                                style="background:#e2e8f0; color:#4a5568;
                                       text-align:center;">
                                NA</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($checklistItems as $section => $items)
                            @if ($section !== 'Umum')
                                <tr>
                                    <td colspan="5"
                                        style="background:linear-gradient(135deg,#e8f5ee,#f0faf4);
                                               padding:10px 16px;">
                                        <div style="display:flex; align-items:center; gap:8px;">
                                            <div
                                                style="width:4px; height:18px;
                                                        background:#006b3f;
                                                        border-radius:2px;">
                                            </div>
                                            <span
                                                style="font-weight:700; color:#004d2e;
                                                         font-size:13px; text-transform:uppercase;
                                                         letter-spacing:0.5px;">
                                                {{ $section }}
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                            @foreach ($items as $item)
                                <tr style="transition:background 0.15s;"
                                    @if ($item->is_critical) class="checklist-critical" @endif>
                                    <td
                                        style="text-align:center; font-weight:600;
                                               color:#718096; font-size:12px;">
                                        {{ $item->nomor_item }}
                                    </td>
                                    <td>
                                        {{ $item->deskripsi }}
                                        @if ($item->is_critical)
                                            <span
                                                style="color:#dc3545; font-weight:700;
                                                         margin-left:3px;">(*)</span>
                                        @endif
                                    </td>
                                    @foreach (['tidak', 'ya', 'na'] as $jawaban)
                                        <td style="text-align:center;">
                                            <label style="cursor:pointer; margin:0;">
                                                <input type="radio" name="checklists[{{ $item->id }}]"
                                                    value="{{ $jawaban }}"
                                                    {{ old("checklists.{$item->id}", 'na') === $jawaban ? 'checked' : '' }}
                                                    style="width:16px; height:16px;
                                                              accent-color:{{ $jawaban === 'tidak' ? '#dc3545' : ($jawaban === 'ya' ? '#006b3f' : '#718096') }};"
                                                    required>
                                            </label>
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Temuan & Working Permit --}}
        <div class="card mb-3">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-flag mr-2" style="color:#006b3f;"></i>
                    Temuan & Working Permit
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>
                                <i class="fas fa-exclamation-triangle mr-1" style="color:#dc3545;"></i>
                                Temuan Unsafe Action
                            </label>
                            <textarea name="unsafe_action_text" class="form-control" rows="3"
                                placeholder="Tuliskan temuan unsafe action, atau 'tidak ada'">{{ old('unsafe_action_text') }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>
                                <i class="fas fa-exclamation-circle mr-1" style="color:#f0a500;"></i>
                                Temuan Unsafe Condition
                            </label>
                            <textarea name="unsafe_condition_text" class="form-control" rows="3"
                                placeholder="Tuliskan temuan unsafe condition, atau 'tidak ada'">{{ old('unsafe_condition_text') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Working Permit --}}
                <div class="form-group">
                    <label>
                        <i class="fas fa-id-card mr-1" style="color:#006b3f;"></i>
                        Working Permit Pekerjaan
                    </label>
                    <div
                        style="background:#f8fafc; border-radius:10px;
                                padding:16px; display:grid;
                                grid-template-columns:repeat(auto-fill, minmax(200px,1fr));
                                gap:10px;">
                        @foreach ($workingPermits as $key => $label)
                            <label
                                style="display:flex; align-items:center; gap:10px;
                                          cursor:pointer; padding:8px 12px;
                                          background:#fff; border-radius:8px;
                                          border:1.5px solid #e2e8f0;
                                          transition:all 0.2s; margin:0;"
                                onmouseover="this.style.borderColor='#006b3f';this.style.background='#f0faf4';"
                                onmouseout="this.style.borderColor='#e2e8f0';this.style.background='#fff';">
                                <input type="checkbox" name="working_permit[]" value="{{ $key }}"
                                    {{ in_array($key, old('working_permit', [])) ? 'checked' : '' }}
                                    style="width:16px; height:16px;
                                              accent-color:#006b3f;">
                                <span
                                    style="font-size:13px; font-weight:500;
                                             color:#4a5568;">
                                    {{ $label }}
                                </span>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- STOP Pekerjaan --}}
                <div
                    style="background:#fff5f5; border:1.5px solid #fed7d7;
                            border-radius:10px; padding:16px;">
                    <label
                        style="display:flex; align-items:center; gap:10px;
                                  cursor:pointer; margin:0;">
                        <input type="checkbox" name="is_stopped" id="is_stopped" value="1"
                            {{ old('is_stopped') ? 'checked' : '' }}
                            style="width:18px; height:18px;
                                      accent-color:#dc3545;"
                            onchange="toggleStopAlasan(this.checked)">
                        <span style="font-size:14px; font-weight:700;
                                     color:#dc3545;">
                            <i class="fas fa-stop-circle mr-1"></i>
                            Pekerjaan Di-STOP
                        </span>
                    </label>
                    <div id="stop_alasan_container"
                        style="{{ old('is_stopped') ? '' : 'display:none;' }} margin-top:12px;">
                        <textarea name="stop_alasan" class="form-control" rows="3" style="border-color:#dc3545;"
                            placeholder="Tuliskan alasan pekerjaan di-stop...">{{ old('stop_alasan') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- Foto Dokumentasi --}}
        <div class="card mb-3" style="border:2px solid #006b3f !important;">
            <div class="card-header"
                style="background:#e8f5ee !important;
                        border-bottom:2px solid #c6f6d5 !important;">
                <h3 class="card-title" style="color:#006b3f !important;">
                    <i class="fas fa-camera mr-2"></i>
                    Foto Dokumentasi Kerja
                </h3>
            </div>
            <div class="card-body">
                <div id="dropZone"
                    style="border:2px dashed #c6f6d5; border-radius:12px;
                            padding:30px 20px; text-align:center;
                            cursor:pointer; transition:all 0.2s;
                            background:#f0faf4;"
                    onclick="document.getElementById('fotoAudit').click()" ondragover="handleDragOver(event)"
                    ondrop="handleDrop(event)">
                    <i class="fas fa-cloud-upload-alt"
                        style="font-size:36px; color:#c6f6d5;
                              margin-bottom:10px; display:block;"></i>
                    <div
                        style="font-size:13px; font-weight:600;
                                color:#006b3f; margin-bottom:4px;">
                        Klik atau drag foto ke sini
                    </div>
                    <div style="font-size:11px; color:#a0aec0;">
                        JPG, PNG · Maks 5MB per foto
                    </div>
                </div>

                <input type="file" name="fotos[]" id="fotoAudit" accept="image/*" multiple
                    style="display:none;" onchange="previewFotos(this)">

                @error('fotos')
                    <div style="color:#dc3545; font-size:12px; margin-top:6px;">
                        {{ $message }}
                    </div>
                @enderror

                {{-- Preview Grid --}}
                <div id="fotoPreview"
                    style="display:grid;
                            grid-template-columns:repeat(auto-fill, minmax(100px, 1fr));
                            gap:8px; margin-top:12px;">
                </div>
            </div>
        </div>

        {{-- Submit --}}
        <div class="d-flex justify-content-between mb-4">
            <a href="{{ route('live-audit.index') }}" class="btn btn-secondary">
                <i class="fas fa-times mr-1"></i> Batal
            </a>
            <button type="submit" class="btn btn-primary" style="padding:10px 28px;">
                <i class="fas fa-save mr-1"></i>
                Simpan & Kirim untuk Validasi
            </button>
        </div>
    </form>
@endsection

@section('css')
    <style>
        .checklist-critical {
            background: #fffdf0 !important;
        }

        .checklist-critical:hover {
            background: #fff9e6 !important;
        }
    </style>
@endsection

@section('js')
    <script>
        function toggleStopAlasan(checked) {
            document.getElementById('stop_alasan_container')
                .style.display = checked ? 'block' : 'none';
        }

        // Preview Foto
        function previewFotos(input) {
            renderPreviews(Array.from(input.files));
        }

        function renderPreviews(files) {
            const preview = document.getElementById('fotoPreview');
            preview.innerHTML = '';
            const dropZone = document.getElementById('dropZone');

            if (files.length > 0) {
                dropZone.style.borderColor = '#006b3f';
                dropZone.style.background = '#e8f5ee';
            }

            files.forEach(file => {
                const reader = new FileReader();
                reader.onload = e => {
                    const div = document.createElement('div');
                    div.style.cssText =
                        'position:relative; border-radius:8px; overflow:hidden;';
                    div.innerHTML = `
                <img src="${e.target.result}"
                     style="width:100%; height:75px; object-fit:cover;
                            border-radius:8px;">
                <div style="position:absolute; bottom:0; left:0; right:0;
                             background:rgba(0,0,0,0.4); padding:3px;
                             text-align:center;">
                    <span style="color:#fff; font-size:9px;">
                        ${file.name.substring(0,12)}...
                    </span>
                </div>`;
                    preview.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        }

        function handleDragOver(e) {
            e.preventDefault();
            document.getElementById('dropZone').style.borderColor = '#006b3f';
            document.getElementById('dropZone').style.background = '#e8f5ee';
        }

        function handleDrop(e) {
            e.preventDefault();
            const files = Array.from(e.dataTransfer.files)
                .filter(f => f.type.startsWith('image/'));
            const input = document.getElementById('fotoAudit');
            const dt = new DataTransfer();
            files.forEach(f => dt.items.add(f));
            input.files = dt.files;
            renderPreviews(files);
        }
    </script>
@endsection

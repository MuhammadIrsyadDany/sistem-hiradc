@extends('adminlte::page')
@section('title', 'Laporkan Temuan')

@section('content_header')
    <x-page-header title="Laporkan Temuan UA/UC"
        subtitle="Dokumentasikan temuan unsafe action, unsafe condition, near miss, atau positive"
        icon="fas fa-exclamation-triangle" backUrl="{{ route('temuan.index') }}">
    </x-page-header>
@endsection

@section('content')
    <form action="{{ route('temuan.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row">
            {{-- Kolom Kiri --}}
            <div class="col-md-8">

                {{-- Info Temuan --}}
                <div class="card mb-3">
                    <div class="card-header"
                        style="background:linear-gradient(135deg,#004d2e,#006b3f) !important;
                                border-bottom:none !important;">
                        <h3 class="card-title text-white">
                            <i class="fas fa-info-circle mr-2"></i>
                            Informasi Temuan
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label>
                                Judul Temuan
                                <span class="text-danger">*</span>
                                <small style="color:#a0aec0; font-weight:400;">
                                    (minimal 5 kata)
                                </small>
                            </label>
                            <input type="text" name="judul_temuan" id="judulTemuan"
                                class="form-control @error('judul_temuan') is-invalid @enderror"
                                value="{{ old('judul_temuan') }}"
                                placeholder="Contoh: Pekerja tidak memakai wearpack saat bekerja di area boiler"
                                onblur="classifyAI(this.value)">
                            @error('judul_temuan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            {{-- AI Result --}}
                            <div id="aiResult"
                                style="display:none; margin-top:10px;
                                        background:#e8f5ee; border:1px solid #c6f6d5;
                                        border-radius:8px; padding:10px 14px;">
                                <div style="display:flex; align-items:center; gap:10px;">
                                    <div
                                        style="width:32px; height:32px; border-radius:8px;
                                                background:#006b3f; display:flex;
                                                align-items:center; justify-content:center;
                                                flex-shrink:0;">
                                        <i class="fas fa-robot" style="color:#fff; font-size:14px;"></i>
                                    </div>
                                    <div>
                                        <div
                                            style="font-size:12px; font-weight:700;
                                                    color:#004d2e;">
                                            Hasil Klasifikasi AI:
                                            <span id="aiKategori" style="text-transform:uppercase;"></span>
                                        </div>
                                        <div style="font-size:11px; color:#718096;">
                                            Tingkat Keyakinan:
                                            <strong id="aiConfidence"></strong>
                                            &nbsp;·&nbsp;
                                            <em>Hasil bersifat estimasi</em>
                                        </div>
                                    </div>
                                    <div id="aiLoader" style="display:none;">
                                        <i class="fas fa-spinner fa-spin" style="color:#006b3f;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>
                                        Kategori
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select name="kategori" id="kategoriSelect"
                                        class="form-control @error('kategori') is-invalid @enderror">
                                        <option value="">-- Pilih Kategori --</option>
                                        @foreach ($kategoriList as $key => $label)
                                            <option value="{{ $key }}"
                                                {{ old('kategori') === $key ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('kategori')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Kondisi</label>
                                    <input type="text" name="kondisi" class="form-control" value="{{ old('kondisi') }}"
                                        placeholder="tidak aman / aman">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>
                                        Distrik
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="distrik"
                                        class="form-control @error('distrik') is-invalid @enderror"
                                        value="{{ old('distrik', 'UP TJ.AWAR-AWAR') }}">
                                    @error('distrik')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>PIC</label>
                                    <input type="text" name="pic" class="form-control" value="{{ old('pic') }}"
                                        placeholder="Nama penanggung jawab">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>
                                        Lokasi
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="lokasi"
                                        class="form-control @error('lokasi') is-invalid @enderror"
                                        value="{{ old('lokasi') }}" placeholder="Contoh: ESP, Boiler, Coal Yard">
                                    @error('lokasi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Keterangan Lokasi</label>
                                    <input type="text" name="keterangan_lokasi" class="form-control"
                                        value="{{ old('keterangan_lokasi') }}" placeholder="Contoh: unit 1, lantai 2">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tindak Lanjut</label>
                                    <textarea name="tindak_lanjut" class="form-control" rows="3" placeholder="Contoh: dilakukan live audit">{{ old('tindak_lanjut') }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Rekomendasi</label>
                                    <textarea name="rekomendasi" class="form-control" rows="3"
                                        placeholder="Contoh: dilakukan awareness kepada personil">{{ old('rekomendasi') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Kolom Kanan --}}
            <div class="col-md-4">

                {{-- Upload Foto --}}
                <div class="card mb-3" style="border:2px solid #006b3f !important;">
                    <div class="card-header"
                        style="background:#e8f5ee !important;
                                border-bottom:2px solid #c6f6d5 !important;">
                        <h3 class="card-title" style="color:#006b3f !important;">
                            <i class="fas fa-camera mr-2"></i>
                            Foto Temuan
                        </h3>
                    </div>
                    <div class="card-body">
                        {{-- Drop Zone --}}
                        <div id="dropZone"
                            style="border:2px dashed #c6f6d5; border-radius:12px;
                                    padding:30px 20px; text-align:center;
                                    cursor:pointer; transition:all 0.2s;
                                    background:#f0faf4;"
                            onclick="document.getElementById('fotoTemuan').click()" ondragover="handleDragOver(event)"
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

                        <input type="file" name="fotos[]" id="fotoTemuan" accept="image/*" multiple
                            style="display:none;" onchange="previewFotos(this)">

                        @error('fotos')
                            <div
                                style="color:#dc3545; font-size:12px;
                                        margin-top:6px;">
                                {{ $message }}
                            </div>
                        @enderror

                        {{-- Preview Grid --}}
                        <div id="fotoPreview"
                            style="display:grid;
                                    grid-template-columns:repeat(3,1fr);
                                    gap:8px; margin-top:12px;">
                        </div>
                    </div>
                </div>

                {{-- Tips --}}
                <div class="card mb-3"
                    style="background:#fffbf0;
                            border:1px solid #fde68a !important;">
                    <div class="card-body" style="padding:16px;">
                        <div
                            style="font-size:12px; font-weight:700;
                                    color:#856404; margin-bottom:10px;">
                            <i class="fas fa-lightbulb mr-1"></i>
                            Tips Pelaporan
                        </div>
                        <ul
                            style="font-size:12px; color:#856404;
                                   padding-left:16px; margin:0; line-height:1.8;">
                            <li>Judul temuan minimal 5 kata</li>
                            <li>Upload foto yang jelas & relevan</li>
                            <li>Isi lokasi secara spesifik</li>
                            <li>AI akan otomatis mengklasifikasikan kategori</li>
                        </ul>
                    </div>
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn btn-primary btn-block" style="padding:12px; font-size:15px;">
                    <i class="fas fa-paper-plane mr-2"></i>
                    Laporkan Temuan
                </button>
                <a href="{{ route('temuan.index') }}" class="btn btn-secondary btn-block mt-2">
                    <i class="fas fa-times mr-1"></i> Batal
                </a>
            </div>
        </div>
    </form>
@endsection

@section('js')
    <script>
        // ============================================================
        // Preview Foto
        // ============================================================
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

        // ============================================================
        // Drag & Drop
        // ============================================================
        function handleDragOver(e) {
            e.preventDefault();
            document.getElementById('dropZone').style.borderColor = '#006b3f';
            document.getElementById('dropZone').style.background = '#e8f5ee';
        }

        function handleDrop(e) {
            e.preventDefault();
            const files = Array.from(e.dataTransfer.files)
                .filter(f => f.type.startsWith('image/'));
            const input = document.getElementById('fotoTemuan');
            const dt = new DataTransfer();
            files.forEach(f => dt.items.add(f));
            input.files = dt.files;
            renderPreviews(files);
        }

        // ============================================================
        // AI Classify
        // ============================================================
        async function classifyAI(judul) {
            if (judul.trim().split(' ').length < 3) return;

            document.getElementById('aiLoader').style.display = 'block';
            document.getElementById('aiResult').style.display = 'flex';
            document.getElementById('aiKategori').textContent = 'Menganalisis...';
            document.getElementById('aiConfidence').textContent = '';

            try {
                const res = await fetch('/temuan/classify-ai', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector(
                            'meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({
                        judul_temuan: judul
                    }),
                });

                const data = await res.json();
                document.getElementById('aiLoader').style.display = 'none';

                if (data.kategori) {
                    const label = data.kategori
                        .replace(/_/g, ' ').toUpperCase();
                    document.getElementById('aiKategori').textContent = label;
                    document.getElementById('aiConfidence').textContent =
                        (data.confidence * 100).toFixed(1) + '%';

                    // Auto-select kategori
                    const sel = document.getElementById('kategoriSelect');
                    if (!sel.value) sel.value = data.kategori;
                } else {
                    document.getElementById('aiResult').style.display = 'none';
                }
            } catch (e) {
                document.getElementById('aiResult').style.display = 'none';
            }
        }
    </script>
@endsection

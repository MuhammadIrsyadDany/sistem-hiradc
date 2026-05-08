@extends('adminlte::page')

@section('title', 'Laporkan Temuan')

@section('content_header')
    <h1>Laporkan Temuan UA/UC</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('temuan.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Distrik <span class="text-danger">*</span></label>
                            <input type="text" name="distrik" class="form-control @error('distrik') is-invalid @enderror"
                                value="{{ old('distrik', 'UP TJ.AWAR-AWAR') }}">
                            @error('distrik')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Kategori <span class="text-danger">*</span></label>
                            <select name="kategori" class="form-control @error('kategori') is-invalid @enderror"
                                id="kategoriSelect">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach ($kategoriList as $key => $label)
                                    <option value="{{ $key }}" {{ old('kategori') === $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kategori')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Judul Temuan <span class="text-danger">*</span></label>
                    <input type="text" name="judul_temuan" id="judulTemuan"
                        class="form-control @error('judul_temuan') is-invalid @enderror" value="{{ old('judul_temuan') }}"
                        placeholder="Minimal 5 kata. Contoh: Pekerja tidak memakai wearpack saat bekerja"
                        onblur="classifyAI(this.value)">
                    @error('judul_temuan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    {{-- AI Result --}}
                    <div id="aiResult" class="mt-2" style="display:none;">
                        <small class="text-muted">
                            <i class="fas fa-robot mr-1"></i>
                            Hasil AI: <strong id="aiKategori"></strong> |
                            Tingkat Keyakinan: <strong id="aiConfidence"></strong>
                            <br>
                            <em>Disclaimer: Hasil ini bersifat estimasi.</em>
                        </small>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Kondisi</label>
                            <input type="text" name="kondisi" class="form-control" value="{{ old('kondisi') }}"
                                placeholder="Contoh: tidak aman">
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
                            <label>Lokasi <span class="text-danger">*</span></label>
                            <input type="text" name="lokasi" class="form-control @error('lokasi') is-invalid @enderror"
                                value="{{ old('lokasi') }}" placeholder="Contoh: ESP">
                            @error('lokasi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Keterangan Lokasi</label>
                            <input type="text" name="keterangan_lokasi" class="form-control"
                                value="{{ old('keterangan_lokasi') }}" placeholder="Contoh: unit 1">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Tindak Lanjut</label>
                    <textarea name="tindak_lanjut" class="form-control" rows="2" placeholder="Contoh: dilakukan live audit">{{ old('tindak_lanjut') }}</textarea>
                </div>

                <div class="form-group">
                    <label>Rekomendasi</label>
                    <textarea name="rekomendasi" class="form-control" rows="2"
                        placeholder="Contoh: dilakukan awareness kepada personil">{{ old('rekomendasi') }}</textarea>
                </div>

                {{-- Upload Foto --}}
                <div class="form-group">
                    <label>Foto Temuan <span class="text-danger">*</span></label>
                    <div class="custom-file">
                        <input type="file" name="fotos[]" class="custom-file-input @error('fotos') is-invalid @enderror"
                            id="fotoTemuan" accept="image/*" multiple onchange="previewFotos(this)">
                        <label class="custom-file-label" for="fotoTemuan">
                            Pilih foto (bisa lebih dari 1)
                        </label>
                    </div>
                    <small class="text-muted">Format: JPG/PNG. Maksimal 5MB per foto.</small>
                    @error('fotos')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                    <div id="fotoPreview" class="d-flex flex-wrap mt-2 gap-2"></div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('temuan.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane mr-1"></i> Laporkan Temuan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js')
    <script>
        function previewFotos(input) {
            const preview = document.getElementById('fotoPreview');
            preview.innerHTML = '';
            const label = input.nextElementSibling;
            label.textContent = input.files.length + ' foto dipilih';

            Array.from(input.files).forEach(file => {
                const reader = new FileReader();
                reader.onload = e => {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.cssText =
                    'width:100px;height:80px;object-fit:cover;border-radius:4px;margin:4px;';
                    preview.appendChild(img);
                };
                reader.readAsDataURL(file);
            });
        }

        async function classifyAI(judul) {
            if (judul.trim().length < 5) return;

            try {
                const res = await fetch('/temuan/classify-ai', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        judul_temuan: judul
                    })
                });

                const data = await res.json();
                if (data.kategori) {
                    document.getElementById('aiResult').style.display = 'block';
                    document.getElementById('aiKategori').textContent = data.kategori.replace('_', ' ').toUpperCase();
                    document.getElementById('aiConfidence').textContent = (data.confidence * 100).toFixed(1) + '%';

                    // Auto-select kategori
                    const select = document.getElementById('kategoriSelect');
                    if (select.value === '') {
                        select.value = data.kategori;
                    }
                }
            } catch (e) {
                console.log('AI classify error:', e);
            }
        }
    </script>
@endsection

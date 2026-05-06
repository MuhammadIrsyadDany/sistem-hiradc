@extends('adminlte::page')

@section('title', 'Upload HIRADC')

@section('content_header')
    <h1>Upload Dokumen HIRADC</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('hiradc.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Judul Dokumen <span class="text-danger">*</span></label>
                            <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror"
                                value="{{ old('judul') }}" placeholder="Contoh: HIRADC Ash Handling 2025">
                            @error('judul')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Unit</label>
                            <input type="text" name="unit" class="form-control" value="{{ old('unit') }}"
                                placeholder="Contoh: PLTU Tanjung Awar-Awar">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Divisi/Bidang</label>
                            <input type="text" name="divisi" class="form-control" value="{{ old('divisi') }}"
                                placeholder="Contoh: Coal Handling Facility">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Area/Lokasi</label>
                            <input type="text" name="area_lokasi" class="form-control" value="{{ old('area_lokasi') }}"
                                placeholder="Contoh: Ash Handling">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Penanggung Jawab</label>
                            <input type="text" name="penanggung_jawab" class="form-control"
                                value="{{ old('penanggung_jawab') }}" placeholder="Nama penanggung jawab">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>File HIRADC <span class="text-danger">*</span></label>
                    <div class="custom-file">
                        <input type="file" name="file" class="custom-file-input @error('file') is-invalid @enderror"
                            id="fileHiradc" accept=".pdf,.xlsx,.xls">
                        <label class="custom-file-label" for="fileHiradc">
                            Pilih file (PDF / Excel)
                        </label>
                    </div>
                    <small class="text-muted">
                        Format: PDF atau Excel. Maksimal 10MB.
                    </small>
                    @error('file')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('hiradc.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload mr-1"></i> Upload & Kirim untuk Validasi
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js')
    <script>
        document.getElementById('fileHiradc').addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name || 'Pilih file';
            this.nextElementSibling.textContent = fileName;
        });
    </script>
@endsection

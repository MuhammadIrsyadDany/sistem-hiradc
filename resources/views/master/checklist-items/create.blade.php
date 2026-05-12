@extends('adminlte::page')

@section('title', 'Tambah Checklist Item')

@section('content_header')
    <h1>Tambah Checklist Item</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('master.checklist-items.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>No Item <span class="text-danger">*</span></label>
                            <input type="number" name="nomor_item"
                                class="form-control @error('nomor_item') is-invalid @enderror"
                                value="{{ old('nomor_item') }}">
                            @error('nomor_item')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Section <span class="text-danger">*</span></label>
                            <input type="text" name="section" class="form-control @error('section') is-invalid @enderror"
                                value="{{ old('section') }}" list="sectionList" placeholder="Pilih atau ketik section baru">
                            <datalist id="sectionList">
                                @foreach ($sections as $section)
                                    <option value="{{ $section }}">
                                @endforeach
                            </datalist>
                            @error('section')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Urutan <span class="text-danger">*</span></label>
                            <input type="number" name="urutan" class="form-control @error('urutan') is-invalid @enderror"
                                value="{{ old('urutan') }}">
                            @error('urutan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Critical Item?</label>
                            <div class="custom-control custom-switch mt-2">
                                <input type="checkbox" class="custom-control-input" id="is_critical" name="is_critical"
                                    value="1" {{ old('is_critical') ? 'checked' : '' }}>
                                <label class="custom-control-label text-danger" for="is_critical">
                                    Tandai sebagai Critical (*)
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Deskripsi Item <span class="text-danger">*</span></label>
                    <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="3"
                        placeholder="Tuliskan deskripsi item checklist...">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('master.checklist-items.index') }}" class="btn btn-secondary">
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

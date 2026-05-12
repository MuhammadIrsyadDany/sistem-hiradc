@extends('adminlte::page')

@section('title', 'Edit Checklist Item')

@section('content_header')
    <h1>Edit Checklist Item</h1>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('master.checklist-items.update', $checklistItem) }}" method="POST">
                @csrf @method('PUT')

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>No Item <span class="text-danger">*</span></label>
                            <input type="number" name="nomor_item"
                                class="form-control @error('nomor_item') is-invalid @enderror"
                                value="{{ old('nomor_item', $checklistItem->nomor_item) }}">
                            @error('nomor_item')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Section <span class="text-danger">*</span></label>
                            <input type="text" name="section" class="form-control @error('section') is-invalid @enderror"
                                value="{{ old('section', $checklistItem->section) }}" list="sectionList">
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
                                value="{{ old('urutan', $checklistItem->urutan) }}">
                            @error('urutan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Status & Critical</label>
                            <div class="custom-control custom-switch mt-1">
                                <input type="checkbox" class="custom-control-input" id="is_critical" name="is_critical"
                                    value="1" {{ old('is_critical', $checklistItem->is_critical) ? 'checked' : '' }}>
                                <label class="custom-control-label text-danger" for="is_critical">
                                    Critical (*)
                                </label>
                            </div>
                            <div class="custom-control custom-switch mt-2">
                                <input type="checkbox" class="custom-control-input" id="is_active" name="is_active"
                                    value="1" {{ old('is_active', $checklistItem->is_active) ? 'checked' : '' }}>
                                <label class="custom-control-label text-success" for="is_active">
                                    Aktif
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Deskripsi Item <span class="text-danger">*</span></label>
                    <textarea name="deskripsi" class="form-control @error('deskripsi') is-invalid @enderror" rows="3">{{ old('deskripsi', $checklistItem->deskripsi) }}</textarea>
                    @error('deskripsi')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('master.checklist-items.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@extends('adminlte::page')

@section('title', 'Buat Live Audit')

@section('content_header')
    <h1>Buat Live Audit / WIP</h1>
@endsection

@section('content')
    <form action="{{ route('live-audit.store') }}" method="POST">
        @csrf

        {{-- Header Pekerjaan --}}
        <div class="card">
            <div class="card-header bg-primary">
                <h3 class="card-title text-white">Informasi Pekerjaan</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Diminta Oleh</label>
                            <input type="text" name="diminta_oleh" class="form-control" value="{{ old('diminta_oleh') }}">
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
                    <label>Nama/Deskripsi Pekerjaan <span class="text-danger">*</span></label>
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
        <div class="card">
            <div class="card-header bg-info">
                <h3 class="card-title text-white">
                    Daftar Periksa (diisi oleh Bidang K3)
                </h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th width="5%">No</th>
                            <th>Action/Condition</th>
                            <th width="10%" class="text-center">Tidak</th>
                            <th width="10%" class="text-center">Ya</th>
                            <th width="10%" class="text-center">NA</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($checklistItems as $section => $items)
                            @if ($section !== 'Umum')
                                <tr class="bg-light">
                                    <td colspan="5">
                                        <strong>{{ $section }}</strong>
                                    </td>
                                </tr>
                            @endif
                            @foreach ($items as $item)
                                <tr @if ($item->is_critical) class="table-warning" @endif>
                                    <td>{{ $item->nomor_item }}</td>
                                    <td>
                                        {{ $item->deskripsi }}
                                        @if ($item->is_critical)
                                            <span class="text-danger font-weight-bold">(*)</span>
                                        @endif
                                    </td>
                                    @foreach (['tidak', 'ya', 'na'] as $jawaban)
                                        <td class="text-center">
                                            <input type="radio" name="checklists[{{ $item->id }}]"
                                                value="{{ $jawaban }}"
                                                {{ old("checklists.{$item->id}", 'na') === $jawaban ? 'checked' : '' }}
                                                required>
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
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Temuan & Working Permit</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Temuan Unsafe Action</label>
                            <textarea name="unsafe_action_text" class="form-control" rows="3"
                                placeholder="Tuliskan temuan unsafe action, atau 'tidak ada'">{{ old('unsafe_action_text') }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Temuan Unsafe Condition</label>
                            <textarea name="unsafe_condition_text" class="form-control" rows="3"
                                placeholder="Tuliskan temuan unsafe condition, atau 'tidak ada'">{{ old('unsafe_condition_text') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Working Permit Pekerjaan</label>
                    <div class="row">
                        @foreach ($workingPermits as $key => $label)
                            <div class="col-md-3 col-6">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="wp_{{ $key }}"
                                        name="working_permit[]" value="{{ $key }}"
                                        {{ in_array($key, old('working_permit', [])) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="wp_{{ $key }}">
                                        {{ $label }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- STOP Pekerjaan --}}
                <div class="form-group">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="is_stopped" name="is_stopped"
                            value="1" {{ old('is_stopped') ? 'checked' : '' }}
                            onchange="toggleStopAlasan(this.checked)">
                        <label class="custom-control-label text-danger font-weight-bold" for="is_stopped">
                            Pekerjaan Di-STOP
                        </label>
                    </div>
                </div>

                <div id="stop_alasan_container" style="{{ old('is_stopped') ? '' : 'display:none' }}">
                    <div class="form-group">
                        <label>Alasan STOP Pekerjaan</label>
                        <textarea name="stop_alasan" class="form-control border-danger" rows="3"
                            placeholder="Tuliskan alasan pekerjaan di-stop...">{{ old('stop_alasan') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between mb-4">
            <a href="{{ route('live-audit.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save mr-1"></i> Simpan & Kirim untuk Validasi
            </button>
        </div>
    </form>
@endsection

@section('js')
    <script>
        function toggleStopAlasan(checked) {
            document.getElementById('stop_alasan_container').style.display = checked ? 'block' : 'none';
        }
    </script>
@endsection

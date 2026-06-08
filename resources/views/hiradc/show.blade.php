@extends('adminlte::page')
@section('title', 'Detail HIRADC')

@section('content_header')
    <x-page-header title="Detail HIRADC — {{ $hiradc->nama_area }}"
        subtitle="Identifikasi bahaya, penilaian risiko, dan pengendalian" icon="fas fa-file-alt"
        backUrl="{{ route('hiradc.index') }}">
        <a href="{{ Storage::url($hiradc->file_path) }}" target="_blank" class="btn btn-secondary">
            <i class="fas fa-download mr-1"></i> Download File
        </a>
    </x-page-header>
@endsection

@section('content')
    @if (session('success'))
        <x-alert type="success">{{ session('success') }}</x-alert>
    @endif

    {{-- Info Header --}}
    <div class="card mb-3">
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <div style="display:grid; grid-template-columns:1fr 1fr;
                                gap:12px;">
                        @php
                            $infos = [
                                ['label' => 'Nama Area', 'value' => $hiradc->nama_area],
                                ['label' => 'Unit', 'value' => $hiradc->unit ?? '-'],
                                ['label' => 'Divisi/Bidang', 'value' => $hiradc->divisi ?? '-'],
                                ['label' => 'No Dokumen', 'value' => $hiradc->no_dokumen ?? '-'],
                                ['label' => 'Tahun', 'value' => $hiradc->tahun ?? '-'],
                                ['label' => 'Penanggung Jawab', 'value' => $hiradc->penanggung_jawab ?? '-'],
                            ];
                        @endphp
                        @foreach ($infos as $info)
                            <div>
                                <div
                                    style="font-size:10px; color:#a0aec0; font-weight:700;
                                            text-transform:uppercase; letter-spacing:0.5px;">
                                    {{ $info['label'] }}
                                </div>
                                <div style="font-size:13px; font-weight:500; color:#2d3748;">
                                    {{ $info['value'] }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Distribusi Risiko --}}
                <div class="col-md-4">
                    <div
                        style="font-size:11px; color:#a0aec0; font-weight:700;
                                text-transform:uppercase; letter-spacing:0.5px;
                                margin-bottom:10px;">
                        Distribusi Level Risiko
                    </div>
                    @php
                        $dist = $hiradc->risiko_distribusi;
                        $risikoConfig = [
                            'rendah' => ['label' => 'Rendah', 'color' => '#00a65a'],
                            'moderat' => ['label' => 'Moderat', 'color' => '#f0a500'],
                            'tinggi' => ['label' => 'Tinggi', 'color' => '#fd7e14'],
                            'sangat_tinggi' => ['label' => 'Sangat Tinggi', 'color' => '#dc3545'],
                            'ekstrim' => ['label' => 'Ekstrim', 'color' => '#2d3748'],
                        ];
                    @endphp
                    @foreach ($risikoConfig as $key => $cfg)
                        @if ($dist[$key] > 0)
                            <div
                                style="display:flex; justify-content:space-between;
                                        align-items:center; margin-bottom:6px;">
                                <span style="font-size:12px; color:#4a5568;">
                                    <span
                                        style="display:inline-block; width:8px;
                                                 height:8px; border-radius:50%;
                                                 background:{{ $cfg['color'] }};
                                                 margin-right:6px;"></span>
                                    {{ $cfg['label'] }}
                                </span>
                                <span
                                    style="background:{{ $cfg['color'] }}20;
                                             color:{{ $cfg['color'] }};
                                             font-size:12px; font-weight:700;
                                             padding:2px 10px; border-radius:20px;">
                                    {{ $dist[$key] }}
                                </span>
                            </div>
                        @endif
                    @endforeach
                    @if (array_sum($dist) === 0)
                        <div style="font-size:12px; color:#a0aec0;">
                            Belum ada aspek bahaya
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Tabel HIRADC + Form Tambah --}}
    <div class="card mb-3">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">
                <i class="fas fa-table mr-2" style="color:#006b3f;"></i>
                Data Aktivitas & Aspek Bahaya
            </h3>
            @can('hiradc.create')
                <button class="btn btn-sm btn-primary" onclick="toggleFormAktivitas()">
                    <i class="fas fa-plus mr-1"></i> Tambah Aktivitas
                </button>
            @endcan
        </div>

        {{-- Form Tambah Aktivitas --}}
        @can('hiradc.create')
            <div id="formAktivitas"
                style="display:none;
                 background:#f0faf4; border-bottom:2px solid #c6f6d5;
                 padding:16px 20px;">
                <form action="{{ route('hiradc.aktivitas.store', $hiradc) }}" method="POST">
                    @csrf
                    <div
                        style="font-size:13px; font-weight:700; color:#006b3f;
                                margin-bottom:12px;">
                        <i class="fas fa-running mr-1"></i>
                        Tambah Aktivitas Baru
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group mb-2">
                                <label style="font-size:12px;">
                                    Nama Aktivitas *
                                </label>
                                <input type="text" name="nama_aktivitas" class="form-control form-control-sm"
                                    placeholder="Contoh: Dry Unloading Fly Ash" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-2">
                                <label style="font-size:12px;">
                                    Sumber Bahaya *
                                </label>
                                <select name="sumber_bahaya" class="form-control form-control-sm" required>
                                    @foreach ($sumberOptions as $val => $label)
                                        <option value="{{ $val }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group mb-2">
                                <label style="font-size:12px;">Kondisi *</label>
                                <select name="kondisi" class="form-control form-control-sm" required>
                                    @foreach ($kondisiOptions as $val => $label)
                                        <option value="{{ $val }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <div class="form-group mb-2 w-100">
                                <label style="font-size:12px;">&nbsp;</label>
                                <div style="display:flex; gap:6px;">
                                    <button type="submit" class="btn btn-success btn-sm flex-1" style="flex:1;">
                                        <i class="fas fa-save"></i> Simpan
                                    </button>
                                    <button type="button" class="btn btn-secondary btn-sm" onclick="toggleFormAktivitas()">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        @endcan

        <div class="card-body p-0">
            @if ($hiradc->aktivitas->isEmpty())
                <x-empty-state icon="fas fa-running" message="Belum ada aktivitas"
                    sub="Klik 'Tambah Aktivitas' untuk mulai mengisi data HIRADC" />
            @else
                {{-- Loop per Aktivitas --}}
                @foreach ($hiradc->aktivitas as $aktivitas)
                    <div style="border-bottom:2px solid #e8f5ee;">
                        {{-- Header Aktivitas --}}
                        <div
                            style="background:linear-gradient(135deg,#f0faf4,#e8f5ee);
                                    padding:12px 20px;
                                    display:flex; justify-content:space-between;
                                    align-items:center;">
                            <div style="display:flex; align-items:center; gap:12px;">
                                <div
                                    style="width:32px; height:32px; border-radius:8px;
                                            background:#006b3f; color:#fff; font-size:13px;
                                            font-weight:700; display:flex; align-items:center;
                                            justify-content:center;">
                                    {{ $loop->iteration }}
                                </div>
                                <div>
                                    <div
                                        style="font-size:14px; font-weight:700;
                                                color:#004d2e;">
                                        {{ $aktivitas->nama_aktivitas }}
                                    </div>
                                    <div style="font-size:11px; color:#718096;">
                                        Sumber: {{ $aktivitas->sumber_bahara_label }}
                                        &nbsp;·&nbsp;
                                        Kondisi: {{ $aktivitas->kondisi_label }}
                                        &nbsp;·&nbsp;
                                        {{ $aktivitas->aspekBahaya->count() }} aspek bahaya
                                    </div>
                                </div>
                            </div>
                            <div style="display:flex; gap:8px; align-items:center;">
                                @can('hiradc.create')
                                    <button class="btn btn-sm btn-outline-primary"
                                        onclick="toggleFormAspek({{ $aktivitas->id }})"
                                        style="font-size:11px; padding:4px 10px;">
                                        <i class="fas fa-plus mr-1"></i>
                                        Tambah Aspek Bahaya
                                    </button>
                                    <form action="{{ route('hiradc.aktivitas.destroy', [$hiradc, $aktivitas]) }}"
                                        method="POST" class="d-inline"
                                        onsubmit="return confirm('Hapus aktivitas ini beserta semua aspek bahayannya?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" style="font-size:11px; padding:4px 10px;">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endcan
                            </div>
                        </div>

                        {{-- Form Tambah Aspek Bahaya --}}
                        @can('hiradc.create')
                            <div id="formAspek{{ $aktivitas->id }}"
                                style="display:none; background:#fffbf0;
                                        border-bottom:1px solid #fde68a;
                                        padding:14px 20px;">
                                <form action="{{ route('hiradc.aspek-bahaya.store', $aktivitas) }}" method="POST">
                                    @csrf
                                    <div
                                        style="font-size:12px; font-weight:700;
                                                color:#856404; margin-bottom:10px;">
                                        <i class="fas fa-biohazard mr-1"></i>
                                        Tambah Aspek Bahaya untuk:
                                        {{ $aktivitas->nama_aktivitas }}
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group mb-2">
                                                <label style="font-size:11px;">
                                                    Potensi/Aktual Aspek Lingkungan
                                                </label>
                                                <textarea name="potensi_aspek_lingkungan" class="form-control form-control-sm" rows="2"
                                                    placeholder="Contoh: Area kerja berdebu"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-2">
                                                <label style="font-size:11px;">
                                                    Potensi/Aktual Bahaya K3 *
                                                </label>
                                                <textarea name="potensi_bahaya_k3" class="form-control form-control-sm" rows="2"
                                                    placeholder="Contoh: Terpapar debu fly ash" required></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group mb-2">
                                                <label style="font-size:11px;">
                                                    Peraturan Terkait
                                                </label>
                                                <input type="text" name="peraturan_terkait"
                                                    class="form-control form-control-sm"
                                                    placeholder="Contoh: Permenaker No.5 Tahun 2018">
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group mb-2">
                                                <label style="font-size:11px;">
                                                    Pengendalian yang Ada Saat Ini
                                                </label>
                                                <textarea name="pengendalian_existing" class="form-control form-control-sm" rows="2"
                                                    placeholder="Contoh: APD masker, wearpack"></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group mb-2">
                                                <label style="font-size:11px;">
                                                    Level Risiko Awal *
                                                </label>
                                                <select name="level_risiko" class="form-control form-control-sm" required>
                                                    @foreach ($levelOptions as $val => $label)
                                                        <option value="{{ $val }}">
                                                            {{ $label }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4 d-flex align-items-end">
                                            <div class="form-group mb-2 w-100">
                                                <label style="font-size:11px;">&nbsp;</label>
                                                <div style="display:flex; gap:6px;">
                                                    <button type="submit" class="btn btn-warning btn-sm" style="flex:1;">
                                                        <i class="fas fa-save mr-1"></i>
                                                        Simpan
                                                    </button>
                                                    <button type="button" class="btn btn-secondary btn-sm"
                                                        onclick="toggleFormAspek({{ $aktivitas->id }})">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @endcan

                        {{-- Tabel Aspek Bahaya --}}
                        @if ($aktivitas->aspekBahaya->isNotEmpty())
                            <table class="table table-sm mb-0" style="font-size:12px;">
                                <thead>
                                    <tr style="background:#fafafa;">
                                        <th width="3%">#</th>
                                        <th width="18%">Aspek Lingkungan</th>
                                        <th width="18%">Bahaya K3</th>
                                        <th width="18%">Peraturan</th>
                                        <th width="15%">Pengendalian Ada</th>
                                        <th width="8%" class="text-center">
                                            Risiko Awal
                                        </th>
                                        <th width="8%" class="text-center">
                                            Risiko Akhir
                                        </th>
                                        <th width="12%" class="text-center">
                                            Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($aktivitas->aspekBahaya as $aspek)
                                        <tr>
                                            <td style="color:#a0aec0;">
                                                {{ $loop->iteration }}
                                            </td>
                                            <td>
                                                {{ $aspek->potensi_aspek_lingkungan ?? '-' }}
                                            </td>
                                            <td style="font-weight:500; color:#2d3748;">
                                                {{ $aspek->potensi_bahaya_k3 }}
                                            </td>
                                            <td style="color:#718096;">
                                                {{ $aspek->peraturan_terkait ?? '-' }}
                                            </td>
                                            <td style="color:#718096;">
                                                {{ $aspek->pengendalian_existing ?? '-' }}
                                            </td>
                                            <td class="text-center">
                                                {!! $aspek->level_risiko_badge !!}
                                            </td>
                                            <td class="text-center">
                                                @if ($aspek->programKerja->where('status', 'closed')->isNotEmpty())
                                                    @if ($aspek->level_risiko_akhir)
                                                        <div>
                                                            {!! $aspek->level_risiko_akhir_badge !!}
                                                            @if ($aspek->status_penurunan === 'turun')
                                                                <div style="font-size:10px; color:#00a65a;">
                                                                    <i class="fas fa-arrow-down"></i>
                                                                    Turun
                                                                </div>
                                                            @endif
                                                        </div>
                                                    @else
                                                        <button class="btn btn-xs btn-outline-success"
                                                            style="font-size:10px; padding:2px 8px;"
                                                            onclick="toggleUpdateRisiko({{ $aspek->id }})">
                                                            <i class="fas fa-edit"></i>
                                                            Update
                                                        </button>
                                                    @endif
                                                @else
                                                    <span style="font-size:10px; color:#cbd5e0;">
                                                        Belum ada program selesai
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @can('hiradc.create')
                                                    <a href="{{ route('program-kerja.create', ['hiradc_id' => $hiradc->id, 'aspek_id' => $aspek->id]) }}"
                                                        class="btn btn-xs btn-primary"
                                                        style="font-size:10px; padding:2px 8px;" title="Tambah Program Kerja">
                                                        <i class="fas fa-plus"></i> PK
                                                    </a>
                                                    <form action="{{ route('hiradc.aspek-bahaya.destroy', $aspek) }}"
                                                        method="POST" class="d-inline"
                                                        onsubmit="return confirm('Hapus aspek bahaya ini?')">
                                                        @csrf @method('DELETE')
                                                        <button class="btn btn-xs btn-danger"
                                                            style="font-size:10px; padding:2px 8px;">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endcan
                                            </td>
                                        </tr>

                                        {{-- Form Update Risiko Akhir --}}
                                        <tr id="updateRisiko{{ $aspek->id }}"
                                            style="display:none; background:#f0faf4;">
                                            <td colspan="8" style="padding:10px 16px;">
                                                <form
                                                    action="{{ route('hiradc.aspek-bahaya.update-risiko-akhir', $aspek) }}"
                                                    method="POST" style="display:flex; gap:10px; align-items:center;">
                                                    @csrf
                                                    <span
                                                        style="font-size:12px; color:#006b3f;
                                                                 font-weight:600;">
                                                        Update Level Risiko Akhir:
                                                    </span>
                                                    <select name="level_risiko_akhir" class="form-control form-control-sm"
                                                        style="width:160px;">
                                                        @foreach ($levelOptions as $val => $label)
                                                            <option value="{{ $val }}">
                                                                {{ $label }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <button type="submit" class="btn btn-success btn-sm">
                                                        <i class="fas fa-save mr-1"></i>
                                                        Simpan
                                                    </button>
                                                    <button type="button" class="btn btn-secondary btn-sm"
                                                        onclick="toggleUpdateRisiko({{ $aspek->id }})">
                                                        Batal
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>

                                        {{-- Program Kerja terkait aspek ini --}}
                                        @if ($aspek->programKerja->isNotEmpty())
                                            @foreach ($aspek->programKerja as $pk)
                                                <tr style="background:#f8f9fa;">
                                                    <td></td>
                                                    <td colspan="5" style="padding:6px 14px;">
                                                        <div
                                                            style="display:flex;
                                                                    align-items:center;
                                                                    gap:8px;">
                                                            <i class="fas fa-tasks"
                                                                style="color:#6f42c1;
                                                                      font-size:11px;"></i>
                                                            <span
                                                                style="font-size:12px;
                                                                         color:#4a5568;
                                                                         font-weight:500;">
                                                                {{ $pk->nama_program }}
                                                            </span>
                                                            <span
                                                                style="font-size:10px;
                                                                         color:#a0aec0;">
                                                                · PIC: {{ $pk->pic }}
                                                                · Deadline:
                                                                {{ $pk->deadline->format('d M Y') }}
                                                            </span>
                                                        </div>
                                                    </td>
                                                    <td colspan="2" class="text-center">
                                                        {!! $pk->status_badge !!}
                                                    </td>
                                                    <td class="text-center">
                                                        <a href="{{ route('program-kerja.show', $pk) }}"
                                                            class="btn btn-xs btn-info"
                                                            style="font-size:10px;
                                                                  padding:2px 8px;">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div
                                style="padding:16px 20px; font-size:12px;
                                        color:#a0aec0; text-align:center;">
                                Belum ada aspek bahaya. Klik "Tambah Aspek Bahaya" di atas.
                            </div>
                        @endif
                    </div>
                @endforeach
            @endif
        </div>
    </div>
@endsection

@section('js')
    <script>
        function toggleFormAktivitas() {
            const el = document.getElementById('formAktivitas');
            el.style.display = el.style.display === 'none' ? 'block' : 'none';
        }

        function toggleFormAspek(id) {
            const el = document.getElementById('formAspek' + id);
            el.style.display = el.style.display === 'none' ? 'block' : 'none';
        }

        function toggleUpdateRisiko(id) {
            const el = document.getElementById('updateRisiko' + id);
            el.style.display = el.style.display === 'none' ? 'table-row' : 'none';
        }
    </script>
@endsection

@extends('adminlte::page')
@section('title', 'Master Checklist Items')

@section('content_header')
    <x-page-header title="Master Checklist Items" subtitle="Kelola daftar item pemeriksaan live audit"
        icon="fas fa-list-check">
        <a href="{{ route('master.checklist-items.create') }}" class="btn btn-primary">
            <i class="fas fa-plus mr-1"></i> Tambah Item
        </a>
    </x-page-header>
@endsection

@section('content')
    @if (session('success'))
        <x-alert type="success">{{ session('success') }}</x-alert>
    @endif

    {{-- Summary per Section --}}
    @php
        $sections = \App\Models\ChecklistItem::whereNull('deleted_at')
            ->where('is_active', true)
            ->selectRaw('section, COUNT(*) as total, SUM(is_critical) as critical')
            ->groupBy('section')
            ->orderBy('section')
            ->get();
    @endphp

    <div class="row mb-4">
        @foreach ($sections as $section)
            <div class="col-md-2 col-4 mb-2">
                <div
                    style="background:#fff; border-radius:10px; padding:12px 14px;
                            box-shadow:0 2px 8px rgba(0,0,0,0.06);
                            border-left:3px solid #006b3f; text-align:center;">
                    <div style="font-size:20px; font-weight:800; color:#1a202c;">
                        {{ $section->total }}
                    </div>
                    <div
                        style="font-size:10px; color:#718096; font-weight:600;
                                text-transform:uppercase; letter-spacing:0.5px;
                                margin-top:2px;">
                        {{ $section->section }}
                    </div>
                    @if ($section->critical > 0)
                        <div style="font-size:10px; color:#dc3545; margin-top:3px;">
                            {{ $section->critical }} critical
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th width="6%" class="text-center">No</th>
                        <th width="14%">Section</th>
                        <th>Deskripsi</th>
                        <th width="10%" class="text-center">Critical</th>
                        <th width="8%" class="text-center">Urutan</th>
                        <th width="10%" class="text-center">Status</th>
                        <th width="10%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $item)
                        <tr style="{{ $item->trashed() ? 'opacity:0.5;' : '' }}">
                            <td class="text-center"
                                style="font-weight:600; color:#718096;
                                       font-size:13px;">
                                {{ $item->nomor_item }}
                            </td>
                            <td>
                                <span
                                    style="background:#e8f5ee; color:#006b3f;
                                             font-size:11px; padding:3px 10px;
                                             border-radius:20px; font-weight:600;">
                                    {{ $item->section }}
                                </span>
                            </td>
                            <td style="font-size:13px;">
                                {{ $item->deskripsi }}
                                @if ($item->trashed())
                                    <span
                                        style="background:#e2e8f0; color:#718096;
                                                 font-size:10px; padding:2px 8px;
                                                 border-radius:10px; margin-left:6px;">
                                        Dihapus
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($item->is_critical)
                                    <span
                                        style="background:#fee2e2; color:#dc3545;
                                                 font-size:11px; padding:3px 10px;
                                                 border-radius:20px; font-weight:700;">
                                        <i class="fas fa-star mr-1" style="font-size:9px;"></i>
                                        Critical
                                    </span>
                                @else
                                    <span style="color:#cbd5e0; font-size:12px;">—</span>
                                @endif
                            </td>
                            <td class="text-center" style="font-size:13px; color:#718096;">
                                {{ $item->urutan }}
                            </td>
                            <td class="text-center">
                                @if ($item->trashed())
                                    <span
                                        style="background:#f8d7da; color:#721c24;
                                                 font-size:11px; padding:3px 10px;
                                                 border-radius:20px; font-weight:600;">
                                        Nonaktif
                                    </span>
                                @elseif($item->is_active)
                                    <span
                                        style="background:#d4edda; color:#155724;
                                                 font-size:11px; padding:3px 10px;
                                                 border-radius:20px; font-weight:600;">
                                        Aktif
                                    </span>
                                @else
                                    <span
                                        style="background:#fff3cd; color:#856404;
                                                 font-size:11px; padding:3px 10px;
                                                 border-radius:20px; font-weight:600;">
                                        Tidak Aktif
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($item->trashed())
                                    <form action="{{ route('master.checklist-items.restore', $item->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-success" title="Aktifkan kembali"
                                            style="padding:4px 10px;">
                                            <i class="fas fa-undo"></i>
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('master.checklist-items.edit', $item) }}"
                                        class="btn btn-sm btn-warning" style="padding:4px 10px;" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('master.checklist-items.destroy', $item) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('Nonaktifkan item ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger" style="padding:4px 10px;" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <x-empty-state icon="fas fa-list" message="Belum ada item checklist"
                                    sub="Tambahkan item checklist menggunakan tombol di atas" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($items->hasPages())
            <div class="card-footer">
                {{ $items->links() }}
            </div>
        @endif
    </div>
@endsection

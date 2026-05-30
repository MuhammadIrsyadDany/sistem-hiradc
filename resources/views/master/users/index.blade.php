@extends('adminlte::page')
@section('title', 'Manajemen User')

@section('content_header')
    <x-page-header title="Manajemen User" subtitle="Kelola akun dan hak akses pengguna sistem" icon="fas fa-users">
        <a href="{{ route('master.users.create') }}" class="btn btn-primary">
            <i class="fas fa-user-plus mr-1"></i> Tambah User
        </a>
    </x-page-header>
@endsection

@section('content')
    @if (session('success'))
        <x-alert type="success">{{ session('success') }}</x-alert>
    @endif
    @if (session('error'))
        <x-alert type="danger">{{ session('error') }}</x-alert>
    @endif

    {{-- Summary Cards --}}
    <div class="row mb-4">
        @php
            $totalUsers = \App\Models\User::count();
            $activeUsers = \App\Models\User::where('is_active', true)->count();
            $roles = \Spatie\Permission\Models\Role::withCount('users')->get();
        @endphp
        <div class="col-md-3 col-6 mb-2">
            <x-stat-mini label="Total User" :value="$totalUsers" color="#006b3f" />
        </div>
        <div class="col-md-3 col-6 mb-2">
            <x-stat-mini label="User Aktif" :value="$activeUsers" color="#00a65a" />
        </div>
        <div class="col-md-3 col-6 mb-2">
            <x-stat-mini label="Nonaktif" :value="$totalUsers - $activeUsers" color="#6c757d" />
        </div>
        <div class="col-md-3 col-6 mb-2">
            <x-stat-mini label="Total Role" :value="$roles->count()" color="#17a2b8" />
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Pengguna</th>
                        <th>NIP / Jabatan</th>
                        <th>Role</th>
                        <th>No HP</th>
                        <th width="10%" class="text-center">Status</th>
                        <th width="10%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $index => $user)
                        <tr>
                            <td style="color:#a0aec0; font-size:12px;">
                                {{ $users->firstItem() + $index }}
                            </td>
                            <td>
                                <div style="display:flex; align-items:center; gap:12px;">
                                    <div
                                        style="width:38px; height:38px; border-radius:50%;
                                                background:linear-gradient(135deg,#006b3f,#00a65a);
                                                color:#fff; font-size:15px; font-weight:700;
                                                display:flex; align-items:center;
                                                justify-content:center; flex-shrink:0;">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div
                                            style="font-size:13px; font-weight:600;
                                                    color:#2d3748;">
                                            {{ $user->name }}
                                        </div>
                                        <div style="font-size:11px; color:#a0aec0;">
                                            {{ $user->email }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div style="font-size:12px; color:#2d3748;">
                                    {{ $user->nip ?? '-' }}
                                </div>
                                <div style="font-size:11px; color:#a0aec0;">
                                    {{ $user->jabatan ?? '-' }}
                                </div>
                            </td>
                            <td>
                                <div style="display:flex; flex-wrap:wrap; gap:4px;">
                                    @foreach ($user->roles as $role)
                                        <span
                                            style="background:#e8f5ee; color:#006b3f;
                                                     font-size:10px; padding:2px 8px;
                                                     border-radius:20px; font-weight:600;
                                                     border:1px solid #c6f6d5;">
                                            {{ $role->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td style="font-size:13px;">
                                {{ $user->no_hp ?? '-' }}
                            </td>
                            <td class="text-center">
                                @if ($user->is_active)
                                    <span
                                        style="background:#d4edda; color:#155724;
                                                 font-size:11px; padding:3px 12px;
                                                 border-radius:20px; font-weight:600;">
                                        <i class="fas fa-circle mr-1" style="font-size:7px;"></i>
                                        Aktif
                                    </span>
                                @else
                                    <span
                                        style="background:#e2e8f0; color:#718096;
                                                 font-size:11px; padding:3px 12px;
                                                 border-radius:20px; font-weight:600;">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('master.users.edit', $user) }}" class="btn btn-sm btn-warning"
                                    style="padding:4px 10px;" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if ($user->id !== auth()->id())
                                    <form action="{{ route('master.users.destroy', $user) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('Nonaktifkan user ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger" style="padding:4px 10px;" title="Nonaktifkan">
                                            <i class="fas fa-ban"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <x-empty-state icon="fas fa-users" message="Belum ada user"
                                    sub="Tambahkan user menggunakan tombol di atas" />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($users->hasPages())
            <div class="card-footer">
                {{ $users->links() }}
            </div>
        @endif
    </div>
@endsection

@extends('adminlte::page')

@section('title', 'Profil Saya')

@section('content_header')
    <h1>Profil Saya</h1>
@endsection

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        {{-- Info Profil --}}
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user mr-1"></i>
                        Informasi Profil
                    </h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nama Lengkap
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name', $user->name) }}">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Email
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" name="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email', $user->email) }}">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>NIP</label>
                                    <input type="text" name="nip" class="form-control"
                                        value="{{ old('nip', $user->nip) }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Jabatan</label>
                                    <input type="text" name="jabatan" class="form-control"
                                        value="{{ old('jabatan', $user->jabatan) }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>No HP</label>
                                    <input type="text" name="no_hp" class="form-control"
                                        value="{{ old('no_hp', $user->no_hp) }}">
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> Update Profil
                        </button>
                    </form>
                </div>
            </div>

            {{-- Ganti Password --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-lock mr-1"></i>
                        Ganti Password
                    </h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.update-password') }}" method="POST">
                        @csrf @method('PUT')

                        <div class="form-group">
                            <label>Password Lama
                                <span class="text-danger">*</span>
                            </label>
                            <input type="password" name="current_password"
                                class="form-control @error('current_password') is-invalid @enderror">
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Password Baru
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="password" name="password"
                                        class="form-control @error('password') is-invalid @enderror">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Konfirmasi Password Baru
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="password" name="password_confirmation" class="form-control">
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-key mr-1"></i> Ganti Password
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Info Akun --}}
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <div
                            style="width:80px; height:80px; border-radius:50%;
                                    background:#007bff; color:white;
                                    display:flex; align-items:center;
                                    justify-content:center; font-size:32px;
                                    margin: 0 auto;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    </div>
                    <h5 class="font-weight-bold">{{ $user->name }}</h5>
                    <p class="text-muted mb-1">{{ $user->jabatan ?? '-' }}</p>
                    <p class="text-muted small mb-3">{{ $user->email }}</p>

                    <div class="mb-3">
                        @foreach ($user->roles as $role)
                            <span class="badge badge-primary mr-1">
                                {{ $role->name }}
                            </span>
                        @endforeach
                    </div>

                    <hr>

                    <table class="table table-sm text-left">
                        <tr>
                            <th>NIP</th>
                            <td>{{ $user->nip ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>No HP</th>
                            <td>{{ $user->no_hp ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>Bergabung</th>
                            <td>{{ $user->created_at->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>
                                @if ($user->is_active)
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-danger">Nonaktif</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

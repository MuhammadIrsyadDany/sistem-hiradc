@extends('adminlte::page')
@section('title', 'Profil Saya')

@section('content_header')
    <x-page-header title="Profil Saya" subtitle="Kelola informasi akun dan keamanan" icon="fas fa-user-circle">
    </x-page-header>
@endsection

@section('content')
    @if (session('success'))
        <x-alert type="success">{{ session('success') }}</x-alert>
    @endif

    <div class="row">
        {{-- Kolom Kiri — Kartu Profil --}}
        <div class="col-md-4">

            {{-- Profile Card --}}
            <div class="card mb-3" style="overflow:hidden;">
                {{-- Cover --}}
                <div
                    style="height:90px;
                            background:linear-gradient(135deg,#004d2e,#006b3f,#008a50);">
                </div>
                {{-- Avatar --}}
                <div style="text-align:center; margin-top:-40px; padding:0 20px 20px;">
                    <div
                        style="width:80px; height:80px; border-radius:50%;
                                background:linear-gradient(135deg,#006b3f,#00a65a);
                                color:#fff; font-size:32px; font-weight:800;
                                display:flex; align-items:center;
                                justify-content:center; margin:0 auto;
                                border:4px solid #fff;
                                box-shadow:0 4px 15px rgba(0,107,63,0.3);">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div style="margin-top:12px;">
                        <div style="font-size:18px; font-weight:700; color:#1a202c;">
                            {{ $user->name }}
                        </div>
                        <div style="font-size:13px; color:#718096; margin-top:2px;">
                            {{ $user->jabatan ?? 'PLTU Tanjung Awar-Awar' }}
                        </div>
                        <div
                            style="margin-top:10px; display:flex;
                                    justify-content:center; flex-wrap:wrap; gap:6px;">
                            @foreach ($user->roles as $role)
                                <span
                                    style="background:#e8f5ee; color:#006b3f;
                                             font-size:11px; padding:3px 12px;
                                             border-radius:20px; font-weight:600;
                                             border:1px solid #c6f6d5;">
                                    {{ $role->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Info Stats --}}
                <div style="border-top:1px solid #f0f4f8; padding:16px 20px;">
                    @php
                        $infos = [
                            ['icon' => 'fas fa-id-badge', 'label' => 'NIP', 'value' => $user->nip ?? '-'],
                            ['icon' => 'fas fa-envelope', 'label' => 'Email', 'value' => $user->email],
                            ['icon' => 'fas fa-phone', 'label' => 'No HP', 'value' => $user->no_hp ?? '-'],
                            [
                                'icon' => 'fas fa-calendar',
                                'label' => 'Bergabung',
                                'value' => $user->created_at->format('d M Y'),
                            ],
                        ];
                    @endphp
                    @foreach ($infos as $info)
                        <div
                            style="display:flex; align-items:center; gap:12px;
                                    padding:8px 0; border-bottom:1px solid #f0f4f8;">
                            <div
                                style="width:30px; height:30px; border-radius:8px;
                                        background:#e8f5ee; display:flex;
                                        align-items:center; justify-content:center;
                                        flex-shrink:0;">
                                <i class="{{ $info['icon'] }}" style="color:#006b3f; font-size:12px;"></i>
                            </div>
                            <div style="flex:1; overflow:hidden;">
                                <div
                                    style="font-size:10px; color:#a0aec0;
                                            font-weight:700; text-transform:uppercase;
                                            letter-spacing:0.5px;">
                                    {{ $info['label'] }}
                                </div>
                                <div
                                    style="font-size:12px; color:#2d3748;
                                            font-weight:500; white-space:nowrap;
                                            overflow:hidden; text-overflow:ellipsis;">
                                    {{ $info['value'] }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Status Aktif --}}
                <div
                    style="padding:14px 20px; background:#f8fafc;
                            display:flex; justify-content:space-between;
                            align-items:center;">
                    <span style="font-size:12px; color:#718096;">
                        Status Akun
                    </span>
                    @if ($user->is_active)
                        <span
                            style="background:#d4edda; color:#155724;
                                     font-size:11px; padding:3px 12px;
                                     border-radius:20px; font-weight:600;">
                            <i class="fas fa-circle mr-1" style="font-size:8px;"></i>
                            Aktif
                        </span>
                    @else
                        <span
                            style="background:#f8d7da; color:#721c24;
                                     font-size:11px; padding:3px 12px;
                                     border-radius:20px; font-weight:600;">
                            Nonaktif
                        </span>
                    @endif
                </div>
            </div>

            {{-- Aktivitas Singkat --}}
            <div class="card mb-3" style="background:#f0faf4;
                        border:1px solid #c6f6d5 !important;">
                <div class="card-body" style="padding:16px;">
                    <div
                        style="font-size:12px; font-weight:700;
                                color:#006b3f; margin-bottom:12px;">
                        <i class="fas fa-chart-bar mr-1"></i>
                        Kontribusi Saya
                    </div>
                    @php
                        $userId = auth()->id();
                        $contributions = [
                            [
                                'label' => 'HIRADC Diupload',
                                'value' => \App\Models\HiradcDocument::where('uploaded_by', $userId)->count(),
                                'color' => '#17a2b8',
                            ],
                            [
                                'label' => 'Live Audit',
                                'value' => \App\Models\LiveAudit::where('created_by', $userId)->count(),
                                'color' => '#006b3f',
                            ],
                            [
                                'label' => 'Temuan Dilaporkan',
                                'value' => \App\Models\Temuan::where('reported_by', $userId)->count(),
                                'color' => '#f0a500',
                            ],
                        ];
                    @endphp
                    @foreach ($contributions as $c)
                        <div
                            style="display:flex; justify-content:space-between;
                                    align-items:center; margin-bottom:10px;">
                            <span style="font-size:12px; color:#4a5568;">
                                {{ $c['label'] }}
                            </span>
                            <span
                                style="background:#fff; border:1px solid #c6f6d5;
                                         border-radius:20px; padding:2px 12px;
                                         font-size:13px; font-weight:700;
                                         color:{{ $c['color'] }};">
                                {{ $c['value'] }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Kolom Kanan — Form --}}
        <div class="col-md-8">

            {{-- Update Profil --}}
            <div class="card mb-3">
                <div class="card-header"
                    style="background:linear-gradient(135deg,#004d2e,#006b3f) !important;
                            border-bottom:none !important;">
                    <h3 class="card-title text-white">
                        <i class="fas fa-user-edit mr-2"></i>
                        Edit Informasi Profil
                    </h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>
                                        Nama Lengkap
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
                                    <label>
                                        Email
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
                                        value="{{ old('nip', $user->nip) }}" placeholder="Nomor Induk Pegawai">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Jabatan</label>
                                    <input type="text" name="jabatan" class="form-control"
                                        value="{{ old('jabatan', $user->jabatan) }}" placeholder="Jabatan / posisi">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>No HP</label>
                                    <input type="text" name="no_hp" class="form-control"
                                        value="{{ old('no_hp', $user->no_hp) }}" placeholder="08xxxxxxxxxx">
                                </div>
                            </div>
                        </div>
                        
                        {{-- Upload Signature --}}
                        <div class="form-group mt-3">
                            <label>Tanda Tangan Scan (PNG/JPG)</label>
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <div class="custom-file">
                                        <input type="file" name="signature" class="custom-file-input" id="signatureFile" accept="image/*">
                                        <label class="custom-file-label" for="signatureFile">Pilih file ttd scan...</label>
                                    </div>
                                    <small class="text-muted mt-1 d-block">
                                        Upload file scan tanda tangan digital (format PNG transparan disarankan). Maksimal 2MB.
                                    </small>
                                </div>
                                <div class="col-md-4 text-center">
                                    @if ($user->signature_path)
                                        <div class="mt-2" style="border: 1px dashed #cbd5e0; padding: 8px; border-radius: 8px; background: #fafafa;">
                                            <img src="{{ Storage::url($user->signature_path) }}" alt="Tanda Tangan" style="max-height: 70px; max-width: 100%; object-fit: contain;">
                                            <div style="font-size: 10px; color: #a0aec0; margin-top: 4px;">Tanda tangan aktif</div>
                                        </div>
                                    @else
                                        <div class="mt-2 text-muted" style="font-size: 12px; border: 1px dashed #cbd5e0; padding: 15px; border-radius: 8px;">
                                            Belum ada tanda tangan
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary" style="padding:10px 24px;">
                                <i class="fas fa-save mr-1"></i>
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Ganti Password --}}
            <div class="card mb-3">
                <div class="card-header"
                    style="background:#f8fafc !important;
                            border-bottom:2px solid #e2e8f0 !important;">
                    <h3 class="card-title">
                        <i class="fas fa-lock mr-2" style="color:#006b3f;"></i>
                        Keamanan Akun
                    </h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.update-password') }}" method="POST">
                        @csrf @method('PUT')

                        <div class="form-group">
                            <label>
                                Password Lama
                                <span class="text-danger">*</span>
                            </label>
                            <div style="position:relative;">
                                <input type="password" name="current_password" id="currentPass"
                                    class="form-control @error('current_password') is-invalid @enderror"
                                    placeholder="Masukkan password lama">
                                <i class="fas fa-eye"
                                    style="position:absolute; right:12px; top:50%;
                                          transform:translateY(-50%); cursor:pointer;
                                          color:#a0aec0; font-size:14px;"
                                    onclick="togglePasswordField('currentPass',this)"></i>
                            </div>
                            @error('current_password')
                                <div
                                    style="color:#dc3545; font-size:12px;
                                            margin-top:4px;">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>
                                        Password Baru
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div style="position:relative;">
                                        <input type="password" name="password" id="newPass"
                                            class="form-control @error('password') is-invalid @enderror"
                                            placeholder="Min. 8 karakter">
                                        <i class="fas fa-eye"
                                            style="position:absolute; right:12px;
                                                  top:50%; transform:translateY(-50%);
                                                  cursor:pointer; color:#a0aec0;
                                                  font-size:14px;"
                                            onclick="togglePasswordField('newPass',this)"></i>
                                    </div>
                                    @error('password')
                                        <div
                                            style="color:#dc3545; font-size:12px;
                                                    margin-top:4px;">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>
                                        Konfirmasi Password Baru
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div style="position:relative;">
                                        <input type="password" name="password_confirmation" id="confirmPass"
                                            class="form-control" placeholder="Ulangi password baru">
                                        <i class="fas fa-eye"
                                            style="position:absolute; right:12px;
                                                  top:50%; transform:translateY(-50%);
                                                  cursor:pointer; color:#a0aec0;
                                                  font-size:14px;"
                                            onclick="togglePasswordField('confirmPass',this)"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Password Strength --}}
                        <div id="strengthBar" style="display:none; margin-bottom:16px;">
                            <div
                                style="font-size:11px; color:#718096;
                                        margin-bottom:4px;">
                                Kekuatan Password:
                                <span id="strengthLabel"></span>
                            </div>
                            <div
                                style="background:#e2e8f0; border-radius:10px;
                                        height:6px;">
                                <div id="strengthFill"
                                    style="height:6px; border-radius:10px;
                                            transition:width 0.3s; width:0%;">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-warning" style="padding:10px 24px;">
                                <i class="fas fa-key mr-1"></i>
                                Ganti Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        function togglePasswordField(id, icon) {
            const input = document.getElementById(id);
            const isPass = input.type === 'password';
            input.type = isPass ? 'text' : 'password';
            icon.className = isPass ?
                'fas fa-eye-slash' :
                'fas fa-eye';
            icon.style.cssText = icon.style.cssText;
        }

        // Password Strength
        document.getElementById('newPass').addEventListener('input', function() {
            const val = this.value;
            const bar = document.getElementById('strengthBar');
            const fill = document.getElementById('strengthFill');
            const label = document.getElementById('strengthLabel');

            if (val.length === 0) {
                bar.style.display = 'none';
                return;
            }

            bar.style.display = 'block';

            let score = 0;
            if (val.length >= 8) score++;
            if (val.length >= 12) score++;
            if (/[A-Z]/.test(val)) score++;
            if (/[0-9]/.test(val)) score++;
            if (/[^A-Za-z0-9]/.test(val)) score++;

            const levels = [{
                    pct: '20%',
                    color: '#dc3545',
                    text: 'Sangat Lemah'
                },
                {
                    pct: '40%',
                    color: '#f0a500',
                    text: 'Lemah'
                },
                {
                    pct: '60%',
                    color: '#ffc107',
                    text: 'Cukup'
                },
                {
                    pct: '80%',
                    color: '#006b3f',
                    text: 'Kuat'
                },
                {
                    pct: '100%',
                    color: '#00a65a',
                    text: 'Sangat Kuat'
                },
            ];

            const idx = Math.min(score - 1, 4);
            if (idx >= 0) {
                fill.style.width = levels[idx].pct;
                fill.style.background = levels[idx].color;
                label.textContent = levels[idx].text;
                label.style.color = levels[idx].color;
                label.style.fontWeight = '600';
            }
        });

        // Signature file input dynamic label
        document.getElementById('signatureFile')?.addEventListener('change', function(e) {
            const fileName = this.files[0]?.name ?? 'Pilih file ttd scan...';
            const label = this.nextElementSibling;
            if (label) label.textContent = fileName;
        });
    </script>
@endsection

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Sistem HIRADC PLTU</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #006b3f;
            --primary-dark: #004d2e;
            --primary-light: #008a50;
            --text-dark: #1f2937;
            --text-gray: #6b7280;
            --border: #e5e7eb;
            --danger: #dc2626;
            --bg-light: #f5f7fa;
            --white: #ffffff;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: var(--bg-light);
            min-height: 100vh;
            display: flex;
            overflow: hidden;
        }

        /* =========================
           LEFT PANEL
        ========================= */
        .login-left {
            width: 55%;
            background:
                linear-gradient(rgba(0, 0, 0, 0.25),
                    rgba(0, 0, 0, 0.25)),
                linear-gradient(145deg,
                    var(--primary-dark) 0%,
                    var(--primary) 50%,
                    var(--primary-light) 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 60px;
            position: relative;
            overflow: hidden;
        }

        .login-left::before,
        .login-left::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.05);
        }

        .login-left::before {
            width: 420px;
            height: 420px;
            top: -140px;
            right: -140px;
        }

        .login-left::after {
            width: 280px;
            height: 280px;
            bottom: -100px;
            left: -100px;
        }

        .logo-area {
            position: relative;
            z-index: 2;
            margin-bottom: 45px;
        }

        .logo-icon {
            width: 85px;
            height: 85px;
            border-radius: 22px;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 22px;
            backdrop-filter: blur(4px);
        }

        .logo-icon i {
            color: var(--white);
            font-size: 36px;
        }

        .logo-area h1 {
            color: var(--white);
            font-size: 34px;
            font-weight: 700;
            margin-bottom: 10px;
            letter-spacing: 0.5px;
        }

        .subtitle {
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.7;
            font-size: 14px;
        }

        .features {
            position: relative;
            z-index: 2;
            max-width: 420px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 16px 18px;
            margin-bottom: 14px;
            border-radius: 14px;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(5px);
            transition: 0.25s ease;
        }

        .feature-item:hover {
            transform: translateY(-2px);
            background: rgba(255, 255, 255, 0.12);
        }

        .feature-item .icon {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .feature-item .icon i {
            color: var(--white);
            font-size: 18px;
        }

        .feature-item .text h4 {
            color: var(--white);
            font-size: 14px;
            margin-bottom: 4px;
        }

        .feature-item .text p {
            color: rgba(255, 255, 255, 0.7);
            font-size: 12px;
            line-height: 1.5;
        }

        /* =========================
           RIGHT PANEL
        ========================= */
        .login-right {
            width: 45%;
            background: var(--white);
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 50px;
        }

        .login-box {
            width: 100%;
            max-width: 390px;
        }

        .welcome-text {
            margin-bottom: 34px;
        }

        .welcome-text h2 {
            font-size: 30px;
            color: var(--text-dark);
            margin-bottom: 8px;
        }

        .welcome-text p {
            color: var(--text-gray);
            font-size: 14px;
            line-height: 1.6;
        }

        /* =========================
           FORM
        ========================= */
        .form-group {
            margin-bottom: 22px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 14px;
        }

        .input-wrapper input {
            width: 100%;
            height: 48px;
            padding: 0 44px 0 42px;
            border-radius: 10px;
            border: 1.5px solid var(--border);
            background: #fafafa;
            font-size: 14px;
            color: var(--text-dark);
            transition: 0.25s ease;
            outline: none;
        }

        .input-wrapper input:focus {
            border-color: var(--primary);
            background: var(--white);
            box-shadow: 0 0 0 4px rgba(0, 107, 63, 0.1);
        }

        .input-wrapper input.is-invalid {
            border-color: var(--danger);
        }

        .toggle-password {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #9ca3af;
            font-size: 14px;
            transition: 0.2s;
        }

        .toggle-password:hover {
            color: var(--primary);
        }

        .invalid-feedback {
            display: block;
            margin-top: 6px;
            color: var(--danger);
            font-size: 12px;
        }

        /* =========================
           REMEMBER
        ========================= */
        .remember-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 26px;
        }

        .remember-row input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: var(--primary);
            cursor: pointer;
        }

        .remember-row label {
            font-size: 13px;
            color: #4b5563;
            cursor: pointer;
        }

        /* =========================
           BUTTON
        ========================= */
        .btn-login {
            width: 100%;
            height: 50px;
            border: none;
            border-radius: 10px;
            background: linear-gradient(135deg,
                    var(--primary),
                    var(--primary-light));
            color: var(--white);
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            transition: 0.25s ease;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 22px rgba(0, 107, 63, 0.28);
            background: linear-gradient(135deg,
                    var(--primary-dark),
                    var(--primary));
        }

        /* =========================
           FOOTER
        ========================= */
        .login-footer {
            margin-top: 34px;
            text-align: center;
        }

        .login-footer p {
            color: #9ca3af;
            font-size: 12px;
            margin-bottom: 12px;
        }

        .pltn-badge {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 7px 14px;
            border-radius: 30px;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: var(--primary);
            font-size: 12px;
            font-weight: 600;
        }

        /* =========================
           RESPONSIVE
        ========================= */
        @media (max-width: 992px) {
            .login-left {
                width: 50%;
                padding: 40px;
            }

            .login-right {
                width: 50%;
            }
        }

        @media (max-width: 768px) {
            body {
                flex-direction: column;
            }

            .login-left {
                display: none;
            }

            .login-right {
                width: 100%;
                min-height: 100vh;
                padding: 35px 24px;
            }

            .welcome-text h2 {
                font-size: 26px;
            }
        }
    </style>
</head>

<body>

    <!-- LEFT PANEL -->
    <div class="login-left">

        <div class="logo-area">
            <div class="logo-icon">
                <i class="fas fa-shield-alt"></i>
            </div>

            <h1>SHIELD</h1>

            <p class="subtitle">
                Sistem Informasi Keselamatan & Kesehatan Kerja<br>
                PT PLN Nusantara Power<br>
                UP Tanjung Awar-Awar
            </p>
        </div>

        <div class="features">

            <div class="feature-item">
                <div class="icon">
                    <i class="fas fa-file-alt"></i>
                </div>

                <div class="text">
                    <h4>Manajemen HIRADC</h4>
                    <p>Identifikasi bahaya & penilaian risiko terintegrasi</p>
                </div>
            </div>

            <div class="feature-item">
                <div class="icon">
                    <i class="fas fa-clipboard-check"></i>
                </div>

                <div class="text">
                    <h4>Live Audit / WIP</h4>
                    <p>Checklist pekerjaan real-time & laporan digital</p>
                </div>
            </div>

            <div class="feature-item">
                <div class="icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>

                <div class="text">
                    <h4>Pelaporan Temuan</h4>
                    <p>UA/UC dengan klasifikasi AI otomatis</p>
                </div>
            </div>

            <div class="feature-item">
                <div class="icon">
                    <i class="fas fa-chart-bar"></i>
                </div>

                <div class="text">
                    <h4>Dashboard Analytics</h4>
                    <p>Monitoring K3 berbasis data secara real-time</p>
                </div>
            </div>

        </div>
    </div>

    <!-- RIGHT PANEL -->
    <div class="login-right">

        <div class="login-box">

            <div class="welcome-text">
                <h2>Selamat Datang</h2>
                <p>
                    Masuk ke sistem menggunakan akun yang telah terdaftar
                </p>
            </div>

            <form action="{{ route('login.post') }}" method="POST">
                @csrf

                <!-- EMAIL -->
                <div class="form-group">
                    <label for="email">Alamat Email</label>

                    <div class="input-wrapper">
                        <i class="fas fa-envelope input-icon"></i>

                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                            placeholder="nama@pltu.com" class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
                            autofocus>
                    </div>

                    @error('email')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- PASSWORD -->
                <div class="form-group">
                    <label for="passwordField">Password</label>

                    <div class="input-wrapper">
                        <i class="fas fa-lock input-icon"></i>

                        <input type="password" name="password" id="passwordField" placeholder="Masukkan password"
                            class="{{ $errors->has('password') ? 'is-invalid' : '' }}">

                        <i class="fas fa-eye toggle-password" id="togglePassword" onclick="togglePass()"></i>
                    </div>

                    @error('password')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- REMEMBER -->
                <div class="remember-row">
                    <input type="checkbox" name="remember" id="remember">

                    <label for="remember">
                        Ingat saya di perangkat ini
                    </label>
                </div>

                <!-- BUTTON -->
                <button type="submit" class="btn-login">
                    <i class="fas fa-sign-in-alt"></i>
                    Masuk ke Sistem
                </button>

            </form>

            <!-- FOOTER -->
            <div class="login-footer">

                <p>Sistem Informasi K3 — Hak Akses Terbatas</p>

                <div class="pltn-badge">
                    <i class="fas fa-shield-alt"></i>
                    PT PLN Nusantara Power &copy; {{ date('Y') }}
                </div>

            </div>
        </div>
    </div>

    <script>
        function togglePass() {
            const input = document.getElementById('passwordField');
            const icon = document.getElementById('togglePassword');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>

</body>

</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Peminjaman Alat</title>
    <link rel="icon" type="image/png" href="{{ asset('image.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        body {
            font-family: 'Inter', -apple-system, sans-serif;
            background: linear-gradient(-45deg, #0f0c29, #302b63, #24243e, #1a1a2e);
            background-size: 400% 400%;
            animation: gradientShift 20s ease infinite;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(99,102,241,0.15) 0%, transparent 70%);
            top: -200px; right: -200px;
            border-radius: 50%;
            pointer-events: none;
        }

        body::after {
            content: '';
            position: fixed;
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(139,92,246,0.1) 0%, transparent 70%);
            bottom: -150px; left: -150px;
            border-radius: 50%;
            pointer-events: none;
        }

        .login-wrapper {
            width: 100%;
            max-width: 460px;
            position: relative;
            z-index: 1;
        }

        .login-card {
            background: rgba(22, 27, 38, 0.85);
            backdrop-filter: blur(30px) saturate(180%);
            border-radius: 24px;
            padding: 48px 40px;
            border: 1px solid rgba(99, 102, 241, 0.15);
            box-shadow:
                0 32px 64px rgba(0,0,0,0.4),
                0 0 0 1px rgba(255,255,255,0.05) inset;
        }

        @media (max-width: 480px) {
            .login-card {
                padding: 36px 24px;
                border-radius: 20px;
            }
        }

        /* Logo */
        .logo { text-align: center; margin-bottom: 36px; }

        .logo-icon {
            width: 72px; height: 72px;
            background: rgba(15, 23, 42, 0.7);
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            overflow: hidden;
            box-shadow: 0 16px 32px rgba(0, 0, 0, 0.35);
        }

        .logo-icon img { width: 100%; height: 100%; object-fit: cover; }

        .logo h4 {
            color: white;
            font-weight: 900;
            font-size: 1.6rem;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }

        .logo p {
            color: #94a3b8;
            font-size: 0.9rem;
            font-weight: 500;
        }

        /* Form */
        .form-label {
            color: #cbd5e1;
            font-weight: 600;
            font-size: 0.85rem;
            margin-bottom: 8px;
        }

        .input-wrapper {
            position: relative;
            margin-bottom: 20px;
        }

        .input-wrapper .input-icon {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #cbd5e1;
            font-size: 1.1rem;
            z-index: 2;
            transition: color 0.3s;
            pointer-events: none;
            background: transparent;
            padding: 0;
        }

        .form-control {
            background: rgba(15, 23, 42, 0.6);
            border: 1.5px solid #334155;
            border-radius: 14px;
            padding: 14px 16px 14px 48px;
            font-size: 0.95rem;
            color: white;
            height: 52px;
            transition: all 0.3s;
        }

        .password-field { padding-right: 60px; }

        .password-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            width: 34px;
            height: 34px;
            border-radius: 9px;
            border: 1px solid #334155;
            background: rgba(15, 23, 42, 0.85);
            color: #94a3b8;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            z-index: 3;
        }


        .password-toggle:hover {
            color: #e2e8f0;
            background: rgba(99, 102, 241, 0.15);
        }

        .password-toggle:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.25);
        }

        .form-control::placeholder { color: #64748b; }

        .form-control:focus {
            background: rgba(15, 23, 42, 0.8);
            border-color: #6366f1;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15);
            color: white;
            outline: none;
        }

        .input-wrapper:has(.form-control:focus) .input-icon {
            color: #818cf8;
        }

        .input-wrapper .form-control:focus + .password-toggle {
            border-color: #6366f1;
            color: #e2e8f0;
        }

        /* Checkbox */
        .form-check { margin: 16px 0 24px; }

        .form-check-input {
            width: 20px; height: 20px;
            border: 2px solid #475569;
            border-radius: 6px;
            background: transparent;
            cursor: pointer;
        }

        .form-check-input:checked {
            background-color: #6366f1;
            border-color: #6366f1;
        }

        .form-check-input:focus {
            box-shadow: 0 0 0 3px rgba(99,102,241,0.2);
        }

        .form-check-label {
            color: #94a3b8;
            font-weight: 500;
            font-size: 0.9rem;
            margin-left: 8px;
            cursor: pointer;
        }

        /* Button */
        .btn-login {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            border: none;
            border-radius: 14px;
            padding: 15px;
            font-weight: 700;
            font-size: 1rem;
            color: white;
            width: 100%;
            transition: all 0.3s;
            box-shadow: 0 12px 24px rgba(99, 102, 241, 0.3);
            position: relative;
            overflow: hidden;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0; left: -100%;
            width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15), transparent);
            transition: left 0.6s;
        }

        .btn-login:hover::before { left: 100%; }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 16px 32px rgba(99, 102, 241, 0.45);
            color: white;
        }

        .btn-login:active { transform: translateY(0); }

        /* Demo Section */
        .demo-section {
            margin-top: 32px;
            padding-top: 28px;
            border-top: 1px solid rgba(99, 102, 241, 0.15);
        }

        .demo-title {
            color: #94a3b8;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 16px;
            text-align: center;
        }

        .demo-buttons {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }

        @media (max-width: 400px) {
            .demo-buttons {
                grid-template-columns: 1fr;
            }
        }

        .demo-btn {
            background: rgba(99, 102, 241, 0.08);
            border: 1.5px solid rgba(99, 102, 241, 0.2);
            border-radius: 12px;
            padding: 14px 12px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            color: white;
        }

        .demo-btn:hover {
            background: rgba(99, 102, 241, 0.15);
            border-color: #6366f1;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(99, 102, 241, 0.2);
        }

        .demo-btn.active {
            background: rgba(99, 102, 241, 0.2);
            border-color: #818cf8;
            box-shadow: 0 8px 20px rgba(99, 102, 241, 0.25);
        }

        .demo-btn .demo-role {
            font-size: 0.7rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 4px;
        }

        .demo-btn .demo-role.admin { color: #f87171; }
        .demo-btn .demo-role.petugas { color: #fbbf24; }
        .demo-btn .demo-role.peminjam { color: #34d399; }

        .demo-btn .demo-icon {
            font-size: 1.5rem;
            margin-bottom: 6px;
            display: block;
        }

        .demo-btn .demo-email {
            font-size: 0.7rem;
            color: #94a3b8;
            word-break: break-all;
        }

        .demo-password-info {
            margin-top: 14px;
            padding: 10px;
            background: rgba(99, 102, 241, 0.06);
            border-radius: 10px;
            text-align: center;
            color: #94a3b8;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .demo-password-info code {
            background: rgba(99, 102, 241, 0.2);
            color: #a5b4fc;
            padding: 3px 8px;
            border-radius: 6px;
            font-weight: 700;
            font-size: 0.85rem;
        }

        /* Alert */
        .alert {
            border-radius: 14px;
            border: none;
            padding: 14px 18px;
            font-weight: 500;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.9rem;
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.12);
            color: #fca5a5;
            border: 1px solid rgba(239, 68, 68, 0.25);
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.12);
            color: #6ee7b7;
            border: 1px solid rgba(16, 185, 129, 0.25);
        }

        .toast-container {
            position: fixed;
            top: 24px;
            right: 24px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            z-index: 2000;
        }

        .app-toast {
            min-width: 260px;
            max-width: 380px;
            background: rgba(15, 23, 42, 0.95);
            border: 1px solid rgba(148, 163, 184, 0.4);
            border-radius: 14px;
            padding: 12px 14px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            color: #f1f5f9;
            box-shadow: 0 16px 32px rgba(0, 0, 0, 0.35);
            transform: translateY(-6px);
            opacity: 0;
            transition: all 0.25s ease;
        }

        .app-toast.show {
            transform: translateY(0);
            opacity: 1;
        }

        .app-toast.success {
            border-color: rgba(16, 185, 129, 0.5);
        }

        .app-toast.error {
            border-color: rgba(239, 68, 68, 0.5);
        }

        .app-toast .toast-icon {
            font-size: 1.1rem;
            margin-top: 2px;
        }

        .app-toast .toast-body {
            flex: 1;
            font-size: 0.9rem;
            line-height: 1.4;
        }

        .app-toast .toast-close {
            background: transparent;
            border: none;
            color: #94a3b8;
            font-size: 1rem;
            line-height: 1;
            padding: 2px;
        }

        /* Fill animation */
        @keyframes fillPulse {
            0% { box-shadow: 0 0 0 0 rgba(99,102,241,0.4); }
            70% { box-shadow: 0 0 0 8px rgba(99,102,241,0); }
            100% { box-shadow: 0 0 0 0 rgba(99,102,241,0); }
        }

        .input-filled {
            animation: fillPulse 0.6s ease;
            border-color: #6366f1 !important;
        }
    </style>
</head>
<body>
    <div class="toast-container" id="toastContainer">
        @if($errors->any())
            <div class="app-toast error" data-timeout="6000">
                <i class="bi bi-exclamation-circle-fill toast-icon"></i>
                <div class="toast-body">{{ $errors->first() }}</div>
                <button class="toast-close" type="button" aria-label="Close">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        @endif
        @if(session('success'))
            <div class="app-toast success" data-timeout="4000">
                <i class="bi bi-check-circle-fill toast-icon"></i>
                <div class="toast-body">{{ session('success') }}</div>
                <button class="toast-close" type="button" aria-label="Close">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        @endif
    </div>
    <div class="login-wrapper">
        <div class="login-card">
            <div class="logo">
                <div class="logo-icon">
                    <img src="{{ asset('image.png') }}" alt="Logo">
                </div>
                <h4>Peminjaman Alat</h4>
                <p>Sistem Manajemen Peminjaman Sekolah</p>
            </div>

            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf
                <div class="input-wrapper">
                    <label class="form-label">Email Address</label>
                    <div class="position-relative">
                        <i class="bi bi-envelope-fill input-icon"></i>
                        <input type="email" name="email" id="emailInput" class="form-control" placeholder="nama@sekolah.sch.id" value="{{ old('email') }}" required autofocus>
                    </div>
                </div>

                <div class="input-wrapper">
                    <label class="form-label">Password</label>
                    <div class="position-relative">
                        <i class="bi bi-lock-fill input-icon"></i>
                        <input type="password" name="password" id="passwordInput" class="form-control password-field" placeholder="Masukkan password" required>
                        <button type="button" class="password-toggle" id="togglePassword" aria-label="Lihat password">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                </div>

                <div class="form-check">
                    <input type="checkbox" name="remember" class="form-check-input" id="remember">
                    <label class="form-check-label" for="remember">Ingat Saya</label>
                </div>

                <button type="submit" class="btn btn-login">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Masuk Sekarang
                </button>
            </form>


        </div>
    </div>

    <script>
        const passwordInput = document.getElementById('passwordInput');
        const togglePasswordBtn = document.getElementById('togglePassword');
        const toastContainer = document.getElementById('toastContainer');

        if (togglePasswordBtn && passwordInput) {
            togglePasswordBtn.addEventListener('click', () => {
                const isHidden = passwordInput.type === 'password';
                passwordInput.type = isHidden ? 'text' : 'password';
                const icon = togglePasswordBtn.querySelector('i');
                if (icon) {
                    icon.classList.toggle('bi-eye', !isHidden);
                    icon.classList.toggle('bi-eye-slash', isHidden);
                }
                passwordInput.focus();
            });
        }

        if (toastContainer) {
            toastContainer.querySelectorAll('.app-toast').forEach((toast) => {
                const timeout = Number(toast.dataset.timeout || 4000);
                requestAnimationFrame(() => toast.classList.add('show'));
                const closeBtn = toast.querySelector('.toast-close');
                if (closeBtn) {
                    closeBtn.addEventListener('click', () => toast.remove());
                }
                if (timeout > 0) {
                    setTimeout(() => {
                        toast.classList.remove('show');
                        setTimeout(() => toast.remove(), 300);
                    }, timeout);
                }
            });
        }


    </script>
</body>
</html>

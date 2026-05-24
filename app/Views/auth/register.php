<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - Smart Support Service</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        body {
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.1) 0%, rgba(6, 182, 212, 0.1) 100%), 
                        url('/assets/img/background_support.png') no-repeat center center fixed;
            background-size: cover; min-height: 100vh; display: flex; align-items: center; justify-content: center;
        }
        .login-wrapper {
            width: 100%; min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 24px;
            background: radial-gradient(circle at top right, rgba(6, 182, 212, 0.15), transparent 40%), radial-gradient(circle at bottom left, rgba(79, 70, 229, 0.15), transparent 40%);
        }
        .login-card {
            background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px);
            width: 100%; max-width: 440px; border-radius: 24px; padding: 40px;
            box-shadow: 0 20px 40px -15px rgba(15, 23, 42, 0.1), 0 0 0 1px rgba(255, 255, 255, 0.6) inset;
            border: 1px solid rgba(226, 232, 240, 0.8); position: relative;
        }
        .login-card::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 5px;
            background: linear-gradient(90deg, #06b6d4, #4f46e5); border-top-left-radius: 24px; border-top-right-radius: 24px;
        }
        .brand-header { text-align: center; margin-bottom: 28px; }
        .brand-logo {
            width: 48px; height: 48px; background: linear-gradient(135deg, #06b6d4, #4f46e5); border-radius: 14px;
            margin: 0 auto 16px; display: flex; align-items: center; justify-content: center; color: white;
            box-shadow: 0 8px 16px rgba(79, 70, 229, 0.25);
        }
        .login-card h2 { font-size: 24px; font-weight: 700; color: #0f172a; letter-spacing: -0.5px; }
        .login-card p { color: #64748b; margin-top: 6px; font-size: 14px; }
        .form-group { margin-bottom: 18px; }
        label { display: block; margin-bottom: 8px; color: #475569; font-weight: 600; font-size: 13px; }
        .input-wrapper { position: relative; display: flex; align-items: center; }
        .input-wrapper .input-icon { position: absolute; left: 16px; color: #94a3b8; pointer-events: none; transition: color 0.2s ease; }
        .toggle-password { position: absolute; right: 16px; color: #94a3b8; cursor: pointer; background: none; border: none; display: flex; align-items: center; }
        .toggle-password:hover { color: #4f46e5; }
        input {
            width: 100%; padding: 14px 44px 14px 46px; border: 1.5px solid #e2e8f0; border-radius: 14px;
            font-size: 14px; color: #0f172a; background-color: rgba(248, 250, 252, 0.8); outline: none; transition: all 0.2s;
        }
        input:focus { background-color: #ffffff; border-color: #4f46e5; box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.12); }
        input:focus ~ .input-icon { color: #4f46e5; }
        button[type="submit"] {
            width: 100%; padding: 14px; border: none; border-radius: 14px;
            background: linear-gradient(135deg, #3b82f6 0%, #4f46e5 100%); color: white;
            font-size: 15px; font-weight: 600; cursor: pointer; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2); transition: all 0.2s ease; margin-top: 10px;
        }
        button[type="submit"]:hover { transform: translateY(-1px); box-shadow: 0 8px 20px rgba(79, 70, 229, 0.3); filter: brightness(1.05); }
        .alert {
            padding: 12px 16px; border-radius: 14px; background: rgba(254, 242, 242, 0.9); border: 1px solid rgba(254, 226, 226, 1);
            color: #991b1b; margin-bottom: 20px; font-size: 13px; display: flex; align-items: center; gap: 10px; font-weight: 500;
        }
        .register-container { text-align: center; margin-top: 24px; font-size: 14px; color: #64748b; }
        .register-container a { color: #4f46e5; text-decoration: none; font-weight: 600; }
        .register-container a:hover { color: #3b82f6; text-decoration: underline; }
        .info { margin-top: 32px; border-top: 1px solid rgba(226, 232, 240, 0.6); padding-top: 16px; font-size: 12px; color: #94a3b8; text-align: center; }
    </style>
</head>
<body>

<div class="login-wrapper">
    <div class="login-card">
        <div class="brand-header">
            <div class="brand-logo">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
            </div>
            <h2>Daftar Akun</h2>
            <p>Buat akun baru Smart Support Service</p>
        </div>

        <?php if (session()->getFlashdata('error')) : ?>
            <div class="alert">
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <span><?= session()->getFlashdata('error') ?></span>
            </div>
        <?php endif; ?>

        <form action="/register/process" method="post">
            <div class="form-group">
                <label>Nama Lengkap</label>
                <div class="input-wrapper">
                    <svg class="input-icon" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <input type="text" name="nama" placeholder="Nama Lengkap Anda" value="<?= old('nama') ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label>Email</label>
                <div class="input-wrapper">
                    <svg class="input-icon" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <input type="email" name="email" placeholder="Masukkan Email Anda" value="<?= old('email') ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label>Password</label>
                <div class="input-wrapper">
                    <svg class="input-icon" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    <input type="password" id="password" name="password" placeholder="Buat password minimal 6 karakter" required>
                    <button type="button" class="toggle-password" onclick="togglePasswordVisibility()">
                        <svg id="eye-icon" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
            </div>

            <button type="submit">Daftar Akun Baru</button>
        </form>

        <div class="register-container">
            Sudah punya akun? <a href="/login">Login di sini</a>
        </div>

        <div class="info">
            Copyright &copy; 2026 Support Service.<br>All rights reserved.
        </div>
    </div>
</div>

<script>
    function togglePasswordVisibility() {
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eye-icon');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />`;
        } else {
            passwordInput.type = 'password';
            eyeIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />`;
        }
    }
</script>
</body>
</html>
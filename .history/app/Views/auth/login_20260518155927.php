<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Smart Support Service</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background: url('/assets/img/background_support.png') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
        }

        .login-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .login-card {
            background: #ffffff;
            width: 100%;
            max-width: 440px;
            border-radius: 24px;
            padding: 40px;
            /* Double shadow untuk efek kedalaman yang smooth */
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.05), 0 10px 15px -3px rgb(0 0 0 / 0.05), 0 20px 25px -5px rgb(0 0 0 / 0.02);
            border: 1px solid rgba(241, 245, 249, 1);
            position: relative;
            overflow: hidden;
        }

        /* Aksen dekoratif di bagian atas card */
        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, #4f46e5, #06b6d4);
        }

        .brand-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .login-card h2 {
            font-size: 26px;
            font-weight: 700;
            color: #0f172a;
            letter-spacing: -0.5px;
        }

        .login-card p {
            color: #64748b;
            margin-top: 8px;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #334155;
            font-weight: 600;
            font-size: 14px;
        }

        /* Input wrapper untuk meletakkan ikon */
        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-wrapper svg {
            position: absolute;
            left: 14px;
            color: #94a3b8;
            transition: color 0.2s;
        }

        input {
            width: 100%;
            padding: 13px 16px 13px 44px;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            font-size: 14px;
            color: #0f172a;
            background-color: #f8fafc;
            outline: none;
            transition: all 0.2s ease-in-out;
        }

        /* Efek fokus yang glowing dan responsif */
        input:focus {
            background-color: #ffffff;
            border-color: #4f46e5;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        }

        input:focus + svg {
            color: #4f46e5;
        }

        input::placeholder {
            color: #94a3b8;
        }

        button {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 12px;
            background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
            color: white;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
            transition: all 0.2s ease;
            margin-top: 10px;
        }

        button:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(79, 70, 229, 0.35);
            background: linear-gradient(135deg, #4338ca 0%, #3730a3 100%);
        }

        button:active {
            transform: translateY(1px);
        }

        /* Desain alert error yang lebih soft */
        .alert {
            padding: 14px 16px;
            border-radius: 12px;
            background: #fef2f2;
            border: 1px solid #fee2e2;
            color: #991b1b;
            margin-bottom: 24px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
        }

        .info {
            margin-top: 32px;
            border-top: 1px solid #f1f5f9;
            padding-top: 20px;
            font-size: 12px;
            color: #94a3b8;
            text-align: center;
            line-height: 1.5;
        }
    </style>
</head>
<body>

<div class="login-wrapper">
    <div class="login-card">
        <div class="brand-header">
            <h2>Login Admin</h2>
            <p>Masuk ke dashboard Support Service</p>
        </div>

        <?php if (session()->getFlashdata('error')) : ?>
            <div class="alert">
                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <form action="/login/process" method="post">
            <div class="form-group">
                <label>Email</label>
                <div class="input-wrapper">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 002-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <input type="email" name="email" placeholder="admin@support.com" required>
                </div>
            </div>

            <div class="form-group">
                <label>Password</label>
                <div class="input-wrapper">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    <input type="password" name="password" placeholder="Masukkan password" required>
                </div>
            </div>

            <button type="submit">Login ke Dashboard</button>
        </form>

        <div class="info">
            Copyright &copy; 2026 Support Service.<br>All rights reserved.
        </div>
    </div>
</div>

</body>
</html>
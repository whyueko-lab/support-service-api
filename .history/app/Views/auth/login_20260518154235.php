<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Admin - Support Service</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 0;
        }

        .login-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-card {
            background: white;
            width: 100%;
            max-width: 420px;
            border-radius: 14px;
            padding: 30px;
            box-shadow: 0 5px 18px rgba(0,0,0,0.12);
        }

        .login-card h2 {
            margin: 0;
            color: #111827;
        }

        .login-card p {
            color: #6b7280;
            margin-top: 8px;
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #374151;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            background: #1f2937;
            color: white;
            font-size: 15px;
            cursor: pointer;
        }

        button:hover {
            background: #374151;
        }

        .alert {
            padding: 12px;
            border-radius: 8px;
            background: #fee2e2;
            color: #991b1b;
            margin-bottom: 18px;
            font-size: 14px;
        }

        .info {
            margin-top: 20px;
            background: #f3f4f6;
            padding: 12px;
            border-radius: 8px;
            font-size: 13px;
            color: #374151;
            line-height: 1.5;
        }
    </style>
</head>
<body>

<div class="login-wrapper">
    <div class="login-card">
        <h2>Login Admin</h2>
        <p>Masuk ke dashboard Support Service</p>

        <?php if (session()->getFlashdata('error')) : ?>
            <div class="alert">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <form action="/login/process" method="post">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="admin@support.com" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Masukkan password" required>
            </div>

            <button type="submit">Login</button>
        </form>

        <div class="info">
            <strong>Copyright &copy; 2026 Support Service. All rights reserved.</strong>
        </div>
    </div>
</div>

</body>
</html>
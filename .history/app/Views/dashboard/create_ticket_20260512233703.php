<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Buat Tiket - Support Service</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 0;
        }

        .navbar {
            background: #1f2937;
            color: white;
            padding: 18px 30px;
        }

        .navbar h2 {
            margin: 0;
            font-size: 22px;
        }

        .container {
            padding: 30px;
            max-width: 850px;
            margin: auto;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 22px;
        }

        .topbar h3 {
            margin: 0;
            color: #111827;
        }

        .topbar p {
            margin: 5px 0 0;
            color: #6b7280;
        }

        .card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
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

        select,
        textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            box-sizing: border-box;
        }

        textarea {
            min-height: 150px;
            resize: vertical;
        }

        .btn {
            display: inline-block;
            padding: 10px 15px;
            border-radius: 8px;
            background: #1f2937;
            color: white;
            text-decoration: none;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-secondary {
            background: #6b7280;
        }

        .info {
            background: #f3f4f6;
            padding: 14px;
            border-radius: 8px;
            color: #374151;
            margin-bottom: 20px;
            font-size: 14px;
            line-height: 1.6;
        }

        .actions {
            display: flex;
            gap: 10px;
        }
    </style>
</head>
<body>

<div class="navbar">
    <h2>Support Service Dashboard</h2>
</div>

<div class="container">

    <div class="topbar">
        <div>
            <h3>Buat Tiket Baru</h3>
            <p>Input keluhan pelanggan dan sistem akan menentukan prioritas otomatis.</p>
        </div>
    </div>

    <div class="card">

        <div class="info">
            Setelah tiket disimpan, sistem akan menjalankan klasifikasi Naive Bayes,
            menentukan prioritas, menghitung deadline SLA, dan memilih teknisi berdasarkan
            beban kerja paling ringan.
        </div>

        <form action="/dashboard/tickets/store" method="post">

            <div class="form-group">
                <label>Customer</label>
                <select name="id_user" required>
                    <option value="">-- Pilih Customer --</option>
                    <?php foreach ($customers as $c) : ?>
                        <option value="<?= esc($c['id_user']) ?>">
                            <?= esc($c['nama']) ?> - <?= esc($c['email']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Deskripsi Keluhan</label>
                <textarea name="deskripsi" placeholder="Contoh: Internet kantor tidak bisa digunakan sejak pagi..." required></textarea>
            </div>

            <div class="actions">
                <button type="submit" class="btn">Simpan Tiket</button>
                <a href="/dashboard/tickets" class="btn btn-secondary">Kembali</a>
            </div>

        </form>
    </div>

</div>

</body>
</html>
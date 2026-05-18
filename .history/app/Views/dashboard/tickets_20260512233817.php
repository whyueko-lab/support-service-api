<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Tiket - Support Service</title>

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

        .btn {
            display: inline-block;
            padding: 9px 14px;
            border-radius: 8px;
            background: #1f2937;
            color: white;
            text-decoration: none;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-small {
            padding: 6px 10px;
            font-size: 13px;
        }

        .card {
            background: white;
            border-radius: 12px;
            padding: 22px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1100px;
        }

        table th {
            background: #f3f4f6;
            text-align: left;
            padding: 12px;
            color: #374151;
            font-size: 14px;
        }

        table td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 14px;
            vertical-align: top;
        }

        .badge {
            padding: 5px 9px;
            border-radius: 20px;
            font-size: 12px;
            background: #e5e7eb;
            display: inline-block;
        }

        .high {
            background: #fee2e2;
            color: #991b1b;
        }

        .medium {
            background: #fef3c7;
            color: #92400e;
        }

        .low {
            background: #dcfce7;
            color: #166534;
        }

        .open {
            background: #e0f2fe;
            color: #075985;
        }

        .in_progress {
            background: #ede9fe;
            color: #5b21b6;
        }

        .done {
            background: #dcfce7;
            color: #166534;
        }

        .overdue {
            background: #fee2e2;
            color: #991b1b;
        }

        select {
            padding: 6px;
            border-radius: 6px;
            border: 1px solid #d1d5db;
        }

        form {
            display: flex;
            gap: 6px;
            align-items: center;
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
            <h3>Daftar Tiket</h3>
            <p>Monitoring seluruh tiket layanan pelanggan</p>
        </div>

        <div>
    <a href="/dashboard/tickets/create" class="btn">+ Buat Tiket</a>
    <a href="/dashboard" class="btn">Kembali ke Dashboard</a>
</div>

    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Teknisi</th>
                    <th>Deskripsi</th>
                    <th>Kategori</th>
                    <th>Prioritas</th>
                    <th>Status</th>
                    <th>Deadline</th>
                    <th>Tanggal Masuk</th>
                    <th>Update Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($tickets)) : ?>
                    <?php foreach ($tickets as $t) : ?>
                        <tr>
                            <td>#<?= esc($t['id_tiket']) ?></td>
                            <td><?= esc($t['nama_customer']) ?></td>
                            <td><?= esc($t['nama_teknisi'] ?? '-') ?></td>
                            <td><?= esc($t['deskripsi']) ?></td>
                            <td><?= esc($t['kategori']) ?></td>
                            <td>
                                <span class="badge <?= esc($t['prioritas']) ?>">
                                    <?= strtoupper(esc($t['prioritas'])) ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge <?= esc($t['status']) ?>">
                                    <?= strtoupper(str_replace('_', ' ', esc($t['status']))) ?>
                                </span>
                            </td>
                            <td><?= esc($t['deadline'] ?? '-') ?></td>
                            <td><?= esc($t['tanggal_masuk']) ?></td>
                            <td>
                                <form action="/dashboard/tickets/update-status/<?= esc($t['id_tiket']) ?>" method="post">
                                    <select name="status">
                                        <option value="open" <?= $t['status'] == 'open' ? 'selected' : '' ?>>Open</option>
                                        <option value="in_progress" <?= $t['status'] == 'in_progress' ? 'selected' : '' ?>>In Progress</option>
                                        <option value="done" <?= $t['status'] == 'done' ? 'selected' : '' ?>>Done</option>
                                        <option value="overdue" <?= $t['status'] == 'overdue' ? 'selected' : '' ?>>Overdue</option>
                                    </select>
                                    <button type="submit" class="btn btn-small">Simpan</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="10">Belum ada data tiket.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>
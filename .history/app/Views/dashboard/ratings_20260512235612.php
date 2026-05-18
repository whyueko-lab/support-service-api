<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rating Pelanggan - Support Service</title>

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
            min-width: 1000px;
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
            display: inline-block;
            background: #e5e7eb;
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

        .done {
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

        .overdue {
            background: #fee2e2;
            color: #991b1b;
        }

        .stars {
            font-size: 18px;
            letter-spacing: 1px;
        }

        .comment {
            max-width: 350px;
            line-height: 1.5;
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
            <h3>Rating dan Feedback Pelanggan</h3>
            <p>Evaluasi kepuasan pelanggan terhadap layanan support service</p>
        </div>

        <div>
            <a href="/dashboard/tickets" class="btn">Daftar Tiket</a>
            <a href="/dashboard" class="btn">Dashboard</a>
        </div>
    </div>

    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>ID Rating</th>
                    <th>ID Tiket</th>
                    <th>Customer</th>
                    <th>Deskripsi Tiket</th>
                    <th>Prioritas</th>
                    <th>Status</th>
                    <th>Rating</th>
                    <th>Komentar</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($ratings)) : ?>
                    <?php foreach ($ratings as $r) : ?>
                        <tr>
                            <td>#<?= esc($r['id_rating']) ?></td>
                            <td>
                                <a href="/dashboard/tickets/detail/<?= esc($r['id_tiket']) ?>">
                                    #<?= esc($r['id_tiket']) ?>
                                </a>
                            </td>
                            <td>
                                <strong><?= esc($r['nama_customer']) ?></strong><br>
                                <small><?= esc($r['email_customer']) ?></small>
                            </td>
                            <td><?= esc($r['deskripsi']) ?></td>
                            <td>
                                <span class="badge <?= esc($r['prioritas']) ?>">
                                    <?= strtoupper(esc($r['prioritas'])) ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge <?= esc($r['status']) ?>">
                                    <?= strtoupper(str_replace('_', ' ', esc($r['status']))) ?>
                                </span>
                            </td>
                            <td>
                                <strong><?= esc($r['nilai_rating']) ?>/5</strong>
                                <div class="stars">
                                    <?php for ($i = 1; $i <= 5; $i++) : ?>
                                        <?= $i <= $r['nilai_rating'] ? '★' : '☆' ?>
                                    <?php endfor; ?>
                                </div>
                            </td>
                            <td class="comment"><?= esc($r['komentar'] ?? '-') ?></td>
                            <td><?= esc($r['tanggal']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="9">Belum ada data rating pelanggan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>
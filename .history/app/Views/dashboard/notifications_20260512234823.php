<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Notifikasi - Support Service</title>

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

        .unread {
            background: #fee2e2;
            color: #991b1b;
        }

        .read {
            background: #dcfce7;
            color: #166534;
        }

        .role {
            background: #e0f2fe;
            color: #075985;
        }

        .message {
            max-width: 380px;
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
            <h3>Daftar Notifikasi</h3>
            <p>Monitoring seluruh notifikasi sistem untuk customer dan teknisi</p>
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
                    <th>ID</th>
                    <th>Penerima</th>
                    <th>Role</th>
                    <th>ID Tiket</th>
                    <th>Prioritas Tiket</th>
                    <th>Status Tiket</th>
                    <th>Pesan</th>
                    <th>Waktu</th>
                    <th>Status Baca</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($notifications)) : ?>
                    <?php foreach ($notifications as $n) : ?>
                        <tr>
                            <td>#<?= esc($n['id_notifikasi']) ?></td>
                            <td>
                                <strong><?= esc($n['nama_user']) ?></strong><br>
                                <small><?= esc($n['email_user']) ?></small>
                            </td>
                            <td>
                                <span class="badge role">
                                    <?= strtoupper(esc($n['role_user'])) ?>
                                </span>
                            </td>
                            <td>
                                <?php if (!empty($n['id_tiket'])) : ?>
                                    <a href="/dashboard/tickets/detail/<?= esc($n['id_tiket']) ?>">
                                        #<?= esc($n['id_tiket']) ?>
                                    </a>
                                <?php else : ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td><?= esc($n['prioritas_tiket'] ?? '-') ?></td>
                            <td><?= esc($n['status_tiket'] ?? '-') ?></td>
                            <td class="message"><?= esc($n['pesan']) ?></td>
                            <td><?= esc($n['waktu']) ?></td>
                            <td>
                                <?php if ($n['status_baca'] == 1) : ?>
                                    <span class="badge read">Sudah dibaca</span>
                                <?php else : ?>
                                    <span class="badge unread">Belum dibaca</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="9">Belum ada data notifikasi.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>
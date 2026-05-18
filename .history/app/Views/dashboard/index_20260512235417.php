<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - Support Service</title>

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

        .title {
            margin-bottom: 20px;
        }

        .title h3 {
            margin: 0;
            color: #111827;
        }

        .title p {
            margin: 5px 0 0;
            color: #6b7280;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
            margin-bottom: 30px;
        }

        .card {
            background: white;
            border-radius: 12px;
            padding: 22px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
        }

        .card h4 {
            margin: 0;
            color: #6b7280;
            font-size: 14px;
        }

        .card .number {
            margin-top: 10px;
            font-size: 32px;
            font-weight: bold;
            color: #111827;
        }

        .section {
            background: white;
            border-radius: 12px;
            padding: 22px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
            margin-bottom: 25px;
        }

        .section h3 {
            margin-top: 0;
            color: #111827;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th {
            background: #f3f4f6;
            text-align: left;
            padding: 12px;
            color: #374151;
        }

        table td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
        }

        .badge {
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 13px;
            background: #e5e7eb;
        }

        @media (max-width: 900px) {
            .cards {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 600px) {
            .cards {
                grid-template-columns: 1fr;
            }

            .container {
                padding: 15px;
            }
        }
    </style>
</head>
<body>

<div class="navbar">
    <h2>Support Service Dashboard</h2>
</div>

<div class="container">

    <div class="title">
    <h3>Dashboard Admin</h3>
    <p>Monitoring tiket, SLA, teknisi, dan kepuasan pelanggan</p>
    <br>
    <a href="/dashboard/tickets" style="display:inline-block;padding:10px 15px;background:#1f2937;color:white;text-decoration:none;border-radius:8px;">
    Lihat Daftar Tiket
    </a>

    <a href="/dashboard/notifications" style="display:inline-block;padding:10px 15px;background:#374151;color:white;text-decoration:none;border-radius:8px;margin-left:8px;">
    Lihat Notifikasi
    </a>
</div>

    <div class="cards">
        <div class="card">
            <h4>Total Tiket</h4>
            <div class="number"><?= $totalTiket ?></div>
        </div>

        <div class="card">
            <h4>Total Notifikasi</h4>
            <div class="number"><?= $totalNotifikasi ?></div>
        </div>

        <div class="card">
            <h4>Belum Dibaca</h4>
            <div class="number"><?= $notifikasiBelumDibaca ?></div>
        </div>

        <div class="card">
            <h4>Open</h4>
            <div class="number"><?= $open ?></div>
        </div>

        <div class="card">
            <h4>In Progress</h4>
            <div class="number"><?= $inProgress ?></div>
        </div>

        <div class="card">
            <h4>Done</h4>
            <div class="number"><?= $done ?></div>
        </div>

        <div class="card">
            <h4>Overdue</h4>
            <div class="number"><?= $overdue ?></div>
        </div>

        <div class="card">
            <h4>Total Customer</h4>
            <div class="number"><?= $totalCustomer ?></div>
        </div>

        <div class="card">
            <h4>Total Teknisi</h4>
            <div class="number"><?= $totalTeknisi ?></div>
        </div>

        <div class="card">
            <h4>Kepuasan Pengguna</h4>
            <div class="number"><?= $kepuasanPersen ?>%</div>
        </div>
    </div>

    <div class="section">
        <h3>Ringkasan Rating</h3>
        <p>Total Rating: <strong><?= $totalRating ?></strong></p>
        <p>Rata-rata Rating: <strong><?= $rataRataRating ?></strong> dari 5</p>
        <p>Kepuasan Pengguna: <strong><?= $kepuasanPersen ?>%</strong></p>
    </div>

    <div class="section">
        <h3>Beban Kerja Teknisi</h3>

        <table>
            <thead>
                <tr>
                    <th>Nama Teknisi</th>
                    <th>Tiket Aktif</th>
                    <th>Tiket Selesai</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($bebanTeknisi)) : ?>
                    <?php foreach ($bebanTeknisi as $t) : ?>
                        <tr>
                            <td><?= esc($t['nama_teknisi']) ?></td>
                            <td><span class="badge"><?= $t['tiket_aktif'] ?> tiket</span></td>
                            <td><span class="badge"><?= $t['tiket_selesai'] ?> tiket</span></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="3">Belum ada data teknisi</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>
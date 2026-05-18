<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan KPI/SLA - Support Service</title>

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
            font-size: 30px;
            font-weight: bold;
            color: #111827;
        }

        .section {
            background: white;
            border-radius: 12px;
            padding: 22px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
            margin-bottom: 25px;
            overflow-x: auto;
        }

        .section h3 {
            margin-top: 0;
            color: #111827;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 900px;
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
        }

        .badge {
            padding: 5px 9px;
            border-radius: 20px;
            font-size: 12px;
            display: inline-block;
            background: #e5e7eb;
        }

        .good {
            background: #dcfce7;
            color: #166534;
        }

        .bad {
            background: #fee2e2;
            color: #991b1b;
        }

        .warning {
            background: #fef3c7;
            color: #92400e;
        }

        .progress {
            background: #e5e7eb;
            border-radius: 20px;
            height: 14px;
            overflow: hidden;
            width: 100%;
        }

        .progress-bar {
            background: #111827;
            height: 14px;
            border-radius: 20px;
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

    <div class="topbar">
        <div>
            <h3>Laporan KPI/SLA</h3>
            <p>Evaluasi performa layanan, ketepatan waktu penyelesaian, dan kepuasan pelanggan</p>
        </div>

        <div>
            <a href="/dashboard/tickets" class="btn">Daftar Tiket</a>
            <a href="/dashboard" class="btn">Dashboard</a>
        </div>
    </div>

    <div class="cards">
        <div class="card">
            <h4>Total Tiket</h4>
            <div class="number"><?= $totalTiket ?></div>
        </div>

        <div class="card">
            <h4>Tiket Selesai</h4>
            <div class="number"><?= $totalDone ?></div>
        </div>

        <div class="card">
            <h4>On-Time</h4>
            <div class="number"><?= $onTime ?></div>
        </div>

        <div class="card">
            <h4>Overdue</h4>
            <div class="number"><?= $totalOverdue ?></div>
        </div>

        <div class="card">
            <h4>Persentase On-Time</h4>
            <div class="number"><?= $persenOnTime ?>%</div>
        </div>

        <div class="card">
            <h4>Terlambat Selesai</h4>
            <div class="number"><?= $lateDone ?></div>
        </div>

        <div class="card">
            <h4>Kepuasan Pengguna</h4>
            <div class="number"><?= $kepuasanPersen ?>%</div>
        </div>

        <div class="card">
            <h4>Rata-rata Rating</h4>
            <div class="number"><?= $rataRataRating ?>/5</div>
        </div>
    </div>

    <div class="section">
        <h3>Ringkasan SLA</h3>

        <p><strong>Persentase tiket selesai tepat waktu:</strong> <?= $persenOnTime ?>%</p>
        <div class="progress">
            <div class="progress-bar" style="width: <?= $persenOnTime ?>%;"></div>
        </div>

        <br>

        <p><strong>Persentase tiket selesai terlambat:</strong> <?= $persenLate ?>%</p>
        <div class="progress">
            <div class="progress-bar" style="width: <?= $persenLate ?>%;"></div>
        </div>

        <br>

        <p>
            Berdasarkan data tiket yang telah selesai, sistem menghitung ketepatan penyelesaian
            dengan membandingkan tanggal selesai dan deadline SLA. Tiket dikategorikan on-time
            apabila tanggal selesai tidak melewati deadline.
        </p>
    </div>

    <div class="section">
        <h3>Performa Teknisi</h3>

        <table>
            <thead>
                <tr>
                    <th>Nama Teknisi</th>
                    <th>Email</th>
                    <th>Total Tiket</th>
                    <th>Selesai</th>
                    <th>On-Time</th>
                    <th>Overdue</th>
                    <th>Persentase On-Time</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($performaTeknisi)) : ?>
                    <?php foreach ($performaTeknisi as $t) : ?>
                        <tr>
                            <td><?= esc($t['nama_teknisi']) ?></td>
                            <td><?= esc($t['email']) ?></td>
                            <td><?= esc($t['total_tiket']) ?></td>
                            <td><?= esc($t['done']) ?></td>
                            <td><?= esc($t['on_time']) ?></td>
                            <td><?= esc($t['overdue']) ?></td>
                            <td><?= esc($t['persen_on_time']) ?>%</td>
                            <td>
                                <?php if ($t['persen_on_time'] >= 80) : ?>
                                    <span class="badge good">Baik</span>
                                <?php elseif ($t['persen_on_time'] >= 60) : ?>
                                    <span class="badge warning">Cukup</span>
                                <?php else : ?>
                                    <span class="badge bad">Perlu Evaluasi</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="8">Belum ada data teknisi.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>
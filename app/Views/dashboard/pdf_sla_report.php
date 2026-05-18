<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan KPI/SLA</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #111;
        }

        h1 {
            text-align: center;
            font-size: 18px;
            margin-bottom: 4px;
        }

        h2 {
            font-size: 14px;
            margin-top: 22px;
            border-bottom: 1px solid #333;
            padding-bottom: 5px;
        }

        .subtitle {
            text-align: center;
            font-size: 12px;
            margin-bottom: 20px;
        }

        .info {
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            margin-bottom: 18px;
        }

        table th,
        table td {
            border: 1px solid #333;
            padding: 7px;
            text-align: left;
            vertical-align: top;
        }

        table th {
            background: #eeeeee;
        }

        .summary-table td {
            width: 50%;
        }

        .footer {
            margin-top: 30px;
            font-size: 11px;
            text-align: right;
        }
    </style>
</head>
<body>

    <h1>LAPORAN KPI/SLA SUPPORT SERVICE</h1>
    <div class="subtitle">
        Sistem Support Service Berbasis Android dengan Naive Bayes, Priority Queue, dan Load Balancing
    </div>

    <div class="info">
        <strong>Tanggal Cetak:</strong> <?= esc($tanggalCetak) ?><br>
        <strong>Jenis Laporan:</strong> Evaluasi KPI/SLA, Overdue, On-Time, dan Kepuasan Pelanggan<br>
        <strong>Periode:</strong>
        <?php if (!empty($startDate) || !empty($endDate)) : ?>
            <?= esc($startDate ?: '-') ?> sampai <?= esc($endDate ?: '-') ?>
        <?php else : ?>
            Semua Periode
        <?php endif; ?>
    </div>

    <h2>1. Ringkasan Layanan</h2>

    <table class="summary-table">
        <tr>
            <td>Total Tiket</td>
            <td><?= esc($totalTiket) ?></td>
        </tr>
        <tr>
            <td>Tiket Selesai</td>
            <td><?= esc($totalDone) ?></td>
        </tr>
        <tr>
            <td>Tiket On-Time</td>
            <td><?= esc($onTime) ?></td>
        </tr>
        <tr>
            <td>Tiket Overdue</td>
            <td><?= esc($totalOverdue) ?></td>
        </tr>
        <tr>
            <td>Tiket Selesai Terlambat</td>
            <td><?= esc($lateDone) ?></td>
        </tr>
        <tr>
            <td>Persentase On-Time</td>
            <td><?= esc($persenOnTime) ?>%</td>
        </tr>
        <tr>
            <td>Persentase Terlambat</td>
            <td><?= esc($persenLate) ?>%</td>
        </tr>
    </table>

    <h2>2. Ringkasan Kepuasan Pelanggan</h2>

    <table class="summary-table">
        <tr>
            <td>Total Rating</td>
            <td><?= esc($totalRating) ?></td>
        </tr>
        <tr>
            <td>Rata-rata Rating</td>
            <td><?= esc($rataRataRating) ?>/5</td>
        </tr>
        <tr>
            <td>Persentase Kepuasan Pengguna</td>
            <td><?= esc($kepuasanPersen) ?>%</td>
        </tr>
    </table>

    <h2>3. Performa Teknisi</h2>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Teknisi</th>
                <th>Email</th>
                <th>Total Tiket</th>
                <th>Selesai</th>
                <th>On-Time</th>
                <th>Overdue</th>
                <th>% On-Time</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($performaTeknisi)) : ?>
                <?php $no = 1; ?>
                <?php foreach ($performaTeknisi as $t) : ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= esc($t['nama_teknisi']) ?></td>
                        <td><?= esc($t['email']) ?></td>
                        <td><?= esc($t['total_tiket']) ?></td>
                        <td><?= esc($t['done']) ?></td>
                        <td><?= esc($t['on_time']) ?></td>
                        <td><?= esc($t['overdue']) ?></td>
                        <td><?= esc($t['persen_on_time']) ?>%</td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="8">Belum ada data teknisi.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <h2>4. Kesimpulan Laporan</h2>

    <p>
        Berdasarkan data KPI/SLA, sistem menghitung performa layanan dengan membandingkan
        tanggal penyelesaian tiket dan deadline SLA. Tiket dikategorikan on-time apabila tanggal
        selesai tidak melewati deadline. Data kepuasan pelanggan dihitung berdasarkan rating
        pengguna setelah tiket diselesaikan.
    </p>

    <div class="footer">
        Dicetak otomatis oleh Sistem Support Service
    </div>

</body>
</html>
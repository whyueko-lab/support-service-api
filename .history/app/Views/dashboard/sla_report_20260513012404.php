<?= $this->extend('layouts/dashboard_layout') ?>

<?= $this->section('content') ?>

<style>
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
</style>

<div style="margin-bottom:20px;">
    <a href="/dashboard/reports/sla/download-pdf" class="btn">
        Download Report PDF
    </a>
</div>

<div class="section">
    <h3>Filter Periode Laporan</h3>

    <form action="/dashboard/reports/sla" method="get">
        <div style="display:grid; grid-template-columns: 1fr 1fr auto auto; gap:10px; align-items:end;">

            <div>
                <label>Tanggal Mulai</label>
                <input type="date" name="start_date" value="<?= esc($startDate ?? '') ?>">
            </div>

            <div>
                <label>Tanggal Selesai</label>
                <input type="date" name="end_date" value="<?= esc($endDate ?? '') ?>">
            </div>

            <div>
                <button type="submit" class="btn">Filter</button>
            </div>

            <div>
                <a href="/dashboard/reports/sla" class="btn btn-secondary">Reset</a>
            </div>

        </div>
    </form>
</div>

<div style="margin-bottom:20px;">
    <a href="/dashboard/reports/sla/download-pdf?start_date=<?= esc($startDate ?? '') ?>&end_date=<?= esc($endDate ?? '') ?>" class="btn">
        Download Report PDF
    </a>
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

<?= $this->endSection() ?>
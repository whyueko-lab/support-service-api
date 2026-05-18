<?= $this->extend('layouts/dashboard_layout') ?>

<?= $this->section('content') ?>

<div class="cards">
    <div class="card">
        <h4>Total Tiket</h4>
        <div class="number"><?= $totalTiket ?></div>
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

    <div class="card">
        <h4>Total Rating</h4>
        <div class="number"><?= $totalRating ?></div>
    </div>

    <div class="card">
        <h4>Rata-rata Rating</h4>
        <div class="number"><?= $rataRataRating ?>/5</div>
    </div>

    <div class="card">
        <h4>Total Notifikasi</h4>
        <div class="number"><?= $totalNotifikasi ?? 0 ?></div>
    </div>

    <div class="card">
        <h4>Belum Dibaca</h4>
        <div class="number"><?= $notifikasiBelumDibaca ?? 0 ?></div>
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
                        <td><span class="badge done"><?= $t['tiket_selesai'] ?> tiket</span></td>
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

<?= $this->endSection() ?>
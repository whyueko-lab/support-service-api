<?= $this->extend('layouts/dashboard_layout') ?>

<?= $this->section('content') ?>

<style>
    .stars {
        font-size: 18px;
        letter-spacing: 1px;
    }

    .comment {
        max-width: 350px;
        line-height: 1.5;
    }
</style>

<div class="section">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <div>
            <h3>Rating dan Feedback Pelanggan</h3>
            <p>Evaluasi kepuasan pelanggan terhadap layanan support service.</p>
        </div>

        <a href="/dashboard/tickets" class="btn">Daftar Tiket</a>
    </div>

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

<?= $this->endSection() ?>
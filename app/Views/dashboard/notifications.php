<?= $this->extend('layouts/dashboard_layout') ?>

<?= $this->section('content') ?>

<div class="section">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <div>
            <h3>Daftar Notifikasi</h3>
            <p>Monitoring seluruh notifikasi sistem untuk customer dan teknisi.</p>
        </div>

        <a href="/dashboard/tickets" class="btn">Daftar Tiket</a>
    </div>

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
                            <span class="badge open">
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

                        <td style="max-width:380px; line-height:1.5;">
                            <?= esc($n['pesan']) ?>
                        </td>

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

<?= $this->endSection() ?>
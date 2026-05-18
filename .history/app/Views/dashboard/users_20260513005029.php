<?= $this->extend('layouts/dashboard_layout') ?>

<?= $this->section('content') ?>

<div class="section">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <div>
            <h3>Data User</h3>
            <p>Daftar customer, teknisi, dan admin yang terdaftar pada sistem.</p>
        </div>

        <a href="/dashboard/users/create" class="btn">+ Tambah User</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th>Tanggal Dibuat</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($users)) : ?>
                <?php foreach ($users as $u) : ?>
                    <tr>
                        <td>#<?= esc($u['id_user']) ?></td>
                        <td><?= esc($u['nama']) ?></td>
                        <td><?= esc($u['email']) ?></td>
                        <td>
                            <?php if ($u['role'] === 'admin') : ?>
                                <span class="badge high">ADMIN</span>
                            <?php elseif ($u['role'] === 'teknisi') : ?>
                                <span class="badge medium">TEKNISI</span>
                            <?php else : ?>
                                <span class="badge low">CUSTOMER</span>
                            <?php endif; ?>
                        </td>
                        <td><?= esc($u['created_at'] ?? '-') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="5">Belum ada data user.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>
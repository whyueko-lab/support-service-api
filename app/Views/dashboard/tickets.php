<?= $this->extend('layouts/dashboard_layout') ?>

<?= $this->section('content') ?>

<div class="section">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <div>
            <h3>Daftar Tiket</h3>
            <p>Monitoring seluruh tiket layanan pelanggan</p>
        </div>

        <a href="/dashboard/tickets/create" class="btn">+ Buat Tiket</a>
    </div>

    <form action="/dashboard/tickets" method="get" style="margin-bottom:20px;">
        <div style="display:grid; grid-template-columns: 2fr 1fr 1fr auto auto; gap:10px; align-items:end;">

            <div>
                <label>Keyword</label>
                <input
                    type="text"
                    name="keyword"
                    placeholder="Cari deskripsi, kategori, customer, teknisi..."
                    value="<?= esc($keyword ?? '') ?>"
                >
            </div>

            <div>
                <label>Status</label>
                <select name="status">
                    <option value="">Semua Status</option>
                    <option value="open" <?= ($status ?? '') === 'open' ? 'selected' : '' ?>>Open</option>
                    <option value="in_progress" <?= ($status ?? '') === 'in_progress' ? 'selected' : '' ?>>In Progress</option>
                    <option value="done" <?= ($status ?? '') === 'done' ? 'selected' : '' ?>>Done</option>
                    <option value="overdue" <?= ($status ?? '') === 'overdue' ? 'selected' : '' ?>>Overdue</option>
                </select>
            </div>

            <div>
                <label>Prioritas</label>
                <select name="prioritas">
                    <option value="">Semua Prioritas</option>
                    <option value="high" <?= ($prioritas ?? '') === 'high' ? 'selected' : '' ?>>High</option>
                    <option value="medium" <?= ($prioritas ?? '') === 'medium' ? 'selected' : '' ?>>Medium</option>
                    <option value="low" <?= ($prioritas ?? '') === 'low' ? 'selected' : '' ?>>Low</option>
                </select>
            </div>

            <div>
                <button type="submit" class="btn">Filter</button>
            </div>

            <div>
                <a href="/dashboard/tickets" class="btn btn-secondary">Reset</a>
            </div>

        </div>
    </form>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Customer</th>
                <th>Teknisi</th>
                <th>Deskripsi</th>
                <th>Kategori</th>
                <th>Prioritas</th>
                <th>Status</th>
                <th>Deadline</th>
                <th>Update Status</th>
                <th>Detail</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($tickets)) : ?>
                <?php foreach ($tickets as $t) : ?>
                    <tr>
                        <td>#<?= esc($t['id_tiket']) ?></td>
                        <td><?= esc($t['nama_customer']) ?></td>
                        <td><?= esc($t['nama_teknisi'] ?? '-') ?></td>
                        <td><?= esc($t['deskripsi']) ?></td>
                        <td><?= esc($t['kategori']) ?></td>
                        <td>
                            <span class="badge <?= esc($t['prioritas']) ?>">
                                <?= strtoupper(esc($t['prioritas'])) ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge <?= esc($t['status']) ?>">
                                <?= strtoupper(str_replace('_', ' ', esc($t['status']))) ?>
                            </span>
                        </td>
                        <td><?= esc($t['deadline'] ?? '-') ?></td>
                        <td>
                            <form action="/dashboard/tickets/update-status/<?= esc($t['id_tiket']) ?>" method="post" style="display:flex;gap:6px;">
                                <select name="status">
                                    <option value="open" <?= $t['status'] == 'open' ? 'selected' : '' ?>>Open</option>
                                    <option value="in_progress" <?= $t['status'] == 'in_progress' ? 'selected' : '' ?>>In Progress</option>
                                    <option value="done" <?= $t['status'] == 'done' ? 'selected' : '' ?>>Done</option>
                                    <option value="overdue" <?= $t['status'] == 'overdue' ? 'selected' : '' ?>>Overdue</option>
                                </select>
                                <button type="submit" class="btn btn-small">Simpan</button>
                            </form>
                        </td>
                        <td>
                            <a href="/dashboard/tickets/detail/<?= esc($t['id_tiket']) ?>" class="btn btn-small">
                                Detail
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="10">Belum ada data tiket.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>
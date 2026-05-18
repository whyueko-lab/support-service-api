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

<?= $this->endSection() ?>
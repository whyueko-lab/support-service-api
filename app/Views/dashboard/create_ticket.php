<?= $this->extend('layouts/dashboard_layout') ?>

<?= $this->section('content') ?>

<div class="section" style="max-width:850px;">
    <h3>Buat Tiket Baru</h3>
    <p>Input keluhan pelanggan dan sistem akan menentukan prioritas secara otomatis.</p>

    <div class="info">
        Setelah tiket disimpan, sistem akan menjalankan klasifikasi Naive Bayes,
        menentukan prioritas, menghitung deadline SLA, dan memilih teknisi berdasarkan
        beban kerja paling ringan.
    </div>

    <form action="/dashboard/tickets/store" method="post">

        <div class="form-group">
            <label>Customer</label>
            <select name="id_user" required>
                <option value="">-- Pilih Customer --</option>
                <?php foreach ($customers as $c) : ?>
                    <option value="<?= esc($c['id_user']) ?>">
                        <?= esc($c['nama']) ?> - <?= esc($c['email']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Deskripsi Keluhan</label>
            <textarea name="deskripsi" placeholder="Contoh: Internet kantor tidak bisa digunakan sejak pagi..." required></textarea>
        </div>

        <div class="actions">
            <button type="submit" class="btn">Simpan Tiket</button>
            <a href="/dashboard/tickets" class="btn btn-secondary">Kembali</a>
        </div>

    </form>
</div>

<?= $this->endSection() ?>
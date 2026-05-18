<?= $this->extend('layouts/dashboard_layout') ?>

<?= $this->section('content') ?>

<div class="section" style="max-width:850px;">
    <h3>Tambah User Baru</h3>
    <p>Gunakan form ini untuk menambahkan customer, teknisi, atau admin.</p>

    <form action="/dashboard/users/store" method="post">

        <div class="form-group">
            <label>Nama</label>
            <input type="text" name="nama" placeholder="Masukkan nama user" required>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" placeholder="contoh@email.com" required>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="Masukkan password" required>
        </div>

        <div class="form-group">
            <label>Role</label>
            <select name="role" required>
                <option value="">-- Pilih Role --</option>
                <option value="customer">Customer</option>
                <option value="teknisi">Teknisi</option>
                <option value="admin">Admin</option>
            </select>
        </div>

        <div class="actions">
            <button type="submit" class="btn">Simpan User</button>
            <a href="/dashboard/users" class="btn btn-secondary">Kembali</a>
        </div>

    </form>
</div>

<?= $this->endSection() ?>
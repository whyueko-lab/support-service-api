<?= $this->extend('layouts/dashboard_layout') ?>

<?= $this->section('content') ?>

<div class="section" style="max-width:850px;">
    <h3>Edit User</h3>
    <p>Perbarui data user. Kosongkan password jika tidak ingin mengganti password.</p>

    <form action="/dashboard/users/update/<?= esc($user['id_user']) ?>" method="post">

        <div class="form-group">
            <label>Nama</label>
            <input type="text" name="nama" value="<?= esc($user['nama']) ?>" required>
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="<?= esc($user['email']) ?>" required>
        </div>

        <div class="form-group">
            <label>Password Baru</label>
            <input type="password" name="password" placeholder="Kosongkan jika tidak diganti">
        </div>

        <div class="form-group">
            <label>Role</label>
            <select name="role" required>
                <option value="customer" <?= $user['role'] === 'customer' ? 'selected' : '' ?>>Customer</option>
                <option value="teknisi" <?= $user['role'] === 'teknisi' ? 'selected' : '' ?>>Teknisi</option>
                <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
            </select>
        </div>

        <div class="form-group">
            <label>Status User</label>
            <select name="status_user" required>
                <option value="aktif" <?= ($user['status_user'] ?? 'aktif') === 'aktif' ? 'selected' : '' ?>>
                    Aktif
                </option>
                <option value="nonaktif" <?= ($user['status_user'] ?? 'aktif') === 'nonaktif' ? 'selected' : '' ?>>
                    Nonaktif
                </option>
            </select>
        </div>

        <div class="actions">
            <button type="submit" class="btn">Update User</button>
            <a href="/dashboard/users" class="btn btn-secondary">Kembali</a>
        </div>

    </form>
</div>

<?= $this->endSection() ?>
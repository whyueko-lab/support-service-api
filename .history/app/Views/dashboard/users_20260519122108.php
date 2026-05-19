<?= $this->extend('layouts/dashboard_layout') ?>

<?= $this->section('content') ?>

<!-- Custom Style Khusus untuk Manajemen Tabel Data User -->
<style>
    .table-container {
        background: white;
        border-radius: 16px;
        padding: 28px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.02);
    }

    .table-header-flex {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        gap: 16px;
    }

    .table-header-flex h3 {
        font-size: 20px;
        font-weight: 700;
        color: #0f172a;
        letter-spacing: -0.5px;
        margin-bottom: 4px;
    }

    .table-header-flex p {
        font-size: 14px;
        color: #64748b;
    }

    /* Kustomisasi Desain Tabel */
    .responsive-table-wrapper {
        overflow-x: auto;
        border: 1px solid #f1f5f9;
        border-radius: 12px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
        font-size: 14px;
    }

    th {
        background-color: #f8fafc;
        color: #475569;
        font-weight: 600;
        padding: 14px 18px;
        border-bottom: 2px solid #edf2f7;
        text-transform: uppercase;
        font-size: 12px;
        letter-spacing: 0.5px;
    }

    td {
        padding: 16px 18px;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
        vertical-align: middle;
    }

    tr:last-child td {
        border-bottom: none;
    }

    tr:hover td {
        background-color: #f8fafc;
    }

    /* Pembaruan Skema Warna Badge */
    .badge-role {
        font-weight: 700;
        font-size: 11px;
        padding: 4px 10px;
        border-radius: 6px;
        display: inline-block;
        letter-spacing: 0.3px;
    }
    .badge-role.admin { background: rgba(139, 92, 246, 0.1); color: #7c3aed; }
    .badge-role.teknisi { background: rgba(14, 165, 233, 0.1); color: #0284c7; }
    .badge-role.customer { background: rgba(100, 116, 139, 0.1); color: #475569; }

    .badge-status {
        font-weight: 600;
        font-size: 11px;
        padding: 4px 10px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .badge-status.active { background: #dcfce7; color: #166534; }
    .badge-status.inactive { background: #fee2e2; color: #991b1b; }

    /* Dot indicator inside status badge */
    .badge-status::before {
        content: '';
        width: 6px;
        height: 6px;
        border-radius: 50%;
        display: inline-block;
    }
    .badge-status.active::before { background-color: #15803d; }
    .badge-status.inactive::before { background-color: #b91c1c; }

    /* Desain Tombol Tambah User */
    .btn-add-user {
        background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
        color: white;
        padding: 10px 20px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        text-decoration: none;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.15);
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-add-user:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(79, 70, 229, 0.25);
    }

    /* Tombol Aksi Mikro */
    .action-buttons {
        display: flex;
        gap: 6px;
    }

    .btn-action-edit {
        background-color: #f1f5f9;
        color: #334155;
        border: 1px solid #cbd5e1;
        padding: 6px 12px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 13px;
        text-decoration: none;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .btn-action-edit:hover {
        background-color: #e2e8f0;
        color: #0f172a;
    }

    .btn-action-toggle {
        background-color: transparent;
        color: #64748b;
        border: 1px solid #e2e8f0;
        padding: 6px 12px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 13px;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .btn-action-toggle:hover {
        background-color: #fee2e2;
        color: #dc2626;
        border-color: #fca5a5;
    }
    
    .btn-action-toggle.activate-mode:hover {
        background-color: #dcfce7;
        color: #16a34a;
        border-color: #86efac;
    }
</style>

<div class="table-container">
    <div class="table-header-flex">
        <div>
            <h3>Data User</h3>
            <p>Daftar kelola hak akses akun customer, tim teknisi, dan admin utama sistem.</p>
        </div>

        <a href="/dashboard/users/create" class="btn-add-user">
            <i class="uil uil-user-plus"></i> Tambah User Baru
        </a>
    </div>

    <div class="responsive-table-wrapper">
        <table>
            <thead>
                <tr>
                    <th style="width: 80px;">ID</th>
                    <th>Nama Pengguna</th>
                    <th>Alamat Email</th>
                    <th>Hak Akses / Role</th>
                    <th>Status Akun</th>
                    <th>Tanggal Registrasi</th>
                    <th style="width: 200px;">Aksi Manajemen</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($users)) : ?>
                    <?php foreach ($users as $u) : ?>
                        <tr>
                            <td style="font-weight: 600; color: #64748b;">#<?= esc($u['id_user']) ?></td>
                            <td style="font-weight: 600; color: #0f172a;"><?= esc($u['nama']) ?></td>
                            <td style="color: #475569;"><?= esc($u['email']) ?></td>
                            <td>
                                <?php if ($u['role'] === 'admin') : ?>
                                    <span class="badge-role admin">ADMIN</span>
                                <?php elseif ($u['role'] === 'teknisi') : ?>
                                    <span class="badge-role teknisi">TEKNISI</span>
                                <?php else : ?>
                                    <span class="badge-role customer">CUSTOMER</span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <?php if (($u['status_user'] ?? 'aktif') === 'aktif') : ?>
                                    <span class="badge-status active">AKTIF</span>
                                <?php else : ?>
                                    <span class="badge-status inactive">NONAKTIF</span>
                                <?php endif; ?>
                            </td>
                            <td style="color: #64748b; font-size: 13px;"><?= esc($u['created_at'] ?? '-') ?></td>

                            <td>
                                <div class="action-buttons">
                                    <a href="/dashboard/users/edit/<?= esc($u['id_user']) ?>" class="btn-action-edit">
                                        <i class="uil uil-edit"></i> Edit
                                    </a>

                                    <?php if ($u['id_user'] != session()->get('id_user')) : ?>
                                        <?php $isActive = (($u['status_user'] ?? 'aktif') === 'aktif'); ?>
                                        <a href="/dashboard/users/toggle-status/<?= esc($u['id_user']) ?>"
                                           class="btn-action-toggle <?= !$isActive ? 'activate-mode' : '' ?>"
                                           onclick="return confirm('Apakah Anda yakin ingin merubah status akses pengguna ini?')">
                                            <?= $isActive ? 'Nonaktifkan' : 'Aktifkan' ?>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="7" style="text-align: center; color: #94a3b8; padding: 40px 0;">
                            <i class="uil uil-users-alt" style="font-size: 28px; display: block; margin-bottom: 8px;"></i>
                            Belum ada data user tersimpan di database.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
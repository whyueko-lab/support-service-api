<?= $this->extend('layouts/dashboard_layout') ?>

<?= $this->section('content') ?>

<!-- Custom Style Khusus untuk Manajemen Monitoring Notifikasi Sistem -->
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

    .responsive-table-wrapper {
        overflow-x: auto;
        border: 1px solid #f1f5f9;
        border-radius: 12px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
        font-size: 13.5px; /* Sedikit dikecilkan agar seimbang dengan 9 kolom data */
    }

    th {
        background-color: #f8fafc;
        color: #475569;
        font-weight: 600;
        padding: 14px 16px;
        border-bottom: 2px solid #edf2f7;
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 0.5px;
        white-space: nowrap;
    }

    td {
        padding: 14px 16px;
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

    /* Badge untuk Role User */
    .badge-role {
        font-weight: 700;
        font-size: 11px;
        padding: 4px 8px;
        border-radius: 6px;
        text-transform: uppercase;
    }
    .badge-role.admin { background: rgba(139, 92, 246, 0.1); color: #7c3aed; }
    .badge-role.teknisi { background: rgba(14, 165, 233, 0.1); color: #0284c7; }
    .badge-role.customer { background: rgba(100, 116, 139, 0.1); color: #475569; }

    /* Badge untuk Prioritas Tiket */
    .badge-priority {
        font-weight: 700;
        font-size: 11px;
        padding: 3px 8px;
        border-radius: 6px;
    }
    .badge-priority.high { background-color: #fee2e2; color: #ef4444; }
    .badge-priority.medium { background-color: #fef3c7; color: #d97706; }
    .badge-priority.low { background-color: #dcfce7; color: #16a34a; }
    .badge-priority.none { background-color: #f1f5f9; color: #94a3b8; }

    /* Badge Status Baca Minimalis */
    .badge-read-status {
        font-weight: 600;
        font-size: 12px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .badge-read-status.read { color: #94a3b8; }
    .badge-read-status.unread { color: #2563eb; font-weight: 700; }

    .badge-read-status::before {
        content: '';
        width: 7px;
        height: 7px;
        border-radius: 50%;
        display: inline-block;
    }
    .badge-read-status.read::before { background-color: #cbd5e1; }
    .badge-read-status.unread::before { background-color: #3b82f6; box-shadow: 0 0 8px #3b82f6; }

    /* Tautan Tiket Berbentuk Lencana */
    .ticket-link {
        background-color: #f0fdf4;
        color: #16a34a;
        border: 1px solid #bbf7d0;
        padding: 4px 8px;
        border-radius: 6px;
        font-weight: 600;
        text-decoration: none;
        font-size: 12px;
        display: inline-block;
        transition: all 0.15s ease;
    }
    .ticket-link:hover {
        background-color: #16a34a;
        color: white;
    }

    /* Tombol Navigasi Atas */
    .btn-nav {
        background-color: #ffffff;
        color: #4f46e5;
        border: 1.5px solid #e2e8f0;
        padding: 8px 16px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 13.5px;
        text-decoration: none;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-nav:hover {
        background-color: #f8fafc;
        border-color: #cbd5e1;
        color: #3730a3;
    }

    .badge-type {
    font-weight: 700;
    font-size: 11px;
    padding: 4px 8px;
    border-radius: 6px;
    text-transform: uppercase;
    white-space: nowrap;
    }

    .badge-type.tiket_baru {
        background: rgba(16, 185, 129, 0.1);
        color: #059669;
    }

    .badge-type.update_status {
        background: rgba(79, 70, 229, 0.1);
        color: #4f46e5;
    }

    .badge-type.sla_warning {
        background: rgba(245, 158, 11, 0.1);
        color: #d97706;
    }

    .badge-type.overdue {
        background: rgba(239, 68, 68, 0.1);
        color: #dc2626;
    }

    .badge-type.rating {
        background: rgba(234, 179, 8, 0.1);
        color: #ca8a04;
    }

    .badge-type.umum {
        background: #f1f5f9;
        color: #64748b;
    }
</style>

<div class="table-container">
    <div class="table-header-flex">
        <div>
            <h3>Daftar Notifikasi</h3>
            <p>Monitoring log seluruh notifikasi sistem yang didistribusikan kepada customer dan teknisi.</p>
        </div>

        <a href="/dashboard/tickets" class="btn-nav">
            <i class="uil uil-ticket"></i> Lihat Daftar Tiket
        </a>
    </div>

    <div class="responsive-table-wrapper">
        <table>
            <thead>
                <tr>
                    <th style="width: 70px;">ID</th>
                    <th>Penerima</th>
                    <th>Role Penerima</th>
                    <th style="text-align: center;">ID Tiket</th>
                    <th>Prioritas</th>
                    <th>Status Tiket</th>
                    <th style="min-width: 260px;">Pesan Notifikasi</th>
                    <th>Waktu Dikirim</th>
                    <th>Status Baca</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($notifications)) : ?>
                    <?php foreach ($notifications as $n) : ?>
                        <tr>
                            <!-- ID Notifikasi -->
                            <td style="font-weight: 600; color: #94a3b8;">#<?= esc($n['id_notifikasi']) ?></td>

                            <!-- Profil Penerima -->
                            <td>
                                <div style="font-weight: 600; color: #0f172a;"><?= esc($n['nama_user']) ?></div>
                                <div style="font-size: 11.5px; color: #64748b; margin-top: 1px;"><?= esc($n['email_user']) ?></div>
                            </td>

                            <!-- Role Penerima -->
                            <td>
                                <?php $role = strtolower($n['role_user']); ?>
                                <span class="badge-role <?= $role ?>">
                                    <?= esc($n['role_user']) ?>
                                </span>
                            </td>

                            <!-- ID Tiket Relasi -->
                            <td style="text-align: center;">
                                <?php if (!empty($n['id_tiket'])) : ?>
                                    <a href="/dashboard/tickets/detail/<?= esc($n['id_tiket']) ?>" class="ticket-link">
                                        #<?= esc($n['id_tiket']) ?>
                                    </a>
                                <?php else : ?>
                                    <span style="color: #cbd5e1;">-</span>
                                <?php endif; ?>
                            </td>

                            <!-- Prioritas Tiket -->
                            <td>
                                <?php if (!empty($n['prioritas_tiket'])) : ?>
                                    <?php 
                                        $prioClass = 'none';
                                        $prioText = strtoupper($n['prioritas_tiket']);
                                        if (strpos(strtolower($prioText), 'high') !== false || strpos(strtolower($prioText), 'tinggi') !== false) $prioClass = 'high';
                                        elseif (strpos(strtolower($prioText), 'med') !== false || strpos(strtolower($prioText), 'sedang') !== false) $prioClass = 'medium';
                                        elseif (strpos(strtolower($prioText), 'low') !== false || strpos(strtolower($prioText), 'rendah') !== false) $prioClass = 'low';
                                    ?>
                                    <span class="badge-priority <?= $prioClass ?>"><?= esc($prioText) ?></span>
                                <?php else : ?>
                                    <span style="color: #cbd5e1;">-</span>
                                <?php endif; ?>
                            </td>

                            <!-- Status Operasional Tiket -->
                            <td>
                                <?php if (!empty($n['status_tiket'])) : ?>
                                    <span style="font-weight: 600; font-size: 12.5px; color: #475569; text-transform: capitalize;">
                                        <?= esc($n['status_tiket']) ?>
                                    </span>
                                <?php else : ?>
                                    <span style="color: #cbd5e1;">-</span>
                                <?php endif; ?>
                            </td>

                            <!-- Pesan Keluhan/Notifikasi -->
                            <td style="color: #334155; line-height: 1.5; font-size: 13px;">
                                <?= esc($n['pesan']) ?>
                            </td>

                            <!-- Stempel Waktu -->
                            <td style="color: #64748b; font-size: 12px; white-space: nowrap;">
                                <?= esc($n['waktu']) ?>
                            </td>

                            <!-- Status Baca Realtime -->
                            <td>
                                <?php if ($n['status_baca'] == 1) : ?>
                                    <span class="badge-read-status read">Dibaca</span>
                                <?php else : ?>
                                    <span class="badge-read-status unread">Belum Dibaca</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="9" style="text-align: center; color: #94a3b8; padding: 48px 0;">
                            <i class="uil uil-bell-slash" style="font-size: 32px; display: block; margin-bottom: 10px;"></i>
                            Belum ada log notifikasi sistem yang tercatat.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
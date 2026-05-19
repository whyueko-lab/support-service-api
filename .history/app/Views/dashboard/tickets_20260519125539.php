<?= $this->extend('layouts/dashboard_layout') ?>

<?= $this->section('content') ?>

<!-- Custom Style Khusus untuk Manajemen dan Filter Tiket Layanan -->
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

    /* Styling Filter Panel */
    .filter-card {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 24px;
    }

    .filter-grid {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr auto auto;
        gap: 12px;
        align-items: flex-end;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .filter-group label {
        font-size: 12px;
        font-weight: 600;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .filter-control {
        background-color: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        padding: 9px 12px;
        font-size: 13.5px;
        color: #334155;
        outline: none;
        transition: all 0.2s ease;
        width: 100%;
        box-sizing: border-box;
    }

    .filter-control:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }

    /* Kustomisasi Tabel */
    .responsive-table-wrapper {
        overflow-x: auto;
        border: 1px solid #f1f5f9;
        border-radius: 12px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
        font-size: 13.5px;
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

    /* Pembatasan Teks Deskripsi Keluhan */
    .text-truncated {
        max-width: 200px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Badge Klasifikasi Prioritas */
    .badge-priority {
        font-weight: 700;
        font-size: 11px;
        padding: 4px 8px;
        border-radius: 6px;
        display: inline-block;
        text-align: center;
    }
    .badge-priority.high { background-color: #fee2e2; color: #ef4444; }
    .badge-priority.medium { background-color: #fef3c7; color: #d97706; }
    .badge-priority.low { background-color: #dcfce7; color: #16a34a; }

    /* Badge Klasifikasi Status Operasional */
    .badge-status {
        font-weight: 700;
        font-size: 11px;
        padding: 4px 8px;
        border-radius: 6px;
        display: inline-block;
        text-align: center;
    }
    .badge-status.open { background-color: #e0f2fe; color: #0284c7; }
    .badge-status.in_progress { background-color: #ffedd5; color: #ea580c; }
    .badge-status.done { background-color: #dcfce7; color: #15803d; }
    .badge-status.overdue { background-color: #fee2e2; color: #b91c1c; }

    /* Tombol-Tombol Aksi Utama */
    .btn-create-ticket {
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

    .btn-create-ticket:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(79, 70, 229, 0.25);
    }

    .btn-filter-submit {
        background-color: #1e293b;
        color: white;
        border: none;
        padding: 10px 18px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 13.5px;
        cursor: pointer;
        transition: background-color 0.15s ease;
        height: 38px;
        display: inline-flex;
        align-items: center;
    }

    .btn-filter-submit:hover { background-color: #0f172a; }

    .btn-filter-reset {
        background-color: #ffffff;
        color: #64748b;
        border: 1px solid #cbd5e1;
        padding: 9px 18px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 13.5px;
        text-decoration: none;
        transition: all 0.15s ease;
        height: 38px;
        box-sizing: border-box;
        display: inline-flex;
        align-items: center;
    }

    .btn-filter-reset:hover { background-color: #f1f5f9; color: #334155; }

    /* Form Update Status dalam Baris Tabel (Inline Form) */
    .inline-update-form {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .select-table-inline {
        padding: 5px 8px;
        font-size: 12.5px;
        border-radius: 6px;
        border: 1px solid #cbd5e1;
        color: #334155;
        background-color: #ffffff;
    }

    .btn-table-save {
        background-color: #4f46e5;
        color: white;
        border: none;
        padding: 6px 10px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.15s ease;
    }
    .btn-table-save:hover { background-color: #3730a3; }

    .btn-table-detail {
        background-color: #f1f5f9;
        color: #1e293b;
        border: 1px solid #cbd5e1;
        padding: 6px 12px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 12.5px;
        text-decoration: none;
        display: inline-block;
        transition: all 0.15s ease;
    }
    .btn-table-detail:hover { background-color: #e2e8f0; color: #0f172a; }
</style>

<div class="table-container">
    <!-- Header Halaman -->
    <div class="table-header-flex">
        <div>
            <h3>Daftar Tiket Layanan</h3>
            <p>Monitoring, alokasi teknisi, dan resolusi penanganan seluruh berkas keluhan pelanggan.</p>
        </div>

        <a href="/dashboard/tickets/create" class="btn-create-ticket">
            <i class="uil uil-plus-circle"></i> Buat Tiket Baru
        </a>
    </div>

    <!-- Panel Form Pencarian & Penyaringan Data -->
    <form action="/dashboard/tickets" method="get" class="filter-card">
        <div class="filter-grid">
            <div class="filter-group">
                <label>Pencarian Kata Kunci</label>
                <input
                    type="text"
                    name="keyword"
                    class="filter-control"
                    placeholder="Cari deskripsi, kategori, nama customer/teknisi..."
                    value="<?= esc($keyword ?? '') ?>"
                >
            </div>

            <div class="filter-group">
                <label>Status Berkas</label>
                <select name="status" class="filter-control">
                    <option value="">Semua Status</option>
                    <option value="open" <?= ($status ?? '') === 'open' ? 'selected' : '' ?>>Open</option>
                    <option value="in_progress" <?= ($status ?? '') === 'in_progress' ? 'selected' : '' ?>>In Progress</option>
                    <option value="done" <?= ($status ?? '') === 'done' ? 'selected' : '' ?>>Done</option>
                    <option value="overdue" <?= ($status ?? '') === 'overdue' ? 'selected' : '' ?>>Overdue</option>
                </select>
            </div>

            <div class="filter-group">
                <label>Skala Prioritas</label>
                <select name="prioritas" class="filter-control">
                    <option value="">Semua Prioritas</option>
                    <option value="high" <?= ($prioritas ?? '') === 'high' ? 'selected' : '' ?>>High</option>
                    <option value="medium" <?= ($prioritas ?? '') === 'medium' ? 'selected' : '' ?>>Medium</option>
                    <option value="low" <?= ($prioritas ?? '') === 'low' ? 'selected' : '' ?>>Low</option>
                </select>
            </div>

            <div>
                <button type="submit" class="btn-filter-submit">
                    <i class="uil uil-filter" style="margin-right: 4px;"></i> Saring
                </button>
            </div>

            <div>
                <a href="/dashboard/tickets" class="btn-filter-reset">Reset</a>
            </div>
        </div>
    </form>

    <!-- Konten Data Tabel Utama -->
    <div class="responsive-table-wrapper">
        <table>
            <thead>
                <tr>
                    <th style="width: 70px;">ID Tiket</th>
                    <th>Customer</th>
                    <th>Teknisi Terpilih</th>
                    <th>Deskripsi Masalah</th>
                    <th>Kategori</th>
                    <th style="text-align: center;">Prioritas</th>
                    <th style="text-align: center;">Status</th>
                    <th>Batas Waktu</th>
                    <th style="min-width: 170px;">Pembaruan Status Cepat</th>
                    <th style="text-align: center; width: 80px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($tickets)) : ?>
                    <?php foreach ($tickets as $t) : ?>
                        <tr>
                            <!-- ID Tiket -->
                            <td style="font-weight: 600; color: #64748b;">#<?= esc($t['id_tiket']) ?></td>
                            
                            <!-- Nama Customer -->
                            <td style="font-weight: 600; color: #0f172a;"><?= esc($t['nama_customer']) ?></td>
                            
                            <!-- Nama Teknisi Penanggung Jawab -->
                            <td style="color: #475569;">
                                <?php if(!empty($t['nama_teknisi'])): ?>
                                    <span style="display: inline-flex; align-items: center; gap: 4px;">
                                        <i class="uil uil-wrench" style="color: #0284c7; font-size: 12px;"></i> <?= esc($t['nama_teknisi']) ?>
                                    </span>
                                <?php else: ?>
                                    <span style="color: #cbd5e1; font-style: italic;">Belum ditunjuk</span>
                                <?php endif; ?>
                            </td>
                            
                            <!-- Potongan Deskripsi Masalah -->
                            <td>
                                <div class="text-truncated" title="<?= esc($t['deskripsi']) ?>">
                                    <?= esc($t['deskripsi']) ?>
                                </div>
                            </td>
                            
                            <!-- Kategori Tiket -->
                            <td>
                                <span style="background-color: #f1f5f9; padding: 4px 8px; border-radius: 6px; font-weight: 500; font-size: 12.5px; color: #475569;">
                                    <?= esc($t['kategori']) ?>
                                </span>
                            </td>
                            
                            <!-- Label Prioritas Semantik -->
                            <td style="text-align: center;">
                                <?php $prio = strtolower($t['prioritas']); ?>
                                <span class="badge-priority <?= $prio ?>">
                                    <?= strtoupper(esc($t['prioritas'])) ?>
                                </span>
                            </td>
                            
                            <!-- Label Status Berkas Semantik -->
                            <td style="text-align: center;">
                                <?php $statusKey = strtolower($t['status']); ?>
                                <span class="badge-status <?= $statusKey ?>">
                                    <?= strtoupper(str_replace('_', ' ', esc($t['status']))) ?>
                                </span>
                            </td>
                            
                            <!-- Batas Waktu Batasan Kerja (Deadline) -->
                            <td style="color: #64748b; font-size: 12.5px; white-space: nowrap;">
                                <?= esc($t['deadline'] ?? '-') ?>
                            </td>
                            
                            <!-- Inline Form Pembaruan Cepat Status Operasional -->
                            <td>
                                <?php if ($t['status'] !== 'done') : ?>
                                    <form action="/dashboard/tickets/update-status/<?= esc($t['id_tiket']) ?>" method="post" class="inline-update-form">
                                        <select name="status" class="select-table-inline">
                                            <option value="open" <?= $t['status'] == 'open' ? 'selected' : '' ?>>Open</option>
                                            <option value="in_progress" <?= $t['status'] == 'in_progress' ? 'selected' : '' ?>>In Progress</option>
                                            <option value="done" <?= $t['status'] == 'done' ? 'selected' : '' ?>>Done</option>
                                            <option value="overdue" <?= $t['status'] == 'overdue' ? 'selected' : '' ?>>Overdue</option>
                                        </select>
                                        <button type="submit" class="btn-table-save" title="Simpan Status">
                                            <i class="uil uil-check"></i>
                                        </button>
                                    </form>
                                <?php else : ?>
                                    <span class="badge-status done">
                                        SELESAI / TERKUNCI
                                    </span>
                                <?php endif; ?>
                            </td>
                            
                            <!-- Tautan Masuk Detail Informasi Lengkap -->
                            <td style="text-align: center;">
                                <a href="/dashboard/tickets/detail/<?= esc($t['id_tiket']) ?>" class="btn-table-detail">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="10" style="text-align: center; color: #94a3b8; padding: 56px 0;">
                            <i class="uil uil-search-minus" style="font-size: 36px; display: block; margin-bottom: 12px;"></i>
                            Tidak ditemukan data tiket yang memenuhi kriteria filter pencarian.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
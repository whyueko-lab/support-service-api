<?= $this->extend('layouts/dashboard_layout') ?>

<?= $this->section('content') ?>

<!-- Custom Style Khusus Halaman Feedback & Ulasan Kepuasan Pelanggan -->
<style>
    .table-container {
        background: #ffffff;
        border-radius: 16px;
        padding: 28px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.01), 0 2px 4px -1px rgba(0, 0, 0, 0.01);
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

    /* Tombol Navigasi Alternatif */
    .btn-action-link {
        background-color: #ffffff;
        color: #475569;
        border: 1px solid #cbd5e1;
        padding: 10px 18px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
    }

    .btn-action-link:hover {
        background-color: #f8fafc;
        color: #0f172a;
        border-color: #94a3b8;
    }

    /* Pembungkus Tabel Responsif */
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

    /* Link Badge untuk ID Berkas */
    .ticket-id-link {
        font-weight: 600;
        color: #4f46e5;
        text-decoration: none;
        background-color: #eeebff;
        padding: 4px 8px;
        border-radius: 6px;
        transition: all 0.15s ease;
    }
    .ticket-id-link:hover {
        background-color: #4f46e5;
        color: #ffffff;
    }

    /* Penanganan Teks Panjang */
    .text-truncated {
        max-width: 220px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Komponen Pemeringkatan Bintang (Stars Rating) */
    .rating-visual-box {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .stars-row {
        color: #cbd5e1;
        font-size: 15px;
        letter-spacing: 1px;
    }

    .stars-row .star-active {
        color: #f59e0b;
    }

    /* Badge Klasifikasi Prioritas & Status Berkas */
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
</style>

<div class="table-container">
    <!-- Header Halaman -->
    <div class="table-header-flex">
        <div>
            <h3>Rating dan Feedback Pelanggan</h3>
            <p>Evaluasi berkala kepuasan pengguna untuk optimalisasi layanan <em>support service</em>.</p>
        </div>

        <a href="/dashboard/tickets" class="btn-action-link">
            <i class="uil uil-list-ul"></i> Buka Daftar Tiket
        </a>
    </div>

    <!-- Wrapper Tabel Utama -->
    <div class="responsive-table-wrapper">
        <table>
            <thead>
                <tr>
                    <th style="width: 80px;">ID Log</th>
                    <th style="width: 90px;">ID Tiket</th>
                    <th>Identitas Pelanggan</th>
                    <th>Deskripsi Masalah</th>
                    <th style="text-align: center;">Prioritas</th>
                    <th style="text-align: center;">Status</th>
                    <th style="width: 120px;">Penilaian</th>
                    <th>Ulasan / Komentar</th>
                    <th style="width: 110px;">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($ratings)) : ?>
                    <?php foreach ($ratings as $r) : ?>
                        <tr>
                            <!-- ID Log Rating -->
                            <td style="color: #64748b; font-weight: 500;">#<?= esc($r['id_rating']) ?></td>

                            <!-- ID Tiket Terkait -->
                            <td>
                                <a href="/dashboard/tickets/detail/<?= esc($r['id_tiket']) ?>" class="ticket-id-link" title="Lihat detail tiket">
                                    #<?= esc($r['id_tiket']) ?>
                                </a>
                            </td>

                            <!-- Profil Pelanggan -->
                            <td>
                                <div style="font-weight: 600; color: #0f172a;"><?= esc($r['nama_customer']) ?></div>
                                <div style="font-size: 12px; color: #64748b;"><?= esc($r['email_customer']) ?></div>
                            </td>

                            <!-- Potongan Teks Deskripsi Masalah -->
                            <td>
                                <div class="text-truncated" title="<?= esc($r['deskripsi']) ?>">
                                    <?= esc($r['deskripsi']) ?>
                                </div>
                            </td>

                            <!-- Urgensi Tiket -->
                            <td style="text-align: center;">
                                <?php $prio = strtolower($r['prioritas']); ?>
                                <span class="badge-priority <?= $prio ?>">
                                    <?= strtoupper(esc($r['prioritas'])) ?>
                                </span>
                            </td>

                            <!-- Status Akhir Berkas -->
                            <td style="text-align: center;">
                                <?php $statusKey = strtolower($r['status']); ?>
                                <span class="badge-status <?= $statusKey ?>">
                                    <?= strtoupper(str_replace('_', ' ', esc($r['status']))) ?>
                                </span>
                            </td>

                            <!-- Skor Indeks Kepuasan & Representasi Bintang -->
                            <td>
                                <div class="rating-visual-box">
                                    <span style="font-weight: 700; color: #0f172a; font-size: 14px;"><?= esc($r['nilai_rating']) ?> <span style="font-size:11px; color:#94a3b8; font-weight:500;">/ 5</span></span>
                                    <div class="stars-row">
                                        <?php for ($i = 1; $i <= 5; $i++) : ?>
                                            <span class="<?= $i <= $r['nilai_rating'] ? 'star-active' : '' ?>">★</span>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                            </td>

                            <!-- Ulasan Tambahan Kualitatif -->
                            <td>
                                <div class="text-truncated" style="max-width: 300px; color: #475569; font-style: <?= empty($r['komentar']) ? 'italic' : 'normal' ?>;" title="<?= esc($r['komentar'] ?? '-') ?>">
                                    <?= !empty($r['komentar']) ? esc($r['komentar']) : 'Tidak ada ulasan tertulis' ?>
                                </div>
                            </td>

                            <!-- Waktu Submit Penilaian -->
                            <td style="color: #64748b; font-size: 12.5px; white-space: nowrap;">
                                <?= esc($r['tanggal']) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="9" style="text-align: center; color: #94a3b8; padding: 56px 0;">
                            <i class="uil uil-star-half-alt" style="display: block; font-size: 36px; margin-bottom: 12px; color: #cbd5e1;"></i>
                            Belum ada rekaman data rating atau ulasan balik yang dikirimkan oleh pelanggan.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
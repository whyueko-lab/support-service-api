<?= $this->extend('layouts/dashboard_layout') ?>

<?= $this->section('content') ?>

<!-- Tambahan Style Khusus untuk visualisasi detail di halaman Utama -->
<style>
    /* Mengelompokkan statistik agar logis & enak dibaca */
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px;
        margin-bottom: 32px;
    }

    .card-meta {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .card-icon-box {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    /* Varian Warna Kartu Berdasarkan Status */
    .c-total { background: rgba(79, 70, 229, 0.1); color: #4f46e5; }
    .c-open { background: rgba(14, 165, 233, 0.1); color: #0ea5e9; }
    .c-progress { background: rgba(139, 92, 246, 0.1); color: #8b5cf6; }
    .c-done { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .c-overdue { background: rgba(239, 68, 68, 0.1); color: #ef4444; }
    .c-user { background: rgba(100, 116, 139, 0.1); color: #64748b; }
    .c-warning { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }

    /* Layout Khusus Baris Tengah (Rating & SLA) */
    .dashboard-row-split {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 24px;
        margin-bottom: 32px;
    }

    @media (max-width: 1024px) {
        .dashboard-row-split {
            grid-template-columns: 1fr;
        }
    }

    /* Progress bar horizontal untuk rating kepuasan */
    .progress-bar-container {
        background: #e2e8f0;
        border-radius: 99px;
        height: 8px;
        width: 100%;
        margin-top: 14px;
        overflow: hidden;
    }

    .progress-bar-fill {
        background: linear-gradient(90deg, #10b981, #059669);
        height: 100%;
        border-radius: 99px;
        transition: width 1s ease-in-out;
    }

    /* Rating stars layout */
    .rating-stars {
        color: #f59e0b;
        font-size: 14px;
        margin-top: 4px;
    }
</style>

<!-- KELOMPOK 1: UTAMA & STATUS TIKET -->
<h3 style="font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px; color: #0c0c0cff; margin-bottom: 14px; font-weight: 800;">Ringkasan Operasional Tiket</h3>
<div class="dashboard-grid">
    <div class="card">
        <div class="card-meta">
            <h4>Total Tiket</h4>
            <div class="card-icon-box c-total"><i class="uil uil-layer-group"></i></div>
        </div>
        <div class="number"><?= $totalTiket ?></div>
    </div>

    <div class="card">
        <div class="card-meta">
            <h4>Tiket Open</h4>
            <div class="card-icon-box c-open"><i class="uil uil-envelope-open"></i></div>
        </div>
        <div class="number"><?= $open ?></div>
    </div>

    <div class="card">
        <div class="card-meta">
            <h4>In Progress</h4>
            <div class="card-icon-box c-progress"><i class="uil uil-sync"></i></div>
        </div>
        <div class="number"><?= $inProgress ?></div>
    </div>

    <div class="card">
        <div class="card-meta">
            <h4>Selesai (Done)</h4>
            <div class="card-icon-box c-done"><i class="uil uil-check-circle"></i></div>
        </div>
        <div class="number"><?= $done ?></div>
    </div>

    <div class="card">
        <div class="card-meta">
            <h4>Overdue (Terlambat)</h4>
            <div class="card-icon-box c-overdue"><i class="uil uil-exclamation-octagon"></i></div>
        </div>
        <div class="number" style="color: #dc2626;"><?= $overdue ?></div>
    </div>
</div>

<!-- KELOMPOK 2: AKUN & SISTEM -->
<div class="dashboard-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));">
    <div class="card">
        <div class="card-meta">
            <h4>Total Customer</h4>
            <div class="card-icon-box c-user"><i class="uil uil-user-check"></i></div>
        </div>
        <div class="number" style="font-size: 26px;"><?= $totalCustomer ?></div>
    </div>

    <div class="card">
        <div class="card-meta">
            <h4>Total Teknisi</h4>
            <div class="card-icon-box c-user"><i class="uil uil-constructor"></i></div>
        </div>
        <div class="number" style="font-size: 26px;"><?= $totalTeknisi ?></div>
    </div>

    <div class="card">
        <div class="card-meta">
            <h4>Notifikasi Masuk</h4>
            <div class="card-icon-box c-total"><i class="uil uil-bell"></i></div>
        </div>
        <div class="number" style="font-size: 26px;"><?= $totalNotifikasi ?? 0 ?></div>
    </div>

    <div class="card">
        <div class="card-meta">
            <h4>Belum Dibaca</h4>
            <div class="card-icon-box c-warning"><i class="uil uil-comment-alt-dots"></i></div>
        </div>
        <div class="number" style="font-size: 26px; color: #d97706;"><?= $notifikasiBelumDibaca ?? 0 ?></div>
    </div>
</div>

<!-- KELOMPOK 3: DETAIL BEBAN KERJA & RATING SPREAD -->
<div class="dashboard-row-split">
    
    <!-- Bagian Rating Ringkasan -->
    <div class="section" style="margin-bottom: 0;">
        <h3>Kepuasan Layanan</h3>
        
        <div style="text-align: center; margin: 24px 0;">
            <div style="font-size: 48px; font-weight: 800; color: #0f172a; line-height: 1;"><?= $rataRataRating ?></div>
            <div class="rating-stars">
                <i class="uil uil-star"></i><i class="uil uil-star"></i><i class="uil uil-star"></i><i class="uil uil-star"></i><i class="uil uil-star"></i>
                <span style="color: #64748b; font-size: 12px; display: block; margin-top: 4px;">Skala Skor Macam 5.0</span>
            </div>
        </div>

        <div style="display: flex; flex-direction: column; gap: 14px; border-top: 1px solid #f1f5f9; padding-top: 16px;">
            <div style="display: flex; justify-content: space-between; font-size: 14px;">
                <span style="color: #64748b;">Persentase CSAT</span>
                <strong style="color: #10b981;"><?= $kepuasanPersen ?>%</strong>
            </div>
            <div class="progress-bar-container">
                <div class="progress-bar-fill" style="width: <?= $kepuasanPersen ?>%;"></div>
            </div>
            
            <div style="display: flex; justify-content: space-between; font-size: 13px; margin-top: 6px; color: #475569;">
                <span>Total Penilai:</span>
                <strong><?= $totalRating ?> User</strong>
            </div>
        </div>
    </div>

    <!-- Tabel Beban Kerja Teknisi -->
    <div class="section" style="margin-bottom: 0;">
        <h3>Beban Kerja Teknisi</h3>
        <p style="font-size: 13px; color: #64748b; margin-top: -14px; margin-bottom: 20px;">Distribusi penanganan keluhan sistem aktif saat ini.</p>

        <div style="overflow-x: auto;">
            <table style="min-width: 100%;">
                <thead>
                    <tr>
                        <th>Nama Teknisi</th>
                        <th style="width: 160px; text-align: center;">Tiket Aktif</th>
                        <th style="width: 160px; text-align: center;">Tiket Selesai</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($bebanTeknisi)) : ?>
                        <?php foreach ($bebanTeknisi as $t) : ?>
                            <tr>
                                <td style="font-weight: 600; color: #1e293b;"><?= esc($t['nama_teknisi']) ?></td>
                                <td style="text-align: center;">
                                    <span class="badge in_progress" style="font-weight:600; padding: 6px 12px; border-radius: 8px;">
                                        <?= $t['tiket_aktif'] ?> Aktif
                                    </span>
                                </td>
                                <td style="text-align: center;">
                                    <span class="badge done" style="font-weight:600; padding: 6px 12px; border-radius: 8px;">
                                        <?= $t['tiket_selesai'] ?> Done
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="3" style="text-align: center; color: #94a3b8; padding: 32px 0;">Belum ada data teknisi yang bertugas</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
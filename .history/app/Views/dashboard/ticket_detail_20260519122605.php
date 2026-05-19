<?= $this->extend('layouts/dashboard_layout') ?>

<?= $this->section('content') ?>

<!-- Custom Style Khusus untuk Dasbor Detail Workspace Tiket -->
<style>
    .btn-back-wrapper {
        margin-bottom: 24px;
    }

    .btn-back {
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

    .btn-back:hover {
        background-color: #f8fafc;
        color: #0f172a;
        border-color: #94a3b8;
    }

    /* Grid Layout Utama */
    .detail-grid {
        display: grid;
        grid-template-columns: 7fr 4fr;
        gap: 24px;
    }

    .detail-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 24px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.01), 0 2px 4px -1px rgba(0, 0, 0, 0.01);
    }

    .detail-card h3 {
        font-size: 16px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Penataan Baris Data */
    .detail-row {
        display: grid;
        grid-template-columns: 180px 1fr;
        padding: 10px 0;
        border-bottom: 1px dashed #f1f5f9;
        align-items: center;
    }

    .detail-row:last-child {
        border-bottom: none;
    }

    .detail-label {
        color: #64748b;
        font-weight: 600;
        font-size: 13.5px;
    }

    .detail-value {
        color: #1e293b;
        font-size: 14px;
        line-height: 1.5;
    }

    /* Lencana Status & Prioritas */
    .badge-priority {
        font-weight: 700;
        font-size: 11px;
        padding: 4px 10px;
        border-radius: 6px;
        display: inline-block;
    }
    .badge-priority.high { background-color: #fee2e2; color: #ef4444; }
    .badge-priority.medium { background-color: #fef3c7; color: #d97706; }
    .badge-priority.low { background-color: #dcfce7; color: #16a34a; }

    .badge-status {
        font-weight: 700;
        font-size: 11px;
        padding: 4px 10px;
        border-radius: 6px;
        display: inline-block;
    }
    .badge-status.open { background-color: #e0f2fe; color: #0284c7; }
    .badge-status.in_progress { background-color: #ffedd5; color: #ea580c; }
    .badge-status.done { background-color: #dcfce7; color: #15803d; }
    .badge-status.overdue { background-color: #fee2e2; color: #b91c1c; }

    /* Struktur Tampilan Timeline */
    .timeline-container {
        position: relative;
        padding-left: 20px;
        margin-top: 10px;
    }

    .timeline-item {
        position: relative;
        padding-bottom: 20px;
        border-left: 2px solid #e2e8f0;
        padding-left: 24px;
    }

    .timeline-item:last-child {
        border-left: 2px solid transparent;
        padding-bottom: 0;
    }

    .timeline-dot {
        position: absolute;
        left: -6px;
        top: 4px;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background-color: #cbd5e1;
        border: 2px solid #ffffff;
    }

    .timeline-item.unread-log .timeline-dot {
        background-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
    }

    .timeline-content {
        font-size: 13.5px;
        color: #334155;
        line-height: 1.5;
    }

    .timeline-meta {
        font-size: 11.5px;
        color: #94a3b8;
        margin-top: 6px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Blok Sistem Rating */
    .rating-box {
        background-color: #f8fafc;
        border-radius: 12px;
        padding: 16px;
        border: 1px dashed #e2e8f0;
        text-align: center;
    }

    .rating-number {
        font-size: 32px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1;
        margin-bottom: 4px;
    }

    .stars {
        color: #cbd5e1;
        font-size: 20px;
        margin-bottom: 12px;
        letter-spacing: 2px;
    }

    .stars .star-filled {
        color: #f59e0b;
    }

    .rating-comment {
        font-size: 13px;
        color: #475569;
        font-style: italic;
        line-height: 1.5;
        background: white;
        padding: 10px;
        border-radius: 8px;
        border: 1px solid #f1f5f9;
    }

    @media (max-width: 1024px) {
        .detail-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 640px) {
        .detail-row {
            grid-template-columns: 1fr;
            gap: 4px;
        }
    }
</style>

<!-- Tombol Navigasi Kembali -->
<div class="btn-back-wrapper">
    <a href="/dashboard/tickets" class="btn-back">
        <i class="uil uil-arrow-left"></i> Kembali ke Daftar Tiket
    </a>
</div>

<div class="detail-grid">

    <!-- KOLOM KIRI: Data Teknis Utama Tiket & Log Aktifitas -->
    <div>
        <!-- Blok Informasi Tiket -->
        <div class="detail-card">
            <h3><i class="uil uil-info-circle" style="color: #4f46e5;"></i> Detail Informasi Tiket #<?= esc($ticket['id_tiket']) ?></h3>

            <div class="detail-row">
                <div class="detail-label">Deskripsi Masalah</div>
                <div class="detail-value" style="font-weight: 500; color: #0f172a;"><?= esc($ticket['deskripsi']) ?></div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Kategori Layanan</div>
                <div class="detail-value">
                    <span style="background-color: #f1f5f9; padding: 4px 8px; border-radius: 6px; font-weight: 600; font-size: 12.5px; color: #475569;">
                        <?= esc($ticket['kategori']) ?>
                    </span>
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Tingkat Urgensi</div>
                <div class="detail-value">
                    <?php $prio = strtolower($ticket['prioritas']); ?>
                    <span class="badge-priority <?= $prio ?>">
                        <?= strtoupper(esc($ticket['prioritas'])) ?>
                    </span>
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Status Alur Kerja</div>
                <div class="detail-value">
                    <?php $statusKey = strtolower($ticket['status']); ?>
                    <span class="badge-status <?= $statusKey ?>">
                        <?= strtoupper(str_replace('_', ' ', esc($ticket['status']))) ?>
                    </span>
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Bobot Score Klasifikasi</div>
                <div class="detail-value">
                    <strong style="color: #0f172a; font-size: 15px;"><?= esc($ticket['score']) ?></strong>
                    <span style="color: #94a3b8; font-size: 12px; margin-left: 4px;">(Sistem Auto-Assessment)</span>
                </div>
            </div>
        </div>

        <!-- Blok Informasi SLA (Service Level Agreement) -->
        <div class="detail-card">
            <h3><i class="uil uil-clock" style="color: #0284c7;"></i> Pemantauan Service Level Agreement (SLA)</h3>

            <div class="detail-row">
                <div class="detail-label">Waktu Tiket Masuk</div>
                <div class="detail-value"><?= esc($ticket['tanggal_masuk']) ?></div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Batas Tenggat (Deadline)</div>
                <div class="detail-value" style="color: #b91c1c; font-weight: 600;"><?= esc($ticket['deadline'] ?? '-') ?></div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Waktu Penyelesaian</div>
                <div class="detail-value"><?= esc($ticket['tanggal_selesai'] ?? '-') ?></div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Kepatuhan Target SLA</div>
                <div class="detail-value">
                    <?php if ($ticket['status'] === 'overdue') : ?>
                        <span class="badge-status overdue"><i class="uil uil-exclamation-triangle"></i> TERLAMBAT (OVERDUE)</span>
                    <?php elseif ($ticket['status'] === 'done') : ?>
                        <span class="badge-status done"><i class="uil uil-check-circle"></i> BERHASIL SELESAI</span>
                    <?php else : ?>
                        <span class="badge-status open" style="background-color: #ecfdf5; color: #059669;"><i class="uil uil-stopwatch"></i> BERJALAN DALAM BATAS SLA</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Blok Histori Alur Notifikasi -->
        <div class="detail-card">
            <h3><i class="uil uil-history" style="color: #64748b;"></i> Log Garis Waktu Riwayat Notifikasi</h3>

            <div class="timeline-container">
                <?php if (!empty($notifications)) : ?>
                    <?php foreach ($notifications as $n) : ?>
                        <?php $isRead = ($n['status_baca'] == 1); ?>
                        <div class="timeline-item <?= !$isRead ? 'unread-log' : '' ?>">
                            <div class="timeline-dot"></div>
                            <div class="timeline-content">
                                <?= esc($n['pesan']) ?>
                            </div>
                            <div class="timeline-meta">
                                <span><i class="uil uil-calender"></i> <?= esc($n['waktu']) ?></span>
                                <span>•</span>
                                <span style="color: <?= $isRead ? '#94a3b8' : '#2563eb' ?>; font-weight: <?= $isRead ? '500' : '700' ?>;">
                                    <?= $isRead ? 'Sudah dibaca' : 'Belum dibaca' ?>
                                </span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p style="color: #94a3b8; text-align: center; padding: 20px 0; font-size: 13.5px;">
                        <i class="uil uil-bell-slash" style="display:block; font-size:24px; margin-bottom:6px;"></i>
                        Belum ada rekaman log notifikasi untuk berkas tiket ini.
                    </p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- KOLOM KANAN: Data Stakeholder Terkait & Rating Kepuasan -->
    <div>
        <!-- Profil Customer -->
        <div class="detail-card">
            <h3><i class="uil uil-user" style="color: #4b5563;"></i> Informasi Pelanggan</h3>

            <div class="detail-row">
                <div class="detail-label">Nama</div>
                <div class="detail-value" style="font-weight: 600; color: #0f172a;"><?= esc($ticket['nama_customer']) ?></div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Email</div>
                <div class="detail-value" style="font-size: 13px; color: #475569;"><?= esc($ticket['email_customer']) ?></div>
            </div>
        </div>

        <!-- Profil Teknisi -->
        <div class="detail-card">
            <h3><i class="uil uil-wrench" style="color: #4b5563;"></i> Teknisi Penanggung Jawab</h3>

            <?php if (!empty($ticket['nama_teknisi'])) : ?>
                <div class="detail-row">
                    <div class="detail-label">Nama</div>
                    <div class="detail-value" style="font-weight: 600; color: #0f172a;"><?= esc($ticket['nama_teknisi']) ?></div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Email</div>
                    <div class="detail-value" style="font-size: 13px; color: #475569;"><?= esc($ticket['email_teknisi']) ?></div>
                </div>
            <?php else : ?>
                <p style="color: #94a3b8; text-align: center; padding: 12px 0; font-style: italic; font-size: 13px;">
                    Berkas tiket belum dialokasikan ke teknisi manapun.
                </p>
            <?php endif; ?>
        </div>

        <!-- Rating Ulasan Kepuasan -->
        <div class="detail-card">
            <h3><i class="uil uil-star" style="color: #f59e0b;"></i> Penilaian Kepuasan Layanan</h3>

            <?php if ($rating) : ?>
                <div class="rating-box">
                    <div class="rating-number"><?= esc($rating['nilai_rating']) ?><span style="font-size: 16px; color:#64748b; font-weight:500;"> / 5</span></div>

                    <div class="stars">
                        <?php for ($i = 1; $i <= 5; $i++) : ?>
                            <span class="<?= $i <= $rating['nilai_rating'] ? 'star-filled' : '' ?>">★</span>
                        <?php endfor; ?>
                    </div>

                    <?php if (!empty($rating['komentar'])) : ?>
                        <div class="rating-comment">
                            "<?= esc($rating['komentar']) ?>"
                        </div>
                    <?php endif; ?>
                    
                    <div style="font-size: 11px; color: #94a3b8; margin-top: 10px; text-align: right;">
                        <i class="uil uil-calendar-alt"></i> <?= esc($rating['tanggal']) ?>
                    </div>
                </div>
            <?php else : ?>
                <p style="color: #94a3b8; text-align: center; padding: 20px 0; font-style: italic; font-size: 13px;">
                    <i class="uil uil-comment-alt-block" style="display:block; font-size:22px; margin-bottom:6px; color:#cbd5e1;"></i>
                    Pelanggan belum memberikan feedback penilaian.
                </p>
            <?php endif; ?>
        </div>
    </div>

</div>

<?= $this->endSection() ?>
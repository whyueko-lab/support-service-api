<?= $this->extend('layouts/dashboard_layout') ?>

<?= $this->section('content') ?>

<style>
    .detail-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 20px;
    }

    .detail-row {
        display: grid;
        grid-template-columns: 170px 1fr;
        margin-bottom: 12px;
        gap: 10px;
    }

    .detail-label {
        color: #6b7280;
        font-weight: bold;
    }

    .detail-value {
        color: #111827;
    }

    .timeline-item {
        border-left: 3px solid #d1d5db;
        padding-left: 12px;
        margin-bottom: 15px;
    }

    .timeline-item small {
        color: #6b7280;
    }

    .rating-number {
        font-size: 28px;
        font-weight: bold;
        color: #111827;
    }

    .stars {
        color: #111827;
        letter-spacing: 2px;
        font-size: 20px;
    }

    @media (max-width: 900px) {
        .detail-grid {
            grid-template-columns: 1fr;
        }

        .detail-row {
            grid-template-columns: 1fr;
        }
    }
</style>

<div style="margin-bottom:20px;">
    <a href="/dashboard/tickets" class="btn btn-secondary">← Kembali ke Daftar Tiket</a>
</div>

<div class="detail-grid">

    <div>
        <div class="section">
            <h3>Informasi Tiket #<?= esc($ticket['id_tiket']) ?></h3>

            <div class="detail-row">
                <div class="detail-label">Deskripsi</div>
                <div class="detail-value"><?= esc($ticket['deskripsi']) ?></div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Kategori</div>
                <div class="detail-value"><?= esc($ticket['kategori']) ?></div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Prioritas</div>
                <div class="detail-value">
                    <span class="badge <?= esc($ticket['prioritas']) ?>">
                        <?= strtoupper(esc($ticket['prioritas'])) ?>
                    </span>
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Status</div>
                <div class="detail-value">
                    <span class="badge <?= esc($ticket['status']) ?>">
                        <?= strtoupper(str_replace('_', ' ', esc($ticket['status']))) ?>
                    </span>
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Score Klasifikasi</div>
                <div class="detail-value"><?= esc($ticket['score']) ?></div>
            </div>
        </div>

        <div class="section">
            <h3>Informasi SLA</h3>

            <div class="detail-row">
                <div class="detail-label">Tanggal Masuk</div>
                <div class="detail-value"><?= esc($ticket['tanggal_masuk']) ?></div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Deadline</div>
                <div class="detail-value"><?= esc($ticket['deadline'] ?? '-') ?></div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Tanggal Selesai</div>
                <div class="detail-value"><?= esc($ticket['tanggal_selesai'] ?? '-') ?></div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Status SLA</div>
                <div class="detail-value">
                    <?php if ($ticket['status'] === 'overdue') : ?>
                        <span class="badge overdue">OVERDUE</span>
                    <?php elseif ($ticket['status'] === 'done') : ?>
                        <span class="badge done">SELESAI</span>
                    <?php else : ?>
                        <span class="badge open">DALAM BATAS SLA</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="section">
            <h3>Riwayat Notifikasi</h3>

            <?php if (!empty($notifications)) : ?>
                <?php foreach ($notifications as $n) : ?>
                    <div class="timeline-item">
                        <div><?= esc($n['pesan']) ?></div>
                        <small>
                            <?= esc($n['waktu']) ?> |
                            Status baca: <?= $n['status_baca'] == 1 ? 'Sudah dibaca' : 'Belum dibaca' ?>
                        </small>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <p>Belum ada notifikasi untuk tiket ini.</p>
            <?php endif; ?>
        </div>
    </div>

    <div>
        <div class="section">
            <h3>Customer</h3>

            <div class="detail-row">
                <div class="detail-label">Nama</div>
                <div class="detail-value"><?= esc($ticket['nama_customer']) ?></div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Email</div>
                <div class="detail-value"><?= esc($ticket['email_customer']) ?></div>
            </div>
        </div>

        <div class="section">
            <h3>Teknisi</h3>

            <div class="detail-row">
                <div class="detail-label">Nama</div>
                <div class="detail-value"><?= esc($ticket['nama_teknisi'] ?? '-') ?></div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Email</div>
                <div class="detail-value"><?= esc($ticket['email_teknisi'] ?? '-') ?></div>
            </div>
        </div>

        <div class="section">
            <h3>Rating Layanan</h3>

            <?php if ($rating) : ?>
                <div class="rating-number"><?= esc($rating['nilai_rating']) ?>/5</div>

                <div class="stars">
                    <?php for ($i = 1; $i <= 5; $i++) : ?>
                        <?= $i <= $rating['nilai_rating'] ? '★' : '☆' ?>
                    <?php endfor; ?>
                </div>

                <p><?= esc($rating['komentar'] ?? '-') ?></p>
                <small><?= esc($rating['tanggal']) ?></small>
            <?php else : ?>
                <p>Belum ada rating untuk tiket ini.</p>
            <?php endif; ?>
        </div>
    </div>

</div>

<?= $this->endSection() ?>
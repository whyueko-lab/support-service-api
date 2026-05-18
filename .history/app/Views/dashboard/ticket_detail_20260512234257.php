<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Tiket - Support Service</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 0;
        }

        .navbar {
            background: #1f2937;
            color: white;
            padding: 18px 30px;
        }

        .navbar h2 {
            margin: 0;
            font-size: 22px;
        }

        .container {
            padding: 30px;
            max-width: 1100px;
            margin: auto;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 22px;
        }

        .topbar h3 {
            margin: 0;
            color: #111827;
        }

        .topbar p {
            margin: 5px 0 0;
            color: #6b7280;
        }

        .grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
        }

        .card {
            background: white;
            border-radius: 12px;
            padding: 22px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
            margin-bottom: 20px;
        }

        .card h4 {
            margin-top: 0;
            color: #111827;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 10px;
        }

        .row {
            display: grid;
            grid-template-columns: 180px 1fr;
            margin-bottom: 12px;
            gap: 10px;
        }

        .label {
            color: #6b7280;
            font-weight: bold;
        }

        .value {
            color: #111827;
        }

        .badge {
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 13px;
            display: inline-block;
            background: #e5e7eb;
        }

        .high {
            background: #fee2e2;
            color: #991b1b;
        }

        .medium {
            background: #fef3c7;
            color: #92400e;
        }

        .low {
            background: #dcfce7;
            color: #166534;
        }

        .open {
            background: #e0f2fe;
            color: #075985;
        }

        .in_progress {
            background: #ede9fe;
            color: #5b21b6;
        }

        .done {
            background: #dcfce7;
            color: #166534;
        }

        .overdue {
            background: #fee2e2;
            color: #991b1b;
        }

        .btn {
            display: inline-block;
            padding: 10px 15px;
            border-radius: 8px;
            background: #1f2937;
            color: white;
            text-decoration: none;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }

        .timeline-item {
            border-left: 3px solid #d1d5db;
            padding-left: 12px;
            margin-bottom: 15px;
        }

        .timeline-item small {
            color: #6b7280;
        }

        .rating {
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
            .grid {
                grid-template-columns: 1fr;
            }

            .row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<div class="navbar">
    <h2>Support Service Dashboard</h2>
</div>

<div class="container">

    <div class="topbar">
        <div>
            <h3>Detail Tiket #<?= esc($ticket['id_tiket']) ?></h3>
            <p>Informasi lengkap tiket, status, SLA, rating, dan notifikasi</p>
        </div>

        <a href="/dashboard/tickets" class="btn">Kembali ke Daftar Tiket</a>
    </div>

    <div class="grid">

        <div>
            <div class="card">
                <h4>Informasi Tiket</h4>

                <div class="row">
                    <div class="label">Deskripsi</div>
                    <div class="value"><?= esc($ticket['deskripsi']) ?></div>
                </div>

                <div class="row">
                    <div class="label">Kategori</div>
                    <div class="value"><?= esc($ticket['kategori']) ?></div>
                </div>

                <div class="row">
                    <div class="label">Prioritas</div>
                    <div class="value">
                        <span class="badge <?= esc($ticket['prioritas']) ?>">
                            <?= strtoupper(esc($ticket['prioritas'])) ?>
                        </span>
                    </div>
                </div>

                <div class="row">
                    <div class="label">Status</div>
                    <div class="value">
                        <span class="badge <?= esc($ticket['status']) ?>">
                            <?= strtoupper(str_replace('_', ' ', esc($ticket['status']))) ?>
                        </span>
                    </div>
                </div>

                <div class="row">
                    <div class="label">Score Klasifikasi</div>
                    <div class="value"><?= esc($ticket['score']) ?></div>
                </div>
            </div>

            <div class="card">
                <h4>Informasi SLA</h4>

                <div class="row">
                    <div class="label">Tanggal Masuk</div>
                    <div class="value"><?= esc($ticket['tanggal_masuk']) ?></div>
                </div>

                <div class="row">
                    <div class="label">Deadline</div>
                    <div class="value"><?= esc($ticket['deadline'] ?? '-') ?></div>
                </div>

                <div class="row">
                    <div class="label">Tanggal Selesai</div>
                    <div class="value"><?= esc($ticket['tanggal_selesai'] ?? '-') ?></div>
                </div>

                <div class="row">
                    <div class="label">Status SLA</div>
                    <div class="value">
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

            <div class="card">
                <h4>Riwayat Notifikasi</h4>

                <?php if (!empty($notifications)) : ?>
                    <?php foreach ($notifications as $n) : ?>
                        <div class="timeline-item">
                            <div><?= esc($n['pesan']) ?></div>
                            <small><?= esc($n['waktu']) ?> | Status baca: <?= $n['status_baca'] == 1 ? 'Sudah dibaca' : 'Belum dibaca' ?></small>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p>Belum ada notifikasi untuk tiket ini.</p>
                <?php endif; ?>
            </div>
        </div>

        <div>
            <div class="card">
                <h4>Customer</h4>

                <div class="row">
                    <div class="label">Nama</div>
                    <div class="value"><?= esc($ticket['nama_customer']) ?></div>
                </div>

                <div class="row">
                    <div class="label">Email</div>
                    <div class="value"><?= esc($ticket['email_customer']) ?></div>
                </div>
            </div>

            <div class="card">
                <h4>Teknisi</h4>

                <div class="row">
                    <div class="label">Nama</div>
                    <div class="value"><?= esc($ticket['nama_teknisi'] ?? '-') ?></div>
                </div>

                <div class="row">
                    <div class="label">Email</div>
                    <div class="value"><?= esc($ticket['email_teknisi'] ?? '-') ?></div>
                </div>
            </div>

            <div class="card">
                <h4>Rating Layanan</h4>

                <?php if ($rating) : ?>
                    <div class="rating"><?= esc($rating['nilai_rating']) ?>/5</div>

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

</div>

</body>
</html>
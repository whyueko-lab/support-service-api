<?= $this->extend('layouts/dashboard_layout') ?>

<?= $this->section('content') ?>

<!-- Custom Style Khusus Komponen Dashboard Analitik Laporan SLA -->
<style>
    /* Kontainer Section Utama */
    .report-container {
        background: #ffffff;
        border-radius: 16px;
        padding: 28px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.01), 0 2px 4px -1px rgba(0, 0, 0, 0.01);
        margin-bottom: 24px;
    }

    .report-container h3 {
        font-size: 18px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Penataan Komponen Filter & Tombol Ekspor */
    .action-header-grid {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        gap: 20px;
        margin-bottom: 28px;
        background-color: #f8fafc;
        padding: 20px;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
    }

    .filter-form-grid {
        display: grid;
        grid-template-columns: 200px 200px auto auto;
        gap: 12px;
        align-items: flex-end;
        flex-grow: 1;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .form-group label {
        font-size: 12px;
        font-weight: 600;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .input-date-control {
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

    .input-date-control:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }

    /* Sistem Tombol Dashboard */
    .btn-report-submit {
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
    .btn-report-submit:hover { background-color: #0f172a; }

    .btn-report-reset {
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
    .btn-report-reset:hover { background-color: #f1f5f9; color: #334155; }

    .btn-download-pdf {
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        color: white;
        padding: 10px 20px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        text-decoration: none;
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.15);
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        white-space: nowrap;
        height: 38px;
        box-sizing: border-box;
    }
    .btn-download-pdf:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(220, 38, 38, 0.25);
    }

    /* Grid KPI Metrics Cards */
    .kpi-cards-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 28px;
    }

    .kpi-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 20px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
        overflow: hidden;
    }

    .kpi-card h4 {
        font-size: 13px;
        color: #64748b;
        font-weight: 600;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .kpi-card .kpi-number {
        font-size: 28px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.2;
    }

    /* Modifikasi Bilah Progres Ringkasan */
    .summary-progress-wrapper {
        margin-bottom: 20px;
    }

    .summary-meta-text {
        display: flex;
        justify-content: space-between;
        font-size: 14px;
        color: #334155;
        margin-bottom: 6px;
    }

    .progress-track {
        background: #f1f5f9;
        border-radius: 8px;
        height: 12px;
        overflow: hidden;
        width: 100%;
        border: 1px solid #e2e8f0;
    }

    .progress-fill-bar {
        height: 12px;
        border-radius: 8px;
        transition: width 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Variasi Warna Progress */
    .bg-gradient-success { background: linear-gradient(90deg, #10b981, #059669); }
    .bg-gradient-danger { background: linear-gradient(90deg, #f97316, #dc2626); }

    /* Komponen Tabel Performa */
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

    /* Klasifikasi Badge Evaluasi */
    .badge-evaluation {
        font-weight: 700;
        font-size: 11px;
        padding: 4px 10px;
        border-radius: 6px;
        display: inline-block;
        text-align: center;
    }
    .badge-evaluation.good { background-color: #dcfce7; color: #15803d; }
    .badge-evaluation.warning { background-color: #ffedd5; color: #ea580c; }
    .badge-evaluation.bad { background-color: #fee2e2; color: #b91c1c; }

    /* Aturan Responsif Komponen */
    @media (max-width: 1200px) {
        .kpi-cards-grid { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 992px) {
        .action-header-grid {
            flex-direction: column;
            align-items: stretch;
        }
        .filter-form-grid {
            grid-template-columns: 1fr 1fr;
        }
        .btn-download-pdf {
            justify-content: center;
        }
    }

    @media (max-width: 576px) {
        .kpi-cards-grid { grid-template-columns: 1fr; }
        .filter-form-grid { grid-template-columns: 1fr; }
    }
</style>

<!-- PANEL KONTROL: Filter Berkas & Aksi Ekspor Dokumen -->
<div class="action-header-grid">
    <form action="/dashboard/reports/sla" method="get" class="filter-form-grid">
        <div class="form-group">
            <label>Tanggal Mulai</label>
            <input type="date" class="input-date-control" name="start_date" value="<?= esc($startDate ?? '') ?>">
        </div>

        <div class="form-group">
            <label>Tanggal Selesai</label>
            <input type="date" class="input-date-control" name="end_date" value="<?= esc($endDate ?? '') ?>">
        </div>

        <div>
            <button type="submit" class="btn-report-submit">
                <i class="uil uil-filter" style="margin-right: 4px;"></i> Saring Data
            </button>
        </div>

        <div>
            <a href="/dashboard/reports/sla" class="btn-report-reset">Reset</a>
        </div>
    </form>

    <div>
        <a href="/dashboard/reports/sla/download-pdf?start_date=<?= esc($startDate ?? '') ?>&end_date=<?= esc($endDate ?? '') ?>" class="btn-download-pdf">
            <i class="uil uil-file-download"></i> Ekspor Laporan Ringkas (PDF)
        </a>
    </div>
</div>

<!-- SECTION GRID: Tampilan Kartu Ringkasan Metrik (KPI Cards) -->
<div class="kpi-cards-grid">
    <div class="kpi-card" style="border-left: 4px solid #4f46e5;">
        <h4>Total Berkas Tiket</h4>
        <div class="kpi-number"><?= $totalTiket ?></div>
    </div>

    <div class="kpi-card" style="border-left: 4px solid #10b981;">
        <h4>Tiket Selesai (Done)</h4>
        <div class="kpi-number"><?= $totalDone ?></div>
    </div>

    <div class="kpi-card" style="border-left: 4px solid #059669;">
        <h4>Tepat Waktu (On-Time)</h4>
        <div class="kpi-number"><?= $onTime ?></div>
    </div>

    <div class="kpi-card" style="border-left: 4px solid #ef4444;">
        <h4>Melebihi Batas (Overdue)</h4>
        <div class="kpi-number"><?= $totalOverdue ?></div>
    </div>

    <div class="kpi-card" style="border-left: 4px solid #06b6d4;">
        <h4>Persentase On-Time</h4>
        <div class="kpi-number" style="color: #059669;"><?= $persenOnTime ?>%</div>
    </div>

    <div class="kpi-card" style="border-left: 4px solid #f97316;">
        <h4>Terlambat Selesai</h4>
        <div class="kpi-number"><?= $lateDone ?></div>
    </div>

    <div class="kpi-card" style="border-left: 4px solid #ec4899;">
        <h4>Skor Kepuasan</h4>
        <div class="kpi-number"><?= $kepuasanPersen ?>%</div>
    </div>

    <div class="kpi-card" style="border-left: 4px solid #eab308;">
        <h4>Rata-rata Rating</h4>
        <div class="kpi-number" style="color: #d97706;"><?= $rataRataRating ?> <span style="font-size:14px; color:#64748b; font-weight:500;">/ 5</span></div>
    </div>
</div>

<!-- SECTION KEDUA: Visualisasi Pemantauan Indeks SLA -->
<div class="report-container">
    <h3><i class="uil uil-chart-growth" style="color: #4f46e5;"></i> Analisis Tingkat Kepatuhan SLA</h3>
    
    <div class="summary-progress-wrapper">
        <div class="summary-meta-text">
            <span><strong>Penyelesaian Tepat Waktu (On-Time Rate)</strong></span>
            <span style="font-weight: 700; color: #059669;"><?= $persenOnTime ?>%</span>
        </div>
        <div class="progress-track">
            <div class="progress-fill-bar bg-gradient-success" style="width: <?= $persenOnTime ?>%;"></div>
        </div>
    </div>

    <div class="summary-progress-wrapper">
        <div class="summary-meta-text">
            <span><strong>Penyelesaian Terlambat (Late Resolution Rate)</strong></span>
            <span style="font-weight: 700; color: #dc2626;"><?= $persenLate ?>%</span>
        </div>
        <div class="progress-track">
            <div class="progress-fill-bar bg-gradient-danger" style="width: <?= $persenLate ?>%;"></div>
        </div>
    </div>

    <div style="background-color: #f8fafc; padding: 14px 16px; border-radius: 8px; border: 1px solid #f1f5f9; margin-top: 24px;">
        <p style="font-size: 13px; color: #64748b; line-height: 1.6; margin: 0;">
            <i class="uil uil-info-circle" style="color:#4f46e5; margin-right:4px;"></i> 
            <strong>Metodologi Komputasi:</strong> Evaluasi parameter ketepatan waktu dihitung secara otomatis oleh sistem dengan mengomparasi variabel <em>waktu penutupan berkas</em> terhadap <em>tenggat batas resolusi (SLA target deadline)</em>. Berkas mendapatkan klasifikasi <strong>On-Time</strong> apabila pencatatan akhir di basis data tidak melampaui limitasi waktu yang telah ditentukan pada saat tiket diterbitkan.
        </p>
    </div>
</div>

<!-- SECTION KETIGA: Matriks Performa Komparatif Staff Teknisi -->
<div class="report-container">
    <h3><i class="uil uil-users-alt" style="color: #0284c7;"></i> Tabel Parameter Performa Output Teknisi</h3>

    <div class="responsive-table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Nama Lengkap Teknisi</th>
                    <th>Alamat Email</th>
                    <th style="text-align: center;">Total Alokasi</th>
                    <th style="text-align: center;">Resolved</th>
                    <th style="text-align: center;">On-Time</th>
                    <th style="text-align: center;">Overdue</th>
                    <th style="text-align: center;">Rasio On-Time</th>
                    <th style="text-align: center; width: 140px;">Status Evaluasi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($performaTeknisi)) : ?>
                    <?php foreach ($performaTeknisi as $t) : ?>
                        <tr>
                            <!-- Identitas -->
                            <td style="font-weight: 600; color: #0f172a;"><?= esc($t['nama_teknisi']) ?></td>
                            <td style="color: #64748b; font-size: 13px;"><?= esc($t['email']) ?></td>
                            
                            <!-- Penghitungan Berkas -->
                            <td style="text-align: center; font-weight: 500;"><?= esc($t['total_tiket']) ?></td>
                            <td style="text-align: center; color: #10b981; font-weight: 500;"><?= esc($t['done']) ?></td>
                            <td style="text-align: center; color: #059669; font-weight: 500;"><?= esc($t['on_time']) ?></td>
                            <td style="text-align: center; color: #dc2626; font-weight: 500;"><?= esc($t['overdue']) ?></td>
                            
                            <!-- Rasio Efisiensi -->
                            <td style="text-align: center; font-weight: 700; color: #0f172a;">
                                <?= esc($t['persen_on_time']) ?>%
                            </td>
                            
                            <!-- Status Label Semantik Berkala -->
                            <td style="text-align: center;">
                                <?php if ($t['persen_on_time'] >= 80) : ?>
                                    <span class="badge-evaluation good">Baik</span>
                                <?php elseif ($t['persen_on_time'] >= 60) : ?>
                                    <span class="badge-evaluation warning">Cukup</span>
                                <?php else : ?>
                                    <span class="badge-evaluation bad">Perlu Evaluasi</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="8" style="text-align: center; color: #94a3b8; padding: 48px 0;">
                            <i class="uil uil-users-alt" style="display: block; font-size: 32px; margin-bottom: 8px;"></i>
                            Tidak ada rekaman data performa teknisi yang terdaftar pada periode ini.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
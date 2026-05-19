<?= $this->extend('layouts/dashboard_layout') ?>

<?= $this->section('content') ?>

<!-- Custom Style Khusus untuk Halaman Form Tiket -->
<style>
    .form-container {
        max-width: 850px;
        background: white;
        border-radius: 16px;
        padding: 32px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.02);
    }

    .form-container h3 {
        font-size: 20px;
        font-weight: 700;
        color: #0f172a;
        letter-spacing: -0.5px;
        margin-bottom: 6px;
    }

    .form-container p {
        font-size: 14px;
        color: #64748b;
        margin-bottom: 24px;
    }

    /* Info Box Berbasis Tren UI Modern (Indigo/Blue Accent) */
    .info-banner {
        background: rgba(79, 70, 229, 0.05);
        border: 1px solid rgba(79, 70, 229, 0.15);
        padding: 16px 20px;
        border-radius: 12px;
        color: #4338ca;
        margin-bottom: 28px;
        font-size: 13.5px;
        line-height: 1.6;
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }

    .info-banner i {
        font-size: 20px;
        color: #4f46e5;
        margin-top: 2px;
    }

    .form-group {
        margin-bottom: 22px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        color: #475569;
        font-weight: 600;
        font-size: 13.5px;
        letter-spacing: 0.1px;
    }

    /* Reset & Modernisasi Elemen Input Form */
    select, textarea {
        width: 100%;
        padding: 12px 16px;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        font-size: 14px;
        color: #0f172a;
        background-color: #f8fafc;
        outline: none;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        appearance: none; /* Reset panah bawaan browser untuk select jika diperlukan */
    }

    select {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2394a3b8' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 16px center;
        background-size: 18px;
        padding-right: 44px;
        cursor: pointer;
    }

    textarea {
        min-height: 150px;
        resize: vertical;
        line-height: 1.5;
    }

    /* Efek Interaksi saat Fokus */
    select:focus, textarea:focus {
        background-color: #ffffff;
        border-color: #4f46e5;
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
    }

    /* Container Tombol Aksi */
    .form-actions {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-top: 28px;
        border-top: 1px solid #f1f5f9;
        padding-top: 20px;
    }

    /* Standarisasi Tombol Baru */
    .btn-submit {
        background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.15);
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-submit:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(79, 70, 229, 0.25);
        filter: brightness(1.05);
    }

    .btn-cancel {
        background: #ffffff;
        color: #64748b;
        border: 1.5px solid #e2e8f0;
        padding: 11px 24px;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
    }

    .btn-cancel:hover {
        background: #f8fafc;
        color: #334155;
        border-color: #cbd5e1;
    }
</style>

<div class="form-container">
    <h3>Buat Tiket Baru</h3>
    <p>Input keluhan pelanggan dan sistem akan menentukan prioritas secara otomatis.</p>

    <!-- Banner Info Otomatisasi dengan Ikon Modern -->
    <div class="info-banner">
        <i class="uil uil-processor"></i>
        <div>
            Setelah tiket disimpan, sistem akan menjalankan kecerdasan buatan berbasis klasifikasi <strong>Naive Bayes</strong> untuk menentukan prioritas otomatis, menghitung estimasi <i>deadline SLA</i>, serta mendistribusikan penugasan ke teknisi dengan beban kerja paling ringan.
        </div>
    </div>

    <form action="/dashboard/tickets/store" method="post">

        <div class="form-group">
            <label>Pilih Pelanggan / Customer</label>
            <select name="id_user" required>
                <option value="">-- Pilih Customer Terdaftar --</option>
                <?php foreach ($customers as $c) : ?>
                    <option value="<?= esc($c['id_user']) ?>">
                        <?= esc($c['nama']) ?> &nbsp;&bull;&nbsp; <?= esc($c['email']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Deskripsi Kendala / Keluhan</label>
            <textarea name="deskripsi" placeholder="Tuliskan secara detail masalah teknis yang dialami. Contoh: Koneksi internet router lantai 2 terputus total sejak pukul 08:00 WIB dan lampu indikator LOS berkedip merah..." required></textarea>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-submit">
                <i class="uil uil-save"></i> Simpan & Proses Tiket
            </button>
            <a href="/dashboard/tickets" class="btn-cancel">Kembali</a>
        </div>

    </form>
</div>

<?= $this->endSection() ?>
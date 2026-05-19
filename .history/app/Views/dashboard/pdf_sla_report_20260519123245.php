<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan KPI & SLA Support Service</title>

    <style>
        /* Pengaturan Dasar Halaman Cetak Dokumen */
        @page {
            margin: 1.2cm 1.4cm;
        }
        
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11px;
            line-height: 1.5;
            color: #1e293b;
            background-color: #ffffff;
        }

        /* Tata Letak Header / Kop Surat Laporan */
        .report-header {
            text-align: center;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 12px;
            margin-bottom: 18px;
        }

        h1 {
            font-size: 16px;
            font-weight: 800;
            color: #0f172a;
            margin: 0 0 4px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .subtitle {
            font-size: 10px;
            color: #475569;
            margin: 0;
            font-style: italic;
        }

        /* Blok Informasi Metadata Dokumen */
        .metadata-box {
            margin-bottom: 20px;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 10px 14px;
            border-radius: 6px;
        }

        .metadata-box table {
            width: 100%;
            margin: 0;
        }

        .metadata-box td {
            border: none !important;
            padding: 2px 0 !important;
            font-size: 11px;
            color: #334155;
        }

        /* Desain Sub-Judul Konten */
        h2 {
            font-size: 12px;
            font-weight: 700;
            color: #0f172a;
            margin-top: 20px;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 4px;
        }

        /* Standarisasi Desain Tabel Laporan */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }

        table th {
            background-color: #f1f5f9;
            color: #334155;
            font-weight: 700;
            font-size: 10px;
            text-transform: uppercase;
            border: 1px solid #cbd5e1;
            padding: 6px 8px;
        }

        table td {
            border: 1px solid #e2e8f0;
            padding: 6px 8px;
            color: #334155;
            vertical-align: middle;
        }

        /* Variasi Spesifik Struktur Tabel */
        .summary-table td {
            width: 50%;
        }

        .summary-table td:first-child {
            font-weight: 500;
            background-color: #fafafa;
            color: #475569;
        }

        .summary-table td:last-child {
            font-weight: 700;
            text-align: right;
            color: #0f172a;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: 700; }

        /* Catatan Kaki Dokumen */
        .narrative-text {
            font-size: 10.5px;
            color: #475569;
            line-height: 1.6;
            text-align: justify;
            margin-top: 6px;
        }

        .footer {
            margin-top: 40px;
            font-size: 9.5px;
            color: #94a3b8;
            text-align: right;
            border-top: 1px dashed #e2e8f0;
            padding-top: 8px;
        }
    </style>
</head>
<body>

    <!-- KOP UTAMA DOKUMEN LAPORAN -->
    <div class="report-header">
        <h1>Laporan KPI / SLA Support Service</h1>
        <div class="subtitle">
            Sistem Support Service Berbasis Android dengan Naive Bayes, Priority Queue, dan Load Balancing
        </div>
    </div>

    <!-- METADATA CETAK -->
    <div class="metadata-box">
        <table>
            <tr>
                <td style="width: 15%;"><strong>Tanggal Cetak</strong></td>
                <td style="width: 3%;">:</td>
                <td><?= esc($tanggalCetak) ?></td>
            </tr>
            <tr>
                <td><strong>Jenis Laporan</strong></td>
                <td>:</td>
                <td>Evaluasi Kinerja KPI/SLA, Overdue, On-Time, dan Kualitatif Kepuasan Pelanggan</td>
            </tr>
            <tr>
                <td><strong>Periode Data</strong></td>
                <td>:</td>
                <td>
                    <?php if (!empty($startDate) || !empty($endDate)) : ?>
                        <?= esc($startDate ?: '-') ?> s.d. <?= esc($endDate ?: '-') ?>
                    <?php else : ?>
                        Semua Periode Rekaman Sistem
                    <?php endif; ?>
                </td>
            </tr>
        </table>
    </div>

    <!-- BAGIAN 1: RINGKASAN SLA -->
    <h2>1. Ringkasan Kepatuhan Layanan (SLA)</h2>
    <table class="summary-table">
        <tr>
            <td>Total Alokasi Berkas Tiket</td>
            <td><?= esc($totalTiket) ?> Berkas</td>
        </tr>
        <tr>
            <td>Tiket Terselesaikan (Status Done)</td>
            <td><?= esc($totalDone) ?> Berkas</td>
        </tr>
        <tr>
            <td>Penyelesaian Tepat Waktu (On-Time)</td>
            <td><?= esc($onTime) ?> Berkas</td>
        </tr>
        <tr>
            <td>Tiket Melebihi Batas Waktu (Overdue)</td>
            <td><?= esc($totalOverdue) ?> Berkas</td>
        </tr>
        <tr>
            <td>Tiket Selesai Namun Terlambat (Late Done)</td>
            <td><?= esc($lateDone) ?> Berkas</td>
        </tr>
        <tr>
            <td>Rasio Ketepatan Waktu (On-Time Rate)</td>
            <td style="color: #16a34a;"><?= esc($persenOnTime) ?>%</td>
        </tr>
        <tr>
            <td>Rasio Keterlambatan Respon (Late Rate)</td>
            <td style="color: #dc2626;"><?= esc($persenLate) ?>%</td>
        </tr>
    </table>

    <!-- BAGIAN 2: RINGKASAN KEPUASAN -->
    <h2>2. Indeks Kepuasan Pelanggan (CSAT)</h2>
    <table class="summary-table">
        <tr>
            <td>Total Responden Rating</td>
            <td><?= esc($totalRating) ?> Ulasan</td>
        </tr>
        <tr>
            <td>Rata-rata Penilaian Komparatif</td>
            <td><?= esc($rataRataRating) ?> / 5.00</td>
        </tr>
        <tr>
            <td>Persentase Konversi Kepuasan Pengguna</td>
            <td style="color: #2563eb;"><?= esc($kepuasanPersen) ?>%</td>
        </tr>
    </table>

    <!-- BAGIAN 3: MATRIKS TEKNISI -->
    <h2>3. Matriks Akuntabilitas Performa Staf Teknisi</h2>
    <table>
        <thead>
            <tr>
                <th style="width: 5%; text-align: center;">No</th>
                <th style="width: 25%;">Nama Teknisi</th>
                <th style="width: 25%;">Alamat Email</th>
                <th style="width: 10%; text-align: center;">Total Tiket</th>
                <th style="width: 10%; text-align: center;">Selesai</th>
                <th style="width: 10%; text-align: center;">On-Time</th>
                <th style="width: 10%; text-align: center;">Overdue</th>
                <th style="width: 12%; text-align: center;">% On-Time</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($performaTeknisi)) : ?>
                <?php $no = 1; ?>
                <?php foreach ($performaTeknisi as $t) : ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td class="font-bold"><?= esc($t['nama_teknisi']) ?></td>
                        <td style="color: #475569;"><?= esc($t['email']) ?></td>
                        <td class="text-center"><?= esc($t['total_tiket']) ?></td>
                        <td class="text-center" style="color: #16a34a;"><?= esc($t['done']) ?></td>
                        <td class="text-center"><?= esc($t['on_time']) ?></td>
                        <td class="text-center" style="color: #dc2626;"><?= esc($t['overdue']) ?></td>
                        <td class="text-center font-bold" style="background-color: #f8fafc;"><?= esc($t['persen_on_time']) ?>%</td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="8" class="text-center" style="color: #64748b; padding: 20px 0;">
                        Belum ada rekaman data performa teknisi pada periode ini.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- BAGIAN 4: KESIMPULAN KUALITATIF -->
    <h2>4. Metodologi Komputasi & Kesimpulan</h2>
    <p class="narrative-text">
        Dokumen ini menyajikan kalkulasi performa layanan support lapangan secara otomatis. Parameter ketepatan waktu dihitung secara berkala dengan mengomparasi variabel <em>waktu penutupan berkas</em> terhadap <em>tenggat batas resolusi (SLA target deadline)</em>. Tiket mendapatkan klasifikasi <strong>On-Time</strong> apabila pencatatan akhir di basis data tidak melampaui limitasi waktu yang ditentukan saat tiket diterbitkan. Evaluasi kepuasan pelanggan diperoleh melalui rekapitulasi nilai kuantitatif ulasan pasca-penyelesaian kendala di sisi klien.
    </p>

    <!-- FOOTER DOKUMEN -->
    <div class="footer">
        Dokumen digital ini diterbitkan secara otomatis dan sah oleh Sistem Pendukung Keputusan Support Service Corp.
    </div>

</body>
</html>
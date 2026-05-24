<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Support Service Dashboard' ?></title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background: #f8fafc;
            color: #0f172a;
            min-height: 100vh;
        }

        .wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* --- SIDEBAR STYLE (MODERN DARK) --- */
        .sidebar {
            width: 270px;
            background: #0f172a;
            color: #f8fafc;
            padding: 28px 20px;
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            z-index: 50;
            border-right: 1px solid rgba(255, 255, 255, 0.05);
            display: flex;
            flex-direction: column;
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 8px;
        }

        .brand-icon {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, #4f46e5, #06b6d4);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 16px;
        }

        .sidebar h2 {
            font-size: 18px;
            font-weight: 700;
            letter-spacing: -0.3px;
            background: linear-gradient(90deg, #ffffff, #cbd5e1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .sidebar .system-tag {
            font-size: 12px;
            color: #64748b;
            margin-bottom: 32px;
            padding-left: 44px;
            font-weight: 500;
        }

        .menu {
            display: flex;
            flex-direction: column;
            gap: 6px;
            flex-grow: 1;
        }

        .menu a {
            color: #94a3b8;
            text-decoration: none;
            padding: 12px 14px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.2s ease-in-out;
        }

        .menu a i {
            font-size: 18px;
            transition: transform 0.2s ease;
        }

        .menu a:hover {
            background: rgba(255, 255, 255, 0.03);
            color: #f8fafc;
        }

        .menu a:hover i {
            transform: translateX(2px);
        }

        .menu a.active {
            background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
            font-weight: 600;
        }

        /* --- CONTENT MAIN STRUCTURE --- */
        .content {
            margin-left: 270px;
            width: calc(100% - 270px);
            display: flex;
            flex-direction: column;
        }

        /* --- TOPBAR STYLE --- */
        .topbar {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            padding: 20px 40px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 40;
        }

        .topbar h3 {
            font-size: 22px;
            font-weight: 700;
            color: #0f172a;
            letter-spacing: -0.5px;
        }

        .topbar small {
            color: #64748b;
            font-size: 13px;
            margin-top: 2px;
            display: inline-block;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .user-info {
            text-align: right;
        }

        .user-info .name {
            font-weight: 600;
            font-size: 14px;
            color: #0f172a;
        }

        .user-info .email {
            color: #64748b;
            font-size: 12px;
        }

        .logout-btn {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            padding: 8px 14px;
            border-radius: 10px;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .logout-btn:hover {
            background: #dc2626;
            color: white;
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.2);
        }

        .main {
            padding: 40px;
            flex-grow: 1;
        }

        /* --- CARDS CONFIGURATIONS --- */
        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 32px;
        }

        .card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card:hover {
            transform: translateY(-4px);
        }

        .card-info {
            display: flex;
            flex-direction: column;
        }

        .card h4 {
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .card .number {
            font-size: 32px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .card-icon-box {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }

        /* --- PREMIUM PASTEL CARD COLORS --- */
        .card-primary { background: #f0f6ff; border-color: #dbeafe; }
        .card-primary:hover { border-color: #bfdbfe; box-shadow: 0 10px 20px -5px rgba(59, 130, 246, 0.15); }
        .card-primary h4 { color: #2563eb; }
        .card-primary .number { color: #1e3a8a; }
        .card-primary .card-icon-box { background: #dbeafe; color: #2563eb; }

        .card-warning { background: #fffbeb; border-color: #fef3c7; }
        .card-warning:hover { border-color: #fef08a; box-shadow: 0 10px 20px -5px rgba(217, 119, 6, 0.15); }
        .card-warning h4 { color: #d97706; }
        .card-warning .number { color: #78350f; }
        .card-warning .card-icon-box { background: #fef3c7; color: #d97706; }

        .card-success { background: #f0fdf4; border-color: #dcfce7; }
        .card-success:hover { border-color: #bbf7d0; box-shadow: 0 10px 20px -5px rgba(22, 163, 74, 0.15); }
        .card-success h4 { color: #16a34a; }
        .card-success .number { color: #14532d; }
        .card-success .card-icon-box { background: #dcfce7; color: #16a34a; }

        .card-danger { background: #fef2f2; border-color: #fee2e2; }
        .card-danger:hover { border-color: #fecaca; box-shadow: 0 10px 20px -5px rgba(220, 38, 38, 0.15); }
        .card-danger h4 { color: #dc2626; }
        .card-danger .number { color: #7f1d1d; }
        .card-danger .card-icon-box { background: #fee2e2; color: #dc2626; }

        /* --- MODERN TABLE DESIGN --- */
        .section {
            background: white;
            border-radius: 16px;
            padding: 24px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
            margin-bottom: 30px;
        }

        .section h3 {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 20px;
            color: #0f172a;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th {
            background: #f8fafc;
            color: #475569;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.5px;
            padding: 14px 16px;
            border-bottom: 2px solid #e2e8f0;
            text-align: left;
        }

        table td {
            padding: 16px;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
            font-size: 14px;
        }

        .badge {
            padding: 6px 12px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 12px;
            display: inline-block;
        }

        /* --- RESPONSIVE TIMELINE --- */
        @media (max-width: 1024px) {
            .sidebar { width: 78px; padding: 24px 12px; align-items: center; }
            .sidebar h2, .sidebar .system-tag, .menu a span { display: none; }
            .brand-icon { margin: 0; }
            .content { margin-left: 78px; width: calc(100% - 78px); }
            .topbar { padding: 20px 24px; }
            .main { padding: 24px; }
        }

        @media (max-width: 640px) {
            .wrapper { flex-direction: column; }
            .sidebar { position: relative; width: 100%; height: auto; border-right: none; border-bottom: 1px solid rgba(255, 255, 255, 0.05); padding: 16px; }
            .sidebar-brand { display: inline-flex; }
            .menu { flex-direction: row; overflow-x: auto; padding-bottom: 4px; }
            .menu a span { display: none; }
            .content { margin-left: 0; width: 100%; }
            .topbar { flex-direction: column; gap: 16px; align-items: flex-start; }
            .user-profile { width: 100%; justify-content: space-between; }
        }
    </style>
</head>
<body>

<div class="wrapper">

    <?php
    $uri   = service('request')->getUri();
    $seg_2 = $uri->getTotalSegments() >= 2 ? $uri->getSegment(2) : '';
    $seg_3 = $uri->getTotalSegments() >= 3 ? $uri->getSegment(3) : '';
    ?>

    <aside class="sidebar">
        <div class="sidebar-brand">
            <div class="brand-icon">
                <i class="uil uil-bolt"></i>
            </div>
            <h2>Support Service</h2>
        </div>
        <div class="system-tag">Android Ticketing</div>

        <nav class="menu">
            <a href="/dashboard" class="<?= ($seg_2 === '' || $seg_2 === 'index') ? 'active' : '' ?>">
                <i class="uil uil-apps"></i> <span>Dashboard</span>
            </a>
            <a href="/dashboard/users" class="<?= ($seg_2 === 'users') ? 'active' : '' ?>">
                <i class="uil uil-users-alt"></i> <span>Manajemen User</span>
            </a>
            <a href="/dashboard/tickets" class="<?= ($seg_2 === 'tickets' && $seg_3 !== 'create') ? 'active' : '' ?>">
                <i class="uil uil-ticket"></i> <span>Daftar Tiket</span>
            </a>
            <a href="/dashboard/tickets/create" class="<?= ($seg_2 === 'tickets' && $seg_3 === 'create') ? 'active' : '' ?>">
                <i class="uil uil-plus-circle"></i> <span>Buat Tiket</span>
            </a>
            <a href="/dashboard/notifications" class="<?= ($seg_2 === 'notifications') ? 'active' : '' ?>">
                <i class="uil uil-bell"></i> <span>Notifikasi</span>
            </a>
            <a href="/dashboard/ratings" class="<?= ($seg_2 === 'ratings') ? 'active' : '' ?>">
                <i class="uil uil-star"></i> <span>Rating</span>
            </a>
            <a href="/dashboard/reports/sla" class="<?= ($seg_2 === 'reports') ? 'active' : '' ?>">
                <i class="uil uil-chart-growth"></i> <span>Laporan KPI/SLA</span>
            </a>
        </nav>
    </aside>

    <main class="content">
        <div class="topbar">
            <div>
                <h3><?= $pageTitle ?? 'Dashboard Admin' ?></h3>
                <small><?= $pageSubtitle ?? 'Monitoring sistem support service secara real-time' ?></small>
            </div>

            <div class="user-profile">
                <div class="user-info">
                    <div class="name"><?= esc(session()->get('nama') ?? 'Admin') ?></div>
                    <div class="email"><?= esc(session()->get('email') ?? 'admin@support.com') ?></div>
                </div>
                <a href="/logout" class="logout-btn">
                    <i class="uil uil-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>

        <div class="main">
            
            <div class="cards">
                <div class="card card-primary">
                    <div class="card-info">
                        <h4>Total Tiket</h4>
                        <div class="number"><?= $ringkasan_tiket['total_tiket'] ?? 0 ?></div>
                    </div>
                    <div class="card-icon-box">
                        <i class="uil uil-ticket"></i>
                    </div>
                </div>

                <div class="card card-warning">
                    <div class="card-info">
                        <h4>Dalam Proses</h4>
                        <div class="number"><?= $ringkasan_tiket['in_progress'] ?? 0 ?></div>
                    </div>
                    <div class="card-icon-box">
                        <i class="uil uil-clock"></i>
                    </div>
                </div>

                <div class="card card-success">
                    <div class="card-info">
                        <h4>Tiket Selesai</h4>
                        <div class="number"><?= $ringkasan_tiket['done'] ?? 0 ?></div>
                    </div>
                    <div class="card-icon-box">
                        <i class="uil uil-check-circle"></i>
                    </div>
                </div>

                <div class="card card-danger">
                    <div class="card-info">
                        <h4>Overdue</h4>
                        <div class="number"><?= $ringkasan_tiket['overdue'] ?? 0 ?></div>
                    </div>
                    <div class="card-icon-box">
                        <i class="uil uil-exclamation-triangle"></i>
                    </div>
                </div>
            </div>

            <div class="section">
                <h3>Beban Kerja & Produktivitas Teknisi</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Nama Teknisi</th>
                            <th>Tiket Aktif (Open/Progress)</th>
                            <th>Tiket Selesai (Done)</th>
                            <th>Status Beban</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($beban_kerja_teknisi)): ?>
                            <?php foreach ($beban_kerja_teknisi as $bt): ?>
                                <tr>
                                    <td><strong><?= esc($bt['nama_teknisi']) ?></strong></td>
                                    <td>
                                        <span class="badge" style="background: #fffbeb; color: #d97706;">
                                            <?= $bt['tiket_aktif'] ?> Tiket
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge" style="background: #f0fdf4; color: #16a34a;">
                                            <?= $bt['tiket_selesai'] ?> Selesai
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($bt['tiket_aktif'] >= 5): ?>
                                            <span class="badge" style="background: #fef2f2; color: #dc2626;">Overload</span>
                                        <?php elseif ($bt['tiket_aktif'] > 0): ?>
                                            <span class="badge" style="background: #f0f6ff; color: #2563eb;">Normal</span>
                                        <?php else: ?>
                                            <span class="badge" style="background: #f1f5f9; color: #64748b;">Senggang</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" style="text-align: center; color: #64748b; padding: 30px;">
                                    Belum ada data aktivitas teknisi saat ini.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?= $this->renderSection('content') ?>
        </div>
    </main>

</div>

</body>
</html>
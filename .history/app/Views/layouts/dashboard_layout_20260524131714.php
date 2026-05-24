<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Support Service Dashboard' ?></title>
    
    <!-- Google Fonts & Font Icon Premium -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Unicons untuk Ikon Modern -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background: #519ae2ff;
            color: #3e4170ff;
            min-height: 100vh;
        }

        .wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* --- SIDEBAR STYLE (MODERN DARK) --- */
        .sidebar {
            width: 270px;
            background: #0d241eff;
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
            width: 38px;
            height: 38px;
            background: white;
            border-radius: 10px;
            padding: 5px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .brand-icon img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
        }

        .sidebar-brand.logo-center {
            justify-content: center;
            width: 100%;
            margin-bottom: 16px;
        }

        .sidebar-brand.logo-center .brand-icon {
            width: 100px;
            height: 100px;
            background: transparent;
            padding: 0;
            border-radius: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sidebar-brand.logo-center .brand-icon img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
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
            font-size: 16px;
            color: #ffffffff;
            margin-bottom: 32px;
            padding-left: 0;
            font-weight: 600;
            text-align: center;
            line-height: 1.4;
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
            background: rgba(190, 198, 224, 0.8);
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
            color: #111116ff;
            letter-spacing: -0.5px;
        }

        .topbar small {
            color: #061ef8ff;
            font-size: 13px;
            margin-top: 2px;
            display: inline-block;
        }

        /* Profil Pengguna Modern */
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
            color: #0717f0ff;
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

        /* --- TEMPLATE GLOBAL UNTUK KOMPONEN ISI --- */
        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 22px;
            margin-bottom: 32px;
        }

        .card {
            position: relative;
            overflow: hidden;
            border-radius: 20px;
            padding: 26px 24px;
            border: none;
            color: white;
            background: linear-gradient(135deg, #334155 0%, #0f172a 100%);
            box-shadow: 0 18px 35px rgba(15, 23, 42, 0.14);
            transition: transform 0.25s ease, box-shadow 0.25s ease, filter 0.25s ease;
        }

        /* Efek ornamen bulat di dalam card */
        .card::before {
            content: "";
            position: absolute;
            width: 130px;
            height: 130px;
            right: -38px;
            top: -42px;
            background: rgba(255, 255, 255, 0.16);
            border-radius: 50%;
        }

        .card::after {
            content: "";
            position: absolute;
            width: 85px;
            height: 85px;
            right: 28px;
            bottom: -38px;
            background: rgba(255, 255, 255, 0.10);
            border-radius: 50%;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 24px 45px rgba(15, 23, 42, 0.22);
            filter: brightness(1.04);
        }

        .card h4 {
            position: relative;
            z-index: 2;
            color: rgba(255, 255, 255, 0.88);
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.7px;
        }

        .card .number {
            position: relative;
            z-index: 2;
            margin-top: 14px;
            font-size: 36px;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: -0.8px;
        }

        /* Warna otomatis tiap card berdasarkan urutan */
        .cards .card:nth-child(1) {
            background: linear-gradient(135deg, #4f46e5 0%, #2563eb 100%);
        }

        .cards .card:nth-child(2) {
            background: linear-gradient(135deg, #0284c7 0%, #06b6d4 100%);
        }

        .cards .card:nth-child(3) {
            background: linear-gradient(135deg, #f97316 0%, #f59e0b 100%);
        }

        .cards .card:nth-child(4) {
            background: linear-gradient(135deg, #16a34a 0%, #22c55e 100%);
        }

        .cards .card:nth-child(5) {
            background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
        }

        .cards .card:nth-child(6) {
            background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%);
        }

        .cards .card:nth-child(7) {
            background: linear-gradient(135deg, #0f766e 0%, #14b8a6 100%);
        }

        .cards .card:nth-child(8) {
            background: linear-gradient(135deg, #be123c 0%, #f43f5e 100%);
        }

        /* Desain Tabel Modern */
        .section {
            background: white;
            border-radius: 16px;
            padding: 24px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 6px -1px rgba(241, 4, 4, 0.02);
            margin-bottom: 30px;
        }

        .section h3 {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 20px;
            color: #0f172a;
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
        }

        table td {
            padding: 16px;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
        }

        /* --- BUTTONS & BADGES --- */
        .btn {
            padding: 10px 18px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.15);
        }

        .btn-primary:hover {
            box-shadow: 0 6px 16px rgba(79, 70, 229, 0.25);
            filter: brightness(1.05);
        }

        .badge {
            padding: 6px 12px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 12px;
        }

        /* --- RESPONSIVE TIMELINE --- */
        @media (max-width: 1024px) {
            .sidebar {
                width: 78px;
                padding: 24px 12px;
                align-items: center;
            }
            .sidebar h2, .sidebar .system-tag, .menu a span {
                display: none;
            }
            .brand-icon { margin: 0; }
            .content {
                margin-left: 78px;
                width: calc(100% - 78px);
            }
            .topbar { padding: 20px 24px; }
            .main { padding: 24px; }
        }

        @media (max-width: 640px) {
            .wrapper { flex-direction: column; }
            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
                border-right: none;
                border-bottom: 1px solid rgba(255, 255, 255, 0.05);
                padding: 16px;
            }
            .sidebar-brand { display: inline-flex; }
            .menu {
                flex-direction: row;
                overflow-x: auto;
                padding-bottom: 4px;
            }
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
    // Perbaikan pengambilan URI via method getUri() demi keamanan akses enkapsulasi
    $uri   = service('request')->getUri();
    $seg_2 = $uri->getTotalSegments() >= 2 ? $uri->getSegment(2) : '';
    $seg_3 = $uri->getTotalSegments() >= 3 ? $uri->getSegment(3) : '';
    ?>

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="sidebar-brand logo-center">
            <div class="brand-icon">
                <img src="/assets/img/logo_bkk.png" alt="Logo Sistem">
            </div>
        </div>
        <div class="system-tag">Inteligent Smart Ticketing System</div>

        <nav class="menu">
            <!-- 1. Dashboard -->
            <a href="/dashboard" class="<?= ($seg_2 === '' || $seg_2 === 'index') ? 'active' : '' ?>">
                <i class="uil uil-apps"></i> <span>Dashboard</span>
            </a>
            
            <!-- 2. Manajemen User -->
            <a href="/dashboard/users" class="<?= ($seg_2 === 'users') ? 'active' : '' ?>">
                <i class="uil uil-users-alt"></i> <span>Manajemen User</span>
            </a>
            
            <!-- 3. Daftar Tiket -->
            <a href="/dashboard/tickets" class="<?= ($seg_2 === 'tickets' && $seg_3 !== 'create') ? 'active' : '' ?>">
                <i class="uil uil-ticket"></i> <span>Daftar Tiket</span>
            </a>
            
            <!-- 4. Buat Tiket -->
            <a href="/dashboard/tickets/create" class="<?= ($seg_2 === 'tickets' && $seg_3 === 'create') ? 'active' : '' ?>">
                <i class="uil uil-plus-circle"></i> <span>Buat Tiket</span>
            </a>
            
            <!-- 5. Notifikasi -->
            <a href="/dashboard/notifications" class="<?= ($seg_2 === 'notifications') ? 'active' : '' ?>">
                <i class="uil uil-bell"></i> <span>Notifikasi</span>
            </a>
            
            <!-- 6. Rating -->
            <a href="/dashboard/ratings" class="<?= ($seg_2 === 'ratings') ? 'active' : '' ?>">
                <i class="uil uil-star"></i> <span>Rating</span>
            </a>
            
            <!-- 7. Laporan KPI/SLA -->
            <a href="/dashboard/reports/sla" class="<?= ($seg_2 === 'reports') ? 'active' : '' ?>">
                <i class="uil uil-chart-growth"></i> <span>Laporan KPI/SLA</span>
            </a>
        </nav>
    </aside>

    <!-- CONTENT AREA -->
    <main class="content">
        <!-- TOPBAR -->
        <div class="topbar">
            <div>
                <h3><?= $pageTitle ?? 'Dashboard' ?></h3>
                <small><?= $pageSubtitle ?? 'Monitoring sistem support service' ?></small>
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

        <!-- MAIN SECTION -->
        <div class="main">
            <?= $this->renderSection('content') ?>
        </div>
    </main>

</div>

</body>
</html>
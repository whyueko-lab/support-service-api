<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Support Service Dashboard' ?></title>
    
    <!-- Google Fonts & Font Icon Premium -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

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
            background: #f8fafc;
            color: #0f172a;
            min-height: 100vh;
        }

        .wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* =========================
           SIDEBAR
        ========================= */
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
            background: rgba(255, 255, 255, 0.05);
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

        /* =========================
           CONTENT
        ========================= */
        .content {
            margin-left: 270px;
            width: calc(100% - 270px);
            display: flex;
            flex-direction: column;
        }

        /* =========================
           TOPBAR
        ========================= */
        .topbar {
            background: rgba(255, 255, 255, 0.82);
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

        /* =========================
           CARD DASHBOARD WARNA
        ========================= */
        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 32px;
        }

        .card {
            position: relative;
            overflow: hidden;
            border-radius: 20px;
            padding: 24px;
            border: none;
            color: white;
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.12);
            transition: all 0.25s ease;
            min-height: 160px;
        }

        .card::before {
            content: "";
            position: absolute;
            width: 140px;
            height: 140px;
            right: -45px;
            top: -45px;
            background: rgba(255, 255, 255, 0.18);
            border-radius: 50%;
        }

        .card::after {
            content: "";
            position: absolute;
            width: 90px;
            height: 90px;
            right: 20px;
            bottom: -35px;
            background: rgba(255, 255, 255, 0.12);
            border-radius: 50%;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.18);
        }

        .card-icon {
            position: relative;
            z-index: 2;
            width: 46px;
            height: 46px;
            border-radius: 14px;
            background: rgba(255, 255, 255, 0.18);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
        }

        .card-icon i {
            font-size: 24px;
            color: white;
        }

        .card h4 {
            position: relative;
            z-index: 2;
            color: rgba(255, 255, 255, 0.86);
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.6px;
        }

        .card .number {
            position: relative;
            z-index: 2;
            margin-top: 10px;
            font-size: 34px;
            font-weight: 800;
            color: white;
            letter-spacing: -0.8px;
        }

        .card small {
            position: relative;
            z-index: 2;
            display: block;
            margin-top: 8px;
            font-size: 12px;
            color: rgba(255, 255, 255, 0.82);
        }

        .card-blue {
            background: linear-gradient(135deg, #2563eb, #06b6d4);
        }

        .card-green {
            background: linear-gradient(135deg, #16a34a, #22c55e);
        }

        .card-orange {
            background: linear-gradient(135deg, #f97316, #f59e0b);
        }

        .card-red {
            background: linear-gradient(135deg, #dc2626, #f43f5e);
        }

        .card-purple {
            background: linear-gradient(135deg, #7c3aed, #a855f7);
        }

        .card-dark {
            background: linear-gradient(135deg, #0f172a, #334155);
        }

        /* =========================
           SECTION / TABLE
        ========================= */
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
            font-weight: 700;
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

        table tr:hover td {
            background: #f8fafc;
        }

        /* =========================
           BUTTON
        ========================= */
        .btn {
            padding: 10px 18px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
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

        .btn-danger {
            background: #fee2e2;
            color: #b91c1c;
        }

        .btn-danger:hover {
            background: #dc2626;
            color: white;
        }

        .btn-success {
            background: #dcfce7;
            color: #15803d;
        }

        .btn-success:hover {
            background: #16a34a;
            color: white;
        }

        /* =========================
           BADGE WARNA
        ========================= */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 12px;
            border-radius: 999px;
            font-weight: 700;
            font-size: 12px;
            text-transform: capitalize;
        }

        .badge-high {
            background: #fee2e2;
            color: #b91c1c;
        }

        .badge-medium {
            background: #fef3c7;
            color: #b45309;
        }

        .badge-low {
            background: #dcfce7;
            color: #15803d;
        }

        .badge-open {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .badge-progress {
            background: #ede9fe;
            color: #6d28d9;
        }

        .badge-done {
            background: #dcfce7;
            color: #15803d;
        }

        .badge-overdue {
            background: #fee2e2;
            color: #b91c1c;
        }

        .badge-info {
            background: #e0f2fe;
            color: #0369a1;
        }

        /* =========================
           FORM TAMBAHAN
        ========================= */
        input,
        textarea,
        select {
            width: 100%;
            padding: 11px 14px;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            outline: none;
            font-size: 14px;
            color: #0f172a;
            background: white;
        }

        input:focus,
        textarea:focus,
        select:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.12);
        }

        label {
            font-weight: 600;
            font-size: 13px;
            color: #334155;
            margin-bottom: 8px;
            display: inline-block;
        }

        /* =========================
           RESPONSIVE
        ========================= */
        @media (max-width: 1024px) {
            .sidebar {
                width: 78px;
                padding: 24px 12px;
                align-items: center;
            }

            .sidebar h2,
            .sidebar .system-tag,
            .menu a span {
                display: none;
            }

            .brand-icon {
                margin: 0;
            }

            .content {
                margin-left: 78px;
                width: calc(100% - 78px);
            }

            .topbar {
                padding: 20px 24px;
            }

            .main {
                padding: 24px;
            }
        }

        @media (max-width: 640px) {
            .wrapper {
                flex-direction: column;
            }

            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
                border-right: none;
                border-bottom: 1px solid rgba(255, 255, 255, 0.05);
                padding: 16px;
            }

            .sidebar-brand {
                display: inline-flex;
            }

            .menu {
                flex-direction: row;
                overflow-x: auto;
                padding-bottom: 4px;
            }

            .menu a span {
                display: none;
            }

            .content {
                margin-left: 0;
                width: 100%;
            }

            .topbar {
                flex-direction: column;
                gap: 16px;
                align-items: flex-start;
            }

            .user-profile {
                width: 100%;
                justify-content: space-between;
            }

            .cards {
                grid-template-columns: 1fr;
            }
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

    <!-- SIDEBAR -->
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
                <i class="uil uil-apps"></i>
                <span>Dashboard</span>
            </a>
            
            <a href="/dashboard/users" class="<?= ($seg_2 === 'users') ? 'active' : '' ?>">
                <i class="uil uil-users-alt"></i>
                <span>Manajemen User</span>
            </a>
            
            <a href="/dashboard/tickets" class="<?= ($seg_2 === 'tickets' && $seg_3 !== 'create') ? 'active' : '' ?>">
                <i class="uil uil-ticket"></i>
                <span>Daftar Tiket</span>
            </a>
            
            <a href="/dashboard/tickets/create" class="<?= ($seg_2 === 'tickets' && $seg_3 === 'create') ? 'active' : '' ?>">
                <i class="uil uil-plus-circle"></i>
                <span>Buat Tiket</span>
            </a>
            
            <a href="/dashboard/notifications" class="<?= ($seg_2 === 'notifications') ? 'active' : '' ?>">
                <i class="uil uil-bell"></i>
                <span>Notifikasi</span>
            </a>
            
            <a href="/dashboard/ratings" class="<?= ($seg_2 === 'ratings') ? 'active' : '' ?>">
                <i class="uil uil-star"></i>
                <span>Rating</span>
            </a>
            
            <a href="/dashboard/reports/sla" class="<?= ($seg_2 === 'reports') ? 'active' : '' ?>">
                <i class="uil uil-chart-growth"></i>
                <span>Laporan KPI/SLA</span>
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
                    <i class="uil uil-sign-out-alt"></i>
                    Logout
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
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

        /* --- TEMPLATE GLOBAL UNTUK KOMPONEN ISI --- */
        /* Komponen di bawah ini otomatis ter-style modern jika dipanggil di dalam renderSection */
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
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.02);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(15, 23, 42, 0.05);
        }

        .card h4 {
            color: #64748b;
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .card .number {
            margin-top: 12px;
            font-size: 32px;
            font-weight: 700;
            color: #0f172a;
            letter-spacing: -0.5px;
        }

        /* Desain Tabel Modern */
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
            <!-- Gunakan class "active" secara dinamis pada menu yang aktif -->
            <a href="/dashboard" class="active"><i class="uil uil-apps"></i> <span>Dashboard</span></a>
            <a href="/dashboard/users"><i class="uil uil-users-alt"></i> <span>Manajemen User</span></a>
            <a href="/dashboard/tickets"><i class="uil uil-ticket"></i> <span>Daftar Tiket</span></a>
            <a href="/dashboard/tickets/create"><i class="uil uil-plus-circle"></i> <span>Buat Tiket</span></a>
            <a href="/dashboard/notifications"><i class="uil uil-bell"></i> <span>Notifikasi</span></a>
            <a href="/dashboard/ratings"><i class="uil uil-star"></i> <span>Rating</span></a>
            <a href="/dashboard/reports/sla"><i class="uil uil-chart-growth"></i> <span>Laporan KPI/SLA</span></a>
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

        <!-- MAIN SECTION (Dinamis dari view CodeIgniter Anda) -->
        <div class="main">
            <?= $this->renderSection('content') ?>
        </div>
    </main>

</div>

</body>
</html>
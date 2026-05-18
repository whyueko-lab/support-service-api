<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'Support Service Dashboard' ?></title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 0;
            color: #111827;
        }

        .wrapper {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background: #1f2937;
            color: white;
            padding: 22px 18px;
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
        }

        .sidebar h2 {
            font-size: 20px;
            margin: 0 0 8px;
        }

        .sidebar p {
            font-size: 13px;
            color: #d1d5db;
            margin: 0 0 25px;
        }

        .menu {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .menu a {
            color: #e5e7eb;
            text-decoration: none;
            padding: 11px 12px;
            border-radius: 8px;
            font-size: 14px;
            display: block;
        }

        .menu a:hover,
        .menu a.active {
            background: #374151;
            color: white;
        }

        .content {
            margin-left: 250px;
            width: calc(100% - 250px);
        }

        .topbar {
            background: white;
            padding: 18px 30px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .topbar h3 {
            margin: 0;
            font-size: 22px;
        }

        .topbar small {
            color: #6b7280;
        }

        .main {
            padding: 30px;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
            margin-bottom: 30px;
        }

        .card {
            background: white;
            border-radius: 12px;
            padding: 22px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
        }

        .card h4 {
            margin: 0;
            color: #6b7280;
            font-size: 14px;
        }

        .card .number {
            margin-top: 10px;
            font-size: 30px;
            font-weight: bold;
            color: #111827;
        }

        .section {
            background: white;
            border-radius: 12px;
            padding: 22px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.08);
            margin-bottom: 25px;
            overflow-x: auto;
        }

        .section h3 {
            margin-top: 0;
            color: #111827;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 900px;
        }

        table th {
            background: #f3f4f6;
            text-align: left;
            padding: 12px;
            color: #374151;
            font-size: 14px;
        }

        table td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 14px;
            vertical-align: top;
        }

        .btn {
            display: inline-block;
            padding: 9px 14px;
            border-radius: 8px;
            background: #1f2937;
            color: white;
            text-decoration: none;
            border: none;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-secondary {
            background: #6b7280;
        }

        .btn-small {
            padding: 6px 10px;
            font-size: 13px;
        }

        .badge {
            padding: 5px 9px;
            border-radius: 20px;
            font-size: 12px;
            display: inline-block;
            background: #e5e7eb;
        }

        .high, .overdue, .unread, .bad {
            background: #fee2e2;
            color: #991b1b;
        }

        .medium, .warning {
            background: #fef3c7;
            color: #92400e;
        }

        .low, .done, .read, .good {
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

        select,
        textarea,
        input {
            width: 100%;
            padding: 11px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
        }

        textarea {
            min-height: 140px;
            resize: vertical;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #374151;
            font-weight: bold;
        }

        .actions {
            display: flex;
            gap: 10px;
        }

        .info {
            background: #f3f4f6;
            padding: 14px;
            border-radius: 8px;
            color: #374151;
            margin-bottom: 20px;
            font-size: 14px;
            line-height: 1.6;
        }

        @media (max-width: 900px) {
            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
            }

            .wrapper {
                flex-direction: column;
            }

            .content {
                margin-left: 0;
                width: 100%;
            }

            .cards {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 600px) {
            .cards {
                grid-template-columns: 1fr;
            }

            .main {
                padding: 15px;
            }

            .topbar {
                padding: 15px;
            }
        }
    </style>
</head>
<body>

<div class="wrapper">

    <aside class="sidebar">
        <h2>Support Service</h2>
        <p>Android Ticketing System</p>

        <nav class="menu">
            <a href="/dashboard">Dashboard</a>
            <a href="/dashboard/tickets">Daftar Tiket</a>
            <a href="/dashboard/tickets/create">Buat Tiket</a>
            <a href="/dashboard/notifications">Notifikasi</a>
            <a href="/dashboard/ratings">Rating</a>
            <a href="/dashboard/reports/sla">Laporan KPI/SLA</a>
        </nav>
    </aside>

    <main class="content">
        <div class="topbar">
            <div>
                <h3><?= $pageTitle ?? 'Dashboard' ?></h3>
                <small><?= $pageSubtitle ?? 'Monitoring sistem support service' ?></small>
                <small><?= $pageSubtitle ?? 'By WES' ?></small>
            </div>
        </div>

        <div class="main">
            <?= $this->renderSection('content') ?>
        </div>
    </main>

</div>

</body>
</html>
<?php

namespace App\Controllers;

use App\Models\TicketModel;
use App\Models\UserModel;
use App\Models\RatingModel;
use App\Models\NotificationModel;
use App\Helpers\ModelManager;
use App\Helpers\NaiveBayesClassifier;
use Dompdf\Dompdf;
use Dompdf\Options;

class Dashboard extends BaseController
{
    private function updateOverdueTickets()
    {
        date_default_timezone_set('Asia/Jakarta');

        $ticketModel = new TicketModel();

        $now = date('Y-m-d H:i:s');

        $ticketModel
            ->whereIn('status', ['open', 'in_progress'])
            ->where('tanggal_selesai', null)
            ->where('deadline <', $now)
            ->set([
                'status' => 'overdue',
                'updated_at' => $now
            ])
            ->update();
    }

    private function getNotificationBadgeData()
    {
        $notificationModel = new NotificationModel();

        return [
            'totalNotifikasi' => $notificationModel->countAllResults(),

            // Semua notifikasi yang belum dibaca
            'notifikasiBelumDibaca' => $notificationModel
                ->where('status_baca', 0)
                ->countAllResults(),

            // Khusus notifikasi tiket baru
            'notifikasiTiketBaru' => $notificationModel
                ->where('status_baca', 0)
                ->where('tipe_notifikasi', 'tiket_baru')
                ->countAllResults()
        ];
    }

    public function index()
    {
        date_default_timezone_set('Asia/Jakarta');

        $this->updateOverdueTickets();

        $check = $this->checkAdmin();
        if ($check) {
            return $check;
        }

        $ticketModel = new TicketModel();
        $userModel   = new UserModel();
        $ratingModel = new RatingModel();

        $totalTiket = $ticketModel->countAllResults();

        $open = $ticketModel
            ->where('status', 'open')
            ->countAllResults();

        $inProgress = $ticketModel
            ->where('status', 'in_progress')
            ->countAllResults();

        $done = $ticketModel
            ->where('status', 'done')
            ->countAllResults();

        $overdue = $ticketModel
            ->where('status', 'overdue')
            ->countAllResults();

        $totalCustomer = $userModel
            ->where('role', 'customer')
            ->countAllResults();

        $totalTeknisi = $userModel
            ->where('role', 'teknisi')
            ->countAllResults();

        $ratings = $ratingModel->findAll();

        $totalRating = count($ratings);
        $totalNilai = 0;

        foreach ($ratings as $rating) {
            $totalNilai += $rating['nilai_rating'];
        }

        $rataRataRating = $totalRating > 0 ? $totalNilai / $totalRating : 0;
        $kepuasanPersen = ($rataRataRating / 5) * 100;

        $teknisi = $userModel
            ->where('role', 'teknisi')
            ->findAll();

        $bebanTeknisi = [];

        foreach ($teknisi as $t) {
            $jumlahAktif = $ticketModel
                ->where('id_teknisi', $t['id_user'])
                ->whereIn('status', ['open', 'in_progress'])
                ->countAllResults();

            $jumlahSelesai = $ticketModel
                ->where('id_teknisi', $t['id_user'])
                ->where('status', 'done')
                ->countAllResults();

            $bebanTeknisi[] = [
                'nama_teknisi' => $t['nama'],
                'tiket_aktif' => $jumlahAktif,
                'tiket_selesai' => $jumlahSelesai
            ];
        }

        $data = [
            'title' => 'Dashboard Admin',
            'pageTitle' => 'Dashboard Admin',
            'pageSubtitle' => 'Monitoring tiket, SLA, teknisi, dan kepuasan pelanggan',

            'totalTiket' => $totalTiket,
            'open' => $open,
            'inProgress' => $inProgress,
            'done' => $done,
            'overdue' => $overdue,
            'totalCustomer' => $totalCustomer,
            'totalTeknisi' => $totalTeknisi,
            'totalRating' => $totalRating,
            'rataRataRating' => round($rataRataRating, 2),
            'kepuasanPersen' => round($kepuasanPersen, 2),
            'bebanTeknisi' => $bebanTeknisi
        ];

        $data = array_merge($data, $this->getNotificationBadgeData());

        return view('dashboard/index', $data);
    }

    public function tickets()
    {
        date_default_timezone_set('Asia/Jakarta');

        $this->updateOverdueTickets();

        $check = $this->checkAdmin();
        if ($check) {
            return $check;
        }

        $ticketModel = new TicketModel();

        $keyword   = $this->request->getGet('keyword');
        $status    = $this->request->getGet('status');
        $prioritas = $this->request->getGet('prioritas');

        $query = $ticketModel
            ->select('
                tickets.*,
                customer.nama AS nama_customer,
                teknisi.nama AS nama_teknisi
            ')
            ->join('users AS customer', 'customer.id_user = tickets.id_user')
            ->join('users AS teknisi', 'teknisi.id_user = tickets.id_teknisi', 'left');

        if (!empty($keyword)) {
            $query->groupStart()
                ->like('tickets.deskripsi', $keyword)
                ->orLike('tickets.kategori', $keyword)
                ->orLike('customer.nama', $keyword)
                ->orLike('teknisi.nama', $keyword)
                ->groupEnd();
        }

        if (!empty($status)) {
            $query->where('tickets.status', $status);
        }

        if (!empty($prioritas)) {
            $query->where('tickets.prioritas', $prioritas);
        }

        $tickets = $query
            ->orderBy('tickets.id_tiket', 'DESC')
            ->findAll();

        $data = [
            'title' => 'Daftar Tiket',
            'pageTitle' => 'Daftar Tiket',
            'pageSubtitle' => 'Monitoring seluruh tiket layanan pelanggan',
            'tickets' => $tickets,
            'keyword' => $keyword,
            'status' => $status,
            'prioritas' => $prioritas
        ];

        $data = array_merge($data, $this->getNotificationBadgeData());

        return view('dashboard/tickets', $data);
    }

    public function updateTicketStatus($id_tiket)
    {
        date_default_timezone_set('Asia/Jakarta');

        $ticketModel = new TicketModel();
        $db = \Config\Database::connect();

        $check = $this->checkAdmin();
        if ($check) {
            return $check;
        }

        $status = $this->request->getPost('status');

        // Overdue tidak boleh dipilih manual.
        // Overdue hanya boleh diubah otomatis oleh sistem.
        $allowedStatus = ['open', 'in_progress', 'done'];

        if (!in_array($status, $allowedStatus)) {
            return redirect()->to('/dashboard/tickets');
        }

        $ticket = $ticketModel->find($id_tiket);

        if (!$ticket) {
            return redirect()->to('/dashboard/tickets');
        }

        $dataUpdate = [
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($status === 'done') {
            $dataUpdate['tanggal_selesai'] = date('Y-m-d H:i:s');
        }

        // Jika tiket dikembalikan ke open / in_progress, tanggal_selesai dikosongkan lagi.
        if ($status === 'open' || $status === 'in_progress') {
            $dataUpdate['tanggal_selesai'] = null;
        }

        $ticketModel->update($id_tiket, $dataUpdate);

        $db->table('notifications')->insert([
            'id_user'          => $ticket['id_user'],
            'id_tiket'         => $id_tiket,
            'pesan'            => 'Status tiket #' . $id_tiket . ' diperbarui menjadi ' . strtoupper($status),
            'tipe_notifikasi'  => 'update_status',
            'waktu'            => date('Y-m-d H:i:s'),
            'status_baca'      => 0
        ]);

        return redirect()->to('/dashboard/tickets');
    }

    public function createTicketForm()
    {
        $check = $this->checkAdmin();
        if ($check) {
            return $check;
        }

        $userModel = new UserModel();

        $customers = $userModel
            ->where('role', 'customer')
            ->where('status_user', 'aktif')
            ->findAll();

        $data = [
            'title' => 'Buat Tiket',
            'pageTitle' => 'Buat Tiket Baru',
            'pageSubtitle' => 'Input keluhan pelanggan dengan klasifikasi otomatis',
            'customers' => $customers
        ];

        $data = array_merge($data, $this->getNotificationBadgeData());

        return view('dashboard/create_ticket', $data);
    }

    public function storeTicket()
    {
        date_default_timezone_set('Asia/Jakarta');

        helper('naive_bayes');

        $check = $this->checkAdmin();
        if ($check) {
            return $check;
        }

        $ticketModel = new TicketModel();
        $userModel   = new UserModel();
        $db          = \Config\Database::connect();

        $id_user   = $this->request->getPost('id_user');
        $deskripsi = $this->request->getPost('deskripsi');

        if (!$id_user || !$deskripsi) {
            return redirect()->to('/dashboard/tickets/create');
        }

        $modelManager = new ModelManager();
$model = $modelManager->load();

$classifier = new NaiveBayesClassifier($model);

$hasilKlasifikasi = $classifier->predict($deskripsi);

        $kategori  = $hasilKlasifikasi['kategori'];
        $prioritas = $hasilKlasifikasi['prioritas'];
        $score     = $hasilKlasifikasi['score'];

        $kpi = $db->table('kpi_sla')
            ->where('prioritas', $prioritas)
            ->get()
            ->getRowArray();

        $id_kpi   = $kpi ? $kpi['id_kpi'] : null;
        $tat_hari = $kpi ? $kpi['tat_hari'] : 3;

        $tanggal_masuk = date('Y-m-d H:i:s');
        $deadline      = date('Y-m-d H:i:s', strtotime("+$tat_hari days"));

        $teknisi = $userModel
            ->where('role', 'teknisi')
            ->where('status_user', 'aktif')
            ->findAll();

        $id_teknisi_terpilih = null;
        $beban_terendah = PHP_INT_MAX;

        foreach ($teknisi as $t) {
            $jumlahTiketAktif = $ticketModel
                ->where('id_teknisi', $t['id_user'])
                ->whereIn('status', ['open', 'in_progress'])
                ->countAllResults();

            if ($jumlahTiketAktif < $beban_terendah) {
                $beban_terendah = $jumlahTiketAktif;
                $id_teknisi_terpilih = $t['id_user'];
            }
        }

        $data = [
            'id_user'        => $id_user,
            'id_teknisi'    => $id_teknisi_terpilih,
            'id_kpi'        => $id_kpi,
            'tanggal_masuk' => $tanggal_masuk,
            'deadline'      => $deadline,
            'status'        => 'open',
            'prioritas'     => $prioritas,
            'kategori'      => $kategori,
            'deskripsi'     => $deskripsi,
            'score'         => $score,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s')
        ];

        $ticketModel->insert($data);
        $id_tiket = $ticketModel->insertID();

        $db->table('notifications')->insert([
            'id_user'          => $id_user,
            'id_tiket'         => $id_tiket,
            'pesan'            => 'Tiket baru berhasil dibuat dengan prioritas ' . strtoupper($prioritas),
            'tipe_notifikasi'  => 'tiket_baru',
            'waktu'            => date('Y-m-d H:i:s'),
            'status_baca'      => 0
        ]);

        if ($id_teknisi_terpilih) {
        $db->table('notifications')->insert([
            'id_user'          => $id_teknisi_terpilih,
            'id_tiket'         => $id_tiket,
            'pesan'            => 'Anda mendapatkan tiket baru dengan prioritas ' . strtoupper($prioritas),
            'tipe_notifikasi'  => 'tiket_baru',
            'waktu'            => date('Y-m-d H:i:s'),
            'status_baca'      => 0
        ]);
    }

        return redirect()->to('/dashboard/tickets');
    }

    public function ticketDetail($id_tiket)
    {
        $this->updateOverdueTickets();

        $check = $this->checkAdmin();
        if ($check) {
            return $check;
        }

        $ticketModel = new TicketModel();
        $ratingModel = new RatingModel();
        $db = \Config\Database::connect();

        $ticket = $ticketModel
            ->select('
                tickets.*,
                customer.nama AS nama_customer,
                customer.email AS email_customer,
                teknisi.nama AS nama_teknisi,
                teknisi.email AS email_teknisi
            ')
            ->join('users AS customer', 'customer.id_user = tickets.id_user')
            ->join('users AS teknisi', 'teknisi.id_user = tickets.id_teknisi', 'left')
            ->where('tickets.id_tiket', $id_tiket)
            ->first();

        if (!$ticket) {
            return redirect()->to('/dashboard/tickets');
        }

        $rating = $ratingModel
            ->where('id_tiket', $id_tiket)
            ->first();

        $notifications = $db->table('notifications')
            ->where('id_tiket', $id_tiket)
            ->orderBy('id_notifikasi', 'DESC')
            ->get()
            ->getResultArray();

        $data = [
            'title' => 'Detail Tiket',
            'pageTitle' => 'Detail Tiket #' . $id_tiket,
            'pageSubtitle' => 'Informasi lengkap tiket, SLA, notifikasi, dan rating',
            'ticket' => $ticket,
            'rating' => $rating,
            'notifications' => $notifications
        ];

        $data = array_merge($data, $this->getNotificationBadgeData());

        return view('dashboard/ticket_detail', $data);
    }

    public function notifications()
    {
        $check = $this->checkAdmin();
        if ($check) {
            return $check;
        }

        $db = \Config\Database::connect();

        $notifications = $db->table('notifications')
            ->select('
                notifications.*,
                users.nama AS nama_user,
                users.email AS email_user,
                users.role AS role_user,
                tickets.status AS status_tiket,
                tickets.prioritas AS prioritas_tiket
            ')
            ->join('users', 'users.id_user = notifications.id_user')
            ->join('tickets', 'tickets.id_tiket = notifications.id_tiket', 'left')
            ->orderBy('notifications.id_notifikasi', 'DESC')
            ->get()
            ->getResultArray();

        $data = [
            'title' => 'Notifikasi',
            'pageTitle' => 'Daftar Notifikasi',
            'pageSubtitle' => 'Monitoring seluruh notifikasi customer dan teknisi',
            'notifications' => $notifications
        ];

        $data = array_merge($data, $this->getNotificationBadgeData());

        return view('dashboard/notifications', $data);
    }

    public function ratings()
    {
        $check = $this->checkAdmin();
        if ($check) {
            return $check;
        }

        $ratingModel = new RatingModel();

        $ratings = $ratingModel
            ->select('
                ratings.*,
                tickets.deskripsi,
                tickets.prioritas,
                tickets.status,
                users.nama AS nama_customer,
                users.email AS email_customer
            ')
            ->join('tickets', 'tickets.id_tiket = ratings.id_tiket')
            ->join('users', 'users.id_user = tickets.id_user')
            ->orderBy('ratings.id_rating', 'DESC')
            ->findAll();

        $data = [
            'title' => 'Rating Pelanggan',
            'pageTitle' => 'Rating dan Feedback Pelanggan',
            'pageSubtitle' => 'Evaluasi kepuasan pelanggan terhadap layanan support service',
            'ratings' => $ratings
        ];

        $data = array_merge($data, $this->getNotificationBadgeData());

        return view('dashboard/ratings', $data);
    }

    public function slaReport()
    {
        date_default_timezone_set('Asia/Jakarta');

        $this->updateOverdueTickets();

        $check = $this->checkAdmin();
        if ($check) {
            return $check;
        }

        $userModel   = new UserModel();
        $ratingModel = new RatingModel();

        $startDate = $this->request->getGet('start_date');
        $endDate   = $this->request->getGet('end_date');

        $applyDateFilter = function ($query) use ($startDate, $endDate) {
            if (!empty($startDate)) {
                $query->where('tanggal_masuk >=', $startDate . ' 00:00:00');
            }

            if (!empty($endDate)) {
                $query->where('tanggal_masuk <=', $endDate . ' 23:59:59');
            }

            return $query;
        };

        $totalTiket = $applyDateFilter(new TicketModel())
            ->countAllResults();

        $totalDone = $applyDateFilter(new TicketModel())
            ->where('status', 'done')
            ->countAllResults();

        $overdueAktif = $applyDateFilter(new TicketModel())
            ->where('status', 'overdue')
            ->countAllResults();

        $onTime = $applyDateFilter(new TicketModel())
            ->where('status', 'done')
            ->where('tanggal_selesai <= deadline')
            ->countAllResults();

        $lateDone = $applyDateFilter(new TicketModel())
            ->where('status', 'done')
            ->where('tanggal_selesai > deadline')
            ->countAllResults();

        // Total overdue laporan = tiket masih overdue + tiket selesai melewati deadline.
        $totalOverdue = $overdueAktif + $lateDone;

        $persenOnTime = $totalDone > 0 ? ($onTime / $totalDone) * 100 : 0;
        $persenLate   = $totalDone > 0 ? ($lateDone / $totalDone) * 100 : 0;

        $ratingQuery = $ratingModel
            ->select('ratings.*')
            ->join('tickets', 'tickets.id_tiket = ratings.id_tiket');

        if (!empty($startDate)) {
            $ratingQuery->where('tickets.tanggal_masuk >=', $startDate . ' 00:00:00');
        }

        if (!empty($endDate)) {
            $ratingQuery->where('tickets.tanggal_masuk <=', $endDate . ' 23:59:59');
        }

        $ratings = $ratingQuery->findAll();

        $totalRating = count($ratings);
        $totalNilai = 0;

        foreach ($ratings as $rating) {
            $totalNilai += $rating['nilai_rating'];
        }

        $rataRataRating = $totalRating > 0 ? $totalNilai / $totalRating : 0;
        $kepuasanPersen = ($rataRataRating / 5) * 100;

        $teknisi = $userModel
            ->where('role', 'teknisi')
            ->findAll();

        $performaTeknisi = [];

        foreach ($teknisi as $t) {
            $idTeknisi = $t['id_user'];

            $totalTiketTeknisiQuery = new TicketModel();
            $totalTiketTeknisiQuery->where('id_teknisi', $idTeknisi);
            $totalTiketTeknisi = $applyDateFilter($totalTiketTeknisiQuery)->countAllResults();

            $doneTeknisiQuery = new TicketModel();
            $doneTeknisiQuery
                ->where('id_teknisi', $idTeknisi)
                ->where('status', 'done');
            $doneTeknisi = $applyDateFilter($doneTeknisiQuery)->countAllResults();

            $overdueAktifTeknisiQuery = new TicketModel();
            $overdueAktifTeknisiQuery
                ->where('id_teknisi', $idTeknisi)
                ->where('status', 'overdue');
            $overdueAktifTeknisi = $applyDateFilter($overdueAktifTeknisiQuery)->countAllResults();

            $lateDoneTeknisiQuery = new TicketModel();
            $lateDoneTeknisiQuery
                ->where('id_teknisi', $idTeknisi)
                ->where('status', 'done')
                ->where('tanggal_selesai > deadline');
            $lateDoneTeknisi = $applyDateFilter($lateDoneTeknisiQuery)->countAllResults();

            $overdueTeknisi = $overdueAktifTeknisi + $lateDoneTeknisi;

            $onTimeTeknisiQuery = new TicketModel();
            $onTimeTeknisiQuery
                ->where('id_teknisi', $idTeknisi)
                ->where('status', 'done')
                ->where('tanggal_selesai <= deadline');
            $onTimeTeknisi = $applyDateFilter($onTimeTeknisiQuery)->countAllResults();

            $persenOnTimeTeknisi = $doneTeknisi > 0 ? ($onTimeTeknisi / $doneTeknisi) * 100 : 0;

            $performaTeknisi[] = [
                'id_teknisi' => $idTeknisi,
                'nama_teknisi' => $t['nama'],
                'email' => $t['email'],
                'total_tiket' => $totalTiketTeknisi,
                'done' => $doneTeknisi,
                'overdue' => $overdueTeknisi,
                'on_time' => $onTimeTeknisi,
                'persen_on_time' => round($persenOnTimeTeknisi, 2)
            ];
        }

        $data = [
            'title' => 'Laporan KPI/SLA',
            'pageTitle' => 'Laporan KPI/SLA',
            'pageSubtitle' => 'Evaluasi performa layanan berdasarkan periode tanggal',

            'startDate' => $startDate,
            'endDate' => $endDate,

            'totalTiket' => $totalTiket,
            'totalDone' => $totalDone,
            'totalOverdue' => $totalOverdue,
            'onTime' => $onTime,
            'lateDone' => $lateDone,
            'persenOnTime' => round($persenOnTime, 2),
            'persenLate' => round($persenLate, 2),
            'totalRating' => $totalRating,
            'rataRataRating' => round($rataRataRating, 2),
            'kepuasanPersen' => round($kepuasanPersen, 2),
            'performaTeknisi' => $performaTeknisi
        ];

        $data = array_merge($data, $this->getNotificationBadgeData());

        return view('dashboard/sla_report', $data);
    }

    public function downloadSlaReportPdf()
    {
        date_default_timezone_set('Asia/Jakarta');

        $this->updateOverdueTickets();

        $check = $this->checkAdmin();
        if ($check) {
            return $check;
        }

        $userModel   = new UserModel();
        $ratingModel = new RatingModel();

        $startDate = $this->request->getGet('start_date');
        $endDate   = $this->request->getGet('end_date');

        $applyDateFilter = function ($query) use ($startDate, $endDate) {
            if (!empty($startDate)) {
                $query->where('tanggal_masuk >=', $startDate . ' 00:00:00');
            }

            if (!empty($endDate)) {
                $query->where('tanggal_masuk <=', $endDate . ' 23:59:59');
            }

            return $query;
        };

        $totalTiket = $applyDateFilter(new TicketModel())
            ->countAllResults();

        $totalDone = $applyDateFilter(new TicketModel())
            ->where('status', 'done')
            ->countAllResults();

        $overdueAktif = $applyDateFilter(new TicketModel())
            ->where('status', 'overdue')
            ->countAllResults();

        $onTime = $applyDateFilter(new TicketModel())
            ->where('status', 'done')
            ->where('tanggal_selesai <= deadline')
            ->countAllResults();

        $lateDone = $applyDateFilter(new TicketModel())
            ->where('status', 'done')
            ->where('tanggal_selesai > deadline')
            ->countAllResults();

        // Total overdue laporan = tiket masih overdue + tiket selesai melewati deadline.
        $totalOverdue = $overdueAktif + $lateDone;

        $persenOnTime = $totalDone > 0 ? ($onTime / $totalDone) * 100 : 0;
        $persenLate   = $totalDone > 0 ? ($lateDone / $totalDone) * 100 : 0;

        $ratingQuery = $ratingModel
            ->select('ratings.*')
            ->join('tickets', 'tickets.id_tiket = ratings.id_tiket');

        if (!empty($startDate)) {
            $ratingQuery->where('tickets.tanggal_masuk >=', $startDate . ' 00:00:00');
        }

        if (!empty($endDate)) {
            $ratingQuery->where('tickets.tanggal_masuk <=', $endDate . ' 23:59:59');
        }

        $ratings = $ratingQuery->findAll();

        $totalRating = count($ratings);
        $totalNilai = 0;

        foreach ($ratings as $rating) {
            $totalNilai += $rating['nilai_rating'];
        }

        $rataRataRating = $totalRating > 0 ? $totalNilai / $totalRating : 0;
        $kepuasanPersen = ($rataRataRating / 5) * 100;

        $teknisi = $userModel
            ->where('role', 'teknisi')
            ->findAll();

        $performaTeknisi = [];

        foreach ($teknisi as $t) {
            $idTeknisi = $t['id_user'];

            $totalTiketTeknisiQuery = new TicketModel();
            $totalTiketTeknisiQuery->where('id_teknisi', $idTeknisi);
            $totalTiketTeknisi = $applyDateFilter($totalTiketTeknisiQuery)->countAllResults();

            $doneTeknisiQuery = new TicketModel();
            $doneTeknisiQuery
                ->where('id_teknisi', $idTeknisi)
                ->where('status', 'done');
            $doneTeknisi = $applyDateFilter($doneTeknisiQuery)->countAllResults();

            $overdueAktifTeknisiQuery = new TicketModel();
            $overdueAktifTeknisiQuery
                ->where('id_teknisi', $idTeknisi)
                ->where('status', 'overdue');
            $overdueAktifTeknisi = $applyDateFilter($overdueAktifTeknisiQuery)->countAllResults();

            $lateDoneTeknisiQuery = new TicketModel();
            $lateDoneTeknisiQuery
                ->where('id_teknisi', $idTeknisi)
                ->where('status', 'done')
                ->where('tanggal_selesai > deadline');
            $lateDoneTeknisi = $applyDateFilter($lateDoneTeknisiQuery)->countAllResults();

            $overdueTeknisi = $overdueAktifTeknisi + $lateDoneTeknisi;

            $onTimeTeknisiQuery = new TicketModel();
            $onTimeTeknisiQuery
                ->where('id_teknisi', $idTeknisi)
                ->where('status', 'done')
                ->where('tanggal_selesai <= deadline');
            $onTimeTeknisi = $applyDateFilter($onTimeTeknisiQuery)->countAllResults();

            $persenOnTimeTeknisi = $doneTeknisi > 0 ? ($onTimeTeknisi / $doneTeknisi) * 100 : 0;

            $performaTeknisi[] = [
                'nama_teknisi' => $t['nama'],
                'email' => $t['email'],
                'total_tiket' => $totalTiketTeknisi,
                'done' => $doneTeknisi,
                'on_time' => $onTimeTeknisi,
                'overdue' => $overdueTeknisi,
                'persen_on_time' => round($persenOnTimeTeknisi, 2)
            ];
        }

        $data = [
            'tanggalCetak' => date('d-m-Y H:i:s'),
            'totalTiket' => $totalTiket,
            'totalDone' => $totalDone,
            'totalOverdue' => $totalOverdue,
            'onTime' => $onTime,
            'lateDone' => $lateDone,
            'persenOnTime' => round($persenOnTime, 2),
            'persenLate' => round($persenLate, 2),
            'totalRating' => $totalRating,
            'rataRataRating' => round($rataRataRating, 2),
            'kepuasanPersen' => round($kepuasanPersen, 2),
            'performaTeknisi' => $performaTeknisi,
            'startDate' => $startDate,
            'endDate' => $endDate
        ];

        $html = view('dashboard/pdf_sla_report', $data);

        $options = new Options();
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $filename = 'Laporan_KPI_SLA_' . date('Ymd_His') . '.pdf';

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody($dompdf->output());
    }

    public function users()
    {
        $check = $this->checkAdmin();
        if ($check) {
            return $check;
        }

        $userModel = new UserModel();

        $users = $userModel
            ->orderBy('id_user', 'DESC')
            ->findAll();

        $data = [
            'title' => 'Manajemen User',
            'pageTitle' => 'Manajemen User',
            'pageSubtitle' => 'Kelola data customer, teknisi, dan admin',
            'users' => $users
        ];

        $data = array_merge($data, $this->getNotificationBadgeData());

        return view('dashboard/users', $data);
    }

    public function storeUser()
    {
        $check = $this->checkAdmin();
        if ($check) {
            return $check;
        }

        $userModel = new UserModel();

        $nama     = $this->request->getPost('nama');
        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $role     = $this->request->getPost('role');

        if (!$nama || !$email || !$password || !$role) {
            return redirect()->to('/dashboard/users/create');
        }

        $allowedRole = ['customer', 'admin', 'teknisi'];

        if (!in_array($role, $allowedRole)) {
            return redirect()->to('/dashboard/users/create');
        }

        $cekEmail = $userModel
            ->where('email', $email)
            ->first();

        if ($cekEmail) {
            return redirect()->to('/dashboard/users/create');
        }

        $userModel->insert([
            'nama' => $nama,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role' => $role,
            'status_user' => 'aktif',
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->to('/dashboard/users');
    }

    public function createUserForm()
    {
        $check = $this->checkAdmin();
        if ($check) {
            return $check;
        }

        $data = [
            'title' => 'Tambah User',
            'pageTitle' => 'Tambah User',
            'pageSubtitle' => 'Tambahkan customer, teknisi, atau admin baru'
        ];

        $data = array_merge($data, $this->getNotificationBadgeData());

        return view('dashboard/create_user', $data);
    }

    public function editUserForm($id_user)
    {
        $check = $this->checkAdmin();
        if ($check) {
            return $check;
        }

        $userModel = new UserModel();

        $user = $userModel->find($id_user);

        if (!$user) {
            return redirect()->to('/dashboard/users');
        }

        $data = [
            'title' => 'Edit User',
            'pageTitle' => 'Edit User',
            'pageSubtitle' => 'Perbarui data customer, teknisi, atau admin',
            'user' => $user
        ];

        $data = array_merge($data, $this->getNotificationBadgeData());

        return view('dashboard/edit_user', $data);
    }

    public function updateUser($id_user)
    {
        $check = $this->checkAdmin();
        if ($check) {
            return $check;
        }

        $userModel = new UserModel();

        $user = $userModel->find($id_user);

        if (!$user) {
            return redirect()->to('/dashboard/users');
        }

        $nama        = $this->request->getPost('nama');
        $email       = $this->request->getPost('email');
        $password    = $this->request->getPost('password');
        $role        = $this->request->getPost('role');
        $status_user = $this->request->getPost('status_user');

        if (!$nama || !$email || !$role || !$status_user) {
            return redirect()->to('/dashboard/users/edit/' . $id_user);
        }

        $allowedRole = ['customer', 'admin', 'teknisi'];
        $allowedStatus = ['aktif', 'nonaktif'];

        if (!in_array($role, $allowedRole)) {
            return redirect()->to('/dashboard/users/edit/' . $id_user);
        }

        if (!in_array($status_user, $allowedStatus)) {
            return redirect()->to('/dashboard/users/edit/' . $id_user);
        }

        $cekEmail = $userModel
            ->where('email', $email)
            ->where('id_user !=', $id_user)
            ->first();

        if ($cekEmail) {
            return redirect()->to('/dashboard/users/edit/' . $id_user);
        }

        $dataUpdate = [
            'nama' => $nama,
            'email' => $email,
            'role' => $role,
            'status_user' => $status_user
        ];

        if (!empty($password)) {
            $dataUpdate['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $userModel->update($id_user, $dataUpdate);

        return redirect()->to('/dashboard/users');
    }

    public function deleteUser($id_user)
    {
        $check = $this->checkAdmin();
        if ($check) {
            return $check;
        }

        $userModel = new UserModel();

        $user = $userModel->find($id_user);

        if (!$user) {
            return redirect()->to('/dashboard/users');
        }

        if ($id_user == session()->get('id_user')) {
            return redirect()->to('/dashboard/users');
        }

        $userModel->delete($id_user);

        return redirect()->to('/dashboard/users');
    }

    public function toggleUserStatus($id_user)
    {
        $check = $this->checkAdmin();
        if ($check) {
            return $check;
        }

        $userModel = new UserModel();

        $user = $userModel->find($id_user);

        if (!$user) {
            return redirect()->to('/dashboard/users');
        }

        if ($id_user == session()->get('id_user')) {
            return redirect()->to('/dashboard/users');
        }

        $statusSekarang = $user['status_user'] ?? 'aktif';
        $statusBaru = ($statusSekarang === 'aktif') ? 'nonaktif' : 'aktif';

        $userModel->update($id_user, [
            'status_user' => $statusBaru
        ]);

        return redirect()->to('/dashboard/users');
    }

    private function checkAdmin()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        if (session()->get('role') !== 'admin') {
            return redirect()->to('/login');
        }

        return null;
    }
}
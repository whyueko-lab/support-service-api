<?php

namespace App\Controllers;

use App\Models\TicketModel;
use App\Models\UserModel;
use App\Models\RatingModel;
use Dompdf\Dompdf;
use Dompdf\Options;

class Dashboard extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        $totalNotifikasi = $db->table('notifications')->countAllResults();

        $notifikasiBelumDibaca = $db->table('notifications')
            ->where('status_baca', 0)
            ->countAllResults();

        $check = $this->checkAdmin();
        if ($check) {
            return $check;
        }
            
        $ticketModel = new TicketModel();
        $userModel   = new UserModel();
        $ratingModel = new RatingModel();

        // Ringkasan tiket
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

        // Ringkasan user
        $totalCustomer = $userModel
            ->where('role', 'customer')
            ->countAllResults();

        $totalTeknisi = $userModel
            ->where('role', 'teknisi')
            ->countAllResults();

        // Rating
        $ratings = $ratingModel->findAll();

        $totalRating = count($ratings);
        $totalNilai = 0;

        foreach ($ratings as $rating) {
            $totalNilai += $rating['nilai_rating'];
        }

        $rataRataRating = $totalRating > 0 ? $totalNilai / $totalRating : 0;
        $kepuasanPersen = ($rataRataRating / 5) * 100;

        // Beban kerja teknisi
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
            'totalNotifikasi' => $totalNotifikasi,
            'notifikasiBelumDibaca' => $notifikasiBelumDibaca,
            'bebanTeknisi' => $bebanTeknisi
            
        ];

        return view('dashboard/index', $data);
    }

    public function tickets()
    {
        $check = $this->checkAdmin();
        if ($check) {
            return $check;
        }

        $ticketModel = new \App\Models\TicketModel();

        $tickets = $ticketModel
            ->select('
                tickets.*,
                customer.nama AS nama_customer,
                teknisi.nama AS nama_teknisi
            ')
            ->join('users AS customer', 'customer.id_user = tickets.id_user')
            ->join('users AS teknisi', 'teknisi.id_user = tickets.id_teknisi', 'left')
            ->orderBy('tickets.id_tiket', 'DESC')
            ->findAll();

        return view('dashboard/tickets', [
            'title' => 'Daftar Tiket',
            'pageTitle' => 'Daftar Tiket',
            'pageSubtitle' => 'Monitoring seluruh tiket layanan pelanggan',
            'tickets' => $tickets
        ]);
    }

    public function updateTicketStatus($id_tiket)
    {
        $ticketModel = new \App\Models\TicketModel();
        $db = \Config\Database::connect();

        $check = $this->checkAdmin();
        if ($check) {
            return $check;
        }

        $status = $this->request->getPost('status');

        $allowedStatus = ['open', 'in_progress', 'done', 'overdue'];

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

        $ticketModel->update($id_tiket, $dataUpdate);

        $db->table('notifications')->insert([
            'id_user' => $ticket['id_user'],
            'id_tiket' => $id_tiket,
            'pesan' => 'Status tiket #' . $id_tiket . ' diperbarui menjadi ' . strtoupper($status),
            'waktu' => date('Y-m-d H:i:s'),
            'status_baca' => 0
        ]);

        return redirect()->to('/dashboard/tickets');
    }

    public function createTicketForm()
    {
        $userModel = new \App\Models\UserModel();

        $check = $this->checkAdmin();
        if ($check) {
            return $check;
        }

        $customers = $userModel
            ->where('role', 'customer')
            ->findAll();

        return view('dashboard/create_ticket', [
            'title' => 'Buat Tiket',
            'pageTitle' => 'Buat Tiket Baru',
            'pageSubtitle' => 'Input keluhan pelanggan dengan klasifikasi otomatis',
            'customers' => $customers
        ]);
    }

    public function storeTicket()
    {
        helper('naive_bayes');
            
        $check = $this->checkAdmin();
        if ($check) {
            return $check;
        }

        $ticketModel = new \App\Models\TicketModel();
        $userModel   = new \App\Models\UserModel();
        $db          = \Config\Database::connect();

        $id_user   = $this->request->getPost('id_user');
        $deskripsi = $this->request->getPost('deskripsi');

        if (!$id_user || !$deskripsi) {
            return redirect()->to('/dashboard/tickets/create');
        }

        // =========================
        // 1. Klasifikasi Naive Bayes
        // =========================
        $hasilKlasifikasi = klasifikasiNaiveBayes($deskripsi);

        $kategori  = $hasilKlasifikasi['kategori'];
        $prioritas = $hasilKlasifikasi['prioritas'];
        $score     = $hasilKlasifikasi['score'];

        // =========================
        // 2. Ambil KPI/SLA
        // =========================
        $kpi = $db->table('kpi_sla')
            ->where('prioritas', $prioritas)
            ->get()
            ->getRowArray();

        $id_kpi   = $kpi ? $kpi['id_kpi'] : null;
        $tat_hari = $kpi ? $kpi['tat_hari'] : 3;

        $tanggal_masuk = date('Y-m-d H:i:s');
        $deadline      = date('Y-m-d H:i:s', strtotime("+$tat_hari days"));

        // =========================
        // 3. Load Balancing Teknisi
        // =========================
        $teknisi = $userModel
            ->where('role', 'teknisi')
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

        // =========================
        // 4. Simpan Tiket
        // =========================
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

        // =========================
        // 5. Notifikasi Customer
        // =========================
        $db->table('notifications')->insert([
            'id_user'     => $id_user,
            'id_tiket'    => $id_tiket,
            'pesan'       => 'Tiket berhasil dibuat dengan prioritas ' . strtoupper($prioritas),
            'waktu'       => date('Y-m-d H:i:s'),
            'status_baca' => 0
        ]);

        // =========================
        // 6. Notifikasi Teknisi
        // =========================
        if ($id_teknisi_terpilih) {
            $db->table('notifications')->insert([
                'id_user'     => $id_teknisi_terpilih,
                'id_tiket'    => $id_tiket,
                'pesan'       => 'Anda mendapatkan tiket baru dengan prioritas ' . strtoupper($prioritas),
                'waktu'       => date('Y-m-d H:i:s'),
                'status_baca' => 0
            ]);
        }

        return redirect()->to('/dashboard/tickets');
    }

    public function ticketDetail($id_tiket)
    {
        $check = $this->checkAdmin();
        if ($check) {
            return $check;
        }

        $ticketModel = new \App\Models\TicketModel();
        $ratingModel = new \App\Models\RatingModel();
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

        return view('dashboard/ticket_detail', [
            'title' => 'Detail Tiket',
            'pageTitle' => 'Detail Tiket #' . $id_tiket,
            'pageSubtitle' => 'Informasi lengkap tiket, SLA, notifikasi, dan rating',
            'ticket' => $ticket,
            'rating' => $rating,
            'notifications' => $notifications
        ]);
    }

    public function notifications()
    {
        $db = \Config\Database::connect();

        $check = $this->checkAdmin();
        if ($check) {
            return $check;
        }

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

        return view('dashboard/notifications', [
            'title' => 'Notifikasi',
            'pageTitle' => 'Daftar Notifikasi',
            'pageSubtitle' => 'Monitoring seluruh notifikasi customer dan teknisi',
            'notifications' => $notifications
        ]);
    }

    public function ratings()
    {
        $ratingModel = new \App\Models\RatingModel();

        $check = $this->checkAdmin();
        if ($check) {
            return $check;
        }

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

        return view('dashboard/ratings', [
            'title' => 'Rating Pelanggan',
            'pageTitle' => 'Rating dan Feedback Pelanggan',
            'pageSubtitle' => 'Evaluasi kepuasan pelanggan terhadap layanan support service',
            'ratings' => $ratings
        ]);
    }

    public function slaReport()
    {
        $check = $this->checkAdmin();
        if ($check) {
            return $check;
        }

        $ticketModel = new \App\Models\TicketModel();
        $userModel   = new \App\Models\UserModel();
        $ratingModel = new \App\Models\RatingModel();

        // =========================
        // DATA TIKET
        // =========================
        $totalTiket = $ticketModel->countAllResults();

        $totalDone = $ticketModel
            ->where('status', 'done')
            ->countAllResults();

        $totalOverdue = $ticketModel
            ->where('status', 'overdue')
            ->countAllResults();

        // Tiket selesai tepat waktu:
        // status done dan tanggal_selesai <= deadline
        $onTime = $ticketModel
            ->where('status', 'done')
            ->where('tanggal_selesai <= deadline')
            ->countAllResults();

        $lateDone = $ticketModel
            ->where('status', 'done')
            ->where('tanggal_selesai > deadline')
            ->countAllResults();

        $persenOnTime = $totalDone > 0 ? ($onTime / $totalDone) * 100 : 0;
        $persenLate   = $totalDone > 0 ? ($lateDone / $totalDone) * 100 : 0;

        // =========================
        // RATING / KEPUASAN
        // =========================
        $ratings = $ratingModel->findAll();

        $totalRating = count($ratings);
        $totalNilai = 0;

        foreach ($ratings as $rating) {
            $totalNilai += $rating['nilai_rating'];
        }

        $rataRataRating = $totalRating > 0 ? $totalNilai / $totalRating : 0;
        $kepuasanPersen = ($rataRataRating / 5) * 100;

        // =========================
        // PERFORMA TEKNISI
        // =========================
        $teknisi = $userModel
            ->where('role', 'teknisi')
            ->findAll();

        $performaTeknisi = [];

        foreach ($teknisi as $t) {
            $idTeknisi = $t['id_user'];

            $totalTiketTeknisi = $ticketModel
                ->where('id_teknisi', $idTeknisi)
                ->countAllResults();

            $doneTeknisi = $ticketModel
                ->where('id_teknisi', $idTeknisi)
                ->where('status', 'done')
                ->countAllResults();

            $overdueTeknisi = $ticketModel
                ->where('id_teknisi', $idTeknisi)
                ->where('status', 'overdue')
                ->countAllResults();

            $onTimeTeknisi = $ticketModel
                ->where('id_teknisi', $idTeknisi)
                ->where('status', 'done')
                ->where('tanggal_selesai <= deadline')
                ->countAllResults();

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

        return view('dashboard/sla_report', [
            'title' => 'Laporan KPI/SLA',
            'pageTitle' => 'Laporan KPI/SLA',
            'pageSubtitle' => 'Evaluasi performa layanan, SLA, overdue, dan kepuasan pelanggan',

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
        ]);
    }

    public function downloadSlaReportPdf()
    {
        $check = $this->checkAdmin();
        if ($check) {
            return $check;
        }

        $ticketModel = new \App\Models\TicketModel();
        $userModel   = new \App\Models\UserModel();
        $ratingModel = new \App\Models\RatingModel();

        $totalTiket = $ticketModel->countAllResults();

        $totalDone = $ticketModel
            ->where('status', 'done')
            ->countAllResults();

        $totalOverdue = $ticketModel
            ->where('status', 'overdue')
            ->countAllResults();

        $onTime = $ticketModel
            ->where('status', 'done')
            ->where('tanggal_selesai <= deadline')
            ->countAllResults();

        $lateDone = $ticketModel
            ->where('status', 'done')
            ->where('tanggal_selesai > deadline')
            ->countAllResults();

        $persenOnTime = $totalDone > 0 ? ($onTime / $totalDone) * 100 : 0;
        $persenLate   = $totalDone > 0 ? ($lateDone / $totalDone) * 100 : 0;

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

        $performaTeknisi = [];

        foreach ($teknisi as $t) {
            $idTeknisi = $t['id_user'];

            $totalTiketTeknisi = $ticketModel
                ->where('id_teknisi', $idTeknisi)
                ->countAllResults();

            $doneTeknisi = $ticketModel
                ->where('id_teknisi', $idTeknisi)
                ->where('status', 'done')
                ->countAllResults();

            $overdueTeknisi = $ticketModel
                ->where('id_teknisi', $idTeknisi)
                ->where('status', 'overdue')
                ->countAllResults();

            $onTimeTeknisi = $ticketModel
                ->where('id_teknisi', $idTeknisi)
                ->where('status', 'done')
                ->where('tanggal_selesai <= deadline')
                ->countAllResults();

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
            'performaTeknisi' => $performaTeknisi
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

        $userModel = new \App\Models\UserModel();

        $users = $userModel
            ->orderBy('id_user', 'DESC')
            ->findAll();

        return view('dashboard/users', [
            'title' => 'Manajemen User',
            'pageTitle' => 'Manajemen User',
            'pageSubtitle' => 'Kelola data customer, teknisi, dan admin',
            'users' => $users
        ]);
    }

    public function storeUser()
    {
        $check = $this->checkAdmin();
        if ($check) {
            return $check;
        }

        $userModel = new \App\Models\UserModel();

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

        return view('dashboard/create_user', [
            'title' => 'Tambah User',
            'pageTitle' => 'Tambah User',
            'pageSubtitle' => 'Tambahkan customer, teknisi, atau admin baru'
        ]);
    }

    public function editUserForm($id_user)
    {
        $check = $this->checkAdmin();
        if ($check) {
            return $check;
        }

        $userModel = new \App\Models\UserModel();

        $user = $userModel->find($id_user);

        if (!$user) {
            return redirect()->to('/dashboard/users');
        }

        return view('dashboard/edit_user', [
            'title' => 'Edit User',
            'pageTitle' => 'Edit User',
            'pageSubtitle' => 'Perbarui data customer, teknisi, atau admin',
            'user' => $user
        ]);
    }

    public function updateUser($id_user)
    {
        $check = $this->checkAdmin();
        if ($check) {
            return $check;
        }

        $userModel = new \App\Models\UserModel();

        $user = $userModel->find($id_user);
        
        if (!$user) {
            return redirect()->to('/dashboard/users');
        }

        $nama           = $this->request->getPost('nama');
        $email          = $this->request->getPost('email');
        $password       = $this->request->getPost('password');
        $role           = $this->request->getPost('role');
        $status_user    = $this->request->getPost('status_user');

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
            'role' => $role
        ];

        // Password boleh dikosongkan.
        // Kalau diisi, password akan diganti.
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

        $userModel = new \App\Models\UserModel();

        $user = $userModel->find($id_user);

        if (!$user) {
            return redirect()->to('/dashboard/users');
        }

        // Mencegah admin menghapus akun sendiri
        if ($id_user == session()->get('id_user')) {
            return redirect()->to('/dashboard/users');
        }

        $userModel->delete($id_user);

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
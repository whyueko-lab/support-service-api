<?php

namespace App\Controllers;

use App\Models\TicketModel;
use App\Models\UserModel;
use App\Models\RatingModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        $totalNotifikasi = $db->table('notifications')->countAllResults();

        $notifikasiBelumDibaca = $db->table('notifications')
            ->where('status_baca', 0)
            ->countAllResults();
            
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

        return view('dashboard/index', $data);
    }

    public function tickets()
    {
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
            'tickets' => $tickets
        ]);
    }

    public function updateTicketStatus($id_tiket)
    {
        $ticketModel = new \App\Models\TicketModel();
        $db = \Config\Database::connect();

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

        $customers = $userModel
            ->where('role', 'customer')
            ->findAll();

        return view('dashboard/create_ticket', [
            'customers' => $customers
        ]);
    }

    public function storeTicket()
    {
        helper('naive_bayes');

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
            'ticket' => $ticket,
            'rating' => $rating,
            'notifications' => $notifications
        ]);
    }

    public function notifications()
    {
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

        return view('dashboard/notifications', [
            'notifications' => $notifications
        ]);
    }
}
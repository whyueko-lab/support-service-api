<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\TicketModel;
use App\Models\UserModel;

class TicketController extends BaseController
{
    public function index()
    {
        $ticketModel = new TicketModel();

        $data = $ticketModel
            ->select('tickets.*, customer.nama AS nama_customer, teknisi.nama AS nama_teknisi')
            ->join('users AS customer', 'customer.id_user = tickets.id_user')
            ->join('users AS teknisi', 'teknisi.id_user = tickets.id_teknisi', 'left')
            ->orderBy('tickets.id_tiket', 'DESC')
            ->findAll();

        return $this->response->setJSON([
            'status' => true,
            'message' => 'Data tiket berhasil diambil',
            'data' => $data
        ]);
    }

    public function create()
    {
        // Memuat file naive_bayes_helper.php
        helper('naive_bayes');

        $ticketModel = new TicketModel();
        $userModel = new UserModel();
        $db = \Config\Database::connect();

        $id_user = $this->request->getPost('id_user');
        $deskripsi = $this->request->getPost('deskripsi');

        if (!$id_user || !$deskripsi) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'id_user dan deskripsi wajib diisi'
            ]);
        }

        // =======================================================
        // 1. Klasifikasi Naive Bayes Terintegrasi Fungsi Global Baru
        // =======================================================
        // Menggunakan tanda backslash (\) untuk menegaskan fungsi global helper
        $hasilKlasifikasi = \klasifikasiTiketOtomatis($deskripsi);

        $kategori  = $hasilKlasifikasi['kategori'];
        $prioritas = $hasilKlasifikasi['prioritas'];
        $score     = $hasilKlasifikasi['confidence']; // Disesuaikan dengan key 'confidence' dari output helper baru

        // =========================
        // 2. Ambil KPI/SLA berdasarkan prioritas
        // =========================
        $kpi = $db->table('kpi_sla')
            ->where('prioritas', $prioritas)
            ->get()
            ->getRowArray();

        $id_kpi = $kpi ? $kpi['id_kpi'] : null;
        $tat_hari = $kpi ? $kpi['tat_hari'] : 3;

        $tanggal_masuk = date('Y-m-d H:i:s');
        $deadline = date('Y-m-d H:i:s', strtotime("+$tat_hari days"));

        // =========================
        // 3. Load Balancing Teknisi
        // =========================
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

        // =========================
        // 4. Simpan tiket
        // =========================
        $data = [
            'id_user' => $id_user,
            'id_teknisi' => $id_teknisi_terpilih,
            'id_kpi' => $id_kpi,
            'deskripsi' => $deskripsi,
            'kategori' => $kategori,
            'prioritas' => $prioritas,
            'status' => 'open',
            'score' => $score,
            'tanggal_masuk' => $tanggal_masuk,
            'deadline' => $deadline
        ];

        $ticketModel->insert($data);

        $id_tiket = $ticketModel->insertID();

        // =========================
        // 5. Simpan notifikasi
        // =========================
        $db->table('notifications')->insert([
            'id_user' => $id_user,
            'id_tiket' => $id_tiket,
            'pesan' => 'Tiket berhasil dibuat dengan prioritas ' . strtoupper($prioritas),
            'waktu' => date('Y-m-d H:i:s'),
            'status_baca' => 0
        ]);

        if ($id_teknisi_terpilih) {
            $db->table('notifications')->insert([
                'id_user' => $id_teknisi_terpilih,
                'id_tiket' => $id_tiket,
                'pesan' => 'Anda mendapatkan tiket baru dengan prioritas ' . strtoupper($prioritas),
                'waktu' => date('Y-m-d H:i:s'),
                'status_baca' => 0
            ]);
        }

        return $this->response->setJSON([
            'status' => true,
            'message' => 'Tiket berhasil dibuat dan diklasifikasikan otomatis',
            'data' => [
                'id_tiket' => $id_tiket,
                'id_user' => $id_user,
                'id_teknisi' => $id_teknisi_terpilih,
                'kategori' => $kategori,
                'prioritas' => $prioritas,
                'score' => $score,
                'deadline' => $deadline,
                'status' => 'open',
                'deskripsi' => $deskripsi
            ]
        ]);
    }

    public function updateStatus($id_tiket)
    {
        $ticketModel = new \App\Models\TicketModel();
        $db = \Config\Database::connect();

        $status = $this->request->getPost('status');

        if (!$status) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Status wajib diisi'
            ]);
        }

        $allowedStatus = ['open', 'in_progress', 'done', 'overdue'];

        if (!in_array($status, $allowedStatus)) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Status tidak valid'
            ]);
        }

        $ticket = $ticketModel->find($id_tiket);

        if (!$ticket) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Tiket tidak ditemukan'
            ]);
        }

        // CEGAH STATUS DONE DIUBAH LAGI
        if ($ticket['status'] === 'done') {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Tiket sudah selesai dan tidak dapat diubah kembali'
            ]);
        }

        $dataUpdate = [
            'status' => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($status === 'done') {
            $dataUpdate['tanggal_selesai'] = date('Y-m-d H:i:s');
        }

        $ticketModel->update($id_tiket, $dataUpdate);

        // Notifikasi ke customer
        $db->table('notifications')->insert([
            'id_user' => $ticket['id_user'],
            'id_tiket' => $id_tiket,
            'pesan' => 'Status tiket #' . $id_tiket . ' berubah menjadi ' . strtoupper($status),
            'waktu' => date('Y-m-d H:i:s'),
            'status_baca' => 0
        ]);

        return $this->response->setJSON([
            'status' => true,
            'message' => 'Status tiket berhasil diperbarui',
            'data' => [
                'id_tiket' => $id_tiket,
                'status' => $status,
                'tanggal_selesai' => $dataUpdate['tanggal_selesai'] ?? null
            ]
        ]);
    }

    public function checkOverdue()
    {
        $ticketModel = new \App\Models\TicketModel();
        $db = \Config\Database::connect();

        $sekarang = date('Y-m-d H:i:s');

        $tickets = $ticketModel
            ->whereIn('status', ['open', 'in_progress'])
            ->where('deadline <', $sekarang)
            ->findAll();

        if (count($tickets) === 0) {
            return $this->response->setJSON([
                'status' => true,
                'message' => 'Tidak ada tiket overdue',
                'data' => []
            ]);
        }

        $dataOverdue = [];

        foreach ($tickets as $ticket) {
            $ticketModel->update($ticket['id_tiket'], [
                'status' => 'overdue',
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            // Notifikasi ke customer
            $db->table('notifications')->insert([
                'id_user' => $ticket['id_user'],
                'id_tiket' => $ticket['id_tiket'],
                'pesan' => 'Tiket #' . $ticket['id_tiket'] . ' melewati batas SLA dan berstatus OVERDUE',
                'waktu' => date('Y-m-d H:i:s'),
                'status_baca' => 0
            ]);

            // Notifikasi ke teknisi jika ada
            if (!empty($ticket['id_teknisi'])) {
                $db->table('notifications')->insert([
                    'id_user' => $ticket['id_teknisi'],
                    'id_tiket' => $ticket['id_tiket'],
                    'pesan' => 'Tiket #' . $ticket['id_tiket'] . ' melewati deadline SLA. Segera tindak lanjuti.',
                    'waktu' => date('Y-m-d H:i:s'),
                    'status_baca' => 0
                ]);
            }

            $dataOverdue[] = [
                'id_tiket' => $ticket['id_tiket'],
                'deadline' => $ticket['deadline'],
                'status_baru' => 'overdue'
            ];
        }

        return $this->response->setJSON([
            'status' => true,
            'message' => 'Pengecekan SLA selesai',
            'total_overdue' => count($dataOverdue),
            'data' => $dataOverdue
        ]);
    }

    public function byUser($id_user)
    {
        $ticketModel = new \App\Models\TicketModel();
        $userModel = new \App\Models\UserModel();

        $user = $userModel->find($id_user);

        if (!$user) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'User tidak ditemukan'
            ]);
        }

        $query = $ticketModel
            ->select('
                tickets.*,
                customer.nama AS nama_customer,
                teknisi.nama AS nama_teknisi
            ')
            ->join('users AS customer', 'customer.id_user = tickets.id_user')
            ->join('users AS teknisi', 'teknisi.id_user = tickets.id_teknisi', 'left');

        if ($user['role'] === 'customer') {
            $query->where('tickets.id_user', $id_user);
        } elseif ($user['role'] === 'teknisi') {
            $query->where('tickets.id_teknisi', $id_user);
        } elseif ($user['role'] === 'admin') {
            // Admin melihat semua tiket, jadi tidak perlu where tambahan
        } else {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Role user tidak valid'
            ]);
        }

        $data = $query
            ->orderBy('tickets.id_tiket', 'DESC')
            ->findAll();

        return $this->response->setJSON([
            'status' => true,
            'message' => 'Data tiket berdasarkan role berhasil diambil',
            'role' => $user['role'],
            'data' => $data
        ]);
    }

    public function byStatus($status)
    {
        $allowedStatus = ['open', 'in_progress', 'done', 'overdue'];

        if (!in_array($status, $allowedStatus)) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Status tidak valid'
            ]);
        }

        $ticketModel = new \App\Models\TicketModel();

        $data = $ticketModel
            ->select('
                tickets.*,
                customer.nama AS nama_customer,
                teknisi.nama AS nama_teknisi
            ')
            ->join('users AS customer', 'customer.id_user = tickets.id_user')
            ->join('users AS teknisi', 'teknisi.id_user = tickets.id_teknisi', 'left')
            ->where('tickets.status', $status)
            ->orderBy('tickets.id_tiket', 'DESC')
            ->findAll();

        return $this->response->setJSON([
            'status' => true,
            'message' => 'Data tiket berdasarkan status berhasil diambil',
            'status_filter' => $status,
            'data' => $data
        ]);
    }

    public function byPriority($prioritas)
    {
        $allowedPriority = ['high', 'medium', 'low'];

        if (!in_array($prioritas, $allowedPriority)) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Prioritas tidak valid'
            ]);
        }

        $ticketModel = new \App\Models\TicketModel();

        $data = $ticketModel
            ->select('
                tickets.*,
                customer.nama AS nama_customer,
                teknisi.nama AS nama_teknisi
            ')
            ->join('users AS customer', 'customer.id_user = tickets.id_user')
            ->join('users AS teknisi', 'teknisi.id_user = tickets.id_teknisi', 'left')
            ->where('tickets.prioritas', $prioritas)
            ->orderBy('tickets.id_tiket', 'DESC')
            ->findAll();

        return $this->response->setJSON([
            'status' => true,
            'message' => 'Data tiket berdasarkan prioritas berhasil diambil',
            'prioritas_filter' => $prioritas,
            'data' => $data
        ]);
    }

    public function show($id = null)
{
    // 1. Inisialisasi Model Tiket kamu
    $ticketModel = new \App\Models\TicketModel(); 

    // 2. Cari data tiket berdasarkan ID utama (Primary Key)
    $ticket = $ticketModel->find($id);

    // 3. Jika tiket tidak ditemukan di database
    if (!$ticket) {
        return $this->response->setJSON([
            'status'  => false,
            'message' => 'Tiket dengan ID ' . $id . ' tidak ditemukan.'
        ])->setStatusCode(404);
    }

    // 4. Jika ditemukan, kembalikan data spesifik tiket tersebut
    return $this->response->setJSON([
        'status'  => true,
        'message' => 'Detail tiket berhasil ditemukan',
        'data'    => $ticket
    ]);
}
}
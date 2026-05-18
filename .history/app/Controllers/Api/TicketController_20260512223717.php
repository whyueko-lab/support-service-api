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

        // =========================
        // 1. Klasifikasi Naive Bayes
        // =========================
        $hasilKlasifikasi = klasifikasiNaiveBayes($deskripsi);

        $kategori = $hasilKlasifikasi['kategori'];
        $prioritas = $hasilKlasifikasi['prioritas'];
        $score = $hasilKlasifikasi['score'];

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
}
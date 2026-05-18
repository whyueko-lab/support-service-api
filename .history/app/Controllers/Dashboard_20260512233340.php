<?php

namespace App\Controllers;

use App\Models\TicketModel;
use App\Models\UserModel;
use App\Models\RatingModel;

class Dashboard extends BaseController
{
    public function index()
    {
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
}
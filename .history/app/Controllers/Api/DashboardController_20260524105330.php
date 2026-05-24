<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\TicketModel;
use App\Models\UserModel;
use App\Models\RatingModel;

class DashboardController extends BaseController
{
    private function updateOverdueTickets()
    {
        $ticketModel = new TicketModel();

        $now = date('Y-m-d H:i:s');

        $ticketModel
            ->whereIn('status', ['open', 'in_progress'])
            ->where('tanggal_selesai, null)')
            ->where('deadline <', $now)
            ->set([
                'status' => 'overdue',
                'updated_at' => $now
            ])
            ->update();
    }

    public function admin()
    {
        $this->updateOverdueTickets();

        $ticketModel = new TicketModel();
        $userModel   = new UserModel();
        $ratingModel = new RatingModel();

        // =========================
        // JUMLAH TIKET
        // =========================
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

        // =========================
        // JUMLAH USER
        // =========================
        $totalCustomer = $userModel
            ->where('role', 'customer')
            ->countAllResults();

        $totalTeknisi = $userModel
            ->where('role', 'teknisi')
            ->countAllResults();

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
        // BEBAN KERJA TEKNISI
        // =========================
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
                'id_teknisi' => $t['id_user'],
                'nama_teknisi' => $t['nama'],
                'tiket_aktif' => $jumlahAktif,
                'tiket_selesai' => $jumlahSelesai
            ];
        }

        return $this->response->setJSON([
            'status' => true,
            'message' => 'Data dashboard admin berhasil diambil',
            'data' => [
                'ringkasan_tiket' => [
                    'total_tiket' => $totalTiket,
                    'open' => $open,
                    'in_progress' => $inProgress,
                    'done' => $done,
                    'overdue' => $overdue
                ],
                'ringkasan_user' => [
                    'total_customer' => $totalCustomer,
                    'total_teknisi' => $totalTeknisi
                ],
                'ringkasan_kepuasan' => [
                    'total_rating' => $totalRating,
                    'rata_rata_rating' => round($rataRataRating, 2),
                    'kepuasan_persen' => round($kepuasanPersen, 2)
                ],
                'beban_kerja_teknisi' => $bebanTeknisi
            ]
        ]);
    }

    public function teknisi($id_teknisi)
    {
        $this->updateOverdueTickets();

        $ticketModel = new TicketModel();
        $userModel   = new UserModel();

        $teknisi = $userModel
            ->where('id_user', $id_teknisi)
            ->where('role', 'teknisi')
            ->first();

        if (!$teknisi) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Teknisi tidak ditemukan'
            ]);
        }

        $totalTiket = $ticketModel
            ->where('id_teknisi', $id_teknisi)
            ->countAllResults();

        $open = $ticketModel
            ->where('id_teknisi', $id_teknisi)
            ->where('status', 'open')
            ->countAllResults();

        $inProgress = $ticketModel
            ->where('id_teknisi', $id_teknisi)
            ->where('status', 'in_progress')
            ->countAllResults();

        $done = $ticketModel
            ->where('id_teknisi', $id_teknisi)
            ->where('status', 'done')
            ->countAllResults();

        $overdue = $ticketModel
            ->where('id_teknisi', $id_teknisi)
            ->where('status', 'overdue')
            ->countAllResults();

        $tiketTerbaru = $ticketModel
            ->select('tickets.*, users.nama AS nama_customer')
            ->join('users', 'users.id_user = tickets.id_user')
            ->where('tickets.id_teknisi', $id_teknisi)
            ->orderBy('tickets.id_tiket', 'DESC')
            ->findAll(5);

        return $this->response->setJSON([
            'status' => true,
            'message' => 'Data dashboard teknisi berhasil diambil',
            'data' => [
                'teknisi' => [
                    'id_teknisi' => $teknisi['id_user'],
                    'nama_teknisi' => $teknisi['nama'],
                    'email' => $teknisi['email']
                ],
                'ringkasan_tiket' => [
                    'total_tiket' => $totalTiket,
                    'open' => $open,
                    'in_progress' => $inProgress,
                    'done' => $done,
                    'overdue' => $overdue
                ],
                'tiket_terbaru' => $tiketTerbaru
            ]
        ]);
    }
}
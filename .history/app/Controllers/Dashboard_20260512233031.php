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
}
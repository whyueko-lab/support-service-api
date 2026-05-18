<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\RatingModel;
use App\Models\TicketModel;

class RatingController extends BaseController
{
    public function create()
    {
        $ratingModel = new RatingModel();
        $ticketModel = new TicketModel();

        $id_tiket = $this->request->getPost('id_tiket');
        $nilai_rating = $this->request->getPost('nilai_rating');
        $komentar = $this->request->getPost('komentar');

        if (!$id_tiket || !$nilai_rating) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'id_tiket dan nilai_rating wajib diisi'
            ]);
        }

        if ($nilai_rating < 1 || $nilai_rating > 5) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Nilai rating harus antara 1 sampai 5'
            ]);
        }

        $ticket = $ticketModel->find($id_tiket);

        if (!$ticket) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Tiket tidak ditemukan'
            ]);
        }

        if ($ticket['status'] !== 'done') {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Rating hanya dapat diberikan setelah tiket selesai'
            ]);
        }

        $ratingLama = $ratingModel
            ->where('id_tiket', $id_tiket)
            ->first();

        if ($ratingLama) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Tiket ini sudah pernah diberi rating'
            ]);
        }

        $data = [
            'id_tiket' => $id_tiket,
            'nilai_rating' => $nilai_rating,
            'komentar' => $komentar,
            'tanggal' => date('Y-m-d H:i:s')
        ];

        $ratingModel->insert($data);

        return $this->response->setJSON([
            'status' => true,
            'message' => 'Rating berhasil disimpan',
            'data' => $data
        ]);
    }

    public function index()
    {
        $ratingModel = new RatingModel();

        $data = $ratingModel
            ->select('ratings.*, tickets.deskripsi, tickets.prioritas, tickets.status')
            ->join('tickets', 'tickets.id_tiket = ratings.id_tiket')
            ->orderBy('ratings.id_rating', 'DESC')
            ->findAll();

        return $this->response->setJSON([
            'status' => true,
            'message' => 'Data rating berhasil diambil',
            'data' => $data
        ]);
    }

    public function summary()
    {
        $ratingModel = new RatingModel();

        $ratings = $ratingModel->findAll();

        if (count($ratings) === 0) {
            return $this->response->setJSON([
                'status' => true,
                'message' => 'Belum ada data rating',
                'data' => [
                    'total_rating' => 0,
                    'rata_rata_rating' => 0,
                    'kepuasan_persen' => 0
                ]
            ]);
        }

        $totalNilai = 0;

        foreach ($ratings as $rating) {
            $totalNilai += $rating['nilai_rating'];
        }

        $rataRata = $totalNilai / count($ratings);

        // Konversi rating 1-5 menjadi persen
        $kepuasanPersen = ($rataRata / 5) * 100;

        return $this->response->setJSON([
            'status' => true,
            'message' => 'Ringkasan rating berhasil diambil',
            'data' => [
                'total_rating' => count($ratings),
                'rata_rata_rating' => round($rataRata, 2),
                'kepuasan_persen' => round($kepuasanPersen, 2)
            ]
        ]);
    }
}
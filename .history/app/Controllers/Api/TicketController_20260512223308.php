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
            ->select('tickets.*, users.nama AS nama_customer')
            ->join('users', 'users.id_user = tickets.id_user')
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
        $ticketModel = new TicketModel();

        $id_user = $this->request->getPost('id_user');
        $deskripsi = $this->request->getPost('deskripsi');

        if (!$id_user || !$deskripsi) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'id_user dan deskripsi wajib diisi'
            ]);
        }

        $data = [
            'id_user' => $id_user,
            'deskripsi' => $deskripsi,
            'kategori' => 'umum',
            'prioritas' => 'medium',
            'status' => 'open',
            'score' => 0,
            'tanggal_masuk' => date('Y-m-d H:i:s')
        ];

        $ticketModel->insert($data);

        return $this->response->setJSON([
            'status' => true,
            'message' => 'Tiket berhasil dibuat',
            'data' => $data
        ]);
    }
}
<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\NotificationModel;

class NotificationController extends BaseController
{
    public function byUser($id_user)
    {
        $notificationModel = new NotificationModel();

        $data = $notificationModel
            ->select('notifications.*, tickets.deskripsi, tickets.status, tickets.prioritas')
            ->join('tickets', 'tickets.id_tiket = notifications.id_tiket', 'left')
            ->where('notifications.id_user', $id_user)
            ->orderBy('notifications.id_notifikasi', 'DESC')
            ->findAll();

        return $this->response->setJSON([
            'status' => true,
            'message' => 'Data notifikasi berhasil diambil',
            'data' => $data
        ]);
    }

    public function unreadCount($id_user)
    {
        $notificationModel = new NotificationModel();

        $jumlah = $notificationModel
            ->where('id_user', $id_user)
            ->where('status_baca', 0)
            ->countAllResults();

        return $this->response->setJSON([
            'status' => true,
            'message' => 'Jumlah notifikasi belum dibaca berhasil diambil',
            'data' => [
                'id_user' => $id_user,
                'unread_count' => $jumlah
            ]
        ]);
    }

    public function markAsRead($id_notifikasi)
    {
        $notificationModel = new NotificationModel();

        $notifikasi = $notificationModel->find($id_notifikasi);

        if (!$notifikasi) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Notifikasi tidak ditemukan'
            ]);
        }

        $notificationModel->update($id_notifikasi, [
            'status_baca' => 1
        ]);

        return $this->response->setJSON([
            'status' => true,
            'message' => 'Notifikasi berhasil ditandai sudah dibaca',
            'data' => [
                'id_notifikasi' => $id_notifikasi,
                'status_baca' => 1
            ]
        ]);
    }

    public function markAllAsRead($id_user)
    {
        $notificationModel = new NotificationModel();

        $notificationModel
            ->where('id_user', $id_user)
            ->set(['status_baca' => 1])
            ->update();

        return $this->response->setJSON([
            'status' => true,
            'message' => 'Semua notifikasi berhasil ditandai sudah dibaca',
            'data' => [
                'id_user' => $id_user,
                'status_baca' => 1
            ]
        ]);
    }
}
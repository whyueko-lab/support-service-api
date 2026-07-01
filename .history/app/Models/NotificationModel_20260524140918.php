<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificationModel extends Model
{
    protected $table = 'notifications';
    protected $primaryKey = 'id_notifikasi';

    protected $allowedFields = [
        'id_user',
        'id_tiket',
        'pesan',
        'tipe_notifikasi',
        'waktu',
        'status_baca'
    ];

    protected $useTimestamps = false;

    public function countUnread()
    {
        return $this->where('status_baca', 0)->countAllResults();
    }

    public function countUnreadNewTicket()
    {
        return $this->where('status_baca', 0)
            ->where('tipe_notifikasi', 'tiket_baru')
            ->countAllResults();
    }
}
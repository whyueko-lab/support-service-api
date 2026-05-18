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
        'waktu',
        'status_baca'
    ];

    protected $useTimestamps = false;
}
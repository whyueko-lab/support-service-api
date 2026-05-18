<?php

namespace App\Models;

use CodeIgniter\Model;

class TicketModel extends Model
{
    protected $table = 'tickets';
    protected $primaryKey = 'id_tiket';

    protected $allowedFields = [
        'id_user',
        'id_teknisi',
        'id_kpi',
        'tanggal_masuk',
        'deadline',
        'tanggal_selesai',
        'status',
        'prioritas',
        'kategori',
        'deskripsi',
        'score',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = false;
}
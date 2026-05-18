<?php

namespace App\Models;

use CodeIgniter\Model;

class RatingModel extends Model
{
    protected $table = 'ratings';
    protected $primaryKey = 'id_rating';

    protected $allowedFields = [
        'id_tiket',
        'nilai_rating',
        'komentar',
        'tanggal'
    ];

    protected $useTimestamps = false;
}
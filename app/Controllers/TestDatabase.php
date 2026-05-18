<?php

namespace App\Controllers;

class TestDatabase extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        if ($db->connect()) {
            return "Koneksi database berhasil!";
        }

        return "Koneksi database gagal!";
    }
}
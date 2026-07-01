<?php

namespace App\Controllers;

class TestAI extends BaseController
{
    public function index()
    {
        // 1. Load file helper 'naive_bayes_helper.php'
        helper('naive_bayes'); 

        // 2. Siapkan contoh data uji
        $contohKasus = [
            "aplikasi srv mendadak eror dan tidak bisa login",
            "koneksi internet di ruangan lambat banget, wifi sering putus",
            "kamera cctv di parkiran motor mati mendadak gambar hitam",
            "indikasi imsi catcher aktif ada fake bts terdeteksi"
        ];

        $hasilUji = [];

        foreach ($contohKasus as $teks) {
            // 3. Panggil fungsi helper baru kamu di sini
            $hasilUji[] = [
                'input_user' => $teks,
                'hasil_ai'   => \klasifikasiTiketOtomatis($teks) // <-- Sudah sinkron!
            ];
        }

        // 4. Tampilkan JSON output
        return $this->response->setJSON($hasilUji);
    }
}
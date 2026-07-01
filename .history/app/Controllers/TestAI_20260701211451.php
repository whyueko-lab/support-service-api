<?php

namespace App\Controllers;

class TestAI extends BaseController
{
    public function index()
    {
        // 1. Load helper yang berisi fungsi 'klasifikasiTiketOtomatis'
        helper('naive_bayes'); 

        // 2. Siapkan beberapa contoh teks keluhan dari user untuk ditest
        $contohKasus = [
            "aplikasi srv mendadak eror dan tidak bisa login",
            "koneksi internet di ruangan lambat banget, wifi sering putus",
            "kamera cctv di parkiran motor mati mendadak gambar hitam",
            "indikasi imsi catcher aktif ada fake bts terdeteksi"
        ];

        $hasilUji = [];

        foreach ($contohKasus as $teks) {
            // 3. Panggil fungsi helper global
            $hasilUji[] = [
                'input_user' => $teks,
                'hasil_ai'   => klasifikasiTiketOtomatis($teks) // <--- Memanggil helpermu
            ];
        }

        // 4. Tampilkan hasilnya dalam bentuk JSON agar mudah dibaca
        return $this->response->setJSON($hasilUji);
    }
}
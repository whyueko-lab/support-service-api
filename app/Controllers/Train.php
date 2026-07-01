<?php

namespace App\Controllers;

use App\Helpers\DatasetGenerator;
use App\Helpers\NaiveBayesTrainer;
use App\Helpers\ModelManager;

class Train extends BaseController
{
    public function index()
    {
        try {
            // 1. Inisialisasi dan Generate Dataset Otomatis
            $generator = new DatasetGenerator();
            $generator->generateAll(); // Membuat data untuk SERVER, BTS, INTERNET, dll.
            $dataset = $generator->get(); // Mengambil hasil array dataset

            // 2. Proses Training Menggunakan NaiveBayesTrainer
            $trainer = new NaiveBayesTrainer();
            $modelData = $trainer->train($dataset); // Menghitung Prior dan Word Count

            // 3. Simpan Hasil Perhitungan ke model.json
            $modelManager = new ModelManager();
            $isSaved = $modelManager->save($modelData);

            if ($isSaved) {
                return $this->response->setJSON([
                    'status'  => 'success',
                    'message' => 'Training data berhasil dilakukan!',
                    'total_data' => count($dataset),
                    'path'    => $modelManager->getPath()
                ]);
            } else {
                return $this->response->setJSON([
                    'status'  => 'error',
                    'message' => 'Gagal menulis file model.json'
                ]);
            }

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
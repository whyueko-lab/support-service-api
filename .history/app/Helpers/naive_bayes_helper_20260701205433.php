<?php

use App\Helpers\ModelManager;
use App\Helpers\NaiveBayesClassifier;

if (!function_exists('klasifikasiNaiveBayes')) {
    /**
     * Fungsi Helper global untuk memprediksi kategori dan prioritas tiket
     */
    function klasifikasiNaiveBayes(string $deskripsi): array
    {
        try {
            $manager = new ModelManager();
            
            // Jika file model.json belum ada, beri fallback atau pesan error
            if (!$manager->exists()) {
                return [
                    'kategori'  => 'UMUM',
                    'prioritas' => 'low',
                    'score'     => 0,
                    'error'     => 'Model AI belum dilatih.'
                ];
            }

            // 1. Load data model.json yang sudah matang
            $modelData = $manager->load();

            // 2. Panggil Classifier untuk menghitung teks inputan user
            $classifier = new NaiveBayesClassifier($modelData);
            
            // 3. Lakukan prediksi (ini akan otomatis memanggil TextPreprocessor secara internal)
            return $classifier->predict($deskripsi);

        } catch (\Exception $e) {
            return [
                'kategori'  => 'UMUM',
                'prioritas' => 'low',
                'score'     => 0,
                'error'     => $e->getMessage()
            ];
        }
    }
}
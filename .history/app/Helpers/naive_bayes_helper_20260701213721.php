<?php

use App\Helpers\ModelManager;
use App\Helpers\NaiveBayesClassifier;
use App\Helpers\TextPreprocessor; // Muat preprocessor bawaanmu

if (!function_exists('klasifikasiTiketOtomatis')) {
    /**
     * Fungsi Helper Global untuk memprediksi kategori & prioritas tiket berbasis AI Naive Bayes
     */
    function klasifikasiTiketOtomatis(string $deskripsiMasalah): array
    {
        try {
            $modelManager = new ModelManager();

            if (!$modelManager->exists()) {
                return [
                    'kategori'   => 'UMUM',
                    'prioritas'  => 'low',
                    'confidence' => 100,
                    'error'      => 'Model AI belum dilatih. Jalankan /ai/train terlebih dahulu.'
                ];
            }

            // 1. Load data model.json (berisi dataset training)
            $modelData = $modelManager->load();

            // 2. Bersihkan teks inputan user menjadi array kata (token)
            $preprocessor = new TextPreprocessor();
            $tokens = $preprocessor->process($deskripsiMasalah);

            // 3. Inisialisasi Classifier dengan model data
            $classifier = new NaiveBayesClassifier($modelData);

            // 4. Lakukan prediksi menggunakan array token kata
            $hasilAI = $classifier->predict($tokens);

            // 5. Kembalikan data dan samakan key 'score' menjadi 'confidence' agar fleksibel
            return [
                'kategori'   => $hasilAI['kategori'],
                'prioritas'  => $hasilAI['prioritas'],
                'confidence' => $hasilAI['score'], // Mapping dari 'score' bawaan class kamu
                'token'      => $tokens
            ];

        } catch (\Exception $e) {
            return [
                'kategori'   => 'UMUM',
                'prioritas'  => 'low',
                'confidence' => 0,
                'error'      => $e->getMessage()
            ];
        }
    }
}
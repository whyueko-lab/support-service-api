<?php

// JANGAN gunakan namespace di sini agar fungsi bisa dipanggil global
use App\Helpers\ModelManager;
use App\Helpers\NaiveBayesClassifier;

if (!function_exists('klasifikasiTiketOtomatis')) {
    /**
     * Fungsi Helper Global untuk memprediksi kategori & prioritas tiket berbasis AI Naive Bayes
     * * @param string $deskripsiMasalah Teks komplain/masalah dari user
     * @return array Hasil prediksi [kategori, score, prioritas, confidence, ranking, token]
     */
    function klasifikasiTiketOtomatis(string $deskripsiMasalah): array
    {
        try {
            $modelManager = new ModelManager();

            // Proteksi jika file model.json tidak tersedia
            if (!$modelManager->exists()) {
                return [
                    'kategori'   => 'UMUM',
                    'score'      => 100,
                    'prioritas'  => 'low',
                    'confidence' => 100,
                    'error'      => 'Model AI belum dilatih. Jalankan /ai/train terlebih dahulu.'
                ];
            }

            // 1. Load data model.json yang sudah matang
            $modelData = $modelManager->load();

            // 2. Inisialisasi Classifier dengan data model tersebut
            $classifier = new NaiveBayesClassifier($modelData);

            // 3. Lakukan prediksi teks
            $hasilPrediksi = $classifier->predict($deskripsiMasalah);

            // Pastikan hasil prediksi mengembalikan struktur kategori yang valid
            if (!isset($hasilPrediksi['kategori'])) {
                throw new \Exception("Format output dari NaiveBayesClassifier tidak valid atau 'kategori' kosong.");
            }

            return $hasilPrediksi;

        } catch (\Exception $e) {
            // Fallback aman jika terjadi sesuatu di luar kendali
            return [
                'kategori'   => 'UMUM',
                'score'      => 0,
                'prioritas'  => 'low',
                'confidence' => 0,
                'error'      => $e->getMessage()
            ];
        }
    }
}
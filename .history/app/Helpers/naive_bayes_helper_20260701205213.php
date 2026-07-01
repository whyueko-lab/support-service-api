<?php

// Pastikan untuk meng-include atau autoload kedua class utama jika belum otomatis
// require_once 'TextPreprocessor.php';
// require_once 'NaiveBayesClassifier.php';

if (!function_exists('klasifikasiNaiveBayes')) {

    function klasifikasiNaiveBayes(string $deskripsi): array
    {
        // 1. Ambil data training (Idealnya di-load dari file JSON, Cache, atau Database)
        // Sebagai contoh cepat, kita asumsikan fungsi getTrainingDataset() mengembalikan array dataset kamu.
        $trainingData = getTrainingDataset(); 

        // 2. Jalankan Preprocessing (Tahap Tokenisasi dan Pembersihan)
        $preprocessor = new TextPreprocessor();
        $cleanedWords = $preprocessor->tokenize($deskripsi);

        // 3. Jalankan Proses Klasifikasi Naive Bayes
        $classifier = new NaiveBayesClassifier($trainingData);
        
        // 4. Kembalikan hasil prediksi [kategori, prioritas, score]
        return $classifier->predict($cleanedWords);
    }
}

/**
 * Fungsi pembantu simulasi penampung dataset (agar tidak mengotori fungsi utama helper)
 */
function getTrainingDataset(): array {
    return [
        ['text' => 'server down aplikasi tidak bisa diakses service mati', 'kategori' => 'server'],
        ['text' => 'cpu server tinggi memory penuh database lambat', 'kategori' => 'server'],
        ['text' => 'internet kantor putus koneksi tidak stabil', 'kategori' => 'internet'],
        ['text' => 'wifi tidak bisa connect ssid tidak muncul', 'kategori' => 'wifi'],
        ['text' => 'access point mati lampu indikator tidak menyala', 'kategori' => 'wifi'],
        ['text' => 'kamera cctv mati tidak tampil di monitor', 'kategori' => 'cctv'],
        ['text' => 'bts 2g tidak mau transmit di mobil operasional', 'kategori' => 'perangkat_bts'],
        ['text' => 'perangkat rf hunter tidak mendeteksi signal di dashboard', 'kategori' => 'perangkat_rf'],
        ['text' => 'laptop mati tidak bisa menyala adaptor bermasalah', 'kategori' => 'hardware'],
        ['text' => 'aplikasi error tidak bisa login', 'kategori' => 'software'],
        ['text' => 'permintaan bantuan informasi layanan support', 'kategori' => 'umum'],
        // ... masukkan sisa dataset training kamu yang lain di sini ...
    ];
}
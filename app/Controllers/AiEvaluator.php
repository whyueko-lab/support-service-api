<?php

namespace App\Controllers;

use App\Helpers\DatasetGenerator;
use App\Helpers\ModelManager;
use App\Helpers\NaiveBayesClassifier;
use App\Helpers\TextPreprocessor;

class AiEvaluator extends BaseController
{
    public function index()
    {
        try {
            $modelManager = new ModelManager();
            if (!$modelManager->exists()) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Model JSON belum ada. Jalankan /ai/train terlebih dahulu.'
                ]);
            }

            // 1. Ambil data model.json yang sudah dilatih
            $modelData = $modelManager->load();
            $classifier = new NaiveBayesClassifier($modelData);
            $preprocessor = new TextPreprocessor();

            // 2. Ambil dataset mentah untuk dijadikan data pengujian (Testing Data)
            $generator = new DatasetGenerator();
            $generator->generateAll();
            $dataset = $generator->get();

            $matrix = [];
            $kategoriList = array_keys($modelData['categoryCounts'] ?? []);

            // Inisialisasi struktur matriks untuk menghitung TP, FP, FN
            foreach ($kategoriList as $kat) {
                $matrix[$kat] = ['TP' => 0, 'FP' => 0, 'FN' => 0, 'TN' => 0];
            }

            $totalBenar = 0;
            $totalData = count($dataset);

            // 3. Lakukan pengujian massal (AI menebak seluruh isi dataset)
            foreach ($dataset as $row) {
                $kategoriAsli = strtoupper($row['kategori'] ?? 'UMUM');
                $textMentah   = $row['text'] ?? $row['deskripsi'] ?? '';

                // Proses teks keluhan menjadi token kata bersih
                $tokens = $preprocessor->process($textMentah);
                
                // AI memprediksi kategori
                $prediksi = $classifier->predict($tokens);
                $kategoriAI = strtoupper($prediksi['kategori']);

                if ($kategoriAI === $kategoriAsli) {
                    $totalBenar++;
                    // Jika tebakan benar (True Positive untuk kategori ini)
                    if (isset($matrix[$kategoriAsli])) {
                        $matrix[$kategoriAsli]['TP']++;
                    }
                } else {
                    // Jika tebakan salah (False Positive bagi kategori hasil tebakan AI)
                    if (isset($matrix[$kategoriAI])) {
                        $matrix[$kategoriAI]['FP']++;
                    }
                    // Dan False Negative bagi kategori asli yang gagal ditebak oleh AI
                    if (isset($matrix[$kategoriAsli])) {
                        $matrix[$kategoriAsli]['FN']++;
                    }
                }
            }

            // 4. Hitung Akurasi Global
            $accuracyGlobal = $totalData > 0 ? ($totalBenar / $totalData) * 100 : 0;

            // 5. Hitung Precision, Recall, dan F1-Score per Kategori
            $reportPerCategory = [];
            $sumF1 = 0;
            $validKategoriCount = 0;

            foreach ($matrix as $kat => $value) {
                $tp = $value['TP'];
                $fp = $value['FP'];
                $fn = $value['FN'];

                $precision = ($tp + $fp) > 0 ? ($tp / ($tp + $fp)) * 100 : 0;
                $recall    = ($tp + $fn) > 0 ? ($tp / ($tp + $fn)) * 100 : 0;
                $f1Score   = ($precision + $recall) > 0 ? (2 * $precision * $recall) / ($precision + $recall) : 0;

                $reportPerCategory[$kat] = [
                    'true_positive'  => $tp,
                    'false_positive' => $fp,
                    'false_negative' => $fn,
                    'precision'      => round($precision, 2) . '%',
                    'recall'         => round($recall, 2) . '%',
                    'f1_score'       => round($f1Score, 2) . '%'
                ];

                if (($tp + $fn) > 0) {
                    $sumF1 += $f1Score;
                    $validKategoriCount++;
                }
            }

            // Macro Average F1-Score
            $macroF1 = $validKategoriCount > 0 ? ($sumF1 / $validKategoriCount) : 0;

            // 6. Kembalikan Hasil Evaluasi Statistik berbentuk JSON yang rapi
            return $this->response->setJSON([
                'status' => 'success',
                'metode_evaluasi' => 'Confusion Matrix & Classification Report',
                'ringkasan_global' => [
                    'total_data_uji'  => $totalData,
                    'total_tebakan_benar' => $totalBenar,
                    'akurasi_global'  => round($accuracyGlobal, 2) . '%',
                    'macro_avg_f1_score' => round($macroF1, 2) . '%'
                ],
                'detail_per_kategori' => $reportPerCategory
            ]);

        } catch (\Exception $e) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
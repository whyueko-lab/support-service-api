<?php

namespace App\Helpers;

class NaiveBayesTrainer
{
    protected TextPreprocessor $preprocessor;

    public function __construct()
    {
        $this->preprocessor = new TextPreprocessor();
    }

    public function train(array $dataset): array
    {
        $categoryCounts = [];
        $wordCountsPerCategory = [];
        $totalWordsPerCategory = [];
        $vocabulary = [];
        $totalDocuments = count($dataset);

        // Panggil TextPreprocessor agar sama dengan Classifier saat testing
        $preprocessor = new \App\Helpers\TextPreprocessor();

        foreach ($dataset as $row) {
            // Pastikan nama kategori seragam huruf besar murni (SERVER, WIFI, CCTV, FAKE_BTS)
            $kategori = strtoupper($row['kategori'] ?? 'UMUM');

            if (!isset($categoryCounts[$kategori])) {
                $categoryCounts[$kategori] = 0;
                $wordCountsPerCategory[$kategori] = [];
                $totalWordsPerCategory[$kategori] = 0;
            }

            $categoryCounts[$kategori]++;

            // AMAN: Ambil teks deskripsi atau text dari baris data
            $textMentah = $row['text'] ?? $row['deskripsi'] ?? '';

            // WAJIB: Gunakan preprocessor yang memotong kata menjadi lowercase bersih murni
            $words = $preprocessor->process($textMentah);

            foreach ($words as $word) {
                if ($word === '') continue;

                // Pastikan kata disimpan dalam bentuk lowercase murni
                $wordLower = strtolower($word);

                if (!isset($wordCountsPerCategory[$kategori][$wordLower])) {
                    $wordCountsPerCategory[$kategori][$wordLower] = 0;
                }

                $wordCountsPerCategory[$kategori][$wordLower]++;
                $totalWordsPerCategory[$kategori]++;
                $vocabulary[$wordLower] = true;
            }
        }

        return [
            'categoryCounts'         => $categoryCounts,
            'wordCountsPerCategory'  => $wordCountsPerCategory,
            'totalWordsPerCategory'  => $totalWordsPerCategory,
            'vocabulary'             => $vocabulary,
            'totalDocuments'         => $totalDocuments,
            'vocabSize'              => count($vocabulary)
        ];
    }
}
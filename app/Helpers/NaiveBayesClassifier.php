<?php

namespace App\Helpers;

class NaiveBayesClassifier
{
    private array $categoryCounts = [];
    private array $wordCountsPerCategory = [];
    private array $totalWordsPerCategory = [];
    private array $vocabulary = [];
    private int $totalDocuments = 0;
    private int $vocabSize = 0;

    // Pemetaan prioritas berdasarkan kategori
    private array $priorityMap = [
        'server'        => 'high',
        'internet'      => 'high',
        'wifi'          => 'medium',
        'cctv'          => 'high',
        'perangkat_bts' => 'high',
        'perangkat_rf'  => 'high',
        'hardware'      => 'medium',
        'software'      => 'medium',
        'umum'          => 'low'
    ];

    /**
     * Constructor langsung memetakan hasil load JSON model, TIDAK melatih ulang data.
     */
    public function __construct(array $modelData)
    {
        // Petakan data dari JSON hasil training
        $this->categoryCounts         = $modelData['categoryCounts'] ?? [];
        $this->wordCountsPerCategory  = $modelData['wordCountsPerCategory'] ?? [];
        $this->totalWordsPerCategory  = $modelData['totalWordsPerCategory'] ?? [];
        $this->vocabulary             = $modelData['vocabulary'] ?? [];
        $this->totalDocuments         = $modelData['totalDocuments'] ?? 0;
        $this->vocabSize              = $modelData['vocabSize'] ?? 0;
    }

    /**
     * Memprediksi kategori dan prioritas dari array kata masukan
     */
    public function predict(array $words): array
    {
        $scores = [];

        // Proteksi jika model kosong
        if (empty($this->categoryCounts)) {
            return [
                'kategori'  => 'UMUM',
                'prioritas' => 'low',
                'score'     => 100.00
            ];
        }

        foreach ($this->categoryCounts as $kategori => $jumlahDokumenKategori) {
            // Prior Probability: P(Kategori)
            $prior = $this->totalDocuments > 0 ? ($jumlahDokumenKategori / $this->totalDocuments) : 1;
            $logProbability = log($prior);

            // Likelihood: P(Kata | Kategori) dengan Laplace Smoothing
            foreach ($words as $word) {
                if ($word === '') continue;

                $wordLower = strtolower($word);
                $wordUpper = strtoupper($word);

                $wordFrequency = 0;
                if (isset($this->wordCountsPerCategory[$kategori][$wordLower])) {
                    $wordFrequency = $this->wordCountsPerCategory[$kategori][$wordLower];
                } elseif (isset($this->wordCountsPerCategory[$kategori][$wordUpper])) {
                    $wordFrequency = $this->wordCountsPerCategory[$kategori][$wordUpper];
                } elseif (isset($this->wordCountsPerCategory[$kategori][$word])) {
                    $wordFrequency = $this->wordCountsPerCategory[$kategori][$word];
                }
                
                // Rumus Naive Bayes Laplace Smoothing
                $denominator = ($this->totalWordsPerCategory[$kategori] ?? 0) + $this->vocabSize;
                $likelihood = ($wordFrequency + 1) / ($denominator > 0 ? $denominator : 1);
                
                $logProbability += log($likelihood);
            }

            $scores[] = [
                'kategori'  => $kategori,
                'log_score' => $logProbability
            ];
        }

        // Urutkan score tertinggi ke terendah
        usort($scores, fn($a, $b) => $b['log_score'] <=> $a['log_score']);

        $kategoriTerpilih = $scores[0]['kategori'];

        // --- HITUNG CONFIDENCE DENGAN AMAN (LOG-SUM-EXP TRICK) ---
        $maxScore = $scores[0]['log_score'];
        $totalExp = 0;
        foreach ($scores as $item) {
            $diff = $item['log_score'] - $maxScore;
            if ($diff < -700) $diff = -700; // Mencegah Underflow PHP menjadi 0
            $totalExp += exp($diff);
        }

        $confidence = ($totalExp > 0) ? (exp(0) / $totalExp) : 0;
        $confidencePercent = round($confidence * 100, 2);

        // Fallback jika kata benar-benar baru
        if ($confidencePercent == 0 && count($scores) > 0) {
            $confidencePercent = 100.00;
        }

        return [
            'kategori'  => $kategoriTerpilih,
            'prioritas' => $this->priorityMap[strtolower($kategoriTerpilih)] ?? 'low',
            'score'     => $confidencePercent
        ];
    }
}
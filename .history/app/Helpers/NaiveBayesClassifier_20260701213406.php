<?php

namespace App\Helpers;

class NaiveBayesClassifier
{
    private array $trainingData;
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

    public function __construct(array $trainingData)
    {
        $this->trainingData = $trainingData;
        $this->totalDocuments = count($trainingData);
        $this->train();
    }

    /**
     * Memproses data training untuk menghitung frekuensi kata & dokumen
     */
    private function train(): void
    {
        foreach ($this->trainingData as $data) {
            $kategori = $data['kategori'];

            if (!isset($this->categoryCounts[$kategori])) {
                $this->categoryCounts[$kategori] = 0;
                $this->wordCountsPerCategory[$kategori] = [];
                $this->totalWordsPerCategory[$kategori] = 0;
            }

            $this->categoryCounts[$kategori]++;

            // Anggap data text di dataset sudah bersih/dipreprocess
            $words = explode(' ', $data['text']);

            foreach ($words as $word) {
                if ($word === '') continue;

                if (!isset($this->wordCountsPerCategory[$kategori][$word])) {
                    $this->wordCountsPerCategory[$kategori][$word] = 0;
                }

                $this->wordCountsPerCategory[$kategori][$word]++;
                $this->totalWordsPerCategory[$kategori]++;
                $this->vocabulary[$word] = true;
            }
        }

        $this->vocabSize = count($this->vocabulary);
    }

    /**
     * Memprediksi kategori dan prioritas dari array kata masukan
     */
    public function predict(array $words): array
    {
        $scores = [];

        foreach ($this->categoryCounts as $kategori => $jumlahDokumenKategori) {
            // Prior Probability: P(Kategori)
            $prior = $jumlahDokumenKategori / $this->totalDocuments;
            $logProbability = log($prior);

            // Likelihood: P(Kata | Kategori) dengan Laplace Smoothing
            foreach ($words as $word) {
                if ($word === '') continue;

                $wordFrequency = $this->wordCountsPerCategory[$kategori][$word] ?? 0;
                $likelihood = ($wordFrequency + 1) / ($this->totalWordsPerCategory[$kategori] + $this->vocabSize);
                
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
        $scoreTerpilih = $scores[0]['log_score'];

        // Menghitung persentase keyakinan (Confidence Score) menggunakan Softmax sederhana
        $maxScore = $scores[0]['log_score'];
        $totalExp = 0;
        foreach ($scores as $item) {
            $totalExp += exp($item['log_score'] - $maxScore);
        }

        $confidence = exp($scoreTerpilih - $maxScore) / $totalExp;
        $confidencePercent = round($confidence * 100, 2);

        return [
            'kategori'  => $kategoriTerpilih,
            'prioritas' => $this->priorityMap[strtolower($kategoriTerpilih)] ?? 'low',
            'score'     => $confidencePercent
        ];
    }
}
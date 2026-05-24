<?php

if (!function_exists('klasifikasiNaiveBayes')) {

    function klasifikasiNaiveBayes($deskripsi)
    {
        // =========================
        // 1. Preprocessing Text
        // =========================
        $text = strtolower($deskripsi);
        $text = preg_replace('/[^a-zA-Z0-9\s]/', ' ', $text);
        $text = preg_replace('/\s+/', ' ', $text);
        $text = trim($text);

        // =========================
        // 2. Normalisasi Kata Tidak Baku
        // =========================
        $slang = [
            'lemot'  => 'lambat',
            'ngadat' => 'error',
            'gabisa' => 'tidak bisa',
            'gak'    => 'tidak',
            'ga'     => 'tidak',
            'gk'     => 'tidak',
            'net'    => 'internet',
            'komp'   => 'komputer',
            'pc'     => 'komputer',
            'wifi'   => 'wifi',
            'wifie'  => 'wifi'
        ];

        foreach ($slang as $slangWord => $correctWord) {
            $text = str_replace($slangWord, $correctWord, $text);
        }

        // Pecah kalimat input menjadi kata
        $words = explode(' ', $text);

        // =========================
        // 3. Dataset Training Sederhana
        // =========================
        $trainingData = [
            // Kategori Jaringan
            [
                'text' => 'internet tidak bisa koneksi wifi lambat jaringan putus',
                'kategori' => 'jaringan',
                'prioritas' => 'high'
            ],
            [
                'text' => 'server down vpn tidak bisa router bermasalah',
                'kategori' => 'jaringan',
                'prioritas' => 'high'
            ],
            [
                'text' => 'koneksi jaringan kantor sering terputus internet mati',
                'kategori' => 'jaringan',
                'prioritas' => 'high'
            ],
            [
                'text' => 'wifi tidak tersambung koneksi internet gagal',
                'kategori' => 'jaringan',
                'prioritas' => 'high'
            ],

            // Kategori Hardware
            [
                'text' => 'printer tidak bisa mencetak kertas macet',
                'kategori' => 'hardware',
                'prioritas' => 'medium'
            ],
            [
                'text' => 'laptop mati keyboard rusak monitor tidak menyala',
                'kategori' => 'hardware',
                'prioritas' => 'medium'
            ],
            [
                'text' => 'scanner error mouse tidak berfungsi komputer lambat',
                'kategori' => 'hardware',
                'prioritas' => 'medium'
            ],
            [
                'text' => 'komputer tidak menyala printer rusak hardware bermasalah',
                'kategori' => 'hardware',
                'prioritas' => 'medium'
            ],

            // Kategori Software
            [
                'text' => 'aplikasi error tidak bisa login password salah',
                'kategori' => 'software',
                'prioritas' => 'medium'
            ],
            [
                'text' => 'database bermasalah sistem tidak dapat dibuka',
                'kategori' => 'software',
                'prioritas' => 'medium'
            ],
            [
                'text' => 'program error aplikasi sering keluar sendiri',
                'kategori' => 'software',
                'prioritas' => 'medium'
            ],
            [
                'text' => 'login gagal aplikasi tidak bisa dibuka sistem error',
                'kategori' => 'software',
                'prioritas' => 'medium'
            ],

            // Kategori Umum
            [
                'text' => 'permintaan bantuan informasi layanan',
                'kategori' => 'umum',
                'prioritas' => 'low'
            ],
            [
                'text' => 'kendala lainnya butuh informasi umum',
                'kategori' => 'umum',
                'prioritas' => 'low'
            ],
            [
                'text' => 'bantuan terkait permintaan layanan',
                'kategori' => 'umum',
                'prioritas' => 'low'
            ],
            [
                'text' => 'ingin bertanya informasi layanan support',
                'kategori' => 'umum',
                'prioritas' => 'low'
            ],
        ];

        // =========================
        // 4. Inisialisasi Perhitungan
        // =========================
        $categoryCount = [];
        $wordCountPerCategory = [];
        $totalWordsPerCategory = [];
        $vocabulary = [];

        $totalDocuments = count($trainingData);

        // =========================
        // 5. Training Naive Bayes
        // =========================
        foreach ($trainingData as $data) {
            $kategori = $data['kategori'];

            if (!isset($categoryCount[$kategori])) {
                $categoryCount[$kategori] = 0;
                $wordCountPerCategory[$kategori] = [];
                $totalWordsPerCategory[$kategori] = 0;
            }

            $categoryCount[$kategori]++;

            $cleanText = strtolower($data['text']);
            $cleanText = preg_replace('/[^a-zA-Z0-9\s]/', ' ', $cleanText);
            $cleanText = preg_replace('/\s+/', ' ', $cleanText);
            $cleanText = trim($cleanText);

            $trainingWords = explode(' ', $cleanText);

            foreach ($trainingWords as $word) {
                if ($word == '') {
                    continue;
                }

                if (!isset($wordCountPerCategory[$kategori][$word])) {
                    $wordCountPerCategory[$kategori][$word] = 0;
                }

                $wordCountPerCategory[$kategori][$word]++;
                $totalWordsPerCategory[$kategori]++;

                $vocabulary[$word] = true;
            }
        }

        $vocabSize = count($vocabulary);

        // =========================
        // 6. Hitung Probabilitas Naive Bayes
        // =========================
        $result = [];

        foreach ($categoryCount as $kategori => $jumlahDokumenKategori) {
            // Prior Probability: P(kategori)
            $prior = $jumlahDokumenKategori / $totalDocuments;

            // Menggunakan log probability agar perhitungan stabil
            $logProbability = log($prior);

            foreach ($words as $word) {
                if ($word == '') {
                    continue;
                }

                $wordFrequency = $wordCountPerCategory[$kategori][$word] ?? 0;

                // Likelihood dengan Laplace Smoothing
                // P(kata | kategori)
                $likelihood = ($wordFrequency + 1) / ($totalWordsPerCategory[$kategori] + $vocabSize);

                $logProbability += log($likelihood);
            }

            $result[] = [
                'kategori' => $kategori,
                'log_score' => $logProbability
            ];
        }

        // =========================
        // 7. Urutkan Berdasarkan Score Tertinggi
        // =========================
        usort($result, function ($a, $b) {
            return $b['log_score'] <=> $a['log_score'];
        });

        $kategoriTerpilih = $result[0]['kategori'];
        $scoreTerpilih = $result[0]['log_score'];

        // =========================
        // 8. Konversi Log Score Menjadi Persentase
        // =========================
        // Teknik ini mirip softmax sederhana.
        // Tujuannya agar score lebih enak dibaca, misalnya 87.52%.
        $maxScore = $result[0]['log_score'];
        $totalExp = 0;

        foreach ($result as $item) {
            $totalExp += exp($item['log_score'] - $maxScore);
        }

        $confidence = exp($scoreTerpilih - $maxScore) / $totalExp;
        $confidencePercent = round($confidence * 100, 2);

        // =========================
        // 9. Tentukan Prioritas
        // =========================
        $priorityMap = [
            'jaringan' => 'high',
            'hardware' => 'medium',
            'software' => 'medium',
            'umum' => 'low'
        ];

        return [
            'kategori' => $kategoriTerpilih,
            'prioritas' => $priorityMap[$kategoriTerpilih] ?? 'low',
            'score' => $confidencePercent
        ];
    }
}
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
        // 2. Normalisasi kata tidak baku
        // =========================
        $slang = [
            'lemot'  => 'lambat',
            'ngadat' => 'error',
            'gabisa' => 'tidak bisa',
            'gak'    => 'tidak',
            'ga'     => 'tidak',
            'gk'     => 'tidak',
            'net'    => 'internet',
            'komp'   => 'komputer'
        ];

        foreach ($slang as $slangWord => $correctWord) {
            $text = str_replace($slangWord, $correctWord, $text);
        }

        // Pecah kalimat menjadi kata
        $words = explode(' ', $text);

        // =========================
        // 3. Dataset Training Sederhana
        // =========================
        $trainingData = [
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
                'text' => 'koneksi jaringan kantor sering terputus',
                'kategori' => 'jaringan',
                'prioritas' => 'high'
            ],
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
        ];

        // =========================
        // 4. Hitung jumlah dokumen per kategori
        // =========================
        $categoryCount = [];
        $wordCountPerCategory = [];
        $totalWordsPerCategory = [];
        $vocabulary = [];

        $totalDocuments = count($trainingData);

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

        // =========================a
        // 5. Hitung probabilitas Naive Bayes
        // =========================
        $result = [];

        foreach ($categoryCount as $kategori => $jumlahDokumenKategori) {
            // Prior Probability: P(kategori)
            $prior = $jumlahDokumenKategori / $totalDocuments;

            // Pakai log agar angka tidak terlalu kecil
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

            $result[$kategori] = [
                'kategori' => $kategori,
                'score' => $logProbability
            ];
        }

        // =========================
        // 6. Urutkan berdasarkan score tertinggi
        // =========================
        usort($result, function ($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        $kategoriTerpilih = $result[0]['kategori'];
        $scoreTerpilih = $result[0]['score'];

        // =========================
        // 7. Tentukan prioritas berdasarkan kategori
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
            'score' => $scoreTerpilih
        ];
    }
}
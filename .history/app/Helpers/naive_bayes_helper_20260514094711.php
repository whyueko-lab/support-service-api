<?php

function klasifikasiNaiveBayes($deskripsi)

{
    $text = strtolower($deskripsi);
    $text = preg_replace('/[^a-zA-Z0-9\s]/', ' ', $text);
    $text = preg_replace('/\s+/', ' ', $text);
    $text = trim($text);
    
    // Dataset sederhana berbasis kata kunci
    $rules = [
        'jaringan' => [
            'keywords' => ['internet', 'wifi', 'jaringan', 'koneksi', 'server', 'vpn', 'router'],
            'prioritas' => 'high'
        ],
        'hardware' => [
            'keywords' => ['printer', 'komputer', 'laptop', 'monitor', 'keyboard', 'mouse', 'scanner'],
            'prioritas' => 'medium'
        ],
        'software' => [
            'keywords' => ['login', 'aplikasi', 'error', 'sistem', 'password', 'database', 'program'],
            'prioritas' => 'medium'
        ],
        'umum' => [
            'keywords' => ['bantuan', 'permintaan', 'informasi', 'kendala', 'lainnya'],
            'prioritas' => 'low'
        ]
    ];

    $hasil = [];
    $totalKeyword = 0;

    foreach ($rules as $kategori => $data) {
        $totalKeyword += count($data['keywords']);
    }

    foreach ($rules as $kategori => $data) {
        $score = 0;

        foreach ($data['keywords'] as $keyword) {
            if (strpos($text, $keyword) !== false) {
                $score++;
            }
        }

        // Perhitungan probabilitas sederhana
        $probability = ($score + 1) / ($totalKeyword + count($rules));

        $hasil[$kategori] = [
            'kategori' => $kategori,
            'prioritas' => $data['prioritas'],
            'score' => $probability
        ];
    }

    // Ambil kategori dengan score tertinggi
    usort($hasil, function ($a, $b) {
        return $b['score'] <=> $a['score'];
    });

    return $hasil[0];
}
<?php

namespace App\Helpers;

class TextPreprocessor
{
    // Kamus kata tidak baku (Slang Words)
    private array $slangs = [
        'lemot'  => 'lambat', 'ngadat' => 'error', 'gabisa' => 'tidak bisa',
        'gak'    => 'tidak', 'ga'     => 'tidak', 'gk'     => 'tidak', 'tdk'    => 'tidak',
        'net'    => 'internet', 'inet'   => 'internet', 'wifie'  => 'wifi', 'wi-fi'  => 'wifi',
        'ap'     => 'access point', 'cam'    => 'kamera', 'camera' => 'kamera',
        'srv'    => 'server', 'db'     => 'database', 'apps'   => 'aplikasi', 'app'    => 'aplikasi',
        'ram'    => 'memory', 'memori' => 'memory', 'hdd'    => 'harddisk', 'ssd'    => 'harddisk',
        'tx'     => 'transmit', 'transmisi' => 'transmit', 'pancar' => 'transmit', 'memancar' => 'transmit',
        'sinyal' => 'signal', 'unit' => 'mobil', 'kendaraan' => 'mobil', 'zenix' => 'mobil',
        'gsm'    => '2g', 'kabel rf' => 'kabel rf'
    ];

    /**
     * Preprocessing utama yang dipanggil oleh Trainer & Classifier
     */
    public function process(string $text): array
    // UBAH DARI tokenize MENJADI process AGAR COCOK DENGAN TRAINER & CLASSIFIER KAMU
    {
        // 1. Case Folding (Lowercase)
        $text = strtolower($text);

        // 2. Cleaning (Hapus karakter selain huruf, angka, dan spasi)
        $text = preg_replace('/[^a-zA-Z0-9\s]/', ' ', $text);
        $text = preg_replace('/\s+/', ' ', $text);
        $text = trim($text);

        // 3. Normalisasi Slang Words
        foreach ($this->slangs as $slangWord => $correctWord) {
            $text = preg_replace(
                '/\b' . preg_quote($slangWord, '/') . '\b/',
                $correctWord,
                $text
            );
        }

        // 4. Tokenizing (Pecah menjadi array kata)
        return explode(' ', $text);
    }
}
<?php

namespace App\Helpers;

use Sastrawi\Stemmer\StemmerFactory;

class TextPreprocessor
{
    /**
     * Normalisasi kata
     */
    protected array $slang = [

        // Umum
        'lemot'      => 'lambat',
        'ngadat'     => 'error',
        'gabisa'     => 'tidak bisa',
        'ga'         => 'tidak',
        'gak'        => 'tidak',
        'gk'         => 'tidak',
        'tdk'        => 'tidak',

        // Internet
        'inet'       => 'internet',
        'net'        => 'internet',

        // WiFi
        'wifie'      => 'wifi',
        'wi-fi'      => 'wifi',

        // Kamera
        'cam'        => 'kamera',
        'camera'     => 'kamera',

        // Aplikasi
        'apps'       => 'aplikasi',
        'app'        => 'aplikasi',

        // Server
        'srv'        => 'server',
        'db'         => 'database',

        // Hardware
        'ram'        => 'memory',
        'memori'     => 'memory',
        'ssd'        => 'harddisk',

        // BTS
        'gsm'        => '2g',
        'transmisi'  => 'transmit',
        'pancar'     => 'transmit',
        'memancar'   => 'transmit',
        'sinyal'     => 'signal',

        // Kendaraan
        'kendaraan'  => 'mobil',
        'unit'       => 'mobil',
        'zenix'      => 'mobil'
    ];

    /**
     * Stopword Indonesia
     */
    protected array $stopwords = [

        'yang','dan','di','ke','dari','untuk',
        'agar','adalah','ini','itu','atau',
        'dengan','karena','setelah','sebelum',
        'sudah','telah','akan','sedang',
        'masih','jadi','oleh','sebagai',
        'perlu','bisa','tidak'
    ];

    protected $stemmer;

    public function __construct()
    {
        $factory = new StemmerFactory();
        $this->stemmer = $factory->createStemmer();
    }

    /**
     * Fungsi utama preprocessing
     */
    public function process(string $text): array
    {
        // Case Folding
        $text = strtolower($text);

        // Hapus simbol
        $text = preg_replace('/[^a-z0-9\s]/', ' ', $text);

        // Rapikan spasi
        $text = preg_replace('/\s+/', ' ', $text);

        $text = trim($text);

        // Normalisasi kata
        foreach ($this->slang as $asal => $tujuan) {

            $text = preg_replace(
                '/\b' . preg_quote($asal, '/') . '\b/',
                $tujuan,
                $text
            );
        }

        // Stemming
        $text = $this->stemmer->stem($text);

        // Tokenisasi
        $words = explode(' ', $text);

        // Stopword Removal
        $words = array_filter($words, function ($word) {

            return $word != '' &&
                !in_array($word, $this->stopwords);

        });

        $words = array_values($words);

        // Tambahkan Bigram
        $bigram = $this->createBigram($words);

        // Tambahkan Trigram
        $trigram = $this->createTrigram($words);

        // Gabungkan
        $words = array_merge(
            $words,
            $bigram,
            $trigram
        );

        // Hilangkan duplikat
        return array_values(array_unique($words));
    }

    /**
     * Membuat Bigram
     */
    private function createBigram(array $tokens): array
    {
        $result = [];

        $jumlah = count($tokens);

        for ($i = 0; $i < $jumlah - 1; $i++) {

            $result[] =
                $tokens[$i] .
                '_' .
                $tokens[$i + 1];

        }

        return $result;
    }

    /**
     * Membuat Trigram
     */
    private function createTrigram(array $tokens): array
    {
        $result = [];

        $jumlah = count($tokens);

        for ($i = 0; $i < $jumlah - 2; $i++) {

            $result[] =
                $tokens[$i] .
                '_' .
                $tokens[$i + 1] .
                '_' .
                $tokens[$i + 2];

        }

        return $result;
    }
}
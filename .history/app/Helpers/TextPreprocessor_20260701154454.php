<?php

namespace App\Helpers;

use Sastrawi\Stemmer\StemmerFactory;

class TextPreprocessor
{
    /**
     * Stemmer
     */
    protected $stemmer;

    /**
     * Stopword
     */
    protected array $stopwords = [

        'yang','dan','di','ke','dari','untuk',
        'agar','adalah','ini','itu','atau',
        'dengan','karena','setelah','sebelum',
        'sudah','telah','akan','sedang',
        'masih','jadi','oleh','sebagai',
        'perlu','bisa','tidak',
        'nya','pun','lah','kah'
    ];

    /**
     * Normalisasi Kata
     */
    protected array $slang = [

        // Umum
        'gk'=>'tidak',
        'ga'=>'tidak',
        'gak'=>'tidak',
        'tdk'=>'tidak',
        'nggak'=>'tidak',
        'gabisa'=>'tidak bisa',
        'gbs'=>'tidak bisa',

        'lemot'=>'lambat',
        'ngelag'=>'lambat',
        'lag'=>'lambat',

        'errornya'=>'error',

        // Server
        'srv'=>'server',
        'db'=>'database',
        'sql'=>'mysql',

        // Hardware
        'ram'=>'memory',
        'memori'=>'memory',
        'ssd'=>'harddisk',
        'hdd'=>'harddisk',

        // Internet
        'inet'=>'internet',
        'net'=>'internet',

        // WIFI
        'wi-fi'=>'wifi',
        'wifie'=>'wifi',

        // Kamera
        'cam'=>'kamera',
        'camera'=>'kamera',

        // BTS
        'gsm'=>'2g',
        'transmisi'=>'transmit',
        'memancar'=>'transmit',
        'pancar'=>'transmit',
        'signal'=>'sinyal',

        // Kendaraan
        'kendaraan'=>'mobil',
        'unit'=>'mobil',
        'zenix'=>'mobil'
    ];

    /**
     * Phrase Detection
     */
    protected array $phrases = [

        'server down',
        'access point',
        'force close',
        'tidak transmit',
        'gateway timeout',
        'database penuh',
        'database error',
        'tidak login',
        'tidak connect',
        'tidak terkoneksi',
        'tidak aktif',
        'tidak hidup',
        'tidak online',
        'wifi putus',
        'internet putus',
        'internet lambat',
        'server overload',
        'cpu tinggi',
        'memory penuh'
    ];

    public function __construct()
    {
        $factory = new StemmerFactory();

        $this->stemmer = $factory->createStemmer();
    }

    /**
     * Preprocessing utama
     */
    public function process(string $text): array
    {
        $text = $this->cleanText($text);

        $text = $this->normalizeSlang($text);

        $text = $this->replacePhrase($text);

        $text = $this->stemmer->stem($text);

        $tokens = explode(' ', trim($text));

        $tokens = $this->removeStopword($tokens);

        $tokens = array_values($tokens);

        $tokens = array_merge(
            $tokens,
            $this->createBigram($tokens),
            $this->createTrigram($tokens)
        );

        return array_values(array_unique($tokens));
    }

    /**
     * Bersihkan text
     */
    private function cleanText(string $text): string
    {
        $text = strtolower($text);

        $text = preg_replace('/[^a-z0-9\s]/', ' ', $text);

        $text = preg_replace('/\s+/', ' ', $text);

        return trim($text);
    }
    
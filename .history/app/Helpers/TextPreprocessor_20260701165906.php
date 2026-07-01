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
        'nya','pun','lah','kah',  'padahal',
        'tadi', 'pagi', 'dekat', 'parkiran','motor',
    ];

    /**
     * Normalisasi Kata
     */
    protected array $slang = [

        'deket' => 'dekat',
        'nyala' => 'aktif',
        'nyalanya' => 'aktif',
        'menyala' => 'aktif',
        'padam' => 'mati',
        'mendadak' => 'tiba',

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
        'memory penuh',
        'cctv mati',
        'kamera mati',
        'tidak menyala',
        'tidak tampil',
        'gambar hitam'
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

        $text = $this->normalizeNumber($text);

        $text = $this->normalizeSlang($text);

        $text = $this->replacePhrase($text);

        $text = $this->stemmer->stem($text);

        $tokens = explode(' ',trim($text));

        $tokens = $this->negationHandling($tokens);

        $tokens = $this->removeStopword($tokens);

        $tokens = $this->removeShortToken($tokens);

        $tokens = array_values($tokens);

        $tokens = array_merge(

            $tokens,

            $this->createBigram($tokens),

            $this->createTrigram($tokens)

        );

        return $this->uniqueToken($tokens);

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

        /**
     * Normalisasi slang
     */
    private function normalizeSlang(string $text): string
    {
        foreach ($this->slang as $asal => $tujuan) {

            $text = preg_replace(
                '/\b' . preg_quote($asal, '/') . '\b/u',
                $tujuan,
                $text
            );

        }

        return $text;
    }

    /**
     * Deteksi phrase
     * contoh:
     * server down -> server_down
     * access point -> access_point
     */
    private function replacePhrase(string $text): string
    {
        foreach ($this->phrases as $phrase) {

            $replace = str_replace(
                ' ',
                '_',
                $phrase
            );

            $text = preg_replace(
                '/\b'.preg_quote($phrase,'/').'\b/u',
                $replace,
                $text
            );

        }

        return $text;
    }

    /**
     * Stopword Removal
     */
    private function removeStopword(array $tokens): array
    {
        return array_filter($tokens, function ($word) {

            return $word != ''
                && !in_array($word, $this->stopwords);

        });
    }

    /**
     * Membuat Bigram
     */
    private function createBigram(array $tokens): array
    {
        $result = [];

        $jumlah = count($tokens);

        if ($jumlah < 2) {
            return [];
        }

        for ($i = 0; $i < $jumlah - 1; $i++) {

            $result[] =
                $tokens[$i]
                . '_'
                . $tokens[$i + 1];

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

        if ($jumlah < 3) {
            return [];
        }

        for ($i = 0; $i < $jumlah - 2; $i++) {

            $result[] =
                $tokens[$i]
                .'_'
                .$tokens[$i+1]
                .'_'
                .$tokens[$i+2];

        }

        return $result;
    }

        /**
     * Menghapus angka yang tidak penting
     * Contoh:
     * Error 404 -> error
     * Error 500 -> error
     * tetapi
     * 2G,3G,4G,5G tetap dipertahankan
     */
    private function normalizeNumber(string $text): string
    {
        // Pertahankan 2G 3G 4G 5G
        $text = preg_replace('/\b([2345])g\b/i', '__GEN__$1', $text);

        // Hilangkan angka murni
        $text = preg_replace('/\b\d+\b/', ' ', $text);

        // Kembalikan 2G dst
        $text = str_replace(
            ['__GEN__2','__GEN__3','__GEN__4','__GEN__5'],
            ['2g','3g','4g','5g'],
            $text
        );

        return $text;
    }

    /**
     * Menghapus token duplikat
     */
    private function uniqueToken(array $tokens): array
    {
        return array_values(array_unique($tokens));
    }

    /**
     * Menghapus token terlalu pendek
     */
    private function removeShortToken(array $tokens): array
    {
        return array_filter($tokens,function($token){

            if(in_array($token,['2g','3g','4g','5g']))
                return true;

            return strlen($token)>=2;

        });

    }

    /**
     * Negation Handling
     *
     * tidak login
     * ->
     * tidak_login
     */
    private function negationHandling(array $tokens): array
    {
        $result=[];

        $jumlah=count($tokens);

        for($i=0;$i<$jumlah;$i++){

            if(
                $tokens[$i]=='tidak'
                &&
                isset($tokens[$i+1])
            ){

                $result[]='tidak_'.$tokens[$i+1];

                $i++;

                continue;

            }

            $result[]=$tokens[$i];

        }

        return $result;
    }
}
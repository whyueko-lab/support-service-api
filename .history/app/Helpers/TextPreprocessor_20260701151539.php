<?php

namespace App\Helpers;

use Sastrawi\Stemmer\StemmerFactory;

class TextPreprocessor
{
    protected array $slang = [

        'lemot'=>'lambat',
        'ngadat'=>'error',
        'gabisa'=>'tidak bisa',
        'gak'=>'tidak',
        'ga'=>'tidak',
        'gk'=>'tidak',
        'tdk'=>'tidak',

        'wifi'=>'wifi',
        'wifie'=>'wifi',

        'inet'=>'internet',
        'net'=>'internet',

        'cam'=>'kamera',
        'camera'=>'kamera',

        'apps'=>'aplikasi',
        'app'=>'aplikasi',

        'srv'=>'server',

        'db'=>'database',

        'memori'=>'memory',
        'ram'=>'memory',

        'ssd'=>'harddisk',

        'gsm'=>'2g',

        'transmisi'=>'transmit',
        'pancar'=>'transmit',
        'memancar'=>'transmit',

        'sinyal'=>'signal',

        'kendaraan'=>'mobil',
        'unit'=>'mobil',
        'zenix'=>'mobil'
    ];

    protected array $stopwords = [

        'yang',
        'dan',
        'di',
        'ke',
        'dari',
        'untuk',
        'agar',
        'adalah',
        'ini',
        'itu',
        'atau',
        'dengan',
        'karena',
        'setelah',
        'sebelum',
        'sudah',
        'telah',
        'akan',
        'sedang',
        'masih',
        'jadi',
        'oleh',
        'sebagai',
        'perlu',
        'bisa',
        'tidak'
    ];

    protected $stemmer;

    public function __construct()
    {
        $factory = new StemmerFactory();
        $this->stemmer = $factory->createStemmer();
    }

    public function process(string $text): array
    {

        $words=array_values($words);

$words=$this->createBigram($words);

return $words;

        // lowercase
        $text = strtolower($text);

        // hapus simbol
        $text = preg_replace('/[^a-z0-9\s]/',' ',$text);

        // rapikan spasi
        $text = preg_replace('/\s+/',' ',$text);

        // normalisasi kata
        foreach($this->slang as $asal=>$tujuan){

            $text = preg_replace(
                '/\b'.preg_quote($asal,'/').'\b/',
                $tujuan,
                $text
            );

        }
        

        // stemming
        $text = $this->stemmer->stem($text);

        // tokenisasi
        $words = explode(' ',trim($text));

        // stopword
        $words = array_filter($words,function($word){

            return $word!=''
                && !in_array($word,$this->stopwords);

        });

        return array_values($words);

    }

    private function createBigram(array $tokens): array
    {

        $result = $tokens;

        $jumlah = count($tokens);

        for($i=0;$i<$jumlah-1;$i++){

            $result[] =
                $tokens[$i]
                .'_'.
                $tokens[$i+1];

        }

        return $result;

    }

}
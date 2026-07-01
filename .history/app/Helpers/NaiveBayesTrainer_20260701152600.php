<?php

namespace App\Helpers;

class NaiveBayesTrainer
{

    protected TextPreprocessor $preprocessor;

    public function __construct()
    {
        $this->preprocessor = new TextPreprocessor();
    }

    public function train(array $trainingData): array
    {

        $categoryCount = [];

        $wordCount = [];

        $totalWord = [];

        $documentFrequency = [];

        $vocabulary = [];

        $totalDocument = count($trainingData);

        foreach ($trainingData as $row) {

            $kategori = $row['kategori'];

            if (!isset($categoryCount[$kategori])) {

                $categoryCount[$kategori] = 0;

                $wordCount[$kategori] = [];

                $totalWord[$kategori] = 0;
            }

            $categoryCount[$kategori]++;

            $tokens = $this->preprocessor->process($row['text']);

            $uniqueWords = [];

            foreach ($tokens as $word) {

                if (!isset($wordCount[$kategori][$word])) {

                    $wordCount[$kategori][$word] = 0;
                }

                $wordCount[$kategori][$word]++;

                $totalWord[$kategori]++;

                $vocabulary[$word] = true;

                $uniqueWords[$word] = true;
            }

            foreach ($uniqueWords as $word => $dummy) {

                if (!isset($documentFrequency[$word])) {

                    $documentFrequency[$word] = 0;
                }

                $documentFrequency[$word]++;
            }
        }

        //----------------------------------------
        // Hitung IDF
        //----------------------------------------

        $idf = [];

        foreach ($documentFrequency as $word => $df) {

            $idf[$word] = log($totalDocument / (1 + $df));

        }

        //----------------------------------------
        // Hitung Prior
        //----------------------------------------

        $prior = [];

        foreach ($categoryCount as $kategori => $jumlah) {

            $prior[$kategori] = $jumlah / $totalDocument;

        }

        //----------------------------------------
        // Simpan Model
        //----------------------------------------

        return [

            'prior'=>$prior,

            'categoryCount'=>$categoryCount,

            'wordCount'=>$wordCount,

            'totalWord'=>$totalWord,

            'idf'=>$idf,

            'vocabulary'=>array_keys($vocabulary),

            'vocabularySize'=>count($vocabulary)

        ];

    }

}
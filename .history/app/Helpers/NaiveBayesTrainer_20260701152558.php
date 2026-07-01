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

            foreach ($tokens as $token) {

                if (!isset($wordCount[$kategori][$token])) {
                    $wordCount[$kategori][$token] = 0;
                }

                $wordCount[$kategori][$token]++;

                $totalWord[$kategori]++;

                $vocabulary[$token] = true;

                $uniqueWords[$token] = true;
            }

            foreach ($uniqueWords as $word => $dummy) {

                if (!isset($documentFrequency[$word])) {
                    $documentFrequency[$word] = 0;
                }

                $documentFrequency[$word]++;
            }
        }

        $idf = [];

        foreach ($documentFrequency as $word => $df) {

            $idf[$word] = log(
                $totalDocument /
                (1 + $df)
            );
        }

        $prior = [];

        foreach ($categoryCount as $kategori => $jumlah) {

            $prior[$kategori] = $jumlah / $totalDocument;

        }

        return [

            'prior' => $prior,

            'idf' => $idf,

            'wordCount' => $wordCount,

            'totalWord' => $totalWord,

            'categoryCount' => $categoryCount,

            'vocabularySize' => count($vocabulary),

            'vocabulary' => array_keys($vocabulary)

        ];
    }
}
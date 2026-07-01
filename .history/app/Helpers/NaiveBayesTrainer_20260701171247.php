<?php

namespace App\Helpers;

class NaiveBayesTrainer
{
    protected TextPreprocessor $preprocessor;

    public function __construct()
    {
        $this->preprocessor = new TextPreprocessor();
    }

    public function train(array $dataset): array
    {
        $categoryCount = [];
        $wordCount = [];
        $totalWord = [];
        $vocabulary = [];

        $totalDocument = count($dataset);

        foreach ($dataset as $row) {

            $kategori = strtoupper($row['kategori']);

            if (!isset($categoryCount[$kategori])) {
                $categoryCount[$kategori] = 0;
                $wordCount[$kategori] = [];
                $totalWord[$kategori] = 0;
            }

            $categoryCount[$kategori]++;

            $tokens = $this->preprocessor->process(
                $row['text']
            );

            foreach ($tokens as $token) {

                if (!isset($wordCount[$kategori][$token])) {
                    $wordCount[$kategori][$token] = 0;
                }

                $wordCount[$kategori][$token]++;

                $totalWord[$kategori]++;

                $vocabulary[$token] = true;
            }
        }

        $prior = [];

        foreach ($categoryCount as $kategori => $jumlah) {
            $prior[$kategori] =
                $jumlah / $totalDocument;
        }

        return [

            'prior' => $prior,

            'wordCount' => $wordCount,

            'totalWord' => $totalWord,

            'categoryCount' => $categoryCount,

            'vocabulary' => array_keys($vocabulary),

            'vocabularySize' => count($vocabulary),

            'totalDocument' => $totalDocument,

            'created_at' => date('Y-m-d H:i:s')
        ];
    }
}
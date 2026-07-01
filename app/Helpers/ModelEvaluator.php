<?php

namespace App\Helpers;

class ModelEvaluator
{
    /**
     * Split dataset menjadi train dan test
     */
    public function splitDataset(array $dataset, float $trainRatio = 0.8): array
    {
        shuffle($dataset);

        $split = (int) floor(count($dataset) * $trainRatio);

        return [
            'train' => array_slice($dataset, 0, $split),
            'test'  => array_slice($dataset, $split)
        ];
    }

    public function evaluate(array $dataset): array
    {
        $split = $this->splitDataset($dataset);

        $trainer = new NaiveBayesTrainer();

        $model = $trainer->train($split['train']);

        $classifier = new NaiveBayesClassifier($model);

        $correct = 0;

        $total = count($split['test']);

        $predictions = [];

        foreach ($split['test'] as $row) {

            $hasil = $classifier->predict($row['text']);

            $predictions[] = [

                'actual' => strtoupper($row['kategori']),

                'predict' => strtoupper($hasil['kategori'])

            ];

            if (
                strtoupper($row['kategori'])
                ==
                strtoupper($hasil['kategori'])
            ) {

                $correct++;

            }

        }

        return [

            'accuracy' => round(($correct / $total) * 100, 2),

            'prediction' => $predictions,

            'train' => count($split['train']),

            'test' => count($split['test'])

        ];
    }
}
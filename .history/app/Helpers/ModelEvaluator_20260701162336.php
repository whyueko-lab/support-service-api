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
}
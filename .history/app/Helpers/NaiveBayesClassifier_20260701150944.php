<?php

namespace App\Helpers;

class NaiveBayesClassifier
{

    protected TextPreprocessor $preprocessor;

    protected array $model;

    public function __construct(array $model)
    {
        $this->preprocessor = new TextPreprocessor();

        $this->model = $model;
    }

    public function predict(string $text): array
    {

        $tokens = $this->preprocessor->process($text);

        $scores = [];

        $vocabSize = $this->model['vocabularySize'];

        foreach ($this->model['prior'] as $kategori => $prior) {

            $score = log($prior);

            foreach ($tokens as $word) {

                $tf = $this->model['wordCount'][$kategori][$word] ?? 0;

                $idf = $this->model['idf'][$word] ?? 1;

                $likelihood = (($tf * $idf) + 1)
                    /
                    ($this->model['totalWord'][$kategori] + $vocabSize);

                $score += log($likelihood);

            }

            $scores[$kategori] = $score;

        }

        //---------------------------------
        // Softmax
        //---------------------------------

        $max = max($scores);

        $exp = [];

        $sum = 0;

        foreach($scores as $k=>$v){

            $exp[$k] = exp($v-$max);

            $sum += $exp[$k];

        }

        $confidence = [];

        foreach($exp as $k=>$v){

            $confidence[$k] = round(
                ($v/$sum)*100,
                2
            );

        }

        arsort($confidence);

        $kategori = array_key_first($confidence);

        return [

            'kategori'=>$kategori,

            'score'=>$confidence[$kategori],

            'ranking'=>$confidence

        ];

    }

}
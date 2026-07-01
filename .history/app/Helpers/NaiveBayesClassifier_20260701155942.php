<?php

namespace App\Helpers;

class NaiveBayesClassifier
{

    protected array $model;

    protected TextPreprocessor $preprocessor;

    public function __construct(array $model)
    {

        $this->model = $model;

        $this->preprocessor = new TextPreprocessor();

    }

    /**
     * Prediksi kategori
     */
    public function predict(string $text): array
    {

        $tokens = $this->preprocessor->process($text);

        $score = [];

        $vocabSize = $this->model['vocabularySize'];

        foreach($this->model['prior'] as $kategori=>$prior){

            $logProb = log($prior);

            foreach($tokens as $token){

                $tf =
                    $this->model['wordCount'][$kategori][$token]
                    ??
                    0;

                $totalWord =
                    $this->model['totalWord'][$kategori];

                $prob =

                    ($tf+1)

                    /

                    ($totalWord+$vocabSize);

                $logProb += log($prob);

            }

            $score[$kategori] = $logProb;

        }

                arsort($score);

        $hasil = array_key_first($score);

        return [

            'kategori'=>$hasil,

            'score'=>$score,

            'token'=>$tokens

        ];

    }

}
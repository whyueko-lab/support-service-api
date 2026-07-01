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
        
        $ranking = $this->softmax($score);

        $hasil = array_key_first($ranking);
        $confidence = current($ranking);

        if ($confidence >= 80) {
            $prioritas = 'high';
        } elseif ($confidence >= 50) {
            $prioritas = 'medium';
        } else {
            $prioritas = 'low';
        }

        return [

            'kategori'   => $hasil,
            'score'      => $confidence,
            'prioritas'  => $prioritas,
            'confidence' => $confidence,
            'ranking'    => $ranking,
            'token'      => $tokens

        ];

    }

    private function softmax(array $score): array
    {
        $exp = [];

        foreach ($score as $kategori => $value) {

            $exp[$kategori] = exp($value);

        }

        $total = array_sum($exp);

        foreach ($exp as $kategori => $value) {

            $exp[$kategori] = round(

                ($value / $total) * 100,

                2

            );

        }

        arsort($exp);

        return $exp;
    }

}
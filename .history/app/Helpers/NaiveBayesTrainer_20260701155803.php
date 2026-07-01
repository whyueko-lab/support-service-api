<?php

namespace App\Helpers;

class NaiveBayesTrainer
{
    protected TextPreprocessor $preprocessor;

    public function __construct()
    {
        $this->preprocessor = new TextPreprocessor();
    }

    /**
     * Training Model
     */
    public function train(array $dataset): array
    {

        $categoryCount = [];

        $wordCount = [];

        $totalWord = [];

        $documentFrequency = [];

        $vocabulary = [];

        $totalDocument = count($dataset);

        foreach($dataset as $row){

            $kategori = strtoupper($row['kategori']);

            if(!isset($categoryCount[$kategori])){

                $categoryCount[$kategori]=0;

                $wordCount[$kategori]=[];

                $totalWord[$kategori]=0;

            }

            $categoryCount[$kategori]++;

            $tokens = $this->preprocessor->process(

                $row['text']

            );

            $unique = [];

            foreach($tokens as $token){

                if(!isset($wordCount[$kategori][$token])){

                    $wordCount[$kategori][$token]=0;

                }

                $wordCount[$kategori][$token]++;

                $totalWord[$kategori]++;

                $vocabulary[$token]=true;

                $unique[$token]=true;

            }

            foreach($unique as $word=>$dummy){

                if(!isset($documentFrequency[$word])){

                    $documentFrequency[$word]=0;

                }

                $documentFrequency[$word]++;

            }

        }
        
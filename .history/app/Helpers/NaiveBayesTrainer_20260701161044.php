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

            $tf = [];

            foreach($tokens as $token){

                if(!isset($tf[$token])){

                    $tf[$token]=0;

                }

                $tf[$token]++;

            }

            foreach($tf as $token=>$freq){

                if(!isset($wordCount[$kategori][$token])){

                    $wordCount[$kategori][$token]=0;

                }

                $wordCount[$kategori][$token]+=$freq;

                $totalWord[$kategori]+=$freq;

                $vocabulary[$token]=true;

                $unique[$token]=true;

            }

        }

                /**
         * Hitung IDF
         */
        $idf=[];

        foreach($documentFrequency as $word=>$df){

            $idf[$word]=log(

                $totalDocument

                /

                (1+$df)

            );

        }

        /**
         * Prior Probability
         */

        $prior=[];

        foreach($categoryCount as $kategori=>$jumlah){

            $prior[$kategori]

                =

            $jumlah

            /

            $totalDocument;

        }

        return [

            'prior'=>$prior,

            'idf'=>$idf,

            'wordCount'=>$wordCount,

            'totalWord'=>$totalWord,

            'categoryCount'=>$categoryCount,

            'vocabulary'=>array_keys($vocabulary),

            'vocabularySize'=>count($vocabulary),

            'totalDocument'=>$totalDocument,

            'created_at'=>date('Y-m-d H:i:s')

        ];

    }

}
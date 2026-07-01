<?php

namespace App\Controllers;

use App\Helpers\NaiveBayesTrainer;
use App\Helpers\ModelManager;

class Training extends BaseController
{
    public function index()
    {
        $trainingFile = WRITEPATH . 'training.json';

        if (!file_exists($trainingFile)) {

            return $this->response->setJSON([
                'status'=>false,
                'message'=>'training.json tidak ditemukan'
            ]);

        }

        $trainingData = json_decode(
            file_get_contents($trainingFile),
            true
        );

        $trainer = new NaiveBayesTrainer();

        $model = $trainer->train($trainingData);

        $manager = new ModelManager();

        $manager->save($model);

        return $this->response->setJSON([
            'status'=>true,
            'message'=>'Training berhasil',
            'jumlah'=>count($trainingData)
        ]);
    }
}
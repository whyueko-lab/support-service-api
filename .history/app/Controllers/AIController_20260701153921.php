<?php

namespace App\Controllers;

use App\Helpers\ModelManager;
use App\Helpers\NaiveBayesTrainer;

class AI extends BaseController
{
    public function train()
    {
        $trainingPath = WRITEPATH . 'ai/training.json';

        if (!file_exists($trainingPath)) {

            return $this->response->setJSON([
                'status' => false,
                'message' => 'training.json tidak ditemukan'
            ]);

        }

        $trainingData = json_decode(
            file_get_contents($trainingPath),
            true
        );

        $trainer = new NaiveBayesTrainer();

        $model = $trainer->train($trainingData);

        $manager = new ModelManager();

        $manager->save($model);

        return $this->response->setJSON([
            'status' => true,
            'jumlah_data' => count($trainingData),
            'model' => $manager->getPath()
        ]);
    }
}
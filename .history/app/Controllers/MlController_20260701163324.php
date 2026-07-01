<?php

namespace App\Controllers;

use App\Helpers\DatasetGenerator;
use App\Helpers\NaiveBayesTrainer;
use App\Helpers\ModelManager;
use App\Helpers\ModelEvaluator;

class MlController extends BaseController
{
    public function index()
    {
        // 1. Dataset (bisa dari generator atau manual)
        $generator = new DatasetGenerator();

        $dataset = $generator->generate([
            ['text' => 'bts tidak transmit setelah restart', 'kategori' => 'BTS'],
            ['text' => 'server down', 'kategori' => 'SERVER'],
        ]);

        // 2. Training model
        $trainer = new NaiveBayesTrainer();
        $model = $trainer->train($dataset);

        // 3. Simpan model (opsional tapi penting)
        $modelManager = new ModelManager();
        $modelManager->save($model);

        // 4. Load model untuk evaluasi / prediksi
        $loadedModel = $modelManager->load();

        // 5. Evaluasi
        $evaluator = new ModelEvaluator();
        $result = $evaluator->evaluate($loadedModel, $dataset);

        return $this->response->setJSON($result);
    }
}
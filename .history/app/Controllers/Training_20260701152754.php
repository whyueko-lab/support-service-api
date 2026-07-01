<?php

namespace App\Http\Controllers;

use App\Helpers\NaiveBayesTrainer;
use App\Helpers\ModelManager;

class TrainingController extends Controller
{
    public function train()
    {
        $path = storage_path('app/training.json');

        if (!file_exists($path)) {
            return response()->json([
                'status' => false,
                'message' => 'training.json tidak ditemukan'
            ]);
        }

        $trainingData = json_decode(
            file_get_contents($path),
            true
        );

        $trainer = new NaiveBayesTrainer();

        $model = $trainer->train($trainingData);

        $manager = new ModelManager();

        $manager->save($model);

        return response()->json([
            'status' => true,
            'message' => 'Training berhasil',
            'jumlah_data' => count($trainingData)
        ]);
    }
}
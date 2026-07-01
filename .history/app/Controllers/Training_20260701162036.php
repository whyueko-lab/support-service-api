<?php

namespace App\Controllers;

use App\Helpers\DatasetGenerator;
use App\Helpers\NaiveBayesTrainer;
use App\Helpers\ModelManager;

class Training extends BaseController
{
    public function index()
    {
        // Generate dataset otomatis
        $generator = new DatasetGenerator();

        $generator->generateAll();

        $trainingData = $generator->get();

        // Simpan training.json (opsional)
        file_put_contents(
            WRITEPATH . 'training.json',
            json_encode(
                $trainingData,
                JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
            )
        );

        // Training model
        $trainer = new NaiveBayesTrainer();

        $model = $trainer->train($trainingData);

        // Simpan model
        $manager = new ModelManager();

        $manager->save($model);

        return $this->response->setJSON([
            'status' => true,
            'message' => 'Training berhasil',
            'jumlah_data' => count($trainingData),
            'kategori' => count($model['prior']),
            'vocabulary' => $model['vocabularySize']
        ]);
    }

    public function test()
{
    $manager = new \App\Helpers\ModelManager();

    $model = $manager->load();

    $classifier = new \App\Helpers\NaiveBayesClassifier($model);

    $text = "BTS tidak transmit setelah restart";

    $result = $classifier->predict($text);

    return $this->response->setJSON([
        'input' => $text,
        'hasil' => $result
    ]);
}
}
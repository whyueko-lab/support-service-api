<?php

namespace App\Controllers;

use App\Helpers\DatasetGenerator;
use App\Helpers\NaiveBayesTrainer;
use App\Helpers\ModelManager;

class AIController extends BaseController
{
    public function trainModel()
    {
        // 1. Generate semua data training otomatis
        $generator = new DatasetGenerator();
        $generator->generateAll();
        
        // Ambil array dataset hasil generate
        // (Pastikan di class DatasetGenerator kamu punya properti/method untuk mengambil $dataset)
        $dataset = $generator->getDataset(); 

        // 2. Latih data menggunakan Trainer
        $trainer = new NaiveBayesTrainer();
        $modelData = $trainer->train($dataset);

        // 3. Simpan hasil kalkulasi probabilitas ke model.json
        $manager = new ModelManager();
        $manager->save($modelData);

        return "Training selesai! File model.json berhasil dibuat di " . $manager->getPath();
    }
}
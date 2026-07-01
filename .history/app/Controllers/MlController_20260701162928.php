<?php

namespace App\Controllers;

use App\Helpers\ModelEvaluator;

class MlController extends BaseController
{
    public function index()
    {
        $dataset = [
            ['text' => 'bts tidak transmit setelah restart', 'kategori' => 'BTS'],
            ['text' => 'server down', 'kategori' => 'SERVER'],
        ];

        $evaluator = new ModelEvaluator();
        return $this->response->setJSON($evaluator->evaluate($dataset));
    }
}
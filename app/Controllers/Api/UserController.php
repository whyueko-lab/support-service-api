<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\UserModel;

class UserController extends BaseController
{
    public function index()
    {
        $userModel = new UserModel();

        return $this->response->setJSON([
            'status' => true,
            'message' => 'Data user berhasil diambil',
            'data' => $userModel->findAll()
        ]);
    }
}
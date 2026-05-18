<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\UserModel;

class PasswordFixController extends BaseController
{
    public function fix()
    {
        $userModel = new UserModel();

        $users = $userModel->findAll();

        foreach ($users as $user) {
            if ($user['password'] === '123456') {
                $userModel->update($user['id_user'], [
                    'password' => password_hash('123456', PASSWORD_DEFAULT)
                ]);
            }
        }

        return $this->response->setJSON([
            'status' => true,
            'message' => 'Password user lama berhasil diubah menjadi hash'
        ]);
    }
}
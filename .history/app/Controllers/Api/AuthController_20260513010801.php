<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\UserModel;

class AuthController extends BaseController
{
    public function register()
    {
        $userModel = new UserModel();

        $nama     = $this->request->getPost('nama');
        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $role     = $this->request->getPost('role') ?? 'customer';

        if (!$nama || !$email || !$password) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Nama, email, dan password wajib diisi'
            ]);
        }

        $allowedRole = ['customer', 'admin', 'teknisi'];

        if (!in_array($role, $allowedRole)) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Role tidak valid'
            ]);
        }

        $cekEmail = $userModel
            ->where('email', $email)
            ->first();

        if ($cekEmail) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Email sudah terdaftar'
            ]);
        }

        $data = [
            'nama' => $nama,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role' => $role,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $userModel->insert($data);

        return $this->response->setJSON([
            'status' => true,
            'message' => 'Registrasi berhasil',
            'data' => [
                'id_user' => $userModel->insertID(),
                'nama' => $nama,
                'email' => $email,
                'role' => $role
            ]
        ]);
    }

    public function login()
    {
        $userModel = new UserModel();

        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        if (!$email || !$password) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Email dan password wajib diisi'
            ]);
        }

        $user = $userModel
            ->where('email', $email)
            ->first();

        if (!$user) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Email tidak ditemukan'
            ]);
        }

        if (($user['status_user'] ?? 'aktif') !== 'aktif') {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Akun Anda sedang nonaktif'
            ]);
        }

        if (!password_verify($password, $user['password'])) {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Password salah'
            ]);
        }

        return $this->response->setJSON([
            'status' => true,
            'message' => 'Login berhasil',
            'data' => [
                'id_user' => $user['id_user'],
                'nama' => $user['nama'],
                'email' => $user['email'],
                'role' => $user['role']
            ]
        ]);
    }
}
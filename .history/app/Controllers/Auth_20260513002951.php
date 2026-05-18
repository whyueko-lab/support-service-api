<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        if (session()->get('logged_in')) {
            return redirect()->to('/dashboard');
        }

        return view('auth/login', [
            'title' => 'Login Admin'
        ]);
    }

    public function processLogin()
    {
        $userModel = new UserModel();

        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        if (!$email || !$password) {
            return redirect()->back()->with('error', 'Email dan password wajib diisi');
        }

        $user = $userModel
            ->where('email', $email)
            ->first();

        if (!$user) {
            return redirect()->back()->with('error', 'Email tidak ditemukan');
        }

        if (!password_verify($password, $user['password'])) {
            return redirect()->back()->with('error', 'Password salah');
        }

        if ($user['role'] !== 'admin') {
            return redirect()->back()->with('error', 'Akses hanya untuk admin');
        }

        session()->set([
            'logged_in' => true,
            'id_user'   => $user['id_user'],
            'nama'      => $user['nama'],
            'email'     => $user['email'],
            'role'      => $user['role']
        ]);

        return redirect()->to('/dashboard');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
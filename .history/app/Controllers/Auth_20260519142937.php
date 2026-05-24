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

        if (($user['status_user'] ?? 'aktif') !== 'aktif') {
            return redirect()->back()->with('error', 'Akun Anda sedang nonaktif');
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

    // =========================================================
    // BARU: Menampilkan Halaman Register Web
    // =========================================================
    public function register()
    {
        if (session()->get('logged_in')) {
            return redirect()->to('/dashboard');
        }

        return view('auth/register', [
            'title' => 'Daftar Akun Admin Baru'
        ]);
    }

    // =========================================================
    // BARU: Memproses Pendaftaran Akun Admin dari Web
    // =========================================================
    public function processRegister()
    {
        $userModel = new UserModel();

        $nama     = $this->request->getPost('nama');
        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // 1. Validasi Input Kosong
        if (!$nama || !$email || !$password) {
            return redirect()->back()->with('error', 'Semua kolom wajib diisi')->withInput();
        }

        // 2. Cek Apakah Email Sudah Digunakan
        $existingUser = $userModel->where('email', $email)->first();
        if ($existingUser) {
            return redirect()->back()->with('error', 'Email sudah terdaftar, silakan gunakan email lain')->withInput();
        }

        // 3. Simpan Data User Baru ke Database
        $userModel->save([
            'nama'        => $nama,
            'email'       => $email,
            'password'    => password_hash($password, PASSWORD_DEFAULT), // Enkripsi password aman
            'role'        => 'admin', // Set otomatis sebagai admin sesuai kebutuhan login dashboard Anda
            'status_user' => 'aktif'
        ]);

        // Redirect ke halaman login dengan pesan sukses
        return redirect()->to('/login')->with('success', 'Pendaftaran admin berhasil! Silakan login.');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
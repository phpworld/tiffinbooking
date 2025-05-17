<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Auth extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        return redirect()->to('/auth/login');
    }

    public function login()
    {
        if ($this->request->getMethod() === 'POST') {
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');

            $user = $this->userModel->where('email', $email)->first();

            if ($user && password_verify($password, $user['password'])) {
                $session = session();
                $userData = [
                    'user_id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'logged_in' => true,
                    'is_admin' => false
                ];

                $session->set($userData);
                return redirect()->to('/user/dashboard');
            } else {
                return redirect()->back()->with('error', 'Invalid email or password');
            }
        }

        return view('auth/login');
    }

    public function register()
    {
        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'name' => 'required|min_length[3]|max_length[100]',
                'email' => 'required|valid_email|is_unique[users.email]',
                'password' => 'required|min_length[6]',
                'confirm_password' => 'required|matches[password]',
                'phone' => 'permit_empty|numeric|min_length[10]',
            ];

            if ($this->validate($rules)) {
                $data = [
                    'name' => $this->request->getPost('name'),
                    'email' => $this->request->getPost('email'),
                    'password' => $this->request->getPost('password'),
                    'phone' => $this->request->getPost('phone'),
                    'address' => $this->request->getPost('address'),
                ];

                $this->userModel->insert($data);

                return redirect()->to('/auth/login')->with('success', 'Registration successful. Please login.');
            } else {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }
        }

        return view('auth/register');
    }

    public function logout()
    {
        $session = session();
        $session->destroy();

        return redirect()->to('/auth/login');
    }
}

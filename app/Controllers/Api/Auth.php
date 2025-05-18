<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\API\ResponseTrait;

class Auth extends BaseController
{
    use ResponseTrait;

    protected $userModel;

    public function __construct()
    {
        helper('jwt');
        $this->userModel = new UserModel();
    }

    /**
     * Login API endpoint
     *
     * @return \CodeIgniter\HTTP\Response
     */
    public function login()
    {
        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required|min_length[6]'
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        // Get raw input data
        $jsonInput = $this->request->getJSON();

        // Try to get email and password from JSON input first
        if (!empty($jsonInput) && isset($jsonInput->email) && isset($jsonInput->password)) {
            $email = $jsonInput->email;
            $password = $jsonInput->password;
        } else {
            // Fall back to form data
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');
        }

        // Log the input for debugging
        log_message('debug', 'Login attempt with email: ' . $email);

        // Find user by email (case-insensitive)
        $user = null;
        $users = $this->userModel->findAll();
        foreach ($users as $u) {
            if (strtolower($u['email']) === strtolower($email)) {
                $user = $u;
                break;
            }
        }

        if (!$user) {
            return $this->failNotFound('User not found with the provided email.');
        }

        if (!password_verify($password, $user['password'])) {
            return $this->fail('Invalid password.', 401);
        }

        // Check if user is admin (if is_admin field exists)
        if (isset($user['is_admin']) && $user['is_admin']) {
            return $this->fail('Admin users cannot login through the mobile app.', 403);
        }

        // Generate JWT token
        $userData = [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email']
        ];

        $token = generateJWTToken($userData);

        $response = [
            'status' => true,
            'message' => 'Login successful',
            'data' => [
                'user' => [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'phone' => $user['phone'],
                    'address' => $user['address']
                ],
                'token' => $token
            ]
        ];

        return $this->respond($response);
    }

    /**
     * Register API endpoint
     *
     * @return \CodeIgniter\HTTP\Response
     */
    public function register()
    {
        $rules = [
            'name' => 'required|min_length[3]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'phone' => 'required|min_length[10]',
            'address' => 'required|min_length[5]'
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'phone' => $this->request->getPost('phone'),
            'address' => $this->request->getPost('address'),
            'is_admin' => 0
        ];

        $this->userModel->insert($data);
        $userId = $this->userModel->getInsertID();

        // Generate JWT token
        $userData = [
            'id' => $userId,
            'name' => $data['name'],
            'email' => $data['email']
        ];

        $token = generateJWTToken($userData);

        $response = [
            'status' => true,
            'message' => 'Registration successful',
            'data' => [
                'user' => [
                    'id' => $userId,
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'phone' => $data['phone'],
                    'address' => $data['address']
                ],
                'token' => $token
            ]
        ];

        return $this->respond($response, 201);
    }
}

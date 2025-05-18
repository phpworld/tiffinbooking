<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\WalletModel;
use CodeIgniter\API\ResponseTrait;

class User extends BaseController
{
    use ResponseTrait;
    
    protected $userModel;
    protected $walletModel;
    
    public function __construct()
    {
        helper('jwt');
        $this->userModel = new UserModel();
        $this->walletModel = new WalletModel();
    }
    
    /**
     * Get user profile
     *
     * @return \CodeIgniter\HTTP\Response
     */
    public function profile()
    {
        $userData = getUserFromToken();
        
        if (!$userData) {
            return $this->failUnauthorized('Invalid or expired token');
        }
        
        $userId = $userData['id'];
        $user = $this->userModel->find($userId);
        
        if (!$user) {
            return $this->failNotFound('User not found');
        }
        
        // Get wallet balance
        $wallet = $this->walletModel->where('user_id', $userId)->first();
        $balance = $wallet ? $wallet['balance'] : 0;
        
        $response = [
            'status' => true,
            'data' => [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'phone' => $user['phone'],
                'address' => $user['address'],
                'wallet_balance' => $balance
            ]
        ];
        
        return $this->respond($response);
    }
    
    /**
     * Update user profile
     *
     * @return \CodeIgniter\HTTP\Response
     */
    public function updateProfile()
    {
        $userData = getUserFromToken();
        
        if (!$userData) {
            return $this->failUnauthorized('Invalid or expired token');
        }
        
        $userId = $userData['id'];
        $user = $this->userModel->find($userId);
        
        if (!$user) {
            return $this->failNotFound('User not found');
        }
        
        $rules = [
            'name' => 'required|min_length[3]',
            'phone' => 'required|min_length[10]',
            'address' => 'required|min_length[5]'
        ];
        
        // Only validate email if it's changed
        $email = $this->request->getPost('email');
        if ($email && $email !== $user['email']) {
            $rules['email'] = 'required|valid_email|is_unique[users.email,id,' . $userId . ']';
        }
        
        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }
        
        $data = [
            'name' => $this->request->getPost('name'),
            'phone' => $this->request->getPost('phone'),
            'address' => $this->request->getPost('address')
        ];
        
        // Only update email if it's provided and different
        if ($email && $email !== $user['email']) {
            $data['email'] = $email;
        }
        
        // Update password if provided
        $password = $this->request->getPost('password');
        if ($password) {
            if (strlen($password) < 6) {
                return $this->fail('Password must be at least 6 characters long');
            }
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }
        
        $this->userModel->update($userId, $data);
        
        // Get updated user data
        $updatedUser = $this->userModel->find($userId);
        
        $response = [
            'status' => true,
            'message' => 'Profile updated successfully',
            'data' => [
                'id' => $updatedUser['id'],
                'name' => $updatedUser['name'],
                'email' => $updatedUser['email'],
                'phone' => $updatedUser['phone'],
                'address' => $updatedUser['address']
            ]
        ];
        
        return $this->respond($response);
    }
}

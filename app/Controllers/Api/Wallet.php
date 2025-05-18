<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\WalletModel;
use App\Models\WalletTransactionModel;
use CodeIgniter\API\ResponseTrait;

class Wallet extends BaseController
{
    use ResponseTrait;
    
    protected $walletModel;
    protected $walletTransactionModel;
    
    public function __construct()
    {
        helper('jwt');
        $this->walletModel = new WalletModel();
        $this->walletTransactionModel = new WalletTransactionModel();
    }
    
    /**
     * Get wallet details
     *
     * @return \CodeIgniter\HTTP\Response
     */
    public function index()
    {
        $userData = getUserFromToken();
        
        if (!$userData) {
            return $this->failUnauthorized('Invalid or expired token');
        }
        
        $userId = $userData['id'];
        
        // Get wallet
        $wallet = $this->walletModel->where('user_id', $userId)->first();
        
        if (!$wallet) {
            // Create wallet if it doesn't exist
            $walletData = [
                'user_id' => $userId,
                'balance' => 0
            ];
            
            $this->walletModel->insert($walletData);
            $walletId = $this->walletModel->getInsertID();
            
            $wallet = [
                'id' => $walletId,
                'user_id' => $userId,
                'balance' => 0
            ];
        }
        
        // Get recent transactions
        $transactions = $this->walletTransactionModel->where('wallet_id', $wallet['id'])
                                                    ->orderBy('created_at', 'DESC')
                                                    ->limit(10)
                                                    ->findAll();
        
        $processedTransactions = [];
        foreach ($transactions as $transaction) {
            $processedTransactions[] = [
                'id' => $transaction['id'],
                'amount' => $transaction['amount'],
                'type' => $transaction['type'],
                'description' => $transaction['description'],
                'date' => date('Y-m-d H:i:s', strtotime($transaction['created_at']))
            ];
        }
        
        $response = [
            'status' => true,
            'data' => [
                'balance' => $wallet['balance'],
                'transactions' => $processedTransactions
            ]
        ];
        
        return $this->respond($response);
    }
    
    /**
     * Recharge wallet
     *
     * @return \CodeIgniter\HTTP\Response
     */
    public function recharge()
    {
        $userData = getUserFromToken();
        
        if (!$userData) {
            return $this->failUnauthorized('Invalid or expired token');
        }
        
        $userId = $userData['id'];
        
        $rules = [
            'amount' => 'required|numeric|greater_than[0]'
        ];
        
        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }
        
        $amount = $this->request->getPost('amount');
        
        // Get wallet
        $wallet = $this->walletModel->where('user_id', $userId)->first();
        
        if (!$wallet) {
            // Create wallet if it doesn't exist
            $walletData = [
                'user_id' => $userId,
                'balance' => $amount
            ];
            
            $this->walletModel->insert($walletData);
            $walletId = $this->walletModel->getInsertID();
        } else {
            // Update existing wallet
            $newBalance = $wallet['balance'] + $amount;
            $this->walletModel->update($wallet['id'], ['balance' => $newBalance]);
            $walletId = $wallet['id'];
        }
        
        // Add transaction record
        $transactionData = [
            'wallet_id' => $walletId,
            'amount' => $amount,
            'type' => 'credit',
            'description' => 'Wallet recharge'
        ];
        
        $this->walletTransactionModel->insert($transactionData);
        
        // Get updated wallet
        $updatedWallet = $this->walletModel->find($walletId);
        
        $response = [
            'status' => true,
            'message' => 'Wallet recharged successfully',
            'data' => [
                'balance' => $updatedWallet['balance'],
                'amount_added' => $amount
            ]
        ];
        
        return $this->respond($response);
    }
}

<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateWalletTransactions extends Migration
{
    public function up()
    {
        // Check if user_id column exists
        $fields = $this->db->getFieldData('wallet_transactions');
        $hasUserIdColumn = false;
        $hasWalletIdColumn = false;
        
        foreach ($fields as $field) {
            if ($field->name === 'user_id') {
                $hasUserIdColumn = true;
            }
            if ($field->name === 'wallet_id') {
                $hasWalletIdColumn = true;
            }
        }
        
        // If wallet_id column doesn't exist, add it
        if (!$hasWalletIdColumn) {
            $this->forge->addColumn('wallet_transactions', [
                'wallet_id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'null' => true,
                    'after' => 'id'
                ]
            ]);
        }
        
        // If both columns exist, we need to update wallet_id with the correct values
        if ($hasUserIdColumn && $hasWalletIdColumn) {
            // Get all transactions with user_id but no wallet_id
            $transactions = $this->db->table('wallet_transactions')
                ->where('wallet_id IS NULL')
                ->where('user_id IS NOT NULL')
                ->get()
                ->getResultArray();
            
            foreach ($transactions as $transaction) {
                // Find the wallet for this user
                $wallet = $this->db->table('wallets')
                    ->where('user_id', $transaction['user_id'])
                    ->get()
                    ->getRowArray();
                
                if ($wallet) {
                    // Update the transaction with the wallet_id
                    $this->db->table('wallet_transactions')
                        ->where('id', $transaction['id'])
                        ->update(['wallet_id' => $wallet['id']]);
                }
            }
        }
    }

    public function down()
    {
        // No need to remove the column as it's now part of the schema
    }
}

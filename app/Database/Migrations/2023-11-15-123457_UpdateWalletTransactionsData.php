<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateWalletTransactionsData extends Migration
{
    public function up()
    {
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

    public function down()
    {
        // No need to revert this data migration
    }
}

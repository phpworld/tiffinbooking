<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ModifyWalletTransactions extends Migration
{
    public function up()
    {
        // Make user_id nullable
        $this->forge->modifyColumn('wallet_transactions', [
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ]
        ]);
    }

    public function down()
    {
        // Make user_id NOT NULL
        $this->forge->modifyColumn('wallet_transactions', [
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
            ]
        ]);
    }
}

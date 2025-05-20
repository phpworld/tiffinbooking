<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPaymentIdToBookings extends Migration
{
    public function up()
    {
        $this->forge->addColumn('bookings', [
            'payment_id' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'payment_method'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('bookings', 'payment_id');
    }
}

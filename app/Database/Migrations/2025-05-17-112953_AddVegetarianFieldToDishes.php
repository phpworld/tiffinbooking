<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddVegetarianFieldToDishes extends Migration
{
    public function up()
    {
        $this->forge->addColumn('dishes', [
            'is_vegetarian' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
                'null'       => false,
                'after'      => 'available',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('dishes', 'is_vegetarian');
    }
}

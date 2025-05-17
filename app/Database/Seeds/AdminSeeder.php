<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\AdminModel;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $adminModel = new AdminModel();

        // Check if admin already exists
        $existingAdmin = $adminModel->where('username', 'admin')->first();

        if (!$existingAdmin) {
            $adminModel->insert([
                'username' => 'admin',
                'password' => 'admin123', // Will be hashed by the model
                'name' => 'Administrator'
            ]);

            echo "Admin user created successfully.\n";
        } else {
            echo "Admin user already exists.\n";
        }
    }
}

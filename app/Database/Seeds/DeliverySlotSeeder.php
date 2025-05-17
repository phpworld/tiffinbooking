<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\DeliverySlotModel;

class DeliverySlotSeeder extends Seeder
{
    public function run()
    {
        $slotModel = new DeliverySlotModel();

        $slots = [
            ['slot_time' => '8:00 AM - 9:00 AM', 'is_active' => 1],
            ['slot_time' => '9:00 AM - 10:00 AM', 'is_active' => 1],
            ['slot_time' => '10:00 AM - 11:00 AM', 'is_active' => 1],
            ['slot_time' => '11:00 AM - 12:00 PM', 'is_active' => 1],
            ['slot_time' => '12:00 PM - 1:00 PM', 'is_active' => 1],
            ['slot_time' => '1:00 PM - 2:00 PM', 'is_active' => 1],
            ['slot_time' => '6:00 PM - 7:00 PM', 'is_active' => 1],
            ['slot_time' => '7:00 PM - 8:00 PM', 'is_active' => 1],
            ['slot_time' => '8:00 PM - 9:00 PM', 'is_active' => 1],
        ];

        foreach ($slots as $slot) {
            // Check if slot already exists
            $existingSlot = $slotModel->where('slot_time', $slot['slot_time'])->first();

            if (!$existingSlot) {
                $slotModel->insert($slot);
                echo "Delivery slot '{$slot['slot_time']}' created successfully.\n";
            } else {
                echo "Delivery slot '{$slot['slot_time']}' already exists.\n";
            }
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\WorkOrderStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WorkOrderStatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $statuses = [
            ['name' => 'STL files check', 'color' => '#f0ad4e', 'lock_work_order' => false], // Novi poslati posao
            ['name' => 'Returned for adjustments', 'color' => '#d9534f', 'lock_work_order' => false], // Vracen na doradu
            ['name' => 'Design phase', 'color' => '#5bc0de', 'lock_work_order' => true], // u fazi dizajna
            ['name' => 'Design check', 'color' => '#5bc0de', 'lock_work_order' => true], // u proveri dizajna
            ['name' => 'Design adjustments', 'color' => '#f0ad4e', 'lock_work_order' => true], // dorada dizajna
            ['name' => 'In production – print', 'color' => '#5bc0de', 'lock_work_order' => true], // u izradi - print
            ['name' => 'In production – milling', 'color' => '#5bc0de', 'lock_work_order' => true], // u izradi – rezanje
            ['name' => 'Quality control', 'color' => '#5cb85c', 'lock_work_order' => true], // kontrola kvaliteta
            ['name' => 'Delivery', 'color' => '#5bc0de', 'lock_work_order' => true], // dostava
            ['name' => 'Delivered', 'color' => '#5cb85c', 'lock_work_order' => true], // dostavljen
        ];

        foreach ($statuses as $status) {
            WorkOrderStatus::create($status);
        }
    }
}

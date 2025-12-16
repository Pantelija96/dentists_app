<?php

namespace Database\Seeders;

use App\Models\DeliveryOption;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DeliveryOptionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $options = [
            ['name' => 'Pick-up', 'deleted' => false],
            ['name' => 'Courier', 'deleted' => false],
            ['name' => 'Postal Service', 'deleted' => false],
        ];

        foreach ($options as $option) {
            DeliveryOption::create($option);
        }
    }
}

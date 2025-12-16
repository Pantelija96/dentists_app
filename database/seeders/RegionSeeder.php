<?php

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $options = [
            ['name' => 'Region 1', 'deleted' => false],
            ['name' => 'Region 2', 'deleted' => false],
            ['name' => 'Region 3', 'deleted' => false],
        ];

        foreach ($options as $option) {
            Region::create($option);
        }
    }
}

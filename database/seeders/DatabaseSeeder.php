<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run()
    {
        $this->call([
            RegionSeeder::class,
            UsersTableSeeder::class,
            WorkTypesTableSeeder::class,
            MaterialsTableSeeder::class,
            ParametersTableSeeder::class,
            DeliveryOptionsTableSeeder::class,
            WorkOrderStatusesTableSeeder::class,
            MaterialWorkTypeTableSeeder::class,
            ParameterWorkTypeTableSeeder::class,
            MaterialParameterRulesTableSeeder::class,
        ]);
    }
}

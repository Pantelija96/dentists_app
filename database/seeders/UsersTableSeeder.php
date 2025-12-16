<?php

namespace Database\Seeders;

use App\Models\Region;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'first_name'  => 'Super',
            'last_name'   => 'Admin',
            'email'       => 'superadmin@adn.com',
            'password'    => Hash::make('password'),
            'role'        => 3, // super admin
            'type'        => 1, // person
            'region_id'   => null,
            'is_approved' => true,
        ]);

        $regions = Region::all();

        foreach ($regions as $region) {

            $regionKey = strtolower(str_replace(' ', '', $region->name));
            // "Region 1" → "region1"

            /*
            |--------------------------------------------------------------------------
            | Admin for region
            |--------------------------------------------------------------------------
            */
            User::create([
                'first_name'  => 'Admin',
                'last_name'   => strtoupper($region->name),
                'email'       => "admin@{$regionKey}.com",
                'password'    => Hash::make('password'),
                'role'        => 1, // admin
                'type'        => 1, // person
                'region_id'   => $region->id,
                'is_approved' => true,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Person users
            |--------------------------------------------------------------------------
            */
            User::create([
                'first_name'  => 'John',
                'last_name'   => 'Doe',
                'email'       => "user1@{$regionKey}.com",
                'password'    => Hash::make('password'),
                'role'        => 2, // user
                'type'        => 1, // person
                'region_id'   => $region->id,
                'is_approved' => true,
            ]);

            User::create([
                'first_name'  => 'Jane',
                'last_name'   => 'Doe',
                'email'       => "user2@{$regionKey}.com",
                'password'    => Hash::make('password'),
                'role'        => 2,
                'type'        => 1,
                'region_id'   => $region->id,
                'is_approved' => true, // pending approval
            ]);

            /*
            |--------------------------------------------------------------------------
            | Legal entity (company) users
            |--------------------------------------------------------------------------
            */
            User::create([
                'first_name'  => 'Dental',
                'last_name'   => 'Lab',
                'email'       => "company1@{$regionKey}.com",
                'password'    => Hash::make('password'),
                'role'        => 2, // user
                'type'        => 2, // legal entity
                'region_id'   => $region->id,
                'is_approved' => true,
            ]);

            User::create([
                'first_name'  => 'Smile',
                'last_name'   => 'Studio',
                'email'       => "company2@{$regionKey}.com",
                'password'    => Hash::make('password'),
                'role'        => 2,
                'type'        => 2,
                'region_id'   => $region->id,
                'is_approved' => true,
            ]);
        }
    }
}

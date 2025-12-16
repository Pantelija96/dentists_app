<?php

namespace Database\Seeders;

use App\Models\Parameter;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ParametersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $parameters = [
            // Basic options
            ['name' => 'Color', 'field_type' => 'select', 'options' => json_encode(['A1','A2','A3','B1','B2','C1','C2','C3']), 'deleted' => false],
            ['name' => 'Pre-op', 'field_type' => 'boolean', 'options' => null, 'deleted' => false],
            ['name' => 'Tooth Shape Selection', 'field_type' => 'string', 'options' => null, 'deleted' => false],
            ['name' => 'Tooth Cap Shape Selection', 'field_type' => 'string', 'options' => null, 'deleted' => false],

            // Implant / Abutment options
            ['name' => 'Custom Abutment', 'field_type' => 'boolean', 'options' => null, 'deleted' => false],
            ['name' => 'Multi Unit', 'field_type' => 'boolean', 'options' => null, 'deleted' => false],
            ['name' => 'Implant Manufacturer', 'field_type' => 'string', 'options' => null, 'deleted' => false],
            ['name' => 'Scan Body / Ti Base', 'field_type' => 'boolean', 'options' => null, 'deleted' => false],

            // Gingiva scan
            ['name' => 'Gingiva Scan', 'field_type' => 'boolean', 'options' => null, 'deleted' => false],

            // Work parameters
            ['name' => 'Thickness in mm', 'field_type' => 'number', 'options' => null, 'deleted' => false],
//            ['name' => 'Phase of Work', 'field_type' => 'string', 'options' => null, 'deleted' => false],

            // Framework / class options
            ['name' => 'Class', 'field_type' => 'select', 'options' => json_encode(['Class I','Class II','Class III','Class IV']), 'deleted' => false],
            ['name' => 'Phase of Work', 'field_type' => 'select', 'options' => json_encode(['Phase I','Phase II','Phase III','Phase IV']), 'deleted' => false],
            ['name' => 'Cross/Timble', 'field_type' => 'select', 'options' => json_encode(['Cross','Timble']), 'deleted' => false],
        ];

        foreach ($parameters as $param) {
            Parameter::create($param);
        }
    }
}

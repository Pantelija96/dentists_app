<?php

namespace Database\Seeders;

use App\Models\Material;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MaterialsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $materials = [
            'Monolithic Zirconia',
            'Multilayer Zirconia',
            'PMMA',
            'Composite',
            'Metal',
            '3D Print',
            'Resin for Models',
            'LiSi',
            'Full Zr',
            'BioHPP with Composite Crowns',
            'BioHPP with Zr Crowns',
            'CoCr Base with Composite/Zr Teeth',
//            'Titanium Base with Composite/Zr Teeth',
            'Temporary PMMA',
            'Onyx 3D Print',
            '3D print (Night guard flex)',
            'Zr',
//            'Titanium',
            'CoCr'
        ];

        foreach ($materials as $material) {
            Material::create(['name' => $material, 'deleted' => false]);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Material;
use App\Models\MaterialParameterRule;
use App\Models\Parameter;
use App\Models\WorkType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MaterialParameterRulesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $workTypes = WorkType::with('materials', 'parameters')->get();
        $materials = Material::all();
        $parameters = Parameter::all();

        // Mapping work type => material => allowed parameters
        $rules = [
            'Anatomic Crown' => [
                'Monolithic Zirconia' => ['Color','Pre-op','Tooth Shape Selection'],
                'Multilayer Zirconia' => ['Color','Pre-op','Tooth Shape Selection'],
                'PMMA' => ['Color','Pre-op','Tooth Shape Selection'],
                'Composite' => ['Color','Pre-op','Tooth Shape Selection'],
                'Metal' => ['Color','Pre-op','Tooth Shape Selection'],
                '3D Print' => ['Color','Pre-op','Tooth Shape Selection'],
            ],
            'Anatomic Crown on Implant' => [
                'Monolithic Zirconia' => ['Color','Pre-op','Tooth Shape Selection','Custom Abutment','Multi Unit','Implant Manufacturer','Scan Body / Ti Base'],
                'Multilayer Zirconia' => ['Color','Pre-op','Tooth Shape Selection','Custom Abutment','Multi Unit','Implant Manufacturer','Scan Body / Ti Base'],
                'PMMA' => ['Color','Pre-op','Tooth Shape Selection','Custom Abutment','Multi Unit','Implant Manufacturer','Scan Body / Ti Base'],
                'Composite' => ['Color','Pre-op','Tooth Shape Selection','Custom Abutment','Multi Unit','Implant Manufacturer','Scan Body / Ti Base'],
                'Metal' => ['Color','Pre-op','Tooth Shape Selection','Custom Abutment','Multi Unit','Implant Manufacturer','Scan Body / Ti Base'],
                '3D Print' => ['Color','Pre-op','Tooth Shape Selection','Custom Abutment','Multi Unit','Implant Manufacturer','Scan Body / Ti Base'],
            ],
            'Caps' => [
                'Monolithic Zirconia' => ['Color','Tooth Cap Shape Selection'],
                'Multilayer Zirconia' => ['Color','Tooth Cap Shape Selection'],
                'Metal' => ['Tooth Shape Selection'],
            ],
            'Caps on Implant' => [
                'Monolithic Zirconia' => ['Tooth Cap Shape Selection','Custom Abutment','Multi Unit','Implant Manufacturer','Scan Body / Ti Base'],
                'Multilayer Zirconia' => ['Tooth Cap Shape Selection','Custom Abutment','Multi Unit','Implant Manufacturer','Scan Body / Ti Base'],
                'Metal' => ['Tooth Cap Shape Selection','Custom Abutment','Multi Unit','Implant Manufacturer','Scan Body / Ti Base'],
            ],
            'Anatomic Bridge' => [
                'Monolithic Zirconia' => ['Color','Pre-op','Tooth Shape Selection','Gingiva Scan'],
                'Multilayer Zirconia' => ['Color','Pre-op','Tooth Shape Selection','Gingiva Scan'],
                'PMMA' => ['Color','Pre-op','Tooth Shape Selection','Gingiva Scan'],
                'Composite' => ['Color','Pre-op','Tooth Shape Selection','Gingiva Scan'],
                'Metal' => ['Color','Pre-op','Tooth Shape Selection','Gingiva Scan'],
                '3D Print' => ['Color','Pre-op','Tooth Shape Selection','Gingiva Scan'],
            ],
            'Reduced Bridge' => [
                'Monolithic Zirconia' => ['Color','Tooth Shape Selection','Gingiva Scan'],
                'Multilayer Zirconia' => ['Color','Tooth Shape Selection','Gingiva Scan'],
                'Metal' => ['Color','Tooth Shape Selection','Gingiva Scan'],
            ],
            'Wax-up for Models' => [
                'Resin for Models' => ['Pre-op','Tooth Shape Selection','Color'],
            ],
            'Veneer / Onlay / Inlay' => [
                'LiSi' => ['Color','Tooth Shape Selection'],
                '3D Print' => ['Color','Tooth Shape Selection'],
                'Zr' => ['Color','Tooth Shape Selection'],
            ],
            'Hybrid Work' => [
                'Full Zr' => ['Phase of Work'],
                'BioHPP with Composite Crowns' => ['Phase of Work'],
                'BioHPP with Zr Crowns' => ['Phase of Work'],
                'CoCr Base with Composite/Zr Teeth' => ['Phase of Work', 'Cross/Timble'],
//                'Titanium Base with Composite/Zr Teeth' => ['Phase of Work', 'Cross/Timble'],
                'Temporary PMMA' => ['Phase of Work'],
                'Onyx 3D Print' => ['Phase of Work'],
            ],
            'Bite Splint 3D Print' => [
                '3D print (Night guard flex)' => ['Thickness in mm'],
            ],
            'Skelet' => [
//                'Titanium' => ['Class'],
                'CoCr' => ['Class'],
            ]
        ];

        foreach ($workTypes as $wt) {
            foreach ($wt->materials as $mat) {
                foreach ($parameters as $param) {
                    $allowed = isset($rules[$wt->name][$mat->name]) && in_array($param->name, $rules[$wt->name][$mat->name]);
                    MaterialParameterRule::create([
                        'work_type_id' => $wt->id,
                        'material_id' => $mat->id,
                        'parameter_id' => $param->id,
                        'allowed' => $allowed,
                    ]);
                }
            }
        }
    }
}

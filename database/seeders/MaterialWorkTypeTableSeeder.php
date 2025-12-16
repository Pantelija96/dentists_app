<?php

namespace Database\Seeders;

use App\Models\Material;
use App\Models\WorkType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MaterialWorkTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $workTypes = WorkType::all();
        $materials = Material::all();

        // Example mapping: you can refine per Option C
        foreach($workTypes as $wt){
            switch($wt->name){
                case 'Anatomic Crown':
                    $allowed = ['Monolithic Zirconia','Multilayer Zirconia','PMMA','Composite','Metal','3D Print'];
                    break;
                case 'Anatomic Crown on Implant':
                    $allowed = ['Monolithic Zirconia','Multilayer Zirconia','PMMA','Composite','Metal','3D Print'];
                    break;
                case 'Caps':
                    $allowed = ['Monolithic Zirconia','Multilayer Zirconia','Metal'];
                    break;
                case 'Caps on Implant':
                    $allowed = ['Monolithic Zirconia','Multilayer Zirconia','Metal'];
                    break;
                case 'Anatomic Bridge':
                    $allowed = ['Monolithic Zirconia','Multilayer Zirconia','PMMA','Composite','Metal','3D Print'];
                    break;
                case 'Reduced Bridge':
                    $allowed = ['Monolithic Zirconia','Multilayer Zirconia','Metal'];
                    break;
                case 'Wax-up for Models':
                    $allowed = ['Resin for Models'];
                    break;
                case 'Veneer / Onlay / Inlay':
                    $allowed = ['LiSi','3D Print','Zr'];
                    break;
                case 'Hybrid Work':
                    $allowed = ['Full Zr','BioHPP with Composite Crowns','BioHPP with Zr Crowns','CoCr Base with Composite/Zr Teeth','Titanium Base with Composite/Zr Teeth','Temporary PMMA','Onyx 3D Print'];
                    break;
                case 'Bite Splint 3D Print':
                    $allowed = ['3D print (Night guard flex)'];
                    break;
                case 'Skelet':
                    $allowed = ['Titanium','CoCr'];
                    break;
                default:
                    $allowed = [];
            }

            foreach($materials->whereIn('name',$allowed) as $mat){
                $wt->materials()->attach($mat->id, ['additional_price'=>0]);
            }
        }
    }
}

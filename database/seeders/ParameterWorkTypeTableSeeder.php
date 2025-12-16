<?php

namespace Database\Seeders;

use App\Models\Parameter;
use App\Models\WorkType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ParameterWorkTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $workTypes = WorkType::all();
        $parameters = Parameter::all();

        foreach($workTypes as $wt){
            $requiredParams = [];
            $optionalParams = [];

            switch($wt->name){
                case 'Anatomic Crown':
                    $requiredParams = ['Color','Pre-op','Tooth Shape Selection'];
                    break;
                case 'Anatomic Crown on Implant':
                    $requiredParams = ['Color','Pre-op','Tooth Shape Selection'];
                    break;
                case 'Caps':
                    $requiredParams = ['Color','Tooth Shape Selection'];
                    break;
                case 'Caps on Implant':
                    $requiredParams = ['Tooth Shape Selection','Custom Abutment','Multi Unit','Implant Manufacturer','Scan Body / Ti Base'];
                    break;
                case 'Anatomic Bridge':
                    $requiredParams = ['Color','Pre-op','Tooth Shape Selection','Gingiva Scan'];
                    break;
                case 'Reduced Bridge':
                    $requiredParams = ['Color','Tooth Shape Selection','Gingiva Scan'];
                    break;
                case 'Wax-up for Models':
                    $requiredParams = ['Pre-op','Tooth Shape Selection','Color'];
                    break;
                case 'Veneer / Onlay / Inlay':
                    $requiredParams = ['Color','Tooth Shape Selection'];
                    break;
                case 'Hybrid Work':
                    $requiredParams = ['Phase of Work'];
                    break;
                case 'Bite Splint 3D Print':
                    $requiredParams = ['Thickness in mm'];
                    break;
                case 'Framework Titanium/CoCr':
                    $requiredParams = ['Phase of Work','Framework Class'];
                    break;
            }

            foreach($parameters as $param){
                $wt->parameters()->attach($param->id, ['required'=>in_array($param->name,$requiredParams)]);
            }
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\WorkType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WorkTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $workTypes = [
            ['name'=>'Anatomic Crown', 'base_price'=>0, 'deleted'=>false],
            ['name'=>'Anatomic Crown on Implant', 'base_price'=>0, 'deleted'=>false],
            ['name'=>'Caps', 'base_price'=>0, 'deleted'=>false],
            ['name'=>'Caps on Implant', 'base_price'=>0, 'deleted'=>false],
            ['name'=>'Anatomic Bridge', 'base_price'=>0, 'deleted'=>false],
            ['name'=>'Reduced Bridge', 'base_price'=>0, 'deleted'=>false],
            ['name'=>'Wax-up for Models', 'base_price'=>0, 'deleted'=>false],
            ['name'=>'Veneer / Onlay / Inlay', 'base_price'=>0, 'deleted'=>false],
            ['name'=>'Hybrid Work', 'base_price'=>0, 'deleted'=>false],
            ['name'=>'Bite Splint 3D Print', 'base_price'=>0, 'deleted'=>false],
            ['name'=>'Skelet', 'base_price'=>0, 'deleted'=>false],
            ['name'=>'Attachment / Telescopic Primary', 'base_price'=>0, 'deleted'=>false],
        ];

        foreach($workTypes as $wt){
            WorkType::create($wt);
        }
    }
}

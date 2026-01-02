<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\MaterialParameterRule;
use App\Models\WorkType;
use Illuminate\Http\Request;

class WorkTypeController extends Controller
{
    private $data = [];

    public function materials(WorkType $workType)
    {
        return response()->json(
            $workType->materials()->get()
        );
    }

    public function parameters(WorkType $workType, Material $material)
    {
        $parameters = $workType->parameters()->get();

        $allowedParameters = $parameters->filter(function($param) use ($workType, $material) {
            $rule = MaterialParameterRule::where([
                'work_type_id' => $workType->id,
                'material_id' => $material->id,
                'parameter_id' => $param->id
            ])->first();

            return !$rule || $rule->allowed;
        })->values();

        return response()->json($allowedParameters);
    }
}

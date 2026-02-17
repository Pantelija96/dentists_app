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

        $allowedParameters->transform(function($param) {

            $translation = __('app.' . $param->name);
            $param->translated_name = $translation !== 'app.' . $param->name
                ? $translation
                : $param->name;

            if ($param->field_type === 'select' && $param->options) {
                $options = json_decode($param->options, true);
                $param->translated_options = collect($options)->map(function ($option) {
                    $translated = __('app.' . $option);
                    return [
                        'value' => $option,
                        'label' => $translated !== 'app.' . $option ? $translated : $option
                    ];
                })->values();
            }

            return $param;
        });

        return response()->json($allowedParameters);
    }
}

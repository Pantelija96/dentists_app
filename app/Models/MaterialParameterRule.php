<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialParameterRule extends Model
{
    use HasFactory;

    protected $table = 'material_parameter_rules';
    protected $fillable = ['work_type_id','material_id','parameter_id','allowed'];
    protected $casts = ['allowed'=>'boolean'];

    public function workType()
    {
        return $this->belongsTo(WorkType::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }

    public function parameter()
    {
        return $this->belongsTo(Parameter::class);
    }
}
